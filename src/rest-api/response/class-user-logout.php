<?php
/**
 * REST API Response: User Logout
 *
 * This file defines the `User_Logout` class, which handles user logout functionality
 * in the REST API. It allows users to log out by calling the `wp_logout` function.
 * It does not return any response as the logout is a side-effect action.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * User_Logout class handles user logout functionality in the REST API.
 *
 * This class allows users to log out by calling the `wp_logout` function.
 * It doesn't return any response since the logout is a side-effect action.
 */
final class User_Logout extends \YD\REST_API\Response {

	/**
	 * Callback method for logging out a user.
	 *
	 * This method logs the user out using the `wp_logout` function.
	 * It doesn't return anything as the action is a logout process.
	 *
	 * @param \WP_REST_Request|null $request The request object (optional).
	 *
	 * @return void
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		wp_logout();
	}
}
