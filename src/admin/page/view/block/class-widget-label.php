<?php
/**
 * Admin Widget: Label Block
 *
 * This file defines the `Widget_Label` class, which renders the settings interface
 * for the label widget in the admin dashboard. It includes configuration for the "More" button
 * and supports sortable label items with customizable actions and labels.
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
 * Widget_Label class defines a block that renders the settings for the label widget
 * in the WordPress admin dashboard, allowing customization of the "More" button
 * and label items like the label text and action associated with each item.
 */
final class Widget_Label extends \YD\Mobile_App\Admin\Page\View\Widget_Sortable {

	/**
	 * Constructor for the Widget_Label class.
	 *
	 * Initializes the block and renders the "More" button configuration settings.
	 *
	 * @param Widget\Label $widget The widget object containing the configuration data.
	 */
	public function __construct( Widget\Label $widget ) {
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
	 * Returns the DOM structure for rendering a sortable label item.
	 *
	 * The label item includes fields for setting the label text and action associated
	 * with the label. It also provides an option to remove the item from the list.
	 *
	 * @return void
	 */
	protected function get_item_dom() {
		?>
		<div class="postbox-container meta-box-sortables">
			<div class="stuffbox postbox">
				<div class="postbox-header">
					<h2><?php esc_html_e( 'Label Item', 'yd-mobile-app' ); ?></h2>
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
								<td class="first"><label><?php esc_html_e( 'Label', 'yd-mobile-app' ); ?></label></td>
								<td>
									<?php
										$view = new View\Text( 'widget_data[items]{SORTABLE_INDEX}[label]' );
										$view->set_value( '{SORTABLE_DATA-label}' );
										$view->set_help_text( __( 'Ensures define label on widget.', 'yd-mobile-app' ) );
										$view->set_description( __( 'Appears label on the widget.', 'yd-mobile-app' ) );
										$view->set_custom_attributes(
											array(
												'autocomplete' => 'off',
												'maxlength'    => Widget\Label::LABEL_MAX_LENGTH,
											)
										);
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
