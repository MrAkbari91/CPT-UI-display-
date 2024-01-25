<?php
namespace Templately\Core;

use Exception;
use PriyoMukul\WPNotice\Notices;
use PriyoMukul\WPNotice\Utils\CacheBank;
use PriyoMukul\WPNotice\Utils\NoticeRemover;
use Templately\API\Login;
use Templately\Utils\Base;
use Templately\Utils\Helper;
use Templately\Utils\Options;
use Templately\Core\Platform\Elementor;
use Templately\Core\Platform\Gutenberg;

class Admin extends Base {

	/**
	 * @var CacheBank
	 */
	private static $cache_bank;

	/**
	 * Initially invoked function.
	 * Menu, Assets and maybe redirect on plugin activation is initialized.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		self::$cache_bank = CacheBank::get_instance();

		try {
			add_action( 'admin_init', [ $this, 'notices' ], 11 );
		} catch ( Exception $e ) {
			unset( $e );
		}

		// Remove OLD notice from 1.0.0 (if other WPDeveloper plugin has notice)
		NoticeRemover::get_instance( '1.0.0' );
	}

	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'Theme Builders', 'Post type general name', 'templately' ),
			'singular_name'         => _x( 'Theme Builder', 'Post type singular name', 'templately' ),
			'menu_name'             => _x( 'Theme Builder', 'Admin Menu text', 'templately' ),
			'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'templately' ),
			'add_new'               => __( 'Add New', 'templately' ),
			'add_new_item'          => __( 'Add New Template', 'templately' ),
			'new_item'              => __( 'New Template', 'templately' ),
			'edit_item'             => __( 'Edit Template', 'templately' ),
			'view_item'             => __( 'View Template', 'templately' ),
			'all_items'             => __( 'All Templates', 'templately' ),
			'search_items'          => __( 'Search Templates', 'templately' ),
			'parent_item_colon'     => __( 'Parent Templates:', 'templately' ),
			'not_found'             => __( 'No templates found.', 'templately' ),
			'not_found_in_trash'    => __( 'No templates found in Trash.', 'templately' ),
			'featured_image'        => _x( 'Template Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'templately' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'templately' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'templately' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'templately' ),
			'archives'              => _x( 'Template archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'templately' ),
			'insert_into_item'      => _x( /** @lang text */ "Insert Into Theme Builder", 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'templately' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this template', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'templately' ),
			'filter_items_list'     => _x( 'Filter templates list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'templately' ),
			'items_list_navigation' => _x( 'Templates list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'templately' ),
			'items_list'            => _x( 'Templates list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'templately' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'templately-library' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 10,
			'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'custom-field' ],
		];

		register_post_type( 'templately_library', $args );
	}

	/**
	 * Enqueuing Assets
	 *
	 * @param  string $hook
	 * @return void
	 */
	public function scripts( string $hook ) {
		if ( ! in_array( $hook, [ 'edit.php', 'toplevel_page_templately', 'elementor', 'gutenberg' ], true ) ) {
			return;
		}

		$templately = [];

		if ( 'edit.php' === $hook ) {
			global $current_screen;
			if ( $current_screen->post_type !== 'templately_library' ) {
				return;
			}

			$types = templately()->theme_builder::$templates_manager->get_template_types();
			$types = array_filter( $types, function ( $item ) {
				return $item::get_property( 'builder' ) || $item::get_property( 'builder' ) === null;
			} );

			$types = array_reduce( $types, function ( $carry, $item ) {
				return array_merge( $carry, [
					[
						'value' => call_user_func( [ $item, 'get_type' ] ),
						'label' => call_user_func( [ $item, 'get_title' ] )
					]
				] );
			}, [] );

			$templately = [
				'types' => $types
			];
		}

		$script_dependencies = [];
		$_localize_handle    = 'templately';
		$_current_screen     = 'templately';

		if ( $hook === 'elementor' || $hook === 'gutenberg' ) {
			$_current_screen     = $hook;
			$_localize_handle    = 'templately-' . $hook;
			$script_dependencies = [$_localize_handle];
		}

		if ( $hook === 'toplevel_page_templately' || $hook == 'edit.php' ) {
			templately()->assets->enqueue( 'templately-admin', 'css/admin.css', [ 'templately' ] );
		}

		// Google Font Enqueueing
		templately()->assets->enqueue(
			'templately-dmsans',
			set_url_scheme( '//fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap' )
		);

		templately()->assets->enqueue( 'templately', 'js/templately.js', $script_dependencies, true );
		templately()->assets->enqueue( 'templately', 'css/templately.css', ['templately-dmsans'] );

		/**
		 * @var Elementor|Gutenberg $platform
		 */
		$platform = $this->platform( $_current_screen );

		$templately = array_merge( [
			'url'                => home_url(),
			'site_url'           => site_url(),
			'nonce'              => wp_create_nonce( 'templately_nonce' ),
			'rest_args'          => [
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'endpoint' => get_rest_url( null, 'templately/v1/' )
			],
			'log'                => defined( 'TEMPLATELY_DEBUG_LOG' ) && TEMPLATELY_DEBUG_LOG,
			'dev_mode'           => defined( 'TEMPLATELY_DEV' ) && TEMPLATELY_DEV,
			"icons"              => [
				'construction' => templately()->assets->icon( 'icons/construction.gif' ),
				'profile'      => templately()->assets->icon( 'icons/profile.svg' ),
				'warning'      => templately()->assets->icon( 'icons/warning.png' )
			],
			'promo_image'        => templately()->assets->icon( 'single-page-promo.png' ),
			'default_image'      => templately()->assets->icon( 'clouds/cloud-item.svg' ),
			'not_found'          => templately()->assets->icon( 'no-item-found.png' ),
			'no_items'           => templately()->assets->icon( 'no-items.png' ),
			'loadingImage'       => templately()->assets->icon( 'logos/loading-logo.gif' ),
			'current_url'        => admin_url( 'admin.php?page=templately' ),
			'is_signed'          => Login::is_signed(),
			'is_globally_signed' => Login::is_globally_signed(),
			'signed_as_global'   => Login::signed_as_global(),
			'current_screen'     => $_current_screen,
			'has_elementor_pro'  => rest_sanitize_boolean( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ),
			'theme'                   => $_current_screen == 'templately' ? 'light' : $platform->ui_theme(),
			'is_wp_support_gutenberg' => version_compare( get_bloginfo( 'version' ), '5.0.0', '>=' ),
		], $templately );

		templately()->assets->localize( $_localize_handle, 'templately', $templately );
	}

	/**
	 * Admin notices for Review and others.
	 *
	 * @since 2.0.0
	 * @return void
	 * @throws Exception
	 * @since 2.0.0
	 */
	public function notices() {
		$notices = new Notices( [
			'id'             => 'templately',
			'storage_key'    => 'notices',
			'lifetime'       => 3,
			'stylesheet_url' => TEMPLATELY_ASSETS . 'css/notices.css',
			'styles'         => TEMPLATELY_ASSETS . 'css/notices.css',
			'priority'       => 2,
		] );

		$global_user     = Options::get_instance()->get( 'user', false, get_current_user_id() );
		$download_counts = Options::get_instance()->get( 'total_download_counts', 0, get_current_user_id() );
		$cloud_items     = 0;
		if ( isset( $global_user['my_cloud']['usages'] ) ) {
			$cloud_items = intval( $global_user['my_cloud']['usages'] );
		}

		if ( $cloud_items >= 5 || $download_counts >= 4 ) {
			$message = sprintf( __( "We hope you're enjoying %s! Could you please do us a favor and give us a review on %s to help us spread the word and boost our motivation?", 'templately' ), '<strong>Templately</strong>', '<strong>WordPress</strong>' );

			$_review_notice = [
				'thumbnail' => templately()->assets->icon( 'logos/logo.svg' ),
				'html'      => '<p>' . $message . '</p>',
				'links'     => [
					'later'            => [
						'link'       => 'https://wordpress.org/support/plugin/templately/reviews/#new-post',
						'target'     => '_blank',
						'label'      => __( 'Sure, you deserve it!', 'templately' ),
						'icon_class' => 'dashicons dashicons-external'
					],
					'allready'         => [
						'label'      => __( 'I already did', 'templately' ),
						'icon_class' => 'dashicons dashicons-smiley',
						'attributes' => [
							'data-dismiss' => true
						]
					],
					'maybe_later'      => [
						'label'      => __( 'Maybe Later', 'templately' ),
						'icon_class' => 'dashicons dashicons-calendar-alt',
						'attributes' => [
							'data-later' => true,
							'class'      => 'dismiss-btn'
						]
					],
					'support'          => [
						'link'       => 'https://wpdeveloper.com/support',
						'attributes' => [
							'target' => '_blank'
						],
						'label'      => __( 'I need help', 'templately' ),
						'icon_class' => 'dashicons dashicons-sos'
					],
					'never_show_again' => [
						'label'      => __( 'Never show again', 'templately' ),
						'icon_class' => 'dashicons dashicons-dismiss',
						'attributes' => [
							'data-dismiss' => true
						]
					]
				]
			];

			$notices->add( 'review', $_review_notice, [
				'start'       => $notices->strtotime(),
				'recurrence'  => 20,
				'dismissible' => true,
				'refresh'     => TEMPLATELY_VERSION,
				'screens'     => [
					'dashboard',
					'plugins',
					'themes',
					'edit-page',
					'edit-post',
					'users',
					'tools',
					'options-general',
					'nav-menus'
				]
			] );
		}

		if ( $global_user === false || isset( $global_user['plan'] ) && $global_user['plan'] == 'free' ) {
			$notices->add( 'upsale', wp_sprintf( '<p>%1$s <a target="_blank" href="%3$s">%2$s</a>.</p>', __( '🔥 Get access to 5,000+ Ready Templates & save up to 65% OFF now', 'templately' ), __( 'Upgrade to Pro', 'templately' ), 'https://templately.com/#pricing' ), [
				'start'       => $notices->strtotime( '+10 day' ),
				'dismissible' => true,
				'refresh'     => TEMPLATELY_VERSION,
				'screens'     => [
					'dashboard',
					'plugins',
					'themes',
					'edit-page',
					'edit-post',
					'users',
					'tools',
					'options-general',
					'nav-menus'
				]
			] );

			$notice_text = '<p style="margin-top: 0; margin-bottom: 10px;">Black Friday Sale: Save up to 70% and <strong>get access to 5000+ ready templates</strong> to design amazing websites ✨</p>
			<a class="button button-primary" href="https://wpdeveloper.com/upgrade/templately-bfcm" target="_blank">Upgrade to pro</a> <button data-dismiss="true" class="dismiss-btn button button-link">I don’t want to save money</button>';

			$_black_friday = [
				'thumbnail' => templately()->assets->icon( 'logos/logo-full.svg' ),
				'html'      => $notice_text,
			];

			$notices->add( 'black_friday', $_black_friday, [
				'start'       => $notices->time(),
				'recurrence'  => false,
				'dismissible' => true,
				'refresh'     => TEMPLATELY_VERSION,
				"expire"      => strtotime( '11:59:59pm 2nd December, 2023' ),
			] );
		}

		self::$cache_bank->create_account( $notices );
		self::$cache_bank->calculate_deposits( $notices );
	}

	/**
	 * Adding Menu In Sidebar ( WordPress Left-side Dashboard Menu )
	 *
	 * @return void
	 */
	public function admin_menu() {
		// TODO: Role Management

		add_menu_page( 'Templately', 'Templately', 'delete_posts', 'templately', '', templately()->assets->icon( 'logos/logo-icon.svg' ), '58.7' );

		add_submenu_page( 'templately', 'Templately', 'Template Library', 'delete_posts', 'templately', [
			$this,
			'display'
		], '58.7' );

		add_submenu_page( 'templately', 'Theme Builder', 'Theme Builder', 'delete_posts', 'edit.php?post_type=templately_library', '', '58.7' );

		// add_submenu_page( 'templately', 'Settings', 'Settings', 'administrator', 'templately-settings', [
		// 	Settings::get_instance(),
		// 	'display'
		// ], '58.7' );
	}

	public function display() {
		Helper::views( 'settings' );
	}

	/**
	 * If Elementor doesn't exists.
	 *
	 * @return void
	 */
	public static function has_no_elementor() {
		$plugin_url  = \wp_nonce_url( \self_admin_url( 'update.php?action=install-plugin&amp;plugin=elementor' ), 'install-plugin_elementor' );
		$button_text = 'Install Elementor';
		if ( isset( Helper::get_plugins()['elementor/elementor.php'] ) ) {
			$plugin_url  = \wp_nonce_url( 'plugins.php?action=activate&amp;plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' );
			$button_text = 'Activate Elementor';
		}
		$output = '<div class="notice notice-error">';
		$output .= sprintf(
			"<p><strong>%s</strong> %s <strong>%s</strong> %s &nbsp;&nbsp;<a  class='button-primary' href='%s'>%s</a></p>",
			__( 'Templately', 'templately' ),
			__( 'requires', 'templately' ),
			__( 'Elementor', 'templately' ),
			__( 'plugin to be installed and activated. Please install Elementor to continue.', 'templately' ),
			esc_url( $plugin_url ),
			__( $button_text, 'templately' )
		);
		$output .= '</div>';
		echo $output;
	}

	public function header() {
		Helper::views( 'header' );
	}
}
