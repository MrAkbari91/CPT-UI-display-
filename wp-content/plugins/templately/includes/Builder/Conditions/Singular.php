<?php

namespace Templately\Builder\Conditions;

class Singular extends Condition {
	protected $sub_conditions = [
		'front',
		'error'
	];

	public function get_type(): string {
		return 'singular';
	}

	public function get_priority(): int {
		return 60;
	}

	public function get_label(): string {
		return __( 'Singular', 'templately' );
	}

	public function get_all_label(): string {
		return __( 'All Singular', 'templately' );
	}

	public function get_name(): string {
		return 'singular';
	}

	public function check( $args = [] ): bool {
		return ( is_singular() && ! is_embed() ) || is_404();
	}

	protected function register_sub_conditions() {
		$post_types = $this->builder->get_public_post_types();

		foreach ( $post_types as $post_type => $label ) {
			if ( ! get_post_type_archive_link( $post_type ) ) {
				continue;
			}

			$condition = new Post( [
				'post_type' => $post_type,
			] );

			$this->register_sub_condition( $condition );
		}
	}
}