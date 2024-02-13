<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
        if ( $childname = $this->css()->get_prop( 'child_name' ) ): ?>
<div class="ctc-input-cell">
  <h3><?php echo __( 'Currently loaded', 'child-theme-configurator' ). ': ' . $childname; ?></h3>
</div>
<?php   endif;
