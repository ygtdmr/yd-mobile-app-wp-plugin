<?php
/**
 * REST API Response: Product Info
 *
 * This file defines the `Product_Info` class, which handles the REST API response
 * for retrieving detailed product information, including reviews and custom fields
 * if supported by the WooCommerce setup.
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
 * Product_Info class handles the REST API response for retrieving product information.
 *
 * This class provides detailed information about a product, including its reviews and custom fields
 * if supported by the WooCommerce setup.
 */
final class Product_Info extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving product information.
	 *
	 * This method fetches details about a product, including review settings, and custom fields
	 * if the WooCommerce Custom Fields plugin (WCK) is supported.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the product ID.
	 *
	 * @return array The formatted product information in the response.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$response  = array(
			'is_review_enabled' => Utils\WC::is_review_enabled(),
		);
		$response += Utils\WC\Product::get_info( $request['product_id'], false );
		if ( Utils\WCK::is_support() ) {
			$response += array(
				'fields' => Utils\WCK::get_fields( $request['product_id'] ),
			);
		}
		return $response;
	}
}
