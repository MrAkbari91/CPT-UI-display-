<?php

namespace Templately\API;

use Templately\Core\Platform\Elementor;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * As SavedTemplates is enabled in Elementor.
 * So, this class will represent Elementor's Templates Feature.
 *
 * @method Elementor platform( $id )
 *
 * @since 2.0.0
 *
 * @link https://templately.com
 */
class SavedTemplates extends API {
	private $endpoint = 'saved-templates';

	public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;
		$_route        = $request->get_route();
		$_id           = (int) $this->get_param( 'id', 0 );

		if( $_route === "/templately/v1/saved-templates/delete" && ! (current_user_can( 'delete_post', $_id ) && get_post_type($_id) === "elementor_library")) {
			return $this->error('invalid_permission', __( 'Sorry, you do not have permission to delete this template.', 'templately' ), 'saved-templates/delete', 403 );
		}
		return true;
	}

	public function register_routes() {
		$this->get( $this->endpoint, [ $this, 'get_saved_templates' ] );
		$this->post($this->endpoint . '/delete', [ $this, 'delete' ] );
	}

	/**
	 * Get all the saved templates from Elementor library.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array
	 */
	public function get_saved_templates( WP_REST_Request $request) {
		$platform = $this->get_param( 'platform', 'elementor' );
		return $this->platform( $platform )->get_saved_templates( $request->get_params() );
	}

	/**
	 * Delete saved template.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete( WP_REST_Request $request ){
		$platform = $this->get_param( 'platform', 'elementor' );
		return $this->platform( $platform )->delete( $request->get_params() );
	}
}