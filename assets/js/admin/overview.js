/**
 * Mobile App Summary Stats Script
 * Provides dynamic stat rendering for mobile app dashboards, including tab-based filtering,
 * caching to avoid redundant AJAX calls, and visual feedback via spinners.
 * On interaction, it fetches stat values based on the selected filter and updates the UI accordingly.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

jQuery(function ($) {
  /**
   * Cache object for storing previously loaded statistics
   *
   * @type {Object}
   */
  const CACHE = {};

  /**
   * Root container for the mobile app component
   *
   * @type {jQuery}
   */
  const root = $(".yd-mobile-app");

  /**
   * Container for the summary stats block
   *
   * @type {jQuery}
   */
  const summaryStats = root.find(".summary-stats");

  /**
   * Tabs for selecting the filter on the summary stats
   *
   * @type {jQuery}
   */
  const summaryStatsTabs = summaryStats.find(".summary-stats-tabs");

  /**
   * All loading spinner elements within the summary stats grid
   *
   * @type {jQuery}
   */
  const allSpinnerSummaryStats = summaryStats.find(
    ".summary-stats-grid .spinner",
  );

  /**
   * All value elements within the summary stats grid
   *
   * @type {jQuery}
   */
  const allValueSummaryStats = summaryStats.find(
    ".summary-stats-grid .stat-value",
  );

  /**
   * List of all stat types used in the dashboard
   *
   * @type {Array<string>}
   */
  const allTypes = Array.from(
    summaryStats.find(".summary-stats-grid > [data-stat-type]"),
  ).map((e) => e.getAttribute("data-stat-type"));

  /**
   * Fetches and updates stats based on the provided filter
   *
   * @param {string} filter - Filter key (e.g., "today", "week", etc.)
   */
  const getStats = (filter) => {
    allSpinnerSummaryStats.show();
    allValueSummaryStats.empty().hide();

    /**
     * Updates DOM with fetched statistics
     *
     * @param {Object} stats - Key-value object of stat type to value
     */
    const updateStats = (stats) => {
      CACHE[filter] = stats;
      for (const [type, result] of Object.entries(stats)) {
        summaryStats
          .find(`[data-stat-type="${type}"] .stat-value`)
          .html(result);
      }
      allSpinnerSummaryStats.hide();
      allValueSummaryStats.show();
    };

    if (CACHE[filter] === undefined) {
      window.yd_core.action.runAjax(updateStats, "stat", {
        _wpnonce: window.yd_core.wp_nonce.stat,
        filter: filter,
        types: allTypes,
      });
    } else {
      updateStats(CACHE[filter]);
    }
  };

  // Bind tab clicks to fetch relevant stats
  summaryStatsTabs.children().on("click", (e) => {
    const tab = $(e.currentTarget);
    const filter = tab.attr("data-stat-filter");

    summaryStatsTabs.find("[data-selected]").removeAttr("data-selected");
    tab.attr("data-selected", "1");

    getStats(filter);
  });

  // Load today's stats by default
  getStats("today");
});
