<?php
/**
 * REST API Response: Wishlist_Info
 *
 * This file defines the `Wishlist_Info` class, which handles retrieving information
 * about a customer's wishlist via the REST API. This class fetches the current items
 * in the authenticated user's wishlist and returns them in the response.
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
 * Wishlist_Info class handles retrieving information about a customer's wishlist via the REST API.
 *
 * This class fetches the current items in the authenticated user's wishlist and returns them in the response.
 */
final class Wishlist_Info extends \YD\REST_API\Response {

	/**
	 * Callback method to retrieve the current wishlist items.
	 *
	 * This method gets the wishlist of the authenticated customer and returns
	 * an array containing the items currently in the wishlist.
	 *
	 * @param \WP_REST_Request|null $request The request object. Not used in this method, defaults to null.
	 *
	 * @return array An associative array containing the wishlist items.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$wishlist = parent::get_customer()->get_wishlist();

		return array(
			'items' => $wishlist->get_items(),
		);
	}
}
