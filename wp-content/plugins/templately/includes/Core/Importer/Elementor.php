<?php

namespace Templately\Core\Importer;

use WP_Error;
use function wp_parse_args;
use function current_user_can;
use Elementor\Core\Settings\Page\Model;

use Elementor\Plugin as ElementorPlugin;
use Elementor\TemplateLibrary\Source_Local as ElementorLocal;

class Elementor extends ElementorLocal {
	/**
	 * Get template data.
	 *
	 * @inheritDoc
	 *
	 * @param array $args Custom template arguments.
	 *
	 * @return array Remote Template data.
	 */
	public function get_data( array $args ) {
		ElementorPlugin::$instance->editor->set_edit_mode( true );

		$args['content'] = $this->replace_elements_ids( $args['content'] );
		$args['content'] = $this->process_export_import_content( $args['content'], 'on_import' );

		$args['content'] = $this->iterate_data(
			$args['content'], function( $element_data ) {
				$element = ElementorPlugin::$instance->elements_manager->create_element_instance( $element_data );

				// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
				if ( ! $element ) {
					return null;
				}

				return $element_data;
			}
		);

		if (!isset($args['page_settings'])) {
			$args['page_settings'] = array();
		}
		if(!isset($args['page_settings']["template"])){
			$args['page_settings']["template"] = "elementor_header_footer";
		}

//		$post_id  = false; // FIXME: need to check later on.
//		$document = ElementorPlugin::$instance->documents->get( $post_id );
//		if ( $document ) {
//			$args['content'] = $document->get_elements_raw_data( $args['content'], true );
//		}
		return $args;
	}

	public function import_in_library( $data ) {
		if ( empty( $data ) ) {
			return new WP_Error( 'file_error', 'Invalid File' );
		}

		$content = $data['content'];

		if ( ! is_array( $content ) ) {
			return new WP_Error( 'file_error', 'Invalid File' );
		}

		$page_settings = $this->page_settings( $data );

		// TODO: type check for (theme builder)

		$template_id = $this->save_item( [
			'content' => $content,
			'title' => $data['title'],
			'type' => $data['type'],
			'page_settings' => $page_settings,
		] );

		if ( is_wp_error( $template_id ) ) {
			return $template_id;
		}

		return $this->get_item( $template_id );
	}

	public function create_page( $template_data ){
		$page_settings = $this->page_settings( $template_data );

		$defaults = [
			'post_title'   => isset( $template_data['page_title'] ) ? $template_data['page_title'] : 'Templately: ' . $template_data['title'],
			'page_settings' => $page_settings,
			'status' => current_user_can( 'publish_posts' ) ? 'publish' : 'pending',
		];

		$template_data = wp_parse_args( $template_data, $defaults );

		$document = ElementorPlugin::$instance->documents->create(
			$template_data['type'],
			[
				'post_title' => $template_data['post_title'],
				'post_status' => $template_data['status'],
				'post_type' => 'page',
			]
		);

		if ( is_wp_error( $document ) ) {
			/**
			 * @var WP_Error $document
			 */
			return $document;
		}

		$document->save( [
			'elements' => $template_data['content'],
			'settings' => $page_settings,
		] );

		return $document->get_main_id();
	}

	/**
	 * @param $template_data
	 * @noinspection DuplicatedCode
	 *
	 * @return array
	 */
	private function page_settings( $template_data ) {
		$page_settings = [];

		if (!isset($template_data['page_settings'])) {
			$template_data['page_settings'] = array();
		}

		if(!isset($template_data['page_settings']["template"])){
			$template_data['page_settings']["template"] = "elementor_header_footer";
		}

		$page = new Model([
			'id' => 0,
			'settings' => $template_data['page_settings'],
		]);

		$page_settings_data = $this->process_element_export_import_content($page, 'on_import');

		if (!empty($page_settings_data['settings'])) {
			$page_settings = $page_settings_data['settings'];
		}

		return $page_settings;
	}

	/**
	 * Iterate data.
	 *
	 * Accept any type of Elementor data and a callback function. The callback
	 * function runs recursively for each element and his child elements.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array    $data_container Any type of elementor data.
	 * @param callable $callback       A function to iterate data by.
	 * @param array    $args           Array of args pointers for passing parameters in & out of the callback
	 *
	 * @return mixed Iterated data.
	 */
	public function iterate_data( $data_container, $callback, $args = [] ) {
		if ( isset( $data_container['elType'] ) ) {
			if ( ! empty( $data_container['elements'] ) ) {
				$data_container['elements'] = $this->iterate_data( $data_container['elements'], $callback, $args );
			}

			return call_user_func( $callback, $data_container, $args );
		}

		foreach ( $data_container as $element_key => $element_value ) {
			$element_data = $this->iterate_data( $data_container[ $element_key ], $callback, $args );

			if ( null === $element_data ) {
				unset($data_container[ $element_key ]);
				continue;
			}

			$data_container[ $element_key ] = $element_data;
		}

		return array_values($data_container);
	}

}

