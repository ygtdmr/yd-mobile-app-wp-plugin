/**
 * Custom Product or Category Filter Script
 * Dynamically switches the selection input's target between products and categories based on a checkbox state.
 * Also toggles visibility of additional filter options like "on sale" based on user interaction.
 * Enhances admin UI with responsive input behavior for product filtering configuration.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

jQuery(function ($) {
  /**
   * Reference to the selection input handler for products or categories
   *
   * @type {Object}
   */
  const selection = window.yd_core.ui.input["selection_product_or_category"];

  /**
   * Toggle selection target between products and categories based on checkbox state
   */
  $("#custom_products_input").on("change", (e) => {
    selection.modifyProperties(
      "target",
      e.target.checked ? "product" : "product_category",
    );

    // Clear current selection by simulating a remove click
    selection.getRootDom().find(".remove").click();
  });

  /**
   * Show or hide the "on sale" filter input row based on checkbox state
   */
  $("#filter_as_sale_input")
    .on("change", (e) => {
      /**
       * Input field for the "on sale" filter
       *
       * @type {JQuery<HTMLElement>}
       */
      const inputOnSale = $("#custom_param_on_sale");

      /**
       * Row container of the "on sale" input field
       *
       * @type {JQuery<HTMLElement>}
       */
      const parentInputOnSale = inputOnSale.closest("tr");

      if (e.currentTarget.checked) {
        parentInputOnSale.show();
      } else {
        parentInputOnSale.hide();
      }
    })
    .change();
});
