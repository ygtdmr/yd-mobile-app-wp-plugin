<?php
/**
 * The Auto_Translate class handles the automatic translation process
 * for the mobile app, including starting, stopping, and configuring
 * the translation settings.
 *
 * This class provides functionality to manage the entire lifecycle
 * of automatic translation, including verifying the translation data,
 * initiating the translation process, and handling form submissions.
 * It also allows configuring which languages to translate and whether
 * to only translate drafts.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page\Action
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page\Action;

use YD\Utils;
use YD\Data_Manager;
use YD\Admin\Page\View;
use YD\Admin\Page;
use YD\Mobile_App\Admin\Language_Auto_Translate;
use YD\Mobile_App\Admin\Language_Draft_Manager;
use YD\Mobile_App\Admin\Language_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * The Auto_Translate class handles the automatic translation process
 * for the mobile app, including starting, stopping, and configuring
 * the translation settings.
 */
final class Auto_Translate {

	/**
	 * Determines if the draft translations are empty.
	 *
	 * @var bool
	 */
	private $is_draft_translate_empty;

	/**
	 * Determines if the main translations are empty.
	 *
	 * @var bool
	 */
	private $is_translate_empty;

	/**
	 * Constructor for Auto_Translate class.
	 *
	 * Initializes translation state and checks for empty drafts and translations.
	 */
	public function __construct() {
		$this->is_draft_translate_empty = empty( Language_Draft_Manager::get_all_translates() );
		$this->is_translate_empty       = empty( Language_Manager::get_all_translates() );

		if ( $this->is_draft_translate_empty && $this->is_translate_empty ) {
			Page::redirect( 'language' );
		}

		Utils\Main::verify_nonce( 'auto-translate' );

		// phpcs:ignore WordPress.Security.NonceVerification
		$data = ! empty( $_POST ) ? ( new Data_Manager( $this->get_rules(), $_POST ) )->sanitize() : false;

		if ( false !== $data ) {
			Language_Auto_Translate::save_data( $data );

			if ( $data['is_translating'] ) {
				Language_Auto_Translate::start_translating();
			} else {
				Language_Auto_Translate::stop_translating();
				Page::redirect( 'language' );
			}
		}

		Page::enqueue_style( 'language-auto-translate.css' );
		?>
	<div class="wrap <?php echo( esc_attr( YD_MOBILE_APP ) ); ?> auto-translate">
		<?php
		if ( Language_Auto_Translate::is_translating() ) :
			$this->render_block_translating();
else :
	$this->render_block_form();
endif;
?>
	</div>
		<?php
	}

	/**
	 * Defines the rules for the incoming POST data.
	 *
	 * @return array The rules for validating and sanitizing the POST data.
	 */
	private function get_rules(): array {
		return array(
			'only_draft'       => array(
				'type'     => 'boolean',
				'required' => true,
				'default'  => Language_Auto_Translate::DEFAULT_DATA['only_draft'],
			),
			'selected_locales' => array(
				'type'       => 'array',
				'item_rules' => array(
					'type'   => 'enum',
					'values' => Utils\Main::get_accepted_locales(),
				),
				'required'   => true,
				'default'    => Language_Auto_Translate::DEFAULT_DATA['selected_locales'],
			),
			'is_translating'   => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Renders the form block for the auto-translate settings page.
	 *
	 * @return void
	 */
	private function render_block_form() {
		?>
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Auto Translate', 'yd-mobile-app' ); ?></h1>
	<hr class="wp-header-end">
	<form method="post" autocomplete="off">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php esc_html_e( 'Translate only draft', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Checkbox( 'only_draft', __( 'Translate only draft', 'yd-mobile-app' ) );
							$view->set_help_text( __( 'If enable this, translates only draft languages. Otherwise, translates all.', 'yd-mobile-app' ) );
							$view->set_id( 'only_draft' );
							$view->set_description( __( 'Ensures translates only draft languages.', 'yd-mobile-app' ) );
							$view->set_ignored( true );
							$view->set_disabled( $this->is_draft_translate_empty );
							$view->set_value( ! $this->is_draft_translate_empty );
							$view->render();
						?>
					</td>
				</tr>
				<tr>
					<th><label for="selected_locales"><?php esc_html_e( 'Translate selected languages', 'yd-mobile-app' ); ?></label></th>
					<td>
						<?php
							$view = new View\Selection( 'selected_locales', __( 'Enter language', 'yd-mobile-app' ) );
							$view->set_ajax_action_name( 'language-search' );
							$view->set_properties( array( 'only_supported' => true ) );
							$view->set_id( 'selected_languages' );
							$view->set_required( false );
							$view->set_value( Language_Auto_Translate::DEFAULT_DATA['selected_locales'] );
							$view->set_help_text( __( 'You can type keyword to search for supported language.', 'yd-mobile-app' ) );
							$view->set_description( __( 'If decided to only selected language translate, select one or more supported language.', 'yd-mobile-app' ) );
							$view->set_ignored( true );
							$view->render();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<button id="submit" type="submit" name="is_translating" value="1" class="button-primary"><?php esc_html_e( 'Start translate', 'yd-mobile-app' ); ?></button>
		</p>
	</form>
		<?php
	}

	/**
	 * Renders the block for the translation progress when translating.
	 *
	 * @return void
	 */
	private function render_block_translating() {
		Page::enqueue_script( 'language/auto-translate.js' );

		$status            = Language_Auto_Translate::get_translating_status();
		$status_percentage = ! empty( $status )
			? ( ( $status['size_translated_translates'] / $status['size_target_translates'] ) * 100 )
			: 0;
		$status_text       = ! empty( $status )
			? sprintf(
				' (%s/%s)',
				$status['size_translated_translates'],
				$status['size_target_translates']
			)
			: '';
		?>
		<script>window.yd_core.url.page.language = '<?php echo( esc_js( Page::get_url( 'language' ) ) ); ?>';</script>
		<form method="post" autocomplete="off" id="poststuff">
			<div class="stuffbox translating">
				<h2><?php esc_html_e( 'Auto translate is running', 'yd-mobile-app' ); ?></h2>
				<div class="inside">
					<div class="text-status">
						<span id="text">
						<?php
						esc_html_e( 'Translating', 'yd-mobile-app' );
						echo( esc_html( $status_text ) );
						?>
						</span>
						<span class="spinner is-active"></span>
					</div>
					<div class="progressbar"><div style="width: <?php echo( esc_attr( $status_percentage ) ); ?>%;"></div></div>
					<button id="submit" type="submit" name="is_translating" value="0" class="button-primary"><?php esc_html_e( 'Stop translate', 'yd-mobile-app' ); ?></button>
				</div>
			</div>
		</form>
		<?php
	}
}

?>