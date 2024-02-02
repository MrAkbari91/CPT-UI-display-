(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global wpforms_gutenberg_form_selector */
/* jshint es3: false, esversion: 6 */

'use strict';

var _wp = wp,
  _wp$serverSideRender = _wp.serverSideRender,
  ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
var _wp$element = wp.element,
  createElement = _wp$element.createElement,
  Fragment = _wp$element.Fragment;
var registerBlockType = wp.blocks.registerBlockType;
var _ref = wp.blockEditor || wp.editor,
  InspectorControls = _ref.InspectorControls;
var _wp$components = wp.components,
  SelectControl = _wp$components.SelectControl,
  ToggleControl = _wp$components.ToggleControl,
  PanelBody = _wp$components.PanelBody,
  Placeholder = _wp$components.Placeholder;
var __ = wp.i18n.__;
var wpformsIcon = createElement('svg', {
  width: 20,
  height: 20,
  viewBox: '0 0 612 612',
  className: 'dashicon'
}, createElement('path', {
  fill: 'currentColor',
  d: 'M544,0H68C30.445,0,0,30.445,0,68v476c0,37.556,30.445,68,68,68h476c37.556,0,68-30.444,68-68V68 C612,30.445,581.556,0,544,0z M464.44,68L387.6,120.02L323.34,68H464.44z M288.66,68l-64.26,52.02L147.56,68H288.66z M544,544H68 V68h22.1l136,92.14l79.9-64.6l79.56,64.6l136-92.14H544V544z M114.24,263.16h95.88v-48.28h-95.88V263.16z M114.24,360.4h95.88 v-48.62h-95.88V360.4z M242.76,360.4h255v-48.62h-255V360.4L242.76,360.4z M242.76,263.16h255v-48.28h-255V263.16L242.76,263.16z M368.22,457.3h129.54V408H368.22V457.3z'
}));

/**
 * Popup container.
 *
 * @since 1.8.3
 *
 * @type {object}
 */
var $popup = {};

/**
 * Close button (inside the form builder) click event.
 *
 * @since 1.8.3
 *
 * @param {string} clientID Block Client ID.
 */
var builderCloseButtonEvent = function builderCloseButtonEvent(clientID) {
  $popup.off('wpformsBuilderInPopupClose').on('wpformsBuilderInPopupClose', function (e, action, formId, formTitle) {
    if (action !== 'saved' || !formId) {
      return;
    }

    // Insert a new block when a new form is created from the popup to update the form list and attributes.
    var newBlock = wp.blocks.createBlock('wpforms/form-selector', {
      formId: formId.toString() // Expects string value, make sure we insert string.
    });

    // eslint-disable-next-line camelcase
    wpforms_gutenberg_form_selector.forms = [{
      ID: formId,
      post_title: formTitle
    }];

    // Insert a new block.
    wp.data.dispatch('core/block-editor').removeBlock(clientID);
    wp.data.dispatch('core/block-editor').insertBlocks(newBlock);
  });
};

/**
 * Open builder popup.
 *
 * @since 1.6.2
 *
 * @param {string} clientID Block Client ID.
 */
var openBuilderPopup = function openBuilderPopup(clientID) {
  if (jQuery.isEmptyObject($popup)) {
    var tmpl = jQuery('#wpforms-gutenberg-popup');
    var parent = jQuery('#wpwrap');
    parent.after(tmpl);
    $popup = parent.siblings('#wpforms-gutenberg-popup');
  }
  var url = wpforms_gutenberg_form_selector.get_started_url,
    $iframe = $popup.find('iframe');
  builderCloseButtonEvent(clientID);
  $iframe.attr('src', url);
  $popup.fadeIn();
};
var hasForms = function hasForms() {
  return wpforms_gutenberg_form_selector.forms.length > 0;
};
registerBlockType('wpforms/form-selector', {
  title: wpforms_gutenberg_form_selector.strings.title,
  description: wpforms_gutenberg_form_selector.strings.description,
  icon: wpformsIcon,
  keywords: wpforms_gutenberg_form_selector.strings.form_keywords,
  category: 'widgets',
  attributes: {
    formId: {
      type: 'string'
    },
    displayTitle: {
      type: 'boolean'
    },
    displayDesc: {
      type: 'boolean'
    },
    preview: {
      type: 'boolean'
    }
  },
  example: {
    attributes: {
      preview: true
    }
  },
  supports: {
    customClassName: hasForms()
  },
  edit: function edit(props) {
    // eslint-disable-line max-lines-per-function
    var _props$attributes = props.attributes,
      _props$attributes$for = _props$attributes.formId,
      formId = _props$attributes$for === void 0 ? '' : _props$attributes$for,
      _props$attributes$dis = _props$attributes.displayTitle,
      displayTitle = _props$attributes$dis === void 0 ? false : _props$attributes$dis,
      _props$attributes$dis2 = _props$attributes.displayDesc,
      displayDesc = _props$attributes$dis2 === void 0 ? false : _props$attributes$dis2,
      _props$attributes$pre = _props$attributes.preview,
      preview = _props$attributes$pre === void 0 ? false : _props$attributes$pre,
      setAttributes = props.setAttributes;
    var formOptions = wpforms_gutenberg_form_selector.forms.map(function (value) {
      return {
        value: value.ID,
        label: value.post_title
      };
    });
    var strings = wpforms_gutenberg_form_selector.strings;
    var jsx;
    formOptions.unshift({
      value: '',
      label: wpforms_gutenberg_form_selector.strings.form_select
    });
    function selectForm(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        formId: value
      });
    }
    function toggleDisplayTitle(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        displayTitle: value
      });
    }
    function toggleDisplayDesc(value) {
      // eslint-disable-line jsdoc/require-jsdoc
      setAttributes({
        displayDesc: value
      });
    }

    /**
     * Get block empty JSX code.
     *
     * @since 1.8.3
     *
     * @param {object} props Block properties.
     * @returns {JSX.Element} Block empty JSX code.
     */
    function getEmptyFormsPreview(props) {
      var clientId = props.clientId;
      return /*#__PURE__*/React.createElement(Fragment, {
        key: "wpforms-gutenberg-form-selector-fragment-block-empty"
      }, /*#__PURE__*/React.createElement("div", {
        className: "wpforms-no-form-preview"
      }, /*#__PURE__*/React.createElement("img", {
        src: wpforms_gutenberg_form_selector.block_empty_url
      }), /*#__PURE__*/React.createElement("p", {
        dangerouslySetInnerHTML: {
          __html: strings.wpforms_empty_info
        }
      }), /*#__PURE__*/React.createElement("button", {
        type: "button",
        className: "get-started-button components-button is-button is-primary",
        onClick: function onClick() {
          openBuilderPopup(clientId);
        }
      }, __('Get Started', 'wpforms-lite')), /*#__PURE__*/React.createElement("p", {
        className: "empty-desc",
        dangerouslySetInnerHTML: {
          __html: strings.wpforms_empty_help
        }
      }), /*#__PURE__*/React.createElement("div", {
        id: "wpforms-gutenberg-popup",
        className: "wpforms-builder-popup"
      }, /*#__PURE__*/React.createElement("iframe", {
        src: "about:blank",
        width: "100%",
        height: "100%",
        id: "wpforms-builder-iframe"
      }))));
    }

    /**
     * Print empty forms notice.
     *
     * @since 1.8.3
     *
     * @param {string} clientId Block client ID.
     *
     * @returns {JSX.Element} Field styles JSX code.
     */
    function printEmptyFormsNotice(clientId) {
      return /*#__PURE__*/React.createElement(InspectorControls, {
        key: "wpforms-gutenberg-form-selector-inspector-main-settings"
      }, /*#__PURE__*/React.createElement(PanelBody, {
        className: "wpforms-gutenberg-panel",
        title: strings.form_settings
      }, /*#__PURE__*/React.createElement("p", {
        className: "wpforms-gutenberg-panel-notice wpforms-warning wpforms-empty-form-notice",
        style: {
          display: 'block'
        }
      }, /*#__PURE__*/React.createElement("strong", null, __('You haven’t created a form, yet!', 'wpforms-lite')), __('What are you waiting for?', 'wpforms-lite')), /*#__PURE__*/React.createElement("button", {
        type: "button",
        className: "get-started-button components-button is-button is-secondary",
        onClick: function onClick() {
          openBuilderPopup(clientId);
        }
      }, __('Get Started', 'wpforms-lite'))));
    }
    if (!hasForms()) {
      jsx = [printEmptyFormsNotice(props.clientId)];
      jsx.push(getEmptyFormsPreview(props));
      return jsx;
    }
    jsx = [/*#__PURE__*/React.createElement(InspectorControls, {
      key: "wpforms-gutenberg-form-selector-inspector-controls"
    }, /*#__PURE__*/React.createElement(PanelBody, {
      title: wpforms_gutenberg_form_selector.strings.form_settings
    }, /*#__PURE__*/React.createElement(SelectControl, {
      label: wpforms_gutenberg_form_selector.strings.form_selected,
      value: formId,
      options: formOptions,
      onChange: selectForm
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      label: wpforms_gutenberg_form_selector.strings.show_title,
      checked: displayTitle,
      onChange: toggleDisplayTitle
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      label: wpforms_gutenberg_form_selector.strings.show_description,
      checked: displayDesc,
      onChange: toggleDisplayDesc
    }), /*#__PURE__*/React.createElement("p", {
      className: "wpforms-gutenberg-panel-notice"
    }, /*#__PURE__*/React.createElement("strong", null, strings.update_wp_notice_head), strings.update_wp_notice_text, " ", /*#__PURE__*/React.createElement("a", {
      href: strings.update_wp_notice_link,
      rel: "noreferrer",
      target: "_blank"
    }, strings.learn_more))))];
    if (formId) {
      jsx.push( /*#__PURE__*/React.createElement(ServerSideRender, {
        key: "wpforms-gutenberg-form-selector-server-side-renderer",
        block: "wpforms/form-selector",
        attributes: props.attributes
      }));
    } else if (preview) {
      jsx.push( /*#__PURE__*/React.createElement(Fragment, {
        key: "wpforms-gutenberg-form-selector-fragment-block-preview"
      }, /*#__PURE__*/React.createElement("img", {
        src: wpforms_gutenberg_form_selector.block_preview_url,
        style: {
          width: '100%'
        }
      })));
    } else {
      jsx.push( /*#__PURE__*/React.createElement(Placeholder, {
        key: "wpforms-gutenberg-form-selector-wrap",
        className: "wpforms-gutenberg-form-selector-wrap"
      }, /*#__PURE__*/React.createElement("img", {
        src: wpforms_gutenberg_form_selector.logo_url
      }), /*#__PURE__*/React.createElement(SelectControl, {
        key: "wpforms-gutenberg-form-selector-select-control",
        value: formId,
        options: formOptions,
        onChange: selectForm
      })));
    }
    return jsx;
  },
  save: function save() {
    return null;
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfd3AiLCJ3cCIsIl93cCRzZXJ2ZXJTaWRlUmVuZGVyIiwic2VydmVyU2lkZVJlbmRlciIsIlNlcnZlclNpZGVSZW5kZXIiLCJjb21wb25lbnRzIiwiX3dwJGVsZW1lbnQiLCJlbGVtZW50IiwiY3JlYXRlRWxlbWVudCIsIkZyYWdtZW50IiwicmVnaXN0ZXJCbG9ja1R5cGUiLCJibG9ja3MiLCJfcmVmIiwiYmxvY2tFZGl0b3IiLCJlZGl0b3IiLCJJbnNwZWN0b3JDb250cm9scyIsIl93cCRjb21wb25lbnRzIiwiU2VsZWN0Q29udHJvbCIsIlRvZ2dsZUNvbnRyb2wiLCJQYW5lbEJvZHkiLCJQbGFjZWhvbGRlciIsIl9fIiwiaTE4biIsIndwZm9ybXNJY29uIiwid2lkdGgiLCJoZWlnaHQiLCJ2aWV3Qm94IiwiY2xhc3NOYW1lIiwiZmlsbCIsImQiLCIkcG9wdXAiLCJidWlsZGVyQ2xvc2VCdXR0b25FdmVudCIsImNsaWVudElEIiwib2ZmIiwib24iLCJlIiwiYWN0aW9uIiwiZm9ybUlkIiwiZm9ybVRpdGxlIiwibmV3QmxvY2siLCJjcmVhdGVCbG9jayIsInRvU3RyaW5nIiwid3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3RvciIsImZvcm1zIiwiSUQiLCJwb3N0X3RpdGxlIiwiZGF0YSIsImRpc3BhdGNoIiwicmVtb3ZlQmxvY2siLCJpbnNlcnRCbG9ja3MiLCJvcGVuQnVpbGRlclBvcHVwIiwialF1ZXJ5IiwiaXNFbXB0eU9iamVjdCIsInRtcGwiLCJwYXJlbnQiLCJhZnRlciIsInNpYmxpbmdzIiwidXJsIiwiZ2V0X3N0YXJ0ZWRfdXJsIiwiJGlmcmFtZSIsImZpbmQiLCJhdHRyIiwiZmFkZUluIiwiaGFzRm9ybXMiLCJsZW5ndGgiLCJ0aXRsZSIsInN0cmluZ3MiLCJkZXNjcmlwdGlvbiIsImljb24iLCJrZXl3b3JkcyIsImZvcm1fa2V5d29yZHMiLCJjYXRlZ29yeSIsImF0dHJpYnV0ZXMiLCJ0eXBlIiwiZGlzcGxheVRpdGxlIiwiZGlzcGxheURlc2MiLCJwcmV2aWV3IiwiZXhhbXBsZSIsInN1cHBvcnRzIiwiY3VzdG9tQ2xhc3NOYW1lIiwiZWRpdCIsInByb3BzIiwiX3Byb3BzJGF0dHJpYnV0ZXMiLCJfcHJvcHMkYXR0cmlidXRlcyRmb3IiLCJfcHJvcHMkYXR0cmlidXRlcyRkaXMiLCJfcHJvcHMkYXR0cmlidXRlcyRkaXMyIiwiX3Byb3BzJGF0dHJpYnV0ZXMkcHJlIiwic2V0QXR0cmlidXRlcyIsImZvcm1PcHRpb25zIiwibWFwIiwidmFsdWUiLCJsYWJlbCIsImpzeCIsInVuc2hpZnQiLCJmb3JtX3NlbGVjdCIsInNlbGVjdEZvcm0iLCJ0b2dnbGVEaXNwbGF5VGl0bGUiLCJ0b2dnbGVEaXNwbGF5RGVzYyIsImdldEVtcHR5Rm9ybXNQcmV2aWV3IiwiY2xpZW50SWQiLCJSZWFjdCIsImtleSIsInNyYyIsImJsb2NrX2VtcHR5X3VybCIsImRhbmdlcm91c2x5U2V0SW5uZXJIVE1MIiwiX19odG1sIiwid3Bmb3Jtc19lbXB0eV9pbmZvIiwib25DbGljayIsIndwZm9ybXNfZW1wdHlfaGVscCIsImlkIiwicHJpbnRFbXB0eUZvcm1zTm90aWNlIiwiZm9ybV9zZXR0aW5ncyIsInN0eWxlIiwiZGlzcGxheSIsInB1c2giLCJmb3JtX3NlbGVjdGVkIiwib3B0aW9ucyIsIm9uQ2hhbmdlIiwic2hvd190aXRsZSIsImNoZWNrZWQiLCJzaG93X2Rlc2NyaXB0aW9uIiwidXBkYXRlX3dwX25vdGljZV9oZWFkIiwidXBkYXRlX3dwX25vdGljZV90ZXh0IiwiaHJlZiIsInVwZGF0ZV93cF9ub3RpY2VfbGluayIsInJlbCIsInRhcmdldCIsImxlYXJuX21vcmUiLCJibG9jayIsImJsb2NrX3ByZXZpZXdfdXJsIiwibG9nb191cmwiLCJzYXZlIl0sInNvdXJjZXMiOlsiZmFrZV9kNDg4ZWM3NS5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvKiBnbG9iYWwgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3RvciAqL1xuLyoganNoaW50IGVzMzogZmFsc2UsIGVzdmVyc2lvbjogNiAqL1xuXG4ndXNlIHN0cmljdCc7XG5cbmNvbnN0IHsgc2VydmVyU2lkZVJlbmRlcjogU2VydmVyU2lkZVJlbmRlciA9IHdwLmNvbXBvbmVudHMuU2VydmVyU2lkZVJlbmRlciB9ID0gd3A7XG5jb25zdCB7IGNyZWF0ZUVsZW1lbnQsIEZyYWdtZW50IH0gPSB3cC5lbGVtZW50O1xuY29uc3QgeyByZWdpc3RlckJsb2NrVHlwZSB9ID0gd3AuYmxvY2tzO1xuY29uc3QgeyBJbnNwZWN0b3JDb250cm9scyB9ID0gd3AuYmxvY2tFZGl0b3IgfHwgd3AuZWRpdG9yO1xuY29uc3QgeyBTZWxlY3RDb250cm9sLCBUb2dnbGVDb250cm9sLCBQYW5lbEJvZHksIFBsYWNlaG9sZGVyIH0gPSB3cC5jb21wb25lbnRzO1xuY29uc3QgeyBfXyB9ID0gd3AuaTE4bjtcblxuY29uc3Qgd3Bmb3Jtc0ljb24gPSBjcmVhdGVFbGVtZW50KCAnc3ZnJywgeyB3aWR0aDogMjAsIGhlaWdodDogMjAsIHZpZXdCb3g6ICcwIDAgNjEyIDYxMicsIGNsYXNzTmFtZTogJ2Rhc2hpY29uJyB9LFxuXHRjcmVhdGVFbGVtZW50KCAncGF0aCcsIHtcblx0XHRmaWxsOiAnY3VycmVudENvbG9yJyxcblx0XHRkOiAnTTU0NCwwSDY4QzMwLjQ0NSwwLDAsMzAuNDQ1LDAsNjh2NDc2YzAsMzcuNTU2LDMwLjQ0NSw2OCw2OCw2OGg0NzZjMzcuNTU2LDAsNjgtMzAuNDQ0LDY4LTY4VjY4IEM2MTIsMzAuNDQ1LDU4MS41NTYsMCw1NDQsMHogTTQ2NC40NCw2OEwzODcuNiwxMjAuMDJMMzIzLjM0LDY4SDQ2NC40NHogTTI4OC42Niw2OGwtNjQuMjYsNTIuMDJMMTQ3LjU2LDY4SDI4OC42NnogTTU0NCw1NDRINjggVjY4aDIyLjFsMTM2LDkyLjE0bDc5LjktNjQuNmw3OS41Niw2NC42bDEzNi05Mi4xNEg1NDRWNTQ0eiBNMTE0LjI0LDI2My4xNmg5NS44OHYtNDguMjhoLTk1Ljg4VjI2My4xNnogTTExNC4yNCwzNjAuNGg5NS44OCB2LTQ4LjYyaC05NS44OFYzNjAuNHogTTI0Mi43NiwzNjAuNGgyNTV2LTQ4LjYyaC0yNTVWMzYwLjRMMjQyLjc2LDM2MC40eiBNMjQyLjc2LDI2My4xNmgyNTV2LTQ4LjI4aC0yNTVWMjYzLjE2TDI0Mi43NiwyNjMuMTZ6IE0zNjguMjIsNDU3LjNoMTI5LjU0VjQwOEgzNjguMjJWNDU3LjN6Jyxcblx0fSApXG4pO1xuXG4vKipcbiAqIFBvcHVwIGNvbnRhaW5lci5cbiAqXG4gKiBAc2luY2UgMS44LjNcbiAqXG4gKiBAdHlwZSB7b2JqZWN0fVxuICovXG5sZXQgJHBvcHVwID0ge307XG5cbi8qKlxuICogQ2xvc2UgYnV0dG9uIChpbnNpZGUgdGhlIGZvcm0gYnVpbGRlcikgY2xpY2sgZXZlbnQuXG4gKlxuICogQHNpbmNlIDEuOC4zXG4gKlxuICogQHBhcmFtIHtzdHJpbmd9IGNsaWVudElEIEJsb2NrIENsaWVudCBJRC5cbiAqL1xuY29uc3QgYnVpbGRlckNsb3NlQnV0dG9uRXZlbnQgPSBmdW5jdGlvbiggY2xpZW50SUQgKSB7XG5cblx0JHBvcHVwXG5cdFx0Lm9mZiggJ3dwZm9ybXNCdWlsZGVySW5Qb3B1cENsb3NlJyApXG5cdFx0Lm9uKCAnd3Bmb3Jtc0J1aWxkZXJJblBvcHVwQ2xvc2UnLCBmdW5jdGlvbiggZSwgYWN0aW9uLCBmb3JtSWQsIGZvcm1UaXRsZSApIHtcblx0XHRcdGlmICggYWN0aW9uICE9PSAnc2F2ZWQnIHx8ICEgZm9ybUlkICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdC8vIEluc2VydCBhIG5ldyBibG9jayB3aGVuIGEgbmV3IGZvcm0gaXMgY3JlYXRlZCBmcm9tIHRoZSBwb3B1cCB0byB1cGRhdGUgdGhlIGZvcm0gbGlzdCBhbmQgYXR0cmlidXRlcy5cblx0XHRcdGNvbnN0IG5ld0Jsb2NrID0gd3AuYmxvY2tzLmNyZWF0ZUJsb2NrKCAnd3Bmb3Jtcy9mb3JtLXNlbGVjdG9yJywge1xuXHRcdFx0XHRmb3JtSWQ6IGZvcm1JZC50b1N0cmluZygpLCAvLyBFeHBlY3RzIHN0cmluZyB2YWx1ZSwgbWFrZSBzdXJlIHdlIGluc2VydCBzdHJpbmcuXG5cdFx0XHR9ICk7XG5cblx0XHRcdC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBjYW1lbGNhc2Vcblx0XHRcdHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuZm9ybXMgPSBbIHsgSUQ6IGZvcm1JZCwgcG9zdF90aXRsZTogZm9ybVRpdGxlIH0gXTtcblxuXHRcdFx0Ly8gSW5zZXJ0IGEgbmV3IGJsb2NrLlxuXHRcdFx0d3AuZGF0YS5kaXNwYXRjaCggJ2NvcmUvYmxvY2stZWRpdG9yJyApLnJlbW92ZUJsb2NrKCBjbGllbnRJRCApO1xuXHRcdFx0d3AuZGF0YS5kaXNwYXRjaCggJ2NvcmUvYmxvY2stZWRpdG9yJyApLmluc2VydEJsb2NrcyggbmV3QmxvY2sgKTtcblxuXHRcdH0gKTtcbn07XG5cbi8qKlxuICogT3BlbiBidWlsZGVyIHBvcHVwLlxuICpcbiAqIEBzaW5jZSAxLjYuMlxuICpcbiAqIEBwYXJhbSB7c3RyaW5nfSBjbGllbnRJRCBCbG9jayBDbGllbnQgSUQuXG4gKi9cbmNvbnN0IG9wZW5CdWlsZGVyUG9wdXAgPSBmdW5jdGlvbiggY2xpZW50SUQgKSB7XG5cblx0aWYgKCBqUXVlcnkuaXNFbXB0eU9iamVjdCggJHBvcHVwICkgKSB7XG5cdFx0bGV0IHRtcGwgPSBqUXVlcnkoICcjd3Bmb3Jtcy1ndXRlbmJlcmctcG9wdXAnICk7XG5cdFx0bGV0IHBhcmVudCA9IGpRdWVyeSggJyN3cHdyYXAnICk7XG5cblx0XHRwYXJlbnQuYWZ0ZXIoIHRtcGwgKTtcblxuXHRcdCRwb3B1cCA9IHBhcmVudC5zaWJsaW5ncyggJyN3cGZvcm1zLWd1dGVuYmVyZy1wb3B1cCcgKTtcblx0fVxuXG5cdGNvbnN0IHVybCA9IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuZ2V0X3N0YXJ0ZWRfdXJsLFxuXHRcdCRpZnJhbWUgPSAkcG9wdXAuZmluZCggJ2lmcmFtZScgKTtcblxuXHRidWlsZGVyQ2xvc2VCdXR0b25FdmVudCggY2xpZW50SUQgKTtcblx0JGlmcmFtZS5hdHRyKCAnc3JjJywgdXJsICk7XG5cdCRwb3B1cC5mYWRlSW4oKTtcbn07XG5cbmNvbnN0IGhhc0Zvcm1zID0gZnVuY3Rpb24oKSB7XG5cdHJldHVybiB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmZvcm1zLmxlbmd0aCA+IDA7XG59O1xuXG5yZWdpc3RlckJsb2NrVHlwZSggJ3dwZm9ybXMvZm9ybS1zZWxlY3RvcicsIHtcblx0dGl0bGU6IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy50aXRsZSxcblx0ZGVzY3JpcHRpb246IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5kZXNjcmlwdGlvbixcblx0aWNvbjogd3Bmb3Jtc0ljb24sXG5cdGtleXdvcmRzOiB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLnN0cmluZ3MuZm9ybV9rZXl3b3Jkcyxcblx0Y2F0ZWdvcnk6ICd3aWRnZXRzJyxcblx0YXR0cmlidXRlczoge1xuXHRcdGZvcm1JZDoge1xuXHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0fSxcblx0XHRkaXNwbGF5VGl0bGU6IHtcblx0XHRcdHR5cGU6ICdib29sZWFuJyxcblx0XHR9LFxuXHRcdGRpc3BsYXlEZXNjOiB7XG5cdFx0XHR0eXBlOiAnYm9vbGVhbicsXG5cdFx0fSxcblx0XHRwcmV2aWV3OiB7XG5cdFx0XHR0eXBlOiAnYm9vbGVhbicsXG5cdFx0fSxcblx0fSxcblx0ZXhhbXBsZToge1xuXHRcdGF0dHJpYnV0ZXM6IHtcblx0XHRcdHByZXZpZXc6IHRydWUsXG5cdFx0fSxcblx0fSxcblx0c3VwcG9ydHM6IHtcblx0XHRjdXN0b21DbGFzc05hbWU6IGhhc0Zvcm1zKCksXG5cdH0sXG5cdGVkaXQoIHByb3BzICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRjb25zdCB7IGF0dHJpYnV0ZXM6IHsgZm9ybUlkID0gJycsIGRpc3BsYXlUaXRsZSA9IGZhbHNlLCBkaXNwbGF5RGVzYyA9IGZhbHNlLCBwcmV2aWV3ID0gZmFsc2UgfSwgc2V0QXR0cmlidXRlcyB9ID0gcHJvcHM7XG5cdFx0Y29uc3QgZm9ybU9wdGlvbnMgPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmZvcm1zLm1hcCggdmFsdWUgPT4gKFxuXHRcdFx0eyB2YWx1ZTogdmFsdWUuSUQsIGxhYmVsOiB2YWx1ZS5wb3N0X3RpdGxlIH1cblx0XHQpICk7XG5cblx0XHRjb25zdCBzdHJpbmdzID0gd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzO1xuXHRcdGxldCBqc3g7XG5cblx0XHRmb3JtT3B0aW9ucy51bnNoaWZ0KCB7IHZhbHVlOiAnJywgbGFiZWw6IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5mb3JtX3NlbGVjdCB9ICk7XG5cblx0XHRmdW5jdGlvbiBzZWxlY3RGb3JtKCB2YWx1ZSApIHsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBqc2RvYy9yZXF1aXJlLWpzZG9jXG5cdFx0XHRzZXRBdHRyaWJ1dGVzKCB7IGZvcm1JZDogdmFsdWUgfSApO1xuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIHRvZ2dsZURpc3BsYXlUaXRsZSggdmFsdWUgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUganNkb2MvcmVxdWlyZS1qc2RvY1xuXHRcdFx0c2V0QXR0cmlidXRlcyggeyBkaXNwbGF5VGl0bGU6IHZhbHVlIH0gKTtcblx0XHR9XG5cblx0XHRmdW5jdGlvbiB0b2dnbGVEaXNwbGF5RGVzYyggdmFsdWUgKSB7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUganNkb2MvcmVxdWlyZS1qc2RvY1xuXHRcdFx0c2V0QXR0cmlidXRlcyggeyBkaXNwbGF5RGVzYzogdmFsdWUgfSApO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIEdldCBibG9jayBlbXB0eSBKU1ggY29kZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguM1xuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICogQHJldHVybnMge0pTWC5FbGVtZW50fSBCbG9jayBlbXB0eSBKU1ggY29kZS5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBnZXRFbXB0eUZvcm1zUHJldmlldyggcHJvcHMgKSB7XG5cblx0XHRcdGNvbnN0IGNsaWVudElkID0gcHJvcHMuY2xpZW50SWQ7XG5cblx0XHRcdHJldHVybiAoXG5cdFx0XHRcdDxGcmFnbWVudFxuXHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZnJhZ21lbnQtYmxvY2stZW1wdHlcIj5cblx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtbm8tZm9ybS1wcmV2aWV3XCI+XG5cdFx0XHRcdFx0XHQ8aW1nIHNyYz17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuYmxvY2tfZW1wdHlfdXJsIH0gLz5cblx0XHRcdFx0XHRcdDxwIGRhbmdlcm91c2x5U2V0SW5uZXJIVE1MPXt7IF9faHRtbDogc3RyaW5ncy53cGZvcm1zX2VtcHR5X2luZm8gfX0+PC9wPlxuXHRcdFx0XHRcdFx0PGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3NOYW1lPVwiZ2V0LXN0YXJ0ZWQtYnV0dG9uIGNvbXBvbmVudHMtYnV0dG9uIGlzLWJ1dHRvbiBpcy1wcmltYXJ5XCJcblx0XHRcdFx0XHRcdFx0b25DbGljaz17XG5cdFx0XHRcdFx0XHRcdFx0KCkgPT4ge1xuXHRcdFx0XHRcdFx0XHRcdFx0b3BlbkJ1aWxkZXJQb3B1cCggY2xpZW50SWQgKTtcblx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdD5cblx0XHRcdFx0XHRcdFx0eyBfXyggJ0dldCBTdGFydGVkJywgJ3dwZm9ybXMtbGl0ZScgKSB9XG5cdFx0XHRcdFx0XHQ8L2J1dHRvbj5cblx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cImVtcHR5LWRlc2NcIiBkYW5nZXJvdXNseVNldElubmVySFRNTD17eyBfX2h0bWw6IHN0cmluZ3Mud3Bmb3Jtc19lbXB0eV9oZWxwIH19PjwvcD5cblxuXHRcdFx0XHRcdFx0ey8qIFRlbXBsYXRlIGZvciBwb3B1cCB3aXRoIGJ1aWxkZXIgaWZyYW1lICovfVxuXHRcdFx0XHRcdFx0PGRpdiBpZD1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBvcHVwXCIgY2xhc3NOYW1lPVwid3Bmb3Jtcy1idWlsZGVyLXBvcHVwXCI+XG5cdFx0XHRcdFx0XHRcdDxpZnJhbWUgc3JjPVwiYWJvdXQ6YmxhbmtcIiB3aWR0aD1cIjEwMCVcIiBoZWlnaHQ9XCIxMDAlXCIgaWQ9XCJ3cGZvcm1zLWJ1aWxkZXItaWZyYW1lXCI+PC9pZnJhbWU+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdCk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUHJpbnQgZW1wdHkgZm9ybXMgbm90aWNlLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4zXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gY2xpZW50SWQgQmxvY2sgY2xpZW50IElELlxuXHRcdCAqXG5cdFx0ICogQHJldHVybnMge0pTWC5FbGVtZW50fSBGaWVsZCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gcHJpbnRFbXB0eUZvcm1zTm90aWNlKCBjbGllbnRJZCApIHtcblx0XHRcdHJldHVybiAoXG5cdFx0XHRcdDxJbnNwZWN0b3JDb250cm9scyBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWluc3BlY3Rvci1tYWluLXNldHRpbmdzXCI+XG5cdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbFwiIHRpdGxlPXsgc3RyaW5ncy5mb3JtX3NldHRpbmdzIH0+XG5cdFx0XHRcdFx0XHQ8cCBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Ugd3Bmb3Jtcy13YXJuaW5nIHdwZm9ybXMtZW1wdHktZm9ybS1ub3RpY2VcIiBzdHlsZT17eyBkaXNwbGF5OiAnYmxvY2snIH19PlxuXHRcdFx0XHRcdFx0XHQ8c3Ryb25nPnsgX18oICdZb3UgaGF2ZW7igJl0IGNyZWF0ZWQgYSBmb3JtLCB5ZXQhJywgJ3dwZm9ybXMtbGl0ZScgKSB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHRcdHsgX18oICdXaGF0IGFyZSB5b3Ugd2FpdGluZyBmb3I/JywgJ3dwZm9ybXMtbGl0ZScgKSB9XG5cdFx0XHRcdFx0XHQ8L3A+XG5cdFx0XHRcdFx0XHQ8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzc05hbWU9XCJnZXQtc3RhcnRlZC1idXR0b24gY29tcG9uZW50cy1idXR0b24gaXMtYnV0dG9uIGlzLXNlY29uZGFyeVwiXG5cdFx0XHRcdFx0XHRcdG9uQ2xpY2s9e1xuXHRcdFx0XHRcdFx0XHRcdCgpID0+IHtcblx0XHRcdFx0XHRcdFx0XHRcdG9wZW5CdWlsZGVyUG9wdXAoIGNsaWVudElkICk7XG5cdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHRcdHsgX18oICdHZXQgU3RhcnRlZCcsICd3cGZvcm1zLWxpdGUnICkgfVxuXHRcdFx0XHRcdFx0PC9idXR0b24+XG5cdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdDwvSW5zcGVjdG9yQ29udHJvbHM+XG5cdFx0XHQpO1xuXHRcdH1cblxuXG5cdFx0aWYgKCAhIGhhc0Zvcm1zKCkgKSB7XG5cblx0XHRcdGpzeCA9IFsgcHJpbnRFbXB0eUZvcm1zTm90aWNlKCBwcm9wcy5jbGllbnRJZCApIF07XG5cblx0XHRcdGpzeC5wdXNoKCBnZXRFbXB0eUZvcm1zUHJldmlldyggcHJvcHMgKSApO1xuXHRcdFx0cmV0dXJuIGpzeDtcblx0XHR9XG5cblx0XHRqc3ggPSBbXG5cdFx0XHQ8SW5zcGVjdG9yQ29udHJvbHMga2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1pbnNwZWN0b3ItY29udHJvbHNcIj5cblx0XHRcdFx0PFBhbmVsQm9keSB0aXRsZT17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5mb3JtX3NldHRpbmdzIH0+XG5cdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdGxhYmVsPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5zdHJpbmdzLmZvcm1fc2VsZWN0ZWQgfVxuXHRcdFx0XHRcdFx0dmFsdWU9eyBmb3JtSWQgfVxuXHRcdFx0XHRcdFx0b3B0aW9ucz17IGZvcm1PcHRpb25zIH1cblx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgc2VsZWN0Rm9ybSB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHQ8VG9nZ2xlQ29udHJvbFxuXHRcdFx0XHRcdFx0bGFiZWw9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLnN0cmluZ3Muc2hvd190aXRsZSB9XG5cdFx0XHRcdFx0XHRjaGVja2VkPXsgZGlzcGxheVRpdGxlIH1cblx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgdG9nZ2xlRGlzcGxheVRpdGxlIH1cblx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdDxUb2dnbGVDb250cm9sXG5cdFx0XHRcdFx0XHRsYWJlbD17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3Iuc3RyaW5ncy5zaG93X2Rlc2NyaXB0aW9uIH1cblx0XHRcdFx0XHRcdGNoZWNrZWQ9eyBkaXNwbGF5RGVzYyB9XG5cdFx0XHRcdFx0XHRvbkNoYW5nZT17IHRvZ2dsZURpc3BsYXlEZXNjIH1cblx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZVwiPlxuXHRcdFx0XHRcdFx0PHN0cm9uZz57IHN0cmluZ3MudXBkYXRlX3dwX25vdGljZV9oZWFkIH08L3N0cm9uZz5cblx0XHRcdFx0XHRcdHsgc3RyaW5ncy51cGRhdGVfd3Bfbm90aWNlX3RleHQgfSA8YSBocmVmPXtzdHJpbmdzLnVwZGF0ZV93cF9ub3RpY2VfbGlua30gcmVsPVwibm9yZWZlcnJlclwiIHRhcmdldD1cIl9ibGFua1wiPnsgc3RyaW5ncy5sZWFybl9tb3JlIH08L2E+XG5cdFx0XHRcdFx0PC9wPlxuXG5cdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0PC9JbnNwZWN0b3JDb250cm9scz4sXG5cdFx0XTtcblxuXHRcdGlmICggZm9ybUlkICkge1xuXHRcdFx0anN4LnB1c2goXG5cdFx0XHRcdDxTZXJ2ZXJTaWRlUmVuZGVyXG5cdFx0XHRcdFx0a2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1zZXJ2ZXItc2lkZS1yZW5kZXJlclwiXG5cdFx0XHRcdFx0YmxvY2s9XCJ3cGZvcm1zL2Zvcm0tc2VsZWN0b3JcIlxuXHRcdFx0XHRcdGF0dHJpYnV0ZXM9eyBwcm9wcy5hdHRyaWJ1dGVzIH1cblx0XHRcdFx0Lz5cblx0XHRcdCk7XG5cdFx0fSBlbHNlIGlmICggcHJldmlldyApIHtcblx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHQ8RnJhZ21lbnRcblx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWZyYWdtZW50LWJsb2NrLXByZXZpZXdcIj5cblx0XHRcdFx0XHQ8aW1nIHNyYz17IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuYmxvY2tfcHJldmlld191cmwgfSBzdHlsZT17eyB3aWR0aDogJzEwMCUnIH19Lz5cblx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdCk7XG5cdFx0fSBlbHNlIHtcblx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHQ8UGxhY2Vob2xkZXJcblx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXdyYXBcIlxuXHRcdFx0XHRcdGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itd3JhcFwiPlxuXHRcdFx0XHRcdDxpbWcgc3JjPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5sb2dvX3VybCB9Lz5cblx0XHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdFx0a2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1zZWxlY3QtY29udHJvbFwiXG5cdFx0XHRcdFx0XHR2YWx1ZT17IGZvcm1JZCB9XG5cdFx0XHRcdFx0XHRvcHRpb25zPXsgZm9ybU9wdGlvbnMgfVxuXHRcdFx0XHRcdFx0b25DaGFuZ2U9eyBzZWxlY3RGb3JtIH1cblx0XHRcdFx0XHQvPlxuXHRcdFx0XHQ8L1BsYWNlaG9sZGVyPlxuXHRcdFx0KTtcblx0XHR9XG5cblx0XHRyZXR1cm4ganN4O1xuXHR9LFxuXHRzYXZlKCkge1xuXHRcdHJldHVybiBudWxsO1xuXHR9LFxufSApO1xuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBOztBQUVBLFlBQVk7O0FBRVosSUFBQUEsR0FBQSxHQUFnRkMsRUFBRTtFQUFBQyxvQkFBQSxHQUFBRixHQUFBLENBQTFFRyxnQkFBZ0I7RUFBRUMsZ0JBQWdCLEdBQUFGLG9CQUFBLGNBQUdELEVBQUUsQ0FBQ0ksVUFBVSxDQUFDRCxnQkFBZ0IsR0FBQUYsb0JBQUE7QUFDM0UsSUFBQUksV0FBQSxHQUFvQ0wsRUFBRSxDQUFDTSxPQUFPO0VBQXRDQyxhQUFhLEdBQUFGLFdBQUEsQ0FBYkUsYUFBYTtFQUFFQyxRQUFRLEdBQUFILFdBQUEsQ0FBUkcsUUFBUTtBQUMvQixJQUFRQyxpQkFBaUIsR0FBS1QsRUFBRSxDQUFDVSxNQUFNLENBQS9CRCxpQkFBaUI7QUFDekIsSUFBQUUsSUFBQSxHQUE4QlgsRUFBRSxDQUFDWSxXQUFXLElBQUlaLEVBQUUsQ0FBQ2EsTUFBTTtFQUFqREMsaUJBQWlCLEdBQUFILElBQUEsQ0FBakJHLGlCQUFpQjtBQUN6QixJQUFBQyxjQUFBLEdBQWlFZixF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
},{}]},{},[1])