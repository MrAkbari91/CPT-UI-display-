<?php

namespace Templately\Builder\Conditions;

class Search extends Condition {
	public function get_type(): string {
		return 'archive';
	}

	public function get_priority(): int {
		return 70;
	}

	public function get_label(): string {
		return __( 'Search', 'templately' );
	}

	public function get_name(): string {
		return 'search';
	}

	public function check( $args = [] ): bool {
		return is_search();
	}
}