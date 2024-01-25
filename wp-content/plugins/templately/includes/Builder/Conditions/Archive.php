<?php

namespace Templately\Builder\Conditions;

class Archive extends Condition {
	protected $sub_conditions = [
		'search'
	];

	public function get_priority(): int {
		return 80;
	}

	public function get_name(): string {
		return 'archive';
	}

	public function get_label(): string {
		return 'Archive';
	}

	public function get_type(): string {
		return 'archive';
	}

	public function get_all_label(): string {
		return esc_html__( 'All Archives', 'templately' );
	}

	public function check( $args = [] ): bool {
		$is_archive = is_archive() || is_home() || is_search();

		// WooCommerce is handled by `woocommerce` module.
		if ( $is_archive && class_exists( 'woocommerce' ) && is_woocommerce() ) {
			$is_archive = is_woocommerce() || is_search() && 'product' === get_query_var( 'post_type' );
		}

		return $is_archive;
	}

	protected function register_sub_conditions() {
		foreach ( $this->builder->get_public_post_types() as $post_type => $label ) {
			if ( ! get_post_type_archive_link( $post_type ) ) {
				continue;
			}

			$condition = new PostTypeArchive( [
				'post_type' => $post_type,
			] );

			$this->register_sub_condition( $condition );
		}
	}
}