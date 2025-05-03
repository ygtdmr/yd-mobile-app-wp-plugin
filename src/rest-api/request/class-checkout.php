<?php
/**
 * Checkout Process for Mobile App
 *
 * This file defines the `Checkout` class, which handles the request to initiate the checkout process
 * via the REST API. It provides methods for selecting a payment method and specifying whether
 * shipping information should be sent.
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
 * Checkout class handles the request to initiate the checkout process via the REST API.
 *
 * This class provides methods for handling checkout actions, such as selecting a payment method
 * and specifying whether shipping information should be sent.
 */
final class Checkout extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to proceed with the checkout.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'checkout' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'checkout';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'POST', indicating that the request will initiate a new checkout.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'POST';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments required to complete the checkout, including:
	 * - Payment Method: The chosen payment method for the checkout.
	 * - Send to Shipping: A boolean indicating whether shipping details should be sent.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'payment_method'   => array(
					'required' => true,
					'type'     => 'string',
					'enum'     => array(
						'bacs',
						'cod',
					),
				),
				'send_to_shipping' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		);
	}
}
