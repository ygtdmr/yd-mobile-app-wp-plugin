<?php
/**
 * Product Review Submission for Mobile App
 *
 * This file defines the `Product_Review_Add` class, which handles the request to add a review for a specific product
 * via the REST API. It allows users to add reviews, including a rating, for a product.
 * The request requires authentication and uses a PUT method to submit the review data.
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
 * Product_Review_Add class handles the request to add a review for a specific product
 * via the REST API.
 *
 * This class allows a user to add a review, including a rating, for a product. The request requires authentication
 * and uses a PUT method to submit the review data.
 */
final class Product_Review_Add extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns true, indicating that authentication is required to add a review for a product.
	 *
	 * @return bool True, as authentication is required.
	 */
	public function is_authentication(): bool {
		return true;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products/(?P<product_id>\d+)/reviews' endpoint, which allows submitting a review
	 * for a specific product identified by its product ID.
	 *
	 * @return string The endpoint URL for adding a product review.
	 */
	public function get_endpoint(): string {
		return 'products/(?P<product_id>\d+)/reviews';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'PUT', indicating that the request will update or add a review for a product.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the arguments required for submitting a review:
	 * - 'rating' (required): an integer between 1 and 5 representing the product rating.
	 * - 'review' (required): a string containing the review text.
	 * - 'reply' (optional): an integer indicating the parent review being replied to (defaults to 0).
	 *
	 * @return array The options for the request, including arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'rating' => array(
					'type'     => 'integer',
					'minimum'  => 1,
					'maximum'  => 5,
					'required' => true,
				),
				'review' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'required'          => true,
				),
				'reply'  => array(
					'type'    => 'integer',
					'default' => 0,
				),
			),
		);
	}
}
