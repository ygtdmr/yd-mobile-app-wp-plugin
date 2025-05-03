<?php
/**
 * YD Mobile App
 *
 * @package YD
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 *
 * Plugin Name: Mobile App Management
 * Requires Plugins: yd-core
 * Description: Configures some contents in mobile app.
 * Author: Yigit Demir
 * Author URI: https://github.com/ygtdmr
 * Version: 1.0.0
 * Text Domain: yd-mobile-app
 * Domain Path: /languages
 * Requires at least: 6.8
 * Requires PHP: 8.0
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 9.8
 * WC tested up to: 9.8.3
 */

( defined( 'ABSPATH' ) && defined( 'YD_CORE' ) ) || exit;

/**
 * 'yd_mobile_app_update_slug' function sets global variables related to mobile app update.
 *
 * This function assigns the current plugin information to global variables and defines the mobile app class name.
 *
 * @global string $YD_CURRENT_PLUGIN Holds the name of the current plugin.
 * @global string $YD_CURRENT_PLUGIN_CLASS_NAME Holds the mobile app class name.
 *
 * @return void
 */
function yd_mobile_app_update_slug() {
	$GLOBALS['YD_CURRENT_PLUGIN']            = YD_MOBILE_APP;
	$GLOBALS['YD_CURRENT_PLUGIN_CLASS_NAME'] = 'Mobile_App';
}

/**
 * 'yd_mobile_app_before_init' function initializes the necessary actions and setups for the mobile app integration.
 *
 * This function performs several tasks before WooCommerce initializes:
 * - Declares compatibility for remote logging and custom order tables.
 * - Loads the WooCommerce cart.
 * - Defines a constant for the mobile app if it isn't already defined.
 * - Calls the function to update the mobile app slug.
 * - Requires necessary vendor files and task library.
 *
 * After all setups, the function instantiates the task library for the mobile app.
 *
 * @return void
 */
function yd_mobile_app_before_init() {
	add_action(
		'before_woocommerce_init',
		function () {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'remote_logging', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	);

	add_action(
		'woocommerce_init',
		function () {
			wc_load_cart();
		}
	);

	if ( ! defined( 'YD_MOBILE_APP' ) ) {
		define( 'YD_MOBILE_APP', 'yd-mobile-app' );
	}

	yd_mobile_app_update_slug();

	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/src/task/class-task-library.php';

	new \YD\Mobile_App\Library\Task_Library();
}

yd_mobile_app_before_init();

/**
 * 'yd_mobile_app_rest_api_on_permission_callback' function checks the user’s authentication and sets the current user for REST API permission.
 *
 * This function validates the current user either by retrieving their user ID from the WordPress session or by
 * checking the authentication cookie. If a valid user ID is found, the function sets the current user for further
 * REST API permission checks.
 *
 * @return void
 */
function yd_mobile_app_rest_api_on_permission_callback() {
	// phpcs:ignore Universal.Operators.DisallowShortTernary
	$user_id = wp_get_current_user()->ID ?: wp_validate_auth_cookie( '', 'logged_in' );
	if ( $user_id ) {
		wp_set_current_user( $user_id );
	}
}

add_action( 'yd_rest_api_on_permission_callback', 'yd_mobile_app_rest_api_on_permission_callback' );

/**
 * 'yd_mobile_app_init' function initializes the mobile app integration by setting up necessary components.
 *
 * This function performs several tasks during the mobile app initialization:
 * - Updates the mobile app slug by calling the `yd_mobile_app_update_slug` function.
 * - Loads the plugin's text domain for localization.
 * - Includes the main mobile app class file and initializes the mobile app class.
 *
 * @return void
 */
function yd_mobile_app_init() {
	yd_mobile_app_update_slug();

	load_plugin_textdomain( YD_MOBILE_APP, false, plugin_basename( __DIR__ ) . '/languages' );

	require_once __DIR__ . '/src/class-mobile-app.php';
	new \YD\Mobile_App();
}

add_action( 'init', 'yd_mobile_app_init' );

/**
 * 'yd_mobile_app_admin_init' function sets up the required capabilities for administrators in the mobile app.
 *
 * This function performs the following tasks:
 * - Updates the mobile app slug by calling the `yd_mobile_app_update_slug` function.
 * - Retrieves the 'administrator' role and defines necessary capabilities.
 * - Adds the capabilities required for managing the mobile app and specific post types (e.g., 'yd_widget') to the administrator role.
 *
 * @return void
 */
function yd_mobile_app_admin_init() {
	yd_mobile_app_update_slug();

	$role = get_role( 'administrator' );

	$capabilities = array( 'yd_manage_mobile_app' );
	$post_types   = array( 'yd_widget' );

	foreach ( $post_types as $post_type ) {
		$capabilities += array_values(
			(array) get_post_type_object( $post_type )->cap
		);
	}

	foreach ( $capabilities as $cap ) {
		if ( ! $role->has_cap( $cap ) ) {
			$role->add_cap( $cap, true );
		}
	}
}

add_action( 'admin_init', 'yd_mobile_app_admin_init' );
