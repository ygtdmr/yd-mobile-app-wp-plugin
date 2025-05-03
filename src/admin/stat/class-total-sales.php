<?php
/**
 * Total_Sales: Calculates the total sales amount from paid WooCommerce orders created via the mobile app
 *
 * This class defines the calculation of total sales for orders that were placed via the mobile app.
 * It provides methods to calculate total sales amounts based on predefined time filters (e.g., today, week, month, year).
 * The result is returned as a formatted HTML string representing the total sales amount.
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
 * Total_Sales class calculates the total sales amount from paid WooCommerce orders
 * that were created via the mobile app.
 *
 * This statistic filters the results based on a given time range.
 */
final class Total_Sales extends \YD\Mobile_App\Admin\Stat {

	/**
	 * Returns the stat type identifier.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'total-sales';
	}

	/**
	 * Returns the display name for this stat.
	 *
	 * @return string
	 */
	public function get_display_name(): string {
		return __( 'Total Sales', 'yd-mobile-app' );
	}

	/**
	 * Returns a description of what this stat represents.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return __( 'Shows the total amount of all paid orders.', 'yd-mobile-app' );
	}

	/**
	 * Calculates the total sales amount for the given time filter.
	 *
	 * @param string $filter One of the predefined time filter constants (e.g. 'today', 'week', 'month', 'year').
	 *
	 * @return mixed The formatted total sales amount (HTML string).
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

		$args['date_paid'] = '>' . ( time() - $target_time[ $filter ] );
		$orders            = wc_get_orders( $args );

		$total = 0;
		$total = array_map(
			function ( $order ) {
				return $order->get_total(); },
			$orders
		);
		$total = array_reduce(
			$total,
			function ( $carry, $item ) {
				$carry += $item;
				return $carry; }
		);

		return wc_price( $total );
	}
}
