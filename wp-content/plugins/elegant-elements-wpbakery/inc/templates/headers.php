<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Header Template.
$template_data['eewpb-header-algolia'] = array(
	'name'      => esc_html__( 'Algolia Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-algolia.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-algolia',
);
$template_data['eewpb-header-algolia']['content'] = <<<CONTENT
	[vc_row css=".vc_custom_1603733583344{padding-top: 0px !important;padding-bottom: 10px !important;}"][vc_column width="1/6" css=".vc_custom_1603733850930{margin-top: 10px !important;}" offset="vc_col-xs-4"][vc_single_image image="32" img_size="full" onclick="custom_link" link="#"][/vc_column][vc_column width="5/6" css=".vc_custom_1603733880995{margin-top: 10px !important;}" offset="vc_col-xs-8"][iee_search_form height="36" input_text_color="#5d6494" input_text_bg_color="#dddfed" button_type="icon" button_bg_color="#5468ff" icon_size="21" button_width="60" border_radius="50" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_width_top="" border_width_left="" border_width_bottom="" border_width_right=""][/vc_column][/vc_row][vc_row content_placement="middle"][vc_column width="2/3" css=".vc_custom_1603733716149{margin-left: 0px !important;padding-left: 0px !important;}" offset="vc_col-xs-6"][iee_header_menu text_transform="uppercase" in_active_menu_color="#5d6494" active_menu_color="#5d6494" active_menu_bg_color="rgba(7,122,255,0.01)" main_menu_font_size="12" main_menu_item_space="15" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="60" mobile_toggle_font_size="16" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column][vc_column width="1/3" css=".vc_custom_1603733808720{margin-right: 0px !important;padding-right: 0px !important;padding-left: 0px !important;}" offset="vc_col-xs-6"][vc_row_inner content_placement="middle" css=".vc_custom_1603733820369{padding-right: 0px !important;}"][vc_column_inner width="7/12" css=".vc_custom_1603734225023{margin-right: 0px !important;padding-right: 0px !important;}" offset="vc_col-lg-7 vc_col-md-5 vc_col-xs-5"][vc_column_text]
<p style="text-align: right;"><strong><span style="color: #5d6494; font-size: 12px;">LOG IN</span></strong></p>
[/vc_column_text][/vc_column_inner][vc_column_inner width="5/12" offset="vc_col-lg-5 vc_col-md-7 vc_col-xs-7"][vc_btn title="<strong>FREE TRIAL<strong>" style="gradient-custom" gradient_custom_color_1="#ffffff" gradient_custom_color_2="#ffffff" gradient_text_color="#5468ff" shape="square" size="sm" align="right" i_align="right" i_icon_fontawesome="fas fa-angle-right" add_icon="true" link="url:%23" css=".vc_custom_1603575790390{margin-bottom: 0px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]</strong></strong>
CONTENT;

// Header Template.
$template_data['eewpb-header-birdeye'] = array(
	'name'      => esc_html__( 'Birdeye Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-birdeye.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-birdeye',
);
$template_data['eewpb-header-birdeye']['content'] = <<<CONTENT
	<p>[vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1604349441409{margin-top: 0px !important;margin-right: 0px !important;margin-left: 0px !important;border-right-width: 0px !important;padding-top: 10px !important;padding-bottom: 10px !important;}"][vc_column width="1/6" css=".vc_custom_1603737578325{margin-bottom: 0px !important;margin-left: 0px !important;padding-bottom: 0px !important;}" offset="vc_col-lg-2 vc_col-md-2 vc_col-xs-8"][vc_single_image image="24" img_size="full"][/vc_column][vc_column width="5/12" offset="vc_col-lg-5 vc_col-md-10 vc_col-xs-4" css=".vc_custom_1604349316202{margin-left: 0px !important;padding-left: 0px !important;}"][iee_header_menu menu="birdeye" in_active_menu_color="#000000" active_menu_color="#1e73be" active_menu_bg_color="rgba(7,122,255,0.01)" main_menu_font_size="15" main_menu_item_space="13" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="50" mobile_toggle_font_size="16" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column][vc_column width="5/12" css=".vc_custom_1603737374070{margin-right: 0px !important;margin-left: 0px !important;padding-right: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-5 vc_col-md-12 vc_col-xs-12"][vc_row_inner content_placement="middle" css=".vc_custom_1603574096306{margin-right: 0px !important;padding-right: 0px !important;}"][vc_column_inner width="1/2" offset="vc_col-lg-offset-0 vc_col-lg-6 vc_col-md-offset-0 vc_col-md-3 vc_col-sm-offset-0 vc_col-xs-offset-1 vc_col-xs-10"][iee_content_box heading_text="1 800 561 3357" heading_font_size="20" heading_space="0" icon="fas fa-phone-alt" icon_font_size="18" icon_css=".vc_custom_1603741196738{margin-right: -30px !important;}" icon_position="left" link_type="box" link_url="url:%23" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="elegant-align-center"][/iee_content_box][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-7 vc_col-xs-5"][vc_column_text]</p>
<p style="text-align: right;"><span style="color: #333333; font-size: 16px;"><a style="color: #333333;" href="#">Sign In</a></span></p>
<p>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1603737511033{margin-right: 0px !important;padding-right: 0px !important;}" offset="vc_col-lg-4 vc_col-md-2 vc_col-xs-7"][vc_btn title="<strong>WATCH DEMO</strong>" style="flat" color="primary" size="sm" align="right" link="url:%23" css=".vc_custom_1604349467526{padding-top: 10px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]</p>
CONTENT;

// Header Template.
$template_data['eewpb-header-github'] = array(
	'name'      => esc_html__( 'Github Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-github.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-github',
);
$template_data['eewpb-header-github']['content'] = <<<CONTENT
	[vc_row full_width="stretch_row" content_placement="middle" css=".vc_custom_1603564843357{margin-left: 0px !important;padding-top: 6px !important;padding-bottom: 6px !important;background-color: #24292e !important;}"][vc_column width="7/12" css=".vc_custom_1603741844179{margin-left: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-7 vc_col-md-8"][vc_row_inner content_placement="middle"][vc_column_inner width="1/12"][vc_icon icon_fontawesome="fab fa-github" color="white" link="url:%23" css=".vc_custom_1603563394413{margin-bottom: 0px !important;}"][/vc_column_inner][vc_column_inner width="11/12"][iee_header_menu menu="github" text_transform="capitalize" in_active_menu_color="#ffffff" active_menu_color="#ffffff" active_menu_bg_color="rgba(255,255,255,0.01)" main_menu_font_size="15" main_menu_item_space="10" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="50" mobile_toggle_font_size="16" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="5/12" css=".vc_custom_1603741825063{padding-right: 0px !important;}" offset="vc_col-lg-5 vc_col-md-4 vc_col-xs-12"][vc_row_inner content_placement="top" css=".vc_custom_1603564664501{margin-top: 0px !important;padding-top: 0px !important;}"][vc_column_inner width="7/12" css=".vc_custom_1603741534258{margin-left: 0px !important;}" offset="vc_hidden-md vc_hidden-sm vc_hidden-xs"][iee_search_form placeholder_text="Search Github" search_layout="minimal" height="36" input_text_color="#e5e5e5" input_text_bg_color="rgba(250,250,250,0.01)" button_text_color="#ffffff" icon_size="16" border_radius="3" border_color="rgba(0,0,0,0.01)" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_width_top="" border_width_left="" border_width_bottom="" border_width_right=""][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-8 vc_col-xs-6"][vc_column_text]
<p style="text-align: right; margin-bottom: 0;"><span style="color: #ffffff;"><a style="color: #ffffff;" href="#">Sign In</a></span></p>
[/vc_column_text][/vc_column_inner][vc_column_inner width="1/4" css=".vc_custom_1603741718345{padding-right: 0px !important;}" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-6"][vc_btn title="Sign up" style="outline" size="sm" align="right" link="url:%23" css=".vc_custom_1603565420535{margin-bottom: 0px !important;border-top-width: 1px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;

// Header Template.
$template_data['eewpb-header-justworks'] = array(
	'name'      => esc_html__( 'Justworks Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-justworks.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-justworks',
);
$template_data['eewpb-header-justworks']['content'] = <<<CONTENT
	<p>[vc_row full_width="stretch_row" content_placement="middle" css=".vc_custom_1603833506882{border-bottom-width: 1px !important;padding-top: 10px !important;padding-bottom: 10px !important;border-bottom-color: #eaeaea !important;border-bottom-style: solid !important;}"][vc_column width="5/12" css=".vc_custom_1603832696502{margin-left: 0px !important;padding-left: 0px !important;}"][iee_header_menu menu="justworks" in_active_menu_color="#a9adb5" active_menu_color="#39b6e9" active_menu_bg_color="rgba(7,122,255,0.01)" main_menu_font_size="16" main_menu_item_space="15" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="50" mobile_toggle_type="icon" mobile_toggle_font_size="16" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column][vc_column width="1/4"][vc_single_image image="147" img_size="full" alignment="center"][/vc_column][vc_column width="1/3" css=".vc_custom_1603833208499{margin-right: 0px !important;margin-left: 0px !important;padding-right: 0px !important;padding-left: 0px !important;}" offset="vc_hidden-xs"][vc_row_inner content_placement="middle"][vc_column_inner width="1/2" offset="vc_hidden-xs"][vc_column_text]</p>
<p style="text-align: right;"><span style="color: #39b6e9; font-size: 16px;"><a style="color: #39b6e9;" href="#">Login</a></span></p>
<p>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_btn title="<strong>Get Started</strong>" style="gradient-custom" gradient_custom_color_1="#df5a31" gradient_custom_color_2="#df5a31" align="right" link="url:%23" css=".vc_custom_1603833444375{margin-bottom: 0px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]</p>
CONTENT;

// Header Template.
$template_data['eewpb-header-mailchimp'] = array(
	'name'      => esc_html__( 'Mailchimp Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-mailchimp.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-mailchimp',
);
$template_data['eewpb-header-mailchimp']['content'] = <<<CONTENT
	<p>[vc_row full_width="stretch_row_content" css=".vc_custom_1603831529813{padding-top: 20px !important;padding-bottom: 20px !important;background-color: #ffe01b !important;}"][vc_column width="5/12" offset="vc_col-lg-3 vc_col-md-5 vc_col-xs-4"][iee_header_menu menu="mailchimp" in_active_menu_color="#000000" active_menu_color="#009aed" active_menu_bg_color="rgba(7,122,255,0.01)" main_menu_font_size="14" main_menu_item_space="15" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="50" mobile_toggle_type="icon" mobile_toggle_font_size="16" mobile_toggle_background="rgba(251,251,251,0.01)" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column][vc_column width="1/4" offset="vc_col-lg-6 vc_col-md-3 vc_col-xs-4"][vc_single_image image="148" img_size="full" alignment="center"][/vc_column][vc_column width="1/6" offset="vc_col-lg-2 vc_col-md-3 vc_col-xs-4"][iee_fancy_button button_title="Log In" custom_link="url:%23" color="#000000" color_hover="#fcfcfc" background="#000000" alignment="right" title_font_size="18" hide_on_mobile="small-visibility,medium-visibility,large-visibility"][/vc_column][vc_column width="1/12" css=".vc_custom_1603830856533{margin-left: 0px !important;padding-left: 0px !important;}" offset="vc_hidden-md vc_hidden-sm vc_hidden-xs"][vc_btn title="<strong>Sign Up Free</strong>" style="custom" custom_background="#007c89" custom_text="#ffffff" shape="square" align="right" link="url:%23" css=".vc_custom_1603831278744{margin-bottom: 0px !important;}"][/vc_column][/vc_row]</p>
CONTENT;

// Header Template.
$template_data['eewpb-header-slack'] = array(
	'name'      => esc_html__( 'Slack Header', 'elegant-elements' ),
	'image'     => EEWPB_PLUGIN_URL . '/assets/admin/img/header/header-slack.jpg',
	'type'      => 'elegant_elements_templates',
	'category'  => 'header',
	'unique_id' => 'eewpb-header-slack',
);
$template_data['eewpb-header-slack']['content'] = <<<CONTENT
	<p>[vc_row full_width="stretch_row" content_placement="middle" css=".vc_custom_1604245435002{border-bottom-width: 1px !important;padding-top: 15px !important;padding-bottom: 15px !important;border-bottom-color: #cecece !important;border-bottom-style: solid !important;}"][vc_column width="1/6" css=".vc_custom_1604245968435{padding-top: 10px !important;}"][vc_single_image image="150" img_size="full"][/vc_column][vc_column width="1/2" css=".vc_custom_1604250790628{margin-left: 0px !important;padding-left: 0px !important;}"][iee_header_menu menu="slack" text_transform="capitalize" active_menu_color="#1264a3" active_menu_bg_color="rgba(18,100,163,0.01)" main_menu_font_size="16" main_menu_item_space="15" dropdown_menu_font_size="13" dropdown_menu_height="40" menu_item_height="50" mobile_toggle_type="icon" mobile_toggle_font_size="16" hide_on_mobile="small-visibility,medium-visibility,large-visibility" border_radius_top_left="" border_radius_top_right="" border_radius_bottom_right="" border_radius_bottom_left=""][/vc_column][vc_column width="1/6" css=".vc_custom_1604245883898{margin-right: 0px !important;padding-right: 0px !important;}" offset="vc_hidden-md vc_hidden-sm vc_hidden-xs"][iee_footer_links display_type="horizontal" space_between_links="10" alignment="right" heading_font_size="18" link_font_size="16" links="%5B%7B%22title%22%3A%22Contact%20Sales%22%2C%22link%22%3A%22url%3A%2523%22%7D%2C%7B%22title%22%3A%22Sign%20In%22%2C%22link%22%3A%22url%3A%2523%22%7D%5D" hide_on_mobile="small-visibility,medium-visibility,large-visibility"][/vc_column][vc_column width="1/6"][vc_btn title="TRY FOR FREE" style="custom" custom_background="#611f69" custom_text="#ffffff" size="sm" align="center" link="url:%23" css=".vc_custom_1604245711723{margin-bottom: 0px !important;}"][/vc_column][/vc_row]</p>
CONTENT;
