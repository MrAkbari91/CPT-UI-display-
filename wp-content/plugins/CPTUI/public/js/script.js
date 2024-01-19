
jQuery(document).ready(function () {
    jQuery("#owl-carousel-slider").owlCarousel({
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [767, 2],
        itemsMobile: [600, 1],
        navigation: true,
        navigationText: ["", ""],
        pagination: true,
        autoPlay: true
    });
});


jQuery(document).ready(function () {
    // Add active class on click
    jQuery("#navbar li a[href*=#]").on("click", function (e) {
        e.preventDefault();

        var target = jQuery(this).attr("href");

        jQuery("html, body")
            .stop()
            .animate(
                {
                    scrollTop: jQuery(target).offset().top - 140,
                },
                600,
                function () { }
            );
        return false;
    });

    // Add active class on scroll
    jQuery(window).on("scroll", function () {
        jQuery(".nav-link").parent().removeClass("active");
        var scrollPos = jQuery(document).scrollTop();
        jQuery(".section").each(function () {
            var offsetTop = jQuery(this).offset().top;
            var outerHeight = jQuery(this).outerHeight();
            if (
                scrollPos >= offsetTop - 200 &&
                scrollPos < offsetTop + outerHeight - 200
            ) {
                var target = "#" + jQuery(this).attr("id");
                jQuery('.nav-link[href="' + target + '"]')
                    .parent()
                    .addClass("active");
            }
        });
    });
});