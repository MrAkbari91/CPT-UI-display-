<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// Files Section
?>
<div class="ctc-input-row clearfix" id="input_row_<?php echo $template; ?>_templates">
  <form id="ctc_<?php echo $template; ?>_templates_form" method="post" action="?page=<?php echo $ctcpage; ?>&amp;tab=file_options">
    <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
    <div class="ctc-input-cell"> <strong>
      <?php echo 'parnt' == $template ? __( 'Parent Templates', 'child-theme-configurator' ) : __( 'Child Theme Files', 'child-theme-configurator' ); ?>
      </strong>
<?php 
if ( 'parnt' == $template ): ?>
      <p class="howto">
    <?php _e( 'Copy PHP templates from the parent theme by selecting them here. The Configurator defines a template as a Theme PHP file having no PHP functions or classes. Non-template files cannot be inherited by a child theme.', 'child-theme-configurator' ); ?>
      </p>  
      <p class="howto"><strong>
        <?php _e( 'CAUTION: If your child theme is active, the child theme version of the file will be used instead of the parent immediately after it is copied.', 'child-theme-configurator' );?>
        </strong></p>
      <p class="howto"> <?php printf( __( 'The %s file is generated separately and cannot be copied here.', 'child-theme-configurator' ),
        sprintf( $editorlink, 'functions.php', 'Click to edit functions.php', '<code>functions.php</code>' )
        );
else: ?>
      <p class="howto">
      <?php printf( $editorlink, 'functions.php', __( 'Click to edit functions.php', 'child-theme-configurator' ), __( 'Click to edit files using the Theme Editor', 'child-theme-configurator' ) ); ?>
      </p>
      <p class="howto">
<?php 
    echo ( $this->ctc()->fs ?
        __( 'Delete child theme templates by selecting them here.', 'child-theme-configurator' ) :
        __( 'Delete child theme templates or make them writable by selecting them here. Writable files are displayed in <span style="color:red">red</span>.', 'child-theme-configurator' ) 
    ); ?>
      </p>
<?php 
    endif; 
?>
    </div>
    <div class="ctc-input-cell-wide"> <?php echo $inputs; ?></div>
    <div class="ctc-input-cell"> <strong>&nbsp;</strong> </div>
    <div class="ctc-input-cell-wide" style="margin-top:10px;margin-bottom:10px">
    <?php do_action( 'chld_thm_cfg_file_form_buttons', $template ); ?>
    </div>
  </form>
</div>