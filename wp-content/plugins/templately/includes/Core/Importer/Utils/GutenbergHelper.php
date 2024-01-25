<?php

namespace Templately\Core\Importer\Utils;

class GutenbergHelper extends ImportHelper {
	private $template_settings = [];
	private $forms             = [];

	protected $content;

	protected $post_id;

	/**
	 * @param $template_json
	 * @param $template_settings
	 * @param $extra_content
	 *
	 * @return GutenbergHelper
	 */
	public function prepare( $template_json, $template_settings, $extra_content = [] ) {
		$this->template_settings = $template_settings;
		$this->post_id           = $this->map_post_ids[ $template_settings['post_id'] ];

		$parsed_blocks = parse_blocks( $template_json['content'] );
		if ( ! empty( $extra_content ) ) {
			$this->prepare_form_data( $extra_content, $template_settings );
		}
		$this->replace( $parsed_blocks );
		$this->content = wp_slash( serialize_blocks( $parsed_blocks ) );

		return $this;
	}

	public function update() {
		$this->sse_log('update', 'Updating prepared data, just a moment...', 1, 'eventLog');
		wp_update_post( [
			'ID'           => $this->post_id,
			'post_content' => $this->content
		] );
	}

	private function prepare_form_data( $extra_content, $template_settings ) {
		foreach ( $extra_content as $type => $content ) {
			foreach ( $content as $block_id => $value ) {
				foreach ( $template_settings['data']['form'][ $type ] as $setting ) {
					if ( isset( $setting['id'] ) && $setting['id'] === $block_id ) {
						$this->forms[ $block_id ] = [
							'value' => $value,
							'attr'  => $setting['identifier']
						];
					}
				}

			}
		}
	}

	private function replace( &$blocks ) {
		foreach ( $blocks as &$block ) {
			$this->sse_log('prepare', 'Preparing output for finalize, just a moment...', 1, 'eventLog');
			if ( $block['blockName'] === 'core/navigation' ) {
				if ( ! empty( $block['attrs']['ref'] ) && array_key_exists( $block['attrs']['ref'], $this->map_post_ids ) ) {
					$block['attrs']['ref'] = $this->map_post_ids[ $block['attrs']['ref'] ];
					$this->replace_archive_id( $block['attrs']['ref'] );
				}
			}
			if ( isset( $block['blockId'] ) && array_key_exists( $block['blockId'], $this->forms ) ) {
				$block['attrs'][ $this->forms[ $block['blockId'] ]['attr'] ] = $this->forms[ $block['blockId'] ]['value'];
			}

			if ( ! empty( $block['attrs']['queryData'] ) ) {
				$this->replace_query_data( $block );
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$this->replace( $block['innerBlocks'] );
			}
		}
	}

	private function replace_archive_id( $menu_id ) {
		if ( ! empty( $this->imported_data['archive_settings'] ) ) {
			$post    = get_post( $menu_id );
			$blocks  = parse_blocks( $post->post_content );
			$changed = false;
			foreach ( $blocks as &$block ) {
				$this->sse_log('query', 'Finalizing archive settings, just a moment...', 1, 'eventLog');

				if ( isset( $block['attrs']['id'] ) && $block['attrs']['id'] === $this->imported_data['archive_settings']['archive_id'] ) {
					$changed               = true;
					$block['attrs']['id']  = $this->imported_data['archive_settings']['page_id'];
					$block['attrs']['url'] = get_the_permalink( $this->imported_data['archive_settings']['page_id'] );
				}
			}
			if ( $changed ) {
				wp_update_post( [
					'ID'           => $menu_id,
					'post_content' => wp_slash( serialize_blocks( $blocks ) )
				] );
			}
		}
	}

	/**
	 * @param $block
	 *
	 * @return void
	 */
	private function replace_query_data( &$block ) {
		if ( ! empty( $block['attrs']['queryData']['taxonomies'] ) ) {
			foreach ( $block['attrs']['queryData']['taxonomies'] as &$tax ) {
				$this->sse_log('query', 'Finalizing query data, just a moment...', 1, 'eventLog');
				$tax['value'] = json_decode( $tax['value'], true );
				if ( ! empty( $tax['value'] ) ) {
					foreach ( $tax['value'] as &$val ) {
						if ( array_key_exists( $val['value'], $this->map_term_ids ) ) {
							$val['value'] = $this->map_term_ids[ $val['value'] ];
						}
					}
				}
				$tax['value'] = json_encode( $tax['value'] );
			}
		}
		$this->replace_raw_taxonomies($block);
		if(!empty( $block['attrs']['queryData']['author'])){
			$user = wp_get_current_user();
			$block['attrs']['queryData']['author'] = json_encode([['label' => $user->display_name, 'value' => $user->ID]]);
		}
	}

	/**
	 * @param $block
	 *
	 * @return void
	 */
	private function replace_raw_taxonomies( &$block ) {
		$types = ['category', 'tag'];
		foreach ( $types as $type){
			if(!empty($block['attrs']['queryData'][$type])){
				$block['attrs']['queryData'][$type] = json_decode($block['attrs']['queryData'][$type], true);
				foreach ($block['attrs']['queryData'][$type] as &$term){
					$this->sse_log('prepare', 'Finalizing taxonomies, just a moment...', 1, 'eventLog');
					if ( isset($term['value']) && array_key_exists( $term['value'], $this->map_term_ids ) ) {
						$term['value'] = $this->map_term_ids[ $term['value'] ];
					}
				}
				$block['attrs']['queryData'][$type] = json_encode($block['attrs']['queryData'][$type]);
			}
		}
	}
}