<?php
/**
 * This file defines the `Mobile_App` class, which is responsible for initializing and managing the
 * mobile app functionalities within the WordPress environment. It handles library loading, permission-based
 * admin features, and full cleanup on uninstallation.
 *
 * @package YD
 * @subpackage Mobile_App
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD;

use YD\Mobile_App\Library;

defined( 'ABSPATH' ) || exit;

/**
 * Mobile_App class initializes and manages the mobile app functionalities within the WordPress environment.
 *
 * This class loads the necessary libraries for posts, widgets, REST API, and admin functionalities. It also handles
 * the uninstallation process by cleaning up data related to the mobile app, such as post types, user capabilities,
 * user meta, and options. The class provides an initialization mechanism that checks user permissions before
 * loading the admin-specific functionality.
 */
final class Mobile_App {

	/**
	 * Mobile_App constructor initializes the necessary libraries and sets up admin functionalities if applicable.
	 *
	 * This constructor loads various libraries for posts, widgets, and REST API functionalities. If the current user
	 * has the required capabilities ('yd_manage_mobile_app') and is in the admin area, it also loads the admin library.
	 *
	 * @return void
	 */
	public function __construct() {
		require_once __DIR__ . '/post/class-post-library.php';
		require_once __DIR__ . '/widget/class-widget-library.php';
		require_once __DIR__ . '/rest-api/class-rest-api-library.php';

		new Library\Post_Library();
		new Library\Widget_Library();
		new Library\REST_API_Library();

		// phpcs:ignore WordPress.WP.Capabilities
		if ( is_admin() && current_user_can( 'yd_manage_mobile_app' ) ) {
			require_once __DIR__ . '/admin/class-admin-library.php';
			new Library\Admin_Library();
		}
	}

	/**
	 * Uninstalls the mobile app by removing post types, capabilities, user meta data, and options.
	 *
	 * This method performs cleanup tasks, including:
	 * - Deleting posts of type 'yd_widget'.
	 * - Removing capabilities related to the mobile app from all roles.
	 * - Deleting specific user meta data related to the mobile app and announcements.
	 * - Deleting relevant options from the database.
	 * - Flushing the WordPress cache.
	 *
	 * @return void
	 */
	public static function uninstall() {
		global $wpdb, $wp_roles;

		$capabilities = array( 'yd_manage_mobile_app' );
		$post_types   = array( 'yd_widget' );

		foreach ( $post_types as $post_type ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->posts} WHERE post_type = %s",
					esc_sql( $post_type )
				)
			);
		}

		foreach ( $wp_roles->get_names() as $slug => $name ) {
			foreach ( $capabilities as $cap ) {
				$wp_roles->remove_cap( $slug, $cap );
			}
		}

		foreach ( get_users() as $user ) {
			delete_user_meta( $user->ID, 'yd_mobile_app' );
			delete_user_meta( $user->ID, 'yd_announcement_data' );
		}

		delete_option( 'yd_announcement_data' );
		delete_option( 'yd-mobile-app_accepted_locales' );

		wp_cache_flush();
	}
}
