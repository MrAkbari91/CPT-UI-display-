<?php

namespace Templately\Core\Importer;

use Exception;
use Templately\Core\Importer\Runners\BaseRunner;
use Templately\Core\Importer\Runners\Customizer;
use Templately\Core\Importer\Runners\ElementorContent;
use Templately\Core\Importer\Runners\ExtraContent;
use Templately\Core\Importer\Runners\Finalizer;
use Templately\Core\Importer\Runners\GutenbergContent;
use Templately\Core\Importer\Runners\Templates;
use Templately\Core\Importer\Runners\WPContent;

class Import {
	use LogHelper;

	/**
	 * @var FullSiteImport
	 */
	public $full_site_import;

	/**
	 * @var array
	 */
	private $manifest;

	private $runners;

	private $imported_data = [];

	/**
	 * @throws Exception
	 */
	public function __construct( $full_site_import ) {
		$this->full_site_import = $full_site_import;

		$this->manifest = $full_site_import->manifest;

		$this->register_runners();
	}

	/**
	 * @throws Exception
	 */
	private function register_runners() {
		$this->runners = [
			// TODO: Site Settings Import Runner
			new ExtraContent( $this->full_site_import ),
			new Templates( $this->full_site_import ),
			new GutenbergContent( $this->full_site_import ),
			new ElementorContent( $this->full_site_import ),
			new WPContent( $this->full_site_import ),
			new Customizer( $this->full_site_import ),
			new Finalizer( $this->full_site_import )
		];
	}

	public function run( $callable = null ): array {
		$data = $this->full_site_import->get_request_params();

		/**
		 * @var BaseRunner $runner
		 */
		foreach ( $this->runners as $runner ) {
			try{
				if ( $runner->should_run( $data, $this->imported_data ) ) {
					if( $runner->get_name() != 'finalize' ) {
						$runner->log();
					}

					$import              = $runner->import( $data, $this->imported_data );
					$this->imported_data = array_merge_recursive( $this->imported_data, $import );

					if( $runner->get_name() != 'finalize' ) {
						$runner->log( 100 );
					} else {
						$runner->log( 100, $runner->log_message() );
					}
				}
			}catch (Exception $e){
				error_log($e->getMessage());
			}
		}

		return $this->imported_data;
	}

	public function get_runners() {
		return $this->runners;
	}
}