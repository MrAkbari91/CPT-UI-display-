(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw new Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator.return && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw new Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, catch: function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/* global wpforms_gutenberg_form_selector, Choices, JSX, DOM */
/* jshint es3: false, esversion: 6 */

/**
 * Gutenberg editor block.
 *
 * @since 1.8.1
 */
var WPForms = window.WPForms || {};
WPForms.FormSelector = WPForms.FormSelector || function (document, window, $) {
  var _wp = wp,
    _wp$serverSideRender = _wp.serverSideRender,
    ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
  var _wp$element = wp.element,
    createElement = _wp$element.createElement,
    Fragment = _wp$element.Fragment,
    useState = _wp$element.useState,
    createInterpolateElement = _wp$element.createInterpolateElement;
  var registerBlockType = wp.blocks.registerBlockType;
  var _ref = wp.blockEditor || wp.editor,
    InspectorControls = _ref.InspectorControls,
    InspectorAdvancedControls = _ref.InspectorAdvancedControls,
    PanelColorSettings = _ref.PanelColorSettings;
  var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    Placeholder = _wp$components.Placeholder,
    Flex = _wp$components.Flex,
    FlexBlock = _wp$components.FlexBlock,
    __experimentalUnitControl = _wp$components.__experimentalUnitControl,
    TextareaControl = _wp$components.TextareaControl,
    Button = _wp$components.Button,
    Modal = _wp$components.Modal;
  var _wpforms_gutenberg_fo = wpforms_gutenberg_form_selector,
    strings = _wpforms_gutenberg_fo.strings,
    defaults = _wpforms_gutenberg_fo.defaults,
    sizes = _wpforms_gutenberg_fo.sizes,
    urls = _wpforms_gutenberg_fo.urls,
    isPro = _wpforms_gutenberg_fo.isPro;
  var defaultStyleSettings = defaults;
  var __ = wp.i18n.__;

  /**
   * List of forms.
   *
   * Default value is localized in FormSelector.php.
   *
   * @since 1.8.4
   *
   * @type {Object}
   */
  var formList = wpforms_gutenberg_form_selector.forms;

  /**
   * Blocks runtime data.
   *
   * @since 1.8.1
   *
   * @type {Object}
   */
  var blocks = {};

  /**
   * Whether it is needed to trigger server rendering.
   *
   * @since 1.8.1
   *
   * @type {boolean}
   */
  var triggerServerRender = true;

  /**
   * Popup container.
   *
   * @since 1.8.3
   *
   * @type {Object}
   */
  var $popup = {};

  /**
   * Track fetch status.
   *
   * @since 1.8.4
   *
   * @type {boolean}
   */
  var isFetching = false;

  /**
   * Public functions and properties.
   *
   * @since 1.8.1
   *
   * @type {Object}
   */
  var app = {
    /**
     * Start the engine.
     *
     * @since 1.8.1
     */
    init: function init() {
      app.initDefaults();
      app.registerBlock();
      $(app.ready);
    },
    /**
     * Document ready.
     *
     * @since 1.8.1
     */
    ready: function ready() {
      app.events();
    },
    /**
     * Events.
     *
     * @since 1.8.1
     */
    events: function events() {
      $(window).on('wpformsFormSelectorEdit', _.debounce(app.blockEdit, 250)).on('wpformsFormSelectorFormLoaded', _.debounce(app.formLoaded, 250));
    },
    /**
     * Get fresh list of forms via REST-API.
     *
     * @since 1.8.4
     *
     * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/
     */
    getForms: function getForms() {
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var response;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              if (!isFetching) {
                _context.next = 2;
                break;
              }
              return _context.abrupt("return");
            case 2:
              // Set the flag to true indicating a fetch is in progress.
              isFetching = true;
              _context.prev = 3;
              _context.next = 6;
              return wp.apiFetch({
                path: '/wpforms/v1/forms/',
                method: 'GET',
                cache: 'no-cache'
              });
            case 6:
              response = _context.sent;
              // Update the form list.
              formList = response.forms;
              _context.next = 13;
              break;
            case 10:
              _context.prev = 10;
              _context.t0 = _context["catch"](3);
              // eslint-disable-next-line no-console
              console.error(_context.t0);
            case 13:
              _context.prev = 13;
              isFetching = false;
              return _context.finish(13);
            case 16:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[3, 10, 13, 16]]);
      }))();
    },
    /**
     * Open builder popup.
     *
     * @since 1.6.2
     *
     * @param {string} clientID Block Client ID.
     */
    openBuilderPopup: function openBuilderPopup(clientID) {
      if ($.isEmptyObject($popup)) {
        var tmpl = $('#wpforms-gutenberg-popup');
        var parent = $('#wpwrap');
        parent.after(tmpl);
        $popup = parent.siblings('#wpforms-gutenberg-popup');
      }
      var url = wpforms_gutenberg_form_selector.get_started_url,
        $iframe = $popup.find('iframe');
      app.builderCloseButtonEvent(clientID);
      $iframe.attr('src', url);
      $popup.fadeIn();
    },
    /**
     * Close button (inside the form builder) click event.
     *
     * @since 1.8.3
     *
     * @param {string} clientID Block Client ID.
     */
    builderCloseButtonEvent: function builderCloseButtonEvent(clientID) {
      $popup.off('wpformsBuilderInPopupClose').on('wpformsBuilderInPopupClose', function (e, action, formId, formTitle) {
        if (action !== 'saved' || !formId) {
          return;
        }

        // Insert a new block when a new form is created from the popup to update the form list and attributes.
        var newBlock = wp.blocks.createBlock('wpforms/form-selector', {
          formId: formId.toString() // Expects string value, make sure we insert string.
        });

        // eslint-disable-next-line camelcase
        formList = [{
          ID: formId,
          post_title: formTitle
        }];

        // Insert a new block.
        wp.data.dispatch('core/block-editor').removeBlock(clientID);
        wp.data.dispatch('core/block-editor').insertBlocks(newBlock);
      });
    },
    /**
     * Register block.
     *
     * @since 1.8.1
     */
    // eslint-disable-next-line max-lines-per-function
    registerBlock: function registerBlock() {
      registerBlockType('wpforms/form-selector', {
        title: strings.title,
        description: strings.description,
        icon: app.getIcon(),
        keywords: strings.form_keywords,
        category: 'widgets',
        attributes: app.getBlockAttributes(),
        supports: {
          customClassName: app.hasForms()
        },
        example: {
          attributes: {
            preview: true
          }
        },
        edit: function edit(props) {
          // Get fresh list of forms.
          app.getForms();
          var attributes = props.attributes;
          var formOptions = app.getFormOptions();
          var handlers = app.getSettingsFieldsHandlers(props);

          // Store block clientId in attributes.
          if (!attributes.clientId) {
            // We just want client ID to update once.
            // The block editor doesn't have a fixed block ID, so we need to get it on the initial load, but only once.
            props.setAttributes({
              clientId: props.clientId
            });
          }

          // Main block settings.
          var jsx = [app.jsxParts.getMainSettings(attributes, handlers, formOptions)];

          // Block preview picture.
          if (!app.hasForms()) {
            jsx.push(app.jsxParts.getEmptyFormsPreview(props));
            return jsx;
          }
          var sizeOptions = app.getSizeOptions();

          // Form style settings & block content.
          if (attributes.formId) {
            jsx.push(app.jsxParts.getStyleSettings(props, handlers, sizeOptions), app.jsxParts.getAdvancedSettings(props, handlers), app.jsxParts.getBlockFormContent(props));
            handlers.updateCopyPasteContent();
            $(window).trigger('wpformsFormSelectorEdit', [props]);
            return jsx;
          }

          // Block preview picture.
          if (attributes.preview) {
            jsx.push(app.jsxParts.getBlockPreview());
            return jsx;
          }

          // Block placeholder (form selector).
          jsx.push(app.jsxParts.getBlockPlaceholder(props.attributes, handlers, formOptions));
          return jsx;
        },
        save: function save() {
          return null;
        }
      });
    },
    /**
     * Init default style settings.
     *
     * @since 1.8.1
     */
    initDefaults: function initDefaults() {
      ['formId', 'copyPasteJsonValue'].forEach(function (key) {
        return delete defaultStyleSettings[key];
      });
    },
    /**
     * Check if site has forms.
     *
     * @since 1.8.3
     *
     * @return {boolean} Whether site has at least one form.
     */
    hasForms: function hasForms() {
      return formList.length >= 1;
    },
    /**
     * Block JSX parts.
     *
     * @since 1.8.1
     *
     * @type {Object}
     */
    jsxParts: {
      /**
       * Get main settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} attributes  Block attributes.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} formOptions Form selector options.
       *
       * @return {JSX.Element} Main setting JSX code.
       */
      getMainSettings: function getMainSettings(attributes, handlers, formOptions) {
        if (!app.hasForms()) {
          return app.jsxParts.printEmptyFormsNotice(attributes.clientId);
        }
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-inspector-main-settings"
        }, /*#__PURE__*/React.createElement(PanelBody, {
          className: "wpforms-gutenberg-panel",
          title: strings.form_settings
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.form_selected,
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }), attributes.formId ? /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-form-selector-actions"
        }, /*#__PURE__*/React.createElement("a", {
          href: urls.form_url.replace('{ID}', attributes.formId),
          rel: "noreferrer",
          target: "_blank"
        }, strings.form_edit), isPro && /*#__PURE__*/React.createElement(React.Fragment, null, "\xA0\xA0|\xA0\xA0", /*#__PURE__*/React.createElement("a", {
          href: urls.entries_url.replace('{ID}', attributes.formId),
          rel: "noreferrer",
          target: "_blank"
        }, strings.form_entries))) : null, /*#__PURE__*/React.createElement(ToggleControl, {
          label: strings.show_title,
          checked: attributes.displayTitle,
          onChange: function onChange(value) {
            return handlers.attrChange('displayTitle', value);
          }
        }), /*#__PURE__*/React.createElement(ToggleControl, {
          label: strings.show_description,
          checked: attributes.displayDesc,
          onChange: function onChange(value) {
            return handlers.attrChange('displayDesc', value);
          }
        }), /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice"
        }, /*#__PURE__*/React.createElement("strong", null, strings.panel_notice_head), strings.panel_notice_text, /*#__PURE__*/React.createElement("a", {
          href: strings.panel_notice_link,
          rel: "noreferrer",
          target: "_blank"
        }, strings.panel_notice_link_text))));
      },
      /**
       * Print empty forms notice.
       *
       * @since 1.8.3
       *
       * @param {string} clientId Block client ID.
       *
       * @return {JSX.Element} Field styles JSX code.
       */
      printEmptyFormsNotice: function printEmptyFormsNotice(clientId) {
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
          className: "get-started-button components-button is-secondary",
          onClick: function onClick() {
            app.openBuilderPopup(clientId);
          }
        }, __('Get Started', 'wpforms-lite'))));
      },
      /**
       * Get Field styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Field styles JSX code.
       */
      getFieldStyles: function getFieldStyles(props, handlers, sizeOptions) {
        // eslint-disable-line max-lines-per-function
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.field_styles
        }, /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice wpforms-use-modern-notice"
        }, /*#__PURE__*/React.createElement("strong", null, strings.use_modern_notice_head), strings.use_modern_notice_text, " ", /*#__PURE__*/React.createElement("a", {
          href: strings.use_modern_notice_link,
          rel: "noreferrer",
          target: "_blank"
        }, strings.learn_more)), /*#__PURE__*/React.createElement("p", {
          className: "wpforms-gutenberg-panel-notice wpforms-warning wpforms-lead-form-notice",
          style: {
            display: 'none'
          }
        }, /*#__PURE__*/React.createElement("strong", null, strings.lead_forms_panel_notice_head), strings.lead_forms_panel_notice_text), /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'wpforms-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.fieldSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldSize', value);
          }
        })), /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          label: strings.border_radius,
          value: props.attributes.fieldBorderRadius,
          isUnitSelectTabbable: true,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldBorderRadius', value);
          }
        }))), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.fieldBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: props.attributes.fieldBorderColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBorderColor', value);
            },
            label: strings.border
          }, {
            value: props.attributes.fieldTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldTextColor', value);
            },
            label: strings.text
          }]
        })));
      },
      /**
       * Get Label styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Label styles JSX code.
       */
      getLabelStyles: function getLabelStyles(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.label_styles
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.labelSize,
          className: "wpforms-gutenberg-form-selector-fix-bottom-margin",
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('labelSize', value);
          }
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.labelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelColor', value);
            },
            label: strings.label
          }, {
            value: props.attributes.labelSublabelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelSublabelColor', value);
            },
            label: strings.sublabel_hints.replace('&amp;', '&')
          }, {
            value: props.attributes.labelErrorColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelErrorColor', value);
            },
            label: strings.error_message
          }]
        })));
      },
      /**
       * Get Button styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object}  Button styles JSX code.
       */
      getButtonStyles: function getButtonStyles(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(props),
          title: strings.button_styles
        }, /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'wpforms-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: props.attributes.buttonSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('buttonSize', value);
          }
        })), /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          onChange: function onChange(value) {
            return handlers.styleAttrChange('buttonBorderRadius', value);
          },
          label: strings.border_radius,
          isUnitSelectTabbable: true,
          value: props.attributes.buttonBorderRadius
        }))), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "wpforms-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: props.attributes.buttonBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: props.attributes.buttonTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonTextColor', value);
            },
            label: strings.text
          }]
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-legend wpforms-button-color-notice"
        }, strings.button_color_notice)));
      },
      /**
       * Get style settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props       Block properties.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} sizeOptions Size selector options.
       *
       * @return {Object} Inspector controls JSX code.
       */
      getStyleSettings: function getStyleSettings(props, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "wpforms-gutenberg-form-selector-style-settings"
        }, app.jsxParts.getFieldStyles(props, handlers, sizeOptions), app.jsxParts.getLabelStyles(props, handlers, sizeOptions), app.jsxParts.getButtonStyles(props, handlers, sizeOptions));
      },
      /**
       * Get advanced settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props    Block properties.
       * @param {Object} handlers Block event handlers.
       *
       * @return {Object} Inspector advanced controls JSX code.
       */
      getAdvancedSettings: function getAdvancedSettings(props, handlers) {
        // eslint-disable-next-line react-hooks/rules-of-hooks
        var _useState = useState(false),
          _useState2 = _slicedToArray(_useState, 2),
          isOpen = _useState2[0],
          setOpen = _useState2[1];
        var openModal = function openModal() {
          return setOpen(true);
        };
        var closeModal = function closeModal() {
          return setOpen(false);
        };
        return /*#__PURE__*/React.createElement(InspectorAdvancedControls, null, /*#__PURE__*/React.createElement("div", {
          className: app.getPanelClass(props)
        }, /*#__PURE__*/React.createElement(TextareaControl, {
          label: strings.copy_paste_settings,
          rows: "4",
          spellCheck: "false",
          value: props.attributes.copyPasteJsonValue,
          onChange: function onChange(value) {
            return handlers.pasteSettings(value);
          }
        }), /*#__PURE__*/React.createElement("div", {
          className: "wpforms-gutenberg-form-selector-legend",
          dangerouslySetInnerHTML: {
            __html: strings.copy_paste_notice
          }
        }), /*#__PURE__*/React.createElement(Button, {
          className: "wpforms-gutenberg-form-selector-reset-button",
          onClick: openModal
        }, strings.reset_style_settings)), isOpen && /*#__PURE__*/React.createElement(Modal, {
          className: "wpforms-gutenberg-modal",
          title: strings.reset_style_settings,
          onRequestClose: closeModal
        }, /*#__PURE__*/React.createElement("p", null, strings.reset_settings_confirm_text), /*#__PURE__*/React.createElement(Flex, {
          gap: 3,
          align: "center",
          justify: "flex-end"
        }, /*#__PURE__*/React.createElement(Button, {
          isSecondary: true,
          onClick: closeModal
        }, strings.btn_no), /*#__PURE__*/React.createElement(Button, {
          isPrimary: true,
          onClick: function onClick() {
            closeModal();
            handlers.resetSettings();
          }
        }, strings.btn_yes_reset))));
      },
      /**
       * Get block content JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} props Block properties.
       *
       * @return {JSX.Element} Block content JSX code.
       */
      getBlockFormContent: function getBlockFormContent(props) {
        if (triggerServerRender) {
          return /*#__PURE__*/React.createElement(ServerSideRender, {
            key: "wpforms-gutenberg-form-selector-server-side-renderer",
            block: "wpforms/form-selector",
            attributes: props.attributes
          });
        }
        var clientId = props.clientId;
        var block = app.getBlockContainer(props);

        // In the case of empty content, use server side renderer.
        // This happens when the block is duplicated or converted to a reusable block.
        if (!block || !block.innerHTML) {
          triggerServerRender = true;
          return app.jsxParts.getBlockFormContent(props);
        }
        blocks[clientId] = blocks[clientId] || {};
        blocks[clientId].blockHTML = block.innerHTML;
        blocks[clientId].loadedFormId = props.attributes.formId;
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-form-html"
        }, /*#__PURE__*/React.createElement("div", {
          dangerouslySetInnerHTML: {
            __html: blocks[clientId].blockHTML
          }
        }));
      },
      /**
       * Get block preview JSX code.
       *
       * @since 1.8.1
       *
       * @return {JSX.Element} Block preview JSX code.
       */
      getBlockPreview: function getBlockPreview() {
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-block-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.block_preview_url,
          style: {
            width: '100%'
          },
          alt: ""
        }));
      },
      /**
       * Get block empty JSX code.
       *
       * @since 1.8.3
       *
       * @param {Object} props Block properties.
       * @return {JSX.Element} Block empty JSX code.
       */
      getEmptyFormsPreview: function getEmptyFormsPreview(props) {
        var clientId = props.clientId;
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "wpforms-gutenberg-form-selector-fragment-block-empty"
        }, /*#__PURE__*/React.createElement("div", {
          className: "wpforms-no-form-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.block_empty_url,
          alt: ""
        }), /*#__PURE__*/React.createElement("p", null, createInterpolateElement(__('You can use <b>WPForms</b> to build contact forms, surveys, payment forms, and more with just a few clicks.', 'wpforms-lite'), {
          b: /*#__PURE__*/React.createElement("strong", null)
        })), /*#__PURE__*/React.createElement("button", {
          type: "button",
          className: "get-started-button components-button is-primary",
          onClick: function onClick() {
            app.openBuilderPopup(clientId);
          }
        }, __('Get Started', 'wpforms-lite')), /*#__PURE__*/React.createElement("p", {
          className: "empty-desc"
        }, createInterpolateElement(__('Need some help? Check out our <a>comprehensive guide.</a>', 'wpforms-lite'), {
          // eslint-disable-next-line jsx-a11y/anchor-has-content
          a: /*#__PURE__*/React.createElement("a", {
            href: wpforms_gutenberg_form_selector.wpforms_guide,
            target: "_blank",
            rel: "noopener noreferrer"
          })
        })), /*#__PURE__*/React.createElement("div", {
          id: "wpforms-gutenberg-popup",
          className: "wpforms-builder-popup"
        }, /*#__PURE__*/React.createElement("iframe", {
          src: "about:blank",
          width: "100%",
          height: "100%",
          id: "wpforms-builder-iframe",
          title: "WPForms Builder Popup"
        }))));
      },
      /**
       * Get block placeholder (form selector) JSX code.
       *
       * @since 1.8.1
       *
       * @param {Object} attributes  Block attributes.
       * @param {Object} handlers    Block event handlers.
       * @param {Object} formOptions Form selector options.
       *
       * @return {JSX.Element} Block placeholder JSX code.
       */
      getBlockPlaceholder: function getBlockPlaceholder(attributes, handlers, formOptions) {
        return /*#__PURE__*/React.createElement(Placeholder, {
          key: "wpforms-gutenberg-form-selector-wrap",
          className: "wpforms-gutenberg-form-selector-wrap"
        }, /*#__PURE__*/React.createElement("img", {
          src: wpforms_gutenberg_form_selector.logo_url,
          alt: ""
        }), /*#__PURE__*/React.createElement(SelectControl, {
          key: "wpforms-gutenberg-form-selector-select-control",
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }));
      }
    },
    /**
     * Get Style Settings panel class.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {string} Style Settings panel class.
     */
    getPanelClass: function getPanelClass(props) {
      var cssClass = 'wpforms-gutenberg-panel wpforms-block-settings-' + props.clientId;
      if (!app.isFullStylingEnabled()) {
        cssClass += ' disabled_panel';
      }
      return cssClass;
    },
    /**
     * Determine whether the full styling is enabled.
     *
     * @since 1.8.1
     *
     * @return {boolean} Whether the full styling is enabled.
     */
    isFullStylingEnabled: function isFullStylingEnabled() {
      return wpforms_gutenberg_form_selector.is_modern_markup && wpforms_gutenberg_form_selector.is_full_styling;
    },
    /**
     * Get block container DOM element.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {Element} Block container.
     */
    getBlockContainer: function getBlockContainer(props) {
      var blockSelector = "#block-".concat(props.clientId, " > div");
      var block = document.querySelector(blockSelector);

      // For FSE / Gutenberg plugin we need to take a look inside the iframe.
      if (!block) {
        var editorCanvas = document.querySelector('iframe[name="editor-canvas"]');
        block = editorCanvas && editorCanvas.contentWindow.document.querySelector(blockSelector);
      }
      return block;
    },
    /**
     * Get settings fields event handlers.
     *
     * @since 1.8.1
     *
     * @param {Object} props Block properties.
     *
     * @return {Object} Object that contains event handlers for the settings fields.
     */
    getSettingsFieldsHandlers: function getSettingsFieldsHandlers(props) {
      // eslint-disable-line max-lines-per-function
      return {
        /**
         * Field style attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        styleAttrChange: function styleAttrChange(attribute, value) {
          var block = app.getBlockContainer(props),
            container = block.querySelector("#wpforms-".concat(props.attributes.formId)),
            property = attribute.replace(/[A-Z]/g, function (letter) {
              return "-".concat(letter.toLowerCase());
            }),
            setAttr = {};
          if (container) {
            switch (property) {
              case 'field-size':
              case 'label-size':
              case 'button-size':
                for (var key in sizes[property][value]) {
                  container.style.setProperty("--wpforms-".concat(property, "-").concat(key), sizes[property][value][key]);
                }
                break;
              default:
                container.style.setProperty("--wpforms-".concat(property), value);
            }
          }
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = false;
          this.updateCopyPasteContent();
          $(window).trigger('wpformsFormSelectorStyleAttrChange', [block, props, attribute, value]);
        },
        /**
         * Field regular attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        attrChange: function attrChange(attribute, value) {
          var setAttr = {};
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = true;
          this.updateCopyPasteContent();
        },
        /**
         * Reset Form Styles settings to defaults.
         *
         * @since 1.8.1
         */
        resetSettings: function resetSettings() {
          for (var key in defaultStyleSettings) {
            this.styleAttrChange(key, defaultStyleSettings[key]);
          }
        },
        /**
         * Update content of the "Copy/Paste" fields.
         *
         * @since 1.8.1
         */
        updateCopyPasteContent: function updateCopyPasteContent() {
          var content = {};
          var atts = wp.data.select('core/block-editor').getBlockAttributes(props.clientId);
          for (var key in defaultStyleSettings) {
            content[key] = atts[key];
          }
          props.setAttributes({
            copyPasteJsonValue: JSON.stringify(content)
          });
        },
        /**
         * Paste settings handler.
         *
         * @since 1.8.1
         *
         * @param {string} value New attribute value.
         */
        pasteSettings: function pasteSettings(value) {
          var pasteAttributes = app.parseValidateJson(value);
          if (!pasteAttributes) {
            wp.data.dispatch('core/notices').createErrorNotice(strings.copy_paste_error, {
              id: 'wpforms-json-parse-error'
            });
            this.updateCopyPasteContent();
            return;
          }
          pasteAttributes.copyPasteJsonValue = value;
          props.setAttributes(pasteAttributes);
          triggerServerRender = true;
        }
      };
    },
    /**
     * Parse and validate JSON string.
     *
     * @since 1.8.1
     *
     * @param {string} value JSON string.
     *
     * @return {boolean|object} Parsed JSON object OR false on error.
     */
    parseValidateJson: function parseValidateJson(value) {
      if (typeof value !== 'string') {
        return false;
      }
      var atts;
      try {
        atts = JSON.parse(value);
      } catch (error) {
        atts = false;
      }
      return atts;
    },
    /**
     * Get WPForms icon DOM element.
     *
     * @since 1.8.1
     *
     * @return {DOM.element} WPForms icon DOM element.
     */
    getIcon: function getIcon() {
      return createElement('svg', {
        width: 20,
        height: 20,
        viewBox: '0 0 612 612',
        className: 'dashicon'
      }, createElement('path', {
        fill: 'currentColor',
        d: 'M544,0H68C30.445,0,0,30.445,0,68v476c0,37.556,30.445,68,68,68h476c37.556,0,68-30.444,68-68V68 C612,30.445,581.556,0,544,0z M464.44,68L387.6,120.02L323.34,68H464.44z M288.66,68l-64.26,52.02L147.56,68H288.66z M544,544H68 V68h22.1l136,92.14l79.9-64.6l79.56,64.6l136-92.14H544V544z M114.24,263.16h95.88v-48.28h-95.88V263.16z M114.24,360.4h95.88 v-48.62h-95.88V360.4z M242.76,360.4h255v-48.62h-255V360.4L242.76,360.4z M242.76,263.16h255v-48.28h-255V263.16L242.76,263.16z M368.22,457.3h129.54V408H368.22V457.3z'
      }));
    },
    /**
     * Get block attributes.
     *
     * @since 1.8.1
     *
     * @return {Object} Block attributes.
     */
    getBlockAttributes: function getBlockAttributes() {
      // eslint-disable-line max-lines-per-function
      return {
        clientId: {
          type: 'string',
          default: ''
        },
        formId: {
          type: 'string',
          default: defaults.formId
        },
        displayTitle: {
          type: 'boolean',
          default: defaults.displayTitle
        },
        displayDesc: {
          type: 'boolean',
          default: defaults.displayDesc
        },
        preview: {
          type: 'boolean'
        },
        fieldSize: {
          type: 'string',
          default: defaults.fieldSize
        },
        fieldBorderRadius: {
          type: 'string',
          default: defaults.fieldBorderRadius
        },
        fieldBackgroundColor: {
          type: 'string',
          default: defaults.fieldBackgroundColor
        },
        fieldBorderColor: {
          type: 'string',
          default: defaults.fieldBorderColor
        },
        fieldTextColor: {
          type: 'string',
          default: defaults.fieldTextColor
        },
        labelSize: {
          type: 'string',
          default: defaults.labelSize
        },
        labelColor: {
          type: 'string',
          default: defaults.labelColor
        },
        labelSublabelColor: {
          type: 'string',
          default: defaults.labelSublabelColor
        },
        labelErrorColor: {
          type: 'string',
          default: defaults.labelErrorColor
        },
        buttonSize: {
          type: 'string',
          default: defaults.buttonSize
        },
        buttonBorderRadius: {
          type: 'string',
          default: defaults.buttonBorderRadius
        },
        buttonBackgroundColor: {
          type: 'string',
          default: defaults.buttonBackgroundColor
        },
        buttonTextColor: {
          type: 'string',
          default: defaults.buttonTextColor
        },
        copyPasteJsonValue: {
          type: 'string',
          default: defaults.copyPasteJsonValue
        }
      };
    },
    /**
     * Get form selector options.
     *
     * @since 1.8.1
     *
     * @return {Array} Form options.
     */
    getFormOptions: function getFormOptions() {
      var formOptions = formList.map(function (value) {
        return {
          value: value.ID,
          label: value.post_title
        };
      });
      formOptions.unshift({
        value: '',
        label: strings.form_select
      });
      return formOptions;
    },
    /**
     * Get size selector options.
     *
     * @since 1.8.1
     *
     * @return {Array} Size options.
     */
    getSizeOptions: function getSizeOptions() {
      return [{
        label: strings.small,
        value: 'small'
      }, {
        label: strings.medium,
        value: 'medium'
      }, {
        label: strings.large,
        value: 'large'
      }];
    },
    /**
     * Event `wpformsFormSelectorEdit` handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e     Event object.
     * @param {Object} props Block properties.
     */
    blockEdit: function blockEdit(e, props) {
      var block = app.getBlockContainer(props);
      if (!block || !block.dataset) {
        return;
      }
      app.initLeadFormSettings(block.parentElement);
    },
    /**
     * Init Lead Form Settings panels.
     *
     * @since 1.8.1
     *
     * @param {Element} block Block element.
     */
    initLeadFormSettings: function initLeadFormSettings(block) {
      if (!block || !block.dataset) {
        return;
      }
      if (!app.isFullStylingEnabled()) {
        return;
      }
      var clientId = block.dataset.block;
      var $form = $(block.querySelector('.wpforms-container'));
      var $panel = $(".wpforms-block-settings-".concat(clientId));
      if ($form.hasClass('wpforms-lead-forms-container')) {
        $panel.addClass('disabled_panel').find('.wpforms-gutenberg-panel-notice.wpforms-lead-form-notice').css('display', 'block');
        $panel.find('.wpforms-gutenberg-panel-notice.wpforms-use-modern-notice').css('display', 'none');
        return;
      }
      $panel.removeClass('disabled_panel').find('.wpforms-gutenberg-panel-notice.wpforms-lead-form-notice').css('display', 'none');
      $panel.find('.wpforms-gutenberg-panel-notice.wpforms-use-modern-notice').css('display', null);
    },
    /**
     * Event `wpformsFormSelectorFormLoaded` handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e Event object.
     */
    formLoaded: function formLoaded(e) {
      app.initLeadFormSettings(e.detail.block);
      app.updateAccentColors(e.detail);
      app.loadChoicesJS(e.detail);
      app.initRichTextField(e.detail.formId);
      $(e.detail.block).off('click').on('click', app.blockClick);
    },
    /**
     * Click on the block event handler.
     *
     * @since 1.8.1
     *
     * @param {Object} e Event object.
     */
    blockClick: function blockClick(e) {
      app.initLeadFormSettings(e.currentTarget);
    },
    /**
     * Update accent colors of some fields in GB block in Modern Markup mode.
     *
     * @since 1.8.1
     *
     * @param {Object} detail Event details object.
     */
    updateAccentColors: function updateAccentColors(detail) {
      if (!wpforms_gutenberg_form_selector.is_modern_markup || !window.WPForms || !window.WPForms.FrontendModern || !detail.block) {
        return;
      }
      var $form = $(detail.block.querySelector("#wpforms-".concat(detail.formId))),
        FrontendModern = window.WPForms.FrontendModern;
      FrontendModern.updateGBBlockPageIndicatorColor($form);
      FrontendModern.updateGBBlockIconChoicesColor($form);
      FrontendModern.updateGBBlockRatingColor($form);
    },
    /**
     * Init Modern style Dropdown fields (<select>).
     *
     * @since 1.8.1
     *
     * @param {Object} detail Event details object.
     */
    loadChoicesJS: function loadChoicesJS(detail) {
      if (typeof window.Choices !== 'function') {
        return;
      }
      var $form = $(detail.block.querySelector("#wpforms-".concat(detail.formId)));
      $form.find('.choicesjs-select').each(function (idx, el) {
        var $el = $(el);
        if ($el.data('choice') === 'active') {
          return;
        }
        var args = window.wpforms_choicesjs_config || {},
          searchEnabled = $el.data('search-enabled'),
          $field = $el.closest('.wpforms-field');
        args.searchEnabled = 'undefined' !== typeof searchEnabled ? searchEnabled : true;
        args.callbackOnInit = function () {
          var self = this,
            $element = $(self.passedElement.element),
            $input = $(self.input.element),
            sizeClass = $element.data('size-class');

          // Add CSS-class for size.
          if (sizeClass) {
            $(self.containerOuter.element).addClass(sizeClass);
          }

          /**
           * If a multiple select has selected choices - hide a placeholder text.
           * In case if select is empty - we return placeholder text back.
           */
          if ($element.prop('multiple')) {
            // On init event.
            $input.data('placeholder', $input.attr('placeholder'));
            if (self.getValue(true).length) {
              $input.removeAttr('placeholder');
            }
          }
          this.disable();
          $field.find('.is-disabled').removeClass('is-disabled');
        };
        try {
          var choicesInstance = new Choices(el, args);

          // Save Choices.js instance for future access.
          $el.data('choicesjs', choicesInstance);
        } catch (e) {} // eslint-disable-line no-empty
      });
    },
    /**
     * Initialize RichText field.
     *
     * @since 1.8.1
     *
     * @param {number} formId Form ID.
     */
    initRichTextField: function initRichTextField(formId) {
      // Set default tab to `Visual`.
      $("#wpforms-".concat(formId, " .wp-editor-wrap")).removeClass('html-active').addClass('tmce-active');
    }
  };

  // Provide access to public functions/properties.
  return app;
}(document, window, jQuery);

