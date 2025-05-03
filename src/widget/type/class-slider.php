<?php
/**
 * Slider Widget for Mobile App
 *
 * This file defines the `Slider` class, which represents a slider widget for the mobile app. The class provides
 * functionality for managing the slider's aspect ratio, items, and updating data. It also defines methods for retrieving
 * data in a format suitable for REST API responses.
 *
 * @package YD\Mobile_App
 * @subpackage Widget
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Widget;

use YD\Mobile_App\Widget\Action;
use YD\Admin\Page\View;

defined( 'ABSPATH' ) || exit;

/**
 * Slider class represents a widget of type "slider".
 *
 * This class defines a slider widget with a customizable aspect ratio and items. It provides methods to manage
 * the aspect ratio and items, update the widget data, and retrieve data formatted for REST API.
 */
final class Slider extends \YD\Mobile_App\Widget {

	/**
	 * Aspect ratio of the slider.
	 *
	 * @var string|null
	 */
	private $aspect_ratio;

	/**
	 * Items to be displayed in the slider.
	 *
	 * @var array|null
	 */
	private $items;

	/**
	 * Default aspect ratio for the slider.
	 */
	const DEFAULT_ASPECT_RATIO = '16:9';

	const TEXT_VALUE_MAX_LENGTH = 1024;

	/**
	 * Gets the type of the widget, which is 'slider'.
	 *
	 * @return string The type of the widget, which is 'slider'.
	 */
	public function get_type(): string {
		return parent::TYPE_SLIDER;
	}

	/**
	 * Gets the name of the item in the slider.
	 *
	 * @return string The name of the item in the slider, which is 'slide'.
	 */
	public function get_item_name(): string {
		return __( 'slide', 'yd-mobile-app' );
	}

	/**
	 * Gets the items in the slider.
	 *
	 * @return array The array of items in the slider.
	 */
	public function get_items(): array {
		return $this->items ?? array();
	}

	/**
	 * Gets the aspect ratio of the slider.
	 *
	 * @return string The aspect ratio of the slider, default is '16:9'.
	 */
	public function get_aspect_ratio(): string {
		return $this->aspect_ratio ?? self::DEFAULT_ASPECT_RATIO;
	}

	/**
	 * Updates the slider data.
	 *
	 * @param array $data The data to update the slider.
	 * @param bool  $sanitize Whether to sanitize the data before updating.
	 */
	public function update_data( array $data, bool $sanitize = true ) {
		if ( $sanitize ) {
			$data = parent::get_data_manager( $data )->sanitize();
		}
		$this->aspect_ratio = $data['aspect_ratio'];
		$this->items        = $data['items'];
	}

	/**
	 * Prepares the slider data for REST API response.
	 *
	 * @return array The slider data formatted for the REST API.
	 */
	protected function get_data_for_rest(): array {
		$data  = $this->get_data();
		$items = array_map(
			function ( array $item ) {
				return array(
					// phpcs:ignore Universal.Operators.DisallowShortTernary
					'media_url'      => wp_get_attachment_image_url( $item['media_id'], 'large' ) ?: wp_get_attachment_image_url( $item['media_id'], 'full' ) ?: wp_get_attachment_url( $item['media_id'] ),
					'media_fg_color' => $item['media_fg_color'] ?? '',
					'text_views'     => array_map(
						function ( array $text_view ) {
							return array(
								'position'         => $text_view['position'],
								// phpcs:ignore WordPress.WP.I18n
								'text'             => __( $text_view['text'], 'yd-mobile-app-language' ),
								'size'             => $text_view['size'],
								'color'            => $text_view['color'],
								'alignment'        => $text_view['alignment'],
								'style_class'      => $text_view['style_class'] ?? '',
								'background_color' => $text_view['background']['color'] ?? '',
								'padding'          => array(
									'left'   => $text_view['padding']['left'] ?? 0,
									'right'  => $text_view['padding']['right'] ?? 0,
									'top'    => $text_view['padding']['top'] ?? 0,
									'bottom' => $text_view['padding']['bottom'] ?? 0,
								),
								'border'           => array(
									'color'  => $text_view['border']['color'],
									'width'  => array(
										'left'   => $text_view['border']['width']['left'] ?? 0,
										'right'  => $text_view['border']['width']['right'] ?? 0,
										'top'    => $text_view['border']['width']['top'] ?? 0,
										'bottom' => $text_view['border']['width']['bottom'] ?? 0,
									),
									'radius' => array(
										'lt' => $text_view['border']['radius']['lt'] ?? 0,
										'rt' => $text_view['border']['radius']['rt'] ?? 0,
										'rb' => $text_view['border']['radius']['rb'] ?? 0,
										'lb' => $text_view['border']['radius']['lb'] ?? 0,
									),
								),
							) + Action::init_from_data( $text_view['action'] )->get_data_for_rest();
						},
						$item['text_views'] ?? array()
					),
				) + Action::init_from_data( $item['action'] )->get_data_for_rest();
			},
			$data['items']
		);
		return array(
			'aspect_ratio' => $data['aspect_ratio'],
			'items'        => $items,
		);
	}

