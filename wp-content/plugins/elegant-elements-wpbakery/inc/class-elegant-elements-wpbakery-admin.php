<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elegant_Elements_WPBakery_Admin {

	/**
	 * Store element list.
	 *
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public static $elements = array();

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		add_filter( 'parent_file', array( $this, 'highlight_header_footer_admin_menu' ), 1 );

		add_filter( 'elegant_elements_list', array( $this, 'elegant_elements_list' ) );

		// Save Settings.
		add_action( 'admin_post_save_elegant_elements_wpbakery_settings', array( $this, 'settings_save' ) );
	}

	/**
	 * Highlight the Elegant Elements admin menu if edit header post is being displayed.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $parent_file Menu parent file.
	 * @return string $parent_file
	 */
	public function highlight_header_footer_admin_menu( $parent_file ) {
		global $submenu_file, $post;
		if ( $post && ( 'eewpb_header' === $post->post_type || 'eewpb_footer' === $post->post_type ) ) {
			?>
			<script type="text/javascript">
			jQuery( document ).ready( function() {
				var reference = jQuery( '#toplevel_page_elegant-elements-wpbakery' );

				// Add highlighting to our custom submenu.
				reference.addClass( 'current wp-has-current-submenu' );
			} );
			</script>
			<?php
		}

		return $parent_file;
	}

	/**
	 * Store the elements into an array.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $element Mapped element.
	 * @return void
	 */
	public function elegant_elements_list( $element ) {
		$element_name                    = str_replace( 'Elegant', '', $element['name'] );
		self::$elements[ $element_name ] = $element;
	}

	/**
	 * Admin Head.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_head() {
		$icon = EEWPB_PLUGIN_URL . 'assets/admin/img/icon.svg';
		$css  = '';

		if ( isset( $_GET['vc_action'] ) && 'vc_inline' === $_GET['vc_action'] ) {
			$css = '.notice { display: none; }';
		}

		echo '<style type="text/css">.dashicons-elegant-elements:before {
			content: "";
			background: url( ' . esc_attr( $icon ) . ' ) no-repeat center center;
			background-size: contain;
		}
		.elegant-elements-logo {
			background-image: url( ' . esc_attr( $icon ) . ' ) !important;
			background-color: #0473aa;
		    background-size: 100px 100px;
		    background-position: 20px 12px;
		}
		.elegant-elements-version {
			background: #000000;
			box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
			color: #ffffff;
			display: block;
			margin-top: 5px;
			padding: 10px 0;
			text-align: center;
		}
		#elegant_default_title_typography .select_wrapper.typography-family-backup,
		#elegant_default_title_typography .select_wrapper.typography-script.tooltip,
		#elegant_default_description_typography .select_wrapper.typography-family-backup,
		#elegant_default_description_typography .select_wrapper.typography-script.tooltip {
			display: none !important;
		}
		#pframe, #fs_promo_tab,
		tr.fs-field-site_secret_key,
		tr.fs-field-site_public_key,
		tr.fs-field-product_id {
			display: none;
		}
		.fs-notice {
		    width: calc( 100% - 200px );
		}
		.wrap.fs-section #fs_account .postbox,
		#fs_account .postbox, #fs_account .widefat {
			max-width: 100%;
		}
		.wrap.fs-section {
		    margin-left: 0;
		    margin-right: 0;
		}
		.elegant-elements-features {
		    display: flex;
		    justify-content: space-between;
			flex-wrap: wrap;
		}
		.elegant-elements-features .card {
			max-width: calc( 50% - 10px );
			border: none;
		}
		' . $css . '
		.template-preview-image {
		    width: 300px;
		    padding-top: 220px!important;
		    margin-left: auto!important;
		    margin-right: auto!important;
		    transition: background-position 0.5s ease-out 0.5s;
		    /* background-position: top center; */
			background-position: center;
		    background-size: 100% auto!important;
		    height: 100%;
		    background-repeat: no-repeat;
		}

		.template-preview-image:hover {
		    background-position: bottom center !important;
		    transition: background-position 0.5s linear 0s;
		}
		.vc_templates-list-elegant-templates .vc_ui-template {
		    width: 300px;
		    margin-right: 20px;
			margin-bottom: 55px !important;
		}

		.vc_templates-list-elegant-templates {
		    display: flex;
			flex-wrap: wrap;
		}

		.vc_templates-list-elegant-templates .vc_ui-template .vc_ui-list-bar-item {
			border: 1px solid #e2e2e294 !important;
		    position: relative;
		}

		.vc_templates-list-elegant-templates .vc_ui-template .vc_ui-list-bar-item .vc_ui-list-bar-item-trigger {
		    position: absolute;
			top: 0;
			left: -1px;
			right: -1px;
			width: calc( 100% + 2px );
		    display: flex;
		    height: calc( 100% + 40px );
		    align-items: flex-end;
		    padding-bottom: 0;
		    margin-bottom: 20px;
		    transition: all 0.35s ease-in-out;
		}

		.vc_templates-list-elegant-templates .vc_ui-template .vc_ui-list-bar-item .vc_ui-list-bar-item-trigger span {
		    height: 40px;
		    line-height: 40px;
			background: #f1f1f1;
			color: #666;
		    width: 100%;
		    padding-left: 20px;
		    padding-right: 20px;
		    margin-left: -20px;
		    margin-right: -20px;
		}

		.vc_templates-list-elegant-templates .vc_ui-template:not( .elegant-template-header ):not( .elegant-template-footer ):hover .template-preview-image {
		    /* background-position: bottom center !important; */
		    transition: background-position 0.5s linear 0s;
		}

		.vc_templates-list-elegant-templates .vc_ui-template:hover .vc_ui-list-bar-item-trigger {
		    box-shadow: 0px 0px 6px -1px #0573a8;
		}
		.vc_edit-form-tab.vc_active[data-tab="elegant_elements_templates"] > .vc_column {
		    min-height: 555px;
		}
		.elegant-template-footer .vc_ui-list-bar-item .template-preview-image {
			padding-top: 200px!important;
			background-position: bottom;
			border: 1px solid rgba( 5, 115, 168, .15 );
		}
		.elegant-template-header .vc_ui-list-bar-item .template-preview-image {
			padding-top: 100px!important;
			background-position: top;
			border: 1px solid rgba( 5, 115, 168, .15 );
		}
		.post-type-eewpb_header .vc_templates-template-type-elegant_elements_templates:not( .elegant-template-header ),
		.post-type-eewpb_footer .vc_templates-template-type-elegant_elements_templates:not( .elegant-template-footer ) {
			display: none !important;
		}
		body:not( .post-type-eewpb_header ) .vc_templates-template-type-elegant_elements_templates.elegant-template-header,
		body:not( .post-type-eewpb_footer ) .vc_templates-template-type-elegant_elements_templates.elegant-template-footer {
			display: none !important;
		}
		</style>';
	}

	/**
	 * Admin Menu.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_menu() {
		global $submenu;

		$welcome        = add_menu_page( esc_attr__( 'Elegant Elements', 'elegant-elements' ), esc_attr__( 'Elegant Elements', 'elegant-elements' ), 'manage_options', 'elegant-elements-wpbakery', array( $this, 'welcome' ), 'dashicons-elegant-elements', '4.222222' );
		$register       = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Plugin Registration', 'elegant-elements' ), esc_attr__( 'Register', 'elegant-elements' ), 'manage_options', 'elegant-elements-register', array( $this, 'register_tab' ) );
		$settings       = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Settings', 'elegant-elements' ), esc_attr__( 'Settings', 'elegant-elements' ), 'manage_options', 'elegant-elements-settings', array( $this, 'settings_tab' ) );
		$headers        = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Headers', 'elegant-elements' ), esc_attr__( 'Headers', 'elegant-elements' ), 'manage_options', 'edit.php?post_type=eewpb_header' );
		$page_title_bar = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Page Title Bars', 'elegant-elements' ), esc_attr__( 'Page Title Bars', 'elegant-elements' ), 'manage_options', 'edit.php?post_type=eewpb_ptb' );
		$footers        = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Footers', 'elegant-elements' ), esc_attr__( 'Footers', 'elegant-elements' ), 'manage_options', 'edit.php?post_type=eewpb_footer' );
		$patcher        = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Patcher', 'elegant-elements' ), esc_attr__( 'Patcher', 'elegant-elements' ), 'manage_options', 'elegant-elements-patcher', array( $this, 'patcher_tab' ) );
		$support        = add_submenu_page( 'elegant-elements-wpbakery', esc_attr__( 'Support', 'elegant-elements' ), esc_attr__( 'Support', 'elegant-elements' ), 'manage_options', 'elegant-elements-support', array( $this, 'support_tab' ) );

		// Change the first menu item name.
		if ( current_user_can( 'edit_theme_options' ) ) {
			$submenu['elegant-elements-wpbakery'][0][0] = esc_attr__( 'Welcome', 'elegant-elements' ); // phpcs:ignore
		}

		add_action( 'admin_print_scripts-' . $welcome, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $register, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $settings, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $patcher, array( $this, 'admin_scripts' ) );
		add_action( 'admin_print_scripts-' . $support, array( $this, 'admin_scripts' ) );
	}

	/**
	 * Handles the saving of settings in admin area.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function settings_save() {
		check_admin_referer( 'elegant_elements_wpbakery_save_settings', 'elegant_elements_wpbakery_save_settings' );

		// @codingStandardsIgnoreLine WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
		$settings = ( ! empty( $_POST ) ) ? $_POST : array();

		// Update settings.
		update_option( 'elegant_elements_wpbakery_settings', $settings );

		// Redirect back to the settings page.
		wp_safe_redirect( admin_url( 'admin.php?page=elegant-elements-settings' ) );
		exit;
	}

	/**
	 * Admin scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_media();
		wp_enqueue_style( 'elegant_admin_css', EEWPB_PLUGIN_URL . 'assets/admin/css/min/elegant-elements-admin.min.css', '', EEWPB_VERSION );
		wp_enqueue_script( 'elegant-admin-js', EEWPB_PLUGIN_URL . 'assets/admin/js/min/elegant-elements-admin.min.js', array( 'jquery' ), EEWPB_VERSION, true );
	}

	/**
	 * Loads the welcome page template.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function welcome() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/welcome.php' );
	}

	/**
	 * Loads the register page template.
	 *
	 * @access public
	 * @since 1.4.0
	 * @return void
	 */
	public function register_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/register.php' );
	}

	/**
	 * Loads the support page template.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function support_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/support.php' );
	}

	/**
	 * Loads the patcher page template.
	 *
	 * @access public
	 * @since 1.4.0
	 * @return void
	 */
	public function patcher_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/patches.php' );
	}

	/**
	 * Loads the settings page template.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function settings_tab() {
		require_once wp_normalize_path( dirname( __FILE__ ) . '/admin-screens/settings.php' );
	}

	/**
	 * Loads the settings page template.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function account_tab() {
		// Do nothing. This is just a placeholder function.
	}

	/**
	 * Set the admin page tabs.
	 *
	 * @static
	 * @access protected
	 * @since 1.0
	 * @param string $title The title.
	 * @param string $page  The page slug.
	 */
	public static function admin_tab( $title, $page ) {

		$active_page = '';

		if ( isset( $_GET['page'] ) ) {
			$active_page = $_GET['page'];
		}

		if ( ( 'Headers' !== $title && 'Footers' !== $title ) && false === strpos( $page, 'post_type' ) ) {
			$link = admin_url() . 'admin.php?page=' . $page;
		} else {
			$link = admin_url() . $page;
		}

		if ( $active_page === $page ) {
			$active_tab = ' nav-tab-active';
		} else {
			$active_tab = '';
		}

		echo '<a href="' . $link . '" class="nav-tab fs-tab' . $active_tab . '">' . $title . '</a>'; // phpcs:ignore.

	}

	/**
	 * Adds the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public static function footer() {
		?>
		<div class="elegant-elements-thanks">
			<p class="description"><?php esc_html_e( 'Thank you for choosing Elegant Elements. We are honored and are fully dedicated to making your experience perfect.', 'elegant-elements' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Adds the header.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public static function header() {
		?>
		<h1><?php esc_html_e( 'Welcome to Elegant Elements!', 'elegant-elements' ); ?></h1>
		<div class="about-text">
				<?php esc_attr_e( 'Elegant Elements is now installed and ready to use! Get ready to build something beautiful. Please register your purchase on registration tab to receive automatic updates and support and unlock premium features. We hope you enjoy it!', 'elegant-elements' ); ?>
		</div>
		<div class="elegant-elements-logo wp-badge">
			<span class="elegant-elements-version">
				<?php printf( esc_attr__( 'Version %s', 'elegant-elements' ), esc_attr( EEWPB_VERSION ) ); ?>
			</span>
		</div>
		<h2 class="nav-tab-wrapper">
			<?php
			self::admin_tab( esc_attr__( 'Welcome', 'elegant-elements' ), 'elegant-elements-wpbakery' );
			self::admin_tab( esc_attr__( 'Registration', 'elegant-elements' ), 'elegant-elements-register' );
			self::admin_tab( esc_attr__( 'Settings', 'elegant-elements' ), 'elegant-elements-settings' );
			self::admin_tab( esc_attr__( 'Headers', 'elegant-elements' ), 'edit.php?post_type=eewpb_header' );
			self::admin_tab( esc_attr__( 'Footers', 'elegant-elements' ), 'edit.php?post_type=eewpb_footer' );
			self::admin_tab( esc_attr__( 'Patcher', 'elegant-elements' ), 'elegant-elements-patcher' );
			self::admin_tab( esc_attr__( 'Support', 'elegant-elements' ), 'elegant-elements-support' );
			?>
		</h2>
		<?php
	}
}

new Elegant_Elements_WPBakery_Admin();
