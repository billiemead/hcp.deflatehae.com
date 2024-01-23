<?php
if ( ! class_exists( 'EEWPB_Big_Caps' ) && elegant_is_element_enabled( 'iee_big_caps' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Big_Caps {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Element counter.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-big-caps', array( $this, 'attr' ) );

			add_shortcode( 'iee_big_caps', array( $this, 'render' ) );
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
					'font_size'              => '16',
					'text_color'             => '',
					'first_letter_font_size' => '40',
					'caps_text_color'        => '',
					'alignment'              => 'left',
					'hide_on_mobile'         => elegant_elements_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/big-caps/elegant-big-caps.php' ) ) {
				include locate_template( 'templates/big-caps/elegant-big-caps.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/big-caps/elegant-big-caps.php';
			}

			$this->counter++;

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
				'class' => 'elegant-big-caps',
				'style' => '',
			);

			$attr['class'] .= ' elegant-big-caps-' . $this->counter;
			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['text_color'] . ';';
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
		 * @return string
		 */
		public function generate_style() {
			$style = '';

			$style .= '.elegant-big-caps.elegant-big-caps-' . $this->counter . ':first-letter {';
			$style .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['first_letter_font_size'], 'px' ) . ';';

			if ( '' !== $this->args['caps_text_color'] ) {
				$style .= 'color:' . $this->args['caps_text_color'] . ';';
			}

			$style .= '}';

			return $style;
		}
	}

	new EEWPB_Big_Caps();
} // End if().

/**
 * Map shortcode for big_caps.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_big_caps() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Big Caps', 'elegant-elements' ),
			'shortcode' => 'iee_big_caps',
			'icon'      => 'fa-font fas big-caps-icon',
			'params'    => array(
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Elegant Elements for WPBakery Page Builder', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter content for this element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the content. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '16',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for the content.', 'elegant-elements' ),
					'param_name'  => 'text_color',
					'value'       => '',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'First Letter Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the first letter in the sentense. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'first_letter_font_size',
					'value'       => '40',
					'min'         => '1',
					'max'         => '200',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Capital Letter Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for the first capital letter.', 'elegant-elements' ),
					'param_name'  => 'caps_text_color',
					'value'       => '',
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
					'description' => esc_attr__( 'Align the text to left, right or center.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_big_caps', 99 );
