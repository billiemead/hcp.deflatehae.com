<?php
if ( ! class_exists( 'EEWPB_Carousel' ) && elegant_is_element_enabled( 'iee_carousel' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Carousel {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Carousel counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $carousel_counter = 1;

		/**
		 * Carousel item counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $carousel_item_counter = 1;

		/**
		 * Carousel Items.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $carousel_items = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-carousel', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-carousel-item', array( $this, 'child_attr' ) );

			add_shortcode( 'iee_carousel', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_carousel_item', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'fade'                => 'slide',
					'slides_to_show'      => 3,
					'slides_to_scroll'    => 1,
					'speed'               => 300,
					'variable_width'      => 'no',
					'infinite'            => 'no',
					'accessibility'       => 'yes',
					'adaptive_height'     => 'yes',
					'arrows'              => 'yes',
					'next_arrow_icon'     => '',
					'prev_arrow_icon'     => '',
					'arrow_color'         => '#666666',
					'arrow_font_size'     => 24,
					'dots'                => 'yes',
					'dots_icon_class'     => '',
					'dots_color'          => '#666666',
					'dots_color_active'   => '#333333',
					'dots_font_size'      => 18,
					'autoplay'            => 'yes',
					'autoplay_speed'      => 3,
					'center_padding'      => 100,
					'draggable'           => 'yes',
					'item_padding'        => '',
					'item_margin'         => '',
					'pause_on_hover'      => 'yes',
					'pause_on_dots_hover' => 'yes',
					'responsive'          => 'yes',
					'border_size'         => 0,
					'border_color'        => '#dddddd',
					'border_style'        => 'solid',
					'border_radius'       => 'square',
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$args       = $defaults;
			$this->args = $args;

			$html = '';

			$args['ipad_landscape_slides_to_show']   = '3';
			$args['ipad_portrait_slides_to_show']    = '2';
			$args['mobile_landscape_slides_to_show'] = '1';

			if ( '' !== locate_template( 'templates/carousel/elegant-carousel-parent.php' ) ) {
				include locate_template( 'templates/carousel/elegant-carousel-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/carousel/elegant-carousel-parent.php';
			}

			$this->carousel_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'class' => '',
					'id'    => '',
				),
				$args
			);

			$args             = $defaults;
			$this->child_args = $args;

			$child_html = '';

			if ( '' !== locate_template( 'templates/carousel/elegant-carousel-child.php' ) ) {
				include locate_template( 'templates/carousel/elegant-carousel-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/carousel/elegant-carousel-child.php';
			}

			$this->carousel_item_counter++;

			return $child_html;
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
				'class' => 'elegant-carousel-container elegant-carousel-' . $this->carousel_counter,
				'style' => '',
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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function child_attr() {
			$attr = array(
				'class' => 'elegant-carousel-item elegant-carousel-item-' . $this->carousel_item_counter,
				'style' => '',
			);

			if ( $this->child_args['class'] ) {
				$attr['class'] .= ' ' . $this->child_args['class'];
			}

			if ( $this->child_args['id'] ) {
				$attr['id'] = $this->child_args['id'];
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_scripts() {
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-carousel',
				$eewpb_js_folder_url . '/infi-elegant-carousel.min.js',
				$elegant_js_folder_path . '/infi-elegant-carousel.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-carousel' );
		}
	}

	new EEWPB_Carousel();
} // End if().


/**
 * Map shortcode for carousel.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_carousel() {

	$parent_args = array(
		'name'             => esc_attr__( 'Elegant Carousel', 'elegant-elements' ),
		'shortcode'        => 'iee_carousel',
		'icon'             => 'fas fa-photo-video',
		'as_parent'        => array(
			'only' => 'iee_carousel_item',
		),
		'is_container'     => true,
		'js_view'          => 'VcColumnView',
		'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/elegant-carousel.js',
		'default_content'  => '[iee_carousel_item title="' . esc_attr__( 'Carousel item 1', 'elegant-elements' ) . '"][vc_column_text]Carousel item 1[/vc_column_text][/iee_carousel_item]
							[iee_carousel_item title="' . esc_attr__( 'Carousel item 2', 'elegant-elements' ) . '"][vc_column_text]Carousel item 2[/vc_column_text][/iee_carousel_item]
							[iee_carousel_item title="' . esc_attr__( 'Carousel item 3', 'elegant-elements' ) . '"][vc_column_text]Carousel item 3[/vc_column_text][/iee_carousel_item]
							[iee_carousel_item title="' . esc_attr__( 'Carousel item 4', 'elegant-elements' ) . '"][vc_column_text]Carousel item 4[/vc_column_text][/iee_carousel_item]
							',
		'params'           => array(
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Slide effect', 'elegant-elements' ),
				'param_name'  => 'fade',
				'std'         => 'slide',
				'value'       => array(
					'slide' => esc_attr__( 'Slide', 'elegant-elements' ),
					'fade'  => esc_attr__( 'Fade', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Slide will allow you to select number of slides to show. Fade will display only 1 slide at a time.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Slides to show', 'elegant-elements' ),
				'param_name'  => 'slides_to_show',
				'value'       => '3',
				'min'         => '1',
				'max'         => '10',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'fade',
					'value'   => array( 'slide' ),
				),
				'description' => esc_attr__( 'Number of slides to show at a time.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Slides to scroll', 'elegant-elements' ),
				'param_name'  => 'slides_to_scroll',
				'value'       => '3',
				'min'         => '1',
				'max'         => '10',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'fade',
					'value'   => array( 'slide' ),
				),
				'description' => esc_attr__( 'Number of slides to scroll at a time. Set less than number of slides to show.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Slide transition speed', 'elegant-elements' ),
				'param_name'  => 'speed',
				'value'       => '300',
				'min'         => '100',
				'max'         => '1500',
				'step'        => '50',
				'description' => esc_attr__( 'Transition speed in mili-seconds.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable Variable Width', 'elegant-elements' ),
				'param_name'  => 'variable_width',
				'std'         => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'The first and last slides will display as cut off.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Infinite Loop', 'elegant-elements' ),
				'param_name'  => 'infinite',
				'std'         => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables infinite looping.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Keyboard Accessibility', 'elegant-elements' ),
				'param_name'  => 'accessibility',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables tabbing and arrow key navigation.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Adaptive Height', 'elegant-elements' ),
				'param_name'  => 'adaptive_height',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Adapts slider height to the current slide.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Next / Previous Arrows', 'elegant-elements' ),
				'param_name'  => 'arrows',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable next / previous arrows.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Next arrow icon name', 'elegant-elements' ),
				'param_name'  => 'next_arrow_icon',
				'value'       => '',
				'dependency'  => array(
					'element' => 'arrows',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class name to use for next icon arrow. eg. fa-arrow-right', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Previous arrow icon name', 'elegant-elements' ),
				'param_name'  => 'prev_arrow_icon',
				'value'       => '',
				'dependency'  => array(
					'element' => 'arrows',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class name to use for previous icon arrow. eg. fa-arrow-left', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Arrow icon color', 'elegant-elements' ),
				'param_name'  => 'arrow_color',
				'value'       => '#666666',
				'dependency'  => array(
					'element' => 'arrows',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the next / previous icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Arrow icon font size', 'elegant-elements' ),
				'param_name'  => 'arrow_font_size',
				'value'       => '24',
				'min'         => '12',
				'max'         => '72',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'arrows',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the next / previous icon font size. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Navigation Dots', 'elegant-elements' ),
				'param_name'  => 'dots',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Current slide navigation dots.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Dots icon class', 'elegant-elements' ),
				'param_name'  => 'dots_icon_class',
				'value'       => '',
				'dependency'  => array(
					'element' => 'dots',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Provide FontAwesome icon class to use for navigation dots icon. eg. fa-circle', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Dots icon color', 'elegant-elements' ),
				'param_name'  => 'dots_color',
				'value'       => '#666666',
				'dependency'  => array(
					'element' => 'dots',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the navigation dots icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active dot icon color', 'elegant-elements' ),
				'param_name'  => 'dots_color_active',
				'value'       => '#333333',
				'dependency'  => array(
					'element' => 'dots',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the active slide dots icon color.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Dots icon font size', 'elegant-elements' ),
				'param_name'  => 'dots_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '72',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'dots',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the navigation dots icon font size. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Autoplay Slides', 'elegant-elements' ),
				'param_name'  => 'autoplay',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enable Auto play of slides.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Autoplay Speed', 'elegant-elements' ),
				'param_name'  => 'autoplay_speed',
				'value'       => '3',
				'min'         => '1',
				'max'         => '15',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'autoplay',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Auto play change interval. In seconds.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Side Padding', 'elegant-elements' ),
				'param_name'  => 'center_padding',
				'value'       => '100',
				'min'         => '0',
				'max'         => '500',
				'step'        => '1',
				'dependency'  => array(
					'element' => 'fade',
					'value'   => array( 'slide' ),
				),
				'description' => esc_attr__( 'Side padding when in center mode. In pixels (px).', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Draggable Slides', 'elegant-elements' ),
				'param_name'  => 'draggable',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables desktop dragging.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_dimensions',
				'heading'     => esc_attr__( 'Slide item padding ', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the padding around the slider items. Enter values including any valid CSS unit, ex: 15px, 15px, 15px, 15px.', 'elegant-elements' ),
				'param_name'  => 'item_padding',
				'value'       => '',
			),
			array(
				'type'        => 'ee_dimensions',
				'heading'     => esc_attr__( 'Slide item margin ', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the margin around the slider items. Enter values including any valid CSS unit, ex: 15px, 15px, 15px, 15px.', 'elegant-elements' ),
				'param_name'  => 'item_margin',
				'value'       => '',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Pause on hover', 'elegant-elements' ),
				'param_name'  => 'pause_on_hover',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Pauses autoplay on hover.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Pause on dots hover', 'elegant-elements' ),
				'param_name'  => 'pause_on_dots_hover',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Pauses autoplay when a dot is hovered.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable responsive breakpoints', 'elegant-elements' ),
				'param_name'  => 'responsive',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Enables responsive breakpoints for slider. Slider will auto adjusted on devices with the standard setting.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border size of the carousel item. In pixels.', 'elegant-elements' ),
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
				'value'       => '#dddddd',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'std'         => 'solid',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Radius', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border radius.', 'elegant-elements' ),
				'param_name'  => 'border_radius',
				'std'         => 'square',
				'value'       => array(
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
	);

	$child_args = array(
		'name'            => esc_attr__( 'Carousel Item', 'elegant-elements' ),
		'shortcode'       => 'iee_carousel_item',
		'is_container'    => true,
		'content_element' => true,
		'as_child'        => array(
			'only' => 'iee_carousel',
		),
		'params'          => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Carousel Title', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'Carousel item', 'elegant-elements' ),
				'placeholder' => true,
				'description' => esc_attr__( 'The title is only used as placeholder to identify the carousel item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textarea_html',
				'heading'     => esc_attr__( 'Carousel Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter content to be displayed in this carousel item.', 'elegant-elements' ),
				'param_name'  => 'content',
				'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
				'placeholder' => true,
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
	);

	elegant_elements_map(
		$parent_args
	);

	elegant_elements_map(
		$child_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_carousel', 99 );

// Container content element should extend WPBakeryShortCodesContainer class to inherit all required functionality.
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Iee_Carousel extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Iee_Carousel_Item extends WPBakeryShortCode {
	}
}
