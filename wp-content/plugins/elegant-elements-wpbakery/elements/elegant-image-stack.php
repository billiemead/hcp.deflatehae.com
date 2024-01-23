<?php
if ( ! class_exists( 'EEWPB_Image_Stack' ) && elegant_is_element_enabled( 'iee_image_stack' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.4.0
	 */
	class EEWPB_Image_Stack {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.4.0
		 * @var array
		 */
		protected $args;

		/**
		 * Stacked image counter.
		 *
		 * @access protected
		 * @since 1.4.0
		 * @var int
		 */
		protected $count;

		/**
		 * Constructor.
		 *
		 * @since 1.4.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-image-stack-wrapper', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-image-stack-image-item', array( $this, 'image_item_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-stack-image', array( $this, 'image_attr' ) );

			add_shortcode( 'iee_image_stack', array( $this, 'render' ) );
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

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$stacked_images = rawurlencode(
				wp_json_encode(
					array(
						array(
							'image_url'        => '',
							'retina_image_url' => '',
							'height'           => '300',
							'width'            => '300',
							'top'              => '60',
							'right'            => '0',
						),
						array(
							'image_url'        => '',
							'retina_image_url' => '',
							'height'           => '300',
							'width'            => '300',
							'top'              => '0',
							'right'            => '0',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'image_url'        => '',
					'retina_image_url' => '',
					'height'           => '400',
					'width'            => '400',
					'top_margin'       => '10',
					'bring_to_front'   => 'yes',
					'box_shadow'       => 'md',
					'animation'        => 'sm',
					'border_radius'    => '',
					'margin_top'       => '100px',
					'margin_bottom'    => '100px',
					'stacked_images'   => $stacked_images,
					'hide_on_mobile'   => elegant_elements_default_visibility( 'string' ),
					'class'            => '',
					'id'               => '',
				),
				$args
			);

			$this->args = $defaults;

			$this->count = 1;

			$html = '';

			if ( '' !== locate_template( 'templates/image-stack/elegant-image-stack.php' ) ) {
				include locate_template( 'templates/image-stack/elegant-image-stack.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-stack/elegant-image-stack.php';
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
				'class' => 'elegant-image-stack elegant-image-stack-wrapper',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['margin_top'] ) && '' !== $this->args['margin_top'] ) {
				$attr['style'] .= 'margin-top:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin_top'], 'px' ) . ';';
			}

			if ( isset( $this->args['margin_bottom'] ) && '' !== $this->args['margin_bottom'] ) {
				$attr['style'] .= 'margin-bottom:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin_bottom'], 'px' ) . ';';
			}

			if ( 'yes' === $this->args['bring_to_front'] ) {
				$attr['class'] .= ' bring-to-front';
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
		 * @since 1.4.0
		 * @param array $stack_image Image attributes.
		 * @return array
		 */
		public function image_item_attr( $stack_image ) {
			$attr = array(
				'class' => 'elegant-image-stack-image-item',
				'style' => '',
			);

			$attr['class'] .= ' image-stack-item-' . $this->count;

			if ( '0' !== $this->args['animation'] ) {
				$attr['class'] .= ' elegant-animate';
				$attr['style'] .= 'animation-name: elegantBounce_' . $this->args['animation'] . ';';
			}

			$box_shadow     = ( 'md' === $this->args['box_shadow'] ) ? 'elegant-shadow' : 'elegant-shadow-' . $this->args['box_shadow'];
			$attr['class'] .= ' ' . $box_shadow;

			$attr['style'] .= 'z-index:' . ( $this->args['image_count'] - $this->count ) . ';';

			$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $stack_image['height'], 'px' );
			$attr['style'] .= 'height:' . $height . ';';

			$width          = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $stack_image['width'], 'px' );
			$attr['style'] .= 'width:' . $width . ';';

			if ( isset( $this->args['border_radius'] ) && '' !== $this->args['border_radius'] ) {
				$attr['style'] .= 'border-radius:' . $this->args['border_radius'] . ';';
			}

			if ( isset( $stack_image['top'] ) ) {
				$top            = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $stack_image['top'], '%' );
				$attr['style'] .= 'top:' . $top . ';';
			}

			if ( isset( $stack_image['right'] ) ) {
				$right          = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $stack_image['right'], '%' );
				$attr['style'] .= 'right:' . $right . ';';
			}

			if ( isset( $stack_image['top_margin'] ) ) {
				$top_margin     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $stack_image['top_margin'], '%' );
				$attr['style'] .= 'margin-top:' . $top_margin . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @param array $stack_image Image attributes.
		 * @return array
		 */
		public function image_attr( $stack_image ) {
			$attr = array(
				'class' => 'elegant-image-stack-image',
				'style' => '',
			);

			$image     = wp_get_attachment_image_src( $stack_image['image_url'], 'full' );
			$image_url = $image[0];
			$image_url = esc_url( $image_url );

			$attr['src'] = $image_url;

			if ( isset( $stack_image['retina_image_url'] ) && '' !== $stack_image['retina_image_url'] ) {
				$image_retina     = wp_get_attachment_image_src( $stack_image['retina_image_url'], 'full' );
				$image_retina_url = $image_retina[0];
				$image_retina_url = esc_url( $image_retina_url );

				$attr['srcset']  = $image_url . ' 1x, ';
				$attr['srcset'] .= $image_retina_url . ' 2x ';
			}

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-image-stack' );
		}
	}

	new EEWPB_Image_Stack();
} // End if().

