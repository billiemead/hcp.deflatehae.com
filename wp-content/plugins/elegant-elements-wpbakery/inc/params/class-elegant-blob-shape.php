<?php
/**
 * File Upload param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantBlobShapeGenerator {

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
				'vc_load_elegant_ee_blob_shape_generator',
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
		wp_enqueue_script( 'eewpb-blob-shape-generator-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/blob-shape-generator.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-blob-shape-generator-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/blob-shape-generator.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_blob_shape_generator() {
		vc_add_shortcode_param(
			'ee_blob_shape_generator',
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
		$value  = ( isset( $settings['value'] ) && '' === $value ) ? $settings['value'] : $value;

		$param_name = $settings['param_name'];
		ob_start();
		?>
		<div class="elegant-option-field">
			<div class="eewpb-option-file-upload ui-fileupload eewpb-option-<?php echo esc_attr( $param_name ); ?>">
				<div class="elegant-element-blob-shape-generator-container">
					<div class="elegant-element-blob-shape-generator-fields-wrapper">
						<div class="elegant-element-blob-shape-generator-field">
							<input
								type="hidden"
								id="<?php echo esc_attr( $param_name ); ?>"
								name="<?php echo esc_attr( $param_name ); ?>"
								value="<?php echo esc_attr( $value ); ?>"
								class="ui-blob-shape-generator wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>"
							/>
							<div class="elegant-blob-shape-generator-controls elegant-element-field-controls" style="text-align: center;">
								<a href="#" class="button button-primary elegant-element-blob-shape-generator-button" style="margin-bottom: 15px;"><?php esc_attr_e( 'Generate Blob Shape', 'elegant-elements' ); ?></a>
								<div class="elegant-blob-shape-generator-placeholder-wrapper">
									<div class="elegant-blob-shape-generator-placeholder button-primary" style="border-radius:<?php echo esc_attr( $value ); ?>;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantBlobShapeGenerator();
