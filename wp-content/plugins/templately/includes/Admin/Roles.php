<?php

namespace Templately\Admin;

use Templately\Utils\Base;
use Templately\Utils\Database;
use Templately\Utils\Options;

class Roles extends Base {
	/**
	 * Database class
	 * @var Database
	 */
	protected $database;

	/**
	 * Options class
	 * @var Options
	 */
	protected $options;

	public function __construct() {
		$this->database = new Database();
		$this->options = new Options();
	}

	/**
	 * Default Roles Capabilities
	 *
	 * @return array
	 */
	public function defaults_capabilities(): array {
		$default_capabilities = [
			'administrator' => [
				'edit_templately_builder',
				'edit_templately_settings'
			],
			'editor'        => [
			],
			'author'        => [
			],
			'contributor'   => [
			]
		];

		return apply_filters( 'templately_default_caps', $default_capabilities );
	}

	public function setup( $remove = false ) {
		if ( $this->options->get_option( '_templately_caps_initialized', false ) && ! $remove ) {
			return;
		}

		global $wp_roles;

		$capabilities = $this->defaults_capabilities();

		if( $remove ) {
			unset( $capabilities['administrator'] );
		}

		foreach ( $capabilities as $role => $caps ) {
			foreach ( $caps as $cap ) {
				if ( $remove ) {
					$wp_roles->remove_cap( $role, $cap );
					continue;
				}

				$wp_roles->add_cap( $role, $cap );
			}
		}

		$this->options->get_option( '_betterdocs_caps_initialized', ! $remove );
	}
}