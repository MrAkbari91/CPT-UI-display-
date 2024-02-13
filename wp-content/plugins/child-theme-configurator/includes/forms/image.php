<?php 
if ( !defined( 'ABSPATH' ) ) exit;
// Image Input Cell
?>
<div class="ctc-input-cell" style="height:100px">
  <label class="smaller">
    <input class="ctc_checkbox" id="ctc_img_<?php echo ++$counter; ?>" 
                    name="ctc_img[]" type="checkbox" 
                    value="<?php echo $templatefile; ?>" />
    <?php echo $templatefile; if ( is_array( $imagesize ) ): ?> ( <?php echo $imagesize[ 0 ]; ?> x <?php echo $imagesize[ 1 ]; ?> px ) <?php endif; ?></label>
  <br/>
  <a href="<?php echo $themeuri . $file; ?>" class="thickbox"><img src="<?php echo $themeuri . $file . '?' . time(); ?>" height="72" width="72" style="max-height:72px;max-width:100%;width:auto;height:auto" /></a></div>
