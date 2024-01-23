<?php
if ( ! class_exists( 'EEWPB_Advanced_Video' ) && elegant_is_element_enabled( 'iee_advanced_video' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Advanced_Video {

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

			add_filter( 'eewpb_attr_elegant-advanced-video', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-advanced-video-image', array( $this, 'attr_image' ) );
			add_filter( 'eewpb_attr_elegant-advanced-video-icon', array( $this, 'attr_icon' ) );

			add_shortcode( 'iee_advanced_video', array( $this, 'render' ) );
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
					'video_provider'   => 'youtube',
					'video_id'         => 'il2ZAZX9KpQ',
					'hosted_video_url' => '',
					'image'            => '',
					'image_retina'     => '',
					'width'            => '',
					'alignment'        => 'left',
					'image_overlay'    => 'rgba(0,0,0,0.3)',
					'icon_type'        => 'icon',
					'video_play_icon'  => '',
					'icon_color'       => '#ffffff',
					'icon_font_size'   => '32',
					'image_icon'       => '',
					'video_bg_color'   => 'rgba(0,0,0,1)',
					'hide_on_mobile'   => elegant_elements_default_visibility( 'string' ),
					'class'            => '',
					'id'               => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/advanced-video/elegant-advanced-video.php' ) ) {
				include locate_template( 'templates/advanced-video/elegant-advanced-video.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/advanced-video/elegant-advanced-video.php';
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
				'class' => 'elegant-advanced-video',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= 'max-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';
			$attr['style'] .= 'background: ' . $this->args['video_bg_color'] . ';';

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
				'class' => 'elegant-advanced-video-preview',
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

			if ( 'hosted' !== $this->args['video_provider'] ) {
				$attr['data-embed-url'] = eewpb_get_embed_url_by_provider( $this->args['video_provider'], $this->args['video_id'], true );
			} else {
				$attr['data-embed-url'] = $this->args['hosted_video_url'];
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
		public function attr_icon() {
			$attr = array(
				'class' => 'elegant-advanced-video-play-icon',
				'style' => '',
			);

			$icon_class     = $this->args['video_play_icon'];
			$attr['class'] .= ' ' . $icon_class;

			$attr['style'] .= 'color: ' . $this->args['icon_color'] . ';';
			$attr['style'] .= 'font-size: ' . $this->args['icon_font_size'] . 'px;';

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
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-advanced-video',
				$eewpb_js_folder_url . '/infi-elegant-advanced-video.min.js',
				$elegant_js_folder_path . '/infi-elegant-advanced-video.min.js',
				array( 'jquery' ),
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
			wp_enqueue_style( 'infi-elegant-advanced-video' );
		}
	}

	new EEWPB_Advanced_Video();
} // End if().

/**
 * Map shortcode for advanced_video.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_advanced_video() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Advanced Video', 'elegant-elements' ),
			'shortcode' => 'iee_advanced_video',
			'icon'      => 'fa-play-circle fas advanced-video-icon',
			'params'    => array(
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Video Provider', 'elegant-elements' ),
					'param_name'  => 'video_provider',
					'value'       => array(
						'youtube' => esc_attr__( 'YouTube', 'elegant-elements' ),
						'vimeo'   => esc_attr__( 'Vimeo', 'elegant-elements' ),
						'wistia'  => esc_attr__( 'Wistia', 'elegant-elements' ),
						'hosted'  => esc_attr__( 'Self Hosted', 'elegant-elements' ),
					),
					'std'         => 'youtube',
					'description' => esc_attr__( 'Select the video provide you want to use the video from. You can choose from different providers like YouTube, Vimeo and Wistia.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Video ID', 'elegant-elements' ),
					'param_name'  => 'video_id',
					'value'       => 'il2ZAZX9KpQ',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the video id from your provider. You can get the video ID from the url.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'video_provider',
						'value_not_equal_to' => 'hosted',
					),
				),
				array(
					'type'        => 'ee_file_upload',
					'heading'     => esc_attr__( 'Upload Your Video', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the self-hosted video.', 'elegant-elements' ),
					'param_name'  => 'hosted_video_url',
					'dependency'  => array(
						'element' => 'video_provider',
						'value'   => array( 'hosted' ),
					),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Preview Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to use for video preview.', 'elegant-elements' ),
					'param_name'  => 'image',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Retina Preview Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to be used on retina devices as video preview.', 'elegant-elements' ),
					'param_name'  => 'image_retina',
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
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Image Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the image alignment.', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'none'   => 'Text Flow',
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'std'         => 'left',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
					'param_name'  => 'image_overlay',
					'value'       => 'rgba(0,0,0,0.3)',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the overlay background color of the video placeholder image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Video Placeholder Background Color', 'elegant-elements' ),
					'param_name'  => 'video_bg_color',
					'value'       => 'rgba(0,0,0,1)',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the video placeholder background color.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to use image or FontAwesome icon for play button.', 'elegant-elements' ),
					'param_name'  => 'icon_type',
					'value'       => array(
						'icon'  => 'Font Icon',
						'image' => 'Image Icon',
					),
					'std'         => 'icon',
					'group'       => 'Play Button',
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Choose Play Icon', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the icon for the video play icon on the image.', 'elegant-elements' ),
					'param_name'  => 'video_play_icon',
					'value'       => 'fa-play fas',
					'group'       => 'Play Button',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '#ffffff',
					'placeholder' => true,
					'description' => esc_attr__( 'Choose the icon color of the video play icon.', 'elegant-elements' ),
					'group'       => 'Play Button',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for icon. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'icon_font_size',
					'value'       => '32',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Play Button',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Icon Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload or select the image to use for video play button on the preview image.', 'elegant-elements' ),
					'param_name'  => 'image_icon',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'image' ),
					),
					'group'       => 'Play Button',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_advanced_video', 99 );
