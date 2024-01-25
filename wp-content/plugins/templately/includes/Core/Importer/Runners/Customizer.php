<?php

namespace Templately\Core\Importer\Runners;

use Exception;
use Templately\Core\Importer\Form;
use Templately\Core\Importer\Runners\BaseRunner;
use Templately\Core\Importer\Utils\Utils;

class Customizer extends BaseRunner {

	public function get_name(): string {
		return 'customizer';
	}

	public function get_label(): string {
		return __( 'Customizer', 'templately' );
	}

	public function should_log(): bool {
		return true;
	}

	public function get_action(): string {
		return 'eventLog';
	}

	public function log_message(): string {
		return __( 'Updating customizer settings.', 'templately' );
	}

	public function should_run( $data, $imported_data = [] ): bool {
		$params = $this->origin->get_request_params();
		return ! empty( $params['title'] ) || !empty( $params['slogan'] );
	}

	public function import( $data, $imported_data ): array {
		$params = $this->origin->get_request_params();
		$customizer = [];

		if( ! empty( $params['title'] ) ) {
			$customizer[] = 'title';
			// set_theme_mod( 'blogname', $params['title'] );
			Utils::update_option( 'blogname', $params['title'] );
		}

		// update Tagline
		if( ! empty( $params['slogan'] ) ) {
			$customizer[] = 'slogan';
			// set_theme_mod( 'blogdescription', $params['slogan'] );
			Utils::update_option( 'blogdescription', $params['slogan'] );
		}

		return  [ 'customizer' => $customizer ];
	}

}