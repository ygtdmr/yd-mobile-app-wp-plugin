<?php
/**
 * Customers_Count: Calculates the total number of registered customers who use the mobile app
 *
 * This class defines the calculation of the total number of registered customers who have used the mobile app
 * within a specific time period. It allows counting the customers based on predefined time filters (e.g., today, week, month, year).
 * The result is returned as an integer representing the total number of customers.
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
 * Customers_Count class calculates the total number of registered customers
 * who use the mobile app within a specific time period.
 */
final class Customers_Count extends \YD\Mobile_App\Admin\Stat {

	/**
	 * Returns the stat type identifier.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'customers-count';
	}

	/**
	 * Returns the display name for this stat.
	 *
	 * @return string
	 */
	public function get_display_name(): string {
		return __( 'Count of customers', 'yd-mobile-app' );
	}

	/**
	 * Returns a short description of what this stat represents.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return __( 'Shows the total of all registered customers.', 'yd-mobile-app' );
	}

	/**
	 * Calculates the number of WooCommerce customers who registered
	 * via the mobile app within the specified date filter.
	 *
	 * @param string $filter One of: 'today', 'week', 'month', 'year'.
	 *
	 * @return mixed The number of customers (integer).
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
				'role'       => 'customer',
			)
		);
		$results    = $user_query->get_results();

		return count( $results );
	}
}
