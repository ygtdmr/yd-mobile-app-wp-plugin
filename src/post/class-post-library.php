<?php
/**
 * Post Library Loader for Mobile App
 *
 * This file defines the Post_Library class, which is responsible for
 * loading post-related components (e.g., widgets) within the mobile app.
 *
 * @package YD\Mobile_App
 * @subpackage Library
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Library;

defined( 'ABSPATH' ) || exit;

/**
 * Post_Library class handles the loading of post-related functionality in the mobile app.
 *
 * This class extends from the \YD\Library base class and manages the post widget functionality
 * by loading the relevant classes when required. It defines the directory of the class and the
 * locations where certain post-related functionality should be loaded.
 */
final class Post_Library extends \YD\Library {

	/**
	 * Returns the directory path of the current class.
	 *
	 * This method provides the path to the current directory, which can be used to load
	 * necessary files and classes related to posts.
	 *
	 * @return string The directory path of the current class.
	 */
	protected function get_dir(): string {
		return __DIR__;
	}

	/**
	 * Returns an array of locations where post-related functionality should be loaded.
	 *
	 * This method specifies the locations (or patterns) that will be used to load the post
	 * related functionality. In this case, it includes the 'type/*' pattern.
	 *
	 * @return array The locations for loading the post-related functionality.
	 */
	protected function get_locations(): array {
		return array(
			'type/*',
		);
	}

	/**
	 * Handles the loading of the post-related functionality.
	 *
	 * This method instantiates the post-related widgets or functionality, such as the
	 * \YD\Mobile_App\Post\Widget class, by iterating over an array of post classes and
	 * creating new instances of them.
	 *
	 * @return void
	 */
	protected function on_load() {
		$posts = array(
			\YD\Mobile_App\Post\Widget::class,
		);
		foreach ( $posts as $post ) {
			new $post(); }
	}
}
