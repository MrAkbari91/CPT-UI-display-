<?php

namespace Templately\API;

use Templately\Core\Platform\Elementor;
use Templately\Core\Platform\Gutenberg;

/**
 * @method Elementor|Gutenberg platform( $id );
 */
class Import extends API {
	private $endpoint = 'import';

	public function register_routes() {
		$this->post( 'insert', [ $this, 'insert_template' ] );
		$this->post( $this->endpoint, [ $this, 'import_in_library' ] );
		$this->post( $this->endpoint . '/page', [ $this, 'import_as_page' ] );
	}

    public function insert_template() {
        $platform = $this->get_param( 'platform', 'elementor' );
        $origin   = $this->get_param( 'origin', 'remote' );
        $id       = $this->get_param( 'id', 0, 'intval' );

        if( $id <= 0 || $id == null ) {
            return $this->error('invalid_item_id', __( 'Invalid ID is provided.', 'templately' ), 'get_content', 404 );
        }

        switch ( $origin ) {
            case 'cloud':
                $template_data = $this->get_cloud_content( $id, $platform );
                break;
            case 'remote':
                $template_data = $this->get_remote_content( $id );
                break;
            case 'local':
            default:
                $template_data = $this->get_local_content( $id, $platform );
				$template_data = isset( $template_data['data'] ) ? $template_data['data'] : [];
                break;
        }

		/**
		 * A fallback for old cloud items.
		 */
		$template_data = is_string( $template_data ) ? json_decode( wp_unslash($template_data), true ) : $template_data;

		if( is_wp_error( $template_data ) ) {
			return $template_data;
		}

        if( empty( $template_data ) || ! is_array( $template_data ) || empty( $template_data['content'] ) ) {
            return $this->error(
                'invalid_template_data',
                __( 'Template data is invalid. Please kindly contact Templately Support.', 'templately' ),
                'insert', 404
            );
        }

        return $this->platform( $platform )->insert( $template_data );
    }

    private function get_cloud_content( $id = null, $platform = 'elementor' ){
        $response = $this->http()->query(
            'myCloudInsert', '',
            [
                'api_key'   => $this->api_key,
                'file_id'   => $id,
                'file_type' => $platform
            ]
        )->post();

        if( is_wp_error( $response ) ) {
            // FIXME: need to return an error with handler in react app
            return $this->error( 'invalid_data', $response->get_error_message(), 'get_cloud_content', 404 );
        }

        return is_string( $response ) ? json_decode( $response, true ) : $response;
    }

    private function get_remote_content( $id = null ){

        $response = $this->http()->query(
            'itemContent',
            'status, message, data',
            [
                'api_key' => $this->api_key,
                'id'      => $id
            ]
        )->post();

        if( is_wp_error( $response ) ) {
            // FIXME: need to return an error with handler in react app
            return $this->error( 'invalid_data', $response->get_error_message(), 'get_remote_content', 404 );
        }

        if( isset( $response['status'] ) && $response['status'] === 'error' ) {
            return $this->error( 'invalid_data', $response['message'], 'get_content', 404 );
        }
        if( isset( $response['status'] ) && $response['status'] === 'required-pro-acc' ) {
            return $this->error( 'required_pro_templately', $response['message'], 'get_remote_content', 404 );
        }

        if( ! empty( $response[ 'data' ] ) && is_string( $response[ 'data' ] ) ) {
            $data = json_decode( $response[ 'data' ], true );
        } else {
            $data = isset( $response[ 'data' ] ) ? (array) $response[ 'data' ] : [];
        }

        return $data;
    }

    private function get_local_content( $id = null, $platform = 'elementor' ){
        /**
         * @var Elementor $platformInstance
         */
        $platformInstance = $this->platform( $platform );
        $response = $platformInstance->get_content( $id );

        if( isset( $response['status'] ) && $response['status'] === 'error' ) {
            return $this->error( 'invalid_data', $response['message'], 'get_local_content', 404 );
        }

        return $response;
    }

    public function get_content ( $id = null, $platform = 'elementor', $origin = 'remote' ) {
        switch ( $origin ) {
            case 'cloud':
                $template_data = $this->get_cloud_content( $id, $platform );
                break;
            case 'remote':
                $template_data = $this->get_remote_content( $id );
                break;
            case 'local':
            default:
                $template_data = $this->get_local_content( $id, $platform );
                break;
        }

        return $template_data;
    }

    public function import_in_library(){
        $platform = $this->get_param( 'platform', 'elementor' );
        $id       = $this->get_param( 'id', 0, 'intval' );

        if( $id == 0 ) {
            return $this->error('invalid_item_id', __( 'Invalid ID is provided.', 'templately' ), 'import/page', 404 );
        }

        return $this->platform( $platform )->import_in_library( $id, $this );
    }

    public function import_as_page(){
        $platform = $this->get_param( 'platform', 'elementor' );
        $id       = $this->get_param( 'id', 0, 'intval' );
        $title    = $this->get_param( 'title' );

        if( $id == 0 ) {
            return $this->error('invalid_item_id', __( 'Invalid ID is provided.', 'templately' ), 'import/page', 404 );
        }

        return $this->platform( $platform )->create_page( $id, $title, $this );
    }
}