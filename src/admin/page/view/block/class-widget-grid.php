<?php
/**
 * Admin Widget: Grid Block
 *
 * This file defines the `Widget_Grid` class, which renders the settings interface
 * for the grid widget in the admin dashboard. It includes configuration for the "More" button
 * and supports sortable grid items with customizable titles, medias, and actions.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page\Block
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page\Block;

use YD\Admin\Page\View;
use YD\Mobile_App\Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Widget_Grid class defines a block that renders the settings for the grid widget
 * in the WordPress admin dashboard, allowing customization of the "More" button
 * and grid items like titles, medias, and actions.
 */
final class Widget_Grid extends \YD\Mobile_App\Admin\Page\View\Widget_Sortable {

	/**
	 * Constructor for the Widget_Grid class.
	 *
	 * Initializes the block and renders the "More" button configuration settings.
	 *
	 * @param Widget\Grid $widget The widget object containing the configuration data.
	 */
	public function __construct( Widget\Grid $widget ) {
		?>

		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Action of "More" Button', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Selection_Action( 'widget_data[more_button]' );
							$view->set_value( $widget->get_more_button()['action'] ?? null );
							$view->render();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		parent::__construct( $widget );
	}

	/**
	 * Returns the DOM structure for rendering a sortable grid item.
	 *
	 * The grid item includes fields for setting the title, media, and action for each item in the grid.
	 * It also provides an option to remove the item from the grid.
	 *
	 * @return void
	 */
	protected function get_item_dom() {
		?>
		<div class="postbox-container meta-box-sortables">
			<div class="stuffbox postbox">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Grid item', 'yd-mobile-app' ); ?></h2>
					<div class="handle-actions">
						<button type="button" class="handle-order-higher">
							<span class="order-higher-indicator"></span>
						</button>
						<button type="button" class="handle-order-lower">
							<span class="order-lower-indicator"></span>
						</button>
					</div>
				</div>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Title' ); ?></label></td>
								<td>
									<?php
										$view = new View\Text( 'widget_data[items]{SORTABLE_INDEX}[title]' );
										$view->set_value( '{SORTABLE_DATA-title}' );
										$view->set_required( false );
										$view->set_help_text( __( 'Ensures define title on media. If desired to be empty title, title hides.', 'yd-mobile-app' ) );
										$view->set_description( __( 'Appears title on the media.', 'yd-mobile-app' ) );
										$view->set_custom_attributes(
											array(
												'autocomplete' => 'off',
												'maxlength'    => Widget\Grid::ITEM_TITLE_MAX_LENGTH,
											)
										);
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Media', 'yd-mobile-app' ); ?></label></td>
								<td>
									<?php
										$view = new View\Selection_Media( 'widget_data[items]{SORTABLE_INDEX}[media_id]' );
										$view->set_value( '{SORTABLE_DATA-media_id}' );
										$view->set_help_text( __( 'Ensures select media for grid item. Only one media can select.', 'yd-mobile-app' ) );
										$view->set_description( __( 'Appears selected media on grid item.', 'yd-mobile-app' ) );
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Action' ); ?></label></td>
								<td>
									<?php
										$view = new View\Selection_Action( 'widget_data[items]{SORTABLE_INDEX}' );
										$view->set_value( '{SORTABLE_DATA-action}' );
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span data-action="remove" tabindex="0" class="delete"><?php esc_html_e( 'Remove' ); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
