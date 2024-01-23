<?php
/**
 * Plugin Name: Elegant Elements for WPBakery Page Builder
 * Plugin URI: https://elegantwpb.com/
 * Description: Elegant Elements add-on for WPBakery Page Builder
 * Version: 1.7.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author: InfiWebs
 * Author URI: https://www.infiwebs.com
 * Text Domain: elegant-elements
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version.
if ( ! defined( 'EEWPB_VERSION' ) ) {
	define( 'EEWPB_VERSION', '1.7.0' );
}

// Plugin Root File.
if ( ! defined( 'EEWPB_PLUGIN_FILE' ) ) {
	define( 'EEWPB_PLUGIN_FILE', __FILE__ );
}

// Plugin Folder Path.
if ( ! defined( 'EEWPB_PLUGIN_DIR' ) ) {
	define( 'EEWPB_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( EEWPB_PLUGIN_FILE ) ) );
}

// Plugin Folder URL.
if ( ! defined( 'EEWPB_PLUGIN_URL' ) ) {
	define( 'EEWPB_PLUGIN_URL', plugin_dir_url( EEWPB_PLUGIN_FILE ) );
}

global $eewpb_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

// JS folder URL.
$eewpb_js_folder_url = EEWPB_PLUGIN_URL . 'assets/js/min';

// JS folder path.
$elegant_js_folder_path = EEWPB_PLUGIN_DIR . 'assets/js/min';

// CSS folder URL.
$elegant_css_folder_url = EEWPB_PLUGIN_URL . 'assets/css/min';

// CSS folder path.
$elegant_css_folder_path = EEWPB_PLUGIN_DIR . 'assets/css/min';

if ( ! class_exists( 'Elegant_Elements_WPBakery' ) ) {

	/**
	 * Main Elegant_Elements_WPBakery Class.
	 *
	 * @since 1.0
	 */
	class Elegant_Elements_WPBakery {

		/**
		 * The one, true instance of this object.
		 *
		 * @since 1.0
		 * @static
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Pre-built Templates.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 * @var array
		 */
		public static $templates = array();

		/**
		 * Code Patcher.
		 *
		 * @access public
		 * @since 1.4.0
		 * @var object
		 */
		public $patcher;

		/**
		 * EEWPB_Registration
		 *
		 * @since 1.4.0
		 * @static
		 * @access public
		 * @var object EEWPB_Registration.
		 */
		public $registration;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @since 1.0
		 * @static
		 * @access public
		 */
		public static function get_instance() {

			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null === self::$instance ) {
				self::$instance = new Elegant_Elements_WPBakery();
			}
			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting localization, hooks, filters,
		 * and administrative functions.
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {

			// Init patcher.
			$this->patcher = new Elegant_Elements_Wpbakery_Patcher();

			// Init product registration.
			if ( ( is_admin() && class_exists( 'EEWPB_Product_Registration' ) ) ) {
				$this->registration = new EEWPB_Product_Registration(
					array(
						'type' => 'plugin',
						'name' => 'Elegant Elements for WPBakery Page Builder',
					)
				);
			}

			// Add admin notice if WPBakery Page Builder is deactivated or not installed.
			add_action( 'admin_notices', array( $this, 'wpbakery_required_admin_notice' ) );

			// Load plugin textdomain.
			add_action( 'plugins_loaded', array( $this, 'textdomain' ) );

			// Enqueue scripts on frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 8 );

			// Enqueue styles on frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ), 9 );
			add_action( 'elegant_elements_render_template_style', array( $this, 'elegant_elements_render_template_style' ) );

			// Enqueue scripts on backend.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Add pre-built templates.
			add_filter( 'elegant_elements_wpbakery_templates', array( $this, 'elegant_elements_wpbakery_templates' ) );

			// Place Elegant Elements category on 5th tab.
			add_filter( 'vc_add_element_categories', array( $this, 'update_category_position' ), 11 );

			add_action( 'admin_footer', array( $this, 'add_frontend_editor_helpers' ) );
		}

		/**
		 * Outputs wrapper for the element generator in backend and frontend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function add_frontend_editor_helpers() {
			echo '<div class="elegant-element-shortcode-generator"></div>';
		}

		/**
		 * Function to apply attributes to HTML tags.
		 * Devs can override attributes in a child theme by using the correct slug.
		 *
		 * @since 1.0
		 * @access public
		 * @param  string $slug    Slug to refer to the HTML tag.
		 * @param  array  $attributes Attributes for HTML tag.
		 * @return string The string of all attributes.
		 */
		public static function attributes( $slug, $attributes = array() ) {

			$out  = '';
			$attr = apply_filters( "eewpb_attr_{$slug}", $attributes );

			if ( empty( $attr ) ) {
				$attr['class'] = $slug;
			}

			foreach ( $attr as $name => $value ) {
				if ( 'valueless_attribute' === $value ) {
					$out .= ' ' . esc_html( $name );
				} elseif ( ! empty( $value ) || strlen( $value ) > 0 || is_bool( $value ) ) {
					$value = str_replace( '  ', ' ', $value );
					$out  .= ' ' . esc_html( $name ) . '="' . esc_attr( $value ) . '"';
				}
			}

			return trim( $out );
		}

		/**
		 * Validate shortcode attribute value.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 * @param string $value         The value.
		 * @param string $accepted_unit The accepted unit.
		 * @param string $bc_support    Return value even if invalid.
		 * @return value
		 */
		public static function validate_shortcode_attr_value( $value, $accepted_unit, $bc_support = true ) {

			if ( '' !== $value ) {
				$value           = trim( $value );
				$unit            = preg_replace( '/[\d-]+/', '', $value );
				$numerical_value = preg_replace( '/[a-z,%]/', '', $value );

				if ( empty( $accepted_unit ) ) {
					return $numerical_value;
				}

				// Add unit if it's required.
				if ( empty( $unit ) ) {
					return $numerical_value . $accepted_unit;
				}

				// If unit was found use original value. BC support.
				if ( $bc_support || $unit === $accepted_unit ) {
					return $value;
				}

				return false;
			}

			return '';
		}

		/**
		 * Function to get the default shortcode param values applied.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 * @param  array  $defaults  Array of defaults.
		 * @param  array  $args      Array with user set param values.
		 * @param  string $shortcode Shortcode name.
		 * @return array
		 */
		public static function set_shortcode_defaults( $defaults, $args, $shortcode = false ) {

			if ( ! $args ) {
				$args = array();
			}

			$args = apply_filters( 'eewpb_pre_shortcode_atts', $args, $defaults, $args, $shortcode );
			$args = shortcode_atts( $defaults, $args, $shortcode );

			foreach ( $args as $key => $value ) {
				if ( ( '' === $value || '|' === $value ) && isset( $defaults[ $key ] ) ) {
					$args[ $key ] = $defaults[ $key ];
				}
			}

			return $args;
		}

		/**
		 * Pre-built templates.
		 *
		 * @since 1.0
		 * @param array $template Pre-built template.
		 * @return array $templates
		 */
		public function elegant_elements_wpbakery_templates( $template ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$templates_json = EEWPB_PLUGIN_DIR . 'inc/templates/templates.json';
			$template_json  = $wp_filesystem->get_contents( $templates_json );

			$template_array = json_decode( $template_json, true );

			self::$templates = $template_array;

			return self::$templates;
		}

		/**
		 * Sets the tabs category to 5th number.
		 *
		 * @since 1.0
		 * @param array $tabs The element navigation tabs in WPBakery Page Builder elements popup.
		 * @return array $tabs
		 */
		public function update_category_position( $tabs ) {
			$tab_key = '';

			foreach ( $tabs as $key => $tab ) {
				if ( 'Elegant Elements' === $tab['name'] ) {
					$tab_key = $key;
					break;
				}
			}

			if ( ! isset( $tabs[ $tab_key ] ) ) {
				return $tabs;
			}

			$infi_tab         = $tabs[ $tab_key ];
			$tabs[ $tab_key ] = $tabs[3];
			$tabs[3]          = $infi_tab;

			// Get existing favourite lements.
			$favourite_elements = get_option( 'eewpb_favourite_elements', array() );

			// If there are favourite elements, make sure the tab is added as first.
			if ( ! empty( $favourite_elements ) ) {
				$fav_tab_key = '';

				foreach ( $tabs as $key => $tab ) {
					if ( 'Favourite' === $tab['name'] ) {
						$fav_tab_key = $key;
						break;
					}
				}

				$favourite_tab        = $tabs[ $fav_tab_key ];
				$tabs[ $fav_tab_key ] = $tabs[1];
				$tabs[1]              = $favourite_tab;
			}

			return $tabs;
		}

		/**
		 * Displays admin notice if WPBakery Page Builder is not active.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function wpbakery_required_admin_notice() {
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				echo '<div class="notice notice-warning is-dismissible">
	             <p>' . esc_attr__( 'Elegant Elements is installed and activated correctly. However, it will not take any effect until you install and activate WPBakery Page Builder.', 'elegant-elements' ) . '</p>
	         </div>';
			}
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function textdomain() {

			// Set text domain.
			$domain = 'elegant-elements';

			// Load the plugin textdomain.
			load_plugin_textdomain( $domain, false, dirname( plugin_basename( EEWPB_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Enqueue elegant elements styles on frontend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function frontend_styles() {
			global $post, $elegant_settings;

			// Register menu styles and scripts.
			$this->register_menu_scripts();

			$custom_fonts = array();

			if ( $elegant_settings ) {
				$custom_fonts = $elegant_settings->get( 'custom_fonts' );
				$custom_fonts = isset( $custom_fonts['name'] ) ? $custom_fonts['name'] : array();
			}

			// Register styles.
			wp_register_style( 'infi-elegant-elements', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-elements.min.css', '', EEWPB_VERSION );
			wp_register_style( 'infi-elegant-animations', EEWPB_PLUGIN_URL . 'assets/css/min/infi-css-animations.min.css', '', EEWPB_VERSION );
			wp_register_style( 'infi-elegant-combined-css', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-elements-combined.min.css', '', EEWPB_VERSION );

			// Register styles for each element.
			$elements = array(
				'cards',
				'carousel',
				'testimonials',
				'rotating_text',
				'typewriter_text',
				'partner_logo',
				'promo_box',
				'fancy_banner',
				'special_heading',
				'dual_button',
				'modal_dialog',
				'fancy_button',
				'notification_box',
				'profile_panel',
				'gradient_heading',
				'image_filters',
				'expanding_sections',
				'image_compare',
				'list_box',
				'instagram_gallery',
				'skew_heading',
				'image_hotspot',
				'image_mask_heading',
				'icon_block',
				'dual_style_heading',
				'image_separator',
				'content_toggle',
				'faq_rich_snippets',
				'video_list',
				'advanced_video',
				'business_hours',
				'cube_box',
				'whatsapp_chat_button',
				'ribbon',
				'distortion_hover_image',
				'instagram_teaser_box',
				'instagram_profile_card',
				'shape_divider',
				'forms',
				'content_box',
				'social_icons',
				'search_form',
				'footer_links',
				'countdown_timer',
				'video_lightbox',
				'syntax_highlighter',
				'image_stack',
				'team_member',
				'star_rating',
				'text_path',
				'image_overlay_button',
				'lottie_content_box',
			);

			// Register styles.
			foreach ( $elements  as $element ) {
				$element_handle = str_replace( '_', '-', $element );

				wp_register_style( 'infi-elegant-' . $element_handle, EEWPB_PLUGIN_URL . 'assets/css/min/infi-elegant-' . $element_handle . '.min.css', '', EEWPB_VERSION );
			}

			if ( ! is_404() && ! is_search() && $post ) {

				// Enqueue styles on frontend.
				wp_enqueue_style( 'infi-elegant-elements' );
				wp_enqueue_style( 'infi-elegant-animations' );

				// Enqueue FontAwesome icons.
				wp_enqueue_style( 'vc_font_awesome_5' );

				if ( eewpb_is_combined_enqueue() ) {
					// Enqueue combined css.
					wp_enqueue_style( 'infi-elegant-combined-css' );
				}

				$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine

				if ( $is_vc_inline ) {
					// Enqueue combined css.
					wp_enqueue_style( 'infi-elegant-combined-css' );

					// Enqueue styles for frontend editor.
					wp_enqueue_style( 'infi-elegant-elements-frontend', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-frontend-editor.min.css', '', EEWPB_VERSION );
				}
			}
		}

		/**
		 * Register the scripts and styles for the menu styles.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function register_menu_scripts() {
			$menu_styles = array(
				'alonso',
				'antonio',
				'ariel',
				'caliban',
				'juno',
				'invulner',
				'miranda',
				'prospero',
				'sebastian',
				'viola',
			);

			foreach ( $menu_styles as $menu_style ) {
				// Register menu styles.
				wp_register_style( 'infi-elegant-menu-style-' . $menu_style, EEWPB_PLUGIN_URL . 'assets/css/min/menu/' . $menu_style . '.min.css', '', EEWPB_VERSION );
			}
		}

		/**
		 * Enqueue elegant elements styles on frontend for header and footer builder.
		 *
		 * @since 1.0
		 * @access public
		 * @param object $template_post The template post.
		 * @return void
		 */
		public function elegant_elements_render_template_style( $template_post ) {
			$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine

			if ( $is_vc_inline ) {
				// Enqueue combined css.
				wp_enqueue_style( 'infi-elegant-combined-css' );

				// Enqueue styles for frontend editor.
				wp_enqueue_style( 'infi-elegant-elements-frontend', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-frontend-editor.min.css', '', EEWPB_VERSION );

				// Add fix for the full width container.
				wp_enqueue_script( 'elegant-elements-frontend-fixes', EEWPB_PLUGIN_URL . 'app/js/elegant-wpbakery-frontend-fixes.js', array( 'jquery' ), EEWPB_VERSION, true );
			}

			if ( $template_post ) {
				$shortcodes_custom_css = get_metadata( 'post', $template_post->ID, '_wpb_shortcodes_custom_css', true );
				$shortcodes_custom_css = apply_filters( 'vc_shortcodes_custom_css', $shortcodes_custom_css, $template_post->ID );
				$post_custom_css       = get_metadata( 'post', $template_post->ID, '_wpb_post_custom_css', true );
				$post_custom_css       = apply_filters( 'vc_post_custom_css', $post_custom_css, $template_post->ID );

				// Check if page title bar is used.
				$page_title_bar = get_field( 'elegant_page_title_bar', $template_post->ID );
				if ( $page_title_bar ) {
					$shortcodes_custom_css .= get_metadata( 'post', $page_title_bar->ID, '_wpb_shortcodes_custom_css', true );
					$shortcodes_custom_css .= apply_filters( 'vc_shortcodes_custom_css', $shortcodes_custom_css, $page_title_bar->ID );
					$post_custom_css       .= get_metadata( 'post', $page_title_bar->ID, '_wpb_post_custom_css', true );
					$post_custom_css       .= apply_filters( 'vc_post_custom_css', $post_custom_css, $page_title_bar->ID );
				}

				if ( '' !== $shortcodes_custom_css || '' !== $post_custom_css ) {
					$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
					echo '<style type="text/css" data-type="vc_shortcodes-custom-template-css">';
					echo wp_kses_post( $shortcodes_custom_css );
					echo wp_kses_post( $post_custom_css );
					echo '</style>';
				}
			}
		}

		/**
		 * Enqueue elegant elements scripts on frontend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function frontend_scripts() {
			global $eewpb_js_folder_url;

			// Register scripts for frontend.
			wp_register_script( 'infi-lottie-player', str_replace( '/min', '', $eewpb_js_folder_url ) . '/lottie-player.min.js', array(), EEWPB_VERSION, true );
			wp_register_script( 'infi-packery', str_replace( '/min', '/lib', $eewpb_js_folder_url ) . '/packery-mode.pkgd.min.js', array(), EEWPB_VERSION, true );
			wp_register_script( 'infi-imagesloaded', str_replace( '/min', '/lib', $eewpb_js_folder_url ) . '/imagesloaded.pkgd.min.js', array(), EEWPB_VERSION, true );
			wp_register_script( 'infi-distortion-three', str_replace( '/min', '/lib', $eewpb_js_folder_url ) . '/three.min.js', array(), EEWPB_VERSION, true );
			wp_register_script( 'infi-distortion-tweenmax', str_replace( '/min', '/lib', $eewpb_js_folder_url ) . '/TweenMax.min.js', array(), EEWPB_VERSION, true );
			wp_register_script( 'infi-distortion-hover', $eewpb_js_folder_url . '/infi-elegant-hover-effect.min.js', array( 'infi-imagesloaded', 'infi-distortion-three', 'infi-distortion-tweenmax' ), EEWPB_VERSION, true );
			wp_register_script( 'bootstrap-modal', str_replace( '/min', '', $eewpb_js_folder_url ) . '/bootstrap.modal.min.js', array( 'jquery' ), EEWPB_VERSION, true );
			wp_register_script( 'infi-elegant-background-slider', $eewpb_js_folder_url . '/infi-elegant-background-slider.min.js', array( 'jquery' ), EEWPB_VERSION, true );
			wp_register_script( 'infi-elegant-shape-divider', $eewpb_js_folder_url . '/infi-elegant-shape-divider.min.js', array( 'jquery' ), EEWPB_VERSION, true );
			wp_register_script( 'infi-elegant-mega-menu', $eewpb_js_folder_url . '/elegant-menu.min.js', array( 'jquery' ), EEWPB_VERSION, true );

			// JS with common fixes.
			wp_register_script( 'infi-elegant-fixes', $eewpb_js_folder_url . '/elegant-fixes.min.js', array( 'jquery' ), EEWPB_VERSION, true );
			wp_enqueue_script( 'infi-elegant-fixes' );
		}

		/**
		 * Enqueue required js on backend.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function admin_scripts() {
			global $pagenow, $typenow;

			// Enqueue scripts and styles on backend.
			if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && post_type_supports( $typenow, 'editor' ) ) {
				wp_enqueue_style( 'infi-admin', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-elements-admin.min.css', '', EEWPB_VERSION );

				// Context menu.
				wp_enqueue_script( 'elegant-context-menu', EEWPB_PLUGIN_URL . 'app/js/elegant-context-menu.js', '', EEWPB_VERSION, true );

				// Dynamic element.
				wp_enqueue_script( 'elegant-dynamic-element', EEWPB_PLUGIN_URL . 'app/js/elegant-dynamic-element.js', '', EEWPB_VERSION, true );

				// Localize the dynamic elements script to load available fields.
				wp_localize_script(
					'elegant-dynamic-element',
					'elegantDynamicFields',
					$this->get_available_dynamic_fields()
				);

				// Favourite element.
				wp_enqueue_script( 'elegant-favourite-element', EEWPB_PLUGIN_URL . 'app/js/elegant-favourite-element.js', '', EEWPB_VERSION, true );

				// Get existing favourite lements.
				$favourite_elements = get_option( 'eewpb_favourite_elements', array() );

				// Localize the favourite elements to access in script.
				wp_localize_script(
					'elegant-favourite-element',
					'elegantFavouriteElements',
					$favourite_elements
				);
			}
		}

		/**
		 * Elegant Elements enqueue script.
		 *
		 * @since 1.0
		 * @access public
		 * @param string $handle       Script enqueue handle.
		 * @param string $url          Script url.
		 * @param string $path         Script path.
		 * @param array  $dependencies Script dependencies.
		 * @param string $version      Script version.
		 * @param bool   $in_footer    Enqueue script in footer or not.
		 * @return void
		 */
		public static function enqueue_script( $handle, $url, $path, $dependencies, $version, $in_footer ) {
			wp_enqueue_script( $handle, $url, $dependencies, $version, $in_footer );
		}

		/**
		 * Elegant Elements enqueue script.
		 *
		 * @since 1.2
		 * @access public
		 * @return array
		 */
		public function get_available_dynamic_fields() {
			$available_fields = apply_filters( 'elegant_dynamic_fields', array() );

			// Add WP Core fields.
			$available_fields['post']['page_title']     = esc_attr__( 'Page Title', 'elegant-elements' );
			$available_fields['post']['post_date']      = esc_attr__( 'Post Date', 'elegant-elements' );
			$available_fields['post']['excerpt']        = esc_attr__( 'Excerpt', 'elegant-elements' );
			$available_fields['post']['author_name']    = esc_attr__( 'Author Name', 'elegant-elements' );
			$available_fields['post']['featured_image'] = esc_attr__( 'Featured Image', 'elegant-elements' );

			// If ACF is active, add current post type fields.
			if ( function_exists( 'get_field_objects' ) ) {
				$fields = get_field_objects();
				if ( $fields ) {
					foreach ( $fields as $field ) {
						$available_fields['acf'][ $field['name'] ] = $field['label'];
					}
				}
			}

			// Custom Fields.
			$custom_fields = get_post_custom();
			foreach ( $custom_fields as $meta_field => $value ) {

				// If custom field is already available in ACF or is hidden ( fields start with _ ), skip it here.
				if ( ! isset( $available_fields['acf'][ $meta_field ] ) && 0 !== strpos( $meta_field, '_' ) ) {
					$available_fields['meta'][ $meta_field ] = $meta_field;
				}
			}

			return $available_fields;
		}
	} // End Elegant_Elements_WPBakery class.
} // End if statement.

