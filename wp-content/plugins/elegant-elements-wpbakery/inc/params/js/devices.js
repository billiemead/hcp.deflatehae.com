(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_devices = {
					init: function() {
						var $deviceFields = this.$el.find( '.eewpb-option-devices' );
						if ( $deviceFields.length ) {
							$deviceFields.each(
								function() {
									jQuery( this ).find( '.elegant-device input' ).on(
										'change paste keyup', function() {
											jQuery( this ).parents( '.eewpb-option-devices' ).find( 'input[type="hidden"]' ).val(
												( ( jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(1) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(1) input' ).val() : '0' ) + ',' +
												( ( jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(2) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(2) input' ).val() : '0' ) + ',' +
												( ( jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(3) input' ).val().length ) ? jQuery( this ).parents( '.eewpb-option-devices' ).find( 'div:nth-child(3) input' ).val() : '0' )
											);
										}
									);
								}
							);
						}
					},

					parse: function ( param ) {
						var deviceValue = this.$el.find( '.eewpb-option-devices input[type="hidden"].' + param.param_name ).val(),
							$this       = this;

						jQuery.each(
							jQuery( '.device-item' ), function() {
								var name = jQuery( this ).find( 'input' ).attr( 'name' ),
								value    = jQuery( this ).find( 'input' ).val();

								$this.params[ name ] = value;
							}
						);

						return deviceValue;
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
