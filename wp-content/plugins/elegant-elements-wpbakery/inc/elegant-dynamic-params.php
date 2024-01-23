<?php
/**
 * Dynamic param class.
 *
 * @package elegant-elements
 * @since 1.2
 */
class ElegantDynamicParams {

	/**
	 * The Constructor.
	 *
	 * @package elegant-elements
	 * @since 1.2
	 * @return void
	 */
	public function __construct() {
		add_action(
			'vc_load_default_params',
			array(
				$this,
				'vc_load_elegant_dynamic_params',
			)
		);
	}

	/**
	 * Add custom param to system
	 */
	public function vc_load_elegant_dynamic_params() {
		// Alter the textfield param.
		vc_add_shortcode_param(
			'textfield',
			array(
				$this,
				'render_textfield',
			)
		);

		// Alter the textarea param.
		vc_add_shortcode_param(
			'textarea',
			array(
				$this,
				'render_textarea',
			)
		);
	}

	/**
	 * Textfield param with dynamic value.
	 *
	 * @param array  $settings Param settings array.
	 * @param string $value    Saved element param value.
	 *
	 * @return string - html string.
	 */
	public function render_textfield( $settings, $value ) {
		$value = htmlspecialchars( $value );

		$icon  = '<a href="javascript:void(0);" class="dynamic-param-trigger-input"><span class="dashicons dashicons-database-add"></span></a>';
		$input = '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '" type="text" value="' . $value . '"/>';

		$output = '<div class="elegant-dynamic-param">' . $input . $icon . '</div>';

		return $output;
	}

	/**
	 * Textarea param with dynamic value.
	 *
	 * @param array  $settings Param settings array.
	 * @param string $value    Saved element param value.
	 *
	 * @return string - html string.
	 */
	public function render_textarea( $settings, $value ) {
		$value = htmlspecialchars( $value );

		$icon  = '<a href="javascript:void(0);" class="dynamic-param-trigger-textarea"><span class="dashicons dashicons-database-add"></span></a>';
		$input = '<textarea name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textarea ' . $settings['param_name'] . ' ' . $settings['type'] . '">' . $value . '</textarea>';

		$output = '<div class="elegant-dynamic-param">' . $input . $icon . '</div>';

		return $output;
	}
}

new ElegantDynamicParams();
