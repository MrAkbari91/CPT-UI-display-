<?php

namespace Templately\API;

use WP_REST_Request;
use Templately\Utils\Database;

use function json_decode;

class Items extends API {
	public function permission_check( WP_REST_Request $request ) {
		$this->request = $request;

		$_route = $request->get_route();
		if( $_route === '/templately/v1/items/favourite' || $_route === '/templately/v1/items/rating' ) {
			return parent::permission_check( $request );
		}

		return true;
	}

	public function register_routes() {
		$this->get( 'items', [ $this, 'get_items' ], [
			'type'     => [
				'default'  => 'items',
				'required' => false,
				// 'validate_callback' => function( $param, $request, $key ){
				// 	return in_array( $param, [ 'items', 'sections', 'blocks', 'packs', 'pages' ], true );
				// }
			],
			'platform' => [
				'default'  => 'elementor',
				'required' => false
			],
			'per_page' => [
				'default'  => 40,
				'required' => false
			],
		] );

		$this->get( 'items/(?P<slug>[a-zA-Z0-9-]+)', [ $this, 'get_item' ], [
			'slug' => [
				'required' => true,
			],
			'type' => [
				'default' => 'block'
			]
		] );

		$this->get( 'items/search/(?P<keyword>[a-zA-Z0-9-]+)', [ $this, 'get_search_results' ], [
			'keyword' => [
				'required' => true,
			],
			'platform' => [
				'default' => 'elementor',
			],
			'query_type' => [
				'default' => 'block',
			],
		] );

		$this->post( 'items/favourite', [ $this, 'set_favourite' ] );
		$this->post( 'items/rating', [ $this, 'set_rating' ] );
		$this->get( 'items-count', [ $this, 'get_counts' ] );
		$this->get( 'featured-items', [ $this, 'featured_items' ] );
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	public function get_query_types( $type = 'blocks' ) {
		$item_types = ( $type === 'blocks' || $type === 'sections' ) ? 'items' : trim( $type );
		return in_array( $item_types, [ 'items', 'pages', 'packs' ], true ) ? $item_types : false;
	}

	public function get_items( WP_REST_Request $request ) {
		$_type            = $this->get_param( 'type', 'blocks' );
		$type             = $this->get_query_types( $_type );
		$plan             = $this->get_param( 'plan', 'all' );
		$plan_type        = $this->get_plan( $plan );
		$platform         = $this->get_param( 'platform', 'elementor' );
		$search           = $this->get_param( 'search' );
		$page             = $this->get_param( 'page', 1, 'intval' );
		$per_page         = $this->get_param( 'per_page', 40, 'intval' );
		$template_type_id = $this->get_param( 'template_type_id', 0, 'intval' );
		$category_id      = $this->get_param( 'category_id', 0, 'intval' );

		$dependencies     = $request->get_param( 'dependencies' );
		$tags             = $request->get_param( 'tags' );

		$funcArgs = [
			'page'     => $page,
			'per_page' => $per_page,
			'platform' => $platform,
		];

		if ( $plan_type > 1 ) {
			$funcArgs['plan_type'] = $plan_type;
		}

		if ( $category_id ) {
			$funcArgs['category_id'] = $category_id;
		}
		if ( $template_type_id > 0 ) {
			$funcArgs['template_type_id'] = $template_type_id;
		}
		if ( ! empty( $dependencies['include'] ) || ! empty( $dependencies['exclude'] ) ) {
			$funcArgs['dependencies'] = wp_slash( json_encode( $dependencies ) );
		}
		if ( ! empty( $tags ) ) {
			$funcArgs['tag_id'] = wp_slash( json_encode( $tags ) );
		}

		if ( ! empty( $search ) ) {
			$funcArgs['search'] = $search;
		}

		$query = 'total_page, current_page, data { id, fullsite_import, name, price, rating, downloads, type, template_type{ slug }, slug, favourite_count, thumbnail, thumbnail2, thumbnail3 }';
		if( $type !== 'packs' ) {
			$query = 'total_page, current_page, data { id, name, price, rating, downloads, type, template_type{ slug }, slug, favourite_count, dependencies{ id, name, icon, plugin_file, plugin_original_slug, is_pro, link }, thumbnail }';
		}

		if( $type === false ) {
			return $this->error( 'invalid_type_call', __( 'Invalid Type Call', 'templately' ) );
		}

		return $this->http()->query(
			$type,
			$query,
			$funcArgs
		)->post();
	}

	public function get_item() {
		$slug  = $this->get_param( 'slug' );
		$_type = $this->get_param( 'type', 'blocks' );
		$type  = $this->get_query_types( $_type );

		if ( empty( $slug ) ) {
			return $this->error(
				'invalid_item_slug',
				__( 'Items slug cannot be empty.', 'templately' ),
				'items/:slug',
				'400'
			);
		}

		if( $type === false ) {
			return $this->error( 'invalid_type_call', __( 'Invalid Type Call', 'templately' ) );
		}

		$items_params = 'id, name, rating, type, description, slug, price, features, favourite_count, thumbnail, downloads, categories{ id, name, slug }, dependencies{ id, name, icon, plugin_file, plugin_original_slug, is_pro, link }, tags{ name, id }, categories{ name, id }, screenshots{ url }, banner, pack{ id, name, slug, items{ id, price, name, type, slug, thumbnail } }, live_url, template_type{ id, name, slug }';
		$params       = 'data { ' . $items_params . ', variations { name, slug, type, platform } }';

		if ( $type == 'packs' ) {
			$params = 'data { id, fullsite_import, name, rating, type, slug, live_url, price, features, favourite_count, thumbnail, downloads, categories{ id, name, slug }, items { ' . $items_params . ' }, variations { name, slug, type, platform } }';
		}

		$response = $this->http()->query( $type, $params, [ 'slug' => $slug ] )->post();

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if( empty( $response['data'] ) ) {
			return $this->error(
				'invalid_response',
				__('Item data not found', 'templately'),
				'/items\/' . $slug,
				'404'
			);
		}

		return current( $response['data'] );
	}

	public function get_search_results( WP_REST_Request $request ) {
		$keyword = $request->get_param( 'keyword' );
		$platform = $request->get_param( 'platform' );
		$query_type = $request->get_param( 'query_type' );

		if ( empty( $keyword ) ) {
			return $this->error(
				'invalid_search_keyword',
				__( 'Search keyword cannot be empty.', 'templately' ),
				'items/search/:keyword',
				'400'
			);
		}

		$funcArgs = [
			'search' => $keyword,
			'platform' => $platform,
			'query_type' => $query_type
		];

		$response = $this->http()->query(
			'getItemsAndPacks',
			'total_page, current_page, data { id, name, rating, type, slug, favourite_count, thumbnail }',
			$funcArgs
		)->post();

		if( is_wp_error( $response ) ){
			return $response;
		}

		$modified_response = [
			'data' => []
		];

		if ( ! empty( $response['data'] )  ) {
			$modified_response['data'] = array_map( function( $item ){
				$item['id'] = (int) $item['id'];
				return $item;
			}, $response['data'] );
			unset( $response['data'] );
		}

		return array_merge( $response, $modified_response );
	}

	public function set_favourite(){
		$id     = $this->get_param( 'id', 0, 'intval' );
		$type   = $this->get_param( 'itemType', 'block' );
		$action = $this->get_param( 'action', 'do' );

		$query = 'status, message, data';
		if( $action === 'undo' ) {
			$query = '';
		}

		$funcArgs = [
			'api_key' => $this->api_key,
			'type_id' => $id,
			'type'    => $type,
		];

		$response = $this->http()->mutation(
			$action === 'undo' ? 'unFavourite' : 'favourite',
			$query,
			$funcArgs
		)->post();

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$_response = [];

		$_data = $response;
		if( $action == 'do' ) {
			if( ! empty( $_data['data'] ) && $_data['status'] === 'success' ) {
				$_data['data'] = $_temp_data = json_decode( $_data['data'] );
				$_temp_data = [ $_temp_data ];

				$_favourites = $this->utils('options')->get('favourites');
				$_favourites = $this->utils('helper')->normalizeFavourites( $_temp_data, $_favourites );
				$this->utils('options')->set('favourites', $_favourites);

				$_response['status'] = 'success';
				$_response['data'] = $_favourites;
			}
		}

		if( $action == 'undo' ) {
			$_temp_data = [
				'id' => $id,
				'type'    => $type == 'block' || $type == 'page' ? 'item' : 'pack'
			];

			if( $response == 1 ) {
				$_favourites = $this->utils('options')->get('favourites');
				$_favourites = $this->utils('helper')->normalizeFavourites( $_temp_data, $_favourites, true );
				$this->utils('options')->set('favourites', $_favourites);

				$_response['status'] = 'success';
				$_response['data'] = $_favourites;
			} else {
				$_response['status'] = 'error';
				$_response['message'] = __( 'Unfavourite Action Failed: Something went wrong.', 'templately' );
			}
		}

		return $_response;
	}

	public function set_rating(){
		$type_id  = $this->get_param( 'type_id', 0, 'intval' );
		$itemType = $this->get_param( 'itemType', 'pack' );
		$rating   = $this->get_param( 'rating', 5, 'intval' );

		$query = 'status, message, data';

		$funcArgs = [
			'api_key' => $this->api_key,
			'type_id' => $type_id,
			'type'    => $itemType,
			'rating'  => $rating,
		];

		$response = $this->http()->mutation(
			'review',
			$query,
			$funcArgs
		)->post();

		if( is_wp_error( $response ) ) {
			return $response;
		}

		if( empty( $response['data'] ) ) {
			return $this->error(
				'invalid_response',
				__('Something went wrong.', 'templately'),
				'/items\/rating',
				'404'
			);
		}

		if( ! empty( $response['data'] ) && $response['status'] === 'success' ) {
			$response['data'] = json_decode( $response['data'], true );

			$_ratings = $this->utils('options')->get('reviews', []);
			// $_reviews = $this->utils( 'helper' )->normalizeReviews( $response['user']['reviews'] );
			$_ratings[ $itemType ][ $type_id ] = $rating;
			$this->utils('options')->set('reviews', $_ratings);

			$_ratings[ $itemType ][ "avg_$type_id" ] = (float) $response['data']['avg_rating'];
			$response['data']['reviews'] = $_ratings;
		}

		return $response;
	}

	public function get_counts(){
		$defaults = Database::get_transient( 'counts' );
		if( $defaults ) {
			return $defaults;
		}

		$defaults = [
			'elementor' => [
				'items' => [
					'total' => '1469',
					'starter' => '964',
					'pro' => '415',
				],
				'blocks' => [
					'total' => '517',
					'starter' => '358',
					'pro' => '159',
				],
				'pages' => [
					'total' => '803',
					'starter' => '506',
					'pro' => '297',
				],
				'packs' => [
					'total' => '149',
					'starter' => '100',
					'pro' => '49',
				]
			],
			'gutenberg' => [
				'blocks' => [
					'total' => '3',
					'starter' => '3',
					'pro' => '0',
				],
				'pages' => [
					'total' => '3',
					'starter' => '3',
					'pro' => '0',
				],
				'packs' => [
					'total' => '3',
					'starter' => '3',
					'pro' => '0',
				],
			]
		];

		$response = $this->http()->query(
			'getCounts',
			'key, value'
		)->post();

		if ( is_wp_error( $response ) ) {
			return $defaults;
		}

		$new_array = [
			'items' => [],
			'blocks' => [],
			'pages' => [],
			'packs' => [],
		];
		$_new_data = [];

		array_walk( $response, function( $item ) use ( &$new_array, &$_new_data ) {
			if( in_array( $item['key'] , ['elementor', 'gutenberg'], true ) ) {
				$values = json_decode($item['value'], true);

				array_walk( $values, function( $_item ) use ( &$new_array ) {
					$new_key = explode('-', $_item['key']);
					if( count( $new_key ) === 2 ) {
						if( isset( $new_array[ $new_key[1] ] )) {
							$new_array[ $new_key[1] ] = array_merge( $new_array[ $new_key[1] ], [  $new_key[0] => $_item['value'] ] );
							$temp_array = $new_array[ $new_key[1] ];
							unset( $temp_array['total'] );
							$new_array[ $new_key[1] ]['total'] = array_sum( $temp_array );
						}
					}
				});

				$_new_data[ $item['key'] ] = $new_array;
			}
		});


		Database::set_transient( 'counts', $_new_data );

		return $_new_data;
	}

	/**
	 * Get Featured Item List
	 * @param WP_REST_Request $request
	 * @return mixed
	 */
	public function featured_items(WP_REST_Request $request){
		$defaults = Database::get_transient( 'featuredItems' );
		if( $defaults ) {
			return $defaults;
		}

		$platform = $request->get_param( 'platform' );
		$funcArgs = [
			// 'page'     => 1,
			// 'per_page' => 10,
			'platform' => $platform,
		];
		$response = $this->http()->query(
			'featuredItems',
			'data{ id, name, slug, price, type, thumbnail }',
			$funcArgs
		)->post();

		if( ! is_wp_error( $response ) ) {
			Database::set_transient( 'featuredItems', $response );
		}

		return $response;
	}
}