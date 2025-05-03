<?php
/**
 * Products Horizontal Widget for Mobile App
 *
 * This file defines the `Products_Horizontal` class, which manages the widget settings for displaying
 * a horizontal list of products in the mobile app. It handles the retrieval and filtering of product data
 * based on various parameters, such as custom product selection, sale filtering, and custom product parameters.
 *
 * @package YD\Mobile_App
 * @subpackage Widget
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Products_Horizontal class manages the widget settings for displaying a horizontal list of products.
 *
 * This class handles the retrieval and filtering of product data based on various parameters,
 * such as custom product selection, sale filtering, and custom product parameters.
 */
final class Products_Horizontal extends \YD\Mobile_App\Widget {

	/**
	 * The product IDs to be displayed in the widget.
	 *
	 * @var array
	 */
	private $id;

	/**
	 * Flag to determine if custom products are used.
	 *
	 * @var bool
	 */
	private $is_custom_products;

	/**
	 * Custom parameters for filtering the products.
	 *
	 * @var array
	 */
	private $custom_params;

	/**
	 * Gets the widget type.
	 *
	 * @return string The widget type identifier.
	 */
	public function get_type(): string {
		return parent::TYPE_PRODUCTS_HORIZONTAL;
	}

	/**
	 * Gets the IDs of the products to be displayed.
	 *
	 * @return array The product IDs.
	 */
	public function get_id(): array {
		return $this->id ?? array();
	}

	/**
	 * Checks if custom products are used.
	 *
	 * @return bool True if custom products are used, false otherwise.
	 */
	public function is_custom_products(): bool {
		return $this->is_custom_products ?? false;
	}

	/**
	 * Gets the custom parameters for filtering the products.
	 *
	 * @return array The custom parameters for filtering.
	 */
	public function get_custom_params(): array {
		return $this->custom_params ?? array();
	}

	/**
	 * Updates the widget data with the provided data.
	 *
	 * @param array $data The data to update the widget with.
	 * @param bool  $sanitize Whether to sanitize the data before updating.
	 */
	public function update_data( array $data, bool $sanitize = true ) {
		if ( $sanitize ) {
			$data = parent::get_data_manager( $data )->sanitize();
		}
		$this->id                 = $data['id'] ?? array();
		$this->is_custom_products = $data['is_custom_products'] ?? false;

		if ( isset( $data['custom_params']['filter_as_sale'] ) && ! $data['custom_params']['filter_as_sale'] ) {
			$data['custom_params']['on_sale'] = false;
		}
		$this->custom_params = $data['custom_params'] ?? array();
	}

	/**
	 * Retrieves the widget data formatted for REST API response.
	 *
	 * @return array The widget data formatted for REST API, excluding the sale filter.
	 */
	protected function get_data_for_rest(): array {
		return $this->get_data();
	}

	/**
	 * Gets the widget data.
	 *
	 * @return array The widget data.
	 */
	protected function get_data(): array {
		return array(
			'id'                 => $this->get_id(),
			'is_custom_products' => $this->is_custom_products(),
			'custom_params'      => $this->get_custom_params(),
		);
	}

	/**
	 * Gets the rules for validating widget data.
	 *
	 * @return array The widget data validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'id'                 => array(
				'type'       => 'array',
				'item_rules' => array( 'type' => 'integer' ),
			),
			'is_custom_products' => array(
				'type' => 'boolean',
			),
			'custom_params'      => array(
				'type'  => 'object',
				'rules' => array(
					'search'         => array( 'type' => 'string' ),
					'min_price'      => array( 'sanitize_callback' => '\YD\Utils\WC::sanitize_price' ),
					'max_price'      => array( 'sanitize_callback' => '\YD\Utils\WC::sanitize_price' ),
					'filter_as_sale' => array( 'type' => 'boolean' ),
					'on_sale'        => array( 'type' => 'boolean' ),
				),
			),
		);
	}
}
