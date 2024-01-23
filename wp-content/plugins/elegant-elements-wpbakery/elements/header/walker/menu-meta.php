<?php

/**
 * Adds the menu button fields.
 *
 * @access public
 * @since 1.0
 * @param string $item_id The ID of the menu item.
 * @param object $item    The menu item object.
 * @param int    $depth   The depth of the current item in the menu.
 * @param array  $args    Menu arguments.
 * @param int    $id      Menu ID.
 * @return void.
 */
function elegant_add_menu_button_fields( $item_id, $item, $depth, $args, $id = '' ) {
	$name     = 'menu-item-elegant-megamenu-style';
	$settings = (array) get_post_meta( $item_id, '_elegant_menu_item_settings', true );
	?>
	<div class="elegant-menu-options-container">
		<a class="button button-primary button-large elegant-menu-option-trigger" data-menu-item-id="<?php echo esc_attr( $item_id ); ?>" href="#">
			<?php esc_html_e( 'Elegant Menu', 'elegant-elements' ); ?>
		</a>
	</div>
	<script type="text/javascript">
	var menuItemSettings_<?php echo esc_attr( $item_id ); ?> = <?php echo wp_json_encode( $settings ); ?>
	</script>
	<?php
}

// Add the first level menu style dropdown to the menu fields.
add_action( 'wp_nav_menu_item_custom_fields', 'elegant_add_menu_button_fields', 10, 5 );

/**
 * Adds the mega menu admin script.
 *
 * @access public
 * @since 1.0
 * @return void.
 */
