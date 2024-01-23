<?php
/**
 * Dimensions param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantDimensions {

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
				'vc_load_elegant_ee_dimensions',
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
		wp_enqueue_script( 'eewpb-dimensions-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/dimensions.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-dimensions-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/dimensions.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_dimensions() {
		vc_add_shortcode_param(
			'ee_dimensions',
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

		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-dimensions ui-dimensions eewpb-option-<?php echo esc_attr( $param_name ); ?>">
			<?php
			if ( is_array( $values ) ) {
				// The dimensions param has custom fields only.
				$values_count = count( $values );
				$sub_values   = array();

				if ( ! is_array( $value ) && '' !== $value ) {
					$sub_values = explode( ' ', $value );
				} elseif ( is_array( $value ) ) {
					$sub_values = array_values( $value );
				} else {
					$values_count = 1;
				}

				foreach ( $values as $sub_param => $sub_value ) {
					$dimension_value = ( '' !== $sub_value ) ? $sub_value : $value;
					$icon_svg        = '';

					// Check if param is border_radius.
					if ( false === strpos( $sub_param, 'border_radius' ) ) {
						if ( false !== strpos( $sub_param, 'top' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 7.828V20h-2V7.828l-5.364 5.364-1.414-1.414L12 4l7.778 7.778-1.414 1.414L13 7.828z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[0];
							}
						}
						if ( false !== strpos( $sub_param, 'right' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[1];
							}
						}
						if ( false !== strpos( $sub_param, 'bottom' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 16.172l5.364-5.364 1.414 1.414L12 20l-7.778-7.778 1.414-1.414L11 16.172V4h2v12.172z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[2];
							}
						}
						if ( false !== strpos( $sub_param, 'left' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[3];
							}
						}
					} else {
						// Set icons and param values for border radius.
						if ( false !== strpos( $sub_param, 'top_left' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9.414 8l8.607 8.607-1.414 1.414L8 9.414V17H6V6h11v2z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[0];
							}
						}
						if ( false !== strpos( $sub_param, 'top_right' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.004 9.414l-8.607 8.607-1.414-1.414L14.589 8H7.004V6h11v11h-2V9.414z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[1];
							}
						}
						if ( false !== strpos( $sub_param, 'bottom_left' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9 13.59l8.607-8.607 1.414 1.414-8.607 8.607H18v2H7v-11h2v7.585z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[3];
							}
						}
						if ( false !== strpos( $sub_param, 'bottom_right' ) ) {
							$icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14.59 16.004L5.982 7.397l1.414-1.414 8.607 8.606V7.004h2v11h-11v-2z"/></svg>';
							if ( 4 === $values_count ) {
								$dimension_value = $sub_values[2];
							}
						}
					}
					?>
					<div class="elegant-dimension dimension-item">
						<span class="add-on"><?php echo ( $icon_svg ); ?></span>
						<input class="wpb_vc_param_value <?php echo esc_attr( $sub_param ) . ' ' . $settings['type']; ?>" type="text" name="<?php echo esc_attr( $sub_param ); ?>" id="<?php echo esc_attr( $sub_param ); ?>" value="<?php echo esc_attr( $dimension_value ); ?>" />
					</div>
					<?php
				}
			} else {
				// Output all dimension fields.
				$values = explode( ' ', $value );
				$count  = count( $values );

				if ( 1 === $count ) {
					$dimension_top    = $values[0];
					$dimension_bottom = $values[0];
					$dimension_left   = $values[0];
					$dimension_right  = $values[0];
				}
				if ( 2 === $count ) {
					$dimension_top    = $values[0];
					$dimension_bottom = $values[0];
					$dimension_left   = $values[1];
					$dimension_right  = $values[1];
				}
				if ( 3 === $count ) {
					$dimension_top    = $values[0];
					$dimension_left   = $values[1];
					$dimension_right  = $values[1];
					$dimension_bottom = $values[2];
				}
				if ( 4 === $count ) {
					$dimension_top    = $values[0];
					$dimension_left   = $values[3];
					$dimension_right  = $values[1];
					$dimension_bottom = $values[2];
				}
				?>
				<div class="elegant-dimension dimension-top">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 7.828V20h-2V7.828l-5.364 5.364-1.414-1.414L12 4l7.778 7.778-1.414 1.414L13 7.828z"/></svg></span>
					<input type="text" name="<?php echo esc_attr( $param_name ); ?>_top" id="<?php echo esc_attr( $param_name ); ?>_top" value="<?php echo esc_attr( $dimension_top ); ?>" />
				</div>
				<div class="elegant-dimension dimension-right">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg></span>
					<input type="text" name="<?php echo esc_attr( $param_name ); ?>_right" id="<?php echo esc_attr( $param_name ); ?>_right" value="<?php echo esc_attr( $dimension_right ); ?>" />
				</div>
				<div class="elegant-dimension dimension-bottom">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 16.172l5.364-5.364 1.414 1.414L12 20l-7.778-7.778 1.414-1.414L11 16.172V4h2v12.172z"/></svg></span>
					<input type="text" name="<?php echo esc_attr( $param_name ); ?>_bottom" id="<?php echo esc_attr( $param_name ); ?>_bottom" value="<?php echo esc_attr( $dimension_bottom ); ?>" />
				</div>
				<div class="elegant-dimension dimension-left">
					<span class="add-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg></span>
					<input type="text" name="<?php echo esc_attr( $param_name ); ?>_left" id="<?php echo esc_attr( $param_name ); ?>_left" value="<?php echo esc_attr( $dimension_left ); ?>" />
				</div>
				<?php
			}
			?>
			<input class="wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>" type="hidden" name="<?php echo esc_attr( $param_name ); ?>" id="<?php echo esc_attr( $param_name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantDimensions();
