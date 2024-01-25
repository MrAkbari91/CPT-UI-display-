<?php

namespace Templately\Core\Importer;

use Exception;
use FluentForm\App\Services\Transfer\TransferService;
use FluentForm\Framework\Request\File;

class Form {
	protected $plugin;
	protected $file;
	protected $settings = [];

	public function __construct( $plugin, $file, $settings = [] ) {
		$this->plugin   = $plugin;
		$this->file     = $file;
		$this->settings = $settings;
	}

	/**
	 * @throws Exception
	 */
	public function run() {
		if ( ! file_exists( $this->file ) ) {
			throw new Exception( __( 'Form JSON does not exists.', 'templately' ) );
		}

		$data = $this->import();

		if ( ! $data ) {
			throw new Exception( __( 'Cannot be imported.', 'templately' ) );
		}

		return $data;
	}

	public function import() {
		try {
			$results = null;
			switch ( $this->plugin ) {
				case 'fluent-forms' && is_plugin_active( 'fluentform/fluentform.php' ) :
					$fileObject = new File( $this->file, '' );
					$importer   = new TransferService();
					$inserted   = $importer->importForms( $fileObject );
					$results    = key( $inserted['inserted_forms'] );
					break;
				default:
					break;
			}

			return $results;

		} catch ( Exception $e ) {
			return null;
		}
	}
}