( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Rotating Text element view for frontend editor.
				window.InlineShortcodeView_iee_rotating_text = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_rotating_text.__super__.render.call( this );
							this.renderRotatingText();
							return this;
						},

						/*
						* Re-render the rotating text on settings update.
						*/
						renderRotatingText: function() {
							jQuery( '#vc_inline-frame' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'renderRotatingText' );
						},
						updated: function () {
							window.InlineShortcodeView_iee_rotating_text.__super__.updated.call( this );
							this.renderRotatingText();
						}
					}
				);
		}
	);
}( jQuery ) );
