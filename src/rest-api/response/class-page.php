<?php
/**
 * REST API Response: Page
 *
 * This file defines the `Page` class, which handles the REST API response for retrieving
 * a specific page's content. It fetches a page by its slug and returns its title and content.
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
 * Page class handles the REST API response for retrieving a specific page's content.
 *
 * This class fetches a page by its slug and returns its title and content in a structured format.
 */
final class Page extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving a page's title and content.
	 *
	 * This method fetches a page based on the provided slug in the request.
	 * It returns the page title and content if found, otherwise returns an error.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the page slug.
	 *
	 * @return array|\WP_Error An array containing the page's title and content, or a WP_Error on failure.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$post = Utils\Post::get( $request['page_slug'], 'page' );
		if ( $post instanceof \WP_Error ) {
			return $post;
		}

		return array(
			'title'   => $post->post_title ?? '',
			'content' => Utils\Post::render_content( $post->post_content ?? '', true ),
		);
	}
}
