<?php
/**
 * Products Request for Mobile App
 *
 * This file defines the `Products` class, which handles the request to retrieve a list of products via the REST API.
 * This class allows retrieving products based on various filters such as category, price range, or sale status.
 * The request does not require authentication and uses the GET method to retrieve product data.
 *
 * @package YD\Mobile_App
 * @subpackage Request
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Request;

defined( 'ABSPATH' ) || exit;

/**
 * Products class handles the request to retrieve a list of products
 * via the REST API.
 *
 * This class allows retrieving products based on various filters such as category, price range, or sale status.
 * The request does not require authentication and uses the GET method to retrieve product data.
 */
final class Products extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required to retrieve the list of products.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products' endpoint, which retrieves a list of products available.
	 *
	 * @return string The endpoint URL for retrieving products.
	 */
	public function get_endpoint(): string {
		return 'products';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve a list of products.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the arguments for retrieving products:
	 * - 'page' (optional): an integer for pagination, defaulting to page 1.
	 * - 'on_sale' (optional): a boolean to filter products that are on sale.
	 * - 'sku' (optional): a string to filter products by SKU.
	 * - 'search' (optional): a string to search for products by name.
	 * - 'category' (optional): a string to filter products by category.
	 * - 'min_price' (optional): an integer to filter products by minimum price.
	 * - 'max_price' (optional): an integer to filter products by maximum price.
	 * - 'product_id' (optional): a string to filter a specific product by its ID.
	 * - 'orderby' (optional): a string to order products, defaulting to 'date'. Options: 'date', 'rating', 'popularity', 'price'.
	 * - 'order' (optional): a string to define the sorting order, defaulting to 'desc'. Options: 'asc', 'desc'.
	 *
	 * @return array The options for the request, including arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page'       => array(
					'type'    => 'integer',
					'default' => 1,
					'minimum' => 1,
				),
				'on_sale'    => array( 'type' => 'boolean' ),
				'sku'        => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'search'     => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'category'   => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'min_price'  => array( 'type' => 'integer' ),
				'max_price'  => array( 'type' => 'integer' ),
				'product_id' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'orderby'    => array(
					'type'    => 'string',
					'enum'    => array(
						'date',
						'rating',
						'popularity',
						'price',
					),
					'default' => 'date',
				),
				'order'      => array(
					'type'    => 'string',
					'enum'    => array(
						'asc',
						'desc',
					),
					'default' => 'desc',
				),
			),
		);
	}
}
