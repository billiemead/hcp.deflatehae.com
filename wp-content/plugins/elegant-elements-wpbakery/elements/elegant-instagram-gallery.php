<?php
if ( ! class_exists( 'EEWPB_Instagram_Gallery' ) && elegant_is_element_enabled( 'iee_instagram_gallery' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Instagram_Gallery {

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
			add_filter( 'eewpb_attr_elegant-instagram-gallery', array( $this, 'attr' ) );
			add_shortcode( 'iee_instagram_gallery', array( $this, 'render' ) );
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
					'username'        => '@unsplash',
					'photos_count'    => '10',
					'gallery_layout'  => 'grid',
					'photo_size'      => 'small',
					'masonry_columns' => 'small',
					'link_target'     => 'lightbox',
					'show_likes'      => 'no',
					'show_comments'   => 'no',
					'hover_type'      => 'none',
					'hide_on_mobile'  => elegant_elements_default_visibility( 'string' ),
					'class'           => '',
					'id'              => '',
				),
				$args
			);

			$is_vc_inline  = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine
			if ( $is_vc_inline ) {
				$defaults['gallery_layout'] = 'grid';
				$defaults['class']         .= ' legecy-masonry-mode';
			}

			$this->args = $defaults;

			$html = '';

			if ( ! is_admin() ) {
				if ( '' !== locate_template( 'templates/instagram-gallery/elegant-instagram-gallery.php' ) ) {
					include locate_template( 'templates/instagram-gallery/elegant-instagram-gallery.php', false );
				} else {
					include EEWPB_PLUGIN_DIR . 'templates/instagram-gallery/elegant-instagram-gallery.php';
				}
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
				'class' => 'elegant-instagram-gallery',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( 'none' !== $this->args['hover_type'] ) {
				$attr['class'] .= ' elegant-image-hovers';
			}

			if ( 'grid' !== $this->args['gallery_layout'] ) {
				$attr['class'] .= ' elegant-instagram-gallery-masonry';
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
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_scripts() {
			wp_enqueue_script( 'lightbox' );
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-instagram-gallery' );
		}
	}

	new EEWPB_Instagram_Gallery();
} // End if().

/**
 * Map shortcode for instagram_gallery.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_instagram_gallery() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Instagram Gallery', 'elegant-elements' ),
			'shortcode' => 'iee_instagram_gallery',
			'icon'      => 'fa-instagram fab instagram-gallery-icon',
			'params'    => array(
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Number of Photos', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the number of photos to be displayed.', 'elegant-elements' ),
					'param_name'  => 'photos_count',
					'value'       => '10',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Gallery Layout', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to display gallery in grid or as masonry layout.', 'elegant-elements' ),
					'param_name'  => 'gallery_layout',
					'std'         => 'grid',
					'value'       => array(
						'grid'    => esc_attr__( 'Grid', 'elegant-elements' ),
						'masonry' => esc_attr__( 'Masonry', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_info',
					'heading'     => esc_attr__( 'Masonry in Preview Mode', 'elegant-elements' ),
					'description' => '',
					'param_name'  => 'masonry_info',
					'value'       => esc_attr__( 'Masonry layout works perfect on the frontend. For the preview mode, we disabled the JS masonry layout to save bandwidth and the masonry being displayed using CSS only solution to give you an idea.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'gallery_layout',
						'value'   => array( 'masonry' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Photo Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the Photo size you want to use. Larger the size, higher the loading time.', 'elegant-elements' ),
					'param_name'  => 'photo_size',
					'default'     => 'small',
					'value'       => array(
						'thumbnail' => esc_attr__( 'Thumbnail ( 5 Columns )', 'elegant-elements' ),
						'small'     => esc_attr__( 'Small ( 4 Columns )', 'elegant-elements' ),
						'large'     => esc_attr__( 'Large ( 3 Columns )', 'elegant-elements' ),
						'original'  => esc_attr__( 'Original ( 2 Columns )', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element' => 'gallery_layout',
						'value'   => array( 'grid' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Masonry Columns', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the number of columns for masonry layout.', 'elegant-elements' ),
					'param_name'  => 'masonry_columns',
					'default'     => 'small',
					'value'       => array(
						'thumbnail' => esc_attr__( '5 Columns', 'elegant-elements' ),
						'small'     => esc_attr__( '4 Columns', 'elegant-elements' ),
						'large'     => esc_attr__( '3 Columns', 'elegant-elements' ),
						'original'  => esc_attr__( '2 Columns', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element' => 'gallery_layout',
						'value'   => array( 'masonry' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Link Target', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how you want the images to open when clicked.', 'elegant-elements' ),
					'param_name'  => 'link_target',
					'default'     => 'lightbox',
					'value'       => array(
						'_self'    => esc_attr__( 'Current window ( _self )', 'elegant-elements' ),
						'_blank'   => esc_attr__( 'New window ( _blank )', 'elegant-elements' ),
						'lightbox' => esc_attr__( 'Lightbox', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Hover Image Zoom', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the hover effect type.', 'elegant-elements' ),
					'param_name'  => 'hover_type',
					'value'       => array(
						'none'    => esc_attr__( 'None', 'elegant-elements' ),
						'zoomin'  => esc_attr__( 'Zoom In', 'elegant-elements' ),
						'zoomout' => esc_attr__( 'Zoom Out', 'elegant-elements' ),
					),
					'std'         => 'none',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_instagram_gallery', 99 );
