<?php

if ( ! function_exists( 'conall_edge_load_shortcode_interface' ) ) {
	
	function conall_edge_load_shortcode_interface() {
		include_once EDGE_CORE_ABS_PATH . '/shortcodes/shortcode-interface.php';
	}
	
	add_action( 'conall_edge_before_options_map', 'conall_edge_load_shortcode_interface' );
}

if ( ! function_exists( 'conall_edge_load_shortcodes' ) ) {
	/**
	 * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
	 * and loads load.php file in each. Hooks to conall_edge_after_options_map action
	 *
	 * @see http://php.net/manual/en/function.glob.php
	 */
	function conall_edge_load_shortcodes() {
		foreach ( glob( EDGE_CORE_ABS_PATH . '/shortcodes/*/load.php' ) as $shortcode_load ) {
			include_once $shortcode_load;
		}
		
		include_once EDGE_CORE_ABS_PATH . '/shortcodes/shortcode-loader.php';
	}
	
	add_action( 'conall_edge_before_options_map', 'conall_edge_load_shortcodes' );
}