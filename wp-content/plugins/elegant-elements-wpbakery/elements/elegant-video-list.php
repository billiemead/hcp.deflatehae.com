<?php
if ( ! class_exists( 'EEWPB_Video_List' ) && elegant_is_element_enabled( 'iee_video_list' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Video_List {

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
		 * Video list counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $video_list_counter = 1;

		/**
		 * Video list child counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $video_list_child_counter = 0;

		/**
		 * Element styles.
		 *
		 * @access protected
		 * @since 1.0
		 * @var string
		 */
		protected $styles;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine
			if ( $is_vc_inline ) {
				$this->video_list_counter = wp_rand();
			}

			// Parent filters.
			add_filter( 'eewpb_attr_elegant-video-list', array( $this, 'attr' ) );

			// Child filters.
			add_filter( 'eewpb_attr_elegant-video-list-item', array( $this, 'list_item_attr' ) );
			add_filter( 'eewpb_attr_elegant-video-list-icon', array( $this, 'list_item_icon_attr' ) );
			add_filter( 'eewpb_attr_elegant-video-list-item-title', array( $this, 'list_item_title_attr' ) );

			add_shortcode( 'iee_video_list', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_video_list_item', array( $this, 'render_child' ) );
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

			$this->video_list_child_counter = 1;

			$default_video_list_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title'          => esc_attr__( 'Youtube video', 'elegant-elements' ),
							'video_provider' => 'youtube',
							'video_id'       => 'il2ZAZX9KpQ',
						),
						array(
							'title'          => esc_attr__( 'Wistia Video', 'elegant-elements' ),
							'video_provider' => 'wistia',
							'video_id'       => 'wxz33a3fuc',
						),
						array(
							'title'          => esc_attr__( 'Vimeo Video', 'elegant-elements' ),
							'video_provider' => 'vimeo',
							'video_id'       => '280293143',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'video_position'                    => 'top',
					'video_list_icon'                   => '',
					'icon_color'                        => '#03a9f4',
					'title_color'                       => '#333333',
					'list_item_background_color'        => '#fbfbfb',
					'active_icon_color'                 => '#ffffff',
					'active_title_color'                => '#ffffff',
					'active_list_item_background_color' => '#03a9f4',
					'video_container_background_color'  => '#fbfbfb',
					'border_size'                       => '1',
					'border_color'                      => 'rgba(208,233,244,0.1)',
					'border_style'                      => 'solid',
					'border_position'                   => 'all',
					'border_radius'                     => '',
					'border_radius_top_left'            => '',
					'border_radius_top_right'           => '',
					'border_radius_bottom_right'        => '',
					'border_radius_bottom_left'         => '',
					'element_typography'                => 'default',
					'typography_title'                  => '',
					'title_font_size'                   => '18',
					'icon_font_size'                    => '24',
					'hide_on_mobile'                    => elegant_elements_default_visibility( 'string' ),
					'class'                             => '',
					'id'                                => '',
					'video_list_items'                  => $default_video_list_items,
				),
				$args
			);

			$this->args   = $defaults;
			$this->styles = $this->render_styles();

			// Parse list item params.
			$video_list_items = vc_param_group_parse_atts( $this->args['video_list_items'] );

			// Loop through the list items and generate a shortcode.
			foreach ( $video_list_items as $list_item ) {
				$content .= '[iee_video_list_item';
				foreach ( $list_item as $title => $value ) {
					$content .= ' ' . $title . '="' . $value . '"';
				}
				$content .= ']';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/video-list/elegant-video-list-parent.php' ) ) {
				include locate_template( 'templates/video-list/elegant-video-list-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/video-list/elegant-video-list-parent.php';
			}

			$this->video_list_counter++;

			return $html;
		}

		/**
		 * Render the styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return string Styles.
		 */
		public function render_styles() {
			$styles = '';

			$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item {';

			if ( isset( $this->args['list_item_background_color'] ) ) {
				$styles .= 'background:' . $this->args['list_item_background_color'] . ';';
			}

			if ( isset( $this->args['border_size'] ) && 0 !== $this->args['border_size'] ) {
				$styles .= eewpb_get_border_style( $this->args );
			}

			$styles .= '}';

			if ( isset( $this->args['active_list_item_background_color'] ) ) {
				$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item.active-item,
				 			.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item:hover {';
				$styles .= 'background:' . $this->args['active_list_item_background_color'] . ';';
				$styles .= '}';
			}

			$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item .elegant-video-list-item-title .video-list-icon {';
			$styles .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_font_size'], 'px' ) . ';';

			if ( isset( $this->args['icon_color'] ) ) {
				$styles .= 'color:' . $this->args['icon_color'] . ';';
			}

			$styles .= '}';

			$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item .elegant-video-list-item-title {';

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$title_typography = elegant_get_google_font_styling( $this->args, 'typography_title' );

				$styles .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$styles .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['title_color'] ) ) {
				$styles .= 'color:' . $this->args['title_color'] . ';';
			}

			$styles .= '}';

			if ( isset( $this->args['active_icon_color'] ) ) {
				$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item.active-item .elegant-video-list-item-title .video-list-icon,
								  .elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item:hover .elegant-video-list-item-title .video-list-icon {';
				$styles .= 'color:' . $this->args['active_icon_color'] . ';';
				$styles .= '}';
			}

			if ( isset( $this->args['active_title_color'] ) ) {
				$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item.active-item .elegant-video-list-item-title,
								  .elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-items .elegant-video-list-item:hover .elegant-video-list-item-title {';
				$styles .= 'color:' . $this->args['active_title_color'] . ';';
				$styles .= '}';
			}

			$styles .= '.elegant-video-list.elegant-video-list-' . $this->video_list_counter . ' .elegant-video-list-video-container iframe {';
			$styles .= 'background:' . $this->args['video_container_background_color'] . ';';
			$styles .= '}';

			return $styles;
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
					'title'          => esc_attr__( 'Video Item', 'elegant-elements' ),
					'video_provider' => 'youtube',
					'video_id'       => '',
					'video_file'     => '',
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->child_args = $defaults;
			$child_html       = '';

			if ( '' !== locate_template( 'templates/video-list/elegant-video-list-child.php' ) ) {
				include locate_template( 'templates/video-list/elegant-video-list-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/video-list/elegant-video-list-child.php';
			}

			$this->video_list_child_counter++;

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
				'class' => 'elegant-video-list elegant-video-list-container elegant-video-list-' . $this->video_list_counter,
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' video-position-' . $this->args['video_position'];

			if ( isset( $this->args['class'] ) && '' !== $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) && '' !== $this->args['id'] ) {
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
		public function list_item_attr() {
			$attr = array(
				'class' => 'elegant-video-list-item elegant-video-list-item-' . $this->video_list_child_counter,
				'style' => '',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $args Child shortcode attributes.
		 * @return array
		 */
		public function list_item_title_attr( $args ) {
			$attr = array(
				'class' => 'elegant-video-list-item-title',
				'style' => '',
			);

			if ( isset( $args['video_provider'] ) && 'hosted' !== $args['video_provider'] ) {
				$attr['data-embed-url'] = eewpb_get_embed_url_by_provider( $args['video_provider'], $args['video_id'] );
			} else {
				$attr['data-embed-url'] = $args['video_file'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for the icon.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function list_item_icon_attr() {
			$attr = array(
				'class' => 'video-list-icon',
				'style' => '',
			);

			$icon_class = $this->args['video_list_icon'];

			$attr['class'] .= ' ' . $icon_class;

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
				'infi-elegant-video-list',
				$eewpb_js_folder_url . '/infi-elegant-video-list.min.js',
				$elegant_js_folder_path . '/infi-elegant-video-list.min.js',
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
			wp_enqueue_style( 'infi-elegant-video-list' );
		}
	}

	new EEWPB_Video_List();
} // End if().

/**
 * Map shortcode for video_list.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_video_list() {

	$args = array(
		'name'             => esc_attr__( 'Elegant Video List', 'elegant-elements' ),
		'shortcode'        => 'iee_video_list',
		'icon'             => 'fa-film fas video-list-icon',
		'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/elegant-video-list.js',
		'params'           => array(
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Video position', 'elegant-elements' ),
				'param_name'  => 'video_position',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'top'   => esc_attr__( 'Top', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'std'         => 'top',
				'description' => esc_attr__( 'Choose how you want to position the video. The video list will appear in the opposite direction.', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Video List Icon', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the icon for the video list items.', 'elegant-elements' ),
				'param_name'  => 'video_list_icon',
				'value'       => 'fa-play fas',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
				'param_name'  => 'icon_color',
				'value'       => '#03a9f4',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the icon color of the video list icon.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'List Title Color', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '#333333',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the text color of the video list title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'List Item Background', 'elegant-elements' ),
				'param_name'  => 'list_item_background_color',
				'value'       => '#fbfbfb',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the list item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active - Icon Color', 'elegant-elements' ),
				'param_name'  => 'active_icon_color',
				'value'       => '#ffffff',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the icon color of the active video list icon.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active - List Title Color', 'elegant-elements' ),
				'param_name'  => 'active_title_color',
				'value'       => '#ffffff',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the text color of the active video list title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Active - List Item Background', 'elegant-elements' ),
				'param_name'  => 'active_list_item_background_color',
				'value'       => '#03a9f4',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the background color of the active list item.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Video Container Background Color', 'elegant-elements' ),
				'param_name'  => 'video_container_background_color',
				'value'       => '#fbfbfb',
				'placeholder' => true,
				'description' => esc_attr__( 'Choose the icon color of the video list icon.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border size. In pixels.', 'elegant-elements' ),
				'param_name'  => 'border_size',
				'value'       => '1',
				'min'         => '0',
				'max'         => '50',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => 'rgba(208,233,244,0.1)',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'dependency'  => array(
					'element'            => 'border_size',
					'value_not_equal_to' => '0',
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'std'         => 'solid',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'dependency'  => array(
					'element'            => 'border_size',
					'value_not_equal_to' => '0',
				),
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					'double' => esc_attr__( 'Double', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Position', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the postion of the border.', 'elegant-elements' ),
				'param_name'  => 'border_position',
				'std'         => 'all',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'dependency'  => array(
					'element'            => 'border_size',
					'value_not_equal_to' => '0',
				),
				'value'       => array(
					'all'    => esc_attr__( 'All', 'elegant-elements' ),
					'top'    => esc_attr__( 'Top', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					'bottom' => esc_attr__( 'Bottom', 'elegant-elements' ),
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
				),
			),
			array(
				'type'             => 'ee_dimensions',
				'remove_from_atts' => true,
				'heading'          => esc_attr__( 'Border Radius', 'elegant-elements' ),
				'description'      => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
				'param_name'       => 'border_radius',
				'value'            => array(
					'border_radius_top_left'     => '',
					'border_radius_top_right'    => '',
					'border_radius_bottom_right' => '',
					'border_radius_bottom_left'  => '',
				),
				'group'            => esc_attr__( 'Design', 'elegant-elements' ),
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
				'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
				'param_name'  => 'typography_title',
				'value'       => '',
				'group'       => 'Typography',
				'dependency'  => array(
					'element'            => 'element_typography',
					'value_not_equal_to' => 'default',
				),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => 'Typography',
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Icon Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for icon. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'icon_font_size',
				'value'       => '24',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => 'Typography',
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
				'param_name' => 'video_list_items',
				'group'      => esc_attr__( 'Video Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'title'          => esc_attr__( 'Youtube video', 'elegant-elements' ),
								'video_provider' => 'youtube',
								'video_id'       => 'il2ZAZX9KpQ',
							),
							array(
								'title'          => esc_attr__( 'Wistia Video', 'elegant-elements' ),
								'video_provider' => 'wistia',
								'video_id'       => 'wxz33a3fuc',
							),
							array(
								'title'          => esc_attr__( 'Vimeo Video', 'elegant-elements' ),
								'video_provider' => 'vimeo',
								'video_id'       => '280293143',
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
						'param_name'  => 'title',
						'value'       => esc_attr__( 'Video Item', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter title for this video item to be displayed in the list.', 'elegant-elements' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_attr__( 'Video Provider', 'elegant-elements' ),
						'param_name'  => 'video_provider',
						'value'       => array(
							__( 'YouTube', 'elegant-elements' ) => 'youtube',
							__( 'Vimeo', 'elegant-elements' ) => 'vimeo',
							__( 'Wistia', 'elegant-elements' ) => 'wistia',
							__( 'Self Hosted', 'elegant-elements' ) => 'hosted',
						),
						'description' => esc_attr__( 'Select the video provide you want to use the video from. You can choose from different providers like YouTube, Vimeo and Wistia. If you have a video file, you can choose self hosted option.', 'elegant-elements' ),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Video ID', 'elegant-elements' ),
						'param_name'  => 'video_id',
						'value'       => '',
						'description' => esc_attr__( 'Enter the video id from your provider. You can get the video ID from the url.', 'elegant-elements' ),
						'dependency'  => array(
							'element' => 'video_provider',
							'value'   => array(
								'youtube',
								'vimeo',
								'wistia',
							),
						),
					),
					array(
						'type'        => 'ee_file_upload',
						'heading'     => esc_attr__( 'Video MP4 Upload', 'elegant-elements' ),
						'description' => esc_attr__( 'Add your MP4 video file. This format must be included to render your video with cross-browser compatibility.', 'elegant-elements' ),
						'param_name'  => 'video_file',
						'value'       => '',
						'dependency'  => array(
							'element' => 'video_provider',
							'value'   => array( 'hosted' ),
						),
					),
				),
			),
		),
	);

	elegant_elements_map(
		$args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_video_list', 99 );
