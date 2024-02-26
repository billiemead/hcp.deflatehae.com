// pharvaris-deflate jQuery

//Function to the css rule
jQuery(document).ready(function ($) {
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
    $(window).resize(function () {
        $('#breakpoint-row').html($(window).width());
        if ($(window).width() > 1025) {
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

        } else if ($(window).width() > 480) {
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
    });
});

/* jQuery(document).ready(function ($) {
    $(document).on('click', '.tooltip-close', function () {
        $('#tipclose1').parent().hide();
    })
}); */
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

jQuery(document).ready(function ($) {
    $('.mobile-hamburger').on('click', function () {

        $(this).toggleClass("active");

    });
});