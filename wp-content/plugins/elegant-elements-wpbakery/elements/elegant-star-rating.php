<?php
if ( ! class_exists( 'EEWPB_Star_Rating' ) && elegant_is_element_enabled( 'iee_star_rating' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.6.0
	 */
	class EEWPB_Star_Rating {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.6.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.6.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-star-rating', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-star-rating-item', array( $this, 'item_attr' ) );

			add_shortcode( 'iee_star_rating', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.6.0
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
					'icon'              => 'sharp',
					'unfilled_style'    => 'solid',
					'rating_scale'      => '5',
					'rating_value'      => '4.5',
					'alignment'         => 'left',
					'icon_size'         => '32',
					'icon_spacing'      => '10',
					'icon_fill_color'   => '#f0ad4e',
					'icon_unfill_color' => '#ccd6df',
					'hide_on_mobile'    => elegant_elements_default_visibility( 'string' ),
					'class'             => '',
					'id'                => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/star-rating/elegant-star-rating.php' ) ) {
				include locate_template( 'templates/star-rating/elegant-star-rating.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/star-rating/elegant-star-rating.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.6.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-star-rating elegant-align-' . $this->args['alignment'],
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['title']    = $this->args['rating_value'] . '/' . $this->args['rating_scale'];
			$attr['itemtype'] = 'http://schema.org/Rating';
			$attr['itemprop'] = 'reviewRating';

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
		 * @since 1.6.0
		 * @param array $args Single start item options.
		 * @return array
		 */
		public function item_attr( $args ) {
			$attr = array(
				'class' => 'elegant-star',
				'style' => '',
			);

			if ( ! isset( $args['last_icon'] ) ) {
				$icon_spacing   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_spacing'], 'px' );
				$attr['style'] .= 'margin-right:' . $icon_spacing . ';';
			}

			$icon_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_size'], 'px' );
			$attr['style'] .= 'width:' . $icon_size . '; height:' . $icon_size . ';';

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.6.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-star-rating' );
		}
	}

	new EEWPB_Star_Rating();
} // End if().

/**
 * Map shortcode for star_rating.
 *
 * @since 1.6.0
 * @return void
 */
function map_elegant_elements_wpbakery_star_rating() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Star Rating', 'elegant-elements' ),
			'shortcode' => 'iee_star_rating',
			'icon'      => 'fa-star fas star-rating-icon',
			'params'    => array(
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the star icon.', 'elegant-elements' ),
					'param_name'  => 'icon',
					'std'         => 'sharp',
					'value'       => array(
						'sharp'   => esc_attr__( 'Sharp Edges', 'elegant-elements' ),
						'rounded' => esc_attr__( 'Rounded Edges', 'elegant-elements' ),
					),
					'back_icons'  => array(
						'sharp'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="#333333" d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z"/></svg>',
						'rounded' => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="3 3 18 18" width="24px" fill="#333333"><g><path d="M12,17.27l4.15,2.51c0.76,0.46,1.69-0.22,1.49-1.08l-1.1-4.72l3.67-3.18c0.67-0.58,0.31-1.68-0.57-1.75l-4.83-0.41 l-1.89-4.46c-0.34-0.81-1.5-0.81-1.84,0L9.19,8.63L4.36,9.04c-0.88,0.07-1.24,1.17-0.57,1.75l3.67,3.18l-1.1,4.72 c-0.2,0.86,0.73,1.54,1.49,1.08L12,17.27z"/></g></svg>',
					),
					'icons'       => array(
						'sharp'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="#333333" d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z"/></svg>',
						'rounded' => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="3 3 18 18" width="24px" fill="#333333"><g><path d="M12,17.27l4.15,2.51c0.76,0.46,1.69-0.22,1.49-1.08l-1.1-4.72l3.67-3.18c0.67-0.58,0.31-1.68-0.57-1.75l-4.83-0.41 l-1.89-4.46c-0.34-0.81-1.5-0.81-1.84,0L9.19,8.63L4.36,9.04c-0.88,0.07-1.24,1.17-0.57,1.75l3.67,3.18l-1.1,4.72 c-0.2,0.86,0.73,1.54,1.49,1.08L12,17.27z"/></g></svg>',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Unfilled Icon Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon style for the unfilled icons.', 'elegant-elements' ),
					'param_name'  => 'unfilled_style',
					'std'         => 'solid',
					'value'       => array(
						'solid'   => esc_attr__( 'Solid', 'elegant-elements' ),
						'outline' => esc_attr__( 'Outline', 'elegant-elements' ),
					),
					'back_icons'  => array(
						'solid'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z"/></svg>',
						'outline' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928L12 18.26zm0-2.292l4.247 2.377-.949-4.773 3.573-3.305-4.833-.573L12 5.275l-2.038 4.42-4.833.572 3.573 3.305-.949 4.773L12 15.968z"/></svg>',
					),
					'icons'       => array(
						'solid'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928z"/></svg>',
						'outline' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18.26l-7.053 3.948 1.575-7.928L.587 8.792l8.027-.952L12 .5l3.386 7.34 8.027.952-5.935 5.488 1.575 7.928L12 18.26zm0-2.292l4.247 2.377-.949-4.773 3.573-3.305-4.833-.573L12 5.275l-2.038 4.42-4.833.572 3.573 3.305-.949 4.773L12 15.968z"/></svg>',
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Rating Scale', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the rating scale.', 'elegant-elements' ),
					'param_name'  => 'rating_scale',
					'default'     => '5',
					'value'       => array(
						'5'  => '0-5',
						'10' => '0-10',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Rating', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the rating value.', 'elegant-elements' ),
					'param_name'  => 'rating_value',
					'value'       => '4.5',
					'min'         => '0',
					'max'         => '10',
					'step'        => '0.1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the icons to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the icon size. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_size',
					'value'       => '32',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Design',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Space Between Icons', 'elegant-elements' ),
					'description' => esc_attr__( 'Select spacing between icons. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_spacing',
					'value'       => '10',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Filled Icon Color', 'elegant-elements' ),
					'param_name'  => 'icon_fill_color',
					'value'       => '#f0ad4e',
					'description' => esc_attr__( 'Controls the icon color for the filled part.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Unfilled Icon Color', 'elegant-elements' ),
					'param_name'  => 'icon_unfill_color',
					'value'       => '#ccd6df',
					'description' => esc_attr__( 'Controls the icon color for the unfilled part.', 'elegant-elements' ),
					'group'       => 'Design',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_star_rating', 99 );
