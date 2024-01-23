<?php
if ( ! class_exists( 'EEWPB_Image_Separator' ) && elegant_is_element_enabled( 'iee_image_separator' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Image_Separator {

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
			add_filter( 'eewpb_attr_elegant-image-separator', array( $this, 'attr' ) );
			add_shortcode( 'iee_image_separator', array( $this, 'render' ) );
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
					'image'          => '',
					'type'           => 'horizontal',
					'width'          => '120',
					'height'         => '120',
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-separator/elegant-image-separator.php' ) ) {
				include locate_template( 'templates/image-separator/elegant-image-separator.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-separator/elegant-image-separator.php';
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
				'class' => 'elegant-image-separator',
				'style' => '',
			);

			$attr['class'] .= ' image-separator-' . $this->args['type'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( $this->args['width'] ) {
				$attr['style'] .= 'width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';
			}

			if ( $this->args['height'] ) {
				$attr['style'] .= 'height:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
			}

			if ( 'vertical' === $this->args['type'] ) {
				$attr['style'] .= 'top: calc( 50% - ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ' );';
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
			wp_enqueue_style( 'infi-elegant-image-separator' );
		}
	}

	new EEWPB_Image_Separator();
} // End if().

/**
 * Map shortcode for image_separator.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_separator() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Separator', 'elegant-elements' ),
			'shortcode' => 'iee_image_separator',
			'icon'      => 'fa-image far image-separator-icon',
			'params'    => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Separator Image', 'elegant-elements' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_attr__( 'Select the image to be used for separator.', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Separator Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image separator placement, vertical ( for columns ) or horizontal ( for containers ).', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'horizontal',
					'value'       => array(
						'horizontal' => esc_attr__( 'Horizontal ( For Containers )', 'elegant-elements' ),
						'vertical'   => esc_attr__( 'Vertical ( For Columns )', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '120',
					'min'         => '1',
					'max'         => '1000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '120',
					'min'         => '1',
					'max'         => '1000',
					'step'        => '1',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_separator', 99 );
