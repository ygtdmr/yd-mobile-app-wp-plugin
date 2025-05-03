<?php
/**
 * Language_Draft_Manager: Manages draft translation entries for review or temporary storage.
 *
 * This class is responsible for storing untranslated strings temporarily in draft files,
 * allowing them to be reviewed, edited, or deleted before being committed to the main language files.
 * It supports adding, editing, and deleting draft translations, as well as saving and retrieving them from a file.
 *
 * @package YD
 * @subpackage Mobile_App\Admin
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin;

use YD\Mobile_App\Admin\Language_Manager;
use YD\Data_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Language_Draft_Manager class manages draft translation entries for review or temporary storage.
 *
 * This class is responsible for storing untranslated strings temporarily,
 * allowing them to be reviewed, edited, or deleted before being committed to the main language files.
 */
final class Language_Draft_Manager {

	/**
	 * File path where draft translations are stored.
	 */
	const FILE = Language_Manager::PATH . Language_Manager::DOMAIN . '-draft';

	/**
	 * Holds the list of draft default texts (untranslated strings).
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Constructor loads all draft translations into memory.
	 */
	public function __construct() {
		$this->data = self::get_all_translates();
	}

	/**
	 * Save current draft translations to file.
	 * If no data exists, deletes the draft file.
	 *
	 * @return void
	 */
	public function save() {
		if ( ! empty( $this->data ) ) {
			Data_Manager::write_file(
				self::FILE,
				Data_Manager::encode( $this->data )
			);
		} else {
			self::delete_all_translates();
		}
	}

	/**
	 * Get all draft translations from file.
	 *
	 * @return array List of default texts.
	 */
	public static function get_all_translates(): array {
		return Data_Manager::decode( Data_Manager::read_file( self::FILE ) );
	}

	/**
	 * Delete all draft translations.
	 *
	 * @return void
	 */
	public static function delete_all_translates() {
		wp_delete_file( self::FILE );
	}

	/**
	 * Check if a default text is already in draft translations.
	 *
	 * @param string $default_text The original string.
	 *
	 * @return bool True if it exists in the draft.
	 */
	public function has_translate( string $default_text ): bool {
		return in_array( $default_text, $this->get_all_translates(), true );
	}

	/**
	 * Add a new default text to draft translations.
	 *
	 * @param string $default_text The original string to add.
	 *
	 * @return void
	 */
	public function add_translate( string $default_text ) {
		if ( ! $this->has_translate( $default_text ) ) {
			array_push( $this->data, $default_text );
		}
	}

	/**
	 * Edit an existing default text in the draft translations.
	 *
	 * @param string $default_text The current text to be edited.
	 * @param string $new_default_text The new text to replace it.
	 *
	 * @return void
	 */
	public function edit_translate( string $default_text, string $new_default_text ) {
		if ( $this->has_translate( $default_text ) ) {
			$this->data = array_map(
				function ( $default_text_in_data ) use ( $default_text, $new_default_text ) {
					if ( $default_text === $default_text_in_data ) {
						return $new_default_text;
					}
					return $default_text_in_data;
				},
				$this->data
			);
		}
	}

	/**
	 * Delete a default text from the draft translations.
	 *
	 * @param string $default_text The text to be removed.
	 *
	 * @return void
	 */
	public function delete_translate( string $default_text ) {
		if ( $this->has_translate( $default_text ) ) {
			$this->data = array_filter(
				$this->data,
				function ( $default_text_in_data ) use ( $default_text ) {
					return $default_text !== $default_text_in_data;
				}
			);
		}
	}
}
