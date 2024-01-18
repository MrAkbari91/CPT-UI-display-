<?php


//add style for admin
add_action('admin_enqueue_scripts', 'my_admin_scripts');

function my_admin_scripts()
{
    wp_enqueue_style('cptui-admin-styles', plugin_dir_url(__FILE__) . 'admin/css/style.css', array(), '1.0.0');

    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'admin/js/script.js', array(), '1.0.0', true);
}


//add styles for public
add_action('wp_enqueue_scripts', 'enqueue_my_plugin_styles');
function enqueue_my_plugin_styles()
{
    wp_enqueue_style('cptui-styles', plugin_dir_url(__FILE__) . 'public/css/style.css', array(), '1.0.0');
    wp_enqueue_script('cptui-plugin-script', plugin_dir_url(__FILE__) . 'public/js/script.js', array(), '1.0.0', true);
    wp_enqueue_style('owl-carousel', plugin_dir_url(__FILE__) . 'public/css/owl.carousel.min.css', array(), '1.0.0');
    wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'public/css/font-awesome.min.css', array(), '1.0.0');
    wp_enqueue_script('owl-carousel', plugin_dir_url(__FILE__) . 'public/js/owl.carousel.min.js', array(), '1.0.0', true);
    wp_localize_script('cptui-plugin-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax_nonce'),
    ));
}

/**
 * add settings link at installed plugin list
 * Hook the function to the plugin action links filter
 *
 * */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_settings_link');

function add_settings_link($links)
{
    $settings_link = '<a href="edit.php?post_type=business-terms&page=business_terms_settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
