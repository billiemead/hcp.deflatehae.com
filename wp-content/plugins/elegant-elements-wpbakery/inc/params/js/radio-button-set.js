(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_radio_button_set = {
					init: function() {
						jQuery( 'body' ).find( '.eewpb-option-radio-button-set .ui-button' ).on(
							'click', function( event ) {
								var value          = '',
								$radioSetContainer = jQuery( this ).closest( '.eewpb-option-radio-button-set' );

								event.preventDefault();

								$radioSetContainer.find( '.ui-state-active' ).removeClass( 'ui-state-active' );
								jQuery( this ).addClass( 'ui-state-active' );

								value = $radioSetContainer.find( '.ui-state-active' ).data( 'value' );
								$radioSetContainer.find( '.button-set-value' ).val( value ).attr( 'value', value ).trigger( 'change' );
								jQuery( this ).blur();
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
						return param.default; // needed for saving - without this default value for param will be first value in array
					}
			};
		}
	);
} )( window.jQuery );
