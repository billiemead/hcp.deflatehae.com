<?php
if ( ! class_exists( 'EEWPB_Contact_Form7' ) && defined( 'WPCF7_VERSION' ) && elegant_is_element_enabled( 'iee_contact_form7' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Contact_Form7 {

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

			add_filter( 'eewpb_attr_elegant-contact-form7', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-contact-form7-form-wrapper', array( $this, 'form_attr' ) );

			add_shortcode( 'iee_contact_form7', array( $this, 'render' ) );
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

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'form'                        => '',
					'heading_text'                => esc_attr__( 'Subscribe to our newsletter', 'elegant-elements' ),
					'heading_color'               => '#03a9f4',
					'heading_background_color'    => '#fcfcfc',
					'heading_background_image'    => '',
					'heading_background_position' => 'left top',
					'heading_background_repeat'   => 'no-repeat',
					'heading_size'                => 'h2',
					'heading_font_size'           => '28',
					'heading_align'               => 'left',
					'heading_padding'             => '15px',
					'caption_text'                => esc_attr__( 'Get latest blog posts to your inbox', 'elegant-elements' ),
					'caption_color'               => '#6d6d6d',
					'caption_font_size'           => '18',
					'form_background_color'       => '#f9f9f9',
					'form_background_image'       => '',
					'form_background_position'    => 'left top',
					'form_background_repeat'      => 'no-repeat',
					'form_padding'                => '15px',
					'form_border_size'            => '0',
					'form_border_color'           => '#dddddd',
					'form_border_style'           => 'solid',
					'form_border_radius'          => '0',
					'element_typography'          => 'default',
					'typography_heading'          => '',
					'typography_caption'          => '',
					'hide_on_mobile'              => elegant_elements_default_visibility( 'string' ),
					'class'                       => '',
					'id'                          => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/contact-form7/elegant-contact-form7.php' ) ) {
				include locate_template( 'templates/contact-form7/elegant-contact-form7.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/contact-form7/elegant-contact-form7.php';
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
				'class' => 'elegant-contact-form7',
			);

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
		 * Builds the attributes array for form wrapper.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function form_attr() {

			$attr = array(
				'class' => 'elegant-contact-form7-form-wrapper',
			);

			$styles = '';

			// Set form styles.
			if ( $this->args['form_padding'] ) {
				$styles .= 'padding:' . $this->args['form_padding'] . ';';
			}

			if ( $this->args['form_background_color'] ) {
				$styles .= 'background-color:' . $this->args['form_background_color'] . ';';
			}

			if ( $this->args['form_background_image'] ) {
				$form_background_image     = wp_get_attachment_image_src( $this->args['form_background_image'], 'full' );
				$form_background_image_url = $form_background_image[0];
				$form_background_image_url = esc_url( $form_background_image_url );

				$styles .= 'background-image: url(' . $form_background_image_url . ');';
				$styles .= 'background-position: ' . $this->args['form_background_position'] . ';';
				$styles .= 'background-repeat: ' . $this->args['form_background_repeat'] . ';';
				$styles .= 'background-blend-mode: overlay;';
			}

			if ( $this->args['form_border_size'] ) {
				$styles .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_size'], 'px' ) . ';';
				$styles .= 'border-color:' . $this->args['form_border_color'] . ';';
				$styles .= 'border-style:' . $this->args['form_border_style'] . ';';

				if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {
					$styles .= 'border-top: none;';
				}
			}

			if ( isset( $this->args['form_border_radius'] ) && '' !== $this->args['form_border_radius'] ) {
				$border_radius = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_radius'], 'px' );

				if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {
					$styles .= 'border-radius:0 0 ' . $border_radius . ' ' . $border_radius . ';';
				} else {
					$styles .= 'border-radius:' . $border_radius . ';';
				}
			}

			if ( '' !== $styles ) {
				$attr['style'] = $styles;
			}

			return $attr;
		}
	}

	new EEWPB_Contact_Form7();
} // End if().


/**
 * Map shortcode for contact_form7.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_contact_form7() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Contact Form 7', 'elegant-elements' ),
			'shortcode' => 'iee_contact_form7',
			'icon'      => 'fas fa-envelope contact-form7-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Select Form', 'elegant-elements' ),
					'description' => esc_attr__( 'Select which form you want to use.', 'elegant-elements' ),
					'param_name'  => 'form',
					'value'       => eewpb_get_contact_form_list(),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter heading text for this form.', 'elegant-elements' ),
					'param_name'  => 'heading_text',
					'value'       => esc_attr__( 'Subscribe to our newsletter', 'elegant-elements' ),
					'placeholder' => true,
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the text color for the form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_color',
					'value'       => '#03a9f4',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the form heading area.', 'elegant-elements' ),
					'param_name'  => 'heading_background_color',
					'value'       => '#fcfcfc',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Heading Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the form heading area.', 'elegant-elements' ),
					'param_name'  => 'heading_background_image',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Heading Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_background_position',
					'default'     => 'left top',
					'dependency'  => array(
						'element'   => 'heading_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Heading Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for heading.', 'elegant-elements' ),
					'param_name'  => 'heading_background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						'element'   => 'heading_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the heading size for the form heading.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'std'         => 'h2',
					'value'       => array(
						'h1' => esc_attr__( 'H1', 'elegant-elements' ),
						'h2' => esc_attr__( 'H2', 'elegant-elements' ),
						'h3' => esc_attr__( 'H3', 'elegant-elements' ),
						'h4' => esc_attr__( 'H4', 'elegant-elements' ),
						'h5' => esc_attr__( 'H5', 'elegant-elements' ),
						'h6' => esc_attr__( 'H6', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the heading font size. ( In pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'default'     => '',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select alignment for heading text.', 'elegant-elements' ),
					'param_name'  => 'heading_align',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Heading Area Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'heading_padding',
					'value'       => '15px',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Caption Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter caption text for this form.', 'elegant-elements' ),
					'param_name'  => 'caption_text',
					'value'       => esc_attr__( 'Get latest blog posts to your inbox', 'elegant-elements' ),
					'placeholder' => true,
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Caption Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the text color for the form heading caption.', 'elegant-elements' ),
					'param_name'  => 'caption_color',
					'value'       => '#6d6d6d',
					'dependency'  => array(
						'element'   => 'caption_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Caption Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the caption font size. ( In pixel. )', 'elegant-elements' ),
					'param_name'  => 'caption_font_size',
					'default'     => '',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						'element'   => 'caption_text',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Form Heading', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_color',
					'value'       => '#f9f9f9',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Form Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_image',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Form Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_position',
					'default'     => 'left top',
					'dependency'  => array(
						'element'   => 'form_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Form Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						'element'   => 'form_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Form Area Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'form_padding',
					'value'       => '15px',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Form Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the form. In pixels.', 'elegant-elements' ),
					'param_name'  => 'form_border_size',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color of the form.', 'elegant-elements' ),
					'param_name'  => 'form_border_color',
					'value'       => '#dddddd',
					'dependency'  => array(
						'element'            => 'form_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Form Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'form_border_style',
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
					'std'         => 'solid',
					'dependency'  => array(
						'element'            => 'form_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Form Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border radius for the form container. In pixels (px).', 'elegant-elements' ),
					'param_name'  => 'form_border_radius',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Form Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'std'         => 'default',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Heading Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the form heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading',
					'value'       => '',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Heading Caption Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the form heading caption text.', 'elegant-elements' ),
					'param_name'  => 'typography_caption',
					'value'       => '',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
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

if ( defined( 'WPCF7_VERSION' ) ) {
	add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_contact_form7', 99 );
}
