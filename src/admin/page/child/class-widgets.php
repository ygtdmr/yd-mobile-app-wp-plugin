<?php
/**
 * Admin Page: Widgets
 *
 * This file defines the `Widgets` class, which registers the Widgets admin page for the
 * mobile app interface. It provides menu access and controls visibility and capability settings.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page;

defined( 'ABSPATH' ) || exit;

/**
 * The Widgets page allows the admin to manage the widgets for the mobile app.
 * It provides the ability to access the widget management section in WordPress.
 */
final class Widgets extends \YD\Admin\Page {

	/**
	 * Retrieves the title for the widgets page.
	 *
	 * @return string The title for the page.
	 */
	protected function get_title(): string {
		return __( 'Widgets', 'yd-mobile-app' );
	}

	/**
	 * Retrieves the menu title for the widgets page.
	 *
	 * @return string The menu title.
	 */
	protected function get_menu_title(): string {
		return __( 'Widgets', 'yd-mobile-app' );
	}

	/**
	 * Retrieves the slug for the widgets page.
	 *
	 * @return string The page slug.
	 */
	protected function get_slug(): string {
		return 'edit.php?post_type=yd_widget';
	}

	/**
	 * Retrieves the capability required to access the widgets page.
	 *
	 * @return string The capability required.
	 */
	protected function get_capability(): string {
		return 'yd_manage_mobile_app';
	}

	/**
	 * Retrieves the parent slug for the widgets page.
	 *
	 * @return string The parent slug.
	 */
	protected function get_parent_slug(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Determines whether the page is independent (not part of the menu).
	 *
	 * @return bool True if the page is independent.
	 */
	protected function is_independent(): bool {
		return true;
	}
}
