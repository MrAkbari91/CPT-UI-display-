<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Images Section
$ctcpage = apply_filters( 'chld_thm_cfg_admin_page', CHLD_THM_CFG_MENU );
?>
<div class="ctc-input-row clearfix" id="input_row_images">
  <form id="ctc_image_form" method="post" action="?page=<?php echo $ctcpage; ?>&amp;tab=file_options">
    <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
    <div class="ctc-input-cell"> <strong>
      <?php _e( 'Child Theme Images', 'child-theme-configurator' ); ?>
      </strong>
      <p class="howto">
        <?php _e( 'Delete child theme images by selecting them here.', 'child-theme-configurator' );?>
      </p>
    </div>
    <div class="ctc-input-cell-wide"> <?php echo $inputs; ?> </div>
    <div class="ctc-input-cell"> <strong>&nbsp;</strong> </div>
    <div class="ctc-input-cell-wide" style="margin-top:10px;margin-bottom:10px">
      <input class="ctc_submit button button-primary" id="ctc_image_submit" 
                name="ctc_image_submit"  type="submit" 
                value="<?php _e( 'Delete Selected', 'child-theme-configurator' ); ?>" disabled />
    </div>
  </form>
</div>
