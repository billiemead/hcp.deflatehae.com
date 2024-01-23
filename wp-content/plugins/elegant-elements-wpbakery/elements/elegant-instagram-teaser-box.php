<?php
if ( ! class_exists( 'EEWPB_Instagram_Teaser_Box' ) && elegant_is_element_enabled( 'iee_instagram_teaser_box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Instagram_Teaser_Box {

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

			add_filter( 'eewpb_attr_elegant-instagram-teaser-box', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-instagram-teaser-box-follow-button', array( $this, 'button_attr' ) );

			add_shortcode( 'iee_instagram_teaser_box', array( $this, 'render' ) );
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
					'username'                      => 'unsplash',
					'max_height'                    => 150,
					'button_text_color'             => '#333333',
					'button_background_color'       => '#ffffff',
					'button_text_color_hover'       => '#ffffff',
					'button_background_color_hover' => '#333333',
					'hide_on_mobile'                => elegant_elements_default_visibility( 'string' ),
					'class'                         => '',
					'id'                            => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/instagram-teaser-box/elegant-instagram-teaser-box.php' ) ) {
				include locate_template( 'templates/instagram-teaser-box/elegant-instagram-teaser-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/instagram-teaser-box/elegant-instagram-teaser-box.php';
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
				'class' => 'elegant-instagram-teaser-box',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-clearfix';

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
		public function button_attr() {
			$attr = array(
				'class' => 'button follow-button',
				'style' => '',
			);

			$attr['style'] .= '--color:' . $this->args['button_text_color'] . ';';
			$attr['style'] .= '--color-hover:' . $this->args['button_text_color_hover'] . ';';
			$attr['style'] .= '--background-color:' . $this->args['button_background_color'] . ';';
			$attr['style'] .= '--background-color-hover:' . $this->args['button_background_color_hover'] . ';';

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
			wp_enqueue_style( 'infi-elegant-instagram-teaser-box' );
		}
	}

	new EEWPB_Instagram_Teaser_Box();
} // End if().

/**
 * Map shortcode for instagram_teaser_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_instagram_teaser_box() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Instagram Teaser Box', 'elegant-elements' ),
			'shortcode' => 'iee_instagram_teaser_box',
			'icon'      => 'fa-instagram fab instagram-teaser-box-icon',
			'params'    => array(
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the css height to be used for the images in the block. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'max_height',
					'value'       => '150',
					'min'         => '1',
					'max'         => '500',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Text Color', 'elegant-elements' ),
					'param_name'  => 'button_text_color',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the follow button text color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Background Color', 'elegant-elements' ),
					'param_name'  => 'button_background_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the follow button background color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Text Color on Hover', 'elegant-elements' ),
					'param_name'  => 'button_text_color_hover',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Controls the follow button text color on hover.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Follow Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Follow Button Background Color on Hover', 'elegant-elements' ),
					'param_name'  => 'button_background_color_hover',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the follow button text color.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Follow Button', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_instagram_teaser_box', 99 );
