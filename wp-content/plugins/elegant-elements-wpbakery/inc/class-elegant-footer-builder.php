<?php
/**
 * Footer builder for WPBakery Page Builder.
 *
 * @package Elegant Elements
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Footer Builder class.
 */
class Elegant_Footer_Builder {

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
	 * Get footer template to use.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public $footer_template;

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
			self::$instance = new Elegant_Footer_Builder();
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

		// Enable the WPBakery Page Builder by default for new footer posts.
		add_action( 'wp_insert_post', array( $this, 'set_default_custom_fields' ) );

		// Provide single footer template via filter.
		add_filter( 'template_include', array( $this, 'page_template_single_footer' ), 98 );

		// Replace footer.php template if page has custom footer enabled.
		add_action( 'get_footer', array( $this, 'override_footer_template' ) );

		// Add custom css for the footer post type edit screen.
		add_action( 'admin_head', array( $this, 'clean_footer_builder_edit_screen' ) );

		// Add footer placeholder content styles.
		add_action( 'elegant_footer_placeholder_styles', array( $this, 'elegant_footer_placeholder_styles' ) );
	}

	/**
	 * Add footer placeholder content styles.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function elegant_footer_placeholder_styles() {
		?>
		<style type="text/css">
		#elegant-footer-placeholder-content {
			max-width: 1160px;
			margin: 0 auto;
			padding: 100px 0;
		}
		#elegant-footer-wrapper {
			position: fixed;
			bottom: 0;
			top: auto;
			left: 0;
			right: 0;
		}
		.vc_row[data-vc-full-width] {
			overflow: visible;
		}
		</style>
		<?php
	}

	/**
	 * Add custom css for the footer post type edit screen.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function clean_footer_builder_edit_screen() {
		global $typenow;
		if ( 'eewpb_footer' === $typenow ) {
			?>
			<style>
			#side-sortables .postbox:not(#submitdiv) {
				display: none;
			}
			.post-type-eewpb_footer .acf-field.acf-field-elegant-mobile-header,
			.post-type-eewpb_footer .acf-field.acf-field-elegant-page-title-bar {
				display: none !important;
			}
			</style>
			<?php
		}
	}

	/**
	 * Function for overriding the footer in the elmentor way.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function override_footer_template() {
		$footer_post = eewpb_template_get_post( 'footer' );

		if ( $footer_post ) {
			$this->footer_template = $footer_post;

			// Include template file from the plugin.
			require EEWPB_PLUGIN_DIR . 'theme-parts/single-footer.php';

			// Make sure to enqueue the styles for footer content.
			do_action( 'elegant_elements_render_template_style', $footer_post );

			// Avoid running wp_head hooks again.
			remove_all_actions( 'wp_footer' );

			$templates   = array();
			$templates[] = 'footer.php';

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
		}
	}

	/**
	 * Register post types for footer.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post_types() {
		$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine

		// Register post type - eewpb_footer.
		$labels = array(
			'name'                  => _x( 'Elegant Footer Builder', 'Post Type General Name', 'elegant-elements' ),
			'singular_name'         => _x( 'Elegant Footer Builder', 'Post Type Singular Name', 'elegant-elements' ),
			'menu_name'             => esc_attr__( 'Elegant Footer Builder', 'elegant-elements' ),
			'name_admin_bar'        => esc_attr__( 'Elegant Footer', 'elegant-elements' ),
			'archives'              => esc_attr__( 'Footer Archives', 'elegant-elements' ),
			'attributes'            => esc_attr__( 'Footer Attributes', 'elegant-elements' ),
			'parent_item_colon'     => esc_attr__( 'Parent Footer:', 'elegant-elements' ),
			'all_items'             => esc_attr__( 'All Footers', 'elegant-elements' ),
			'add_new_item'          => esc_attr__( 'Add New Footer', 'elegant-elements' ),
			'add_new'               => esc_attr__( 'Add New', 'elegant-elements' ),
			'new_item'              => esc_attr__( 'New Footer', 'elegant-elements' ),
			'edit_item'             => esc_attr__( 'Edit Footer', 'elegant-elements' ),
			'update_item'           => esc_attr__( 'Update Footer', 'elegant-elements' ),
			'view_item'             => esc_attr__( 'View Footer', 'elegant-elements' ),
			'view_items'            => esc_attr__( 'View Footers', 'elegant-elements' ),
			'search_items'          => esc_attr__( 'Search Footer', 'elegant-elements' ),
			'not_found'             => esc_attr__( 'Not found', 'elegant-elements' ),
			'not_found_in_trash'    => esc_attr__( 'Not found in Trash', 'elegant-elements' ),
			'featured_image'        => esc_attr__( 'Featured Image', 'elegant-elements' ),
			'set_featured_image'    => esc_attr__( 'Set featured image', 'elegant-elements' ),
			'remove_featured_image' => esc_attr__( 'Remove featured image', 'elegant-elements' ),
			'use_featured_image'    => esc_attr__( 'Use as featured image', 'elegant-elements' ),
			'insert_into_item'      => esc_attr__( 'Insert into footer', 'elegant-elements' ),
			'uploaded_to_this_item' => esc_attr__( 'Uploaded to this footer', 'elegant-elements' ),
			'items_list'            => esc_attr__( 'Footers list', 'elegant-elements' ),
			'items_list_navigation' => esc_attr__( 'Footers list navigation', 'elegant-elements' ),
			'filter_items_list'     => esc_attr__( 'Filter footers list', 'elegant-elements' ),
		);
		$args   = array(
			'label'               => esc_attr__( 'Elegant Footer', 'elegant-elements' ),
			'description'         => esc_attr__( 'Elegant Footer Builder for WPBakery Page Builder', 'elegant-elements' ),
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

		register_post_type( 'eewpb_footer', $args );
	}

	/**
	 * Set the default editor as WPBakery Page Builder for footer post type.
	 *
	 * @access public
	 * @param int $post_id Current post id.
	 * @return bool
	 */
	public function set_default_custom_fields( $post_id ) {
		if ( isset( $_GET['post_type'] ) && ( 'eewpb_footer' === $_GET['post_type'] ) ) { // @codingStandardsIgnoreLine
			update_post_meta( $post_id, '_wpb_vc_js_status', 'true', true );
		}

		return true;
	}

	/**
	 * Set footer single template.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $single_post_template Default single post template.
	 * @return array
	 */
	public function page_template_single_footer( $single_post_template ) {
		global $post;

		if ( ! is_singular( 'eewpb_footer' ) ) {
			return $single_post_template;
		}

		// Check the post-type.
		if ( 'eewpb_footer' !== $post->post_type ) {
			return $single_post_template;
		}

		// The filename of the template.
		$filename = 'footer.php';

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
 * Instantiate and return Elegant_Footer_Builder class object.
 *
 * @since 1.0
 * @return object Elegant_Footer_Builder
 */
function eewpb_footer_builder() {
	return Elegant_Footer_Builder::get_instance();
}

// Instantiate the class.
eewpb_footer_builder();
