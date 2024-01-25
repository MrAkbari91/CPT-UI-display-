<?php
namespace Templately\Utils;

use Templately\API\Login;
use WP_Error;

class Http extends Base {
    /**
     * API Endpoint
     * @var string
     */
    private $url = 'https://app.templately.com/api/plugin';

    /**
     * Development Mode
     * @var boolean
     */
    private $dev_mode = false;

    /**
     * API Query
     * @var string
     */
    public $query = null;
    /**
     * API Endpoint
     * @var string
     */
    public $endpoint = null;

    /**
     * Setting the development mode.
     */
    public function __construct() {
        $this->dev_mode = defined( 'TEMPLATELY_DEV' ) && TEMPLATELY_DEV;
    }

    /**
     * Determining the endpoint URL based on the mode.
     *
     * @return string
     */
    public function url() {
        if ( $this->dev_mode ) {
            $this->url = 'https://app.templately.dev/api/plugin';
        }
        return $this->url;
    }

    /**
     * Preparing query arguments.
     * Unknown.
     *
     * @return string
     */
    public static function prepare( $query, ...$args ) {
        return sprintf( $query, ...$args );
    }

    /**
     * Preparing query arguments
     *
     * @param  array $args
     * @return string
     */
    protected function prepareArgs( $args ) {
        $prepareArgs = "";
        foreach ( $args as $key => $value ) {
            switch ( true ) {
                case is_int( $value ):
                case is_bool( $value ):
                case is_string( $value ) && ( $value === 'true' || $value === 'false' ):
                    $prepareArgs .= "$key:" . $value . ",";
                    break;
                default:
                    $prepareArgs .= "$key:" . '"' . $value . '"' . ",";
                    break;
            }
        }

        return rtrim( $prepareArgs, ',' );
    }

    /**
     * Preparing query for the endpoint.
     *
     * @param string $query_name
     * @param string $params
     * @param array $funcArgs
     * @param array ...$args
     * @return Http
     */
    public function query( $query_name, $params, $funcArgs = [], ...$args ) {
        $query = '{';
        $query .= $query_name;
        if ( is_array( $funcArgs ) && ! empty( $funcArgs ) ) {
            $query .= "(" . $this->prepareArgs( $funcArgs ) . ")";
        }
        if ( ! empty( $params ) ) {
            $query .= "{";
            $query .= $params;
            $query .= "}";
        }
        $query .= '}';

        $this->endpoint = $query_name;
        $this->query    = ! empty( $args ) ? sprintf( $query, ...$args ) : $query;
        return $this;
    }

    /**
     * Preparing mutation for the endpoint.
     *
     * @param string $mutate
     * @param string $params
     * @param array $funcArgs
     * @param array ...$args
     * @return Http
     */
    public function mutation( $mutate, $params, $funcArgs = [], ...$args ) {
        $this->query( $mutate, $params, $funcArgs, ...$args );
        $mutation = 'mutation';
        $mutation .= $this->query;
        $this->endpoint = $mutate;
        $this->query    = ! empty( $args ) ? sprintf( $mutation, ...$args ) : $mutation;
        return $this;
    }

    /**
     * This function is responsible for Remote HTTP POST
     *
     * @param string $query
     * @param array $args
     * @return mixed
     */
    public function post( $args = [] ) {
        if ( empty( $query ) ) {
            $query = $this->query;
        }

        $headers = [
            'Content-Type'     => 'application/json',
            'x-templately-ip'  => Helper::get_ip(),
            'x-templately-url' => home_url( '/' )
        ];

        if ( ! empty( $args['headers'] ) ) {
            $headers = wp_parse_args( $args['headers'], $headers );
            unset( $args['headers'] );
        }

        if ( defined( 'TEMPLATELY_DEBUG_LOG' ) && TEMPLATELY_DEBUG_LOG ) {
            Helper::log( 'URL: ' . $this->url() );
            Helper::log( 'QUERY: ' . $query );
        }

        $_default_args = [
            'timeout' => $this->dev_mode ? 40 : 15,
            'headers' => $headers,
            'body'    => wp_json_encode( [
                'query' => $query
            ] )
        ];

        $args     = wp_parse_args( $args, $_default_args );
        $response = wp_remote_post( $this->url(), $args );

        if ( defined( 'TEMPLATELY_DEBUG_LOG' ) && TEMPLATELY_DEBUG_LOG ) {
            Helper::log( 'RAW RESPONSE: ' );
            Helper::log( $response );
            Helper::log( 'END RAW RESPONSE' );
        }

        return $this->maybeErrors( $response, $args );
    }

    /**
     * Formating the self::post() response
     *
     * @param mixed $response
     * @param array $args
     * @return mixed
     */
    private function maybeErrors( &$response, $args = [] ) {
        if ( $response instanceof WP_Error ) {
            return $response; // Return WP_Error, if it is an error.
        }

        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        /**
         * Retrive Data from Response Body.
         */
        $response = json_decode( wp_remote_retrieve_body( $response ), true );
        /**
         * If the graphql hit with any error.
         */
        if ( ! empty( $response['errors'] ) ) {
            if ( defined( 'TEMPLATELY_DEBUG_LOG' ) && TEMPLATELY_DEBUG_LOG ) {
                Helper::log( 'ERROR: ' );
                Helper::log( $response['errors'] );
                Helper::log( 'END ERROR' );
            }
            if ( is_array( $response['errors'] ) ) {
                $wp_error = new WP_Error;
                array_walk( $response['errors'], function ( $error ) use ( &$wp_error ) {
                    if ( isset( $error['message'] ) ) {
                        if ( $error['message'] === 'validation' ) {
                            array_walk( $error['extensions'], function ( $_error, $_error_key ) use ( &$wp_error ) {
                                if ( $_error_key == 'validation' ) {
                                    array_walk( $_error, function ( $v_error, $key ) use ( &$wp_error ) {
                                        $wp_error->add( "{$key}_error", $v_error[0] );
                                    } );
                                }
                            } );
                        } else {
                            if ( isset( $error['debugMessage'] ) ) {
                                $wp_error->add( 'templately_graphql_error', $error['debugMessage'] );
                            } else {
                                $wp_error->add( 'templately_graphql_error', $error['message'] );
                            }
                        }
                    }
                } );

				if( $wp_error->get_error_code() === 'templately_graphql_error' ) {
					if( $wp_error->get_error_message() == 'Unauthorized' ) {
						$global_user = Login::get_instance()->delete();

						return [
							'redirect' => true,
							'url' => 'sign-in',
							'user' => $global_user,
						];
					}
				}

                return $wp_error;
            }
        }

        $_response = isset( $response['data'][$this->endpoint] ) ? $response['data'][$this->endpoint] : [];

        if ( defined( 'TEMPLATELY_DEBUG_LOG' ) && TEMPLATELY_DEBUG_LOG ) {
            Helper::log( 'RESPONSE: ' );
            Helper::log( $_response );
            Helper::log( 'END RESPONSE' );
        }

        return $_response;
    }
}
