<?php
if ( ! class_exists( 'EEWPB_Dual_Button' ) && elegant_is_element_enabled( 'iee_dual_button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Dual_Button {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-dual-button', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-dual-button-separator', array( $this, 'separator_attr' ) );
			add_shortcode( 'iee_dual_button', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'button_1'             => '',
					'button_2'             => '',
					'separator_type'       => 'string',
					'sep_text'             => esc_attr__( 'OR', 'elegant-elements' ),
					'sep_icon'             => '',
					'sep_background_color' => '#ffffff',
					'sep_color'            => '#8bc34a',
					'element_typography'   => '',
					'typography_separator' => '',
					'alignment'            => 'left',
					'hide_on_mobile'       => elegant_elements_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/dual-button/elegant-dual-button.php' ) ) {
				include locate_template( 'templates/dual-button/elegant-dual-button.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/dual-button/elegant-dual-button.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-dual-button',
			);

			if ( isset( $this->args['alignment'] ) && '' !== $this->args['alignment'] ) {
				$attr['class'] .= ' elegant-align-' . $this->args['alignment'];
			}

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for separator.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function separator_attr() {
			$attr = array(
				'class' => 'elegant-dual-button-separator',
				'style' => '',
			);

			$attr['class'] .= ' elegant-separator-type-' . $this->args['separator_type'];

			if ( isset( $this->args['sep_background_color'] ) && '' !== $this->args['sep_background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['sep_background_color'] . ';';
			}

			if ( isset( $this->args['sep_color'] ) && '' !== $this->args['sep_color'] ) {
				$attr['style'] .= 'color:' . $this->args['sep_color'] . ';';
			}

			if ( isset( $this->args['typography_separator'] ) && '' !== $this->args['typography_separator'] ) {
				$separator_typography = elegant_get_google_font_styling( $this->args, 'typography_separator' );

				$attr['style'] .= $separator_typography;
			}

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-dual-button' );
		}
	}

	new EEWPB_Dual_Button();
} // End if().


/**
 * Map shortcode for dual_button.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_dual_button() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Dual Button', 'elegant-elements' ),
			'shortcode' => 'iee_dual_button',
			'icon'      => 'fas fa-clone dual-button-icon',
			'params'    => array(
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button 1 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_1',
					'value'       => '',
					'description' => esc_attr__( 'Click the link to generate or edit button 1 shortcode.', 'elegant-elements' ),
					'element_tag' => 'iee_fancy_button',
					'edit_title'  => 'Edit Button 1 Settings',
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button 2 Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_2',
					'value'       => '',
					'description' => esc_attr__( 'Click the link to generate or edit button 2 shortcode.', 'elegant-elements' ),
					'element_tag' => 'iee_fancy_button',
					'edit_title'  => 'Edit Button 2 Settings',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Separator Type', 'elegant-elements' ),
					'param_name'  => 'separator_type',
					'std'         => 'string',
					'value'       => array(
						'string' => esc_attr__( 'String', 'elegant-elements' ),
						'icon'   => esc_attr__( 'Icon', 'elegant-elements' ),
						'none'   => esc_attr__( 'None', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select if you want to display string or icon in separator or remove the separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Separator Word', 'elegant-elements' ),
					'param_name'  => 'sep_text',
					'value'       => esc_attr__( 'OR', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the string displayed in separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'separator_type',
						'value'   => array( 'string' ),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Separator Icon', 'elegant-elements' ),
					'param_name'  => 'sep_icon',
					'value'       => '',
					'description' => esc_attr__( 'Select the icon to be used as separator.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'separator_type',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Separator Icon / Text Background Color', 'elegant-elements' ),
					'param_name'  => 'sep_background_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Select the color to be applied to separator background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'separator_type',
						'value_not_equal_to' => 'none',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Separator Icon / Text Color', 'elegant-elements' ),
					'param_name'  => 'sep_color',
					'value'       => '#8bc34a',
					'description' => esc_attr__( 'Select the text color to be applied to separator text or icon.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'separator_type',
						'value_not_equal_to' => 'none',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'default'     => 'custom',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'separator_type',
						'value'   => array( 'string' ),
					),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Separator Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the separator text.', 'elegant-elements' ),
					'param_name'  => 'typography_separator',
					'value'       => '',
					'group'       => esc_attr__( 'Separator', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Select the button alignment.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'elegant-elements' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => elegant_elements_visibility_options( 'full' ),
					'icons'       => elegant_get_visibility_icons(),
					'default'     => elegant_elements_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
					'param_name'  => 'class',
					'value'       => '',
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
					'param_name'  => 'id',
					'value'       => '',
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				),
			),
		)
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_dual_button', 99 );
