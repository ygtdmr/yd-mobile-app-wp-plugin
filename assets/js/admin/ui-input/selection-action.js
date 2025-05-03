/**
 * YD_Input_Selection_Action class
 * A dynamic UI component for creating and managing action-based input structures with a selectable target and flexible parameters.
 * Supports both predefined and custom targets, allows param customization based on the selected target,
 * and integrates with existing YD input types (text, checkbox, selection) for value input.
 * Designed to support real-time UI reactivity, data binding, and multilingual placeholder integration.
 *
 * Author: Yigit Demir
 * Since: 1.0.0
 * Version: 1.0.0
 */

"use strict";

class YD_Input_Selection_Action {
  /**
   * Root DOM element for the component
   *
   * @type {jQuery}
   */
  #rootDom;

  /**
   * Configuration object for the component
   *
   * @type {Object}
   */
  #config;

  /**
   * Dropdown option definitions
   *
   * @type {Object}
   */
  #dropdownOptions;

  /**
   * Current value of the component
   *
   * @type {Object}
   */
  #value;

  /**
   * DOM element for the "Add param" button
   *
   * @type {jQuery}
   */
  #buttonAddParam;

  /**
   * DOM element for the "Clear params" button
   *
   * @type {jQuery}
   */
  #buttonClearParam;

  /**
   * jQuery reference to the input DOM for the target selector
   *
   * @type {jQuery}
   */
  #inputTarget;

  /**
   * DOM element wrapping the entire parameters section
   *
   * @type {jQuery}
   */
  #paramsDom;

  /**
   * DOM element containing the list of all added parameters
   *
   * @type {jQuery}
   */
  #paramsListDom;

