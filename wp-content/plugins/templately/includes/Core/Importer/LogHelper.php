<?php

namespace Templately\Core\Importer;

use Templately\Utils\Helper;

trait LogHelper {
	private $log_types = [
		''
	];

	public function sse_log( $type, $message, $progress = 1, $action = 'updateLog', $status = null ) {
		$data = [
			'action'   => $action,
			'type'     => $type,
			'progress' => $progress,
			'message'  => $message
		];

		if ( $progress == 100 && $status == null ) {
			$data['status'] = 'complete';
		} elseif ( $status != null ) {
			$data['status'] = $status;
		}

		$this->sse_message( $data );
	}

	public function removeLog( $type ) {
		$this->sse_message( [
			'action'   => 'removeLog',
			'type'     => $type,
			'progress' => 100
		] );
	}

	public function sse_message( $data ) {
		echo "event: message\n";
		echo 'data: ' . wp_json_encode( $data ) . "\n\n";

		// Log the data into debug log file
		Helper::log( $data );

		// Extra padding.
		echo esc_html( ':' . str_repeat( ' ', 2048 ) . "\n\n" );

		flush();
	}
}