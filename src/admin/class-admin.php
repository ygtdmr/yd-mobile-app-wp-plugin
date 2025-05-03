<?php
/**
 * Admin: Mobile App Integration
 *
 * This file defines the `Admin` class, which integrates mobile app features into the WooCommerce admin panel.
 * It extends the base `YD\Admin` class and registers WooCommerce-related hooks to send user announcements
 * when order statuses change or customer notes are added. The class also defines the pages and AJAX actions
 * available in the mobile app admin interface.
 *
 * @package YD
 * @subpackage Mobile_App
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App;

use YD\Utils;
use YD\Mobile_App\Widget\Action;
use YD\Mobile_App\Admin\Page;
use YD\Mobile_App\Admin\Ajax;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class integrates mobile app features into WooCommerce admin panel.
 *
 * It extends the base YD\Admin class and registers WooCommerce-related hooks
 * to send user announcements when order statuses change or customer notes are added.
 * Also defines the pages and AJAX actions available in the mobile app admin interface.
 */
final class Admin extends \YD\Admin {

	/**
	 * Registers WooCommerce hooks and initializes parent constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action(
			'woocommerce_order_status_changed',
			function ( int $order_id, string $from, string $to, \WC_Order $order ) {
				$user  = new Utils\User( $order->get_user_id() );
				$title = sprintf(
					// translators: %1$s is order ID text (e.g., "Order ID: 123"), used in the order status changed message.
					__( '%s order status changed.', 'woocommerce' ),
					sprintf(
						// translators: %1$s is the label "Order ID", %2$d is the order ID number.
						'%s: %d,',
						__( 'Order ID', 'woocommerce' ),
						$order_id
					)
				);
				$message = sprintf(
					// translators: %1$s is the previous order status, %2$s is the new order status.
					__( 'Order status changed from %1$s to %2$s.', 'woocommerce' ),
					wc_get_order_status_name( $from ),
					wc_get_order_status_name( $to )
				);
				$user->set_announcement(
					$title,
					$message,
					new Action(
						'custom',
						array(
							'page'     => 'order-main',
							'order_id' => strval( $order_id ),
						)
					)
				);
			},
			10,
			4
		);

		add_action(
			'woocommerce_order_note_added',
			function ( int $comment_id, \WC_Order $order ) {
				$comment = get_comment( $comment_id );
				$user    = new Utils\User( $order->get_user_id() );

				$is_customer_note = (int) get_comment_meta( $comment_id, 'is_customer_note', true );
				$via_app          = YD_MOBILE_APP === $comment->comment_agent;

				if ( $is_customer_note && ! $via_app ) {
					$text     = $comment->comment_content;
					$order_id = $order->get_id();

					if ( strlen( $text ) > 64 ) {
						$text = substr( $text, 0, 64 ) . '...';
					}

					$user->set_announcement(
						sprintf(
							'%s: %d, %s %s',
							__( 'Order ID', 'woocommerce' ),
							$order_id,
							__( 'New', 'woocommerce' ),
							__( 'Order Note', 'woocommerce' )
						),
						$text,
						new Action(
							'custom',
							array(
								'page'     => 'order-messages',
								'order_id' => strval( $order_id ),
							)
						)
					);
				}
			},
			10,
			2
		);
	}

	/**
	 * Returns the admin pages to be registered for the mobile app.
	 *
	 * @return array List of admin page classes.
	 */
	protected function get_pages(): array {
		return array(
			Page\Mobile_App::class,
			Page\Overview::class,
			Page\Widgets::class,
			Page\Announcement::class,
			Page\Language::class,
			Page\Settings::class,
		);
	}

	/**
	 * Returns the AJAX action handlers for admin-related operations.
	 *
	 * @return array List of AJAX action classes.
	 */
	protected function get_ajax_actions(): array {
		return array(
			Ajax\Stat::class,
			Ajax\Page_Search::class,
			Ajax\Post_Search::class,
			Ajax\WC_Search::class,
			Ajax\URL_Media::class,
			Ajax\Language::class,
			Ajax\Language_Search::class,
			Ajax\Auto_Translate_Status::class,
		);
	}
}
