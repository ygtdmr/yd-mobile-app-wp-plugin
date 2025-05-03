<?php
/**
 * Admin_Library: Mobile App Admin Bootstrap
 *
 * This file defines the `Admin_Library` class, which is responsible for bootstrapping the admin-related
 * components of the Mobile App module. It extends the base `YD\Library` class and defines the directory
 * structure and autoload locations for various admin files such as pages, actions, AJAX handlers, and stats.
 * It also initializes the main Admin controller for the module.
 *
 * @package YD
 * @subpackage Mobile_App\Library
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Library;

defined( 'ABSPATH' ) || exit;

/**
 * Admin_Library class responsible for bootstrapping the admin-related components of the Mobile App module.
 *
 * This class extends the base YD\Library class and defines the directory structure
 * and autoload locations for various admin files such as pages, actions, AJAX handlers, and stats.
 * It also initializes the main Admin controller for the module.
 */
final class Admin_Library extends \YD\Library {

	/**
	 * Returns the directory path where the admin components are located.
	 *
	 * @return string
	 */
	protected function get_dir(): string {
		return __DIR__;
	}

	/**
	 * Returns an array of glob-style patterns that define the autoload locations
	 * for various admin components like classes, actions, pages, and stats.
	 *
	 * @return array
	 */
	protected function get_locations(): array {
		return array(
			'class-*',
			'ajax/*',
			'action/*',
			'page/view/*',
			'page/view/block/*',
			'page/view/input/*',
			'page/action/*',
			'page/child/*',
			'page/*',
			'stat/*',
		);
	}

	/**
	 * Called when the library is loaded. Instantiates the main Admin controller.
	 *
	 * @return void
	 */
	protected function on_load() {
		new \YD\Mobile_App\Admin();
	}
}
