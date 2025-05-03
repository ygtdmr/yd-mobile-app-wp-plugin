<?php
/**
 * Create_Widget: Handles the creation of widgets and metadata management
 *
 * This file defines the `Create_Widget` class, responsible for processing widget creation,
 * preparing the data for insertion into the database, and managing the widget's position metadata.
 * It ensures that widgets are properly created and positioned within the system.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Action
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Action;

use YD\Mobile_App\Widget;
use YD\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Create_Widget handles the creation of widgets and saves their metadata.
 */
final class Create_Widget {

	/**
	 * Processes widget data and prepares it for insertion into the database.
	 *
	 * @param array $data The data array containing widget details.
	 * @param array $post_data The existing post data to be modified.
	 *
	 * @return array The modified post data with widget information.
	 */
	public static function get_data( array $data, array $post_data ): array {
		$new_widget                = Widget::init_by_type( $data['widget_type'] );
		$post_data['post_content'] = $new_widget->get_data_for_db();
		$post_data['post_title']   = $data['post_title'];
		$post_data['post_name']    = $data['post_title'];
		return $post_data;
	}

	/**
	 * Saves the widget's position metadata.
	 *
	 * @param array    $data The data array containing widget details.
	 * @param \WP_Post $post The WordPress post object to save metadata for.
	 *
	 * @return void
	 */
	public static function save_meta_data( array $data, \WP_Post $post ) {
		$new_position = (
			new \WP_Query(
				array(
					'posts_per_page' => -1,
					'post_type'      => 'yd_widget',
				)
			)
		)->found_posts;

		update_post_meta( $post->ID, Utils\Main::meta_key( 'position' ), $new_position );
	}
}
