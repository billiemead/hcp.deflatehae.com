<?php
/**
 * Elegant_Mega_Menu_Walker
 *
 * @package Elegant_Mega_Menu
 */
class Elegant_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Menu options.
	 *
	 * @since 1.0
	 * @var array
	 */
	private $menu_options;

	/**
	 * Settings
	 *
	 * @since 1.0
	 * @var array
	 */
	private $settings;

	/**
	 * The constructor
	 *
	 * @since 1.0
	 * @param array $menu_options Menu options.
	 * @return void
	 */
	public function __construct( $menu_options ) {
		$this->menu_options = $menu_options;
		$this->settings     = array();
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<div class='elegant-menu-sub-container'><ul class=\"sub-menu elegant-menu-sub-wrapper\">\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul></div>\n";
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 * @param int    $id     Current item ID..
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$atts         = array();
		$args         = (array) $args;
		$is_mobile    = wp_is_mobile();
		$content      = $this->get_item_content( $item->ID );
		$settings     = $this->get_item_settings( $item->ID );
		$user_display = $settings['enable_for_users'];

		// Get the menu content styles.
		$item_style = $this->get_item_styles( $item->ID );
		apply_filters( 'elegant_mega_menu_content_style', $item_style );

		if ( '1' === $settings['hide_on_mobile'] && $is_mobile ) {
			return;
		}

		if ( '1' === $settings['hide_on_desktop'] && ! $is_mobile ) {
			return;
		}

		if ( 'logged-in' === $user_display && ! is_user_logged_in() ) {
			return;
		}

		if ( 'logged-out' === $user_display && is_user_logged_in() ) {
			return;
		}

		$indent          = $depth ? str_repeat( "\t", $depth ) : '';
		$classes         = array();
		$default_classes = (array) $item->classes;
		$class_hide_sub  = '';

		if ( $this->has_children ) {
			$classes[] = 'menu-item-has-children';

			if ( 0 === $depth ) {
				$atts['aria-haspopup'] = 'true';
				$classes[]             = 'elegant-menu-parent-menu-item';
			}
		}

		if ( $content && $settings['enable_mega_menu'] ) {
			$atts['aria-haspopup'] = 'true';
			if ( isset( $settings['mega_menu_width'] ) ) {
				$mega_menu_width = ( 1 === (int) $settings['mega_menu_width'] ) ? 'full' : 'auto';
				$classes[]       = 'elegant-menu-width-' . $mega_menu_width;
			}
		}

		if ( in_array( 'current-menu-item', $default_classes ) ) {
			$classes[] = 'current-menu-item';
		}

		$classes[] = 'elegant-menu-item';
		$classes[] = join( ' ', $this->get_item_html_classes( $settings ) );

		$html_classes = join( ' ', array_filter( $classes ) );
		$html_classes = ( $content && (int) $settings['enable_mega_menu'] ) ? $html_classes . ' menu-item-has-children elegant-menu-item-has-content' . $class_hide_sub : $html_classes;
		$html_classes = $html_classes ? ' class="' . $html_classes . '"' : '';

		$id = $id ? ' id="elegant-menu-item-' . $item->ID . '"' : '';

		$item_config = array();

		$output .= $indent . '<li' . $id . $html_classes . '>';

		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		if ( 1 === (int) $settings['disable_link'] ) {
			unset( $atts['href'] );
		}

		$attributes = '';

		if ( ! isset( $atts['class'] ) ) {
			$atts['class'] = '';
		}

		foreach ( $atts as $attr => $value ) {
			if ( 'class' === $attr ) {
				if ( ! empty( $value ) ) {
					$value .= ' elegant-menu-nav-link';
				} else {
					$value .= 'elegant-menu-nav-link';
				}
			}
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$mega_menu_width = ( 1 === (int) $settings['mega_menu_width'] ) ? 'full' : 'auto';
		$atts['class']  .= 'elegant-menu-width-' . $mega_menu_width;

		$item_output = isset( $args['before'] ) ? $args['before'] : '';

		if ( (int) $settings['enable_mega_menu'] ) {
			$item_output .= '<div class="elegant-menu-link-wrapper">';
		}

		$item_output .= ( 1 === (int) $settings['disable_link'] ) ? '<span' . $attributes . '>' : '<a' . $attributes;

		if ( 1 !== (int) $settings['disable_link'] ) {
			$item_output .= '>';
		}

		$item_output .= $this->get_font_icon( $settings );

		$item_output .= '<span class="elegant-menu-item-label">';
		$item_output .= $item->title;
		$item_output .= '</span>';

		if ( $this->has_children || (int) $settings['enable_mega_menu'] ) {
			if ( 0 === $depth ) {
				$item_output .= '<i class="elegant-caret caret-down"></i>';
			} else {
				$item_output .= '<i class="elegant-caret caret-left"></i>';
			}
		}

		$item_output .= ( 1 === (int) $settings['disable_link'] ) ? '</span>' : '</a>';
		if ( (int) $settings['enable_mega_menu'] ) {
			$item_output .= '</div>';
		}
		$item_output .= isset( $args['after'] ) ? $args['after'] : '';

		if ( $content && $settings['enable_mega_menu'] ) {
			$custom_menus = array();

			preg_match_all( '~\[vc_wp_custommenu[^\]]+\]~', $content, $matches );

			if ( ! empty( $matches[0] ) ) {
				foreach ( $matches[0] as $match ) {
					$custom_menu_id = intval( filter_var( $match, FILTER_SANITIZE_NUMBER_INT ) );
					$custom_menus[] = $custom_menu_id;
				}
			}

			$depth        = 1;
			$item_output .= '<button class="elegant-menu-dropdown-toggle" aria-pressed="false"><i class="fa fa-angle-down"></i></button>';
			$item_output .= '<div class="elegant-menu-content-container" style="opacity: 0;">';
			$item_output .= '<div class="elegant-menu-content-wrapper">';
			$content      = preg_replace( '/<p>(.*?)<\/p>/', '$1', $content );
			$menu_content = elegant_dynamic_content_filter( $content );
			$item_output .= do_shortcode( $menu_content );
			$item_output .= '</div>';
			$item_output .= '</div>';
		} else {
			if ( $this->has_children ) {
				$item_output .= '<button class="elegant-menu-dropdown-toggle" aria-pressed="false"><i class="fa fa-angle-down"></i></button>';
			}
		}

		$output .= apply_filters( 'elegant_nav_walker_menu_start_el', $item_output, $item, $depth, $args, $settings );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker::end_el()
	 *
	 * @since 1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

	/**
	 * Get item settings
	 *
	 * @param int $item_id Item's ID.
	 * @return array $settings
	 */
	private function get_item_settings( $item_id ) {
		$settings         = (array) get_post_meta( $item_id, '_elegant_menu_item_settings', true );
		$default_settings = elegant_get_walker_fields();

		$settings = array_merge( $default_settings, $settings );

		return $settings;
	}

	/**
	 * Get item settings
	 *
	 * @param int $item_id Item's ID.
	 * @return mixed $content
	 */
	private function get_item_content( $item_id ) {
		$content = get_post_meta( $item_id, '_elegant_menu_item_content', true );

		return trim( $content );
	}

	/**
	 * Get item styles
	 *
	 * @param int $item_id Menu item ID.
	 * @return string Menu item CSS.
	 */
	private function get_item_styles( $item_id ) {
		$style = get_post_meta( $item_id, '_vc_custom_item_css', true );

		return trim( $style );
	}

	/**
	 * Build custom HTML classes
	 *
	 * @param array $settings    Item's settings.
	 * @param array $classes     Default classes.
	 *
	 * @return    array    $classes    Custom HTML classes.
	 */
	private function get_item_html_classes( array $settings, array $classes = array() ) {
		if ( (int) $settings['enable_mega_menu'] ) {
			$classes[] = 'elegant-menu-mega';
		}

		if ( $settings['icon'] ) {
			$classes[] = 'elegant-menu-has-icon';
		}

		if ( $settings['class'] ) {
			$classes[] = esc_attr( $settings['class'] );
		}

		return $classes;
	}

	/**
	 * Parse icon
	 *
	 * @param array $settings    Menu item's settings.
	 *
	 * @return    string    $icon    Parsed icon string.
	 */
	private function get_font_icon( array $settings ) {
		$icon = empty( $settings['icon'] ) ? '' : trim( $settings['icon'] );

		if ( $icon ) {
			$icon = '<span class="elegant-menu-icon"><i class="' . $icon . '"></i></span>';
		}

		return $icon;
	}

	/**
	 * Check if mega menu is enabled
	 *
	 * @param int $item_id Item's ID.
	 * @return bool
	 */
	private function is_mega( $item_id ) {
		$settings = $this->get_item_settings( $item_id );

		return (bool) $settings['enable_mega_menu'];
	}
}
