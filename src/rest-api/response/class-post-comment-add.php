<?php
/**
 * REST API Response: Add Post Comment
 *
 * This file defines the `Post_Comment_Add` class, which handles the REST API response for adding
 * a comment to a specific post. It checks if comments are open for the post, inserts the comment,
 * and sets the comment status to 'hold' for moderation.
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
 * Post_Comment_Add class handles the REST API response for adding a comment to a post.
 *
 * This class checks if comments are open for the post and then creates a new comment for the post.
 * It also handles setting the comment status to 'hold' for moderation.
 */
final class Post_Comment_Add extends \YD\REST_API\Response {

	/**
	 * Callback method for adding a comment to a post.
	 *
	 * This method first checks if comments are open for the given post. If they are not open, it returns an error.
	 * If comments are open, it inserts a new comment into the database and sets its status to 'hold' for moderation.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the post ID, comment content, and other data.
	 *
	 * @return \WP_Error|\array Returns a WP_Error if the comment creation fails, otherwise returns the updated comment list.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		if ( ! comments_open( $request['post_id'] ) ) {
			return new \WP_Error( 'comments_closed', __( 'Comments are closed.' ), array( 'status' => 400 ) );
		}

		$user = wp_get_current_user();

		$comment_id = wp_insert_comment(
			array(
				'comment_post_ID'      => $request['post_id'],
				'comment_content'      => $request['content'],
				'comment_author'       => sprintf( '%s %s', $user->first_name, $user->last_name ),
				'comment_author_email' => $user->user_email,
				'comment_parent'       => $request['reply'],
				'user_id'              => get_current_user_id(),
			)
		);

		if ( false === $comment_id ) {
			return new \WP_Error( 'comment_failed_create', __( 'Creating comment failed.' ), array( 'status' => 400 ) );
		} else {
			wp_set_comment_status( $comment_id, 'hold' );
		}

		return ( new Post_Comments() )->get_callback( $request );
	}
}
