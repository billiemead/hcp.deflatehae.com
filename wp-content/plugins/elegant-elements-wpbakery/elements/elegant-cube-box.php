<?php
if ( ! class_exists( 'EEWPB_Cube_Box' ) && elegant_is_element_enabled( 'iee_cube_box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Cube_Box {

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

			add_filter( 'eewpb_attr_elegant-cube-box', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-cube-box-front-content', array( $this, 'front_content_attr' ) );
			add_filter( 'eewpb_attr_elegant-cube-box-back-content', array( $this, 'back_content_attr' ) );

			add_shortcode( 'iee_cube_box', array( $this, 'render' ) );
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
					'cube_direction'             => 'left',
					'front_content'              => '',
					'box_height'                 => '320',
					'front_background_color'     => '#2196F3',
					'back_background_color'      => '#03A9F4',
					'border_position'            => 'all',
					'border_size'                => '0',
					'border_color'               => '',
					'border_style'               => 'solid',
					'border_radius'              => '',
					'border_radius_top_left'     => '',
					'border_radius_top_right'    => '',
					'border_radius_bottom_right' => '',
					'border_radius_bottom_left'  => '',
					'back_border'                => 'yes',
					'back_border_color'          => '',
					'hide_on_mobile'             => elegant_elements_default_visibility( 'string' ),
					'class'                      => '',
					'id'                         => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/cube-box/elegant-cube-box.php' ) ) {
				include locate_template( 'templates/cube-box/elegant-cube-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/cube-box/elegant-cube-box.php';
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
				'class' => 'elegant-cube-box',
				'style' => '',
			);

			$attr['class'] .= ' cube-direction-' . $this->args['cube_direction'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['box_height'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';

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
		public function front_content_attr() {
			$attr = array(
				'class' => 'elegant-cube-box-front-content',
				'style' => '',
			);

			if ( isset( $this->args['front_background_color'] ) ) {
				$attr['style'] .= 'background:' . $this->args['front_background_color'] . ';';
			}

			if ( isset( $this->args['border_size'] ) && 0 !== $this->args['border_size'] ) {
				$attr['style'] .= eewpb_get_border_style( $this->args );
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
		public function back_content_attr() {
			$attr = array(
				'class' => 'elegant-cube-box-back-content',
				'style' => '',
			);

			if ( isset( $this->args['back_background_color'] ) ) {
				$attr['style'] .= 'background:' . $this->args['back_background_color'] . ';';
			}

			if ( $this->args['border_size'] ) {
				$this->args['border_color'] = $this->args['back_border_color'];
				$this->args['border_size']  = ( 'yes' === $this->args['back_border'] ) ? $this->args['border_size'] : 0;
				$attr['style']             .= eewpb_get_border_style( $this->args );
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
			wp_enqueue_style( 'infi-elegant-cube-box' );
		}
	}

	new EEWPB_Cube_Box();
} // End if().

/**
 * Map shortcode for cube_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_cube_box() {

	elegant_elements_map(
		array(
			'name'            => esc_attr__( 'Elegant Cube Box', 'elegant-elements' ),
			'shortcode'       => 'iee_cube_box',
			'icon'            => 'fa-cube fas cube-box-icon',
			'allow_generator' => true,
			'params'          => array(
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Cube Direction', 'elegant-elements' ),
					'description' => esc_attr__( 'Select in which direction the cube should open.', 'elegant-elements' ),
					'param_name'  => 'cube_direction',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'icons'       => elegant_get_direction_icons(),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Box Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the cube box. Width will be set to 100% of its container column. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'box_height',
					'value'       => '320',
					'min'         => '100',
					'max'         => '600',
					'step'        => '1',
				),
				array(
					'type'        => 'textarea_raw_html',
					'heading'     => esc_attr__( 'Content on Front Side', 'elegant-elements' ),
					'param_name'  => 'front_content',
					'value'       => '',
					'description' => esc_attr__( 'Enter the content to be displayed on the front side of the cube box. You can use shortcodes as well.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Content on Back Side', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter content to be displayed when the cube is turned to the back side.', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Front Side Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the front side box.', 'elegant-elements' ),
					'param_name'  => 'front_background_color',
					'value'       => '#2196F3',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Back Side Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the back side box.', 'elegant-elements' ),
					'param_name'  => 'back_background_color',
					'value'       => '#03A9F4',
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
					'std'         => 'solid',
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
					'type'             => 'ee_dimensions',
					'remove_from_atts' => true,
					'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description'      => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'       => 'border_radius',
					'value'            => array(
						'border_radius_top_left'     => '',
						'border_radius_top_right'    => '',
						'border_radius_bottom_right' => '',
						'border_radius_bottom_left'  => '',
					),
					'group'            => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Back Side Border', 'elegant-elements' ),
					'description' => esc_attr__( 'Apply border styling to back side as well? You can control the border color for the back side box.', 'elegant-elements' ),
					'param_name'  => 'back_border',
					'std'         => 'yes',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'            => 'border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Back Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color for the back side box.', 'elegant-elements' ),
					'param_name'  => 'back_border_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'back_border',
						'value_not_equal_to' => 'no',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_cube_box', 99 );
