<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
    Class: ChildThemeConfiguratorAnalysis
    Plugin URI: http://www.childthemeplugin.com/
    Description: Theme Analyzer Class
    Version: 2.1.3
    Author: Lilaea Media
    Author URI: http://www.lilaeamedia.com/
    Text Domain: child-theme-configurator
    Domain Path: /lang
    License: GPLv2
    Copyright (C) 2014-2018 Lilaea Media
*/
class ChildThemeConfiguratorAnalysis {
    
    private $params;
    private $url;
    private $response;
    private $analysis;

    function __construct(){
        $this->params = array(
            'template'      => isset( $_POST[ 'template' ] ) ? $_POST[ 'template' ] : '',
            'stylesheet'    => isset( $_POST[ 'stylesheet' ] ) ? $_POST[ 'stylesheet' ] : '',
            'preview_ctc'   => wp_create_nonce(),
            'now'           => time(),
        );
        $this->analysis = array();
    }

    function is_child(){
        return $this->params[ 'template' ] !== $this->params[ 'stylesheet' ];
    }
    
    function fetch_page(){
        $this->url = home_url( '/' ) . '?' . build_query( $this->params ); //get_home_url()
        $args = array(
            'cookies'       => $_COOKIE,
            'user-agent'    => $_SERVER[ 'HTTP_USER_AGENT' ],
			'sslverify'     => apply_filters( 'https_local_ssl_verify', false )
        );
        $this->response = wp_remote_get( $this->url, $args );
        if ( is_wp_error( $this->response ) ):
            $this->analysis[ 'signals' ][ 'httperr' ] = $this->response->get_error_message();
        else:
            $this->analysis[ 'signals' ] = array();
            $this->analysis[ 'body' ] = $this->response[ 'body' ];
        endif;
    }
    
    function get_analysis(){
        return $this->analysis;
    }
}
