<?php
/**
 * REST API Response: Announcement Handler
 *
 * This file defines the `Announcement` class, which retrieves both global and user-specific announcements
 * via the mobile app's REST API. It fetches global data from options and user-specific data from the user object.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Data_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Announcement class handles the REST API response for fetching announcements.
 *
 * It retrieves global and user-specific announcements from the system. The global announcement is stored as
 * an option in WordPress, while the user-specific announcement is fetched based on the logged-in user.
 */
final class Announcement extends \YD\REST_API\Response {

	/**
	 * Callback method for handling the REST API request and returning the announcement data.
	 *
	 * This method returns an array containing two types of announcements:
	 * - 'global': The announcement for all users, retrieved from the system settings.
	 * - 'user': The announcement specifically for the logged-in user.
	 *
	 * @param \WP_REST_Request|null $request The request object.
	 *
	 * @return array The response data containing global and user-specific announcements.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		return array(
			'message' => array(
				// phpcs:ignore Universal.Operators.DisallowShortTernary
				'global' => Data_Manager::decode( get_option( 'yd_announcement_data' ) ) ?: false,
				'user'   => $this->get_user()->get_announcement(),
			),
		);
	}
}
