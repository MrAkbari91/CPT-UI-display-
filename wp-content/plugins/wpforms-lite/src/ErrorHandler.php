<?php

namespace WPForms;

/**
 * The error handler to suppress deprecated messages from vendor folders.
 */

/**
 * Class ErrorHandler.
 *
 * @since 1.8.5
 */
class ErrorHandler {

	/**
	 * Directories where can deprecation error occurs.
	 *
	 * @since 1.8.5
	 *
	 * @var string[]
	 */
	private $dirs;

	/**
	 * Init class.
	 *
	 * @since 1.8.5
	 */
	public function init() {

		$this->dirs = [
			WPFORMS_PLUGIN_DIR . 'vendor/',
			WPFORMS_PLUGIN_DIR . 'vendor_prefixed/',
			WP_PLUGIN_DIR . '/wpforms-activecampaign/vendor/',
			WP_PLUGIN_DIR . '/wpforms-authorize-net/vendor/',
			WP_PLUGIN_DIR . '/wpforms-aweber/vendor/',
			WP_PLUGIN_DIR . '/wpforms-calculations/vendor/',
			WP_PLUGIN_DIR . '/wpforms-campaign-monitor/vendor/',
			WP_PLUGIN_DIR . '/wpforms-captcha/vendor/',
			WP_PLUGIN_DIR . '/wpforms-clear-cache/vendor/',
			WP_PLUGIN_DIR . '/wpforms-conversational-forms/vendor/',
			WP_PLUGIN_DIR . '/wpforms-coupons/vendor/',
			WP_PLUGIN_DIR . '/wpforms-drip/vendor/',
			WP_PLUGIN_DIR . '/wpforms-e2e-helpers/vendor/',
			WP_PLUGIN_DIR . '/wpforms-form-abandonment/vendor/',
			WP_PLUGIN_DIR . '/wpforms-form-locker/vendor/',
			WP_PLUGIN_DIR . '/wpforms-form-pages/vendor/',
			WP_PLUGIN_DIR . '/wpforms-geolocation/vendor/',
			WP_PLUGIN_DIR . '/wpforms-getresponse/vendor/',
			WP_PLUGIN_DIR . '/wpforms-google-sheets/vendor/',
			WP_PLUGIN_DIR . '/wpforms-hubspot/vendor/',
			WP_PLUGIN_DIR . '/wpforms-lead-forms/vendor/',
			WP_PLUGIN_DIR . '/wpforms-mailchimp/vendor/',
			WP_PLUGIN_DIR . '/wpforms-mailerlite/vendor/',
			WP_PLUGIN_DIR . '/wpforms-offline-forms/vendor/',
			WP_PLUGIN_DIR . '/wpforms-paypal-commerce/vendor/',
			WP_PLUGIN_DIR . '/wpforms-paypal-standard/vendor/',
			WP_PLUGIN_DIR . '/wpforms-post-submissions/vendor/',
			WP_PLUGIN_DIR . '/wpforms-salesforce/vendor/',
			WP_PLUGIN_DIR . '/wpforms-save-resume/vendor/',
			WP_PLUGIN_DIR . '/wpforms-sendinblue/vendor/',
			WP_PLUGIN_DIR . '/wpforms-signatures/vendor/',
			WP_PLUGIN_DIR . '/wpforms-square/vendor/',
			WP_PLUGIN_DIR . '/wpforms-stripe/vendor/',
			WP_PLUGIN_DIR . '/wpforms-surveys-polls/vendor/',
			WP_PLUGIN_DIR . '/wpforms-user-journey/vendor/',
			WP_PLUGIN_DIR . '/wpforms-user-registration/vendor/',
			WP_PLUGIN_DIR . '/wpforms-webhooks/vendor/',
			WP_PLUGIN_DIR . '/wpforms-zapier/vendor/',
		];

		$this->dirs = array_map(
			static function ( $dir ) {

				return str_replace( DIRECTORY_SEPARATOR, '/', $dir );
			},
			$this->dirs
		);

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler
		set_error_handler( [ $this, 'error_handler' ] );
	}

	/**
	 * Error handler.
	 *
	 * @since 1.8.5
	 *
	 * @param int    $level   Error level.
	 * @param string $message Error message.
	 * @param string $file    File produced an error.
	 * @param int    $line    Line number.
	 *
	 * @return bool
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function error_handler( int $level, string $message, string $file, int $line ): bool { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		if ( $level !== E_DEPRECATED ) {
			// Use standard error handler.
			return false;
		}

		$file = str_replace( DIRECTORY_SEPARATOR, '/', $file );

		foreach ( $this->dirs as $dir ) {
			if ( false !== strpos( $file, $dir ) ) {
				// Suppress deprecated errors from this directory.
				return true;
			}
		}

		// Use standard error handler.
		return false;
	}
}
