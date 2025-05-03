/**
 * Auto-Translate Status Polling Script
 * Periodically polls the server to fetch live translation progress and updates the UI with the current status and progress bar.
 * Automatically redirects the user to the language page when the translation is complete.
 * Uses a fixed interval and gracefully handles active/inactive translation states.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

jQuery(function ($) {
  /**
   * Delay between status checks in milliseconds
   *
   * @type {number}
   */
  const delay = 3000;

  /**
   * jQuery element where status text is displayed
   *
   * @type {JQuery<HTMLElement>}
   */
  const textStatus = $("#text");

  /**
   * jQuery element representing the progress bar
   *
   * @type {JQuery<HTMLElement>}
   */
  const progressBar = $(".progressbar > div");

  setInterval(() => {
    window.yd_core.action.runAjax(
      /**
       * Callback to handle response data from auto-translate-status action
       *
       * @param {Object} response - Response object from server
       * @param {Object} response.size - Size information about translations
       * @param {number} response.size.translated_translates - Number of translated strings
       * @param {number} response.size.target_translates - Total number of strings to translate
       * @param {boolean} response.is_translating - Indicates if translation is in progress
       */
      (response) => {
        const { size, is_translating } = response;

        /**
         * Whether the status should be considered active based on size object
         *
         * @type {boolean}
         */
        const isStatusActive = Object.entries(size).length > 0;

        if (isStatusActive) {
          /**
           * Number of translated items
           *
           * @type {number}
           */
          const sizeTranslatedTranslates = size.translated_translates;

          /**
           * Total number of items to translate
           *
           * @type {number}
           */
          const sizeTargetTranslates = size.target_translates;

          textStatus.text(
            `${window.yd_core.ui.getText("Translating")} (${sizeTranslatedTranslates}/${sizeTargetTranslates})`,
          );

          progressBar.css(
            "width",
            `${(sizeTranslatedTranslates / sizeTargetTranslates) * 100}%`,
          );
        } else if (!is_translating) {
          window.location = window.yd_core.url.page.language;
        }
      },
      "auto-translate-status",
    );
  }, delay);
});
