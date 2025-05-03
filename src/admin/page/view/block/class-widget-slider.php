<?php
/**
 * Admin Widget: Slider Block
 *
 * This file defines the `Widget_Slider` class used in the admin panel to manage a sortable
 * slider widget. Each slide can include an image and an associated action. It also supports
 * configurable aspect ratios to control the image layout.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page\Block
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page\Block;

use YD\Admin\Page\View;
use YD\Admin\Page;
use YD\Mobile_App\Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Widget_Slider class provides the functionality to create a sortable slider widget
 * with configurable image aspect ratio and slide items.
 */
final class Widget_Slider extends \YD\Mobile_App\Admin\Page\View\Widget_Sortable {

	/**
	 * Constructor for Widget_Slider.
	 *
	 * Initializes the slider widget settings form with an input for aspect ratio
	 * and other necessary fields. It also calls the parent constructor to render
	 * the sortable items functionality.
	 *
	 * @param Widget\Slider $widget The slider widget object containing the configuration data.
	 */
	public function __construct( Widget\Slider $widget ) {
		Page::enqueue_script( 'edit-widget/slider.js' );
		?>

		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Aspect ratio of slider', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Text( 'widget_data[aspect_ratio]' );
							$view->set_value( $widget->get_aspect_ratio() );
							$view->set_required( false );
							$view->set_id( 'aspect_ratio' );
							$view->set_help_text( __( 'If desired change orientation of slider, aspect ratio should change. Default value is:', 'yd-mobile-app' ) . ' ' . Widget\Slider::DEFAULT_ASPECT_RATIO );
							$view->set_description( __( 'Ensures change aspect ratio of slider.', 'yd-mobile-app' ) );
							$view->set_custom_attributes( array( 'pattern' => '^\d+:\d+$' ) );
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
	 * Renders the DOM for a single slide item.
	 *
	 * This includes fields for selecting an image, defining an action, and removing the slide.
	 * Each slide is rendered as a sortable item.
	 *
	 * @return void
	 */
	protected function get_item_dom() {
		?>
		<div class="postbox-container meta-box-sortables">
			<div class="stuffbox postbox">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Slide', 'yd-mobile-app' ); ?></h2>
					<div class="handle-actions">
						<button type="button" class="handle-order-higher">
							<span class="order-higher-indicator"></span>
						</button>
						<button type="button" class="handle-order-lower">
							<span class="order-lower-indicator"></span>
						</button>
					</div>
				</div>
				<div class="inside yd-widget-slider-slide">
					<table class="form-table">
						<tbody>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Media', 'yd-mobile-app' ); ?></label></td>
								<td>
									<?php
										$view = new View\Selection_Media( 'widget_data[items]{SORTABLE_INDEX}[media_id]' );
										$view->set_value( '{SORTABLE_DATA-media_id}' );
										$view->set_help_text( __( 'Ensures select media for slide banner. Only one media can select.', 'yd-mobile-app' ) );
										$view->set_description( __( 'Appears selected media on slide banner.', 'yd-mobile-app' ) );
										$view->set_required( false );
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Media foreground color', 'yd-mobile-app' ); ?></label></td>
								<td>
									<?php
										$view = new View\Color_Picker( 'widget_data[items]{SORTABLE_INDEX}[media_fg_color]' );
										$view->set_id( 'fg_color' );
										$view->set_value( '{SORTABLE_DATA-media_fg_color}' );
										$view->set_help_text( __( 'Ensures change foreground color for media.', 'yd-mobile-app' ) );
										$view->set_description( __( 'Appears solid color on front of media.', 'yd-mobile-app' ) );
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Media action', 'yd-mobile-app' ); ?></label></td>
								<td>
									<?php
										$view = new View\Selection_Action( 'widget_data[items]{SORTABLE_INDEX}' );
										$view->set_value( '{SORTABLE_DATA-action}' );
										$view->render();
									?>
								</td>
							</tr>
							<tr>
								<td class="first"><label><?php esc_html_e( 'Text Views', 'yd-mobile-app' ); ?></label></td>
								<td>
									<span id="view-action-add" class="button" tabindex="0"><?php esc_html_e( 'Add text', 'yd-mobile-app' ); ?></span>
									<span id="view-action-clear" class="button delete" tabindex="0"><?php esc_html_e( 'Clear texts', 'yd-mobile-app' ); ?></span>
									<div class="text-views" data-value="{SORTABLE_DATA-text_views}"></div>
								</td>
							</tr>
							<tr>
								<td>
									<span data-action="remove" tabindex="0" class="delete"><?php esc_html_e( 'Remove' ); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="slide-preview">
						<h2><b><?php esc_html_e( 'Preview' ); ?></b></h2>
						<div class="slide">
							<div class="container">
								<div class="media-fg-color"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
