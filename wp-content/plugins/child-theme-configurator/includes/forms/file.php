<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// File Input Cell
?>
<label class="ctc-input-cell smaller<?php echo 'child' == $template && !$this->ctc()->fs && is_writable( $themeroot . $file ) ? ' writable' : ''; ?>">
  <input class="ctc_checkbox" id="ctc_file_<?php echo $template . '_' . ++$counter; ?>" 
                    name="ctc_file_<?php echo $template; ?>[]" type="checkbox" 
                    value="<?php echo $templatefile; ?>" />
  <?php echo 'child' == $template ? apply_filters( 'chld_thm_cfg_editor_link', $templatefile, $editorlink ) : $templatefile; ?></label> 
  
  