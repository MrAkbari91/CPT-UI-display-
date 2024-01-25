<?php

namespace Templately\API;

use Templately\Builder\Types\BaseTemplate;
use WP_Error;
use WP_REST_Request;

class ThemeBuilderApi extends API {
	public function permission_check( WP_REST_Request $request ) {
		if ( $request->get_route() === '/templately/v1/create-template' ) {
			return wp_verify_nonce( $request->get_param( 'nonce' ), 'templately_nonce' );
		}

		return parent::permission_check( $request );
	}

	public function register_routes() {
		$this->post( 'create-template', [ $this, 'create' ], [
			'platform' => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_string( $param ) && ( $param == 'elementor' || 'gutenberg' == $param );
				}
			],
			'type'     => [
				'required'          => true,
				'validate_callback' => function ( $param ) {
					return is_string( $param ) && array_key_exists( $param, templately()->theme_builder::$templates_manager->get_template_types() );
				}
			],
			'title'    => [
				'required' => true,
			]
		] );
	}

	public function create( WP_REST_Request $request ) {
		$platform      = $request->get_param( 'platform' );
		$template_type = $request->get_param( 'type' );
		$title         = $request->get_param( 'title' );

		if ( empty( $title ) ) {
			$title = '';
		}

		$post_data = [
			'post_title' => $title,
			'post_type' => 'templately_library'
		];

		/**
		 * @var BaseTemplate $template
		 */
		$template = templately()->theme_builder::$templates_manager->create( $template_type, $post_data, [ 'platform' => $platform ] );

		if ( is_wp_error( $template ) ) {
			/**
			 * @var WP_Error $template ;
			 */
			return $this->error( 'failed_template_creation', sprintf( __( 'Something went wrong. %s', 'templately' ), $template->get_error_message() ) );
		}

		return $this->success( $template->get_edit_url() );
	}
}