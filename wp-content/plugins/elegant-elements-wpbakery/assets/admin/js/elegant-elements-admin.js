( function() {

	'use strict';

    jQuery( document ).ready( function() {
        var formModified = false,
			// Uploading files.
			file_frame, attachment;

        // Handle the radio button.
        jQuery( '.ui-buttonset .ui-button' ).on( 'click', function( e ) {
            e.preventDefault();

            jQuery( this ).parent().find( '.button-set-value' ).val( jQuery( this ).data( 'value' ) ).trigger( 'change' );
            jQuery( this ).parent().find( '.ui-button' ).removeClass( 'ui-state-active' );
            jQuery( this ).addClass( 'ui-state-active' );
        } );

        // Handle unsaved changes warning.
        jQuery( '.elegant-elements-settings form *' ).change( function() {
            formModified = true;
        } );

        // Set flag to false if form is being submitted.
        jQuery( '.elegant-elements-settings form' ).submit( function() {
            formModified = false;
        } );

        window.onbeforeunload = confirmExit;
        function confirmExit() {
            if ( formModified ) {
                return '';
            }
        }

		// Handle media upload.
		jQuery( '.button-upload-image' ).on( 'click', function( event ) {
			var $this = jQuery( this );

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media( {
				title: 'Instagram Profile Image',
				button: {
					text: 'Set Image'
				},
				multiple: false  // Set to true to allow multiple files to be selected.
			} );

			// When the file is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one file from the uploader.
				attachment = file_frame.state().get( 'selection' ).first().toJSON();
				$this.next( '.elegant-media-url' ).val( attachment.url ).trigger( 'change' );
				$this.prev( '.elegant-media-upload-field' ).html( '<img src="' + attachment.url + '">' );
			} );

			// Finally, open the modal
			file_frame.open();
		} );
    } );
}( jQuery ) );
