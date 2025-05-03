<?php
/**
 * REST API Response: Posts List Handler
 *
 * This file defines the `Posts` class, responsible for handling REST API responses
 * related to retrieving a filtered list of published posts. Supports parameters such as
 * search terms, categories, tags, and ordering options.
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
 * Posts class handles the REST API response for retrieving a list of published posts.
 *
 * This class allows fetching posts with various parameters such as search, category, tag, order, etc.
 */
final class Posts extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving a list of posts based on various filters.
	 *
	 * This method queries posts based on provided parameters like search term, category, tag, order, etc.
	 * It then formats and returns the post data.
	 *
	 * @param \WP_REST_Request|null $request The request object containing various filters (search, order, category, etc.).
	 *
	 * @return array The formatted post data in response.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$query = new \WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 10,
				'paged'          => $request['page'],
				's'              => $request['search'],
				'orderby'        => $request['orderby'],
				'order'          => $request['order'],
				'cat'            => $request['category'],
				'tag_id'         => $request['tag'],
			)
		);

		return array(
			'posts' => array_map(
				function ( \WP_Post $post ) {
					return Utils\Post::get_info( $post );
				},
				$query->posts
			),
		);
	}
}
