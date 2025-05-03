<?php
/**
 * REST API Response: User_Edit
 *
 * This file defines the `User_Edit` class, which handles the REST API response
 * for editing a user's information. Depending on whether the user has access to WooCommerce
 * (i.e., is a customer with support privileges), the class will update either the customer’s data
 * or the regular user data.
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
 * User_Edit class handles the REST API response for editing a user's information.
 *
 * Depending on whether the user has access to WooCommerce (i.e., is a customer with support privileges),
 * the class will update either the customer’s data or the regular user data.
 */
final class User_Edit extends \YD\REST_API\Response {

	/**
	 * Callback method for editing user information.
	 *
	 * This method checks if the user is associated with WooCommerce (i.e., a customer) and has support privileges.
	 * If the user has WooCommerce support, it updates the customer information, otherwise, it updates the regular user information.
	 *
	 * @param \WP_REST_Request|null $request The request object containing user data to be updated.
	 *
	 * @return mixed The result of the user data update (either customer or regular user).
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		if ( Utils\WC::is_support() ) {
			return parent::get_customer()->edit( $request->get_params() );
		} else {
			return parent::get_user()->edit( $request->get_params() );
		}
	}
}
