/**
 * Mobile App Language Management Script
 * Provides a full-featured interface for managing multilingual translations within a mobile app dashboard.
 * Supports infinite scroll loading, tab-based locale switching, dynamic item creation/deletion,
 * translation status tracking, inline editing of default and target texts, and batch submission of changes.
 * Uses AJAX for asynchronous interaction and includes robust UI state syncing and scroll behavior handling.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

jQuery(function ($) {
  /**
   * Current running AJAX request
   *
   * @type {XMLHttpRequest|null}
   */
  let xhr = null;

  /**
   * Current pagination state
   *
   * @type {number}
   */
  let itemsCurrentPage = 1;

  const root = $(".yd-mobile-app.language");
  const form = root.find("form");

  const contentList = form.find("ul.content-list");
  const tabs = form.find(".language-tabs");

  const buttonSubmit = root.find("#submit");
  const buttonTranslate = root.find(
    '.page-title-action[data-action="translate"]',
  );
  const buttonAddItem = root.find('.page-title-action[data-action="new"]');
  const buttonRemoveAll = form.find('.button[data-action="remove-all"]');
  const buttonRemove = form.find('.delete[data-action="remove"]');

  const textDefault = form.find("#text-default");
  const textTarget = form.find("#text-target");

  const acceptedLanguages = window.yd_core.language.accepted;

  /**
   * Currently selected list item for translation
   *
   * @type {jQuery|null}
   */
  let currentTranslateItem = null;

  /**
   * Currently selected locale for translation target
   *
   * @type {string|null}
   */
  let currentTargetLocale = null;

  /**
   * Tracks latest scrollTop value
   *
   * @type {number}
   */
  let latestScrollTop = 0;

  /**
   * Observer to detect when the last item is visible (for lazy loading)
   *
   * @type {IntersectionObserver}
   */
  const scrollObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && isAjaxDone()) {
      getItems();
      contentList.scrollTop(contentList.scrollTop() + 32);
    }
  });

  /**
   * Finds a content list item by its default text
   *
   * @param {string} [defaultText]
   * @returns {jQuery}
   */
  const findItem = (defaultText) => {
    let dom;
    if (defaultText === undefined) {
      dom = currentTranslateItem;
    } else {
      dom = contentList
        .find("li[data-default-text]")
        .filter((i, e) => e.getAttribute("data-default-text") === defaultText);
    }
    return dom;
  };

  /**
   * Checks if an item is newly created
   *
   * @param {string} defaultText
   * @returns {boolean}
   */
  const isNewItem = (defaultText) => {
    return findItem(defaultText).attr("data-new")?.length > 0;
  };

  /**
   * Executes an AJAX request and caches the current XHR.
   *
   * @param {string} method - HTTP method (GET or POST)
   * @param {Function} onDone - Callback on success
   * @param {Object} [data={}] - Data to send
   */
  const runAjax = (method, onDone, data = {}) => {
    if (xhr) xhr.abort();
    xhr = window.yd_core.action.runAjax(
      (responseData) => {
        onDone?.(responseData);
      },
      "language",
      jQuery.extend(
        { _wpnonce: form.find('input[name="_wpnonce"]').val() },
        data,
      ),
      method,
    );
  };

  /**
   * Determines whether the current AJAX request has completed.
   *
   * @returns {boolean}
   */
  const isAjaxDone = () => xhr.readyState === 4;

  /**
   * Returns the appropriate CSS class for a flag icon
   *
   * @param {string} locale
   * @returns {string}
   */
  const getFlagClass = (locale) =>
    "flag " + (locale.split("_")[1] ?? "s-" + locale).toLowerCase();

  /**
   * Updates the UI tabs based on filled languages
   *
   * @param {string[]} filledLanguages
   * @param {string|null} [keepActiveTab=null]
   */
  const updateFlags = (filledLanguages, keepActiveTab = null) => {
    tabs.empty();
    const newTabs = Object.keys(acceptedLanguages)
      .sort((a, b) => filledLanguages.includes(a) > filledLanguages.includes(b))
      .map((locale) => {
        const displayName = acceptedLanguages[locale];
        return $(`
				<span tabindex="0" data-target-locale="${locale}" class="nav-tab ${filledLanguages.includes(locale) ? "filled" : ""} ${keepActiveTab === locale ? "nav-tab-active" : ""}">
					<span class="${getFlagClass(locale)}"></span>
					${displayName}
				</span>
				`).on("click", (e) => {
          const target = $(e.currentTarget);
          if (target.hasClass("nav-tab-active")) return;

          const targetLocale = target.attr("data-target-locale");
          const defaultText = contentList
            .find("li.selected")
            .attr("data-default-text");

          currentTargetLocale = targetLocale;

          tabs.find(".nav-tab-active").removeClass("nav-tab-active");
          target.addClass("nav-tab-active");
          if (filledLanguages.includes(targetLocale)) {
            getTargetText(defaultText, targetLocale);
          } else {
            textTarget.removeAttr("disabled").val("");
          }
        });
      });
    tabs.append(newTabs);
  };

  /**
   * Updates the current list item's language status based on input
   */
  const updateCurrentItemFilledLanguages = () => {
    let filledLanguages = JSON.parse(
      currentTranslateItem.attr("data-filled-languages"),
    );
    let currentRemovedLanguages = JSON.parse(
      currentTranslateItem.attr("data-removed-languages"),
    );

    if (textTarget.val().length) {
      if (!filledLanguages.includes(currentTargetLocale)) {
        filledLanguages.push(currentTargetLocale);
      }
      currentRemovedLanguages = currentRemovedLanguages.filter(
        (locale) => locale !== currentTargetLocale,
      );
    } else {
      if (!currentRemovedLanguages.includes(currentTargetLocale)) {
        currentRemovedLanguages.push(currentTargetLocale);
      }
      filledLanguages = filledLanguages.filter(
        (locale) => locale !== currentTargetLocale,
      );
    }

    currentTranslateItem
      .attr("data-filled-languages", JSON.stringify(filledLanguages))
      .attr("data-removed-languages", JSON.stringify(currentRemovedLanguages))
      .removeClass()
      .addClass("selected " + getFillType(filledLanguages));

    updateFlags(filledLanguages, currentTargetLocale);
  };

  /**
   * Updates the item text displayed in the list (shortened if needed)
   *
   * @param {jQuery} item
   * @param {string} text
   */
  const updateItemText = (item, text) => {
    const maxLength = 16;
    item.text(
      text.length >= maxLength ? text.slice(0, maxLength) + "..." : text,
    );
  };

  /**
   * Creates and renders a language item element
   *
   * @param {string} defaultText - The default language string
   * @param {string[]} filledLanguages - Array of filled target locales
   * @param {boolean} [isNew=false] - Whether the item is newly created
   */
  const createItem = (defaultText, filledLanguages, isNew = false) => {
    const fillType = getFillType(filledLanguages);

    const item = $(`<li ${isNew ? 'data-new="1"' : ""} tabindex="0"></li>`)
      .attr("data-default-text", defaultText)
      .attr("data-filled-languages", JSON.stringify(filledLanguages))
      .attr("data-removed-languages", "[]")
      .addClass(fillType)
      .on("click", (e) => {
        const currentTarget = $(e.currentTarget);
        if (currentTarget.hasClass("selected")) return;

        currentTranslateItem = currentTarget;

        textDefault.removeAttr("disabled");
        textTarget.val("").attr("disabled", "disabled");
        contentList.find(".selected").removeClass("selected");
        currentTarget.addClass("selected");

        currentTargetLocale = null;

        buttonRemove.css("visibility", "visible");

        textDefault.val(
          currentTarget.attr("data-new-default-text") ??
            currentTarget.attr("data-default-text"),
        );

        updateFlags(currentTarget.attr("data-filled-languages"));
      });

    updateItemText(item, defaultText);

    if (isNew) {
      contentList.prepend(item);
      item.click();
    } else {
      contentList.append(item);
    }
  };

  /**
   * Loads target translation text for a specific locale
   *
   * @param {string} defaultText
   * @param {string} targetLocale
   */
  const getTargetText = (defaultText, targetLocale) => {
    let currentValue;

    if (
      isNewItem(defaultText) ||
      currentTranslateItem.attr("data-target-" + targetLocale)?.length
    ) {
      currentValue =
        currentTranslateItem.attr("data-target-" + targetLocale) ?? "";
    }

    if (currentValue !== undefined) {
      textTarget.removeAttr("disabled").val(currentValue);
    } else {
      textTarget.val("").attr("disabled", "disabled");
      const spinner = form.find(".edit > .target > .spinner");
      spinner.addClass("is-active");
      runAjax(
        "GET",
        (text) => {
          spinner.removeClass("is-active");
          textTarget.removeAttr("disabled").val(text);
        },
        { default_text: defaultText, target_locale: targetLocale },
      );
    }
  };

  /**
   * Determines the fill type (filled, half-filled, empty) of a translation item
   *
   * @param {string[]} filledLanguages
   * @returns {string}
   */
  const getFillType = (filledLanguages) => {
    const isFilled = () =>
      JSON.stringify(filledLanguages.sort()) ===
      JSON.stringify(Object.keys(acceptedLanguages).sort());
    return isFilled(filledLanguages)
      ? "filled"
      : filledLanguages.length > 0
        ? "half-filled"
        : "";
  };

  /**
   * Loads translation items from the server and renders them
   */
  const getItems = () => {
    if (itemsCurrentPage !== -1) {
      const spinner = contentList.find(".spinner");
      spinner.addClass("is-active").show().appendTo(contentList);

      runAjax(
        "GET",
        (items) => {
          if (itemsCurrentPage === 1) {
            for (const [_, filledLanguages] of Object.entries(items)) {
              if (getFillType(filledLanguages) !== "filled") {
                buttonTranslate.show();
                break;
              }
            }
          }

          latestScrollTop = contentList.scrollTop();

          for (const [defaultText, filled] of Object.entries(items)) {
            createItem(defaultText, filled);
          }

          contentList.scrollTop(latestScrollTop);

          if (Object.keys(items).length) {
            itemsCurrentPage++;
          } else {
            itemsCurrentPage = -1;
          }

          if (contentList.children("li").length) {
            scrollObserver.disconnect();
            scrollObserver.observe(contentList.children("li").last()[0]);
          }

          spinner.removeClass("is-active").hide();
          buttonAddItem.show();
        },
        { page: itemsCurrentPage },
      );
    }
  };

  /**
   * Scrolls the language tab container to the active tab
   */
  const scrollToActiveTab = () => {
    const activeTab = tabs.find(".nav-tab-active");
    const prevTabs = activeTab.prevAll();

    let currentPositionLeft = 0;
    for (let index = 0; index < prevTabs.length - 1; index++) {
      const tab = $(prevTabs[index]);
      const gap = index > 0 ? 8 : 0;
      const width = tab.outerWidth() + gap;
      currentPositionLeft += width;
    }

    tabs.scrollLeft(currentPositionLeft);
  };

  form.on("submit", (e) => {
    e.preventDefault();

    const spinner = form.find(".actions > .spinner");
    spinner.addClass("is-active");

    const getChangedItems = () => {
      const data = {};
      contentList.find("li:not([data-is-delete])").each((_, item) => {
        item = $(item);
        const filledLanguages = JSON.parse(item.attr("data-filled-languages"));
        const removedLanguages = JSON.parse(
          item.attr("data-removed-languages"),
        );
        const defaultText = item.attr("data-default-text");

        data[defaultText] = {};

        filledLanguages.forEach((locale) => {
          const targetText = item.attr("data-target-" + locale);
          if (targetText !== undefined) data[defaultText][locale] = targetText;
        });

        if (removedLanguages.length) {
          data[defaultText]["removed_targets"] = removedLanguages;
        }

        if (item.attr("data-new")) {
          data[defaultText]["is_new"] = true;
        } else if (item.attr("data-new-default-text")) {
          data[defaultText]["new_default_text"] = item.attr(
            "data-new-default-text",
          );
        }

        if (!Object.keys(data[defaultText]).length) {
          delete data[defaultText];
        }
      });
      return data;
    };

    const getRemovedItems = () =>
      Array.from(contentList.find("li[data-is-delete]")).map((item) =>
        item.getAttribute("data-default-text"),
      );

    const requestData = {
      removed_items: getRemovedItems(),
      changed_items: getChangedItems(),
    };

    runAjax(
      "POST",
      () => {
        window.location = window.location;
      },
      requestData,
    );
  });

  buttonRemove.on("click", () => {
    if (
      window.confirm(
        window.yd_core.ui.getText("Are you sure you want to do this?"),
      )
    ) {
      if (isNewItem()) {
        currentTranslateItem.remove();
      } else {
        currentTranslateItem.attr("data-is-delete", "1").hide();
      }

      currentTargetLocale = null;

      tabs.empty();

      textDefault.val("").attr("disabled", "disabled");
      textTarget.val("").attr("disabled", "disabled");

      buttonRemove.css("visibility", "hidden");

      $(window.document).trigger("yd-form-change");
    }
  });

  buttonAddItem.on("click", () => {
    const newItemsLength = contentList.find("li[data-new]").length;

    const defaultText =
      `${window.yd_core.ui.getText("New translate")} ` + (newItemsLength + 1);

    createItem(defaultText, [], true);

    $(window.document).trigger("yd-form-change");

    contentList.scrollTop(0);
  });

  buttonRemoveAll.on("click", () => {
    if (
      window.confirm(
        window.yd_core.ui.getText("Are you sure you want to do this?"),
      )
    ) {
      const spinner = form.find(".actions > .spinner");
      spinner.addClass("is-active");

      buttonSubmit.attr("disabled", "disabled");

      runAjax(
        "POST",
        () => {
          $(window.document).trigger("yd-form-change", { changed: false });
          window.location = window.location;
        },
        { remove_all: true },
      );
    }
  });

  $("textarea").on("input", (e) => {
    const isTextDefault = e.currentTarget === textDefault[0];

    if (isTextDefault) {
      const defaultText = textDefault.val();

      updateItemText(currentTranslateItem, defaultText);

      currentTranslateItem.attr(
        isNewItem() ? "data-default-text" : "data-new-default-text",
        defaultText,
      );
    } else {
      currentTranslateItem.attr(
        "data-target-" + currentTargetLocale,
        textTarget.val(),
      );
      updateCurrentItemFilledLanguages();

      scrollToActiveTab();
    }

    $(window.document).trigger("yd-form-change");
  });

  getItems();
});
