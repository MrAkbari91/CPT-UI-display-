<?php

namespace Templately\Builder\Types;

class Single extends ThemeTemplate {
	public static function get_type(): string {
		return 'single';
	}

	public static function get_title(): string {
		return __( 'Single', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Singles', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location']                  = 'single';
		$properties['condition']                 = 'include/singular/post';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
}