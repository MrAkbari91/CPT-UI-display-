<?php

namespace Templately\API;

use WP_REST_Request;
use Templately\Utils\Helper;
use Templately\Utils\Options;

class Login extends API {
    public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;
        $_route = $request->get_route();
        if ( '/templately/v1/login' === $_route ) {
            return true;
        }

        if ( '/templately/v1/pricing' === $_route ) {
            return true;
        }

        return parent::permission_check( $request );
    }

    public function register_routes() {
        $this->post( 'login', [$this, 'login'] );
        $this->post( 'logout', [$this, 'logout'] );
        $this->get( 'is-signed', [$this, 'is_signed'] );
        $this->get( 'pricing', [$this, 'pricing'] );
    }

	public function pricing(){
		$data = get_transient( "templately_subscriptions" );

		if( is_array( $data ) && ! empty( $data ) ) {
			return $data;
		}

		$query = 'id, price, name, discounted_price, type, sites';
		$response = $this->http()->query(
			'subscriptionPlans',
			$query
		)->post();

		set_transient( "templately_subscriptions", $response, WEEK_IN_SECONDS );

		return $response;
	}

    public function login() {
        $errors    = [];
        $_ip       = Helper::get_ip();
        $_site_url = home_url( '/' );

        $global_signin = (bool) $this->get_param( 'global_signin', false );
        $viaAPI        = (bool) $this->get_param( 'viaAPI', false );
        $email         = $this->get_param( 'email', '', 'sanitize_email' );
        $password      = $this->get_param( 'password' );

        $funcArgs = [
            'ip'       => $_ip,
            'site_url' => $_site_url
        ];

        if ( $viaAPI ) {
            $api_key             = $this->get_param( 'api_key' );
            $funcArgs['api_key'] = $api_key;

            if ( empty( $api_key ) ) {
                $errors['api_key'] = __( 'API Key field cannot be empty.', 'templately' );
            }
        } else {
            $funcArgs['email']    = $email;
            $funcArgs['password'] = addcslashes( $password, '"' );

            if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $errors['email'] = __( 'Make sure you have given a valid email address.', 'templately' );
            }

            if ( empty( $password ) ) {
                $errors['password'] = __( 'Password field cannot be empty.', 'templately' );
            }
        }

        if ( ! empty( $errors ) ) {
            return $this->error( 'login_error', $errors, 'login', 400 );
        }

        $query = 'status, message, user{ id, name, first_name, last_name, display_name, email, profile_photo, joined, is_verified, api_key, plan, plan_expire_at, my_cloud{ limit, usages, last_pushed }, favourites{ id, type }, show_notice, reviews{ type, type_id, rating } }';

        $response = $this->http()->mutation(
            $viaAPI ? 'connectWithApiKey' : 'connect',
            $query,
            $funcArgs
        )->post();

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( $global_signin && ! Login::is_globally_signed() ) {
            Options::set_global_login();
        }

        if ( ! empty( $response['user']['api_key'] ) ) {
            $this->utils( 'options' )->set( 'api_key', $response['user']['api_key'] );
            unset( $response['user']['api_key'] );
        }

        $meta = [
            'is_globally_signed' => Login::is_globally_signed(),
            'signed_as_global'   => Login::signed_as_global()
        ];

        if ( ! empty( $response['user']['my_cloud']['last_pushed'] ) ) {
            $_cloud_activity = unserialize( $response['user']['my_cloud']['last_pushed'] );
            $this->utils( 'options' )->set( 'cloud_activity', $_cloud_activity );
            $meta['cloud_activity'] = $_cloud_activity;
            unset( $response['user']['my_cloud']['last_pushed'] );
        }

        if ( ! empty( $response['user']['favourites'] ) ) {
            $_favourites = $this->utils( 'helper' )->normalizeFavourites( $response['user']['favourites'] );
            $this->utils( 'options' )->set( 'favourites', $_favourites );

            unset( $response['user']['favourites'] );
            $meta['favourites'] = $_favourites;
        }

		if ( ! empty( $response['user']['reviews'] ) ) {
			$_reviews = $this->utils( 'helper' )->normalizeReviews( $response['user']['reviews'] );
			$this->utils( 'options' )->set( 'reviews', $_reviews );

			unset( $response['user']['reviews'] );
			$meta['reviews'] = $_reviews;
		}

        $this->utils( 'options' )->set( 'user', $response['user'] );
        $response['user']['meta'] = $this->user_meta( $meta );

        return $response;
    }

    public function logout() {
        $response = $this->http()->mutation(
            'disconnect',
            'status, message, data',
            [
                'api_key'  => $this->api_key,
                "site_url" => home_url( '/' )
            ]
        )->post();

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( ! isset( $response['status'] ) || $response['status'] !== 'success' ) {
            return $this->error( 'logout_error', $response['message'], 'logout', 404 );
        }

        // Remove All Metas
        $global_user = $this->delete();

        $response = [
            'status'  => 'success',
            'message' => __( 'Logged out.', 'templately' )
        ];

        if ( ! empty( $global_user ) ) {
            $response['global_user'] = $global_user;
        }

        return $response;
    }

	public function delete(){
		$this->utils( 'options' )
            ->remove( 'user' )
            ->remove( 'favourites' )
            ->remove( 'reviews' )
            ->remove( 'cloud_activity' )
            ->remove( 'api_key' )
            ->remove( 'global_login' )
            ->remove( 'templates_in_clouds' );

        if ( $this->utils( 'options' )->whoami() === 'global' ) {
            $this->utils( 'options' )->remove_global_login();
        }

		$global_user_id = $this->utils( 'options' )->is_global();
		$global_user = null;

        if ( $global_user_id !== $this->utils( 'options' )->current_user_id() ) {
            $global_user = $this->utils( 'options' )->get( 'user', false, $global_user_id );

            if ( ! empty( $global_user ) ) {
                $global_user['meta'] = $this->user_meta();
            }
        }

		return $global_user;
	}

    public static function is_signed() {
        $_response = [
            'status' => 'success'
        ];

        $_user = ( new static )->utils( 'options' )->get( 'user', null );

        if ( ! is_null( $_user ) ) {
            $_user['meta'] = self::get_instance()->user_meta();
        }

        if ( empty( $_user ) ) {
            $_response['status'] = 'error';
        }

        $_response['user'] = $_user;

        return $_response;
    }

    public function user_meta( $meta = [] ) {
        $_meta = [
            'link_account'       => self::utils( 'options' )->link_account(),
            'unlink_account'     => self::utils( 'options' )->unlink_account(),
            'is_globally_signed' => Login::is_globally_signed(),
            'signed_as_global'   => Login::signed_as_global(),
            'starred'            => self::utils( 'options' )->get( 'favourites' ),
            'reviews'            => self::utils( 'options' )->get( 'reviews' ),
            'cloud_activity'     => self::utils( 'options' )->get( 'cloud_activity' ),
            'has_api'            => rest_sanitize_boolean( self::utils( 'options' )->get( 'api_key' ) )
        ];

        return array_merge( $_meta, $meta );
    }

    public static function is_globally_signed() {
        return rest_sanitize_boolean(  ( new static )->utils( 'options' )->is_globally_signed() );
    }

    public static function signed_as_global() {
        return rest_sanitize_boolean(  ( new static )->utils( 'options' )->signed_as_global() );
    }
}