<?php
/**
 * Orders_Count: Calculates the total number of WooCommerce orders created via the mobile app
 *
 * This class defines the calculation of the total number of orders that were placed via the mobile app.
 * It provides methods to count the total number of orders based on predefined time filters (e.g., today, week, month, year).
 * The result is returned as an integer representing the total number of orders.
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
 * Orders_Count class calculates the total number of WooCommerce orders
 * created via the mobile app within a specific time period.
 */
final class Orders_Count extends \YD\Mobile_App\Admin\Stat {

	/**
	 * Returns the stat type identifier.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'orders-count';
	}

	/**
	 * Returns the display name for this stat.
	 *
	 * @return string
	 */
	public function get_display_name(): string {
		return __( 'Count of orders', 'yd-mobile-app' );
	}

	/**
	 * Returns a short description of what this stat represents.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return __( 'Shows the total of all orders.', 'yd-mobile-app' );
	}

	/**
	 * Calculates the number of orders created via the mobile app
	 * within the specified date filter.
	 *
	 * @param string $filter One of: 'today', 'week', 'month', 'year'.
	 *
	 * @return mixed The number of orders (integer).
	 */
	public function calculate( string $filter ): mixed {
		$args = array(
			'created_via' => YD_MOBILE_APP,
		);

		$target_time = array(
			self::FILTER_DATE_TODAY => DAY_IN_SECONDS,
			self::FILTER_DATE_WEEK  => WEEK_IN_SECONDS,
			self::FILTER_DATE_MONTH => MONTH_IN_SECONDS,
			self::FILTER_DATE_YEAR  => YEAR_IN_SECONDS,
		);

		$args['date_created'] = '>' . ( time() - $target_time[ $filter ] );
		$orders               = wc_get_orders( $args );

		return count( $orders );
	}
}
