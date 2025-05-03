<?php
/**
 * Language Manager for Mobile App
 *
 * This class handles the management of PO/MO translation files for the mobile app.
 * It provides functionality for loading, editing, saving, deleting translations
 * for different locales, and utility methods for retrieving language data.
 *
 * @package YD\Mobile_App
 * @subpackage Admin
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin;

use YD\Utils;
use YD\Data_Manager;

use Gettext\Translation;
use Gettext\Translations;
use Gettext\Loader\PoLoader;
use Gettext\Generator\PoGenerator;
use Gettext\Generator\MoGenerator;

defined( 'ABSPATH' ) || exit;

/**
 * Language_Manager class manages the PO/MO translation files for the mobile app.
 *
 * This class provides functionality for loading, editing, saving, and deleting
 * translations for different locales, along with utility methods for retrieving
 * language data.
 */
final class Language_Manager {

	/**
	 * Holds the translations loaded from the PO file.
	 *
	 * @var Translations
	 */
	private $translations;

	/**
	 * The locale currently being managed (e.g. 'en_US').
	 *
	 * @var string
	 */
	private $locale;

	/**
	 * Directory path where translation files are stored.
	 */
	const PATH = WP_CONTENT_DIR . '/languages/plugins/';

	/**
	 * The translation domain used for PO/MO file names.
	 */
	const DOMAIN = YD_MOBILE_APP . '-language';

	/**
	 * Constructor loads translation file for the given locale.
	 *
	 * @param string $locale The locale identifier (e.g. en_US).
	 */
	public function __construct( string $locale ) {
		if ( ! is_dir( self::PATH ) ) {
			wp_mkdir_p( self::PATH, 0777 );
		}

		$file_content = Data_Manager::read_file( self::get_file_name( $locale, 'po' ) ) ?? '';

		// phpcs:ignore WordPress.PHP.NoSilencedErrors
		$this->translations = @( new PoLoader() )->loadString( $file_content );
		$this->locale       = $locale;
		$this->translations->setDomain( self::DOMAIN );
	}

	/**
	 * Save the current translations to .po and .mo files.
	 *
	 * @return void
	 */
	public function save() {
		$file_name = self::get_file_name( $this->locale );
		Data_Manager::write_file( $file_name . '.po', ( new PoGenerator() )->generateString( $this->translations ) );
		Data_Manager::write_file( $file_name . '.mo', ( new MoGenerator() )->generateString( $this->translations ) );
	}

	/**
	 * Check if a default text has a translation.
	 *
	 * @param string $default_text The original string.
	 *
	 * @return bool True if translation exists.
	 */
	public function has_translate( string $default_text ): bool {
		return boolval( $this->translations->find( null, $default_text ) );
	}

	/**
	 * Get the translated version of a default text.
	 *
	 * @param string $default_text The original string.
	 *
	 * @return string The translated text or empty string.
	 */
	public function get_translate( string $default_text ): string {
		$translation = $this->get_translation( $default_text );
		if ( $translation ) {
			return $translation->getTranslation();
		}
		return '';
	}

	/**
	 * Add a new translation entry.
	 *
	 * @param string $default_text The original string.
	 * @param string $target The translated string.
	 *
	 * @return void
	 */
	public function add_translate( string $default_text, string $target ) {
		$translation = Translation::create( '', $default_text );
		$translation->translate( $target );
		$this->translations->add( $translation );
	}

	/**
	 * Edit an existing translation.
	 *
	 * @param string      $default_text The original string to edit.
	 * @param string|null $target The new translated string.
	 * @param string|null $new_default_text Optional new default text.
	 *
	 * @return void
	 */
	public function edit_translate( string $default_text, ?string $target, ?string $new_default_text = null ) {
		if ( null === $target ) {
			$target = $this->get_translate( $default_text );
		}

		$this->delete_translate( $default_text );

		if ( ! empty( $target ) ) {
			$this->add_translate( $new_default_text ?? $default_text, $target );
		}
	}

	/**
	 * Delete a translation by default text.
	 *
	 * @param string $default_text The original string to remove.
	 *
	 * @return void
	 */
	public function delete_translate( string $default_text ) {
		$translation = $this->get_translation( $default_text );
		if ( ! is_null( $translation ) ) {
			$this->translations->remove( $translation );
		}
	}

	/**
	 * Get a translation object for a default text.
	 *
	 * @param string $default_text The original string.
	 *
	 * @return Translation|null The translation object or null.
	 */
	private function get_translation( string $default_text ): ?Translation {
		return $this->translations->find( null, $default_text );
	}

	/**
	 * Get a list of all language display names.
	 *
	 * @param bool $is_translated Whether to return translated names.
	 *
	 * @return array List of locales and their names.
	 */
	public static function get_all_display_names( bool $is_translated = true ): array {
		$language_names = Utils\Language::get_language_names();

		if ( $is_translated ) {
			foreach ( $language_names as $locale => $name ) {
				$language_names[ $locale ] = Utils\Language::translate_language_name( $name );
			}
		}

		return $language_names;
	}

	/**
	 * Get a list of all locale identifiers.
	 *
	 * @return array List of locale codes.
	 */
	public static function get_all_locales(): array {
		return array_keys( self::get_all_display_names() );
	}

	/**
	 * Get a map of translated strings and the locales that provide them.
	 *
	 * @return array Associative array with default texts and locales.
	 */
	public static function get_all_translates(): array {
		$translates = array();

		foreach ( Utils\Main::get_accepted_locales() as $locale ) {
			$translations = self::load_translations( $locale );

			foreach ( $translations as $translation ) {
				if ( empty( $translation->getTranslation() ) ) {
					continue;
				}

				$default_text = $translation->getOriginal();

				if ( in_array( $default_text, array_keys( $translates ), true ) ) {
					$translates[ $default_text ][] = $locale;
				} else {
					$translates[ $default_text ] = array( $locale );
				}
			}
		}

		return $translates;
	}

	/**
	 * Delete all stored translation files.
	 *
	 * @return void
	 */
	public static function delete_all_translates() {
		foreach ( scandir( self::PATH ) as $file_name ) {
			if ( strpos( $file_name, self::DOMAIN . '-' ) !== false ) {
				wp_delete_file( self::PATH . $file_name );
			}
		}
	}

	/**
	 * Build the file name for a translation file.
	 *
	 * @param string      $locale The locale code.
	 * @param string|null $file_extension The file extension (e.g. po, mo).
	 *
	 * @return string Full file path.
	 */
	private static function get_file_name( string $locale, ?string $file_extension = null ): string {
		return ( self::PATH . self::DOMAIN . '-' . $locale ) . ( empty( $file_extension ) ? '' : '.' . $file_extension );
	}

	/**
	 * Load translations for a given locale.
	 *
	 * @param string $locale The locale code.
	 *
	 * @return Translations Loaded translations object.
	 */
	private static function load_translations( string $locale ): Translations {
		return ( new PoLoader() )->loadString(
			Data_Manager::read_file(
				self::get_file_name( $locale, 'po' )
			) ?? ''
		);
	}
}
