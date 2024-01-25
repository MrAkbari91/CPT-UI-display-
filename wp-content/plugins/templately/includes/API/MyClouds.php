<?php

namespace Templately\API;

use Templately\Core\Platform;
use WP_REST_Request;

use function wp_slash;
use function json_encode;
use function json_decode;
use function file_exists;
use function wp_upload_dir;

class MyClouds extends API {

	public function register_routes() {
		$this->get( 'clouds', [ $this, 'get_clouds' ] );
		$this->get( 'clouds/usage', [ $this, 'get_cloud_usage' ] );

		$this->post( 'clouds/copy-move', [ $this, 'copy_or_move' ] );
		$this->post( 'clouds/upload', [ $this, 'upload' ] );

		$this->get( 'clouds/download/(?P<id>[a-zA-Z0-9-]+)', [ $this, 'download' ] );
		$this->get( 'clouds/delete/(?P<id>[a-zA-Z0-9-]+)', [ $this, 'delete' ] );
	}

	public function get_clouds() {
		$page = $this->get_param( 'page', 1, 'intval' );
		$per_page = $this->get_param( 'per_page', 12, 'intval' );
		$platform = $this->get_param( 'platform', 'elementor' );
		$files_search = $this->get_param( 'search' );

		// { myCloud( api_key: "%s", cloud_files_page: %s, cloud_files_per_page: %s, file_type: "%s"%s ) { limit, usages, name, last_pushed, files {  data{ id, name, thumbnail, medium_thumbnail, preview, file_type, created_at }, current_page, total_page } } }

		$funcArgs = [
			'api_key' => $this->api_key,
			'cloud_files_page' => $page,
			'cloud_files_per_page' => $per_page,
			'file_type' => $platform,
		];

		$query = 'limit, usages, last_pushed, files{ data { id, name, last_modified }, current_page, total_page }';

		if( ! empty( $files_search ) ) {
			$funcArgs['files_search'] = $files_search;
			// $query = 'files{ data { id, name, last_modified }, current_page, total_page }';
		}

		$response = $this->http()->query(
			'myCloud',
			$query,
			$funcArgs
		)->post();

		if( ! is_wp_error( $response ) ) {
			if( ! empty( $response['last_pushed'] ) ) {
				$this->options()->set( 'cloud_activity', $response['last_pushed'] );
			}
		}

		return $response;
	}

	public function get_cloud_usage() {
		$response = $this->http()->query(
			'myCloudUsage',
			'data, status, message',
			[
				'api_key' => $this->api_key
			]
		)->post();

        if( is_wp_error( $response ) ) {
            return $this->error(
                'invalid_response', $response->get_error_message(),
                'clouds/usage', 404
            );
        }

        return $response;
	}

	public function copy_or_move(){
		$my_cloud_file_id = $this->get_param( 'my_cloud_id', 0, 'intval' );
		$workspace_id = $this->get_param( 'workspace_id', 0, 'intval' );
		$action = $this->get_param( 'action', 'copy', 'strtolower' );

		$funcArgs = [
			'api_key'          => $this->api_key,
			'my_cloud_file_id' => $my_cloud_file_id,
			'workspace_id'     => $workspace_id,
			'action'           => $action,
		];

		return $this->http()->mutation(
			'copyOrMoveToWorkspace',
			'status, message',
			$funcArgs
		)->post();
	}

