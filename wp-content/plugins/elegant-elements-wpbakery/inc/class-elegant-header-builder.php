<?php
/**
 * Header builder for WPBakery Page Builder.
 *
 * @package Elegant Elements
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Builder class.
 */
class Elegant_Header_Builder {

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
	 * Get header template to use.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public $header_template;

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
			self::$instance = new Elegant_Header_Builder();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function __construct() {
		// Register post types.
		add_action( 'init', array( $this, 'register_post_types' ) );

		// Enable the WPBakery Page Builder by default for new header posts.
		add_action( 'wp_insert_post', array( $this, 'set_default_custom_fields' ) );

		// Provide single header template via filter.
		add_filter( 'template_include', array( $this, 'page_template_single_header' ), 99 );

		// Replace header.php template if page has custom header enabled.
		add_action( 'get_header', array( $this, 'override_header_template' ) );

		// Add custom css for the header post type edit screen.
		add_action( 'admin_head', array( $this, 'clean_header_builder_edit_screen' ) );

		// Add content container div with theme compatibility.
		add_action( 'eewpb_header_theme_container', array( $this, 'header_theme_container' ) );

		// Filter body classes and apply them to header container.
		add_filter( 'body_class', array( $this, 'body_class' ), 999 );

		// Add header placeholder content styles.
		add_action( 'elegant_header_placeholder_styles', array( $this, 'elegant_header_placeholder_styles' ) );
	}

