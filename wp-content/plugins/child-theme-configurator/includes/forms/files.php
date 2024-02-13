<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Files Panel
?>

<div id="file_options_panel" 
        class="ctc-option-panel <?php $this->maybe_disable(); echo 'file_options' == $active_tab ? ' ctc-option-panel-active' : ''; ?>">
  <?php $this->render_file_form( 'parnt' ); ?>
  <?php $this->render_file_form( 'child' ); ?>
  <?php $this->render_image_form(); ?>
  <div class="ctc-input-row clearfix" id="input_row_theme_image">
    <form id="ctc_theme_image_form" method="post" action="?page=<?php echo CHLD_THM_CFG_MENU; ?>" enctype="multipart/form-data">
      <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
      <div class="ctc-input-cell"> <strong>
        <?php _e( 'Upload New Child Theme Image', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto">
          <?php _e( 'Theme images reside under the <code>images</code> directory in your child theme and are meant for stylesheet use only. Use the Media Library for content images.', 'child-theme-configurator' ); ?>
        </p>
      </div>
      <div class="ctc-input-cell-wide">
        <input type="file" id="ctc_theme_image" name="ctc_theme_image" value="" />
        <input class="ctc_submit button button-primary" id="ctc_theme_image_submit" 
                name="ctc_theme_image_submit"  type="submit" 
                value="<?php _e( 'Upload', 'child-theme-configurator' ); ?>" />
      </div>
    </form>
  </div>
  <?php if ( $screenshot = $this->get_theme_screenshot() ): ?>
  <div class="ctc-input-row clearfix" id="input_row_screenshot_view">
    <div class="ctc-input-cell"> <strong>
      <?php _e( 'Child Theme Screenshot', 'child-theme-configurator' ); ?>
      </strong> </div>
    <div class="ctc-input-cell-wide"> <a href="<?php echo $screenshot; ?>" class="thickbox"><img src="<?php echo $screenshot; ?>" height="150" width="200" style="max-height:150px;max-width:200px;width:auto;height:auto" /></a> </div>
  </div>
  <?php endif; ?>
  <div class="ctc-input-row clearfix" id="input_row_screenshot">
    <form id="ctc_screenshot_form" method="post" action="?page=<?php echo CHLD_THM_CFG_MENU; ?>" enctype="multipart/form-data">
    <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
      <div class="ctc-input-cell"> <strong>
        <?php _e( 'Upload New Screenshot', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto">
          <?php _e( 'The theme screenshot should be a 4:3 ratio (e.g., 880px x 660px) JPG, PNG or GIF. It will be renamed <code>screenshot</code>.', 'child-theme-configurator' ); ?>
        </p>
      </div>
      <div class="ctc-input-cell-wide">
        <input type="file" id="ctc_theme_screenshot" name="ctc_theme_screenshot" value="" />
        <input class="ctc_submit button button-primary" id="ctc_theme_screenshot_submit" 
                name="ctc_theme_screenshot_submit"  type="submit" 
                value="<?php _e( 'Upload', 'child-theme-configurator' ); ?>" />
      </div>
    </form>
  </div>
  <div class="ctc-input-row clearfix" id="input_row_screenshot">
      <div class="ctc-input-cell"> <strong>
        <?php _e( 'Export Child Theme as Zip Archive', 'child-theme-configurator' ); ?>
        </strong> <p class="howto"><?php _e( 'Click "Export Zip" to save a backup of the currently loaded child theme. You can export any of your themes from the Parent/Child tab.', 'child-theme-configurator' ); ?></p></div>
      <div class="ctc-input-cell-wide"><?php
        include ( CHLD_THM_CFG_DIR . '/includes/forms/zipform.php' ); 
      ?></div>
  </div>
  <?php if ( 'direct' != $this->ctc()->fs_method ): ?>
  <div class="ctc-input-row clearfix" id="input_row_permissions">
    <form id="ctc_permission_form" method="post" action="?page=<?php echo CHLD_THM_CFG_MENU; ?>">
    <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
      <div class="ctc-input-cell"> <strong>
        <?php _e( 'Secure Child Theme', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto">
          <?php _e( 'Attempt to reset child theme permissions to user ownership and read-only access.', 'child-theme-configurator' ); ?>
        </p>
      </div>
      <div class="ctc-input-cell-wide">
        <input class="ctc_submit button button-primary" id="ctc_reset_permission" 
                name="ctc_reset_permission"  type="submit" 
                value="<?php _e( 'Make read-only', 'child-theme-configurator' ); ?>" />
      </div>
    </form>
  </div>
  <?php endif; ?>
  <div class="ctc-input-row clearfix" id="input_update_key">
<?php  // uses output buffer to modify and extend files tab actions
  ob_start();
  do_action( 'chld_thm_cfg_files_tab' ); 
  $files_tab_options = apply_filters( 'chld_thm_cfg_files_tab_filter', ob_get_contents() );
  ob_end_clean();
  echo $files_tab_options;
  ?>
  </div>
</div>
