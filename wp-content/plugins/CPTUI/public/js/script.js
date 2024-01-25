
jQuery(document).ready(function () {
    jQuery("#owl-carousel-slider").owlCarousel({
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [767, 2],
        itemsMobile: [600, 1],
        navigation: true,
        navigationText: ["", ""],
        pagination: true,
        autoPlay: true,
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

//navbar js
const container = document.querySelector("#navbar");
const primary = container.querySelector(".primary");
const primaryItems = container.querySelectorAll(".primary > li:not(.more)");
container.classList.add("jsfied");

// insert "more" button and duplicate the list

primary.insertAdjacentHTML(
    "beforeend",
    `<li class="more">
        <button type="button" aria-haspopup="true" aria-expanded="false">
           <i class="fa fa-bars"></i>
        </button>
        <ul class="secondary">
            ${primary.innerHTML}
        </ul>
    </li>`
);

const secondary = container.querySelector(".secondary");
const secondaryItems = secondary.querySelectorAll("li");
const allItems = container.querySelectorAll("li");
const moreLi = primary.querySelector(".more");
const moreBtn = moreLi.querySelector("button");
moreBtn.addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.toggle("show-secondary");
    moreBtn.setAttribute(
        "aria-expanded",
        container.classList.contains("show-secondary")
    );
});

// adapt tabs

const doAdapt = () => {
    // reveal all items for the calculation
    allItems.forEach((item) => {
        item.classList.remove("hidden");
    });

    // hide items that won't fit in the Primary
    let stopWidth = moreBtn.offsetWidth;
    let hiddenItems = [];
    const primaryWidth = primary.offsetWidth;
    primaryItems.forEach((item, i) => {
        if (primaryWidth >= stopWidth + item.offsetWidth) {
            stopWidth += item.offsetWidth;
        } else {
            item.classList.add("hidden");
            hiddenItems.push(i);
        }
    });

    // toggle the visibility of More button and items in Secondary
    if (!hiddenItems.length) {
        moreLi.classList.add("hidden");
        container.classList.remove("show-secondary");
        moreBtn.setAttribute("aria-expanded", false);
    } else {
        secondaryItems.forEach((item, i) => {
            if (!hiddenItems.includes(i)) {
                item.classList.add("hidden");
            }
        });
    }
};

doAdapt(); // adapt immediately on load
window.addEventListener("resize", doAdapt); // adapt on window resize