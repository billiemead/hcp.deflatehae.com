(function ( $ ) {
	"use strict";

	$(
		function () {
				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_file_upload = {
					init: function() {
						this.$el.find( '.eewpb-option-file-upload .elegant-upload-button' ).on(
							'click', function( event ) {
								var $this = jQuery( this ),
								// Instantiates the variable that holds the media library frame.
								fileUploadFrame,
								field = jQuery( this ).prev( 'input' );

								event.preventDefault();

								// Sets up the media library frame.
								fileUploadFrame = wp.media.frames.fileUploadFrame = wp.media(
									{
										title: fileUploadParam.title,
										button: { text:  fileUploadParam.button },
									}
								);

								// Runs when an image is selected.
								fileUploadFrame.on(
									'select', function() {

										// Grabs the attachment selection and creates a JSON representation of the model.
										var media_attachment = fileUploadFrame.state().get( 'selection' ).first().toJSON();

										// Sends the attachment URL to our custom image input field.
										jQuery( field ).val( media_attachment.url );

									}
								);

								// Opens the media library frame.
								fileUploadFrame.open();
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
