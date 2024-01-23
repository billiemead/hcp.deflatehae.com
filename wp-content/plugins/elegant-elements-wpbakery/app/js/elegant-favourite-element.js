(function ( $ ) {
	"use strict";

	$( function () {
		jQuery( document ).ready( function() {
			// Add favourite star to the elements.
			jQuery( '.vc_el-container' ).append( '<span class="elegant-fav" title="Add to Favourites"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 17l-5.878 3.59 1.598-6.7-5.23-4.48 6.865-.55L12 2.5l2.645 6.36 6.866.55-5.231 4.48 1.598 6.7z"/></svg></span>' );

			// Loop through the favourite elements and make the star active.
			jQuery.each( window.elegantFavouriteElements, function() {
				jQuery( 'li[data-element="' + this + '"]' ).find( '.elegant-fav' ).addClass( 'is-favourite' ).attr( 'title', 'Remove from Favourites' );
			} );

			// Handle adding the element to favourite.
			jQuery( 'body' ).find( '.elegant-fav' ).on( 'click', function() {
			    var $this = jQuery( this ),
					element = $this.parents( 'li' ).attr( 'data-element' ),
					data = {
						element_tag: element,
						action: 'eewpb_add_to_favourite'
					};

				jQuery.ajax( {
					url: ajaxurl,
					type: 'POST',
					data: data,
					success: function( response ) {
						if ( 'added' === response ) {
							$this.addClass( 'is-favourite' );
							$this.attr( 'title', 'Remove from Favourites' );
						} else if ( 'removed' === response ) {
							$this.removeClass( 'is-favourite' );
							$this.attr( 'title', 'Add to Favourites' );
						}
					}
				} );
			} );
		} );
	} );
} ) ( window.jQuery );
