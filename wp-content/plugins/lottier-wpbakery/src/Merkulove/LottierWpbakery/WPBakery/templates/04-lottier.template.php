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
	$template['name']           = esc_html__( 'Lottier – Split Features', 'lottier-wpbakery' );
	$template['content']        = <<<CONTENT

[vc_row content_placement="middle"][vc_column width="1/4" css=".vc_custom_1591355825034{padding-right: 10% !important;padding-left: 10% !important;}"][vc_icon type="material" icon_material="vc-material vc-material-brightness_low" color="custom" background_style="rounded-outline" size="lg" css=".vc_custom_1591356370278{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;}" custom_color="#04788e"][vc_custom_heading text="Extremely light and fast" font_container="tag:h4|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal" css=".vc_custom_1591356152246{padding-top: 20px !important;}"][vc_icon type="material" icon_material="vc-material vc-material-ac_unit" color="custom" background_style="rounded-outline" size="lg" css=".vc_custom_1591356413256{margin-top: 80px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;}" custom_color="#04788e"][vc_custom_heading text="Flexible and powerful settings" font_container="tag:h4|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal" css=".vc_custom_1591356192477{padding-top: 20px !important;}"][/vc_column][vc_column width="1/4" css=".vc_custom_1591355871600{padding-top: 80px !important;padding-right: 10% !important;padding-left: 10% !important;}"][vc_icon type="material" icon_material="vc-material vc-material-schedule" color="custom" background_style="rounded-outline" size="lg" css=".vc_custom_1591356382179{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;}" custom_color="#04788e"][vc_custom_heading text="Fast and reliable solutions" font_container="tag:h4|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal" css=".vc_custom_1591356161593{padding-top: 20px !important;}"][vc_icon type="material" icon_material="vc-material vc-material-center_focus_strong" color="custom" background_style="rounded-outline" size="lg" css=".vc_custom_1591356393656{margin-top: 80px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;}" custom_color="#04788e"][vc_custom_heading text="An attention-grabbing solution" font_container="tag:h4|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:700%20bold%20regular%3A700%3Anormal" css=".vc_custom_1591356169005{padding-top: 20px !important;}"][/vc_column][vc_column width="2/4"][mdp_wpb_lottier properties_animation_speed="1" properties_controls="" properties_header="" properties_description="" properties_enable_link="" animation_url="url:https%3A%2F%2Fwpbakery.merkulov.design%2Fwp-content%2Fuploads%2F2020%2F06%2F23936-lighthouse.json|||" style_animation_css=".vc_custom_1591356255293{padding-top: 10% !important;padding-bottom: 10% !important;}"][/vc_column][/vc_row]

CONTENT;
	array_unshift( $data, $template );
	return $data;
} );
