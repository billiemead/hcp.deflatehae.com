( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Countdown timer element view for frontend editor.
				window.InlineShortcodeView_iee_countdown_timer = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_countdown_timer.__super__.render.call( this );
							this.renderCountdownTimer();
							return this;
						},

						/*
						* Re-render the countdown timer on settings update.
						*/
						renderCountdownTimer: function() {
							jQuery( '#vc_inline-frame' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'renderCountdownTimer' );
						},
						updated: function () {
							window.InlineShortcodeView_iee_countdown_timer.__super__.updated.call( this );
							this.renderCountdownTimer();
						}
					}
				);
		}
	);
}( jQuery ) );
