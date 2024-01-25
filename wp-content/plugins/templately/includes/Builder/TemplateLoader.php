<?php

namespace Templately\Builder;

use Elementor\Plugin;
use Templately\Builder\Managers\LocationManager;
use Templately\Builder\Managers\ThemeCompatibility;
use Templately\Builder\Types\BaseTemplate;
use Templately\Utils\Views;
use ElementorPro\Modules\ThemeBuilder\Module;

class TemplateLoader {
	/**
	 * @var Views
	 */
	private $views;

	public function __construct( $builder, $views ) {
		$this->views = $views;

		// new ThemeCompatibility();

		add_action( 'get_header', [ $this, 'get_header' ], 0 );
		add_action( 'get_footer', [ $this, 'get_footer' ], 0 );
		add_action( 'elementor/document/wrapper_attributes', [ $this, 'wrapper_attributes' ], 10, 2 );

		/**
		 * Only for Development Mode.
		 */
		if ( defined( 'TEMPLATELY_DEV_VIEWS' ) && TEMPLATELY_DEV_VIEWS ) {
			add_action( 'templately_builder_header_before', [ $this, 'header_helper' ], 0 );
			add_action( 'templately_builder_footer_before', [ $this, 'footer_helper' ], 0 );
		}
	}

	public function header_helper() {
		echo '<small>Header</small>';
	}

	public function footer_helper() {
		echo '<small>Footer</small>';
	}

	public function get_header() {
		if(!$this->is_header_footer()){
			return;
		}
		$this->views->get_header();

		$templates   = [];
		$templates[] = 'header.php';

		remove_all_actions( 'wp_head' );

		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function get_footer( $name ) {
		if(!$this->is_header_footer()){
			return;
		}
		$this->views->get_footer();

		$templates = [];
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "footer-{$name}.php";
		}

		$templates[] = 'footer.php';

		// remove_all_actions( 'wp_footer' );

		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function is_header_footer(){
		if(class_exists( 'Elementor\Plugin' )){
			$pid       = get_the_ID();
			$post_type = get_post_type($pid);
			$document  = Plugin::$instance->documents->get( $pid );

			if(
				$post_type === 'elementor_library' &&
				(
					$document->get_type() === 'header' ||
					$document->get_type() === 'footer')
				){
				return false;
			}
		}

		$header = templately()->theme_builder::$conditions_manager->get_templates_by_location( 'header' );
		$footer = templately()->theme_builder::$conditions_manager->get_templates_by_location( 'footer' );

		if(!empty($header) || !empty($footer)){
			return true;
		}
		return false;
	}

	public function wrapper_attributes( $attributes, $document ) {
		$post_type = get_post_type($document->get_main_id());

		if( $post_type === 'templately_library' ){
			$template = templately()->theme_builder->get_template( $document->get_main_id() );
			$attributes['data-elementor-type']      = 'templately-' . $template->get_type();
			$attributes['data-elementor-id']        = $template->get_main_id();
			$attributes['data-elementor-post-type'] = 'templately_library';
			$attributes['data-elementor-title']     = $template->get_title();
		}

		return $attributes;
	}

}