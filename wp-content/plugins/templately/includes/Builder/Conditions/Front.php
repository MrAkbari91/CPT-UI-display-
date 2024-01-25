<?php

namespace Templately\Builder\Conditions;

class Front extends Condition {
	public function get_priority(): int {
		return 30;
	}

	public function get_label(): string {
		return __( 'Front Page', 'templately' );
	}

	public function get_type(): string {
		return 'singular';
	}

	public function get_name(): string {
		return 'front';
	}

	public function check( $args = [] ): bool {
		return is_front_page();
	}
}