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
	$template['name']           = esc_html__( 'Lottier – Features', 'lottier-wpbakery' );
	$template['content']        = <<<CONTENT

[vc_row gap="35" css=".vc_custom_1591351797872{padding: 16px !important;}"][vc_column width="1/3" css=".vc_custom_1591284554112{border-top-width: 1px !important;border-right-width: 1px !important;border-bottom-width: 1px !important;border-left-width: 1px !important;padding-top: 10% !important;padding-right: 10% !important;padding-bottom: 10% !important;padding-left: 10% !important;border-left-color: #e0e0e0 !important;border-left-style: solid !important;border-right-color: #e0e0e0 !important;border-right-style: solid !important;border-top-color: #e0e0e0 !important;border-top-style: solid !important;border-bottom-color: #e0e0e0 !important;border-bottom-style: solid !important;border-radius: 30px !important;}"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23817-performance.json|||" description_position="footer" description_text="Extremely lighter and incredibly fast." style_description_font="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal"][/vc_column][vc_column width="1/3" css=".vc_custom_1591284623668{border-top-width: 1px !important;border-right-width: 1px !important;border-bottom-width: 1px !important;border-left-width: 1px !important;padding-top: 10% !important;padding-right: 10% !important;padding-bottom: 10% !important;padding-left: 10% !important;border-left-color: #e0e0e0 !important;border-left-style: solid !important;border-right-color: #e0e0e0 !important;border-right-style: solid !important;border-top-color: #e0e0e0 !important;border-top-style: solid !important;border-bottom-color: #e0e0e0 !important;border-bottom-style: solid !important;border-radius: 30px !important;}"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23802-paths.json|||" description_position="footer" description_text="Flexible and powerful animation control settings" style_description_font="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal"][/vc_column][vc_column width="1/3" css=".vc_custom_1591284612550{padding: 10% !important;border: 1px solid #e0e0e0 !important;border-radius: 30px !important;}"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23804-creativity.json|||" description_position="footer" description_text="A powerful creative tool to improve your site." style_description_font="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal"][/vc_column][/vc_row]

CONTENT;
	array_unshift( $data, $template );
	return $data;
} );
