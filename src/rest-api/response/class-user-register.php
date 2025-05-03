<?php
/**
 * REST API Response: User Register
 *
 * This file defines the `User_Register` class, which handles the REST API response
 * for user registration. It registers a new user either via WooCommerce or WordPress,
 * depending on whether WooCommerce support is enabled.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

defined( 'ABSPATH' ) || exit;

use YD\Utils;

/**
 * User_Register class handles user registration functionality via the REST API.
 *
 * This class processes user registration by using either WooCommerce's customer registration method
 * or WordPress's user registration method depending on whether WooCommerce support is enabled.
 */
final class User_Register extends \YD\REST_API\Response {

	/**
	 * Callback method to handle user registration.
	 *
	 * This method registers a new user either via WooCommerce or WordPress,
	 * depending on the context of the request.
	 *
	 * @param \WP_REST_Request|null $request The request object containing registration data.
	 *
	 * @return \WP_Error|\WP_User The result of the registration process (error or user info).
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		if ( Utils\WC::is_support() ) {
			$result = Utils\WC\Customer::create( $request->get_params() );
		} else {
			$data   = Utils\Main::sanitize_array_by_keys(
				$request->get_params(),
				array(
					'first_name',
					'last_name',
					'email',
					'password',
				)
			);
			$result = Utils\User::create( $data );
		}

		if ( $result instanceof \WP_Error ) {
			return $result;
		}

		return $result->get_info();
	}
}
