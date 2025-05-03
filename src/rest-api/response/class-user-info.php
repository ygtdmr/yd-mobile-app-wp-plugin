<?php
/**
 * REST API Response: User Info Handler
 *
 * This file defines the `User_Info` class, which retrieves user details via the mobile app's REST API.
 * It conditionally returns WooCommerce customer information or regular user data based on the user's access level.
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
 * User_Info class handles the REST API response for retrieving user information.
 *
 * Depending on whether the user has access to WooCommerce (i.e., is a customer with support privileges),
 * the class will return either the customer’s information or the regular user data.
 */
final class User_Info extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving user information.
	 *
	 * This method checks if the user is associated with WooCommerce (i.e., a customer) and has support privileges.
	 * If the user has WooCommerce support, it fetches the customer’s information; otherwise, it fetches the regular user information.
	 *
	 * @param \WP_REST_Request|null $request The request object (not used in this case).
	 *
	 * @return mixed The result of the user data retrieval (either customer or regular user).
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		if ( Utils\WC::is_support() ) {
			return parent::get_customer()->get_info();
		} else {
			return parent::get_user()->get_info();
		}
	}
}
