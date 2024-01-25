<?php

namespace Templately\Builder\Conditions;


class PostTypeArchive extends Condition {
	protected $post_type;
	protected $post_taxonomies = [];

	public function __construct( $args = [] ) {
		$this->post_type = get_post_type_object( $args['post_type'] );
		$taxonomies      = get_object_taxonomies( $args['post_type'], 'objects' );

		$this->post_taxonomies = wp_filter_object_list( $taxonomies, [
			'public'            => true,
			'show_in_nav_menus' => true,
		] );

		parent::__construct( $args );
	}

	public function get_priority(): int {
		return 70;
	}

	public function get_name(): string {
		return $this->post_type->name . '_archive';
	}

	public function get_all_label(): string {
		return sprintf( __( '%s Archive', 'templately' ), $this->post_type->label );
	}

	public function get_label(): string {
		return sprintf( __( '%s Archive', 'templately' ), $this->post_type->labels->singular_name );
	}

	public function get_type(): string {
		return 'archive';
	}

	public function check( $args = [] ): bool {
		return is_post_type_archive( $this->post_type->name ) || ( 'post' === $this->post_type->name && is_home() );
	}

	protected function register_sub_conditions() {
		if ( ! empty( $this->post_taxonomies ) ) {
			foreach ( $this->post_taxonomies as $taxonomy => $taxonomy_object ) {
				$condition = new Taxonomy( [
					'taxonomy' => $taxonomy,
					'instance' => $taxonomy_object
				] );

				$this->register_sub_condition( $condition );
			}
		}

		if ( $this->post_type->name === 'product' ) {
			$condition = new ProductSearch();
			$this->register_sub_condition( $condition );
		}
	}
}