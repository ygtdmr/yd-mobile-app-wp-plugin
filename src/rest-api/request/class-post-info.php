<?php
/**
 * Post Information Retrieval for Mobile App
 *
 * This file defines the `Post_Info` class, which handles the request to retrieve detailed
 * information about a specific post via the REST API. It fetches post details using the post ID.
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
 * Post_Info class handles the request to retrieve detailed information of a specific post via the REST API.
 *
 * This class fetches information about a post identified by its post ID, providing the post's details.
 */
final class Post_Info extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve detailed information about a post.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'posts/(?P<post_id>\d+)' endpoint for the REST API request,
	 * where the `post_id` is dynamically inserted into the URL to retrieve information about a specific post.
	 *
	 * @return string The endpoint URL for retrieving detailed post information.
	 */
	public function get_endpoint(): string {
		return 'posts/(?P<post_id>\d+)';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve detailed information about the post.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an empty array, as no additional options are required for this request.
	 *
	 * @return array The options for the request.
	 */
	public function get_options(): array {
		return array();
	}
}
