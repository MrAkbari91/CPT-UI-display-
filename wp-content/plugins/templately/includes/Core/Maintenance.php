<?php

namespace Templately\Core;

use Templately\Admin\Roles;

class Maintenance {
	/**
	 * Init Maintenance
	 *
	 * @return void
	 * @since 2.0.1
	 */
	public static function init() {
		register_activation_hook( TEMPLATELY_PLUGIN_BASENAME, [ __CLASS__, 'activation' ] );
		register_uninstall_hook( TEMPLATELY_PLUGIN_BASENAME, [ __CLASS__, 'uninstall' ] );
		register_deactivation_hook( TEMPLATELY_PLUGIN_BASENAME, [ __CLASS__, 'deactivation' ] );
		add_action( 'admin_init', [ __CLASS__, 'maybe_redirect_templately' ] );
	}

	/**
	 * Runs on activation
	 *
	 * @return void
	 * @since 2.0.1
	 */
	public static function activation( $network_wide ) {
		// Initialize Roles
		( new Roles() )->setup();

		if ( wp_doing_ajax() ) {
			return;
		}

        if ( is_multisite() && $network_wide ) {
			return;
        }

        set_transient( 'templately_activation_redirect', true, MINUTE_IN_SECONDS );
	}

	/**
	 * Runs on activation
	 *
	 * @return void
	 * @since 2.0.1
	 */
	public static function deactivation($network_wide) {
		// De-initialize Roles
		( new Roles() )->setup( true );
		if ( is_multisite() && $network_wide ) {
			return;
		}

		set_transient( 'templately_activation_redirect', true, MINUTE_IN_SECONDS );
	}


	/**
	 * Runs on uninstallation.
	 *
	 * @return void
	 * @since 2.0.1
	 */
	public static function uninstall() {

	}

	/**
	 * Redirect on Active
	 */
	public static function maybe_redirect_templately() {
		if ( ! get_transient( 'templately_activation_redirect' ) ) {
			return;
		}
		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'templately_activation_redirect' );
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Safe Redirect to Templately Page
		wp_safe_redirect( admin_url( 'admin.php?page=templately&path=elementor/packs' ) );
		exit;
	}
}
