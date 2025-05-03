<?php
/**
 * Admin Page: Settings
 *
 * This file defines the `Settings` class, which manages the configuration of accepted languages
 * for the mobile app within the WordPress admin. It includes options for language selection,
 * validation, and saving preferences.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page;

use YD\Utils;
use YD\Admin\Page\View;
use YD\Mobile_App\Admin\Language_Manager;
use YD\Mobile_App\Admin\Language_Auto_Translate;

defined( 'ABSPATH' ) || exit;

/**
 * Settings page allows configuring the supported languages for the mobile app.
 * It provides options to manage accepted languages and save changes.
 */
final class Settings extends \YD\Admin\Page {

	/**
	 * Retrieves the title for the settings page.
	 *
	 * @return string The title for the page.
	 */
	protected function get_title(): string {
		return __( 'Settings' );
	}

	/**
	 * Retrieves the menu title for the settings page.
	 *
	 * @return string The menu title.
	 */
	protected function get_menu_title(): string {
		return __( 'Settings' );
	}

	/**
	 * Retrieves the menu title for the settings page.
	 *
	 * @return string The menu title.
	 */
	protected function get_slug(): string {
		return 'settings';
	}

	/**
	 * Retrieves the capability required to access the settings page.
	 *
	 * @return string The capability required.
	 */
	protected function get_capability(): string {
		return 'yd_manage_mobile_app';
	}

	/**
	 * Retrieves the parent slug for the settings page.
	 *
	 * @return string The parent slug.
	 */
	protected function get_parent_slug(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Retrieves the rules for validating the submitted data.
	 *
	 * @return array The rules for the settings form data.
	 */
	protected function get_rules(): array {
		return array(
			'languages' => array(
				'type'       => 'array',
				'item_rules' => array(
					'type'    => 'enum',
					'values'  => Language_Manager::get_all_locales(),
					'default' => '',
				),
			),
		);
	}

	/**
	 * Retrieves the nonce for the settings page.
	 *
	 * @return string The nonce.
	 */
	protected function get_wp_nonce(): string {
		return 'settings';
	}

	/**
	 * Handles the form submission and updates the accepted languages.
	 *
	 * If no languages are selected, the option is deleted. Otherwise, the languages are saved.
	 *
	 * @return void
	 */
	protected function do_action_post() {
		if ( ! Language_Auto_Translate::is_translating() ) {
			if ( empty( $this->data['languages'] ) ) {
				delete_option( YD_MOBILE_APP . '_accepted_locales' );
			} else {
				update_option( YD_MOBILE_APP . '_accepted_locales', wp_json_encode( $this->data['languages'] ), false );
			}
		}
	}

	/**
	 * Renders the settings page content, including the form for selecting languages.
	 *
	 * @return void
	 */
	protected function callback() {
		?>
		<div class="wrap <?php echo( esc_attr( YD_MOBILE_APP ) ); ?>">
			<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form method="post" autocomplete="off">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'settings' ) ); ?>">
				<table class="form-table">
					<tbody>
						<?php if ( ! Language_Auto_Translate::is_translating() ) : ?>
						<tr>
							<th><label for="languages"><?php esc_html_e( 'Supported Languages', 'yd-mobile-app' ); ?></label></th>
							<td>
								<?php
									$view = new View\Selection( 'languages', 'Enter language' );
									$view->set_ajax_action_name( 'language-search' );
									$view->set_id( 'languages' );
									$view->set_required( false );
									$view->set_value( Utils\Main::get_accepted_locales() );
									$view->set_help_text( __( 'You can type keyword to search for language.', 'yd-mobile-app' ) );
									$view->set_description( __( 'If decided to app is multilingual, define supported languages. This requires for widget content etc.', 'yd-mobile-app' ) );
									$view->render();
								?>
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>">
				</p>
			</form>
		</div>
		<?php
	}
}

?>