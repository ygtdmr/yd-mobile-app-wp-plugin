<?php
/**
 * REST API Response: Home Widgets
 *
 * This file defines the `Home` class, which provides a REST API response handler
 * for retrieving home page widgets from the `yd_widget` custom post type.
 *
 * @package YD\Mobile_App
 * @subpackage REST_API\Response
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\REST_API\Response;

use YD\Mobile_App\Widget;
use YD\Data_Manager;
use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Home class handles the REST API response for retrieving home page widgets.
 *
 * This class fetches widgets from the `yd_widget` custom post type, ordered by
 * their position, and returns them in the API response.
 */
final class Home extends \YD\REST_API\Response {

	/**
	 * Callback method for retrieving the widgets on the home page.
	 *
	 * This method queries the `yd_widget` post type, retrieves the widgets based on
	 * the current page and orders them by their position (using a numeric meta key).
	 * The widgets are then returned in the response in a structured format.
	 *
	 * @param \WP_REST_Request|null $request The request object containing pagination info.
	 *
	 * @return array An array containing the widgets and their corresponding data.
	 */
	public function get_callback( ?\WP_REST_Request $request = null ) {
		$query = new \WP_Query(
			array(
				'posts_per_page' => 4,
				'paged'          => $request['page'],
				'post_type'      => 'yd_widget',
				// phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_key'       => Utils\Main::meta_key( 'position' ),
				'meta_type'      => 'NUMERIC',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
			)
		);

		return array(
			'widgets' => array_map(
				function ( $post ) {
					$widget = Widget::get_by_id( $post->ID );
					return $widget->view_data();
				},
				$query->posts
			),
		);
	}
}
