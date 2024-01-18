<?php
/*
*
* Plugin Name: CPT UI
* Description: CPT UI is a plugin for managing custom post types. list a custom post type, add new custom post type, edit custom post type, delete custom post type. Display custom post type in admin dashboard. admin can edit a view of custom post type. like, grid, thumbnail, list.
* Version: 1.0
* Author: Dhruv Akbari
* License: GPLv2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: CPT ui
* Domain Path: /languages
* 
*/

/**
 * install plugin
 * */
include_once plugin_dir_path(__FILE__) . 'install.php';
include_once plugin_dir_path(__FILE__) . 'include.php';
include_once plugin_dir_path(__FILE__) . 'create-business-post-type.php';

register_activation_hook(__FILE__, 'plugin_activate');
register_deactivation_hook(__FILE__, 'plugin_deactivate');

/**
 * add settings link at installed plugin list
 * */

// Hook the function to the plugin action links filter

// add settings page for plugin
include_once plugin_dir_path(__FILE__) . 'setting-business-terms.php';
include_once plugin_dir_path(__FILE__) . 'save_setting_function.php';
//shortcode page
include_once plugin_dir_path(__FILE__) . 'display_shortcodes.php';
// include uninstall page
include_once plugin_dir_path(__FILE__) . 'uninstall.php';
?>
