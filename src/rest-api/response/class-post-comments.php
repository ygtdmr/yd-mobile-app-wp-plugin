<?php
/**
 * REST API Response: Post Comments
 *
 * This file defines the `Post_Comments` class, which handles the REST API response
 * for fetching comments of a specific post. It retrieves both held (pending) and approved
 * comments, ensuring that comments are open for the post before proceeding.
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
 * Post_Comments class handles the REST API response for fetching comments of a specific post.
 *
 * This class retrieves both held (pending) and approved comments for a given post and formats them for the response.
 */
final class Post_Comments extends \YD\REST_API\Response {

	/**
	 * Callback method for fetching comments on a post.
	 *
	 * This method retrieves comments for a post, considering both held (pending) and approved comments.
	 * It also ensures that comments are open for the post before proceeding with fetching comments.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the post ID, pagination info, and other parameters.
	 *
	 * @return \WP_Error|\array Returns a WP_Error if comments are closed or an error occurs, otherwise returns the comments.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		if ( ! comments_open( $request['post_id'] ) ) {
			return new \WP_Error( 'comments_closed', __( 'Comments are closed.' ), array( 'status' => 400 ) );
		}

		$comments = get_comments(
			array(
				'post_id'            => $request['post_id'],
				'status'             => 'approve',
				'number'             => 10,
				'paged'              => $request['page'] ?? 1,
				'orderby'            => 'date',
				'order'              => 'asc',
				'parent'             => 0,
				'include_unapproved' => array(
					get_current_user_id(),
				),
			)
		);

		$comments = array_map(
			function ( \WP_Comment $comment ) {
				return Utils\Post::get_comment_info( $comment );
			},
			$comments
		);

		return array( 'comments' => $comments );
	}
}