function elegant_mega_menu_admin_script() {
	?>
	<script type="text/javascript">
		jQuery( 'body' ).find( '.elegant-menu-option-trigger' ).click( function( e ) {
			var menuItemID = jQuery( this ).data( 'menu-item-id' ),
				menuItemName = jQuery( this ).closest( '.menu-item-settings' ).find( '.edit-menu-item-title' ).val(),
				menuID = jQuery( '#menu' ).val(),
				url = '<?php echo admin_url( 'post-new.php?post_type=elegant_menu' ); ?>',
				popupContent;

			e.preventDefault();

			if ( jQuery( 'body' ).find( '#elegant-menu-item-' + menuItemID ).length ) {
				jQuery( 'body' ).find( '#elegant-menu-item-' + menuItemID ).show();
			} else {
				url = url + '&elegant_menu_id=' + menuID  + '&elegant_menu_item_id=' + menuItemID;

				popupContent  = '<div id="elegant-menu-item-' + menuItemID + '" class="elegant-mega-menu-customize-wpb-settings-wrapper">';
				popupContent += '<div class="elegant-menu-item-popup-heading"><span><?php esc_attr_e( 'Elegant Menu Settings', 'elegant-elements' ); ?> ( ' + menuItemName + ' ) </span>';
				popupContent += '<div class="elegant-menu-settings-save" onclick="elegantSaveSettings(' + menuItemID + ');" title="<?php esc_attr_e( 'Save Changes', 'elegant-elements' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18 19h1V6.828L17.172 5H16v4H7V5H5v14h1v-7h12v7zM4 3h14l2.707 2.707a1 1 0 0 1 .293.707V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm4 11v5h8v-5H8z"/></svg></div>';
				popupContent += '<div class="elegant-menu-settings-toggle" onclick="elegantToggleSettings(' + menuItemID + ');" title="<?php esc_attr_e( 'Show / Hide Settings', 'elegant-elements' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M6.17 18a3.001 3.001 0 0 1 5.66 0H22v2H11.83a3.001 3.001 0 0 1-5.66 0H2v-2h4.17zm6-7a3.001 3.001 0 0 1 5.66 0H22v2h-4.17a3.001 3.001 0 0 1-5.66 0H2v-2h10.17zm-6-7a3.001 3.001 0 0 1 5.66 0H22v2H11.83a3.001 3.001 0 0 1-5.66 0H2V4h4.17zM9 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm6 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-6 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg></div>';
				popupContent += '<div class="elegant-menu-settings-close" onclick="elegantCloseSettings();" title="<?php esc_attr_e( 'Close this popup', 'elegant-elements' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg></div>';
				popupContent += '</div>';
				popupContent += '<div class="elegant-mega-menu-customize-wpb-iframe-wrapper show-settings">';
				popupContent += '<div class="elegant-mega-menu-loading"><span class="spinner"></span></div>';
				popupContent += elegantGetSettingsPanel( menuItemID );
				popupContent += '<iframe class="elegant-mega-menu-customize-wpb-iframe" src="' + url + '"></iframe>';
				popupContent += '</div>';
				popupContent += '</div>';

				popupContent = jQuery( popupContent );

				jQuery( 'body' ).append( popupContent );
			}
		} );

		// Handle saving of the Settings.
		function elegantSaveSettings( menuItemID ) {
			var previewFrame = jQuery( '#elegant-menu-item-' + menuItemID + ' .elegant-mega-menu-customize-wpb-iframe' )[0].contentWindow,
				formData = jQuery( '#elegant-menu-settings-' + menuItemID ).serializeArray(),
				formObject = {};

			for ( var i = 0; i < formData.length; i++ ) {
				formObject[ formData[ i ]['name'] ] = formData[ i ]['value'];
			}

			previewFrame.jQuery( 'body' ).trigger( 'elegantSaveMenuContent' );

			jQuery( 'body' ).find( '.elegant-mega-menu-loading' ).show();
			jQuery( 'body' ).find( 'iframe.elegant-mega-menu-customize-wpb-iframe' ).css( 'visibility', 'hidden' );

			jQuery.ajax( {
				url: ajaxurl,
				data: formObject,
				method: 'POST',
				dataType: 'html'
			} ).done( function( response ) {
				jQuery( window.parent ).trigger( 'load' );
			} ).fail( function( response ) {
				jQuery( window.parent ).trigger( 'load' );
			} );
		}

		// Toggle settings panel.
		function elegantToggleSettings( menuItemID ) {
			jQuery( '#elegant-menu-item-' + menuItemID + ' .elegant-mega-menu-customize-wpb-iframe-wrapper' ).toggleClass( 'show-settings' );
		}

		// Close settings popup on close button click.
		function elegantCloseSettings() {
			jQuery( '.elegant-mega-menu-customize-wpb-settings-wrapper' ).hide();
		}

		// Generate and return the settings HTML.
		function elegantGetSettingsPanel( itemID ) {
			var output = '<form id="elegant-menu-settings-' + itemID + '" class="elegant-mega-menu-settings"><h3>Settings</h3>',
				settings = window[ 'menuItemSettings_' + itemID ];

			// Add required inputs as hidden.
			output += '<input type="hidden" value="save_elegant_menu_item_settings" name="action" />';
			output += '<input type="hidden" value="' + itemID + '" name="item_id" />';

			// Enable mega menu option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Enable Mega Menu', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<input type="hidden" class="button-set-value" value="' + settings.enable_mega_menu + '" name="enable_mega_menu" id="enable_mega_menu" />\
							<a data-value="1" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' === settings.enable_mega_menu ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Yes', 'elegant-elements' ); ?></a>\
							<a data-value="0" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' !== settings.enable_mega_menu ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'No', 'elegant-elements' ); ?></a>\
						</div>\
					</div>\
				</div>\
			</div>';

			// Mega menu width option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Mega Menu Width', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<input type="hidden" class="button-set-value" value="' + settings.mega_menu_width + '" name="mega_menu_width" id="mega_menu_width" />\
							<a data-value="1" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' === settings.mega_menu_width ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Full', 'elegant-elements' ); ?></a>\
							<a data-value="0" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' !== settings.mega_menu_width ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Auto', 'elegant-elements' ); ?></a>\
						</div>\
					</div>\
				</div>\
			</div>';

			// Hide on mobile option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Hide on Mobile', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<input type="hidden" class="button-set-value" value="' + settings.hide_on_mobile + '" name="hide_on_mobile" id="hide_on_mobile" />\
							<a data-value="1" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' === settings.hide_on_mobile ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Yes', 'elegant-elements' ); ?></a>\
							<a data-value="0" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' !== settings.hide_on_mobile ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'No', 'elegant-elements' ); ?></a>\
						</div>\
					</div>\
				</div>\
			</div>';

			// Hide on desktop option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Hide on Desktop', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<input type="hidden" class="button-set-value" value="' + settings.hide_on_desktop + '" name="hide_on_desktop" id="hide_on_desktop" />\
							<a data-value="1" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' === settings.hide_on_desktop ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Yes', 'elegant-elements' ); ?></a>\
							<a data-value="0" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' !== settings.hide_on_desktop ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'No', 'elegant-elements' ); ?></a>\
						</div>\
					</div>\
				</div>\
			</div>';

			// Disable link option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Disable Menu Link', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<input type="hidden" class="button-set-value" value="' + settings.disable_link + '" name="disable_link" id="disable_link" />\
							<a data-value="1" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' === settings.disable_link ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'Yes', 'elegant-elements' ); ?></a>\
							<a data-value="0" onclick="elegantRadioButtons( event, this );" class="ui-button buttonset-item' + ( ( '1' !== settings.disable_link ) ? ' ui-state-active' : '' ) + '" href="#"><?php esc_attr_e( 'No', 'elegant-elements' ); ?></a>\
						</div>\
					</div>\
				</div>\
			</div>';

			// User display option.
			output += '<div class="elegant-elements-option">\
				<div class="elegant-elements-option-title">\
					<span class="option-label"><?php esc_attr_e( 'Enable for Users', 'elegant-elements' ); ?></span>\
				</div>\
				<div class="elegant-elements-option-input">\
					<div class="elegant-option-field">\
						<div class="elegant-form-radio-button-set ui-buttonset">\
							<select name="enable_for_users" id="enable_for_users">\
								<option value="all" ' + ( ( 'all' === settings.enable_for_users ) ? 'selected' : '' ) + '><?php esc_attr_e( 'All', 'elegant-elements' ); ?></option>\
								<option value="logged-in" ' + ( ( 'logged-in' === settings.enable_for_users ) ? 'selected' : '' ) + '><?php esc_attr_e( 'Logged In', 'elegant-elements' ); ?></option>\
								<option value="logged-out" ' + ( ( 'logged-out' === settings.enable_for_users ) ? 'selected' : '' ) + '><?php esc_attr_e( 'Logged Out', 'elegant-elements' ); ?></option>\
							</select>\
						</div>\
					</div>\
				</div>\
			</div>';

			output += '</form>';

			return output;
		}

		// Display iframe when triggered from iframe content.
		window.onload = function( e ) {
			jQuery( 'body' ).find( '.elegant-mega-menu-loading' ).hide();
			jQuery( 'body' ).find( 'iframe.elegant-mega-menu-customize-wpb-iframe' ).css( 'visibility', 'visible' );
		};

		// Handle radio button set.
		function elegantRadioButtons( event, el ) {
			var value = '',
				$radioSetContainer = jQuery( el ).closest( '.elegant-form-radio-button-set' );

			event.preventDefault();

			$radioSetContainer.find( '.ui-state-active' ).removeClass( 'ui-state-active' );
			jQuery( el ).addClass( 'ui-state-active' );

			value = $radioSetContainer.find( '.ui-state-active' ).data( 'value' );
			$radioSetContainer.find( '.button-set-value' ).val( value ).attr( 'value', value ).trigger( 'change' );
			jQuery( el ).blur();
		}
	</script>
	<style>
	.elegant-mega-menu-customize-wpb-settings-wrapper {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		min-height: 400px;
		background: rgba(0,0,0,0.5);
		z-index: 99999;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}
	.elegant-menu-item-popup-heading {
		width: 80%;
		background: #fbfbfb;
		font-size: 18px;
		line-height: 2em;
		padding: 5px 20px;
		box-sizing: border-box;
		border-bottom: 1px solid #f2f2f2;
		position: relative;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.elegant-menu-settings-save,
	.elegant-menu-settings-toggle,
	.elegant-menu-settings-close {
		width: 47px;
		height: 46px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		position: absolute;
		right: 98px;
		top: 0;
		background: #efefef;
		cursor: pointer;
		border-right: 1px solid #fbfbfb;
	}
	.elegant-menu-settings-toggle {
		right: 49px;
	}
	.elegant-menu-settings-close {
		right: 0;
	}
	.elegant-mega-menu-settings h3 {
		margin-top: 5px;
	}
	.elegant-mega-menu-settings {
		height: 80vh;
		padding: 10px 15px;
		box-sizing: border-box;
		background: #fdfdfd;
		border-right: 1px solid #f2f2f2;
		transform: translateX(-100%);
		transition: all 0.2s ease-in-out;
		width: 380px;
		overflow-y: scroll;
		display: none;
	}
	.show-settings .elegant-mega-menu-settings {
		transform: translateX(0);
		display: block;
	}
	.elegant-mega-menu-customize-wpb-iframe-wrapper {
		width: 80%;
		background: #fff;
		height: 80vh;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		position: relative;
	}
	.elegant-mega-menu-customize-wpb-iframe-wrapper .elegant-mega-menu-loading {
		width: 100%;
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		z-index: 999;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.elegant-mega-menu-customize-wpb-iframe-wrapper .spinner {
		visibility: visible;
	}
	iframe.elegant-mega-menu-customize-wpb-iframe {
		background: #fff;
		width: 100%;
		height: 80vh;
		visibility: hidden;
	}
	.elegant-elements-option {
		margin: 0px;
		padding: 15px 0;
		border-top: 1px solid #f1f1f1;
		display: flex;
		align-items: center;
	}
	.elegant-elements-option .elegant-elements-option-title {
		width: 50%;
	}
	.elegant-elements-option .elegant-elements-option-input {
		width: 50%;
		display: flex;
		align-items: flex-end;
		justify-content: flex-end;
	}
	.elegant-elements-option .ui-button {
		background: #f5f5f5;
		border-bottom-color: #bbb;
		border-radius: 0;
		border: 1px solid #ccc;
		box-shadow: inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);
		color: #333;
		cursor: pointer;
		display: block;
		float: left;
		font-size: 13px;
		height: auto;
		line-height: normal;
		margin-right: -1px;
		margin-bottom: 2px;
		overflow: visible;
		padding: 7px 14px 8px;
		text-decoration: none;
		text-shadow: 0 1px 1px rgba(255,255,255,.75);
		transition: all .2s ease-in-out;
		vertical-align: middle;
		z-index: 1
	}
	.elegant-elements-option .ui-button .ui-button-text {
		display: block;
		line-height: 1.4;
		padding: 0
	}
	.elegant-elements-option .ui-buttonset .ui-state-active {
		background-color: #00a0d2!important;
		background-image: none!important;
		border-color: #0064cd #0064cd #003f81;
		border-color: rgba(0,0,0,.1)!important;
		box-shadow: none!important;
		color: #fff;
		text-shadow: 0 -1px 0 rgba(0,0,0,.25)
	}
	.elegant-elements-option .ui-buttonset .ui-state-active[data-value="0"] {
		background-color: #222!important
	}
	.elegant-elements-option .elegant-form-text-field {
		line-height: 1.75em;
		padding-left: .75em;
		border-radius: 0!important;
		border-bottom-left-radius: 0!important;
		border-bottom-right-radius: 0!important;
		border-top-left-radius: 0!important;
		border-top-right-radius: 0!important;
		background: #f4f2f2!important;
		box-shadow: none!important;
		transition: all .2s ease-in-out;
		border: 1px solid rgba(0,0,0,.05);
		color: #32373c;
		height: 31px;
		width: 100%
	}
	</style>
	<?php
}

