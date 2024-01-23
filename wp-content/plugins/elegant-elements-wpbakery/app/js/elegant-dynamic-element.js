(function ( $ ) {
	"use strict";

	$( function () {
		window.elegantCloseDynamicContent = function() {
			jQuery( 'body' ).find( '.elegant-dynamic-content-popup-wrapper' ).remove();
		};

		window.elegantGenerateDynamicContentPopup = function( value, fieldType ) {
			var dynamicContentPopup = '',
				availableFields = window.elegantDynamicFields,
				selected = '';

			// Generate dynamic content popup.
			dynamicContentPopup += '<div class="elegant-dynamic-content-popup-wrapper">';
			dynamicContentPopup += '<div class="elegant-dynamic-content-popup">';
			dynamicContentPopup += '<div class="elegant-dynamic-content-popup-header"><h3>Dynamic Content</h3><div class="elegant-dynamic-content-popup-close">';
			dynamicContentPopup += '<a href="javascript:void(0);" onclick="elegantCloseDynamicContent();"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-reactroot="">\
									<path stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1" stroke="#221b38" d="M6.15 17.46C5.95 17.26 5.95 16.95 6.15 16.75L16.75 6.15C16.95 5.95 17.26 5.95 17.46 6.15C17.66 6.35 17.66 6.66 17.46 6.86L6.85 17.46C6.66 17.66 6.34 17.66 6.15 17.46Z"></path>\
									<path stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1" stroke="#221b38" d="M6.15 6.15C6.35 5.95 6.66 5.95 6.86 6.15L17.47 16.76C17.67 16.96 17.67 17.27 17.47 17.47C17.27 17.67 16.96 17.67 16.76 17.47L6.15 6.85C5.95 6.66 5.95 6.34 6.15 6.15Z"></path>\
									</svg></a>';
			dynamicContentPopup += '</div></div>';

			dynamicContentPopup += '<div class="elegant-dynamic-content-popup-body">';
			dynamicContentPopup += '<select class="elegant-dynamic-content-dropdown">';

			dynamicContentPopup += '<optgroup label="Page">';
			jQuery.each( availableFields.post, function( option, title ) {
				selected = ( '{{' + option + '}}' === value ) ? 'selected' : '';
				dynamicContentPopup += '<option ' + selected + ' value="{{' + option + '}}">' + title + '</option>';
			} );
			dynamicContentPopup += '</optgroup>';

			if ( 'undefined' !== typeof availableFields.acf ) {
				dynamicContentPopup += '<optgroup label="ACF">';
				jQuery.each( availableFields.acf, function( option, title ) {
					selected = ( '{{acf:' + option + '}}' === value ) ? 'selected' : '';
					dynamicContentPopup += '<option' + selected + '  value="{{acf:' + option + '}}">' + title + '</option>';
				} );
				dynamicContentPopup += '</optgroup>';
			}

			dynamicContentPopup += '<optgroup label="Custom Fields">';
			jQuery.each( availableFields.meta, function( option, title ) {
				selected = ( '{{meta:' + option + '}}' === value ) ? 'selected' : '';
				dynamicContentPopup += '<option ' + selected + ' value="{{meta:' + option + '}}">' + title + '</option>';
			} );
			dynamicContentPopup += '</optgroup>';

			dynamicContentPopup += '</select>';
			dynamicContentPopup += '<p>Choose the field you want to inherit the content from.</p>';
			dynamicContentPopup += '</div>';

			dynamicContentPopup += '<div class="elegant-dynamic-content-popup-footer">';
			dynamicContentPopup += '<button class="elegant-dynamic-content-save elegant-save-' + fieldType + ' button button-primary" onclicks="elegantSaveDynamicContent();"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-reactroot="">\
				<path fill="none" d="M1.29289 12.0329C1.68342 11.6424 2.31658 11.6424 2.70711 12.0329L8.83711 18.1629C9.22763 18.5534 9.22763 19.1866 8.83711 19.5771C8.44658 19.9676 7.81342 19.9676 7.42289 19.5771L1.29289 13.4471C0.902369 13.0566 0.902369 12.4234 1.29289 12.0329Z" clip-rule="evenodd" fill-rule="evenodd" undefined="1"></path>\
				<path fill="none" d="M22.7071 4.29289C23.0976 4.68342 23.0976 5.31658 22.7071 5.70711L8.83711 19.5771C8.44659 19.9676 7.81342 19.9676 7.4229 19.5771C7.03237 19.1866 7.03237 18.5534 7.4229 18.1629L21.2929 4.29289C21.6834 3.90237 22.3166 3.90237 22.7071 4.29289Z" clip-rule="evenodd" fill-rule="evenodd" undefined="1"></path>\
				</svg></button>';
			dynamicContentPopup += '</div>';

			dynamicContentPopup += '</div>';
			dynamicContentPopup += '</div>';

			return dynamicContentPopup;
		};

		/**
		* Alter the textfield param to include dynamic data.
		*/
		window.vc.atts.textfield = {
			init: function() {
				this.$el.find( '.dynamic-param-trigger-input' ).off().click( function( event ) {
					var triggerEl = jQuery( this ),
						value = jQuery( this ).prev( '.wpb-textinput' ).val(),
						availableFields = window.elegantDynamicFields,
						dynamicContentPopup = window.elegantGenerateDynamicContentPopup( value, 'input' ),
						inputField = jQuery( this ).prev( 'input' );

					event.preventDefault();

					jQuery( this ).addClass( 'active' );
					jQuery( 'body' ).find( '.elegant-dynamic-content-popup' ).remove();

					jQuery( 'body' ).append( dynamicContentPopup );

					jQuery( 'body .elegant-save-input' ).on( 'click', function() {
						var dynamicFieldValue = jQuery( 'body' ).find( '.elegant-dynamic-content-dropdown' ).val();

						// Set the value.
						inputField.val( dynamicFieldValue );

						// Close the popup.
						elegantCloseDynamicContent();
					} );

				} );
			},

			/**
			 * Used in shortcode saving
			 * Default: '' empty (unchecked)
			 * Can be overwritten by 'std'
			 * @param param
			 * @returns {string}
			 */
			defaults: function ( param ) {
				return param.default; // needed for saving - without this default value for param will be first value in array
			}
		};

		/**
		* Alter the textarea param to include dynamic data.
		*/
		window.vc.atts.textarea = {
			init: function() {
				this.$el.find( '.dynamic-param-trigger-textarea' ).off().click( function( event ) {
					var triggerEl = jQuery( this ),
						value = jQuery( this ).prev( '.wpb-textarea' ).val(),
						availableFields = window.elegantDynamicFields,
						dynamicContentPopup = window.elegantGenerateDynamicContentPopup( value, 'textarea' ),
						textarea = jQuery( this ).prev( 'textarea' ),
						selected = '';

					event.preventDefault();

					jQuery( this ).addClass( 'active' );
					jQuery( 'body' ).find( '.elegant-dynamic-content-popup' ).remove();

					jQuery( 'body' ).append( dynamicContentPopup );

					jQuery( 'body .elegant-save-textarea' ).on( 'click', function() {
						var dynamicFieldValue = jQuery( 'body' ).find( '.elegant-dynamic-content-dropdown' ).val();

						// Set the value.
						textarea.text( dynamicFieldValue ).trigger( 'change' );

						// Close the popup.
						elegantCloseDynamicContent();
					} );

				} );
			},

			/**
			 * Used in shortcode saving
			 * Default: '' empty (unchecked)
			 * Can be overwritten by 'std'
			 * @param param
			 * @returns {string}
			 */
			defaults: function ( param ) {
				return param.default; // needed for saving - without this default value for param will be first value in array
			}
		};
	} );
} )( window.jQuery );
