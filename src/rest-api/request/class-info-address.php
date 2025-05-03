<?php
/**
 * Info Address Request for Mobile App
 *
 * This file defines the `Info_Address` class, which handles the request to retrieve address
 * information via the REST API. It fetches information related to address fields, such as states
 * by country, providing users with the necessary data for address management or selection.
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
 * Info_Address class handles the request to retrieve address information via the REST API.
 *
 * This class fetches information related to address fields, such as states by country,
 * providing users with the necessary data for address management or selection.
 */
final class Info_Address extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve address information.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'info/address' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'info/address';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve address-related information.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments for the request, which includes:
	 * - states_by_country: A parameter to retrieve states based on the country.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'states_by_country' => array( 'type' => 'string' ),
			),
		);
	}
}
