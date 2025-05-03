<?php
/**
 * Post Comments Request for Mobile App
 *
 * This file defines the `Post_Comments` class, which handles the request to retrieve all comments
 * for a specific post via the REST API. It fetches the comments for a post identified by its post ID,
 * providing options for pagination and ordering the comments by ascending or descending order.
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
 * Post_Comments class handles the request to retrieve all comments for a specific post via the REST API.
 *
 * This class fetches the comments for a post identified by its post ID, providing options
 * for pagination and ordering the comments by ascending or descending order.
 */
final class Post_Comments extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve comments for a post.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'posts/(?P<post_id>\d+)/comments' endpoint for the REST API request,
	 * where the `post_id` is dynamically inserted into the URL to fetch the comments for a specific post.
	 *
	 * @return string The endpoint URL for retrieving comments of a post.
	 */
	public function get_endpoint(): string {
		return 'posts/(?P<post_id>\d+)/comments';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the comments for a post.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the arguments for the request, which include:
	 * - page: The page number for pagination, with a default value of 1.
	 * - order: The order in which comments are fetched, with possible values 'asc' or 'desc', defaulting to 'desc'.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page' => array(
					'type'    => 'integer',
					'default' => 1,
				),
			),
		);
	}
}
