<?php
/**
 * Cart Information Request for Mobile App
 *
 * This file defines the `Cart_Info` class, which handles the request to fetch the current
 * information of a customer's cart via the REST API. The class provides methods for retrieving
 * the cart details, such as items, pricing, and shipping information.
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
 * Cart_Info class handles the request to fetch the current information of a customer's cart via the REST API.
 *
 * This class provides methods for retrieving the cart details, such as items, pricing, and shipping information.
 */
final class Cart_Info extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not needed
	 * to retrieve the cart information.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'cart' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'cart';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will fetch cart information.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, indicating there are no additional options
	 * needed for this request.
	 *
	 * @return array The options for the request.
	 */
	public function get_options(): array {
		return array();
	}
}
