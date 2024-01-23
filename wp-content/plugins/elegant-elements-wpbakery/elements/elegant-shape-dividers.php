<?php
if ( ! class_exists( 'EEWPB_Shape_Dividers' ) && elegant_is_element_enabled( 'iee_shape_divider' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Shape_Dividers {

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
			add_filter( 'eewpb_attr_elegant-shape-divider', array( $this, 'attr' ) );
			add_shortcode( 'iee_shape_divider', array( $this, 'render' ) );
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

			// Enqueue script.
			wp_enqueue_script( 'infi-elegant-shape-divider' );

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'shape_divider_position' => 'top',
					'top_divider_type'       => 'fan',
					'bottom_divider_type'    => 'fan',
					'height'                 => 150,
					'shape_fill_color'       => '#0473aa',
					'shape_bg_color'         => '#ffffff',
					'enable_fullwidth'       => 'yes',
					'top_margin'             => 0,
					'send_to_back'           => 'no',
					'hide_on_mobile'         => elegant_elements_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/shape-divider/elegant-shape-divider.php' ) ) {
				include locate_template( 'templates/shape-divider/elegant-shape-divider.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/shape-divider/elegant-shape-divider.php';
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
				'class' => 'elegant-shape-divider-wrap',
				'style' => '',
			);

			if ( 'top' === $this->args['shape_divider_position'] ) {
				$shape_divider_style = $this->args['top_divider_type'];
				$attr['class']      .= ' top_divider_visible';
				$attr['class']      .= ' position_top';
			} else {
				$shape_divider_style = $this->args['bottom_divider_type'];
				$attr['class']      .= ' bottom_divider_visible';
				$attr['class']      .= ' position_bottom';
			}

			if ( 'yes' === $this->args['enable_fullwidth'] ) {
				$attr['class'] .= ' elegant-shape-divider-fullwidth';
			}

			$attr['data-style'] = $shape_divider_style;

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$attr['style'] = 'height:' . $height . ';';

			$top_margin     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['top_margin'], 'px' );
			$attr['style'] .= 'margin-top:' . $top_margin . ';';

			if ( 'yes' === $this->args['send_to_back'] ) {
				$attr['style'] .= 'z-index: -1;';
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
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-shape-divider' );
		}
	}

	new EEWPB_Shape_Dividers();
} // End if().

