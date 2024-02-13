<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>

<div class="notice-warning notice is-dismissible<?php echo ( 'upgrade' == $msg ? ' ctc-upgrade-notice' : '' ); ?>" style="display:block">
  <?php
        switch( $msg ):
        
            case 'writable': ?>
  <p class="ctc-section-toggle" id="ctc_perm_options">
    <?php _e( 'The child theme is in read-only mode and Child Theme Configurator cannot apply changes. Click to see options', 'child-theme-configurator' ); ?>
  </p>
  <div class="ctc-section-toggle-content" id="ctc_perm_options_content">
    <p>
    <ol>
      <?php
        $ctcpage = apply_filters( 'chld_thm_cfg_admin_page', CHLD_THM_CFG_MENU );
        if ( 'WIN' != substr( strtoupper( PHP_OS ), 0, 3 ) ):
            _e( '<li>Temporarily set write permissions by clicking the button below. When you are finished editing, revert to read-only by clicking "Make read-only" under the "Files" tab.</li>', 'child-theme-configurator' );
?>
      <form action="" method="post">
        <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
        <input name="ctc_set_writable" class="button" type="submit" value="<?php _e( 'Make files writable', 'child-theme-configurator' ); ?>"/>
      </form>
      <?php   endif;
        _e( '<li><a target="_blank"  href="http://codex.wordpress.org/Editing_wp-config.php#WordPress_Upgrade_Constants" title="Editin wp-config.php">Add your FTP/SSH credentials to the WordPress config file</a>.</li>', 'child-theme-configurator' );
        if ( isset( $_SERVER[ 'SERVER_SOFTWARE' ] ) && preg_match( '%iis%i',$_SERVER[ 'SERVER_SOFTWARE' ] ) )
            _e( '<li><a target="_blank" href="http://technet.microsoft.com/en-us/library/cc771170" title="Setting Application Pool Identity">Assign WordPress to an application pool that has write permissions</a> (Windows IIS systems).</li>', 'child-theme-configurator' );
        _e( '<li><a target="_blank" href="http://codex.wordpress.org/Changing_File_Permissions" title="Changing File Permissions">Set write permissions on the server manually</a> (not recommended).</li>', 'child-theme-configurator' );
        if ( 'WIN' != substr( strtoupper( PHP_OS ), 0, 3 ) ):
            _e( '<li>Run PHP under Apache with suEXEC (contact your web host).</li>', 'child-theme-configurator' );
        endif; ?>
    </ol>
    </p>
  </div>
  <?php
                break;
                
                
            case 'owner':
            
                $ctcpage = apply_filters( 'chld_thm_cfg_admin_page', CHLD_THM_CFG_MENU ); // FIXME? ?>
  <p>
    <?php _e( 'This Child Theme has incorrect ownership permissions. Child Theme Configurator will attempt to correct this when you click the button below.', 'child-theme-configurator' ) ?>
  </p>
  <p>
  <form action="" method="post">
    <?php 
                wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
    <input name="ctc_reset_permission" class="button" type="submit" value="<?php _e( 'Correct Child Theme Permissions', 'child-theme-configurator' ); ?>"/>
  </form>
  </p>
  <?php
                break;
                
                
            case 'enqueue': ?>
  <p>
    <?php _e( 'Child Theme Configurator needs to update its internal data. Please set your preferences below and click "Generate Child Theme Files" to update your configuration.', 'child-theme-configurator' ) ?>
  </p>
  <?php
                break;
                
                
            case 'max_styles':
            
                echo sprintf( __( '<strong>However, some styles could not be parsed due to memory limits.</strong> Try deselecting "Additional Stylesheets" below and click "Generate/Rebuild Child Theme Files". %sWhy am I seeing this?%s', 'child-theme-configurator' ), 
                '<a target="_blank" href="' . LILAEAMEDIA_URL . '/child-theme-configurator#php_memory">',
                '</a>' );
                break;
                
                
            case 'config': ?>
  <p>
    <?php _e( 'Child Theme Configurator did not detect any configuration data because a previously configured Child Theme has been removed. Please follow the steps for "CONFIGURE an existing Child Theme" under the "Parent/Child" Tab.', 'child-theme-configurator' ) ?>
  </p>
  <?php
                break;
                
                
            case 'changed': ?>
  <p>
    <?php _e( 'Your stylesheet has changed since the last time you used the Configurator. Please follow the steps for "CONFIGURE an existing Child Theme" under the "Parent/Child" Tab or you will lose these changes.', 'child-theme-configurator' ) ?>
  </p>
  <?php
                break;
                
                
            case 'upgrade': 
                $child = $this->css()->get_prop( 'child' );
            ?>
  <?php if ( $child ): ?>
  <div class="clearfix">
    <?php endif; ?>
    <h3>
      <?php _e( 'Thank you for installing Child Theme Configurator.', 'child-theme-configurator' ); ?>
    </h3>
    <p class="howto">
      <?php _e( 'A lot of time and testing has gone into this release but there may be edge cases. If you have any questions, please', 'child-theme-configurator' ); ?>
      <a href="<?php echo LILAEAMEDIA_URL; ?>/contact" target="_blank">
      <?php _e( 'Contact Us.', 'child-theme-configurator' ); ?>
      </a></p>
    <p class="howto">
      <?php _e( 'For more information, please open the Help tab at the top right or ', 'child-theme-configurator' ) ?>
      <a href="http://www.childthemeplugin.com/tutorial-videos/" target="_blank">
      <?php _e( 'click here to view the latest videos.', 'child-theme-configurator' ); ?>
      </a></p>
    <?php if ( $child ): ?>
    <p>
      <?php _e( 'It is a good idea to save a Zip Archive of your Child Theme before using CTC for the first time. Click the "save backup" link ( see Step 2, below ) to export your themes.', 'child-theme-configurator' ); ?>
    </p>
  </div>
  <?php endif;
        break;
 ?>
  <?php endswitch; ?>
</div>
