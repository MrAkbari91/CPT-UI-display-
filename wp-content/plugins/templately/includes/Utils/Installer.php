<?php

namespace Templately\Utils;

use Automatic_Upgrader_Skin;
use Plugin_Upgrader;
use Theme_Upgrader;
use WP_Ajax_Upgrader_Skin;
use WP_Filesystem_Base;
use function activate_plugin;
use function current_user_can;
use function install_plugin_install_status;
use function is_plugin_inactive;
use function is_wp_error;
use function plugins_api;
use function sanitize_key;
use function wp_unslash;

class Installer extends Base {
	/**
	 * Some process take long time to execute
	 * for that need to raise the limit.
	 */
	public static function raise_limits() {
		wp_raise_memory_limit( 'admin' );
		if ( wp_is_ini_value_changeable( 'max_execution_time' ) ) {
			@ini_set( 'max_execution_time', 0 );
		}
		@ set_time_limit( 0 );
	}

	public function install( $plugin ): array {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$response = [ 'success' => false ];

		$_plugins     = Helper::get_plugins();
		$is_installed = isset( $_plugins[ $plugin['plugin_file'] ] );

		if ( isset( $plugin['is_pro'] ) && $plugin['is_pro'] ) {
			if ( ! $is_installed ) {
				$response['code']    = 'pro_plugin';
				$response['message'] = 'Pro Plugin';
			}
		}

		if ( ! $is_installed ) {
			/**
			 * @var array|object $api
			 */
			$api = plugins_api( 'plugin_information', [
					'slug'   => sanitize_key( wp_unslash( $plugin['slug'] ) ),
					'fields' => [
						'sections' => false,
					],
				] );

			if ( is_wp_error( $api ) ) {
				$response['message'] = $api->get_error_message();

				return $response;
			}

			$response['name'] = $api->name;

			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				$response['code']    = $result->get_error_code();
				$response['message'] = $result->get_error_message();

				return $response;
			} elseif ( is_wp_error( $skin->result ) ) {
				$response['code']    = $skin->result->get_error_code();
				$response['message'] = $skin->result->get_error_message();

				return $response;
			} elseif ( $skin->get_errors()->has_errors() ) {
				$response['message'] = $skin->get_error_messages();

				return $response;
			} elseif ( is_null( $result ) ) {
				global $wp_filesystem;
				$response['code']    = 'unable_to_connect_to_filesystem';
				$response['message'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
					$response['message'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				return $response;
			}

			$install_status        = install_plugin_install_status( $api );
			$plugin['plugin_file'] = $install_status['file'];
		}

		$activate_status = $this->activate_plugin( $plugin['plugin_file'] );

		if ( is_wp_error( $activate_status ) ) {
			$response['message'] = $activate_status->get_error_message();
		}

		if ( $activate_status && ! is_wp_error( $activate_status ) ) {
			$response['success'] = true;
		}

		$response['slug'] = $plugin['slug'];

		return $response;
	}

	public function install_and_activate_theme($theme_slug) {
		require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
		require_once(ABSPATH . 'wp-admin/includes/theme.php');
		require_once(ABSPATH . 'wp-admin/includes/theme-install.php');

		$response = ['success' => false];

		if (!function_exists('themes_api')) {
			$response['message'] = 'Function themes_api does not exist';
			return $response;
		}

		$api = themes_api('theme_information', [
			'slug' => sanitize_key($theme_slug),
			'fields' => [
				'sections' => false,
			],
		]);

		if (is_wp_error($api)) {
			$response['message'] = $api->get_error_message();
			return $response;
		}

		if (!wp_get_theme($theme_slug)->exists()) {
			$upgrader = new Theme_Upgrader(new Automatic_Upgrader_Skin());
			$result = $upgrader->install($api->download_link);

			if (is_wp_error($result)) {
				$response['message'] = $result->get_error_message();
				return $response;
			}

			$response['install'] = 'success';
		}

		switch_theme($theme_slug);

		if ($theme_slug == get_option('stylesheet')) {
			$response['success'] = true;
		} else {
			$response['message'] = 'Failed to activate theme';
		}

		return $response;
	}

	private function activate_plugin( $file ) {
		if ( is_plugin_active( $file ) ) {
			return true;
		}

		if ( current_user_can( 'activate_plugin', $file ) && is_plugin_inactive( $file ) ) {
			$result = activate_plugin( $file );
			if ( is_wp_error( $result ) ) {
				return $result;
			} else {
				return true;
			}
		}

		return false;
	}

}