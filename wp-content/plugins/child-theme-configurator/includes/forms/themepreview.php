<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// Theme Preview
// Renders localized version of theme preview that is merged into 
// jQuery selectmenu object for parent and child theme options
?>

<div id="ctc_theme_option_<?php echo $this->ctc()->sanitize_slug( $slug ); ?>" class="clearfix ctc-theme-option">
  <div class="ctc-theme-option-left"><img src="<?php echo $theme[ 'screenshot' ]; ?>" class="ctc-theme-option-image"/></div>
  <div class="ctc-theme-option-right">
    <h3 class="theme-name"><?php echo $theme[ 'Name' ]; ?></h3>
    <?php _e( 'Version: ', 'child-theme-configurator' ); echo esc_attr( $theme[ 'Version' ] );?>
    <br/>
    <?php _e( 'By: ', 'child-theme-configurator' ); echo esc_attr( $theme[ 'Author' ] );?>
    <br/><?php if ( !is_multisite() || $theme[ 'allowed' ] ): ?>
    <a href="<?php echo admin_url( '/customize.php?theme=' . $slug );?>" title="<?php _e( 'Preview', 'child-theme-configurator' ); 
    if ( is_multisite() ) _e(' in default Site', 'child-theme-configurator'); ?>" class="ctc-live-preview" target="_blank">
    <?php _e( 'Live Preview', 'child-theme-configurator' ); ?>
    </a><?php else: ?>
    <a href="<?php echo network_admin_url( '/themes.php?theme=' . $slug );?>" title="<?php _e( 'Go to Themes', 'child-theme-configurator' ); ?>" class="ctc-live-preview">
    <?php _e( 'Not Network Enabled', 'child-theme-configurator' );?>
    </a><?php endif; ?></div>
</div>
