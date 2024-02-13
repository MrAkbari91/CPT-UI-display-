<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

    class ChildThemeConfigurator {
        
        static $instance;
        static $plugin = 'child-theme-configurator/child-theme-configurator.php';
        static $oldpro = 'child-theme-configurator-plugins/child-theme-configurator-plugins.php';
        static $ctcpro = 'child-theme-configurator-pro/child-theme-configurator-pro.php';
        
        static function init() {
            defined( 'LILAEAMEDIA_URL' ) or 
            define( 'LILAEAMEDIA_URL',                  "http://www.lilaeamedia.com" );
            defined( 'CHLD_THM_CFG_DOCS_URL' ) or 
            define( 'CHLD_THM_CFG_DOCS_URL',            "http://www.childthemeplugin.com" );
            define( 'CHLD_THM_CFG_VERSION',             '2.6.5' );
            define( 'CHLD_THM_CFG_PREV_VERSION',        '1.7.9.1' );
            define( 'CHLD_THM_CFG_MIN_WP_VERSION',      '3.7' );
            define( 'CHLD_THM_CFG_PRO_MIN_VERSION',     '2.2.0' );
            defined( 'CHLD_THM_CFG_BPSEL' ) or 
            define( 'CHLD_THM_CFG_BPSEL',               '2500' );
            defined( 'CHLD_THM_CFG_MAX_RECURSE_LOOPS' ) or 
            define( 'CHLD_THM_CFG_MAX_RECURSE_LOOPS',   '1000' );
            defined( 'CHLD_THM_CFG_MENU' ) or 
            define( 'CHLD_THM_CFG_MENU',                'chld_thm_cfg_menu' );
            
            // verify WP version support
            global $wp_version;
            if ( version_compare( $wp_version, CHLD_THM_CFG_MIN_WP_VERSION, '<' ) ):
                add_action( 'all_admin_notices', 'ChildthemeConfigurator::version_notice' );
                return;
            endif;
            if ( file_exists( trailingslashit( dirname( CHLD_THM_CFG_DIR ) ) . self::$oldpro ) 
                || file_exists( trailingslashit( dirname( CHLD_THM_CFG_DIR ) ) . self::$ctcpro ) ):
                // check if old version is installed
                add_action( 'admin_init', 'ChildThemeConfiguratorUpgrade::check_version' );
            endif;
            // setup admin hooks
            if ( is_multisite() )
                add_action( 'network_admin_menu',   'ChildThemeConfigurator::network_admin' );
            add_action( 'admin_menu',               'ChildThemeConfigurator::admin' );
            // add plugin upgrade notification
            add_action( 'in_plugin_update_message-' . self::$plugin, 
                                                    'ChildThemeConfigurator::upgrade_notice', 10, 2 );
            // setup ajax actions
            add_action( 'wp_ajax_ctc_update',       'ChildThemeConfigurator::save' );
            add_action( 'wp_ajax_ctc_query',        'ChildThemeConfigurator::query' );
            add_action( 'wp_ajax_ctc_dismiss',      'ChildThemeConfigurator::dismiss' );
            add_action( 'wp_ajax_pro_dismiss',      'ChildThemeConfiguratorUpgrade::ajax_dismiss_notice' );
            add_action( 'wp_ajax_ctc_analyze',      'ChildThemeConfigurator::analyze' );
            
            // initialize languages
            add_action( 'init',                     'ChildThemeConfigurator::lang' );
            
            // prevent old Pro activation
            if ( isset( $_GET[ 'action' ] ) 
                && isset( $_GET[ 'plugin' ] ) 
                && 'activate' == $_GET[ 'action' ] 
                && self::$oldpro == $_GET[ 'plugin' ] )
                unset( $_GET[ 'action' ] );
        }
        
        static function ctc() {
            // create admin object
            if ( !isset( self::$instance ) ):
                self::$instance = new ChildThemeConfiguratorAdmin( __FILE__ );
            endif;
            return self::$instance;
        }
        
        static function lang() {
            // initialize languages
            load_plugin_textdomain( 'child-theme-configurator', FALSE, basename( CHLD_THM_CFG_DIR ) . '/lang' );
        }
        
        static function save() {
            // ajax write
            self::ctc()->ajax_save_postdata();
        }
        
        static function query() {
            // ajax read
            self::ctc()->ajax_query_css();
        }
                
        static function dismiss() {
            self::ctc()->ajax_dismiss_notice();
        }
    
        static function analyze() {
            self::ctc()->ajax_analyze();
        }
    
        static function network_admin() {
            $hook = add_theme_page( 
                    __( 'Child Theme Configurator', 'child-theme-configurator' ), 
                    __( 'Child Themes', 'child-theme-configurator' ), 
                    'install_themes', 
                    CHLD_THM_CFG_MENU, 
                    'ChildThemeConfigurator::render' 
            );
            add_action( 'load-' . $hook, 'ChildThemeConfigurator::page_init' );
        }
        
        static function admin() {
            $hook = add_management_page(
                    __( 'Child Theme Configurator', 'child-theme-configurator' ), 
                    __( 'Child Themes', 'child-theme-configurator' ), 
                    'install_themes', 
                    CHLD_THM_CFG_MENU, 
                    'ChildThemeConfigurator::render' 
            );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ChildThemeConfigurator::action_links' );
            add_action( 'load-' . $hook, 'ChildThemeConfigurator::page_init' );        
        }
        
        static function action_links( $actions ) {
            $actions[] = '<a href="' . admin_url( 'tools.php?page=' . CHLD_THM_CFG_MENU ). '">' 
                . __( 'Child Themes', 'child-theme-configurator' ) . '</a>' . LF;
            return $actions;
        }
        
        static function page_init() {
            // start admin controller
            self::ctc()->ctc_page_init();
        }
        
        static function render() {
            // display admin page
            self::ctc()->render();
        }
        
        static function version_notice() {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            unset( $_GET[ 'activate' ] );
            echo '<div class="notice-warning notice is-dismissible"><p>' . 
                sprintf( __( 'Child Theme Configurator requires WordPress version %s or later.', 'child-theme-configurator' ), 
                CHLD_THM_CFG_MIN_WP_VERSION ) . '</p></div>' . LF;
        }

        static function upgrade_notice( $current, $new ){
           if ( isset( $new->upgrade_notice ) && strlen( trim ( $new->upgrade_notice ) ) )
                echo '<p style="background-color:#d54d21;padding:1em;color:#fff;margin: 9px 0">'
                    . esc_html( $new->upgrade_notice ) . '</p>';
        }
        
    }
    