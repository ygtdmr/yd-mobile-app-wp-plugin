<?php
/**
 * Post Comment Add Request for Mobile App
 *
 * This file defines the `Post_Comment_Add` class, which handles the request to add a comment
 * to a specific post via the REST API. The class allows authenticated users to add comments to
 * posts by specifying the post ID and comment content.
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
 * Post_Comment_Add class handles the request to add a comment to a specific post via the REST API.
 *
 * This class allows authenticated users to add comments to posts by specifying the post ID and comment content.
 */
final class Post_Comment_Add extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required
	 * to add a comment to a post.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'posts/(?P<post_id>\d+)/comments' endpoint for the REST API request,
	 * where the `post_id` is dynamically inserted into the URL to target the correct post.
	 *
	 * @return string The endpoint URL for adding a comment to a post.
	 */
	public function get_endpoint(): string {
		return 'posts/(?P<post_id>\d+)/comments';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'PUT', indicating that the request will add a comment to a post.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments for the request, which includes:
	 * - content: The comment content, which is required and sanitized as a string.
	 * - reply: An optional parameter specifying the ID of a comment being replied to, defaulting to 0.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'content' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
				'reply'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
			),
		);
	}
}
