<?php
/**
 * Lottier for Wpbakery
 * Lottie animations in just a few clicks without writing a single line of code
 * Exclusively on https://1.envato.market/lottier-wpbakery
 *
 * @encoding        UTF-8
 * @version         1.1.5
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\LottierWpbakery;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * SINGLETON: UninstallHelper class run when the users deletes the plugin.
 *
 * @since 1.0.0
 *
 **/
final class UninstallHelper {

	/**
	 * The one true UninstallHelper.
	 *
     * @since 1.0.0
     * @access private
	 * @var UninstallHelper
	 **/
	private static $instance;

    /**
     * Implement plugin uninstallation.
     *
     * @param string $uninstall_mode - Uninstall mode: plugin, plugin+settings, plugin+settings+data
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     **/
    public function uninstall( $uninstall_mode ) { }

	/**
	 * Main UninstallHelper Instance.
	 * Insures that only one instance of UninstallHelper exists in memory at any one time.
	 *
	 * @static
     * @since 1.0.0
     * @access public
     *
	 * @return UninstallHelper
	 **/
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

}
