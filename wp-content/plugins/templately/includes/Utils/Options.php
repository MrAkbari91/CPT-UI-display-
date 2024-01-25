<?php

namespace Templately\Utils;

use function get_option;
use function update_option;
use function get_user_meta;
use function update_user_meta;
use function get_current_user_id;

class Options extends Base {
	/**
	 * Current User Id
	 * @var integer
	 */
	private $current_user = 0;

	/**
	 * User has any api?
	 * @var boolean
	 */
	private $has_api = false;

	/**
	 * Automatically invoked and set up the properties.
	 */
	public function __construct(){
		$this->current_user = get_current_user_id();
		$this->has_api      = ! empty( $this->get( 'api_key', '', $this->current_user ) );
	}

	/**
	 * Get the current user ID.
	 * @return int
	 */
	public function current_user_id(){
		return $this->current_user;
	}

	/**
	 * Can a user link another templately account in a setup?.
	 * @return boolean
	 */
	public function link_account() {
		return $this->whoami() === 'link' && ! $this->has_api;
	}

	public function unlink_account() {
		return ( $this->whoami() === 'link' || $this->whoami() === 'local' ) && $this->has_api;
	}
	/**
	 * Get determined who am I.
	 * @return string
	 */
	public function whoami(){
		$_whoami = 'local';

		if( $this->is_global() > 0 && $this->is_global() === $this->current_user ) {
			$_whoami = 'global';
		}

		if( $this->is_global() > 0 && $this->is_global() !== $this->current_user ) {
			$_whoami = 'link';
		}

		if( $this->is_global() == 0 ) {
			$_whoami = 'local';
		}

		return $_whoami;
	}
	/**
	 * Get user id determine dynamically
	 * @return integer
	 */
	private function user_id(){
		$_whoami = $this->whoami();
		// Helper::log( '_whoami: ' . $_whoami );

		if( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$parse_uri = explode( '/', substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], '?' ) ) );
			if( $_whoami === 'link' && array_pop( $parse_uri ) === 'login' ) {
				return $this->current_user;
			}
		}

		if( $_whoami === 'link' && $this->has_api ) {
			return $this->current_user;
		}

		return $_whoami === 'local' ? $this->current_user : $this->is_global();
	}
	/**
	 * Globally logged in and the User ID of globally logged-in user.
	 * @return integer
	 */
	public function is_global(){
		return intval( get_option('_templately_global_login', 0) );
	}
	/**
	 * Set global login flag
	 * @return boolean
	 */
	public static function set_global_login() {
		return update_option('_templately_global_login', get_current_user_id(), 'no');
	}
	/**
	 * Remove global login flag
	 * @return boolean
	 */
	public function remove_global_login() {
		return delete_option( '_templately_global_login' );
	}

	public function is_globally_signed() {
		return $this->whoami() !== 'local';
	}
	public function signed_as_global() {
		return $this->current_user === $this->is_global();
	}
	/**
	 * Set optional usermeta or option data
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function set( $key, $value, $user_id = null ){
		$key = '_templately_' . $key;

		if( $user_id === null ) {
			$user_id = $this->user_id();
		}

		$updated = update_user_meta( $user_id, $key, $value );

		if( $key === '_templately_api_key' && $updated ) {
			$this->has_api = true;
		}

		return $updated;
	}
	/**
	 * Get optional usermeta or option data
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $key, $default = false, $user_id = null ){
		$key = '_templately_' . $key;
		$_user_meta = get_user_meta(  is_null( $user_id ) ? $this->user_id() : $user_id, $key, true );
		return ! empty( $_user_meta ) ? $_user_meta : $default;
	}
	/**
	 * Remove options data or usermeta
	 *
	 * @param string $key
	 * @return Options
	 */
	public function remove( $key ){
		$key = '_templately_' . $key;
		$updated = delete_user_meta( $this->user_id(), $key ); // delete user meta

		if( $key === '_templately_api_key' && $updated ) {
			$this->has_api = false;
		}

		return $this;
	}

	/**
	 * Get option data
	 *
	 * @since 2.0.1
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get_option( $key, $default = false ){
		return get_option( $key, $default );
	}

	/**
	 * Update option data
	 *
	 * @since 2.0.1
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param string $autoload
	 *
	 * @return bool
	 */
	public function update_option( $key, $value, $autoload = 'no' ){
		return update_option( $key, $value, $autoload );
	}
}