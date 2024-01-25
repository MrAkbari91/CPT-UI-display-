<?php

namespace Templately\Builder\Conditions;

class ProductSearch extends Search {
	public function get_priority(): int {
		return 40;
	}

	public function get_label(): string {
		return __( 'Product Search', 'templately' );
	}

	public function get_name(): string {
		return 'product_search';
	}

	public function check( $args = [] ): bool {
		return is_search() && 'product' === get_query_var( 'post_type' );
	}
}