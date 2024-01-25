<?php

namespace Templately\Builder\Managers;

use Templately\Builder\PageTemplates;
use Templately\Builder\Source;
use Templately\Builder\Types\Archive;
use Templately\Builder\Types\BaseTemplate;
use Templately\Builder\Types\CourseArchive;
use Templately\Builder\Types\CourseSingle;
use Templately\Builder\Types\Error;
use Templately\Builder\Types\Footer;
use Templately\Builder\Types\Header;
use Templately\Builder\Types\Page;
use Templately\Builder\Types\Post;
use Templately\Builder\Types\ProductArchive;
use Templately\Builder\Types\ProductSingle;
use Templately\Builder\Types\Single;
use WP_Error;

class TemplateManager {
	private $types     = [];
	private $documents = [];

	public function __construct( $builder ) {
		$this->register_types();
	}

	public function register_types() {
		$types = [
			'header'          => Header::class,
			'footer'          => Footer::class,
			'single'          => Single::class,
			'archive'         => Archive::class,
			'post'            => Post::class,
			'page'            => Page::class,
			'error'           => Error::class,

			// WooCommerce Template Types
			'product_single'  => ProductSingle::class,
			'product_archive' => ProductArchive::class,

			// LMS (LearnDash)
			'course_archive'  => CourseArchive::class,
			'course_single'   => CourseSingle::class,
		];

		$this->types = $types;
	}

	/**
	 * @param       $type
	 * @param       $post_data
	 * @param array $meta
	 *
	 * @return BaseTemplate|WP_Error
	 */
	public function create( $type, $post_data, array $meta = [] ) {
		/**
		 * @var BaseTemplate $class ;
		 */
		$class = $this->get_template_type( $type );

		$should_update_title = false;

		if ( empty( $post_data['post_title'] ) ) {
			$post_data['post_title'] = ucwords( $type ) . ' Template';

			$should_update_title = true;
		}

		$meta = wp_parse_args( $meta, [
			Source::TYPE_META_KEY     => $type,
			Source::PLATFORM_META_KEY => $meta['platform'] ?? 'gutenberg'
		] );

		if ( isset( $meta['platform'] ) ) {
			unset( $meta['platform'] );
		}

		if ( $meta[ Source::PLATFORM_META_KEY ] == 'elementor' ) {
			$meta['_wp_page_template'] = 'elementor_header_footer';
		} elseif ( $meta[ Source::PLATFORM_META_KEY ] == 'gutenberg' ) {
			$meta['_wp_page_template'] = PageTemplates::TEMPLATE_HEADER_FOOTER;
		}

		$post_data['meta_input'] = $meta;

		$post_type = $class::get_property( 'cpt' );
		if ( ! empty( $post_type ) && empty( $post_data['post_type'] ) ) {
			$post_data['post_type'] = $post_type;
		}

		$post_id = wp_insert_post( $post_data );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		if ( $should_update_title ) {
			$post_data['ID']         = $post_id;
			$post_data['post_title'] .= " #{$post_id} (by Templately)";
			unset( $post_data['meta_input'] );

			wp_update_post( $post_data );
		}

		return new $class( [
			'post_id' => $post_id
		] );
	}

	private function get_template_type( $type, $default = 'post' ): string {
		if ( isset( $this->types[ $type ] ) ) {
			return $this->types[ $type ];
		}

		return $this->types[ $default ];
	}

	/**
	 *
	 * @param int  $post_id
	 * @param bool $from_cache
	 *
	 * @return ThemeTemplate||false
	 */
	public function get( int $post_id, $from_cache = true ) {
		$this->register_types();

		$post_id = absint( $post_id );

		if ( ! $post_id || ! get_post( $post_id ) ) {
			return false;
		}

		if ( ! $from_cache || ! isset( $this->documents[ $post_id ] ) ) {
			$doc_type       = $this->get_template_type_by_id( $post_id );
			$doc_type_class = $this->get_template_type( $doc_type );

			$this->documents[ $post_id ] = new $doc_type_class( [
				'post_id' => $post_id,
			] );
		}

		return $this->documents[ $post_id ];
	}

	private function get_template_type_by_id( int $post_id ) {
		// Auto-save inherits from the original post.
		if ( wp_is_post_autosave( $post_id ) ) {
			$post_id = wp_get_post_parent_id( $post_id );
		}

		$template_type = get_post_meta( $post_id, Source::TYPE_META_KEY, true );

		if ( $template_type && isset( $this->types[ $template_type ] ) ) {
			return $template_type;
		}

		return 'post';
	}

	public function get_template_types(): array {
		$this->register_types();

		return $this->types;
	}
}