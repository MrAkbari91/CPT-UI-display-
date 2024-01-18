<?php

/**
 * Activate the plugin.
 */
function plugin_activate()
{
    // Trigger our function that registers the custom post type plugin.
    create_business_terms_post_type();

    // Add default post meta values
    add_option('business_terms_display_grid', array( 'column' => 4,'rows' => 3));
    add_option('business_terms_display_list', array('post' => 10));
    add_option('business_terms_display_carousel', array( 'post' => 4,'order' => 'ASC'));

    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules();
}
