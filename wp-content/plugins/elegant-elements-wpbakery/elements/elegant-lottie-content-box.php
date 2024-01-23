<?php
if ( elegant_is_element_enabled( 'iee_lottie_content_box' ) && ! class_exists( 'EEWPB_Lottie_Content_Box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.7.0
	 */
	class EEWPB_Lottie_Content_Box {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.7.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.7.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-lottie-content-box', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-content-box-icon', array( $this, 'icon_attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-icon-player', array( $this, 'player_attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-content-box-content', array( $this, 'content_attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-content-box-heading', array( $this, 'heading_attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-content-box-description', array( $this, 'description_attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-content-box-link-text', array( $this, 'link_text_attr' ) );

			add_shortcode( 'iee_lottie_content_box', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.7.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			// Enqueue Lottie Player js.
			wp_enqueue_script( 'infi-lottie-player' );

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'heading_text'                    => '',
					'heading_size'                    => 'h3',
					'heading_font_size'               => '21',
					'description_font_size'           => '16',
					'link_text_font_size'             => '15',
					'description_text'                => '',
					'icon_url'                        => '',
					'icon_height'                     => 64,
					'icon_width'                      => 64,
					'animation_mode'                  => 'normal',
					'animation_play'                  => 'autoplay',
					'animation_loop'                  => 'yes',
					'icon_position'                   => 'content_top',
					'content_alignment'               => 'left',
					'icon_alignment'                  => 'left',
					'box_background_color'            => '',
					'heading_text_color'              => '#333333',
					'content_text_color'              => '#333333',
					'box_padding_top'                 => '25px',
					'box_padding_right'               => '25px',
					'box_padding_bottom'              => '25px',
					'box_padding_left'                => '25px',
					'border_size'                     => '0',
					'border_color'                    => '',
					'border_style'                    => 'solid',
					'border_radius'                   => '',
					'border_radius_top_left'          => '',
					'border_radius_top_right'         => '',
					'border_radius_bottom_right'      => '',
					'border_radius_bottom_left'       => '',
					'icon_border_size'                => '0',
					'icon_background_color'           => '#ffffff',
					'icon_border_color'               => '',
					'icon_border_style'               => 'solid',
					'icon_border_radius'              => '',
					'icon_border_radius_top_left'     => '',
					'icon_border_radius_top_right'    => '',
					'icon_border_radius_bottom_right' => '',
					'icon_border_radius_bottom_left'  => '',
					'icon_padding_top'                => '15px',
					'icon_padding_right'              => '15px',
					'icon_padding_bottom'             => '15px',
					'icon_padding_left'               => '15px',
					'link_type'                       => 'text',
					'link_text'                       => esc_attr__( 'Read More', 'elegant-elements' ),
					'link_url'                        => '#',
					'link_target'                     => '',
					'link_text_color'                 => '',
					'element_content'                 => '',
					'hide_on_mobile'                  => elegant_elements_default_visibility( 'string' ),
					'class'                           => '',
					'id'                              => '',
				),
				$args
			);

			// Set icon padding.
			$icon_padding_values           = array();
			$icon_padding_values['top']    = ( isset( $args['icon_padding_top'] ) && '' !== $args['icon_padding_top'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['icon_padding_top'], 'px' ) : $defaults['icon_padding_top'];
			$icon_padding_values['right']  = ( isset( $args['icon_padding_right'] ) && '' !== $args['icon_padding_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['icon_padding_right'], 'px' ) : $defaults['icon_padding_right'];
			$icon_padding_values['bottom'] = ( isset( $args['icon_padding_bottom'] ) && '' !== $args['icon_padding_bottom'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['icon_padding_bottom'], 'px' ) : $defaults['icon_padding_bottom'];
			$icon_padding_values['left']   = ( isset( $args['icon_padding_left'] ) && '' !== $args['icon_padding_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['icon_padding_left'], 'px' ) : $defaults['icon_padding_left'];

			$defaults['icon_padding'] = implode( ' ', $icon_padding_values );
			$defaults['icon_padding'] = trim( $defaults['icon_padding'] );

			// Set box padding.
			$box_padding_values           = array();
			$box_padding_values['top']    = ( isset( $args['box_padding_top'] ) && '' !== $args['box_padding_top'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['box_padding_top'], 'px' ) : $defaults['box_padding_top'];
			$box_padding_values['right']  = ( isset( $args['box_padding_right'] ) && '' !== $args['box_padding_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['box_padding_right'], 'px' ) : $defaults['box_padding_right'];
			$box_padding_values['bottom'] = ( isset( $args['box_padding_bottom'] ) && '' !== $args['box_padding_bottom'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['box_padding_bottom'], 'px' ) : $defaults['box_padding_bottom'];
			$box_padding_values['left']   = ( isset( $args['box_padding_left'] ) && '' !== $args['box_padding_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $args['box_padding_left'], 'px' ) : $defaults['box_padding_left'];

			$defaults['box_padding'] = implode( ' ', $box_padding_values );
			$defaults['box_padding'] = trim( $defaults['box_padding'] );

			$defaults = apply_filters( 'fusion_builder_default_args', $defaults, 'iee_lottie_content_box', $args );

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/lottie-content-box/elegant-lottie-content-box.php' ) ) {
				include locate_template( 'templates/lottie-content-box/elegant-lottie-content-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/lottie-content-box/elegant-lottie-content-box.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box',
				'style' => '',
			);

			$attr['class'] .= ' icon-position-' . $this->args['icon_position'];
			$attr['class'] .= ' link-type-' . $this->args['link_type'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['border_size'] ) && 0 !== $this->args['border_size'] ) {
				$border_args    = array(
					'border_size'                => $this->args['border_size'],
					'border_color'               => $this->args['border_color'],
					'border_style'               => $this->args['border_style'],
					'border_radius_top_left'     => $this->args['border_radius_top_left'],
					'border_radius_top_right'    => $this->args['border_radius_top_right'],
					'border_radius_bottom_right' => $this->args['border_radius_bottom_right'],
					'border_radius_bottom_left'  => $this->args['border_radius_bottom_left'],
					'border_position'            => 'all',
				);
				$attr['style'] .= eewpb_get_border_style( $border_args );
			}

			$padding        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding'], 'px' );
			$attr['style'] .= 'padding:' . $padding . ';';

			if ( '' !== $this->args['box_background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['box_background_color'] . ';';
			}

			if ( 'border_top' === $this->args['icon_position'] ) {
				$icon_width     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_width'], 'px' );
				$attr['style'] .= 'margin-top:' . $icon_width . ';';
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
		 * @since 1.7.0
		 * @return array
		 */
		public function icon_attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box-icon',
				'style' => '',
			);

			$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_height'], 'px' );
			$width          = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_width'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';

			if ( isset( $this->args['icon_border_size'] ) && 0 !== $this->args['icon_border_size'] ) {
				$border_args = array(
					'border_size'                => $this->args['icon_border_size'],
					'border_color'               => $this->args['icon_border_color'],
					'border_style'               => $this->args['icon_border_style'],
					'border_radius_top_left'     => $this->args['icon_border_radius_top_left'],
					'border_radius_top_right'    => $this->args['icon_border_radius_top_right'],
					'border_radius_bottom_right' => $this->args['icon_border_radius_bottom_right'],
					'border_radius_bottom_left'  => $this->args['icon_border_radius_bottom_left'],
					'border_position'            => 'all',
				);

				$attr['style'] .= eewpb_get_border_style( $border_args );
			}

			if ( '' !== $this->args['icon_background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['icon_background_color'] . ';';
			}

			$padding        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_padding'], 'px' );
			$attr['style'] .= 'padding:' . $padding . '; box-sizing: content-box;';

			$icon_width       = Elegant_Elements_WPBakery::validate_shortcode_attr_value( ( $this->args['icon_width'] / 2 ), 'px' );
			$icon_width_full  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_width'], 'px' );
			$box_padding_top  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding_top'], 'px' );
			$box_padding_left = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding_left'], 'px' );
			$icon_padding_top = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_padding_top'], 'px' );
			$icon_border      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( ( $this->args['icon_border_size'] / 2 ), 'px' );
			$box_border       = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_size'], 'px' );

			$attr['class'] .= ' elegant-align-' . $this->args['icon_alignment'];

			if ( 'border_top' === $this->args['icon_position'] ) {
				$attr['style'] .= 'margin-top: -' . ( ( '' === $box_padding_top ? 15 : (int) $box_padding_top ) + (int) $icon_width + (int) ( '' === $icon_padding_top ? 1 : (int) $icon_padding_top ) + (int) $icon_border ) . 'px;';
				$attr['style'] .= 'margin-bottom: ' . $box_padding_top . ';';
			}

			if ( 'border_top' === $this->args['icon_position'] || 'content_top' === $this->args['icon_position'] ) {
				if ( 'right' === $this->args['icon_alignment'] ) {
					$attr['style'] .= 'right: calc( -100% + ' . $icon_width_full . ' + ' . $box_padding_left . ' );';
				}
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function player_attr() {
			$attr = array(
				'src'        => $this->args['icon_url'],
				'background' => $this->args['icon_background_color'],
				'speed'      => 1,
				'style'      => '',
			);

			if ( 'bounce' === $this->args['animation_mode'] ) {
				$attr['mode'] = 'bounce';
			}

			if ( 'yes' === $this->args['animation_loop'] ) {
				$attr['loop'] = true;
			}

			$attr[ $this->args['animation_play'] ] = true;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box-content',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['content_alignment'];

			$box_padding_top   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding_top'], 'px' );
			$box_padding_left  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding_left'], 'px' );
			$box_padding_right = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_padding_right'], 'px' );

			if ( 'left' === $this->args['icon_position'] ) {
				$icon_width     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_width'], 'px' );
				$attr['style'] .= 'width: calc( 100% - ' . $box_padding_left . ' - ' . $box_padding_right . ' - ' . $icon_width . ' );';
			}

			if ( 'right' === $this->args['icon_position'] ) {
				$icon_width     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_width'], 'px' );
				$attr['style'] .= 'width: calc( 100% - ' . $box_padding_left . ' - ' . $box_padding_right . ' - ' . $icon_width . ' );';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function heading_attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box-heading',
				'style' => '',
			);

			if ( isset( $this->args['heading_text_color'] ) ) {
				$attr['style'] .= 'color:' . $this->args['heading_text_color'] . ';';
			}

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box-description',
				'style' => '',
			);

			if ( isset( $this->args['content_text_color'] ) ) {
				$attr['style'] .= 'color:' . $this->args['content_text_color'] . ';';
			}

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function link_text_attr() {
			$attr = array(
				'class' => 'elegant-lottie-content-box-link-text',
				'style' => '',
			);

			if ( isset( $this->args['link_text_color'] ) ) {
				$attr['style'] .= 'color:' . $this->args['link_text_color'] . ';';
			}

			if ( isset( $this->args['link_text_font_size'] ) ) {
				$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['link_text_font_size'], 'px' );
				$attr['style'] .= 'font-size:' . $font_size . ';';
			}

			$link   = vc_build_link( $this->args['link_url'] );
			$url    = esc_url( $link['url'] );
			$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

			$attr['href']   = $url;
			$attr['target'] = $target;

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-lottie-content-box' );
		}
	}

	new EEWPB_Lottie_Content_Box();
} // End if().

/**
 * Map shortcode for lottie_content_box.
 *
 * @since 1.7.0
 * @return void
 */
function map_elegant_elements_wpbakery_lottie_content_box() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Lottie Content Box', 'elegant-elements' ),
			'shortcode' => 'iee_lottie_content_box',
			'icon'      => 'fa-bacon fas lottie-content-box-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Content Box Heading', 'elegant-elements' ),
					'param_name'  => 'heading_text',
					'value'       => esc_attr__( 'Content Box Heading', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter text for the content box heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the title size, H1-H6.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'default'     => 'h3',
					'dependency'  => array(
						'element'   => 'heading_text',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '21',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Content Box Description Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter description text for the content box.', 'elegant-elements' ),
					'param_name'  => 'description_text',
					'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '16',
					'min'         => '10',
					'max'         => '50',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_file_upload',
					'heading'     => esc_attr__( 'Lottie Icon JSON File.', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the Lottie JSON file or enter the Lottie image animation url from https://lottiefiles.com.', 'elegant-elements' ),
					'param_name'  => 'icon_url',
					'value'       => '',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Animation', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should start as autoplay or on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'animation_play',
					'value'       => array(
						'autoplay' => 'Autoplay',
						'hover'    => 'Hover',
					),
					'default'     => 'autoplay',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Animation in Loop', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should play continuously in loop or only once.', 'elegant-elements' ),
					'param_name'  => 'animation_loop',
					'value'       => array(
						'yes' => 'Yes',
						'no'  => 'No',
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Mode', 'elegant-elements' ),
					'description' => esc_attr__( 'Normal mode will play animation in one direction and the bounce mode will play animation in revese after the normal animation.', 'elegant-elements' ),
					'param_name'  => 'animation_mode',
					'value'       => array(
						'normal' => 'Normal',
						'bounce' => 'Bounce',
					),
					'default'     => 'normal',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Content Alignment', 'elegant-elements' ),
					'param_name'  => 'content_alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the heading, description, and the link text to left, right or center, when on the top of the content or on border.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Box Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the content box.', 'elegant-elements' ),
					'param_name'  => 'box_background_color',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color for the content box heading text.', 'elegant-elements' ),
					'param_name'  => 'heading_text_color',
					'value'       => '#333333',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Description Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color for the content box description text.', 'elegant-elements' ),
					'param_name'  => 'content_text_color',
					'value'       => '#333333',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size. In pixels.', 'elegant-elements' ),
					'param_name'  => 'border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'border_style',
					'default'     => 'solid',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
						'double' => esc_attr__( 'Double', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description' => __( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'border_radius',
					'value'       => array(
						'border_radius_top_left'     => '',
						'border_radius_top_right'    => '',
						'border_radius_bottom_right' => '',
						'border_radius_bottom_left'  => '',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Content Box Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'box_padding',
					'value'       => array(
						'box_padding_top'    => '25px',
						'box_padding_right'  => '25px',
						'box_padding_bottom' => '25px',
						'box_padding_left'   => '25px',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'       => 'ee_info',
					'heading'    => esc_attr__( 'Icon Design Options', 'elegant-elements' ),
					'param_name' => 'icon_design_options',
					'value'      => '',
					'group'      => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Icon Position', 'elegant-elements' ),
					'description' => __( 'Select the icon position.', 'elegant-elements' ),
					'param_name'  => 'icon_position',
					'default'     => 'content_top',
					'value'       => array(
						'content_top' => esc_attr__( 'On Top of Content', 'elegant-elements' ),
						'border_top'  => esc_attr__( 'On Top of Border', 'elegant-elements' ),
						'left'        => esc_attr__( 'Left Side of Content', 'elegant-elements' ),
						'right'       => esc_attr__( 'Right Side of Content', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Alignment', 'elegant-elements' ),
					'param_name'  => 'icon_alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Align the icon to left, right or center, when on the top of the content or on border.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'icon_position',
						'value'   => array( 'content_top', 'border_top' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this icon. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_width',
					'value'       => '64',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this icon. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_height',
					'value'       => '64',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size. In pixels.', 'elegant-elements' ),
					'param_name'  => 'icon_border_size',
					'value'       => '0',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the content box.', 'elegant-elements' ),
					'param_name'  => 'icon_background_color',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'icon_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'icon_border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'icon_border_style',
					'default'     => 'solid',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'icon_border_size',
						'value_not_equal_to' => '0',
					),
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
						'double' => esc_attr__( 'Double', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Icon Border Radius', 'elegant-elements' ),
					'description' => __( 'Controls the icon border radius. ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'icon_border_radius',
					'value'       => array(
						'icon_border_radius_top_left'     => '',
						'icon_border_radius_top_right'    => '',
						'icon_border_radius_bottom_right' => '',
						'icon_border_radius_bottom_left'  => '',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Icon Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'icon_padding',
					'value'       => array(
						'icon_padding_top'    => '15px',
						'icon_padding_right'  => '15px',
						'icon_padding_bottom' => '15px',
						'icon_padding_left'   => '15px',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Link Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the type of link that should show in the content box.', 'elegant-elements' ),
					'param_name'  => 'link_type',
					'default'     => 'text',
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
					'value'       => array(
						'text'    => esc_attr__( 'Link Text', 'elegant-elements' ),
						'button'  => esc_attr__( 'Button', 'elegant-elements' ),
						'content' => esc_attr__( 'Entire Content Box', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Link Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Insert the text to display as the link.', 'elegant-elements' ),
					'param_name'  => 'link_text',
					'value'       => esc_attr__( 'Read More', 'elegant-elements' ),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'text' ),
					),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_attr__( 'Link Url', 'elegant-elements' ),
					'description' => esc_attr__( "Add the link's url ex: http://example.com.", 'elegant-elements' ),
					'param_name'  => 'link_url',
					'value'       => '',
					'dependency'  => array(
						'element'            => 'link_type',
						'value_not_equal_to' => 'button',
					),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
					'description' => __( '_self = open in same window <br />_blank = open in new window', 'elegant-elements' ),
					'param_name'  => 'link_target',
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
					'value'       => array(
						'_self'  => esc_attr__( 'Same Window', 'elegant-elements' ),
						'_blank' => esc_attr__( 'New Window/Tab', 'elegant-elements' ),
					),
					'default'     => '_self',
					'dependency'  => array(
						'element'            => 'link_type',
						'value_not_equal_to' => 'button',
					),
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'element_tag' => 'iee_fancy_button',
					'description' => esc_attr__( 'Click the link to generate or edit button shortcode.', 'elegant-elements' ),
					'edit_title'  => 'Edit Button Settings',
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'button' ),
					),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Link Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color for the link text.', 'elegant-elements' ),
					'param_name'  => 'link_text_color',
					'value'       => '',
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'text' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Link Text Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for link text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'link_text_font_size',
					'value'       => '15',
					'min'         => '10',
					'max'         => '50',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'text' ),
					),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_lottie_content_box', 99 );
