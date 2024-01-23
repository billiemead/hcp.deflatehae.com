<?php
/**
 * Checkbox Button Set param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantCheckboxButtonSet {

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
				'vc_load_elegant_ee_checkbox_button_set',
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
		wp_enqueue_script( 'eewpb-checbox-buttonset-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/checkbox-button-set.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-checbox-buttonset-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/checkbox-button-set.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_checkbox_button_set() {
		vc_add_shortcode_param(
			'ee_checkbox_button_set',
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
		$values = isset( $settings['value'] ) && is_array( $settings['value'] ) ? $settings['value'] : array(
			esc_attr__( 'Yes', 'elegant-elements' ) => 'true',
		);
		$icons  = isset( $settings['icons'] ) && is_array( $settings['icons'] ) ? $settings['icons'] : array();

		if ( ! empty( $values ) ) {
			$param_name = $settings['param_name'];
			ob_start();
			?>
			<div class="elegant-option-field">
				<div class="eewpb-option-checkbox-button-set ui-buttonset eewpb-option-<?php echo esc_attr( $param_name ); ?>">
					<?php
					$choice = $value;
					$index  = 0;

					if ( '' === $choice && isset( $settings['default'] ) ) {
						$choice = $settings['default'];
					}

					if ( is_array( $choice ) ) {
						$choice = implode( ',', $choice );
					}
					?>
					<input type="hidden" id="<?php echo esc_attr( $param_name ); ?>" name="<?php echo esc_attr( $param_name ); ?>" value="<?php echo esc_attr( $choice ); ?>" class="button-set-value wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ) . ' ' . $settings['type']; ?>" />
					<?php
					$choices = explode( ',', $choice );

					foreach ( $values as $title => $value ) {
						$index++;
						$button_class  = ( in_array( $value, $choices, true ) ) ? ' ui-state-active' : '';
						$icon          = ( isset( $icons[ $value ] ) && '' !== $icons[ $value ] ) ? $icons[ $value ] : '';
						$button_class .= ( '' !== $icon ) ? ' has-tooltip' : '';
						$lable         = $title;

						if ( '' !== $icon && false !== strpos( $icon, 'svg' ) ) {
							$title = $icon;
						} elseif ( '' !== $icon ) {
							$title = '';
						}
						?>
						<a href="#" class="ui-button buttonset-item<?php echo esc_attr( $button_class ); ?>" data-value="<?php echo esc_attr( $value ); ?>" aria-label="<?php echo esc_attr( $lable ); ?>"><?php echo sprintf( '%s', $title ); ?></a>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			$output = ob_get_clean();
		}

		return $output;
	}
}

new ElegantCheckboxButtonSet();
