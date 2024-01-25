<?php

namespace Templately\Builder\Managers;

use Templately\Builder\TemplateLoader;

class ThemeCompatibility {
	private $theme;
	private $template;

	public function __construct() {
		add_action( 'init', [$this, 'init'] );
	}

	public function init() {
		$this->theme = wp_get_theme();
		$this->template = $this->theme->get_template();

		// switch ( $this->template ) {
		// }

		add_action( 'templately_locations', [ $this, 'register_locations' ], 999 );
	}

	public function register_locations( $manager ) {

	}
}