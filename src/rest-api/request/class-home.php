<?php
/**
 * Home Page Request for Mobile App
 *
 * This file defines the `Home` class, which handles the request to retrieve home page information
 * via the REST API. The class fetches data relevant to the home page, such as featured products,
 * categories, or promotional content, with support for pagination.
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
 * Home class handles the request to retrieve home page information via the REST API.
 *
 * This class fetches data relevant to the home page, such as featured products, categories, or promotional content,
 * with support for pagination.
 */
final class Home extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve the home page information.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'home' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'home';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve home page data.
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
	 * - Page: The page number for pagination of home page content, with a default value of 1.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page' => array(
					'type'    => 'integer',
					'default' => 1,
					'minimum' => 1,
				),
			),
		);
	}
}
