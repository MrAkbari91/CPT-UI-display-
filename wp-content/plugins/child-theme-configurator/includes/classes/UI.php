<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/*
    Class: ChildThemeConfiguratorUI
    Plugin URI: http://www.childthemeplugin.com/
    Description: Handles the plugin User Interface
    Author: Lilaea Media
    Author URI: http://www.lilaeamedia.com/
    Text Domain: chld_thm_cfg
    Domain Path: /lang
    License: GPLv2
    Copyright (C) 2014-2018 Lilaea Media
*/
class ChildThemeConfiguratorUI {

    var $warnings = array();
    var $swatch_txt;
    var $colors;
    
    function __construct() {
        // always load dict_sel for UI
        $this->css()->load_config( 'dict_sel' );
        add_filter( 'chld_thm_cfg_files_tab_filter',    array( $this, 'render_files_tab_options' ) );
        add_action( 'chld_thm_cfg_tabs',                array( $this, 'render_addl_tabs' ), 10, 4 );
        add_action( 'chld_thm_cfg_panels',              array( $this, 'render_addl_panels' ), 10, 4 );
        add_action( 'chld_thm_cfg_related_links',       array( $this, 'render_lilaea_plug' ) );
        add_action( 'chld_thm_cfg_before_tabs',         array( $this, 'render_current_theme' ), 5 );
        add_action( 'chld_thm_cfg_before_tabs',         array( $this, 'render_debug_toggle' ), 100 );
        add_action( 'chld_thm_cfg_file_form_buttons',   array( $this, 'render_file_form_buttons' ), 10, 1 );
        add_action( 'chld_thm_cfg_admin_notices',       array( $this, 'get_colors' ) );
        add_action( 'admin_enqueue_scripts',            array( $this, 'enqueue_scripts' ), 99 );
        add_filter( 'chld_thm_cfg_localize_array',      array( $this, 'filter_localize_array' ) );
        add_action( 'all_admin_notices',                array( $this, 'all_admin_notices' ) );
        $this->swatch_txt = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'child-theme-configurator' );
    }
    
    // helper function to globalize ctc object
    function ctc() {
        return ChildThemeConfigurator::ctc();
    }
    
    function css() {
        return ChildThemeConfigurator::ctc()->css;
    }
    
    function render() {
        // load web fonts for this theme
        if ( $imports = $this->css()->get_prop( 'imports' ) ):
            $ext = 0;
            foreach ( $imports as $import ):
                $this->ctc()->convert_import_to_enqueue( $import, ++$ext, TRUE );
            endforeach;
        endif;
        //$themes     = $this->ctc()->themes;
        //$child      = $this->css()->get_prop( 'child' );
        //$hidechild  = apply_filters( 'chld_thm_cfg_hidechild', ( count( $this->ctc()->themes ) ? '' : 'disabled' ) );
        //$enqueueset = ( isset( $this->css()->enqueue ) && $child );
        $this->ctc()->debug( 'Enqueue set: ' . ( $this->enqueue_is_set() ? 'TRUE' : 'FALSE' ), __FUNCTION__, __CLASS__ );
        //$imports    = $this->css()->get_prop( 'imports' );
        //$id         = 0;
        $this->ctc()->fs_method = get_filesystem_method();
        add_thickbox();
        include ( CHLD_THM_CFG_DIR . '/includes/forms/main.php' ); 
    } 

    function enqueue_is_set(){
        return isset( $this->css()->enqueue ) && $this->css()->get_prop( 'child' );         
    }
    
    function maybe_disable(){
        echo apply_filters( 'chld_thm_cfg_maybe_disable', ( count( $this->ctc()->themes[ 'child' ] ) ? '' : 'ctc-disabled' ) );
    }
    function supports_disable(){
        if ( defined( 'CHLD_THM_CFG_PRO_VERSION' ) && version_compare( '2.2.0', CHLD_THM_CFG_PRO_VERSION, '<' ) )
            return TRUE;
        return ( !defined( 'CHLD_THM_CFG_PRO_VERSION' ) );
    }
    
    function all_admin_notices(){
        do_action( 'chld_thm_cfg_admin_notices' );
    }
    
    function get_colors(){
        global $_wp_admin_css_colors;
        $user_admin_color = get_user_meta( get_current_user_id(), 'admin_color', TRUE );
        $this->colors = $_wp_admin_css_colors[ $user_admin_color ]->colors;
    }
    
    function render_current_theme(){
        include ( CHLD_THM_CFG_DIR . '/includes/forms/current-theme.php' ); 
    }
    
    function render_debug_toggle(){ 
        include ( CHLD_THM_CFG_DIR . '/includes/forms/debug-toggle.php' ); 
    }
    
    function render_file_form_buttons( $template ){
        include ( CHLD_THM_CFG_DIR . '/includes/forms/file-form-buttons.php' ); 
    }
    
    function render_theme_menu( $template = 'child', $selected = NULL ) {
        include ( CHLD_THM_CFG_DIR . '/includes/forms/theme-menu.php' ); 
    }
    
    function render_file_form( $template = 'parnt' ) {
        global $wp_filesystem; 
        if ( $theme = $this->ctc()->css->get_prop( $template ) ):
            $themeroot  = trailingslashit( get_theme_root() ) . trailingslashit( $theme );
            $files      = $this->ctc()->get_files( $theme, 'child' == $template ? 'template,stylesheet,txt' : 'template' );
            // This include is used for both parent template section and the child files section
            $ctcpage = apply_filters( 'chld_thm_cfg_admin_page', CHLD_THM_CFG_MENU );

            if ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ):
                $linktext = __( 'The Theme editor has been disabled. Template files must be edited offline.', 'child-theme-configurator' );
                $editorbase = '';
                $editorlink = '';
            else:
                $linktext = __( 'Click here to edit template files using the Theme Editor', 'child-theme-configurator' );
                $editorbase = apply_filters( 'chld_thm_cfg_editor_base', ( is_multisite() ? network_admin_url( 'theme-editor.php' ) : admin_url( 'theme-editor.php' ) ) . '?' ) . 'theme=' . $this->ctc()->css->get_prop( 'child' );
                $editorlink = '<a href="' . $editorbase . '&file=%s" title="%s">%s</a>';
            endif;
            $counter    = 0;
            sort( $files );
            ob_start();
            foreach ( $files as $file ):
                $templatefile = $file; //preg_replace( '%\.php$%', '', $file );
                include ( CHLD_THM_CFG_DIR . '/includes/forms/file.php' );            
            endforeach;
            $inputs = ob_get_contents();
            ob_end_clean();
            if ( $counter ):
                include ( CHLD_THM_CFG_DIR . '/includes/forms/fileform.php' );            
            endif;
        else:
            echo $template . ' theme not set.';
        endif;
    }
    
    function render_image_form() {
         
        if ( $theme = $this->ctc()->css->get_prop( 'child' ) ):
            $themeuri   = trailingslashit( get_theme_root_uri() ) . trailingslashit( $theme );
            $themeroot  = trailingslashit( get_theme_root() ) . trailingslashit( $theme );
            $files      = $this->ctc()->get_files( $theme, 'img' );
            
            $counter = 0;
            sort( $files );
            ob_start();
            foreach ( $files as $file ):
                $imagesize = getimagesize( $themeroot . $file ); // added 2.3.0
                $templatefile = preg_replace( '/^images\//', '', $file );
                include( CHLD_THM_CFG_DIR . '/includes/forms/image.php' );             
            endforeach;
            $inputs = ob_get_clean();
            if ( $counter ) include( CHLD_THM_CFG_DIR . '/includes/forms/images.php' );
        endif;
    }
    
    function render_settings_errors() {
        include ( CHLD_THM_CFG_DIR . '/includes/forms/settings-errors.php' ); 
    }
    
    function render_help_content() {
        global $wp_version;
        if ( version_compare( $wp_version, '3.3' ) >= 0 ):
            $screen = get_current_screen();
                
            // load help content via output buffer so we can use plain html for updates
            // then use regex to parse for help tab parameter values
            
            $regex_sidebar = '/' . preg_quote( '<!-- BEGIN sidebar -->' ) . '(.*?)' . preg_quote( '<!-- END sidebar -->' ) . '/s';
            $regex_tab = '/' . preg_quote( '<!-- BEGIN tab -->' ) . '\s*<h\d id="(.*?)">(.*?)<\/h\d>(.*?)' . preg_quote( '<!-- END tab -->' ) . '/s';
            $locale = get_locale();
            $dir = CHLD_THM_CFG_DIR . '/includes/help/';
            $file = $dir . $locale . '.php';
            if ( !is_readable( $file ) ) $file = $dir . 'en_US.php';
            ob_start();
            include( $file );
            $help_raw = ob_get_clean();
            // parse raw html for tokens
            preg_match( $regex_sidebar, $help_raw, $sidebar );
            preg_match_all( $regex_tab, $help_raw, $tabs );

            // Add help tabs
            if ( isset( $tabs[ 1 ] ) ):
                $priority = 0;
                while( count( $tabs[ 1 ] ) ):
                    $id         = array_shift( $tabs[ 1 ] );
                    $title      = array_shift( $tabs[ 2 ] );
                    $content    = array_shift( $tabs[ 3 ] );
                    $tab = array(
                        'id'        => $id,
                        'title'     => $title,
                        'content'   => $content, 
                        'priority'  => ++$priority,
                    );
                    $screen->add_help_tab( $tab );
                endwhile;
            endif;
            if ( isset( $sidebar[ 1 ] ) )
                $screen->set_help_sidebar( $sidebar[ 1 ] );
        endif;
    }
    
    function render_addl_tabs( $active_tab = NULL, $hidechild = '', $enqueueset = TRUE ) {
        include ( CHLD_THM_CFG_DIR . '/includes/forms/addl_tabs.php' );            
    }

    function render_addl_panels( $active_tab = NULL, $hidechild = '', $enqueueset = TRUE ) {
        include ( CHLD_THM_CFG_DIR . '/includes/forms/addl_panels.php' );            
    }

    function render_lilaea_plug() {
        include ( CHLD_THM_CFG_DIR . '/includes/forms/related.php' );
    }
    
    function render_files_tab_options( $output ) {
        $regex = '%<div class="ctc\-input\-cell ctc-clear">.*?(</form>).*%s';
        $output = preg_replace( $regex, "$1", $output );
        return $output;
    }
    
    function render_notices( $msg ) { 
        include ( CHLD_THM_CFG_DIR . '/includes/forms/notices.php' );
    }

    function get_theme_screenshot() {
        
        foreach ( array_keys( $this->ctc()->imgmimes ) as $extreg ): 
            foreach ( explode( '|', $extreg ) as $ext ):
                if ( $screenshot = $this->ctc()->css->is_file_ok( $this->ctc()->css->get_child_target( 'screenshot.' . $ext ) ) ):
                    $screenshot = trailingslashit( get_theme_root_uri() ) . $this->ctc()->theme_basename( '', $screenshot );
                    return $screenshot . '?' . time();
                endif;
            endforeach; 
        endforeach;
        return FALSE;
    }
    
    function cmp_theme( $a, $b ) {
        return strcmp( strtolower( $a[ 'Name' ] ), strtolower( $b[ 'Name' ] ) );
    }
        
    function enqueue_scripts() {
        wp_enqueue_style( 'chld-thm-cfg-admin', CHLD_THM_CFG_URL . 'css/chldthmcfg.css', array(), CHLD_THM_CFG_VERSION );
        
        // we need to use local jQuery UI Widget/Menu/Selectmenu 1.11.2 because selectmenu is not included in < 1.11.2
        // this will be updated in a later release to use WP Core scripts when it is widely adopted
        
        if ( !wp_script_is( 'jquery-ui-selectmenu', 'registered' ) ): // selectmenu.min.js
            wp_enqueue_script( 'jquery-ui-selectmenu', CHLD_THM_CFG_URL . 'js/selectmenu.min.js', 
                array( 'jquery','jquery-ui-core','jquery-ui-position' ), CHLD_THM_CFG_VERSION, TRUE );
        endif;
        
        wp_enqueue_script( 'chld-thm-cfg-spectrum', CHLD_THM_CFG_URL . 'js/spectrum.min.js', array( 'jquery' ), CHLD_THM_CFG_VERSION, TRUE );
        wp_enqueue_script( 'chld-thm-cfg-ctcgrad', CHLD_THM_CFG_URL . 'js/ctcgrad.min.js', array( 'jquery' ), CHLD_THM_CFG_VERSION, TRUE );
        wp_enqueue_script( 'chld-thm-cfg-admin', CHLD_THM_CFG_URL . 'js/chldthmcfg' . ( SCRIPT_DEBUG ? '' : '.min' ) . '.js',
        //wp_enqueue_script( 'chld-thm-cfg-admin', CHLD_THM_CFG_URL . 'js/chldthmcfg.js',
            array(
                'jquery-ui-autocomplete', 
                'jquery-ui-selectmenu',   
                'chld-thm-cfg-spectrum',
                'chld-thm-cfg-ctcgrad'
            ), CHLD_THM_CFG_VERSION, TRUE );
            
        $localize_array = apply_filters( 'chld_thm_cfg_localize_script', array(
            'converted'                 => $this->css()->get_prop( 'converted' ),
            'ssl'                       => is_ssl(),
            'homeurl'                   => home_url( '/' ) . '?ModPagespeed=off&' . ( defined( 'WP_ROCKET_VERSION' ) ? '' : 'ao_noptimize=1&' ) . 'preview_ctc=1', // WP Rocket serves cached page when ao_nooptimize is present v2.3.0
            'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
            'customizerurl'             => admin_url( 'customize.php' ),
            'theme_uri'                 => get_theme_root_uri(),
            'theme_dir'                 => basename( get_theme_root_uri() ),
            'page'                      => CHLD_THM_CFG_MENU,
            'themes'                    => $this->ctc()->themes,
            'source'                    => apply_filters( 'chld_thm_cfg_source_uri', get_theme_root_uri() . '/' 
                . $this->css()->get_prop( 'parnt' ) . '/style.css', $this->css() ),
            'target'                    => apply_filters( 'chld_thm_cfg_target_uri', get_theme_root_uri() . '/' 
                . $this->css()->get_prop( 'child' ) . '/style.css', $this->css() ),
            'parnt'                     => $this->css()->get_prop( 'parnt' ),
            'child'                     => $this->css()->get_prop( 'child' ),
            'addl_css'                  => $this->css()->get_prop( 'addl_css' ),
            'forcedep'                  => $this->css()->get_prop( 'forcedep' ),
            'swappath'                  => $this->css()->get_prop( 'swappath' ),
            'imports'                   => $this->css()->get_prop( 'imports' ),
            'converted'                 => $this->css()->get_prop( 'converted' ),
            'copy_mods'                 => $this->ctc()->copy_mods,
            'is_debug'                  => $this->ctc()->is_debug,
            '_background_url_txt'       => __( 'URL/None', 'child-theme-configurator' ),
            '_background_origin_txt'    => __( 'Origin', 'child-theme-configurator' ),
            '_background_color1_txt'    => __( 'Color 1', 'child-theme-configurator' ),
            '_background_color2_txt'    => __( 'Color 2', 'child-theme-configurator' ),
            '_border_width_txt'         => __( 'Width/None', 'child-theme-configurator' ),
            '_border_style_txt'         => __( 'Style', 'child-theme-configurator' ),
            '_border_color_txt'         => __( 'Color', 'child-theme-configurator' ),
            'swatch_txt'                => $this->swatch_txt,
            'load_txt'                  => __( 'Are you sure you wish to RESET? This will destroy any work you have done in the Configurator.', 'child-theme-configurator' ),
            'important_txt'             => __( '<span style="font-size:10px">!</span>', 'child-theme-configurator' ),
            'selector_txt'              => __( 'Selectors', 'child-theme-configurator' ),
            'close_txt'                 => __( 'Close', 'child-theme-configurator' ),
            'edit_txt'                  => __( 'Edit Selector', 'child-theme-configurator' ),
            'cancel_txt'                => __( 'Cancel', 'child-theme-configurator' ),
            'rename_txt'                => __( 'Rename', 'child-theme-configurator' ),
            'css_fail_txt'              => __( 'The stylesheet cannot be displayed.', 'child-theme-configurator' ),
            'child_only_txt'            => __( '(Child Only)', 'child-theme-configurator' ),
            'inval_theme_txt'           => __( 'Please enter a valid Child Theme.', 'child-theme-configurator' ),
            'inval_name_txt'            => __( 'Please enter a valid Child Theme name.', 'child-theme-configurator' ),
            'theme_exists_txt'          => __( '<strong>%s</strong> exists. Please enter a different Child Theme', 'child-theme-configurator' ),
            'js_txt'                    => __( 'The page could not be loaded correctly.', 'child-theme-configurator' ),
            'jquery_txt'                => __( 'Conflicting or out-of-date jQuery libraries were loaded by another plugin:', 'child-theme-configurator' ),
            'plugin_txt'                => __( 'Deactivating or replacing plugins may resolve this issue.', 'child-theme-configurator' ),
            'contact_txt'               => sprintf( __( '%sWhy am I seeing this?%s', 'child-theme-configurator' ),
                '<a target="_blank" href="' . CHLD_THM_CFG_DOCS_URL . '/how-to-use/#script_dep">',
                '</a>' ),
            'nosels_txt'                => __( 'No Styles Available. Check Parent/Child settings.', 'child-theme-configurator' ),
            'anlz1_txt'                 => __( 'Updating', 'child-theme-configurator' ),
            'anlz2_txt'                 => __( 'Checking', 'child-theme-configurator' ),
            'anlz3_txt'                 => __( 'The theme "%s" generated unexpected PHP debug output.', 'child-theme-configurator' ),
            'anlz4_txt'                 => __( 'The theme "%s" could not be analyzed because the preview did not render correctly.', 'child-theme-configurator' ),
            'anlz5_txt'                 => sprintf( __( '<p>First, verify you can <a href="%s">preview your home page with the Customizer</a> and try analyzing again.</p><p>If that does not work, try temporarily disabling plugins that <strong>minify CSS</strong> or that <strong>force redirects between HTTP and HTTPS</strong>.</p>', 'child-theme-configurator' ), admin_url( '/customize.php' ) ), // . '?page=' . CHLD_THM_CFG_MENU ),
            'anlz6_txt'                 => __( 'Click to show/hide PHP debug output', 'child-theme-configurator' ),
            // php error description modified v2.3.0
            'anlz7_txt'                 => __( '<p><strong>PLEASE NOTE:</strong></p><p><em>The analyzer reveals errors that may otherwise go undetected. Unless this is a fatal error, WordPress may appear to work correctly; however, PHP will continue to log the error until it is resolved. Please contact the author of any theme or plugin</em> <strong>mentioned above</strong> <em>and cut/paste the error from the text area.</em> <strong>Do not use a screen capture as it may cut off part of the error text.</strong> <em>Additional information about the error may also be available in the <a href="http://www.childthemeplugin.com/child-theme-faqs/" target="_blank">CTC documentation</a>.</em></p>', 'child-theme-configurator' ),
            'anlz8_txt'                 => __( 'Do Not Activate "%s"! A PHP FATAL ERROR has been detected.', 'child-theme-configurator' ),
            'anlz9_txt'                 => __( 'This theme loads stylesheets after the wp_styles queue.', 'child-theme-configurator' ),
            'anlz10_txt'                => __( '<p>This makes it difficult for plugins to override these styles. You can try to resolve this using the  "Repair header template" option (Step 6, "Additional handling options", below).</p>', 'child-theme-configurator' ),
            'anlz11_txt'                => __( "This theme loads the parent theme's <code>style.css</code> file outside the wp_styles queue.", 'child-theme-configurator' ),
            'anlz12_txt'                => __( '<p>This is common with older themes but requires the use of <code>@import</code>, which is no longer recommended. You can try to resolve this using the "Repair header template" option (see step 6, "Additional handling options", below).</p>', 'child-theme-configurator' ),
            'anlz13_txt'                => __( 'This child theme does not load a Configurator stylesheet.', 'child-theme-configurator' ),
            'anlz14_txt'                => __( '<p>If you want to customize styles using this plugin, please click "Configure Child Theme" again to add this to the settings.</p>', 'child-theme-configurator' ),
            'anlz15_txt'                => __( "This child theme uses the parent stylesheet but does not load the parent theme's <code>style.css</code> file.", 'child-theme-configurator' ),
            'anlz16_txt'                => __( '<p>Please select a stylesheet handling method or check "Ignore parent theme stylesheets" (see step 6, below).</p>', 'child-theme-configurator' ),
            'anlz17_txt'                => __( 'This child theme appears to be functioning correctly.', 'child-theme-configurator' ),
            'anlz18_txt'                => __( 'This theme appears OK to use as a Child theme.', 'child-theme-configurator' ),
            'anlz19_txt'                => __( 'This Child Theme has not been configured for this plugin.', 'child-theme-configurator' ),
            'anlz20_txt'                => __( '<p>The Configurator makes significant modifications to the child theme, including stylesheet changes and additional php functions. Please consider using the DUPLICATE child theme option (see step 1, above) and keeping the original as a backup.</p>', 'child-theme-configurator' ),
            'anlz21_txt'                => __( "This child theme uses <code>@import</code> to load the parent theme's <code>style.css</code> file.", 'child-theme-configurator' ),
            'anlz22_txt'                => __( '<p>Please consider selecting "Use the WordPress style queue" for the parent stylesheet handling option (see step 6, below).</p>', 'child-theme-configurator' ),
            'anlz23_txt'                => __( 'This theme loads additional stylesheets after the <code>style.css</code> file:', 'child-theme-configurator' ),
            'anlz24_txt'                => __( '<p>Consider saving new custom styles to a "Separate stylesheet" (see step 5, below) so that you can customize these styles.</p>', 'child-theme-configurator' ),
            'anlz25_txt'                => __( "The parent theme's <code>style.css</code> file is being loaded automatically.", 'child-theme-configurator' ),
            'anlz26_txt'                => __( '<p>The Configurator selected "Do not add any parent stylesheet handling" for the "Parent stylesheet handling" option (see step 6, below).</p>', 'child-theme-configurator' ),
            'anlz27_txt'                => __( "This theme does not require the parent theme's <code>style.css</code> file for its appearance.", 'child-theme-configurator' ),
            'anlz28_txt'                => __( "This Child Theme was configured to accomodate a hard-coded stylesheet link.", 'child-theme-configurator' ),
            'anlz29_txt'                => __( '<p>This workaround was used in earlier versions of CTC and can be eliminated by using the "Repair header template" option (see step 6, "Additional handling options", below).</p>', 'child-theme-configurator' ),
            'anlz30_txt'                => __( 'Click to show/hide raw analysis data. Please include contents below with any support requests.', 'child-theme-configurator' ),
            'anlz31_txt'                => __( 'This child theme was configured using the CTC Pro "Genesis stylesheet handling" method.', 'child-theme-configurator' ),
            'anlz32_txt'                => __( '<p>This method has been replaced by the "Separate stylesheet" and "Ignore Parent Theme" options ( selected below ) for broader framework compatability.</p>', 'child-theme-configurator' ),
            'anlz33_txt'                => __( '<p>%1Click Here%2 to view the theme as viewed by the Analyzer.</p>', 'child-theme-configurator' ),
            'anlzrtl_txt'               => __( 'This theme uses a RTL (right-to-left) stylesheet that is not being loaded in the child theme.', 'child-theme-configurator' ), // added 2.3.0
            'anlzrtl2_txt'              => __( 'Use the Web Fonts tab to add a link to the parent RTL stylesheet. See the documentation for more information.</p>', 'child-theme-configurator' ), // added 2.3.0
            'anlzcache1_txt'            => __( 'Both WP Rocket and Autoptimize plugins are enabled.', 'child-theme-configurator' ),
            'anlzcache2_txt'            => __( 'The combination of these two plugins interferes with the Analysis results. Please temporarily deactivate one of them and Analyze again.', 'child-theme-configurator' ),
            'anlzconfig_txt'            => __( '<p><strong>The WordPress configuration file has been modified incorrectly.</strong> Please see <a href="http://www.childthemeplugin.com/child-theme-faqs/#constants" target="_blank">this FAQ</a> for more information.</p>', 'child-theme-configurator' ),
            'dismiss_txt'               => __( 'Dismiss this notice.', 'child-theme-configurator' ),
        ) );
        wp_localize_script(
            'chld-thm-cfg-admin', 
            'ctcAjax', 
            apply_filters( 'chld_thm_cfg_localize_array', $localize_array )
        );
    }
    
    function filter_localize_array( $arr ) {
        $arr[ 'pluginmode' ] = !$this->ctc()->is_theme();
        return $arr;
    }
    
}
?>
