(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_dimensions = {
					init: function() {
						var $dimensionFields = this.$el.find( '.eewpb-option-dimensions' );
						if ( $dimensionFields.length ) {
							$dimensionFields.each(
								function() {
									jQuery( this ).find( '.elegant-dimension input' ).on(
										'change paste keyup', function() {
											jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'input[type="hidden"]' ).val(
												( ( jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(1) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(1) input' ).val() : '0px' ) + ' ' +
												( ( jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(2) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(2) input' ).val() : '0px' ) + ' ' +
												( ( jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(3) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(3) input' ).val() : '0px' ) + ' ' +
												( ( jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(4) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-dimensions' ).find( 'div:nth-child(4) input' ).val() : '0px' )
											);
										}
									);
								}
							);
						}
					},

					parse: function ( param ) {
						var dimensionValue = this.$el.find( '.eewpb-option-dimensions input[type="hidden"].' + param.param_name ).val(),
							$this          = this;

						jQuery.each(
							jQuery( '.dimension-item' ), function() {
								var name = jQuery( this ).find( 'input' ).attr( 'name' ),
								value    = jQuery( this ).find( 'input' ).val();

								$this.params[ name ] = value;
							}
						);

						return dimensionValue;
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
