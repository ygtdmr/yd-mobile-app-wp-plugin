<?php
/**
 * Product Information Retrieval for Mobile App
 *
 * This file defines the `Product_Info` class, which handles the request to retrieve details
 * of a specific product via the REST API. It allows fetching information about a product
 * specified by its ID. This request does not require authentication.
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
 * Product_Info class handles the request to retrieve details of a specific product via the REST API.
 *
 * This class allows fetching information about a single product by its ID.
 * The request does not require authentication.
 */
final class Product_Info extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve details of a product.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products/(?P<product_id>\d+)' endpoint for the REST API request,
	 * which retrieves the information of a product specified by its ID.
	 *
	 * @return string The endpoint URL for fetching product information.
	 */
	public function get_endpoint(): string {
		return 'products/(?P<product_id>\d+)';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve product details.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, as no additional options are required for this request.
	 *
	 * @return array The options for the request.
	 */
	public function get_options(): array {
		return array();
	}
}
