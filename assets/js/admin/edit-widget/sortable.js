/**
 * Sortable Post Box Manager Script
 * Provides a dynamic UI for managing sortable content blocks (postboxes) in the mobile app admin interface.
 * Supports adding, removing, and reordering of blocks, while maintaining proper data indexing for form submission.
 * Integrates with YD input components and ensures correct data-binding via dynamic attribute replacement.
 * Includes confirmation prompts, DOM reinitialization, and seamless scroll handling.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

jQuery(function ($) {
  /**
   * Root element for mobile app postbox management
   *
   * @type {JQuery<HTMLElement>}
   */
  const root = $(".yd-mobile-app");

  /**
   * Container for all sortable postboxes
   *
   * @type {JQuery<HTMLElement>}
   */
  const postBoxesRoot = root.find("#post-body-content .ui-sortable");

  /**
   * Button to add a new postbox
   *
   * @type {JQuery<HTMLElement>}
   */
  const addNewButton = root.find("#action-new");

  /**
   * Button to clear all postboxes
   *
   * @type {JQuery<HTMLElement>}
   */
  const clearButton = root.find("#action-clear");

  /**
   * Reference to page block configuration from yd_core
   *
   * @type {Object}
   */
  const pageBlock = window.yd_core.page.block;

  /**
   * Creates and sets up a postbox DOM element
   *
   * @param {number} index - Index of the postbox
   * @param {Object|null} data - Data to populate postbox with
   * @param {boolean} [isNew=false] - Whether the postbox is newly created
   * @returns {JQuery<HTMLElement>} - jQuery-wrapped postbox element
   */
  const setupPostBox = (index, data, isNew = false) => {
    var postBox = pageBlock.itemDom.replaceAll(
      "{SORTABLE_INDEX}",
      `[${index}]`,
    );

    /**
     * Escapes double quotes for use in HTML attributes
     *
     * @param {string} value - Input string
     * @returns {string} - Escaped string
     */
    const escAttr = (value) => value.replaceAll('"', "&quot;");

    if (!isNew) {
      for (const [key, value] of Object.entries(data)) {
        postBox = postBox.replaceAll(
          `{SORTABLE_DATA-${key}}`,
          typeof value === "object" ? escAttr(JSON.stringify(value)) : value,
        );
      }
    }

    postBox = postBox.replaceAll(/{SORTABLE_DATA-.+?}/g, "");
    postBox = $(postBox);

    const upButton = postBox.find(".handle-actions .handle-order-higher");
    const downButton = postBox.find(".handle-actions .handle-order-lower");
    const removeButton = postBox.find("[data-action=\"remove\"]");

    removeButton.on("click", (e) => {
      if (
        window.confirm(
          window.yd_core.ui.getText("Are you sure to remove this item?"),
        )
      ) {
        $(window.document).trigger("yd-form-change");
        postBox.remove();
      }
      updateCurrentIndex();
      e.preventDefault();

      if (!postBoxesRoot.children().length) clearButton.hide();
    });

    upButton.on("click", (e) => {
      e.preventDefault();
      if (postBox.prev().length) {
        postBox.insertBefore(postBox.prev());
      } else {
        postBox.insertAfter(postBoxesRoot.children().last());
      }
      $(window.document).trigger("yd-form-change");
      updateCurrentIndex();
      postBox.get(0).scrollIntoView();
    });

    downButton.on("click", (e) => {
      e.preventDefault();
      if (postBox.next().length) {
        postBox.insertAfter(postBox.next());
      } else {
        postBox.insertBefore(postBoxesRoot.children().first());
      }
      $(window.document).trigger("yd-form-change");
      updateCurrentIndex();
      postBox.get(0).scrollIntoView();
    });

    return postBox;
  };

  /**
   * Updates index references for all postboxes and their form data attributes
   */
  const updateCurrentIndex = () => {
    const replaceIndex = (value, newIndex) =>
      value?.replaceAll(/data\[items\]\[\d+\]/g, `data[items][${newIndex}]`);

    postBoxesRoot.find(".postbox-container").each((_, postBox) => {
      postBox = $(postBox);
      const newIndex = postBox.index();

      for (let item of postBox.find(`
		  input,
		  textarea,
		  .yd-admin-ui-input-dropdown,
		  .yd-admin-ui-input-selection,
		  .yd-admin-ui-input-selection-media,
		  .yd-admin-ui-input-selection-action
		`)) {
        item = $(item);

        if (item.hasClass("yd-admin-ui-input")) {
          const input = window.yd_core.ui.findByDom(item);

          if (input.getConfig)
            input.modifyConfig(
              "data_name",
              replaceIndex(input.getConfig("data_name"), newIndex),
            );

          const newConfig = replaceIndex(item.attr("data-config"), newIndex);
          if (newConfig) item.attr("data-config", newConfig);
        } else {
          const newName = replaceIndex(item.attr("name"), newIndex);
          if (newName) item.attr("name", newName);
        }
      }
    });
  };

  addNewButton.on("click", (e) => {
    $(window.document).trigger("yd-form-change");

    const newPostBox = setupPostBox(
      postBoxesRoot.children().length,
      null,
      true,
    );
    postBoxesRoot.append(newPostBox);
    window.yd_core.ui.init().then(() => {
      updateCurrentIndex();
      root.trigger("yd-sortable-item-loaded", newPostBox);
    });

    newPostBox[0].scrollIntoView();

    if (clearButton.is(":hidden")) clearButton.show();

    e.preventDefault();
  });

  clearButton.on("click", (e) => {
    if (
      window.confirm(
        window.yd_core.ui.getText("Are you sure you want to clear all?"),
      )
    ) {
      postBoxesRoot.empty();
      clearButton.hide();
      $(window.document).trigger("yd-form-change");
    }
    e.preventDefault();
  });

  for (let index = 0; index < pageBlock.items.length; index++) {
    postBoxesRoot.append(setupPostBox(index, pageBlock.items[index]));
    if (clearButton.is(":hidden")) clearButton.show();
  }

  window.yd_core.ui.init().then(() => {
    for (const postBox of postBoxesRoot.children()) {
      root.trigger("yd-sortable-item-loaded", postBox);
    }
  });
});
