<?php
/**
 * REST API Response: Product Categories
 *
 * This file defines the `Product_Categories` class, which handles the REST API response for retrieving
 * all available product categories. It queries the product categories and returns their basic details
 * such as ID, slug, and name for use in the API response.
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
 * Product_Categories class handles the REST API response for retrieving product categories.
 *
 * This class fetches all available product categories and formats them for use in the API response.
 */
final class Product_Categories extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving product categories.
	 *
	 * This method queries the product categories and returns their basic details like ID, slug, and name.
	 *
	 * @param \WP_REST_Request|null $request The request object (not used in this case).
	 *
	 * @return array The formatted product category data in response.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
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
