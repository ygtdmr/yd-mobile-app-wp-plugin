<?php
/**
 * Post Categories Request for Mobile App
 *
 * This file defines the `Post_Categories` class, which handles the request to retrieve all
 * post categories via the REST API. The class fetches the categories of posts, providing a list
 * of categories available on the site.
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
 * Post_Categories class handles the request to retrieve all post categories via the REST API.
 *
 * This class fetches the categories of posts, providing a list of categories available on the site.
 */
final class Post_Categories extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to retrieve the list of post categories.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'posts/categories' endpoint for the REST API request,
	 * which retrieves the categories for posts.
	 *
	 * @return string The endpoint URL for retrieving post categories.
	 */
	public function get_endpoint(): string {
		return 'posts/categories';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve the post categories.
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
