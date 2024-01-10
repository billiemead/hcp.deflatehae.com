<?php

if(!function_exists('conall_edge_disable_wpml_css')) {
    function conall_edge_disable_wpml_css() {
	    define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
    }

	add_action('after_setup_theme', 'conall_edge_disable_wpml_css');
}