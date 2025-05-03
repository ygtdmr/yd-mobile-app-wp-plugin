<?php
/**
 * Product Reviews Retrieval for Mobile App
 *
 * This file defines the `Product_Reviews` class, which handles the request to retrieve reviews
 * for a specific product via the REST API. It fetches reviews based on the product ID and does not
 * require authentication.
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
 * Product_Reviews class handles the request to retrieve reviews for a specific product
 * via the REST API.
 *
 * This class allows retrieving a list of reviews for a given product. The request does not require authentication
 * and uses a GET method to retrieve review data.
 */
final class Product_Reviews extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required to retrieve the reviews
	 * for a product.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products/(?P<product_id>\d+)/reviews' endpoint, which retrieves all reviews
	 * for a specific product identified by its product ID.
	 *
	 * @return string The endpoint URL for retrieving product reviews.
	 */
	public function get_endpoint(): string {
		return 'products/(?P<product_id>\d+)/reviews';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'GET', indicating that the request will retrieve reviews for a product.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method defines the arguments for retrieving reviews:
	 * - 'page' (optional): an integer for pagination, defaulting to page 1.
	 * - 'orderby' (optional): a string to order reviews by, either 'date' or 'rating' (defaults to 'date').
	 * - 'order' (optional): a string to define the sorting order, either 'asc' or 'desc' (defaults to 'desc').
	 *
	 * @return array The options for the request, including arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'page'    => array(
					'type'    => 'integer',
					'default' => 1,
				),
				'orderby' => array(
					'type'    => 'string',
					'enum'    => array(
						'date',
						'rating',
					),
					'default' => 'date',
				),
				'order'   => array(
					'type'    => 'string',
					'enum'    => array(
						'asc',
						'desc',
					),
					'default' => 'asc',
				),
			),
		);
	}
}
