<?php

namespace Templately\Builder\Factory;

use Elementor\Core\Base\Document;
use Templately\Builder\Source;

class TemplateFactory {
	protected $platform;

	protected $template;
	protected $template_manager;

	public function __construct( $platform = 'elementor' ) {
		$this->platform         = $platform;
		$this->template_manager = templately()->theme_builder::$templates_manager;
	}

	public function create( $type, $data, $meta = [] ) {
		$data = wp_parse_args( $data );
		
		$meta = wp_parse_args( $meta, [
			Source::PLATFORM_META_KEY => $this->platform,
			Source::TYPE_META_KEY     => $type,
		] );

		if ( $this->platform === 'elementor' ) {
			$meta[ Document::BUILT_WITH_ELEMENTOR_META_KEY ] = 'builder';
		}

		return $this->template_manager->create( $type, $data, $meta );
	}

}