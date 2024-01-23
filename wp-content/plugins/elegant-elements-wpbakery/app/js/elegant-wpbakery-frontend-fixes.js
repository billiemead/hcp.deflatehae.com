jQuery( document ).on( 'vc-full-width-row', function( $elements ) {
	setTimeout( function() {
		jQuery.each($elements, function(key, item) {
			var $el = jQuery(this);
			$el.addClass("vc_hidden");
			var el_margin_left, el_margin_right, offset, width, padding, paddingRight, $el_full = $el.next(".vc_row-full-width");
			$el_full.length || ($el_full = $el.parent().next(".vc_row-full-width")), $el_full.length && (el_margin_left = parseInt($el.css("margin-left"), 10), el_margin_right = parseInt($el.css("margin-right"), 10), offset = 0 - $el_full.offset().left - el_margin_left, width = jQuery(window).width(), "rtl" === $el.css("direction") && (offset -= $el_full.width(), offset += width, offset += el_margin_left, offset += el_margin_right), $el.css({
				position: "relative",
				left: offset,
				"box-sizing": "border-box",
				width: width
			}), $el.data("vcStretchContent") || ("rtl" === $el.css("direction") ? ((padding = offset) < 0 && (padding = 0), (paddingRight = offset) < 0 && (paddingRight = 0)) : ((padding = -1 * offset) < 0 && (padding = 0), (paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right) < 0 && (paddingRight = 0)), $el.css({
				"padding-left": padding + "px",
				"--padding-left": padding + "px",
				"padding-right": padding + "px"
			})), $el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden"), jQuery(document).trigger("vc-full-width-row-single", {
				el: $el,
				offset: offset,
				marginLeft: el_margin_left,
				marginRight: el_margin_right,
				elFull: $el_full,
				width: width
			}))
		});
	}, 30 );
} );

// Remove placeholder div as it isn't required.
jQuery( '.site .vc_empty-placeholder' ).remove();

// Fire animated dividers render event.
jQuery( window ).load( function() {
	jQuery( document ).trigger( 'renderAnimatedDivider' );
} );
