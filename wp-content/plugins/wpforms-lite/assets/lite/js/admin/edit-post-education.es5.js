(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global wpforms_edit_post_education */

/**
 * WPForms Edit Post Education function.
 *
 * @since 1.8.1
 */

'use strict';

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
var WPFormsEditPostEducation = window.WPFormsEditPostEducation || function (document, window, $) {
  /**
   * Public functions and properties.
   *
   * @since 1.8.1
   *
   * @type {object}
   */
  var app = {
    /**
     * Determine if the notice was showed before.
     *
     * @since 1.8.1
     */
    isNoticeVisible: false,
    /**
     * Start the engine.
     *
     * @since 1.8.1
     */
    init: function init() {
      $(window).on('load', function () {
        // In the case of jQuery 3.+, we need to wait for a ready event first.
        if (typeof $.ready.then === 'function') {
          $.ready.then(app.load);
        } else {
          app.load();
        }
      });
    },
    /**
     * Page load.
     *
     * @since 1.8.1
     */
    load: function load() {
      if (!app.isGutenbergEditor()) {
        app.maybeShowClassicNotice();
        app.bindClassicEvents();
        return;
      }
      var blockLoadedInterval = setInterval(function () {
        if (!document.querySelector('.editor-post-title__input, iframe[name="editor-canvas"]')) {
          return;
        }
        clearInterval(blockLoadedInterval);
        if (!app.isFse()) {
          app.maybeShowGutenbergNotice();
          app.bindGutenbergEvents();
          return;
        }
        var iframe = document.querySelector('iframe[name="editor-canvas"]');
        var observer = new MutationObserver(function () {
          var iframeDocument = iframe.contentDocument || iframe.contentWindow.document || {};
          if (iframeDocument.readyState === 'complete' && iframeDocument.querySelector('.editor-post-title__input')) {
            app.maybeShowGutenbergNotice();
            app.bindFseEvents();
            observer.disconnect();
          }
        });
        observer.observe(document.body, {
          subtree: true,
          childList: true
        });
      }, 200);
    },
    /**
     * Bind events for Classic Editor.
     *
     * @since 1.8.1
     */
    bindClassicEvents: function bindClassicEvents() {
      var $document = $(document);
      if (!app.isNoticeVisible) {
        $document.on('input', '#title', app.maybeShowClassicNotice);
      }
      $document.on('click', '.wpforms-edit-post-education-notice-close', app.closeNotice);
    },
    /**
     * Bind events for Gutenberg Editor.
     *
     * @since 1.8.1
     */
    bindGutenbergEvents: function bindGutenbergEvents() {
      var $document = $(document);
      $document.on('DOMSubtreeModified', '.edit-post-layout', app.distractionFreeModeToggle);
      if (app.isNoticeVisible) {
        return;
      }
      $document.on('input', '.editor-post-title__input', app.maybeShowGutenbergNotice).on('DOMSubtreeModified', '.editor-post-title__input', app.maybeShowGutenbergNotice);
    },
    /**
     * Bind events for Gutenberg Editor in FSE mode.
     *
     * @since 1.8.1
     */
    bindFseEvents: function bindFseEvents() {
      var $iframe = $('iframe[name="editor-canvas"]');
      $(document).on('DOMSubtreeModified', '.edit-post-layout', app.distractionFreeModeToggle);
      $iframe.contents().on('DOMSubtreeModified', '.editor-post-title__input', app.maybeShowGutenbergNotice);
    },
    /**
     * Determine if the editor is Gutenberg.
     *
     * @since 1.8.1
     *
     * @returns {boolean} True if the editor is Gutenberg.
     */
    isGutenbergEditor: function isGutenbergEditor() {
      return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
    },
    /**
     * Determine if the editor is Gutenberg in FSE mode.
     *
     * @since 1.8.1
     *
     * @returns {boolean} True if the Gutenberg editor in FSE mode.
     */
    isFse: function isFse() {
      return Boolean($('iframe[name="editor-canvas"]').length);
    },
    /**
     * Create a notice for Gutenberg.
     *
     * @since 1.8.1
     */
    showGutenbergNotice: function showGutenbergNotice() {
      wp.data.dispatch('core/notices').createInfoNotice(wpforms_edit_post_education.gutenberg_notice.template, app.getGutenbergNoticeSettings());

      // The notice component doesn't have a way to add HTML id or class to the notice.
      // Also, the notice became visible with a delay on old Gutenberg versions.
      var hasNotice = setInterval(function () {
        var noticeBody = $('.wpforms-edit-post-education-notice-body');
        if (!noticeBody.length) {
          return;
        }
        var $notice = noticeBody.closest('.components-notice');
        $notice.addClass('wpforms-edit-post-education-notice');
        $notice.find('.is-secondary, .is-link').removeClass('is-secondary').removeClass('is-link').addClass('is-primary');
        clearInterval(hasNotice);
      }, 100);
    },
    /**
     * Get settings for the Gutenberg notice.
     *
     * @since 1.8.1
     *
     * @returns {object} Notice settings.
     */
    getGutenbergNoticeSettings: function getGutenbergNoticeSettings() {
      var pluginName = 'wpforms-edit-post-product-education-guide';
      var noticeSettings = {
        id: pluginName,
        isDismissible: true,
        HTML: true,
        __unstableHTML: true,
        actions: [{
          className: 'wpforms-edit-post-education-notice-guide-button',
          variant: 'primary',
          label: wpforms_edit_post_education.gutenberg_notice.button
        }]
      };
      if (!wpforms_edit_post_education.gutenberg_guide) {
        noticeSettings.actions[0].url = wpforms_edit_post_education.gutenberg_notice.url;
        return noticeSettings;
      }
      var Guide = wp.components.Guide;
      var useState = wp.element.useState;
      var registerPlugin = wp.plugins.registerPlugin;
      var unregisterPlugin = wp.plugins.unregisterPlugin;
      var GutenbergTutorial = function GutenbergTutorial() {
        var _useState = useState(true),
          _useState2 = _slicedToArray(_useState, 2),
          isOpen = _useState2[0],
          setIsOpen = _useState2[1];
        if (!isOpen) {
          return null;
        }
        return (
          /*#__PURE__*/
          // eslint-disable-next-line react/react-in-jsx-scope
          React.createElement(Guide, {
            className: "edit-post-welcome-guide",
            onFinish: function onFinish() {
              unregisterPlugin(pluginName);
              setIsOpen(false);
            },
            pages: app.getGuidePages()
          })
        );
      };
      noticeSettings.onDismiss = app.updateUserMeta;
      noticeSettings.actions[0].onClick = function () {
        return registerPlugin(pluginName, {
          render: GutenbergTutorial
        });
      };
      return noticeSettings;
    },
    /**
     * Get Guide pages in proper format.
     *
     * @since 1.8.1
     *
     * @returns {Array} Guide Pages.
     */
    getGuidePages: function getGuidePages() {
      var pages = [];
      wpforms_edit_post_education.gutenberg_guide.forEach(function (page) {
        pages.push({
          /* eslint-disable react/react-in-jsx-scope */
          content: /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("h1", {
            className: "edit-post-welcome-guide__heading"
          }, page.title), /*#__PURE__*/React.createElement("p", {
            className: "edit-post-welcome-guide__text"
          }, page.content)),
          image: /*#__PURE__*/React.createElement("img", {
            className: "edit-post-welcome-guide__image",
            src: page.image,
            alt: page.title
          })
          /* eslint-enable react/react-in-jsx-scope */
        });
      });
      return pages;
    },
    /**
     * Show notice if the page title matches some keywords for Classic Editor.
     *
     * @since 1.8.1
     */
    maybeShowClassicNotice: function maybeShowClassicNotice() {
      if (app.isNoticeVisible) {
        return;
      }
      if (app.isTitleMatchKeywords($('#title').val())) {
        app.isNoticeVisible = true;
        $('.wpforms-edit-post-education-notice').removeClass('wpforms-hidden');
      }
    },
    /**
     * Show notice if the page title matches some keywords for Gutenberg Editor.
     *
     * @since 1.8.1
     */
    maybeShowGutenbergNotice: function maybeShowGutenbergNotice() {
      if (app.isNoticeVisible) {
        return;
      }
      var $postTitle = app.isFse() ? $('iframe[name="editor-canvas"]').contents().find('.editor-post-title__input') : $('.editor-post-title__input');
      var tagName = $postTitle.prop('tagName');
      var title = tagName === 'TEXTAREA' ? $postTitle.val() : $postTitle.text();
      if (app.isTitleMatchKeywords(title)) {
        app.isNoticeVisible = true;
        app.showGutenbergNotice();
      }
    },
    /**
     * Add notice class when the distraction mode is enabled.
     *
     * @since 1.8.1.2
     */
    distractionFreeModeToggle: function distractionFreeModeToggle() {
      if (!app.isNoticeVisible) {
        return;
      }
      var $document = $(document);
      var isDistractionFreeMode = Boolean($document.find('.is-distraction-free').length);
      if (!isDistractionFreeMode) {
        return;
      }
      var isNoticeHasClass = Boolean($('.wpforms-edit-post-education-notice').length);
      if (isNoticeHasClass) {
        return;
      }
      var $noticeBody = $document.find('.wpforms-edit-post-education-notice-body');
      var $notice = $noticeBody.closest('.components-notice');
      $notice.addClass('wpforms-edit-post-education-notice');
    },
    /**
     * Determine if the title matches keywords.
     *
     * @since 1.8.1
     *
     * @param {string} titleValue Page title value.
     *
     * @returns {boolean} True if the title matches some keywords.
     */
    isTitleMatchKeywords: function isTitleMatchKeywords(titleValue) {
      var expectedTitleRegex = new RegExp(/\b(contact|form)\b/i);
      return expectedTitleRegex.test(titleValue);
    },
    /**
     * Close a notice.
     *
     * @since 1.8.1
     */
    closeNotice: function closeNotice() {
      $(this).closest('.wpforms-edit-post-education-notice').remove();
      app.updateUserMeta();
    },
    /**
     * Update user meta and don't show the notice next time.
     *
     * @since 1.8.1
     */
    updateUserMeta: function updateUserMeta() {
      $.post(wpforms_edit_post_education.ajax_url, {
        action: 'wpforms_education_dismiss',
        nonce: wpforms_edit_post_education.education_nonce,
        section: 'edit-post-notice'
      });
    }
  };
  return app;
}(document, window, jQuery);
WPFormsEditPostEducation.init();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfc2xpY2VkVG9BcnJheSIsImFyciIsImkiLCJfYXJyYXlXaXRoSG9sZXMiLCJfaXRlcmFibGVUb0FycmF5TGltaXQiLCJfdW5zdXBwb3J0ZWRJdGVyYWJsZVRvQXJyYXkiLCJfbm9uSXRlcmFibGVSZXN0IiwiVHlwZUVycm9yIiwibyIsIm1pbkxlbiIsIl9hcnJheUxpa2VUb0FycmF5IiwibiIsIk9iamVjdCIsInByb3RvdHlwZSIsInRvU3RyaW5nIiwiY2FsbCIsInNsaWNlIiwiY29uc3RydWN0b3IiLCJuYW1lIiwiQXJyYXkiLCJmcm9tIiwidGVzdCIsImxlbiIsImxlbmd0aCIsImFycjIiLCJyIiwibCIsInQiLCJTeW1ib2wiLCJpdGVyYXRvciIsImUiLCJ1IiwiYSIsImYiLCJuZXh0IiwiZG9uZSIsInB1c2giLCJ2YWx1ZSIsInJldHVybiIsImlzQXJyYXkiLCJXUEZvcm1zRWRpdFBvc3RFZHVjYXRpb24iLCJ3aW5kb3ciLCJkb2N1bWVudCIsIiQiLCJhcHAiLCJpc05vdGljZVZpc2libGUiLCJpbml0Iiwib24iLCJyZWFkeSIsInRoZW4iLCJsb2FkIiwiaXNHdXRlbmJlcmdFZGl0b3IiLCJtYXliZVNob3dDbGFzc2ljTm90aWNlIiwiYmluZENsYXNzaWNFdmVudHMiLCJibG9ja0xvYWRlZEludGVydmFsIiwic2V0SW50ZXJ2YWwiLCJxdWVyeVNlbGVjdG9yIiwiY2xlYXJJbnRlcnZhbCIsImlzRnNlIiwibWF5YmVTaG93R3V0ZW5iZXJnTm90aWNlIiwiYmluZEd1dGVuYmVyZ0V2ZW50cyIsImlmcmFtZSIsIm9ic2VydmVyIiwiTXV0YXRpb25PYnNlcnZlciIsImlmcmFtZURvY3VtZW50IiwiY29udGVudERvY3VtZW50IiwiY29udGVudFdpbmRvdyIsInJlYWR5U3RhdGUiLCJiaW5kRnNlRXZlbnRzIiwiZGlzY29ubmVjdCIsIm9ic2VydmUiLCJib2R5Iiwic3VidHJlZSIsImNoaWxkTGlzdCIsIiRkb2N1bWVudCIsImNsb3NlTm90aWNlIiwiZGlzdHJhY3Rpb25GcmVlTW9kZVRvZ2dsZSIsIiRpZnJhbWUiLCJjb250ZW50cyIsIndwIiwiYmxvY2tzIiwiQm9vbGVhbiIsInNob3dHdXRlbmJlcmdOb3RpY2UiLCJkYXRhIiwiZGlzcGF0Y2giLCJjcmVhdGVJbmZvTm90aWNlIiwid3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uIiwiZ3V0ZW5iZXJnX25vdGljZSIsInRlbXBsYXRlIiwiZ2V0R3V0ZW5iZXJnTm90aWNlU2V0dGluZ3MiLCJoYXNOb3RpY2UiLCJub3RpY2VCb2R5IiwiJG5vdGljZSIsImNsb3Nlc3QiLCJhZGRDbGFzcyIsImZpbmQiLCJyZW1vdmVDbGFzcyIsInBsdWdpbk5hbWUiLCJub3RpY2VTZXR0aW5ncyIsImlkIiwiaXNEaXNtaXNzaWJsZSIsIkhUTUwiLCJfX3Vuc3RhYmxlSFRNTCIsImFjdGlvbnMiLCJjbGFzc05hbWUiLCJ2YXJpYW50IiwibGFiZWwiLCJidXR0b24iLCJndXRlbmJlcmdfZ3VpZGUiLCJ1cmwiLCJHdWlkZSIsImNvbXBvbmVudHMiLCJ1c2VTdGF0ZSIsImVsZW1lbnQiLCJyZWdpc3RlclBsdWdpbiIsInBsdWdpbnMiLCJ1bnJlZ2lzdGVyUGx1Z2luIiwiR3V0ZW5iZXJnVHV0b3JpYWwiLCJfdXNlU3RhdGUiLCJfdXNlU3RhdGUyIiwiaXNPcGVuIiwic2V0SXNPcGVuIiwiUmVhY3QiLCJjcmVhdGVFbGVtZW50Iiwib25GaW5pc2giLCJwYWdlcyIsImdldEd1aWRlUGFnZXMiLCJvbkRpc21pc3MiLCJ1cGRhdGVVc2VyTWV0YSIsIm9uQ2xpY2siLCJyZW5kZXIiLCJmb3JFYWNoIiwicGFnZSIsImNvbnRlbnQiLCJGcmFnbWVudCIsInRpdGxlIiwiaW1hZ2UiLCJzcmMiLCJhbHQiLCJpc1RpdGxlTWF0Y2hLZXl3b3JkcyIsInZhbCIsIiRwb3N0VGl0bGUiLCJ0YWdOYW1lIiwicHJvcCIsInRleHQiLCJpc0Rpc3RyYWN0aW9uRnJlZU1vZGUiLCJpc05vdGljZUhhc0NsYXNzIiwiJG5vdGljZUJvZHkiLCJ0aXRsZVZhbHVlIiwiZXhwZWN0ZWRUaXRsZVJlZ2V4IiwiUmVnRXhwIiwicmVtb3ZlIiwicG9zdCIsImFqYXhfdXJsIiwiYWN0aW9uIiwibm9uY2UiLCJlZHVjYXRpb25fbm9uY2UiLCJzZWN0aW9uIiwialF1ZXJ5Il0sInNvdXJjZXMiOlsiZmFrZV8zNzFkOGI2My5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvKiBnbG9iYWwgd3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uICovXG5cbi8qKlxuICogV1BGb3JtcyBFZGl0IFBvc3QgRWR1Y2F0aW9uIGZ1bmN0aW9uLlxuICpcbiAqIEBzaW5jZSAxLjguMVxuICovXG5cbid1c2Ugc3RyaWN0JztcblxuY29uc3QgV1BGb3Jtc0VkaXRQb3N0RWR1Y2F0aW9uID0gd2luZG93LldQRm9ybXNFZGl0UG9zdEVkdWNhdGlvbiB8fCAoIGZ1bmN0aW9uKCBkb2N1bWVudCwgd2luZG93LCAkICkge1xuXG5cdC8qKlxuXHQgKiBQdWJsaWMgZnVuY3Rpb25zIGFuZCBwcm9wZXJ0aWVzLlxuXHQgKlxuXHQgKiBAc2luY2UgMS44LjFcblx0ICpcblx0ICogQHR5cGUge29iamVjdH1cblx0ICovXG5cdGNvbnN0IGFwcCA9IHtcblxuXHRcdC8qKlxuXHRcdCAqIERldGVybWluZSBpZiB0aGUgbm90aWNlIHdhcyBzaG93ZWQgYmVmb3JlLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0aXNOb3RpY2VWaXNpYmxlOiBmYWxzZSxcblxuXHRcdC8qKlxuXHRcdCAqIFN0YXJ0IHRoZSBlbmdpbmUuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRpbml0OiBmdW5jdGlvbigpIHtcblxuXHRcdFx0JCggd2luZG93ICkub24oICdsb2FkJywgZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0Ly8gSW4gdGhlIGNhc2Ugb2YgalF1ZXJ5IDMuKywgd2UgbmVlZCB0byB3YWl0IGZvciBhIHJlYWR5IGV2ZW50IGZpcnN0LlxuXHRcdFx0XHRpZiAoIHR5cGVvZiAkLnJlYWR5LnRoZW4gPT09ICdmdW5jdGlvbicgKSB7XG5cdFx0XHRcdFx0JC5yZWFkeS50aGVuKCBhcHAubG9hZCApO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdGFwcC5sb2FkKCk7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogUGFnZSBsb2FkLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0bG9hZDogZnVuY3Rpb24oKSB7XG5cblx0XHRcdGlmICggISBhcHAuaXNHdXRlbmJlcmdFZGl0b3IoKSApIHtcblx0XHRcdFx0YXBwLm1heWJlU2hvd0NsYXNzaWNOb3RpY2UoKTtcblx0XHRcdFx0YXBwLmJpbmRDbGFzc2ljRXZlbnRzKCk7XG5cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCBibG9ja0xvYWRlZEludGVydmFsID0gc2V0SW50ZXJ2YWwoIGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdGlmICggISBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnLmVkaXRvci1wb3N0LXRpdGxlX19pbnB1dCwgaWZyYW1lW25hbWU9XCJlZGl0b3ItY2FudmFzXCJdJyApICkge1xuXHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGNsZWFySW50ZXJ2YWwoIGJsb2NrTG9hZGVkSW50ZXJ2YWwgKTtcblxuXHRcdFx0XHRpZiAoICEgYXBwLmlzRnNlKCkgKSB7XG5cblx0XHRcdFx0XHRhcHAubWF5YmVTaG93R3V0ZW5iZXJnTm90aWNlKCk7XG5cdFx0XHRcdFx0YXBwLmJpbmRHdXRlbmJlcmdFdmVudHMoKTtcblxuXHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGNvbnN0IGlmcmFtZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdpZnJhbWVbbmFtZT1cImVkaXRvci1jYW52YXNcIl0nICk7XG5cdFx0XHRcdGNvbnN0IG9ic2VydmVyID0gbmV3IE11dGF0aW9uT2JzZXJ2ZXIoIGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0Y29uc3QgaWZyYW1lRG9jdW1lbnQgPSBpZnJhbWUuY29udGVudERvY3VtZW50IHx8IGlmcmFtZS5jb250ZW50V2luZG93LmRvY3VtZW50IHx8IHt9O1xuXG5cdFx0XHRcdFx0aWYgKCBpZnJhbWVEb2N1bWVudC5yZWFkeVN0YXRlID09PSAnY29tcGxldGUnICYmIGlmcmFtZURvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICcuZWRpdG9yLXBvc3QtdGl0bGVfX2lucHV0JyApICkge1xuXHRcdFx0XHRcdFx0YXBwLm1heWJlU2hvd0d1dGVuYmVyZ05vdGljZSgpO1xuXHRcdFx0XHRcdFx0YXBwLmJpbmRGc2VFdmVudHMoKTtcblxuXHRcdFx0XHRcdFx0b2JzZXJ2ZXIuZGlzY29ubmVjdCgpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSApO1xuXHRcdFx0XHRvYnNlcnZlci5vYnNlcnZlKCBkb2N1bWVudC5ib2R5LCB7IHN1YnRyZWU6IHRydWUsIGNoaWxkTGlzdDogdHJ1ZSB9ICk7XG5cdFx0XHR9LCAyMDAgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQmluZCBldmVudHMgZm9yIENsYXNzaWMgRWRpdG9yLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0YmluZENsYXNzaWNFdmVudHM6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRjb25zdCAkZG9jdW1lbnQgPSAkKCBkb2N1bWVudCApO1xuXG5cdFx0XHRpZiAoICEgYXBwLmlzTm90aWNlVmlzaWJsZSApIHtcblx0XHRcdFx0JGRvY3VtZW50Lm9uKCAnaW5wdXQnLCAnI3RpdGxlJywgYXBwLm1heWJlU2hvd0NsYXNzaWNOb3RpY2UgKTtcblx0XHRcdH1cblxuXHRcdFx0JGRvY3VtZW50Lm9uKCAnY2xpY2snLCAnLndwZm9ybXMtZWRpdC1wb3N0LWVkdWNhdGlvbi1ub3RpY2UtY2xvc2UnLCBhcHAuY2xvc2VOb3RpY2UgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQmluZCBldmVudHMgZm9yIEd1dGVuYmVyZyBFZGl0b3IuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRiaW5kR3V0ZW5iZXJnRXZlbnRzOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0Y29uc3QgJGRvY3VtZW50ID0gJCggZG9jdW1lbnQgKTtcblxuXHRcdFx0JGRvY3VtZW50XG5cdFx0XHRcdC5vbiggJ0RPTVN1YnRyZWVNb2RpZmllZCcsICcuZWRpdC1wb3N0LWxheW91dCcsIGFwcC5kaXN0cmFjdGlvbkZyZWVNb2RlVG9nZ2xlICk7XG5cblx0XHRcdGlmICggYXBwLmlzTm90aWNlVmlzaWJsZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHQkZG9jdW1lbnRcblx0XHRcdFx0Lm9uKCAnaW5wdXQnLCAnLmVkaXRvci1wb3N0LXRpdGxlX19pbnB1dCcsIGFwcC5tYXliZVNob3dHdXRlbmJlcmdOb3RpY2UgKVxuXHRcdFx0XHQub24oICdET01TdWJ0cmVlTW9kaWZpZWQnLCAnLmVkaXRvci1wb3N0LXRpdGxlX19pbnB1dCcsIGFwcC5tYXliZVNob3dHdXRlbmJlcmdOb3RpY2UgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQmluZCBldmVudHMgZm9yIEd1dGVuYmVyZyBFZGl0b3IgaW4gRlNFIG1vZGUuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRiaW5kRnNlRXZlbnRzOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0Y29uc3QgJGlmcmFtZSA9ICQoICdpZnJhbWVbbmFtZT1cImVkaXRvci1jYW52YXNcIl0nICk7XG5cblx0XHRcdCQoIGRvY3VtZW50IClcblx0XHRcdFx0Lm9uKCAnRE9NU3VidHJlZU1vZGlmaWVkJywgJy5lZGl0LXBvc3QtbGF5b3V0JywgYXBwLmRpc3RyYWN0aW9uRnJlZU1vZGVUb2dnbGUgKTtcblxuXHRcdFx0JGlmcmFtZS5jb250ZW50cygpXG5cdFx0XHRcdC5vbiggJ0RPTVN1YnRyZWVNb2RpZmllZCcsICcuZWRpdG9yLXBvc3QtdGl0bGVfX2lucHV0JywgYXBwLm1heWJlU2hvd0d1dGVuYmVyZ05vdGljZSApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBEZXRlcm1pbmUgaWYgdGhlIGVkaXRvciBpcyBHdXRlbmJlcmcuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKlxuXHRcdCAqIEByZXR1cm5zIHtib29sZWFufSBUcnVlIGlmIHRoZSBlZGl0b3IgaXMgR3V0ZW5iZXJnLlxuXHRcdCAqL1xuXHRcdGlzR3V0ZW5iZXJnRWRpdG9yOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0cmV0dXJuIHR5cGVvZiB3cCAhPT0gJ3VuZGVmaW5lZCcgJiYgdHlwZW9mIHdwLmJsb2NrcyAhPT0gJ3VuZGVmaW5lZCc7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIERldGVybWluZSBpZiB0aGUgZWRpdG9yIGlzIEd1dGVuYmVyZyBpbiBGU0UgbW9kZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybnMge2Jvb2xlYW59IFRydWUgaWYgdGhlIEd1dGVuYmVyZyBlZGl0b3IgaW4gRlNFIG1vZGUuXG5cdFx0ICovXG5cdFx0aXNGc2U6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRyZXR1cm4gQm9vbGVhbiggJCggJ2lmcmFtZVtuYW1lPVwiZWRpdG9yLWNhbnZhc1wiXScgKS5sZW5ndGggKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ3JlYXRlIGEgbm90aWNlIGZvciBHdXRlbmJlcmcuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRzaG93R3V0ZW5iZXJnTm90aWNlOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0d3AuZGF0YS5kaXNwYXRjaCggJ2NvcmUvbm90aWNlcycgKS5jcmVhdGVJbmZvTm90aWNlKFxuXHRcdFx0XHR3cGZvcm1zX2VkaXRfcG9zdF9lZHVjYXRpb24uZ3V0ZW5iZXJnX25vdGljZS50ZW1wbGF0ZSxcblx0XHRcdFx0YXBwLmdldEd1dGVuYmVyZ05vdGljZVNldHRpbmdzKClcblx0XHRcdCk7XG5cblx0XHRcdC8vIFRoZSBub3RpY2UgY29tcG9uZW50IGRvZXNuJ3QgaGF2ZSBhIHdheSB0byBhZGQgSFRNTCBpZCBvciBjbGFzcyB0byB0aGUgbm90aWNlLlxuXHRcdFx0Ly8gQWxzbywgdGhlIG5vdGljZSBiZWNhbWUgdmlzaWJsZSB3aXRoIGEgZGVsYXkgb24gb2xkIEd1dGVuYmVyZyB2ZXJzaW9ucy5cblx0XHRcdGNvbnN0IGhhc05vdGljZSA9IHNldEludGVydmFsKCBmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRjb25zdCBub3RpY2VCb2R5ID0gJCggJy53cGZvcm1zLWVkaXQtcG9zdC1lZHVjYXRpb24tbm90aWNlLWJvZHknICk7XG5cdFx0XHRcdGlmICggISBub3RpY2VCb2R5Lmxlbmd0aCApIHtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRjb25zdCAkbm90aWNlID0gbm90aWNlQm9keS5jbG9zZXN0KCAnLmNvbXBvbmVudHMtbm90aWNlJyApO1xuXHRcdFx0XHQkbm90aWNlLmFkZENsYXNzKCAnd3Bmb3Jtcy1lZGl0LXBvc3QtZWR1Y2F0aW9uLW5vdGljZScgKTtcblx0XHRcdFx0JG5vdGljZS5maW5kKCAnLmlzLXNlY29uZGFyeSwgLmlzLWxpbmsnICkucmVtb3ZlQ2xhc3MoICdpcy1zZWNvbmRhcnknICkucmVtb3ZlQ2xhc3MoICdpcy1saW5rJyApLmFkZENsYXNzKCAnaXMtcHJpbWFyeScgKTtcblxuXHRcdFx0XHRjbGVhckludGVydmFsKCBoYXNOb3RpY2UgKTtcblx0XHRcdH0sIDEwMCApO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgc2V0dGluZ3MgZm9yIHRoZSBHdXRlbmJlcmcgbm90aWNlLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJucyB7b2JqZWN0fSBOb3RpY2Ugc2V0dGluZ3MuXG5cdFx0ICovXG5cdFx0Z2V0R3V0ZW5iZXJnTm90aWNlU2V0dGluZ3M6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRjb25zdCBwbHVnaW5OYW1lID0gJ3dwZm9ybXMtZWRpdC1wb3N0LXByb2R1Y3QtZWR1Y2F0aW9uLWd1aWRlJztcblx0XHRcdGNvbnN0IG5vdGljZVNldHRpbmdzID0ge1xuXHRcdFx0XHRpZDogcGx1Z2luTmFtZSxcblx0XHRcdFx0aXNEaXNtaXNzaWJsZTogdHJ1ZSxcblx0XHRcdFx0SFRNTDogdHJ1ZSxcblx0XHRcdFx0X191bnN0YWJsZUhUTUw6IHRydWUsXG5cdFx0XHRcdGFjdGlvbnM6IFtcblx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRjbGFzc05hbWU6ICd3cGZvcm1zLWVkaXQtcG9zdC1lZHVjYXRpb24tbm90aWNlLWd1aWRlLWJ1dHRvbicsXG5cdFx0XHRcdFx0XHR2YXJpYW50OiAncHJpbWFyeScsXG5cdFx0XHRcdFx0XHRsYWJlbDogd3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uLmd1dGVuYmVyZ19ub3RpY2UuYnV0dG9uLFxuXHRcdFx0XHRcdH0sXG5cdFx0XHRcdF0sXG5cdFx0XHR9O1xuXG5cdFx0XHRpZiAoICEgd3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uLmd1dGVuYmVyZ19ndWlkZSApIHtcblxuXHRcdFx0XHRub3RpY2VTZXR0aW5ncy5hY3Rpb25zWzBdLnVybCA9IHdwZm9ybXNfZWRpdF9wb3N0X2VkdWNhdGlvbi5ndXRlbmJlcmdfbm90aWNlLnVybDtcblxuXHRcdFx0XHRyZXR1cm4gbm90aWNlU2V0dGluZ3M7XG5cdFx0XHR9XG5cblx0XHRcdGNvbnN0IEd1aWRlID0gd3AuY29tcG9uZW50cy5HdWlkZTtcblx0XHRcdGNvbnN0IHVzZVN0YXRlID0gd3AuZWxlbWVudC51c2VTdGF0ZTtcblx0XHRcdGNvbnN0IHJlZ2lzdGVyUGx1Z2luID0gd3AucGx1Z2lucy5yZWdpc3RlclBsdWdpbjtcblx0XHRcdGNvbnN0IHVucmVnaXN0ZXJQbHVnaW4gPSB3cC5wbHVnaW5zLnVucmVnaXN0ZXJQbHVnaW47XG5cdFx0XHRjb25zdCBHdXRlbmJlcmdUdXRvcmlhbCA9IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdGNvbnN0IFsgaXNPcGVuLCBzZXRJc09wZW4gXSA9IHVzZVN0YXRlKCB0cnVlICk7XG5cblx0XHRcdFx0aWYgKCAhIGlzT3BlbiApIHtcblx0XHRcdFx0XHRyZXR1cm4gbnVsbDtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHJldHVybiAoXG5cdFx0XHRcdFx0Ly8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIHJlYWN0L3JlYWN0LWluLWpzeC1zY29wZVxuXHRcdFx0XHRcdDxHdWlkZVxuXHRcdFx0XHRcdFx0Y2xhc3NOYW1lPVwiZWRpdC1wb3N0LXdlbGNvbWUtZ3VpZGVcIlxuXHRcdFx0XHRcdFx0b25GaW5pc2g9eyAoKSA9PiB7XG5cdFx0XHRcdFx0XHRcdHVucmVnaXN0ZXJQbHVnaW4oIHBsdWdpbk5hbWUgKTtcblx0XHRcdFx0XHRcdFx0c2V0SXNPcGVuKCBmYWxzZSApO1xuXHRcdFx0XHRcdFx0fSB9XG5cdFx0XHRcdFx0XHRwYWdlcz17IGFwcC5nZXRHdWlkZVBhZ2VzKCkgfVxuXHRcdFx0XHRcdC8+XG5cdFx0XHRcdCk7XG5cdFx0XHR9O1xuXG5cdFx0XHRub3RpY2VTZXR0aW5ncy5vbkRpc21pc3MgPSBhcHAudXBkYXRlVXNlck1ldGE7XG5cdFx0XHRub3RpY2VTZXR0aW5ncy5hY3Rpb25zWzBdLm9uQ2xpY2sgPSAoKSA9PiByZWdpc3RlclBsdWdpbiggcGx1Z2luTmFtZSwgeyByZW5kZXI6IEd1dGVuYmVyZ1R1dG9yaWFsIH0gKTtcblxuXHRcdFx0cmV0dXJuIG5vdGljZVNldHRpbmdzO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBHZXQgR3VpZGUgcGFnZXMgaW4gcHJvcGVyIGZvcm1hdC5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHJldHVybnMge0FycmF5fSBHdWlkZSBQYWdlcy5cblx0XHQgKi9cblx0XHRnZXRHdWlkZVBhZ2VzOiBmdW5jdGlvbigpIHtcblxuXHRcdFx0Y29uc3QgcGFnZXMgPSBbXTtcblxuXHRcdFx0d3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uLmd1dGVuYmVyZ19ndWlkZS5mb3JFYWNoKCBmdW5jdGlvbiggcGFnZSApIHtcblx0XHRcdFx0cGFnZXMucHVzaChcblx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHQvKiBlc2xpbnQtZGlzYWJsZSByZWFjdC9yZWFjdC1pbi1qc3gtc2NvcGUgKi9cblx0XHRcdFx0XHRcdGNvbnRlbnQ6IChcblx0XHRcdFx0XHRcdFx0PD5cblx0XHRcdFx0XHRcdFx0XHQ8aDEgY2xhc3NOYW1lPVwiZWRpdC1wb3N0LXdlbGNvbWUtZ3VpZGVfX2hlYWRpbmdcIj57IHBhZ2UudGl0bGUgfTwvaDE+XG5cdFx0XHRcdFx0XHRcdFx0PHAgY2xhc3NOYW1lPVwiZWRpdC1wb3N0LXdlbGNvbWUtZ3VpZGVfX3RleHRcIj57IHBhZ2UuY29udGVudCB9PC9wPlxuXHRcdFx0XHRcdFx0XHQ8Lz5cblx0XHRcdFx0XHRcdCksXG5cdFx0XHRcdFx0XHRpbWFnZTogPGltZyBjbGFzc05hbWU9XCJlZGl0LXBvc3Qtd2VsY29tZS1ndWlkZV9faW1hZ2VcIiBzcmM9eyBwYWdlLmltYWdlIH0gYWx0PXsgcGFnZS50aXRsZSB9IC8+LFxuXHRcdFx0XHRcdFx0LyogZXNsaW50LWVuYWJsZSByZWFjdC9yZWFjdC1pbi1qc3gtc2NvcGUgKi9cblx0XHRcdFx0XHR9XG5cdFx0XHRcdCk7XG5cdFx0XHR9ICk7XG5cblx0XHRcdHJldHVybiBwYWdlcztcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogU2hvdyBub3RpY2UgaWYgdGhlIHBhZ2UgdGl0bGUgbWF0Y2hlcyBzb21lIGtleXdvcmRzIGZvciBDbGFzc2ljIEVkaXRvci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdG1heWJlU2hvd0NsYXNzaWNOb3RpY2U6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRpZiAoIGFwcC5pc05vdGljZVZpc2libGUgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0aWYgKCBhcHAuaXNUaXRsZU1hdGNoS2V5d29yZHMoICQoICcjdGl0bGUnICkudmFsKCkgKSApIHtcblx0XHRcdFx0YXBwLmlzTm90aWNlVmlzaWJsZSA9IHRydWU7XG5cblx0XHRcdFx0JCggJy53cGZvcm1zLWVkaXQtcG9zdC1lZHVjYXRpb24tbm90aWNlJyApLnJlbW92ZUNsYXNzKCAnd3Bmb3Jtcy1oaWRkZW4nICk7XG5cdFx0XHR9XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFNob3cgbm90aWNlIGlmIHRoZSBwYWdlIHRpdGxlIG1hdGNoZXMgc29tZSBrZXl3b3JkcyBmb3IgR3V0ZW5iZXJnIEVkaXRvci5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqL1xuXHRcdG1heWJlU2hvd0d1dGVuYmVyZ05vdGljZTogZnVuY3Rpb24oKSB7XG5cblx0XHRcdGlmICggYXBwLmlzTm90aWNlVmlzaWJsZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCAkcG9zdFRpdGxlID0gYXBwLmlzRnNlKCkgP1xuXHRcdFx0XHQkKCAnaWZyYW1lW25hbWU9XCJlZGl0b3ItY2FudmFzXCJdJyApLmNvbnRlbnRzKCkuZmluZCggJy5lZGl0b3ItcG9zdC10aXRsZV9faW5wdXQnICkgOlxuXHRcdFx0XHQkKCAnLmVkaXRvci1wb3N0LXRpdGxlX19pbnB1dCcgKTtcblx0XHRcdGNvbnN0IHRhZ05hbWUgPSAkcG9zdFRpdGxlLnByb3AoICd0YWdOYW1lJyApO1xuXHRcdFx0Y29uc3QgdGl0bGUgPSB0YWdOYW1lID09PSAnVEVYVEFSRUEnID8gJHBvc3RUaXRsZS52YWwoKSA6ICRwb3N0VGl0bGUudGV4dCgpO1xuXG5cdFx0XHRpZiAoIGFwcC5pc1RpdGxlTWF0Y2hLZXl3b3JkcyggdGl0bGUgKSApIHtcblx0XHRcdFx0YXBwLmlzTm90aWNlVmlzaWJsZSA9IHRydWU7XG5cblx0XHRcdFx0YXBwLnNob3dHdXRlbmJlcmdOb3RpY2UoKTtcblx0XHRcdH1cblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQWRkIG5vdGljZSBjbGFzcyB3aGVuIHRoZSBkaXN0cmFjdGlvbiBtb2RlIGlzIGVuYWJsZWQuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjEuMlxuXHRcdCAqL1xuXHRcdGRpc3RyYWN0aW9uRnJlZU1vZGVUb2dnbGU6IGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRpZiAoICEgYXBwLmlzTm90aWNlVmlzaWJsZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCAkZG9jdW1lbnQgPSAkKCBkb2N1bWVudCApO1xuXHRcdFx0Y29uc3QgaXNEaXN0cmFjdGlvbkZyZWVNb2RlID0gQm9vbGVhbiggJGRvY3VtZW50LmZpbmQoICcuaXMtZGlzdHJhY3Rpb24tZnJlZScgKS5sZW5ndGggKTtcblxuXHRcdFx0aWYgKCAhIGlzRGlzdHJhY3Rpb25GcmVlTW9kZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRjb25zdCBpc05vdGljZUhhc0NsYXNzID0gQm9vbGVhbiggJCggJy53cGZvcm1zLWVkaXQtcG9zdC1lZHVjYXRpb24tbm90aWNlJyApLmxlbmd0aCApO1xuXG5cdFx0XHRpZiAoIGlzTm90aWNlSGFzQ2xhc3MgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0Y29uc3QgJG5vdGljZUJvZHkgPSAkZG9jdW1lbnQuZmluZCggJy53cGZvcm1zLWVkaXQtcG9zdC1lZHVjYXRpb24tbm90aWNlLWJvZHknICk7XG5cdFx0XHRjb25zdCAkbm90aWNlID0gJG5vdGljZUJvZHkuY2xvc2VzdCggJy5jb21wb25lbnRzLW5vdGljZScgKTtcblxuXHRcdFx0JG5vdGljZS5hZGRDbGFzcyggJ3dwZm9ybXMtZWRpdC1wb3N0LWVkdWNhdGlvbi1ub3RpY2UnICk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIERldGVybWluZSBpZiB0aGUgdGl0bGUgbWF0Y2hlcyBrZXl3b3Jkcy5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjguMVxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHRpdGxlVmFsdWUgUGFnZSB0aXRsZSB2YWx1ZS5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm5zIHtib29sZWFufSBUcnVlIGlmIHRoZSB0aXRsZSBtYXRjaGVzIHNvbWUga2V5d29yZHMuXG5cdFx0ICovXG5cdFx0aXNUaXRsZU1hdGNoS2V5d29yZHM6IGZ1bmN0aW9uKCB0aXRsZVZhbHVlICkge1xuXG5cdFx0XHRjb25zdCBleHBlY3RlZFRpdGxlUmVnZXggPSBuZXcgUmVnRXhwKCAvXFxiKGNvbnRhY3R8Zm9ybSlcXGIvaSApO1xuXG5cdFx0XHRyZXR1cm4gZXhwZWN0ZWRUaXRsZVJlZ2V4LnRlc3QoIHRpdGxlVmFsdWUgKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ2xvc2UgYSBub3RpY2UuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS44LjFcblx0XHQgKi9cblx0XHRjbG9zZU5vdGljZTogZnVuY3Rpb24oKSB7XG5cblx0XHRcdCQoIHRoaXMgKS5jbG9zZXN0KCAnLndwZm9ybXMtZWRpdC1wb3N0LWVkdWNhdGlvbi1ub3RpY2UnICkucmVtb3ZlKCk7XG5cblx0XHRcdGFwcC51cGRhdGVVc2VyTWV0YSgpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBVcGRhdGUgdXNlciBtZXRhIGFuZCBkb24ndCBzaG93IHRoZSBub3RpY2UgbmV4dCB0aW1lLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDEuOC4xXG5cdFx0ICovXG5cdFx0dXBkYXRlVXNlck1ldGEoKSB7XG5cblx0XHRcdCQucG9zdChcblx0XHRcdFx0d3Bmb3Jtc19lZGl0X3Bvc3RfZWR1Y2F0aW9uLmFqYXhfdXJsLFxuXHRcdFx0XHR7XG5cdFx0XHRcdFx0YWN0aW9uOiAnd3Bmb3Jtc19lZHVjYXRpb25fZGlzbWlzcycsXG5cdFx0XHRcdFx0bm9uY2U6IHdwZm9ybXNfZWRpdF9wb3N0X2VkdWNhdGlvbi5lZHVjYXRpb25fbm9uY2UsXG5cdFx0XHRcdFx0c2VjdGlvbjogJ2VkaXQtcG9zdC1ub3RpY2UnLFxuXHRcdFx0XHR9XG5cdFx0XHQpO1xuXHRcdH0sXG5cdH07XG5cblx0cmV0dXJuIGFwcDtcblxufSggZG9jdW1lbnQsIHdpbmRvdywgalF1ZXJ5ICkgKTtcblxuV1BGb3Jtc0VkaXRQb3N0RWR1Y2F0aW9uLmluaXQoKTtcbiJdLCJtYXBwaW5ncyI6IkFBQUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxZQUFZOztBQUFDLFNBQUFBLGVBQUFDLEdBQUEsRUFBQUMsQ0FBQSxXQUFBQyxlQUFBLENBQUFGLEdBQUEsS0FBQUcscUJBQUEsQ0FBQUgsR0FBQSxFQUFBQyxDQUFBLEtBQUFHLDJCQUFBLENBQUFKLEdBQUEsRUFBQUMsQ0FBQSxLQUFBSSxnQkFBQTtBQUFBLFNBQUFBLGlCQUFBLGNBQUFDLFNBQUE7QUFBQSxTQUFBRiw0QkFBQUcsQ0FBQSxFQUFBQyxNQUFBLFNBQUFELENBQUEscUJBQUFBLENBQUEsc0JBQUFFLGlCQUFBLENBQUFGLENBQUEsRUFBQUMsTUFBQSxPQUFBRSxDQUFBLEdBQUFDLE1BQUEsQ0FBQUMsU0FBQSxDQUFBQyxRQUFBLENBQUFDLElBQUEsQ0FBQVAsQ0FBQSxFQUFBUSxLQUFBLGFBQUFMLENBQUEsaUJBQUFILENBQUEsQ0FBQVMsV0FBQSxFQUFBTixDQUFBLEdBQUFILENBQUEsQ0FBQVMsV0FBQSxDQUFBQyxJQUFBLE1BQUFQLENBQUEsY0FBQUEsQ0FBQSxtQkFBQVEsS0FBQSxDQUFBQyxJQUFBLENBQUFaLENBQUEsT0FBQUcsQ0FBQSwrREFBQVUsSUFBQSxDQUFBVixDQUFBLFVBQUFELGlCQUFBLENBQUFGLENBQUEsRUFBQUMsTUFBQTtBQUFBLFNBQUFDLGtCQUFBVCxHQUFBLEVBQUFxQixHQUFBLFFBQUFBLEdBQUEsWUFBQUEsR0FBQSxHQUFBckIsR0FBQSxD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
},{}]},{},[1])