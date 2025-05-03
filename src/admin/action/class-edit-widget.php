<?php
/**
 * Edit_Widget: Handles widget editing and metadata management
 *
 * This file defines the `Edit_Widget` class, which is responsible for processing widget data,
 * preparing it for database saving, and saving the widget's position metadata.
 * It ensures that widgets are properly updated and managed in the system.
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
 * Edit_Widget handles the editing of widgets and their metadata.
 */
final class Edit_Widget {

	/**
	 * Processes widget data and prepares it for saving to the database.
	 *
	 * @param array $data The data array containing widget details.
	 * @param array $post_data The existing post data to be modified.
	 *
	 * @return array The modified post data with widget information.
	 */
	public static function get_data( array $data, array $post_data ): array {
		$widget_id    = $data['post_ID'];
		$widget_title = $data['post_title'];
		$widget_data  = $data['widget_data'];
		$widget_class = $data['widget_class'] ?? '';

		$widget = Widget::get_by_id( $widget_id );
		$widget->set_title( $widget_title );

		if ( ! empty( $widget_data ) ) {
			$widget->set_class( $widget_class );
			$widget->update_data( $widget_data );
		}

		$post_data['post_title']   = $widget->get_title();
		$post_data['post_name']    = $widget->get_title();
		$post_data['post_content'] = $widget->get_data_for_db();

		return $post_data;
	}

	/**
	 * Saves the widget's position metadata.
	 *
	 * @param array $data The data array containing widget details.
	 *
	 * @return void
	 */
	public static function save_meta_data( array $data ) {
		$widget_id       = $data['post_ID'];
		$widget_position = $data['widget_position'];

		$current_widget_index = get_post_meta( $widget_id, Utils\Main::meta_key( 'position' ), true );
		$target_post          = ( new \WP_Query(
			array(
				'post_type'  => 'yd_widget',
				// phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_query' => array(
					array(
						'key'     => Utils\Main::meta_key( 'position' ),
						'value'   => $widget_position,
						'compare' => '=',
					),
				),
			)
		) )->post;

		update_post_meta( $target_post->ID, Utils\Main::meta_key( 'position' ), $current_widget_index );
		update_post_meta( $widget_id, Utils\Main::meta_key( 'position' ), $widget_position );
	}
}
