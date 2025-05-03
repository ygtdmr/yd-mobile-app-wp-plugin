<?php
/**
 * Users_Count: Calculates the number of registered users who use the mobile app.
 *
 * This class defines the calculation of the total number of registered users with the `subscriber` role
 * who have the `yd_mobile_app` meta field set to true. The number of users is filtered by predefined time filters
 * (e.g., today, week, month, year) to count users who registered during the specified time range.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Stat
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Stat;

defined( 'ABSPATH' ) || exit;

/**
 * Users_Count class calculates the number of registered users who use the mobile app.
 *
 * This stat is filtered by time range (today, week, month, year) and only includes users
 * with the `subscriber` role who have the `yd_mobile_app` meta field set to true.
 */
final class Users_Count extends \YD\Mobile_App\Admin\Stat {

	/**
	 * Returns the stat type identifier.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'users-count';
	}

	/**
	 * Returns the display name for this stat.
	 *
	 * @return string
	 */
	public function get_display_name(): string {
		return __( 'Count of users', 'yd-mobile-app' );
	}

	/**
	 * Returns a short description of what this stat represents.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return __( 'Shows the total of all registered users.', 'yd-mobile-app' );
	}

	/**
	 * Calculates the number of users for the given time filter.
	 *
	 * @param string $filter One of the predefined time filter constants.
	 *
	 * @return mixed The count of users.
	 */
	public function calculate( string $filter ): mixed {
		$target_time = array(
			self::FILTER_DATE_TODAY => DAY_IN_SECONDS,
			self::FILTER_DATE_WEEK  => WEEK_IN_SECONDS,
			self::FILTER_DATE_MONTH => MONTH_IN_SECONDS,
			self::FILTER_DATE_YEAR  => YEAR_IN_SECONDS,
		);

		$user_query = new \WP_User_Query(
			array(
				// phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_query' => array(
					array(
						'key'     => 'yd_mobile_app',
						'value'   => true,
						'compare' => '=',
					),
				),
				'date_query' => array(
					array(
						'after'     => gmdate( 'c', time() - $target_time[ $filter ] ),
						'inclusive' => true,
					),
				),
				'role'       => 'subscriber',
			)
		);
		$results    = $user_query->get_results();

		return count( $results );
	}
}
