<?php
/**
 * Customer Order Message Sending for Mobile App
 *
 * This file defines the `Customer_Order_Message_Send` class, which handles the request to send a message
 * to a customer's order via the REST API. It allows users to send a message related to a specific order
 * by providing the order ID and message content.
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
 * Customer_Order_Message_Send class handles the request to send a message to a customer's order via the REST API.
 *
 * This class allows for sending a message related to a specific order by providing the order ID
 * and the message content.
 */
final class Customer_Order_Message_Send extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to send a message to a customer's order.
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
	 * This method returns 'PUT', indicating that the request will update the order with a new message.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments required to send the message, which includes:
	 * - Message: The content of the message to be sent to the order.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'message' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
			),
		);
	}
}
