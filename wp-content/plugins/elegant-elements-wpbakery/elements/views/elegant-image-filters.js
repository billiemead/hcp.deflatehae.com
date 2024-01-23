( function() {

	'use strict';

	jQuery( document ).ready(
		function() {
				// Image filter view for frontend editor.
				window.InlineShortcodeView_iee_image_filters = window.InlineShortcodeViewContainer.extend(
					{
						// Render called every time when some of attributes changed.
						render: function () {
							// Set the first filter as active.
							this.$el.find( '.elegant-image-filters-navigation .elegant-image-filters-navigation-item[data-filter-all="true"]' ).addClass( 'filter-active' );

							window.InlineShortcodeView_iee_image_filters.__super__.render.call( this );

							_.bindAll( this, 'generateFilterNavigation' );

							return this;
						},

						/*
						* Re-generate the navigation items.
						*/
						generateFilterNavigation: function() {
							var filterImages = this.$el.find( '.elegant-image-filter-item[data-nav-items]' ),
							navItems     = this.$el.find( '.elegant-image-filters-navigation' ),
							navItemList  = [];

							// Remove all existing navigation items except the "all" filter.
							this.$el.find( '.elegant-image-filters-navigation .elegant-image-filters-navigation-item:not(\'[data-filter-all="true"]\')' ).remove();

							// Set the first filter as active.
							this.$el.find( '.elegant-image-filters-navigation .elegant-image-filters-navigation-item[data-filter-all="true"]' ).addClass( 'filter-active' );

							if ( 0 !== filterImages.length ) {
								jQuery.each(
									filterImages, function( index, filterImage ) {
										var navItemData = jQuery( filterImage ).attr( 'data-nav-items' ),
										navigationItems = navItemData.split( ',' );

										jQuery.each(
											navigationItems, function( index2, navItem ) {
												var navItemClass = navItem.toLowerCase().trim();

												if ( 'undefined' === typeof navItemList[ navItemClass ] ) {
													navItems.append( '<li class="elegant-image-filters-navigation-item" role="menuitem"><a href="#" data-filter=".' + navItemClass + '">' + navItem + '</a></li>' );
												}

												navItemList[ navItemClass ] = navItem;
											}
										);
									}
								);
							}
						},
						updated: function () {
							window.InlineShortcodeView_iee_image_filters.__super__.updated.call( this );
							_.defer( this.generateFilterNavigation );
						}
					}
				);

				// Image filter view for frontend editor.
				window.InlineShortcodeView_iee_filter_image = window.InlineShortcodeView.extend(
					{
						render: function () {
							this.$el.addClass( 'elegant-image-filter-item' );

							window.InlineShortcodeView_iee_filter_image.__super__.render.call( this );

							_.bindAll( this, 'generateFilterNavigation' );

							return this;
						},

						/*
						* Re-generate the navigation items.
						*/
						generateFilterNavigation: function() {
							var filterImages = this.$el.parents( '.elegant-image-filters-wrapper' ).find( '.elegant-image-filter-item[data-nav-items]' ),
							navItems     = this.$el.parents( '.elegant-image-filters-wrapper' ).find( '.elegant-image-filters-navigation' ),
							navItemList  = [];

							// Remove all existing navigation items except the "all" filter.
							this.$el.parents( '.elegant-image-filters-wrapper' ).find( '.elegant-image-filters-navigation .elegant-image-filters-navigation-item:not(\'[data-filter-all="true"]\')' ).remove();

							// Set the first filter as active.
							this.$el.parents( '.elegant-image-filters-wrapper' ).find( '.elegant-image-filters-navigation .elegant-image-filters-navigation-item[data-filter-all="true"]' ).addClass( 'filter-active' );

							if ( 0 !== filterImages.length ) {
								jQuery.each(
									filterImages, function( index, filterImage ) {
										var navItemData = jQuery( filterImage ).attr( 'data-nav-items' ),
										navigationItems = navItemData.split( ',' );

										jQuery.each(
											navigationItems, function( index2, navItem ) {
												var navItemClass = navItem.toLowerCase().trim();

												if ( 'undefined' === typeof navItemList[ navItemClass ] ) {
													navItems.append( '<li class="elegant-image-filters-navigation-item" role="menuitem"><a href="#" data-filter=".' + navItemClass + '">' + navItem + '</a></li>' );
												}

												navItemList[ navItemClass ] = navItem;
											}
										);
									}
								);
							}
						},
						updated: function () {
							window.InlineShortcodeView_iee_filter_image.__super__.updated.call( this );
							_.defer( this.generateFilterNavigation );
						}
					}
				);
		}
	);
}( jQuery ) );
