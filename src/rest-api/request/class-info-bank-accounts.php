<?php
/**
 * Info Bank Accounts Request for Mobile App
 *
 * This file defines the `Info_Bank_Accounts` class, which handles the request to retrieve
 * bank account information via the REST API. It fetches data related to bank accounts, such as
 * available bank account details, providing users with the necessary information for financial
 * transactions or management.
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
 * Info_Bank_Accounts class handles the request to retrieve bank account information via the REST API.
 *
 * This class fetches data related to bank accounts, such as available bank account details,
 * providing users with the necessary information for financial transactions or management.
 */
final class Info_Bank_Accounts extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve bank account information.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'info/bank-accounts' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'info/bank-accounts';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve bank account-related information.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, indicating that no additional options
	 * are required for this request.
	 *
	 * @return array The options for the request.
	 */
	public function get_options(): array {
		return array();
	}
}