/**
 * Instantiates the Elegant_Elements_WPBakery class.
 * Make sure the class is properly set-up.
 * The Elegant_Elements_WPBakery class is a singleton
 * so we can directly access the one true Elegant_Elements_WPBakery object using this function.
 *
 * @return object Elegant_Elements_WPBakery
 */
function infi_eewpb() {
	return Elegant_Elements_WPBakery::get_instance();
}

/**
 * Instantiate Elegant_Elements_WPBakery class.
 *
 * @since 1.0
 * @return void
 */
function infi_elegant_elements_wpbakery_activate() {
	infi_eewpb();
}
add_action( 'after_setup_theme', 'infi_elegant_elements_wpbakery_activate', 11 );

/**
 * Initialize Elegant elements once FB elements are loaded.
 *
 * @since 1.0
 * @return void
 */
function init_elegant_elements_wpb() {
	// Require all custom params.
	foreach ( glob( EEWPB_PLUGIN_DIR . 'inc/params/*.php', GLOB_NOSORT ) as $filename ) {
		require_once $filename;
	}

	// Require all elements.
	foreach ( glob( EEWPB_PLUGIN_DIR . 'elements/*.php', GLOB_NOSORT ) as $filename ) {
		require_once $filename;
	}

	// Require all header elements.
	foreach ( glob( EEWPB_PLUGIN_DIR . 'elements/header/*.php', GLOB_NOSORT ) as $filename ) {
		require_once $filename;
	}

	// Require the dynamic params.
	require_once EEWPB_PLUGIN_DIR . 'inc/elegant-dynamic-params.php';
}
add_action( 'vc_after_init', 'init_elegant_elements_wpb' );

require_once EEWPB_PLUGIN_DIR . 'inc/class-eewpb-updater.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-eewpb-envato-api.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-eewpb-product-registration.php';
require_once EEWPB_PLUGIN_DIR . 'inc/helpers.php';
require_once EEWPB_PLUGIN_DIR . 'inc/template-helpers.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-forms.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-header-builder.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-footer-builder.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-elements-wpbakery-admin.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-templates-library.php';
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-elements-wpbakery-patcher.php';

/**
 * Auto activate elements on plugin activation.
 *
 * @since 1.0
 * @return void
 */
function elegant_elements_activation() {
	// Update current version number to database.
	update_option( 'elegant_elements_wpbakery_version', EEWPB_VERSION );

	// Reset Patches.
	delete_transient( 'wppatcher_patches_' . sanitize_title_with_dashes( 'elegant-elements-wpbakery' ) );
}

register_activation_hook( EEWPB_PLUGIN_FILE, 'elegant_elements_activation' );
