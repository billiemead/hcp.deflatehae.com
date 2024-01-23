(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_checkbox_button_set = {
					init: function() {
						jQuery( 'body' ).find( '.eewpb-option-checkbox-button-set .ui-button' ).on(
							'click', function( e ) {
								var $checkboxsetcontainer = jQuery( this ).closest( '.eewpb-option-checkbox-button-set' );

								e.preventDefault();

								jQuery( this ).toggleClass( 'ui-state-active' );

								$checkboxsetcontainer.find( '.button-set-value' ).val(
									$checkboxsetcontainer.find( '.ui-state-active' ).map(
										function( _, el ) {
											return jQuery( el ).data( 'value' );
										}
									).get()
								).trigger( 'change' );
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
