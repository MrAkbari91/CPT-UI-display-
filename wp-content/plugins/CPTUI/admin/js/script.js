jQuery(document).ready(function () {
    jQuery('#display-settings-grid-form').on('submit', function (e) {
        e.preventDefault();
        var gridFormData = jQuery(this).serialize();
        console.log(gridFormData)
        jQuery.ajax({
            type: 'POST',
            url: '../wp-admin/admin-ajax.php',
            data: {
                action: 'save_display_grid_setting',
                grid_form_data: gridFormData,
            },
            success: function (response) {
                console.log(response)
                jQuery('#grid-message').html(`<p class="message-success">${response}</p>`);
            },
            error: function (error) {
                jQuery('#grid-message').html(`<p class="message-error">${error}</p>`);
            }
        });
    });

    jQuery('#display-settings-List-form').on('submit', function (e) {
        e.preventDefault();
        var listFormData = jQuery(this).serialize();
        console.log(listFormData)
        jQuery.ajax({
            type: 'POST',
            url: '../wp-admin/admin-ajax.php',
            data: {
                action: 'save_display_list_setting',
                list_form_data: listFormData,
            },
            success: function (response) {
                console.log(response)
                jQuery('#list-message').html(`<p class="message-success">${response}</p>`);
            },
            error: function (error) {
                jQuery('#list-message').html(`<p class="message-error">${error}</p>`);
            }
        });
    })

    jQuery('#display-settings-Carousel-form').on('submit', function (e) {
        e.preventDefault();
        var carouselFormData = jQuery(this).serialize();
        console.log(carouselFormData)
        jQuery.ajax({
            type: 'POST',
            url: '../wp-admin/admin-ajax.php',
            data: {
                action: 'save_display_carousel_setting',
                carousel_form_data: carouselFormData,
            },
            success: function (response) {
                console.log(response)
                jQuery('#carousel-message').html(`<p class="message-success">${response}</p>`);
            },
            error: function (error) {
                jQuery('#carousel-message').html(`<p class="message-error">${error}</p>`);
            }
        });
    })
});