// Add script for the menu meta and editing.
add_action( 'admin_footer-nav-menus.php', 'elegant_mega_menu_admin_script' );

/**
 * Add loading spinner.
 *
 * @internal Used as a callback. PLEASE DO NOT CALL THIS FUNCTION DIRECTLY!
 * @see https://developer.wordpress.org/reference/hooks/in_admin_header/
 */
function elegant_add_loading_spinner() {
	global $post;

	if ( ! $post || 'elegant_menu' !== $post->post_type ) {
		return;
	}

	// Prevent autosave on menu item editor.
	wp_dequeue_script( 'autosave' );

	$menu_id = isset( $_GET['elegant_menu_id'] ) ? absint( $_GET['elegant_menu_id'] ) : 0; // @codingStandardsIgnoreLine
	$item_id = isset( $_GET['elegant_menu_item_id'] ) ? absint( $_GET['elegant_menu_item_id'] ) : 0; // @codingStandardsIgnoreLine
	?>
	<div class="elegant-mega-menu-loading">
		<span class="spinner"></span>
	</div>

	<!-- Add menu item id and menu id as inputs. -->
	<input id="elegant_menu_item_id" type="hidden" name="elegant_menu_item_id" value="<?php echo esc_attr( $item_id ); ?>">
	<input id="elegant_menu_id" type="hidden" name="elegant_menu_id" value="<?php echo esc_attr( $menu_id ); ?>">

	<!-- Add scripts -->
	<script type="text/javascript">
	// Let the parent window know the frame is loading.
	jQuery( document ).ready( function() {
		jQuery( window.parent ).trigger( 'load' );
	} );

	// Close the spinner once the window is completely loaded.
	window.onload = function() {
		jQuery( 'body' ).find( '.elegant-mega-menu-loading' ).addClass( 'loaded' );
	};

	// Perform the content saving.
	jQuery( document ).on( 'elegantSaveMenuContent', function() {
		var formData = jQuery( '#post' ).serializeArray(),
			formObject = {};

		for ( var i = 0; i < formData.length; i++ ) {
			formObject[ formData[ i ]['name'] ] = formData[ i ]['value'];
		}

		formObject.action               = 'save_elegant_menu_item';
		formObject.elegant_menu_item_id = jQuery( '#elegant_menu_item_id' ).val();
		formObject.elegant_menu_id      = jQuery( '#elegant_menu_id' ).val();

		jQuery.ajax( {
			url: ajaxurl,
			data: formObject,
			method: 'POST',
			dataType: 'html'
		} ).done( function( response ) {
			jQuery( window.parent ).trigger( 'load' );
		} ).fail( function( response ) {
			jQuery( window.parent ).trigger( 'load' );
		} );
	} );
	</script>
	<style>
	html.wp-toolbar {
		padding: 0;
	}
	body {
		background: #fff;
	}
	.elegant-mega-menu-loading .spinner {
		visibility: visible;
	}
	.elegant-mega-menu-loading {
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #f7f7f7;
		z-index: 99999;
	}
	.elegant-mega-menu-loading.loaded {
		display: none;
	}
	.updated,
	#post-body-content,
	#screen-meta-links,
	#wpadminbar,
	#adminmenumain,
	.notice,
	.wrap h1.wp-heading-inline,
	#post-body.columns-2 #postbox-container-1,
	#wpb_visual_composer .handlediv,
	#wpb_visual_composer h2.hndle.ui-sortable-handle,
	#wpb_visual_composer .vc_navbar,
	.wp-header-end,
	#wpfooter,
	#query-monitor-main,
	.postbox-header {
		display: none !important;
	}
	#wpb_visual_composer {
		display: block !important;
		width: 100%;
		margin: 0;
		border: none;
		box-shadow: none;
	}
	#visual_composer_content,
	#poststuff .vc_welcome.vc_not-empty {
		margin: 0 !important;
	}
	#poststuff,
	#poststuff #post-body.columns-2,
	#wpbody-content {
		padding: 0;
		margin: 0;
	}
	#wpcontent {
		margin: 0 !important;
		padding: 20px !important;
	}
	.wrap {
		margin: 0 !important;
	}
	.vc_welcome-header.vc_welcome-visible-e {
		line-height: 1.3em;
	}
	</style>
	<?php
}
add_action( 'in_admin_header', 'elegant_add_loading_spinner', 0, 0 );

/**
 * Change post content.
 *
 * @internal Used as a callback. PLEASE DO NOT CALL THIS FUNCTION DIRECTLY!
 * @see https://developer.wordpress.org/reference/hooks/edit_form_top/
 * @param object $post Current post object.
 */
function elegant_alter_content( $post ) {
	if ( isset( $_GET['elegant_menu_item_id'] ) && 'elegant_menu' === $post->post_type ) { // @codingStandardsIgnoreLine
		$post_id                       = absint( $_GET['elegant_menu_item_id'] ); // @codingStandardsIgnoreLine
		$GLOBALS['post']->post_content = get_post_meta( $post_id, '_elegant_menu_item_content', true );
	}
}
add_action( 'edit_form_top', 'elegant_alter_content' );
