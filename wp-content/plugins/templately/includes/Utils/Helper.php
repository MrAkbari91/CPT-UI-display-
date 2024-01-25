<?php
namespace Templately\Utils;

use WP_Error;
use WP_REST_Response;
use function get_plugins;
use function is_plugin_active;
/**
 * Utility Helper for Templately
 *
 * This class contains some helper functions for easy access.
 *
 * @since 1.0.0
 */
class Helper extends Base {
    /**
     * Get installed WordPress Plugin List
     * @return array
     */
    public static function get_plugins(){
        if( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return get_plugins();
    }

    /**
     * Get installed WordPress Plugin List
     * @return boolean
     */
    public static function is_plugin_active( $plugin ){
        if( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return is_plugin_active( $plugin );
    }

    /**
	 * Collect IP from request.
	 *
	 * @return string
	 */
	public static function get_ip() {
		$ip = '127.0.0.1'; // Local IP
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : $ip;
		}

		return sanitize_text_field( $ip );
	}

    /**
     * Get views for front-end display
     *
     * @param string $name  it will be file name only from the view's folder.
     * @param array $data
     * @return void
     */
    public static function views( $name, $data = [] ){
		extract( $data );
        $helper = self::class;
        $file = TEMPLATELY_PATH . 'views/' . $name . '.php';

        if( is_readable( $file ) ) {
            include_once $file;
        }
    }

	/**
	 * Sanitize Helper
	 *
	 * @param mixed $value
	 * @param string $type
	 *
	 * @return bool|string
	 */
	public static function sanitize( $value, $type = 'text' ){
		switch ( $type ) {
			case 'boolean':
				$sanitized_value = rest_sanitize_boolean( $value );
				break;
			default:
				$sanitized_value = sanitize_text_field( $value );
				break;
		}

		return $sanitized_value;
	}

	/**
	 * API Error Formatter
	 *
	 * @param int $error_code
	 * @param mixed $error_message
	 * @param string $endpoint
	 * @param integer $status
	 * @param array $additional_data
	 * @return WP_Error
	 */
	public static function error( $error_code, $error_message, $endpoint = '', $status = 500, $additional_data = [] ) {
		$additional_data['status'] = $status;
		if ( ! empty( $endpoint ) ) {
			$additional_data['endpoint'] = $endpoint;
		}

		return new WP_Error( $error_code, $error_message, $additional_data );
	}

	/**
	 * API Response Formatter
	 *
	 * @param mixed $data
	 * @return WP_REST_Response
	 */
	public static function success( $data ) {
		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Normalize Favourites Data
	 *
	 * @param array $favourites
	 * @param array $_favourites
	 * @param boolean $undo
	 *
	 * @return array
	 */
	public function normalizeFavourites( $favourites, $_favourites = [], $undo = false ){
		if( $undo ) {
			$_favourites[ $favourites['type'] ] = array_values(array_filter( $_favourites[ $favourites['type'] ], function( $item ) use( $favourites ) {
				return $item != $favourites['id'];
			} ));
			return $_favourites;
		}

		array_map( function( $item ) use ( &$_favourites) {
			if( ! is_null( $item ) ) {
				$item = (array) $item;
				if ( isset( $_favourites[ $item['type'] ] ) ){
					$_favourites[ $item['type'] ][] = $item['id'];
				} else {
					$_favourites[ $item['type'] ] = [ $item['id'] ];
				}
			}
			return $_favourites;
		}, $favourites );

		return $_favourites;
	}

	public function normalizeReviews( $favourites, $_favourites = [], $undo = false ){
		array_map( function( $item ) use ( &$_favourites) {
			if( ! is_null( $item ) ) {
				$item = (array) $item;
				if ( !isset( $_favourites[ $item['type'] ] ) ){
					$_favourites[ $item['type'] ] = [];
				}
				$_favourites[ $item['type'] ][$item['type_id']] = $item['rating'];
			}
			return $_favourites;
		}, $favourites );

		return $_favourites;
	}

	/**
	 * Trigger Error
	 *
	 * @param object $triggered_by
	 * @return void
	 */
	public static function trigger_error( $triggered_by, $method = 'get_instance' ){
		$class = get_class( $triggered_by );
		$trace = debug_backtrace(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		$file = $trace[0]['file'];
		$line = $trace[0]['line'];
		trigger_error("Call to undefined method $class::$method() in $file on line $line", E_USER_ERROR);
	}

	/**
	 * Printing Error Logs in debug.log file.
	 *
	 * @param mixed $log
	 * @return void
	 */
	public static function log( $log ){
		if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}