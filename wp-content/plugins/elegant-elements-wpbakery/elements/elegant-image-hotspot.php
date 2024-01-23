<?php
if ( ! class_exists( 'EEWPB_Image_Hotspot' ) && elegant_is_element_enabled( 'iee_image_hotspot' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Image_Hotspot {

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
		 * Image Filters counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $image_hotspot_counter = 1;

		/**
		 * Image Filters child counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $image_hotspot_child_counter = 0;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			// Parent filters.
			add_filter( 'eewpb_attr_elegant-image-hotspot', array( $this, 'attr' ) );

			// Child filters.
			add_filter( 'eewpb_attr_elegant-image-hotspot-item', array( $this, 'hotspot_item_attr' ) );

			add_shortcode( 'iee_image_hotspot', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_image_hotspot_item', array( $this, 'render_child' ) );
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

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$this->image_hotspot_child_counter = 1;

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'hotspot_image'                  => '',
					'hotspot_size'                   => '16',
					'hotspot_text_color'             => '#ffffff',
					'hotspot_background_color'       => '#333333',
					'hotspot_background_color_hover' => '#666666',
					'pointer_effect'                 => 'pulse',
					'hide_on_mobile'                 => elegant_elements_default_visibility( 'string' ),
					'class'                          => '',
					'id'                             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/image-hotspot/elegant-image-hotspot-parent.php' ) ) {
				include locate_template( 'templates/image-hotspot/elegant-image-hotspot-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-hotspot/elegant-image-hotspot-parent.php';
			}

			$this->image_hotspot_counter++;

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
					'title'                    => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
					'link_url'                 => '',
					'hotspot_position_left'    => '50',
					'hotspot_position_top'     => '50',
					'pointer_type'             => 'count',
					'pointer_icon'             => '',
					'tooltip_position'         => 'top',
					'tooltip_text_color'       => '#ffffff',
					'tooltip_background_color' => '#333333',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/image-hotspot/elegant-image-hotspot-child.php' ) ) {
				include locate_template( 'templates/image-hotspot/elegant-image-hotspot-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-hotspot/elegant-image-hotspot-child.php';
			}

			$this->image_hotspot_child_counter++;

			return $child_html;
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
				'class' => 'elegant-image-hotspot elegant-image-hotspot-container',
			);

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
		public function hotspot_item_attr() {
			$attr = array(
				'class' => 'elegant-image-hotspot-item',
				'style' => '',
			);

			$size = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['hotspot_size'], 'px' );

			$attr['style'] .= 'font-size:' . $size . ';';
			$attr['style'] .= 'margin-left: calc( -' . $size . ' / 2 );';
			$attr['style'] .= 'margin-top: calc( -' . $size . ' / 2 );';
			$attr['style'] .= 'top:' . $this->child_args['hotspot_position_top'] . ';';
			$attr['style'] .= 'left:' . $this->child_args['hotspot_position_left'] . ';';
			$attr['style'] .= 'background:' . $this->args['hotspot_background_color'] . ';';
			$attr['style'] .= 'color:' . $this->args['hotspot_text_color'] . ';';
			$attr['style'] .= '--background-color:' . $this->args['hotspot_background_color'] . ';';
			$attr['style'] .= '--hover-background-color:' . $this->args['hotspot_background_color_hover'] . ';';
			$attr['style'] .= '--tooltip-text-color:' . $this->child_args['tooltip_text_color'] . ';';
			$attr['style'] .= '--tooltip-background-color:' . $this->child_args['tooltip_background_color'] . ';';

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
			wp_enqueue_style( 'infi-elegant-image-hotspot' );
		}
	}

	new EEWPB_Image_Hotspot();
} // End if().

/**
 * Map shortcode for image_hotspot.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_hotspot() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Image Hotspot', 'elegant-elements' ),
		'shortcode' => 'iee_image_hotspot',
		'icon'      => 'fa-file-image fas image-hotspot-icon',
		'params'    => array(
			array(
				'type'        => 'attach_image',
				'heading'     => esc_attr__( 'Hotspot Image', 'elegant-elements' ),
				'param_name'  => 'hotspot_image',
				'value'       => '',
				'description' => esc_attr__( 'Upload the image to be displayed hotspot locations on.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_hotspot',
				'heading'     => esc_attr__( 'Hotspot Preview', 'elegant-elements' ),
				'param_name'  => 'preview',
				'value'       => '',
				'description' => sprintf( esc_attr__( 'Click to add - Drag to move - Edit content below %1$s Note: this preview will not reflect hotspot style choices or show tooltips. %2$s This is only used as a visual guide for positioning.', 'elegant-elements' ), '<br/>', '<br/>' ),
			),
			array(
				'type'        => 'textarea',
				'heading'     => esc_attr__( 'Hotspots', 'elegant-elements' ),
				'param_name'  => 'content',
				'description' => '',
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Hotspot Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size of the hotspot pointer text. ( In Pixel ).', 'elegant-elements' ),
				'param_name'  => 'hotspot_size',
				'value'       => '16',
				'min'         => '10',
				'max'         => '100',
				'step'        => '1',
				'group'       => 'Design',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Text Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_text_color',
				'value'       => '#ffffff',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the text or icon color of the hotspot pointer.', 'elegant-elements' ),
				'group'       => 'Design',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Background Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_background_color',
				'value'       => '#333333',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the hotspot pointer.', 'elegant-elements' ),
				'group'       => 'Design',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Hotspot Hover Background Color', 'elegant-elements' ),
				'param_name'  => 'hotspot_background_color_hover',
				'value'       => '#666666',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the hotspot pointer when mouse hover.', 'elegant-elements' ),
				'group'       => 'Design',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Pointer Effect', 'elegant-elements' ),
				'param_name'  => 'pointer_effect',
				'std'         => 'pulse',
				'value'       => array(
					'pulse' => esc_attr__( 'Pulse', 'elegant-elements' ),
					'sonar' => esc_attr__( 'Sonar', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the animation effect for the hotspot pointer.', 'elegant-elements' ),
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
	);

	$child_args = array(
		'name'              => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
		'shortcode'         => 'iee_image_hotspot_item',
		'hide_from_builder' => true,
		'as_child'          => array(
			'only' => 'iee_image_hotspot',
		),
		'content_element'   => false,
		'params'            => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'Hotspot Item', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title for this hotspot item to be displayed when hover.', 'elegant-elements' ),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_attr__( 'Link URL', 'elegant-elements' ),
				'param_name'  => 'link_url',
				'value'       => '',
				'dependency'  => array(
					'element'   => 'title',
					'not_empty' => true,
				),
				'description' => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_attr__( 'Pointer Type', 'elegant-elements' ),
				'param_name'  => 'pointer_type',
				'default'     => 'count',
				'value'       => array(
					'count' => esc_attr__( 'The Item Counter Number', 'elegant-elements' ),
					'icon'  => esc_attr__( 'FontAwesome Icon', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Select the pointer placeholder for this item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Pointer Icon', 'elegant-elements' ),
				'param_name'  => 'pointer_icon',
				'value'       => 'fa fa-map-marker',
				'description' => esc_attr__( 'Select the icon to be used as hotspot pointer.', 'elegant-elements' ),
				'dependency'  => array(
					'element' => 'pointer_type',
					'value'   => array( 'icon' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Tooltip Position', 'elegant-elements' ),
				'param_name'  => 'tooltip_position',
				'std'         => 'top',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					'top'    => esc_attr__( 'Top', 'elegant-elements' ),
					'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
				),
				'icons'       => elegant_get_direction_icons(),
				'description' => esc_attr__( 'Select the tooltip position for this hotspot item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Text Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_text_color',
				'default'     => '#ffffff',
				'value'       => '',
				'description' => esc_attr__( 'Choose the text color of the tooltip.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Tooltip Background Color', 'elegant-elements' ),
				'param_name'  => 'tooltip_background_color',
				'default'     => '#333333',
				'value'       => '',
				'description' => esc_attr__( 'Choose the background color of the tooltip.', 'elegant-elements' ),
			),
		),
	);

	elegant_elements_map(
		$parent_args
	);

	elegant_elements_map(
		$child_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_hotspot', 99 );
