<?php
/**
 * Widget Post Type Management for Mobile App
 *
 * This file defines the `Widget` class, which handles the management of the "Widget" custom post type
 * within the WordPress admin interface. It includes methods for editing, saving, displaying, and managing
 * various widget-related functionalities, such as widget types, positions, and meta data.
 *
 * @package YD\Mobile_App
 * @subpackage Post
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Post;

use YD\Admin\Page;
use YD\Mobile_App\Admin\Page\Block;
use YD\Admin\Page\View;
use YD\Mobile_App\Admin\Action;
use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Widget class handles the management of the custom post type "Widget" in the WordPress admin.
 *
 * This class provides methods for editing, saving, displaying, and handling other administrative actions
 * related to widgets. It includes functionality for defining widget types, rendering widget forms,
 * managing widget positions, and saving widget data.
 */
final class Widget extends \YD\Post {

	/**
	 * Get the slug of the widget post type.
	 *
	 * @return string The slug for the widget post type.
	 */
	protected function get_slug(): string {
		return 'widget';
	}

	/**
	 * Get the singular label for the widget post type.
	 *
	 * @return string The singular label for the widget post type.
	 */
	protected function get_label_singular(): string {
		return 'Widget';
	}

	/**
	 * Get the plural label for the widget post type.
	 *
	 * @return string The plural label for the widget post type.
	 */
	protected function get_label_plural(): string {
		return 'Widgets';
	}

