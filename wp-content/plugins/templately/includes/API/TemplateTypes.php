<?php
namespace Templately\API;

use Templately\Utils\Database;
use WP_REST_Request;

/**
 * TemplateTypes Class
 *
 * @property mixed $_items
 *
 * @since 2.0.0
 * @package Templately\API
 */
class TemplateTypes extends API {
    private $endpoint = 'templateTypes';

	public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;
		return true;
	}

	public function register_routes() {
		$this->get( $this->endpoint, [ $this, 'get_types' ] );
	}

	public function get_types() {
        /**
         * Return if there is any cache data.
         */
		$_type = $this->get_param( 'itemType', 'page' );
		$_type = rtrim( $_type, 's');

        $types = Database::get_transient( $this->endpoint );
		if( ! empty( $types ) && ! empty( $types[ $_type ] ) ){
			return $this->success( $types[ $_type ] );
		}

		$id       = $this->get_param( 'id', 0, 'intval' );
		$funcArgs = [];

		if ( $id > 0 ) {
			$funcArgs['id'] = $id;
		}

		$query_params = 'item_template_types{id, name, slug}, page_template_types{id, name, slug}, pack_template_types{id, name, slug}';
		$response = $this->http()->query( 'groupedTemplateTypes', $query_params, $funcArgs )->post();
        /**
         * Caching the response in transient.
         */
		if( ! is_wp_error( $response ) ) {
			$this->_items = [
				'block' => [],
				'page' => [],
				'pack' => [],
			];
			if( is_array( $response ) ) {
				array_walk( $response, function( $_item, $key ) {
					switch( $key ) {
						case 'item_template_types':
							$this->_items['block'] = $_item;
							break;
						case 'page_template_types':
							$this->_items['page'] = $_item;
							break;
						case 'pack_template_types':
							$this->_items['pack'] = $_item;
							break;
					}
				} );

				Database::set_transient( $this->endpoint, $this->_items );
			}

			return $this->_items[ $_type ];
		}

        return $response;
	}
}