/**
 * Provides dynamic creation, editing and live-preview of “text view” widgets
 * inside a slide container in the WP-Admin post-box for the YD Mobile App.
 *
 * Author:  Yigit Demir
 * Version: 1.0.0
 * Since:   1.0.0
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
   * Escapes double quotes so the value can be placed safely inside an
   * HTML attribute.
   *
   * @param  {string} value – Raw text.
   * @return {string} Escaped text.
   */
  const escAttr = (value) => value.replaceAll('"', "&quot;");

  /**
   * Creates one “text view” item in both the form *and* the live preview.
   * Also wires every input so that changes are mirrored instantly.
   *
   * @param {jQuery} textViews – Container <div class="text-views">.
   * @param {Object=} data     – Previously-saved configuration. Undefined = default.
   */
  const newTextView = (textViews, data) => {
    const sortableItemIndex = textViews.closest(".postbox-container").index();
    const textViewIndex = textViews.children().length;
    const dataKey = `widget_data[items][${sortableItemIndex}][text_views][${textViewIndex}]`;

    textViews.append(`
			<div class="text-view">
				<p>${window.yd_core.ui.getText("Text")}</p>
				<div data-preview-type="text" class="yd-admin-ui-input yd-admin-ui-input-textarea"><textarea class="regular-text" type="text" placeholder="${window.yd_core.ui.getText("Value")}" name="${dataKey}[text]" required>${escAttr(data?.text ?? "")}</textarea></div>
				<div class="yd-admin-ui-input yd-admin-ui-input-selection-action" data-config='{"data_name":"${dataKey}[action]"}' data-value="${escAttr(JSON.stringify(data?.action ?? ""))}" data-dropdown-options='{"targets":{"0":"${window.yd_core.ui.getText("None")}","page":"${window.yd_core.ui.getText("Page")}","post":"${window.yd_core.ui.getText("Post")}","post-list":"${window.yd_core.ui.getText("Posts")}","custom":"${window.yd_core.ui.getText("Custom")}","product":"${window.yd_core.ui.getText("Product")}","product-list":"${window.yd_core.ui.getText("Product list")}"},"params":{"page":{"id":{"ajax_action_name":"page-search","input_type":"selection","input_properties":{"is_multiple":false},"display_name":"${window.yd_core.ui.getText("Page title")}"}},"product":{"id":{"input_type":"selection","input_properties":{"is_multiple":false,"target":"product"},"display_name":"${window.yd_core.ui.getText("Product name")}"}},"product-list":{"search":{"input_type":"text","display_name":"${window.yd_core.ui.getText("Search")}"},"category":{"input_type":"selection","input_properties":{"is_multiple":true,"target":"product_category"},"display_name":"${window.yd_core.ui.getText("Categories")}"},"min_price":{"input_type":"text","display_name":"${window.yd_core.ui.getText("Min price")}"},"max_price":{"input_type":"text","display_name":"${window.yd_core.ui.getText("Max price")}"},"on_sale":{"input_type":"checkbox","display_name":"${window.yd_core.ui.getText("On sale")}"}},"post":{"id":{"input_type":"selection","ajax_action_name":"post-search","input_properties":{"is_multiple":false,"target":"post"},"display_name":"${window.yd_core.ui.getText("Post name")}"}},"post-list":{"search":{"input_type":"text","display_name":"${window.yd_core.ui.getText("Search")}"},"category":{"input_type":"selection","ajax_action_name":"post-search","input_properties":{"is_multiple":true,"target":"post_category"},"display_name":"${window.yd_core.ui.getText("Categories")}"},"tag":{"ajax_action_name":"post-search","input_type":"selection","input_properties":{"is_multiple":true,"target":"post_tag"},"display_name":"${window.yd_core.ui.getText("Tags")}"}}}}'></div>
				<h4 data-action="toggle-properties" tabindex="0">${window.yd_core.ui.getText("Properties")}</h4>
				<div class="properties">
					<p>${window.yd_core.ui.getText("Text color")}</p>
					<div data-preview-type="color" class="yd-admin-ui-input yd-admin-ui-input-color-picker"><input type="text" data-alpha-enabled="true" name="${dataKey}[color]" value="${data?.color ?? "rgb(0,0,0)"}"/></div>
					<p>${window.yd_core.ui.getText("Text font size")}</p>
					<input data-preview-type="size" class="small-text" type="number" name="${dataKey}[size]" value="${data?.size ?? "16"}" min="8" max="128">
					<p>${window.yd_core.ui.getText("Text alignment")}</p>
					<div data-preview-type="alignment" class="yd-admin-ui-input yd-admin-ui-input-dropdown" data-value="${data?.alignment ?? "left"}" data-config="${escAttr(
            JSON.stringify({
              data_name: dataKey + "[alignment]",
              options: {
                left: window.yd_core.ui.getText("Left"),
                right: window.yd_core.ui.getText("Right"),
                center: window.yd_core.ui.getText("Center"),
                justify: window.yd_core.ui.getText("Justify"),
              },
            }),
          )}"></div>
					<p>${window.yd_core.ui.getText("Text style class")}</p>
					<div data-preview-type="text" class="yd-admin-ui-input yd-admin-ui-input-text"><input class="regular-text" type="text" placeholder="${window.yd_core.ui.getText("Value")}" name="${dataKey}[style_class]" value="${escAttr(data?.style_class ?? "")}"/></div>
					<p>${window.yd_core.ui.getText("Text background color")}</p>
					<div data-preview-type="background-color" class="yd-admin-ui-input yd-admin-ui-input-color-picker"><input type="text" data-alpha-enabled="true" name="${dataKey}[background_color]" value="${data?.background_color ?? ""}"/></div>
					<p>${window.yd_core.ui.getText("Text padding")}</p>
					<span class="input-area">
						<div>
							<small>${window.yd_core.ui.getText("Left")}</small>
							<input data-preview-type="padding-left" class="small-text" type="number" name="${dataKey}[padding][left]" value="${data?.padding?.left ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Right")}</small>
							<input data-preview-type="padding-right" class="small-text" type="number" name="${dataKey}[padding][right]" value="${data?.padding?.right ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Top")}</small>
							<input data-preview-type="padding-top" class="small-text" type="number" name="${dataKey}[padding][top]" value="${data?.padding?.top ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Bottom")}</small>
							<input data-preview-type="padding-bottom" class="small-text" type="number" name="${dataKey}[padding][bottom]" value="${data?.padding?.bottom ?? "0"}" min="0" max="128">
						</div>
					</span>
					<p>${window.yd_core.ui.getText("Text border color")}</p>
					<div data-preview-type="border-color" class="yd-admin-ui-input yd-admin-ui-input-color-picker"><input type="text" data-alpha-enabled="true" name="${dataKey}[border][color]" value="${data?.border?.color ?? "rgb(0,0,0)"}"/></div>
					<p>${window.yd_core.ui.getText("Text border width")}</p>
					<span class="input-area">
						<div>
							<small>${window.yd_core.ui.getText("Left")}</small>
							<input data-preview-type="border-width-left" class="small-text" type="number" name="${dataKey}[border][width][left]" value="${data?.border?.width?.left ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Right")}</small>
							<input data-preview-type="border-width-right" class="small-text" type="number" name="${dataKey}[border][width][right]" value="${data?.border?.width?.right ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Top")}</small>
							<input data-preview-type="border-width-top" class="small-text" type="number" name="${dataKey}[border][width][top]" value="${data?.border?.width?.top ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Bottom")}</small>
							<input data-preview-type="border-width-bottom" class="small-text" type="number" name="${dataKey}[border][width][bottom]" value="${data?.border?.width?.bottom ?? "0"}" min="0" max="128">
						</div>
					</span>
					<p>${window.yd_core.ui.getText("Text border radius")}</p>
					<span class="input-area">
						<div>
							<small>${window.yd_core.ui.getText("Left top")}</small>
							<input data-preview-type="border-radius-lt" class="small-text" type="number" name="${dataKey}[border][radius][lt]" value="${data?.border?.radius?.lt ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Right top")}</small>
							<input data-preview-type="border-radius-rt" class="small-text" type="number" name="${dataKey}[border][radius][rt]" value="${data?.border?.radius?.rt ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Right bottom")}</small>
							<input data-preview-type="border-radius-rb" class="small-text" type="number" name="${dataKey}[border][radius][rb]" value="${data?.border?.radius?.rb ?? "0"}" min="0" max="128">
						</div>
						<div>
							<small>${window.yd_core.ui.getText("Left bottom")}</small>
							<input data-preview-type="border-radius-lb" class="small-text" type="number" name="${dataKey}[border][radius][lb]" value="${data?.border?.radius?.lb ?? "0"}" min="0" max="128">
						</div>
					</span>
				</div>
				<input data-preview-type="position-x" type="hidden" name="${dataKey}[position][x]" value="${data?.position.x ?? "0"}"/>
				<input data-preview-type="position-y" type="hidden" name="${dataKey}[position][y]" value="${data?.position.y ?? "0"}"/>
				<span class="delete" tabindex="0">${window.yd_core.ui.getText("Remove text")}</span>
			</div>
		`);
    const textView = textViews.find(".text-view:last-child");
    const toggleProperties = textView.find('[data-action="toggle-properties"]');
    const removeButton = textView.find(".delete");

    toggleProperties.on("click", () =>
      toggleProperties.toggleClass("expanded"),
    );

    removeButton.on("click", (e) => {
      $(e.currentTarget).parent().remove();
      textViews.trigger("yd-slide-view-change", [null, textViewIndex, true]);
    });

    window.yd_core.ui.init().then(() => {
      const textInput = textView.find('[data-preview-type="text"]');
      const colorInput = textView.find('[data-preview-type="color"]');
      const sizeInput = textView.find('[data-preview-type="size"]');
      const alignmentInput = textView.find('[data-preview-type="alignment"]');

      const backgroundColorInput = textView.find(
        '[data-preview-type="background-color"]',
      );

      const paddingLeftInput = textView.find(
        '[data-preview-type="padding-left"]',
      );
      const paddingRightInput = textView.find(
        '[data-preview-type="padding-right"]',
      );
      const paddingTopInput = textView.find(
        '[data-preview-type="padding-top"]',
      );
      const paddingBottomInput = textView.find(
        '[data-preview-type="padding-bottom"]',
      );

      const borderColorInput = textView.find(
        '[data-preview-type="border-color"]',
      );
      const borderWidthLeftInput = textView.find(
        '[data-preview-type="border-width-left"]',
      );
      const borderWidthRightInput = textView.find(
        '[data-preview-type="border-width-right"]',
      );
      const borderWidthTopInput = textView.find(
        '[data-preview-type="border-width-top"]',
      );
      const borderWidthBottomInput = textView.find(
        '[data-preview-type="border-width-bottom"]',
      );

      const borderRadiusLTInput = textView.find(
        '[data-preview-type="border-radius-lt"]',
      );
      const borderRadiusRTInput = textView.find(
        '[data-preview-type="border-radius-rt"]',
      );
      const borderRadiusRBInput = textView.find(
        '[data-preview-type="border-radius-rb"]',
      );
      const borderRadiusLBInput = textView.find(
        '[data-preview-type="border-radius-lb"]',
      );

      const positionXInput = textView.find('[data-preview-type="position-x"]');
      const positionYInput = textView.find('[data-preview-type="position-y"]');

      const getData = () => {
        return {
          text: textInput.find("textarea").val(),
          color: colorInput.find("input").val(),
          size: sizeInput.val(),
          position: {
            x: positionXInput.val(),
            y: positionYInput.val(),
          },
          alignment: alignmentInput.find("input").val(),
          background_color: backgroundColorInput.find("input").val(),
          padding: {
            left: paddingLeftInput.val(),
            right: paddingRightInput.val(),
            top: paddingTopInput.val(),
            bottom: paddingBottomInput.val(),
          },
          border: {
            color: borderColorInput.find("input").val(),
            width: {
              left: borderWidthLeftInput.val(),
              right: borderWidthRightInput.val(),
              top: borderWidthTopInput.val(),
              bottom: borderWidthBottomInput.val(),
            },
            radius: {
              lt: borderRadiusLTInput.val(),
              rt: borderRadiusRTInput.val(),
              rb: borderRadiusRBInput.val(),
              lb: borderRadiusLBInput.val(),
            },
          },
        };
      };

      const onChangeEvent = () => {
        textViews.trigger("yd-slide-view-change", [
          getData(),
          textViewIndex,
          false,
        ]);
      };

      textViews.trigger("yd-slide-view-init", [getData(), textViewIndex]);

      textViews.on("input yd-color-change", onChangeEvent);
    });
  };

  root.on("yd-sortable-item-loaded", (_, slide) => {
    slide = $(slide);

    const aspectRatio = root
      .find("#aspect_ratio_input")
      .val()
      .replace(":", "/");
    const previewSlide = slide.find(".slide-preview .slide");
    const previewContainer = previewSlide.find(".container");
    const mediaForegroundColorDom = previewSlide.find(".media-fg-color");
    const selectionMedia = slide.find(".yd-admin-ui-input-selection-media");
    const textViews = slide.find(".text-views");

    const viewsData = JSON.parse(textViews.attr("data-value") || false) || [];

    const updateTextView = (textView, data) => {
      textView.text(data.text);
      textView.css({
        color: data.color,
        fontSize: data.size + "px",
        textAlign: data.alignment,
        backgroundColor: data.background_color,
        padding: `${data.padding.top}px ${data.padding.right}px ${data.padding.bottom}px ${data.padding.left}px`,
        borderColor: data.border.color,
        borderWidth: `${data.border.width.top}px ${data.border.width.right}px ${data.border.width.bottom}px ${data.border.width.left}px`,
        borderRadius: `${data.border.radius.lt}px ${data.border.radius.rt}px ${data.border.radius.rb}px ${data.border.radius.lb}px`,
      });
    };

    previewSlide.css("aspect-ratio", aspectRatio);
    selectionMedia
      .find(".selection-media")
      .on("yd-on-media-load yd-on-media-change", (_, mediaDom) => {
        const currentMediaDom = $(mediaDom).clone();

        previewSlide.find(".media").remove();
        previewSlide.prepend(currentMediaDom);
      });

    for (const data of viewsData) {
      newTextView(textViews, data);
    }

    mediaForegroundColorDom.css(
      "background",
      slide.find("#fg_color input").val(),
    );
    slide.find("#fg_color").on("yd-color-change", (_, color) => {
      mediaForegroundColorDom.css("background", color);
    });

    textViews.on("yd-slide-view-init", (_, data, index) => {
      const textView = $("<p/>");
      textView.draggable({
        containment: "parent",
        drag: (_, ui) => {
          const x = ui.position.left;
          const y = ui.position.top;

          const inputPositionX = textViews.find(
            `.text-view:eq(${index}) input[data-preview-type="position-x"]`,
          );
          const inputPositionY = textViews.find(
            `.text-view:eq(${index}) input[data-preview-type="position-y"]`,
          );

          inputPositionX.val(x);
          inputPositionY.val(y);
        },
      });

      updateTextView(textView, data);
      previewContainer.append(textView);

      textView.css({
        left: data.position.x + "px",
        top: data.position.y + "px",
      });
    });

    textViews.on("yd-slide-view-change", (_, data, index, isRemove) => {
      const textView = previewContainer.find(`p:eq(${index})`);

      if (isRemove) {
        textView.remove();
      } else {
        updateTextView(textView, data);
      }
    });

    slide.find("#view-action-add").on("click", () => {
      newTextView(textViews);
    });

    slide.find("#view-action-clear").on("click", () => {
      textViews.empty();
      previewContainer.find("p").remove();
    });
  });
});
