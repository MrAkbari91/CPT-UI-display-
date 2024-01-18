<?php
/**
 * create custom post type business-terms
 */

if (!function_exists('create_business_terms_post_type')) {
    function create_business_terms_post_type()
    {
        $supports = array(
            'title', // post title
            'editor', // post content
            'author', // post author
            'thumbnail', // featured images
            'excerpt', // post excerpt
            'comments', // post comments
            'revisions', // post revisions
            'page-attributes', // custom attributes for pages
            'post-formats', // post formats
        );
        $labels = array(
            'name' => _x('Business Terms', 'plural'),
            'singular_name' => _x('Business Term', 'singular'),
            'menu_name' => _x('Business Terms', 'admin menu'),
            'name_admin_bar' => _x('Business Terms', 'admin bar'),
            'add_new' => _x('Add New Term', 'add new'),
            'add_new_item' => __('Add New Terms'),
            'new_item' => __('New Terms'),
            'edit_item' => __('Edit Terms'),
            'view_item' => __('View Terms'),
            'all_items' => __('All Terms'),
            'search_items' => __('Search Terms'),
            'not_found' => __('No Terms found.'),
            'not_found_in_trash' => __('No Terms found in Trash.'),
            'update_item' => __('Update Terms'),
            'featured_image ' => __('Featured Image'),
        );
        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'menu_position' => 5,
            'public' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'business-term'),
            'has_archive' => true,
            'hierarchical' => false,
        );
        register_post_type('business-terms', $args);

    }
}

add_action('init', 'create_business_terms_post_type');


/**
 * Register Custom Taxonomy
 **/

if (!function_exists('create_business_terms_taxonomy')) {

    function create_business_terms_taxonomy()
    {
        $labels = array(
            'name' => _x('Categories', 'taxonomy general name'),
            'singular_name' => _x('Category', 'taxonomy singular name'),
            'search_items' => __('Search Categories'),
            'all_items' => __('All Categories'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Categories'),
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'business-category'),
        );
        register_taxonomy('business-category', array('business-terms'), $args);
    }
}
add_action('init', 'create_business_terms_taxonomy');

/**
 * Add Settings Page
 *
 * Hook the settings page function to the admin_menu action
 */

add_action('admin_menu', 'business_terms_settings_menu');

if (!function_exists('business_terms_settings_menu')) {
    function business_terms_settings_menu()
    {
        add_submenu_page(
            'edit.php?post_type=business-terms', // Parent menu slug
            'Settings',                          // Page title
            'Settings',                          // Menu title
            'manage_options',                    // Capability required
            'business_terms_settings',           // Menu slug
            'business_terms_settings_page'       // Callback function
        );
    }
}