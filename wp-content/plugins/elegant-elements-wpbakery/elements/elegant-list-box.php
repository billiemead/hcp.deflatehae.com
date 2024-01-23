<?php
if ( ! class_exists( 'EEWPB_List_Box' ) && elegant_is_element_enabled( 'iee_list_box' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_List_Box {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * List box counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $list_box_counter = 1;

		/**
		 * The CSS class of circle elements.
		 *
		 * @access private
		 * @since 1.0
		 * @var string
		 */
		private $circle_class = 'circle-no';

		/**
		 * Parent SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $parent_args;

		/**
		 * Child SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_list-box-shortcode', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-title', array( $this, 'title_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-title-span', array( $this, 'title_span_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-items', array( $this, 'items_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-li-item', array( $this, 'li_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-span', array( $this, 'span_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-icon', array( $this, 'icon_attr' ) );
			add_filter( 'eewpb_attr_list-box-shortcode-item-content', array( $this, 'item_content_attr' ) );

			add_shortcode( 'iee_list_box', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_list_box_item', array( $this, 'render_child' ) );
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

			$list_box_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'item_content' => esc_attr__( 'Designers', 'elegant-elements' ),
						),
						array(
							'item_content' => esc_attr__( 'Developers', 'elegant-elements' ),
						),
						array(
							'item_content' => esc_attr__( 'Agencies', 'elegant-elements' ),
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'circle'         => 'yes',
					'circlecolor'    => '#2283d8',
					'icon'           => 'fas fa-chevron-right',
					'iconcolor'      => '#ffffff',
					'size'           => '13px',
					'title'          => esc_attr__( 'List Box Title', 'elegant-elements' ),
					'title_align'    => 'center',
					'title_color'    => '#333333',
					'border_color'   => '#dddddd',
					'border_style'   => 'solid',
					'border_size'    => '1',
					'border_radius'  => 'square',
					'item_align'     => 'center',
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
					'list_box_items' => $list_box_items,
				),
				$args
			);

			// Dertmine line-height and margin from font size.
			$font_size                           = str_replace( 'px', '', $defaults['size'] );
			$defaults['circle_yes_font_size']    = $font_size * 0.88;
			$defaults['line_height']             = $font_size * 1.7;
			$defaults['icon_margin']             = $font_size * 0.7;
			$defaults['icon_margin_position']    = ( is_rtl() ) ? 'left' : 'right';
			$defaults['content_margin']          = $defaults['line_height'] + $defaults['icon_margin'];
			$defaults['content_margin_position'] = ( is_rtl() ) ? 'right' : 'left';

			$this->parent_args = $defaults;

			// Parse list item params.
			$list_box_items = vc_param_group_parse_atts( $this->parent_args['list_box_items'] );

			// Loop through the list items and generate a shortcode.
			$content = '';
			foreach ( $list_box_items as $item ) {
				$content .= '[iee_list_box_item';
				foreach ( $item as $title => $value ) {
					if ( 'item_content' !== $title ) {
						$content .= ' ' . $title . '="' . $value . '"';
					}
				}
				$content .= ']' . $item['item_content'] . '[/iee_list_box_item]';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/list-box/elegant-list-box.php' ) ) {
				include locate_template( 'templates/list-box/elegant-list-box.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/list-box/elegant-list-box.php';
			}

			$this->list_box_counter++;

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
					'icon' => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/list-box/elegant-list-box-item.php' ) ) {
				include locate_template( 'templates/list-box/elegant-list-box-item.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/list-box/elegant-list-box-item.php';
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

			$attr = array();

			$attr['class']  = 'elegant-list-box elegant-checklist elegant-list-box-' . $this->list_box_counter;
			$attr['class'] .= ' elegant-align-' . $this->parent_args['item_align'];

			$attr          = elegant_elements_visibility_atts( $this->parent_args['hide_on_mobile'], $attr );
			$font_size     = str_replace( 'px', '', $this->parent_args['size'] );
			$line_height   = $font_size * 1.7;
			$attr['style'] = 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->parent_args['size'], 'px' ) . ';line-height:' . $line_height . 'px;';

			if ( $this->parent_args['class'] ) {
				$attr['class'] .= ' ' . $this->parent_args['class'];
			}

			if ( $this->parent_args['id'] ) {
				$attr['id'] = $this->parent_args['id'];
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
		public function title_attr() {
			$attr = array();

			$attr['class']  = 'elegant-list-box-title';
			$attr['class'] .= ' elegant-align-' . $this->parent_args['title_align'];

			$attr['style'] = 'color:' . $this->parent_args['title_color'] . ';';

			$font_size      = str_replace( 'px', '', $this->parent_args['size'] );
			$line_height    = $font_size * 1.2;
			$margin_bottom  = $font_size * 1.7;
			$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->parent_args['size'], 'px' ) . ';line-height:' . $line_height . 'px;margin-bottom:-' . $margin_bottom . 'px;';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function items_attr() {
			$attr = array();

			$attr['class'] = 'elegant-list-box-items';

			$attr['style']  = 'border-color:' . $this->parent_args['border_color'] . ';';
			$attr['style'] .= 'border-style:' . $this->parent_args['border_style'] . ';';
			$attr['style'] .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->parent_args['border_size'], 'px' ) . ';';

			$font_size      = str_replace( 'px', '', $this->parent_args['size'] );
			$line_height    = $font_size * 2;
			$attr['style'] .= 'padding-top:' . $line_height . 'px;';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function title_span_attr() {
			$attr = array(
				'class' => 'elegant-list-box-border-' . $this->parent_args['border_radius'],
			);

			$attr['style']  = 'border-color:' . $this->parent_args['border_color'] . ';';
			$attr['style'] .= 'border-style:' . $this->parent_args['border_style'] . ';';
			$attr['style'] .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->parent_args['border_size'], 'px' ) . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function li_attr() {

			$attr = array();

			$attr['class'] = 'elegant-list-item elegant-li-item';

			return $attr;

		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function item_content_attr() {
			return array(
				'class' => 'elegant-list-item-content elegant-li-item-content',
				'style' => 'margin-' . $this->parent_args['content_margin_position'] . ':' . $this->parent_args['content_margin'] . 'px;',
			);
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function span_attr() {

			$attr = array(
				'style' => '',
			);

			if ( 'yes' === $this->child_args['circle'] || 'yes' === $this->parent_args['circle'] && ( 'no' !== $this->child_args['circle'] ) ) {
				$this->circle_class = 'circle-yes';

				if ( ! $this->child_args['circlecolor'] ) {
					$circlecolor = $this->parent_args['circlecolor'];
				} else {
					$circlecolor = $this->child_args['circlecolor'];
				}
				$attr['style'] = 'background-color:' . $circlecolor . ';';

				$attr['style'] .= 'font-size:' . $this->parent_args['circle_yes_font_size'] . 'px;';
			}

			$attr['class'] = 'icon-wrapper ' . $this->circle_class;

			$attr['style'] .= 'height:' . $this->parent_args['line_height'] . 'px;';
			$attr['style'] .= 'width:' . $this->parent_args['line_height'] . 'px;';
			$attr['style'] .= 'margin-' . $this->parent_args['icon_margin_position'] . ':' . $this->parent_args['icon_margin'] . 'px;';

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

			if ( ! $this->child_args['icon'] ) {
				$icon = $this->parent_args['icon'];
			} else {
				$icon = $this->child_args['icon'];
			}

			$icon_class = $icon;

			if ( ! $this->child_args['iconcolor'] ) {
				$iconcolor = $this->parent_args['iconcolor'];
			} else {
				$iconcolor = $this->child_args['iconcolor'];
			}

			return array(
				'class' => 'elegant-list-item-icon elegant-li-icon ' . $icon_class,
				'style' => 'color:' . $iconcolor . ';',
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
			wp_enqueue_style( 'infi-elegant-list-box' );
		}
	}

	new EEWPB_List_Box();
} // End if().

/**
 * Map shortcode for list_box.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_list_box() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant List Box', 'elegant-elements' ),
		'shortcode' => 'iee_list_box',
		'icon'      => 'fas fa-list-alt',
		'params'    => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'List Box Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title for this list box.', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'List Box Title', 'elegant-elements' ),
				'placeholder' => true,
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Title Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Select list title alighment.', 'elegant-elements' ),
				'param_name'  => 'title_align',
				'std'         => 'center',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the list box title text color.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
				'default'     => '#333333',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border size of the column. In pixels.', 'elegant-elements' ),
				'param_name'  => 'border_size',
				'value'       => '1',
				'min'         => '1',
				'max'         => '50',
				'step'        => '1',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => '#dddddd',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'std'         => 'solid',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Title Border Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how do you want the title border display.', 'elegant-elements' ),
				'param_name'  => 'border_radius',
				'std'         => 'square',
				'value'       => array(
					'square' => esc_attr__( 'Square', 'elegant-elements' ),
					'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					'pill'   => esc_attr__( 'Pill', 'elegant-elements' ),
				),
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'iconpicker',
				'heading'     => esc_attr__( 'Select Icon', 'elegant-elements' ),
				'param_name'  => 'icon',
				'value'       => 'fas fa-chevron-right',
				'description' => esc_attr__( 'Global setting for all list items, this can be overridden individually. Click an icon to select, click again to deselect.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Checklist Icon Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items.  Controls the color of the checklist icon.', 'elegant-elements' ),
				'param_name'  => 'iconcolor',
				'value'       => '',
				'default'     => '#ffffff',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Checklist Circle', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items. Turn on if you want to display a circle background for checklists.', 'elegant-elements' ),
				'param_name'  => 'circle',
				'std'         => 'yes',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Checklist Circle Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Global setting for all list items.  Controls the color of the checklist circle background.', 'elegant-elements' ),
				'param_name'  => 'circlecolor',
				'value'       => '',
				'default'     => '#2283d8',
				'dependency'  => array(
					'element'            => 'circle',
					'value_not_equal_to' => 'no',
				),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Item Font Size', 'elegant-elements' ),
				'description' => esc_attr__( "Select the list item's font size. In pixels (px), ex: 13px.", 'elegant-elements' ),
				'param_name'  => 'size',
				'value'       => '13',
				'min'         => '10',
				'max'         => '50',
				'step'        => '1',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'List Item Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Select list item alighment.', 'elegant-elements' ),
				'param_name'  => 'item_align',
				'default'     => 'center',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'icons'       => elegant_get_alignment_icons(),
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
				'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				'param_name'  => 'class',
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
				'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				'param_name'  => 'id',
				'value'       => '',
			),
			array(
				'type'       => 'param_group',
				'param_name' => 'list_box_items',
				'group'      => esc_attr__( 'List Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'item_content' => esc_attr__( 'Designers', 'elegant-elements' ),
							),
							array(
								'item_content' => esc_attr__( 'Developers', 'elegant-elements' ),
							),
							array(
								'item_content' => esc_attr__( 'Agencies', 'elegant-elements' ),
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'iconpicker',
						'heading'     => esc_attr__( 'Select Icon', 'elegant-elements' ),
						'param_name'  => 'icon',
						'value'       => '',
						'description' => esc_attr__( 'This setting will override the global setting. ', 'elegant-elements' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_attr__( 'List Item Content', 'elegant-elements' ),
						'description' => esc_attr__( 'Add list item content.', 'elegant-elements' ),
						'param_name'  => 'item_content',
						'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
						'placeholder' => true,
					),
				),
			),
		),
	);

	elegant_elements_map(
		$parent_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_list_box', 99 );
