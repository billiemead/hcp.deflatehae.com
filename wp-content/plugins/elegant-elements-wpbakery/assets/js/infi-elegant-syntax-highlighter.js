( function( jQuery ) {
	'use strict';
	var elegantSyntaxHighlighter = function( e ) {
		var i, a;
		( a = {} ).readOnly = 'undefined' !== jQuery( e ).data( 'readonly' ) && jQuery( e ).data( 'readonly' ),
			a.lineNumbers = 'undefined' !== jQuery( e ).data( 'linenumbers' ) && jQuery( e ).data( 'linenumbers' ),
			a.lineWrapping = 'undefined' !== jQuery( e ).data( 'linewrapping' ) && jQuery( e ).data( 'linewrapping' ),
			a.theme = 'undefined' !== jQuery( e ).data( 'theme' ) ? jQuery( e ).data( 'theme' ) : 'default',
			a.mode = 'undefined' !== jQuery( e ).data( 'mode' ) ? jQuery( e ).data( 'mode' ) : 'text/html';

		i = wp.CodeMirror.fromTextArea( e, a );
		jQuery( e ).addClass( 'code-mirror-initialized' ),
			i.setSize( '100%', 'auto' ),
			jQuery( document ).trigger( 'resize' ),
			jQuery( e ).parents( '.elegant-syntax-highlighter-container' ).css( 'opacity', '1' );
	};

	jQuery( document ).on( 'ready renderSyntaxHighlighter', function() {
		var textAreas = jQuery( '.elegant-syntax-highlighter-textarea' );

		jQuery.each( textAreas, function( i, textArea ) {
			if ( ! jQuery( textArea ).hasClass( 'code-mirror-initialized' ) ) {
				setTimeout( function() {
					elegantSyntaxHighlighter( textArea );
				}, 200 );
			}
		} );

		jQuery( '.elegant-syntax-highlighter-copy-code-title' ).on( 'click', function() {
			var element = jQuery( this ),
				i = document.getElementById( jQuery( this ).data( 'id' ) );

			element.parent( '.elegant-syntax-highlighter-copy-code' ).addClass( 'syntax-highlighter-copying' ), jQuery( i ).removeAttr( 'style' ).css( {
				position: 'absolute',
				left: '-1000%'
			} );

			jQuery( i ).select(), document.execCommand( 'Copy', !1, null ), setTimeout( function() {
				element.parent( '.elegant-syntax-highlighter-copy-code' ).removeClass( 'syntax-highlighter-copying' );
			}, 200 );
		} );
	} );
}( jQuery ) );
