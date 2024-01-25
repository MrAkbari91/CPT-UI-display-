<?php

namespace Templately\API;

use Templately\API\API;
use WP_REST_Request;
use WP_REST_Response;

class FullSiteImport extends API {
	public function register_routes() {
		$this->get( 'site-import/(?P<id>[0-9]+)', [ $this, 'site_import' ] );
	}


}