<?php
if ( elegant_is_element_enabled( 'iee_image_overlay_button' ) && ! class_exists( 'EEWPB_Image_Overlay_Button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.7.0
	 */
	class EEWPB_Image_Overlay_Button {

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
			add_filter( 'eewpb_attr_elegant-image-overlay-button-wrapper', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-image-overlay-button-image', array( $this, 'attr_image' ) );
			add_filter( 'eewpb_attr_elegant-image-overlay-button', array( $this, 'attr_button' ) );
			add_filter( 'eewpb_attr_elegant-image-overlay-button-overlay', array( $this, 'attr_overlay' ) );

			add_shortcode( 'iee_image_overlay_button', array( $this, 'render' ) );
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

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'image'                  => '',
					'image_retina'           => '',
					'width'                  => '400',
					'overlay_color'          => 'rgba(0,0,0,0.6)',
					'overlay_appearance'     => 'fade',
					'image_background_color' => '#f2f2f2',
					'element_content'        => '',
					'hide_on_mobile'         => elegant_elements_default_visibility( 'string' ),
					'class'                  => '',
					'id'                     => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-overlay-button/elegant-image-overlay-button.php' ) ) {
				include locate_template( 'templates/image-overlay-button/elegant-image-overlay-button.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-overlay-button/elegant-image-overlay-button.php';
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
				'class' => 'elegant-image-overlay-button-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-center';

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= 'background-color:' . $this->args['image_background_color'];

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
		public function attr_image() {
			$attr = array(
				'class' => 'image-overlay-button-image',
			);

			$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
			$image_url = $image[0];
			$image_url = esc_url( $image_url );

			$attr['src'] = $image_url;
			$attr['alt'] = basename( $image_url );

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$image            = wp_get_attachment_image_src( $this->args['image_retina'], 'full' );
				$retina_image_url = $image[0];
				$retina_image_url = esc_url( $retina_image_url );

				$attr['srcset']  = $image_url . ' 1x, ';
				$attr['srcset'] .= $retina_image_url . ' 2x ';
			}

			$attr['style'] = 'max-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function attr_button() {
			$attr = array(
				'class' => 'image-overlay-button',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.7.0
		 * @return array
		 */
		public function attr_overlay() {
			$attr = array(
				'class' => 'elegant-image-overlay',
				'style' => '',
			);

			$attr['class'] .= ' overlay-appearance-' . $this->args['overlay_appearance'];

			$attr['style'] .= 'background-color:' . $this->args['overlay_color'];

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
			wp_enqueue_style( 'infi-elegant-image-overlay-button' );
		}
	}

	new EEWPB_Image_Overlay_Button();
} // End if().

/**
 * Map shortcode for image_overlay_button.
 *
 * @since 1.7.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_overlay_button() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Overlay Button', 'elegant-elements' ),
			'shortcode' => 'iee_image_overlay_button',
			'icon'      => 'fa-images fas image-overlay-button-icon',
			'params'    => array(
				array(
					'type'         => 'attach_image',
					'heading'      => esc_attr__( 'Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload or select the image.', 'elegant-elements' ),
					'param_name'   => 'image',
					'dynamic_data' => true,
				),
				array(
					'type'         => 'attach_image',
					'heading'      => esc_attr__( 'Retina Image', 'elegant-elements' ),
					'description'  => esc_attr__( 'Upload or select the image to be used on retina devices.', 'elegant-elements' ),
					'param_name'   => 'image_retina',
					'dynamic_data' => true,
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the maximum css width for the image. Height will change in the proportion automatically. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '320',
					'min'         => '50',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => '',
					'element_tag' => 'iee_fancy_button',
					'description' => esc_attr__( 'Click the link to generate or edit button shortcode.', 'elegant-elements' ),
					'edit_title'  => 'Edit Button Settings',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Overlay Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the overlay color.', 'elegant-elements' ),
					'param_name'  => 'overlay_color',
					'value'       => 'rgba(0,0,0,0.6)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Overlay Appearance', 'elegant-elements' ),
					'description' => __( 'Select how the overlay should appear.', 'elegant-elements' ),
					'default'     => 'fade',
					'param_name'  => 'overlay_appearance',
					'value'       => array(
						'fade'         => esc_attr__( 'Fade', 'elegant-elements' ),
						'slide_left'   => esc_attr__( 'Slide From Left', 'elegant-elements' ),
						'slide_right'  => esc_attr__( 'Slide From Right', 'elegant-elements' ),
						'slide_top'    => esc_attr__( 'Slide From Top', 'elegant-elements' ),
						'slide_bottom' => esc_attr__( 'Slide From Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Image Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image background color. If the image does not fit in the column, this background color will fill the empty space.', 'elegant-elements' ),
					'param_name'  => 'image_background_color',
					'value'       => '#f2f2f2',
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
		)
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_overlay_button', 99 );
