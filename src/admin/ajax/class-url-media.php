<?php
/**
 * URL_Media: Handles AJAX requests to retrieve the URL of a WordPress attachment (image or video).
 *
 * This class processes AJAX requests to fetch the URL of a WordPress attachment,
 * either an image or a video, based on the provided attachment ID and the desired
 * media type (size for images or a default size for videos). If the requested size
 * is unavailable, it defaults to returning the full-size media URL.
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
 * Handles AJAX requests to fetch the URL for a WordPress attachment, which could be
 * either an image or a video. The URL is returned based on the attachment ID and
 * the desired media size for images or defaults to the standard video URL for videos.
 */
final class URL_Media extends \YD\Admin\Ajax {

	/**
	 * Returns the action name for the AJAX request.
	 *
	 * @return string Action name.
	 */
	protected function get_action_name(): string {
		return 'url-media';
	}

	/**
	 * Returns the validation rules for the incoming AJAX data.
	 *
	 * @return array Validation rules.
	 */
	protected function get_rules(): array {
		return array(
			'id'   => array(
				'type'     => 'integer',
				'required' => true,
				'default'  => 0,
			),
			'size' => array(
				'type'     => 'enum',
				'values'   => array(
					'thumbnail',
					'medium',
					'large',
					'full',
				),
				'required' => true,
				'default'  => 'full',
			),
		);
	}

	/**
	 * Handles the AJAX request to retrieve the URL of the attachment based on the provided ID, type, and size.
	 *
	 * If the requested image size is not available, it will return the full-size image URL.
	 * If the attachment is a video, it returns the video URL.
	 * If the full-size image or video URL is not available, it will return the general attachment URL.
	 *
	 * @return void
	 */
	protected function get_action() {
		$url = wp_get_attachment_image_url( $this->data['id'], $this->data['size'] );

		if ( ! $url ) {
			$url = wp_get_attachment_image_url( $this->data['id'], 'full' );
		}
		if ( ! $url ) {
			$url = wp_get_attachment_url( $this->data['id'] );
		}
		parent::send_success(
			array(
				'url'  => $url,
				'type' => explode( '/', get_post_mime_type( get_post( $this->data['id'] ) ) )[0],
			)
		);
	}
}
