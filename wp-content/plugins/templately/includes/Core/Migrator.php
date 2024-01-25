<?php

namespace Templately\Core;

use Templately\API\Profile;
use Templately\Utils\Base;
use Templately\Utils\Options;

class Migrator extends Base {
	/**
	 * Options Database
	 *
	 * @var Options|null
	 */
	private $options = null;

	public function __construct(){
		$this->options = Options::get_instance();
		add_action('admin_init', [ $this, 'init' ]);
	}

	public function init(){
		$_old_version = $this->options->get_option( '_templately_migrate' );

		/**
		 * Migration for v1.3.6 to v2.0.1
		 */
		if( \version_compare(TEMPLATELY_VERSION, '2.0.1', '=') && \version_compare($_old_version, '1.3.6', '=') ) {
			$user_choice = $this->options->get_option('_templately_user_login_choice');
			$user_id = false;
			if( ! empty( $user_choice ) && isset($user_choice['choice']) ) {
				$user_id = intval($user_choice['id']);
			}

			$cloud_activity = $this->options->get_option('_templately_cloud_last_activity');
			$user_profile   = $this->options->get_option('_templately_connect_data');
			$_api_key       = $this->options->get_option('_templately_api_key');

			if ( $user_id === false ) {
				$user_id = get_current_user_id();

				$_api_key       = $this->options->get('api_key', false, $user_id );
				$user_profile   = $this->options->get('connect_data', false, $user_id );
				$cloud_activity = $this->options->get('cloud_last_activity', false, $user_id );
			}


			if( ! empty( $_api_key ) ) {
				// SET API
				$this->options->set('api_key', $_api_key, $user_id );
				$this->options->set('cloud_activity', $cloud_activity, $user_id );

				$_favourites = [];
				if( isset( $user_profile['favourites'] ) ) {
					$_favourites = $user_profile['favourites'];
					unset($user_profile['favourites']);
				}

				$this->options->set('user', $user_profile, $user_id );
				$this->options->set('favourites', $_favourites, $user_id );
			}
		}

		/**
		 * Migration for v2.2.10 to v2.2.11
		 */
		if( TEMPLATELY_VERSION !== $_old_version && \version_compare($_old_version, '2.2.10', '<=') ) {
			Profile::get_instance()->sync();
		}

		$this->options->update_option( '_templately_migrate', TEMPLATELY_VERSION );
	}

}
