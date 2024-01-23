<?php
if ( ! class_exists( 'EEWPB_Image_Compare' ) && elegant_is_element_enabled( 'iee_image_compare' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Image_Compare {

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

			add_filter( 'eewpb_attr_elegant-image-compare', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-image-compare-label-before', array( $this, 'before_image_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-compare-label-after', array( $this, 'after_image_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-compare-handle', array( $this, 'drag_handle_attr' ) );

			add_shortcode( 'iee_image_compare', array( $this, 'render' ) );
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

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'before_image'                         => '',
					'before_image_caption'                 => esc_attr__( 'Original', 'elegant-elements' ),
					'after_image'                          => '',
					'after_image_caption'                  => esc_attr__( 'Modified', 'elegant-elements' ),
					'before_image_caption_color'           => '',
					'before_image_caption_background_color' => '',
					'after_image_caption_color'            => '',
					'after_image_caption_background_color' => '',
					'handle_background_color'              => '',
					'image_caption_position'               => 'bottom',
					'hide_on_mobile'                       => elegant_elements_default_visibility( 'string' ),
					'class'                                => '',
					'id'                                   => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-compare/elegant-image-compare.php' ) ) {
				include locate_template( 'templates/image-compare/elegant-image-compare.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-compare/elegant-image-compare.php';
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
				'class' => 'elegant-image-compare',
			);

			if ( isset( $this->args['image_caption_position'] ) && '' !== $this->args['image_caption_position'] ) {
				$attr['class'] .= ' image-caption-position-' . $this->args['image_caption_position'];
			}

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
		public function before_image_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-label',
				'style' => '',
			);

			$attr['data-type'] = 'modified';

			if ( isset( $this->args['before_image_caption_color'] ) && '' !== $this->args['before_image_caption_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['before_image_caption_color'] . ';';
			}

			if ( isset( $this->args['before_image_caption_background_color'] ) && '' !== $this->args['before_image_caption_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['before_image_caption_background_color'] . ';';
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
		public function after_image_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-label',
				'style' => '',
			);

			$attr['data-type'] = 'original';

			if ( isset( $this->args['after_image_caption_color'] ) && '' !== $this->args['after_image_caption_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['after_image_caption_color'] . ';';
			}

			if ( isset( $this->args['after_image_caption_background_color'] ) && '' !== $this->args['after_image_caption_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['after_image_caption_background_color'] . ';';
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
		public function drag_handle_attr() {
			$attr = array(
				'class' => 'elegant-image-compare-handle',
				'style' => '',
			);

			if ( isset( $this->args['handle_background_color'] ) && '' !== $this->args['handle_background_color'] ) {
				$attr['style'] .= 'background-color: ' . $this->args['handle_background_color'] . ';';
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
			global $eewpb_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-image-compare-mobile',
				$eewpb_js_folder_url . '/jquery.mobile.custom.min.js',
				$elegant_js_folder_path . '/jquery.mobile.custom.min.js',
				array( 'jquery' ),
				'1',
				true
			);
			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-image-compare',
				$eewpb_js_folder_url . '/infi-elegant-image-compare.min.js',
				$elegant_js_folder_path . '/infi-elegant-image-compare.min.js',
				array( 'infi-elegant-image-compare-mobile' ),
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
			wp_enqueue_style( 'infi-elegant-image-compare' );
		}
	}

	new EEWPB_Image_Compare();
} // End if().


/**
 * Map shortcode for image_compare.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_compare() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Compare', 'elegant-elements' ),
			'shortcode' => 'iee_image_compare',
			'icon'      => 'fas fa-book-open image-compare-icon',
			'params'    => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Before Image', 'elegant-elements' ),
					'param_name'  => 'before_image',
					'value'       => '',
					'description' => esc_attr__( 'Upload the image to be displayed as before image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Before Image Caption', 'elegant-elements' ),
					'param_name'  => 'before_image_caption',
					'value'       => esc_attr__( 'Original', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be displayed for the original image or "Before" image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'After Image', 'elegant-elements' ),
					'param_name'  => 'after_image',
					'value'       => '',
					'description' => esc_attr__( 'Upload the image to be displayed as after image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'After Image Caption', 'elegant-elements' ),
					'param_name'  => 'after_image_caption',
					'value'       => esc_attr__( 'Modified', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be displayed for the modified image or "After" image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Before Image Caption Color', 'elegant-elements' ),
					'param_name'  => 'before_image_caption_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text color for original image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Before Image Caption Background Color', 'elegant-elements' ),
					'param_name'  => 'before_image_caption_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for original image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'After Image Caption Color', 'elegant-elements' ),
					'param_name'  => 'after_image_caption_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text color for modified image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'After Image Caption Background Color', 'elegant-elements' ),
					'param_name'  => 'after_image_caption_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for modified image caption text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Drag Handle Background Color', 'elegant-elements' ),
					'param_name'  => 'handle_background_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for image drag handle.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Caption Position', 'elegant-elements' ),
					'param_name'  => 'image_caption_position',
					'std'         => 'bottom',
					'value'       => array(
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'middle' => esc_attr__( 'Middle', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose the image caption position on the image.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_compare', 99 );
