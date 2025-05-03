<?php
/**
 * Cart Editing for Mobile App
 *
 * This file defines the `Cart_Edit` class, which handles the request to edit a customer's cart
 * via the REST API. It provides methods for adding or removing items, applying or removing coupons,
 * and updating shipping details in the cart.
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
 * Cart_Edit class handles the request to edit a customer's cart via the REST API.
 *
 * This class provides methods for modifying the cart, such as adding or removing items,
 * applying or removing coupons, and updating shipping details.
 */
final class Cart_Edit extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, indicating that authentication is not needed
	 * to modify the cart.
	 *
	 * @return bool False, as authentication is not required.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'cart' endpoint for the REST API request.
	 *
	 * @return string The endpoint URL.
	 */
	public function get_endpoint(): string {
		return 'cart';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'PUT', indicating that the request will update the cart.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns the arguments required to modify the cart, including:
	 * - Items: List of cart items to be modified.
	 * - Coupon Add: Coupon code to add to the cart.
	 * - Coupon Remove: Coupon code to remove from the cart.
	 * - Shipping: Shipping details for the cart.
	 *
	 * @return array The options and arguments for the request.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'items'         => array(
					'type'              => 'array',
					'default'           => array(),
					'items'             => array(
						'type'       => 'object',
						'properties' => array(
							'key'        => array(
								'type'              => 'string',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'product_id' => array( 'type' => 'integer' ),
							'quantity'   => array(
								'type'     => 'integer',
								'minimum'  => 0,
								'required' => true,
							),
							'fields'     => array( 'type' => 'object' ),
						),
					),
					'validate_callback' => function ( array $items ) {
						foreach ( $items as $item ) {
							if ( ! isset( $item['key'] ) && ! isset( $item['product_id'] ) ) {
								return false;
							}
						}
						return true;
					},
				),
				'coupon_add'    => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'coupon_remove' => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'shipping'      => array(
					'type'       => 'object',
					'properties' => array(
						'package_id' => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
						'rate_id'    => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
					),
				),
			),
		);
	}
}
