<?php
/**
 * User Info Request for Mobile App
 *
 * This file defines the `User_Info` class, which handles the request for retrieving user information via the REST API.
 * This class allows authenticated users to retrieve their personal information. It uses the GET method to fetch data
 * from the 'user' endpoint. The request requires authentication to access the user data.
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
 * User_Info class handles the request for retrieving user information.
 *
 * This class allows authenticated users to retrieve their personal information.
 * It uses the GET method to fetch data from the 'user' endpoint.
 * The request requires authentication to access the user data.
 */
final class User_Info extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required to retrieve user information.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'user' endpoint, which is used to retrieve the user's information.
	 *
	 * @return string The endpoint URL for retrieving user data.
	 */
	public function get_endpoint(): string {
		return 'user';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve user information.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method does not define any specific options for the GET request to fetch user data.
	 *
	 * @return array The options for the request (empty in this case).
	 */
	public function get_options(): array {
		return array();
	}
}
