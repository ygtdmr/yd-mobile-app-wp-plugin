<?php
/**
 * Product Price Calculation Request for Mobile App
 *
 * This file defines the `Product_Price_Calculate` class, which handles the request to calculate
 * the price of a specific product based on the given quantity via the REST API. The class allows
 * calculating the price of a product by submitting a POST request with the quantity. Authentication
 * is not required for this request.
 *
 * @package YD\Mobile_App
 * @subpackage Request
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Request;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Price_Calculate class handles the request to calculate the price of a specific product
 * based on the given quantity via the REST API.
 *
 * This class allows calculating the price of a product by submitting a POST request with the quantity.
 * The request does not require authentication.
 */
final class Product_Price_Calculate extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not required
	 * to calculate the product price.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'products/(?P<product_id>\d+)/calculate' endpoint for the REST API request,
	 * which calculates the price of a product based on the provided quantity.
	 *
	 * @return string The endpoint URL for calculating the product price.
	 */
	public function get_endpoint(): string {
		return 'products/(?P<product_id>\d+)/calculate';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'POST', indicating that the request will submit data to calculate the price.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'POST';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an array of arguments that define the data expected in the request.
	 * Specifically, the 'quantity' argument must be an integer and is required, while 'fields' is an object.
	 *
	 * @return array The options for the request, including arguments.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'quantity' => array(
					'type'     => 'integer',
					'minimum'  => 1,
					'required' => true,
				),
				'fields'   => array( 'type' => 'object' ),
			),
		);
	}
}
