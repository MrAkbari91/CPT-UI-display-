<?php
namespace Templately\Utils;

class Enqueue extends Base {
    private $plugin_url;
    private $plugin_path;
    private $version;

    public function __construct( $plugin_url, $plugin_path, $version ){
        $this->plugin_url  = $plugin_url;
        $this->plugin_path = $plugin_path;
        $this->version     = $version;
    }

	public function enqueue( $handle, $filename, $dependencies = [], $args = null ) {
		$config = $this->asset_config( $filename, $dependencies, $args );
		$this->call_wp_func( 'wp_enqueue', $handle, $config );
	}

	public function register( $handle, $filename, $dependencies = [], $args = null ) {
		$config = $this->asset_config( $filename, $dependencies, $args );
		$this->call_wp_func( 'wp_register', $handle, $config );
	}

    public function localize( $handle, $name, $args ){
        wp_localize_script( $handle, $name, $args );
    }

    private function call_wp_func( $action, $handle, $config ) {
		call_user_func( $action . '_' . $config['type'], $handle, $config['url'], $config['dependencies'], $config['version'], $config['args'] );

		if ( 'script' === $config['type'] && in_array( 'wp-i18n', $config['dependencies'], true ) ) {
			wp_set_script_translations( $handle, 'templately' );
		}
	}

    public function asset_config( $filename, $dependencies = [], $args = null ) {
		$is_js             = preg_match( '/\.js$/', $filename );
		$basename          = preg_replace( '/\.\w+$/', '', $filename );
		$url               = $this->asset_url( $filename );
		$version           = $this->version;
		$asset_config_path = $this->dist_path( $basename . '.asset.php' );

		if ( file_exists( $asset_config_path ) ) {
			$asset_config = require $asset_config_path;

			if ( $is_js ) {
				$dependencies = array_unique( array_merge( $asset_config['dependencies'], $dependencies ) );
			}
			$version = $asset_config['version'];
		}

		return [
			'url'          => $url,
			'dependencies' => $dependencies,
			'version'      => $version,
			'type'         => $is_js ? 'script' : 'style',
			'args'         => null !== $args ? $args : ( $is_js ? false : 'all' ),
		];
	}

    public function asset_url( $filename ){
        if( filter_var( $filename, FILTER_VALIDATE_URL ) ) {
            return $filename;
        }

        return $this->plugin_url . 'assets/' . $filename;
    }

    public function dist_path( $file ){
        return path_join( $this->plugin_path, 'assets/' . $file );
    }

    public function icon( $name ) {
		return $this->plugin_url . 'assets/images/' . $name . '?v=' . $this->version;
	}
}