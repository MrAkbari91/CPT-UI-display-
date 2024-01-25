<?php

namespace Templately\Core\Importer\Runners;

use Templately\Builder\PageTemplates;
use Templately\Core\Importer\Utils\Utils;
use WP_Error;

class GutenbergContent extends BaseRunner {

	public function get_name(): string {
		return 'content';
	}

	public function get_label(): string {
		return __( 'Block Editor Content', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		return $this->platform == 'gutenberg' && ! empty( $this->manifest['content'] );
	}

	public function should_log(): bool {
		return true;
	}

	public function get_action(): string {
		return 'eventLog';
	}

	public function log_message(): string {
		return __( 'Importing Gutenberg Templates (Pages, Posts etc)', 'templately' );
	}

	public function import( $data, $imported_data ): array {
		$results  = [];
		$contents = $this->manifest['content'];
		$path     = $this->dir_path . 'content' . DIRECTORY_SEPARATOR;

		$processed = 0;
		$total     = array_reduce($contents, function($carry, $item) {
			return $carry + count($item);
		}, 0);

		foreach ( $contents as $type => $posts ) {
			foreach ( $posts as $id => $settings ) {
				$import = $this->import_page_content( $id, $type, $path, $settings );

				if ( ! $import ) {
					$results[ $type ]['failed'][ $id ] = $import;
				} else {
					Utils::import_page_settings( $import, $settings );
					$results[ $type ]['succeed'][ $id ] = $import;
				}

				// Broadcast Log
				$processed += 1;
				$progress   = floor( ( 100 * $processed ) / $total );
				$this->log( $progress, null, 'eventLog' );
			}
		}

		return [ 'content' => $results ];
	}

	/**
	 * @param $id
	 * @param $type
	 * @param $path
	 * @param $settings
	 *
	 * @return false|int|void|WP_Error
	 */
	private function import_page_content( $id, $type, $path, $settings ) {
		try {
			$json_content = Utils::read_json_file( $path . '/' . $type . '/' . $id . '.json' );
			if ( ! empty( $json_content ) ) {

				/**
				 * TODO:
				 *
				 * We can check if there is any data for settings.
				 * if yes: ignore content from insert.
				 *
				 * Process the content while finalizing.
				 */

				$post_data = [
					'post_title'    => $json_content['title'] ?? ucfirst( $type ) . ' - (by Templately)',
					'post_status'   => 'publish',
					'post_type'     => $type,
					'post_content'  => wp_slash( $json_content['content'] ),
					'page_template' => PageTemplates::TEMPLATE_HEADER_FOOTER
				];
				$inserted  = wp_insert_post( $post_data );

				if ( is_wp_error( $inserted ) ) {
					return false;
				}

				return $inserted;
			}
		} catch ( \Exception $e ) {
			return false;
		}
	}
}