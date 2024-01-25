<?php

namespace Templately\Builder\Types;


class Search extends ThemeTemplate {
	public static function get_type(): string {
		return 'search';
	}

	public static function get_title(): string {
		return __( 'Search Result', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Search Results', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location']                  = 'archive';
		$properties['condition']                 = 'include/archive/search';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
}