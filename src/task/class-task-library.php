<?php
/**
 * Task_Library: Responsible for loading and initializing specific tasks within the mobile app.
 *
 * This class loads and initializes tasks such as translation that are part of the app's library.
 * It defines task-related directories, and upon loading, it performs the necessary actions for the tasks.
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
 * Task_Library class is responsible for loading and initializing specific tasks within the mobile app.
 *
 * This class provides the functionality to load and initialize tasks, like translation, that are part of the app's library.
 * It defines the task directory and locations, and performs specific actions upon loading.
 */
final class Task_Library extends \YD\Library {

	/**
	 * Gets the directory of the current class.
	 *
	 * This function returns the directory path of the class, which can be useful for locating related files.
	 *
	 * @return string The directory path of the current class.
	 */
	protected function get_dir(): string {
		return __DIR__;
	}

	/**
	 * Gets the locations where task-related files are stored.
	 *
	 * This function returns an array of locations that can be used to identify the paths for task-related files.
	 * In this case, it points to directories matching 'type/*'.
	 *
	 * @return array The locations of task-related files.
	 */
	protected function get_locations(): array {
		return array(
			'type/*',
		);
	}

	/**
	 * Initializes the tasks upon class load.
	 *
	 * This function loops through an array of tasks and creates an instance of each task class.
	 * In this case, it loads the `Translate` task for the mobile app.
	 *
	 * @return void
	 */
	protected function on_load() {
		$tasks = array(
			\YD\Mobile_App\Task\Translate::class,
		);
		foreach ( $tasks as $task ) {
			new $task();
		}
	}
}
