<?php
/**
 * REST API Response: Wishlist Edit Handler
 *
 * This file defines the `Wishlist_Edit` class, which allows the editing of wishlist items
 * for the authenticated user through the mobile app's REST API.
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
 * Wishlist_Edit class handles editing of a customer's wishlist via the REST API.
 *
 * This class allows for the modification of wishlist items for the authenticated user,
 * processing each item individually and handling errors if any occur.
 */
final class Wishlist_Edit extends \YD\REST_API\Response {

	/**
	 * Constructor method for Wishlist_Edit.
	 * This method initializes the error group array for wishlist errors.
	 */
	public function __construct() {
		parent::__construct();
		$this->error_group['wishlist_errors'] = array();
	}

	/**
	 * Callback method to handle the request for editing wishlist items.
	 *
	 * This method processes the provided wishlist items, editing them one by one. If an error occurs
	 * while editing an item, it is captured and added to the error group.
	 *
	 * @param \WP_REST_Request|null $request The request object containing wishlist item data.
	 *
	 * @return mixed The response from the Wishlist_Info callback.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$wishlist = parent::get_customer()->get_wishlist();

		foreach ( $request['items'] as $index => $item ) {
			$status = $wishlist->edit( $item );

			if ( $status instanceof \WP_Error ) {
				parent::add_error( $status, 'wishlist', 'wishlist-item-' . $item['product_id'] );
			}
		}
		return ( new Wishlist_Info() )->get_callback();
	}
}
