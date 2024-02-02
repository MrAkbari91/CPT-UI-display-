jQuery(document).ready(function($) {

    // Responsive Menu
    $('.menu-toggle').click(function() {
        $(this).toggleClass('active');
        $(this).parent().find('ul.nav-menu').slideToggle();
        $(this).parent().find('div.nav-menu > ul').slideToggle();
    });

    $('.dropdown-toggle').click(function() {
        $(this).toggleClass('active');
        $(this).parent().find('.sub-menu').first().slideToggle();
        $(this).parent().find('.children').first().slideToggle();
    });

    if( $(window).width() < 1024 ) {
        $('#site-navigation .nav-menu').find("li").last().bind( 'keydown', function(e) {
            if( !e.shiftKey && e.which === 9 ) {
                e.preventDefault();
                $('.site-header').find('.menu-toggle').focus();
            }
        });
    }
    else {
        $('#site-navigation .nav-menu').find("li").unbind('keydown');
    }

    $(window).resize(function() {
        if( $(window).width() < 1024 ) {
            $('#site-navigation .nav-menu').find("li").last().bind( 'keydown', function(e) {
                if( !e.shiftKey && e.which === 9 ) {
                    e.preventDefault();
                    $('.site-header').find('.menu-toggle').focus();
                }
            });
        }
        else {
            $('#site-navigation .nav-menu').find("li").unbind('keydown');
        }
    });

    $('.menu-toggle').on('keydown', function (e) {
        var tabKey    = e.keyCode === 9;
        var shiftKey  = e.shiftKey;

        if( $('.menu-toggle').hasClass('active') ) {
            if ( shiftKey && tabKey ) {
                e.preventDefault();
                $('#site-navigation .nav-menu').find("li:last-child > a").focus();
                $('#site-navigation .nav-menu').find("li").last().bind( 'keydown', function(e) {
                    if( !e.shiftKey && e.which === 9 ) {
                        e.preventDefault();
                        $('.site-header').find('.menu-toggle').focus();
                    }
                });
            }
        }
    });

    // Adds a search icon.
    $('.search-form input[type="submit"]').replaceWith('<button type="submit" class="search-submit" value="Search"><svg id="icon-search" viewBox="0 0 30 32"><path class="path1" d="M20.571 14.857q0-3.304-2.348-5.652t-5.652-2.348-5.652 2.348-2.348 5.652 2.348 5.652 5.652 2.348 5.652-2.348 2.348-5.652zM29.714 29.714q0 0.929-0.679 1.607t-1.607 0.679q-0.964 0-1.607-0.679l-6.125-6.107q-3.196 2.214-7.125 2.214-2.554 0-4.884-0.991t-4.018-2.679-2.679-4.018-0.991-4.884 0.991-4.884 2.679-4.018 4.018-2.679 4.884-0.991 4.884 0.991 4.018 2.679 2.679 4.018 0.991 4.884q0 3.929-2.214 7.125l6.125 6.125q0.661 0.661 0.661 1.607z"></path></svg></button>');

    // Sets scroll to top.
    var scroll    = $(window).scrollTop();  
    var scrollup  = $('.to-top');  

    $(window).scroll(function() {
        if ($(this).scrollTop() > 1) {
            scrollup.css({bottom:"25px"});
        } 
        else {
            scrollup.css({bottom:"-100px"});
        }
    });

    scrollup.click(function() {
        $('html, body').animate({scrollTop: '0px'}, 800);
        return false;
    });
    
});