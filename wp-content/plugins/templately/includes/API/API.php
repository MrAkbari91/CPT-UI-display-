<?php

namespace Templately\API;

use WP_Error;
use WP_REST_Server;
use WP_REST_Request;
use function ucfirst;

use WP_REST_Response;
use Templately\Utils\Base;
use Templately\Utils\Http;
use Templately\Utils\Plan;
use Templately\Core\Module;
use function call_user_func;

use Templately\Utils\Helper;
use Templately\Utils\Options;
use function register_rest_route;

/**
 * @method Http http()
 * @method Options options()
 * @method Options|Http|Helper utils( string $name )
 */
abstract class API extends Base {
	protected $api_key;
	protected $request;

	private static $allowed_classes = [
		'utils' => [
			'options',
			'http',
			'helper'
		],
		'api' => [
			'dependencies',
		],
	];

	public function __construct() {
		/**
		 * Registering as a submodule of the API module
		 */
		Module::get_instance()->add( (object) [
            'object' => $this
        ], 'API' );
	}

	private static function is_allowed( $type, $class ){
		if( ! array_key_exists( $type, self::$allowed_classes ) ) {
			return false;
		}
		if( ! class_exists( '\\Templately\\'. ucfirst( $type ) .'\\' . ucfirst( $class ) ) ) {
			return false;
		}
		return true;
	}

	/**
	 * @param $type
	 * @param $parameters
	 *
	 * @return mixed|void
	 */
	public static function __callStatic( $type, $parameters = [] ){
		return ( new static )->call_user_func( $type, $parameters );
	}

	/**
	 * @param $type
	 * @param $parameters
	 *
	 * @return mixed|void
	 */
	public function __call( $type, $parameters = [] ){
		return $this->call_user_func( $type, $parameters );
	}

	/**
	 * @param $type
	 * @param $parameters
	 *
	 * @return mixed|void
	 */
	protected function call_user_func( $type, $parameters = [] ) {
		if( $type === 'http' || $type === 'options' ) {
			$parameters[0] = $type;
			$type = 'utils';
		}
		if( ! empty ( $parameters[0] ) && self::is_allowed( $type, $parameters[0] ) ) {
			return call_user_func( [ '\\Templately\\'. ucfirst( $type ) .'\\' . ucfirst( $parameters[0] ), 'get_instance' ] );
		}

		Helper::trigger_error( $this );
	}

	protected function get_namespace( $endpoint = '' ) {
		return '/' . TEMPLATELY_API_NAMESPACE . ( ! empty( $endpoint ) ? "/$endpoint" : '' );
	}

	/**
	 * @param string $param
	 * @param mixed $default
	 * @param string $sanitizer
	 *
	 * @return false|mixed
	 */
	public function get_param( $param, $default = '', $sanitizer = 'sanitize_text_field' ) {
		$_value = $this->request->get_param( $param );
		if ( ! empty( $_value ) ) {
			if( is_callable($sanitizer) && ! is_array( $_value ) ) {
				return call_user_func_array( $sanitizer, [ $_value ] );
			} elseif ( is_array( $_value ) && is_callable($sanitizer) ) {
				return array_map( $sanitizer, $_value );
			}
			return $_value;
		}

		return $default;
	}

	/**
	 * @param $request WP_REST_Request for getting all route request in time.
	 *
	 * @return WP_Error|boolean
	 */
	public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;
		$this->api_key = $this->utils('options')->get( 'api_key' );

		if ( ! empty( $this->api_key ) ) {
			return true;
		}

		$_route = $request->get_route();
		return $this->permission_error( '', $_route );
	}

	/**
	 * @param $message
	 * @param $endpoint
	 *
	 * @return WP_Error
	 */
	protected function permission_error( $message, $endpoint = '') {
        if( empty( $message ) ) {
            $message = __( 'Sorry, you need to login/re-login again.', 'templately' );
        }

        $_additional_data = [
            'status'   => rest_authorization_required_code(),
        ];

        if( ! empty( $endpoint ) ) {
            $_additional_data['endpoint'] = $endpoint;
        }

        // Delete user logged meta data
		$this->utils('options')
			->remove('user')
			->remove('favourites')
			->remove('reviews')
			->remove('cloud_activity')
			->remove('api_key');

		if( $this->utils('options')->whoami() === 'global' ) {
			$this->utils('options')->remove_global_login();
		}

		return new WP_Error( 'invalid_api_key', $message, $_additional_data );
	}

	public function get( $endpoint, $callback, $args = [] ){
		return $this->register_endpoint( $endpoint, $callback, $args, WP_REST_Server::READABLE );
	}
	public function post( $endpoint, $callback, $args = [] ){
		return $this->register_endpoint( $endpoint, $callback, $args );
	}

	public function register_endpoint( $endpoint, $callback, $args = [], $methods = WP_REST_Server::CREATABLE ) {
		return register_rest_route(
			TEMPLATELY_API_NAMESPACE,
			$endpoint,
			[
				'methods'             => $methods,
				'callback'            => $callback,
				'permission_callback' => [ $this, 'permission_check' ],
				'args'                => $args,
			]
		);
	}

	public function response( $response, $endpoint, $status = 500, $additional_data = [] ) {
		if ( $response instanceof WP_Error ) {
			return $this->error(
				$response->get_error_code(),
				$response->get_error_message(),
				$endpoint,
				$status,
				$additional_data
			);
		}

		return $this->success( $response );
	}

	/**
	 * @param $data
	 *
	 * @return WP_REST_Response
	 */
	public function success( $data ) {
		return new WP_REST_Response( $data, 200 );
	}
	/**
	 * @param $error_code string
	 * @param $error_message string|array
	 * @param $endpoint string
	 * @param $status int
	 * @param $additional_data array
	 *
	 * @return WP_Error
	 */
	public function error( $error_code, $error_message, $endpoint = '', $status = 500, $additional_data = [] ) {
		return Helper::error( $error_code, $error_message, $endpoint, $status, $additional_data );
	}
	/**
	 * @param $plan
	 *
	 * @return int
	 */
	public function get_plan( $plan = 'all' ) {
		return Plan::get( $plan );
	}
}