	public function upload( WP_REST_Request $request ) {
		$file_content = $request->get_param( 'file_content' );
		$thumbnails   = $request->get_param( 'thumbnails' );

		$name          = $this->get_param( 'name' );
		$file_type     = $this->get_param( 'platform', 'elementor' );
		$id            = $this->get_param( 'id', 0, 'intval' );
		$workspace_id  = $this->get_param( 'workspace_id', 0, 'intval' );

		if( empty( $file_content ) ) {
            /**
             * @var Platform $platform
             */
            $platform = $this->platform( $file_type );
			$template_data = $platform->get_template_data( $id );

			$file_content = $template_data['file_content'];
			if( empty( $name ) ) {
				$name = $template_data['name'];
			}
		}

        if( $file_type === 'elementor' && ! array_key_exists( 'content', $file_content ) ) {
            $file_content = [
                'content' => $file_content,
            ];
        }

        $file_content = wp_slash( json_encode(
            $file_content,
            JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        ) );

		$funcArgs = [
			'api_key'      => $this->api_key,
			'name'         => $name,
			'file_type'    => $file_type,
			'file_content' => $file_content,
		];

		if ( ! empty( $thumbnails ) ) {
			$funcArgs['thumbnail'] = $thumbnails;
		}

		if ( $workspace_id > 0 ) {
			$funcArgs['workspace_id'] = $workspace_id;
		}

		$response = $this->http()->mutation(
			'pushToMyCloud',
			'message, status, data', // 'file, status, message, file_name, file_type',
			$funcArgs
		)->post();

		if( $id > 0 && ! is_wp_error( $response ) && $response['status'] === 'success' ) {
			$data = (array) json_decode($response['data'], true);
			$data = ! empty( $data['myCloud_item'] ) ? $data['myCloud_item'] : 0;
			if ( $data > 0 ) {
				$_templates = $this->utils('options')->get( 'templates_in_clouds', [] );
				$_templates[ $id ] = $data;
				$this->utils('options')->set( 'templates_in_clouds', $_templates );
			}
		}

		return $response;
	}

	public function download() {
		$id       = $this->get_param( 'id', 0, 'intval' );
		$platform = $this->get_param( 'platform', 'elementor' );

		if ( $id <= 0 ) {
			return $this->error(
                'invalid_id', __( 'Invalid Item ID for download cloud item.', 'templately' ),
                'clouds/download/:id', 400
            );
		}

		$response = $this->http()->mutation(
			'downloadMyCloudItem',
			'file, status, message, file_name, file_type',
			[
				'api_key' => $this->api_key,
				'id'      => $id
			]
		)->post();

		if ( ! is_wp_error( $response ) && ! empty( $response['file_name'] ) && ! empty( $response['file'] ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			$upload_dir   = wp_upload_dir();
			$file_content = json_decode( $response['file'], true );

            if( $platform === 'elementor' ) {
                if( is_string( $file_content ) ) {
                    $file_content = [
                        'content' => $file_content
                    ];
                }

                if( ! empty( $file_content ) && is_array( $file_content ) ) {
                    $file_content['page_settings'] = ! empty( $file_content['page_settings'] ) ? $file_content['page_settings'] : [] ;
                    $file_content['version']       = ! empty( $file_content['version'] ) ? $file_content['version'] : '';
                    $file_content['type']          = ! empty( $file_content['type'] ) ? $file_content['type'] : 'section';
                    $file_content['title']         = ! empty( $file_content['title'] ) ? $file_content['title'] : \basename( $response['file_name'], '.json' );
                }
            }

			$file_content = is_array( $file_content ) ? json_encode( $file_content ) : $file_content;

            $file_name       = 'templately-tmp.json';
            $_temp_file_name = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $file_name;
            $_temp_file_url  = $upload_dir['baseurl'] . DIRECTORY_SEPARATOR . $file_name;

			if ( file_exists( $_temp_file_name ) ) {
				unlink( $_temp_file_name );
			}

			$handle = fopen( $_temp_file_name, 'x+' );

			fwrite( $handle, $file_content );
			fclose( $handle );
			$response['fileURL'] = $_temp_file_url;
		}

		return $response;
	}

	public function delete( WP_REST_Request $request ) {
		$_id = $this->get_param( 'id', 0, 'intval' );

		if ( $_id <= 0 ) {
			return $this->error( 'invalid_id', __( 'Invalid Item ID for delete cloud item.', 'templately' ), 'clouds/delete/:id', 400 );
		}

		return $this->http()->query(
			'removeFromMyCloud',
			'status, data, message',
			[
				'api_key' => $this->api_key,
				'file_id' => $_id
			]
		)->post();
	}

}