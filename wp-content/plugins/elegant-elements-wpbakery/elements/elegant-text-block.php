<?php
if ( ! class_exists( 'EEWPB_Text_Block' ) && elegant_is_element_enabled( 'iee_text_block' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.3.0
	 */
	class EEWPB_Text_Block {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.3.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.3.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-text-block', array( $this, 'attr' ) );
			add_shortcode( 'iee_text_block', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.3.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'typography_content'       => '',
					'content_font_size'        => '',
					'content_line_height'      => '',
					'content_line_height_unit' => 'em',
					'hide_on_mobile'           => elegant_elements_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/text-block/elegant-text-block.php' ) ) {
				include locate_template( 'templates/text-block/elegant-text-block.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/text-block/elegant-text-block.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.3.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-text-block',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['typography_content'] ) && '' !== $this->args['typography_content'] ) {
				$font_styling   = elegant_get_google_font_styling( $this->args, 'typography_content' );
				$attr['style'] .= $font_styling;
			}

			if ( isset( $this->args['content_font_size'] ) && '' !== $this->args['content_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['content_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['content_line_height'] ) && '' !== $this->args['content_line_height'] ) {
				$attr['style'] .= 'line-height:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['content_line_height'], $this->args['content_line_height_unit'] ) . ';';
			}

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new EEWPB_Text_Block();
} // End if().

/**
 * Map shortcode for text_block.
 *
 * @since 1.3.0
 * @return void
 */
function map_elegant_elements_wpbakery_text_block() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Text Block', 'elegant-elements' ),
			'shortcode' => 'iee_text_block',
			'icon'      => 'fas fa-paragraph text-block-icon',
			'params'    => array(
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
					'description' => '',
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the content.', 'elegant-elements' ),
					'param_name'  => 'typography_content',
					'value'       => '',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for content. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'content_font_size',
					'value'       => '16',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Line Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the line height for content. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'content_line_height',
					'value'       => '1',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Line Hight Unit', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the CSS unit for the line-height.', 'elegant-elements' ),
					'param_name'  => 'content_line_height_unit',
					'value'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'std'         => 'em',
					'group'       => 'Typography',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_text_block', 99 );
