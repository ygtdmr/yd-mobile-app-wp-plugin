<?php
/**
 * Language: Handles language translations
 *
 * This file defines the `Language` class, responsible for managing language translations.
 * It allows the removal of all translations, and handles the addition, editing, and deletion of translations
 * for different locales in the mobile app.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Action
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Action;

use YD\Utils;
use YD\Mobile_App\Admin\Language_Manager;
use YD\Mobile_App\Admin\Language_Draft_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Language class handles language translations, including adding, editing, and deleting translations.
 */
final class Language {

	/**
	 * Constructor for the Language class. Handles the removal of all translations or processes changes to translations.
	 *
	 * @param array $data The data array containing actions and translation information.
	 */
	public function __construct( array $data ) {
		if ( ! empty( $data['remove_all'] ) ) {
			Language_Manager::delete_all_translates();
			Language_Draft_Manager::delete_all_translates();
		} else {
			$accepted_locales = Utils\Main::get_accepted_locales();

			foreach ( $accepted_locales as $locale ) {
				if ( empty( $data['removed_items'] ) && empty( $data['changed_items'] ) ) {
					return;
				}

				$manager       = new Language_Manager( $locale );
				$draft_manager = new Language_Draft_Manager();

				if ( ! empty( $data['removed_items'] ) ) {
					foreach ( $data['removed_items'] as $default_text ) {
						if ( $draft_manager->has_translate( $default_text ) ) {
							$draft_manager->delete_translate( $default_text );
							continue;
						}
						if ( $manager->has_translate( $default_text ) ) {
							$manager->delete_translate( $default_text );
						}
					}
				}

				if ( ! empty( $data['changed_items'] ) ) {
					foreach ( $data['changed_items'] as $default_text => $props ) {
						$is_draft = empty(
							array_filter(
								$props,
								function ( $prop_key ) use ( $accepted_locales ) {
									return in_array( $prop_key, $accepted_locales, true );
								},
								ARRAY_FILTER_USE_KEY
							)
						) && empty( Language_Manager::get_all_translates()[ $default_text ] );

						if ( $is_draft ) {
							if ( $manager->has_translate( $default_text ) ) {
								$manager->delete_translate( $default_text );
							}
							if ( ! empty( $props['new_default_text'] ) ) {
								$draft_manager->edit_translate( $default_text, $props['new_default_text'] );
							} else {
								$draft_manager->add_translate( $default_text );
							}
							continue;
						}

						if ( ! empty( $props['removed_targets'] ) ) {
							if ( in_array( $locale, $props['removed_targets'], true ) ) {
								$manager->delete_translate( $default_text );
								continue;
							}
						}

						$is_new_translate = ! ( empty( $props['is_new'] ) && $manager->has_translate( $default_text ) );

						if ( $is_new_translate ) {
							if ( ! in_array( $locale, array_keys( $props ), true ) ) {
								continue;
							}

							if ( $draft_manager->has_translate( $default_text ) ) {
								$draft_manager->delete_translate( $default_text );
							}
							$manager->add_translate( $props['new_default_text'] ?? $default_text, $props[ $locale ] );
						} else {
							$manager->edit_translate( $default_text, $props[ $locale ] ?? null, $props['new_default_text'] ?? null );
						}
					}
				}

				$manager->save();
				$draft_manager->save();
			}
		}
	}
}
