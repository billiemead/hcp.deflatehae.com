<?php
if ( ! class_exists( 'EEWPB_Special_Heading' ) && elegant_is_element_enabled( 'iee_special_heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Special_Heading {

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

			add_filter( 'eewpb_attr_elegant-special-heading', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-special-heading-wrapper', array( $this, 'attr_heading_wrapper' ) );
			add_filter( 'eewpb_attr_elegant-special-heading-title', array( $this, 'title_attr' ) );
			add_filter( 'eewpb_attr_elegant-special-heading-description', array( $this, 'description_attr' ) );

			add_shortcode( 'iee_special_heading', array( $this, 'render' ) );
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
					'title'                       => '',
					'heading_size'                => 'h2',
					'element_typography'          => 'default',
					'typography_title'            => '',
					'title_color'                 => '',
					'title_font_size'             => '28',
					'description'                 => '',
					'typography_description'      => '',
					'description_color'           => '',
					'description_font_size'       => '18',
					'background_color'            => '',
					'background_image'            => '',
					'background_position'         => 'left top',
					'background_repeat'           => 'no-repeat',
					'height'                      => 'auto',
					'container_padding'           => '',
					'alignment'                   => 'center',
					'additional_content_position' => 'after_heading',
					'hide_on_mobile'              => elegant_elements_default_visibility( 'string' ),
					'class'                       => '',
					'id'                          => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/special-heading/elegant-special-heading.php' ) ) {
				include locate_template( 'templates/special-heading/elegant-special-heading.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/special-heading/elegant-special-heading.php';
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
				'class' => 'elegant-special-heading',
				'style' => '',
			);

			$attr['class'] .= ' special-heading-align-' . $this->args['alignment'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['height'] ) && '0' !== $this->args['height'] ) {
				$attr['style'] .= 'height:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

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

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr_heading_wrapper() {
			$attr = array(
				'class' => 'elegant-special-heading-wrapper',
				'style' => '',
			);

			$container_padding = $this->args['container_padding'];
			$css_class         = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $container_padding, ' ' ), 'iee_special_heading', $this->args );
			$attr['class']    .= ' ' . $css_class;

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
				'class' => 'elegant-special-heading-title',
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
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-special-heading-description',
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
			wp_enqueue_style( 'infi-elegant-special-heading' );
		}
	}

	new EEWPB_Special_Heading();
} // End if().

/**
 * Map shortcode for special_heading.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_special_heading() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Special Heading', 'elegant-elements' ),
			'shortcode' => 'iee_special_heading',
			'icon'      => 'fa-h-square fas special-heading-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Title', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Elegant Special Heading', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text for the heading title.', 'elegant-elements' ),
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
						'not_empty' => true,
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
					'heading'     => esc_attr__( 'Heading Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the typography for the heading title.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading title.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Heading Description', 'elegant-elements' ),
					'param_name'  => 'description',
					'value'       => esc_attr__( 'Your special heading description text goes here. You can edit it using Frontend Builder.', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text for the heading description.', 'elegant-elements' ),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Description Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the typography for the heading description.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Color', 'elegant-elements' ),
					'param_name'  => 'description_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading description.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Container Background Color', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
					'description' => esc_attr__( 'Select the color for the heading container background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Heading Container Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the heading container.', 'elegant-elements' ),
					'param_name'  => 'background_image',
					'group'       => 'Design',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Heading Container Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for heading container.', 'elegant-elements' ),
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
					'group'       => 'Design',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Heading Container Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for heading container.', 'elegant-elements' ),
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
					'group'       => 'Design',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Container Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height To be applied for the heading container. Set to 0 to apply "auto" height. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '',
					'min'         => '0',
					'max'         => '1000',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'             => 'css_editor',
					'heading'          => esc_attr__( 'Heading Container Padding', 'elegant-elements' ),
					'param_name'       => 'container_padding',
					'value'            => '',
					'description'      => esc_attr__( 'Controls the space around heading container. Enter values in valid CSS units. eg. px, em, %.', 'elegant-elements' ),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
					'edit_field_class' => 'vc_col-sm-12 vc_column elegant-dimensions',
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
					'description' => esc_attr__( 'Select the heading title alignment.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Enable Additional Content?', 'elegant-elements' ),
					'description' => esc_attr__( 'Additional content allows you to place custom content including shortcodes in the special heading.', 'elegant-elements' ),
					'param_name'  => 'additional_content',
					'std'         => 'no',
					'value'       => array(
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Additional Content', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => esc_attr__( 'Enter the additional content you want to use in the special heading element. You can add shortcodes of button, separator element here for advanced usage.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'additional_content',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Additional Content Position', 'elegant-elements' ),
					'param_name'  => 'additional_content_position',
					'std'         => 'after_heading',
					'value'       => array(
						'above_heading'    => esc_attr__( 'Above Heading Text', 'elegant-elements' ),
						'after_heading'    => esc_attr__( 'Between Heading and Description', 'elegant-elements' ),
						'after_decription' => esc_attr__( 'After Description', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the position for the additional content.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'additional_content',
						'value'   => array( 'yes' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_special_heading', 99 );

class WPBakeryShortCode_Iee_Special_Heading extends WPBakeryShortCode {
}