	/**
	 * Add header placeholder content styles.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function elegant_header_placeholder_styles() {
		?>
		<style>
		.vc_empty-placeholder {
			display: none;
		}
		.wpb-controls-placeholder {
			background: rgba( 5, 114, 170, .6 );
			margin-top: 0;
			width: 100%;
			height: 30px;
		}
		.vc_row[data-vc-full-width] {
			overflow: visible !important;
		}
		.content-area {
			max-width: 1160px;
			margin: 0 auto;
			padding-top: 40px;
		}
		h3.header-edit-heading {
			padding: 40px 20px;
		}
		.compose-mode .vc_vc_row>[data-vc-full-width=true], .compose-mode .vc_vc_row_inner, .compose-mode .vc_vc_section>[data-vc-full-width=true] {
			padding-top: 0;
		}
		@media screen and ( min-width: 768px ) and ( max-width: 1024px ) {
			#elegant-header-wrapper .vc_row.wpb_row .vc_vc_column {
				order: var(--tablet-order);
			}
		}

		@media screen and ( max-width: 767px ) {
			#elegant-header-wrapper .vc_row.wpb_row .vc_vc_column {
				order: var(--mobile-order);
			}
		}

		@media screen and ( min-width: 1024px ) {
			#elegant-header-wrapper .vc_row.wpb_row .vc_vc_column {
				order: var(--desktop-order);
			}
		}
		</style>
		<script type="text/javascript">
		jQuery( window ).load( function() {
			jQuery.each( jQuery( '#elegant-header-wrapper .vc_column_container' ), function() {
				var style = jQuery( this ).attr( 'style' );
				jQuery( this ).parent( '.vc_element' ).attr( 'style', style );
			} );
		} );
		</script>
		<?php
	}

	/**
	 * Add the container div with theme compatibility.
	 *
	 * @since 1.0
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {

		// Remove astra theme sidebar class as it conflicts with the full width row styling.
		$astra_sidebar_class = array_search( 'ast-right-sidebar', $classes );
		unset( $classes[ $astra_sidebar_class ] );

		$astra_sidebar_class = array_search( 'ast-left-sidebar', $classes );
		unset( $classes[ $astra_sidebar_class ] );

		if ( function_exists( 'astra_page_layout' ) ) {
			$classes[] = 'theme-astra';
		}

		if ( function_exists( 'storefront_do_shortcode' ) ) {
			$classes[] = 'theme-storefront';
		}

		return $classes;
	}

	/**
	 * Add the container div with theme compatibility.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function header_theme_container() {
		// Get the active theme.
		$theme = wp_get_theme();

		$container_div = '<div id="main" class="hfeed site container eewpb-container">';

		// Astra compatibility.
		if ( function_exists( 'astra_page_layout' ) ) {
			$classes       = ' ast-' . astra_page_layout();
			$container_div = '<div id="page" class="hfeed site ' . $classes . ' ast-container eewpb-container">';
		}

		// OceanWP compatibility.
		if ( 'OceanWP' === $theme->name ) {
			$container_div = '<div id="main" class="hfeed site eewpb-container">';
		}

		// Flatsome compatibility.
		if ( 'Flatsome' === $theme->name ) {
			$container_div = '<div id="main" class="hfeed site">';
		}

		echo wp_kses_post( $container_div );
	}

	/**
	 * Add custom css for the header post type edit screen.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function clean_header_builder_edit_screen() {
		global $typenow;
		if ( 'eewpb_header' === $typenow ) {
			?>
			<style>
			#side-sortables .postbox:not(#submitdiv) {
				display: none;
			}
			</style>
			<?php
		}
	}

	/**
	 * Function for overriding the header in the elmentor way.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function override_header_template() {
		$header_post = eewpb_template_get_post( 'header' );

		if ( $header_post ) {
			$this->header_template = $header_post;

			// Include template file from the plugin.
			require EEWPB_PLUGIN_DIR . 'theme-parts/single-header.php';

			// Make sure to enqueue the styles for header content.
			do_action( 'elegant_elements_render_template_style', $header_post );

			// Avoid running wp_head hooks again.
			remove_all_actions( 'wp_head' );

			$templates   = array();
			$templates[] = 'header.php';

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
		}

	}

	/**
	 * Register post types for headers.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post_types() {
		$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine

		// Register post type - eewpb_header.
		$labels = array(
			'name'                  => _x( 'Elegant Header Builder', 'Post Type General Name', 'elegant-elements' ),
			'singular_name'         => _x( 'Elegant Header Builder', 'Post Type Singular Name', 'elegant-elements' ),
			'menu_name'             => esc_attr__( 'Elegant Header Builder', 'elegant-elements' ),
			'name_admin_bar'        => esc_attr__( 'Elegant Header', 'elegant-elements' ),
			'archives'              => esc_attr__( 'Header Archives', 'elegant-elements' ),
			'attributes'            => esc_attr__( 'Header Attributes', 'elegant-elements' ),
			'parent_item_colon'     => esc_attr__( 'Parent Header:', 'elegant-elements' ),
			'all_items'             => esc_attr__( 'All Headers', 'elegant-elements' ),
			'add_new_item'          => esc_attr__( 'Add New Header', 'elegant-elements' ),
			'add_new'               => esc_attr__( 'Add New', 'elegant-elements' ),
			'new_item'              => esc_attr__( 'New Header', 'elegant-elements' ),
			'edit_item'             => esc_attr__( 'Edit Header', 'elegant-elements' ),
			'update_item'           => esc_attr__( 'Update Header', 'elegant-elements' ),
			'view_item'             => esc_attr__( 'View Header', 'elegant-elements' ),
			'view_items'            => esc_attr__( 'View Headers', 'elegant-elements' ),
			'search_items'          => esc_attr__( 'Search Header', 'elegant-elements' ),
			'not_found'             => esc_attr__( 'Not found', 'elegant-elements' ),
			'not_found_in_trash'    => esc_attr__( 'Not found in Trash', 'elegant-elements' ),
			'featured_image'        => esc_attr__( 'Featured Image', 'elegant-elements' ),
			'set_featured_image'    => esc_attr__( 'Set featured image', 'elegant-elements' ),
			'remove_featured_image' => esc_attr__( 'Remove featured image', 'elegant-elements' ),
			'use_featured_image'    => esc_attr__( 'Use as featured image', 'elegant-elements' ),
			'insert_into_item'      => esc_attr__( 'Insert into header', 'elegant-elements' ),
			'uploaded_to_this_item' => esc_attr__( 'Uploaded to this header', 'elegant-elements' ),
			'items_list'            => esc_attr__( 'Headers list', 'elegant-elements' ),
			'items_list_navigation' => esc_attr__( 'Headers list navigation', 'elegant-elements' ),
			'filter_items_list'     => esc_attr__( 'Filter headers list', 'elegant-elements' ),
		);
		$args   = array(
			'label'               => esc_attr__( 'Elegant Header', 'elegant-elements' ),
			'description'         => esc_attr__( 'Elegant Header Builder for WPBakery Page Builder', 'elegant-elements' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 4,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => ( is_user_logged_in() ) ? true : false,
			'capability_type'     => 'page',
		);

		// Register post type - eewpb_ptb.
		$ptb_labels = array(
			'name'                  => _x( 'Elegant Page Title Bar', 'Post Type General Name', 'elegant-elements' ),
			'singular_name'         => _x( 'Elegant Page Title Bar', 'Post Type Singular Name', 'elegant-elements' ),
			'menu_name'             => esc_attr__( 'Elegant Page Title Bar', 'elegant-elements' ),
			'name_admin_bar'        => esc_attr__( 'Page Title Bar', 'elegant-elements' ),
			'all_items'             => esc_attr__( 'All Page Title Bars', 'elegant-elements' ),
			'add_new_item'          => esc_attr__( 'Add New Header', 'elegant-elements' ),
			'add_new'               => esc_attr__( 'Add New', 'elegant-elements' ),
			'new_item'              => esc_attr__( 'New Page Title Bar', 'elegant-elements' ),
			'edit_item'             => esc_attr__( 'Edit Page Title Bar', 'elegant-elements' ),
			'update_item'           => esc_attr__( 'Update Page Title Bar', 'elegant-elements' ),
			'view_item'             => esc_attr__( 'View Page Title Bar', 'elegant-elements' ),
			'view_items'            => esc_attr__( 'View Page Title Bars', 'elegant-elements' ),
			'search_items'          => esc_attr__( 'Search Page Title Bar', 'elegant-elements' ),
			'not_found'             => esc_attr__( 'Not found', 'elegant-elements' ),
			'not_found_in_trash'    => esc_attr__( 'Not found in Trash', 'elegant-elements' ),
			'featured_image'        => esc_attr__( 'Featured Image', 'elegant-elements' ),
			'set_featured_image'    => esc_attr__( 'Set featured image', 'elegant-elements' ),
			'remove_featured_image' => esc_attr__( 'Remove featured image', 'elegant-elements' ),
			'use_featured_image'    => esc_attr__( 'Use as featured image', 'elegant-elements' ),
			'insert_into_item'      => esc_attr__( 'Insert into header', 'elegant-elements' ),
			'uploaded_to_this_item' => esc_attr__( 'Uploaded to this header', 'elegant-elements' ),
			'items_list'            => esc_attr__( 'Page Title Bars list', 'elegant-elements' ),
			'items_list_navigation' => esc_attr__( 'Page Title Bars list navigation', 'elegant-elements' ),
			'filter_items_list'     => esc_attr__( 'Filter headers list', 'elegant-elements' ),
		);
		$ptb_args   = array(
			'label'               => esc_attr__( 'Elegant Page Title Bar', 'elegant-elements' ),
			'description'         => esc_attr__( 'Elegant Page Title Bar for WPBakery Page Builder', 'elegant-elements' ),
			'labels'              => $ptb_labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 4,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => ( is_user_logged_in() ) ? true : false,
			'capability_type'     => 'page',
		);

		// Register post type - elegant_menu.
		$menu_labels = array(
			'name'          => esc_attr__( 'Elegant Mega Menu Items', 'elegant-elements' ),
			'singular_name' => esc_attr__( 'Elegant Mega Menu Item', 'elegant-elements' ),
			'all_items'     => esc_attr__( 'All Menu Items', 'elegant-elements' ),
		);

		$menu_args = array(
			'labels'              => $menu_labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => ( $is_vc_inline ) ? true : false,
			'supports'            => array( 'title', 'editor' ),
		);

		// Register header builder.
		register_post_type( 'eewpb_header', $args );

		// Register page title bar.
		register_post_type( 'eewpb_ptb', $ptb_args );

		// Register menu builder.
		register_post_type( 'elegant_menu', $args );
	}

	/**
	 * Set the default editor as WPBakery Page Builder for header post type.
	 *
	 * @access public
	 * @param int $post_id Current post id.
	 * @return bool
	 */
	public function set_default_custom_fields( $post_id ) {
		if ( isset( $_GET['post_type'] ) && ( 'eewpb_header' === $_GET['post_type'] || 'elegant_menu' === $_GET['post_type'] || 'eewpb_ptb' === $_GET['post_type'] ) ) { // @codingStandardsIgnoreLine
			update_post_meta( $post_id, '_wpb_vc_js_status', 'true', true );
		}

		return true;
	}

