( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Distortion hover image element view for frontend editor.
				window.InlineShortcodeView_iee_distortion_hover_image = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_distortion_hover_image.__super__.render.call( this );
							return this;
						},

						/*
						* Re-render the video list on settings update.
						*/
						renderDistortionHoverImage: function() {
							jQuery( '#vc_inline-frame' )[ 0 ].contentWindow.jQuery( 'body' ).trigger( 'renderDistortionHoverImage' );
						},
						updated: function () {
							window.InlineShortcodeView_iee_distortion_hover_image.__super__.updated.call( this );
							this.renderDistortionHoverImage();
						}
					}
				);
		}
	);
}( jQuery ) );
