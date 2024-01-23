<?php
/**
 * Hotspot param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantHotSpot {

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
				'vc_load_elegant_ee_hotspot',
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
		wp_enqueue_script( 'eewpb-hotspot-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/hotspot.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-hotspot-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/hotspot.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_hotspot() {
		vc_add_shortcode_param(
			'ee_hotspot',
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
		$value      = ( isset( $settings['value'] ) && '' === $value ) ? $settings['value'] : $value;
		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-hotspot ui-hotspot eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<div id="image-hotspot-preview">
					<input
						name="<?php echo esc_attr( $param_name ); ?>"
						type="hidden"
						class="wpb_vc_param_value <?php echo esc_attr( $param_name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
					/>
					<?php
					if ( ! empty( $value ) ) {
						?>
						<img src="<?php echo esc_attr( $value ); ?>" alt="preview" />
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantHotSpot();
