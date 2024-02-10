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

var bgArray = [
    '/wp-content/themes/conall-child/images/living-backg-1-1920x1200.jpg',
    '/wp-content/themes/conall-child/images/living-backg-2-1920x1200.jpg',
    '/wp-content/themes/conall-child/images/living-backg-3-1920x1200.jpg',
    '/wp-content/themes/conall-child/images/living-backg-4-1920x1200.jpg'
]
jQuery(document).ready(function ($) {
    $('#redselect').on('change', function () {
        value = $(this).val() - 1;
        $('#living-hae-impacts-row').css({
            'background-image': 'url(' + bgArray[value] + ')'
        });
    });
});
jQuery(document).ready(function ($) {
    $(document).on('click', '.pv-red-select', function () {
        $(this).parent().toggleClass('focused');
    })
});

/* jQuery(document).ready(function ($) {
    $(document).on('click', '.tooltip-close', function () {
        $('#tipclose1').parent().hide();
    })
}); */