<?php

namespace Templately\Builder\Managers;

use Templately\Builder\PageTemplates;
use Templately\Builder\Source;
use Templately\Builder\ThemeBuilder;
use Templately\Builder\Types\BaseTemplate;
use Templately\Builder\Types\ThemeTemplate;

class LocationManager {
	/**
	 * @var array<string, ThemeTemplate>
	 */
	public $locations_queue   = [];
	public $locations_skipped = [];
	public $locations_printed = [];
	public $did_locations     = [];

	protected $locations = [];

	/**
	 * @var ThemeBuilder
	 */
	protected $builder;

	public function __construct( $builder ) {
		$this->builder = $builder;

		/**
		 * Don't know yet if we need it or not.
		 */
		$this->set_locations();

		/**
		 * Priority is 13,
		 * Because it should be run after elementor & woocommerce
		 */
		add_filter( 'template_include', [ $this, 'template_include' ], 13 );
	}

	private function set_locations() {
		$this->locations = [
			'header'  => [
				'label'    => __( 'Header', 'templately' ),
				'multiple' => false
			],
			'footer'  => [
				'label'    => __( 'Footer', 'templately' ),
				'multiple' => false
			],
			'archive' => [
				'label'    => __( 'Archive', 'templately' ),
				'multiple' => false
			],
			'single'  => [
				'label'    => __( 'Single', 'templately' ),
				'multiple' => false
			]
		];
	}

	public function template_include( $template_path ) {
		$location             = '';
		$page_template_module = $this->get_template_module();

		/**
		 * Return if it is Elementor Template.
		 * This should be elementor's responsibility.
		 */

		if ( get_post_type( get_the_ID() ) === 'elementor_library' ) {
			return $template_path;
		}
		else if( $this->get_platform(get_the_ID()) === 'elementor' && !class_exists('Elementor\Plugin') ) {
			return $template_path;
		}

		if ( is_singular() ) {
			/**
			 * @var BaseTemplate $template
			 */
			$template = $this->builder::$templates_manager->get( get_the_ID() );

			if ( $template && $template->get_property( 'support_wp_page_templates' ) ) {
				$page_template_module->set_platform( $template->get_platform() );
				$wp_page_template = $template->get_meta( '_wp_page_template' );

				$_custom_template_path = $page_template_module->get_template_path( $wp_page_template );


				if ( empty( $_custom_template_path ) ) {
					$location = 'single';

					$templates_for_location = $this->builder::$conditions_manager->get_templates_by_location( $location );

					if ( empty( $templates_for_location ) ) {
						return $template_path;
					}

					$template_id = key( $templates_for_location );

					$template      = $templates_for_location[ $template_id ];
					$page_template = $template->get_meta( '_wp_page_template' );
					$platform      = $template->get_platform();

					if ( ! empty( $platform ) ) {
						$page_template_module->set_platform( $platform );
					}
					$path = $page_template_module->get_template_path( $page_template );
					$page_template_module->set_print_callback( function () use ( $location ) {
						$this->do_location( $location );
					} );
					set_query_var( 'using_templately_template', 1 );

					return $path;
				}

				if ( $wp_page_template && $wp_page_template !== 'default' ) {
					set_query_var( 'using_templately_template', 1 );

					return $_custom_template_path;
				}
			}
		} else {
			$template = false;
		}

		if ( $template instanceof ThemeTemplate ) {
			$location = $template->get_location();
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$location = 'archive';
		} elseif ( is_archive() || is_tax() || is_home() || is_search() ) {
			$location = 'archive';
		} elseif ( is_singular() || is_404() ) {
			$location = 'single';
		}

		if ( $location ) {
			$templates_for_location = $this->builder::$conditions_manager->get_templates_by_location( $location );

			if ( empty( $templates_for_location ) ) {
				set_query_var( 'using_templately_template', 1 );

				return $template_path;
			}

			if ( 'single' === $location || 'archive' === $location ) {
				$template_id            = key( $templates_for_location );
				$template               = $templates_for_location[ $template_id ];
				$document_page_template = $template->get_meta( '_wp_page_template' );
				$platform               = $template->get_platform();

				if ( ! empty( $platform ) ) {
					$page_template_module->set_platform( $platform );
				}

				if ( $document_page_template ) {
					$page_template = $document_page_template;
				}
			}
		}
		$is_header_footer = 'header' === $location || 'footer' === $location;
		if ( empty( $page_template ) && ! $is_header_footer ) {
			$page_template = $page_template_module->get_header_footer_template();
		}

		if ( ! empty( $page_template ) ) {
			$path = $page_template_module->get_template_path( $page_template );

			if ( $path ) {
				$page_template_module->set_print_callback( function () use ( $location ) {
					$this->do_location( $location );
				} );
				set_query_var( 'using_templately_template', 1 );
				$template_path = $path;
			}
		}

		return $template_path;
	}

