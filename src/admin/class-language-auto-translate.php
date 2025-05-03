<?php
/**
 * Language_Auto_Translate: Manages the automatic translation process for the mobile app.
 *
 * This class handles the settings for auto-translation, including triggering the translation task,
 * updating the translation status, and managing the stored state data in WordPress options.
 * The auto-translation process is controlled by settings that specify whether to translate only draft strings
 * and which locales to translate, if specified.
 *
 * @package YD\Mobile_App
 * @subpackage Admin
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin;

use YD\Task;
use YD\Utils;
use YD\Data_Manager;
use YD\Mobile_App\Admin\Language_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Language_Auto_Translate class manages the automatic translation process.
 *
 * This class handles settings related to auto-translation, triggers the translation task,
 * and manages its runtime status and state data stored in options.
 */
final class Language_Auto_Translate {

	/**
	 * Default configuration for auto-translation settings.
	 *
	 * - only_draft: Whether to translate only draft strings.
	 * - selected_locales: An array of locale codes to limit translation. Empty means all locales.
	 * - is_translating: Current translation status.
	 */
	const DEFAULT_DATA = array(
		'only_draft'       => true,
		'selected_locales' => array(),
		'is_translating'   => false,
	);

	/**
	 * Checks if the translation process is currently running.
	 *
	 * @return bool True if translating, false otherwise.
	 */
	public static function is_translating(): bool {
		return self::get_data()['is_translating'];
	}

	/**
	 * Updates the translating status in the stored data.
	 *
	 * @param bool $is_translating True to start, false to stop.
	 *
	 * @return void
	 */
	public static function set_translating( bool $is_translating ) {
		$data                   = self::get_data();
		$data['is_translating'] = $is_translating;
		self::save_data( $data );
	}

	/**
	 * Retrieves stored auto-translation data from WordPress options.
	 *
	 * @return array The current translation configuration.
	 */
	public static function get_data(): array {
		wp_cache_delete( 'yd_auto_translate', 'options' );
		return json_decode( get_option( 'yd_auto_translate', wp_json_encode( self::DEFAULT_DATA ) ), true );
	}

	/**
	 * Saves auto-translation configuration to WordPress options.
	 *
	 * @param array $data The data to be saved.
	 *
	 * @return void
	 */
	public static function save_data( array $data ) {
		update_option( 'yd_auto_translate', wp_json_encode( $data ), false );
	}

	/**
	 * Triggers the background task to start translation.
	 *
	 * @return void
	 */
	public static function start_translating() {
		Task::run_once( 'translate' );
	}

	/**
	 * Stops the translation process and clears status.
	 *
	 * @return void
	 */
	public static function stop_translating() {
		self::set_translating_status( false );
		Task::stop( 'translate' );
	}

	/**
	 * Gets the current status of the translation task.
	 *
	 * @return array Status information.
	 */
	public static function get_translating_status(): array {
		return Utils\Admin::get_action_status( 'auto-translate' );
	}

	/**
	 * Sets the status of the translation task.
	 *
	 * @param array|bool $status The status data or false to clear it.
	 *
	 * @return void
	 */
	public static function set_translating_status( array|bool $status ) {
		Utils\Admin::set_action_status( 'auto-translate', $status );
	}
}