	/**
	 * Set header single template.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $single_post_template Default single post template.
	 * @return array
	 */
	public function page_template_single_header( $single_post_template ) {
		global $post;

		if ( ! is_singular( 'eewpb_header' ) && ! is_singular( 'eewpb_ptb' ) ) {
			return $single_post_template;
		}

		// Check the post-type.
		if ( 'eewpb_header' !== $post->post_type && 'eewpb_ptb' !== $post->post_type ) {
			return $single_post_template;
		}

		// Check the post-type.
		if ( 'eewpb_header' === $post->post_type ) {
			// The filename of the template.
			$filename = 'single.php';
		} elseif ( 'eewpb_ptb' === $post->post_type ) {
			// The filename of the template.
			$filename = 'single-ptb.php';
		}

		// Include template file from the plugin.
		$single_template = EEWPB_PLUGIN_DIR . 'theme-parts/' . $filename;

		// Checks if the single post is.
		if ( file_exists( $single_template ) ) {
			return $single_template;
		}

		return $single_post_template;
	}
}

/**
 * Instantiate and return Elegant_Header_Builder class object.
 *
 * @since 1.0
 * @return object Elegant_Header_Builder
 */
function eewpb_header_builder() {
	return Elegant_Header_Builder::get_instance();
}

// Instantiate the class.
eewpb_header_builder();
