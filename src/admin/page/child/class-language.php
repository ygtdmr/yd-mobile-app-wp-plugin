<?php
/**
 * Admin Page: Language
 *
 * This file defines the `Language` class, which allows the administrator to manage
 * translations of the app's text. It includes functionality for both manual and automatic
 * translation of text used on the front-end of the app.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page;

use YD\Utils;
use YD\Mobile_App\Admin\Language_Auto_Translate;

defined( 'ABSPATH' ) || exit;

/**
 * Language page allows the administrator to manage the translations of the app's text.
 * It includes functionality for manual and automatic translation of text used on the front-end.
 */
final class Language extends \YD\Admin\Page {

	/**
	 * Maximum allowed length for the language text.
	 */
	const TEXT_MAX_LENGTH = 512;

	/**
	 * Retrieves the title for the page.
	 *
	 * @return string The title for the page.
	 */
	protected function get_title(): string {
		return __( 'Language' );
	}

	/**
	 * Retrieves the menu title for the page.
	 *
	 * @return string The menu title.
	 */
	protected function get_menu_title(): string {
		return __( 'Language' );
	}

	/**
	 * Retrieves the slug for the page.
	 *
	 * @return string The page slug.
	 */
	protected function get_slug(): string {
		return 'language';
	}

	/**
	 * Retrieves the capability required to access the page.
	 *
	 * @return string The capability required.
	 */
	protected function get_capability(): string {
		return 'yd_manage_mobile_app';
	}

	/**
	 * Retrieves the parent slug for the page.
	 *
	 * @return string The parent slug.
	 */
	protected function get_parent_slug(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Retrieves the actions that can be performed on the page.
	 *
	 * @return array The available actions.
	 */
	protected function get_actions(): array {
		return array(
			'auto-translate' => Action\Auto_Translate::class,
		);
	}

	/**
	 * Retrieves a notice for the page.
	 *
	 * @return array The notice data.
	 */
	protected function get_notice(): array {
		if ( Language_Auto_Translate::is_translating() ) {
			$translating_status = Language_Auto_Translate::get_translating_status();
			if ( ! empty( $translating_status ) ) {
				return array(
					'message'              => sprintf(
						__( 'Application languages translating automatically.', 'yd-mobile-app' ) . ' (%s/%s)',
						$translating_status['size_translated_translates'],
						$translating_status['size_target_translates']
					),
					'args'                 => array(
						'type'        => 'info',
						'dismissible' => false,
					),
					'visible_all_page'     => true,
					'visible_current_page' => false,
				);
			}
		}
		return array();
	}

	/**
	 * Checks if the page is enabled based on accepted locales.
	 *
	 * @return bool True if the page is enabled, false otherwise.
	 */
	protected function is_enabled(): bool {
		return ! empty( Utils\Main::get_accepted_locales() );
	}

	/**
	 * Handles the page rendering and form submission for managing translations.
	 *
	 * If a translation process is ongoing, the page redirects to the auto-translate action.
	 * The page includes a form to input default and translated text values.
	 *
	 * @return void
	 */
	protected function callback() {
		if ( Language_Auto_Translate::is_translating() ) {
			parent::redirect_action( 'auto-translate', 'auto-translate' );
		}

		parent::enqueue_script( 'language/language.js' );
		parent::enqueue_style( 'language.css' );
		parent::enqueue_style( 'language-flags.css' );
		?>

		<div class="wrap <?php echo( esc_attr( YD_MOBILE_APP ) ); ?> language">
			<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<span class="page-title-action" data-action="new" tabindex="0" style="display:none;"><?php esc_html_e( 'Add new translate', 'yd-mobile-app' ); ?></span>
			<span class="page-title-action logo-google-translate" data-action="translate" tabindex="0" style="display:none;" onClick="window.location = '<?php echo( esc_attr( esc_js( parent::get_action_location( 'auto-translate', 'auto-translate' ) ) ) ); ?>';"><?php esc_html_e( 'Auto Translate', 'yd-mobile-app' ); ?></span>

			<form method="post" autocomplete="off">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'language' ) ); ?>">

				<p><?php esc_html_e( 'This page, fixes language of text for front side.', 'yd-mobile-app' ); ?></p>

				<div class="actions">
					<span class="button delete action" data-action="remove-all" tabindex="0"><?php esc_html_e( 'Clear List' ); ?></span><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>">
					<span class="spinner"></span>
				</div>

				<div class="edit">
					<span>
						<span class="spinner"></span>
						<textarea id="text-default" rows="4" placeholder="<?php esc_html_e( 'Default value', 'yd-mobile-app' ); ?>" disabled="disabled" maxlength="<?php echo( esc_attr( self::TEXT_MAX_LENGTH ) ); ?>"></textarea>
					</span>
					<span class="target">
						<span class="spinner"></span>
						<span class="language-tabs"></span>
						<textarea id="text-target" rows="4" placeholder="<?php esc_html_e( 'Enter value', 'yd-mobile-app' ); ?>" disabled="disabled" maxlength="<?php echo( esc_attr( self::TEXT_MAX_LENGTH ) ); ?>"></textarea>
					</span>
				</div>

				<span class="delete" data-action="remove" tabindex="0" style="visibility:hidden;"><?php esc_html_e( 'Remove' ); ?></span>

				<ul class="content-list"><span class="spinner is-active"></span></ul>
			</form>
		</div>
		<?php
	}
}
?>
