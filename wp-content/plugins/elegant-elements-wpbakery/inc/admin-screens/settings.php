<div class="wrap about-wrap elegant-elements-wrap elegant-elements-settings">

	<?php
		// Display admin page header.
		Elegant_Elements_WPBakery_Admin::header();

		// Get options page settings.
		$settings = get_option( 'elegant_elements_wpbakery_settings', array() );
	?>
	<div class="elegant-elements-important-notice">
		<p class="about-description">
			<?php esc_attr_e( 'Elegant Elements for WPBakery Page Builder comes with unique elements and features for WPBakery Page Builder, which are completely customizable from the WPBakery Page Builder itself. We care about the site performance and your experience of using our plugin. To make it more flexible, we have provided the following options for general and optimization settings.', 'elegant-elements' ); ?>
		</p>
	</div>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<div class="elegant-elements-option">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'Enqueue Combined Styles', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Currently, the styles are enqueued if post content contains the shortcode for the specific element. If you want to use our elements inside widget areas or in custom page templates, please turn this option ON, so we can enqueue the combined styles globally to make it work flawlessly outside post content.', 'elegant-elements' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<div class="elegant-form-radio-button-set ui-buttonset">
						<?php
						$enqueue_combined_scripts = ( function_exists( 'wc' ) ) ? '1' : '0';
						if ( isset( $settings['enqueue_combined_scripts'] ) ) {
							$enqueue_combined_scripts = $settings['enqueue_combined_scripts'];
						}
						?>
						<input type="hidden" class="button-set-value" value="<?php echo esc_attr( $enqueue_combined_scripts ); ?>" name="enqueue_combined_scripts" id="enqueue_combined_scripts" />
						<a data-value="1" class="ui-button buttonset-item<?php echo ( $enqueue_combined_scripts ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'Yes', 'elegant-elements' ); ?></a>
						<a data-value="0" class="ui-button buttonset-item<?php echo ( ! $enqueue_combined_scripts ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'No', 'elegant-elements' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="elegant-elements-option option-section-heading" style="background: #fbfbfb; margin-bottom: 0px;">
			<div class="elegant-elements-option-title" style="width: 100%;margin-bottom: 0px;">
				<h3><?php esc_attr_e( 'Enable / Disable Elements', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'You can enable or disable elements as per your requirements. Just turn them on to enable and off to disable.', 'elegant-elements' ); ?></p>
			</div>
		</div>
		<div class="elegant-elements-option" style="margin-top: 0px; border-bottom-color: #f7f7f7;">
			<div class="elegant-elements-list">
				<?php
				$elements = Elegant_Elements_WPBakery_Admin::$elements;
				if ( is_array( $elements ) && ! empty( $elements ) ) {
					asort( $elements );
					foreach ( $elements as $name => $element ) {
						?>
						<div class="elegant-option-field">
							<div class="elegant-element-name"><?php echo esc_attr( $name ); ?></div>
							<div class="elegant-form-radio-button-set ui-buttonset">
								<?php
								$element_option = 1;
								if ( isset( $settings[ 'enable_' . $element['base'] ] ) ) {
									$element_option = $settings[ 'enable_' . $element['base'] ];
								}
								?>
								<input type="hidden" class="button-set-value" value="<?php echo esc_attr( $element_option ); ?>" name="<?php echo 'enable_' . $element['base']; ?>" id="<?php echo 'enable_' . $element['base']; ?>" />
								<a data-value="1" class="ui-button buttonset-item<?php echo ( $element_option ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'On', 'elegant-elements' ); ?></a>
								<a data-value="0" class="ui-button buttonset-item<?php echo ( ! $element_option ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'Off', 'elegant-elements' ); ?></a>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>

		<div class="elegant-elements-option option-section-heading" style="background: #fbfbfb; margin-bottom: 0px;">
			<div class="elegant-elements-option-title" style="width: 100%;margin-bottom: 0px;">
				<h3><?php esc_attr_e( 'ReCaptcha', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Set the ReCaptcha settings here to use it on your site.', 'elegant-elements' ); ?></p>
			</div>
		</div>

		<div class="elegant-elements-option" style="margin-top: 0px; margin-bottom: 0px; border-bottom-color: #f7f7f7;">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'ReCaptcha Version', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Set the ReCaptcha version you want to use and make sure your keys below match the set version.', 'elegant-elements' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<div class="elegant-form-radio-button-set ui-buttonset">
						<?php
						$recaptcha_version = 'v2';
						if ( isset( $settings['recaptcha_version'] ) ) {
							$recaptcha_version = $settings['recaptcha_version'];
						}
						?>
						<input type="hidden" class="button-set-value" value="<?php echo esc_attr( $recaptcha_version ); ?>" name="recaptcha_version" id="recaptcha_version" />
						<a data-value="v2" class="ui-button buttonset-item<?php echo ( 'v2' === $recaptcha_version ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'V2', 'elegant-elements' ); ?></a>
						<a data-value="v3" class="ui-button buttonset-item<?php echo ( 'v3' === $recaptcha_version ) ? ' ui-state-active' : ''; ?>" href="#"><?php esc_attr_e( 'V3', 'elegant-elements' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="elegant-elements-option" style="margin-top: 0px; margin-bottom: 0px; border-bottom-color: #f7f7f7;">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'ReCaptcha Site Key', 'elegant-elements' ); ?></h3>
				<p class="description"><?php echo sprintf( __( 'Follow the steps in <a href="%s" target="_blank">this article</a> to get the site key.', 'elegant-elements' ), 'https://elegantwpb.com/docs/getting-started/settings/how-to-set-up-recaptcha/' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<div class="elegant-form-textfield">
						<?php
						$recaptcha_public = ( isset( $settings['recaptcha_public'] ) ) ? $settings['recaptcha_public'] : '';
						?>
						<input type="text" class="elegant-form-text-field" value="<?php echo esc_attr( $recaptcha_public ); ?>" name="recaptcha_public" id="recaptcha_public" />
					</div>
				</div>
			</div>
		</div>

		<div class="elegant-elements-option" style="margin-top: 0px;">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'ReCaptcha Secret Key', 'elegant-elements' ); ?></h3>
				<p class="description"><?php echo sprintf( __( 'Follow the steps in <a href="%s" target="_blank">this article</a> to get the secret key.', 'elegant-elements' ), 'https://elegantwpb.com/docs/getting-started/settings/how-to-set-up-recaptcha/' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<div class="elegant-form-textfield">
						<?php
						$recaptcha_private = ( isset( $settings['recaptcha_private'] ) ) ? $settings['recaptcha_private'] : '';
						?>
						<input type="text" class="elegant-form-text-field" value="<?php echo esc_attr( $recaptcha_private ); ?>" name="recaptcha_private" id="recaptcha_private" />
					</div>
				</div>
			</div>
		</div>

		<div class="elegant-elements-option option-section-heading" style="background: #fbfbfb; margin-bottom: 0px;">
			<div class="elegant-elements-option-title" style="width: 100%;margin-bottom: 0px;">
				<h3><?php esc_attr_e( 'Instagram', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Manage your instagram account and the profile picture.', 'elegant-elements' ); ?></p>
			</div>
		</div>
		<div class="elegant-elements-option" style="margin-top: 0px; margin-bottom: 0px;">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'Connect Instagram Account', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Click to connect your Instagram account to use the gallery and profile teaser elements. If you see the Instagram gallery is not loading, please refresh the Instagram token by clicking on the Re-authenticate button.', 'elegant-elements' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<?php
					$api_data     = get_option( 'elegant_elements_wpbakery_instagram_api_data', array() );
					$redirect_url = admin_url( 'admin.php?page=elegant-elements-settings' );

					// Disconnect account if clicked.
					if ( isset( $_GET['disconnect-instagram'] ) ) {
						delete_option( 'elegant_elements_wpbakery_instagram_api_data' );

						if ( isset( $api_data['username'] ) ) {
							// Clear the transients.
							delete_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $api_data['username'] ) );
						}
						$api_data = array();
					}

					// Refresh Instagram API results transient.
					if ( isset( $_GET['refresh-instagram'] ) ) {
						// Clear the transients.
						delete_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $api_data['username'] ) );
					}
					?>
					<div class="elegant-form-radio-button-set ui-buttonset">
						<?php
						if ( isset( $_GET['code'] ) ) {
							$code     = $_GET['code'];
							$code     = base64_decode( $code ); // @codingStandardsIgnoreLine
							$api_data = json_decode( $code, true );

							if ( isset( $api_data['username'] ) ) {
								update_option( 'elegant_elements_wpbakery_instagram_api_data', $api_data );

								// Unschedule the previous refresh token event.
								$timestamp = wp_next_scheduled( 'elegant_instagram_refresh_token' );
								wp_unschedule_event( $timestamp, 'elegant_instagram_refresh_token' );

								// Schedule refresh token event.
								if ( ! wp_next_scheduled( 'elegant_instagram_refresh_token' ) ) {
									wp_schedule_event( time(), 'fifty_days', 'elegant_instagram_refresh_token' );
								}

								// Clear the transient to load images as fresh.
								delete_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $api_data['username'] ) );
							} else {
								esc_attr_e( 'Something went wrong! Please try connecting again.', 'elegant-elements' );
							}
						}

						if ( isset( $api_data['access_token'] ) ) {
							?>
							<div class="connected-dot">
								<p style="display: flex;">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-reactroot="" style="padding-right: 5px;">
										<path fill="none" d="M20.86 14.13C20 14.7 19.56 15.74 19.77 16.76C20.13 18.55 18.55 20.13 16.76 19.77C15.74 19.57 14.7 20 14.13 20.86C13.12 22.38 10.89 22.38 9.88 20.86C9.3 20 8.26 19.56 7.24 19.77C5.45 20.13 3.87 18.55 4.23 16.76C4.43 15.74 4 14.7 3.14 14.13C1.62 13.12 1.62 10.89 3.14 9.88C4 9.3 4.44 8.26 4.23 7.24C3.87 5.45 5.45 3.87 7.24 4.23C8.26 4.44 9.3 4 9.87 3.14C10.88 1.62 13.11 1.62 14.12 3.14C14.7 4 15.74 4.44 16.76 4.23C18.55 3.87 20.13 5.45 19.77 7.24C19.56 8.26 20 9.3 20.86 9.87C22.38 10.88 22.38 13.12 20.86 14.13Z" undefined="1" style="fill: #4caf50;"></path>
										<path stroke-linejoin="round" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1" stroke="#221b38" d="M8 12L10.5 15L16 9" style="stroke: #fff;"></path>
									</svg>
									<?php
									esc_attr_e( 'Connected as: ', 'elegant-elements' );
									echo '&nbsp;<strong><i>' . $api_data['username'] . '</i></strong>';
									?>
								</p>
							</div>
							<p>
								<a href="https://api.instagram.com/oauth/authorize?app_id=2710578392513521&redirect_uri=https://www.infiwebs.com/api/instagram.php&response_type=code&scope=user_profile,user_media&state=<?php echo rawurlencode( $redirect_url ); ?>" class="button button-primary"><?php esc_attr_e( 'Re-authenticate', 'elegant-elements' ); ?></a>
								<a href="<?php echo esc_attr( $redirect_url ); ?>&disconnect-instagram=true" class="button remove-image"><?php esc_attr_e( 'Disconnect', 'elegant-elements' ); ?></a>
							</p>
							<?php
						} else {
							?>
							<p><a href="https://api.instagram.com/oauth/authorize?app_id=2710578392513521&redirect_uri=https://www.infiwebs.com/api/instagram.php&response_type=code&scope=user_profile,user_media&state=<?php echo rawurlencode( $redirect_url ); ?>" class="button button-primary"><?php esc_attr_e( 'Connect to Instagram', 'elegant-elements' ); ?></a></p>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="elegant-elements-option" style="margin-top: 0px;">
			<div class="elegant-elements-option-title">
				<h3><?php esc_attr_e( 'Instagram Profile Image', 'elegant-elements' ); ?></h3>
				<p class="description"><?php esc_attr_e( 'Upload the instagram profile image to be displayed in the Instagram Teaser and Profile Card elements.', 'elegant-elements' ); ?></p>
			</div>
			<div class="elegant-elements-option-input">
				<div class="elegant-option-field">
					<div class="elegant-form-radio-button-set ui-buttonset">
						<?php
						$instagram_profile_image = '';
						if ( isset( $settings['instagram_profile_image'] ) ) {
							$instagram_profile_image = $settings['instagram_profile_image'];
						}
						?>
						<div class="elegant-media-upload-field">
							<?php
							if ( $instagram_profile_image ) {
								?>
								<img src="<?php echo esc_attr( $instagram_profile_image ); ?>" />
								<?php
							}
							?>
						</div>
						<a href="javascript:void(0);" class="button button-primary button-upload-image"><?php esc_attr_e( 'Upload Image', 'elegant-elements' ); ?></a>
						<input type="hidden" class="elegant-media-url" value="<?php echo esc_attr( $instagram_profile_image ); ?>" name="instagram_profile_image" id="instagram_profile_image" />
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="action" value="save_elegant_elements_wpbakery_settings">

		<?php wp_nonce_field( 'elegant_elements_wpbakery_save_settings', 'elegant_elements_wpbakery_save_settings' ); ?>
		<input type="submit" class="button button-primary elegant-elements-save-settings" value="<?php esc_attr_e( 'Save Settings', 'elegant-elements' ); ?>" />
	</form>
	<?php Elegant_Elements_WPBakery_Admin::footer(); ?>
</div>
<style>
.elegant-elements-list {
	display: flex;
	flex: 1;
	flex-wrap: wrap;
}
.elegant-elements-list .elegant-option-field {
	display: inline-flex;
	width: 30%;
	align-items: center;
	justify-content: space-between;
	padding: 10px;
	border: 1px solid #EEEEEE;
	margin-right: 18px;
	margin-bottom: 18px;
}
.elegant-elements-list .elegant-option-field:nth-child(3n) {
	margin-right: 0 !important;
}
.elegant-elements-list .elegant-option-field .elegant-element-name {
	font-size: 14px;
}
</style>
<?php
if ( is_admin() ) {
	?>
	<!-- Remove the instagram code and other parameters from the url to avoid issues. -->
	<script type="text/javascript">
	jQuery( document ).ready( function() {
		window.history.replaceState( null, null, window.location.pathname + '?page=elegant-elements-settings' );
	})
	</script>
	<?php
}
