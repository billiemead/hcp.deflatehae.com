<?php
if ( ! class_exists( 'EEWPB_Blob_Shape_Image' ) && elegant_is_element_enabled( 'iee_blob_shape_image' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Blob_Shape_Image {

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

			add_filter( 'eewpb_attr_elegant-blob-shape-image-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-blob-shape-image', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-blob-shape-image-background', array( $this, 'background_attr' ) );
			add_filter( 'eewpb_attr_elegant-blob-shape-image-content', array( $this, 'content_attr' ) );

			add_shortcode( 'iee_blob_shape_image', array( $this, 'render' ) );
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
					'blob_shape'                => '59% 41% 41% 59% / 29% 48% 52% 71%',
					'width'                     => 400,
					'height'                    => 400,
					'alignment'                 => 'left',
					'link_url'                  => '',
					'image'                     => '',
					'background_color_1'        => 'rgba(174,0,255,0.5)',
					'background_color_2'        => 'rgba(255,106,0,0.5)',
					'gradient_angle'            => '45',
					'fade_offset'               => '50',
					'background_color_1_offset' => '0',
					'background_color_2_offset' => '100',
					'hide_on_mobile'            => elegant_elements_default_visibility( 'string' ),
					'class'                     => '',
					'id'                        => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/blob-shape-image/elegant-blob-shape-image.php' ) ) {
				include locate_template( 'templates/blob-shape-image/elegant-blob-shape-image.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/blob-shape-image/elegant-blob-shape-image.php';
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
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-wrapper',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-clearfix';
			$attr['style'] .= 'display: flex;';

			if ( 'center' === $this->args['alignment'] ) {
				$attr['style'] .= 'justify-content: center;';
			} elseif ( 'right' === $this->args['alignment'] ) {
				$attr['style'] .= 'justify-content: flex-end;';
			} else {
				$attr['style'] .= 'justify-content: flex-start;';
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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image',
				'style' => '',
			);

			$height = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$width  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' );

			$attr['style'] .= 'height:' . $height . ';';
			$attr['style'] .= 'width:' . $width . ';';
			$attr['style'] .= 'max-width: 100%;';
			$attr['style'] .= 'border-radius:' . $this->args['blob_shape'] . ';';
			$attr['style'] .= eewpb_gradient_color( $this->args['gradient_angle'], $this->args['background_color_1'], $this->args['background_color_2'], $this->args['fade_offset'], $this->args['background_color_1_offset'], $this->args['background_color_2_offset'] );

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function background_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-background',
				'style' => '',
			);

			if ( '' !== $this->args['image'] ) {
				$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
				$image_url = $image[0];
				$image_url = esc_url( $image_url );

				$attr['style'] .= 'background-image:url(' . $image_url . ');';
				$attr['style'] .= 'background-blend-mode: overlay;';
				$attr['style'] .= 'mix-blend-mode: overlay;';
				$attr['style'] .= 'background-size: cover;';
				$attr['style'] .= 'background-repeat: no-repeat;';
				$attr['style'] .= 'background-position: center;';
				$attr['style'] .= 'height: 100%;';
				$attr['style'] .= 'border-radius: inherit;';
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
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-blob-shape-image-content',
				'style' => '',
			);

			$attr['style'] .= 'transform: translate(0%, -100%);';
			$attr['style'] .= 'height: inherit;';
			$attr['style'] .= 'display: flex;';
			$attr['style'] .= 'flex-direction: column;';
			$attr['style'] .= 'align-items: center;';
			$attr['style'] .= 'justify-content: center;';
			$attr['style'] .= 'padding: 40px;';

			return $attr;
		}
	}

	new EEWPB_Blob_Shape_Image();
} // End if().

/**
 * Map shortcode for blob_shape_image.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_blob_shape_image() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Blob Shape Image', 'elegant-elements' ),
			'shortcode' => 'iee_blob_shape_image',
			'icon'      => 'fa-shapes fas blob-shape-image-icon',
			'params'    => array(
				array(
					'type'        => 'ee_blob_shape_generator',
					'heading'     => esc_attr__( 'Blob Shape', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to add empty space, vertically or horizontally.', 'elegant-elements' ),
					'param_name'  => 'blob_shape',
					'value'       => '59% 41% 41% 59% / 29% 48% 52% 71%',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '400',
					'min'         => '1',
					'max'         => '1200',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '400',
					'min'         => '1',
					'max'         => '1200',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the blob to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_attr__( 'Link URL', 'elegant-elements' ),
					'param_name'  => 'link_url',
					'value'       => '',
					'description' => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'description' => esc_attr__( 'The background image for this blob shape.', 'elegant-elements' ),
					'param_name'  => 'image',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for the blob.', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => 'rgba(174,0,255,0.5)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color 2', 'elegant-elements' ),
					'description' => esc_attr__( 'If set, both colors will form a gradient color.', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => 'rgba(255,106,0,0.5)',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Gradient Angle', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the gradient angle.', 'elegant-elements' ),
					'param_name'  => 'gradient_angle',
					'value'       => '45',
					'min'         => '0',
					'max'         => '180',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'   => 'background_color_2',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Fade Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the gradient fade offset.', 'elegant-elements' ),
					'param_name'  => 'fade_offset',
					'value'       => '50',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'   => 'background_color_2',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Background Color 1 Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color 1 offset.', 'elegant-elements' ),
					'param_name'  => 'background_color_1_offset',
					'value'       => '0',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'   => 'background_color_2',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Background Color 2 Offset', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color 2 offset.', 'elegant-elements' ),
					'param_name'  => 'background_color_2_offset',
					'value'       => '100',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'   => 'background_color_2',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Content on Image', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => esc_attr__( 'Enter content you want to display over the image. Leave blank to display image only.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_blob_shape_image', 99 );
