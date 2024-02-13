<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class ChildThemeConfiguratorUpgrade {
    
	private static $old;
    private static $version = '1.0.0';
    //private static $lilaeaupdateurl = 'http://www.lilaeamedia.com/updates/update.php?product=intelliwidget-pro';
    private static $lilaeaupdateurl = 'http://www.lilaeamedia.com/updates/update-replace.php';
    private static $update;
    
    static function get_version(){
        $info = get_plugins( '/' . dirname( self::$old ) );
        if ( isset( $info[ basename( self::$old ) ] ) )
            self::$version = $info[ basename( self::$old ) ][ 'Version' ];
    }
    
    static function check_version(){
        if ( is_admin() && current_user_can( 'install_plugins' ) && !self::has_dismissed() ):
            if ( file_exists( trailingslashit( dirname( CHLD_THM_CFG_DIR ) ) . ChildThemeConfigurator::$oldpro ) ):
                self::$old = ChildThemeConfigurator::$oldpro;
                self::get_version();
                if ( isset( $_REQUEST[ 'ctc_pro_upgrade' ] ) && wp_verify_nonce( $_REQUEST[ 'ctcnonce' ], 'ctc_pro_upgrade' ) ):
                    self::do_upgrade();
                else:
                    self::upgrade_notice();
                endif;
            else:
                // check if old version is installed but inactive
                self::$old = ChildThemeConfigurator::$ctcpro;
                self::get_version();
                if ( self::$version < CHLD_THM_CFG_PRO_MIN_VERSION ):
                    // do upgrade if user requests it
                    if ( isset( $_REQUEST[ 'ctc_pro_upgrade' ] ) && wp_verify_nonce( $_REQUEST[ 'ctcnonce' ], 'ctc_pro_upgrade' ) ):
                        self::do_upgrade();
                    else:
                        // otherwise show notice
                        self::upgrade_notice();
                    endif;
                endif;
            endif;
        endif;
    }
        
    static function upgrade_notice(){
        if ( isset( $_GET[ 'action' ] ) 
            && 'activate' == $_GET[ 'action' ]
            && isset( $_GET[ 'plugin' ] ) 
            && self::$old == $_GET[ 'plugin' ] )
            unset( $_GET[ 'action' ] );
        deactivate_plugins( self::$old, FALSE, is_network_admin() );
        add_action( 'all_admin_notices', 'ChildThemeConfiguratorUpgrade::admin_notice' );
    }
    
    static function admin_notice(){
        $update_key = isset( $_POST[ 'ctc_update_key' ] ) ? sanitize_text_field( $_POST[ 'ctc_update_key' ] ) : self::get_update_key();
        if ( isset( $_GET[ 'invalidkey' ] ) || empty( $update_key ) ):
            //
            $input = '<input type="text" name="ctc_update_key" value="" autocomplete="off" placeholder="' . __( 'Enter your Update Key', 'child-theme-configurator' ) . '" />'; 
            if ( isset( $_GET[ 'invalidkey' ] ) ):?><div class="notice-error notice is-dismissible"><p><?php printf( __( 'Sorry, we could not validate your Update Key. Please try again or, if you need assistance, please %s', 'child-theme-configurator' ), sprintf( '<a href="%s/contact/" target="_blank">%s</a>', LILAEAMEDIA_URL, __( 'contact us.', 'child-theme-configurator' ) ) ); ?></p></div><?php endif;
        else:
            //
            $input = '<input type="hidden" name="ctc_update_key" value="' . esc_attr( $update_key ) . ' " />';
        endif;
?><div class="notice-warning notice is-dismissible ctc-pro-upgrade-notice"><form action="" method="post"><?php wp_nonce_field( 'ctc_pro_upgrade', 'ctcnonce' ) ?>
<p><strong><?php echo sprintf( __( 'Child Theme Configurator Pro version %s', 'child-theme-configurator' ), self::$version ) . __( ' is not compatible with the installed version of Child Theme Configurator and has been deactivated.', 'child-theme-configurator' ); ?>
</strong></p>
<p><?php _e( 'You can upgrade to the latest version by clicking the button below. After validating the Update Key from your order, WordPress will retrieve the plugin from our website and install it automatically. If you no longer wish to use the premium version, you can dismiss this notice by clicking the close icon (x) at the top right.', 'child-theme-configurator' ); ?></p>
<p><?php echo $input; ?> <input type="submit" name="ctc_pro_upgrade" value="<?php _e( 'Upgrade Now', 'child-theme-configurator' ); ?>" class="button button-primary" /></p></form>
<script>
jQuery( document ).ready(function($){
    $( document ).on( 'click', '.notice-dismiss', function(){ //.iwpro-upgrade-notice
        $.post(
            '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            { '_wpnonce': '<?php echo wp_create_nonce( 'ctc_pro_dismiss' ); ?>', 'action': 'ctc_pro_dismiss' }
        );
    });
});
</script></div>
<?php
    }

    /**
     * ajax callback to dismiss upgrade notice 
     */
    static function ajax_dismiss_notice() {
        if ( wp_verify_nonce( $_POST[ '_wpnonce' ], 'ctc_pro_dismiss' ) )
            update_user_meta( get_current_user_id(), 'ctc_pro_upgrade_notice' , CHLD_THM_CFG_VERSION );
        die(0);
    }
    
    static function has_dismissed(){
        $dismissed = get_user_meta( get_current_user_id(), 'ctc_pro_upgrade_notice', TRUE );
        if ( $dismissed == CHLD_THM_CFG_VERSION )
            return TRUE;
        return FALSE;
    }
    
    static function reset_dismissed(){
        delete_user_meta( get_current_user_id(), 'ctc_pro_upgrade_notice' );
    }
        
    static function get_update_key(){
        if ( $options = get_site_option( CHLD_THM_CFG_OPTIONS ) )
            return $options[ 'update_key' ];
        return FALSE;
    }
    
    static function set_update_key( $key ){
        if ( !( $options = get_site_option( CHLD_THM_CFG_OPTIONS ) ) )
            $options = array();
        $options[ 'update_key' ] = $key;
        update_site_option( CHLD_THM_CFG_OPTIONS, $options );
    }
    
    static function do_upgrade(){
        // if $old == $new upgrade
        /*
         */
        // otherwise install
            // set Install transient
            // do install
            // delete old version
        // if upgrade
        
		add_filter( 'site_transient_update_plugins', 'ChildThemeConfiguratorUpgrade::injectUpdate' ); //WP 3.0+
		add_filter( 'transient_update_plugins', 'ChildThemeConfiguratorUpgrade::injectUpdate' );      //WP 2.8+

		// Clear the version number cache when something - anything - is upgraded or WP clears the update cache.
        
		add_filter( 'upgrader_post_install', 'ChildThemeConfiguratorUpgrade::clearCachedVersion' );
		add_action( 'delete_site_transient_update_plugins', 'ChildThemeConfiguratorUpgrade::clearCachedVersion' );

        $key = isset( $_REQUEST[ 'ctc_update_key' ] ) ? sanitize_text_field( $_REQUEST[ 'ctc_update_key' ] ) : self::get_update_key();
		//Query args to append to the URL. Plugins can add their own by using a filter callback (see addQueryArgFilter()).
		$args = array(
            'installed_version' => self::$version,
            'key'               => $key,
            'product'           => dirname( self::$old ),
        );
		
		//Various options for the wp_remote_get() call. Plugins can filter these, too.
		$options = array(
			'timeout' => 10, //seconds
			'headers' => array(
				'Accept' => 'application/json'
			),
		);
		
		//The plugin info should be at 'http://your-api.com/url/here/$slug/info.json'
        $url = add_query_arg( $args, self::$lilaeaupdateurl );

		$result = wp_remote_get(
			$url,
			$options
		);

		//Try to parse the response
		$pluginInfo = NULL;
		if ( !is_wp_error( $result ) 
            && isset( $result[ 'response' ][ 'code' ] ) 
            && ( $result[ 'response' ][ 'code' ] == 200 ) 
            && !empty( $result['body'] ) ):
            
			$pluginInfo = json_decode( $result['body'] );
            if ( empty( $pluginInfo ) || version_compare( $pluginInfo->version, self::$version, '<' )  ):
                $query = array( 'invalidkey' => 1 );
                $url = add_query_arg( $query );
                wp_redirect( $url );
                die();
            endif;
            // create update object
            $update = new StdClass;
		
		    $update->id             = 0;
		    $update->slug           = $pluginInfo->slug;
            $update->new_version    = $pluginInfo->version;
            $update->url            = $pluginInfo->homepage;
            $update->package        = $pluginInfo->download_url;
            if ( !empty( $pluginInfo->upgrade_notice ) )
                $update->upgrade_notice = $pluginInfo->upgrade_notice;
            self::$update = $update;
    
            // add update to cache
            wp_update_plugins();
            // run upgrader
            $title                  = __( 'Update Plugin', 'child-theme-configurator' );
            $plugin                 = self::$old;
            //$nonce                  = 'ctc_pro_upgrade'; //'upgrade-plugin_' . $plugin;
            $nonce                  = 'upgrade-plugin_' . self::$old;
            $url                    = 'update.php?action=upgrade-plugin&plugin=' . urlencode( self::$old ) 
                . '&ctc_pro_upgrade=1&ctcnonce=' . $_REQUEST[ 'ctcnonce' ];
            wp_enqueue_script( 'updates' );
            include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            require_once( ABSPATH . 'wp-admin/admin-header.php' );
    
            $upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( compact( 'title', 'nonce', 'url', 'plugin' ) ) );
            //return;
            $upgrader->upgrade( $plugin );
            include(ABSPATH . 'wp-admin/admin-footer.php');
            self::set_update_key( $key );

		endif;
	}

    static function injectUpdate( $updates ){
        
		if ( !empty( self::$update ) ):

			if ( !is_object( $updates ) ):
				$updates = new StdClass();
				$updates->response = array();
			endif;

			$updates->response[ self::$old ] = self::$update;

		elseif ( isset( $updates, $updates->response ) ):
        
			unset( $updates->response[ self::$old ] );
            
		endif;
		return $updates;
    }
    
    static function clearCachedVersion( $args = NULL ){
        self::$update = NULL;
        return $args;
    }
    
    /**
     * deletes old version of plugin without removing option settings
     */
    static function delete_old_version() {
        if ( isset( $_REQUEST[ 'deleted' ] ) ) return;
        $slug = dirname( self::$old );
        // clean up hooks from < 2.2.0
        wp_clear_scheduled_hook( 'check_plugin_updates-' . $slug );
        delete_option( 'external_updates-' . $slug );
        // remove old Pro version
        if ( current_user_can( 'delete_plugins' ) ):
            $redir = NULL;
            if ( isset( $_GET[ 'action' ] ) ): 
                // unset action parameter if it is for old CTC Pro
                if ( 'activate' == $_GET[ 'action' ]
                    && isset( $_GET[ 'plugin' ] ) 
                    && self::$old == $_GET[ 'plugin' ] ):
                    unset( $_GET[ 'action' ] );
                // handle two-step FTP Authentication form
                elseif ( 'delete-selected' == $_GET[ 'action' ] 
                    && isset( $_GET[ 'verify-delete' ] ) 
                    && isset( $_GET[ 'checked' ] ) 
                    && self::$old == $_GET[ 'checked' ][ 0 ] ):
                    
                    unset( $_GET[ 'action' ] );
                    unset( $_GET[ 'checked' ] );
                    unset( $_GET[ 'verify-delete' ] );
                    unset( $_REQUEST[ 'action' ] );
                    unset( $_REQUEST[ 'checked' ] );
                    unset( $_REQUEST[ 'verify-delete' ] );
                    
                    $redir = self_admin_url( "plugins.php?activate=true" ); 
                elseif ( 'activate' != $_GET[ 'action' ] ):
                    return;
                endif;
            endif;
            // deactivate old Pro version
            deactivate_plugins( self::$old, FALSE, is_network_admin() );
            // remove uninstall hook so that options are preserved
            $uninstallable_plugins = (array) get_option( 'uninstall_plugins' );
            if ( isset( $uninstallable_plugins[ self::$old ] ) ):
                unset( $uninstallable_plugins[ self::$old ] );
                update_option( 'uninstall_plugins', $uninstallable_plugins );
            endif;
            unset( $uninstallable_plugins );
            // remove old Pro version
            $delete_result = delete_plugins( array( self::$old ) );
            //Store the result in a cache rather than a URL param due to object type & length
            global $user_ID;
            set_transient( 'plugins_delete_result_' . $user_ID, $delete_result ); 
            // force plugin cache to reload
            wp_cache_delete( 'plugins', 'plugins' );

            // if this is two-step FTP authentication, redirect back to activated
            if ( $redir ):
                if ( is_wp_error( $delete_result ) )
                    $redir = self_admin_url( "plugins.php?deleted=" . self::$old );
                wp_redirect( $redir );
                exit;
            endif;
        endif;
    }
}
