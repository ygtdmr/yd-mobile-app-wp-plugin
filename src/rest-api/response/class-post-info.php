<?php
/**
 * REST API Response: Post_Info
 *
 * This file defines the `Post_Info` class, which handles retrieving detailed information
 * about a specific post via the REST API. This class fetches the post details along with
 * whether comments are open for the post or not.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Post_Info class handles the REST API response for retrieving detailed information about a specific post.
 *
 * This class fetches the post details along with whether comments are open for the post or not.
 */
final class Post_Info extends \YD\REST_API\Response {

	/**
	 * Callback method for fetching detailed information about a specific post.
	 *
	 * This method retrieves the post data and checks if comments are open for the post.
	 * It then formats the data and returns it in the response.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the post ID.
	 *
	 * @return \WP_Error|\array Returns a WP_Error if the post is not found or an error occurs, otherwise returns the post information.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$post = Utils\Post::get( (int) $request['post_id'] );
		if ( $post instanceof \WP_Error ) {
			return $post;
		}

		$response  = array(
			'is_comments_open' => comments_open( $request['post_id'] ),
		);
		$response += Utils\Post::get_info( $post, false );

		return $response;
	}
}
