<?php

namespace Templately\Core\Importer\Runners;

use Templately\Core\Importer\Utils\Utils;
use Templately\Core\Importer\WPImport;
use Templately\Utils\Helper;

class WPContent extends BaseRunner {
	private $selected_types = [];

	/**
	 * @var int
	 */
	private $total = 1;

	private $processed = [];

	public function get_name(): string {
		return 'wp-content';
	}

	public function get_label(): string {
		return __( 'WordPress Contents', 'templately' );
	}

	public function log_message(): string {
		return __( 'Importing Pages, Posts, Products, Navigation, etc', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		return ! empty( $this->manifest['wp-content'] );
		// return isset( $data[ 'wp-content' ] );
	}

	public function import( $data, $imported_data ): array {
		$path       = $this->dir_path . 'wp-content' . DIRECTORY_SEPARATOR;
		$post_types = $this->filter_post_types( $data['selected_post_types'] ?? [] );
		$taxonomies = [];
		$terms      = [];
		$results    = [];

		$this->import_actions();

		$contents    = $this->manifest['wp-content'];
		$this->total = array_reduce( $contents, function ( $carry, $item ) {
			return $carry + count( $item );
		}, 0 );

		foreach ( $post_types as $type ) {
			if(empty($data['import_demo_content']) && !in_array($type, ['wp_navigation', 'nav_menu_item'])) {
				continue;
			}
			$import = $this->import_type_data( $type, $path, $imported_data, $taxonomies, $terms );

			if ( empty( $import['posts'] ) ) {
				continue;
			}
			$results['wp-content'][ $type ] = $import['posts'];
			$results['terms'][ $type ]      = $import['terms'];
			$imported_data                  = array_merge( $imported_data, $results );
		}

		$this->import_actions( true );

		return $results;
	}

	private function filter_post_types( $selected_custom_post_types = [] ) {
		$wp_builtin_post_types = Utils::get_builtin_wp_post_types();

		foreach ( $selected_custom_post_types as $custom_post_type ) {
			if ( post_type_exists( $custom_post_type ) ) {
				$this->selected_types[] = $custom_post_type;
			}
		}

		$post_types      = array_merge( $wp_builtin_post_types, $this->selected_types );
		$index           = array_search( 'nav_menu_item', $post_types, true );
		$gutenberg_index = array_search( 'wp_navigation', $post_types, true );

		if ( false !== $index ) {
			unset( $post_types[ $index ] );
			$post_types[] = 'nav_menu_item';
		}

		if ( false !== $gutenberg_index ) {
			unset( $post_types[ $gutenberg_index ] );
			$post_types[] = 'wp_navigation';
		}

		return $post_types;
	}

	private function import_type_data( $type, $path, $imported_data, $taxonomies, $terms ): array {
		$args = [
			'fetch_attachments' => true,
			'posts'             => Utils::map_old_new_post_ids( $imported_data ),
			'terms'             => Utils::map_old_new_term_ids( $imported_data ),
			'taxonomies'        => ! empty( $taxonomies[ $type ] ) ? $taxonomies[ $type ] : [],
			'posts_meta'        => [
				self::META_SESSION_KEY => $this->session_id,
			],
			'terms_meta'        => [
				self::META_SESSION_KEY => $this->session_id,
			],
		];

		if ( isset( $imported_data['archive_settings'] ) ) {
			$args['posts'][ $imported_data['archive_settings']['old_id'] ] = $imported_data['archive_settings']['page_id'];
		}

		$file = $path . $type . '/' . $type . '.xml';

		if ( ! file_exists( $file ) ) {
			return [];
		}

		$wp_importer = new WPImport( $file, $args );
		$result      = $wp_importer->run();

		return $result['summary'];
	}

	private function import_actions( $remove = false ) {
		if ( ! $remove ) {
			add_action( 'templately_import.process_post', [ $this, 'post_log' ], 10, 2 );
			add_action( 'templately_import.process_term', [ $this, 'post_log' ], 10, 2 );
			add_action( 'import_start', [ $this, 'update_total' ], 10 );

			return;
		}

		remove_action( 'templately_import.process_post', [ $this, 'post_log' ] );
		remove_action( 'templately_import.process_term', [ $this, 'post_log' ] );
		remove_action( 'import_start', [ $this, 'update_total' ] );
	}

	public function post_log( $post, $result ) {
		if ( isset( $post['post_type'] ) ) {
			if ( ! isset( $this->manifest['wp-content'][ $post['post_type'] ] ) ) {
				return;
			}

			$commonItems = array_intersect(array_keys($result['succeed']), $this->manifest['wp-content'][ $post['post_type'] ]);
			$this->processed[ $post['post_type'] ] = count($commonItems);

			$type  = $post['post_type'];
			$title = $post['post_title'];
		} elseif ( isset( $post['term_id'] ) ) {
			/**
			 * FIXME: We should fix it later, with a proper count of terms and make a total with post itself.
			 */
			return;
		}

		if ( empty( $type ) || empty( $title ) ) {
			return;
		}

		$failed = 0;
		if ( ! empty( $result['failed'] ) ) {
			$failed = count( $result['failed'] );
		}

		$processed = array_reduce( $this->processed, function ( $carry, $item ) {
			return $carry + $item;
		}, 0 );

		$progress = $this->total > 0 ? floor( ( 100 * ( $processed + $failed ) ) / $this->total ) : 100;

		$this->log( $progress);
	}

	public function update_total( $WPImport ) {
		$contents = &$this->manifest['wp-content'];

		foreach ($WPImport->posts as $key => $post) {
			$postType = $post['post_type'];
			$postId = $post['post_id'];

			if (!isset($contents[$postType]) || !in_array($postId, $contents[$postType])) {
				$contents[$postType][] = $postId;
			}
		}

		$this->total = array_reduce( $contents, function ( $carry, $item ) {
			return $carry + count( $item );
		}, 0 );

	}
}