<?php
/**
 * User Logout Request for Mobile App
 *
 * This file defines the `User_Logout` class, which handles the request for logging out
 * the authenticated user. It requires authentication, meaning the user must be logged in
 * to access this functionality. The class sends a GET request to the 'user/logout' endpoint
 * to log out the user.
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
 * User_Logout class handles the request for logging out the authenticated user.
 *
 * This class logs out a user by sending a GET request to the 'user/logout' endpoint.
 * It requires authentication, meaning the user must be logged in to access this functionality.
 */
final class User_Logout extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, as authentication is required to log out the user.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'user/logout' endpoint, which is used to log out the user.
	 *
	 * @return string The endpoint URL for logging out the user.
	 */
	public function get_endpoint(): string {
		return 'user/logout';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', as the request to log out a user uses the GET method.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, as there are no additional options required for the logout request.
	 *
	 * @return array The options for the request, which is an empty array in this case.
	 */
	public function get_options(): array {
		return array();
	}
}
