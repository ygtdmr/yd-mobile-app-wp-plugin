<?php
/**
 * Post_Search: Handles AJAX requests for searching posts or taxonomies (categories, tags).
 *
 * This class processes AJAX requests to search and filter posts or taxonomies based on the provided criteria,
 * including keyword or value, and returns the matching results such as posts, categories, or tags.
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
 * Handles AJAX requests for searching posts or taxonomies (categories, tags) based on given criteria such as keyword or value.
 */
final class Post_Search extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'post-search';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'target'  => array(
				'default'  => 'post',
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
	 * Fetches the posts based on provided criteria (keyword or value).
	 *
	 * @return array List of posts matching the search criteria.
	 */
	private function get_posts(): array {
		$result = get_posts(
			array(
				'status'    => 'publish',
				'post_type' => 'post',
			)
			+ ( ! empty( $this->data['keyword'] ) ? array( 's' => $this->data['keyword'] ) : array( 'include' => $this->data['value'] ) )
		);

		if ( is_wp_error( $result ) ) {
			return array();
		}

		$result = array_map(
			function ( \WP_Post $post ) {
				return array(
					'id'   => $post->ID,
					'name' => $post->post_title,
				);
			},
			array_values( $result )
		);

		return $this->sort_result( $result );
	}

	/**
	 * Fetches the terms (categories, tags) based on provided criteria (keyword or value).
	 *
	 * @param string $taxonomy The taxonomy to search (either 'category' or 'post_tag').
	 *
	 * @return array List of terms matching the search criteria.
	 */
	private function get_terms( string $taxonomy ): array {
		$result = get_terms(
			array(
				'taxonomy'   => $taxonomy,
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
	 * Handles the AJAX request by sending the result of the post or taxonomy search.
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
			case 'post':
				$result = $this->get_posts();
				break;
			case 'post_category':
				$result = $this->get_terms( 'category' );
				break;
			case 'post_tag':
				$result = $this->get_terms( 'post_tag' );
				break;
		}

		parent::send_success( $result );
	}
}
