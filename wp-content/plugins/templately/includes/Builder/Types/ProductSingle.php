<?php

namespace Templately\Builder\Types;

class ProductSingle extends Single {
	public static function get_type(): string {
		return 'product_single';
	}

	public static function get_title(): string {
		return __( 'Product Single', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Products Single', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['condition'] = 'include/singular/product';
		$properties['builder']   = post_type_exists( 'product' );

		return $properties;
	}
}