<?php

namespace Templately\Core\Importer\Runners;

use Exception;
use Templately\Builder\Factory\TemplateFactory;
use Templately\Core\Importer\FullSiteImport;
use Templately\Core\Importer\Import;
use Templately\Core\Importer\LogHelper;
use Templately\Core\Importer\Utils\ImportHelper;
use Templately\Core\Importer\Utils\Utils;

abstract class BaseRunner {
	use LogHelper;

	const META_SESSION_KEY = '_templately_import_session_id';
	/**
	 * @var FullSiteImport
	 */
	protected $origin;
	protected $platform;
	protected $manifest;
	protected $dir_path;
	protected $session_id;

	/**
	 * @var ImportHelper
	 */
	protected $json;

	/**
	 * @var TemplateFactory
	 */
	protected $factory;

	/**
	 * @throws Exception
	 */
	public function __construct( FullSiteImport $full_site_import ) {
		$this->origin   = $full_site_import;
		$this->dir_path = $full_site_import->dir_path;
		$this->manifest = $full_site_import->manifest;
		$this->platform = $this->manifest['platform'] ?? '';
		$this->session_id = $full_site_import->session_id;


		$this->factory = new TemplateFactory( $this->platform );
		$this->json = Utils::get_json_helper( $this->platform );

		if ( empty( $this->platform ) ) {
			throw new Exception( __( 'Platform is not specified. Please try again after specifying the platform.', 'templately' ) );
		}
	}

	public function should_log(): bool {
		return true;
	}

	public function get_action(): string {
		return 'updateLog';
	}

	abstract public function get_name(): string;

	abstract public function get_label(): string;

	abstract public function should_run( $data, $imported_data = [] ): bool;

	abstract public function import( $data, $imported_data ): array;

	abstract public function log_message() : string;

	public function log( $progress = 0, $message = null, $action = null ) {
		if( ! $this->should_log() ) {
			return;
		}

		if( $message == null ) {
			$message = $this->log_message();
		}

		if( $action == null ) {
			$action = $this->get_action();
		}

		$this->sse_log( $this->get_name(), $message, min( $progress, 100 ), $action );
	}
}