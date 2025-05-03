<?php
/**
 * Language_Search: Handles AJAX requests for language search.
 *
 * This class processes AJAX requests to search and filter available languages based on the provided criteria,
 * including the keyword, supported languages, and specific locale options. It returns the matching languages
 * based on these filters.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Ajax
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Ajax;

defined( 'ABSPATH' ) || exit;

use YD\Utils;
use YD\Mobile_App\Admin\Language_Manager;

/**
 * Class for handling AJAX requests for language search.
 * It performs searching and filtering of available languages based on the given criteria.
 */
final class Language_Search extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'language-search';
	}

	/**
	 * Returns the rules for validating incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'keyword'                => array(
				'type' => 'string',
			),
			'value'                  => array(
				'type'       => 'array',
				'item_rules' => array(
					'type'   => 'enum',
					'values' => Language_Manager::get_all_locales(),
				),
			),
			'only_supported'         => array( 'type' => 'boolean' ),
			'include_current_locale' => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Compares two words, case-insensitively, to check if one is part of the other.
	 *
	 * @param string $a First word.
	 * @param string $b Second word.
	 *
	 * @return bool True if the second word is found in the first word.
	 */
	private function compare_words( $a, $b ): bool {
		$a = mb_convert_case( $a, MB_CASE_LOWER_SIMPLE, 'UTF-8' );
		$b = mb_convert_case( $b, MB_CASE_LOWER_SIMPLE, 'UTF-8' );
		return strpos( $a, $b ) !== false;
	}

	/**
	 * Handles the AJAX request to search for languages.
	 * It filters languages based on the provided keyword, supported languages, and locale criteria.
	 *
	 * @return void
	 */
	protected function get_action() {
		$result        = array();
		$display_names = Language_Manager::get_all_display_names();

		if ( ! empty( $this->data['only_supported'] ) ) {
			$display_names = array_filter(
				$display_names,
				function ( $key ) {
					if ( get_locale() === $key ) {
						return ! empty( $this->data['include_current_locale'] );
					}
					return in_array( $key, Utils\Main::get_accepted_locales(), true );
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		$display_names = array_filter(
			$display_names,
			function ( $display_name, $key ) {
				if ( ! empty( $this->data['value'] ) ) {
					return in_array( $key, $this->data['value'] ?? '', true );
				} elseif ( ! empty( $this->data['keyword'] ) ) {
					return $this->compare_words( $display_name, $this->data['keyword'] ?? '' );
				}
				return false;
			},
			ARRAY_FILTER_USE_BOTH
		);

		foreach ( $display_names as $key => $value ) {
			$is_searching = ! empty( $this->data['keyword'] ) && count( $result ) >= 8;
			if ( $is_searching ) {
				continue;
			}
			$result[] = array(
				'id'   => $key,
				'name' => $value,
			);
		}

		parent::send_success( $result );
	}
}
