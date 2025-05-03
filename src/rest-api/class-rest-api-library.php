<?php
/**
 * REST API Library for Mobile App
 *
 * This file defines the `REST_API_Library` class, which handles the registration and configuration
 * of the REST API routes for the mobile app. It dynamically loads the necessary routes for mobile
 * app functionalities, including user login, product info, order management, and more. It also integrates
 * with WooCommerce and other modules as needed.
 *
 * @package YD\Mobile_App
 * @subpackage Library
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Library;

use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * REST_API_Library class handles the registration and configuration of the REST API routes for the mobile app.
 *
 * It dynamically loads the necessary routes for mobile app functionalities, including user login, product info,
 * order management, and more. It also integrates with WooCommerce and other modules as needed.
 */
final class REST_API_Library extends \YD\Library {

	/**
	 * Returns the directory where this class is located.
	 *
	 * @return string The directory path.
	 */
	protected function get_dir(): string {
		return __DIR__;
	}

	/**
	 * Returns an array of locations where the API request and response files are located.
	 *
	 * These locations are used for including the relevant API request and response logic.
	 *
	 * @return array List of location paths.
	 */
	protected function get_locations(): array {
		return array(
			'request/*',
			'response/*',
		);
	}

	/**
	 * Returns an array of locations where the API request and response files are located.
	 *
	 * These locations are used for including the relevant API request and response logic.
	 *
	 * @return array List of location paths.
	 */
	private function get_routes(): array {
		$routes = array(
			'Home',
			'User_Login',
			'User_Logout',
			'User_Register',
			'User_Info',
			'User_Edit',
			'Announcement',
			'Page',
			'Posts',
			'Post_Info',
			'Post_Comments',
			'Post_Comment_Add',
			'Post_Categories',
		);

		if ( Utils\WC::is_support() ) {
			array_push(
				$routes,
				'Customer_Orders',
				'Customer_Order',
				'Customer_Order_Messages',
				'Customer_Order_Message_Send',
				'Cart_Info',
				'Cart_Edit',
				'Checkout',
				'Products',
				'Product_Info',
				'Product_Categories',
				'Info_Address',
				'Info_Bank_Accounts',
			);

			if ( Utils\WC::is_review_enabled() ) {
				array_push( $routes, 'Product_Reviews', 'Product_Review_Add' );
			}

			if ( Utils\WCK::is_support() ) {
				array_push( $routes, 'Product_Price_Calculate' );
			}

			if ( Utils\WC\Customer\Wishlist::is_support() ) {
				array_push( $routes, 'Wishlist_Info', 'Wishlist_Edit' );
			}
		}

		return $routes;
	}

	/**
	 * Loads and initializes the REST API for the mobile app.
	 *
	 * This method registers the mobile app's REST API routes and makes them available for use.
	 *
	 * @return void
	 */
	protected function on_load() {
		new \YD\REST_API( 'mobile-app/v1', $this->get_routes() );
	}
}
