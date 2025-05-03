<?php
/**
 * Auto_Translate_Status: Handles AJAX requests for the auto-translate status.
 *
 * This class processes AJAX requests to retrieve the current status of the auto-translation process. It checks whether
 * the translation is ongoing and provides details about the translated and target translation sizes.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Ajax
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Ajax;

use YD\Mobile_App\Admin\Language_Auto_Translate;

defined( 'ABSPATH' ) || exit;

/**
 * Class to handle AJAX requests for the auto-translate status.
 * This class returns the current status of the auto-translation process.
 */
final class Auto_Translate_Status extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'auto-translate-status';
	}

	/**
	 * Handles the AJAX request to get the auto-translate status.
	 * It retrieves the translation status and sends it as a response.
	 *
	 * @return void
	 */
	protected function get_action() {
		$status = Language_Auto_Translate::get_translating_status();
		parent::send_success(
			array(
				'is_translating' => Language_Auto_Translate::is_translating(),
				'size'           => ! empty( $status )
					? array(
						'translated_translates' => $status['size_translated_translates'],
						'target_translates'     => $status['size_target_translates'],
					)
					: array(),
			)
		);
	}
}
