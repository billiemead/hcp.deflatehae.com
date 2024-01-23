<?php
/**
 * Range Slider param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantRangeSlider {

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
				'vc_load_elegant_ee_range_slider',
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
		wp_enqueue_script( 'eewpb-range-slider-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/range-slider.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-range-slider-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/range-slider.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_range_slider() {
		vc_add_shortcode_param(
			'ee_range_slider',
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
		$output = '';
		$min    = isset( $settings['min'] ) ? $settings['min'] : 0;
		$max    = isset( $settings['max'] ) ? $settings['max'] : 100;
		$step   = isset( $settings['step'] ) ? $settings['step'] : 1;
		$value  = ( isset( $settings['value'] ) && '' === $value ) ? $settings['value'] : $value;

		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-range-slider ui-rangeslider eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<input type="number" class="ui-range-slider-value" value="<?php echo esc_attr( $value ); ?>" style="padding: 4px 0px 4px 7px;"/>
				<input
					type="range"
					id="<?php echo esc_attr( $param_name ); ?>"
					name="<?php echo esc_attr( $param_name ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					min="<?php echo esc_attr( $min ); ?>"
					max="<?php echo esc_attr( $max ); ?>"
					step="<?php echo esc_attr( $step ); ?>"
					class="ui-range-slider wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>"
				/>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantRangeSlider();
