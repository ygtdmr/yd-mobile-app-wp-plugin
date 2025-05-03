<?php
/**
 * REST API Response: Product Price Calculation
 *
 * This file defines the `Product_Price_Calculate` class, which provides a REST API response handler
 * for calculating a product's price based on the product ID, quantity, and custom fields (if applicable).
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Price_Calculate class handles the REST API response for calculating a product's price.
 *
 * This class calculates the price of a product based on its ID, quantity, and custom fields (if applicable).
 */
final class Product_Price_Calculate extends \YD\REST_API\Response {

	/**
	 * Callback method for calculating the product price.
	 *
	 * This method calculates the price of a product, considering quantity and custom fields.
	 * If any error occurs during the price calculation, it returns a WP_Error.
	 *
	 * @param \WP_REST_Request|null $request The request object containing product ID, quantity, and custom fields.
	 *
	 * @return array|\WP_Error The calculated price or WP_Error if the calculation fails.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$price = Utils\WCK::get_price( $request['product_id'], $request['quantity'], $request['fields'] );
		if ( $price instanceof \WP_Error ) {
			return $price;
		}
		return array( 'price' => $price );
	}
}
