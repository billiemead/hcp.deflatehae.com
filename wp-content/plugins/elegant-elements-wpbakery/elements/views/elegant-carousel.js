( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Animated dividers element view for frontend editor.
				window.InlineShortcodeView_iee_carousel = window.InlineShortcodeViewContainer.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {

							window.InlineShortcodeView_iee_carousel.__super__.render.call( this );
							this.content();

							return this;
						},
						addControls: function () {
							this.$controls = jQuery( '<div class="no-controls"></div>' );
							this.$controls.appendTo( this.$el );

							return this;
						}
					}
				);
		}
	);
}( jQuery ) );
