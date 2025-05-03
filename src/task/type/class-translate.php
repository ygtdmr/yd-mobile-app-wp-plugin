<?php
/**
 * Translate: Responsible for handling the automatic translation of language strings within the mobile app.
 *
 * This class interacts with external translation services (e.g., Google Translate) to translate text and manage
 * language drafts. It controls the translation process, including whether translations are draft-only or for all
 * available locales, and updates translation statuses as the process progresses.
 *
 * @package YD\Mobile_App
 * @subpackage Task
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Task;

use YD\Utils;
use YD\Data_Manager;
use YD\Mobile_App\Admin\Language_Manager;
use YD\Mobile_App\Admin\Language_Draft_Manager;
use YD\Mobile_App\Admin\Language_Auto_Translate;

use Stichoza\GoogleTranslate\GoogleTranslate;

defined( 'ABSPATH' ) || exit;

/**
 * Translate class is responsible for handling the automatic translation of language strings within the mobile app.
 *
 * This class interacts with external translation services (such as Google Translate) to translate text and manage language drafts.
 * It controls the translation process, including whether the translation is draft-only or for all available locales.
 */
final class Translate extends \YD\Task {

	/**
	 * Returns the task type as 'translate'.
	 *
	 * @return string The task type.
	 */
	protected function get_type(): string {
		return 'translate';
	}

	/**
	 * Returns the libraries that are required for the task.
	 *
	 * This method defines the libraries that the task depends on, such as the Data Manager and Language Managers.
	 *
	 * @return array The list of required libraries.
	 */
	protected function get_libraries(): array {
		return array(
			'class-data-manager'                  => YD_CORE,
			'utils/class-main'                    => YD_CORE,
			'admin/class-language-manager'        => YD_MOBILE_APP,
			'admin/class-language-draft-manager'  => YD_MOBILE_APP,
			'admin/class-language-auto-translate' => YD_MOBILE_APP,
		);
	}

	/**
	 * Executes the translation process using data from the Language Auto Translate settings.
	 *
	 * This method retrieves the settings and invokes the `run_translate` method to start the translation process.
	 *
	 * @return void
	 */
	protected function get_action() {
		$data = Language_Auto_Translate::get_data();

		$this->run_translate(
			$data['only_draft'],
			$data['selected_locales']
		);
	}

	/**
	 * Checks if a translation process is currently ongoing.
	 *
	 * @return bool True if translating, false otherwise.
	 */
	private function is_translating(): bool {
		return Language_Auto_Translate::is_translating();
	}

	/**
	 * Starts the translation process for the specified locales and draft settings.
	 *
	 * This method controls the entire translation workflow, including selecting locales, filtering translations,
	 * and interacting with the Google Translate API to translate default text.
	 * It also updates the translation status periodically.
	 *
	 * @param bool  $is_only_draft Whether to only translate drafts.
	 * @param array $selected_locales List of locales to translate to.
	 *
	 * @return void
	 */
	private function run_translate( bool $is_only_draft, array $selected_locales ) {
		set_time_limit( 0 );

		$target_locales = $selected_locales;
		if ( ! $target_locales ) {
			$target_locales = Utils\Main::get_accepted_locales();
		}

		$draft_translates = Language_Draft_Manager::get_all_translates();
		$draft_translates = array_combine( $draft_translates, array_fill( 0, count( $draft_translates ), array() ) );

		$target_translates = $is_only_draft ? $draft_translates : array_merge( $draft_translates, Language_Manager::get_all_translates() );
		$target_translates = array_filter(
			$target_translates,
			function ( $filled_languages ) use ( $target_locales ) {
				return ! ( count( array_intersect( $target_locales, $filled_languages ) ) === count( $target_locales ) );
			}
		);

		$translating_status         = Language_Auto_Translate::get_translating_status();
		$size_target_translates     = $translating_status['size_target_translates'] ?? count( array_keys( $target_translates ) );
		$size_translated_translates = $translating_status['size_translated_translates'] ?? 0;

		Language_Auto_Translate::set_translating_status(
			array(
				'size_target_translates'     => $size_target_translates,
				'size_translated_translates' => $size_translated_translates,
			)
		);

		$google_translate = new GoogleTranslate();

		foreach ( $target_translates as $default_text => $filled_languages ) {
			if ( ! $this->is_translating() ) {
				break;
			}

			$target_locales = array_filter(
				$target_locales,
				function ( $locale ) use ( $filled_languages ) {
					return ! in_array( $locale, $filled_languages, true );
				}
			);

			foreach ( $target_locales as $target_locale ) {
				if ( ! $this->is_translating() ) {
					break;
				}

				$source_language = explode( '_', get_locale() )[0];
				$target_language = explode( '_', $target_locale )[0];

				$google_translate->setSource( $source_language );
				$google_translate->setTarget( $target_language );

				try {
					$translated_text = $google_translate->translate( $default_text );
					$this->update_translate( $target_locale, $default_text, $translated_text );
					sleep( 1 );
				} catch ( \Exception $e ) {
					parent::log( $e->getMessage() );
				}
			}

			++$size_translated_translates;

			Language_Auto_Translate::set_translating_status(
				array(
					'size_target_translates'     => $size_target_translates,
					'size_translated_translates' => $size_translated_translates,
				)
			);
		}

		$is_translated_all = ( $size_target_translates === $size_translated_translates );

		if ( $is_translated_all ) {
			Language_Auto_Translate::set_translating( false );
		}

		if ( $is_translated_all || ! $this->is_translating() ) {
			Language_Auto_Translate::stop_translating();
		}
	}

	/**
	 * Updates the translation for a specific locale.
	 *
	 * This method updates the translated text in both the language manager and the draft manager.
	 *
	 * @param string $target_locale The target locale to update.
	 * @param string $default_text The original text to translate.
	 * @param string $translated_text The translated text.
	 *
	 * @return void
	 */
	private function update_translate( string $target_locale, string $default_text, string $translated_text ) {
		$language_manager = new Language_Manager( $target_locale );
		$language_manager->edit_translate( $default_text, $translated_text );
		$language_manager->save();

		$draft_manager = new Language_Draft_Manager();
		if ( $draft_manager->has_translate( $default_text ) ) {
			$draft_manager->delete_translate( $default_text );
			$draft_manager->save();
		}
	}
}
