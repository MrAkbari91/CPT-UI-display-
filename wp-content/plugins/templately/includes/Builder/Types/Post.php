<?php

namespace Templately\Builder\Types;

class Post extends BaseTemplate {
	public static function get_type(): string {
		return 'wp-post';
	}

	public static function get_title(): string {
		return __( 'Post', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Posts', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['support_wp_page_templates'] = true;
		$properties['cpt']                       = 'post';
		$properties['builder']                   = false;

		return $properties;
	}
}