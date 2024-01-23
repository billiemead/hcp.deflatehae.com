<?php
/**
 * Optgroup dropdown param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantSelectOptGroup {

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
				'vc_load_elegant_ee_select_optgroup',
			)
		);
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_select_optgroup() {
		vc_add_shortcode_param(
			'ee_select_optgroup',
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
		$options    = $settings['value'];
		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-select-optgroup ui-select-optgroup eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<select id="<?php echo esc_attr( $param_name ); ?>" name="<?php echo esc_attr( $param_name ); ?>" class="ui-select-optgroup wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>">
					<?php
					foreach ( $options as $opt_group => $option_array ) {
						echo '<optgroup label="' . $opt_group . '">';

						foreach ( $option_array as $option => $option_value ) {
							echo '<option value="' . $option_value . '"' . selected( $value, $option_value ) . '>' . $option . '</option>';
						}

						echo '</optgroup>';
					}
					?>
				</select>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantSelectOptGroup();
