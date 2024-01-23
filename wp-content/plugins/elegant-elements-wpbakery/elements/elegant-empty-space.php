<?php
if ( ! class_exists( 'EEWPB_Empty_Space' ) && elegant_is_element_enabled( 'iee_empty_space' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Empty_Space {

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
			add_filter( 'eewpb_attr_elegant-empty-space', array( $this, 'attr' ) );
			add_shortcode( 'iee_empty_space', array( $this, 'render' ) );
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
					'type'           => 'vertical',
					'width'          => 10,
					'height'         => 10,
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/empty-space/elegant-empty-space.php' ) ) {
				include locate_template( 'templates/empty-space/elegant-empty-space.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/empty-space/elegant-empty-space.php';
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
				'class' => 'elegant-empty-space',
				'style' => '',
			);

			$attr['class'] .= ' space-' . $this->args['type'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( 'vertical' == $this->args['type'] ) {
				$attr['class'] .= ' elegant-clearfix';
				$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
				$attr['style']  = 'height:' . $height . ';';
			} else {
				$width         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' );
				$attr['style'] = 'width:' . $width . ';';
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

	new EEWPB_Empty_Space();
} // End if().

/**
 * Map shortcode for empty_space.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_empty_space() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Empty Space', 'elegant-elements' ),
			'shortcode' => 'iee_empty_space',
			'icon'      => 'fas fa-arrows-alt-v empty-space-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Space Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to add empty space, vertically or horizontally.', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'vertical',
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical space between two elements', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal space between two elements', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '10',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'vertical' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '10',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'horizontal' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_empty_space', 99 );
