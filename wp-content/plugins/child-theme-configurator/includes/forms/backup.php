<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// Backup Input Cell
?>
<label class="ctc-input-cell smaller<?php echo 'child' == $template && !$this->ctc()->fs && is_writable( $themeroot . $backup ) ? ' writable' : ''; ?>">
      <input class="ctc_checkbox" id="ctc_file_<?php echo $template . '_' . ++$counter; ?>" 
                    name="ctc_file_<?php echo $template; ?>[]" type="checkbox" 
                    value="<?php echo $templatefile; ?>" /><?php echo __( 'Backup', 'child-theme-configurator' ) . ' ' . $label; ?></label>