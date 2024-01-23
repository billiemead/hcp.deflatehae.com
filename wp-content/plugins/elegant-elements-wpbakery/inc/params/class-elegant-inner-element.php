<?php
/**
 * Inner element param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantInnerElement {

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
				'vc_load_elegant_ee_inner_element',
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
		wp_enqueue_script( 'eewpb-inner-element-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/inner-element.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-inner-element-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/inner-element.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_inner_element() {
		vc_add_shortcode_param(
			'ee_inner_element',
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
		$tag        = $settings['element_tag'];
		$edit_title = $settings['edit_title'];
		$value      = ( isset( $settings['value'] ) && '' === $value ) ? $settings['value'] : $value;
		$value      = rawurldecode( base64_decode( $value ) ); // @codingStandardsIgnoreLine

		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-inner-element ui-inner-element eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<p><a href="javascript:void(0);" class="elegant-elements-add-shortcode" data-tag="<?php echo esc_attr( $tag ); ?>" data-edit-title="<?php echo esc_attr( $edit_title ); ?>" data-editor-id="eewpb-textarea-<?php echo esc_attr( $param_name ); ?>"><?php esc_attr_e( 'Generate or Edit Shortcode', 'elegant-elements' ); ?></a></p>
				<textarea
					name="<?php echo esc_attr( $param_name ); ?>"
					id="eewpb-textarea-<?php echo esc_attr( $param_name ); ?>"
					class="wpb_vc_param_value wpb-textarea <?php echo esc_attr( $param_name ); ?> <?php echo esc_attr( $settings['type'] ); ?>"><?php echo esc_attr( $value ); ?>
				</textarea>
			</div>
			<div class="eewpb_inner_element-edit"></div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantInnerElement();
