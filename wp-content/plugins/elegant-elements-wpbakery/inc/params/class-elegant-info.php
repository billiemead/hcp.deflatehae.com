<?php
/**
 * Information param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantInfoParam {

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
				'vc_load_elegant_ee_info',
			)
		);
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_info() {
		vc_add_shortcode_param(
			'ee_info',
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
		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-info ui-info-text eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<div class="ui-info-text"><?php echo esc_attr( $value ); ?></div>
				<input type="hidden" id="<?php echo esc_attr( $param_name ); ?>" name="<?php echo esc_attr( $param_name ); ?>" value="" class="wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>" />
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantInfoParam();
