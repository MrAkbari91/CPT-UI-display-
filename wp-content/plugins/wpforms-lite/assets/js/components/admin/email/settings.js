/* global Choices */
/**
 * Script for manipulating DOM events in the "Email" settings page.
 * This script will be accessible in the "WPForms" → "Settings" → "Email" page.
 *
 * @since 1.8.5
 */

const WPFormsEmailSettings = window.WPFormsEmailSettings || ( function( document, window, $ ) {
	/**
	 * Elements holder.
	 *
	 * @since 1.8.5
	 *
	 * @type {Object}
	 */
	const el = {};

	/**
	 * Public functions and properties.
	 *
	 * @since 1.8.5
	 */
	const app = {

		/**
		 * Start the engine.
		 *
		 * @since 1.8.5
		 */
		init() {
			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 1.8.5
		 */
		ready() {
			app.setup();
			app.bindEvents();
			app.relocateImageSize();
		},

		/**
		 * Setup. Prepare some variables.
		 *
		 * @since 1.8.5
		 */
		setup() {
			// Cache DOM elements.
			el.$wrapper = $( '.wpforms-admin-settings-email' );
			el.$headerImage = $( '.wpforms-email-header-image' );
			el.$imageSize = $( '.wpforms-email-header-image-size' );
			el.$colorScheme = $( '#wpforms-setting-row-email-color-scheme' );
			el.$typography = $( '#wpforms-setting-row-email-typography' );
		},

		/**
		 * Bind events.
		 *
		 * @since 1.8.5
		 */
		bindEvents() {
			el.$wrapper
				.on( 'change', '.wpforms-email-template input[type="radio"]', app.handleOnUpdateTemplate )
				.on( 'click', '.wpforms-setting-remove-image', app.handleOnRemoveHeaderImage );
		},

		/**
		 * Callback for template change.
		 *
		 * @since 1.8.5
		 *
		 * @param {Object} event An event which takes place in the DOM.
		 */
		handleOnUpdateTemplate( event ) {
			const selected = $( event.currentTarget ).val();
			const $hideForNone = el.$wrapper.find( '.hide-for-template-none' );
			const $imageSizeChoices = el.$headerImage.find( '.choices' );
			const $backgroundControl = el.$wrapper.find( '.email-background-color' );
			const $legacyNotice = el.$wrapper.find( '.wpforms-email-legacy-notice' );

			const isPro = el.$wrapper.find( '.education-modal' ).length === 0;
			const isNone = selected === 'none';
			const isDefault = selected === 'default';

			$hideForNone.toggle( ! isNone );
			$imageSizeChoices.toggle( ! isDefault );
			$legacyNotice.toggle( isDefault );
			$backgroundControl.toggle( ( isDefault || ! isPro ) && ! isNone );

			el.$colorScheme.toggleClass( 'legacy-template', isDefault );
			el.$typography.toggleClass( 'legacy-template', isDefault );
		},

		/**
		 * Callback for "Remove Image" button click.
		 *
		 * @since 1.8.5
		 */
		handleOnRemoveHeaderImage() {
			$( this ).closest( '.wpforms-setting-row' ).removeClass( 'has-external-image-url' );
		},

		/**
		 * Callback for the image size select input change.
		 *
		 * @since 1.8.5
		 */
		handleOnUpdateImageSize() {
			// Get the selected value.
			const value = $( this ).val();

			// Remove the previous image size class.
			el.$headerImage.removeClass( ( index, className ) => ( className.match( /has-image-size-\w+/g ) || [] ).join( ' ' ) );
			// Add the new image size class.
			el.$headerImage.addClass( `has-image-size-${ value }` );
		},

		/**
		 * Relocate image size select input for styling purposes.
		 *
		 * @since 1.8.5
		 */
		relocateImageSize() {
			const $removeImage = $( '.wpforms-setting-remove-image' );

			// Bail if there is no "Remove Image" button.
			if ( $removeImage.length === 0 ) {
				return;
			}

			// Move the select input before the "Remove Image" button.
			const $select = el.$imageSize.find( 'select' );
			const selectHtml = $select.get( 0 ).outerHTML;
			el.$headerImage.find( '.wpforms-setting-remove-image' ).before( selectHtml );
			$select.remove();

			try {
				// Cache the new select input.
				const $newSelect = el.$headerImage.find( 'select' );
				// Add the image size class. Note that the default value is 140.
				el.$headerImage.addClass( `has-image-size-${ $newSelect.val() || 'medium' }` );
				// Bind the change event, and update the image size class.
				$newSelect.on( 'change', app.handleOnUpdateImageSize );
				// Initialize Choices.
				new Choices( el.$headerImage.find( 'select' ).get( 0 ), {
					searchEnabled: false,
					shouldSort: false,
					itemSelectText: '',
				} );

				// Disable some settings if default template is selected.
				if ( el.$wrapper.find( '.wpforms-card-image input:checked' ).val() === 'default' ) {
					el.$headerImage.find( '.choices' ).toggle();
				}
			} catch ( e ) {
				// Do nothing.
			}
		},
	};

	// Provide access to public functions/properties.
	return app;
}( document, window, jQuery ) );

// Initialize.
WPFormsEmailSettings.init();
