<?php
if ( ! class_exists( 'EEWPB_Promo_Box' ) && elegant_is_element_enabled( 'iee_promo_box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Promo_Box {

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

			add_filter( 'eewpb_attr_elegant-promo-box', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-promo-box-title', array( $this, 'title_attr' ) );
			add_filter( 'eewpb_attr_elegant-promo-box-description-wrapper', array( $this, 'description_wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-promo-box-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_promo_box', array( $this, 'render' ) );
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
					'image'                  => '',
					'title'                  => esc_attr__( 'Elegant Promo Box', 'elegant-elements' ),
					'heading_size'           => 'h2',
					'element_typography'     => 'default',
					'typography_title'       => '',
					'title_font_size'        => '28',
					'title_color'            => '',
					'description'            => esc_attr__( 'Your promo text goes here. You can edit it using Frontend Builder.', 'elegant-elements' ),
					'typography_description' => '',
					'description_font_size'  => '18',
					'description_color'      => '',
					'image_align'            => 'left',
					'content_align'          => 'center',
					'background_color'       => '',
					'background_image'       => '',
					'background_position'    => 'left top',
					'background_repeat'      => 'no-repeat',
					'height'                 => '300',
					'element_content'        => '',
					'hide_on_mobile'         => elegant_elements_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;
			$content    = rawurldecode( base64_decode( $this->args['element_content'] ) ); // @codingStandardsIgnoreLine

			$html = '';

			if ( '' !== locate_template( 'templates/promo-box/elegant-promo-box.php' ) ) {
				include locate_template( 'templates/promo-box/elegant-promo-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/promo-box/elegant-promo-box.php';
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
				'class' => 'elegant-promo-box',
				'style' => '',
			);

			$attr['class'] .= ' promo-image-align-' . $this->args['image_align'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
			}

			if ( isset( $this->args['background_image'] ) && '' !== $this->args['background_image'] ) {
				$background_image     = wp_get_attachment_image_src( $this->args['background_image'], 'full' );
				$background_image_url = $background_image[0];
				$background_image_url = esc_url( $background_image_url );

				$attr['style'] .= 'background-image: url("' . $background_image_url . '");';
				$attr['style'] .= 'background-position:' . $this->args['background_position'] . ';';
				$attr['style'] .= 'background-repeat:' . $this->args['background_repeat'] . ';';
				$attr['style'] .= 'background-blend-mode: overlay;';
			}

			if ( isset( $this->args['height'] ) && '' !== $this->args['height'] ) {
				$attr['style'] .= 'height:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for title.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$title_typography = elegant_get_google_font_styling( $this->args, 'typography_title' );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for description.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-description-wrapper',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['content_align'];

			return $attr;
		}

		/**
		 * Builds the attributes array for description.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-promo-box-description',
				'style' => '',
			);

			if ( isset( $this->args['typography_description'] ) && '' !== $this->args['typography_description'] ) {
				$description_typography = elegant_get_google_font_styling( $this->args, 'typography_description' );

				$attr['style'] .= $description_typography;
			}

			if ( isset( $this->args['description_font_size'] ) && '' !== $this->args['description_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['description_color'] ) && '' !== $this->args['description_color'] ) {
				$attr['style'] .= 'color:' . $this->args['description_color'] . ';';
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
			wp_enqueue_style( 'infi-elegant-promo-box' );
		}
	}

	new EEWPB_Promo_Box();
} // End if().


/**
 * Map shortcode for promo_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_promo_box() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Promo Box', 'elegant-elements' ),
			'shortcode' => 'iee_promo_box',
			'icon'      => 'fas fa-ad promo-box-icon',
			'params'    => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Upload Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the image to be used in promo box.', 'elegant-elements' ),
					'param_name'  => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Promo Title', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Elegant Promo Box', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the text to be used as promo box title.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the title size, H1-H6.', 'elegant-elements' ),
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
						'element'   => 'title',
						'not_empty' => '',
					),
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
					'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for promo box title.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Promo Description Text', 'elegant-elements' ),
					'param_name'  => 'description',
					'value'       => esc_attr__( 'Your promo text goes here. You can edit it using Frontend Builder.', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text to be used as promo box description text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for promo box description.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Image Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to align the image with text.', 'elegant-elements' ),
					'param_name'  => 'image_align',
					'value'       => array(
						'left'  => esc_attr__( 'Left', 'elegant-elements' ),
						'right' => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'std'         => 'left',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Content Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select box content alighment including title, description and button.', 'elegant-elements' ),
					'param_name'  => 'content_align',
					'std'         => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Promo Box Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => 'rgba(247,247,247,0.6)',
					'placeholder' => true,
					'description' => esc_attr__( 'Controls the background color applied to the promo box.', 'elegant-elements' ),
					'group'       => 'Background',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Promo Box Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the promo box.', 'elegant-elements' ),
					'param_name'  => 'background_image',
					'group'       => 'Background',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Promo Box Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for promo box.', 'elegant-elements' ),
					'param_name'  => 'background_position',
					'default'     => 'left top',
					'dependency'  => array(
						'element'   => 'background_image',
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
					'group'       => 'Background',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Promo Box Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for promo box.', 'elegant-elements' ),
					'param_name'  => 'background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						'element'   => 'background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => 'Background',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Promo Box Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the height for promo box. In Pixel (px). eg. 400px', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '300',
					'min'         => '100',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'description' => esc_attr__( 'Click the link to generate or edit button shortcode.', 'elegant-elements' ),
					'element_tag' => 'iee_fancy_button',
					'edit_title'  => 'Edit Button Settings',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_promo_box', 99 );
