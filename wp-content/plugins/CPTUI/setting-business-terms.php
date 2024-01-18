<?php
function business_terms_settings_page()
{
    $list_value = get_option('business_terms_display_list');
    $grid_value = get_option('business_terms_display_grid');
    $carousel_value = get_option('business_terms_display_carousel');
    ?>
    <div class="wrap">
        <h1>Business Terms Settings</h1>
        <div>
            <div id="display-settings">
                <div id="grid-view-setting">
                    <h2>Grid View Settings</h2>
                    <form id="display-settings-grid-form">
                        <div class="form-group">
                            <label for="column">Display Column:</label>
                            <input type="number" name="column" id="column" value="<?php echo $grid_value['column']; ?>"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="rows">Display Rows:</label>
                            <input type="number" name="rows" id="rows" value="<?php echo $grid_value['rows']; ?>"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="grid_shortcode">Shortcode:</label>
                            <input type="text" name="grid_shortcode" id="grid_shortcode" class="cptui-shortcode"
                                   readonly
                                   value="[business-terms-grid]" title="Copy this shortcode">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="button-primary save-display-setting"
                                   id="save-display-grid-setting"
                                   value="Save Setting">
                            <div class="form-group message" id="grid-message"></div>
                        </div>
                    </form>
                </div>

                <div id="list-view-setting">
                    <h2>List View Settings</h2>
                    <form id="display-settings-List-form">
                        <div class="form-group">
                            <label for="display_terms_list">Display Terms </label>
                            <input type="number" name="display_terms" id="display_terms_list" value="<?php echo $list_value['display_terms']; ?>"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="list_shortcode">Shortcode:</label>
                            <input type="text" name="list_shortcode" id="list_shortcode" class="cptui-shortcode"
                                   readonly
                                   value="[business-terms-list]" title="Copy this shortcode">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="button-primary save-display-setting"
                                   id="save-display-list-setting"
                                   value="Save Setting">
                            <div class="form-group message" id="list-message"></div>
                        </div>
                    </form>
                </div>

                <div id="carousel-view-setting">
                    <h2>
                        Carousel View Settings
                    </h2>
                    <form id="display-settings-Carousel-form">
                        <div class="form-group">
                            <label for="display_terms_grid">Display Terms:</label>
                            <input type="number" name="display_terms" id="display_terms_grid" value="<?php echo $carousel_value['display_terms']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="carousel_shortcode">Shortcode:</label>
                            <input type="text" name="carousel_shortcode" id="carousel_shortcode" class="cptui-shortcode"
                                   readonly
                                   value="[business-terms-carousel]" title="Copy this shortcode">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="button-primary save-display-setting"
                                   id="save-display-carousel-setting" value="Save Setting">
                            <div class="form-group message" id="carousel-message"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