	/**
	 * Get the text domain for the widget post type.
	 *
	 * @return string The text domain for translation.
	 */
	protected function get_text_domain(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Get the arguments for the widget post type.
	 *
	 * @return array The arguments for registering the post type.
	 */
	protected function get_args(): array {
		return array(
			'public'             => true,
			'publicly_queryable' => false,
			'has_archive'        => false,
			'show_in_menu'       => false,
			'show_in_rest'       => false,
			'rewrite'            => false,
			'map_meta_cap'       => true,
			'capability_type'    => parent::get_type(),
			'supports'           => array( 'title' ),
		);
	}

	/**
	 * Callback function to render the widget edit form.
	 *
	 * @param \WP_Post $post The current post object.
	 */
	protected function callback_edit( \WP_Post $post ) {
		Page::enqueue_script( 'ui-input/selection-action.js' );
		wp_dequeue_script( 'autosave' );

		$widget = \YD\Mobile_App\Widget::get_by_id( $post->ID );

		if ( null === $widget ) {
			?>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Type' ); ?></th>
					<td>
						<?php
							$view = new View\Dropdown(
								'widget_type',
								array(
									\YD\Mobile_App\Widget::TYPE_SLIDER => __( 'Slider', 'yd-mobile-app' ),
									\YD\Mobile_App\Widget::TYPE_GRID => __( 'Grid', 'yd-mobile-app' ),
									\YD\Mobile_App\Widget::TYPE_LABEL => __( 'Label', 'yd-mobile-app' ),
								) + (
									Utils\WC::is_support()
									? array( \YD\Mobile_App\Widget::TYPE_PRODUCTS_HORIZONTAL => __( 'Products Horizontal', 'yd-mobile-app' ) )
									: array()
								)
							);
							$view->set_id( 'widget_type' );
							$view->set_help_text( __( 'Ensures define type of widget. This option does not change later.', 'yd-mobile-app' ) );
							$view->set_description( __( 'Select type of widget.', 'yd-mobile-app' ) );
							$view->set_ignored( true );
							$view->render();
						?>
					</td>
				</tr>
				</table>
			<?php
			return;
		}
		?>
<table class="form-table">
	<tr>
		<th><?php esc_html_e( 'Class', 'yd-mobile-app' ); ?></th>
		<td>
			<?php
				$view = new View\Text( 'widget_class' );
				$view->set_value( $widget->get_class() );
				$view->set_required( false );
				$view->set_id( 'class' );
				$view->set_help_text( esc_html__( 'This field can fill for custom style in mobile app otherwise leave empty this field.', 'yd-mobile-app' ) );
				$view->set_description( esc_html__( 'Ensures appear custom style in mobile app.', 'yd-mobile-app' ) );
				$view->set_custom_attributes(
					array(
						'autocomplete' => 'off',
						'maxlength'    => \YD\Mobile_App\Widget::CLASS_MAX_LENGTH,
					)
				);
				$view->render();
			?>
		</td>
	</tr>
</table>
		<?php
		switch ( $widget->get_type() ) {
			case \YD\Mobile_App\Widget::TYPE_SLIDER:
				new Block\Widget_Slider( $widget );
				break;
			case \YD\Mobile_App\Widget::TYPE_GRID:
				new Block\Widget_Grid( $widget );
				break;
			case \YD\Mobile_App\Widget::TYPE_LABEL:
				new Block\Widget_Label( $widget );
				break;
			case \YD\Mobile_App\Widget::TYPE_PRODUCTS_HORIZONTAL:
				Page::enqueue_script( 'edit-widget/product-horizontal.js' );
				new Block\Widget_Products_Horizontal( $widget );
				break;
		}

		Page::enqueue_style( 'edit-widget.css' );

		if ( $widget->is_sortable() ) {
			Page::enqueue_script( 'edit-widget/sortable.js' );
			// translators: %s is the singular name of the widget item to be added (e.g., "block", "section").
			$action_new_title = sprintf( __( 'Add new %s', 'yd-mobile-app' ), $widget->get_item_name() );
			?>
			<span id="action-new" tabindex="0" class="button" style="margin-right: 4px;"><?php echo( esc_html( $action_new_title ) ); ?></span>
			<span id="action-clear" class="button delete" style="float: right; display:none;" tabindex="0"><?php esc_html_e( 'Clear' ); ?></span>
			<hr style="margin-top: 16px;"/>
			<?php
		}
	}

	/**
	 * Define meta boxes for the widget post type.
	 *
	 * @param \WP_Post $post The current post object.
	 * @return array An array of meta boxes to display.
	 */
	protected function get_meta_boxes( \WP_Post $post ): array {
		$meta_boxes = array();
		$widget     = \YD\Mobile_App\Widget::get_by_id( $post->ID );

		$meta_box_position = array(
			'id'       => 'position',
			'title'    => __( 'Position' ),
			'callback' => function ( \WP_Post $post ) {
				?>
				<input type="number" min="0" name="widget_position" value="<?php echo( esc_html( get_post_meta( $post->ID, Utils\Main::meta_key( 'position' ), true ) ) ); ?>" style="width: 100%; margin-top: 4px; text-align: center;"/><?php
			},
			'context'  => 'side',
		);

		if ( null !== $widget ) {
			array_push( $meta_boxes, $meta_box_position );
		}

		return $meta_boxes;
	}

	/**
	 * Callback function triggered after deleting a widget.
	 *
	 * @param int $post_id The post ID of the deleted widget.
	 */
	protected function after_delete( int $post_id ) {
		delete_post_meta( $post_id, Utils\Main::meta_key( 'position' ) );

		$query = new \WP_Query(
			array(
				'posts_per_page' => -1,
				'post_type'      => 'yd_widget',
				// phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_type'      => 'NUMERIC',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
			)
		);

		$fixed_position = 0;
		foreach ( $query->posts as $post ) {
			update_post_meta( $post->ID, Utils\Main::meta_key( 'position' ), $fixed_position );
			++$fixed_position;
		}
	}

	/**
	 * Save data for the widget post.
	 *
	 * @param array $data The data to save.
	 * @param array $post_data The raw post data.
	 * @param bool  $is_new Indicates if the widget is being created or updated.
	 * @return array The processed data to save.
	 */
	protected function save_data( array $data, array $post_data, bool $is_new ): array {
		$action = $is_new ? Action\Create_Widget::class : Action\Edit_Widget::class;
		return $action::get_data( $data, $post_data );
	}

	/**
	 * Save meta data for the widget post.
	 *
	 * @param array    $data The data to save.
	 * @param \WP_Post $post The post object to save the data for.
	 * @param bool     $is_new Whether the widget is new or being edited.
	 * @return void
	 */
	protected function save_meta_data( array $data, \WP_Post $post, bool $is_new ) {
		$action = $is_new ? Action\Create_Widget::class : Action\Edit_Widget::class;
		$action::save_meta_data( $data, $post );
	}

	/**
	 * Get rules for the widget.
	 *
	 * @return array The widget rules.
	 */
	private function get_rules_by_widget(): array {
		$widget = \YD\Mobile_App\Widget::get_by_id( get_the_ID() );

		if ( $widget ) {
			return $widget->get_rules();
		}
		return array();
	}

	/**
	 * Modify the query for retrieving widgets based on position.
	 *
	 * @param \WP_Query $query The query object to modify.
	 * @return \WP_Query The modified query.
	 */
	protected function pre_get_posts( \WP_Query $query ): \WP_Query {
		// phpcs:ignore WordPress.DB.SlowDBQuery
		$query->query_vars['meta_key']  = Utils\Main::meta_key( 'position' );
		$query->query_vars['meta_type'] = 'NUMERIC';
		$query->query_vars['orderby']   = 'meta_value_num';
		$query->query_vars['order']     = 'ASC';

		return $query;
	}

	/**
	 * Manage the columns displayed for the widget post type.
	 *
	 * @param string $column_name The name of the column.
	 * @param int    $post_id The ID of the post.
	 */
	protected function manage_column( string $column_name, int $post_id ) {
		$widget = \YD\Mobile_App\Widget::get_by_id( $post_id );
		switch ( $column_name ) {
			case 'widget-type':
				echo ( esc_html( $widget->get_type() ) );
				break;
		}
	}

	/**
	 * Get the column titles for the widget post type.
	 *
	 * @param array $columns The existing columns.
	 * @return array The modified columns.
	 */
	protected function get_column_title( array $columns ): array {
		unset( $columns['date'] );
		return $columns + array(
			'widget-type' => __( 'Widget Type', 'yd-mobile-app' ),
			'date'        => __( 'Date' ),
		);
	}

	/**
	 * Get the validation rules for the widget data.
	 *
	 * @return array The rules for widget data.
	 */
	protected function get_rules(): array {
		return array(
			'post_ID'         => array(
				'type'              => 'integer',
				'required'          => true,
				'default'           => 0,
				'sanitize_callback' => function ( $value ) {
					if ( get_post( $value ) ) {
						return $value;
					} else {
						throw new \Exception( 'Something wrong when update post. Please check Post ID.' );
					}
				},
			),
			'post_title'      => array(
				'type'            => 'string',
				'required'        => true,
				'default'         => 'Widget',
				'sanitize_length' => \YD\Mobile_App\Widget::TITLE_MAX_LENGTH,
				'pattern_match'   => '/\S/',
			),
			'widget_type'     => array(
				'type'   => 'enum',
				'values' => array(
					\YD\Mobile_App\Widget::TYPE_SLIDER,
					\YD\Mobile_App\Widget::TYPE_GRID,
					\YD\Mobile_App\Widget::TYPE_LABEL,
					\YD\Mobile_App\Widget::TYPE_PRODUCTS_HORIZONTAL,
				),
			),
			'widget_position' => array(
				'type'              => 'integer',
				'required'          => true,
				'default'           => 0,
				'sanitize_callback' => function ( $value ) {
					$value = (int) $value;

					$posts_length = ( new \WP_Query(
						array( 'post_type' => 'yd_widget' )
					) )->found_posts;

					if ( $value > $posts_length ) {
						return $posts_length - 1;
					}
					if ( $value < 0 ) {
						return 0;
					}

					return $value;
				},
			),
			'widget_class'    => array(
				'type'                  => 'string',
				'sanitize_length'       => \YD\Mobile_App\Widget::CLASS_MAX_LENGTH,
				'sanitize_raw_callback' => function ( string $data ) {
					return strtolower( $data );
				},
				'pattern_replace'       => array(
					array(
						'pattern'     => '/[^a-z0-9\-\_\,]/',
						'replacement' => '',
					),
					array(
						'pattern'     => '/\,\,/',
						'replacement' => '',
					),
				),
			),
			'widget_data'     => array(
				'pass'    => true,
				'default' => array(),
			),
		);
	}
}

?>