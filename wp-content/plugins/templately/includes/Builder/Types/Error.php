<?php

namespace Templately\Builder\Types;

class Error extends Single {
	public static function get_type(): string {
		return 'single';
	}

	public static function get_title(): string {
		return __( '404 Error', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( '404 Error Pages', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location']                  = 'single';
		$properties['condition']                 = 'include/singular/error';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
}