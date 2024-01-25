<?php
/**
 * Plugin Name: Templately
 * Description: The Best Templates Cloud for Elementor & Gutenberg. Get access to stunning templates, WorkSpace, Cloud Library & many more.
 * Plugin URI: https://templately.com
 * Author: Templately
 * Version: 3.0.0
 * Author URI: https://templately.com/
 * Text Domain: templately
 * Domain Path: /languages
 */
defined( 'ABSPATH' ) or die( 'Access denied!' ); // Avoid direct file request

define( 'TEMPLATELY_FILE', __FILE__ );
define( 'TEMPLATELY_PATH', plugin_dir_path( TEMPLATELY_FILE ) );
require TEMPLATELY_PATH . 'vendor/autoload.php';

/**
 * Templately Class will be instantiated by function.
 * @return Templately\Plugin
 */
function templately() {
    return Templately\Plugin::get_instance();
}

templately();