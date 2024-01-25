<?php

namespace Templately\Utils;

class Views extends Base {

	protected $dir = '';

	public function __construct( $path ) {
		$this->dir = trailingslashit($path);
	}

	public function get( string $template, array $args = [] ) {
		extract( $args );

		include $this->get_path( $template );
	}

	public function get_path( $template ): string {
		return $this->dir . $template . '.php';
	}

	public function get_header() {
		$this->get( 'templates/header' );
	}

	public function get_footer() {
		$this->get( 'templates/footer' );
	}
}