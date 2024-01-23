<?php
if ( ! class_exists( 'EEWPB_Distortion_Hover_Image' ) && elegant_is_element_enabled( 'iee_distortion_hover_image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Distortion_Hover_Image {

		/**
		 * Elementor counter.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $counter = 1;

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

			add_filter( 'eewpb_attr_elegant-distortion-hover-image-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-distortion-hover-image', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-distortion-hover-content', array( $this, 'content_attr' ) );

			add_shortcode( 'iee_distortion_hover_image', array( $this, 'render' ) );
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

			// Enqueue the script.
			wp_enqueue_script( 'infi-distortion-hover' );

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'first_image'         => '',
					'second_image'        => '',
					'displacement_image'  => '',
					'width'               => 300,
					'height'              => 300,
					'distortion_position' => 'from_left',
					'content_overlay'     => 'rgba(0,0,0,0.3)',
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/distortion-hover-image/elegant-distortion-hover-image.php' ) ) {
				include locate_template( 'templates/distortion-hover-image/elegant-distortion-hover-image.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/distortion-hover-image/elegant-distortion-hover-image.php';
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
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-image-wrapper',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' );

			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';

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
		public function attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-image distortion-hover-image-' . $this->counter,
				'style' => '',
			);

			$first_image     = wp_get_attachment_image_src( $this->args['first_image'], 'full' );
			$first_image_url = $first_image[0];
			$first_image_url = esc_url( $first_image_url );

			$second_image     = wp_get_attachment_image_src( $this->args['second_image'], 'full' );
			$second_image_url = $second_image[0];
			$second_image_url = esc_url( $second_image_url );

			$displacement_image     = wp_get_attachment_image_src( $this->args['displacement_image'], 'full' );
			$displacement_image_url = $displacement_image[0];
			$displacement_image_url = esc_url( $displacement_image_url );

			$first_image        = $first_image_url;
			$second_image       = $second_image_url;
			$displacement_image = $displacement_image_url;

			$attr['data-firstImage']        = $first_image;
			$attr['data-secondImage']       = $second_image;
			$attr['data-displacementImage'] = $displacement_image;
			$attr['data-intensity']         = ( 'from_left' === $this->args['distortion_position'] ) ? -0.5 : 0.5;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-distortion-hover-content',
				'style' => '',
			);

			$attr['style'] .= 'background: ' . $this->args['content_overlay'] . ';';

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
			wp_enqueue_style( 'infi-elegant-distortion-hover-image' );
		}
	}

	new EEWPB_Distortion_Hover_Image();
} // End if().

/**
 * Map shortcode for distortion_hover_image.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_distortion_hover_image() {

	elegant_elements_map(
		array(
			'name'             => esc_attr__( 'Elegant Distortion Hover Image', 'elegant-elements' ),
			'shortcode'        => 'iee_distortion_hover_image',
			'icon'             => 'fa-images fas distortion-hover-image-icon',
			'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/distortion-hover-image.js',
			'params'           => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the first image of the animation.', 'elegant-elements' ),
					'param_name'  => 'first_image',
					'value'       => '',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image 2', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the second image of the animation.', 'elegant-elements' ),
					'param_name'  => 'second_image',
					'value'       => '',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Displacement Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the image to do the transition between the above two images.', 'elegant-elements' ),
					'param_name'  => 'displacement_image',
					'value'       => '',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '500',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '500',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Distortion Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to animate the image.', 'elegant-elements' ),
					'param_name'  => 'distortion_position',
					'std'         => 'from_left',
					'value'       => array(
						'from_left'  => esc_attr__( 'From Left', 'elegant-elements' ),
						'from_right' => esc_attr__( 'From Right', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Content on Image', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Your content goes here', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter content you want to display over the image. Leave blank to display image only.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Content Overlay Color', 'elegant-elements' ),
					'param_name'  => 'content_overlay',
					'value'       => '',
					'default'     => 'rgba(0,0,0,0.3)',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the overlay color for the content on image.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_distortion_hover_image', 99 );
