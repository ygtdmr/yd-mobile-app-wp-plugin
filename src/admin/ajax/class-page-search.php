<?php
/**
 * Page_Search: Handles AJAX requests for searching pages based on provided criteria such as keyword or specific values.
 *
 * This class processes AJAX requests to search for pages based on the provided keyword or specific values. It validates
 * incoming data, retrieves matching pages, and sorts the results according to the specified order.
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
 * Handles AJAX requests for searching pages based on provided criteria such as keyword or specific values.
 */
final class Page_Search extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'page-search';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'value'   => array(
				'default'    => array(),
				'type'       => 'array',
				'item_rules' => array(
					'type' => 'string',
				),
				'required'   => true,
			),
			'keyword' => array(
				'type' => 'string',
			),
		);
	}

	/**
	 * Fetches the search results for pages based on provided criteria.
	 *
	 * @return array List of pages matching the search criteria.
	 */
	private function get_result(): array {
		$result = get_posts(
			array(
				'status'    => 'publish',
				'post_type' => 'page',
			)
			+ ( ! empty( $this->data['keyword'] ) ? array( 's' => $this->data['keyword'] ) : array( 'name' => current( $this->data['value'] ) ) )
		);

		if ( is_wp_error( $result ) ) {
			return array();
		}

		$result = array_map(
			function ( \WP_Post $post ) {
				return array(
					'id'   => $post->post_name,
					'name' => $post->post_title,
				);
			},
			array_values( $result )
		);

		return $this->sort_result( $result );
	}

	/**
	 * Sorts the result array based on the order of the 'value' array.
	 *
	 * @param array $result The result to be sorted.
	 *
	 * @return array Sorted result.
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
	 * Handles the AJAX request by sending the result of the page search.
	 *
	 * @return void
	 */
	protected function get_action() {
		if ( empty( $this->data['value'] ) && empty( $this->data['keyword'] ) ) {
			parent::send_success( array() );
			return;
		}

		parent::send_success( $this->get_result() );
	}
}
