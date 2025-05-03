<?php
/**
 * Posts Request for Mobile App
 *
 * This file defines the `Posts` class, which handles the request to retrieve a list of posts via the REST API.
 * This class allows fetching posts with options for pagination, search, category, tags, and ordering.
 * The request can be filtered and sorted based on multiple criteria, such as date, title, comment count, and author.
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
 * Posts class handles the request to retrieve a list of posts via the REST API.
 *
 * This class allows fetching posts with options for pagination, search, category, tags, and ordering.
 * The request can be filtered and sorted based on multiple criteria, such as date, title, comment count, and author.
 */
final class Posts extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve a list of posts.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'posts' endpoint for the REST API request, which retrieves a list of posts.
	 *
	 * @return string The endpoint URL for fetching posts.
	 */
	public function get_endpoint(): string {
		return 'posts';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve posts.
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
	 * - search: A search string to filter posts based on title or content.
	 * - category: A category slug to filter posts by category.
	 * - tag: A tag slug to filter posts by tag.
	 * - orderby: Defines how the posts should be ordered, with possible values 'date', 'title', 'comment_count', or 'author', defaulting to 'date'.
	 * - order: Defines the order direction, either 'asc' or 'desc', with 'desc' as the default.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page'     => array(
					'type'    => 'integer',
					'default' => 1,
					'minimum' => 1,
				),
				'search'   => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'category' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'tag'      => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'orderby'  => array(
					'type'    => 'string',
					'enum'    => array(
						'date',
						'title',
						'comment_count',
						'author',
					),
					'default' => 'date',
				),
				'order'    => array(
					'type'    => 'string',
					'enum'    => array(
						'asc',
						'desc',
					),
					'default' => 'desc',
				),
			),
		);
	}
}
