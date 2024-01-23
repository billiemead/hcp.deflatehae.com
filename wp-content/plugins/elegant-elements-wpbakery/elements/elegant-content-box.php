<?php
if ( ! class_exists( 'EEWPB_Content_Box' ) && elegant_is_element_enabled( 'iee_content_box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Content_Box {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Content box counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $content_box_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-content-box', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-icon-wrapper', array( $this, 'icon_wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-icon', array( $this, 'icon_attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-content', array( $this, 'content_attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-heading', array( $this, 'heading_attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-description', array( $this, 'description_attr' ) );
			add_filter( 'eewpb_attr_elegant-content-box-link', array( $this, 'link_attr' ) );

			add_shortcode( 'iee_content_box', array( $this, 'render' ) );
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
					'heading_text'            => esc_attr__( 'Elegant Content Box', 'elegant-elements' ),
					'heading_font_size'       => 20,
					'heading_space'           => 15,
					'heading_text_color'      => '',
					'icon_type'               => 'icon',
					'image_icon_width'        => 64,
					'image_icon_height'       => 64,
					'icon'                    => '',
					'icon_font_size'          => '32',
					'icon_color'              => '',
					'icon_color_hover'        => '',
					'icon_bg_color_hover'     => '',
					'icon_border_color_hover' => '',
					'image_icon'              => '',
					'icon_css'                => '',
					'css'                     => '',
					'alignment'               => 'left',
					'icon_position'           => 'top',
					'icon_vertical_position'  => 'top',
					'link_type'               => 'none',
					'link_url'                => '',
					'link_text'               => esc_attr__( 'Learn More', 'elegant-elements' ),
					'button_shortcode'        => '',
					'hide_on_mobile'          => elegant_elements_default_visibility( 'string' ),
					'class'                   => '',
					'id'                      => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/content-box/elegant-content-box.php' ) ) {
				include locate_template( 'templates/content-box/elegant-content-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/content-box/elegant-content-box.php';
			}

			$this->content_box_counter++;

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
				'class' => 'elegant-content-box',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-content-box-' . $this->content_box_counter;
			$attr['class'] .= ' icon-position-' . $this->args['icon_position'];

			if ( 'top' === $this->args['icon_position'] ) {
				$attr['class'] .= ' elegant-align-' . $this->args['alignment'];
			} else {
				$attr['class'] .= ' icon-vertical-' . $this->args['icon_vertical_position'];
			}

			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $this->args['css'], ' ' ), 'iee_content_box', $this->args );
			if ( '' !== $css_class ) {
				$attr['class'] .= ' ' . $css_class;
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
		public function icon_wrapper_attr() {
			$attr = array(
				'class' => 'elegant-content-box-icon-wrapper',
				'style' => '',
			);

			if ( 'icon' == $this->args['icon_type'] ) {
				$attr['class'] .= ' elegant-clearfix';
				$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_font_size'], 'px' );
				$attr['style']  = 'font-size:' . $font_size . ';';
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
		public function icon_attr() {
			$attr = array(
				'class' => 'elegant-content-box-icon',
				'style' => '',
			);

			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $this->args['icon_css'], ' ' ), 'iee_content_box', $this->args );
			if ( '' !== $css_class ) {
				$attr['class'] .= ' ' . $css_class;
			}

			if ( '' !== $this->args['icon_color'] ) {
				$attr['style'] .= 'color:' . $this->args['icon_color'] . ';';
			}

			if ( 'image' === $this->args['icon_type'] ) {
				$width  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['image_icon_width'], 'px' );
				$height = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['image_icon_height'], 'px' );

				$attr['style'] .= 'width:' . $width . ';';
				$attr['style'] .= 'height:' . $height . ';';
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
				'class' => 'elegant-content-box-content',
				'style' => '',
			);

			if ( 'icon' === $this->args['icon_type'] ) {
				$attr['class'] .= ' elegant-clearfix';
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
		public function heading_attr() {
			$attr = array(
				'class' => 'elegant-content-box-heading',
				'style' => '',
			);

			$font_size     = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' );
			$attr['style'] = 'font-size:' . $font_size . ';';

			if ( '' !== $this->args['heading_text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['heading_text_color'] . ';';
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
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-content-box-description',
				'style' => '',
			);

			$space_before  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_space'], 'px' );
			$attr['style'] = 'margin-top:' . $space_before . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function link_attr() {
			$attr = array(
				'class' => 'elegant-content-box-link',
				'style' => '',
			);

			$attr['class'] .= ' elegant-link-type-' . $this->args['link_type'];

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
			wp_enqueue_style( 'infi-elegant-content-box' );
		}
	}

	new EEWPB_Content_Box();
} // End if().

/**
 * Map shortcode for content_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_content_box() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Content Box', 'elegant-elements' ),
			'shortcode' => 'iee_content_box',
			'icon'      => 'far fa-file-alt content-box-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Heading', 'elegant-elements' ),
					'param_name'  => 'heading_text',
					'admin_label' => true,
					'value'       => esc_attr__( 'Elegant Content Box', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text to be displayed as the content box heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Description', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Content box content goes here. You can edit the content from backend and frontend editor.', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text to be displayed as the content box content.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font size for the heading text in the content box. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '20',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Space between Heading and Description text', 'elegant-elements' ),
					'description' => esc_attr__( 'Control the space between heading and description text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'heading_space',
					'value'       => '15',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_text_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color of the heading text. You can change the color for description text right in the editor.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Type', 'elegant-elements' ),
					'description' => esc_attr__( 'What icon type are you going to use for this content box?', 'elegant-elements' ),
					'param_name'  => 'icon_type',
					'default'     => 'icon',
					'value'       => array(
						'icon'  => esc_attr__( 'Font Icon', 'elegant-elements' ),
						'image' => esc_attr__( 'Image Icon', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Icon Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width for the image icon in the content box. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'image_icon_width',
					'value'       => '64',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'image' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Image Icon Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the image icon in the content box. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'image_icon_height',
					'value'       => '64',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'image' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Content Box Icon', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the icon for this content box.', 'elegant-elements' ),
					'param_name'  => 'icon',
					'default'     => 'fa-wand',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font size for the icon in the content box. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_font_size',
					'value'       => '32',
					'min'         => '10',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Content Box Image Icon', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image icon for this content box.', 'elegant-elements' ),
					'param_name'  => 'image_icon',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'image' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font color of the icon.', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color - Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon font color on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'icon_color_hover',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Background Color - Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon background color on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'icon_bg_color_hover',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Border Color - Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon border color on mouse hover.', 'elegant-elements' ),
					'param_name'  => 'icon_border_color_hover',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'css_editor',
					'heading'     => esc_attr__( 'Icon CSS', 'elegant-elements' ),
					'param_name'  => 'icon_css',
					'value'       => '',
					'description' => '',
					'group'       => esc_attr__( 'Icon', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Position', 'elegant-elements' ),
					'param_name'  => 'icon_position',
					'std'         => 'top',
					'value'       => array(
						'top'   => esc_attr__( 'Top', 'elegant-elements' ),
						'left'  => esc_attr__( 'Left', 'elegant-elements' ),
						'right' => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Position the icon on top, left or right side of the content.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Vertical Alignment', 'elegant-elements' ),
					'param_name'  => 'icon_vertical_position',
					'std'         => 'top',
					'value'       => array(
						'top'    => esc_attr__( 'Top', 'elegant-elements' ),
						'middle' => esc_attr__( 'Middle', 'elegant-elements' ),
						'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Position the icon vertical on top, middle or bottom.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'icon_position',
						'value_not_equal_to' => array( 'top' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Content Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'dependency'  => array(
						'element' => 'icon_position',
						'value'   => array( 'top' ),
					),
					'description' => esc_attr__( 'Align the icon, heading and the description text horizontally to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Link Type', 'elegant-elements' ),
					'param_name'  => 'link_type',
					'std'         => 'none',
					'value'       => array(
						'none'   => esc_attr__( 'No Link', 'elegant-elements' ),
						'box'    => esc_attr__( 'Link Entire Content Box', 'elegant-elements' ),
						'text'   => esc_attr__( 'Add Text Link', 'elegant-elements' ),
						'button' => esc_attr__( 'Add Fancy Button', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose how you want to add link for this content box.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_attr__( 'Link URL', 'elegant-elements' ),
					'param_name'  => 'link_url',
					'value'       => '',
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'box', 'text' ),
					),
					'description' => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Link Text', 'elegant-elements' ),
					'param_name'  => 'link_text',
					'value'       => esc_attr__( 'Learn More', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'text' ),
					),
					'description' => esc_attr__( 'Add the text for the link to be added in the content box.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_shortcode',
					'value'       => '',
					'dependency'  => array(
						'element' => 'link_type',
						'value'   => array( 'button' ),
					),
					'description' => esc_attr__( 'Click the link to generate or edit button shortcode.', 'elegant-elements' ),
					'element_tag' => 'iee_fancy_button',
					'edit_title'  => 'Edit Button Settings',
					'group'       => esc_attr__( 'Link', 'elegant-elements' ),
				),
				array(
					'type'        => 'css_editor',
					'heading'     => esc_attr__( 'CSS', 'elegant-elements' ),
					'param_name'  => 'css',
					'value'       => '',
					'description' => '',
					'group'       => esc_attr__( 'Design Options', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_content_box', 99 );
