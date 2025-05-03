<?php
/**
 * Admin Widget: Horizontal Products Block
 *
 * This file defines the `Widget_Products_Horizontal` class, which is responsible for rendering
 * the settings of the horizontal product widget in the admin interface. It includes options
 * to configure filtering by category or custom product names, sale status, keyword search,
 * and price range.
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
 * Widget_Products_Horizontal class defines the block for rendering the settings
 * related to the horizontal product widget, allowing the customization of product display
 * by category, name, and additional filtering parameters such as sales status and price.
 */
final class Widget_Products_Horizontal {

	/**
	 * Constructor for the Widget_Products_Horizontal class.
	 *
	 * Initializes the block and renders the product-related settings, including the
	 * option to enable custom products and set filters like sale status, price range, and search.
	 *
	 * @param Widget\Products_Horizontal $widget The widget object containing the configuration data.
	 */
	public function __construct( Widget\Products_Horizontal $widget ) {
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Enable custom products', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Checkbox( 'widget_data[is_custom_products]', __( 'Enable custom products', 'yd-mobile-app' ) );
							$view->set_value( $widget->is_custom_products() );
							$view->set_help_text( __( 'If enable this, products are lists by product name otherwise lists products in the category.', 'yd-mobile-app' ) );
							$view->set_id( 'custom_products' );
							$view->set_description( __( 'Ensures enter only product name for below selection.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label for="selection_product_or_category_input"><?php esc_html_e( 'Category or product name', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Selection( 'widget_data[id]', __( 'Enter name', 'yd-mobile-app' ) );
							$view->set_properties(
								array( 'target' => $widget->is_custom_products() ? 'product' : 'product_category' )
							);
							$view->set_id( 'selection_product_or_category' );
							$view->set_required( false );
							$view->set_value( $widget->get_id() );
							$view->set_help_text( __( 'This ensures list products in the category or by product name.', 'yd-mobile-app' ) );
							$view->set_description( __( 'Appears products in the category or by product name.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<br/>
		<h3><?php esc_html_e( 'Custom Parameters', 'yd-mobile-app' ); ?></h3>
		<p><?php esc_html_e( 'The following options affect products filtering.', 'yd-mobile-app' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Filter as sale', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Checkbox( 'widget_data[custom_params][filter_as_sale]', __( 'Filter as sale', 'yd-mobile-app' ) );
							$view->set_value( $widget->get_custom_params()['filter_as_sale'] ?? false );
							$view->set_help_text( __( 'If desired filtering products by on sale or not, this option should be checked.', 'yd-mobile-app' ) );
							$view->set_id( 'filter_as_sale' );
							$view->set_description( __( 'Ensures enable filtering products by on sale or not', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'On sale', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Checkbox( 'widget_data[custom_params][on_sale]', __( 'On sale', 'yd-mobile-app' ) );
							$view->set_value( $widget->get_custom_params()['on_sale'] ?? false );
							$view->set_help_text( __( 'Ensures filter products by on sale or not.', 'yd-mobile-app' ) );
							$view->set_id( 'custom_param_on_sale' );
							$view->set_description( __( 'Filters products by on sale or not.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label for="custom_param_search_input"><?php esc_html_e( 'Search' ); ?></label></th>
					<td>
						<?php
							$view = new View\Text( 'widget_data[custom_params][search]' );
							$view->set_required( false );
							$view->set_value( $widget->get_custom_params()['search'] ?? '' );
							$view->set_help_text( __( 'Ensures filter products by keywords.', 'yd-mobile-app' ) );
							$view->set_id( 'custom_param_search' );
							$view->set_description( __( 'Filters products by keywords: SKU, product name etc.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label for="custom_param_min_price_input"><?php esc_html_e( 'Min price', 'woocommerce' ); ?></label></th>
					<td>
						<?php
							$view = new View\Text( 'widget_data[custom_params][min_price]' );
							$view->set_required( false );
							$view->set_value( $widget->get_custom_params()['min_price'] ?? '' );
							$view->set_help_text( __( 'Ensures filter products by start with minimum price.', 'yd-mobile-app' ) );
							$view->set_id( 'custom_param_min_price' );
							$view->set_description( __( 'Filters products by start with minimum price.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label for="custom_param_max_price_input"><?php esc_html_e( 'Max price', 'woocommerce' ); ?></label></th>
					<td>
						<?php
							$view = new View\Text( 'widget_data[custom_params][max_price]' );
							$view->set_required( false );
							$view->set_value( $widget->get_custom_params()['max_price'] ?? '' );
							$view->set_help_text( __( 'Ensures filter products limit by maximum price.', 'yd-mobile-app' ) );
							$view->set_id( 'custom_param_max_price' );
							$view->set_description( __( 'Filters products limit by maximum price.', 'yd-mobile-app' ) );
							$view->render();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}
?>
