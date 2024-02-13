<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

/**
 * Provides methods required for preview to work without customizer options.
 * This only loads when CTC preview is loaded.
 */
class ChildThemeConfiguratorPreview {
    protected $theme;
    protected $original_stylesheet;
    protected $stylesheet;
    protected $template;
    protected $priorities   = array();
    protected $handles      = array();
    protected $queued       = array();
    protected $registered;
    
    public function __construct(){
        add_action( 'setup_theme',   array( $this, 'setup_theme' ) );
        add_filter( 'wp_redirect_status', array( $this, 'wp_redirect_status' ), 1000 );
         // Do not spawn cron (especially the alternate cron) while running the Customizer.
        remove_action( 'init', 'wp_cron' );

        // Do not run update checks when rendering the controls.
        remove_action( 'admin_init', '_maybe_update_core' );
        remove_action( 'admin_init', '_maybe_update_plugins' );
        remove_action( 'admin_init', '_maybe_update_themes' );
    }
    
    public function check_wp_queue(){
        global $wp_filter;
        if ( empty( $wp_filter[ 'wp_enqueue_scripts' ] ) )
            return;
        // Iterate through all the added hook priorities
        $this->priorities = array_keys( $wp_filter[ 'wp_enqueue_scripts' ]->callbacks );

        // add hook directly after each priority to get any added stylesheet handles
        foreach ( $this->priorities as $priority )
            add_action( 'wp_enqueue_scripts', array( $this, 'get_handles' ), $priority );
    }
    
    public function get_handles(){
        global $wp_styles;
        // remove priority from stack
        $priority = array_shift( $this->priorities );
        if ( !is_object( $wp_styles ) )
            return;
        // get handles queued since last check
        $this->handles[ $priority ] = array_diff( $wp_styles->queue, $this->queued );
        // add new handles to queued array
        $this->queued = array_merge( $this->queued, $this->handles[ $priority ] );
    }
    
    public function setup_theme() {
        // are we previewing? - removed nonce requirement to bool flag v2.2.5
        if ( empty( $_GET['preview_ctc'] ) || !current_user_can( 'switch_themes' ) )
            return;
        $this->original_stylesheet = get_stylesheet();
        $this->theme = wp_get_theme( isset( $_GET[ 'stylesheet' ] ) ? $_GET[ 'stylesheet' ] : NULL );
        if ( ! $this->is_theme_active() ):
            add_filter( 'template', array( $this, 'get_template' ) );
            add_filter( 'stylesheet', array( $this, 'get_stylesheet' ) );
			// @link: https://core.trac.wordpress.org/ticket/20027
			add_filter( 'pre_option_stylesheet', array( $this, 'get_stylesheet' ) );
			add_filter( 'pre_option_template', array( $this, 'get_template' ) );

            // swap out theme mods with preview theme mods
            add_filter( 'pre_option_theme_mods_' . $this->original_stylesheet, array( $this, 'preview_mods' ) );
        endif;
        add_action( 'wp_head', array( $this, 'check_wp_queue' ), 0 );
        // impossibly high priority to test for stylesheets loaded after wp_head()
        add_action( 'wp_print_styles', array( $this, 'test_css' ), 999999 );
        // pass the wp_styles queue back to use for stylesheet handle verification
        add_action( 'wp_footer', array( $this, 'parse_stylesheet' ) );
        send_origin_headers();
        // hide admin bar in preview
        show_admin_bar( false );
    }
    
    /**
     * Retrieves child theme mods for preview
     */        
    public function preview_mods() { 
        if ( $this->is_theme_active() ) return false;
        return get_option( 'theme_mods_' . $this->get_stylesheet() );
    }
    
