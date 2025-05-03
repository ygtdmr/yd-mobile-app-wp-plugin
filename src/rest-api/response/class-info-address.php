<?php
/**
 * REST API Response: Address Information
 *
 * This file defines the `Info_Address` class, which handles the REST API response
 * for retrieving the list of allowed countries and their respective states. It checks
 * the request for a specific country and returns the allowed countries along with the
 * states for the requested country, if available.
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
 * Info_Address class handles the REST API response for retrieving allowed countries and their states.
 *
 * This class returns the list of allowed countries and the states of a specific country based on the provided request.
 */
final class Info_Address extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving allowed countries and their states.
	 *
	 * This method checks the request for a specific country and returns the list of allowed
	 * countries along with the states for that country. If the requested country has no states,
	 * an empty array is returned.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the country data.
	 *
	 * @return array An array containing the allowed countries and the states for the requested country.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$states_by_country = $request['states_by_country'];
		return array(
			'countries' => WC()->countries->get_allowed_countries(),
			'states'    => WC()->countries->get_allowed_country_states()[ $states_by_country ] ?? array(),
		);
	}
}
