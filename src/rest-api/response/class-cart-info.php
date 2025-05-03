<?php
/**
 * REST API Response: Cart Information
 *
 * This file defines the `Cart_Info` class, which provides a REST API response handler
 * for retrieving the current customer's cart information. It includes cart items, totals,
 * cross-sells, and any errors present in the cart.
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
 * Cart_Info class handles the REST API response for retrieving cart information.
 *
 * It fetches the items, totals, cross-sells, and errors from the current customer's cart
 * and returns them in the response.
 */
final class Cart_Info extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving the cart information.
	 *
	 * This method fetches the items in the cart, the total amounts, any cross-sells,
	 * and any errors that may be present in the cart. It returns this data as part of
	 * the response.
	 *
	 * @param \WP_REST_Request|null $request The request object for fetching the cart details.
	 *
	 * @return array The cart information including items, totals, cross-sells, and errors.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$cart = parent::get_customer()->get_cart();

		return array(
			'items'       => $cart->get_items( $request ),
			'totals'      => $cart->get_totals(),
			'cross_sells' => $cart->get_cross_sells(),
			'errors'      => $cart->get_errors()->get_error_messages(),
		);
	}
}
