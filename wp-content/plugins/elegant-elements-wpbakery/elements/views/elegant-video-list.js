( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Video list element view for frontend editor.
				window.InlineShortcodeView_iee_video_list = window.InlineShortcodeView.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							window.InlineShortcodeView_iee_video_list.__super__.render.call( this );
							return this;
						},

						/*
						* Re-render the video list on settings update.
						*/
						elegantVideoListActivateFirstItem: function() {
							var firstListItem = this.$el.find( '.elegant-video-list-item:first-child' ),
							embedUrl          = firstListItem.find( '.elegant-video-list-item-title' ).data( 'embed-url' ),
							videoContainer    = this.$el.find( '.elegant-video-list-video-container' ),
							videoWidth        = videoContainer.outerWidth(),
							embedCode         = '<iframe src="' + embedUrl + '" style="width: ' + videoWidth + 'px;height:calc( ' + videoWidth + 'px / 1.78 )"></iframe>';

							firstListItem.addClass( 'active-item' );
							videoContainer.html( embedCode );
						},
						updated: function () {
							window.InlineShortcodeView_iee_video_list.__super__.updated.call( this );
							this.elegantVideoListActivateFirstItem();
						}
					}
				);
		}
	);
}( jQuery ) );
