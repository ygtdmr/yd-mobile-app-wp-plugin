<?php
/**
 * This file defines the `Widget_Sortable` abstract class, which provides the foundational
 * structure for sortable widget views in the WordPress admin interface of the YD Mobile App plugin.
 * It outputs JavaScript-based configuration for dynamic sorting and expects subclasses to define
 * their own item rendering logic.
 *
 * @package YD\Mobile_App
 * @subpackage Admin\Page\View
 * @author Yigit Demir
 * @since 1.0.0
 * @version 1.0.0
 */

namespace YD\Mobile_App\Admin\Page\View;

defined( 'ABSPATH' ) || exit;

/**
 * Widget_Sortable abstract class defines the structure for sortable widgets
 * used in the admin panel of the YD Mobile App plugin.
 *
 * It injects JavaScript variables to handle sortable items dynamically
 * and provides a visual container where those items will appear.
 *
 * Subclasses must implement the item DOM template.
 */
abstract class Widget_Sortable {

	/**
	 * Constructor method initializes the sortable interface.
	 *
	 * Outputs the JavaScript configuration for sortable items
	 * and renders an empty container where sortable items will be displayed.
	 *
	 * @param \YD\Mobile_App\Widget $widget The widget instance providing sortable item data.
	 */
	public function __construct( \YD\Mobile_App\Widget $widget ) {
		?>
		<script>
			window.yd_core.page.block = {
				items: <?php echo wp_json_encode( $widget->get_items() ); ?>,
				itemDom: `<?php $this->get_item_dom(); ?>`
			};
		</script>
		<br>
		<div class="ui-sortable"></div>
		<?php
	}

	/**
	 * Renders the HTML template for a single sortable item.
	 *
	 * This method must be implemented by subclasses to define
	 * how each sortable item should be displayed.
	 *
	 * @return void
	 */
	abstract protected function get_item_dom();
}

?>
