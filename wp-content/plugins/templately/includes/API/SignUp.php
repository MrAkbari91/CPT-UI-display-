<?php

namespace Templately\API;

use Templately\Utils\Helper;
use Templately\Utils\Options;
use WP_REST_Request;

class SignUp extends API {
	public function permission_check( WP_REST_Request $request )  {
		$this->request = $request;
		return true;
	}

	public function register_routes() {
		$this->post('signup', [ $this, 'create_account' ] );
	}

	public function create_account() {
		$errors    = [];
		$_ip       = Helper::get_ip();
		$_site_url = home_url( '/' );

		$first_name       = $this->get_param( 'first_name' );
		$last_name        = $this->get_param( 'last_name' );
		$email            = $this->get_param( 'email', '', 'sanitize_email' );
		$password         = $this->get_param( 'password' );
		$confirm_password = $this->get_param( 'confirm_password' );

		if ( empty( $first_name ) ) {
			$errors['first_name'] = __( 'First name cannot be empty.', 'templately' );
		}
		if ( empty( $last_name ) ) {
			$errors['last_name'] = __( 'Last name cannot be empty.', 'templately' );
		}
		if ( empty( $email ) ) {
			$errors['email'] = __( 'Email cannot be empty.', 'templately' );
		}
		if ( $email && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$errors['email'] = __( 'Make sure you have given a valid email address.', 'templately' );
		}
		if ( empty( $password ) ) {
			$errors['password'] = __( 'Password cannot be empty.', 'templately' );
		}
		if ( empty( $confirm_password ) ) {
			$errors['confirm_password'] = __( 'Confirm password cannot be empty.', 'templately' );
		}

		if ( ! empty( $password ) && ! empty( $confirm_password ) && $password !== $confirm_password ) {
			$errors['password_mismatched'] = __( 'Password and confirm password should be matched.', 'templately' );
		}

		if ( ! empty( $errors ) ) {
			return $this->error( 'signup_errors', $errors, 'signup', '400' );
		}

		$query = 'status, message, user{ id, name, first_name, last_name, display_name, email, profile_photo, joined, is_verified, api_key, plan, plan_expire_at, my_cloud{ limit, usages, last_pushed }, show_notice }';

		$response = $this->http()->mutation(
			'createUser',
			$query,
			[
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'email'      => $email,
				'password'   => $password,
				'site_url'   => $_site_url,
				'ip'         => $_ip
			]
		)->post();

		if( is_wp_error( $response ) ) {
			return $response;
		}

		if ( ! Login::is_globally_signed() ) {
			Options::set_global_login();
		}

		if ( ! empty( $response['user']['api_key'] ) ) {
			$this->utils('options')->set( 'api_key', $response['user']['api_key'] );
			unset( $response['user']['api_key'] );
		}

		$this->utils('options')->set( 'user', $response['user'] );

		return $response;
	}
}