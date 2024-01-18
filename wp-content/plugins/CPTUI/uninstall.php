<?php
/**
 * Deactivate the plugin.
 * */
function plugin_deactivate()
{
    unregister_post_type('business-terms');
    // Delete post meta
    delete_option('business_terms_display_list');
    delete_option('business_terms_display_grid');
    delete_option('business_terms_display_carousel');

    // Clear the permalinks
    flush_rewrite_rules();
}
