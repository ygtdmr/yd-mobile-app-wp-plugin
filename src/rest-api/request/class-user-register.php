<?php
/**
 * User Registration Request for Mobile App
 *
 * This file defines the `User_Register` class, which handles the request for registering a new user.
 * It allows new users to register by sending a POST request to the 'user' endpoint.
 * It does not require authentication, making it available to non-authenticated users.
 *
 * @package YD\Mobile_App
 * @subpackage Request
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Request;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * User_Register class handles the request for registering a new user.
 *
 * This class allows new users to register by sending a POST request to the 'user' endpoint.
 * It does not require authentication, meaning it is available to non-authenticated users.
 */
final class User_Register extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, as authentication is not required to register a new user.
	 *
	 * @return bool False, as authentication is not required for user registration.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'user' endpoint, which is used to register a new user.
	 *
	 * @return string The endpoint URL for user registration.
	 */
	public function get_endpoint(): string {
		return 'user';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'POST', as the request to register a user uses the POST method.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'POST';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an array of arguments required for registering a new user. These include:
	 * - first name
	 * - last name
	 * - email
	 * - password
	 *
	 * Additionally, if WooCommerce support is enabled, billing and shipping address details are required as well.
	 *
	 * @return array The options for the request, including the user registration fields.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'first_name' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
				'last_name'  => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
				'email'      => array(
					'type'     => 'string',
					'format'   => 'email',
					'required' => true,
				),
				'password'   => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
			) + (
				Utils\WC::is_support()
				? array(
					'billing'  => array(
						'type'       => 'object',
						'required'   => true,
						'properties' => Utils\WC::get_address_rules( 'billing' ),
					),
					'shipping' => array(
						'type'       => 'object',
						'properties' => Utils\WC::get_address_rules( 'shipping' ),
					),
				)
				: array()
			),
		);
	}
}
