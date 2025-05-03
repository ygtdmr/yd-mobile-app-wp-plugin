<?php
/**
 * Language: Handles AJAX requests for language operations such as fetching and updating language translations.
 *
 * This class processes AJAX requests to fetch and update language translations, including handling both GET and POST
 * requests for language data, validating incoming data, and returning the appropriate translations or language items.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Ajax
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Ajax;

use YD\Utils;
use YD\Data_Manager;
use YD\Mobile_App\Admin\Action;
use YD\Mobile_App\Admin\Page;
use YD\Mobile_App\Admin\Language_Manager;
use YD\Mobile_App\Admin\Language_Draft_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Handles AJAX requests for language operations like fetching and updating language translations.
 */
final class Language extends \YD\Admin\Ajax {

	/**
	 * Flag to determine if the request is a POST request.
	 *
	 * @var bool
	 */
	private $is_action;

	/**
	 * Constructor to initialize the flag for POST requests.
	 */
	public function __construct() {
		parent::__construct();
		if ( ! empty( $_SERVER['REQUEST_METHOD'] ) ) {
			$this->is_action = 'POST' === $_SERVER['REQUEST_METHOD'];
		} else {
			$this->is_action = false;
		}
	}

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'language';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		$accepted_locales = Utils\Main::get_accepted_locales();
		$rule_text        = array(
			'type'            => 'string',
			'sanitize_length' => Page\Language::TEXT_MAX_LENGTH,
			'pattern_replace' => array(
				array(
					'pattern'     => '/\\\\/',
					'replacement' => '',
				),
			),
		);
		return $this->is_action
		? array(
			'changed_items' => array(
				'type'  => 'object',
				'rules' => array(
					'*' => array(
						'type'     => 'object',
						'key_rule' => $rule_text,
						'rules'    => array(
							'is_new'           => array( 'type' => 'boolean' ),
							'new_default_text' => $rule_text,
							'removed_targets'  => array(
								'type'       => 'array',
								'item_rules' => array( 'type' => 'string' ),
							),
						)
						+ array_combine(
							$accepted_locales,
							array_map(
								function () use ( $rule_text ) {
									return $rule_text; },
								$accepted_locales
							)
						),
					),
				),
			),
			'removed_items' => array(
				'type'       => 'array',
				'item_rules' => array( 'type' => 'string' ),
			),
			'remove_all'    => array( 'type' => 'boolean' ),
		)
		: array(
			'target_locale' => array( 'type' => 'string' ),
			'default_text'  => $rule_text,
			'page'          => array(
				'type'     => 'integer',
				'required' => true,
				'default'  => 1,
			),
		);
	}

	/**
	 * Returns the nonce value for the AJAX request.
	 *
	 * @return string Nonce value.
	 */
	protected function get_wp_nonce(): string {
		return 'language';
	}

	/**
	 * Handles the AJAX request by performing different actions based on the request method (GET or POST).
	 *
	 * @return void
	 */
	protected function get_action() {
		$result = array();
		if ( $this->is_action ) {
			new Action\Language( $this->data );
		} else {
			$with_data = ! empty( $this->data['target_locale'] ) && ! empty( $this->data['default_text'] );
			$result    = $with_data ? $this->get_language_text() : $this->get_language_items();
		}
		parent::send_success( $result );
	}

	/**
	 * Fetches the language items (translations) for the specified page.
	 *
	 * @return array List of language items (translations).
	 */
	private function get_language_items(): array {
		$item_count_per_page = 16;
		$page                = $this->data['page'];

		if ( ! $page ) {
			$page = 1;
		}

		$translates       = Language_Manager::get_all_translates();
		$draft_translates = Language_Draft_Manager::get_all_translates();
		$draft_translates = array_combine( $draft_translates, array_fill( 0, count( $draft_translates ), array() ) );

		$all_translates = array_merge( $draft_translates, $translates );

		uasort(
			$all_translates,
			function ( $a, $b ) {
				return ( count( $a ) > count( $b ) ) ? 1 : -1;
			}
		);

		return array_slice( $all_translates, ( $page - 1 ) * $item_count_per_page, $item_count_per_page );
	}

	/**
	 * Fetches the translated text for the specified target locale and default text.
	 *
	 * @return string Translated text.
	 */
	private function get_language_text(): string {
		return ( new Language_Manager( $this->data['target_locale'] ) )->get_translate( $this->data['default_text'] );
	}
}
