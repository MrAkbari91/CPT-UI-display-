<?php

namespace Templately\Builder\Types;

use Templately\Core\Importer\FullSiteImport;

class Header extends HeaderFooterBase {
	public static function get_type(): string {
		return 'header';
	}

	public static function get_title(): string {
		return __( 'Header', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Headers', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['location'] = 'header';

		return $properties;
	}


	public function import( $elementor_data ) {
		if ( $this->is_elementor_template() ) {
			$request = FullSiteImport::get_instance()->get_session_data();

			$elementor_data['content'] = \Elementor\Plugin::$instance->db->iterate_data($elementor_data['content'], function($element) use ($request){
				// check if it's a image widget
				if( $element['elType'] == 'widget' && $element['widgetType'] == 'image' ) {
					// check if it has a image source
					if( isset($request['logo']['url']) ) {
						// set the image url
						$element['settings']['image']['id']  = $request['logo']['id'];
						$element['settings']['image']['url'] = $request['logo']['url'];
					}
				}

				return $element;
			});


		}
		parent::import( $elementor_data );
	}
}