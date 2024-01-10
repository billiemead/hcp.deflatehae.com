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
	$template['name']           = esc_html__( 'Lottier – Social', 'lottier-wpbakery' );
	$template['content']        = <<<CONTENT

[vc_row content_placement="middle" css=".vc_custom_1591358105361{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;border-top-width: 1px !important;border-right-width: 1px !important;border-bottom-width: 1px !important;border-left-width: 1px !important;border-left-color: #cccccc !important;border-left-style: solid !important;border-right-color: #cccccc !important;border-right-style: solid !important;border-top-color: #cccccc !important;border-top-style: solid !important;border-bottom-color: #cccccc !important;border-bottom-style: solid !important;border-radius: 25px !important;}"][vc_column width="1/6"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_description="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F19697-thumbs-up-notification-icon-animation.json|||" style_animation_css=".vc_custom_1591358147884{margin-bottom: 24px !important;}"][/vc_column][vc_column width="5/6"][vc_custom_heading text="Follow, Like, Share!" font_container="tag:h4|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal" css=".vc_custom_1591357954131{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][vc_column_text el_class="mdp-no-frame" css=".vc_custom_1591358129897{margin-top: -20px !important;margin-right: 0px !important;margin-bottom: 25px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"]Like us and share this page on social networks.[/vc_column_text][/vc_column][/vc_row]

CONTENT;
	array_unshift( $data, $template );
	return $data;
} );
