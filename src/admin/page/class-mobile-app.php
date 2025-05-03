<?php
/**
 * Admin Page: Mobile App Management
 *
 * This file defines the `Mobile_App` class, which extends the base admin page functionality
 * to provide a WordPress dashboard interface for managing mobile app settings.
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
 * Mobile_App class defines the main admin page configuration
 * for managing the mobile app via WordPress admin dashboard.
 */
final class Mobile_App extends \YD\Admin\Page {

	/**
	 * Returns the title displayed on the page header.
	 *
	 * @return string
	 */
	protected function get_title(): string {
		return __( 'Mobile App', 'yd-mobile-app' );
	}

	/**
	 * Returns the title shown in the WordPress admin menu.
	 *
	 * @return string
	 */
	protected function get_menu_title(): string {
		return __( 'Mobile App', 'yd-mobile-app' );
	}

	/**
	 * Returns the slug used in the URL for this admin page.
	 *
	 * @return string
	 */
	protected function get_slug(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Returns the required capability to access this page.
	 *
	 * @return string
	 */
	protected function get_capability(): string {
		return 'yd_manage_mobile_app';
	}

	/**
	 * Returns the SVG icon as a data URI to be shown in the admin menu.
	 *
	 * @return string
	 */
	protected function get_icon_url(): string {
		return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjggMTI4Ij48cGF0aCBmaWxsPSIjQTdBQUFEIiBkPSJNODQuOCAwQzkzLjIgMCAxMDAgNy4yIDEwMCAxNnY5NmMwIDguOC02LjggMTYtMTUuMiAxNkg0My4yYy04LjQgMC0xNS4yLTcuMi0xNS4yLTE2VjE2YzAtOC44IDYuOC0xNiAxNS4yLTE2Wk02NC41IDEwMmE2LjUgNi41IDAgMSAwIDAgMTMgNi41IDYuNSAwIDAgMCAwLTEzWm01LjYtNjlINTljLS43IDAtMS4zLjUtMS40IDEuMmwtMSA3LjRjLTEuOC43LTMuMyAxLjYtNC44IDIuN0w0NSA0MS41Yy0uNi0uMi0xLjMgMC0xLjcuN2wtNS41IDkuNmMtLjQuNy0uMiAxLjQuMyAxLjhsNS45IDQuN2EyMi40IDIyLjQgMCAwIDAgMCA1LjRsLTYgNC43Yy0uNS40LS42IDEuMS0uMyAxLjhsNS41IDkuNmMuNC43IDEuMS45IDEuNy43bDctMi44YzEuNCAxIDMgMiA0LjYgMi43bDEgNy40Yy4yLjcuOCAxLjIgMS41IDEuMmgxMWMuOCAwIDEuMy0uNSAxLjQtMS4ybDEtNy40YzEuOC0uNyAzLjMtMS42IDQuOC0yLjdsNi45IDIuOGMuNi4yIDEuMyAwIDEuNy0uN2w1LjUtOS42Yy40LS43LjItMS40LS4zLTEuOGwtNi00LjdhMjIgMjIgMCAwIDAgMC01LjRsNS45LTQuN2MuNS0uNC43LTEuMS4zLTEuOGwtNS41LTkuNmMtLjQtLjctMS4xLS45LTEuNy0uN2wtNyAyLjhjLTEuNC0xLTMtMi00LjYtMi43bC0xLTcuNGMtLjItLjctLjctMS4yLTEuNC0xLjJabS01LjYgMTguMmM1LjQgMCA5LjcgNC40IDkuNyA5LjhzLTQuMyA5LjgtOS43IDkuOGE5LjggOS44IDAgMCAxLTkuNy05LjhjMC01LjQgNC4zLTkuOCA5LjctOS44Wk03MyAxNEg1NC44YTMgMyAwIDAgMC0yLjggMyAzIDMgMCAwIDAgMyAzaDE4LjJhMyAzIDAgMCAwIDIuOC0zIDMgMyAwIDAgMC0zLTNaIi8+PC9zdmc+';
	}
}
