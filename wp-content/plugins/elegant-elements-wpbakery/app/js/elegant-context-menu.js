window.vc || ( window.vc = {} );

(function ( $ ) {
	"use strict";

	$( function () {
		jQuery( window ).on( 'load', function() {
			var ElegantContextMenu = window.wp.Backbone.View.extend( {
				className: 'elegant-context-menu',
	            events: {
					'click .elegant-menu-edit': 'editElement',
					'click .elegant-menu-copy': 'copyElement',
					'click .elegant-menu-paste-after': 'pasteElementAfter',
					'click .elegant-menu-paste-before': 'pasteElementBefore',
					'click .elegant-menu-paste-styles': 'applyStylesOnly',
					'click .elegant-menu-reset-styles': 'resetStyles',
					'click .elegant-menu-append-element': 'appendNewElement',
					'click .elegant-menu-prepend-element': 'prependNewElement'
	            },

				/**
				 * Initialize the context menu.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				initialize: function( args ) {
					this.args      = args;
					this.shortcode = vc.shortcodes.get( args.model_id );
					this.tag       = this.shortcode.attributes.shortcode;
					this.copyData  = {
						data: {
							shortcode: false,
							settings: false,
							tag: false
						}
					};

					// Retrieve stored values.
					this.getCopy();

					// Remove existing context menus.
					this.removeExisting();
				},

				/**
				 * Render the context menu.
				 *
				 * @since 1.0
				 * @return {Object} this
				 */
				render: function() {
					var contextMenu = jQuery( '<ul class="elegant-context-menu-items"></ul>' ),
						elementName = vc.getMapped( this.tag ).name;

					contextMenu.prepend( '<span class="elegant-context-menu-heading">' + elementName + '</span>' );
					contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-edit">Edit</li>' );
					contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-copy">Copy</li>' );
					contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-paste-before">Paste Before</li>' );
					contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-paste-after">Paste After</li>' );

					if ( this.copyData.data.tag === this.tag ) {
						contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-paste-styles">Paste Style</li>' );
					}

					contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-reset-styles">Reset Style</li>' );
					// contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-append-element">Append New Element</li>' );
					// contextMenu.append( '<li class="elegant-context-menu-item elegant-menu-prepend-element">Prepend New Element</li>' );

					this.$el.html( contextMenu );

					return this;
				},

				/**
				 * Edit the current element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				editElement: function() {
					// Open element settings model.
					vc.edit_element_block_view.render( this.shortcode );

					// Remove existing context menus.
					this.removeExisting();
				},

				/**
				 * Copy the current element to clipboard.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				copyElement: function() {
					var $this = this,
						$tempEditor = jQuery( '<textarea>' ),
						content     = '',
						params      = this.shortcode.attributes.params,
						closingTag  = '',
						shortcode   = '',
						elementName = vc.getMapped( this.tag ).name,
						data;

					// Loop through all the element params and generate shortcode string.
					jQuery.each( params, function( name, value ) {
						if ( 'content' === name ) {
							closingTag = value + '[/' + $this.tag + ']';
						} else {
							shortcode += ' ' + name + '="' + value + '"';
						}
					} );

					// Generate shortcode and insert into the shortcode field.
					content = '[' + this.tag + shortcode + ']' + closingTag;

					// Copy to actual clipboard, handy for pasting.
					jQuery( 'body' ).append( $tempEditor );
					$tempEditor.val( content ).select();
					document.execCommand( 'copy' );
					$tempEditor.remove();

					// Store settings and shortcode in browser localStorage.
					data = {
						tag: this.tag,
						shortcode: content,
						settings: JSON.stringify( params )
					};
					this.storeCopy( data );

					if ( 'undefined' !== typeof window.vc.builder ) {
						window.vc.showMessage( elementName + ' Copied Successfully!' );
					}
				},

				/**
				 * Insert the copied element after the current element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				pasteElementAfter: function() {
					var options = {
		                    shortcode: this.copyData.data.tag,
		                    parent_id: this.shortcode.attributes.parent_id,
							params: JSON.parse( this.copyData.data.settings ),
							place_after_id: this.shortcode.attributes.id
		                },
						element,
						shortcode;

					if ( ! this.copyData.data.tag ) {
						this.showErrorMessage( 'No element is copied! Please click on "copy" link in context menu to copy the element.' );
						return false;
					}

					if( 'undefined' === typeof window.vc.builder ) {
						if ( jQuery( '[data-model-id="' + this.shortcode.attributes.id + '"]').prev( '.wpb_content_element' ).length ) {
							options.place_after_id = jQuery( '[data-model-id="' + this.shortcode.attributes.id + '"]').prev( '.wpb_content_element' ).attr( 'data-model-id' );
						}
						options.order = this.shortcode.attributes.order + 1;
						options.root_id = this.shortcode.attributes.root_id;
						element = vc.shortcodes.create( options );
						element.save({
                            order: options.order - 1
                        });
					} else {
						options.order = this.shortcode.attributes.order;
						shortcode     = window.vc.shortcodes.create( options );
						element       = window.vc.builder.create( shortcode );
						element.render();
						vc.events.triggerShortcodeEvents( 'shortcodeView:updated', shortcode );
					}
				},

				/**
				 * Insert the copied elemene before the current element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				pasteElementBefore: function() {
					var options = {
		                    shortcode: this.copyData.data.tag,
		                    parent_id: this.shortcode.attributes.parent_id,
							params: JSON.parse( this.copyData.data.settings )
		                },
						element,
						shortcode;

					if ( ! this.copyData.data.tag ) {
						this.showErrorMessage( 'No element is copied! Please click on "copy" link in context menu to copy the element.' );
						return false;
					}

					if( 'undefined' === typeof window.vc.builder ) {
						options.order = this.shortcode.attributes.order;

						vc.activity = 'prepend';
						element = vc.shortcodes.create( options );
						element.save({
                            order: options.order - 1
                        });
					} else {
						if ( jQuery( '#vc_inline-frame' ).contents().find( '[data-model-id="' + this.shortcode.attributes.id + '"]').prev( '.vc_element' ).length ) {
							options.place_after_id = jQuery( '#vc_inline-frame' ).contents().find( '[data-model-id="' + this.shortcode.attributes.id + '"]').prev( '.vc_element' ).attr( 'data-model-id' );
						}

						options.order = this.shortcode.attributes.order - 1;
						vc.activity = 'prepend';
						shortcode   = window.vc.shortcodes.create( options );
						element     = window.vc.builder.create( shortcode );
						element.render();
						vc.events.triggerShortcodeEvents( 'shortcodeView:updated', shortcode );
					}
				},

				/**
				 * Apply the styles from copied element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				applyStylesOnly: function() {
					var options = {
		                    shortcode: this.copyData.data.tag,
		                    parent_id: this.shortcode.attributes.parent_id,
							params: JSON.parse( this.copyData.data.settings )
		                },
						mappedElement = vc.getMapped( this.tag ),
						shortcodeSettings = this.shortcode.attributes.params;

					if ( options.shortcode !== this.tag ) {
						this.showErrorMessage( 'Copied element does not match with this element!' );
						return false;
					}

					jQuery.each( mappedElement.params, function( index, param ) {
						var dependentParam, dependentParamValue;

						// Set the colors and dimension values from copied element.
						if ( 'colorpicker' === param.type || 'ee_dimensions' === param.type || 'google_fonts' === param.type || 'css_editor' === param.type ) {
							// Set dependency value.
							if ( param.dependency && 'undefined' !== typeof param.dependency.value ) {
								dependentParam      = param.dependency.element;
								dependentParamValue = param.dependency.value[0];
								shortcodeSettings[ dependentParam ] = dependentParamValue;
							}

							shortcodeSettings[ param.param_name ] = options.params[ param.param_name ];
						}
					} );

					this.shortcode.save({
                        params: shortcodeSettings
                    });

					if ( 'undefined' !== typeof window.vc.builder ) {
						vc.setDataChanged();
						vc.builder.update( this.shortcode );
						window.vc.showMessage( 'Styles Applied Successfully!' );
					}
				},

				/**
				 * Reset the element styles from mapped element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				resetStyles: function() {
					var mappedElement = vc.getMapped( this.tag ),
						elementName = mappedElement.name,
						shortcodeSettings = this.shortcode.attributes.params;

					jQuery.each( mappedElement.params, function( index, param ) {
						var dependentParam, dependentParamValue;

						// Reset the colors, typography, and dimension values.
						if ( 'colorpicker' === param.type || 'ee_dimensions' === param.type || 'google_fonts' === param.type ) {
							// Set dependency value.
							if ( param.dependency && 'undefined' !== typeof param.dependency.value ) {
								dependentParam      = param.dependency.element;
								dependentParamValue = param.dependency.value[0];
								shortcodeSettings[ dependentParam ] = dependentParamValue;
							}

							shortcodeSettings[ param.param_name ] = ( 'undefined' !== param.std ) ? param.std : param.value;
						}
					} );

					this.shortcode.save({
                        params: shortcodeSettings
                    });

					if ( 'undefined' !== typeof window.vc.builder ) {
						vc.setDataChanged();
						vc.builder.update( this.shortcode );
						window.vc.showMessage( elementName + ' styles are successfully reset!' );
					}
				},

				/**
				 * Insert new element after the current element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				appendNewElement: function() {
					var shortcode = this.shortcode;

					shortcode.place_after_id = shortcode.attributes.id;

					window.vc.add_element_block_view.render( shortcode );
					window.vc.events.on( 'shortcodes:add', function( model ) {
						if ( shortcode.id === model.attributes.parent_id ) {
							model.save({
								order: shortcode.order + 1,
								place_after_id: shortcode.attributes.id
							});

							model.trigger( 'save' );
							vc.events.triggerShortcodeEvents( 'shortcodeView:updated', model );
							if ( 'undefined' !== typeof window.vc.builder ) {
								vc.builder.update( model );
								vc.setDataChanged();
							}
						}
					} );
				},

				/**
				 * Insert new element before the current element.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				prependNewElement: function() {
					window.vc.activity = "prepend";
					window.vc.add_element_block_view.render( this.shortcode );
				},

				/**
				 * Get stored data.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				getCopy: function() {
					if ( 'undefined' !== typeof Storage ) {
						if ( localStorage.getItem( 'elegantCopyShortcode' ) ) {
							this.copyData.data.tag       = localStorage.getItem( 'elegantCopyShortcodeTag' );
							this.copyData.data.shortcode = localStorage.getItem( 'elegantCopyShortcode' );
							this.copyData.data.settings  = localStorage.getItem( 'elegantCopySettings' );
						}
					}
				},

				/**
				 * Stored copy data.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				storeCopy: function( data ) {
					if ( 'undefined' !== typeof Storage ) {
						localStorage.setItem( 'elegantCopyShortcodeTag', data.tag );
						localStorage.setItem( 'elegantCopyShortcode', data.shortcode );
						localStorage.setItem( 'elegantCopySettings', data.settings );
						this.getCopy();
					}
				},

				/**
				 * Removes the existing context menus.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				removeExisting: function() {
					jQuery( 'body' ).find( '.elegant-context-menu' ).remove();
				},

				/**
				 * Displays error message on top.
				 *
				 * @since 1.0
				 * @return {void}
				 */
				showErrorMessage: function( message ) {
					var $message = jQuery( '<div class="vc_panel-message type-error" style="z-index: 99999;margin-top: 18px;">' + message + '</div>' ).prependTo( jQuery( 'body' ) );
					$message.fadeIn( 500 ), vc.message_timeout = window.setTimeout( function() {
						$message.slideUp( 500, function() {
							jQuery( this ).remove()
						} ), vc.message_timeout = !1
					}, 5500 );
				}
	        } );

			// Remove context menu on side click.
			jQuery( vc.frame_window ).on( 'click', function( event ) {
				jQuery( 'body' ).find( '.elegant-context-menu' ).remove();
			} );

			// Display context menu if clicked on element on frontend.
			jQuery( vc.frame_window ).contextmenu( function( event ) {
				var element = jQuery( event.target ).closest( '.vc_element' ),
					model_id = element.attr( 'data-model-id' ),
					element_tag = element.attr( 'data-tag' ),
					top = event.clientY + 56,
					left = event.clientX,
					contextMenuView,
					contextMenu;

				if ( model_id && 'vc_row' !== element_tag && 'vc_column' !== element_tag ) {
					event.preventDefault();
				} else {
					jQuery( 'body' ).find( '.elegant-context-menu' ).remove();
					return;
				}

				contextMenuView = new ElegantContextMenu( {
					model_id: model_id
				} );

				contextMenu = contextMenuView.render().el;
				top = jQuery( contextMenu ).outerHeight() + top;
				jQuery( contextMenu ).css( { top: top, left: left } );

				jQuery( 'body' ).append( contextMenu );
			} );

			// Remove context menu on side click.
			jQuery( 'body' ).on( 'click', function( event ) {
				jQuery( 'body' ).find( '.elegant-context-menu' ).remove();
			} );

			// Display context menu if clicked on element on backend.
			jQuery( '.wpb_content_element' ).contextmenu( function( event ) {
				var element = jQuery( event.target ).closest( '.wpb_content_element' ),
					model_id = element.attr( 'data-model-id' ),
					element_tag = element.attr( 'data-tag' ),
					top = event.pageY,
					left = event.pageX,
					contextMenuView,
					contextMenu;

				if ( model_id && 'vc_row' !== element_tag && 'vc_column' !== element_tag ) {
					event.preventDefault();
				} else {
					jQuery( 'body' ).find( '.elegant-context-menu' ).remove();
					return;
				}

				contextMenuView = new ElegantContextMenu( {
					model_id: model_id
				} );

				contextMenu = contextMenuView.render().el;
				jQuery( contextMenu ).css( { top: top, left: left } );

				jQuery( 'body' ).append( contextMenu );
			} );
		} );
	} );
} ) ( window.jQuery );
