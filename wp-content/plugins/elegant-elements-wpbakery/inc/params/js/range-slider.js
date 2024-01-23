(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_range_slider = {
					init: function() {
						jQuery( 'body' ).find( '.eewpb-option-range-slider .ui-range-slider' ).on(
							'input', function( event ) {
								var value                  = jQuery( this ).val(),
								$rangeSliderValueContainer = jQuery( this ).prev( '.ui-range-slider-value' );

								event.preventDefault();

								$rangeSliderValueContainer.val( value );
							}
						);

						jQuery( 'body' ).find( '.eewpb-option-range-slider .ui-range-slider-value' ).on(
							'change', function( event ) {
								var value             = jQuery( this ).val(),
								$rangeSliderContainer = jQuery( this ).next( '.ui-range-slider' );

								event.preventDefault();

								$rangeSliderContainer.val( value );
							}
						);
					},

					/**
					 * Used in shortcode saving
					 * Default: '' empty (unchecked)
					 * Can be overwritten by 'std'
					 * @param param
					 * @returns {string}
					 */
					defaults: function ( param ) {
						return ''; // needed for saving - without this default value for param will be first value in array
					}
			};
		}
	);
} )( window.jQuery );
