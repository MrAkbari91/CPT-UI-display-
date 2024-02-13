<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>

<select class="ctc-select" id="ctc_theme_<?php echo $template; ?>" name="ctc_theme_<?php echo $template; ?>" 
            style="visibility:hidden" <?php echo $this->ctc()->is_theme() ? '' : ' disabled '; ?> autocomplete="off" >
  <?php
            uasort( $this->ctc()->themes[ $template ], array( $this, 'cmp_theme' ) );
            foreach ( $this->ctc()->themes[ $template ] as $slug => $theme )
                echo '<option value="' . $slug . '"' . ( $slug == $selected ? ' selected' : '' ) . '>' 
                    . esc_attr( $theme[ 'Name' ] ) . '</option>' . LF; 
        ?>
</select>
<div style="display:none">
  <?php 
        foreach ( $this->ctc()->themes[ $template ] as $slug => $theme )
            include ( CHLD_THM_CFG_DIR . '/includes/forms/themepreview.php' ); ?>
</div>
