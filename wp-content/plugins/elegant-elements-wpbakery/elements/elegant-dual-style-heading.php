<?php
if ( ! class_exists( 'EEWPB_Dual_Style_Heading' ) && elegant_is_element_enabled( 'iee_dual_style_heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Dual_Style_Heading {

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

			add_filter( 'eewpb_attr_elegant-dual-style-heading', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-dual-style-heading-first', array( $this, 'attr_heading_first' ) );
			add_filter( 'eewpb_attr_elegant-dual-style-heading-last', array( $this, 'attr_heading_last' ) );

			add_shortcode( 'iee_dual_style_heading', array( $this, 'render' ) );
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
					'heading_first'                    => esc_attr__( 'Elegant Elements', 'elegant-elements' ),
					'heading_last'                     => esc_attr__( 'for WPBakery Page Builder', 'elegant-elements' ),
					'heading_tag'                      => 'h2',
					'alignment'                        => 'left',
					'padding_top'                      => '',
					'padding_right'                    => '',
					'padding_bottom'                   => '',
					'padding_left'                     => '',
					'padding'                          => '',
					'heading_gap'                      => '0px',
					'element_typography'               => 'default',
					'typography_heading_first'         => '',
					'typography_heading_last'          => '',
					'font_size'                        => '18px',
					'heading_first_text_color'         => '#ffffff',
					'heading_first_background_color'   => '#333333',
					'heading_first_background_color_2' => '',
					'heading_first_gradient_type'      => 'horizontal',
					'heading_first_gradient_direction' => '0deg',
					'heading_first_border_size'        => '',
					'heading_first_border_color'       => '',
					'heading_first_border_style'       => 'solid',
					'heading_first_border_position'    => 'all',
					'heading_first_border_radius'      => '',
					'first_border_radius_top_left'     => '0px',
					'first_border_radius_top_right'    => '0px',
					'first_border_radius_bottom_left'  => '0px',
					'first_border_radius_bottom_right' => '0px',
					'heading_last_text_color'          => '#333333',
					'heading_last_background_color'    => '#fbfbfb',
					'heading_last_background_color_2'  => '',
					'heading_last_gradient_type'       => 'horizontal',
					'heading_last_gradient_direction'  => '0deg',
					'heading_last_border_size'         => '',
					'heading_last_border_color'        => '',
					'heading_last_border_style'        => 'solid',
					'heading_last_border_position'     => 'all',
					'heading_last_border_radius'       => '',
					'last_border_radius_top_left'      => '0px',
					'last_border_radius_top_right'     => '0px',
					'last_border_radius_bottom_left'   => '0px',
					'last_border_radius_bottom_right'  => '0px',
					'hide_on_mobile'                   => elegant_elements_default_visibility( 'string' ),
					'class'                            => '',
					'id'                               => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/dual-style-heading/elegant-dual-style-heading.php' ) ) {
				include locate_template( 'templates/dual-style-heading/elegant-dual-style-heading.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/dual-style-heading/elegant-dual-style-heading.php';
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
				'class' => 'elegant-dual-style-heading',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr_heading_first() {
			$attr = array(
				'class' => 'elegant-dual-style-heading-first',
				'style' => '',
			);

			if ( isset( $this->args['heading_first_text_color'] ) && '' !== $this->args['heading_first_text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['heading_first_text_color'] . ';';
			}

			if ( isset( $this->args['heading_first_background_color'] ) && '' !== $this->args['heading_first_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['heading_first_background_color'] . ';';
			}

			if ( isset( $this->args['heading_first_background_color_2'] ) && '' !== $this->args['heading_first_background_color_2'] ) {
				$gradient_direction = ( 'vertical' == $this->args['heading_first_gradient_type'] ) ? 'top' : $this->args['heading_first_gradient_direction'];
				$attr['style']     .= eewpb_build_gradient_color( $this->args['heading_first_background_color'], $this->args['heading_first_background_color_2'], $gradient_direction );
			}

			if ( isset( $this->args['padding'] ) && '' !== $this->args['padding'] ) {
				$attr['style'] .= 'padding: ' . $this->args['padding'] . ';';
			}

			if ( isset( $this->args['font_size'] ) && '' !== $this->args['font_size'] ) {
				$attr['style'] .= 'font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['heading_gap'] ) && '' !== $this->args['heading_gap'] ) {
				$attr['style'] .= 'margin-right: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_gap'], 'px' ) . ';';
			}

			// Border.
			if ( $this->args['heading_first_border_color'] && $this->args['heading_first_border_size'] && $this->args['heading_first_border_style'] ) {
				$border_position = ( 'all' !== $this->args['heading_first_border_position'] ) ? '-' . $this->args['heading_first_border_position'] : '';
				$border_size     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_first_border_size'], 'px' );
				$attr['style']  .= 'border' . $border_position . ':' . $border_size . ' ' . $this->args['heading_first_border_style'] . ' ' . $this->args['heading_first_border_color'] . ';';
			}

			// Border radius.
			$border_radius_top_left     = ( isset( $this->args['first_border_radius_top_left'] ) && '' !== $this->args['first_border_radius_top_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['first_border_radius_top_left'], 'px' ) : '0px';
			$border_radius_top_right    = ( isset( $this->args['first_border_radius_top_right'] ) && '' !== $this->args['first_border_radius_top_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['first_border_radius_top_right'], 'px' ) : '0px';
			$border_radius_bottom_right = ( isset( $this->args['first_border_radius_bottom_right'] ) && '' !== $this->args['first_border_radius_bottom_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['first_border_radius_bottom_right'], 'px' ) : '0px';
			$border_radius_bottom_left  = ( isset( $this->args['first_border_radius_bottom_left'] ) && '' !== $this->args['first_border_radius_bottom_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['first_border_radius_bottom_left'], 'px' ) : '0px';
			$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
			$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

			if ( '' !== $border_radius ) {
				$attr['style'] .= 'border-radius: ' . $border_radius . ';';
			}

			// Typography.
			if ( isset( $this->args['typography_heading_first'] ) && '' !== $this->args['typography_heading_first'] ) {
				$attr['style'] .= elegant_get_google_font_styling( $this->args, 'typography_heading_first' );
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
		public function attr_heading_last() {
			$attr = array(
				'class' => 'elegant-dual-style-heading-last',
				'style' => '',
			);

			if ( isset( $this->args['heading_last_text_color'] ) && '' !== $this->args['heading_last_text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['heading_last_text_color'] . ';';
			}

			if ( isset( $this->args['heading_last_background_color'] ) && '' !== $this->args['heading_last_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['heading_last_background_color'] . ';';
			}

			if ( isset( $this->args['heading_last_background_color_2'] ) && '' !== $this->args['heading_last_background_color_2'] ) {
				$gradient_direction = ( 'vertical' == $this->args['heading_last_gradient_type'] ) ? 'top' : $this->args['heading_last_gradient_direction'];
				$attr['style']     .= eewpb_build_gradient_color( $this->args['heading_last_background_color'], $this->args['heading_last_background_color_2'], $gradient_direction );
			}

			if ( isset( $this->args['padding'] ) && '' !== $this->args['padding'] ) {
				$attr['style'] .= 'padding: ' . $this->args['padding'] . ';';
			}

			if ( isset( $this->args['font_size'] ) && '' !== $this->args['font_size'] ) {
				$attr['style'] .= 'font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			// Border.
			if ( $this->args['heading_last_border_color'] && $this->args['heading_last_border_size'] && $this->args['heading_last_border_style'] ) {
				$border_position = ( 'all' !== $this->args['heading_last_border_position'] ) ? '-' . $this->args['heading_last_border_position'] : '';
				$border_size     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_last_border_size'], 'px' );
				$attr['style']  .= 'border' . $border_position . ':' . $border_size . ' ' . $this->args['heading_last_border_style'] . ' ' . $this->args['heading_last_border_color'] . ';';
			}

			// Border radius.
			$border_radius_top_left     = ( isset( $this->args['last_border_radius_top_left'] ) && '' !== $this->args['last_border_radius_top_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['last_border_radius_top_left'], 'px' ) : '0px';
			$border_radius_top_right    = ( isset( $this->args['last_border_radius_top_right'] ) && '' !== $this->args['last_border_radius_top_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['last_border_radius_top_right'], 'px' ) : '0px';
			$border_radius_bottom_right = ( isset( $this->args['last_border_radius_bottom_right'] ) && '' !== $this->args['last_border_radius_bottom_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['last_border_radius_bottom_right'], 'px' ) : '0px';
			$border_radius_bottom_left  = ( isset( $this->args['last_border_radius_bottom_left'] ) && '' !== $this->args['last_border_radius_bottom_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['last_border_radius_bottom_left'], 'px' ) : '0px';
			$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
			$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

			if ( '' !== $border_radius ) {
				$attr['style'] .= 'border-radius: ' . $border_radius . ';';
			}

			// Typography.
			if ( isset( $this->args['typography_heading_last'] ) && '' !== $this->args['typography_heading_last'] ) {
				$attr['style'] .= elegant_get_google_font_styling( $this->args, 'typography_heading_last' );
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
			wp_enqueue_style( 'infi-elegant-dual-style-heading' );
		}
	}

	new EEWPB_Dual_Style_Heading();
} // End if().

/**
 * Map shortcode for dual_style_heading.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_dual_style_heading() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Dual Style Heading', 'elegant-elements' ),
			'shortcode' => 'iee_dual_style_heading',
			'icon'      => 'fa-h-square fas dual-style-heading-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text for the heading to be displayed in first place.', 'elegant-elements' ),
					'param_name'  => 'heading_first',
					'value'       => esc_attr__( 'Elegant Elements', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading Text 2', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text for the heading to be displayed in second place.', 'elegant-elements' ),
					'param_name'  => 'heading_last',
					'value'       => esc_attr__( 'for WPBakery Page Builder', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Tag', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the heading tag, H1-H6, Div or Span.', 'elegant-elements' ),
					'param_name'  => 'heading_tag',
					'value'       => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'DIV',
						'span' => 'SPAN',
					),
					'std'         => 'h2',
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
					'description' => esc_attr__( 'Align the heading to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'             => 'ee_dimensions',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Padding', 'elegant-elements' ),
					'description'      => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'padding',
					'value'            => array(
						'padding_top'    => '',
						'padding_right'  => '',
						'padding_bottom' => '',
						'padding_left'   => '',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Gap', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gap between two heading texts. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_gap',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
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
					'heading'     => esc_attr__( 'Heading 1 Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the first heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading_first',
					'value'       => '',
					'group'       => 'Typography',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Heading 2 Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the second heading text.', 'elegant-elements' ),
					'param_name'  => 'typography_heading_last',
					'value'       => '',
					'group'       => 'Typography',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_first_text_color',
					'value'       => '#ffffff',
					'default'     => '',
					'description' => esc_attr__( 'Controls the text color for the first heading text.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'heading_first_background_color',
					'value'       => '#333333',
					'default'     => '',
					'description' => esc_attr__( 'Controls the background color for the first heading text.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'heading_first_background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Controls the second background color for the first heading text, that will help to form the gradient background.', 'elegant-elements' ),
					'group'       => 'Heading 1 Style',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
					'param_name'  => 'heading_first_gradient_type',
					'std'         => 'vertical',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
					'param_name'  => 'heading_first_gradient_direction',
					'std'         => '0deg',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'value'       => array(
						'0deg'   => esc_attr__( 'Left to Right', 'elegant-elements' ),
						'45deg'  => esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ),
						'-45deg' => esc_attr__( 'Top - Left Angle', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'            => 'heading_first_gradient_type',
						'value_not_equal_to' => 'vertical',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the heading. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_first_border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_style',
					'std'         => 'solid',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_first_border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the border.', 'elegant-elements' ),
					'param_name'  => 'heading_first_border_position',
					'std'         => 'all',
					'group'       => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_first_border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'all'    => esc_attr__( 'All', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					),
				),
				array(
					'type'             => 'ee_dimensions',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'heading_first_border_radius',
					'value'            => array(
						'first_border_radius_top_left'     => '',
						'first_border_radius_top_right'    => '',
						'first_border_radius_bottom_left'  => '',
						'first_border_radius_bottom_right' => '',
					),
					'group'            => esc_attr__( 'Heading 1 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_last_text_color',
					'value'       => '#333333',
					'default'     => '',
					'description' => esc_attr__( 'Controls the text color for the last heading text.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'heading_last_background_color',
					'value'       => '#fbfbfb',
					'default'     => '',
					'description' => esc_attr__( 'Controls the background color for the last heading text.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'param_name'  => 'heading_last_background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Controls the second background color for the last heading text, that will help to form the gradient background.', 'elegant-elements' ),
					'group'       => 'Heading 2 Style',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
					'param_name'  => 'heading_last_gradient_type',
					'std'         => 'vertical',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
					'param_name'  => 'heading_last_gradient_direction',
					'std'         => '0deg',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'value'       => array(
						'0deg'   => esc_attr__( 'Left to Right', 'elegant-elements' ),
						'45deg'  => esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ),
						'-45deg' => esc_attr__( 'Top - Left Angle', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'            => 'heading_last_gradient_type',
						'value_not_equal_to' => 'vertical',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the heading. In pixels.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_last_border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_style',
					'std'         => 'solid',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_last_border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the border.', 'elegant-elements' ),
					'param_name'  => 'heading_last_border_position',
					'std'         => 'all',
					'group'       => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'heading_last_border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'all'    => esc_attr__( 'All', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					),
				),
				array(
					'type'             => 'ee_dimensions',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'heading_last_border_radius',
					'value'            => array(
						'last_border_radius_top_left'     => '',
						'last_border_radius_top_right'    => '',
						'last_border_radius_bottom_left'  => '',
						'last_border_radius_bottom_right' => '',
					),
					'group'            => esc_attr__( 'Heading 2 Style', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_dual_style_heading', 99 );
