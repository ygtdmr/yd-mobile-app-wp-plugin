<?php
/**
 * REST API Response: Product_Reviews
 *
 * This file defines the `Product_Reviews` class, which handles the REST API response
 * for fetching product reviews. This class retrieves the reviews for a product, handles both
 * approved and pending (hold) reviews, and supports sorting by rating or date.
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
 * Product_Reviews class handles the REST API response for fetching product reviews.
 *
 * This class retrieves the reviews for a product, handles both approved and pending (hold) reviews,
 * and supports sorting by rating or date.
 */
final class Product_Reviews extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving product reviews.
	 *
	 * This method fetches both approved and pending reviews for a product, allows sorting by rating or date,
	 * and formats the review information.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the product ID, page, sorting, and ordering parameters.
	 *
	 * @return array The formatted list of reviews for the product.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$product = wc_get_product( $request['product_id'] );

		if ( $product ) {
			if ( $product instanceof \WC_Product_Variation ) {
				$request['product_id'] = $product->get_parent_id();
			}
		}

		$comments = get_comments(
			array(
				'post_id'            => $request['product_id'],
				'status'             => 'approve',
				'number'             => 10,
				'paged'              => $request['page'],
				'order'              => $request['order'],
				'parent'             => 0,
				'include_unapproved' => array(
					get_current_user_id(),
				),
			) + (
				'rating' === $request['orderby']
				? array(
					'orderby'   => 'meta_value_num',
					'meta_type' => 'NUMERIC',
					// phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_key'  => 'rating',
				)
				: array( 'orderby' => 'date' )
			)
		);

		$reviews = array_map(
			function ( \WP_Comment $comment ) {
				return Utils\Post::get_comment_info(
					$comment,
					array(
						'rating' => array(
							// phpcs:ignore WordPress.DB.SlowDBQuery
							'meta_key'  => 'rating',
							'is_single' => true,
							'type'      => 'integer',
						),
					)
				);
			},
			$comments
		);

		return array( 'reviews' => $reviews );
	}
}