	private function get_platform($post_id) {
		$post_type = get_post_type( get_the_ID() );
		if ( $post_type == Source::CPT ) {
			$platform = get_post_meta( $post_id, Source::PLATFORM_META_KEY, true );
		} elseif ( get_post_meta( $post_id, '_elementor_edit_mode', true ) == 'builder' || $post_type == 'elementor_library' ) {
			$platform = 'elementor';
		} else {
			$platform = 'gutenberg';
		}
		return $platform;
	}

	/**
	 * Get page templating modules and set platform if needed.
	 *
	 * @param string $platform
	 *
	 * @return PageTemplates
	 */
	private function get_template_module( string $platform = '' ): PageTemplates {
		$module = templately()->theme_builder::$page_template_module;

		if ( ! empty( $platform ) ) {
			$module = $module->set_platform( $platform );
		}

		return $module;
	}

	public function get_location( $location ) {
		$locations = $this->get_locations();

		return $locations[ $location ] ?? [];
	}

	public function get_locations(): array {
		$this->register_locations();

		return $this->locations;
	}

	public function register_locations() {
		if ( ! did_action( 'templately_locations' ) ) {
			do_action( 'templately_locations', $this );
		}
	}

	/**
	 * Getting the Idea From Elementor itself.
	 *
	 * @param $location
	 *
	 * @return bool
	 */
	public function do_location( $location ): bool {
		$templates_for_location = $this->builder::$conditions_manager->get_templates_by_location( $location );

		foreach ( $templates_for_location as $template_id => $template ) {
			$this->add_template_to_location( $location, $template_id );
		}

		if ( empty( $this->locations_queue[ $location ] ) ) {
			return false;
		}

		while ( ! empty( $this->locations_queue[ $location ] ) ) {
			$template_id = key( $this->locations_queue[ $location ] );
			$template    = $this->builder->get_template( $template_id );

			if ( ! $template || $this->is_printed( $location, $template_id ) ) {
				$this->skip_template_from_location( $location, $template_id );
				continue;
			}

			if ( empty( $documents_by_conditions[ $template_id ] ) ) {
				$post_status = get_post_status( $template_id );
				if ( 'publish' !== $post_status ) {
					$this->skip_template_from_location( $location, $template_id );
					continue;
				}
			}
			$template->print_content();
			$this->did_locations[] = $location;

			$this->set_is_printed( $location, $template_id );
		}

		return true;
	}

	/**
	 * @param string  $location
	 * @param integer $template_id
	 */
	public function add_template_to_location( string $location, int $template_id ) {
		if ( isset( $this->locations_skipped[ $location ][ $template_id ] ) ) {
			return;
		}

		if ( ! isset( $this->locations_queue[ $location ] ) ) {
			$this->locations_queue[ $location ] = [];
		}

		$this->locations_queue[ $location ][ $template_id ] = $template_id;
	}

	public function is_printed( $location, $template_id ): bool {
		return isset( $this->locations_printed[ $location ][ $template_id ] );
	}

	public function skip_template_from_location( $location, $template_id ) {
		$this->remove_template_from_location( $location, $template_id );

		if ( ! isset( $this->locations_skipped[ $location ] ) ) {
			$this->locations_skipped[ $location ] = [];
		}

		$this->locations_skipped[ $location ][ $template_id ] = $template_id;
	}

	public function remove_template_from_location( $location, $template_id ) {
		unset( $this->locations_queue[ $location ][ $template_id ] );
	}

	public function set_is_printed( $location, $template_id ) {
		if ( ! isset( $this->locations_printed[ $location ] ) ) {
			$this->locations_printed[ $location ] = [];
		}

		$this->locations_printed[ $location ][ $template_id ] = $template_id;
		$this->remove_template_from_location( $location, $template_id );
	}
}