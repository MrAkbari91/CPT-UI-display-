<?php

namespace Templately\Builder\Types;


class Footer extends HeaderFooterBase {
	public static function get_type(): string {
		return 'footer';
	}

	public static function get_title(): string {
		return __( 'Footer', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Footers', 'templately' );
	}

	public function get_name(): string {
		return 'footer';
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location'] = 'footer';

		return $properties;
	}
}