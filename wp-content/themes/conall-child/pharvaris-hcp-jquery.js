// pharvaris-deflate jQuery

/* $(function () {
    $(document).on('click', '.pv-red-select', function () {
        $(this).parent().toggleClass('focused');
    })
}); */

/* jQuery(function () {
    // This code will not trigger the error

}); */
/*
jQuery(document).ready(function ($) {
    $(document).on('click', '.tooltip-close', function () {
        $('.ult-tooltipster-base').tooltip('hide')
    })
}); */
/* jQuery(document).ready(function ($) {
    $(window).resize(function () {
        $('#test').html($(window).width());
        if ($(window).width() > 768) {
            alert();
        }
    });
}); */
//Function to the css rule
jQuery(document).ready(function ($) {
    $(window).resize(function () {
        $('#living-hero-row').html($(window).width());
        if ($(window).width() > 1024) {
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