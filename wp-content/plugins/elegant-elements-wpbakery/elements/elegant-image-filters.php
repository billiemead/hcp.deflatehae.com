<?php
if ( ! class_exists( 'EEWPB_Image_Filters' ) && elegant_is_element_enabled( 'iee_image_filters' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Image_Filters {

		/**
		 * An array of the parent shortcode arguments.
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
		private $image_filters_counter = 1;

		/**
		 * Image Filters child counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $image_filters_child_counter = 0;

		/**
		 * Image Filters.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $image_filters = array();

		/**
		 * Image Filter Navigation.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $image_filter_navigation = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-image-filters-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-filters-navigation', array( $this, 'navigation_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-filters-navigation-item', array( $this, 'navigation_item_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-filters-content', array( $this, 'content_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-filter-title', array( $this, 'filter_title_attr' ) );
			add_filter( 'eewpb_attr_elegant-image-filter-title-overlay', array( $this, 'title_overlay_attr' ) );

			add_shortcode( 'iee_image_filters', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_filter_image', array( $this, 'render_child' ) );
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

			$this->image_filters_child_counter = 1;

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'columns'                            => '3',
					'grid_item_padding'                  => '10',
					'element_typography'                 => 'default',
					'typography_navigation_title'        => '',
					'navigation_title_font_size'         => '18',
					'navigation_title_color'             => '',
					'typography_image_title'             => '',
					'image_title_font_size'              => '18',
					'image_title_color'                  => '',
					'image_title_position'               => 'after_image',
					'image_title_layout'                 => 'boxed',
					'lightbox_image_meta'                => 'caption',
					'boxed_background_color'             => '',
					'overlay_background_color'           => '',
					'navigation_layout'                  => 'horizontal',
					'navigation_alignment'               => 'left',
					'navigation_position'                => 'left',
					'active_navigation_border_type'      => 'round',
					'use_all_filter'                     => 'yes',
					'all_filter_text'                    => esc_attr__( 'All', 'elegant-elements' ),
					'filter_separator'                   => '',
					'navigation_active_color'            => '',
					'navigation_active_background_color' => '',
					'hide_on_mobile'                     => elegant_elements_default_visibility( 'string' ),
					'class'                              => '',
					'id'                                 => '',
				),
				$args
			);

			$this->args = $defaults;

			// Enqueue scripts.
			$is_vc_inline  = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine
			if ( ! $is_vc_inline ) {
				$this->add_scripts();
			}

			$html = '';
			if ( ! eewpb_is_combined_enqueue() ) {
				$html .= $this->add_styles();
			}

			do_shortcode( $content );

			if ( '' !== locate_template( 'templates/image-filters/elegant-image-filters-parent.php' ) ) {
				include locate_template( 'templates/image-filters/elegant-image-filters-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-filters/elegant-image-filters-parent.php';
			}

			$this->image_filters_counter++;

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
					'image_url'                => '',
					'orientation'              => 'auto',
					'title'                    => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'navigation'               => esc_attr__( 'Category 1, Category 2', 'elegant-elements' ),
					'click_action'             => 'none',
					'element_content'          => '',
					'lightbox_image_meta'      => $this->args['lightbox_image_meta'],
					'modal_anchor'             => '',
					'url'                      => '',
					'target'                   => '_blank',
					'image_title_color'        => '',
					'boxed_background_color'   => '',
					'overlay_background_color' => '',
					'lightbox_image_url'       => '',
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/image-filters/elegant-image-filters-child.php' ) ) {
				include locate_template( 'templates/image-filters/elegant-image-filters-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/image-filters/elegant-image-filters-child.php';
			}

			$this->image_filters_child_counter++;

			return $child_html;
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
				'class' => 'elegant-image-filters-wrapper',
			);

			$attr['class'] .= ' elegant-image-filters-' . $this->image_filters_counter;

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['navigation_layout'] ) && 'horizontal' === $this->args['navigation_layout'] ) {
				$attr['class'] .= ' image-filter-navigation-layout-horizontal image-filter-navigation-align-' . $this->args['navigation_alignment'];
			} else {
				$attr['class'] .= ' image-filter-navigation-layout-vertical image-filter-navigation-position-' . $this->args['navigation_position'];
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
		public function navigation_attr() {
			$attr = array(
				'class'      => 'elegant-image-filters-navigation',
				'role'       => 'menu',
				'aria-label' => 'filters',
				'style'      => '',
			);

			if ( isset( $this->args['active_navigation_border_type'] ) && '' !== $this->args['active_navigation_border_type'] ) {
				$attr['class'] .= ' image-filters-active-navigation-' . $this->args['active_navigation_border_type'];
			}

			if ( isset( $this->args['typography_navigation_title'] ) && '' !== $this->args['typography_navigation_title'] ) {
				$typography_navigation_title = elegant_get_google_font_styling( $this->args, 'typography_navigation_title' );

				$attr['style'] .= $typography_navigation_title;
			}

			if ( isset( $this->args['navigation_title_font_size'] ) && '' !== $this->args['navigation_title_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['navigation_title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['navigation_title_color'] ) && '' !== $this->args['navigation_title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['navigation_title_color'] . ';';
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
		public function navigation_item_attr() {
			$attr = array(
				'class' => 'elegant-image-filters-navigation-item',
				'role'  => 'menuitem',
			);

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
				'class' => 'elegant-image-filters-content',
				'style' => 'opacity:0;',
			);

			$columns = ( isset( $this->args['columns'] ) ) ? $this->args['columns'] : '3';

			$attr['class'] .= ' elegant-image-filter-grid-' . $columns;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $args Current child attributes.
		 * @return array
		 */
		public function filter_title_attr( $args ) {
			$attr = array(
				'class' => 'elegant-image-filter-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_image_title'] ) && '' !== $this->args['typography_image_title'] ) {
				$typography_image_title = elegant_get_google_font_styling( $this->args, 'typography_image_title' );

				$attr['style'] .= $typography_image_title;
			}

			if ( isset( $this->args['image_title_font_size'] ) && '' !== $this->args['image_title_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['image_title_font_size'], 'px' ) . ';';
			}

			if ( isset( $args['image_title_color'] ) && '' !== $args['image_title_color'] ) {
				$attr['style'] .= 'color:' . $args['image_title_color'] . ';';
			} elseif ( isset( $this->args['image_title_color'] ) && '' !== $this->args['image_title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['image_title_color'] . ';';
			}

			if ( isset( $this->args['image_title_position'] ) && 'on_image_hover' !== $this->args['image_title_position'] ) {
				if ( isset( $this->args['image_title_layout'] ) && 'unboxed' !== $this->args['image_title_layout'] ) {
					$boxed_background_color = ( isset( $args['boxed_background_color'] ) && '' !== $args['boxed_background_color'] ) ? $args['boxed_background_color'] : $this->args['boxed_background_color'];
					$attr['class']         .= ' image-filter-title-layout-boxed';
					$attr['style']         .= 'background-color:' . $boxed_background_color . ';';
				}
			} else {
				$attr['class'] .= ' image-filter-title-layout-overlay';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $args Current child attributes.
		 * @return array
		 */
		public function title_overlay_attr( $args ) {
			$attr = array(
				'class' => 'image-filter-title-overlay',
				'style' => '',
			);

			if ( isset( $this->args['grid_item_padding'] ) && '' !== $this->args['grid_item_padding'] ) {
				$attr['style'] .= 'top:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';
				$attr['style'] .= 'left:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';
				$attr['style'] .= 'width: calc( 100% - ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( ( $this->args['grid_item_padding'] * 2 ), 'px' ) . ');';
				$attr['style'] .= 'height: calc( 100% - ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( ( $this->args['grid_item_padding'] * 2 ), 'px' ) . ');';
			}

			$overlay_background_color = ( isset( $args['overlay_background_color'] ) && '' !== $args['overlay_background_color'] ) ? $args['overlay_background_color'] : $this->args['overlay_background_color'];
			$attr['style']           .= 'background-color:' . $overlay_background_color . ';';

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

			wp_enqueue_script( 'isotope' );

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-image-filters',
				$eewpb_js_folder_url . '/infi-elegant-image-filters.min.js',
				$elegant_js_folder_path . '/infi-elegant-image-filters.min.js',
				array( 'jquery', 'infi-imagesloaded', 'infi-packery' ),
				'1',
				true
			);
		}

		/**
		 * Add active navigation styling.
		 *
		 * @access public
		 * @since 1.0
		 * @return string $styles Generated CSS.
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-image-filters' );

			$style  = '<style type="text/css">';
			$style .= '.elegant-image-filters-wrapper.elegant-image-filters-' . $this->image_filters_counter . ' .elegant-image-filters-navigation-item.filter-active {';
			$style .= 'color:' . $this->args['navigation_active_color'] . ';';
			$style .= 'background-color:' . $this->args['navigation_active_background_color'] . ';';
			$style .= '}';
			$style .= '</style>';

			return $style;
		}
	}

	new EEWPB_Image_Filters();
} // End if().


/**
 * Map shortcode for image_filters.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_image_filters() {

	$parent_args = array(
		'name'             => esc_attr__( 'Elegant Image Filters', 'elegant-elements' ),
		'shortcode'        => 'iee_image_filters',
		'icon'             => 'fas fa-images image-filter-icon',
		'as_parent'        => array(
			'only' => 'iee_filter_image',
		),
		'js_view'          => 'VcColumnView',
		'is_container'     => true,
		'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/elegant-image-filters.js',
		'default_content'  => '[iee_filter_image title="' . esc_attr__( 'Your Content Goes Here', 'elegant-elements' ) . '" /]',
		'params'           => array(
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Image Grid Coumns', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the number of columns you want the images to be display.', 'elegant-elements' ),
				'param_name'  => 'columns',
				'value'       => '3',
				'min'         => '2',
				'max'         => '6',
				'step'        => '1',
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Image Padding', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the space you want in between images in the grid. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'grid_item_padding',
				'value'       => '10',
				'min'         => '0',
				'max'         => '100',
				'step'        => '1',
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
				'group'       => 'Typography',
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Navigation Title Font', 'elegant-elements' ),
				'description' => esc_attr__( 'Select font for the navigation title.', 'elegant-elements' ),
				'param_name'  => 'typography_navigation_title',
				'value'       => '',
				'dependency'  => array(
					'element'            => 'element_typography',
					'value_not_equal_to' => 'default',
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Navigation Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for navigation title text. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'navigation_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Navigation Title Color', 'elegant-elements' ),
				'param_name'  => 'navigation_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the navigation title text color for inactive state. To change active nagivation item styling, visit navigation tab.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Image Title Font', 'elegant-elements' ),
				'description' => esc_attr__( 'Select font for the image title.', 'elegant-elements' ),
				'param_name'  => 'typography_image_title',
				'value'       => '',
				'dependency'  => array(
					'element'            => 'element_typography',
					'value_not_equal_to' => 'default',
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Image Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for image title text. In Pixels (px).', 'elegant-elements' ),
				'param_name'  => 'image_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Image Title Color', 'elegant-elements' ),
				'param_name'  => 'image_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the image title text color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Image Title Position', 'elegant-elements' ),
				'param_name'  => 'image_title_position',
				'std'         => 'after_image',
				'value'       => array(
					'after_image'    => esc_attr__( 'After Image', 'elegant-elements' ),
					'before_image'   => esc_attr__( 'Before Image', 'elegant-elements' ),
					'on_image_hover' => esc_attr__( 'On Image Hover', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls where the image title will be displayed.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Image Title Layout', 'elegant-elements' ),
				'param_name'  => 'image_title_layout',
				'std'         => 'boxed',
				'value'       => array(
					'boxed'   => esc_attr__( 'Boxed', 'elegant-elements' ),
					'unboxed' => esc_attr__( 'Unboxed', 'elegant-elements' ),
				),
				'dependency'  => array(
					'element'            => 'image_title_position',
					'value_not_equal_to' => 'on_image_hover',
				),
				'description' => esc_attr__( 'Choose if you want to display image in boxed mode with background color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_checkbox_button_set',
				'heading'     => esc_attr__( 'Image Meta in Lightbox', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to display from the lightbox image meta in lightbox if you display lightbox on image click. Uncheck all to disable.', 'elegant-elements' ),
				'param_name'  => 'lightbox_image_meta',
				'default'     => 'caption',
				'value'       => array(
					'caption' => esc_attr__( 'Caption', 'elegant-elements' ),
					'title'   => esc_attr__( 'Title', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Boxed Background Color', 'elegant-elements' ),
				'param_name'  => 'boxed_background_color',
				'value'       => '#fbfbfb',
				'dependency'  => array(
					'element' => 'image_title_layout',
					'value'   => array( 'boxed' ),
				),
				'description' => esc_attr__( 'Controls the box background color for image title.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
				'param_name'  => 'overlay_background_color',
				'value'       => 'rgba(0,0,0,0.6)',
				'dependency'  => array(
					'element' => 'image_title_position',
					'value'   => array( 'on_image_hover' ),
				),
				'description' => esc_attr__( 'Controls the overlay background color for image title.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Image Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Layout', 'elegant-elements' ),
				'param_name'  => 'navigation_layout',
				'std'         => 'horizontal',
				'value'       => array(
					'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls the filter navigatio layout.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Alignment', 'elegant-elements' ),
				'param_name'  => 'navigation_alignment',
				'std'         => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'icons'       => elegant_get_alignment_icons(),
				'dependency'  => array(
					'element' => 'navigation_layout',
					'value'   => array( 'horizontal' ),
				),
				'description' => esc_attr__( 'Set filter navigation alignment.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Filter Navigation Position', 'elegant-elements' ),
				'param_name'  => 'navigation_position',
				'std'         => 'left',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'dependency'  => array(
					'element' => 'navigation_layout',
					'value'   => array( 'vertical' ),
				),
				'description' => esc_attr__( 'Set filter navigation position for the vertical layout.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Active Navigation Border Type', 'elegant-elements' ),
				'param_name'  => 'active_navigation_border_type',
				'std'         => 'round',
				'value'       => array(
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'bottom' => esc_attr__( 'Bottom Only', 'elegant-elements' ),
					'top'    => esc_attr__( 'Top Only', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Controls the border type for active navigation item.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => sprintf( esc_attr__( 'Use %s Filter', 'elegant-elements' ), '"All"' ),
				'param_name'  => 'use_all_filter',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Choose if you want to enable the "All" filter to display all your images.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => sprintf( esc_attr__( '%s Filter Text', 'elegant-elements' ), '"All"' ),
				'param_name'  => 'all_filter_text',
				'value'       => esc_attr__( 'All', 'elegant-elements' ),
				'placeholder' => true,
				'dependency'  => array(
					'element' => 'use_all_filter',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Choose if you want to enable the "All" filter to display all your images.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Filter Navigation Separator', 'elegant-elements' ),
				'param_name'  => 'filter_separator',
				'value'       => '',
				'placeholder' => true,
				'dependency'  => array(
					'element' => 'navigation_layout',
					'value'   => array( 'horizontal' ),
				),
				'description' => esc_attr__( 'Controls the separator between navigation items.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active Navigation Text Color', 'elegant-elements' ),
				'param_name'  => 'navigation_active_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the active navigation text color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active Navigation Background Color', 'elegant-elements' ),
				'param_name'  => 'navigation_active_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the active navigation background color.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Navigation', 'elegant-elements' ),
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
		'name'         => esc_attr__( 'Filter Image', 'elegant-elements' ),
		'shortcode'    => 'iee_filter_image',
		'icon'         => 'fas fa-image image-filter-icon',
		'as_child'     => array(
			'only' => 'iee_image_filters',
		),
		'is_container' => false,
		'params'       => array(
			array(
				'type'        => 'attach_image',
				'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to be used in the filter.', 'elegant-elements' ),
				'param_name'  => 'image_url',
				'value'       => '',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Image Orientation', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the image orientation you want to set.', 'elegant-elements' ),
				'param_name'  => 'orientation',
				'std'         => 'auto',
				'value'       => array(
					'auto'      => esc_attr__( 'Auto', 'elegant-elements' ),
					'portrait'  => esc_attr__( 'Portrait', 'elegant-elements' ),
					'landscape' => esc_attr__( 'Landscape', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be used for this filter image.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Navitation Category Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be used as filter navigation this image. You can use multiple titles separated with comma.', 'elegant-elements' ),
				'param_name'  => 'navigation',
				'placeholder' => true,
				'value'       => esc_attr__( 'Category 1, Category 2', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to do when user click on filter image.', 'elegant-elements' ),
				'param_name'  => 'click_action',
				'std'         => 'none',
				'value'       => array(
					'modal'    => esc_attr__( 'Open Modal', 'elegant-elements' ),
					'url'      => esc_attr__( 'Open URL', 'elegant-elements' ),
					'lightbox' => esc_attr__( 'Open Lightbox', 'elegant-elements' ),
					'none'     => esc_attr__( 'Do Nothing', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_attr__( 'Lightbox Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to be opened in the lightbox. Default image will be used instead.', 'elegant-elements' ),
				'param_name'  => 'lightbox_image_url',
				'value'       => '',
				'dependency'  => array(
					'element' => 'click_action',
					'value'   => array( 'lightbox' ),
				),
			),
			array(
				'type'        => 'ee_checkbox_button_set',
				'heading'     => esc_attr__( 'Image Meta in Lightbox', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose what you want to display from the lightbox image meta in lightbox. Keep empty to inherit from parent.', 'elegant-elements' ),
				'param_name'  => 'lightbox_image_meta',
				'default'     => '',
				'value'       => array(
					'caption' => esc_attr__( 'Caption', 'elegant-elements' ),
					'title'   => esc_attr__( 'Title', 'elegant-elements' ),
				),
				'dependency'  => array(
					'element' => 'click_action',
					'value'   => array( 'lightbox' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Modal Window Anchor', 'elegant-elements' ),
				'description' => esc_attr__( 'Add the class name of the modal window you want to open on filter image click.', 'elegant-elements' ),
				'param_name'  => 'modal_anchor',
				'value'       => '',
				'dependency'  => array(
					'element' => 'click_action',
					'value'   => array( 'modal' ),
				),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter the url you want to open on filter image click.', 'elegant-elements' ),
				'param_name'  => 'url',
				'value'       => '',
				'dependency'  => array(
					'element' => 'click_action',
					'value'   => array( 'url' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Image Title Color', 'elegant-elements' ),
				'param_name'  => 'image_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to override the image title text color set in parent. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Boxed Background Color', 'elegant-elements' ),
				'param_name'  => 'boxed_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to override the box background color set in parent for this image title. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Overlay Background Color', 'elegant-elements' ),
				'param_name'  => 'overlay_background_color',
				'value'       => '',
				'description' => esc_attr__( 'Change to overrides the overlay background color set in parent for this image title. Keep empty to use parent default.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Title Styling', 'elegant-elements' ),
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

	elegant_elements_map(
		$parent_args
	);

	elegant_elements_map(
		$child_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_image_filters', 99 );

// Container content element should extend WPBakeryShortCodesContainer class to inherit all required functionality.
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Iee_Image_Filters extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Iee_Filter_Image extends WPBakeryShortCode {
	}
}
