<?php
/**
 * REST API Response: Customer Order Messages
 *
 * This file defines the `Customer_Order_Messages` class, which provides a REST API
 * response handler for retrieving messages related to a customer's specific order.
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
 * Customer_Order_Messages class handles the REST API response for retrieving
 * the messages associated with a specific customer order.
 *
 * This class retrieves and returns the messages that are associated with a
 * particular order, allowing customers to view all the messages related to
 * their orders.
 */
final class Customer_Order_Messages extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving order messages.
	 *
	 * This method fetches the messages associated with the order specified by the
	 * `order_id` in the request. If the messages are retrieved successfully, it
	 * returns the list of messages. If there is an error, it returns the error.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the order ID.
	 *
	 * @return array|\WP_Error The list of messages related to the order or an error if retrieval fails.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$messages = parent::get_customer()->get_order_messages( $request['order_id'] );

		if ( $messages instanceof \WP_Error ) {
			return $messages;
		}

		return array(
			'messages' => $messages,
		);
	}
}
