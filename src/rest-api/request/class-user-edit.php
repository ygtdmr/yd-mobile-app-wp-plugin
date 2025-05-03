<?php
/**
 * User Information Update for Mobile App
 *
 * This file defines the `User_Edit` class, which handles the request for updating user information
 * via the REST API. Users can update their personal information, including their name, email, password,
 * and optionally billing and shipping addresses if WooCommerce support is enabled. The request requires
 * authentication and uses the PUT method to update user data.
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
 * User_Edit class handles the request for updating user information.
 *
 * This class allows users to update their personal information, including their first name, last name, email, and password.
 * Additionally, if WooCommerce support is enabled, users can also update their billing and shipping addresses.
 * The request requires authentication and uses the PUT method to update user data.
 */
final class User_Edit extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required to edit user information.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'user' endpoint, which is used to update the user information.
	 *
	 * @return string The endpoint URL for updating user data.
	 */
	public function get_endpoint(): string {
		return 'user';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'PUT', indicating that the request will update user information.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the arguments for updating user information:
	 * - 'first_name' (optional): the user's first name.
	 * - 'last_name' (optional): the user's last name.
	 * - 'email' (optional): the user's email address.
	 * - 'password' (optional): the user's password information, which includes 'current' and 'new' passwords.
	 *
	 * If WooCommerce support is enabled, the following fields are included:
	 * - 'billing' (optional): an object representing the user's billing address.
	 * - 'shipping' (optional): an object representing the user's shipping address.
	 *
	 * @return array The options for the request, including arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'first_name' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'last_name'  => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'email'      => array(
					'type'   => 'string',
					'format' => 'email',
				),
				'password'   => array(
					'type'       => 'object',
					'properties' => array(
						'current' => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
						'new'     => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
					),
				),
			) + (
				Utils\WC::is_support()
				? array(
					'billing'  => array(
						'type'       => 'object',
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
