<?php

namespace Templately\Builder;

use Elementor\Plugin;

class PageTemplates {
	const TEMPLATE_HEADER_FOOTER = 'templately_header_footer';
	private $platform = '';
	private $callback = null;
	private $module   = null;
	private $templates;

	public function __construct() {
		add_filter( 'theme_page_templates', [ $this, 'add_new_template' ] );
		add_filter( 'theme_post_templates', [ $this, 'add_new_template' ] );
		add_filter( 'theme_templately_library_templates', [ $this, 'add_new_template' ] );

		// Add a filter to the save post to inject out template into the page cache.
		// add_filter( 'wp_insert_post_data', [ $this, 'register_templates' ] );

		$this->templates = [
			self::TEMPLATE_HEADER_FOOTER => __( 'Templately Gutenberg Template', 'templately' ),
		];
	}

	public function set_platform( $platform ): PageTemplates {
		$this->platform = $platform;

		if ( $this->platform === 'elementor' ) {
			$this->module = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
		} elseif ( $this->platform === 'gutenberg' ) {
			$this->module = new self();
		}

		return $this;
	}

	public function set_print_callback( $callback ) {
		if ( $this->platform === 'elementor' ) {
			$this->module->set_print_callback( $callback );

			return;
		}

		$this->callback = $callback;
	}

	private function callback() {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}

	public function print() {
		if ( ! $this->callback ) {
			$this->callback = [ $this, 'callback' ];
		}

		call_user_func( $this->callback );
	}

	/**
	 * Get page template path.
	 *
	 * Retrieve the path for any given page template.
	 *
	 * @param string $page_template The page template name.
	 *
	 * @return string Page template path.
	 * @since 2.0.0
	 * @access public
	 *
	 */
	public function get_template_path( string $page_template ): string {
		if ( $this->platform === 'elementor' ) {
			return $this->module->get_template_path( $page_template );
		}

		$template_path = '';

		$file = TEMPLATELY_PATH . 'views/';


		switch ( $page_template ) {
			case self::TEMPLATE_HEADER_FOOTER:
				if ( $this->platform === 'gutenberg' ) {
					$template_path = $file . 'templates/templately-gutenberg-template.php';
				} else {
					$template_path = $file . 'templates/header-footer.php';
				}
				break;
			default:
				break;
		}

		return $template_path;
	}

	public function get_header_footer_template(): string {
		if ( $this->platform === 'elementor' ) {
			return $this->module::TEMPLATE_HEADER_FOOTER;
		}

		return self::TEMPLATE_HEADER_FOOTER;
	}

	public function add_new_template( $posts_templates ) {
		return array_merge( $posts_templates, $this->templates );
	}

	public function register_templates( $atts ) {

		foreach ( [ 'page', 'post' ] as $type ) {
			// Create the key used for the themes cache
			$cache_key = $type . '_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

			// Retrieve the cache list.
			// If it doesn't exist, or it's empty prepare an array.
			$templates = wp_get_theme()->{'get_' . $type . '_templates()'};
			if ( empty( $templates ) ) {
				$templates = [];
			}

			// New cache, therefore remove the old one.
			wp_cache_delete( $cache_key, 'themes' );

			// Now add our template to the list of templates by merging our templates.
			// with the existing templates array from the cache.
			$templates = array_merge( $templates, $this->templates );

			// Add the modified cache to allow WordPress to pick it up for listing.
			// available templates.
			wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		}

		return $atts;
	}
}