/**
 * Map shortcode for shape_divider.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_shape_divider() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Shape Divider', 'elegant-elements' ),
			'shortcode' => 'iee_shape_divider',
			'icon'      => 'fas fa-draw-polygon shape-divider-icon',
			'params'    => array(
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Shape Divider Position', 'elegant-elements' ),
					'param_name'  => 'shape_divider_position',
					'std'         => 'top',
					'value'       => array(
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the divider type position.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Choose Divider Type', 'elegant-elements' ),
					'param_name'  => 'top_divider_type',
					'std'         => 'fan',
					'value'       => array(
						'fan'               => esc_html__( 'Fan', 'elegant-elements' ),
						'fan2'              => esc_html__( 'Fan 2', 'elegant-elements' ),
						'slant-2'           => esc_html__( 'Slant', 'elegant-elements' ),
						'tilt'              => esc_html__( 'Tilt', 'elegant-elements' ),
						'tilt_flip'         => esc_html__( 'Tilt Flip', 'elegant-elements' ),
						'triangle'          => esc_html__( 'Triangle', 'elegant-elements' ),
						'triangle_op'       => esc_html__( 'Triangle Opacity', 'elegant-elements' ),
						'rocks'             => esc_html__( 'Rocks', 'elegant-elements' ),
						'rocks_op'          => esc_html__( 'Rocks Opacity', 'elegant-elements' ),
						'ramp'              => esc_html__( 'Ramp', 'elegant-elements' ),
						'ramp_op'           => esc_html__( 'Ramp Opacity', 'elegant-elements' ),
						'curve'             => esc_html__( 'Curve', 'elegant-elements' ),
						'curve_opacity'     => esc_html__( 'Curve Opacity', 'elegant-elements' ),
						'curve_asym'        => esc_html__( 'Curve Asym.', 'elegant-elements' ),
						'mountains'         => esc_html__( 'Mountains', 'elegant-elements' ),
						'wave1'             => esc_html__( 'Wave', 'elegant-elements' ),
						'wave2'             => esc_html__( 'Wave Opacity', 'elegant-elements' ),
						'waves'             => esc_html__( 'Waves', 'elegant-elements' ),
						'waves_opacity'     => esc_html__( 'Waves Opacity', 'elegant-elements' ),
						'waves_opacity_alt' => esc_html__( 'Waves Opacity 2', 'elegant-elements' ),
						'waves_opacity_3'   => esc_html__( 'Waves Opacity 3', 'elegant-elements' ),
						'asym'              => esc_html__( 'Asymetric 1', 'elegant-elements' ),
						'asym2'             => esc_html__( 'Asymetric 2', 'elegant-elements' ),
						'graph1'            => esc_html__( 'Graph 1', 'elegant-elements' ),
						'graph2'            => esc_html__( 'Graph 2', 'elegant-elements' ),
						'clouds'            => esc_html__( 'Clouds', 'elegant-elements' ),
						'clouds2'           => esc_html__( 'Clouds 2', 'elegant-elements' ),
						'clouds3'           => esc_html__( 'Clouds 3', 'elegant-elements' ),
						'speech'            => esc_html__( 'Speech', 'elegant-elements' ),
					),
					'icons'       => array(
						'fan'               => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/fan.jpg',
						'fan2'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/fan2.jpg',
						'slant-2'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/slant.jpg',
						'tilt'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/tilt.jpg',
						'tilt_flip'         => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/tilt-flip.jpg',
						'triangle'          => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/triangle.jpg',
						'triangle_op'       => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/triangle_opacity.jpg',
						'rocks'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/rocks.jpg',
						'rocks_op'          => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/rocks2.jpg',
						'ramp'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/ramp.jpg',
						'ramp_op'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/ramp_op.jpg',
						'curve'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/curve_down.jpg',
						'curve_opacity'     => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/curve_opacity.jpg',
						'curve_asym'        => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/curve_asym.jpg',
						'mountains'         => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/mountains.jpg',
						'wave1'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/wave.jpg',
						'wave2'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/wave_opacity.jpg',
						'waves'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/waves_no_opacity.jpg',
						'waves_opacity'     => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/waves.jpg',
						'waves_opacity_alt' => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/waves_opacity.jpg',
						'waves_opacity_3'   => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/waves_opacity_3.jpg',
						'asym'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/asym.jpg',
						'asym2'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/asym_2.jpg',
						'graph1'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/graph_1.jpg',
						'graph2'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/graph_2.jpg',
						'clouds'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/clouds.jpg',
						'clouds2'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/clouds_2.jpg',
						'clouds3'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/clouds_3.jpg',
						'speech'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/top/speech.jpg',
					),
					'dependency'  => array(
						'element' => 'shape_divider_position',
						'value'   => array( 'top' ),
					),
					'description' => esc_attr__( 'Divider shape appears at the top.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Choose Divider Type', 'elegant-elements' ),
					'param_name'  => 'bottom_divider_type',
					'std'         => 'fan',
					'value'       => array(
						'fan'               => esc_html__( 'Fan', 'elegant-elements' ),
						'fan2'              => esc_html__( 'Fan 2', 'elegant-elements' ),
						'slant-2'           => esc_html__( 'Slant', 'elegant-elements' ),
						'tilt'              => esc_html__( 'Tilt', 'elegant-elements' ),
						'triangle'          => esc_html__( 'Triangle', 'elegant-elements' ),
						'triangle_op'       => esc_html__( 'Triangle Opacity', 'elegant-elements' ),
						'rocks'             => esc_html__( 'Rocks', 'elegant-elements' ),
						'rocks_op'          => esc_html__( 'Rocks Opacity', 'elegant-elements' ),
						'ramp'              => esc_html__( 'Ramp', 'elegant-elements' ),
						'ramp_op'           => esc_html__( 'Ramp Opacity', 'elegant-elements' ),
						'curve'             => esc_html__( 'Curve', 'elegant-elements' ),
						'curve_opacity'     => esc_html__( 'Curve Opacity', 'elegant-elements' ),
						'curve_asym'        => esc_html__( 'Curve Asym.', 'elegant-elements' ),
						'mountains'         => esc_html__( 'Mountains', 'elegant-elements' ),
						'wave1'             => esc_html__( 'Wave', 'elegant-elements' ),
						'wave2'             => esc_html__( 'Wave Opacity', 'elegant-elements' ),
						'waves'             => esc_html__( 'Waves', 'elegant-elements' ),
						'waves_opacity'     => esc_html__( 'Waves Opacity', 'elegant-elements' ),
						'waves_opacity_alt' => esc_html__( 'Waves Opacity 2', 'elegant-elements' ),
						'waves_opacity_3'   => esc_html__( 'Waves Opacity 3', 'elegant-elements' ),
						'asym'              => esc_html__( 'Asymetric 1', 'elegant-elements' ),
						'asym2'             => esc_html__( 'Asymetric 2', 'elegant-elements' ),
						'graph1'            => esc_html__( 'Graph 1', 'elegant-elements' ),
						'graph2'            => esc_html__( 'Graph 2', 'elegant-elements' ),
						'clouds'            => esc_html__( 'Clouds', 'elegant-elements' ),
						'clouds2'           => esc_html__( 'Clouds 2', 'elegant-elements' ),
						'clouds3'           => esc_html__( 'Clouds 3', 'elegant-elements' ),
						'speech'            => esc_html__( 'Speech', 'elegant-elements' ),
					),
					'icons'       => array(
						'fan'               => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/fan.jpg',
						'fan2'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/fan2.jpg',
						'slant-2'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/slant.jpg',
						'tilt'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/tilt.jpg',
						'triangle'          => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/triangle.jpg',
						'triangle_op'       => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/triangle_opacity.jpg',
						'rocks'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/rocks.jpg',
						'rocks_op'          => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/rocks2.jpg',
						'ramp'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/ramp.jpg',
						'ramp_op'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/ramp_op.jpg',
						'curve'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/curve_down.jpg',
						'curve_opacity'     => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/curve_opacity.jpg',
						'curve_asym'        => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/curve_asym.jpg',
						'mountains'         => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/mountains.jpg',
						'wave1'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/wave.jpg',
						'wave2'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/wave_opacity.jpg',
						'waves'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/waves_no_opacity.jpg',
						'waves_opacity'     => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/waves.jpg',
						'waves_opacity_alt' => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/waves_opacity.jpg',
						'waves_opacity_3'   => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/waves_opacity_3.jpg',
						'asym'              => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/asym.jpg',
						'asym2'             => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/asym_2.jpg',
						'graph1'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/graph_1.jpg',
						'graph2'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/graph_2.jpg',
						'clouds'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/clouds.jpg',
						'clouds2'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/clouds_2.jpg',
						'clouds3'           => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/clouds_3.jpg',
						'speech'            => EEWPB_PLUGIN_URL . '/assets/admin/img/shape_dividers/bottom/speech.jpg',
					),
					'dependency'  => array(
						'element' => 'shape_divider_position',
						'value'   => array( 'bottom' ),
					),
					'description' => esc_attr__( 'Divider shape appears at the bottom.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the shape divider. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '150',
					'min'         => '10',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Shape Fill Color', 'elegant-elements' ),
					'param_name'  => 'shape_fill_color',
					'value'       => '#0473aa',
					'description' => esc_attr__( 'Controls the color of the SVG fill in the shape divider.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Shape Background Color', 'elegant-elements' ),
					'param_name'  => 'shape_bg_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the color of the empty area in the shape divider.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Margin Top', 'elegant-elements' ),
					'description' => esc_attr__( 'Control the spacing from top of the shape divider. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'top_margin',
					'value'       => '0',
					'min'         => '-500',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Send to Back', 'elegant-elements' ),
					'param_name'  => 'send_to_back',
					'std'         => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Make the shape divider appear behind the element if negative margin is used.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Fullwidth Shape Divider', 'elegant-elements' ),
					'param_name'  => 'enable_fullwidth',
					'std'         => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Enable to set the shape divider to full browser width.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_shape_divider', 99 );
