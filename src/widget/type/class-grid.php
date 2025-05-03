<?php
/**
 * Grid Widget for Mobile App
 *
 * This file defines the `Grid` class, which represents a widget for displaying a grid of items
 * with an optional "more" button. The grid items can contain a title, image, and associated action.
 * The "more" button can be configured to provide additional functionality or navigation.
 *
 * @package YD\Mobile_App
 * @subpackage Widget
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Widget;

use YD\Mobile_App\Widget\Action;
use YD\Data_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Grid class defines a widget for displaying a grid of items with an optional "more" button.
 *
 * This class handles the display of items in a grid layout, each item containing a title, image,
 * and an associated action. It also manages the "more" button for additional functionality or navigation.
 *
 * The widget is part of the mobile app, with specific data structures, rules, and functions to manage
 * the widget's behavior in the app.
 */
final class Grid extends \YD\Mobile_App\Widget {
	/**
	 * The "more" button configuration for the widget, including title and action.
	 *
	 * @var array
	 */
	private $more_button;

	/**
	 * The list of items in the grid, each containing title, image, and associated action.
	 *
	 * @var array
	 */
	private $items;

	/**
	 * Maximum length for the item title.
	 */
	const ITEM_TITLE_MAX_LENGTH = 64;

	/**
	 * Gets the widget type, which is a grid.
	 *
	 * @return string The widget type.
	 */
	public function get_type(): string {
		return parent::TYPE_GRID;
	}

	/**
	 * Gets the item name for the widget.
	 *
	 * @return string The item name.
	 */
	public function get_item_name(): string {
		return __( 'item', 'yd-mobile-app' );
	}

	/**
	 * Gets the configuration for the "more" button.
	 *
	 * @return array The more button configuration.
	 */
	public function get_more_button(): array {
		return $this->more_button ?? array();
	}

	/**
	 * Gets the list of items in the grid.
	 *
	 * @return array The list of grid items.
	 */
	public function get_items(): array {
		return $this->items ?? array();
	}

	/**
	 * Updates the widget data.
	 *
	 * @param array $data Data to update the widget with.
	 * @param bool  $sanitize Whether to sanitize the data before updating.
	 */
	public function update_data( array $data, bool $sanitize = true ) {
		if ( $sanitize ) {
			$data = parent::get_data_manager( $data )->sanitize();
		}
		$this->more_button = $data['more_button'];
		$this->items       = $data['items'];
	}

	/**
	 * Prepares the widget data for REST API response.
	 *
	 * @return array The REST API formatted widget data.
	 */
	protected function get_data_for_rest(): array {
		$data = $this->get_data();

		$items = array_map(
			function ( array $item ) {
				return array(
					// phpcs:ignore WordPress.WP.I18n
					'title'     => __( $item['title'], 'yd-mobile-app-language' ),
					// phpcs:ignore Universal.Operators.DisallowShortTernary
					'media_url' => array( 'media_url' => wp_get_attachment_image_url( $item['media_id'], 'large' ) ?: wp_get_attachment_image_url( $item['media_id'], 'full' ) ?: wp_get_attachment_url( $item['media_id'] ) ),
				) + Action::init_from_data( $item['action'] )->get_data_for_rest();
			},
			$data['items']
		);
		return array(
			'more_button' => Action::init_from_data( $data['more_button']['action'] )->get_data_for_rest(),
			'items'       => $items,
		);
	}

	/**
	 * Retrieves the widget data.
	 *
	 * @return array The widget data.
	 */
	protected function get_data(): array {
		return array(
			'more_button' => $this->get_more_button(),
			'items'       => $this->get_items(),
		);
	}

	/**
	 * Defines the rules for the widget's data, including validation for more button and items.
	 *
	 * @return array The rules for widget data.
	 */
	protected function get_rules(): array {
		return array(
			'more_button' => array(
				'type'  => 'object',
				'rules' => Action::get_data_rules(),
			),
			'items'       => array(
				'type'     => 'array',
				'default'  => array(),
				'rules'    => array(
					'title'    => array(
						'type'            => 'string',
						'sanitize_length' => self::ITEM_TITLE_MAX_LENGTH,
					),
					'media_id' => array(
						'type'     => 'integer',
						'default'  => 0,
						'required' => true,
					),
				) + Action::get_data_rules(),
				'required' => true,
			),
		);
	}
}
