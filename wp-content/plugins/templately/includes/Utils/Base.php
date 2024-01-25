<?php

namespace Templately\Utils;

use Templately\Core\Module;
use Templately\Core\Platform;

abstract class Base {
	/**
	 * Holds the plugin instance.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @static
	 *
	 * @var Base
	 */
	private static $instances = [];

	/**
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'templately' ), '1.0.0' );
	}

	/**
	 * Disable un-serializing of the class.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function __wakeup() {
		// Un-serializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'templately' ), '1.0.0' );
	}

	/**
	 * Sets up a single instance of the plugin.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @return static An instance of the class.
	 */
	public static function get_instance( ...$args ) {
		$module = get_called_class();
		if ( ! isset( self::$instances[ $module ] ) ) {
			self::$instances[ $module ] = new $module( ...$args );
		}

		return self::$instances[ $module ];
	}

	/**
	 * Determine the plugin module to make platform based things happened.
	 *
	 * @param  string $id
	 * @return Platform|null
	 */
	public function platform( $id ){
		$platform = Module::get_instance()->active( $id );
		if( ! is_null( $platform ) ) {
			return $platform;
		}

		return null;
	}
}