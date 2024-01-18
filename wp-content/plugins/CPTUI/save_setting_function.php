<?php

// savesetting.php

add_action('wp_ajax_save_display_grid_setting', 'save_display_grid_setting');
function save_display_grid_setting()
{
    parse_str($_POST['grid_form_data'], $form_data);

    // Update specific values
    $current_options = array(
        'column' => sanitize_text_field($form_data['column']),
        'rows' => sanitize_text_field($form_data['rows']),
    );

    // Save the entire array back to options table
    update_option('business_terms_display_grid', $current_options);
    echo 'Settings saved successfully';
    wp_die();
}

add_action('wp_ajax_save_display_list_setting', 'save_display_list_setting');
function save_display_list_setting()
{
    parse_str($_POST['list_form_data'], $form_data);

    // Update specific values
    $current_options = array(
        'display_terms' => sanitize_text_field($form_data['display_terms']),
    );

    // Save the entire array back to options table
    update_option('business_terms_display_list', $current_options);
    echo 'Settings saved successfully';
    wp_die();
}

add_action('wp_ajax_save_display_carousel_setting', 'save_display_carousel_setting');
function save_display_carousel_setting()
{
    parse_str($_POST['carousel_form_data'], $form_data);

    // Update specific values
    $current_options = array(
        'display_terms' => sanitize_text_field($form_data['display_terms']),
    );

    // Save the entire array back to options table
    update_option('business_terms_display_carousel', $current_options);
    echo 'Settings saved successfully';
    wp_die();
}