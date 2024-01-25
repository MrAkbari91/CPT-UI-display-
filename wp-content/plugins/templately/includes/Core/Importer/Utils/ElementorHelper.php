<?php

namespace Templately\Core\Importer\Utils;

use Templately\Builder\Types\BaseTemplate;

class ElementorHelper extends ImportHelper {
	protected $content;

	protected $post_id;

	private $nav_menus = [];

	private $ea_post_widgets = [
		"eael-post-grid",
		"eael-post-list",
		"eael-post-timeline",
		"eael-content-timeline",
		"eael-dynamic-filterable-gallery",
		"eael-post-carousel",
		"eael-post-block",
		"eael-woo-product-carousel",
		"eael-woo-product-slider"
	];

	private $el_post_widgets = [
		'posts',
		'portfolio',
		'archive-posts',
		'woocommerce-products'
	];

	/**
	 * @param $template_json
	 * @param $template_settings
	 * @param $extra_content
	 *
	 * @return ElementorHelper
	 */
	public function prepare( $template_json, $template_settings, $extra_content = [] ) {

		$extraContent = $extra_content;
		$_data        = [];
		foreach ( $template_settings['data'] as $type => $type_data ) {
			if ( empty( $type_data ) ) {
				continue;
			}

			if ( $type == 'nav_menus' ) {
				$this->nav_menus = $type_data;
			}

			if ( $type == 'form' || $type == 'post_type' ) {
				foreach ( $type_data as $plugin => $plugin_data ) {
					if ( $type == 'post_type' ) {
						$_data[ $plugin_data['id'] ] = [
							'type'  => $type,
							'query' => $plugin_data['query']
						];
					} else {
						foreach ( $plugin_data as $value ) {
							if ( empty( $value['settings'] ) ) {
								continue;
							}

							foreach ( $value['settings'] as $key => $v ) {
								if ( ! isset( $extraContent[ $plugin ] ) ) {
									continue;
								}
								$_data[ $value['id'] ][ $key ] = $extraContent[ $plugin ][ $value['id'] ];
							}
						}
					}
					$this->sse_log('prepare', 'Preparing output for finalize, just a moment...', 1, 'eventLog');
				}
			}
			$this->sse_log('prepare', 'Preparing output for finalize, just a moment...', 1, 'eventLog');
		}

		// $content = $template_json;
		$this->json_prepare( $template_json['content'], $_data );
		$this->post_id                    = $this->map_post_ids[ $template_settings['post_id'] ];
		$this->content                    = $template_json;
		$this->content['import_settings'] = $template_settings;

		return $this;
	}

	public function update() {
		/**
		 * @var BaseTemplate $template
		 */
		$template = templately()->theme_builder::$templates_manager->get( $this->post_id );
		$this->sse_log('update', 'Updating prepared data, just a moment...', 1, 'eventLog');
		$template->import( $this->content );

		$this->nav_menus = [];
		$this->content   = [];
	}

	private function json_prepare( &$elements, $data ) {
		foreach ( $elements as &$element ) {
			if ( ! empty( $data ) ) {
				foreach ( $data as $id => $settings ) {
					if ( $element['id'] == $id ) {
						if ( isset( $settings['type'] ) && $settings['type'] == 'post_type' ) {
							$this->replace_query_data( $element, $settings['query'] );
						} else {
							foreach ( $settings as $key => $value ) {
								$element['settings'][ $key ] = $value;
							}
						}
						unset( $data[ $id ] );
					}
				}
			}

			/**
			 * Menu Update if needed.
			 */
			if ( ! empty( $this->nav_menus ) ) {
				$this->nav_menu_update( $element );
			}

			if ( ! empty( $element['elements'] ) ) {
				$this->json_prepare( $element['elements'], $data );
			}
			$this->sse_log('prepare', 'Preparing output for finalize, just a moment...', 1, 'eventLog');
		}
	}

	private function nav_menu_update( &$element ) {
		$this->sse_log('nav-menu', 'Updating nav menus, just a moment...', 1, 'eventLog');
		if ( ! isset( $element['widgetType'] ) ) {
			return;
		}

		switch ( $element['widgetType'] ) {
			case 'eael-simple-menu':
				$element['settings']['eael_simple_menu_menu'] = $this->map_term_ids[ $element['settings']['eael_simple_menu_menu'] ];
				break;
			case 'eael-advanced-menu':
				$element['settings']['eael_advanced_menu_menu'] = $this->map_term_ids[ $element['settings']['eael_advanced_menu_menu'] ];
				break;
		}
	}

	private function replace_query_data( &$element, $data ) {
		$this->sse_log('query', 'Finalizing query data, just a moment...', 1, 'eventLog');
		if ( ! empty( $element['widgetType'] ) ) {
			if ( in_array( $element['widgetType'], $this->ea_post_widgets ) ) {
				if ( ! empty( $data['tax_query'] ) ) {
					foreach ( $data['tax_query'] as $key => $tax_query ) {
						if ( $key === 'relation' ) {
							continue;
						}
						if ( isset( $element['settings'][ $tax_query['taxonomy'] . '_ids' ] ) ) {
							$new_ids = [];
							foreach ( $element['settings'][ $tax_query['taxonomy'] . '_ids' ] as $id ) {
								if ( isset( $this->map_term_ids[ $id ] ) ) {
									$new_ids[] = $this->map_term_ids[ $id ];
								}
							}
							$element['settings'][ $tax_query['taxonomy'] . '_ids' ] = $new_ids;
						}
					}
				}
				if ( ! empty( $element['settings']['authors'] ) ) {
					$element['settings']['authors'] = [ get_current_user_id() ];
				}
			}
			if ( in_array( $element['widgetType'], $this->el_post_widgets ) ) {
				if ( ! empty( $data['tax_query'] ) ) {
					$this->replace_term_ids( $element, [
						'posts_include_term_ids',
						'posts_exclude_term_ids',
						'query_include_term_ids',
						'query_exclude_term_ids',
					] );

				}
				if ( ! empty( $data['author__in'] ) ) {
					$keys = [ 'posts_include_authors', 'query_include_authors' ];
					foreach ( $keys as $key ) {
						if ( isset( $element['settings'][ $key ] ) ) {
							$element['settings'][ $key ] = [ get_current_user_id() ];
						}
					}
				}
			}
			if ( $element['widgetType'] == 'eael-woo-product-gallery' ) {
				$this->replace_term_ids( $element, [ 'eael_product_gallery_categories', 'eael_product_gallery_tags' ] );

			}
			if ( $element['widgetType'] == 'eicon-woocommerce' ) {
				$this->replace_term_ids( $element, [ 'eael_product_grid_categories' ] );
			}
		}
	}

	private function replace_term_ids( &$element, $keys ) {
		foreach ( $keys as $key ) {
			$this->sse_log('terms', 'Finalizing term ids, just a moment...', 1, 'eventLog');
			if ( ! empty( $element['settings'][ $key ] ) ) {
				$new_ids = [];
				foreach ( $element['settings'][ $key ] as $id ) {
					if ( isset( $this->map_term_ids[ $id ] ) ) {
						$new_ids[] = $this->map_term_ids[ $id ];
					}
				}
				if(!empty($new_ids)){
					$element['settings'][ $key ] = $new_ids;
				}
			}

		}
	}
}