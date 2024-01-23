<?php
if ( ! class_exists( 'EEWPB_Header_Menu' ) && elegant_is_element_enabled( 'iee_header_menu' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Header_Menu {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Store styles for each mega menu item.
		 *
		 * @access protected
		 * @since 1.0
		 * @var string
		 */
		protected $mega_menu_styles;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			// Initialize the mega menu styles as blank.
			$this->mega_menu_styles = '';

			// Header attributes.
			add_filter( 'eewpb_attr_elegant-header-menu', array( $this, 'attr' ) );

			// Store styles for each mega menu item.
			add_filter( 'elegant_mega_menu_content_style', array( $this, 'mega_menu_styles' ) );

			// Add header menu shortcode.
			add_shortcode( 'iee_header_menu', array( $this, 'render' ) );
		}

		/**
		 * Store mega menu styles.
		 *
		 * @access public
		 * @since 1.0
		 * @param string $css Styles for each mega menu item.
		 * @return void
		 */
		public function mega_menu_styles( $css ) {
			$this->mega_menu_styles .= $css;
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

			// Enqueue script.
			wp_enqueue_script( 'infi-elegant-mega-menu' );

			if ( isset( $args['menu_style'] ) && '' !== $args['menu_style'] ) {
				wp_enqueue_style( 'infi-elegant-menu-style-' . $args['menu_style'] );
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'menu'                        => '',
					'text_transform'              => 'none',
					'menu_style'                  => 'default',
					'in_active_menu_color'        => '#636363',
					'active_menu_color'           => '#0062d3',
					'active_menu_bg_color'        => 'rgba( 7, 122, 255, .20 )',
					'border_radius_top_left'      => '',
					'border_radius_top_right'     => '',
					'border_radius_bottom_right'  => '',
					'border_radius_bottom_left'   => '',
					'alignment'                   => 'left',
					'mobile_alignment'            => 'left',
					'main_menu_font_size'         => '14',
					'main_menu_item_space'        => '15',
					'dropdown_menu_color'         => '#636363',
					'dropdown_menu_bg'            => '#ffffff',
					'dropdown_menu_active_color'  => '#0062d3',
					'dropdown_menu_active_bg'     => '#fbfbfb',
					'dropdown_menu_height'        => '40',
					'menu_item_height'            => '50',
					'dropdown_menu_font_size'     => '13',
					'mobile_toggle_type'          => 'both',
					'mobile_toggle_text'          => esc_attr__( 'Menu', 'elegant-elements' ),
					'mobile_toggle_icon'          => '',
					'mobile_toggle_font_size'     => 16,
					'mobile_toggle_background'    => '#fbfbfb',
					'mobile_toggle_color'         => '#343434',
					'mobile_menu_background'      => '#1f1f1f',
					'mobile_menu_text_color'      => '#bbbbbb',
					'mobile_menu_highlight_color' => '#ffffff',
					'hide_on_mobile'              => elegant_elements_default_visibility( 'string' ),
					'class'                       => '',
					'id'                          => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/header-menu/elegant-header-menu.php' ) ) {
				include locate_template( 'templates/header-menu/elegant-header-menu.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/header-menu/elegant-header-menu.php';
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
				'class' => 'elegant-header-menu elegant-menu-container',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['class'] .= ' elegant-menu-align-' . $this->args['alignment'];
			$attr['class'] .= ' elegant-menu-mobile-align-' . $this->args['mobile_alignment'];
			$attr['class'] .= ' menu--' . $this->args['menu_style'];

			$attr['style'] .= '--text_transform:' . $this->args['text_transform'] . ';';
			$attr['style'] .= '--menu-color: ' . $this->args['in_active_menu_color'] . ';';
			$attr['style'] .= '--active-color: ' . $this->args['active_menu_color'] . ';';
			$attr['style'] .= '--dropdown-menu-color: ' . $this->args['dropdown_menu_color'] . ';';
			$attr['style'] .= '--dropdown-menu-bg: ' . $this->args['dropdown_menu_bg'] . ';';
			$attr['style'] .= '--dropdown-active-color: ' . $this->args['dropdown_menu_active_color'] . ';';
			$attr['style'] .= '--dropdown-active-bg: ' . $this->args['dropdown_menu_active_bg'] . ';';
			$attr['style'] .= '--menu-item-height: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['menu_item_height'], 'px' ) . ';';
			$attr['style'] .= '--dropdown-menu-item-height: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['dropdown_menu_height'], 'px' ) . ';';
			$attr['style'] .= '--menu-font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['main_menu_font_size'], 'px' ) . ';';
			$attr['style'] .= '--menu-item-space: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['main_menu_item_space'], 'px' ) . ';';
			$attr['style'] .= '--dropdown-menu-font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['dropdown_menu_font_size'], 'px' ) . ';';
			$attr['style'] .= '--mobile-menu-bg: ' . $this->args['mobile_menu_background'] . ';';
			$attr['style'] .= '--mobile-menu-color: ' . $this->args['mobile_menu_text_color'] . ';';
			$attr['style'] .= '--mobile-menu-Highlight-color: ' . $this->args['mobile_menu_highlight_color'] . ';';

			if ( 'default' === $this->args['menu_style'] ) {
				$border_radius_top_left     = isset( $this->args['border_radius_top_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius_top_left'], 'px' ) : '0px';
				$border_radius_top_right    = isset( $this->args['border_radius_top_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius_top_right'], 'px' ) : '0px';
				$border_radius_bottom_right = isset( $this->args['border_radius_bottom_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius_bottom_right'], 'px' ) : '0px';
				$border_radius_bottom_left  = isset( $this->args['border_radius_bottom_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius_bottom_left'], 'px' ) : '0px';
				$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
				$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '' : $border_radius;

				if ( '' !== $border_radius ) {
					$attr['style'] .= '--border-radius:' . esc_attr( $border_radius ) . ';';
				}

				$attr['style'] .= '--active-bg-color: ' . $this->args['active_menu_bg_color'] . ';';
			}

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new EEWPB_Header_Menu();
} // End if().

/**
 * Map shortcode for header_menu.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_header_menu() {
	// Whether we are actually on an edit screen.
	$is_editor    = ( ( isset( $_POST['action'] ) && 'vc_edit_form' === $_POST['action'] ) || ( isset( $_GET['vc_action'] ) && 'vc_inline' === $_GET['vc_action'] ) ) ? true : false; // @codingStandardsIgnoreLine
	$menu_options = array();

	// If we are on edit screen, fetch menu options.
	if ( $is_editor ) {
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$menu_options[ $menu->slug ] = $menu->name;
		}
	}

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Header Menu', 'elegant-elements' ),
			'shortcode' => 'iee_header_menu',
			'icon'      => 'fas fa-bars header-menu-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Menu', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the menu.', 'elegant-elements' ),
					'param_name'  => 'menu',
					'value'       => $menu_options,
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Menu Text Style', 'elegant-elements' ),
					'param_name'  => 'text_transform',
					'std'         => 'none',
					'value'       => array(
						'none'       => esc_attr__( 'Default', 'elegant-elements' ),
						'uppercase'  => esc_attr__( 'Uppercase', 'elegant-elements' ),
						'capitalize' => esc_attr__( 'Capitalize', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Set the text style for the menu and sub-menu items.', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Menu Style', 'elegant-elements' ),
					'param_name'  => 'menu_style',
					'std'         => 'default',
					'value'       => array(
						'default'   => esc_attr__( 'Default', 'elegant-elements' ),
						'alonso'    => esc_attr__( 'Alonso', 'elegant-elements' ),
						'antonio'   => esc_attr__( 'Antonio', 'elegant-elements' ),
						'ariel'     => esc_attr__( 'Ariel', 'elegant-elements' ),
						'caliban'   => esc_attr__( 'Caliban', 'elegant-elements' ),
						'juno'      => esc_attr__( 'Juno', 'elegant-elements' ),
						'invulner'  => esc_attr__( 'Invulner', 'elegant-elements' ),
						'miranda'   => esc_attr__( 'Miranda', 'elegant-elements' ),
						'prospero'  => esc_attr__( 'Prospero', 'elegant-elements' ),
						'sebastian' => esc_attr__( 'Sebastian', 'elegant-elements' ),
						'viola'     => esc_attr__( 'Viola', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Choose the menu style. This will be applied for the active and menu hover.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'In-active Menu Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the in-active menu item.', 'elegant-elements' ),
					'param_name'  => 'in_active_menu_color',
					'value'       => '#636363',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Menu Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text and border color for the active menu item.', 'elegant-elements' ),
					'param_name'  => 'active_menu_color',
					'value'       => '#0062d3',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Menu Item Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for the active menu item and hover menu item.', 'elegant-elements' ),
					'param_name'  => 'active_menu_bg_color',
					'value'       => 'rgba( 7, 122, 255, .20 )',
					'dependency'  => array(
						'element' => 'menu_style',
						'value'   => array( 'default' ),
					),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Active Menu Item Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter values including any valid CSS unit, ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'border_radius',
					'value'       => array(
						'border_radius_top_left'     => '',
						'border_radius_top_right'    => '',
						'border_radius_bottom_right' => '',
						'border_radius_bottom_left'  => '',
					),
					'dependency'  => array(
						'element' => 'menu_style',
						'value'   => array( 'default' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Menu Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'      => esc_attr__( 'Left', 'elegant-elements' ),
						'center'    => esc_attr__( 'Center', 'elegant-elements' ),
						'right'     => esc_attr__( 'Right', 'elegant-elements' ),
						'justified' => esc_attr__( 'Justified', 'elegant-elements' ),
					),
					'icons'       => elegant_get_four_alignment_icons(),
					'description' => esc_attr__( 'Align the menu to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Mobile Toggle Menu Button Alignment', 'elegant-elements' ),
					'param_name'  => 'mobile_alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the menu on mobile to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Main Menu Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font-size for the main menu item text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'main_menu_font_size',
					'value'       => '14',
					'min'         => '10',
					'max'         => '50',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Main Menu Item Space', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the space on left and right side of main menu items. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'main_menu_item_space',
					'value'       => '15',
					'min'         => '1',
					'max'         => '50',
					'step'        => '1',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Dropdown Menu Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the dropdown menu item.', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_color',
					'value'       => '#636363',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Dropdown Menu Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for the dropdown menu item.', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_bg',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Dropdown Menu Hover and Active Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the dropdown menu item for the hover and active state.', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_active_color',
					'value'       => '#0062d3',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Dropdown Menu Hover and Active Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for the dropdown menu item for the hover and active state.', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_active_bg',
					'value'       => '#fbfbfb',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Dropdown Menu Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font-size for the dropdown menu item text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_font_size',
					'value'       => '13',
					'min'         => '10',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Dropdown Menu Item Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the dropdown menu item. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'dropdown_menu_height',
					'value'       => '40',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Dropdown Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Menu Item Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the main menu item. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'menu_item_height',
					'value'       => '50',
					'min'         => '1',
					'max'         => '200',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Mobile Menu Toggle Type', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_type',
					'std'         => 'both',
					'value'       => array(
						'text' => esc_attr__( 'Text Only', 'elegant-elements' ),
						'icon' => esc_attr__( 'Icon Only', 'elegant-elements' ),
						'both' => esc_attr__( 'Icon and Text', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Set the mobile menu toggle type.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Mobile Toggle Text', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_text',
					'value'       => esc_attr__( 'Menu', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the text for the mobile menu toggle button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'mobile_toggle_type',
						'value_not_equal_to' => 'icon',
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Mobile Toggle Icon', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_icon',
					'value'       => '',
					'description' => esc_attr__( 'Choose icon for the mobile menu toggle button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'mobile_toggle_type',
						'value_not_equal_to' => 'text',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Menu Toggle Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font-size for the menu toggle button. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_font_size',
					'value'       => '16',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Mobile Toggle Button Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for the mobile toggle button.', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_background',
					'value'       => '#fbfbfb',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Mobile Toggle Button Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the mobile toggle button.', 'elegant-elements' ),
					'param_name'  => 'mobile_toggle_color',
					'value'       => '#343434',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Mobile Menu Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for the mobile menu container.', 'elegant-elements' ),
					'param_name'  => 'mobile_menu_background',
					'value'       => '#1f1f1f',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Mobile Menu Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the mobile menu item and mobile sub-menu item.', 'elegant-elements' ),
					'param_name'  => 'mobile_menu_text_color',
					'value'       => '#bbbbbb',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Mobile Menu Highlight Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the mobile menu item for hover and active state.', 'elegant-elements' ),
					'param_name'  => 'mobile_menu_highlight_color',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Mobile Menu', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_header_menu', 99 );
