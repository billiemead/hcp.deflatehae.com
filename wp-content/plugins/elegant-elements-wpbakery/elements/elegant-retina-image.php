<?php
if ( ! class_exists( 'EEWPB_Retina_Image' ) && elegant_is_element_enabled( 'iee_retina_image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Retina_Image {

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

			add_filter( 'eewpb_attr_elegant-retina-image', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-retina-image-src', array( $this, 'attr_image' ) );

			add_shortcode( 'iee_retina_image', array( $this, 'render' ) );
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
					'image'          => '',
					'image_retina'   => '',
					'width'          => '400',
					'link_url'       => '',
					'alignment'      => 'left',
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/retina-image/elegant-retina-image.php' ) ) {
				include locate_template( 'templates/retina-image/elegant-retina-image.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/retina-image/elegant-retina-image.php';
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
				'class' => 'elegant-retina-image',
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
		public function attr_image() {
			$attr = array(
				'class' => 'retina-image',
			);

			$image       = wp_get_attachment_image_src( $this->args['image'], 'full' );
			$image_url   = $image[0];
			$image_url   = esc_url( $image_url );
			$attr['src'] = $image_url;

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$image_retina     = wp_get_attachment_image_src( $this->args['image_retina'], 'full' );
				$image_retina_url = $image_retina[0];
				$image_retina_url = esc_url( $image_retina_url );
				$attr['srcset']   = $image_url . ' 1x, ';
				$attr['srcset']  .= $image_retina_url . ' 2x ';
			}

			if ( '' !== $this->args['width'] ) {
				$attr['style']  = 'max-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';
				$attr['style'] .= 'width:100%;';
			}

			return $attr;
		}
	}

	new EEWPB_Retina_Image();
} // End if().

/**
 * Map shortcode for retina_image.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_retina_image() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Retina Image', 'elegant-elements' ),
			'shortcode' => 'iee_retina_image',
			'icon'      => 'fa-eye fas retina-image-icon',
			'params'    => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image.', 'elegant-elements' ),
					'param_name'  => 'image',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Retina Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to be used on retina devices.', 'elegant-elements' ),
					'param_name'  => 'image_retina',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the maximum css width for the image. Height will change in the proportion automatically. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '400',
					'min'         => '50',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_attr__( 'Link URL', 'elegant-elements' ),
					'param_name'  => 'link_url',
					'value'       => '',
					'description' => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image alignment.', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'icons'       => elegant_get_alignment_icons(),
					'std'         => 'left',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_retina_image', 99 );
