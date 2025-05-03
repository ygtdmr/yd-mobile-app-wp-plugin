<?php
/**
 * Customer Orders Request for Mobile App
 *
 * This file defines the `Customer_Orders` class, which handles the request to retrieve a list of a customer's orders
 * via the REST API. The class fetches the orders placed by the customer, providing them with information about all their
 * past orders, with support for pagination.
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
 * Customer_Orders class handles the request to retrieve a list of a customer's orders via the REST API.
 *
 * This class fetches the orders placed by the customer, providing them with information about all their past
 * orders, with support for pagination.
 */
final class Customer_Orders extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to retrieve the list of a customer's orders.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'customer/orders' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'customer/orders';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the list of orders.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments for the request, which includes:
	 * - Page: The page number for pagination of orders, with a default value of 1.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page' => array(
					'type'    => 'integer',
					'default' => 1,
					'minimum' => 1,
				),
			),
		);
	}
}
