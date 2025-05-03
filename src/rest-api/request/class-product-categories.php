<?php
/**
 * Product Categories Request for Mobile App
 *
 * This file defines the `Product_Categories` class, which handles the request to retrieve
 * a list of product categories via the REST API. The class fetches the categories for products
 * in the store, without requiring authentication.
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
 * Product_Categories class handles the request to retrieve a list of product categories via the REST API.
 *
 * This class fetches the categories for products in the store, without requiring authentication.
 */
final class Product_Categories extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve a list of product categories.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products/categories' endpoint for the REST API request,
	 * which retrieves a list of product categories.
	 *
	 * @return string The endpoint URL for fetching product categories.
	 */
	public function get_endpoint(): string {
		return 'products/categories';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve product categories.
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
