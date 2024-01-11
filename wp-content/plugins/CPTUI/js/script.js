
jQuery(document).ready(function (jQuery) {
    // Hide all display settings divs initially
    var grid_view = `<div id="grid-view-setting">
        <h2>Grid View Settings</h2>
        <label for="grid-columns">Grid Columns:</label>
        <input type="number" name="grid-columns" id="grid-columns" value="4">
            <label for="shortcode">Shortcode:</label>
            <input type="text" name="shortcode" id="shortcode" readonly value="[business-terms-grid]">
        </div>`;

    var list_view = `<div id="list-view-setting">
        <h2>List View Settings</h2>
        <label for="list-rows">List Rows:</label>
        <input type="number" name="list-rows" id="list-rows" value="3">
            <label for="shortcode">Shortcode:</label>
            <input type="text" name="shortcode" id="shortcode" readonly value="[business-terms-list]">
        </div>`;

    var carousel_view = `<div id="thumbnail-view-setting">
        <h2>Thumbnail View Settings</h2>
        <label for="thumbnail-rows">Thumbnail Rows:</label>
        <input type="number" name="thumbnail-rows" id="thumbnail-rows" value="3">
            <label for="shortcode">Shortcode:</label>
            <input type="text" name="shortcode" id="shortcode" readonly value="[business-terms-carousel]">
        </div>`;

    var selectedValue = jQuery("#display-type").val();
    displayType(selectedValue);

    function displayType(selectedValue) {
        if (selectedValue == 'grid_view') {
            jQuery('#display-settings').html(grid_view);
        } else if (selectedValue == 'list_view') {
            jQuery('#display-settings').html(list_view);
        } else if (selectedValue == 'carousel_view') {
            jQuery('#display-settings').html(carousel_view);
        }
    }

    jQuery('#display-type').on('change', function () {
        var selectedValue = jQuery(this).val();
        displayType(selectedValue);
    })

    // Handle form submission with AJAX
    jQuery('#display-settings-form').submit(function (e) {
        e.preventDefault();

        var data = {
            action: 'save_business_terms_settings',
            displayType: jQuery('#display-type').val(),
        };

        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('Failed to save settings.');
            }
        });
    });
});



var container = document.getElementById('business-terms-carousel-wrapper')
var slider = document.getElementById('carousel-slider');
var slides = document.getElementsByClassName('slide').length;
var buttons = document.getElementsByClassName('btn');


var currentPosition = 0;
var currentMargin = 0;
var slidesPerPage = 0;
var slidesCount = slides - slidesPerPage;
var containerWidth = container.offsetWidth;
var prevKeyActive = false;
var nextKeyActive = true;

window.addEventListener("resize", checkWidth);

function checkWidth() {
    containerWidth = container.offsetWidth;
    setParams(containerWidth);
}

function setParams(w) {
    if (w < 551) {
        slidesPerPage = 1;
    } else {
        if (w < 901) {
            slidesPerPage = 2;
        } else {
            if (w < 1101) {
                slidesPerPage = 3;
            } else {
                slidesPerPage = 4;
            }
        }
    }
    slidesCount = slides - slidesPerPage;
    if (currentPosition > slidesCount) {
        currentPosition -= slidesPerPage;
    };
    currentMargin = - currentPosition * (100 / slidesPerPage);
    slider.style.marginLeft = currentMargin + '%';
    if (currentPosition > 0) {
        buttons[0].classList.remove('inactive');
    }
    if (currentPosition < slidesCount) {
        buttons[1].classList.remove('inactive');
    }
    if (currentPosition >= slidesCount) {
        buttons[1].classList.add('inactive');
    }
}

setParams();

function slideRight() {
    if (currentPosition != 0) {
        slider.style.marginLeft = currentMargin + (100 / slidesPerPage) + '%';
        currentMargin += (100 / slidesPerPage);
        currentPosition--;
    };
    if (currentPosition === 0) {
        buttons[0].classList.add('inactive');
    }
    if (currentPosition < slidesCount) {
        buttons[1].classList.remove('inactive');
    }
};

function slideLeft() {
    if (currentPosition != slidesCount) {
        slider.style.marginLeft = currentMargin - (100 / slidesPerPage) + '%';
        currentMargin -= (100 / slidesPerPage);
        currentPosition++;
    };
    if (currentPosition == slidesCount) {
        buttons[1].classList.add('inactive');
    }
    if (currentPosition > 0) {
        buttons[0].classList.remove('inactive');
    }
};