<?php
if ( ! class_exists( 'EEWPB_Image_Mask_Heading' ) && elegant_is_element_enabled( 'iee_image_mask_heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Image_Mask_Heading {

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

			add_filter( 'eewpb_attr_elegant-image-mask-heading-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-mask-heading', array( $this, 'heading_attr' ) );

			add_shortcode( 'iee_image_mask_heading', array( $this, 'render' ) );
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
					'heading'             => esc_attr__( 'Image Mask Heading', 'elegant-elements' ),
					'heading_size'        => 'h2',
					'alignment'           => 'center',
					'mask_image'          => '',
					'background_position' => 'left top',
					'background_repeat'   => 'no-repeat',
					'background_size'     => 'auto',
					'element_typography'  => 'default',
					'typography_heading'  => '',
					'heading_font_size'   => '28',
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-mask-heading/elegant-image-mask-heading.php' ) ) {
				include locate_template( 'templates/image-mask-heading/elegant-image-mask-heading.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-mask-heading/elegant-image-mask-heading.php';
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
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-image-mask-heading-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' elegant-image-mask-heading-align-' . $this->args['alignment'];

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
		 * Builds the attributes array for heading.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function heading_attr() {

			$attr = array(
				'class' => 'elegant-image-mask-heading',
				'style' => '',
			);

			if ( isset( $this->args['typography_heading'] ) && '' !== $this->args['typography_heading'] ) {
				$heading_typography = elegant_get_google_font_styling( $this->args, 'typography_heading' );

				$attr['style'] .= $heading_typography;
			}

			if ( isset( $this->args['mask_image'] ) && '' !== $this->args['mask_image'] ) {
				$mask_image     = wp_get_attachment_image_src( $this->args['mask_image'], 'full' );
				$mask_image_url = $mask_image[0];
				$mask_image_url = esc_url( $mask_image_url );

				$attr['style'] .= 'background-image: url(' . $mask_image_url . ');';
				$attr['style'] .= 'background-position:' . $this->args['background_position'] . ';';
				$attr['style'] .= 'background-repeat:' . $this->args['background_repeat'] . ';';
				$attr['style'] .= 'background-size:' . $this->args['background_size'] . ';';
				$attr['style'] .= '-webkit-background-clip: text;';
				$attr['style'] .= '-webkit-text-fill-color: transparent;';
			}

			if ( isset( $this->args['heading_font_size'] ) && '' !== $this->args['heading_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height:1.2em;';
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
			wp_enqueue_style( 'infi-elegant-image-mask-heading' );
		}
	}

	new EEWPB_Image_Mask_Heading();
} // End if().


/**
 * Map shortcode for image_mask_heading.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_mask_heading() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Mask Heading', 'elegant-elements' ),
			'shortcode' => 'iee_image_mask_heading',
			'icon'      => 'fas fa-file-word image-mask-heading-icon',
			'params'    => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Heading text', 'elegant-elements' ),
					'param_name'  => 'heading',
					'value'       => esc_attr__( 'Image Mask Heading', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the text for the heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the heading size, H1-H6.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'std'         => 'h2',
					'dependency'  => array(
						'element'   => 'heading',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Select the heading alignment.', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Mask Image', 'elegant-elements' ),
					'param_name'  => 'mask_image',
					'value'       => '',
					'description' => esc_attr__( 'Select the image to be used for masking the background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image.', 'elegant-elements' ),
					'param_name'  => 'background_position',
					'default'     => 'left top',
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
					'dependency'  => array(
						'element'   => 'mask_image',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats.', 'elegant-elements' ),
					'param_name'  => 'background_repeat',
					'default'     => 'no-repeat',
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'   => 'mask_image',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Background Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image show be sized.', 'elegant-elements' ),
					'param_name'  => 'background_size',
					'default'     => 'auto',
					'value'       => array(
						'auto'    => esc_attr__( 'Auto', 'elegant-elements' ),
						'cover'   => esc_attr__( 'Cover', 'elegant-elements' ),
						'contain' => esc_attr__( 'Contain', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'   => 'mask_image',
						'not_empty' => true,
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
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
					'param_name'  => 'typography_heading',
					'value'       => '',
					'description' => esc_attr__( 'Select the font for the heading.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '300',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_mask_heading', 99 );
