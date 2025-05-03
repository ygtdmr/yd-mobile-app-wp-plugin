<?php
/**
 * User Login for Mobile App
 *
 * This file defines the `User_Login` class, which handles the request for user login via the REST API.
 * It allows users to log in by providing their login credentials (username/email and password).
 * The request uses the POST method to send data to the 'user/login' endpoint and does not require authentication.
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
 * User_Login class handles the request for user login.
 *
 * This class allows users to log in by providing their login credentials (username/email and password).
 * It uses the POST method to send data to the 'user/login' endpoint and does not require authentication.
 */
final class User_Login extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, as authentication is not required for logging in a user.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'user/login' endpoint, which is used to log in a user.
	 *
	 * @return string The endpoint URL for logging in a user.
	 */
	public function get_endpoint(): string {
		return 'user/login';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'POST', indicating that the request will send user credentials to log in.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'POST';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the required arguments for the request, which are 'login' and 'password'.
	 *
	 * @return array The options for the request, including required arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'login'    => array( 'required' => true ),
				'password' => array( 'required' => true ),
			),
		);
	}
}
