<?php

namespace Templately\Builder\Types;


class Archive extends ThemeTemplate {
	public static function get_type(): string {
		return 'archive';
	}

	public static function get_title(): string {
		return __( 'Archive', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Archives', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location']                  = 'archive';
		$properties['condition']                 = 'include/archive/post_archive';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}
}