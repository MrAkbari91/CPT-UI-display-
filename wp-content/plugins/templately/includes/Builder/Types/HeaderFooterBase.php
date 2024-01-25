<?php

namespace Templately\Builder\Types;

abstract class HeaderFooterBase extends ThemeTemplate {
	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['condition'] = 'include/general';

		return $properties;
	}
}