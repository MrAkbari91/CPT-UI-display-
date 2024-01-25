<?php

namespace Templately\Builder\Conditions;

class Error extends Condition {
	public function get_priority(): int {
		return 20;
	}

	public function get_label(): string {
		return __( '404 Error', 'templately' );
	}

	public function get_type(): string {
		return 'singular';
	}

	public function get_name(): string {
		return 'error';
	}

	public function check( $args = [] ): bool {
		return is_404();
	}
}