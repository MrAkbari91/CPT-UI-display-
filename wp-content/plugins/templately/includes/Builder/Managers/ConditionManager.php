<?php

namespace Templately\Builder\Managers;

use Templately\Builder\Conditions\Condition;
use Templately\Builder\ThemeBuilder;
use Templately\Builder\Types\ThemeTemplate;

class ConditionManager {
	const NONCE_KEY = 'templately_ajax_nonce';
	const META_KEY  = '_templately_conditions';

	private $cache;

	private $locations = [];

	/**
	 * @var Condition[]
	 */
	private $conditions = [];

	/**
	 * @var ThemeBuilder
	 */
	private $builder;
	
	private $location_cache = [];

	public function __construct( $builder ) {
		$this->cache   = new Cache();
		$this->builder = $builder;

		add_action( 'wp_loaded', [ $this, 'register' ] );
		add_action( 'wp_ajax_templately_conditions', [ $this, 'get_conditions' ] );
		add_action( 'wp_ajax_templately_save_conditions', [ $this, 'save_conditions' ] );
		add_action( 'wp_ajax_templately_check_conditions', [ $this, 'check_conditions' ] );
	}

	public function get_conditions() {
		$config = [];

		foreach ( $this->conditions as $condition ) {
			$config[ $condition->get_name() ] = $condition->get_config();
		}

		// throw new \Exception('Something went wrong');

		wp_send_json( $config );
	}

	public function get_conditions_for_display( $template_id = null ): array {
		if ( ! is_numeric( $template_id ) ) {
			$template_id = null;
		}

		$config = [];
		foreach ( $this->conditions as $condition ) {
			$config[ $condition->get_name() ] = $condition->get_config();
		}

		if ( ! $template_id ) {
			return [
				'config' => $config
			];
		}

		$template        = $this->builder::$templates_manager->get( $template_id );
		$conditions_meta = $template->get_meta( self::META_KEY );

		$conditions_for_display = [];

		if ( is_array( $conditions_meta ) && ! empty( $conditions_meta ) ) {
			foreach ( $conditions_meta as $c ) {
				$conditions_for_display[] = $this->parse_condition( $c );
			}
		}


		return [
			'config'              => $config,
			'template_conditions' => $conditions_for_display
		];
	}

	private function verify_nonce(): bool {
		return true;
		// return isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( $_REQUEST['_nonce'], self::NONCE_KEY );
	}

	public function save_conditions( $post_id, $conditions = [] ): bool {
		$conditions_to_save = [];
		foreach ( $conditions as $condition ) {
			if ( is_string( $condition ) ) {
				$conditions_to_save[] = rtrim( $condition, '/' );
				continue;
			}

			unset( $condition['id'] );
			$conditions_to_save[] = rtrim( implode( '/', $condition ), '/' );
		}

		/**
		 * @var ThemeTemplate $template
		 */
		$template = $this->builder->get_template( $post_id );

		if ( empty( $conditions_to_save ) ) {
			$template->delete_meta( self::META_KEY );
			$this->cache->remove( $post_id )->save();
		} else {
			$template->update_meta( self::META_KEY, $conditions );
			$this->cache->add( $template, $conditions )->save();
		}

		$this->cache->regenerate();

		return true;
	}

	public function check_conditions() {
		if ( ! $this->verify_nonce() ) {
			wp_send_json_error( [ 'Unauthorized to take action.' ], 401 );
		}
	}

	public function register() {
		$this->register_condition( 'general' );
	}

	public function register_condition( string $id, $args = [] ) {
		if ( isset( $this->conditions[ $id ] ) ) {
			return;
		}

		// if ( strpos( $id, '_' ) !== false ) {
		// 	$id = implode( '', array_map( 'ucfirst', explode( '_', $id ) ) );
		// }

		$class_name = '\\Templately\\Builder\\Conditions\\' . ucfirst( $id );
		/** @var Condition $condition */
		$condition = new $class_name( $args );
		$this->register_condition_instance( $condition );

		foreach ( $condition->get_sub_conditions() as $key => $_sub_id ) {
			if ( is_numeric( $key ) ) {
				$id   = $_sub_id;
				$args = [];
			} else {
				$id   = $key;
				$args = $_sub_id;
			}
			$this->register_condition( $_sub_id, $args );
		}
	}

