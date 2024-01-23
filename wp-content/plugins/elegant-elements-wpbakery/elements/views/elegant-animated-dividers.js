( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Animated dividers element view for frontend editor.
				window.InlineShortcodeView_iee_animated_dividers = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_animated_dividers.__super__.render.call( this );
							return this;
						},

						/*
						* Re-render the video list on settings update.
						*/
						renderAnimatedDivider: function() {
							jQuery( '#vc_inline-frame' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'renderAnimatedDivider' );
						},
						updated: function () {
							window.InlineShortcodeView_iee_animated_dividers.__super__.updated.call( this );
							this.renderAnimatedDivider();
						}
					}
				);
		}
	);
}( jQuery ) );
