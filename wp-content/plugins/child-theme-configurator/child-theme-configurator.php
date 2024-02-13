<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
    Plugin Name: Child Theme Configurator
    Plugin URI: http://www.childthemeplugin.com
    Description: When using the Customizer is not enough - Create child themes and customize styles, templates, functions and more.
    Version: 2.6.5
    Author: Lilaea Media
    Author URI: http://www.lilaeamedia.com
    Text Domain: child-theme-configurator
    Domain Path: /lang
    License: GPLv2
    Copyright (C) 2014-2024 Lilaea Media
*/
// define CTC constants
defined( 'LF' ) or define( 'LF', "\n" );
defined( 'CHLD_THM_CFG_DIR' ) or define( 'CHLD_THM_CFG_DIR', dirname( __FILE__ ) );
defined( 'CHLD_THM_CFG_URL' ) or define( 'CHLD_THM_CFG_URL', plugin_dir_url( __FILE__ ) );
defined( 'CHLD_THM_CFG_OPTIONS' ) or define( 'CHLD_THM_CFG_OPTIONS', 'chld_thm_cfg_options' );
    
// activate autoloader
spl_autoload_register( 'chld_thm_cfg_autoload' );

function chld_thm_cfg_autoload( $class ) {
    $base = str_replace( 'ChildThemeConfigurator', '', $class );
    $file = dirname( __FILE__ ) . '/includes/classes/' . ( '' == $base ? 'Core' : $base ) . '.php';
    if ( file_exists( $file ) )
        include_once( $file );
}     

// load CTC Core for admin calls
if ( is_admin() ) 
    add_action( 'plugins_loaded', 'ChildThemeConfigurator::init', 5 );

// handle CTC Preview
if ( isset( $_GET['preview_ctc'] ) ):
    // replace core preview function with CTCP function for quick preview
    remove_action( 'setup_theme', 'preview_theme' );
    new ChildThemeConfiguratorPreview();
endif; 

// append timestamp to linked stylesheets to force cache refresh

add_filter( 'style_loader_src', 'chld_thm_cfg_version', 10, 2 );

function chld_thm_cfg_version( $src, $handle ) {
    // only filter links for current theme
    if ( is_child_theme() && strstr( $src, get_stylesheet() ) && ( $ver = wp_get_theme()->Version ) )
        $src = preg_replace( "/ver=(.*?)(\&|$)/", 'ver=' . $ver . "$2", $src );
    return $src;
}

// remove configuration data on uninstall
register_uninstall_hook( __FILE__, 'chld_thm_cfg_uninstall' );

function chld_thm_cfg_uninstall() {
    foreach( array(
        '',
        '_configvars',
        '_dict_qs',
        '_dict_sel',
        '_dict_query',
        '_dict_rule',
        '_dict_val',
        '_dict_seq',
        '_dict_token',
        '_sel_ndx',
        '_val_ndx',
        '_debug',
    ) as $key )
        delete_site_option( CHLD_THM_CFG_OPTIONS . $key );
}
   