// Initialize.
WPForms.FormSelector.init();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfcmVnZW5lcmF0b3JSdW50aW1lIiwiZSIsInQiLCJyIiwiT2JqZWN0IiwicHJvdG90eXBlIiwibiIsImhhc093blByb3BlcnR5IiwibyIsImRlZmluZVByb3BlcnR5IiwidmFsdWUiLCJpIiwiU3ltYm9sIiwiYSIsIml0ZXJhdG9yIiwiYyIsImFzeW5jSXRlcmF0b3IiLCJ1IiwidG9TdHJpbmdUYWciLCJkZWZpbmUiLCJlbnVtZXJhYmxlIiwiY29uZmlndXJhYmxlIiwid3JpdGFibGUiLCJ3cmFwIiwiR2VuZXJhdG9yIiwiY3JlYXRlIiwiQ29udGV4dCIsIm1ha2VJbnZva2VNZXRob2QiLCJ0cnlDYXRjaCIsInR5cGUiLCJhcmciLCJjYWxsIiwiaCIsImwiLCJmIiwicyIsInkiLCJHZW5lcmF0b3JGdW5jdGlvbiIsIkdlbmVyYXRvckZ1bmN0aW9uUHJvdG90eXBlIiwicCIsImQiLCJnZXRQcm90b3R5cGVPZiIsInYiLCJ2YWx1ZXMiLCJnIiwiZGVmaW5lSXRlcmF0b3JNZXRob2RzIiwiZm9yRWFjaCIsIl9pbnZva2UiLCJBc3luY0l0ZXJhdG9yIiwiaW52b2tlIiwiX3R5cGVvZiIsInJlc29sdmUiLCJfX2F3YWl0IiwidGhlbiIsImNhbGxJbnZva2VXaXRoTWV0aG9kQW5kQXJnIiwiRXJyb3IiLCJkb25lIiwibWV0aG9kIiwiZGVsZWdhdGUiLCJtYXliZUludm9rZURlbGVnYXRlIiwic2VudCIsIl9zZW50IiwiZGlzcGF0Y2hFeGNlcHRpb24iLCJhYnJ1cHQiLCJyZXR1cm4iLCJUeXBlRXJyb3IiLCJyZXN1bHROYW1lIiwibmV4dCIsIm5leHRMb2MiLCJwdXNoVHJ5RW50cnkiLCJ0cnlMb2MiLCJjYXRjaExvYyIsImZpbmFsbHlMb2MiLCJhZnRlckxvYyIsInRyeUVudHJpZXMiLCJwdXNoIiwicmVzZXRUcnlFbnRyeSIsImNvbXBsZXRpb24iLCJyZXNldCIsImlzTmFOIiwibGVuZ3RoIiwiZGlzcGxheU5hbWUiLCJpc0dlbmVyYXRvckZ1bmN0aW9uIiwiY29uc3RydWN0b3IiLCJuYW1lIiwibWFyayIsInNldFByb3RvdHlwZU9mIiwiX19wcm90b19fIiwiYXdyYXAiLCJhc3luYyIsIlByb21pc2UiLCJrZXlzIiwicmV2ZXJzZSIsInBvcCIsInByZXYiLCJjaGFyQXQiLCJzbGljZSIsInN0b3AiLCJydmFsIiwiaGFuZGxlIiwiY29tcGxldGUiLCJmaW5pc2giLCJjYXRjaCIsIl9jYXRjaCIsImRlbGVnYXRlWWllbGQiLCJhc3luY0dlbmVyYXRvclN0ZXAiLCJnZW4iLCJyZWplY3QiLCJfbmV4dCIsIl90aHJvdyIsImtleSIsImluZm8iLCJlcnJvciIsIl9hc3luY1RvR2VuZXJhdG9yIiwiZm4iLCJzZWxmIiwiYXJncyIsImFyZ3VtZW50cyIsImFwcGx5IiwiZXJyIiwidW5kZWZpbmVkIiwiV1BGb3JtcyIsIndpbmRvdyIsIkZvcm1TZWxlY3RvciIsImRvY3VtZW50IiwiJCIsIl93cCIsIndwIiwiX3dwJHNlcnZlclNpZGVSZW5kZXIiLCJzZXJ2ZXJTaWRlUmVuZGVyIiwiU2VydmVyU2lkZVJlbmRlciIsImNvbXBvbmVudHMiLCJfd3AkZWxlbWVudCIsImVsZW1lbnQiLCJjcmVhdGVFbGVtZW50IiwiRnJhZ21lbnQiLCJ1c2VTdGF0ZSIsImNyZWF0ZUludGVycG9sYXRlRWxlbWVudCIsInJlZ2lzdGVyQmxvY2tUeXBlIiwiYmxvY2tzIiwiX3JlZiIsImJsb2NrRWRpdG9yIiwiZWRpdG9yIiwiSW5zcGVjdG9yQ29udHJvbHMiLCJJbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzIiwiUGFuZWxDb2xvclNldHRpbmdzIiwiX3dwJGNvbXBvbmVudHMiLCJTZWxlY3RDb250cm9sIiwiVG9nZ2xlQ29udHJvbCIsIlBhbmVsQm9keSIsIlBsYWNlaG9sZGVyIiwiRmxleCIsIkZsZXhCbG9jayIsIl9fZXhwZXJpbWVudGFsVW5pdENvbnRyb2wiLCJUZXh0YXJlYUNvbnRyb2wiLCJCdXR0b24iLCJNb2RhbCIsIl93cGZvcm1zX2d1dGVuYmVyZ19mbyIsIndwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IiLCJzdHJpbmdzIiwiZGVmYXVsdHMiLCJzaXplcyIsInVybHMiLCJpc1BybyIsImRlZmF1bHRTdHlsZVNldHRpbmdzIiwiX18iLCJpMThuIiwiZm9ybUxpc3QiLCJmb3JtcyIsInRyaWdnZXJTZXJ2ZXJSZW5kZXIiLCIkcG9wdXAiLCJpc0ZldGNoaW5nIiwiYXBwIiwiaW5pdCIsImluaXREZWZhdWx0cyIsInJlZ2lzdGVyQmxvY2siLCJyZWFkeSIsImV2ZW50cyIsIm9uIiwiXyIsImRlYm91bmNlIiwiYmxvY2tFZGl0IiwiZm9ybUxvYWRlZCIsImdldEZvcm1zIiwiX2NhbGxlZSIsInJlc3BvbnNlIiwiX2NhbGxlZSQiLCJfY29udGV4dCIsImFwaUZldGNoIiwicGF0aCIsImNhY2hlIiwidDAiLCJjb25zb2xlIiwib3BlbkJ1aWxkZXJQb3B1cCIsImNsaWVudElEIiwiaXNFbXB0eU9iamVjdCIsInRtcGwiLCJwYXJlbnQiLCJhZnRlciIsInNpYmxpbmdzIiwidXJsIiwiZ2V0X3N0YXJ0ZWRfdXJsIiwiJGlmcmFtZSIsImZpbmQiLCJidWlsZGVyQ2xvc2VCdXR0b25FdmVudCIsImF0dHIiLCJmYWRlSW4iLCJvZmYiLCJhY3Rpb24iLCJmb3JtSWQiLCJmb3JtVGl0bGUiLCJuZXdCbG9jayIsImNyZWF0ZUJsb2NrIiwidG9TdHJpbmciLCJJRCIsInBvc3RfdGl0bGUiLCJkYXRhIiwiZGlzcGF0Y2giLCJyZW1vdmVCbG9jayIsImluc2VydEJsb2NrcyIsInRpdGxlIiwiZGVzY3JpcHRpb24iLCJpY29uIiwiZ2V0SWNvbiIsImtleXdvcmRzIiwiZm9ybV9rZXl3b3JkcyIsImNhdGVnb3J5IiwiYXR0cmlidXRlcyIsImdldEJsb2NrQXR0cmlidXRlcyIsInN1cHBvcnRzIiwiY3VzdG9tQ2xhc3NOYW1lIiwiaGFzRm9ybXMiLCJleGFtcGxlIiwicHJldmlldyIsImVkaXQiLCJwcm9wcyIsImZvcm1PcHRpb25zIiwiZ2V0Rm9ybU9wdGlvbnMiLCJoYW5kbGVycyIsImdldFNldHRpbmdzRmllbGRzSGFuZGxlcnMiLCJjbGllbnRJZCIsInNldEF0dHJpYnV0ZXMiLCJqc3giLCJqc3hQYXJ0cyIsImdldE1haW5TZXR0aW5ncyIsImdldEVtcHR5Rm9ybXNQcmV2aWV3Iiwic2l6ZU9wdGlvbnMiLCJnZXRTaXplT3B0aW9ucyIsImdldFN0eWxlU2V0dGluZ3MiLCJnZXRBZHZhbmNlZFNldHRpbmdzIiwiZ2V0QmxvY2tGb3JtQ29udGVudCIsInVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQiLCJ0cmlnZ2VyIiwiZ2V0QmxvY2tQcmV2aWV3IiwiZ2V0QmxvY2tQbGFjZWhvbGRlciIsInNhdmUiLCJwcmludEVtcHR5Rm9ybXNOb3RpY2UiLCJSZWFjdCIsImNsYXNzTmFtZSIsImZvcm1fc2V0dGluZ3MiLCJsYWJlbCIsImZvcm1fc2VsZWN0ZWQiLCJvcHRpb25zIiwib25DaGFuZ2UiLCJhdHRyQ2hhbmdlIiwiaHJlZiIsImZvcm1fdXJsIiwicmVwbGFjZSIsInJlbCIsInRhcmdldCIsImZvcm1fZWRpdCIsImVudHJpZXNfdXJsIiwiZm9ybV9lbnRyaWVzIiwic2hvd190aXRsZSIsImNoZWNrZWQiLCJkaXNwbGF5VGl0bGUiLCJzaG93X2Rlc2NyaXB0aW9uIiwiZGlzcGxheURlc2MiLCJwYW5lbF9ub3RpY2VfaGVhZCIsInBhbmVsX25vdGljZV90ZXh0IiwicGFuZWxfbm90aWNlX2xpbmsiLCJwYW5lbF9ub3RpY2VfbGlua190ZXh0Iiwic3R5bGUiLCJkaXNwbGF5Iiwib25DbGljayIsImdldEZpZWxkU3R5bGVzIiwiZ2V0UGFuZWxDbGFzcyIsImZpZWxkX3N0eWxlcyIsInVzZV9tb2Rlcm5fbm90aWNlX2hlYWQiLCJ1c2VfbW9kZXJuX25vdGljZV90ZXh0IiwidXNlX21vZGVybl9ub3RpY2VfbGluayIsImxlYXJuX21vcmUiLCJsZWFkX2Zvcm1zX3BhbmVsX25vdGljZV9oZWFkIiwibGVhZF9mb3Jtc19wYW5lbF9ub3RpY2VfdGV4dCIsImdhcCIsImFsaWduIiwianVzdGlmeSIsInNpemUiLCJmaWVsZFNpemUiLCJzdHlsZUF0dHJDaGFuZ2UiLCJib3JkZXJfcmFkaXVzIiwiZmllbGRCb3JkZXJSYWRpdXMiLCJpc1VuaXRTZWxlY3RUYWJiYWJsZSIsImNvbG9ycyIsIl9fZXhwZXJpbWVudGFsSXNSZW5kZXJlZEluU2lkZWJhciIsImVuYWJsZUFscGhhIiwic2hvd1RpdGxlIiwiY29sb3JTZXR0aW5ncyIsImZpZWxkQmFja2dyb3VuZENvbG9yIiwiYmFja2dyb3VuZCIsImZpZWxkQm9yZGVyQ29sb3IiLCJib3JkZXIiLCJmaWVsZFRleHRDb2xvciIsInRleHQiLCJnZXRMYWJlbFN0eWxlcyIsImxhYmVsX3N0eWxlcyIsImxhYmVsU2l6ZSIsImxhYmVsQ29sb3IiLCJsYWJlbFN1YmxhYmVsQ29sb3IiLCJzdWJsYWJlbF9oaW50cyIsImxhYmVsRXJyb3JDb2xvciIsImVycm9yX21lc3NhZ2UiLCJnZXRCdXR0b25TdHlsZXMiLCJidXR0b25fc3R5bGVzIiwiYnV0dG9uU2l6ZSIsImJ1dHRvbkJvcmRlclJhZGl1cyIsImJ1dHRvbkJhY2tncm91bmRDb2xvciIsImJ1dHRvblRleHRDb2xvciIsImJ1dHRvbl9jb2xvcl9ub3RpY2UiLCJfdXNlU3RhdGUiLCJfdXNlU3RhdGUyIiwiX3NsaWNlZFRvQXJyYXkiLCJpc09wZW4iLCJzZXRPcGVuIiwib3Blbk1vZGFsIiwiY2xvc2VNb2RhbCIsImNvcHlfcGFzdGVfc2V0dGluZ3MiLCJyb3dzIiwic3BlbGxDaGVjayIsImNvcHlQYXN0ZUpzb25WYWx1ZSIsInBhc3RlU2V0dGluZ3MiLCJkYW5nZXJvdXNseVNldElubmVySFRNTCIsIl9faHRtbCIsImNvcHlfcGFzdGVfbm90aWNlIiwicmVzZXRfc3R5bGVfc2V0dGluZ3MiLCJvblJlcXVlc3RDbG9zZSIsInJlc2V0X3NldHRpbmdzX2NvbmZpcm1fdGV4dCIsImlzU2Vjb25kYXJ5IiwiYnRuX25vIiwiaXNQcmltYXJ5IiwicmVzZXRTZXR0aW5ncyIsImJ0bl95ZXNfcmVzZXQiLCJibG9jayIsImdldEJsb2NrQ29udGFpbmVyIiwiaW5uZXJIVE1MIiwiYmxvY2tIVE1MIiwibG9hZGVkRm9ybUlkIiwic3JjIiwiYmxvY2tfcHJldmlld191cmwiLCJ3aWR0aCIsImFsdCIsImJsb2NrX2VtcHR5X3VybCIsImIiLCJ3cGZvcm1zX2d1aWRlIiwiaWQiLCJoZWlnaHQiLCJsb2dvX3VybCIsImNzc0NsYXNzIiwiaXNGdWxsU3R5bGluZ0VuYWJsZWQiLCJpc19tb2Rlcm5fbWFya3VwIiwiaXNfZnVsbF9zdHlsaW5nIiwiYmxvY2tTZWxlY3RvciIsImNvbmNhdCIsInF1ZXJ5U2VsZWN0b3IiLCJlZGl0b3JDYW52YXMiLCJjb250ZW50V2luZG93IiwiYXR0cmlidXRlIiwiY29udGFpbmVyIiwicHJvcGVydHkiLCJsZXR0ZXIiLCJ0b0xvd2VyQ2FzZSIsInNldEF0dHIiLCJzZXRQcm9wZXJ0eSIsImNvbnRlbnQiLCJhdHRzIiwic2VsZWN0IiwiSlNPTiIsInN0cmluZ2lmeSIsInBhc3RlQXR0cmlidXRlcyIsInBhcnNlVmFsaWRhdGVKc29uIiwiY3JlYXRlRXJyb3JOb3RpY2UiLCJjb3B5X3Bhc3RlX2Vycm9yIiwicGFyc2UiLCJ2aWV3Qm94IiwiZmlsbCIsImRlZmF1bHQiLCJtYXAiLCJ1bnNoaWZ0IiwiZm9ybV9zZWxlY3QiLCJzbWFsbCIsIm1lZGl1bSIsImxhcmdlIiwiZGF0YXNldCIsImluaXRMZWFkRm9ybVNldHRpbmdzIiwicGFyZW50RWxlbWVudCIsIiRmb3JtIiwiJHBhbmVsIiwiaGFzQ2xhc3MiLCJhZGRDbGFzcyIsImNzcyIsInJlbW92ZUNsYXNzIiwiZGV0YWlsIiwidXBkYXRlQWNjZW50Q29sb3JzIiwibG9hZENob2ljZXNKUyIsImluaXRSaWNoVGV4dEZpZWxkIiwiYmxvY2tDbGljayIsImN1cnJlbnRUYXJnZXQiLCJGcm9udGVuZE1vZGVybiIsInVwZGF0ZUdCQmxvY2tQYWdlSW5kaWNhdG9yQ29sb3IiLCJ1cGRhdGVHQkJsb2NrSWNvbkNob2ljZXNDb2xvciIsInVwZGF0ZUdCQmxvY2tSYXRpbmdDb2xvciIsIkNob2ljZXMiLCJlYWNoIiwiaWR4IiwiZWwiLCIkZWwiLCJ3cGZvcm1zX2Nob2ljZXNqc19jb25maWciLCJzZWFyY2hFbmFibGVkIiwiJGZpZWxkIiwiY2xvc2VzdCIsImNhbGxiYWNrT25Jbml0IiwiJGVsZW1lbnQiLCJwYXNzZWRFbGVtZW50IiwiJGlucHV0IiwiaW5wdXQiLCJzaXplQ2xhc3MiLCJjb250YWluZXJPdXRlciIsInByb3AiLCJnZXRWYWx1ZSIsInJlbW92ZUF0dHIiLCJkaXNhYmxlIiwiY2hvaWNlc0luc3RhbmNlIiwialF1ZXJ5Il0sInNvdXJjZXMiOlsiZmFrZV82YWZiYmE5NC5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvKiBnbG9iYWwgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3RvciwgQ2hvaWNlcywgSlNYLCBET00gKi9cbi8qIGpzaGludCBlczM6IGZhbHNlLCBlc3ZlcnNpb246IDYgKi9cblxuLyoqXG4gKiBHdXRlbmJlcmcgZWRpdG9yIGJsb2NrLlxuICpcbiAqIEBzaW5jZSAxLjguMVxuICovXG5jb25zdCBXUEZvcm1zID0gd2luZG93LldQRm9ybXMgfHwge307XG5cbldQRm9ybXMuRm9ybVNlbGVjdG9yID0gV1BGb3Jtcy5Gb3JtU2VsZWN0b3IgfHwgKCBmdW5jdGlvbiggZG9jdW1lbnQsIHdpbmRvdywgJCApIHtcblx0Y29uc3QgeyBzZXJ2ZXJTaWRlUmVuZGVyOiBTZXJ2ZXJTaWRlUmVuZGVyID0gd3AuY29tcG9uZW50cy5TZXJ2ZXJTaWRlUmVuZGVyIH0gPSB3cDtcblx0Y29uc3QgeyBjcmVhdGVFbGVtZW50LCBGcmFnbWVudCwgdXNlU3RhdGUsIGNyZWF0ZUludGVycG9sYXRlRWxlbWVudCB9ID0gd3AuZWxlbWVudDtcblx0Y29uc3QgeyByZWdpc3RlckJsb2NrVHlwZSB9ID0gd3AuYmxvY2tzO1xuXHRjb25zdCB7IEluc3BlY3RvckNvbnRyb2xzLCBJbnNwZWN0b3JBZHZhbmNlZENvbnRyb2xzLCBQYW5lbENvbG9yU2V0dGluZ3MgfSA9IHdwLmJsb2NrRWRpdG9yIHx8IHdwLmVkaXRvcjtcblx0Y29uc3QgeyBTZWxlY3RDb250cm9sLCBUb2dnbGVDb250cm9sLCBQYW5lbEJvZHksIFBsYWNlaG9sZGVyLCBGbGV4LCBGbGV4QmxvY2ssIF9fZXhwZXJpbWVudGFsVW5pdENvbnRyb2wsIFRleHRhcmVhQ29udHJvbCwgQnV0dG9uLCBNb2RhbCB9ID0gd3AuY29tcG9uZW50cztcblx0Y29uc3QgeyBzdHJpbmdzLCBkZWZhdWx0cywgc2l6ZXMsIHVybHMsIGlzUHJvIH0gPSB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yO1xuXHRjb25zdCBkZWZhdWx0U3R5bGVTZXR0aW5ncyA9IGRlZmF1bHRzO1xuXHRjb25zdCB7IF9fIH0gPSB3cC5pMThuO1xuXG5cdC8qKlxuXHQgKiBMaXN0IG9mIGZvcm1zLlxuXHQgKlxuXHQgKiBEZWZhdWx0IHZhbHVlIGlzIGxvY2FsaXplZCBpbiBGb3JtU2VsZWN0b3IucGhwLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjRcblx0ICpcblx0ICogQHR5cGUge09iamVjdH1cblx0ICovXG5cdGxldCBmb3JtTGlzdCA9IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuZm9ybXM7XG5cblx0LyoqXG5cdCAqIEJsb2NrcyBydW50aW1lIGRhdGEuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguMVxuXHQgKlxuXHQgKiBAdHlwZSB7T2JqZWN0fVxuXHQgKi9cblx0Y29uc3QgYmxvY2tzID0ge307XG5cblx0LyoqXG5cdCAqIFdoZXRoZXIgaXQgaXMgbmVlZGVkIHRvIHRyaWdnZXIgc2VydmVyIHJlbmRlcmluZy5cblx0ICpcblx0ICogQHNpbmNlIDEuOC4xXG5cdCAqXG5cdCAqIEB0eXBlIHtib29sZWFufVxuXHQgKi9cblx0bGV0IHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSB0cnVlO1xuXG5cdC8qKlxuXHQgKiBQb3B1cCBjb250YWluZXIuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguM1xuXHQgKlxuXHQgKiBAdHlwZSB7T2JqZWN0fVxuXHQgKi9cblx0bGV0ICRwb3B1cCA9IHt9O1xuXG5cdC8qKlxuXHQgKiBUcmFjayBmZXRjaCBzdGF0dXMuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguNFxuXHQgKlxuXHQgKiBAdHlwZSB7Ym9vbGVhbn1cblx0ICovXG5cdGxldCBpc0ZldGNoaW5nID0gZmFsc2U7XG5cblx0LyoqXG5cdCAqIFB1YmxpYyBmdW5jdGlvbnMgYW5kIHByb3BlcnRpZXMuXG5cdCAqXG5cdCAqIEBzaW5jZSAxLjguMVxuXHQgKlxuXHQgKiBAdHlwZSB7T2JqZWN0fVxuXHQgKi9cblx0Y29uc3QgYXBwID0ge1xuXG5cdFx0LyoqXG5cdFx0ICogU3RhcnQgdGhlIGVuZ2luZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdGluaXQoKSB7XG5cdFx0XHRhcHAuaW5pdERlZmF1bHRzKCk7XG5cdFx0XHRhcHAucmVnaXN0ZXJCbG9jaygpO1xuXG5cdFx0XHQkKCBhcHAucmVhZHkgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRG9jdW1lbnQgcmVhZHkuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRyZWFkeSgpIHtcblx0XHRcdGFwcC5ldmVudHMoKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRXZlbnRzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0ZXZlbnRzKCkge1xuXHRcdFx0JCggd2luZG93IClcblx0XHRcdFx0Lm9uKCAnd3Bmb3Jtc0Zvcm1TZWxlY3RvckVkaXQnLCBfLmRlYm91bmNlKCBhcHAuYmxvY2tFZGl0LCAyNTAgKSApXG5cdFx0XHRcdC5vbiggJ3dwZm9ybXNGb3JtU2VsZWN0b3JGb3JtTG9hZGVkJywgXy5kZWJvdW5jZSggYXBwLmZvcm1Mb2FkZWQsIDI1MCApICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBmcmVzaCBsaXN0IG9mIGZvcm1zIHZpYSBSRVNULUFQSS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguNFxuXHRcdCAqXG5cdFx0ICogQHNlZSBodHRwczovL2RldmVsb3Blci53b3JkcHJlc3Mub3JnL2Jsb2NrLWVkaXRvci9yZWZlcmVuY2UtZ3VpZGVzL3BhY2thZ2VzL3BhY2thZ2VzLWFwaS1mZXRjaC9cblx0XHQgKi9cblx0XHRhc3luYyBnZXRGb3JtcygpIHtcblx0XHRcdC8vIElmIGEgZmV0Y2ggaXMgYWxyZWFkeSBpbiBwcm9ncmVzcywgZXhpdCB0aGUgZnVuY3Rpb24uXG5cdFx0XHRpZiAoIGlzRmV0Y2hpbmcgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0Ly8gU2V0IHRoZSBmbGFnIHRvIHRydWUgaW5kaWNhdGluZyBhIGZldGNoIGlzIGluIHByb2dyZXNzLlxuXHRcdFx0aXNGZXRjaGluZyA9IHRydWU7XG5cblx0XHRcdHRyeSB7XG5cdFx0XHRcdC8vIEZldGNoIGZvcm1zLlxuXHRcdFx0XHRjb25zdCByZXNwb25zZSA9IGF3YWl0IHdwLmFwaUZldGNoKCB7XG5cdFx0XHRcdFx0cGF0aDogJy93cGZvcm1zL3YxL2Zvcm1zLycsXG5cdFx0XHRcdFx0bWV0aG9kOiAnR0VUJyxcblx0XHRcdFx0XHRjYWNoZTogJ25vLWNhY2hlJyxcblx0XHRcdFx0fSApO1xuXG5cdFx0XHRcdC8vIFVwZGF0ZSB0aGUgZm9ybSBsaXN0LlxuXHRcdFx0XHRmb3JtTGlzdCA9IHJlc3BvbnNlLmZvcm1zO1xuXHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHQvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgbm8tY29uc29sZVxuXHRcdFx0XHRjb25zb2xlLmVycm9yKCBlcnJvciApO1xuXHRcdFx0fSBmaW5hbGx5IHtcblx0XHRcdFx0aXNGZXRjaGluZyA9IGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBPcGVuIGJ1aWxkZXIgcG9wdXAuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS42LjJcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBjbGllbnRJRCBCbG9jayBDbGllbnQgSUQuXG5cdFx0ICovXG5cdFx0b3BlbkJ1aWxkZXJQb3B1cCggY2xpZW50SUQgKSB7XG5cdFx0XHRpZiAoICQuaXNFbXB0eU9iamVjdCggJHBvcHVwICkgKSB7XG5cdFx0XHRcdGNvbnN0IHRtcGwgPSAkKCAnI3dwZm9ybXMtZ3V0ZW5iZXJnLXBvcHVwJyApO1xuXHRcdFx0XHRjb25zdCBwYXJlbnQgPSAkKCAnI3dwd3JhcCcgKTtcblxuXHRcdFx0XHRwYXJlbnQuYWZ0ZXIoIHRtcGwgKTtcblxuXHRcdFx0XHQkcG9wdXAgPSBwYXJlbnQuc2libGluZ3MoICcjd3Bmb3Jtcy1ndXRlbmJlcmctcG9wdXAnICk7XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0IHVybCA9IHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuZ2V0X3N0YXJ0ZWRfdXJsLFxuXHRcdFx0XHQkaWZyYW1lID0gJHBvcHVwLmZpbmQoICdpZnJhbWUnICk7XG5cblx0XHRcdGFwcC5idWlsZGVyQ2xvc2VCdXR0b25FdmVudCggY2xpZW50SUQgKTtcblx0XHRcdCRpZnJhbWUuYXR0ciggJ3NyYycsIHVybCApO1xuXHRcdFx0JHBvcHVwLmZhZGVJbigpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDbG9zZSBidXR0b24gKGluc2lkZSB0aGUgZm9ybSBidWlsZGVyKSBjbGljayBldmVudC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguM1xuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGNsaWVudElEIEJsb2NrIENsaWVudCBJRC5cblx0XHQgKi9cblx0XHRidWlsZGVyQ2xvc2VCdXR0b25FdmVudCggY2xpZW50SUQgKSB7XG5cdFx0XHQkcG9wdXBcblx0XHRcdFx0Lm9mZiggJ3dwZm9ybXNCdWlsZGVySW5Qb3B1cENsb3NlJyApXG5cdFx0XHRcdC5vbiggJ3dwZm9ybXNCdWlsZGVySW5Qb3B1cENsb3NlJywgZnVuY3Rpb24oIGUsIGFjdGlvbiwgZm9ybUlkLCBmb3JtVGl0bGUgKSB7XG5cdFx0XHRcdFx0aWYgKCBhY3Rpb24gIT09ICdzYXZlZCcgfHwgISBmb3JtSWQgKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0Ly8gSW5zZXJ0IGEgbmV3IGJsb2NrIHdoZW4gYSBuZXcgZm9ybSBpcyBjcmVhdGVkIGZyb20gdGhlIHBvcHVwIHRvIHVwZGF0ZSB0aGUgZm9ybSBsaXN0IGFuZCBhdHRyaWJ1dGVzLlxuXHRcdFx0XHRcdGNvbnN0IG5ld0Jsb2NrID0gd3AuYmxvY2tzLmNyZWF0ZUJsb2NrKCAnd3Bmb3Jtcy9mb3JtLXNlbGVjdG9yJywge1xuXHRcdFx0XHRcdFx0Zm9ybUlkOiBmb3JtSWQudG9TdHJpbmcoKSwgLy8gRXhwZWN0cyBzdHJpbmcgdmFsdWUsIG1ha2Ugc3VyZSB3ZSBpbnNlcnQgc3RyaW5nLlxuXHRcdFx0XHRcdH0gKTtcblxuXHRcdFx0XHRcdC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBjYW1lbGNhc2Vcblx0XHRcdFx0XHRmb3JtTGlzdCA9IFsgeyBJRDogZm9ybUlkLCBwb3N0X3RpdGxlOiBmb3JtVGl0bGUgfSBdO1xuXG5cdFx0XHRcdFx0Ly8gSW5zZXJ0IGEgbmV3IGJsb2NrLlxuXHRcdFx0XHRcdHdwLmRhdGEuZGlzcGF0Y2goICdjb3JlL2Jsb2NrLWVkaXRvcicgKS5yZW1vdmVCbG9jayggY2xpZW50SUQgKTtcblx0XHRcdFx0XHR3cC5kYXRhLmRpc3BhdGNoKCAnY29yZS9ibG9jay1lZGl0b3InICkuaW5zZXJ0QmxvY2tzKCBuZXdCbG9jayApO1xuXHRcdFx0XHR9ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFJlZ2lzdGVyIGJsb2NrLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0Ly8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRyZWdpc3RlckJsb2NrKCkge1xuXHRcdFx0cmVnaXN0ZXJCbG9ja1R5cGUoICd3cGZvcm1zL2Zvcm0tc2VsZWN0b3InLCB7XG5cdFx0XHRcdHRpdGxlOiBzdHJpbmdzLnRpdGxlLFxuXHRcdFx0XHRkZXNjcmlwdGlvbjogc3RyaW5ncy5kZXNjcmlwdGlvbixcblx0XHRcdFx0aWNvbjogYXBwLmdldEljb24oKSxcblx0XHRcdFx0a2V5d29yZHM6IHN0cmluZ3MuZm9ybV9rZXl3b3Jkcyxcblx0XHRcdFx0Y2F0ZWdvcnk6ICd3aWRnZXRzJyxcblx0XHRcdFx0YXR0cmlidXRlczogYXBwLmdldEJsb2NrQXR0cmlidXRlcygpLFxuXHRcdFx0XHRzdXBwb3J0czoge1xuXHRcdFx0XHRcdGN1c3RvbUNsYXNzTmFtZTogYXBwLmhhc0Zvcm1zKCksXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGV4YW1wbGU6IHtcblx0XHRcdFx0XHRhdHRyaWJ1dGVzOiB7XG5cdFx0XHRcdFx0XHRwcmV2aWV3OiB0cnVlLFxuXHRcdFx0XHRcdH0sXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGVkaXQoIHByb3BzICkge1xuXHRcdFx0XHRcdC8vIEdldCBmcmVzaCBsaXN0IG9mIGZvcm1zLlxuXHRcdFx0XHRcdGFwcC5nZXRGb3JtcygpO1xuXG5cdFx0XHRcdFx0Y29uc3QgeyBhdHRyaWJ1dGVzIH0gPSBwcm9wcztcblx0XHRcdFx0XHRjb25zdCBmb3JtT3B0aW9ucyA9IGFwcC5nZXRGb3JtT3B0aW9ucygpO1xuXHRcdFx0XHRcdGNvbnN0IGhhbmRsZXJzID0gYXBwLmdldFNldHRpbmdzRmllbGRzSGFuZGxlcnMoIHByb3BzICk7XG5cblx0XHRcdFx0XHQvLyBTdG9yZSBibG9jayBjbGllbnRJZCBpbiBhdHRyaWJ1dGVzLlxuXHRcdFx0XHRcdGlmICggISBhdHRyaWJ1dGVzLmNsaWVudElkICkge1xuXHRcdFx0XHRcdFx0Ly8gV2UganVzdCB3YW50IGNsaWVudCBJRCB0byB1cGRhdGUgb25jZS5cblx0XHRcdFx0XHRcdC8vIFRoZSBibG9jayBlZGl0b3IgZG9lc24ndCBoYXZlIGEgZml4ZWQgYmxvY2sgSUQsIHNvIHdlIG5lZWQgdG8gZ2V0IGl0IG9uIHRoZSBpbml0aWFsIGxvYWQsIGJ1dCBvbmx5IG9uY2UuXG5cdFx0XHRcdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKCB7IGNsaWVudElkOiBwcm9wcy5jbGllbnRJZCB9ICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0Ly8gTWFpbiBibG9jayBzZXR0aW5ncy5cblx0XHRcdFx0XHRjb25zdCBqc3ggPSBbXG5cdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0TWFpblNldHRpbmdzKCBhdHRyaWJ1dGVzLCBoYW5kbGVycywgZm9ybU9wdGlvbnMgKSxcblx0XHRcdFx0XHRdO1xuXG5cdFx0XHRcdFx0Ly8gQmxvY2sgcHJldmlldyBwaWN0dXJlLlxuXHRcdFx0XHRcdGlmICggISBhcHAuaGFzRm9ybXMoKSApIHtcblx0XHRcdFx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0RW1wdHlGb3Jtc1ByZXZpZXcoIHByb3BzICksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGNvbnN0IHNpemVPcHRpb25zID0gYXBwLmdldFNpemVPcHRpb25zKCk7XG5cblx0XHRcdFx0XHQvLyBGb3JtIHN0eWxlIHNldHRpbmdzICYgYmxvY2sgY29udGVudC5cblx0XHRcdFx0XHRpZiAoIGF0dHJpYnV0ZXMuZm9ybUlkICkge1xuXHRcdFx0XHRcdFx0anN4LnB1c2goXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRTdHlsZVNldHRpbmdzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICksXG5cdFx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRBZHZhbmNlZFNldHRpbmdzKCBwcm9wcywgaGFuZGxlcnMgKSxcblx0XHRcdFx0XHRcdFx0YXBwLmpzeFBhcnRzLmdldEJsb2NrRm9ybUNvbnRlbnQoIHByb3BzICksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRoYW5kbGVycy51cGRhdGVDb3B5UGFzdGVDb250ZW50KCk7XG5cblx0XHRcdFx0XHRcdCQoIHdpbmRvdyApLnRyaWdnZXIoICd3cGZvcm1zRm9ybVNlbGVjdG9yRWRpdCcsIFsgcHJvcHMgXSApO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8vIEJsb2NrIHByZXZpZXcgcGljdHVyZS5cblx0XHRcdFx0XHRpZiAoIGF0dHJpYnV0ZXMucHJldmlldyApIHtcblx0XHRcdFx0XHRcdGpzeC5wdXNoKFxuXHRcdFx0XHRcdFx0XHRhcHAuanN4UGFydHMuZ2V0QmxvY2tQcmV2aWV3KCksXG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHRyZXR1cm4ganN4O1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8vIEJsb2NrIHBsYWNlaG9sZGVyIChmb3JtIHNlbGVjdG9yKS5cblx0XHRcdFx0XHRqc3gucHVzaChcblx0XHRcdFx0XHRcdGFwcC5qc3hQYXJ0cy5nZXRCbG9ja1BsYWNlaG9sZGVyKCBwcm9wcy5hdHRyaWJ1dGVzLCBoYW5kbGVycywgZm9ybU9wdGlvbnMgKSxcblx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0cmV0dXJuIGpzeDtcblx0XHRcdFx0fSxcblx0XHRcdFx0c2F2ZTogKCkgPT4gbnVsbCxcblx0XHRcdH0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogSW5pdCBkZWZhdWx0IHN0eWxlIHNldHRpbmdzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0aW5pdERlZmF1bHRzKCkge1xuXHRcdFx0WyAnZm9ybUlkJywgJ2NvcHlQYXN0ZUpzb25WYWx1ZScgXS5mb3JFYWNoKCAoIGtleSApID0+IGRlbGV0ZSBkZWZhdWx0U3R5bGVTZXR0aW5nc1sga2V5IF0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ2hlY2sgaWYgc2l0ZSBoYXMgZm9ybXMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjNcblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge2Jvb2xlYW59IFdoZXRoZXIgc2l0ZSBoYXMgYXQgbGVhc3Qgb25lIGZvcm0uXG5cdFx0ICovXG5cdFx0aGFzRm9ybXMoKSB7XG5cdFx0XHRyZXR1cm4gZm9ybUxpc3QubGVuZ3RoID49IDE7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEJsb2NrIEpTWCBwYXJ0cy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHR5cGUge09iamVjdH1cblx0XHQgKi9cblx0XHRqc3hQYXJ0czoge1xuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBtYWluIHNldHRpbmdzIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBhdHRyaWJ1dGVzICBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGZvcm1PcHRpb25zIEZvcm0gc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtKU1guRWxlbWVudH0gTWFpbiBzZXR0aW5nIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRNYWluU2V0dGluZ3MoIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBmb3JtT3B0aW9ucyApIHtcblx0XHRcdFx0aWYgKCAhIGFwcC5oYXNGb3JtcygpICkge1xuXHRcdFx0XHRcdHJldHVybiBhcHAuanN4UGFydHMucHJpbnRFbXB0eUZvcm1zTm90aWNlKCBhdHRyaWJ1dGVzLmNsaWVudElkICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JDb250cm9scyBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWluc3BlY3Rvci1tYWluLXNldHRpbmdzXCI+XG5cdFx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsXCIgdGl0bGU9eyBzdHJpbmdzLmZvcm1fc2V0dGluZ3MgfT5cblx0XHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3MuZm9ybV9zZWxlY3RlZCB9XG5cdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZvcm1JZCB9XG5cdFx0XHRcdFx0XHRcdFx0b3B0aW9ucz17IGZvcm1PcHRpb25zIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5hdHRyQ2hhbmdlKCAnZm9ybUlkJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdHsgYXR0cmlidXRlcy5mb3JtSWQgPyAoXG5cdFx0XHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1hY3Rpb25zXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHQ8YSBocmVmPXsgdXJscy5mb3JtX3VybC5yZXBsYWNlKCAne0lEfScsIGF0dHJpYnV0ZXMuZm9ybUlkICkgfSByZWw9XCJub3JlZmVycmVyXCIgdGFyZ2V0PVwiX2JsYW5rXCI+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5mb3JtX2VkaXQgfVxuXHRcdFx0XHRcdFx0XHRcdFx0PC9hPlxuXHRcdFx0XHRcdFx0XHRcdFx0eyBpc1BybyAmJiAoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdDw+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Jm5ic3A7Jm5ic3A7fCZuYnNwOyZuYnNwO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdDxhIGhyZWY9eyB1cmxzLmVudHJpZXNfdXJsLnJlcGxhY2UoICd7SUR9JywgYXR0cmlidXRlcy5mb3JtSWQgKSB9IHJlbD1cIm5vcmVmZXJyZXJcIiB0YXJnZXQ9XCJfYmxhbmtcIj5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5mb3JtX2VudHJpZXMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdDwvYT5cblx0XHRcdFx0XHRcdFx0XHRcdFx0PC8+XG5cdFx0XHRcdFx0XHRcdFx0XHQpIH1cblx0XHRcdFx0XHRcdFx0XHQ8L3A+XG5cdFx0XHRcdFx0XHRcdCkgOiBudWxsIH1cblx0XHRcdFx0XHRcdFx0PFRvZ2dsZUNvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3Muc2hvd190aXRsZSB9XG5cdFx0XHRcdFx0XHRcdFx0Y2hlY2tlZD17IGF0dHJpYnV0ZXMuZGlzcGxheVRpdGxlIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5hdHRyQ2hhbmdlKCAnZGlzcGxheVRpdGxlJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDxUb2dnbGVDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLnNob3dfZGVzY3JpcHRpb24gfVxuXHRcdFx0XHRcdFx0XHRcdGNoZWNrZWQ9eyBhdHRyaWJ1dGVzLmRpc3BsYXlEZXNjIH1cblx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5hdHRyQ2hhbmdlKCAnZGlzcGxheURlc2MnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlXCI+XG5cdFx0XHRcdFx0XHRcdFx0PHN0cm9uZz57IHN0cmluZ3MucGFuZWxfbm90aWNlX2hlYWQgfTwvc3Ryb25nPlxuXHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5wYW5lbF9ub3RpY2VfdGV4dCB9XG5cdFx0XHRcdFx0XHRcdFx0PGEgaHJlZj17IHN0cmluZ3MucGFuZWxfbm90aWNlX2xpbmsgfSByZWw9XCJub3JlZmVycmVyXCIgdGFyZ2V0PVwiX2JsYW5rXCI+eyBzdHJpbmdzLnBhbmVsX25vdGljZV9saW5rX3RleHQgfTwvYT5cblx0XHRcdFx0XHRcdFx0PC9wPlxuXHRcdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdFx0PC9JbnNwZWN0b3JDb250cm9scz5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogUHJpbnQgZW1wdHkgZm9ybXMgbm90aWNlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguM1xuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBjbGllbnRJZCBCbG9jayBjbGllbnQgSUQuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7SlNYLkVsZW1lbnR9IEZpZWxkIHN0eWxlcyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0cHJpbnRFbXB0eUZvcm1zTm90aWNlKCBjbGllbnRJZCApIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8SW5zcGVjdG9yQ29udHJvbHMga2V5PVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1pbnNwZWN0b3ItbWFpbi1zZXR0aW5nc1wiPlxuXHRcdFx0XHRcdFx0PFBhbmVsQm9keSBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbFwiIHRpdGxlPXsgc3RyaW5ncy5mb3JtX3NldHRpbmdzIH0+XG5cdFx0XHRcdFx0XHRcdDxwIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZSB3cGZvcm1zLXdhcm5pbmcgd3Bmb3Jtcy1lbXB0eS1mb3JtLW5vdGljZVwiIHN0eWxlPXsgeyBkaXNwbGF5OiAnYmxvY2snIH0gfT5cblx0XHRcdFx0XHRcdFx0XHQ8c3Ryb25nPnsgX18oICdZb3UgaGF2ZW7igJl0IGNyZWF0ZWQgYSBmb3JtLCB5ZXQhJywgJ3dwZm9ybXMtbGl0ZScgKSB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHRcdFx0eyBfXyggJ1doYXQgYXJlIHlvdSB3YWl0aW5nIGZvcj8nLCAnd3Bmb3Jtcy1saXRlJyApIH1cblx0XHRcdFx0XHRcdFx0PC9wPlxuXHRcdFx0XHRcdFx0XHQ8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzc05hbWU9XCJnZXQtc3RhcnRlZC1idXR0b24gY29tcG9uZW50cy1idXR0b24gaXMtc2Vjb25kYXJ5XCJcblx0XHRcdFx0XHRcdFx0XHRvbkNsaWNrPXtcblx0XHRcdFx0XHRcdFx0XHRcdCgpID0+IHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0YXBwLm9wZW5CdWlsZGVyUG9wdXAoIGNsaWVudElkICk7XG5cdFx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHQ+XG5cdFx0XHRcdFx0XHRcdFx0eyBfXyggJ0dldCBTdGFydGVkJywgJ3dwZm9ybXMtbGl0ZScgKSB9XG5cdFx0XHRcdFx0XHRcdDwvYnV0dG9uPlxuXHRcdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdFx0PC9JbnNwZWN0b3JDb250cm9scz5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IEZpZWxkIHN0eWxlcyBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgICAgICAgQmxvY2sgcHJvcGVydGllcy5cblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBoYW5kbGVycyAgICBCbG9jayBldmVudCBoYW5kbGVycy5cblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBzaXplT3B0aW9ucyBTaXplIHNlbGVjdG9yIG9wdGlvbnMuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7T2JqZWN0fSBGaWVsZCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEZpZWxkU3R5bGVzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT17IGFwcC5nZXRQYW5lbENsYXNzKCBwcm9wcyApIH0gdGl0bGU9eyBzdHJpbmdzLmZpZWxkX3N0eWxlcyB9PlxuXHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlIHdwZm9ybXMtdXNlLW1vZGVybi1ub3RpY2VcIj5cblx0XHRcdFx0XHRcdFx0PHN0cm9uZz57IHN0cmluZ3MudXNlX21vZGVybl9ub3RpY2VfaGVhZCB9PC9zdHJvbmc+XG5cdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy51c2VfbW9kZXJuX25vdGljZV90ZXh0IH0gPGEgaHJlZj17IHN0cmluZ3MudXNlX21vZGVybl9ub3RpY2VfbGluayB9IHJlbD1cIm5vcmVmZXJyZXJcIiB0YXJnZXQ9XCJfYmxhbmtcIj57IHN0cmluZ3MubGVhcm5fbW9yZSB9PC9hPlxuXHRcdFx0XHRcdFx0PC9wPlxuXG5cdFx0XHRcdFx0XHQ8cCBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Ugd3Bmb3Jtcy13YXJuaW5nIHdwZm9ybXMtbGVhZC1mb3JtLW5vdGljZVwiIHN0eWxlPXsgeyBkaXNwbGF5OiAnbm9uZScgfSB9PlxuXHRcdFx0XHRcdFx0XHQ8c3Ryb25nPnsgc3RyaW5ncy5sZWFkX2Zvcm1zX3BhbmVsX25vdGljZV9oZWFkIH08L3N0cm9uZz5cblx0XHRcdFx0XHRcdFx0eyBzdHJpbmdzLmxlYWRfZm9ybXNfcGFuZWxfbm90aWNlX3RleHQgfVxuXHRcdFx0XHRcdFx0PC9wPlxuXG5cdFx0XHRcdFx0XHQ8RmxleCBnYXA9eyA0IH0gYWxpZ249XCJmbGV4LXN0YXJ0XCIgY2xhc3NOYW1lPXsgJ3dwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZmxleCcgfSBqdXN0aWZ5PVwic3BhY2UtYmV0d2VlblwiPlxuXHRcdFx0XHRcdFx0XHQ8RmxleEJsb2NrPlxuXHRcdFx0XHRcdFx0XHRcdDxTZWxlY3RDb250cm9sXG5cdFx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3Muc2l6ZSB9XG5cdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZT17IHByb3BzLmF0dHJpYnV0ZXMuZmllbGRTaXplIH1cblx0XHRcdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBzaXplT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZFNpemUnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0XHQ8L0ZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0PEZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdFx0XHQ8X19leHBlcmltZW50YWxVbml0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLmJvcmRlcl9yYWRpdXMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBwcm9wcy5hdHRyaWJ1dGVzLmZpZWxkQm9yZGVyUmFkaXVzIH1cblx0XHRcdFx0XHRcdFx0XHRcdGlzVW5pdFNlbGVjdFRhYmJhYmxlXG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZEJvcmRlclJhZGl1cycsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDwvRmxleEJsb2NrPlxuXHRcdFx0XHRcdFx0PC9GbGV4PlxuXG5cdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29sb3ItcGlja2VyXCI+XG5cdFx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb250cm9sLWxhYmVsXCI+eyBzdHJpbmdzLmNvbG9ycyB9PC9kaXY+XG5cdFx0XHRcdFx0XHRcdDxQYW5lbENvbG9yU2V0dGluZ3Ncblx0XHRcdFx0XHRcdFx0XHRfX2V4cGVyaW1lbnRhbElzUmVuZGVyZWRJblNpZGViYXJcblx0XHRcdFx0XHRcdFx0XHRlbmFibGVBbHBoYVxuXHRcdFx0XHRcdFx0XHRcdHNob3dUaXRsZT17IGZhbHNlIH1cblx0XHRcdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbG9yLXBhbmVsXCJcblx0XHRcdFx0XHRcdFx0XHRjb2xvclNldHRpbmdzPXsgW1xuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5maWVsZEJhY2tncm91bmRDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZEJhY2tncm91bmRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmJhY2tncm91bmQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5maWVsZEJvcmRlckNvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2ZpZWxkQm9yZGVyQ29sb3InLCB2YWx1ZSApLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5ib3JkZXIsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5maWVsZFRleHRDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdmaWVsZFRleHRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLnRleHQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdF0gfVxuXHRcdFx0XHRcdFx0XHQvPlxuXHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0PC9QYW5lbEJvZHk+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBMYWJlbCBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzICAgICAgIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge09iamVjdH0gTGFiZWwgc3R5bGVzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRMYWJlbFN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT17IGFwcC5nZXRQYW5lbENsYXNzKCBwcm9wcyApIH0gdGl0bGU9eyBzdHJpbmdzLmxhYmVsX3N0eWxlcyB9PlxuXHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLnNpemUgfVxuXHRcdFx0XHRcdFx0XHR2YWx1ZT17IHByb3BzLmF0dHJpYnV0ZXMubGFiZWxTaXplIH1cblx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1maXgtYm90dG9tLW1hcmdpblwiXG5cdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBzaXplT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2xhYmVsU2l6ZScsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0Lz5cblxuXHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbG9yLXBpY2tlclwiPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29udHJvbC1sYWJlbFwiPnsgc3RyaW5ncy5jb2xvcnMgfTwvZGl2PlxuXHRcdFx0XHRcdFx0XHQ8UGFuZWxDb2xvclNldHRpbmdzXG5cdFx0XHRcdFx0XHRcdFx0X19leHBlcmltZW50YWxJc1JlbmRlcmVkSW5TaWRlYmFyXG5cdFx0XHRcdFx0XHRcdFx0ZW5hYmxlQWxwaGFcblx0XHRcdFx0XHRcdFx0XHRzaG93VGl0bGU9eyBmYWxzZSB9XG5cdFx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1wYW5lbFwiXG5cdFx0XHRcdFx0XHRcdFx0Y29sb3JTZXR0aW5ncz17IFtcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMubGFiZWxDb2xvcixcblx0XHRcdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U6ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdsYWJlbENvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MubGFiZWwsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5sYWJlbFN1YmxhYmVsQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnbGFiZWxTdWJsYWJlbENvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3Muc3VibGFiZWxfaGludHMucmVwbGFjZSggJyZhbXA7JywgJyYnICksXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5sYWJlbEVycm9yQ29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnbGFiZWxFcnJvckNvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MuZXJyb3JfbWVzc2FnZSxcblx0XHRcdFx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHRcdFx0XSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHQ8L1BhbmVsQm9keT5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IEJ1dHRvbiBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzICAgICAgIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge09iamVjdH0gIEJ1dHRvbiBzdHlsZXMgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJ1dHRvblN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8UGFuZWxCb2R5IGNsYXNzTmFtZT17IGFwcC5nZXRQYW5lbENsYXNzKCBwcm9wcyApIH0gdGl0bGU9eyBzdHJpbmdzLmJ1dHRvbl9zdHlsZXMgfT5cblx0XHRcdFx0XHRcdDxGbGV4IGdhcD17IDQgfSBhbGlnbj1cImZsZXgtc3RhcnRcIiBjbGFzc05hbWU9eyAnd3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1mbGV4JyB9IGp1c3RpZnk9XCJzcGFjZS1iZXR3ZWVuXCI+XG5cdFx0XHRcdFx0XHRcdDxGbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdFx0PFNlbGVjdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsPXsgc3RyaW5ncy5zaXplIH1cblx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlPXsgcHJvcHMuYXR0cmlidXRlcy5idXR0b25TaXplIH1cblx0XHRcdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBzaXplT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZT17ICggdmFsdWUgKSA9PiBoYW5kbGVycy5zdHlsZUF0dHJDaGFuZ2UoICdidXR0b25TaXplJywgdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdFx0Lz5cblx0XHRcdFx0XHRcdFx0PC9GbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdDxGbGV4QmxvY2s+XG5cdFx0XHRcdFx0XHRcdFx0PF9fZXhwZXJpbWVudGFsVW5pdENvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2J1dHRvbkJvcmRlclJhZGl1cycsIHZhbHVlICkgfVxuXHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw9eyBzdHJpbmdzLmJvcmRlcl9yYWRpdXMgfVxuXHRcdFx0XHRcdFx0XHRcdFx0aXNVbml0U2VsZWN0VGFiYmFibGVcblx0XHRcdFx0XHRcdFx0XHRcdHZhbHVlPXsgcHJvcHMuYXR0cmlidXRlcy5idXR0b25Cb3JkZXJSYWRpdXMgfSAvPlxuXHRcdFx0XHRcdFx0XHQ8L0ZsZXhCbG9jaz5cblx0XHRcdFx0XHRcdDwvRmxleD5cblxuXHRcdFx0XHRcdFx0PGRpdiBjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLWNvbG9yLXBpY2tlclwiPlxuXHRcdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItY29udHJvbC1sYWJlbFwiPnsgc3RyaW5ncy5jb2xvcnMgfTwvZGl2PlxuXHRcdFx0XHRcdFx0XHQ8UGFuZWxDb2xvclNldHRpbmdzXG5cdFx0XHRcdFx0XHRcdFx0X19leHBlcmltZW50YWxJc1JlbmRlcmVkSW5TaWRlYmFyXG5cdFx0XHRcdFx0XHRcdFx0ZW5hYmxlQWxwaGFcblx0XHRcdFx0XHRcdFx0XHRzaG93VGl0bGU9eyBmYWxzZSB9XG5cdFx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1jb2xvci1wYW5lbFwiXG5cdFx0XHRcdFx0XHRcdFx0Y29sb3JTZXR0aW5ncz17IFtcblx0XHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0dmFsdWU6IHByb3BzLmF0dHJpYnV0ZXMuYnV0dG9uQmFja2dyb3VuZENvbG9yLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRvbkNoYW5nZTogKCB2YWx1ZSApID0+IGhhbmRsZXJzLnN0eWxlQXR0ckNoYW5nZSggJ2J1dHRvbkJhY2tncm91bmRDb2xvcicsIHZhbHVlICksXG5cdFx0XHRcdFx0XHRcdFx0XHRcdGxhYmVsOiBzdHJpbmdzLmJhY2tncm91bmQsXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR2YWx1ZTogcHJvcHMuYXR0cmlidXRlcy5idXR0b25UZXh0Q29sb3IsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdG9uQ2hhbmdlOiAoIHZhbHVlICkgPT4gaGFuZGxlcnMuc3R5bGVBdHRyQ2hhbmdlKCAnYnV0dG9uVGV4dENvbG9yJywgdmFsdWUgKSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MudGV4dCxcblx0XHRcdFx0XHRcdFx0XHRcdH0sXG5cdFx0XHRcdFx0XHRcdFx0XSB9IC8+XG5cdFx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1sZWdlbmQgd3Bmb3Jtcy1idXR0b24tY29sb3Itbm90aWNlXCI+XG5cdFx0XHRcdFx0XHRcdFx0eyBzdHJpbmdzLmJ1dHRvbl9jb2xvcl9ub3RpY2UgfVxuXHRcdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHRcdDwvUGFuZWxCb2R5PlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgc3R5bGUgc2V0dGluZ3MgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzICAgICAgIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gaGFuZGxlcnMgICAgQmxvY2sgZXZlbnQgaGFuZGxlcnMuXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gc2l6ZU9wdGlvbnMgU2l6ZSBzZWxlY3RvciBvcHRpb25zLlxuXHRcdFx0ICpcblx0XHRcdCAqIEByZXR1cm4ge09iamVjdH0gSW5zcGVjdG9yIGNvbnRyb2xzIEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRTdHlsZVNldHRpbmdzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkge1xuXHRcdFx0XHRyZXR1cm4gKFxuXHRcdFx0XHRcdDxJbnNwZWN0b3JDb250cm9scyBrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXN0eWxlLXNldHRpbmdzXCI+XG5cdFx0XHRcdFx0XHR7IGFwcC5qc3hQYXJ0cy5nZXRGaWVsZFN0eWxlcyggcHJvcHMsIGhhbmRsZXJzLCBzaXplT3B0aW9ucyApIH1cblx0XHRcdFx0XHRcdHsgYXBwLmpzeFBhcnRzLmdldExhYmVsU3R5bGVzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkgfVxuXHRcdFx0XHRcdFx0eyBhcHAuanN4UGFydHMuZ2V0QnV0dG9uU3R5bGVzKCBwcm9wcywgaGFuZGxlcnMsIHNpemVPcHRpb25zICkgfVxuXHRcdFx0XHRcdDwvSW5zcGVjdG9yQ29udHJvbHM+XG5cdFx0XHRcdCk7XG5cdFx0XHR9LFxuXG5cdFx0XHQvKipcblx0XHRcdCAqIEdldCBhZHZhbmNlZCBzZXR0aW5ncyBKU1ggY29kZS5cblx0XHRcdCAqXG5cdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdCAqXG5cdFx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgICAgQmxvY2sgcHJvcGVydGllcy5cblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBoYW5kbGVycyBCbG9jayBldmVudCBoYW5kbGVycy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtPYmplY3R9IEluc3BlY3RvciBhZHZhbmNlZCBjb250cm9scyBKU1ggY29kZS5cblx0XHRcdCAqL1xuXHRcdFx0Z2V0QWR2YW5jZWRTZXR0aW5ncyggcHJvcHMsIGhhbmRsZXJzICkge1xuXHRcdFx0XHQvLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgcmVhY3QtaG9va3MvcnVsZXMtb2YtaG9va3Ncblx0XHRcdFx0Y29uc3QgWyBpc09wZW4sIHNldE9wZW4gXSA9IHVzZVN0YXRlKCBmYWxzZSApO1xuXHRcdFx0XHRjb25zdCBvcGVuTW9kYWwgPSAoKSA9PiBzZXRPcGVuKCB0cnVlICk7XG5cdFx0XHRcdGNvbnN0IGNsb3NlTW9kYWwgPSAoKSA9PiBzZXRPcGVuKCBmYWxzZSApO1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEluc3BlY3RvckFkdmFuY2VkQ29udHJvbHM+XG5cdFx0XHRcdFx0XHQ8ZGl2IGNsYXNzTmFtZT17IGFwcC5nZXRQYW5lbENsYXNzKCBwcm9wcyApIH0+XG5cdFx0XHRcdFx0XHRcdDxUZXh0YXJlYUNvbnRyb2xcblx0XHRcdFx0XHRcdFx0XHRsYWJlbD17IHN0cmluZ3MuY29weV9wYXN0ZV9zZXR0aW5ncyB9XG5cdFx0XHRcdFx0XHRcdFx0cm93cz1cIjRcIlxuXHRcdFx0XHRcdFx0XHRcdHNwZWxsQ2hlY2s9XCJmYWxzZVwiXG5cdFx0XHRcdFx0XHRcdFx0dmFsdWU9eyBwcm9wcy5hdHRyaWJ1dGVzLmNvcHlQYXN0ZUpzb25WYWx1ZSB9XG5cdFx0XHRcdFx0XHRcdFx0b25DaGFuZ2U9eyAoIHZhbHVlICkgPT4gaGFuZGxlcnMucGFzdGVTZXR0aW5ncyggdmFsdWUgKSB9XG5cdFx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctZm9ybS1zZWxlY3Rvci1sZWdlbmRcIiBkYW5nZXJvdXNseVNldElubmVySFRNTD17IHsgX19odG1sOiBzdHJpbmdzLmNvcHlfcGFzdGVfbm90aWNlIH0gfT48L2Rpdj5cblxuXHRcdFx0XHRcdFx0XHQ8QnV0dG9uIGNsYXNzTmFtZT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItcmVzZXQtYnV0dG9uXCIgb25DbGljaz17IG9wZW5Nb2RhbCB9Pnsgc3RyaW5ncy5yZXNldF9zdHlsZV9zZXR0aW5ncyB9PC9CdXR0b24+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblxuXHRcdFx0XHRcdFx0eyBpc09wZW4gJiYgKFxuXHRcdFx0XHRcdFx0XHQ8TW9kYWwgY2xhc3NOYW1lPVwid3Bmb3Jtcy1ndXRlbmJlcmctbW9kYWxcIlxuXHRcdFx0XHRcdFx0XHRcdHRpdGxlPXsgc3RyaW5ncy5yZXNldF9zdHlsZV9zZXR0aW5ncyB9XG5cdFx0XHRcdFx0XHRcdFx0b25SZXF1ZXN0Q2xvc2U9eyBjbG9zZU1vZGFsIH0+XG5cblx0XHRcdFx0XHRcdFx0XHQ8cD57IHN0cmluZ3MucmVzZXRfc2V0dGluZ3NfY29uZmlybV90ZXh0IH08L3A+XG5cblx0XHRcdFx0XHRcdFx0XHQ8RmxleCBnYXA9eyAzIH0gYWxpZ249XCJjZW50ZXJcIiBqdXN0aWZ5PVwiZmxleC1lbmRcIj5cblx0XHRcdFx0XHRcdFx0XHRcdDxCdXR0b24gaXNTZWNvbmRhcnkgb25DbGljaz17IGNsb3NlTW9kYWwgfT5cblx0XHRcdFx0XHRcdFx0XHRcdFx0eyBzdHJpbmdzLmJ0bl9ubyB9XG5cdFx0XHRcdFx0XHRcdFx0XHQ8L0J1dHRvbj5cblxuXHRcdFx0XHRcdFx0XHRcdFx0PEJ1dHRvbiBpc1ByaW1hcnkgb25DbGljaz17ICgpID0+IHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0Y2xvc2VNb2RhbCgpO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRoYW5kbGVycy5yZXNldFNldHRpbmdzKCk7XG5cdFx0XHRcdFx0XHRcdFx0XHR9IH0+XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHsgc3RyaW5ncy5idG5feWVzX3Jlc2V0IH1cblx0XHRcdFx0XHRcdFx0XHRcdDwvQnV0dG9uPlxuXHRcdFx0XHRcdFx0XHRcdDwvRmxleD5cblx0XHRcdFx0XHRcdFx0PC9Nb2RhbD5cblx0XHRcdFx0XHRcdCkgfVxuXHRcdFx0XHRcdDwvSW5zcGVjdG9yQWR2YW5jZWRDb250cm9scz5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGJsb2NrIGNvbnRlbnQgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7SlNYLkVsZW1lbnR9IEJsb2NrIGNvbnRlbnQgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJsb2NrRm9ybUNvbnRlbnQoIHByb3BzICkge1xuXHRcdFx0XHRpZiAoIHRyaWdnZXJTZXJ2ZXJSZW5kZXIgKSB7XG5cdFx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHRcdDxTZXJ2ZXJTaWRlUmVuZGVyXG5cdFx0XHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itc2VydmVyLXNpZGUtcmVuZGVyZXJcIlxuXHRcdFx0XHRcdFx0XHRibG9jaz1cIndwZm9ybXMvZm9ybS1zZWxlY3RvclwiXG5cdFx0XHRcdFx0XHRcdGF0dHJpYnV0ZXM9eyBwcm9wcy5hdHRyaWJ1dGVzIH1cblx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0KTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGNvbnN0IGNsaWVudElkID0gcHJvcHMuY2xpZW50SWQ7XG5cdFx0XHRcdGNvbnN0IGJsb2NrID0gYXBwLmdldEJsb2NrQ29udGFpbmVyKCBwcm9wcyApO1xuXG5cdFx0XHRcdC8vIEluIHRoZSBjYXNlIG9mIGVtcHR5IGNvbnRlbnQsIHVzZSBzZXJ2ZXIgc2lkZSByZW5kZXJlci5cblx0XHRcdFx0Ly8gVGhpcyBoYXBwZW5zIHdoZW4gdGhlIGJsb2NrIGlzIGR1cGxpY2F0ZWQgb3IgY29udmVydGVkIHRvIGEgcmV1c2FibGUgYmxvY2suXG5cdFx0XHRcdGlmICggISBibG9jayB8fCAhIGJsb2NrLmlubmVySFRNTCApIHtcblx0XHRcdFx0XHR0cmlnZ2VyU2VydmVyUmVuZGVyID0gdHJ1ZTtcblxuXHRcdFx0XHRcdHJldHVybiBhcHAuanN4UGFydHMuZ2V0QmxvY2tGb3JtQ29udGVudCggcHJvcHMgKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGJsb2Nrc1sgY2xpZW50SWQgXSA9IGJsb2Nrc1sgY2xpZW50SWQgXSB8fCB7fTtcblx0XHRcdFx0YmxvY2tzWyBjbGllbnRJZCBdLmJsb2NrSFRNTCA9IGJsb2NrLmlubmVySFRNTDtcblx0XHRcdFx0YmxvY2tzWyBjbGllbnRJZCBdLmxvYWRlZEZvcm1JZCA9IHByb3BzLmF0dHJpYnV0ZXMuZm9ybUlkO1xuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0PEZyYWdtZW50IGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZnJhZ21lbnQtZm9ybS1odG1sXCI+XG5cdFx0XHRcdFx0XHQ8ZGl2IGRhbmdlcm91c2x5U2V0SW5uZXJIVE1MPXsgeyBfX2h0bWw6IGJsb2Nrc1sgY2xpZW50SWQgXS5ibG9ja0hUTUwgfSB9IC8+XG5cdFx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGJsb2NrIHByZXZpZXcgSlNYIGNvZGUuXG5cdFx0XHQgKlxuXHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHQgKlxuXHRcdFx0ICogQHJldHVybiB7SlNYLkVsZW1lbnR9IEJsb2NrIHByZXZpZXcgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJsb2NrUHJldmlldygpIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8RnJhZ21lbnRcblx0XHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZnJhZ21lbnQtYmxvY2stcHJldmlld1wiPlxuXHRcdFx0XHRcdFx0PGltZyBzcmM9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmJsb2NrX3ByZXZpZXdfdXJsIH0gc3R5bGU9eyB7IHdpZHRoOiAnMTAwJScgfSB9IGFsdD1cIlwiIC8+XG5cdFx0XHRcdFx0PC9GcmFnbWVudD5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cblx0XHRcdC8qKlxuXHRcdFx0ICogR2V0IGJsb2NrIGVtcHR5IEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguM1xuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBwcm9wcyBCbG9jayBwcm9wZXJ0aWVzLlxuXHRcdFx0ICogQHJldHVybiB7SlNYLkVsZW1lbnR9IEJsb2NrIGVtcHR5IEpTWCBjb2RlLlxuXHRcdFx0ICovXG5cdFx0XHRnZXRFbXB0eUZvcm1zUHJldmlldyggcHJvcHMgKSB7XG5cdFx0XHRcdGNvbnN0IGNsaWVudElkID0gcHJvcHMuY2xpZW50SWQ7XG5cblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8RnJhZ21lbnRcblx0XHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3ItZnJhZ21lbnQtYmxvY2stZW1wdHlcIj5cblx0XHRcdFx0XHRcdDxkaXYgY2xhc3NOYW1lPVwid3Bmb3Jtcy1uby1mb3JtLXByZXZpZXdcIj5cblx0XHRcdFx0XHRcdFx0PGltZyBzcmM9eyB3cGZvcm1zX2d1dGVuYmVyZ19mb3JtX3NlbGVjdG9yLmJsb2NrX2VtcHR5X3VybCB9IGFsdD1cIlwiIC8+XG5cdFx0XHRcdFx0XHRcdDxwPlxuXHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdGNyZWF0ZUludGVycG9sYXRlRWxlbWVudChcblx0XHRcdFx0XHRcdFx0XHRcdFx0X18oXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J1lvdSBjYW4gdXNlIDxiPldQRm9ybXM8L2I+IHRvIGJ1aWxkIGNvbnRhY3QgZm9ybXMsIHN1cnZleXMsIHBheW1lbnQgZm9ybXMsIGFuZCBtb3JlIHdpdGgganVzdCBhIGZldyBjbGlja3MuJyxcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnd3Bmb3Jtcy1saXRlJ1xuXHRcdFx0XHRcdFx0XHRcdFx0XHQpLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0YjogPHN0cm9uZyAvPixcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdFx0KVxuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0PC9wPlxuXHRcdFx0XHRcdFx0XHQ8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzc05hbWU9XCJnZXQtc3RhcnRlZC1idXR0b24gY29tcG9uZW50cy1idXR0b24gaXMtcHJpbWFyeVwiXG5cdFx0XHRcdFx0XHRcdFx0b25DbGljaz17XG5cdFx0XHRcdFx0XHRcdFx0XHQoKSA9PiB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGFwcC5vcGVuQnVpbGRlclBvcHVwKCBjbGllbnRJZCApO1xuXHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0PlxuXHRcdFx0XHRcdFx0XHRcdHsgX18oICdHZXQgU3RhcnRlZCcsICd3cGZvcm1zLWxpdGUnICkgfVxuXHRcdFx0XHRcdFx0XHQ8L2J1dHRvbj5cblx0XHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwiZW1wdHktZGVzY1wiPlxuXHRcdFx0XHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdFx0XHRcdGNyZWF0ZUludGVycG9sYXRlRWxlbWVudChcblx0XHRcdFx0XHRcdFx0XHRcdFx0X18oXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J05lZWQgc29tZSBoZWxwPyBDaGVjayBvdXQgb3VyIDxhPmNvbXByZWhlbnNpdmUgZ3VpZGUuPC9hPicsXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3dwZm9ybXMtbGl0ZSdcblx0XHRcdFx0XHRcdFx0XHRcdFx0KSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0e1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBqc3gtYTExeS9hbmNob3ItaGFzLWNvbnRlbnRcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRhOiA8YSBocmVmPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci53cGZvcm1zX2d1aWRlIH0gdGFyZ2V0PVwiX2JsYW5rXCIgcmVsPVwibm9vcGVuZXIgbm9yZWZlcnJlclwiIC8+LFxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFx0XHQpXG5cdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHQ8L3A+XG5cblx0XHRcdFx0XHRcdFx0eyAvKiBUZW1wbGF0ZSBmb3IgcG9wdXAgd2l0aCBidWlsZGVyIGlmcmFtZSAqLyB9XG5cdFx0XHRcdFx0XHRcdDxkaXYgaWQ9XCJ3cGZvcm1zLWd1dGVuYmVyZy1wb3B1cFwiIGNsYXNzTmFtZT1cIndwZm9ybXMtYnVpbGRlci1wb3B1cFwiPlxuXHRcdFx0XHRcdFx0XHRcdDxpZnJhbWUgc3JjPVwiYWJvdXQ6YmxhbmtcIiB3aWR0aD1cIjEwMCVcIiBoZWlnaHQ9XCIxMDAlXCIgaWQ9XCJ3cGZvcm1zLWJ1aWxkZXItaWZyYW1lXCIgdGl0bGU9XCJXUEZvcm1zIEJ1aWxkZXIgUG9wdXBcIj48L2lmcmFtZT5cblx0XHRcdFx0XHRcdFx0PC9kaXY+XG5cdFx0XHRcdFx0XHQ8L2Rpdj5cblx0XHRcdFx0XHQ8L0ZyYWdtZW50PlxuXHRcdFx0XHQpO1xuXHRcdFx0fSxcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBHZXQgYmxvY2sgcGxhY2Vob2xkZXIgKGZvcm0gc2VsZWN0b3IpIEpTWCBjb2RlLlxuXHRcdFx0ICpcblx0XHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdFx0ICpcblx0XHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBhdHRyaWJ1dGVzICBCbG9jayBhdHRyaWJ1dGVzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGhhbmRsZXJzICAgIEJsb2NrIGV2ZW50IGhhbmRsZXJzLlxuXHRcdFx0ICogQHBhcmFtIHtPYmplY3R9IGZvcm1PcHRpb25zIEZvcm0gc2VsZWN0b3Igb3B0aW9ucy5cblx0XHRcdCAqXG5cdFx0XHQgKiBAcmV0dXJuIHtKU1guRWxlbWVudH0gQmxvY2sgcGxhY2Vob2xkZXIgSlNYIGNvZGUuXG5cdFx0XHQgKi9cblx0XHRcdGdldEJsb2NrUGxhY2Vob2xkZXIoIGF0dHJpYnV0ZXMsIGhhbmRsZXJzLCBmb3JtT3B0aW9ucyApIHtcblx0XHRcdFx0cmV0dXJuIChcblx0XHRcdFx0XHQ8UGxhY2Vob2xkZXJcblx0XHRcdFx0XHRcdGtleT1cIndwZm9ybXMtZ3V0ZW5iZXJnLWZvcm0tc2VsZWN0b3Itd3JhcFwiXG5cdFx0XHRcdFx0XHRjbGFzc05hbWU9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXdyYXBcIj5cblx0XHRcdFx0XHRcdDxpbWcgc3JjPXsgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5sb2dvX3VybCB9IGFsdD1cIlwiIC8+XG5cdFx0XHRcdFx0XHQ8U2VsZWN0Q29udHJvbFxuXHRcdFx0XHRcdFx0XHRrZXk9XCJ3cGZvcm1zLWd1dGVuYmVyZy1mb3JtLXNlbGVjdG9yLXNlbGVjdC1jb250cm9sXCJcblx0XHRcdFx0XHRcdFx0dmFsdWU9eyBhdHRyaWJ1dGVzLmZvcm1JZCB9XG5cdFx0XHRcdFx0XHRcdG9wdGlvbnM9eyBmb3JtT3B0aW9ucyB9XG5cdFx0XHRcdFx0XHRcdG9uQ2hhbmdlPXsgKCB2YWx1ZSApID0+IGhhbmRsZXJzLmF0dHJDaGFuZ2UoICdmb3JtSWQnLCB2YWx1ZSApIH1cblx0XHRcdFx0XHRcdC8+XG5cdFx0XHRcdFx0PC9QbGFjZWhvbGRlcj5cblx0XHRcdFx0KTtcblx0XHRcdH0sXG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBTdHlsZSBTZXR0aW5ncyBwYW5lbCBjbGFzcy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtzdHJpbmd9IFN0eWxlIFNldHRpbmdzIHBhbmVsIGNsYXNzLlxuXHRcdCAqL1xuXHRcdGdldFBhbmVsQ2xhc3MoIHByb3BzICkge1xuXHRcdFx0bGV0IGNzc0NsYXNzID0gJ3dwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsIHdwZm9ybXMtYmxvY2stc2V0dGluZ3MtJyArIHByb3BzLmNsaWVudElkO1xuXG5cdFx0XHRpZiAoICEgYXBwLmlzRnVsbFN0eWxpbmdFbmFibGVkKCkgKSB7XG5cdFx0XHRcdGNzc0NsYXNzICs9ICcgZGlzYWJsZWRfcGFuZWwnO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXR1cm4gY3NzQ2xhc3M7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIERldGVybWluZSB3aGV0aGVyIHRoZSBmdWxsIHN0eWxpbmcgaXMgZW5hYmxlZC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7Ym9vbGVhbn0gV2hldGhlciB0aGUgZnVsbCBzdHlsaW5nIGlzIGVuYWJsZWQuXG5cdFx0ICovXG5cdFx0aXNGdWxsU3R5bGluZ0VuYWJsZWQoKSB7XG5cdFx0XHRyZXR1cm4gd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5pc19tb2Rlcm5fbWFya3VwICYmIHdwZm9ybXNfZ3V0ZW5iZXJnX2Zvcm1fc2VsZWN0b3IuaXNfZnVsbF9zdHlsaW5nO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgYmxvY2sgY29udGFpbmVyIERPTSBlbGVtZW50LlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdH0gcHJvcHMgQmxvY2sgcHJvcGVydGllcy5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge0VsZW1lbnR9IEJsb2NrIGNvbnRhaW5lci5cblx0XHQgKi9cblx0XHRnZXRCbG9ja0NvbnRhaW5lciggcHJvcHMgKSB7XG5cdFx0XHRjb25zdCBibG9ja1NlbGVjdG9yID0gYCNibG9jay0keyBwcm9wcy5jbGllbnRJZCB9ID4gZGl2YDtcblx0XHRcdGxldCBibG9jayA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoIGJsb2NrU2VsZWN0b3IgKTtcblxuXHRcdFx0Ly8gRm9yIEZTRSAvIEd1dGVuYmVyZyBwbHVnaW4gd2UgbmVlZCB0byB0YWtlIGEgbG9vayBpbnNpZGUgdGhlIGlmcmFtZS5cblx0XHRcdGlmICggISBibG9jayApIHtcblx0XHRcdFx0Y29uc3QgZWRpdG9yQ2FudmFzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ2lmcmFtZVtuYW1lPVwiZWRpdG9yLWNhbnZhc1wiXScgKTtcblxuXHRcdFx0XHRibG9jayA9IGVkaXRvckNhbnZhcyAmJiBlZGl0b3JDYW52YXMuY29udGVudFdpbmRvdy5kb2N1bWVudC5xdWVyeVNlbGVjdG9yKCBibG9ja1NlbGVjdG9yICk7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBibG9jaztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IHNldHRpbmdzIGZpZWxkcyBldmVudCBoYW5kbGVycy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtPYmplY3R9IE9iamVjdCB0aGF0IGNvbnRhaW5zIGV2ZW50IGhhbmRsZXJzIGZvciB0aGUgc2V0dGluZ3MgZmllbGRzLlxuXHRcdCAqL1xuXHRcdGdldFNldHRpbmdzRmllbGRzSGFuZGxlcnMoIHByb3BzICkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRcdHJldHVybiB7XG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIEZpZWxkIHN0eWxlIGF0dHJpYnV0ZSBjaGFuZ2UgZXZlbnQgaGFuZGxlci5cblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBhdHRyaWJ1dGUgQXR0cmlidXRlIG5hbWUuXG5cdFx0XHRcdCAqIEBwYXJhbSB7c3RyaW5nfSB2YWx1ZSAgICAgTmV3IGF0dHJpYnV0ZSB2YWx1ZS5cblx0XHRcdFx0ICovXG5cdFx0XHRcdHN0eWxlQXR0ckNoYW5nZSggYXR0cmlidXRlLCB2YWx1ZSApIHtcblx0XHRcdFx0XHRjb25zdCBibG9jayA9IGFwcC5nZXRCbG9ja0NvbnRhaW5lciggcHJvcHMgKSxcblx0XHRcdFx0XHRcdGNvbnRhaW5lciA9IGJsb2NrLnF1ZXJ5U2VsZWN0b3IoIGAjd3Bmb3Jtcy0keyBwcm9wcy5hdHRyaWJ1dGVzLmZvcm1JZCB9YCApLFxuXHRcdFx0XHRcdFx0cHJvcGVydHkgPSBhdHRyaWJ1dGUucmVwbGFjZSggL1tBLVpdL2csICggbGV0dGVyICkgPT4gYC0keyBsZXR0ZXIudG9Mb3dlckNhc2UoKSB9YCApLFxuXHRcdFx0XHRcdFx0c2V0QXR0ciA9IHt9O1xuXG5cdFx0XHRcdFx0aWYgKCBjb250YWluZXIgKSB7XG5cdFx0XHRcdFx0XHRzd2l0Y2ggKCBwcm9wZXJ0eSApIHtcblx0XHRcdFx0XHRcdFx0Y2FzZSAnZmllbGQtc2l6ZSc6XG5cdFx0XHRcdFx0XHRcdGNhc2UgJ2xhYmVsLXNpemUnOlxuXHRcdFx0XHRcdFx0XHRjYXNlICdidXR0b24tc2l6ZSc6XG5cdFx0XHRcdFx0XHRcdFx0Zm9yICggY29uc3Qga2V5IGluIHNpemVzWyBwcm9wZXJ0eSBdWyB2YWx1ZSBdICkge1xuXHRcdFx0XHRcdFx0XHRcdFx0Y29udGFpbmVyLnN0eWxlLnNldFByb3BlcnR5KFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRgLS13cGZvcm1zLSR7IHByb3BlcnR5IH0tJHsga2V5IH1gLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRzaXplc1sgcHJvcGVydHkgXVsgdmFsdWUgXVsga2V5IF0sXG5cdFx0XHRcdFx0XHRcdFx0XHQpO1xuXHRcdFx0XHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdFx0XHRcdGJyZWFrO1xuXG5cdFx0XHRcdFx0XHRcdGRlZmF1bHQ6XG5cdFx0XHRcdFx0XHRcdFx0Y29udGFpbmVyLnN0eWxlLnNldFByb3BlcnR5KCBgLS13cGZvcm1zLSR7IHByb3BlcnR5IH1gLCB2YWx1ZSApO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHNldEF0dHJbIGF0dHJpYnV0ZSBdID0gdmFsdWU7XG5cblx0XHRcdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKCBzZXRBdHRyICk7XG5cblx0XHRcdFx0XHR0cmlnZ2VyU2VydmVyUmVuZGVyID0gZmFsc2U7XG5cblx0XHRcdFx0XHR0aGlzLnVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQoKTtcblxuXHRcdFx0XHRcdCQoIHdpbmRvdyApLnRyaWdnZXIoICd3cGZvcm1zRm9ybVNlbGVjdG9yU3R5bGVBdHRyQ2hhbmdlJywgWyBibG9jaywgcHJvcHMsIGF0dHJpYnV0ZSwgdmFsdWUgXSApO1xuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdC8qKlxuXHRcdFx0XHQgKiBGaWVsZCByZWd1bGFyIGF0dHJpYnV0ZSBjaGFuZ2UgZXZlbnQgaGFuZGxlci5cblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0XHRcdCAqXG5cdFx0XHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBhdHRyaWJ1dGUgQXR0cmlidXRlIG5hbWUuXG5cdFx0XHRcdCAqIEBwYXJhbSB7c3RyaW5nfSB2YWx1ZSAgICAgTmV3IGF0dHJpYnV0ZSB2YWx1ZS5cblx0XHRcdFx0ICovXG5cdFx0XHRcdGF0dHJDaGFuZ2UoIGF0dHJpYnV0ZSwgdmFsdWUgKSB7XG5cdFx0XHRcdFx0Y29uc3Qgc2V0QXR0ciA9IHt9O1xuXG5cdFx0XHRcdFx0c2V0QXR0clsgYXR0cmlidXRlIF0gPSB2YWx1ZTtcblxuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoIHNldEF0dHIgKTtcblxuXHRcdFx0XHRcdHRyaWdnZXJTZXJ2ZXJSZW5kZXIgPSB0cnVlO1xuXG5cdFx0XHRcdFx0dGhpcy51cGRhdGVDb3B5UGFzdGVDb250ZW50KCk7XG5cdFx0XHRcdH0sXG5cblx0XHRcdFx0LyoqXG5cdFx0XHRcdCAqIFJlc2V0IEZvcm0gU3R5bGVzIHNldHRpbmdzIHRvIGRlZmF1bHRzLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICovXG5cdFx0XHRcdHJlc2V0U2V0dGluZ3MoKSB7XG5cdFx0XHRcdFx0Zm9yICggY29uc3Qga2V5IGluIGRlZmF1bHRTdHlsZVNldHRpbmdzICkge1xuXHRcdFx0XHRcdFx0dGhpcy5zdHlsZUF0dHJDaGFuZ2UoIGtleSwgZGVmYXVsdFN0eWxlU2V0dGluZ3NbIGtleSBdICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdC8qKlxuXHRcdFx0XHQgKiBVcGRhdGUgY29udGVudCBvZiB0aGUgXCJDb3B5L1Bhc3RlXCIgZmllbGRzLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICovXG5cdFx0XHRcdHVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQoKSB7XG5cdFx0XHRcdFx0Y29uc3QgY29udGVudCA9IHt9O1xuXHRcdFx0XHRcdGNvbnN0IGF0dHMgPSB3cC5kYXRhLnNlbGVjdCggJ2NvcmUvYmxvY2stZWRpdG9yJyApLmdldEJsb2NrQXR0cmlidXRlcyggcHJvcHMuY2xpZW50SWQgKTtcblxuXHRcdFx0XHRcdGZvciAoIGNvbnN0IGtleSBpbiBkZWZhdWx0U3R5bGVTZXR0aW5ncyApIHtcblx0XHRcdFx0XHRcdGNvbnRlbnRbIGtleSBdID0gYXR0c1sga2V5IF07XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyggeyBjb3B5UGFzdGVKc29uVmFsdWU6IEpTT04uc3RyaW5naWZ5KCBjb250ZW50ICkgfSApO1xuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdC8qKlxuXHRcdFx0XHQgKiBQYXN0ZSBzZXR0aW5ncyBoYW5kbGVyLlxuXHRcdFx0XHQgKlxuXHRcdFx0XHQgKiBAc2luY2UgMS44LjFcblx0XHRcdFx0ICpcblx0XHRcdFx0ICogQHBhcmFtIHtzdHJpbmd9IHZhbHVlIE5ldyBhdHRyaWJ1dGUgdmFsdWUuXG5cdFx0XHRcdCAqL1xuXHRcdFx0XHRwYXN0ZVNldHRpbmdzKCB2YWx1ZSApIHtcblx0XHRcdFx0XHRjb25zdCBwYXN0ZUF0dHJpYnV0ZXMgPSBhcHAucGFyc2VWYWxpZGF0ZUpzb24oIHZhbHVlICk7XG5cblx0XHRcdFx0XHRpZiAoICEgcGFzdGVBdHRyaWJ1dGVzICkge1xuXHRcdFx0XHRcdFx0d3AuZGF0YS5kaXNwYXRjaCggJ2NvcmUvbm90aWNlcycgKS5jcmVhdGVFcnJvck5vdGljZShcblx0XHRcdFx0XHRcdFx0c3RyaW5ncy5jb3B5X3Bhc3RlX2Vycm9yLFxuXHRcdFx0XHRcdFx0XHR7IGlkOiAnd3Bmb3Jtcy1qc29uLXBhcnNlLWVycm9yJyB9XG5cdFx0XHRcdFx0XHQpO1xuXG5cdFx0XHRcdFx0XHR0aGlzLnVwZGF0ZUNvcHlQYXN0ZUNvbnRlbnQoKTtcblxuXHRcdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHBhc3RlQXR0cmlidXRlcy5jb3B5UGFzdGVKc29uVmFsdWUgPSB2YWx1ZTtcblxuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoIHBhc3RlQXR0cmlidXRlcyApO1xuXG5cdFx0XHRcdFx0dHJpZ2dlclNlcnZlclJlbmRlciA9IHRydWU7XG5cdFx0XHRcdH0sXG5cdFx0XHR9O1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBQYXJzZSBhbmQgdmFsaWRhdGUgSlNPTiBzdHJpbmcuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSB2YWx1ZSBKU09OIHN0cmluZy5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge2Jvb2xlYW58b2JqZWN0fSBQYXJzZWQgSlNPTiBvYmplY3QgT1IgZmFsc2Ugb24gZXJyb3IuXG5cdFx0ICovXG5cdFx0cGFyc2VWYWxpZGF0ZUpzb24oIHZhbHVlICkge1xuXHRcdFx0aWYgKCB0eXBlb2YgdmFsdWUgIT09ICdzdHJpbmcnICkge1xuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9XG5cblx0XHRcdGxldCBhdHRzO1xuXG5cdFx0XHR0cnkge1xuXHRcdFx0XHRhdHRzID0gSlNPTi5wYXJzZSggdmFsdWUgKTtcblx0XHRcdH0gY2F0Y2ggKCBlcnJvciApIHtcblx0XHRcdFx0YXR0cyA9IGZhbHNlO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXR1cm4gYXR0cztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IFdQRm9ybXMgaWNvbiBET00gZWxlbWVudC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7RE9NLmVsZW1lbnR9IFdQRm9ybXMgaWNvbiBET00gZWxlbWVudC5cblx0XHQgKi9cblx0XHRnZXRJY29uKCkge1xuXHRcdFx0cmV0dXJuIGNyZWF0ZUVsZW1lbnQoXG5cdFx0XHRcdCdzdmcnLFxuXHRcdFx0XHR7IHdpZHRoOiAyMCwgaGVpZ2h0OiAyMCwgdmlld0JveDogJzAgMCA2MTIgNjEyJywgY2xhc3NOYW1lOiAnZGFzaGljb24nIH0sXG5cdFx0XHRcdGNyZWF0ZUVsZW1lbnQoXG5cdFx0XHRcdFx0J3BhdGgnLFxuXHRcdFx0XHRcdHtcblx0XHRcdFx0XHRcdGZpbGw6ICdjdXJyZW50Q29sb3InLFxuXHRcdFx0XHRcdFx0ZDogJ001NDQsMEg2OEMzMC40NDUsMCwwLDMwLjQ0NSwwLDY4djQ3NmMwLDM3LjU1NiwzMC40NDUsNjgsNjgsNjhoNDc2YzM3LjU1NiwwLDY4LTMwLjQ0NCw2OC02OFY2OCBDNjEyLDMwLjQ0NSw1ODEuNTU2LDAsNTQ0LDB6IE00NjQuNDQsNjhMMzg3LjYsMTIwLjAyTDMyMy4zNCw2OEg0NjQuNDR6IE0yODguNjYsNjhsLTY0LjI2LDUyLjAyTDE0Ny41Niw2OEgyODguNjZ6IE01NDQsNTQ0SDY4IFY2OGgyMi4xbDEzNiw5Mi4xNGw3OS45LTY0LjZsNzkuNTYsNjQuNmwxMzYtOTIuMTRINTQ0VjU0NHogTTExNC4yNCwyNjMuMTZoOTUuODh2LTQ4LjI4aC05NS44OFYyNjMuMTZ6IE0xMTQuMjQsMzYwLjRoOTUuODggdi00OC42MmgtOTUuODhWMzYwLjR6IE0yNDIuNzYsMzYwLjRoMjU1di00OC42MmgtMjU1VjM2MC40TDI0Mi43NiwzNjAuNHogTTI0Mi43NiwyNjMuMTZoMjU1di00OC4yOGgtMjU1VjI2My4xNkwyNDIuNzYsMjYzLjE2eiBNMzY4LjIyLDQ1Ny4zaDEyOS41NFY0MDhIMzY4LjIyVjQ1Ny4zeicsXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0KSxcblx0XHRcdCk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBibG9jayBhdHRyaWJ1dGVzLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtPYmplY3R9IEJsb2NrIGF0dHJpYnV0ZXMuXG5cdFx0ICovXG5cdFx0Z2V0QmxvY2tBdHRyaWJ1dGVzKCkgeyAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG1heC1saW5lcy1wZXItZnVuY3Rpb25cblx0XHRcdHJldHVybiB7XG5cdFx0XHRcdGNsaWVudElkOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogJycsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZvcm1JZDoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZvcm1JZCxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZGlzcGxheVRpdGxlOiB7XG5cdFx0XHRcdFx0dHlwZTogJ2Jvb2xlYW4nLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmRpc3BsYXlUaXRsZSxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZGlzcGxheURlc2M6IHtcblx0XHRcdFx0XHR0eXBlOiAnYm9vbGVhbicsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZGlzcGxheURlc2MsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdHByZXZpZXc6IHtcblx0XHRcdFx0XHR0eXBlOiAnYm9vbGVhbicsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZpZWxkU2l6ZToge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZpZWxkU2l6ZSxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZmllbGRCb3JkZXJSYWRpdXM6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5maWVsZEJvcmRlclJhZGl1cyxcblx0XHRcdFx0fSxcblx0XHRcdFx0ZmllbGRCYWNrZ3JvdW5kQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5maWVsZEJhY2tncm91bmRDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0ZmllbGRCb3JkZXJDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmZpZWxkQm9yZGVyQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGZpZWxkVGV4dENvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuZmllbGRUZXh0Q29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGxhYmVsU2l6ZToge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmxhYmVsU2l6ZSxcblx0XHRcdFx0fSxcblx0XHRcdFx0bGFiZWxDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmxhYmVsQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGxhYmVsU3VibGFiZWxDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmxhYmVsU3VibGFiZWxDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0bGFiZWxFcnJvckNvbG9yOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMubGFiZWxFcnJvckNvbG9yLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRidXR0b25TaXplOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuYnV0dG9uU2l6ZSxcblx0XHRcdFx0fSxcblx0XHRcdFx0YnV0dG9uQm9yZGVyUmFkaXVzOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuYnV0dG9uQm9yZGVyUmFkaXVzLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHRidXR0b25CYWNrZ3JvdW5kQ29sb3I6IHtcblx0XHRcdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdFx0XHRkZWZhdWx0OiBkZWZhdWx0cy5idXR0b25CYWNrZ3JvdW5kQ29sb3IsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJ1dHRvblRleHRDb2xvcjoge1xuXHRcdFx0XHRcdHR5cGU6ICdzdHJpbmcnLFxuXHRcdFx0XHRcdGRlZmF1bHQ6IGRlZmF1bHRzLmJ1dHRvblRleHRDb2xvcixcblx0XHRcdFx0fSxcblx0XHRcdFx0Y29weVBhc3RlSnNvblZhbHVlOiB7XG5cdFx0XHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0XHRcdFx0ZGVmYXVsdDogZGVmYXVsdHMuY29weVBhc3RlSnNvblZhbHVlLFxuXHRcdFx0XHR9LFxuXHRcdFx0fTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogR2V0IGZvcm0gc2VsZWN0b3Igb3B0aW9ucy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7QXJyYXl9IEZvcm0gb3B0aW9ucy5cblx0XHQgKi9cblx0XHRnZXRGb3JtT3B0aW9ucygpIHtcblx0XHRcdGNvbnN0IGZvcm1PcHRpb25zID0gZm9ybUxpc3QubWFwKCAoIHZhbHVlICkgPT4gKFxuXHRcdFx0XHR7IHZhbHVlOiB2YWx1ZS5JRCwgbGFiZWw6IHZhbHVlLnBvc3RfdGl0bGUgfVxuXHRcdFx0KSApO1xuXG5cdFx0XHRmb3JtT3B0aW9ucy51bnNoaWZ0KCB7IHZhbHVlOiAnJywgbGFiZWw6IHN0cmluZ3MuZm9ybV9zZWxlY3QgfSApO1xuXG5cdFx0XHRyZXR1cm4gZm9ybU9wdGlvbnM7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEdldCBzaXplIHNlbGVjdG9yIG9wdGlvbnMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge0FycmF5fSBTaXplIG9wdGlvbnMuXG5cdFx0ICovXG5cdFx0Z2V0U2l6ZU9wdGlvbnMoKSB7XG5cdFx0XHRyZXR1cm4gW1xuXHRcdFx0XHR7XG5cdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3Muc21hbGwsXG5cdFx0XHRcdFx0dmFsdWU6ICdzbWFsbCcsXG5cdFx0XHRcdH0sXG5cdFx0XHRcdHtcblx0XHRcdFx0XHRsYWJlbDogc3RyaW5ncy5tZWRpdW0sXG5cdFx0XHRcdFx0dmFsdWU6ICdtZWRpdW0nLFxuXHRcdFx0XHR9LFxuXHRcdFx0XHR7XG5cdFx0XHRcdFx0bGFiZWw6IHN0cmluZ3MubGFyZ2UsXG5cdFx0XHRcdFx0dmFsdWU6ICdsYXJnZScsXG5cdFx0XHRcdH0sXG5cdFx0XHRdO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBFdmVudCBgd3Bmb3Jtc0Zvcm1TZWxlY3RvckVkaXRgIGhhbmRsZXIuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBlICAgICBFdmVudCBvYmplY3QuXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IHByb3BzIEJsb2NrIHByb3BlcnRpZXMuXG5cdFx0ICovXG5cdFx0YmxvY2tFZGl0KCBlLCBwcm9wcyApIHtcblx0XHRcdGNvbnN0IGJsb2NrID0gYXBwLmdldEJsb2NrQ29udGFpbmVyKCBwcm9wcyApO1xuXG5cdFx0XHRpZiAoICEgYmxvY2sgfHwgISBibG9jay5kYXRhc2V0ICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGFwcC5pbml0TGVhZEZvcm1TZXR0aW5ncyggYmxvY2sucGFyZW50RWxlbWVudCApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBJbml0IExlYWQgRm9ybSBTZXR0aW5ncyBwYW5lbHMuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7RWxlbWVudH0gYmxvY2sgQmxvY2sgZWxlbWVudC5cblx0XHQgKi9cblx0XHRpbml0TGVhZEZvcm1TZXR0aW5ncyggYmxvY2sgKSB7XG5cdFx0XHRpZiAoICEgYmxvY2sgfHwgISBibG9jay5kYXRhc2V0ICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGlmICggISBhcHAuaXNGdWxsU3R5bGluZ0VuYWJsZWQoKSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCBjbGllbnRJZCA9IGJsb2NrLmRhdGFzZXQuYmxvY2s7XG5cdFx0XHRjb25zdCAkZm9ybSA9ICQoIGJsb2NrLnF1ZXJ5U2VsZWN0b3IoICcud3Bmb3Jtcy1jb250YWluZXInICkgKTtcblx0XHRcdGNvbnN0ICRwYW5lbCA9ICQoIGAud3Bmb3Jtcy1ibG9jay1zZXR0aW5ncy0keyBjbGllbnRJZCB9YCApO1xuXG5cdFx0XHRpZiAoICRmb3JtLmhhc0NsYXNzKCAnd3Bmb3Jtcy1sZWFkLWZvcm1zLWNvbnRhaW5lcicgKSApIHtcblx0XHRcdFx0JHBhbmVsXG5cdFx0XHRcdFx0LmFkZENsYXNzKCAnZGlzYWJsZWRfcGFuZWwnIClcblx0XHRcdFx0XHQuZmluZCggJy53cGZvcm1zLWd1dGVuYmVyZy1wYW5lbC1ub3RpY2Uud3Bmb3Jtcy1sZWFkLWZvcm0tbm90aWNlJyApXG5cdFx0XHRcdFx0LmNzcyggJ2Rpc3BsYXknLCAnYmxvY2snICk7XG5cblx0XHRcdFx0JHBhbmVsXG5cdFx0XHRcdFx0LmZpbmQoICcud3Bmb3Jtcy1ndXRlbmJlcmctcGFuZWwtbm90aWNlLndwZm9ybXMtdXNlLW1vZGVybi1ub3RpY2UnIClcblx0XHRcdFx0XHQuY3NzKCAnZGlzcGxheScsICdub25lJyApO1xuXG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0JHBhbmVsXG5cdFx0XHRcdC5yZW1vdmVDbGFzcyggJ2Rpc2FibGVkX3BhbmVsJyApXG5cdFx0XHRcdC5maW5kKCAnLndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZS53cGZvcm1zLWxlYWQtZm9ybS1ub3RpY2UnIClcblx0XHRcdFx0LmNzcyggJ2Rpc3BsYXknLCAnbm9uZScgKTtcblxuXHRcdFx0JHBhbmVsXG5cdFx0XHRcdC5maW5kKCAnLndwZm9ybXMtZ3V0ZW5iZXJnLXBhbmVsLW5vdGljZS53cGZvcm1zLXVzZS1tb2Rlcm4tbm90aWNlJyApXG5cdFx0XHRcdC5jc3MoICdkaXNwbGF5JywgbnVsbCApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBFdmVudCBgd3Bmb3Jtc0Zvcm1TZWxlY3RvckZvcm1Mb2FkZWRgIGhhbmRsZXIuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBlIEV2ZW50IG9iamVjdC5cblx0XHQgKi9cblx0XHRmb3JtTG9hZGVkKCBlICkge1xuXHRcdFx0YXBwLmluaXRMZWFkRm9ybVNldHRpbmdzKCBlLmRldGFpbC5ibG9jayApO1xuXHRcdFx0YXBwLnVwZGF0ZUFjY2VudENvbG9ycyggZS5kZXRhaWwgKTtcblx0XHRcdGFwcC5sb2FkQ2hvaWNlc0pTKCBlLmRldGFpbCApO1xuXHRcdFx0YXBwLmluaXRSaWNoVGV4dEZpZWxkKCBlLmRldGFpbC5mb3JtSWQgKTtcblxuXHRcdFx0JCggZS5kZXRhaWwuYmxvY2sgKVxuXHRcdFx0XHQub2ZmKCAnY2xpY2snIClcblx0XHRcdFx0Lm9uKCAnY2xpY2snLCBhcHAuYmxvY2tDbGljayApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDbGljayBvbiB0aGUgYmxvY2sgZXZlbnQgaGFuZGxlci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IGUgRXZlbnQgb2JqZWN0LlxuXHRcdCAqL1xuXHRcdGJsb2NrQ2xpY2soIGUgKSB7XG5cdFx0XHRhcHAuaW5pdExlYWRGb3JtU2V0dGluZ3MoIGUuY3VycmVudFRhcmdldCApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBVcGRhdGUgYWNjZW50IGNvbG9ycyBvZiBzb21lIGZpZWxkcyBpbiBHQiBibG9jayBpbiBNb2Rlcm4gTWFya3VwIG1vZGUuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSBkZXRhaWwgRXZlbnQgZGV0YWlscyBvYmplY3QuXG5cdFx0ICovXG5cdFx0dXBkYXRlQWNjZW50Q29sb3JzKCBkZXRhaWwgKSB7XG5cdFx0XHRpZiAoXG5cdFx0XHRcdCEgd3Bmb3Jtc19ndXRlbmJlcmdfZm9ybV9zZWxlY3Rvci5pc19tb2Rlcm5fbWFya3VwIHx8XG5cdFx0XHRcdCEgd2luZG93LldQRm9ybXMgfHxcblx0XHRcdFx0ISB3aW5kb3cuV1BGb3Jtcy5Gcm9udGVuZE1vZGVybiB8fFxuXHRcdFx0XHQhIGRldGFpbC5ibG9ja1xuXHRcdFx0KSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0Y29uc3QgJGZvcm0gPSAkKCBkZXRhaWwuYmxvY2sucXVlcnlTZWxlY3RvciggYCN3cGZvcm1zLSR7IGRldGFpbC5mb3JtSWQgfWAgKSApLFxuXHRcdFx0XHRGcm9udGVuZE1vZGVybiA9IHdpbmRvdy5XUEZvcm1zLkZyb250ZW5kTW9kZXJuO1xuXG5cdFx0XHRGcm9udGVuZE1vZGVybi51cGRhdGVHQkJsb2NrUGFnZUluZGljYXRvckNvbG9yKCAkZm9ybSApO1xuXHRcdFx0RnJvbnRlbmRNb2Rlcm4udXBkYXRlR0JCbG9ja0ljb25DaG9pY2VzQ29sb3IoICRmb3JtICk7XG5cdFx0XHRGcm9udGVuZE1vZGVybi51cGRhdGVHQkJsb2NrUmF0aW5nQ29sb3IoICRmb3JtICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXQgTW9kZXJuIHN0eWxlIERyb3Bkb3duIGZpZWxkcyAoPHNlbGVjdD4pLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdH0gZGV0YWlsIEV2ZW50IGRldGFpbHMgb2JqZWN0LlxuXHRcdCAqL1xuXHRcdGxvYWRDaG9pY2VzSlMoIGRldGFpbCApIHtcblx0XHRcdGlmICggdHlwZW9mIHdpbmRvdy5DaG9pY2VzICE9PSAnZnVuY3Rpb24nICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0ICRmb3JtID0gJCggZGV0YWlsLmJsb2NrLnF1ZXJ5U2VsZWN0b3IoIGAjd3Bmb3Jtcy0keyBkZXRhaWwuZm9ybUlkIH1gICkgKTtcblxuXHRcdFx0JGZvcm0uZmluZCggJy5jaG9pY2VzanMtc2VsZWN0JyApLmVhY2goIGZ1bmN0aW9uKCBpZHgsIGVsICkge1xuXHRcdFx0XHRjb25zdCAkZWwgPSAkKCBlbCApO1xuXG5cdFx0XHRcdGlmICggJGVsLmRhdGEoICdjaG9pY2UnICkgPT09ICdhY3RpdmUnICkge1xuXHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGNvbnN0IGFyZ3MgPSB3aW5kb3cud3Bmb3Jtc19jaG9pY2VzanNfY29uZmlnIHx8IHt9LFxuXHRcdFx0XHRcdHNlYXJjaEVuYWJsZWQgPSAkZWwuZGF0YSggJ3NlYXJjaC1lbmFibGVkJyApLFxuXHRcdFx0XHRcdCRmaWVsZCA9ICRlbC5jbG9zZXN0KCAnLndwZm9ybXMtZmllbGQnICk7XG5cblx0XHRcdFx0YXJncy5zZWFyY2hFbmFibGVkID0gJ3VuZGVmaW5lZCcgIT09IHR5cGVvZiBzZWFyY2hFbmFibGVkID8gc2VhcmNoRW5hYmxlZCA6IHRydWU7XG5cdFx0XHRcdGFyZ3MuY2FsbGJhY2tPbkluaXQgPSBmdW5jdGlvbigpIHtcblx0XHRcdFx0XHRjb25zdCBzZWxmID0gdGhpcyxcblx0XHRcdFx0XHRcdCRlbGVtZW50ID0gJCggc2VsZi5wYXNzZWRFbGVtZW50LmVsZW1lbnQgKSxcblx0XHRcdFx0XHRcdCRpbnB1dCA9ICQoIHNlbGYuaW5wdXQuZWxlbWVudCApLFxuXHRcdFx0XHRcdFx0c2l6ZUNsYXNzID0gJGVsZW1lbnQuZGF0YSggJ3NpemUtY2xhc3MnICk7XG5cblx0XHRcdFx0XHQvLyBBZGQgQ1NTLWNsYXNzIGZvciBzaXplLlxuXHRcdFx0XHRcdGlmICggc2l6ZUNsYXNzICkge1xuXHRcdFx0XHRcdFx0JCggc2VsZi5jb250YWluZXJPdXRlci5lbGVtZW50ICkuYWRkQ2xhc3MoIHNpemVDbGFzcyApO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdC8qKlxuXHRcdFx0XHRcdCAqIElmIGEgbXVsdGlwbGUgc2VsZWN0IGhhcyBzZWxlY3RlZCBjaG9pY2VzIC0gaGlkZSBhIHBsYWNlaG9sZGVyIHRleHQuXG5cdFx0XHRcdFx0ICogSW4gY2FzZSBpZiBzZWxlY3QgaXMgZW1wdHkgLSB3ZSByZXR1cm4gcGxhY2Vob2xkZXIgdGV4dCBiYWNrLlxuXHRcdFx0XHRcdCAqL1xuXHRcdFx0XHRcdGlmICggJGVsZW1lbnQucHJvcCggJ211bHRpcGxlJyApICkge1xuXHRcdFx0XHRcdFx0Ly8gT24gaW5pdCBldmVudC5cblx0XHRcdFx0XHRcdCRpbnB1dC5kYXRhKCAncGxhY2Vob2xkZXInLCAkaW5wdXQuYXR0ciggJ3BsYWNlaG9sZGVyJyApICk7XG5cblx0XHRcdFx0XHRcdGlmICggc2VsZi5nZXRWYWx1ZSggdHJ1ZSApLmxlbmd0aCApIHtcblx0XHRcdFx0XHRcdFx0JGlucHV0LnJlbW92ZUF0dHIoICdwbGFjZWhvbGRlcicgKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHR0aGlzLmRpc2FibGUoKTtcblx0XHRcdFx0XHQkZmllbGQuZmluZCggJy5pcy1kaXNhYmxlZCcgKS5yZW1vdmVDbGFzcyggJ2lzLWRpc2FibGVkJyApO1xuXHRcdFx0XHR9O1xuXG5cdFx0XHRcdHRyeSB7XG5cdFx0XHRcdFx0Y29uc3QgY2hvaWNlc0luc3RhbmNlID0gbmV3IENob2ljZXMoIGVsLCBhcmdzICk7XG5cblx0XHRcdFx0XHQvLyBTYXZlIENob2ljZXMuanMgaW5zdGFuY2UgZm9yIGZ1dHVyZSBhY2Nlc3MuXG5cdFx0XHRcdFx0JGVsLmRhdGEoICdjaG9pY2VzanMnLCBjaG9pY2VzSW5zdGFuY2UgKTtcblx0XHRcdFx0fSBjYXRjaCAoIGUgKSB7fSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG5vLWVtcHR5XG5cdFx0XHR9ICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYWxpemUgUmljaFRleHQgZmllbGQuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7bnVtYmVyfSBmb3JtSWQgRm9ybSBJRC5cblx0XHQgKi9cblx0XHRpbml0UmljaFRleHRGaWVsZCggZm9ybUlkICkge1xuXHRcdFx0Ly8gU2V0IGRlZmF1bHQgdGFiIHRvIGBWaXN1YWxgLlxuXHRcdFx0JCggYCN3cGZvcm1zLSR7IGZvcm1JZCB9IC53cC1lZGl0b3Itd3JhcGAgKS5yZW1vdmVDbGFzcyggJ2h0bWwtYWN0aXZlJyApLmFkZENsYXNzKCAndG1jZS1hY3RpdmUnICk7XG5cdFx0fSxcblx0fTtcblxuXHQvLyBQcm92aWRlIGFjY2VzcyB0byBwdWJsaWMgZnVuY3Rpb25zL3Byb3BlcnRpZXMuXG5cdHJldHVybiBhcHA7XG59KCBkb2N1bWVudCwgd2luZG93LCBqUXVlcnkgKSApO1xuXG4vLyBJbml0aWFsaXplLlxuV1BGb3Jtcy5Gb3JtU2VsZWN0b3IuaW5pdCgpO1xuIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7K0NBQ0EscUpBQUFBLG1CQUFBLFlBQUFBLG9CQUFBLFdBQUFDLENBQUEsU0FBQUMsQ0FBQSxFQUFBRCxDQUFBLE9BQUFFLENBQUEsR0FBQUMsTUFBQSxDQUFBQyxTQUFBLEVBQUFDLENBQUEsR0FBQUgsQ0FBQSxD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
},{}]},{},[1])