<?php
/**
 * REST API Response: Customer_Order_Message_Send
 *
 * This file defines the `Customer_Order_Message_Send` class, which handles the REST API response for sending
 * order-related messages. The message is sent through the customer's order system, and the response contains
 * the list of sent messages.
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
 * Customer_Order_Message_Send class handles the REST API response for sending
 * order-related messages.
 *
 * This class allows customers to send messages related to their orders. The
 * message is sent through the customer's order system, and the response contains
 * the list of sent messages.
 */
final class Customer_Order_Message_Send extends \YD\REST_API\Response {

	/**
	 * Callback method for sending order messages.
	 *
	 * This method sends a message related to a specific order. If the message is
	 * sent successfully, it returns the list of all sent messages. If there is an
	 * error, it returns the error.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the order ID and message.
	 *
	 * @return array|\WP_Error The list of sent messages or an error if the message could not be sent.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$messages = parent::get_customer()->send_order_message(
			$request['order_id'],
			$request['message']
		);

		if ( $messages instanceof \WP_Error ) {
			return $messages;
		}

		return array(
			'messages' => $messages,
		);
	}
}
