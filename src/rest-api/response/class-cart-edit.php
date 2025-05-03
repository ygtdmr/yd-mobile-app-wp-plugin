<?php
/**
 * REST API Response: Cart Edit
 *
 * This file defines the `Cart_Edit` class, which handles the REST API response
 * for editing the shopping cart. It allows adding/removing items, applying/removing
 * coupons, and selecting shipping rates based on the provided request data.
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
 * Cart_Edit class handles the REST API response for editing the shopping cart.
 *
 * It allows adding/removing items, applying/removing coupons, and selecting shipping rates
 * based on the provided request data.
 */
final class Cart_Edit extends \YD\REST_API\Response {

	/**
	 * Constructor for Cart_Edit class.
	 * Initializes the error group for cart-related errors.
	 */
	public function __construct() {
		parent::__construct();
		$this->error_group['cart_errors'] = array();
	}

	/**
	 * Callback method for handling the cart edit request and updating the cart data.
	 *
	 * This method processes the cart items, applies or removes coupons, and updates the
	 * shipping rates based on the request. It returns the updated cart information after
	 * performing the requested actions.
	 *
	 * @param \WP_REST_Request|null $request The request object containing cart modifications.
	 *
	 * @return array|\WP_Error The updated cart information or an error response.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$cart = parent::get_customer()->get_cart();

		foreach ( $request['items'] as $index => $item ) {
			if ( Utils\WCK::is_support() ) {
				$_POST['wck']      = $item['fields'] ?? array();
				$_POST['quantity'] = $item['quantity'];
			}
			$status = $cart->edit( $item );
			if ( $status instanceof \WP_Error ) {
				parent::add_error( $status, 'cart', 'cart-item-' . ( $item['key'] ?? $item['product_id'] ) );
			}
		}

		if ( ! empty( $request['coupon_add'] ) ) {
			$status = $cart->apply_coupon( $request['coupon_add'] );
			if ( $status instanceof \WP_Error ) {
				return $status;
			}
		} elseif ( ! empty( $request['coupon_remove'] ) ) {
			$cart->remove_coupon( $request['coupon_remove'] );
		}

		if ( ! empty( $request['shipping'] ) ) {
			$cart->select_shipping_rate( $request['shipping']['package_id'], $request['shipping']['rate_id'] );
		}

		return ( new Cart_Info() )->get_callback();
	}
}
