<?php
/**
 * Label Widget for Mobile App
 *
 * This file defines the `Label` class, which represents a widget for displaying a label with items
 * and an optional "more" button. The widget allows setting the title of the label, a list of items,
 * and the behavior of the "more" button, including its title and associated action.
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
 * Label class manages the widget settings for displaying a label with items and a 'more' button.
 *
 * This class handles the configuration of the label, items under it, and the behavior of the 'more' button,
 * including the title and associated action for the button.
 */
final class Label extends \YD\Mobile_App\Widget {

	/**
	 * The data for the 'more' button (e.g., title and action).
	 *
	 * @var array
	 */
	private $more_button;

	/**
	 * The items to be displayed in the label widget.
	 *
	 * @var array
	 */
	private $items;

	/**
	 * The maximum allowed length for the label title.
	 */
	const LABEL_MAX_LENGTH = 64;

	/**
	 * Gets the widget type.
	 *
	 * @return string The widget type identifier.
	 */
	public function get_type(): string {
		return parent::TYPE_LABEL;
	}

	/**
	 * Gets the item name for the label widget.
	 *
	 * @return string The item name.
	 */
	public function get_item_name(): string {
		return __( 'title', 'yd-mobile-app' );
	}

	/**
	 * Gets the 'more' button data.
	 *
	 * @return array The 'more' button data.
	 */
	public function get_more_button(): array {
		return $this->more_button ?? array();
	}

	/**
	 * Gets the items under the label widget.
	 *
	 * @return array The items.
	 */
	public function get_items(): array {
		return $this->items ?? array();
	}

	/**
	 * Updates the widget data with the provided values.
	 *
	 * @param array $data The data to update the widget with.
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
	 * Retrieves the widget data formatted for REST API response.
	 *
	 * @return array The widget data formatted for REST API, including the 'more' button and items.
	 */
	protected function get_data_for_rest(): array {
		$data = $this->get_data();

		$items = array_map(
			function ( array $item ) {
				return array(
					// phpcs:ignore WordPress.WP.I18n
					'label' => __( $item['label'], 'yd-mobile-app-language' ),
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
	 * Gets the widget data.
	 *
	 * @return array The widget data including 'more_button' and 'items'.
	 */
	protected function get_data(): array {
		return array(
			'more_button' => $this->get_more_button(),
			'items'       => $this->get_items(),
		);
	}

	/**
	 * Gets the rules for validating widget data.
	 *
	 * @return array The widget data validation rules, including rules for 'more_button' and 'items'.
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
					'label' => array(
						'type'            => 'string',
						'sanitize_length' => self::LABEL_MAX_LENGTH,
						'required'        => true,
						'default'         => 'Label',
					),
				) + Action::get_data_rules(),
				'required' => true,
			),
		);
	}
}