  /**
   * Creates an instance of YD_Input_Selection_Action.
   *
   * @param {HTMLElement|string} rootDom - Root DOM element or selector
   * @param {string} config - JSON string for configuration
   * @param {string} dropdownOptions - JSON string defining dropdown targets and parameters
   * @param {string} value - JSON string for the initial value
   */
  constructor(rootDom, config, dropdownOptions, value) {
    this.#rootDom = jQuery(rootDom);
    this.#config = JSON.parse(config);
    this.#dropdownOptions = JSON.parse(dropdownOptions);
    this.#value = JSON.parse(value || "{}");

    this.#render().then(() => {
      this.#loadEvents();
    });
  }

  /**
   * Initializes the UI components (via yd_core)
   *
   * @private
   * @returns {Promise<void>}
   */
  #initUI() {
    return window.yd_core.ui.init();
  }

  /**
   * Returns the root DOM element
   *
   * @returns {jQuery}
   */
  getRootDom() {
    return this.#rootDom;
  }

  /**
   * Returns a config value by key
   *
   * @param {string} key - Config key to retrieve
   * @returns {*}
   */
  getConfig(key) {
    return this.#config[key];
  }

  /**
   * Modifies a key in the config object
   *
   * @param {string} key - Key to set
   * @param {*} value - Value to assign
   */
  modifyConfig(key, value) {
    Object.defineProperty(this.#config, key, { value: value });
  }

  /**
   * Renders the component structure and initializes dropdown UI
   *
   * @private
   * @returns {Promise<void>}
   */
  async #render() {
    this.#rootDom.prepend(`
            <div class="selection-action">
                <div class="target">
                    <h4>${window.yd_core.ui.getText("Target")}</h4>
                    <div class="yd-admin-ui-input yd-admin-ui-input-dropdown"></div>
                </div>
                <div class="params">
                    <br/>
                    <h4>${window.yd_core.ui.getText("Params")}</h4>
                    <span class="button action" data-action="add" tabindex="0">${window.yd_core.ui.getText("Add param")}</span><span class="button action delete" data-action="clear" tabindex="0">${window.yd_core.ui.getText("Clear params")}</span>
                    <div class="params-list"></div>
                </div>
            </div>
        `);

    this.#rootDom
      .find(".target .yd-admin-ui-input-dropdown")
      .attr(
        "data-config",
        JSON.stringify({
          data_name: this.#config.data_name + "[target]",
          options: this.#dropdownOptions.targets,
        }),
      )
      .attr("data-value", this.#value.target);

    await this.#initUI().then(() => {
      this.#inputTarget = window.yd_core.ui
        .findByDom(this.#rootDom.find(".target .yd-admin-ui-input-dropdown"))
        .getInput();
    });

    this.#paramsDom = this.#rootDom.find(".params");
    this.#paramsListDom = this.#rootDom.find(".params-list");

    this.#buttonAddParam = this.#rootDom.find('[data-action="add"]');
    this.#buttonClearParam = this.#rootDom.find('[data-action="clear"]');
  }

  /**
   * Binds DOM event handlers for parameter interaction
   *
   * @private
   */
  #loadEvents() {
    const clearParams = (e) => {
      this.#paramsDom.find('[data-action="remove"]').click();
      e.preventDefault();
    };

    this.#buttonClearParam.on("click", clearParams);
    this.#inputTarget.on("change", (e) => {
      clearParams(e);
      if (this.#inputTarget.val() === "0") {
        this.#paramsDom.hide();
      } else {
        this.#paramsDom.show();
      }
    });

    const addParam = (key, value) => {
      const isTargetCustom = this.#inputTarget.val() === "custom";

      const paramDom = jQuery(`
                <div class="param">
                    ${
                      isTargetCustom
                        ? `
                            <div class="key">
                                <div class="yd-admin-ui-input yd-admin-ui-input-text"><input class="regular-text" type="text" placeholder="${window.yd_core.ui.getText("Key")}" value="${key ?? ""}" required/></div>
                            </div>
                            <div class="value">
                                <div class="yd-admin-ui-input yd-admin-ui-input-text"><input class="regular-text" type="text" placeholder="${window.yd_core.ui.getText("Value")}" name="${key?.length ? `${this.#config.data_name}[params][${key}]` : ""}" value="${value ?? ""}" required/></div>
                            </div>
                        `
                        : `
                        <div class="key"><div class="yd-admin-ui-input yd-admin-ui-input-dropdown"></div></div>
                        <div class="value"></div>
                        `
                    }
                    <div style="width: 64px; text-align: center;">
                        <span class="delete" data-action="remove" tabindex="0">${window.yd_core.ui.getText("Remove")}</span>
                    </div>
                </div>
            `);

      paramDom.find('[data-action="remove"]').on("click", (e) => {
        if (!this.#rootDom.hasClass("ignored")) {
          jQuery(window.document).trigger("yd-form-change");
        }
        paramDom.remove();
        e.preventDefault();
      });

      this.#paramsListDom.append(paramDom);

      if (isTargetCustom) {
        const inputKey = paramDom.find(".key input");
        const inputValue = paramDom.find(".value input");

        inputKey.on("change keyup", () => {
          inputValue.attr(
            "name",
            `${this.#config.data_name}[params][${inputKey.val()}]`,
          );
        });

        return;
      }

      const selectedParams =
        this.#dropdownOptions.params[this.#inputTarget.val()];
      const options = {};

      for (const key of Object.keys(selectedParams)) {
        options[key] = selectedParams[key].display_name;
      }

      paramDom
        .find(".key > .yd-admin-ui-input-dropdown")
        .attr("data-config", JSON.stringify({ options: options }))
        .attr("data-value", key);

      let dropdownKeyInput;

      this.#initUI().then(() => {
        dropdownKeyInput = window.yd_core.ui
          .findByDom(paramDom.find(".key > .yd-admin-ui-input-dropdown"))
          .getInput();
        dropdownKeyInput.on("change", () => {
          addParamValue();
        });

        addParamValue(value);
      });

      const addParamValue = (value = []) => {
        const valueDom = paramDom.find(".value");
        valueDom.empty();

        const { input_type, input_properties, display_name, ajax_action_name } =
          selectedParams[dropdownKeyInput.val()];

        const name = `${this.#config.data_name}[params][${dropdownKeyInput.val()}]`;

        switch (input_type) {
          case "text":
            valueDom.append(
              jQuery(
                `<div class="yd-admin-ui-input yd-admin-ui-input-text"></div>`,
              ).append(
                jQuery(
                  `<input class="regular-text" type="text" placeholder="${window.yd_core.ui.getText("Enter value")}" name="${name}" required/>`,
                ).attr("value", value),
              ),
            );
            break;
          case "checkbox":
            valueDom.append(
              jQuery(
                `<div class="yd-admin-ui-input yd-admin-ui-input-checkbox"></div>`,
              )
                .append(
                  jQuery(`<label></label>`)
                    .text(display_name)
                    .prepend(
                      `<input type="checkbox" ${value ? "checked" : ""}/>`,
                    ),
                )
                .append(
                  `<input type="hidden" value="${value ? 1 : 0}" name="${name}"/>`,
                ),
            );
            break;
          case "selection":
            valueDom.append(
              jQuery(
                '<div class="yd-admin-ui-input yd-admin-ui-input-selection"></div>',
              )
                .attr(
                  "data-config",
                  JSON.stringify({
                    data_name: name,
                    display_name: window.yd_core.ui.getText("Enter value"),
                    is_required: true,
                    is_multiple: input_properties.is_multiple,
                    ajax_action_name: ajax_action_name,
                  }),
                )
                .attr(
                  "data-properties",
                  JSON.stringify({ target: input_properties.target }),
                )
                .attr("data-value", JSON.stringify(value)),
            );
            break;
        }
        this.#initUI();
      };
    };

    if (this.#inputTarget.val() === "0") {
      this.#paramsDom.hide();
    } else {
      this.#paramsDom.show();
    }

    if (Object.keys(this.#value?.params ?? {}).length) {
      for (const [key, value] of Object.entries(this.#value.params)) {
        addParam(key, value);
      }
    }

    this.#buttonAddParam.on("click", (e) => {
      if (!this.#rootDom.hasClass("ignored")) {
        jQuery(window.document).trigger("yd-form-change");
      }
      addParam();
      e.preventDefault();
    });
  }
}
