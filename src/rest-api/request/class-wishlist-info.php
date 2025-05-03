<?php
/**
 * Wishlist Information Retrieval for Mobile App
 *
 * This file defines the `Wishlist_Info` class, which handles the request to retrieve information
 * about the user's wishlist via the REST API. It allows users to view their wishlist without requiring
 * authentication, making it accessible to unauthenticated users.
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
 * Wishlist_Info class handles the request to retrieve information about the user's wishlist.
 *
 * This class allows users to view their wishlist by sending a GET request to the 'wishlist' endpoint.
 * Authentication is not required for this request, meaning it can be used by unauthenticated users.
 */
final class Wishlist_Info extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, as authentication is not required to view the wishlist.
	 *
	 * @return bool False, as authentication is not required for retrieving the wishlist information.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'wishlist' endpoint, which is used to get the user's wishlist information.
	 *
	 * @return string The endpoint URL for retrieving the wishlist information.
	 */
	public function get_endpoint(): string {
		return 'wishlist';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', as the request to retrieve the wishlist uses the GET method.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array as no additional arguments are needed to retrieve the wishlist.
	 *
	 * @return array The options for the request, which is an empty array for this specific request.
	 */
	public function get_options(): array {
		return array();
	}
}
