( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Syntax highlighter element view for frontend editor.
				window.InlineShortcodeView_iee_syntax_highlighter = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_syntax_highlighter.__super__.render.call( this );
							this.renderCountdownTimer();
							return this;
						},

						/*
						* Re-render the syntax highlighter on settings update.
						*/
						renderCountdownTimer: function() {
							jQuery( '#vc_inline-frame' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'renderSyntaxHighlighter' );
						},
						updated: function () {
							window.InlineShortcodeView_iee_syntax_highlighter.__super__.updated.call( this );
							this.renderCountdownTimer();
						}
					}
				);
		}
	);
}( jQuery ) );
