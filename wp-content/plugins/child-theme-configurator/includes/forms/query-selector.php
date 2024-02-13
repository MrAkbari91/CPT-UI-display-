<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Query/Selector Panel
        $ctcpage = apply_filters( 'chld_thm_cfg_admin_page', CHLD_THM_CFG_MENU );
?>

<div id="query_selector_options_panel" 
        class="ctc-option-panel <?php $this->maybe_disable(); echo 'query_selector_options' == $active_tab ? ' ctc-option-panel-active' : ''; ?>">
<p class="howto"><?php _e( 'To find and edit specific selectors within @media query blocks, first choose the query, then the selector. Use the "base" query to edit all other selectors.', 'child-theme-configurator' ); ?></p>
  <form id="ctc_query_selector_form" method="post" action="?page=<?php echo $ctcpage; ?>">
    <?php wp_nonce_field( apply_filters( 'chld_thm_cfg_action', 'ctc_update' ) ); ?>
    <div class="ctc-input-row clearfix" id="input_row_query">
      <div class="ctc-input-cell"> <strong>
        <?php _e( '@media Query', 'child-theme-configurator' ); ?>
        </strong> <?php _e( '( or "base" )', 'child-theme-configurator' ); ?> <a href="#" class="ctc-rewrite-toggle rewrite-query"></a></div>
      <div class="ctc-input-cell" id="ctc_sel_ovrd_query_selected">&nbsp;</div>
      <div class="ctc-input-cell">
        <div class="ui-widget">
          <input id="ctc_sel_ovrd_query" />
        </div>
      </div>
    </div>
    <div class="ctc-input-row clearfix" id="input_row_selector">
      <div class="ctc-input-cell"> <strong>
        <?php _e( 'Selector', 'child-theme-configurator' ); ?>
        </strong> <a href="#" class="ctc-rewrite-toggle rewrite-selector"></a></div>
      <div class="ctc-input-cell" id="ctc_sel_ovrd_selector_selected">&nbsp;</div>
      <div class="ctc-input-cell">
        <div class="ui-widget">
          <input id="ctc_sel_ovrd_selector" />
          <div id="ctc_status_qsid" style="float:right"></div>
        </div>
      </div>
    </div>
    <div class="ctc-selector-row clearfix" id="ctc_sel_ovrd_rule_inputs_container" style="display:none">
      <div class="ctc-input-row clearfix">
        <div class="ctc-input-cell"><strong>
          <?php _e( 'Sample', 'child-theme-configurator' ); ?>
          </strong></div>
        <div class="ctc-input-cell clearfix" style="max-height:150px;overflow:hidden">
          <div class="ctc-swatch" id="ctc_child_all_0_swatch"><?php echo $this->swatch_txt; ?></div>
        </div>
        <div id="ctc_status_sel_val"></div>
        <div class="ctc-input-cell ctc-button-cell" id="ctc_save_query_selector_cell">
          <input type="submit" class="button button-primary ctc-save-input" id="ctc_save_query_selector" 
            name="ctc_save_query_selector" value="<?php _e( 'Save Child Values', 'child-theme-configurator' ); ?>" disabled />
          <a class="ctc-delete-input" id="ctc_delete_query_selector" href="#"><?php _e( 'Delete Child Values', 'child-theme-configurator' ); ?></a>
          <input type="hidden" id="ctc_sel_ovrd_qsid" 
            name="ctc_sel_ovrd_qsid" value="" />
        </div>
      </div>
      <div class="ctc-input-row clearfix" id="ctc_sel_ovrd_rule_header" style="display:none">
        <div class="ctc-input-cell"> <strong>
          <?php _e( 'Property', 'child-theme-configurator' ); ?>
          </strong> </div>
        <div class="ctc-input-cell"> <strong>
          <?php _e( 'Baseline Value', 'child-theme-configurator' ); ?>
          </strong> </div>
        <div class="ctc-input-cell"> <strong>
          <?php _e( 'Child Value', 'child-theme-configurator' ); ?>
          </strong> </div>
      </div>
      <div id="ctc_sel_ovrd_rule_inputs" style="display:none"> </div>
      <div class="ctc-input-row clearfix" id="ctc_sel_ovrd_new_rule" style="display:none">
        <div class="ctc-input-cell"> <strong>
          <?php _e( 'New Property', 'child-theme-configurator' ); ?>
          </strong> </div>
        <div class="ctc-input-cell">
          <div class="ui-widget">
            <input id="ctc_new_rule_menu" />
          </div>
        </div>
      </div>
      <div class="ctc-input-row clearfix" id="input_row_load_order">
        <div class="ctc-input-cell"> <strong>
          <?php _e( 'Order', 'child-theme-configurator' ); ?>
          </strong> </div>
        <div class="ctc-input-cell" id="ctc_child_load_order_container">&nbsp;</div>
      </div>
    </div></form><form id="ctc_raw_css_form" method="post" action="?page=<?php echo $ctcpage; ?>">
    <div class="ctc-selector-row clearfix" id="ctc_new_selector_row">
      <div class="ctc-input-cell">
        <div class="ctc-textarea-button-cell" id="ctc_save_query_selector_cell">
          <input type="button" class="button" id="ctc_copy_selector" 
            name="ctc_copy_selector" value="<?php _e( 'Copy Selector', 'child-theme-configurator' ); ?>"  /> &nbsp;
          <input type="button" class="button button-primary ctc-save-input" id="ctc_save_new_selectors" 
            name="ctc_save_new_selectors" value="<?php _e( 'Save', 'child-theme-configurator' ); ?>"  disabled />
        </div>
        <strong>
        <?php _e( 'Raw CSS', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto"><?php _e( 'Use to enter shorthand CSS or new @media queries and selectors.', 'child-theme-configurator' );?></p><p class="howto"><?php _e( 'Values entered here are merged into existing child styles or added to the child stylesheet if they do not exist in the parent.', 'child-theme-configurator' ); ?></p>
      </div>
      <div class="ctc-input-cell-wide">
        <textarea id="ctc_new_selectors" name="ctc_new_selectors" wrap="off"></textarea>
      </div>
    </div>
  </form>
</div>
