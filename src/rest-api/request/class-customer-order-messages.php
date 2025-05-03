<?php
/**
 * Customer Order Messages Request for Mobile App
 *
 * This file defines the `Customer_Order_Messages` class, which handles the request to retrieve messages related
 * to a customer's order via the REST API. The class fetches the messages associated with a specific order,
 * identified by the order ID, providing the customer with the order's message history.
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
 * Customer_Order_Messages class handles the request to retrieve messages related to a customer's order via the REST API.
 *
 * This class fetches the messages associated with a specific order, identified by the order ID,
 * providing the customer with the order's message history.
 */
final class Customer_Order_Messages extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to retrieve messages from a customer's order.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'customer/orders/(?P<order_id>\d+)/messages' endpoint for the REST API request,
	 * where the `order_id` is dynamically inserted into the URL.
	 *
	 * @return string The endpoint URL with a placeholder for the order ID.
	 */
	public function get_endpoint(): string {
		return 'customer/orders/(?P<order_id>\d+)/messages';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the messages for the order.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, indicating that no additional options
	 * are required for this request.
	 *
	 * @return array The options for the request.
	 */
	public function get_options(): array {
		return array();
	}
}
