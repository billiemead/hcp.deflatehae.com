<?php
if ( ! class_exists( 'EEWPB_Video_Lightbox' ) && elegant_is_element_enabled( 'iee_video_lightbox' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.4.0
	 */
	class EEWPB_Video_Lightbox {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.4.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.4.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-video-lightbox', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-video-lightbox-icon', array( $this, 'icon_attr' ) );
			add_filter( 'eewpb_attr_elegant-video-lightbox-image', array( $this, 'image_attr' ) );

			add_shortcode( 'iee_video_lightbox', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.4.0
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
					'video_trigger'    => 'icon',
					'icon'             => 'fas fa-play',
					'icon_color'       => '#333333',
					'icon_size'        => 32,
					'image'            => '',
					'image_retina'     => '',
					'width'            => '',
					'alignment'        => 'left',
					'text'             => '',
					'video_provider'   => 'youtube',
					'video_url'        => '',
					'hosted_video_url' => '',
					'hide_on_mobile'   => elegant_elements_default_visibility( 'string' ),
					'class'            => '',
					'id'               => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/video-lightbox/elegant-video-lightbox.php' ) ) {
				include locate_template( 'templates/video-lightbox/elegant-video-lightbox.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/video-lightbox/elegant-video-lightbox.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-video-lightbox',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

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
		 * @since 1.4.0
		 * @return array
		 */
		public function icon_attr() {
			$attr = array(
				'class' => 'elegant-video-lightbox-icon',
				'style' => '',
			);

			$attr['class'] .= ' ' . $this->args['icon'];

			$attr['style'] .= 'color: ' . $this->args['icon_color'] . ';';
			$attr['style'] .= 'font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_size'], 'px' );

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return array
		 */
		public function image_attr() {
			$attr = array(
				'class' => 'elegant-video-lightbox-image',
				'style' => '',
			);

			$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
			$image_url = $image[0];
			$image_url = esc_url( $image_url );

			$attr['src'] = $image_url;

			if ( isset( $this->args['image_retina'] ) && '' !== $this->args['image_retina'] ) {
				$image_retina     = wp_get_attachment_image_src( $this->args['image_retina'], 'full' );
				$image_retina_url = $image_retina[0];
				$image_retina_url = esc_url( $image_retina_url );

				$attr['srcset']  = $image_url . ' 1x, ';
				$attr['srcset'] .= $image_retina_url . ' 2x ';
			}

			$attr['style'] = 'max-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return void
		 */
		public function add_scripts() {
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-video-lightbox',
				$eewpb_js_folder_url . '/infi-elegant-lightbox.min.js',
				$elegant_js_folder_path . '/infi-elegant-lightbox.min.js',
				array( 'jquery' ),
				EEWPB_VERSION,
				true
			);
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-video-lightbox' );
		}
	}

	new EEWPB_Video_Lightbox();
} // End if().

/**
 * Map shortcode for video_lightbox.
 *
 * @since 1.4.0
 * @return void
 */
function map_elegant_elements_wpbakery_video_lightbox() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Video Lightbox', 'elegant-elements' ),
			'shortcode' => 'iee_video_lightbox',
			'icon'      => 'fa-play fas video-lightbox-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Video Trigger', 'elegant-elements' ),
					'param_name'  => 'video_trigger',
					'admin_label' => true,
					'value'       => array(
						'icon'  => __( 'Icon', 'elegant-elements' ),
						'image' => __( 'Image', 'elegant-elements' ),
						'text'  => __( 'Text', 'elegant-elements' ),
					),
					'description' => __( 'Select the trigger for video lightbox', 'elegant-elements' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => __( 'Video Icon', 'elegant-elements' ),
					'param_name'  => 'icon',
					'value'       => 'fa-play fas',
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => __( 'Video Icon', 'elegant-elements' ),
					'description' => __( 'Select the color of the icon.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Video Icon color', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '#333333',
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => __( 'Video Icon', 'elegant-elements' ),
					'description' => __( 'Select the color of the icon.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => __( 'Video Icon Size', 'elegant-elements' ),
					'param_name'  => 'icon_size',
					'value'       => '32',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => __( 'Video Icon', 'elegant-elements' ),
					'description' => __( 'Select the icon size in pixels.', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to use.', 'elegant-elements' ),
					'param_name'  => 'image',
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'image' ),
					),
					'group'       => __( 'Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Retina Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to be used on retina devices.', 'elegant-elements' ),
					'param_name'  => 'image_retina',
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'image' ),
					),
					'group'       => __( 'Image', 'elegant-elements' ),
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
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'image' ),
					),
					'group'       => __( 'Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Video Trigger Text', 'elegant-elements' ),
					'param_name'  => 'text',
					'value'       => '',
					'description' => esc_attr__( 'Enter the text to be used to trigger the video lightbox.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'video_trigger',
						'value'   => array( 'text' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Video Provider', 'elegant-elements' ),
					'param_name'  => 'video_provider',
					'admin_label' => true,
					'value'       => array(
						'youtube' => __( 'YouTube', 'elegant-elements' ),
						'vimeo'   => __( 'Vimeo', 'elegant-elements' ),
						'wistia'  => __( 'Wistia', 'elegant-elements' ),
						'hosted'  => __( 'Self Hosted', 'elegant-elements' ),
					),
					'description' => __( 'Select the video provider', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Video URL', 'elegant-elements' ),
					'param_name'  => 'video_url',
					'admin_label' => true,
					'dependency'  => array(
						'element'            => 'video_provider',
						'value_not_equal_to' => 'hosted',
					),
					'description' => __( 'Enter the the Youtube/Vimeo/Wistia video URL. E.g: https://www.youtube.com/watch?v=L_KaQfS1hRQ', 'elegant-elements' ),
				),
				array(
					'type'       => 'ee_file_upload',
					'heading'    => __( 'Upload Self Hosted Video', 'elegant-elements' ),
					'param_name' => 'hosted_video_url',
					'dependency' => array(
						'element' => 'video_provider',
						'value'   => array( 'hosted' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon/image/text alignment.', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'std'         => 'left',
					'icons'       => elegant_get_alignment_icons(),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_video_lightbox', 99 );
