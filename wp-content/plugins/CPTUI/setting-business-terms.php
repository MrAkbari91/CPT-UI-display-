<?php
function business_terms_settings_page()
{
    $list_value = get_option('business_terms_display_list');
    $grid_value = get_option('business_terms_display_grid');
    //    add_option('business_terms_display_grid', array( 'column' => 4,'rows' => 3));
//    add_option('business_terms_display_list', array('post' => 10));
//    add_option('business_terms_display_carousel', array( 'post' => 4,'order' => 'ASC'));
    ?>
    <div class="wrap">
        <h1>Business Terms Settings</h1>
        <div>
            <div id="display-settings">
                <div id="grid-view-setting">
                    <h2>Grid View Settings</h2>
                    <form id="display-settings-grid-form">
                        <div>
                            <label for="column">Column:</label>
                            <input type="number" name="column" id="column" value="<?php echo $grid_value['column']; ?>"
                                   required>
                        </div>
                        <div>
                            <label for="rows">Rows:</label>
                            <input type="number" name="rows" id="rows" value="<?php echo $grid_value['rows']; ?>"
                                   required>
                        </div>
                        <div>
                            <label for="grid_shortcode">Shortcode:</label>
                            <input type="text" name="grid_shortcode" id="grid_shortcode" class="cptui-shortcode" readonly
                                   value="[business-terms-grid]">
                        </div>
                        <div>
                            <input class="button-primary save-display-setting" id="save-display-grid-setting"
                                   value="Save Setting">
                        </div>
                    </form>
                </div>

                <div id="list-view-setting">
                    <h2>List View Settings</h2>
                    <form id="display-settings-List-form">
                        <div>
                            <label for="Posts">Display Terms </label>
                            <input type="number" name="Posts" id="Posts" value="<?php echo $list_value['post']; ?>"
                                   required>
                        </div>
                        <div>
                            <label for="list_shortcode">Shortcode:</label>
                            <input type="text" name="list_shortcode" id="list_shortcode" class="cptui-shortcode" readonly
                                   value="[business-terms-list]">
                        </div>
                        <div>
                            <input class="button-primary save-display-setting" id="save-display-list-setting"
                                   value="Save Setting">
                        </div>
                    </form>
                </div>

                <div id="carousel-view-setting">
                    <h2>
                        Carousel View Settings
                    </h2>
                    <form id="display-settings-Carousel-form">
                        <div>
                            <label for="carousel-rows">Carousel Rows:</label>
                            <input type="number" name="carousel-rows" id="carousel-rows" value="3" required>
                        </div>
                        <div>
                            <label for="carousel_shortcode">Shortcode:</label>
                            <input type="text" name="carousel_shortcode" id="carousel_shortcode" class="cptui-shortcode" readonly
                                   value="[business-terms-carousel]">
                        </div>
                        <div>
                            <button class="button-primary save-display-setting" id="save-display-carousel-setting">Save
                                Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo plugins_url('admin/js/script.js', __FILE__); ?>"></script>
    <?php
}
