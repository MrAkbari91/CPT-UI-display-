<?php
if ( !defined( 'ABSPATH' ) ) exit;
// Additional stylesheets
$stylesheets = $this->get_files( $this->get_current_parent(), 'stylesheet' );
if ( count( $stylesheets ) ):?>
<div class="ctc-input-cell ctc-section-toggle" id="ctc_additional_css_files"> <strong>
  <?php _e( 'Parse additional stylesheets:', 'child-theme-configurator' ); ?>
  </strong> </div>
<div class="ctc-input-cell-wide ctc-section-toggle-content" id="ctc_additional_css_files_content">
  <p style="margin-top:0">
    <?php _e( 'Stylesheets that are currently being loaded by the parent theme are automatically selected below (except for Bootstrap stylesheets which add a large amount data to the configuration). To further reduce overhead, select only the additional stylesheets you wish to customize.', 'child-theme-configurator' ); ?>
  </p>
  <ul>
<?php foreach ( $stylesheets as $stylesheet ): ?>
    <li>
      <label>
        <input class="ctc_checkbox" name="ctc_additional_css[]" type="checkbox" 
                value="<?php echo $stylesheet; ?>" />
        <?php echo esc_attr( $stylesheet ); ?></label>
    </li>
<?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
