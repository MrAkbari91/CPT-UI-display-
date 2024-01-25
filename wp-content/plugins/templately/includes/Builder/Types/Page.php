<?php

namespace Templately\Builder\Types;

use Templately\Builder\Types\BaseTemplate;

class Page extends BaseTemplate {

	static public function get_type(): string {
		return 'wp-page';
	}

	static public function get_title(): string {
		return __( 'Page', 'templately' );
	}

	static public function get_plural_title(): string {
		return __( 'Pages', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['support_wp_page_templates'] = true;
		$properties['cpt']                       = 'page';
		$properties['builder']                   = false;

		return $properties;
	}
}