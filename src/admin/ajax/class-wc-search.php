<?php
/**
 * WC_Search: Handles AJAX requests for searching WooCommerce products and product categories.
 *
 * This class processes incoming AJAX requests for searching WooCommerce products or categories
 * based on the provided keyword or value. It validates the request data, fetches the relevant
 * results, and sorts them according to the provided criteria.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Ajax
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Ajax;

defined( 'ABSPATH' ) || exit;

/**
 * Handles AJAX requests for searching WooCommerce products and product categories.
 */
final class WC_Search extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'wc-search';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'target'  => array(
				'default'  => 'product',
				'required' => true,
			),
			'value'   => array(
				'default'    => array(),
				'type'       => 'array',
				'item_rules' => array(
					'type' => 'integer',
				),
				'required'   => true,
			),
			'keyword' => array(
				'type' => 'string',
			),
		);
	}

	/**
	 * Retrieves product categories based on the provided search keyword or value.
	 *
	 * @return array List of product categories.
	 */
	private function get_product_categories(): array {
		$result = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
			) +
			( ! empty( $this->data['keyword'] ) ? array( 'search' => $this->data['keyword'] ) : array( 'term_taxonomy_id' => $this->data['value'] ) )
		);

		if ( is_wp_error( $result ) ) {
			return array();
		}

		$result = array_map(
			function ( $term ) {
				return array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			},
			array_values( $result )
		);

		return $this->sort_result( $result );
	}

	/**
	 * Retrieves products based on the provided search keyword or value.
	 *
	 * @return array List of products.
	 */
	private function get_products(): array {
		$result = wc_get_products(
			array(
				'status' => 'publish',
			) +
			( ! empty( $this->data['keyword'] ) ? array( 's' => $this->data['keyword'] ) : array( 'include' => $this->data['value'] ) )
		);

		if ( is_wp_error( $result ) ) {
			return array();
		}

		$result = array_map(
			function ( $product ) {
				return array(
					'id'   => $product->get_id(),
					'name' => $product->get_name(),
				);
			},
			array_values( $result )
		);

		return $this->sort_result( $result );
	}

	/**
	 * Sorts the search results based on the order of IDs in the 'value' array.
	 *
	 * @param array $result List of search result items.
	 *
	 * @return array Sorted search results.
	 */
	private function sort_result( $result ): array {
		usort(
			$result,
			function ( $a, $b ) {
				$index_a = array_search( $a['id'], $this->data['value'], true );
				$index_b = array_search( $b['id'], $this->data['value'], true );

				if ( $index_a === $index_b ) {
					return 0;
				}

				return ( $index_a > $index_b ) ? 1 : -1;
			}
		);
		return $result;
	}

	/**
	 * Handles the AJAX request for searching products or categories based on the provided target and data.
	 *
	 * @return void
	 */
	protected function get_action() {
		if ( empty( $this->data['value'] ) && empty( $this->data['keyword'] ) ) {
			parent::send_success( array() );
			return;
		}

		$result = array();
		switch ( $this->data['target'] ) {
			case 'product':
				$result = $this->get_products();
				break;
			case 'product_category':
				$result = $this->get_product_categories();
				break;
		}

		parent::send_success( $result );
	}
}
