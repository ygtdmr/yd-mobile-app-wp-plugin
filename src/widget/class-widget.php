<?php
/**
 * Widget Management for Mobile App
 *
 * This file defines the `Widget` abstract class, which provides the base structure for different widget types
 * within the mobile app. The class includes methods for handling widget titles, managing data, and encoding/
 * decoding widget data for storage. Abstract methods are provided for widget types to implement specific behavior.
 *
 * @package YD\Mobile_App
 * @subpackage Widget
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App;

use YD\Data_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract class Widget defines the base structure for different widget types in the mobile app.
 *
 * This class provides the core functionality for widgets such as getting and setting widget title, managing data,
 * and encoding/decoding widget data for storage. It also defines abstract methods that must be implemented by
 * concrete widget types.
 */
abstract class Widget {

	/**
	 * Maximum allowed length for the widget title.
	 */
	const TITLE_MAX_LENGTH = 128;

	/**
	 * Maximum allowed length for the widget class.
	 */
	const CLASS_MAX_LENGTH = 128;

	// Widget type constants.
	const TYPE_SLIDER              = 'slider';
	const TYPE_GRID                = 'grid';
	const TYPE_LABEL               = 'label';
	const TYPE_PRODUCTS_HORIZONTAL = 'products-horizontal';

	/**
	 * Data manager instance responsible for handling widget data validation and sanitization.
	 *
	 * @var Data_Manager
	 */
	private $data_manager;

	/**
	 * Widget title that is displayed in the app. It can be set and retrieved.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Widget class that is appear how look widget style in the app. It can be set and retrieved.
	 *
	 * @var string
	 */
	private $class;

	/**
	 * Gets the widget type.
	 *
	 * @return string The widget type.
	 */
	abstract public function get_type(): string;

	/**
	 * Updates the widget data.
	 *
	 * @param array $data Data to update the widget with.
	 * @param bool  $sanitize Whether to sanitize the data.
	 *
	 * @return void
	 */
	abstract public function update_data( array $data, bool $sanitize = true );

	/**
	 * Retrieves the widget data.
	 *
	 * @return array The widget data.
	 */
	abstract protected function get_data(): array;

	/**
	 * Retrieves the widget data in a format suitable for REST API.
	 *
	 * @return array The REST API data format.
	 */
	abstract protected function get_data_for_rest(): array;

	/**
	 * Defines the rules for the widget's data.
	 *
	 * @return array The rules for widget data.
	 */
	abstract protected function get_rules(): array;

	/**
	 * Sets the widget title.
	 *
	 * @param string $title The title to set.
	 *
	 * @return void
	 */
	public function set_title( string $title ) {
		$this->title = $title;
	}

	/**
	 * Sets the widget class.
	 *
	 * @param string $value The class to set.
	 *
	 * @return void
	 */
	public function set_class( string $value ) {
		$this->class = $value;
	}

	/**
	 * Gets the widget title.
	 *
	 * @return string The widget title.
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * Gets the widget class.
	 *
	 * @return string The widget class.
	 */
	public function get_class(): string {
		return $this->class ?? '';
	}

	/**
	 * Gets the data manager instance for the widget.
	 *
	 * @param array $data The widget data.
	 *
	 * @return Data_Manager The data manager instance.
	 *
	 * @throws \Exception If rules are missing for the widget.
	 */
	public function get_data_manager( array $data ): Data_Manager {
		if ( ! $this->data_manager ) {
			$rules = $this->get_rules();

			if ( empty( $rules ) ) {
				throw new \Exception( '[\YD\Data_Manager] Missing Rule: Rules should be not empty.' );
			}

			$this->data_manager = new Data_Manager( $rules, $data );
		}
		return $this->data_manager;
	}

	/**
	 * Checks if the widget is sortable.
	 *
	 * @return bool Whether the widget is sortable.
	 */
	public function is_sortable(): bool {
		return method_exists( $this, 'get_item_name' );
	}

	/**
	 * Prepares the widget data for database storage.
	 *
	 * @return string The encoded widget data for storage.
	 */
	public function get_data_for_db(): string {
		$root_data = $this->get_root_data(
			$this->get_data()
		);
		return Data_Manager::encode( $root_data );
	}

	/**
	 * Prepares the widget data for view.
	 *
	 * @return array The viewable widget data.
	 */
	public function view_data(): array {
		// phpcs:ignore WordPress.WP.I18n
		return array( 'title' => __( $this->get_title(), 'yd-mobile-app-language' ) ) + $this->get_root_data( $this->get_data_for_rest() );
	}

	/**
	 * Prepares the root data structure for the widget.
	 *
	 * @param array $data The widget data.
	 *
	 * @return array The root data structure containing the widget type and data.
	 */
	private function get_root_data( array $data ): array {
		return array(
			'type'  => $this->get_type(),
			'class' => $this->get_class(),
			'data'  => $data,
		);
	}

	/**
	 * Retrieves a widget by its post ID.
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return self|null The widget instance or null if not found.
	 */
	public static function get_by_id( int $post_id ): ?self {
		$post = get_post( $post_id );

		if ( ! $post || 'yd_widget' !== $post->post_type || 'auto-draft' === $post->post_status ) {
			return null;
		}

		$post_data = Data_Manager::decode( $post->post_content );

		$widget = self::init_by_type( $post_data['type'], $post->ID );
		$widget->set_title( $post->post_title );
		$widget->set_class( $post_data['class'] ?? '' );
		$widget->update_data( $post_data['data'], false );
		return $widget;
	}

	/**
	 * Initializes a widget instance based on its type.
	 *
	 * @param string $type The widget type.
	 *
	 * @return self The widget instance.
	 */
	public static function init_by_type( string $type ): self {
		switch ( $type ) {
			case self::TYPE_SLIDER:
				return new Widget\Slider();
			case self::TYPE_GRID:
				return new Widget\Grid();
			case self::TYPE_LABEL:
				return new Widget\Label();
			case self::TYPE_PRODUCTS_HORIZONTAL:
				return new Widget\Products_Horizontal();
		}
	}
}
