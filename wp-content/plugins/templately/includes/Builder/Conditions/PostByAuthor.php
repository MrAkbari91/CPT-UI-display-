<?php

namespace Templately\Builder\Conditions;

class PostByAuthor extends Condition {
	private $post_type;

	public function __construct( $args = [] ) {
		$this->post_type = $args;

		parent::__construct( $args );
	}

	public function get_priority(): int {
		return 40;
	}

	public function get_label(): string {
		return sprintf( __( '%s By Author', 'templately' ), $this->post_type->label );
	}

	public function get_type(): string {
		return 'singular';
	}

	public function get_name(): string {
		return $this->post_type->name . '_by_author';
	}

	public function check( $args = [] ): bool {
		if ( ! isset( $args['id'] ) ) {
			return false;
		}

		return is_singular( $this->post_type->name ) && get_post_field( 'post_author' ) === $args['id'];
	}

	protected function register_controls() {
		$this->add_control( $this->get_name(), [
			'field'      => 'ID',
			'query_type' => 'authors',
			'options'    => [ '' => 'All' ],
			'query'      => []
		] );
	}
}