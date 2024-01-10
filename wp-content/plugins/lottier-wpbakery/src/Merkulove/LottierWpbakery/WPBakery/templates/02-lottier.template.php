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
	$template['name']           = esc_html__( 'Lottier – Hero Block', 'lottier-wpbakery' );
	$template['content']        = <<<CONTENT

[vc_row content_placement="middle"][vc_column width="2/3"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_description="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23844-concept-man-flying-with-books.json|||"][/vc_column][vc_column width="1/3"][vc_custom_heading text="Free your creativity" font_container="tag:h3|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal"][vc_column_text el_class="mdp-no-frame"]The animation library includes thousands of exciting animations made by professional designers from around the world. You can easily pick up an animation that suits your site and your customers and use it in just two clicks.[/vc_column_text][/vc_column][/vc_row]

CONTENT;
	array_unshift( $data, $template );
	return $data;
} );
