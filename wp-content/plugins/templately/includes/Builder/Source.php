<?php

namespace Templately\Builder;

use Templately\Utils\Helper;
use WP_Post;
use WP_Query;

class Source {
	const CPT                    = 'templately_library';
	const TYPE_META_KEY          = '_templately_template_type';
	const PLATFORM_META_KEY      = '_templately_template_platform';

	public $post_type_object;

	/**
	 * @var ThemeBuilder
	 */
	protected $builder;

	public function __construct( $builder ) {
		$this->builder = $builder;

		$this->add_actions();
		$this->register_post_type();
	}

	private function add_actions() {
		add_filter( 'views_edit-' . self::CPT, [ $this, 'admin_print_tabs' ] );
		add_action( 'in_admin_header', [ $this, 'in_admin_header' ] );
		if ( is_admin() ) {
			// add_action( 'manage_posts_extra_tablenav', [ $this, 'extra_table_nav' ] );
			add_action( 'manage_' . self::CPT . '_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );
			add_filter( 'manage_' . self::CPT . '_posts_columns', [ $this, 'custom_columns' ] );
			add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );
			add_action( 'admin_footer', [ $this, 'app' ] );
		}

		add_action( 'save_post', [ $this, 'save_post' ], 11, 3 );
	}

	/**
	 * If needed to add a filter for platform
	 *
	 * @param $which
	 *
	 * @return void
	 * @suppress 50
	 */
	public function extra_table_nav( $which ) {
		if( ! $this->is_edit_screen() ) {
			return;
		}

		// if( $which === 'top' ) {
		//
		// }
	}

	/**
	 * Print custom column data (Template Type and Platform)
	 *
	 * @param $column_name
	 * @param $post_id
	 *
	 * @return void
	 */
	public function custom_column( $column_name, $post_id ) {
		switch ( $column_name ) {
			case 'template_type':
				$types = $this->builder::$templates_manager->get_template_types();
				$type  = get_post_meta( $post_id, self::TYPE_META_KEY, true );
				echo call_user_func( [ $types[ $type ], 'get_title' ] );
				break;
			case 'template_platform':
				echo get_post_meta( $post_id, self::PLATFORM_META_KEY, true );
				break;
		}
	}

	/**
	 * Add custom columns. (Template Type and Platform)
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function custom_columns( $columns ) {
		$old_columns = $columns;

		unset( $columns['date'] );
		unset( $columns['author'] );

		$columns['template_type']     = __( 'Type', 'templately' );
		$columns['template_platform'] = __( 'Platform', 'templately' );

		$columns['author'] = $old_columns['author'];
		$columns['date']   = $old_columns['date'];

		return $columns;
	}

	/**
	 * Add New Template Button App
	 * @return void
	 */
	public function in_admin_header() {
		if ( ! $this->is_edit_screen() ) {
			return;
		}

		echo '<div id="templately-theme-builder-admin-header"></div>';
	}

	/**
	 * Edit conditions button app
	 * @return void
	 */
	public function app() {
		if( ! $this->is_edit_screen() ) {
			return;
		}

		Helper::views( 'builder/edit-conditions' );
	}

	private function is_edit_screen(): bool {
		global $current_screen;

		if ( ! $current_screen ) {
			return false;
		}

		return 'edit' === $current_screen->base && self::CPT === $current_screen->post_type;
	}

	public function post_row_actions( $actions, $post ) {
		if ( $this->is_edit_screen() ) {
			$actions['edit-conditions'] = sprintf( '<a data-template_id="%1$s" class="templately-edit-conditions" href="#">%2$s</a>', $post->ID, esc_html__( 'Edit Conditions', 'templately' ) );
		}

		return $actions;
	}

	public function save_post( int $post_id, WP_Post $post, bool $update ) {
		if ( $post->post_type !== self::CPT ) {
			return;
		}

		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// FIXME: this is something I need to fix in the future.
	}

	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function register_post_type() {
		$name = esc_html_x( 'Templates', 'Template Library', 'templately' );

		$labels = [
			'name'               => $name,
			'singular_name'      => esc_html_x( 'Template', 'Template Library', 'templately' ),
			'add_new'            => esc_html_x( 'Add New Template', 'Template Library', 'templately' ),
			'add_new_item'       => esc_html_x( 'Add New Template', 'Template Library', 'templately' ),
			'edit_item'          => esc_html_x( 'Edit Template', 'Template Library', 'templately' ),
			'new_item'           => esc_html_x( 'New Template', 'Template Library', 'templately' ),
			'all_items'          => esc_html_x( 'All Templates', 'Template Library', 'templately' ),
			'view_item'          => esc_html_x( 'View Template', 'Template Library', 'templately' ),
			'search_items'       => esc_html_x( 'Search Template', 'Template Library', 'templately' ),
			'not_found'          => esc_html_x( 'No Templates found', 'Template Library', 'templately' ),
			'not_found_in_trash' => esc_html_x( 'No Templates found in Trash', 'Template Library', 'templately' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html_x( 'Templates', 'Template Library', 'templately' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-admin-page',
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'thumbnail', 'author', 'editor', 'elementor' ],
		];

		/**
		 * Register template library post type args.
		 *
		 * @param array $args Arguments for registering a post type.
		 */

		$this->post_type_object = register_post_type( self::CPT, $args );
	}

	/**
	 * Tab menu
	 *
	 * @param $tabs
	 *
	 * @return void
	 */
	public function admin_print_tabs( $tabs ) {
		$types = templately()->theme_builder::$templates_manager->get_template_types();

		$template_types = [];
		$status_args    = [ 'post_type' => 'templately_library' ];

		$template_types['all'] = [
			'url'   => add_query_arg( $status_args, 'edit.php' ),
			'label' => __( 'All', 'templately' )
		];

		foreach ( $types as $type_name => $type ) {
			if( $type::get_property( 'builder' ) === false ) {
				continue;
			}

			$status_args['type']          = $type_name;
			$template_types[ $type_name ] = [
				'url'   => add_query_arg( $status_args, 'edit.php' ),
				'label' => call_user_func( [ $type, 'get_title' ] )
			];
		}

		$this->builder::$views->get( 'builder/tabs', [ 'tabs' => $tabs, 'template_types' => $template_types ] );
	}

	/**
	 * Retrieves items.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_items( array $args = [] ): array {
		$template_types = [];

		if ( ! empty( $args['type'] ) ) {
			$template_types = $args['type'];
			unset( $args['type'] );
		}

		$defaults_args = [
			'post_type'      => self::CPT,
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		];

		if ( ! empty( $template_types ) ) {
			$defaults_args['meta_query'] = [
				[
					'key'   => self::TYPE_META_KEY,
					'value' => $template_types,
				]
			];
		}

		$args  = wp_parse_args( $args, $defaults_args );
		$items = new WP_Query( $args );

		return $items->posts;
	}
}