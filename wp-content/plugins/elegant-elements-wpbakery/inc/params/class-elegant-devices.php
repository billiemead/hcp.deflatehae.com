<?php
/**
 * Devices param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantDevices {

	/**
	 * The Constructor.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function __construct() {
		add_action(
			'vc_load_default_params',
			array(
				$this,
				'vc_load_elegant_ee_devices',
			)
		);

		add_action(
			'vc_backend_editor_enqueue_js_css',
			array(
				$this,
				'vc_enqueue_editor_scripts_backend',
			)
		);

		add_action(
			'vc_frontend_editor_enqueue_js_css',
			array(
				$this,
				'vc_enqueue_editor_scripts_frontend',
			)
		);
	}

	/**
	 * Enqueue the scripts for this param in backend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_backend() {
		wp_enqueue_script( 'eewpb-devices-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/devices.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-devices-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/devices.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_devices() {
		vc_add_shortcode_param(
			'ee_devices',
			array(
				$this,
				'render',
			)
		);
	}

	/**
	 * Checkbox shortcode attribute type.
	 *
	 * @param array  $settings Param settings array.
	 * @param string $value    Saved element param value.
	 *
	 * @return string - html string.
	 */
	public function render( $settings, $value ) {
		$output     = '';
		$values     = $settings['value'];
		$param_name = $settings['param_name'];
		$min        = $settings['min'];
		$max        = $settings['max'];

		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-devices ui-devices ui-dimensions eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<?php
				// Output all device fields.
				$values = explode( ',', $value );
				$count  = count( $values );

				$device_desktop = $values[0];
				$device_phone   = ( isset( $values[1] ) ) ? $values[1] : '';
				$device_tablet  = ( isset( $values[2] ) ) ? $values[2] : '';
				?>
				<div class="elegant-device device-desktop">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M4 16h16V5H4v11zm9 2v2h4v2H7v-2h4v-2H2.992A.998.998 0 0 1 2 16.993V4.007C2 3.451 2.455 3 2.992 3h18.016c.548 0 .992.449.992 1.007v12.986c0 .556-.455 1.007-.992 1.007H13z" fill="rgba(255,255,255,1)"/></svg></span>
					<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" name="<?php echo esc_attr( $param_name ); ?>_desktop" id="<?php echo esc_attr( $param_name ); ?>_desktop" value="<?php echo esc_attr( $device_desktop ); ?>" autocomplete="off" />
				</div>
				<div class="elegant-device device-tablet">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M6 4v16h12V4H6zM5 2h14a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm7 15a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" fill="rgba(255,255,255,1)"/></svg></span>
					<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" name="<?php echo esc_attr( $param_name ); ?>_tablet" id="<?php echo esc_attr( $param_name ); ?>_tablet" value="<?php echo esc_attr( $device_phone ); ?>" autocomplete="off" />
				</div>
				<div class="elegant-device device-phone">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M7 4v16h10V4H7zM6 2h12a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm6 15a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" fill="rgba(255,255,255,1)"/></svg></span>
					<input type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" name="<?php echo esc_attr( $param_name ); ?>_phone" id="<?php echo esc_attr( $param_name ); ?>_phone" value="<?php echo esc_attr( $device_tablet ); ?>" autocomplete="off" />
				</div>
				<input class="wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>" type="hidden" name="<?php echo esc_attr( $param_name ); ?>" id="<?php echo esc_attr( $param_name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantDevices();