	/**
	 * Gets the slider data.
	 *
	 * @return array The slider data, including aspect ratio and items.
	 */
	protected function get_data(): array {
		return array(
			'aspect_ratio' => $this->get_aspect_ratio(),
			'items'        => $this->get_items(),
		);
	}

	/**
	 * Gets the validation rules for the slider data.
	 *
	 * @return array The rules for validating the slider data, including aspect ratio and items.
	 */
	protected function get_rules(): array {
		return array(
			'aspect_ratio' => array(
				'type'     => 'string',
				'default'  => self::DEFAULT_ASPECT_RATIO,
				'pattern'  => '/^\d+\:\d+$/',
				'required' => true,
			),
			'items'        => array(
				'type'     => 'array',
				'default'  => array(),
				'rules'    => array(
					'media_id'       => array(
						'type'     => 'integer',
						'default'  => 0,
						'required' => true,
					),
					'media_fg_color' => array(
						'type'          => 'string',
						'pattern_match' => View\Color_Picker::RULE_PATTERN_MATCH,
					),
				) + Action::get_data_rules()
					+ array(
						'text_views' => array(
							'type'  => 'array',
							'rules' => array(
								'position'         => array(
									'type'     => 'object',
									'rules'    => array(
										'x' => array(
											'type'     => 'double',
											'default'  => 0,
											'required' => true,
										),
										'y' => array(
											'type'     => 'double',
											'default'  => 0,
											'required' => true,
										),
									),
									'default'  => array(),
									'required' => true,
								),
								'color'            => array(
									'type'          => 'string',
									'pattern_match' => View\Color_Picker::RULE_PATTERN_MATCH,
									'default'       => 'rgb(0,0,0)',
									'required'      => true,
								),
								'size'             => array(
									'type'     => 'integer',
									'min'      => 8,
									'max'      => 128,
									'default'  => 16,
									'required' => true,
								),
								'alignment'        => array(
									'type'     => 'enum',
									'values'   => array(
										'left',
										'right',
										'center',
										'justify',
									),
									'required' => true,
									'default'  => 'left',
								),
								'padding'          => array(
									'type'  => 'object',
									'rules' => array(
										'left'   => array( 'type' => 'integer' ),
										'right'  => array( 'type' => 'integer' ),
										'top'    => array( 'type' => 'integer' ),
										'bottom' => array( 'type' => 'integer' ),
									),
								),
								'background_color' => array(
									'type'          => 'string',
									'pattern_match' => View\Color_Picker::RULE_PATTERN_MATCH,
								),
								'border'           => array(
									'type'  => 'object',
									'rules' => array(
										'color'  => array(
											'type'     => 'string',
											'pattern_match' => View\Color_Picker::RULE_PATTERN_MATCH,
											'default'  => 'rgb(0,0,0)',
											'required' => true,
										),
										'width'  => array(
											'type'  => 'object',
											'rules' => array(
												'left'   => array( 'type' => 'integer' ),
												'right'  => array( 'type' => 'integer' ),
												'top'    => array( 'type' => 'integer' ),
												'bottom' => array( 'type' => 'integer' ),
											),
										),
										'radius' => array(
											'type'  => 'object',
											'rules' => array(
												'lt' => array( 'type' => 'integer' ),
												'rt' => array( 'type' => 'integer' ),
												'rb' => array( 'type' => 'integer' ),
												'lb' => array( 'type' => 'integer' ),
											),
										),
									),
								),
								'text'             => array(
									'type'            => 'string',
									'is_textarea'     => true,
									'default'         => '',
									'sanitize_length' => self::TEXT_VALUE_MAX_LENGTH,
									'required'        => true,
								),
								'style_class'      => array(
									'type'            => 'string',
									'sanitize_length' => self::TEXT_VALUE_MAX_LENGTH,
									'sanitize_raw_callback' => function ( string $data ) {
										return strtolower( $data );
									},
									'pattern_replace' => array(
										array(
											'pattern'     => '/[^a-z0-9\-\_\,]/',
											'replacement' => '',
										),
										array(
											'pattern'     => '/\,\,/',
											'replacement' => '',
										),
									),
								),
							) + Action::get_data_rules(),
						),
					),
				'required' => true,
			),
		);
	}
}
