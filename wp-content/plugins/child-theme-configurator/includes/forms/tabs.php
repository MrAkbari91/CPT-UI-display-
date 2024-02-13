<?php  
if ( !defined( 'ABSPATH' ) ) exit;
// Tabs Bar

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'parent_child_options'; 
?>

<h2 class="nav-tab-wrapper clearfix">
<a id="parent_child_options" href="" 
                    class="nav-tab<?php echo 'parent_child_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Parent/ Child', 'child-theme-configurator' ); ?>
</a><a id="query_selector_options" href="" 
                    class="nav-tab <?php $this->maybe_disable(); echo 'query_selector_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Query/ Selector', 'child-theme-configurator' ); ?>
</a><a id="rule_value_options" href="" 
                    class="nav-tab <?php $this->maybe_disable(); echo 'rule_value_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Property/ Value', 'child-theme-configurator' ); ?>
</a><?php
    if ( $this->ctc()->is_theme() ):  
    ?><a id="import_options" href="" 
                    class="nav-tab <?php $this->maybe_disable(); echo 'import_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Web Fonts & CSS', 'child-theme-configurator' ); ?>
</a><?php 
    endif; ?><a id="view_parnt_options" href="" 
                    class="nav-tab <?php $this->maybe_disable(); echo 'view_parnt_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Baseline Styles', 'child-theme-configurator' ); ?>
</a><a id="view_child_options" href="" 
                    class="nav-tab <?php $this->maybe_disable(); echo 'view_child_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Child Styles', 'child-theme-configurator' ); ?>
</a><?php 
    if ( $this->ctc()->is_theme() ):  
    ?><a id="file_options" href="" class="nav-tab <?php $this->maybe_disable(); echo 'file_options' == $active_tab ? ' nav-tab-active' : ''; ?>">
<?php _e( 'Files', 'child-theme-configurator' ); ?>
</a><?php 
    endif; 
    if ( $this->enqueue_is_set() || $this->supports_disable() ):
        do_action( 'chld_thm_cfg_tabs', $active_tab );
    endif;
?>
  <i id="ctc_status_preview"></i>
</h2>