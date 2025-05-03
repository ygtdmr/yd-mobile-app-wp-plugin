<?php
/**
 * Customer Order Request for Mobile App
 *
 * This file defines the `Customer_Order` class, which handles the request to retrieve a customer's order details
 * via the REST API. The class fetches the details of a specific order, identified by the order ID, providing the
 * customer with information about their order status and contents.
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
 * Customer_Order class handles the request to retrieve a customer's order details via the REST API.
 *
 * This class fetches the details of a specific order, identified by the order ID, providing
 * the customer with information about their order status and contents.
 */
final class Customer_Order extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to retrieve the details of a customer's order.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'customer/orders/(?P<order_id>\d+)' endpoint for the REST API request,
	 * where the `order_id` is dynamically inserted into the URL.
	 *
	 * @return string The endpoint URL with a placeholder for the order ID.
	 */
	public function get_endpoint(): string {
		return 'customer/orders/(?P<order_id>\d+)';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the details of the order.
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
