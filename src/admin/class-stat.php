<?php
/**
 * Admin: Stat
 *
 * This file defines the abstract `Stat` class, which provides the structure for various types
 * of admin statistics. This class offers a standard interface for calculating statistical values
 * such as total sales, order count, customer count, etc., within defined date ranges.
 *
 * @package YD
 * @subpackage Mobile_App\Admin
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Stat class defines the structure for different types of admin statistics.
 *
 * This class provides a standard interface for calculating statistical values
 * such as total sales, order count, customer count, etc., within defined date ranges.
 */
abstract class Stat {

	/**
	 * Date filter constant for today's statistics.
	 */
	const FILTER_DATE_TODAY = 'today';

	/**
	 * Date filter constant for this week's statistics.
	 */
	const FILTER_DATE_WEEK = 'week';

	/**
	 * Date filter constant for this month's statistics.
	 */
	const FILTER_DATE_MONTH = 'month';

	/**
	 * Date filter constant for this year's statistics.
	 */
	const FILTER_DATE_YEAR = 'year';

	/**
	 * Returns the unique type identifier for the stat.
	 *
	 * @return string
	 */
	abstract public function get_type(): string;

	/**
	 * Returns the display name for the stat to be shown in the UI.
	 *
	 * @return string
	 */
	abstract public function get_display_name(): string;

	/**
	 * Returns a short description of the stat.
	 *
	 * @return string
	 */
	abstract public function get_description(): string;

	/**
	 * Calculates and returns the statistical data based on the given filter.
	 *
	 * @param string $filter One of the FILTER_DATE_* constants.
	 *
	 * @return mixed
	 */
	abstract public function calculate( string $filter ): mixed;

	/**
	 * Factory method to get a stat instance based on its type.
	 *
	 * @param string $type The type of stat to retrieve.
	 *
	 * @return self|null
	 */
	public static function get_stat_by_type( string $type ): ?self {
		switch ( $type ) {
			case 'total-sales':
				return new Stat\Total_Sales();
			case 'orders-count':
				return new Stat\Orders_Count();
			case 'customers-count':
				return new Stat\Customers_Count();
			case 'users-count':
				return new Stat\Users_Count();
			default:
				return null;
		}
	}
}
