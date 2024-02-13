<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

        if ( 'child' == $template && !$this->ctc()->fs ): ?>

<input class="ctc_submit button button-primary" id="ctc_templates_writable_submit" 
              name="ctc_templates_writable_submit" type="submit" 
              value="<?php _e( 'Make Selected Writable', 'child-theme-configurator' ); ?>" />
<?php endif; ?>
<input class="ctc_submit button button-primary" id="ctc_<?php echo $template; ?>_templates_submit" 
              name="ctc_<?php echo $template; ?>_templates_submit" type="submit" 
              value="<?php echo ( 'parnt' == $template ?  __( 'Copy Selected to Child Theme', 'child-theme-configurator' ) : __( 'Delete Selected', 'child-theme-configurator' ) ); ?>" />
