jQuery(document).ready(function ($) {
    jQuery('#save-display-list-setting').on('click', function (e) {
        e.preventDefault();

        var postsValue = jQuery('#Posts').val();

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_display_list_setting',
                posts: postsValue,
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});
