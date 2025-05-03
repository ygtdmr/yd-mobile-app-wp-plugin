<?php
/**
 * REST API Response: Bank Accounts Information
 *
 * This file defines the `Info_Bank_Accounts` class, which handles the REST API response
 * for retrieving bank account information. It fetches and returns the list of bank accounts
 * configured in WooCommerce for BACs (Bank Transfer).
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
 * Info_Bank_Accounts class handles the REST API response for retrieving bank accounts information.
 *
 * This class fetches and returns the list of bank accounts configured in WooCommerce for BACs (Bank Transfer).
 */
final class Info_Bank_Accounts extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving the bank accounts information.
	 *
	 * This method fetches the bank accounts stored in the WooCommerce settings
	 * under 'woocommerce_bacs_accounts'. If no bank accounts are set, it returns an empty array.
	 *
	 * @param \WP_REST_Request|null $request The request object (not used in this case).
	 *
	 * @return array An array containing the configured bank accounts for WooCommerce BACs.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		return array(
			// phpcs:ignore Universal.Operators.DisallowShortTernary
			'accounts' => get_option( 'woocommerce_bacs_accounts' ) ?: array(),
		);
	}
}
