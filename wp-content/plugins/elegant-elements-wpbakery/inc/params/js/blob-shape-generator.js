(function ( $ ) {
	"use strict";

	$(
		function () {
			function elegantGenerateBlob() {
				  var percentage11,
				 percentage21,
				 percentage31,
				 percentage41,
				 borderRadius = '';

				  const percentage1 = _.random( 10, 80 );
				  const percentage2 = _.random( 15, 85 );
				  const percentage3 = _.random( 20, 80 );
				  const percentage4 = _.random( 15, 85 );

				  percentage11 = 100 - percentage1;
				  percentage21 = 100 - percentage2;
				  percentage31 = 100 - percentage3;
				  percentage41 = 100 - percentage4;

				  borderRadius = percentage1 + '% ' + percentage11 + '% ' + percentage21 + '% ' + percentage2 + '% / ' + percentage3 + '% ' + percentage4 + '% ' + percentage41 + '% ' + percentage31 + '%';

				  return borderRadius;
			}

				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_blob_shape_generator = {
					init: function() {
						// Blob Shape Generator.
						this.$el.find( 'a.elegant-element-blob-shape-generator-button' ).on(
							'click', function( event ) {
								var blobGeneratorInput = jQuery( this ).closest( '.elegant-element-blob-shape-generator-field' ).find( 'input' ),
								blobShapePreview       = jQuery( this ).next( '.elegant-blob-shape-generator-placeholder-wrapper' ).find( '.elegant-blob-shape-generator-placeholder' ),
								borderRadius           = elegantGenerateBlob();

								// Prevent the default browser action.
								event.preventDefault();

								// Update the shape in preview.
								blobShapePreview.css( 'border-radius', borderRadius );

								// Update input value and trigger the change.
								blobGeneratorInput.val( borderRadius ).trigger( 'change' );
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
