<?php
/**
 * Widget Library Management for Mobile App
 *
 * This file defines the `Widget_Library` class, which extends the base `YD\Library` class and provides
 * methods for locating widget-related files and directories. It helps in identifying the locations of
 * widget class files and widget types for further processing.
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
 * Widget_Library class extends the base library class and defines methods for locating widget-related files.
 *
 * This class provides functionality to locate widget-related files and directories, such as class files and
 * widget types. It extends the base `YD\Library` class to offer additional functionality specific to widgets.
 */
final class Widget_Library extends \YD\Library {

	/**
	 * Gets the directory of the widget library.
	 *
	 * This method returns the directory where the widget library files are stored.
	 *
	 * @return string The directory path for the widget library.
	 */
	protected function get_dir(): string {
		return __DIR__;
	}

	/**
	 * Gets the locations for widget-related files.
	 *
	 * This method returns an array of patterns representing the locations where widget-related files are
	 * located, such as class files and widget types.
	 *
	 * @return array The array of file location patterns.
	 */
	protected function get_locations(): array {
		return array(
			'class-*',
			'type/*',
		);
	}
}
