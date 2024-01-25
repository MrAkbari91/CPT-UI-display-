<?php

namespace Templately\API;

use Templately\Builder\Managers\ConditionManager;
use Templately\Builder\Source;
use WP_REST_Request;
use WP_REST_Response;

class Conditions extends API {
	/**
	 * @var ConditionManager
	 */
	private $conditions_manager;

	public function permission_check( WP_REST_Request $request ) {
		$post_type_object = get_post_type_object( Source::CPT );

		return current_user_can( $post_type_object->cap->edit_posts );
	}

	public function register_routes() {
		$this->get( 'conditions', [ $this, 'get_conditions' ], [
			'template_id' => [
				'required'          => false,
				'validate_callback' => function ( $param ) {
					return empty( $param ) || is_numeric( $param );
				}
			]
		] );

		$this->get( 'check-conditions', [ $this, 'check' ], [
			'template_id' => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_numeric( $param );
				}
			]
		] );

		$this->post( 'save-conditions', [ $this, 'save' ], [
			'template_id' => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_numeric( $param );
				}
			],
			'conditions'  => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_array( $param );
				}
			]
		] );

		$this->get( 'autocomplete-condition', [ $this, 'autocomplete' ], [
			'payload' => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_string( $param );
				}
			],
			'query'   => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_array( $param ) && array_key_exists( 'query_type', $param );
				}
			]
		] );

		$this->conditions_manager = templately()->theme_builder::$conditions_manager;
	}

	public function get_conditions( WP_REST_Request $request ): WP_REST_Response {
		$conditions = $this->conditions_manager->get_conditions_for_display( $request->get_param( 'template_id' ) );

		return $this->success( $conditions );
	}

	public function check( WP_REST_Request $request ): WP_REST_Response {
		return $this->success( [] );
	}

	public function save( WP_REST_Request $request ): WP_REST_Response {
		$conditions = rest_sanitize_array( $request->get_param( 'conditions' ) );
		$id         = (int) $request->get_param( 'template_id' );


		$this->conditions_manager->save_conditions( $id, $conditions );


		return $this->success( __( 'Successfully saved.', 'templately' ) );
	}

	public function autocomplete( WP_REST_Request $request ): WP_REST_Response {
		$query = $request->get_param( 'query' );
		$type  = $query['query_type'] ?? '';

		if ( empty( $type ) ) {
			// FIXME: need throw error maybe
			return $this->success( [] );
		}

		$by_field = $query['field'] ?? '';

		if ( empty( $by_field ) ) {
			// FIXME: need throw error maybe
			return $this->success( [] );
		}

		$payload = sanitize_text_field( $request->get_param( 'payload' ) );
		$args    = [ 'search' => $payload ];

		if ( isset( $query['query'] ) ) {
			$args = wp_parse_args( $query['query'], $args );
		}

		$results = [];

		switch ( $type ) {
			case 'taxonomy':
				$_default = [ 'hide_empty' => false ];
				$data     = get_terms( wp_parse_args( $args, $_default ) );
				$data_key = 'name';
				break;
			case 'posts':
				$args['s'] = $args['search'];
				$data      = get_posts( $args );
				$data_key  = 'post_title';
				break;
			case 'authors':
				$args['search_columns'] = [ 'user_nicename', 'user_login' ];
				$args['search']         = "*{$args['search']}*";
				$data                   = get_users( $args );

				$data_key = 'display_name';
				break;
		}

		if ( ! empty( $data ) && is_array( $data ) ) {
			foreach ( $data as $item ) {
				$results[] = [
					'label' => $item->{$data_key},
					'value' => $item->{$by_field}
				];
			}
		}

		return $this->success( $results );
	}
}