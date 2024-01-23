<?php
/**
 * File Upload param class.
 *
 * @package elegant-elements
 * @since 1.0
 */
class ElegantFileUpload {

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
				'vc_load_elegant_ee_file_upload',
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
		wp_enqueue_script( 'eewpb-file-upload-backend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/file-upload.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
		wp_localize_script(
			'eewpb-file-upload-backend',
			'fileUploadParam',
			array(
				'title'  => esc_attr__( 'Choose or Upload File', 'elegant-elements' ),
				'button' => esc_attr__( 'Use this media', 'elegant-elements' ),
			)
		);
	}

	/**
	 * Enqueue the styles for this param in frontend.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 * @return void
	 */
	public function vc_enqueue_editor_scripts_frontend() {
		wp_enqueue_script( 'eewpb-file-upload-frontend', preg_replace( '/\s/', '%20', plugins_url( 'inc/params/js/file-upload.js', EEWPB_PLUGIN_FILE ) ), array( 'jquery' ), EEWPB_VERSION, true );
		wp_localize_script(
			'eewpb-file-upload-frontend',
			'fileUploadParam',
			array(
				'title'  => esc_attr__( 'Choose or Upload File', 'elegant-elements' ),
				'button' => esc_attr__( 'Use this media', 'elegant-elements' ),
			)
		);
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_ee_file_upload() {
		vc_add_shortcode_param(
			'ee_file_upload',
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
				<input
					type="text"
					id="<?php echo esc_attr( $param_name ); ?>"
					name="<?php echo esc_attr( $param_name ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					class="ui-file-upload wpb_vc_param_value <?php echo esc_attr( $param_name ) . ' ' . $settings['type']; ?>"
				/>
				<a href="javascript:void(0);" class="button button-file-upload elegant-upload-button"><?php echo esc_attr__( 'Upload', 'elegant-elements' ); ?></a>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}

new ElegantFileUpload();
