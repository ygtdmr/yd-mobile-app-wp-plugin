<?php
/**
 * REST API Response: Customer Orders
 *
 * This file defines the `Customer_Orders` class, which provides a REST API
 * response handler for retrieving a list of orders placed by the authenticated customer.
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
 * Customer_Orders class handles the REST API response for retrieving
 * a customer's order list.
 *
 * This class retrieves all the orders placed by a specific customer, allowing
 * customers to view their order history.
 */
final class Customer_Orders extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving a customer's list of orders.
	 *
	 * This method fetches all orders placed by the customer and returns
	 * them as an array. If there are no orders, it returns an empty array.
	 *
	 * @param \WP_REST_Request|null $request The request object (not used in this case).
	 *
	 * @return array An array containing the customer's orders.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		return array(
			'orders' => parent::get_customer()->get_orders(),
		);
	}
}
