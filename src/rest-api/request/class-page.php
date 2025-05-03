<?php
/**
 * Page Retrieval for Mobile App
 *
 * This file defines the `Page` class, which handles the request to retrieve the content of a specific page
 * via the REST API. It allows users to fetch page details using the slug as an identifier.
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
 * Page class handles the request to retrieve a specific page's content via the REST API.
 *
 * This class fetches the content of a page based on its slug, allowing users to retrieve
 * specific page details using the slug as an identifier.
 */
final class Page extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve the content of a page.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'page/(?P<page_slug>[a-zA-Z0-9_-]+)' endpoint for the REST API request,
	 * where the `page_slug` is dynamically inserted into the URL.
	 *
	 * @return string The endpoint URL with a placeholder for the page slug.
	 */
	public function get_endpoint(): string {
		return 'page/(?P<page_slug>[a-zA-Z0-9_-]+)';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the content of the page.
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