    public function parse_stylesheet() {
        echo '<script>/*<![CDATA[' . LF;
        $queue = implode( "\n", $this->queued );
        global $wp_styles;
        $registered = implode( "\n", array_keys( $wp_styles->registered ) );
        echo 'BEGIN WP QUEUE' . LF . $queue . LF . 'END WP QUEUE' . LF;
        echo 'BEGIN WP REGISTERED' . LF . $registered . LF . 'END WP REGISTERED' . LF;
        if ( is_child_theme() ):
            // check for signals that indicate specific settings
            $file = get_stylesheet_directory() . '/style.css';
            if ( file_exists( $file ) && ( $styles = @file_get_contents( $file ) ) ):
                // is this child theme a standalone ( framework ) theme?
                if ( defined( 'CHLD_THM_CFG_IGNORE_PARENT' ) ):
                    echo 'CHLD_THM_CFG_IGNORE_PARENT' . LF;
                endif;
                // has this child theme been configured by CTC? ( If it has the timestamp, it is one of ours. )
                if ( preg_match( '#\nUpdated: \d\d\d\d\-\d\d\-\d\d \d\d:\d\d:\d\d\n#s', $styles ) ):
                    echo 'IS_CTC_THEME' . LF;
                endif;
                // is this child theme using the @import method?
                if ( preg_match( '#\@import\s+url\(.+?\/' . preg_quote( get_template() ) . '\/style\.css.*?\);#s', $styles ) ):
                    echo 'HAS_CTC_IMPORT' . LF;
                endif;
            endif;
        else:
            // Check if the parent style.css file is used at all. If not we can skip the parent stylesheet handling altogether.
            $file = get_template_directory() . '/style.css';
            if ( file_exists( $file ) && ( $styles = @file_get_contents( $file ) ) ):
                $styles = preg_replace( '#\/\*.*?\*\/#s', '', $styles );
                if ( preg_match_all( '#\@import\s+(url\()?(.+?)(\))?;#s', $styles, $imports ) ):
                    echo 'BEGIN IMPORT STYLESHEETS' . LF;
                    foreach ( $imports[ 2 ] as $import )
                        echo trim( str_replace( array( "'", '"' ), '', $import ) ) . LF;
                    echo 'END IMPORT STYLESHEETS' . LF;
                    
                elseif ( !preg_match( '#\s*([\[\.\#\:\w][\w\-\s\(\)\[\]\'\^\*\.\#\+:,"=>]+?)\s*\{(.*?)\}#s', $styles ) ):
                    echo 'NO_CTC_STYLES' . LF;
                endif;
            endif;
        endif;
        /**
         * Use the filter api to determine the parent stylesheet enqueue priority
         * because some themes do not use the standard 10 for various reasons.
         * We need to match this priority so that the stylesheets load in the correct order.
         */
        echo 'BEGIN CTC IRREGULAR' . LF;
        // Iterate through all the added hook priorities
        foreach ( $this->handles as $priority => $arr )
            // If this is a non-standard priority hook, determine which handles are being enqueued.
            // These will then be compared to the primary handle ( style.css ) 
            // to determine the enqueue priority to use for the parent stylesheet. 
            if ( $priority != 10 && !empty( $arr ) )
                echo $priority . ',' . implode( ",", $arr ) . LF;
        echo 'END CTC IRREGULAR' . LF;
;
        if ( defined( 'WP_CACHE' ) && WP_CACHE )
            echo 'HAS_WP_CACHE' . LF;
        if ( defined( 'AUTOPTIMIZE_PLUGIN_DIR' ) )
            echo 'HAS_AUTOPTIMIZE' . LF;
        if ( defined( 'WP_ROCKET_VERSION' ) )
            echo 'HAS_WP_ROCKET' . LF;
        echo ']]>*/</script>' . LF;
    }
    
    // enqueue dummy stylesheet with extremely high priority to test wp_head()
    public function test_css() {
        wp_enqueue_style( 'ctc-test', get_stylesheet_directory_uri() . '/ctc-test.css' );
    }
    
    public function woocommerce_unforce_ssl_checkout( $bool ){
        return FALSE;
    }

    public function wp_redirect_status( $status ) {
        return 200;
    }
    
    public function is_theme_active() {
        return $this->get_stylesheet() == $this->original_stylesheet;
    }
    
    public function get_template() {
        return $this->theme()->get_template();
    }
    
    public function get_stylesheet() {
        return $this->theme()->get_stylesheet();
    }
    
    
    public function theme() {
        return $this->theme;
    }
    
}