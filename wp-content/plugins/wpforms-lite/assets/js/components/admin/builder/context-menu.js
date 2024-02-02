// noinspection ES6ConvertVarToLetConst
/**
 * Context menu module.
 *
 * @since 1.8.6
 */

var WPForms = window.WPForms || {}; // eslint-disable-line no-var

WPForms.Admin = WPForms.Admin || {};
WPForms.Admin.Builder = WPForms.Admin.Builder || {};

WPForms.Admin.Builder.ContextMenu = WPForms.Admin.Builder.ContextMenu || ( function( document, window, $ ) {
	/**
	 * Elements holder.
	 *
	 * @since 1.8.6
	 *
	 * @type {Object}
	 */
	const el = {};

	/**
	 * Public functions and properties.
	 *
	 * @since 1.8.6
	 *
	 * @type {Object}
	 */
	const app = {

		/**
		 * CSS selectors.
		 *
		 * @since 1.8.6
		 *
		 * @type {Object}
		 */
		selectors: {
			contextMenu: '.wpforms-context-menu',
			contextMenuItem: '.wpforms-context-menu-list-item',
			contextMenuSelectiveItem: '.wpforms-context-menu-list-item-selective',
			contextMenuDivider: '.wpforms-context-menu-list-divider',
			builder: '#wpforms-builder',
		},

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 1.8.6
		 */
		init() {
			$( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.8.6
		 */
		ready() {
			app.setup();
			app.events();
		},

		/**
		 * Setup. Prepare some variables.
		 *
		 * @since 1.8.6
		 */
		setup() {
			// Cache DOM elements.
			el.$document = $( document );
			el.$contextMenu = $( app.selectors.contextMenu );
			el.$contextMenuItem = $( app.selectors.contextMenuItem );
			el.$contextMenuSelectiveItem = $( app.selectors.contextMenuSelectiveItem );
			el.$contextMenuDivider = $( app.selectors.contextMenuDivider );
			el.$builder = $( app.selectors.builder );
		},

		/**
		 * Bind events.
		 *
		 * @since 1.8.6
		 */
		events() {
			el.$document.on( 'contextmenu', function( e ) {
				const $field = $( e.target ).closest( '.wpforms-field' );

				if ( $( e.target ).closest( app.selectors.contextMenu ).length || ! $field.length ) {
					return;
				}

				e.preventDefault();

				app.hideMenu();

				setTimeout( function() {
					app.checkMenuItemsVisibility( $field );
					app.checkDividerVisibility();
					app.menuPositioning( e );
					app.menuItemClickAction( $field );
					app.checkSelectiveMenuItemsState( $field );
				}, 150 );
			} );

			el.$document.on( 'click', app.hideMenuOnClick );
			el.$builder.on( 'wpformsFieldTabToggle', app.hideMenuOnClick );
		},

		/**
		 * Menu item click action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 */
		menuItemClickAction( $field ) {
			const fieldId = $field.data( 'field-id' );

			el.$contextMenuItem.off( 'click' ).on( 'click', function() {
				const $item = $( this );

				if ( $item.hasClass( 'wpforms-context-menu-list-item-has-child' ) ) {
					return;
				}

				const action = $item.data( 'action' );
				const actionHandlers = {
					edit: () => app.handleEditAction( $field, fieldId ),
					duplicate: () => app.handleDuplicateAction( $field ),
					delete: () => app.handleDeleteAction( $field ),
					required: () => app.handleRequiredAction( $item, fieldId ),
					label: () => app.handleLabelAction( $item, fieldId ),
					'smart-logic': () => app.handleSmartLogicAction( $field, fieldId ),
					'field-size': () => app.handleSizeAction( $item, fieldId ),
				};

				const handler = actionHandlers[ action ];

				if ( handler ) {
					handler();
				}

				app.hideMenu();
			} );
		},

		/**
		 * Handle edit action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field  Field object.
		 * @param {string} fieldId Field ID.
		 */
		handleEditAction( $field, fieldId ) {
			$field.trigger( 'click' );
			$( `#wpforms-field-option-basic-${ fieldId } .wpforms-field-option-group-toggle` ).trigger( 'click' );
		},

		/**
		 * Handle duplicate action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 */
		handleDuplicateAction( $field ) {
			$field.find( '.wpforms-field-duplicate' ).first().trigger( 'click' );
		},

		/**
		 * Handle delete action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 */
		handleDeleteAction( $field ) {
			$field.find( '.wpforms-field-delete' ).first().trigger( 'click' );
		},

		/**
		 * Handle required action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $item   Menu item object.
		 * @param {string} fieldId Field ID.
		 */
		handleRequiredAction( $item, fieldId ) {
			$( `#wpforms-field-option-${ fieldId }-required` ).trigger( 'click' );
			const state = app.checkRequiredState( fieldId ) ? 'active' : 'inactive';
			app.toggleItemText( $item, state );
		},

		/**
		 * Handle label action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $item   Menu item object.
		 * @param {string} fieldId Field ID.
		 */
		handleLabelAction( $item, fieldId ) {
			$( `#wpforms-field-option-${ fieldId }-label_hide` ).trigger( 'click' );
			const state = app.checkLabelState( fieldId ) ? 'active' : 'inactive';
			app.toggleItemText( $item, state );
		},

		/**
		 * Handle smart logic action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field  Field object.
		 * @param {string} fieldId Field ID.
		 */
		handleSmartLogicAction( $field, fieldId ) {
			$field.trigger( 'click' );
			$( `#wpforms-field-option-conditionals-${ fieldId } .wpforms-field-option-group-toggle` ).trigger( 'click' );
			$( `#wpforms-field-option-${ fieldId } .wpforms-field-option-group-conditionals .education-modal` ).trigger( 'click' );
		},

		/**
		 * Handle size action.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $item   Menu item object.
		 * @param {string} fieldId Field ID.
		 */
		handleSizeAction( $item, fieldId ) {
			const value = $item.data( 'value' );

			$( `#wpforms-field-option-${ fieldId }-size` ).val( value ).trigger( 'change' );
			$item.addClass( 'wpforms-context-menu-list-item-active' ).siblings().removeClass( 'wpforms-context-menu-list-item-active' );
		},

		/**
		 * Toggle item text.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $item Menu item object.
		 * @param {string} state State.
		 */
		toggleItemText( $item, state ) {
			const $text = $item.find( '.wpforms-context-menu-list-item-text' );
			const activeText = $text.data( 'active-text' );
			const inactiveText = $text.data( 'inactive-text' ) || $text.text();

			if ( ! activeText ) {
				return;
			}

			$text.data( 'inactive-text', inactiveText );
			$text.text( state === 'active' ? activeText : inactiveText );
		},

		/**
		 * Check selective menu items state.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 */
		checkSelectiveMenuItemsState( $field ) {
			const fieldId = $field.data( 'field-id' );

			el.$contextMenuSelectiveItem.each( function() {
				const $item = $( this );
				const action = $item.data( 'action' );
				const value = $item.data( 'value' );

				const shouldChangeStateHandlers = {
					required: () => app.checkRequiredState( fieldId ),
					label: () => app.checkLabelState( fieldId ),
					'field-size': () => app.checkFieldSizeState( fieldId, value ),
				};

				const handler = shouldChangeStateHandlers[ action ];

				if ( handler() ) {
					$item.addClass( 'wpforms-context-menu-list-item-active' );
					app.toggleItemText( $item, 'active' );
				} else {
					$item.removeClass( 'wpforms-context-menu-list-item-active' );
					app.toggleItemText( $item, 'inactive' );
				}
			} );
		},

		/**
		 * Check the required state.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 *
		 * @return {boolean} True if option checked.
		 */
		checkRequiredState( fieldId ) {
			return $( `#wpforms-field-option-${ fieldId }-required[type="checkbox"]` ).is( ':checked' );
		},

		/**
		 * Check label state.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 *
		 * @return {boolean} True if option checked.
		 */
		checkLabelState( fieldId ) {
			return $( `#wpforms-field-option-${ fieldId }-label_hide[type="checkbox"]` ).is( ':checked' );
		},

		/**
		 * Check field size state.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 * @param {string} value   Value.
		 *
		 * @return {boolean} True if value equals.
		 */
		checkFieldSizeState( fieldId, value ) {
			return $( `#wpforms-field-option-${ fieldId }-size` ).val() === value;
		},

		/**
		 * Menu positioning.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} e Event object.
		 */
		menuPositioning( e ) {
			const menuWidth = el.$contextMenu.width();
			const menuHeight = el.$contextMenu.height();
			const windowWidth = window.innerWidth;
			const windowHeight = window.innerHeight;

			el.$contextMenu.removeClass( 'wpforms-context-menu-selective-left' );

			let topPosition = e.pageY;
			let leftPosition = e.pageX;

			if ( e.pageY + menuHeight > windowHeight ) {
				topPosition = windowHeight - menuHeight - 15;
			}

			if ( e.pageX + menuWidth > windowWidth ) {
				leftPosition = windowWidth - menuWidth - 15;
				el.$contextMenu.addClass( 'wpforms-context-menu-selective-left' );
			}

			el.$contextMenu.css( {
				top: topPosition + 'px',
				left: leftPosition + 'px',
			} );

			el.$contextMenu.fadeIn( 150 );
		},

		/**
		 * Check menu items visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field jQuery object.
		 */
		checkMenuItemsVisibility( $field ) {
			const fieldId = $field.data( 'field-id' );

			const shouldHideHandlers = {
				edit: () => false,
				duplicate: () => app.shouldHideDuplicate( $field ),
				delete: () => app.shouldHideDelete( $field ),
				required: () => app.shouldHideRequired( fieldId ),
				label: () => app.shouldHideLabel( fieldId ),
				'smart-logic': () => app.shouldHideSmartLogic( fieldId ),
				'field-size': () => app.shouldHideFieldSize( fieldId, $field ),
			};

			el.$contextMenuItem.each( function() {
				const $item = $( this );
				const action = $item.data( 'action' );
				const handler = shouldHideHandlers[ action ];

				if ( handler() ) {
					$item.hide();
				}
			} );
		},

		/**
		 * Check duplicate visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideDuplicate( $field ) {
			const $duplicate = $field.find( '.wpforms-field-duplicate' );

			return $duplicate.length === 0 || $duplicate.css( 'display' ) === 'none';
		},

		/**
		 * Check delete visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} $field Field object.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideDelete( $field ) {
			const $delete = $field.find( '.wpforms-field-delete' );

			return $delete.length === 0 || $delete.css( 'display' ) === 'none';
		},

		/**
		 * Check required visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideRequired( fieldId ) {
			return $( `#wpforms-field-option-${ fieldId }-required[type="checkbox"]` ).length === 0;
		},

		/**
		 * Check label visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideLabel( fieldId ) {
			const $label = $( `#wpforms-field-option-${ fieldId }-label_hide[type="checkbox"]` );

			return $label.length === 0 || $label.parents( '.wpforms-field-option-row' ).hasClass( 'wpforms-disabled' );
		},

		/**
		 * Check field size visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 * @param {Object} $field  Field object.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideFieldSize( fieldId, $field ) {
			const isFieldInColumn = $field.closest( '.wpforms-field-layout' ).length > 0;
			const $size = $( `#wpforms-field-option-${ fieldId }-size` );

			return $size.length === 0 || isFieldInColumn || $size.parent().hasClass( 'wpforms-hidden' );
		},

		/**
		 * Check smart logic visibility.
		 *
		 * @since 1.8.6
		 *
		 * @param {string} fieldId Field ID.
		 *
		 * @return {boolean} True if should hide.
		 */
		shouldHideSmartLogic( fieldId ) {
			return $( `#wpforms-field-option-conditionals-${ fieldId }` ).length === 0 && $( `#wpforms-field-option-${ fieldId } .wpforms-field-option-group-conditionals .education-modal` ).length === 0;
		},

		/**
		 * Check divider visibility.
		 *
		 * @since 1.8.6
		 */
		checkDividerVisibility() {
			el.$contextMenuDivider.each( function() {
				const $divider = $( this );
				const visibility = $divider.data( 'visibility' );

				let shouldHide = true;

				visibility.split( ',' ).forEach( function( item ) {
					if ( $( '.wpforms-context-menu-list-item[data-action="' + item.trim() + '"]' ).css( 'display' ) !== 'none' ) {
						shouldHide = false;
					}
				} );

				if ( shouldHide ) {
					$divider.hide();
				} else {
					$divider.show();
				}
			} );
		},

		/**
		 * Hide menu.
		 *
		 * @since 1.8.6
		 */
		hideMenu() {
			el.$contextMenu.fadeOut( 150 );
			setTimeout( function() {
				el.$contextMenuItem.show();
			}, 150 );
		},

		/**
		 * Hide menu on click.
		 *
		 * @since 1.8.6
		 *
		 * @param {Object} e Event object.
		 */
		hideMenuOnClick( e ) {
			if ( $( e.target ).closest( app.selectors.contextMenu ).length ) {
				return;
			}

			app.hideMenu();
		},
	};

	// Provide access to public functions/properties.
	return app;
}( document, window, jQuery ) );

// Initialize.
WPForms.Admin.Builder.ContextMenu.init();
