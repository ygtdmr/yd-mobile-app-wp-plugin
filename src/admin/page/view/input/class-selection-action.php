<?php
/**
 * Custom Input Component: Selection Action
 *
 * This file defines the `Selection_Action` class, a dynamic input field used within the
 * admin panel interface to configure action-based behaviors such as redirection or custom logic.
 * It supports multiple target types (pages, posts, products) with dynamic parameters.
 *
 * @package YD
 * @subpackage Admin\Page\View
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Admin\Page\View;

use YD\Utils;
use YD\Mobile_App\Widget\Action;

defined( 'ABSPATH' ) || exit;

/**
 * Selection_Action class defines a dynamic input field
 * for selecting an action type and related parameters.
 *
 * It is used to configure redirection or action behaviors
 * in the admin panel widgets.
 */
final class Selection_Action extends Input {

	/**
	 * Constructor for the Selection_Action class.
	 *
	 * Initializes the component with the specified data name and sets help text and description.
	 *
	 * @param string $data_name The name of the data (optional).
	 */
	public function __construct( string $data_name = '' ) {
		$this->set_data_name( $data_name );

		parent::set_help_text( __( 'Firstly, select "Target" value for what want to do. If desired open an page, target is should be selected value of "Product" or "Product List". If desired do other process, "Target" value is should be selected "Custom".', 'yd-mobile-app' ) );
		parent::set_description( __( 'Ensures open a page or do other process.', 'yd-mobile-app' ) );
	}

	/**
	 * Returns the full data name of the field, optionally nested under a parent name.
	 *
	 * @return string
	 */
	public function get_data_name(): string {
		$data_name = parent::get_data_name();
		return empty( $data_name ) ? 'action' : $data_name . '[action]';
	}

	/**
	 * Returns the unique input name used for this field.
	 *
	 * @return string
	 */
	protected function get_name(): string {
		return 'selection-action';
	}

	/**
	 * Returns data attributes and configuration for the input field.
	 * This defines the available targets and dynamic parameters based on target type.
	 *
	 * @return array
	 */
	protected function get_data_attributes(): array {
		return array(
			'config'           => array(
				'data_name' => $this->get_data_name(),
			),
			'dropdown-options' => array(
				'targets' => array(
					__( 'None' ),
					Action::TARGET_PAGE      => __( 'Page' ),
					Action::TARGET_POST      => __( 'Post' ),
					Action::TARGET_POST_LIST => __( 'Posts' ),
					Action::TARGET_CUSTOM    => __( 'Custom', 'yd-mobile-app' ),
				) + (
					Utils\WC::is_support()
					? array(
						Action::TARGET_PRODUCT      => __( 'Product', 'yd-mobile-app' ),
						Action::TARGET_PRODUCT_LIST => __( 'Product list', 'yd-mobile-app' ),
					)
					: array()
				),
				'params'  => array(
					Action::TARGET_PAGE         => array(
						'id' => array(
							'ajax_action_name' => 'page-search',
							'input_type'       => 'selection',
							'input_properties' => array(
								'is_multiple' => false,
							),
							'display_name'     => __( 'Page title' ),
						),
					),
					Action::TARGET_PRODUCT      => array(
						'id' => array(
							'input_type'       => 'selection',
							'input_properties' => array(
								'is_multiple' => false,
								'target'      => 'product',
							),
							'display_name'     => __( 'Product name', 'woocommerce' ),
						),
					),
					Action::TARGET_PRODUCT_LIST => array(
						'search'    => array(
							'input_type'   => 'text',
							'display_name' => __( 'Search' ),
						),
						'category'  => array(
							'input_type'       => 'selection',
							'input_properties' => array(
								'is_multiple' => true,
								'target'      => 'product_category',
							),
							'display_name'     => __( 'Categories' ),
						),
						'min_price' => array(
							'input_type'   => 'text',
							'display_name' => __( 'Min price', 'woocommerce' ),
						),
						'max_price' => array(
							'input_type'   => 'text',
							'display_name' => __( 'Max price', 'woocommerce' ),
						),
						'on_sale'   => array(
							'input_type'   => 'checkbox',
							'display_name' => __( 'On sale', 'yd-mobile-app' ),
						),
					),
					Action::TARGET_POST         => array(
						'id' => array(
							'input_type'       => 'selection',
							'ajax_action_name' => 'post-search',
							'input_properties' => array(
								'is_multiple' => false,
								'target'      => 'post',
							),
							'display_name'     => __( 'Post name' ),
						),
					),
					Action::TARGET_POST_LIST    => array(
						'search'   => array(
							'input_type'   => 'text',
							'display_name' => __( 'Search' ),
						),
						'category' => array(
							'input_type'       => 'selection',
							'ajax_action_name' => 'post-search',
							'input_properties' => array(
								'is_multiple' => true,
								'target'      => 'post_category',
							),
							'display_name'     => __( 'Categories' ),
						),
						'tag'      => array(
							'ajax_action_name' => 'post-search',
							'input_type'       => 'selection',
							'input_properties' => array(
								'is_multiple' => true,
								'target'      => 'post_tag',
							),
							'display_name'     => __( 'Tags' ),
						),
					),
				),
			),
			'value'            => $this->get_value(),
		);
	}
}