/**
 * Map shortcode for image_stack.
 *
 * @since 1.4.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_stack() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Image Stack', 'elegant-elements' ),
			'shortcode' => 'iee_image_stack',
			'icon'      => 'fas fa-layer-group image-stack-icon',
			'params'    => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Main Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload an image to display in the first place and on top of all images.', 'elegant-elements' ),
					'param_name'  => 'image_url',
					'value'       => '',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Main Image - Retina', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload an image to display in the first place and on top of all images.', 'elegant-elements' ),
					'param_name'  => 'retina_image_url',
					'value'       => '',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '500',
					'min'         => '1',
					'max'         => '1500',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for the image. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'width',
					'value'       => '500',
					'min'         => '1',
					'max'         => '1500',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Top Margin', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the top margin for the first image. ( In % ).', 'elegant-elements' ),
					'param_name'  => 'top_margin',
					'value'       => '10',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Bring Image to Front on Hover?', 'elegant-elements' ),
					'param_name'  => 'bring_to_front',
					'value'       => array(
						'yes' => __( 'Yes', 'elegant-elements' ),
						'no'  => __( 'No', 'elegant-elements' ),
					),
					'description' => __( 'On hover, the image will be displayed on the top of all images.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Box Shadow', 'elegant-elements' ),
					'param_name'  => 'box_shadow',
					'default'     => 'md',
					'value'       => array(
						'0'  => __( 'No', 'elegant-elements' ),
						'sm' => __( 'Small', 'elegant-elements' ),
						'md' => __( 'Medium', 'elegant-elements' ),
						'lg' => __( 'Large', 'elegant-elements' ),
					),
					'description' => __( 'Controls the box shadow on image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Animation', 'elegant-elements' ),
					'param_name'  => 'animation',
					'default'     => 'sm',
					'value'       => array(
						'0'  => __( 'No', 'elegant-elements' ),
						'sm' => __( 'Small', 'elegant-elements' ),
						'md' => __( 'Medium', 'elegant-elements' ),
						'lg' => __( 'Large', 'elegant-elements' ),
					),
					'description' => __( 'Controls the animation on image.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'border_radius',
					'value'       => array(
						'border_radius_top_left'     => '',
						'border_radius_top_right'    => '',
						'border_radius_bottom_right' => '',
						'border_radius_bottom_left'  => '',
					),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Margin', 'elegant-elements' ),
					'description' => esc_attr__( 'Add top and bottom margin to avoid content overlap.', 'elegant-elements' ),
					'param_name'  => 'margin',
					'value'       => array(
						'margin_top'    => '100px',
						'margin_bottom' => '100px',
					),
				),
				array(
					'type'       => 'param_group',
					'param_name' => 'stacked_images',
					'group'      => esc_attr__( 'Images', 'elegant-elements' ),
					'value'      => rawurlencode(
						wp_json_encode(
							array(
								array(
									'image_url' => '',
									'height'    => '300',
									'width'     => '300',
									'top'       => '60',
									'right'     => '0',
								),
								array(
									'image_url' => '',
									'height'    => '300',
									'width'     => '300',
									'top'       => '0',
									'right'     => '0',
								),
							)
						)
					),
					'params'     => array(
						array(
							'type'        => 'attach_image',
							'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
							'description' => esc_attr__( 'Upload an image to display in stack.', 'elegant-elements' ),
							'param_name'  => 'image_url',
							'value'       => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'attach_image',
							'heading'     => esc_attr__( 'Retina Image', 'elegant-elements' ),
							'description' => esc_attr__( 'Upload an image to display on retina devices.', 'elegant-elements' ),
							'param_name'  => 'retina_image_url',
							'value'       => '',
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the css height for the image. ( In Pixel ).', 'elegant-elements' ),
							'param_name'  => 'height',
							'value'       => '300',
							'min'         => '1',
							'max'         => '1500',
							'step'        => '1',
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Width', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the css width for the image. ( In Pixel ).', 'elegant-elements' ),
							'param_name'  => 'width',
							'value'       => '300',
							'min'         => '1',
							'max'         => '1500',
							'step'        => '1',
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Top Position', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the top position for this image. ( In % ).', 'elegant-elements' ),
							'param_name'  => 'top',
							'value'       => '40',
							'min'         => '0',
							'max'         => '100',
							'step'        => '1',
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Right Position', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the right position for this image. ( In % ).', 'elegant-elements' ),
							'param_name'  => 'right',
							'value'       => '0',
							'min'         => '0',
							'max'         => '100',
							'step'        => '1',
						),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_stack', 99 );
