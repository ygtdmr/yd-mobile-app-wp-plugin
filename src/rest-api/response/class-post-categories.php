<?php
/**
 * REST API Response: Post Categories
 *
 * This file defines the `Post_Categories` class, which provides a REST API response handler
 * for retrieving the categories within the 'category' taxonomy. The response includes category
 * IDs, slugs, and names.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

defined( 'ABSPATH' ) || exit;

/**
 * Post_Categories class handles the REST API response for retrieving the post categories.
 *
 * This class fetches all categories in the 'category' taxonomy and returns them with their IDs, slugs, and names.
 */
final class Post_Categories extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving all post categories.
	 *
	 * This method fetches all categories from the 'category' taxonomy and returns an array of category details including ID, slug, and name.
	 *
	 * @param \WP_REST_Request|null $request The request object, not used in this case.
	 *
	 * @return array An array containing the list of categories with their IDs, slugs, and names.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
			)
		);

		$terms = array_map(
			function ( $term ) {
				return array(
					'id'   => $term->term_id,
					'slug' => $term->slug,
					'name' => $term->name,
				);
			},
			$terms
		);

		return array( 'categories' => $terms );
	}
}
