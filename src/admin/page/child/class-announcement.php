<?php
/**
 * Admin Page: Announcement
 *
 * This file defines the `Announcement` class, which handles the display and functionality
 * of notifications. It allows the administrator to create and publish notification messages
 * with customizable titles, texts, and actions for the mobile app.
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
use YD\Data_Manager;
use YD\Mobile_App\Widget\Action;

defined( 'ABSPATH' ) || exit;

/**
 * Announcement page handles the display and functionality of notifications.
 * It allows the administrator to create and publish notification messages
 * with customizable titles, texts, and actions for the mobile app.
 */
final class Announcement extends \YD\Admin\Page {

	/**
	 * Maximum allowed length for the notification title.
	 */
	const TITLE_MAX_LENGTH = 64;

	/**
	 * Maximum allowed length for the notification text.
	 */
	const TEXT_MAX_LENGTH = 512;

	/**
	 * Retrieves the title for the page.
	 *
	 * @return string The title for the page.
	 */
	protected function get_title(): string {
		return __( 'Notifications' );
	}

	/**
	 * Retrieves the menu title for the page.
	 *
	 * @return string The menu title.
	 */
	protected function get_menu_title(): string {
		return __( 'Notifications' );
	}

	/**
	 * Retrieves the slug for the page.
	 *
	 * @return string The page slug.
	 */
	protected function get_slug(): string {
		return 'announcement';
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
	 * Retrieves the rules for sanitizing the input data.
	 *
	 * @return array The rules for sanitization.
	 */
	protected function get_rules(): array {
		return array(
			'title' => array(
				'type'            => 'string',
				'sanitize_length' => self::TITLE_MAX_LENGTH,
			),
			'text'  => array(
				'type'            => 'string',
				'sanitize_length' => self::TEXT_MAX_LENGTH,
			),
		) + Action::get_data_rules();
	}

	/**
	 * Retrieves the nonce for the page.
	 *
	 * @return string The nonce value.
	 */
	protected function get_wp_nonce(): string {
		return 'announcement';
	}

	/**
	 * Retrieves a notice for the page.
	 *
	 * @return array The notice data.
	 */
	protected function get_notice(): array {
		return array(
			'message' => sprintf( '<strong>%s</strong>', __( 'Notice: Each press publish button, customer receives a new notification.', 'yd-mobile-app' ) ),
			'args'    => array(
				'type'        => 'warning',
				'dismissible' => false,
			),
		);
	}

	/**
	 * Handles the form submission when a notification is published.
	 *
	 * If no title or text is provided, the announcement data is deleted.
	 * Otherwise, the notification data is encoded and stored.
	 *
	 * @return void
	 */
	protected function do_action_post() {
		if ( empty( $this->data['title'] ) && empty( $this->data['text'] ) ) {
			delete_option( 'yd_announcement_data' );
		} else {
			$this->data = array( 'uuid' => wp_generate_uuid4() ) + $this->data;
			update_option( 'yd_announcement_data', Data_Manager::encode( $this->data ), false );
		}
	}

	/**
	 * Renders the page content for announcements.
	 *
	 * This includes a form to input the title, text, and action for the notification.
	 * The form data is pre-filled with the current notification data.
	 *
	 * @return void
	 */
	protected function callback() {
		self::enqueue_script( 'ui-input/selection-action.js' );
		$data = Data_Manager::decode( get_option( 'yd_announcement_data' ) );
		?>
		<div class="wrap <?php echo( esc_attr( YD_MOBILE_APP ) ); ?>">
			<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form method="post" autocomplete="off">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'announcement' ) ); ?>">
				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="title_input"><?php esc_html_e( 'Title' ); ?></label></th>
							<td>
								<?php
									$view = new View\Text( 'title' );
									$view->set_id( 'title' );
									$view->set_value( $data['title'] ?? null );
									$view->set_required( false );
									$view->set_help_text( __( 'Ensures appears title of notification.', 'yd-mobile-app' ) );
									$view->set_description( __( 'Enter title of notification.', 'yd-mobile-app' ) );
									$view->set_custom_attributes(
										array(
											'autocomplete' => 'off',
											'maxlength'    => self::TITLE_MAX_LENGTH,
										)
									);
									$view->render();
								?>
							</td>
						</tr>
						<tr>
							<th><label for="message_input"><?php esc_html_e( 'Text' ); ?></label></th>
							<td>
								<?php
									$view = new View\Textarea( 'text' );
									$view->set_id( 'text' );
									$view->set_value( $data['text'] ?? null );
									$view->set_required( false );
									$view->set_help_text( __( 'Ensures appears text of notification.', 'yd-mobile-app' ) );
									$view->set_description( __( 'Enter text of notification.', 'yd-mobile-app' ) );
									$view->set_custom_attributes(
										array(
											'autocomplete' => 'off',
											'maxlength'    => self::TEXT_MAX_LENGTH,
										)
									);
									$view->render();
								?>
							</td>
						</tr>
						<tr>
							<th><label for="action_input"><?php esc_html_e( 'Action' ); ?></label></th>
							<td>
								<?php
									$view = new View\Selection_Action();
									$view->set_value( $data['action'] ?? null );
									$view->set_required( false );
									$view->render();
								?>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Publish' ); ?>">
				</p>
			</form>
		</div>
		<?php
	}
}
?>
