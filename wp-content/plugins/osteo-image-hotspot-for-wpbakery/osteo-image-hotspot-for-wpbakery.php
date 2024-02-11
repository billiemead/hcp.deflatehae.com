<?php

/**
 * Plugin Name: Osteo Image Hotspot for WPbakery
 * Description: This is a image hotspot addon for WPbakery Page Builder.
 * Plugin URI:  https://twinkledev.xyz/codecanyon/osteo-image-hotspot/
 * Version:     1.0.0
 * Author:      TwinkleTheme
 * Author URI:  https://codecanyon.net/user/twinkletheme
 * Text Domain: 'osteo-image-hotspot'
 */

namespace Osteo_Image_Hotspot;

defined( 'ABSPATH' ) || die();
define( 'OSTEO_IMAGE_HOTSPOT_VERSION', '1.0.0' );
define( 'OSTEO_IMAGE_HOTSPOT_ROOT', __FILE__ );
define( 'OSTEO_IMAGE_HOTSPOT_PATH', plugin_dir_path( OSTEO_IMAGE_HOTSPOT_ROOT ) );
define( 'OSTEO_IMAGE_HOTSPOT_URL', plugin_dir_url( OSTEO_IMAGE_HOTSPOT_ROOT ) );
define( 'OSTEO_IMAGE_HOTSPOT_ASSETS', trailingslashit( OSTEO_IMAGE_HOTSPOT_URL . 'assets/' ) );
define( 'OSTEO_IMAGE_HOTSPOT_PLUGIN_BASE', plugin_basename( OSTEO_IMAGE_HOTSPOT_ROOT ) );

class OsteoImageHotspot {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    private function __construct() {
        add_action( 'plugins_loaded', [$this, 'load_textdomain'] );
        add_action( 'plugins_loaded', [$this, 'init'] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'osteo-image-hotspot', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    function init() {

        $this->include_files();

        // Check if WPBakery Page Builder is installed
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Extend WPBakery Page Builder is required
            add_action( 'admin_notices', array( $this, 'showVcVersionNotice' ) );
            return;
        }

    }

    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data( __FILE__ );
        echo '
        <div class="updated">
          <p>' . sprintf( __( '<strong>%s</strong> requires <strong><a href="https://wpbakery.com/" target="_blank">WPbakery Page Builder</a></strong> plugin to be installed and activated on your site.', 'vc_extend' ), esc_html( $plugin_data['Name'] ) ) . '</p>
        </div>';
    }

    public function include_files() {
        include_once OSTEO_IMAGE_HOTSPOT_PATH . ( 'includes/functions.php' );
        include_once OSTEO_IMAGE_HOTSPOT_PATH . ( 'includes/widgets-manager.php' );
        include_once OSTEO_IMAGE_HOTSPOT_PATH . ( 'includes/row-manager.php' );
        include_once OSTEO_IMAGE_HOTSPOT_PATH . ( 'lib/tgm-active.php' );
    }
}

OsteoImageHotspot::instance();