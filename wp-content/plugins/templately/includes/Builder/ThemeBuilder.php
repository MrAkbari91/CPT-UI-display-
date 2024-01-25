<?php

namespace Templately\Builder;

use Elementor\Core\Base\Document;
use Templately\Builder\Managers\ConditionManager;
use Templately\Builder\Managers\LocationManager;
use Templately\Builder\Managers\TemplateManager;
use Templately\Builder\Managers\ThemeCompatibility;
use Templately\Builder\Types\ThemeTemplate;
use Templately\Utils\Base;
use Templately\Utils\Views;

class ThemeBuilder extends Base {
	/**
	 * @var Source
	 */
	public static $source;

	/**
	 * @var ConditionManager
	 */
	public static $conditions_manager;

	/**
	 * @var TemplateManager
	 */
	public static $templates_manager;

	/**
	 * @var LocationManager
	 */
	public static $location_manager;

	/**
	 * @var ThemeCompatibility
	 */
	public static $theme_compatibility;

	/**
	 * @var PageTemplates
	 */
	public static $page_template_module;

	public static $views;

	public function __construct() {
		self::$views = Views::get_instance( TEMPLATELY_VIEWS_ABSPATH );

		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ], 1 );
		add_action( 'wp', function () {
			new TemplateLoader( $this, self::$views );
		} );

		add_action( 'init', [ $this, 'source_register' ] );
		add_action( 'wp_ajax_templately_create_template', [ $this, 'create_template' ] );

		add_filter( 'elementor/document/config', [$this, 'elementor_document_config'], 10, 2 );
		add_filter( 'elementor/documents/get/post_id', [$this, 'elementor_documents_get_post_id'] );

		self::$theme_compatibility  = new ThemeCompatibility();
		self::$location_manager     = new LocationManager( $this );
		self::$page_template_module = new PageTemplates();
	}

	public function pre_get_posts( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || empty( $query->query['post_type'] ) || $query->query['post_type'] !== 'templately_library' ) {
			return;
		}

		$template_types = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : '';

		if ( ! empty( $template_types ) ) {
			$query->set( 'meta_query', [
				[
					'key'   => Source::TYPE_META_KEY,
					'value' => $template_types,
				]
			] );
		}
	}

	public function source_register() {
		self::$templates_manager  = new TemplateManager( $this );
		self::$conditions_manager = new ConditionManager( $this );
		self::$source             = new Source( $this );
	}

	public function create_template() {
		check_admin_referer( 'templately_create_template' );

		$_type = sanitize_text_field( wp_unslash( $_POST['template_type'] ) );

		$_meta = [];

		if ( isset( $_POST['meta'] ) && is_array( $_POST['meta'] ) ) {
			$_meta = array_map( 'sanitize_text_field', wp_unslash( $_POST['meta'] ) );
		}

		$_post_data = [
			'post_type'  => 'templately_library',
			'post_title' => sanitize_text_field( wp_unslash( $_POST['title'] ) )
		];

		$template = self::$templates_manager->create( $_type, $_post_data, $_meta );

		if ( is_wp_error( $template ) ) {
			wp_die( $template );
		}

		wp_redirect( $template->get_edit_url() );
		die;
	}

	public function get_public_post_types(): array {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	public function get_template( $template_id ) {
		$template = self::$templates_manager->get( $template_id );

		if ( ! empty( $template ) && ! $template instanceof ThemeTemplate ) {
			$template = null;
		}

		return $template;
	}

	/**
	 * Save additional data for a specific post.
	 *
	 * This function filters the data by adding additional configuration.
	 * External developers can use this function to add custom configuration for different posts.
	 * It checks if the post type is not 'templately_library', then it adds a new configuration
	 * 'theme-post-content' to the data.
	 *
	 * @param array $data The original data that needs to be saved.
	 * @param int   $post_id The ID of the post for which the data is being saved.
	 *
	 * @return array         The modified data with the additional configuration.
	 */
	public function elementor_document_config( $data, $post_id ) {
		if ( Source::CPT === get_post_type( $post_id ) ) {
			$data['panel'] = [
				'widgets_settings'    => [
					'theme-post-content' => [
						'show_in_panel' => true,
					],
				],
				'elements_categories' => [
					'theme-elements-archive' => [
						'title' => esc_html__( 'Archive', 'elementor-pro' ),
					],
				],
			];

			$conditions = self::$conditions_manager->get_conditions_for_display( $post_id );

			$data['templately_builder']['conditions'] = $conditions;
			$data['templately_builder']['post_type']  = get_post_type( $post_id );
		}

		return $data;
	}

	public function elementor_documents_get_post_id( $post_id ) {
		if ( Source::CPT === get_post_type( $post_id ) ) {
			add_filter( 'get_post_metadata', [ $this, 'modify_template_type' ], 10, 4 );
		}

		return $post_id;
	}

	public function modify_template_type( $value, $object_id, $meta_key, $single ) {
		if ( Document::TYPE_META_KEY === $meta_key && Source::CPT === get_post_type( $object_id ) ) {
			remove_filter( 'get_post_metadata', [ $this, 'modify_template_type' ] );
			$value = get_post_meta( $object_id, Source::TYPE_META_KEY, $single );
		}

		return $value;
	}

}