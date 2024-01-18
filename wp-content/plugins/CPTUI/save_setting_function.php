<?php

// savesetting.php

add_action('wp_ajax_save_display_list_setting', 'save_display_list_setting_callback');
function save_display_list_setting_callback() {
    check_ajax_referer('ajax_nonce', 'security');

    $postsValue = sanitize_text_field($_POST['posts']);

    update_option('business_terms_display_list', array('post' => $postsValue));

    echo 'Settings saved successfully';

    wp_die();
}
