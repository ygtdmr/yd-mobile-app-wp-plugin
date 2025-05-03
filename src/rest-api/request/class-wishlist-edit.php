<?php
/**
 * Wishlist Edit Request for Mobile App
 *
 * This file defines the `Wishlist_Edit` class, which handles the request to edit a user's wishlist via the REST API.
 * This class allows users to add or remove products from their wishlist by sending a PUT request to the 'wishlist' endpoint.
 * Authentication is not required for this request, meaning it can be used by unauthenticated users.
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
 * Wishlist_Edit class handles the request to edit a user's wishlist.
 *
 * This class allows users to add or remove products from their wishlist by sending a PUT request to the 'wishlist' endpoint.
 * Authentication is not required for this request, meaning it can be used by unauthenticated users.
 */
final class Wishlist_Edit extends \YD\REST_API\Request {

	/**
	 * Determines whether authentication is required for the request.
	 *
	 * This method returns false, as authentication is not required to edit the wishlist.
	 *
	 * @return bool False, as authentication is not required for editing the wishlist.
	 */
	public function is_authentication(): bool {
		return false;
	}

	/**
	 * Retrieves the endpoint for the request.
	 *
	 * This method returns the 'wishlist' endpoint, which is used to edit the user's wishlist.
	 *
	 * @return string The endpoint URL for editing the wishlist.
	 */
	public function get_endpoint(): string {
		return 'wishlist';
	}

	/**
	 * Retrieves the HTTP method for the request.
	 *
	 * This method returns 'PUT', as the request to edit the wishlist uses the PUT method.
	 *
	 * @return string The HTTP method for the request.
	 */
	public function get_method(): string {
		return 'PUT';
	}

	/**
	 * Retrieves any additional options for the request.
	 *
	 * This method returns an array of arguments required for editing the wishlist. These include:
	 * - items: an array of objects representing the items in the wishlist. Each item has a 'product_id' and a flag to indicate if it should be removed.
	 *
	 * @return array The options for the request, including the wishlist edit fields.
	 */
	public function get_options(): array {
		return array(
			'args' => array(
				'items' => array(
					'required' => true,
					'type'     => 'array',
					'minItems' => 1,
					'items'    => array(
						'type'       => 'object',
						'properties' => array(
							'is_remove'  => array( 'type' => 'boolean' ),
							'product_id' => array(
								'type'              => 'integer',
								'sanitize_callback' => 'sanitize_text_field',
								'required'          => true,
							),
						),
					),
				),
			),
		);
	}
}
