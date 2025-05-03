<?php
/**
 * REST API Response: Checkout
 *
 * This file defines the `Checkout` class, which handles the REST API response
 * for processing the checkout. It performs the checkout process, creates an order,
 * and returns the order details through a callback.
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
 * Checkout class handles the REST API response for processing the checkout.
 *
 * It handles the checkout process, creates an order, and returns the order details
 * through a callback.
 */
final class Checkout extends \YD\REST_API\Response {

	/**
	 * Callback method for processing the checkout.
	 *
	 * This method performs the checkout process by purchasing the items from the
	 * customer's cart and creating an order. If the order creation is successful,
	 * it fetches the order details. If there is an error, it returns the error.
	 *
	 * @param \WP_REST_Request|null $request The request object containing the checkout parameters.
	 *
	 * @return array|\WP_Error The order details or an error if the order could not be created.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$order = parent::get_customer()->get_checkout()->purchase( $request->get_params() );

		if ( $order instanceof \WP_Error ) {
			return $order;
		}

		$order_request             = new \WP_REST_Request();
		$order_request['order_id'] = $order->get_id();

		return ( new Customer_Order() )->get_callback( $order_request );
	}
}
