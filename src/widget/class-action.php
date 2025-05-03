<?php
/**
 * Action Widget for Mobile App
 *
 * This file defines the `Action` class, which handles the definition and management of actions
 * that a widget can perform. Actions include targeting specific pages, posts, products, and custom data,
 * as well as managing the parameters associated with each action. The class also provides methods
 * for initializing actions from data and formatting them for use in the REST API.
 *
 * @package YD\Mobile_App
 * @subpackage Widget
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Widget;

use YD\Data_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Action class handles the definition and management of widget actions.
 *
 * This class defines different types of actions that a widget can perform, such as targeting specific pages,
 * posts, or custom data. It provides methods to initialize actions from data, retrieve action parameters,
 * and get action data formatted for REST API.
 */
final class Action {

	// Constants representing the different action targets.
	const TARGET_PAGE         = 'page';
	const TARGET_PRODUCT      = 'product';
	const TARGET_PRODUCT_LIST = 'product-list';
	const TARGET_POST         = 'post';
	const TARGET_POST_LIST    = 'post-list';
	const TARGET_CUSTOM       = 'custom';

	/**
	 * The target for the action (e.g., page, post).
	 *
	 * @var string
	 */
	private $target;

	/**
	 * The parameters associated with the action.
	 *
	 * @var array
	 */
	private $params;

	/**
	 * Action constructor initializes the action with a target and parameters.
	 *
	 * @param string $target The target for the action (e.g., page, post).
	 * @param array  $params Parameters associated with the action.
	 */
	public function __construct( string $target, array $params ) {
		$this->target = $target;
		$this->params = $params;
	}

	/**
	 * Initializes an Action instance from the provided data.
	 *
	 * @param array $action The action data containing the target and parameters.
	 *
	 * @return Action The initialized Action instance.
	 */
	public static function init_from_data( array $action ): Action {
		return new Action( $action['target'] ?? '', $action['params'] ?? array() );
	}

	/**
	 * Gets the target for the action.
	 *
	 * @return string The action target (e.g., page, post).
	 */
	public function get_target() {
		return $this->target;
	}

	/**
	 * Gets the parameters for the action.
	 *
	 * @return array The parameters associated with the action.
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * Prepares the action data for REST API response.
	 *
	 * @return array The action data formatted for REST API, or false if the target or parameters are missing.
	 */
	public function get_data_for_rest(): array {
		if ( empty( $this->target ) || empty( $this->params ) ) {
			return array(
				'action' => false,
			);
		}

		return array(
			'action' => array(
				'target' => $this->target,
				'params' => $this->params,
			),
		);
	}

	/**
	 * Gets the rules for validating action data.
	 *
	 * This method returns the rules for action data, including the valid targets and associated parameter rules
	 * for each target type (e.g., page, post, product).
	 *
	 * @return array The action data validation rules.
	 */
	public static function get_data_rules(): array {
		return array(
			'action' => array(
				'type'  => 'object',
				'rules' => array(
					'target' => array(
						'type'   => 'enum',
						'values' => array(
							self::TARGET_PAGE,
							self::TARGET_POST,
							self::TARGET_POST_LIST,
							self::TARGET_PRODUCT,
							self::TARGET_PRODUCT_LIST,
							self::TARGET_CUSTOM,
						),
					),
					'params' => array(
						'type'            => 'object',
						'rules_from_keys' => array(
							'target_key' => 'target',
							'rules'      => array(
								self::TARGET_PAGE         => array(
									'id' => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'string' ),
										'default'    => array(),
										'required'   => true,
									),
								),
								self::TARGET_POST         => array(
									'id' => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'integer' ),
										'default'    => array(),
										'required'   => true,
									),
								),
								self::TARGET_POST_LIST    => array(
									'search'   => array(
										'type'            => 'string',
										'sanitize_length' => 256,
									),
									'category' => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'integer' ),
									),
									'tag'      => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'integer' ),
									),
								),
								self::TARGET_PRODUCT      => array(
									'id' => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'integer' ),
										'default'    => array(),
										'required'   => true,
									),
								),
								self::TARGET_PRODUCT_LIST => array(
									'search'    => array(
										'type'            => 'string',
										'sanitize_length' => 256,
									),
									'category'  => array(
										'type'       => 'array',
										'item_rules' => array( 'type' => 'integer' ),
									),
									'min_price' => array( 'sanitize_callback' => '\YD\Utils\WC::sanitize_price' ),
									'max_price' => array( 'sanitize_callback' => '\YD\Utils\WC::sanitize_price' ),
									'on_sale'   => array( 'type' => 'boolean' ),
								),
								self::TARGET_CUSTOM       => array(
									'*' => array(
										'key_rule' => array( 'type' => 'string' ),
										'type'     => 'string',
									),
								),
							),
						),
					),
				),
			),
		);
	}
}