	/**
	 * @param Condition $object
	 */
	public function register_condition_instance( Condition $object ) {
		$this->conditions[ $object->get_name() ] = $object;
	}

	public function get_templates_by_location( $location ): array {
		if ( isset( $this->location_cache[ $location ] ) ) {
			return $this->location_cache[ $location ];
		}

		$template_ids = $this->get_template_ids( $location );

		//		dump( $location, $template_ids );

		$templates = [];

		$location_settings = templately()->theme_builder::$location_manager->get_location( $location );

		if ( ! empty( $template_ids ) ) {
			foreach ( $template_ids as $template_id => $priority ) {
				$template = $this->builder::$templates_manager->get( $template_id );

				if ( $template ) {
					$templates[ $template_id ] = $template;
				}

				if ( empty( $location_settings['multiple'] ) ) {
					break;
				}
			}
		}

		$this->location_cache[ $location ] = $templates;

		return $templates;
	}

	private function get_template_ids( $location ): array {
		$current_post_id = get_the_ID();
		$template        = $this->builder->get_template( $current_post_id );
		if ( $template && $location === $template->get_location() ) {
			return [ $current_post_id => 1 ];
		}

		return $this->get_location_templates( $location );
	}

	public function get_location_templates( string $location ): array {
		$conditions_priority = [];
		$conditions_groups   = $this->cache->get_by_location( $location );

		if ( empty( $conditions_groups ) ) {
			return $conditions_priority;
		}


		$excludes = [];

		//		dump( $conditions_groups );

		foreach ( $conditions_groups as $template_id => $conditions ) {
			foreach ( $conditions as $condition ) {
				$origin_c = $condition;

				extract( $this->parse_condition( $condition ) );

				$is_include = $type === 'include';
				$condition  = $this->get_condition( $name );

				if ( ! $condition ) {
					continue;
				}

				$is_passed     = $condition->check();
				$sub_condition = null;


				if ( $is_passed && $sub_name ) {
					$sub_condition = $this->get_condition( $sub_name );


					if ( ! $sub_condition ) {
						continue;
					}

					$is_passed = $sub_condition->check( [ 'id' => $sub_id ] );
				}

				if ( $is_passed ) {
					$post_status = get_post_status( $template_id );

					if ( $post_status !== 'publish' ) {
						continue;
					}

					if ( $is_include ) {
						$conditions_priority[ $template_id ] = $this->get_priority( $condition, $sub_condition, $sub_id );
					} else {
						$excludes[] = $template_id;
					}
				}
			}
		}

		foreach ( $excludes as $exclude_id ) {
			unset( $conditions_priority[ $exclude_id ] );
		}

		asort( $conditions_priority );

		return $conditions_priority;
	}

	private function get_condition( $name ) {
		return $this->conditions[ $name ] ?? null;
	}

	/**
	 * @param $condition
	 * @param $sub_condition
	 * @param $sub_id
	 *
	 * @return int|mixed
	 */
	private function get_priority( $condition, $sub_condition, $sub_id ) {
		$priority = $condition->get_priority();

		if ( $sub_condition ) {
			if ( $sub_condition->get_priority() < $priority ) {
				$priority = $sub_condition->get_priority();
			}

			$priority -= 10;

			if ( $sub_id ) {
				$priority -= 10;
			} elseif ( 0 === count( $sub_condition->get_sub_conditions() ) ) {
				$priority -= 5;
			}
		}

		return $priority;
	}

	protected function parse_condition( $condition ): array {
		if ( is_string( $condition ) ) {
			list( $type, $name, $sub_name, $sub_id ) = array_pad( explode( '/', $condition ), 4, '' );
			$condition = compact( 'type', 'name', 'sub_name', 'sub_id' );
		}

		return $condition;
	}

}