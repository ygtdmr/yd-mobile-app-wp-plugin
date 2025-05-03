<?php
/**
 * REST API Response: Products List Handler
 *
 * This file defines the `Products` class, responsible for retrieving and formatting
 * a list of WooCommerce products using the WooCommerce Store API utilities.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Utils;
use Automattic\WooCommerce\StoreApi\Utilities;

defined( 'ABSPATH' ) || exit;

/**
 * Products class handles the REST API response for fetching a list of products.
 *
 * This class utilizes the WooCommerce Store API's ProductQuery class to retrieve the list of products,
 * formats the product information, and returns the data in a structured format.
 */
final class Products extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving the list of products.
	 *
	 * This method uses WooCommerce's ProductQuery class to fetch products based on request parameters,
	 * formats the product data, and returns it in a structured format.
	 *
	 * @param \WP_REST_Request|null $request The request object containing various query parameters.
	 *
	 * @return array The formatted list of products.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$product_query = new Utilities\ProductQuery();
		$results       = $product_query->get_objects( $request );

		$products = array_map(
			function ( $product ) {
				return Utils\WC\Product::get_info( $product );
			},
			$results['objects']
		);

		return array( 'products' => $products );
	}
}
