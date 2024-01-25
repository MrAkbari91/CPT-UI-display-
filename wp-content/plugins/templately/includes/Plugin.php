<?php
/**
 * Templately plugin.
 *
 * The main plugin handler class is responsible for initializing Templately. The
 * class registers and all the components required to run the plugin.
 *
 * @package Templately
 */

namespace Templately;

use Templately\API\Conditions;
use Templately\API\ThemeBuilderApi;
use Templately\Builder\ThemeBuilder;
use Templately\Core\Importer\FullSiteImport;
use Templately\Utils\Base;
use Templately\Utils\Enqueue;

use Templately\Core\Admin;
use Templately\Core\Module;

use Templately\API\Tags;
use Templately\API\Items;
use Templately\API\Login;
use Templately\API\SignUp;
use Templately\API\Profile;
use Templately\API\Import;
use Templately\API\MyClouds;
use Templately\API\WorkSpaces;
use Templately\API\Categories;
use Templately\API\Dependencies;
use Templately\API\TemplateTypes;
use Templately\API\SavedTemplates;
use Templately\Core\Maintenance;
use Templately\Core\Migrator;
use Templately\Core\Platform\Gutenberg;
use Templately\Core\Platform\Elementor;

final class Plugin extends Base {
    public $version = '3.0.0';

	public $admin;
	/**
	 * Enqueue class responsible for assets
	 * @var Enqueue
	 */
	public $assets;

	/**
	 * @var ThemeBuilder
	 */
	public $theme_builder;

	/**
	 * Plugin constructor.
	 * Initializing Templately plugin.
	 *
	 * @access private
	 */
	public function __construct() {
		$this->define_constants();
		$this->set_locale();

		Maintenance::init();

		$this->assets        = Enqueue::get_instance( TEMPLATELY_URL, TEMPLATELY_PATH, $this->version );
		$this->admin         = Admin::get_instance();
		$this->theme_builder = ThemeBuilder::get_instance();

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		/**
		 * Initialize.
		 */
		do_action( 'templately_init' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 2.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'templately' ), '2.0' );
	}

	/**
	 * Un-serializing instances of this class is forbidden.
	 *
	 * @since 2.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Un-serializing instances of this class is forbidden.', 'templately' ), '2.0' );
	}

	/**
	 * Initializing Things on Plugins Loaded
	 * @return void
	 */
	public function plugins_loaded() {
		$this->platforms(); // PLATFORMS LOADED
		$this->apis(); // APIs LOADED

		/**
		 * Migrator for Templately
		 */
		Migrator::get_instance();

		/**
		 * Full Site Import
		 */
		FullSiteImport::get_instance();
	}

	/**
	 * Initialize all platforms
	 * @return void
	 */
	public function platforms() {
		Gutenberg::get_instance();
		Elementor::get_instance();
	}

	/**
	 * All the API instantiated
	 *
	 * @return void
	 */
	private function apis() {
		Conditions::get_instance();
		Categories::get_instance();
		TemplateTypes::get_instance();
		Dependencies::get_instance();
		Tags::get_instance();
		ThemeBuilderApi::get_instance();

		Items::get_instance();
		SavedTemplates::get_instance();

		Login::get_instance();
		SignUp::get_instance();
		Import::get_instance();
		Profile::get_instance();
		MyClouds::get_instance();
		WorkSpaces::get_instance();
	}

	/**
	 * Register all REST API endpoints
	 * @return void
	 */
	public function register_routes() {
		if ( ! empty( $modules = Module::get_instance()->get( 'API' ) ) ) {
			foreach ( $modules as $module ) {
				$module->object->register_routes();
			}
		}
	}

	/**
	 * Define CONSTANTS
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function define_constants() {
		$this->define( 'TEMPLATELY_URL', plugin_dir_url( TEMPLATELY_FILE ) );
		$this->define( 'TEMPLATELY_ASSETS', TEMPLATELY_URL . 'assets/' );
		$this->define( 'TEMPLATELY_PLUGIN_BASENAME', plugin_basename( TEMPLATELY_FILE ) );
		$this->define( 'TEMPLATELY_VERSION', $this->version );
		$this->define( 'TEMPLATELY_API_NAMESPACE', 'templately/v1' );
		$this->define( 'TEMPLATELY_VIEWS_ABSPATH', TEMPLATELY_PATH . 'views/' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string $name Constant name.
	 * @param mixed $value Constant value.
	 *
	 * @return void
	 */
	private function define( string $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Setting the locale for translation availability
	 * @return void
	 * @since 1.0.0
	 */
	public function set_locale() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Loading Text Domain on init HOOK
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'templately', false, dirname( TEMPLATELY_PLUGIN_BASENAME ) . '/languages' );
	}
}
