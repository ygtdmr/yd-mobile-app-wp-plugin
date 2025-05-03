<?php
/**
 * REST API Response: Customer Order
 *
 * This file defines the `Customer_Order` class, which provides a REST API
 * response handler for retrieving a specific order belonging to a customer.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

defined( 'ABSPATH' ) || exit;

/**
 * Customer_Order class handles the REST API response for retrieving
 * a specific customer order.
 *
 * This class retrieves the details of a specific customer order, allowing
 * customers to view their order information using the `order_id`.
 */
final class Customer_Order extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving a customer's specific order.
	 *
	 * This method fetches the details of a customer's order using the `order_id`
	 * provided in the request. It returns the order details, or an error if
	 * the order cannot be retrieved.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the order ID.
	 *
	 * @return array|\WP_Error The order details or an error if retrieval fails.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		return parent::get_customer()->get_order( $request['order_id'] );
	}
}
