<?php
if ( ! class_exists( 'EEWPB_Lottie_Animated_Image' ) && elegant_is_element_enabled( 'iee_lottie_animated_image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Lottie_Animated_Image {

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

			add_filter( 'eewpb_attr_elegant-lottie-image', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-lottie-player', array( $this, 'player_attr' ) );

			add_shortcode( 'iee_lottie_animated_image', array( $this, 'render' ) );

			// Allow JSON file upload.
			add_filter( 'upload_mimes', array( $this, 'add_json_mime_type' ), 1 );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $atts    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $atts, $content = '' ) {

			// Enqueue Lottie Player js.
			wp_enqueue_script( 'infi-lottie-player' );

			$heart_animation_json_url = EEWPB_PLUGIN_URL . 'assets/json/heart-animation.json';

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'json_url'         => $heart_animation_json_url,
					'height'           => 300,
					'width'            => 300,
					'animation_mode'   => 'normal',
					'animation_play'   => 'autoplay',
					'animation_loop'   => 'yes',
					'background_color' => '',
					'class'            => '',
					'id'               => '',
					'hide_on_mobile'   => elegant_elements_default_visibility( 'string' ),
				),
				$atts
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/lottie-image/elegant-lottie-image.php' ) ) {
				include locate_template( 'templates/lottie-image/elegant-lottie-image.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/lottie-image/elegant-lottie-image.php';
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
				'class' => 'elegant-lottie-image',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width          = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' );
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
		public function player_attr() {
			$attr = array(
				'src'        => $this->args['json_url'],
				'background' => $this->args['background_color'],
				'speed'      => 1,
				'style'      => '',
			);

			if ( 'bounce' === $this->args['animation_mode'] ) {
				$attr['mode'] = 'bounce';
			}

			if ( 'yes' === $this->args['animation_loop'] ) {
				$attr['loop'] = true;
			}

			$attr[ $this->args['animation_play'] ] = true;

			$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width          = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';

			return $attr;
		}

		/**
		 * Allow JSON file upload in the media library.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $mime_types Current array of mime types.
		 * @return array Updated array of mime types with JSON type included.
		 */
		public function add_json_mime_type( $mime_types ) {

			$mime_types['json'] = 'text/plain';

			return $mime_types;
		}
	}

	new EEWPB_Lottie_Animated_Image();
} // End if().

/**
 * Map shortcode for lottie_animated_image.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_lottie_animated_image() {

	$heart_animation_json_url = EEWPB_PLUGIN_URL . 'assets/json/heart-animation.json';

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Lottie Animated Image', 'elegant-elements' ),
			'shortcode' => 'iee_lottie_animated_image',
			'icon'      => 'fa-magic fas lottie-image-icon',
			'params'    => array(
				array(
					'type'        => 'ee_file_upload',
					'heading'     => esc_attr__( 'Lottie Animation JSON File.', 'elegant-elements' ),
					'description' => sprintf( esc_attr__( 'Upload the Lottie JSON file or enter the Lottie image animation url from %s.', 'elegant-elements' ), '<a href="https://lottiefiles.com" target="_blank">https://lottiefiles.com</a>' ),
					'param_name'  => 'json_url',
					'value'       => $heart_animation_json_url,
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '300',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for this image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '300',
					'min'         => '1',
					'max'         => '2000',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Animation', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should start as autoplay or on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'animation_play',
					'value'       => array(
						'autoplay' => 'Autoplay',
						'hover'    => 'Hover',
					),
					'std'         => 'autoplay',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Animation in Loop', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls if the animation should play continuously in loop or only once.', 'elegant-elements' ),
					'param_name'  => 'animation_loop',
					'value'       => array(
						'yes' => 'Yes',
						'no'  => 'No',
					),
					'std'         => 'yes',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Play Mode', 'elegant-elements' ),
					'description' => esc_attr__( 'Normal mode will play animation in one direction and the bounce mode will play animation in revese after the normal animation.', 'elegant-elements' ),
					'param_name'  => 'animation_mode',
					'value'       => array(
						'normal' => 'Normal',
						'bounce' => 'Bounce',
					),
					'std'         => 'normal',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the image.', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_lottie_animated_image', 99 );
