<?php

namespace Templately\Builder\Types;

class ProductArchive extends Archive {
	public static function get_type(): string {
		return 'product_archive';
	}

	public static function get_title(): string {
		return __( 'Product Archive', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Product Archives', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['builder']   = post_type_exists( 'product' );
		$properties['condition'] = 'include/archive/product_archive';

		return $properties;
	}
}