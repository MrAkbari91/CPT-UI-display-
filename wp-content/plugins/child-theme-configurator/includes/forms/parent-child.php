<?php
if ( !defined( 'ABSPATH' ) )exit;
// Parent/Child Panel
?>

<div id="parent_child_options_panel" class="ctc-option-panel<?php echo 'parent_child_options' == $active_tab ? ' ctc-option-panel-active' : ''; ?>">
    <form id="ctc_load_form" method="post" action="">

        <?php if ( $this->ctc()->is_theme() ): ?>

        <?php   // theme inputs 
    wp_nonce_field( 'ctc_update' ); 
    do_action( 'chld_thm_cfg_controls' );
    
?><input type="hidden" name="ctc_analysis" value=""/>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child">
            <div class="ctc-input-cell"><span class="ctc-step ctc-step-number">1</span>
                <strong class="shift">
                    <?php _e( 'Select an action:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide">
                <label>
        <input class="ctc-radio ctc-themeonly" id="ctc_child_type_new" name="ctc_child_type" type="radio" value="new" 
            <?php echo ( 'new' == $this->ctc()->childtype ? 'checked' : '' ); ?> />
        <strong>
        <?php _e( 'CREATE a new Child Theme', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto indent sep">
          <?php _e( 'Install a new customizable child theme using an installed theme as a parent.', 'child-theme-configurator' ); ?>
        </p>
        </label>
            

                <!-- /div -->
                <?php if ( count( $this->ctc()->themes[ 'child' ] ) ): ?>
                <!-- div class="ctc-input-cell ctc-clear">&nbsp;</div>
      <div class="ctc-input-cell-wide" -->
                <label>
        <input class="ctc-radio ctc-themeonly" id="ctc_child_type_existing" name="ctc_child_type"  type="radio" value="existing" 
            <?php echo ( 'new' != $this->ctc()->childtype ? 'checked' : '' ); ?> />
        <strong>
        <?php _e( 'CONFIGURE an existing Child Theme', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto indent sep">
          <?php _e( 'Set up a previously installed child theme for use with the Configurator or to modify current settings.', 'child-theme-configurator' ); ?>
        </p>
        </label>
            

                <!-- /div>
      <div class="ctc-input-cell ctc-clear">&nbsp;</div>
      <div class="ctc-input-cell-wide" -->
                <label>
        <input class="ctc-radio ctc-themeonly" id="ctc_child_type_duplicate" name="ctc_child_type"  type="radio" value="duplicate" />
        <strong>
        <?php _e( 'DUPLICATE an existing Child Theme', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto indent sep">
          <?php _e( 'Make a complete copy of an existing Child Theme in a new directory, including any menus, widgets and other Customizer settings. The option to copy the Parent Theme settings (step 8, below) is disabled with this action.', 'child-theme-configurator' ); ?>
        </p>
        </label>
            

                <!-- /div>
      <div class="ctc-input-cell ctc-clear">&nbsp;</div>
      <div class="ctc-input-cell-wide" -->
                <label>
        <input class="ctc-radio ctc-themeonly" id="ctc_child_type_reset" name="ctc_child_type"  type="radio" value="reset" />
        <strong>
        <?php _e( 'RESET an existing Child Theme (this will destroy any work you have done in the Configurator)', 'child-theme-configurator' ); ?>
        </strong>
        <p class="howto indent">
          <?php _e( 'Revert the Child theme stylesheet and functions files to their state before the initial configuration or last reset. Additional child theme files will not be removed, but you can delete them under the Files tab.', 'child-theme-configurator' ); ?>
        </p>
        </label>
            

            </div>
            <?php endif; ?>
        </div>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_new_theme_option" style="display:none">
            <div class="ctc-input-cell" style="clear:both"><span class="ctc-step ctc-step-number">2</span>
                <strong>
                    <?php _e( 'Select a Parent Theme:', 'child-theme-configurator' ); ?>
                </strong>
                <p class="howto indent">
                    <a href="#" class="ctc-backup-theme">
                        <?php _e( 'Click here to save a backup of the selected theme.', 'child-theme-configurator' ); ?>
                    </a>
                </p>
            </div>
            <div class="ctc-input-cell">
                <?php $this->render_theme_menu( 'parnt', $this->ctc()->get_current_parent() ); ?>
                <input type="button" class="button button-primary ctc-analyze-theme" value="<?php _e( 'Analyze', 'child-theme-configurator' ); ?>"/>
            </div>
            <div class="ctc-input-cell"><span class="ctc-analyze-howto"><span class="ctc-step ctc-step-number">3</span>
                <strong>
                    <?php _e( 'Analyze Parent Theme', 'child-theme-configurator' ); ?>
                </strong>
                <p class="howto indent">
                    <?php _e( 'Click "Analyze" to determine stylesheet dependencies and other potential issues.', 'child-theme-configurator' ); ?>
                </p>
                </span>
            </div>
            <div class="ctc-input-cell ctc-clear">&nbsp;</div>
            <div class="ctc-input-cell-wide ctc-analysis" id="parnt_analysis_notice">&nbsp;</div>
        </div>
        <?php if ( count( $this->ctc()->themes[ 'child' ] ) ): ?>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_existing_theme_option" style="display:none">
            <div class="ctc-input-cell"><span class="ctc-step ctc-step-number">2</span>
                <strong>
                    <?php _e( 'Select a Child Theme:', 'child-theme-configurator' ); ?>
                </strong>
                <p class="howto indent">
                    <a href="#" class="ctc-backup-theme">
                        <?php _e( 'Click here to save a backup of the selected theme.', 'child-theme-configurator' ); ?>
                    </a>
                </p>
            </div>
            <div class="ctc-input-cell">
                <?php $this->render_theme_menu( 'child', $this->ctc()->get_current_child() ); ?>
                <input type="button" class="button button-primary ctc-analyze-theme" value="<?php _e( 'Analyze', 'child-theme-configurator' ); ?>"/>
            </div>
            <div class="ctc-input-cell"><span class="ctc-analyze-howto"><span class="ctc-step ctc-step-number">3</span>
                <strong>
                    <?php _e( 'Analyze Child Theme', 'child-theme-configurator' ); ?>
                </strong>
                <p class="howto indent">
                    <?php _e( 'Click "Analyze" to determine stylesheet dependencies and other potential issues.', 'child-theme-configurator' ); ?>
                </p>
                </span>
            </div>
            <div class="ctc-input-cell ctc-clear">&nbsp;</div>
            <div class="ctc-input-cell-wide ctc-analysis" id="child_analysis_notice">&nbsp;</div>
        </div>
        <?php 
    endif; ?>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_new_theme_slug" style="display:none">
            <div class="ctc-input-cell" style="clear:both"><span class="ctc-step ctc-step-number">4</span>
                <strong class="shift">
                    <?php _e( 'Name the new theme directory:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell">
                <input class="ctc_text ctc-themeonly" id="ctc_child_template" name="ctc_child_template" type="text" placeholder="<?php _e( 'Directory Name', 'child-theme-configurator' ); ?>" autocomplete="off"  />
            </div>
            <div class="ctc-input-cell"><span class="howto">
        <strong>
          <?php _e( 'NOTE:', 'child-theme-configurator' ); ?>
        </strong>
        <?php _e( 'This is NOT the name of the Child Theme. You can customize the name, description, etc. in step 7, below.', 'child-theme-configurator' ); ?></span>
            
            </div>
        </div>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_theme_slug" style="display:none">
            <div class="ctc-input-cell" style="clear:both"><span class="ctc-step ctc-step-number">4</span>
                <strong class="shift">
                    <?php _e( 'Verify Child Theme directory:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell"><code id="theme_slug_container">
      </code>
            

            </div>
            <div class="ctc-input-cell">
                <span class="howto">
                    <?php _e( 'For verification only (you cannot modify the directory of an existing Child Theme).', 'child-theme-configurator' ); ?>
                </span>
            </div>
        </div>
        <?php
        $handling = $this->ctc()->get( 'handling' );
        $ignoreparnt = $this->ctc()->get( 'ignoreparnt' );
        $enqueue = $this->ctc()->get( 'enqueue' );
        $this->ctc()->debug( 'handling: ' . $handling . ' ignore: ' . $ignoreparnt . ' enqueue: ' . $enqueue, 'parent-child.php' );
        ?>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_stylesheet_handling_container" style="display:none">
            <div class="ctc-input-cell ctc-clear" id="input_row_stylesheet_handling"><span class="ctc-step ctc-step-number">5</span>
                <strong class="shift">
                    <?php _e( 'Select where to save new styles:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide sep">
                <div id="child_handling_notice"></div>
                <label>
          <input class="ctc_radio ctc-themeonly" id="ctc_handling_primary" name="ctc_handling" type="radio" 
                value="primary" <?php checked( $handling, 'primary' ); ?> autocomplete="off" />
          <strong>
          <?php _e( "Primary Stylesheet (style.css)", 'child-theme-configurator' ); ?>
          </strong>
          <p class="howto indent sep">
            <?php _e( 'Save new custom styles directly to the Child Theme primary stylesheet, replacing the existing values. The primary stylesheet will load in the order set by the theme.', 'child-theme-configurator' ); ?>
          </p>
          </label>
            

            </div>
            <div class="ctc-input-cell ctc-clear">&nbsp;</div>
            <div class="ctc-input-cell-wide">
                <label>
          <input class="ctc_radio ctc-themeonly" id="ctc_handling_separate" name="ctc_handling" type="radio" 
                value="separate" <?php checked( $handling, 'separate' ); ?>  autocomplete="off" />
          <strong>
          <?php _e( 'Separate Stylesheet', 'child-theme-configurator' ); ?>
          </strong>
          <p class="howto indent">
            <?php _e( 'Save new custom styles to a separate stylesheet and combine any existing child theme styles with the parent to form baseline. Select this option if you want to preserve the existing child theme styles instead of overwriting them. This option also allows you to customize stylesheets that load after the primary stylesheet.', 'child-theme-configurator' ); ?>
          </p>
          </label>
            

            </div>
        </div>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_parent_handling_container" style="display:none">
            <div class="ctc-input-cell ctc-clear" id="input_row_parent_handling" title="<?php _e( 'Click to expand', 'child-theme-configurator' ); ?>"><span class="ctc-step ctc-step-number">6</span>
                <strong class="shift">
                    <?php _e( 'Select Parent Theme stylesheet handling:', 'child-theme-configurator' ); ?>
                </strong>
                <p class="howto"></p>
            </div>
            <div class="ctc-input-cell-wide sep">
                <div id="parent_handling_notice"></div>
                <?php // deprecated enqueue values
        if ( 'both' == $enqueue || 'child' == $enqueue ): 
            $enqueue = 'enqueue'; 
        endif; ?>
                <label>
          <input class="ctc_checkbox ctc-themeonly" id="ctc_enqueue_enqueue" name="ctc_enqueue" type="radio" 
                value="enqueue" <?php checked( $enqueue, 'enqueue' ); ?> autocomplete="off" />
          <strong>
          <?php _e( 'Use the WordPress style queue.', 'child-theme-configurator' ); ?>
          </strong>
          </label>
            

                <p class="howto indent sep">
                    <?php _e( "Let the Configurator determine the appropriate actions and dependencies and update the functions file automatically.", 'child-theme-configurator' ); ?>
                </p>
                <label>
          <input class="ctc_checkbox ctc-themeonly" id="ctc_enqueue_import" name="ctc_enqueue" type="radio" 
                value="import" <?php checked( $enqueue, 'import' ); ?> autocomplete="off" />
          <strong>
          <?php _e( 'Use <code>@import</code> in the child theme stylesheet.', 'child-theme-configurator' ); ?>
          </strong>
          </label>
            

                <p class="howto indent sep">
                    <?php _e( "Only use this option if the parent stylesheet cannot be loaded using the WordPress style queue. Using <code>@import</code> is not recommended.", 'child-theme-configurator' ); ?>
                </p>
                <label>
          <input class="ctc_checkbox ctc-themeonly" id="ctc_enqueue_none" name="ctc_enqueue" type="radio" 
                value="none" <?php checked( $enqueue, 'none' ); ?> autocomplete="off" />
          <strong>
          <?php _e( 'Do not add any parent stylesheet handling.', 'child-theme-configurator' ); ?>
          </strong>
          <p class="howto indent sep">
            <?php _e( "Select this option if this theme already handles the parent theme stylesheet or if the parent theme's <code>style.css</code> file is not used for its appearance.", 'child-theme-configurator' ); ?>
          </p>
          </label>
            
            </div>
            <div class="ctc-input-cell ctc-clear">
                <strong style="float:right">
                    <?php _e( 'Advanced handling options', 'child-theme-configurator' ); ?>:</strong>
                <p class="howto">
                </p>
            </div>
            <div class="ctc-input-cell-wide">
                <label><input class="ctc_checkbox ctc-themeonly" id="ctc_ignoreparnt" name="ctc_ignoreparnt" type="checkbox" 
                value="1" autocomplete="off" />
          <strong><?php _e( 'Ignore parent theme stylesheets.', 'child-theme-configurator' ); ?></strong>
          <p class="howto indent"><?php _e( 'Do not load or parse the parent theme styles. Only use this option if the Child Theme uses a Framework like Genesis and uses <em>only child theme stylesheets</em> for its appearance.', 'child-theme-configurator' ); ?></p></label>
            </div>
            <div id="ctc_repairheader_container" style="display:none">
                <div class="ctc-input-cell ctc-clear">
                    <p class="howto">
                    </p>
                </div>
                <div class="ctc-input-cell-wide sep">
                    <label><input class="ctc_checkbox ctc-themeonly" id="ctc_repairheader" name="ctc_repairheader" type="checkbox" 
                value="1" autocomplete="off" />
<strong><?php _e( 'Repair the header template in the child theme.', 'child-theme-configurator' ); ?></strong>
<p class="howto indent"><?php _e( 'Let the Configurator (try to) resolve any stylesheet issues listed above. This can fix many, but not all, common problems.', 'child-theme-configurator' ); ?></p></label>
                </div>
            </div>

            <div id="ctc_dependencies_container" style="display:none">
                <div class="ctc-input-cell ctc-clear">
                    <p class="howto">
                    </p>
                </div>
                <div class="ctc-input-cell-wide sep">
                    <strong class="indent">
                        <?php _e( 'Do not force dependency for these stylesheet handles:', 'child-theme-configurator' ); ?>
                    </strong>
                    <p id="ctc_dependencies"></p>
                    <p class="howto indent">
                        <?php _e( 'By default, the order of stylesheets that load prior to the primary stylesheet is preserved by treating them as dependencies. In some cases, stylesheets are detected in the preview that are not used site-wide. If necessary, dependency can be removed for specific stylesheets above.', 'child-theme-configurator' ); ?>
                    </p>
                </div>
            </div>
            <?php //do_action( 'chld_thm_cfg_enqueue_options' ); // removed for ctc 2.0 ?>
        </div>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="ctc_child_header_parameters" style="display:none">
            <div class="ctc-input-cell"  ><span class="ctc-step ctc-step-number">7</span>
                <strong>
                    <?php _e( 'Customize the Child Theme Name, Description, Author, Version, etc.:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide">
                <button class="ctc-section-toggle button-secondary" id="ctc_theme_attributes" title="<?php _e( 'Click to toggle form', 'child-theme-configurator' ); ?>"><?php _e( 'Click to Edit Child Theme Attributes', 'child-theme-configurator' ); ?> &nbsp;</button></div>
            <div class="ctc-clear ctc-section-toggle-content" id="ctc_theme_attributes_content">
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_name">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Child Theme Name', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <input class="ctc_text ctc-themeonly" id="ctc_child_name" name="ctc_child_name" type="text" value="<?php echo esc_attr( $this->ctc()->get( 'child_name' ) ); ?>" placeholder="<?php _e( 'Theme Name', 'child-theme-configurator' ); ?>" autocomplete="off" />
                    </div>
                </div>
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_website">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Theme Website', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <input class="ctc_text ctc-themeonly" id="ctc_child_themeuri" name="ctc_child_themeuri" type="text" value="<?php echo esc_attr( $this->ctc()->get( 'themeuri' ) ); ?>" placeholder="<?php _e( 'Theme Website', 'child-theme-configurator' ); ?>" autocomplete="off" />
                    </div>
                </div>
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_author">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Author', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <input class="ctc_text" id="ctc_child_author" name="ctc_child_author" type="text" value="<?php echo esc_attr( $this->ctc()->get( 'author' ) ); ?>" placeholder="<?php _e( 'Author', 'child-theme-configurator' ); ?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_authoruri">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Author Website', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <input class="ctc_text ctc-themeonly" id="ctc_child_authoruri" name="ctc_child_authoruri" type="text" value="<?php echo esc_attr( $this->ctc()->get( 'authoruri' ) ); ?>" placeholder="<?php _e( 'Author Website', 'child-theme-configurator' ); ?>" autocomplete="off" />
                    </div>
                </div>
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_descr">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Theme Description', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <textarea class="ctc_text ctc-themeonly" id="ctc_child_descr" name="ctc_child_descr" placeholder="<?php _e( 'Description', 'child-theme-configurator' ); ?>" autocomplete="off" ><?php echo esc_textarea( $this->ctc()->get( 'descr' ) ); ?></textarea>
                    </div>
                </div>
                <div class="ctc-input-row clearfix ctc-themeonly-container" id="input_row_child_tags">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Theme Tags', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell-wide">
                        <textarea class="ctc_text ctc-themeonly" id="ctc_child_tags" name="ctc_child_tags" placeholder="<?php _e( 'Tags', 'child-theme-configurator' ); ?>" autocomplete="off" ><?php echo esc_textarea( $this->ctc()->get( 'tags' ) ); ?></textarea>
                    </div>
                </div>
                <div class="clearfix ctc-themeonly-container" id="input_row_child_version">
                    <div class="ctc-input-cell">
                        <strong>
                            <?php _e( 'Version', 'child-theme-configurator' ); ?>
                        </strong>
                    </div>
                    <div class="ctc-input-cell">
                        <input class="ctc_text" id="ctc_child_version" name="ctc_child_version" type="text" value="<?php echo esc_attr( $this->ctc()->get( 'version' ) ); ?>" placeholder="<?php _e( 'Version', 'child-theme-configurator' ); ?>" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <?php //if ( ! is_multisite() || ! empty( $this->ctc()->themes[ 'parnt' ][ $this->ctc()->get_current_parent() ][ 'allowed' ] ) ): ?>
        <div class="ctc-input-row clearfix ctc-themeonly-container" id="ctc_copy_theme_mods" style="display:none">
            <div class="ctc-input-cell">
                <label for="ctc_parent_mods"><span class="ctc-step ctc-step-number">8</span><strong>
          <?php _e( 'Copy Menus, Widgets and other Customizer Settings from the Parent Theme to the Child Theme:', 'child-theme-configurator' ); ?>
          </strong> </label>
            </div>
            <div class="ctc-input-cell-wide howto">
                <label for="ctc_parent_mods">
          <input class="ctc_checkbox ctc-themeonly" id="ctc_parent_mods" name="ctc_parent_mods" type="checkbox" 
                value="1" />
          <?php _e( "This option replaces the Child Theme's existing Menus, Widgets and other Customizer Settings with those from the Parent Theme. You should only need to use this option the first time you configure a Child Theme.", 'child-theme-configurator' ); ?>
          <h3>
          <?php _e( 'IMPORTANT: Some "premium" themes use unsupported options that cannot be copied with the free verson of CTC. If you purchased this theme from a website such as "ThemeForest," child themes may not work correctly. Click the "Upgrade" tab for more information.', 'child-theme-configurator' ); ?>
          </h3>
        </label>
            

            </div>
        </div>
        <?php //endif; ?>

        <div class="ctc-input-row clearfix" id="ctc_configure_submit" style="display:none">
            <div class="ctc-input-cell"><span class="ctc-step ctc-step-number">9</span>
                <strong class="shift">
                    <?php _e( 'Click to run the Configurator:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide">
                <input class="ctc_submit button button-primary" id="ctc_load_styles" name="ctc_load_styles" type="submit" value="<?php _e( 'Configure Child Theme', 'child-theme-configurator' ); ?>" disabled/>
            </div>
        </div>

        <?php
        else :


            // plugin inputs 
            wp_nonce_field( 'ctc_plugin' );
        ?>
        <input class="ctc-hidden" id="ctc_theme_parnt" name="ctc_theme_parnt" type="hidden" value="<?php echo $this->ctc()->css->get_prop( 'parnt' ); ?>"/>
        <input class="ctc-hidden" id="ctc_theme_child" name="ctc_theme_child" type="hidden" value="<?php echo $this->ctc()->css->get_prop( 'child' ); ?>"/>
        <input class="ctc-hidden" id="ctc_action" name="ctc_action" type="hidden" value="plugin"/>
        <input class="ctc-hidden" id="ctc_child_type" name="ctc_child_type" type="hidden" value="existing"/>
        <div class="ctc-input-cell-wide ctc-analysis" id="child_analysis_notice">&nbsp;</div>
        <div class="ctc-input-row clearfix" id="ctc_stylesheet_files">
            <?php
            $stylesheets = ChildThemeConfiguratorPro::ctcp()->get_css_files();
            if ( count( $stylesheets ) ): ?>
            <div class="ctc-input-cell ctc-section-toggle" id="ctc_additional_css_files">
                <strong>
                    <?php _e( 'Parse Plugin stylesheets:', 'chld_thm_cfg' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide ctc-section-toggle-content" id="ctc_additional_css_files_content" style="display:block">
                <p style="margin-top:0">
                    <?php _e( 'Select the plugin stylesheets you wish to customize below.', 'chld_thm_cfg' ); ?>
                </p>
                <ul>
                    <?php 
            foreach ( $stylesheets as $stylesheet => $label ): ?>
                    <li><label>
              <input class="ctc_checkbox" name="ctc_additional_css[]" type="checkbox" 
                value="<?php echo $stylesheet; ?>" />
              <?php echo $label; ?></label>
                    
                    </li>
                    <?php 
            endforeach; ?>
                </ul>
            </div>
            <?php 
        endif; ?>
        </div>
        <div class="ctc-input-row clearfix" id="ctc_configure_submit_plugins" style="display:none">
            <div class="ctc-input-cell">
                <strong class="shift">
                    <?php _e( 'Click to run the Configurator:', 'child-theme-configurator' ); ?>
                </strong>
            </div>
            <div class="ctc-input-cell-wide">
                <input class="ctc_submit button button-primary" id="ctc_load_styles_plugins" name="ctc_load_styles" type="submit" value="<?php _e( 'Configure Plugin Styles', 'child-theme-configurator' ); ?>" disabled/>
            </div>
        </div>
        <?php endif;  ?>
    </form>
</div>