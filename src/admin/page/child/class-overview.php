<?php
/**
 * Admin Page: Overview
 *
 * This file defines the `Overview` class, which provides an overview of key statistics
 * related to the mobile app. It displays real-time data on sales, orders, and customers
 * (or users depending on the setup), with the ability to filter by different time ranges.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page;

use YD\Utils;
use YD\Admin\Page;
use YD\Mobile_App\Admin\Stat;

defined( 'ABSPATH' ) || exit;

/**
 * Overview page displays an overview of key statistics related to the mobile app.
 * It provides real-time data on sales, orders, and customers (or users depending on the setup).
 */
final class Overview extends \YD\Admin\Page {

	/**
	 * Retrieves the title for the overview page.
	 *
	 * @return string The title for the page.
	 */
	protected function get_title(): string {
		return __( 'Overview' );
	}

	/**
	 * Retrieves the menu title for the overview page.
	 *
	 * @return string The menu title.
	 */
	protected function get_menu_title(): string {
		return __( 'Overview' );
	}

	/**
	 * Retrieves the slug for the page.
	 *
	 * @return string The page slug.
	 */
	protected function get_slug(): string {
		return '';
	}

	/**
	 * Retrieves the capability required to access the page.
	 *
	 * @return string The capability required.
	 */
	protected function get_capability(): string {
		return 'yd_manage_mobile_app';
	}

	/**
	 * Retrieves the parent slug for the page.
	 *
	 * @return string The parent slug.
	 */
	protected function get_parent_slug(): string {
		return YD_MOBILE_APP;
	}

	/**
	 * Renders the page content, including the statistics grid and tabs.
	 *
	 * It dynamically fetches stats and provides functionality to filter the data by time range (today, week, month, year).
	 *
	 * @return void
	 */
	protected function callback() {
		?><script> window.yd_core.wp_nonce.stat = '<?php echo( esc_js( wp_create_nonce( 'stat' ) ) ); ?>'; </script>
		<?php

		Page::enqueue_style( 'overview.css' );
		Page::enqueue_script( 'overview.js' );

		$stats = $this->get_stats();
		?>
		<div class="wrap overview">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Overview' ); ?></h1>
			<hr class="wp-header-end">

			<div id="poststuff" class="summary-stats">
				<div class="summary-stats-tabs">
					<span data-selected="1" data-stat-filter="today"><?php esc_html_e( 'Today' ); ?></span><span data-stat-filter="week"><?php esc_html_e( 'This week', 'yd-mobile-app' ); ?></span><span data-stat-filter="month"><?php esc_html_e( 'This month', 'yd-mobile-app' ); ?></span><span data-stat-filter="year"><?php esc_html_e( 'This year', 'yd-mobile-app' ); ?></span>
				</div>
				<div class="summary-stats-grid">
					<?php foreach ( $stats as $stat ) : ?>
					<div data-stat-type="<?php echo( esc_attr( $stat->get_type() ) ); ?>" class="stuffbox">
						<h2><?php echo( esc_html( $stat->get_display_name() ) ); ?></h2>
						<div class="inside">
							<span class="spinner is-active"></span>
							<h1 class="stat-value" style="display:none;"></h1>
							<p><?php echo( esc_html( $stat->get_description() ) ); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			
		</div>
		<?php
	}

	/**
	 * Retrieves the statistics to be displayed on the overview page.
	 *
	 * The stats vary based on whether WooCommerce is supported or not.
	 *
	 * @return array The list of statistics objects to be shown.
	 */
	private function get_stats(): array {
		$stats = array();
		if ( Utils\WC::is_support() ) {
			array_push(
				$stats,
				new Stat\Total_Sales(),
				new Stat\Orders_Count(),
				new Stat\Customers_Count()
			);
		} else {
			array_push( $stats, new Stat\Users_Count() );
		}
		return $stats;
	}
}

?>