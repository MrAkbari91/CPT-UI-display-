<?php

namespace Templately\API;

use stdClass;
use Templately\Utils\Helper;
use Templately\Utils\Database;
use Templately\Utils\Installer;
use WP_REST_Request;

use function current_user_can;


class Dependencies extends API {

	private $endpoint = 'dependencies';
	public $query = 'dependencies{ id, name, icon, plugin_file, plugin_original_slug, is_pro, link }';

	public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;
		$_route = $request->get_route();

		if( $_route === '/templately/v1/dependencies/install' && ! current_user_can( 'install_plugins' ) ) {
			return $this->error('invalid_permission', __( 'Sorry, you do not have permission to install a plugin.', 'templately' ), 'dependencies/install', 403 );
		}

		return true;
	}

	public function register_routes() {
		$this->get( $this->endpoint, [ $this, 'get_dependencies' ] );
		$this->post( $this->endpoint . '/check', [$this, 'check_dependencies'] );
		$this->post( $this->endpoint . '/install', [$this, 'install_dependencies'] );
	}

	public function get_dependencies() {
		$dependencies = Database::get_transient( $this->endpoint );

		if( $dependencies ) {
			return $this->success( $dependencies );
		}

		$response = $this->http()->query(
			'dependencies',
			'id, name, icon, is_pro, platforms{ id, name, file_type, icon }'
		)->post();

		if( ! is_wp_error( $response ) ) {
			$_dependencies = [];
			if( ! empty( $response ) ) {
				$_dependencies[ 'unknown' ] = [];
				foreach( $response as $dependency ) {
					if( ! empty( $dependency['platforms'] ) ) {
						foreach( $dependency['platforms'] as $platform ) {
							if( ! isset( $_dependencies[ $platform['name'] ] ) ) {
								$_dependencies[ $platform['name'] ] = [];
							}
							$_dependencies[ $platform['name'] ][] = $dependency;
						}
					} else {
						$_dependencies[ 'unknown' ][] = $dependency;
					}
				}
			}

			Database::set_transient( $this->endpoint, $_dependencies );
			return $_dependencies;
		}

		return $response;
	}

	public function check_dependencies(){
		$dependencies = $this->get_param( 'dependencies', '', '' );
		$platform = $this->get_param( 'platform', 'elementor' );

		$_inactive_plugins = [];
		$_plugins = Helper::get_plugins();

		if( ! Helper::is_plugin_active( 'elementor/elementor.php' ) && $platform === 'elementor' ) {
			$elementor_plugin              = new stdClass();
			$elementor_plugin->name        = __( 'Elementor', 'templately' );
			$elementor_plugin->plugin_file = 'elementor/elementor.php';
			$elementor_plugin->slug        = 'elementor';
			$elementor_plugin->is_pro      = false;

			$_inactive_plugins[] = $elementor_plugin;
		}

		if ( ! empty( $dependencies ) && is_array( $dependencies ) ) {
			foreach ( $dependencies as $dependency ) {
				if( ! is_array( $dependency ) || ! isset( $dependency['plugin_file'] ) ) {
					continue;
				}

				$dependency  = ( object ) $dependency;
				if ( is_null( $dependency->plugin_file ) || Helper::is_plugin_active( $dependency->plugin_file ) ) {
					continue;
				}

				if( isset( $dependency->plugin_original_slug ) ) {
					$dependency->slug = $dependency->plugin_original_slug;
					unset( $dependency->plugin_original_slug );
				}

				if ( $dependency->is_pro ) {
					if ( isset( $_plugins[ $dependency->plugin_file ] ) ) {
						unset( $dependency->is_pro );
						$dependency->message = __( 'You have the plugin installed.', 'templately' );
					}
				}
				$_inactive_plugins[] = $dependency;
			}
		}

		return [
			'dependencies' => $_inactive_plugins
		];
	}

	public function install_dependencies(){
		$requirements = $this->get_param( 'requirement', [], '' );
		if( empty( $requirements ) ) {
			return $this->error(
				'invalid_requirements',
				__('You have supplied an invalid requirements. Please reload the page and try again.'),
				'/install',
				400
			);
		}
		return Installer::get_instance()->install( $requirements );
	}
}