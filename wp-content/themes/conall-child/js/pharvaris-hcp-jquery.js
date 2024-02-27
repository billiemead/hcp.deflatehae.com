// pharvaris-deflate jQuery

jQuery(document).ready(function ($) {
    $(window).load(function () {
        function check() {
            var bgArray = [
                '/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg',
                '/wp-content/themes/conall-child/images/living-backg-2-1920x1200.jpg',
                '/wp-content/themes/conall-child/images/living-backg-3-1920x1200.jpg',
                '/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg'
            ]
            $('#redselect').on('change', function () {
                value = $(this).val() - 1;
                $('#living-hae-impacts-row').css({
                    'background-image': 'url(' + bgArray[value] + ')'
                });
            });
            if ($(window).width() > 1024) {
                $('#living-hae-impacts-row').css({
                    'background-image': 'url(/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg)'
                });
                var bgArray = [
                    '/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-2-1920x1200.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-3-1920x1200.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg'
                ]
                $('#redselect').on('change', function () {
                    value = $(this).val() - 1;
                    $('#living-hae-impacts-row').css({
                        'background-image': 'url(' + bgArray[value] + ')'
                    });
                });
            } else if ($(window).width() > 768) {
                $('#living-hae-impacts-row').css({
                    'background-image': 'url(/wp-content/themes/conall-child/images/living-backg-1-1024x1475.jpg)'
                });

                var bgArray = [
                    '/wp-content/themes/conall-child/images/living-backg-1-1024x1475.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-2-1024x1475.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-3-1024x1475.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-4-1024x1475.jpg'
                ]
                $('#redselect').on('change', function () {
                    value = $(this).val() - 1;
                    $('#living-hae-impacts-row').css({
                        'background-image': 'url(' + bgArray[value] + ')'
                    });
                });
            } else if ($(window).width() > 576) {
                $('#living-hae-impacts-row').css({
                    'background-image': 'url(/wp-content/themes/conall-child/images/living-backg-1-576x1280.jpg)'
                });
                var bgArray = [
                    '/wp-content/themes/conall-child/images/living-backg-1-576x1280.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-2-576x1280.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-3-576x1280.jpg',
                    '/wp-content/themes/conall-child/images/living-backg-4-576x1280.jpg'
                ]
                $('#redselect').on('change', function () {
                    value = $(this).val() - 1;
                    $('#living-hae-impacts-row').css({
                        'background-image': 'url(' + bgArray[value] + ')'
                    });
                });
            }
        }
        $('#breakpoint-row').html($(window).width());
        check(); // first-time check

        $(window).resize(function () {  // no `on` here
            $('#breakpoint-row').html($(window).width());
            check();
        }); // no `.resize()` needed here
    })
}); // no `.resize()` needed here

// toggle Show All/Close All tips
jQuery(document).ready(function ($) {
    $('#toggler').on('click', function () {
        if ($(this).text() === "show all") {
            $(this).text("close all").css('font-weight', 'bold').css('text-decoration', 'underline');
        }
        else {
            $(this).text("show all").css('font-weight', 'bold').css('text-decoration', 'underline');
        }
        $("div#show-all-tips").toggle(300);
    })
});

// if any individual hotspot is clicked, hide SHOW ALL TIPS
jQuery(document).ready(function ($) {
    $('.ult-hotspot-tooltip').on('click', function () {

        $('#show-all-tips').css({
            'display': 'none'
        });

    })
});

// Toogle between Mobile hamburger and close X
jQuery(document).ready(function ($) {
    $('.mobile-hamburger').on('click', function () {

        $(this).toggleClass("active");

    });
});