<?php
if ( ! class_exists( 'EEWPB_Testimonials' ) && elegant_is_element_enabled( 'iee_testimonials' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Testimonials {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Testimonials counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $testimonials_counter = 1;

		/**
		 * Testimonials.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $testimonials = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'iee_testimonials', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_testimonial', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$testimonial_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title'     => esc_attr__( 'John Doe', 'elegant-elements' ),
							'sub_title' => esc_attr__( 'Designer', 'elegant-elements' ),
							'testimony' => esc_attr__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam posuere erat nec porta venenatis. Donec rhoncus nisi non placerat tempus.', 'elegant-elements' ),
						),
						array(
							'title'     => esc_attr__( 'Alice Brooks', 'elegant-elements' ),
							'sub_title' => esc_attr__( 'Developer', 'elegant-elements' ),
							'testimony' => esc_attr__( 'Morbi tempus vestibulum sem vel varius. Sed condimentum mollis diam nec placerat. Curabitur in quam quis quam volutpat imperdiet ac eu augue.', 'elegant-elements' ),
						),
						array(
							'title'     => esc_attr__( 'Gary Meyer', 'elegant-elements' ),
							'sub_title' => esc_attr__( 'Agency', 'elegant-elements' ),
							'testimony' => esc_attr__( 'Maecenas sodales nisi ligula, sed consectetur felis pellentesque sit amet. Donec rhoncus est orci, non cursus enim ullamcorper vel. Nam ut dapibus risus.', 'elegant-elements' ),
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'heading_size'         => 'h2',
					'text_color'           => '',
					'background_color'     => '',
					'background_image'     => '',
					'background_position'  => 'left top',
					'element_typography'   => 'default',
					'typography_title'     => '',
					'typography_sub_title' => '',
					'typography_content'   => '',
					'title_font_size'      => '28',
					'sub_title_font_size'  => '18',
					'content_font_size'    => '16',
					'description_position' => 'left',
					'hide_on_mobile'       => elegant_elements_default_visibility( 'string' ),
					'class'                => '',
					'id'                   => '',
					'testimonial_items'    => $testimonial_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$testimonial_items = vc_param_group_parse_atts( $this->args['testimonial_items'] );

			// Loop through the list items and generate a shortcode.
			$content = '';
			foreach ( $testimonial_items as $item ) {
				$content .= '[iee_testimonial';
				foreach ( $item as $title => $value ) {
					if ( 'testimony' !== $title ) {
						$content .= ' ' . $title . '="' . $value . '"';
					}
				}
				$content .= ']' . $item['testimony'] . '[/iee_testimonial]';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/testimonials/elegant-testimonials-parent.php' ) ) {
				include locate_template( 'templates/testimonials/elegant-testimonials-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/testimonials/elegant-testimonials-parent.php';
			}

			$this->testimonials_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'image_url'       => '',
					'title'           => esc_attr__( 'Your Title Goes Here', 'elegant-elements' ),
					'title_color'     => '',
					'sub_title'       => '',
					'sub_title_color' => '',
					'class'           => '',
					'id'              => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/testimonials/elegant-testimonials-child.php' ) ) {
				include locate_template( 'templates/testimonials/elegant-testimonials-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/testimonials/elegant-testimonials-child.php';
			}

			return $child_html;
		}

		/**
		 * Returns equivalent global information for FB param.
		 *
		 * @since 1.0
		 * @return array|bool Element option data.
		 */
		public function iee_testimonials_map_descriptions() {
			$shortcode_option_map = array();

			$shortcode_option_map['background_color']['iee_testimonials'] = array(
				'theme-option' => 'iee_testimonials_background_color',
				'reset'        => true,
			);

			return $shortcode_option_map;
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
				'infi-elegant-testimonials',
				$eewpb_js_folder_url . '/infi-elegant-testimonials.min.js',
				$elegant_js_folder_path . '/infi-elegant-testimonials.min.js',
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
			wp_enqueue_style( 'infi-elegant-testimonials' );
		}
	}

	new EEWPB_Testimonials();
} // End if().


/**
 * Map shortcode for testimonials.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_testimonials() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Testimonials', 'elegant-elements' ),
		'shortcode' => 'iee_testimonials',
		'icon'      => 'fas fa-users testimonials-icon',
		'params'    => array(
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the testimonials title size, H1-H6.', 'elegant-elements' ),
				'param_name'  => 'heading_size',
				'value'       => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'std'         => 'h2',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'param_name'  => 'text_color',
				'value'       => '',
				'description' => esc_attr__( 'Select text color for testimonial. Individual element text colors can be changed from child testimonial.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
				'param_name'  => 'background_color',
				'value'       => '',
				'description' => esc_attr__( 'Select background color for the testimonials container.', 'elegant-elements' ),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_attr__( 'Background Image', 'elegant-elements' ),
				'param_name'  => 'background_image',
				'value'       => '',
				'description' => esc_attr__( 'Select background image for the testimonials container.', 'elegant-elements' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_attr__( 'Background Image Position', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the postion of the background image for testimonials background image.', 'elegant-elements' ),
				'param_name'  => 'background_position',
				'default'     => 'left top',
				'dependency'  => array(
					'element'   => 'background_image',
					'not_empty' => true,
				),
				'value'       => array(
					'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
					'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
					'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
					'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
					'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
					'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
					'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
					'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
					'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
				'param_name'  => 'element_typography',
				'std'         => 'default',
				'value'       => array(
					'default' => esc_attr__( 'Default', 'elegant-elements' ),
					'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial title.', 'elegant-elements' ),
				'param_name'  => 'typography_title',
				'value'       => '',
				'dependency'  => array(
					'element' => 'element_typography',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Sub-title Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial sub-title.', 'elegant-elements' ),
				'param_name'  => 'typography_sub_title',
				'value'       => '',
				'dependency'  => array(
					'element' => 'element_typography',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial content.', 'elegant-elements' ),
				'param_name'  => 'typography_content',
				'value'       => '',
				'dependency'  => array(
					'element' => 'element_typography',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for title. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'title_font_size',
				'value'       => '28',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Sub Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for title. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'sub_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for testimonial content. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'content_font_size',
				'value'       => '16',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Description Position', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the postion of the testimonial content.', 'elegant-elements' ),
				'param_name'  => 'description_position',
				'std'         => 'left',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
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
			array(
				'type'       => 'param_group',
				'param_name' => 'testimonial_items',
				'group'      => esc_attr__( 'Testimonial Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'title'     => esc_attr__( 'John Doe', 'elegant-elements' ),
								'sub_title' => esc_attr__( 'Designer', 'elegant-elements' ),
								'testimony' => esc_attr__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam posuere erat nec porta venenatis. Donec rhoncus nisi non placerat tempus.', 'elegant-elements' ),
							),
							array(
								'title'     => esc_attr__( 'Alice Brooks', 'elegant-elements' ),
								'sub_title' => esc_attr__( 'Developer', 'elegant-elements' ),
								'testimony' => esc_attr__( 'Morbi tempus vestibulum sem vel varius. Sed condimentum mollis diam nec placerat. Curabitur in quam quis quam volutpat imperdiet ac eu augue.', 'elegant-elements' ),
							),
							array(
								'title'     => esc_attr__( 'Gary Meyer', 'elegant-elements' ),
								'sub_title' => esc_attr__( 'Agency', 'elegant-elements' ),
								'testimony' => esc_attr__( 'Maecenas sodales nisi ligula, sed consectetur felis pellentesque sit amet. Donec rhoncus est orci, non cursus enim ullamcorper vel. Nam ut dapibus risus.', 'elegant-elements' ),
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'attach_image',
						'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
						'description' => esc_attr__( 'Upload an image to display in the frame.', 'elegant-elements' ),
						'param_name'  => 'image_url',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter title to be displayed for this testimonial.', 'elegant-elements' ),
						'param_name'  => 'title',
						'placeholder' => true,
						'admin_label' => true,
						'value'       => esc_attr__( 'Your Title Goes Here', 'elegant-elements' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Sub Title', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter sub-title to be displayed for this testimonial.', 'elegant-elements' ),
						'param_name'  => 'sub_title',
						'placeholder' => true,
						'value'       => esc_attr__( 'Sub Title Here', 'elegant-elements' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_attr__( 'Description', 'elegant-elements' ),
						'description' => esc_attr__( 'Provide testimonial description.', 'elegant-elements' ),
						'param_name'  => 'testimony',
						'value'       => esc_attr__( 'Your testimonial description text goes here.', 'elegant-elements' ),
						'placeholder' => true,
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_attr__( 'Title Color', 'elegant-elements' ),
						'param_name'       => 'title_color',
						'value'            => '',
						'edit_field_class' => 'vc_col-sm-6 vc_col-md-6',
						'description'      => esc_attr__( 'Select text color for this testimonial title.', 'elegant-elements' ),
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_attr__( 'Sub Title Color', 'elegant-elements' ),
						'param_name'       => 'sub_title_color',
						'value'            => '',
						'edit_field_class' => 'vc_col-sm-6 vc_col-md-6',
						'description'      => esc_attr__( 'Select text color for this testimonial sub title.', 'elegant-elements' ),
					),
				),
			),
		),
	);

	elegant_elements_map(
		$parent_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_testimonials', 99 );
