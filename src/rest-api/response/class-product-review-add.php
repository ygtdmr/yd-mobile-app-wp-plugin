<?php
/**
 * REST API Response: Product_Review_Add
 *
 * This file defines the `Product_Review_Add` class, which handles the REST API response
 * for adding a product review. This class allows users to add a review for a product,
 * including a rating and the ability to reply to existing reviews.
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
 * Product_Review_Add class handles the REST API response for adding a product review.
 *
 * This class allows users to add a review for a product, including a rating and reply to existing reviews.
 */
final class Product_Review_Add extends \YD\REST_API\Response {

	/**
	 * Callback method for adding a product review.
	 *
	 * This method inserts a product review comment, associates a rating with it,
	 * and sets the review to "hold" status for moderation.
	 *
	 * @param \WP_REST_Request|null $request The request object containing product ID, review content, rating, and other details.
	 *
	 * @return array|\WP_Error The list of reviews for the product or a WP_Error if the review creation fails.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$product = wc_get_product( $request['product_id'] );
		if ( $product ) {
			if ( $product instanceof \WC_Product_Variation ) {
				$request['product_id'] = $product->get_parent_id();
			}
		}

		$customer_info = parent::get_customer()->get_info();

		$comment_id = wp_insert_comment(
			array(
				'comment_post_ID'      => $request['product_id'],
				'comment_content'      => $request['review'],
				'comment_author'       => sprintf( '%s %s', $customer_info['first_name'], $customer_info['last_name'] ),
				'comment_author_email' => $customer_info['email'],
				'comment_author_IP'    => Utils\Main::get_client_ip(),
				'comment_parent'       => $request['reply'],
				'user_id'              => get_current_user_id(),
			)
		);

		if ( false === $comment_id ) {
			return new \WP_Error( 'review_failed_create', __( 'Creating product review failed.', 'woocommerce' ), array( 'status' => 400 ) );
		} else {
			update_comment_meta( $comment_id, 'rating', $request['rating'] );
			wp_set_comment_status( $comment_id, 'hold' );
		}

		return ( new Product_Reviews() )->get_callback( $request );
	}
}
