(function($) {
	"use strict";

	jQuery(
		function() {
			/*
			Class used in edit form and editor models to save/render param type
			*/
			window.vc.atts.ee_hotspot = {
				init: function( param ) {
					var $that     = this,
					childSettings = '',
					modelOptions  = {
						shortcode: 'iee_image_hotspot_item',
						parent_id: null
					},
					$hotSpotData  = [];

					// Fix param settings in the design tab.
					this.$el.find( '#vc_edit-form-tab-1 [data-vc-ui-element="panel-shortcode-param"]' ).each(
						function() {
							var $field, param;
							($field = jQuery( this )).data( "vcInitParam" ) || (param = $field.data( "param_settings" ), vc.atts.init.call( $that, param, $field ), $field.data( "vcInitParam", ! 0 ))
						}
					);

					// Retrieve and store child element edit form.
					jQuery.ajax(
						{
							type: 'POST',
							url: window.ajaxurl,
							data: {
								action: 'vc_edit_form',
								tag: 'iee_image_hotspot_item',
								parent_tag: null,
								post_id: vc_post_id,
								_vcnonce: window.vcAdminNonce
							}
						}
					).done(
						function( response ) {
							childSettings = response.replace( '>0', '>' );
						}
					);

					jQuery( 'body' ).on(
						'click', '[data-vc-shortcode="iee_image_hotspot"] .vc_icon-remove', function() {
							jQuery( '#image-hotspot-preview' ).addClass( 'no-img' );
						}
					);

					if ( this.$el.find( '[name="preview"]' ).val().length == 0 ) {
						jQuery( '#image-hotspot-preview' ).addClass( 'no-img' );
					}

					// Set up initial data.
					if ( this.$el.find( '.wpb-textarea.content' ).val().length > 0 ) {

						var hotspotRegex = wp.shortcode.regexp( 'iee_image_hotspot_item' ),
						matches;

						while ( matches = hotspotRegex.exec( this.$el.find( '.wpb-textarea.content' ).val() ) ) {

							var namedAttrs = wp.shortcode.attrs( matches[3] ).named;
							var hotspotObj = namedAttrs;

							$hotSpotData.push( hotspotObj );

						}
					}

					if ( $hotSpotData.length > 0 ) {
						// Retrieve and store child element edit form.
						jQuery.ajax(
							{
								type: 'POST',
								url: window.ajaxurl,
								data: {
									action: 'wpb_edit_hotspot_form',
									tag: 'iee_image_hotspot_item',
									shortcodeItems: $hotSpotData,
									_vcnonce: window.vcAdminNonce
								}
							}
						).done(
							function( response ) {
									jQuery( response ).insertBefore( 'div[data-vc-shortcode-param-name="content"]' );

									var $content = $that.$el.find( '.elegant-hotspot-content' );
									jQuery( '[data-vc-ui-element="panel-shortcode-param"]', $content ).each(
										function() {
											var $field, param;
											($field = jQuery( this )).data( "vcInitParam" ) || (param = $field.data( "param_settings" ), vc.atts.init.call( $that, param, $field ), $field.data( "vcInitParam", ! 0 ))
										}
									);
							}
						);
					}

					// Update preview image.
					this.$el.find( 'input.hotspot_image' ).on(
						'change', function( e ) {
							var imageID = jQuery( this ).val(),
							imageObject = wp.media.model.Attachment.get( imageID ),
							imageURL    = imageObject.attributes.url;

							jQuery( '#image-hotspot-preview img' ).remove();
							jQuery( '#image-hotspot-preview' ).removeClass( 'no-img' ).append( '<img src="' + imageURL + '" alt="preview" />' );
							jQuery( '#image-hotspot-preview input[type="hidden"]' ).attr( 'value', imageURL );
						}
					);

					// Add hotspots that already exist in data.
					if ( $hotSpotData.length > 0 ) {
						jQuery.each(
							$hotSpotData, function( k, v ) {
								var rel = k + 1;

								// Hotspot.
								jQuery( '#image-hotspot-preview' ).append( '<div class="hotspot" data-rel="' + rel + '" style="top: ' + v.hotspot_position_top + '; left: ' + v.hotspot_position_left + ';">' + rel + '</div>' );

							}
						);
					}

					function updateHotSpotData() {
						var hotSpots = jQuery( 'body' ).find( '.elegant-hotspot-content' ),
						// Empty hotspotdata.
						$hotSpotData = [];

						$that.$convertedData = '';

						jQuery.each(
							hotSpots, function() {
								var rel = jQuery( this ).attr( 'data-rel' ),
								$elm    = jQuery( '.hotspot[data-rel="' + rel + '"]' );

								// Update positions.
								$hotSpotData[ rel ] = {
									'left': jQuery( $elm )[0].style.left,
									'top': jQuery( $elm )[0].style.top,
									'shortcode': ''
								}

								jQuery( this ).find( '[data-vc-ui-element="panel-shortcode-param"]' ).each(
									function( i ) {
										var name, value;

										name  = jQuery( this ).attr( 'data-vc-shortcode-param-name' );
										value = jQuery( this ).find( '[name="' + name + '"]' ).val();

										$hotSpotData[ rel  ]['shortcode'] += ' ' + name + '="' + value + '"';
									}
								);

								// Store it / convert to shortcodes.
								$that.$convertedData += '[iee_image_hotspot_item hotspot_position_left="' + $hotSpotData[ rel  ].left + '" hotspot_position_top="' + $hotSpotData[ rel  ].top + '"' + $hotSpotData[ rel  ].shortcode + '][/iee_image_hotspot_item]';
							}
						);

						$that.$el.find( '.wpb-textarea.content' ).val( $that.$convertedData ).trigger( 'change' );
					}

					// Add new with click.
					jQuery( '#image-hotspot-preview:not(#image-hotspot-preview .hotspot)' ).click(
						function( e ) {

							if ( jQuery( '.ui-draggable-dragging' ).length > 0 ) {
								  return;
							}

							var posX    = jQuery( this ).offset().left,
							posY        = jQuery( this ).offset().top,
							parentSizes = {
								height: jQuery( this ).height(),
								width: jQuery( this ).width()
							},
							hotSpots    = jQuery( '#image-hotspot-preview .hotspot' ).length;

							hotSpots++;

							var $hotspot = jQuery( '<div class="hotspot" data-rel="' + hotSpots + '">' + hotSpots + '</div>' );

							$hotspot.css(
								{
									'left': ( ( e.pageX - posX ) / parentSizes.width ) * 100 + '%',
									'top': ( ( e.pageY - posY ) / parentSizes.height ) * 100 + '%'
								}
							);

							jQuery( this ).append( $hotspot );

							jQuery( '<div class="elegant-hotspot-content" data-rel="' + hotSpots + '"><div class="wpb_element_label"><span><a class="delete" href="#" title="Delete Hotspot"><i class="fa fa-trash" aria-hidden="true"></i></a></span> Hotspot <i>Number <span class="num">' + hotSpots + '</span></i></div>' + childSettings + '</div>' ).insertBefore( 'div[data-vc-shortcode-param-name="content"]' );

							var $content = jQuery( 'body' ).find( '.elegant-hotspot-content' );
							jQuery( '[data-vc-ui-element="panel-shortcode-param"]', $content ).each(
								function() {
									var $field, param;
									($field = jQuery( this )).data( "vcInitParam" ) || (param = $field.data( "param_settings" ), vc.atts.init.call( $that, param, $field ), $field.data( "vcInitParam", ! 0 ))
								}
							);

							hotspotMakeDraggable();
							updateHotSpotData();
						}
					);

					// Turn into draggable.
					function hotspotMakeDraggable() {
						jQuery( '#image-hotspot-preview .hotspot:not(.ui-draggable)' ).draggable(
							{
								containment: 'parent',
								stop: function( event, ui ) {
									var $elm        = jQuery( this );
									var pos         = $elm.position(),
										parentSizes = {
											height: $elm.parent().height(),
											width: $elm.parent().width()
									};

									$elm.css( 'top', ( ( pos.top / parentSizes.height ) * 100 ) + '%' ).css( 'left', ( ( pos.left / parentSizes.width ) * 100 ) + '%' );

									updateHotSpotData();
								}
							}
						);
					}

					hotspotMakeDraggable();

					// Remove.
					jQuery( 'body' ).off( 'click.hotspot-delete-event' );
					jQuery( 'body' ).on(
						'click.hotspot-delete-event', '.elegant-hotspot-content a.delete', function( e ) {
							var $confirm = confirm( 'Are you sure you want to delete? There is no undo.' );

							if ( $confirm == false ) {
								return false;
							}

							var $rel = jQuery( this ).parents( '.elegant-hotspot-content' ).attr( 'data-rel' );
							jQuery( this ).parents( '.elegant-hotspot-content' ).remove();
							jQuery( '.hotspot[data-rel="' + $rel + '"]' ).remove();

							hotspotUpdateNumbers();

							// Empty hotspotdata so list can be rebuilt inside next function.
							$hotSpotData = [];

							updateHotSpotData();

							return false;
						}
					);

					// Update numbers.
					function hotspotUpdateNumbers() {
						jQuery( '#image-hotspot-preview .hotspot' ).each(
							function( i ) {
								jQuery( this ).html( i + 1 );
								jQuery( this ).attr( 'data-rel', i + 1 );
							}
						);

						jQuery( '.elegant-hotspot-content' ).each(
							function( i ) {
									jQuery( this ).find( 'span.num' ).html( i + 1 );
									jQuery( this ).attr( 'data-rel', i + 1 );
							}
						);
					}

					// Update when typing content or changing select value.
					jQuery( 'body' ).on(
						'keyup change', '.elegant-hotspot-content .wpb_vc_param_value', function() {
							updateHotSpotData();
						}
					);

					jQuery( document ).on(
						'hotspotSaving', function() {
							updateHotSpotData();
						}
					);
				},

				parse: function ( param ) {
					var previewImage = this.$el.find( '#image-hotspot-preview input[type="hidden"]' ).val();

					// Make sure params changed that doesn't trigger event to update are updated on settings save.
					jQuery( document ).trigger( 'hotspotSaving' );

					return previewImage;
				},

				/**
				 * Used in shortcode saving
				 * Default: '' empty (unchecked)
				 * Can be overwritten by 'std'
				 * @param param
				 * @returns {string}
				 */
				defaults: function(param) {
					return ''; // needed for saving - without this default value for param will be first value in array
				}
			};
		}
	);
})( window.jQuery );
