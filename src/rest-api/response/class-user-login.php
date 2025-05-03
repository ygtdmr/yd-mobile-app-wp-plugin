<?php
/**
 * REST API Response: User Login
 *
 * This file defines the `User_Login` class, which handles the REST API response
 * for logging in a user using their username/email and password. It returns customer
 * information if authentication is successful or an error if authentication fails.
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
 * User_Login class handles user login functionality in the REST API.
 *
 * This class allows users to log in using their username/email and password.
 * If authentication is successful, it returns the customer information.
 * If the login fails, it returns an authentication error.
 */
final class User_Login extends \YD\REST_API\Response {

	/**
	 * Callback method for logging in a user.
	 *
	 * This method tries to authenticate the user using the provided credentials.
	 * If successful, it returns the customer information. If authentication fails,
	 * it returns a WP_Error indicating failure.
	 *
	 * @param \WP_REST_Request|null $request The request object, which contains the login credentials.
	 *
	 * @return mixed The customer information if login is successful or WP_Error if authentication fails.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$user = wp_signon(
			array(
				'user_login'    => $request['login'],
				'user_password' => $request['password'],
				'remember'      => true,
			)
		);

		if ( $user instanceof \WP_Error ) {
			return new \WP_Error(
				'authentication_failed',
				wp_strip_all_tags( __( '<strong>Error:</strong> Invalid username, email address or incorrect password.' ) ),
				array( 'status' => 401 )
			);
		}

		return parent::get_customer( $user->ID )->get_info();
	}
}
