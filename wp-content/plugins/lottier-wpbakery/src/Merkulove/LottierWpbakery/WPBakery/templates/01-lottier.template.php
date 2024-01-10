<?php
/**
 * {{plugin_description}}
 * Exclusively on Envato Market: {{plugin_uri}}
 *
 * @encoding        UTF-8
 * @version         1.1.5
 * @copyright       {{plugin_copyright}}
 * @license         {{license}}
 * @contributors    {{contributors}}
 * @support         {{plugin_support}}
 **/

add_filter( 'vc_load_default_templates', function($data) {
	$template                   = [];
	$template['name']           = esc_html__( 'Lottier – Order complete', 'lottier-wpbakery' );
	$template['content']        = <<<CONTENT

[vc_row content_placement="middle" el_class="mdp-no-frame"][vc_column width="1/4"][vc_custom_heading text="Thank you!" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal"][vc_custom_heading text="Your order is on the way" font_container="tag:h3|text_align:left"][vc_column_text]Back to products ->[/vc_column_text][/vc_column][vc_column width="3/4"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_description="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23821-delivery-loader-animation.json|||"][/vc_column][/vc_row]

CONTENT;
	array_unshift( $data, $template );
	return $data;
} );
