<?php

namespace Templately\Core\Importer\Utils;

use Templately\Core\Importer\LogHelper;

/**
 * @property $imported_data []
 * @property $map_post_ids []
 * @property $map_term_ids []
 */
abstract class ImportHelper {
	use LogHelper;
	protected $imported_data = [];

	public $map_post_ids = [];

	public $map_term_ids = [];

	public function __set( $key, $value ) {
		if ( property_exists( $this, $key ) ) {
			$this->{$key} = $value;
		}
	}
}