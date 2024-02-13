<?php 
if ( !defined( 'ABSPATH' ) ) exit;
?>
<form id="ctc_export_theme_form" method="post" action="?page=<?php echo CHLD_THM_CFG_MENU; ?>">
  <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
    <input class="ctc_submit button button-primary" name="ctc_export_child_zip"  type="submit" value="<?php _e( 'Export Child Theme', 'child-theme-configurator' ); ?>" />
    <input type="hidden" id="ctc_export_theme" name="ctc_export_theme" value="" />
</form>

