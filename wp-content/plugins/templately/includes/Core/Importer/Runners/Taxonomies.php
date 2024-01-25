<?php

namespace Templately\Core\Importer\Runners;

use Templately\Core\Importer\Runners\BaseRunner;

class Taxonomies extends BaseRunner {
	public function get_name(): string {
		return 'taxonomies';
	}

	public function get_label(): string {
		return __( 'Taxonomies', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		return ! empty( $this->manifest['taxonomies'] );
	}

	public function log_message(): string {
		return __( 'Importing Taxonomies', 'templately' );
	}

	public function import( $data, $imported_data ): array {
		// TODO: Implement import() method.

		return [];
	}
}