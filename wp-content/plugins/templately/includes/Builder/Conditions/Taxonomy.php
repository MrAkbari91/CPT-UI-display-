<?php

namespace Templately\Builder\Conditions;

class Taxonomy extends Condition {
	protected $taxonomy;

	public function __construct( $args = [] ) {
		$this->taxonomy = $args['instance'];

		parent::__construct( $args );
	}

	public function get_priority(): int {
		return 70;
	}

	public function get_type(): string {
		return 'archive';
	}

	public function get_label(): string {
		return ucwords( $this->taxonomy->label );
	}

	public function check( $args = [] ): bool {
		$taxonomy = $this->get_name();
		$id       = (int) $args['id'];

		if ( 'category' === $taxonomy ) {
			return is_category( $id );
		}

		if ( 'post_tag' === $taxonomy ) {
			return is_tag( $id );
		}

		return is_tax( $taxonomy, $id );
	}

	public function get_name(): string {
		return $this->taxonomy->name;
	}

	protected function register_controls() {
		$this->add_control( 'taxonomy', [
			'field'      => 'term_id',
			'query_type' => 'taxonomy',
			'options'    => [ '' => 'All' ],
			'query'      => [ 'taxonomy' => $this->get_name() ]
		] );
	}
}