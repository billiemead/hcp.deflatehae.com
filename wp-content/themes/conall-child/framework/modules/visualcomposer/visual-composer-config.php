<?php

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
if(function_exists('vc_set_as_theme')) {
	vc_set_as_theme(true);
}

/**
 * Change path for overridden templates
 */
if(function_exists('vc_set_shortcodes_templates_dir')) {
	$dir = EDGE_ROOT_DIR . '/vc-templates';
	vc_set_shortcodes_templates_dir( $dir );
}

// if ( ! function_exists('conall_edge_configure_visual_composer') ) {
	/**
	 * Configuration for Visual Composer
	 * Hooks on vc_after_init action
	 */
	// function conall_edge_configure_visual_composer() {

		/**
		 * Removing shortcodes
		 */
		/* vc_remove_element("vc_wp_search");
		vc_remove_element("vc_wp_meta");
		vc_remove_element("vc_wp_recentcomments");
		vc_remove_element("vc_wp_calendar");
		vc_remove_element("vc_wp_pages");
		vc_remove_element("vc_wp_tagcloud");
		vc_remove_element("vc_wp_custommenu");
		vc_remove_element("vc_wp_text");
		vc_remove_element("vc_wp_posts");
		vc_remove_element("vc_wp_links");
		vc_remove_element("vc_wp_categories");
		vc_remove_element("vc_wp_archives");
		vc_remove_element("vc_wp_rss");
		vc_remove_element("vc_teaser_grid");
		vc_remove_element("vc_button");
		vc_remove_element("vc_cta_button");
		vc_remove_element("vc_cta_button2");
		vc_remove_element("vc_message");
		vc_remove_element("vc_tour");
		vc_remove_element("vc_progress_bar");
		vc_remove_element("vc_pie");
		vc_remove_element("vc_posts_slider");
		vc_remove_element("vc_toggle");
		vc_remove_element("vc_images_carousel");
		vc_remove_element("vc_posts_grid");
		vc_remove_element("vc_carousel");
		vc_remove_element("vc_gmaps");
		vc_remove_element("vc_cta");
		vc_remove_element("vc_round_chart");
		vc_remove_element("vc_line_chart");
		vc_remove_element("vc_tta_accordion");
		vc_remove_element("vc_tta_tour");
		vc_remove_element("vc_tta_tabs");
		vc_remove_element("vc_separator");
		vc_remove_element("vc_section"); */

		/**
		 * Remove Grid Elements if grid elements disabled
		 */
		/* vc_remove_element('vc_basic_grid');
		vc_remove_element('vc_media_grid');
		vc_remove_element('vc_masonry_grid');
		vc_remove_element('vc_masonry_media_grid');
		vc_remove_element('vc_icon');
		vc_remove_element('vc_button2');
		vc_remove_element("vc_custom_heading"); */

		/**
		 * Remove unused parameters
		 */
		/* if (function_exists('vc_remove_param')) {
			vc_remove_param('vc_row', 'full_width');
			vc_remove_param('vc_row', 'full_height');
			vc_remove_param('vc_row', 'content_placement');
			vc_remove_param('vc_row', 'video_bg');
			vc_remove_param('vc_row', 'video_bg_url');
			vc_remove_param('vc_row', 'video_bg_parallax');
			vc_remove_param('vc_row', 'parallax');
			vc_remove_param('vc_row', 'parallax_image');
			vc_remove_param('vc_row', 'gap');
			vc_remove_param('vc_row', 'columns_placement');
			vc_remove_param('vc_row', 'equal_height');
			vc_remove_param('vc_row', 'parallax_speed_bg');
			vc_remove_param('vc_row', 'parallax_speed_video');
			vc_remove_param('vc_row', 'css_animation');
			vc_remove_param('vc_row_inner', 'content_placement');
			vc_remove_param('vc_row_inner', 'equal_height');
			vc_remove_param('vc_row_inner', 'gap');
			vc_remove_param('vc_row_inner', 'disable_element');
			vc_remove_param('vc_row', 'disable_element');
		} */
	// }

	// add_action('vc_after_init', 'conall_edge_configure_visual_composer');
// }

// if ( ! function_exists('conall_edge_configure_visual_composer_frontend_editor') ) {
	/**
	 * Configuration for Visual Composer FrontEnd Editor
	 * Hooks on vc_after_init action
	 */
	// function conall_edge_configure_visual_composer_frontend_editor() {
		/**
		 * Remove frontend editor
		 */
	//	if(function_exists('vc_disable_frontend')){
	//		vc_disable_frontend();
	//	}
	// }

	// add_action('vc_after_init', 'conall_edge_configure_visual_composer_frontend_editor');
// }

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Edgtf_Accordion extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Accordion_Tab extends WPBakeryShortCodesContainer {}
    class WPBakeryShortCode_Edgtf_Animation_Holder extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Elements_Holder extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Elements_Holder_Item extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Gallery_Blocks extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Gallery_Blocks_Masonry extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Item_Showcase extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Parallax_Sections extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Pricing_Tables extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Tabs extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Tab extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Team_Carousels extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Call_To_Action_Slider extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Particles extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Edgtf_Particles_Content extends WPBakeryShortCodesContainer {}
}

/*** Row ***/
if ( ! function_exists('conall_edge_vc_row_map') ) {
	/**
	 * Map VC Row shortcode
	 * Hooks on vc_after_init action
	 */
	function conall_edge_vc_row_map() {

		$animations = array(
			esc_html__( 'No animation', 'conall' ) => '',
			esc_html__( 'Elements Shows From Left Side', 'conall' ) => 'edgtf-element-from-left',
			esc_html__( 'Elements Shows From Right Side', 'conall' ) => 'edgtf-element-from-right',
			esc_html__( 'Elements Shows From Top Side', 'conall' ) => 'edgtf-element-from-top',
			esc_html__( 'Elements Shows From Bottom Side', 'conall' ) => 'edgtf-element-from-bottom',
			esc_html__( 'Elements Shows From Fade', 'conall' ) => 'edgtf-element-from-fade'
		);

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Row Type', 'conall' ) ,
			'param_name' => 'row_type',
			'value' => array(
				esc_html__( 'Row', 'conall' ) => 'row',
				esc_html__( 'Parallax', 'conall' ) => 'parallax'
			)
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Content Width', 'conall' ) ,
			'param_name' => 'content_width',
			'value' => array(
				esc_html__( 'Full Width', 'conall' ) => 'full-width',
				esc_html__( 'In Grid', 'conall' ) => 'grid'
			)
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Anchor ID', 'conall' ) ,
			'param_name' => 'anchor',
			'value' => '',
			'description' => esc_html__( 'For example home', 'conall' )
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Content Aligment', 'conall' ) ,
			'param_name' => 'content_aligment',
			'value' => array(
				esc_html__( 'Left', 'conall' ) => 'left',
				esc_html__( 'Center', 'conall' ) => 'center',
				esc_html__( 'Right', 'conall' ) => 'right'
			)
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Video Background', 'conall' ) ,
			'value' => array(
				esc_html__( 'No', 'conall' ) => '',
				esc_html__( 'Yes', 'conall' ) => 'show_video'
			),
			'param_name' => 'video',
			'dependency' => Array('element' => 'row_type', 'value' => array('row'))
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Video Overlay', 'conall' ) ,
			'value' => array(
				esc_html__( 'No', 'conall' ) => '',
				esc_html__( 'Yes', 'conall' ) => 'show_video_overlay'
			),
			'param_name' => 'video_overlay',
			'dependency' => Array('element' => 'video', 'value' => array('show_video'))
		));

		vc_add_param('vc_row', array(
			'type' => 'attach_image',
			'class' => '',
			'heading' => esc_html__( 'Video Overlay Image (pattern)', 'conall' ) ,
			'value' => '',
			'param_name' => 'video_overlay_image',
			'dependency' => Array('element' => 'video_overlay', 'value' => array('show_video_overlay'))
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Video Background (webm) File URL', 'conall' ) ,
			'value' => '',
			'param_name' => 'video_webm',
			'dependency' => Array('element' => 'video', 'value' => array('show_video'))
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Video Background (mp4) file URL', 'conall' ) ,
			'value' => '',
			'param_name' => 'video_mp4',
			'dependency' => Array('element' => 'video', 'value' => array('show_video'))
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Video Background (ogv) file URL', 'conall' ) ,
			'value' => '',
			'param_name' => 'video_ogv',
			'dependency' => Array('element' => 'video', 'value' => array('show_video'))
		));

		vc_add_param('vc_row', array(
			'type' => 'attach_image',
			'class' => '',
			'heading' => esc_html__( 'Video Preview Image', 'conall' ) ,
			'value' => '',
			'param_name' => 'video_image',
			'dependency' => Array('element' => 'video', 'value' => array('show_video'))
		));

		vc_add_param("vc_row", array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Full Screen Height', 'conall' ) ,
			'param_name' => 'full_screen_section_height',
			'value' => array(
				esc_html__( 'No', 'conall' ) => 'no',
				esc_html__( 'Yes', 'conall' ) => 'yes'
			),
			'save_always' => true,
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Vertically Align Content In Middle', 'conall' ) ,
			'param_name' => 'vertically_align_content_in_middle',
			'value' => array(
				esc_html__( 'No', 'conall' ) => 'no',
				esc_html__( 'Yes', 'conall' ) => 'yes'
			),
			'dependency' => array('element' => 'full_screen_section_height', 'value' => 'yes')
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Section Height', 'conall' ) ,
			'param_name' => 'section_height',
			'value' => '',
			'dependency' => Array('element' => 'full_screen_section_height', 'value' => array('no'))
		));

		vc_add_param('vc_row', array(
			'type' => 'attach_image',
			'class' => '',
			'heading' => esc_html__( 'Parallax Background image', 'conall' ) ,
			'value' => '',
			'param_name' => 'parallax_background_image',
			'description' => esc_html__( 'Please note that for parallax row type, background image from Design Options will not work so you should to fill this field', 'conall' ) ,
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Parallax speed', 'conall' ) ,
			'param_name' => 'parallax_speed',
			'value' => '',
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));

		vc_add_param('vc_row', array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'CSS Animation', 'conall' ) ,
			'param_name' => 'css_animation',
			'value' => $animations,
			'dependency' => Array('element' => 'row_type', 'value' => array('row'))
		));

		vc_add_param('vc_row', array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Transition delay (ms)', 'conall' ) ,
			'param_name' => 'transition_delay',
			'admin_label' => true,
			'value' => '',
			'dependency' => array('element' => 'css_animation', 'not_empty' => true)
		));

		/*** Row Inner ***/

		vc_add_param('vc_row_inner', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Row Type', 'conall' ) ,
			'param_name' => 'row_type',
			'value' => array(
				esc_html__( 'Row', 'conall' ) => 'row',
				esc_html__( 'Parallax', 'conall' ) => 'parallax'
			)
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Content Width', 'conall' ) ,
			'param_name' => 'content_width',
			'value' => array(
				esc_html__( 'Full Width', 'conall' ) => 'full-width',
				esc_html__( 'In Grid', 'conall' ) => 'grid'
			)
		));

		vc_add_param("vc_row_inner", array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Full Screen Height', 'conall' ) ,
			'param_name' => 'full_screen_section_height',
			'value' => array(
				esc_html__( 'No', 'conall' ) => 'no',
				esc_html__( 'Yes', 'conall' ) => 'yes'
			),
			'save_always' => true,
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Vertically Align Content In Middle', 'conall' ) ,
			'param_name' => 'vertically_align_content_in_middle',
			'value' => array(
				esc_html__( 'No', 'conall' ) => 'no',
				esc_html__( 'Yes', 'conall' ) => 'yes'
			),
			'dependency' => array('element' => 'full_screen_section_height', 'value' => 'yes')
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Section Height', 'conall' ) ,
			'param_name' => 'section_height',
			'value' => '',
			'dependency' => Array('element' => 'full_screen_section_height', 'value' => array('no'))
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'attach_image',
			'class' => '',
			'heading' => esc_html__( 'Parallax Background image', 'conall' ) ,
			'value' => '',
			'param_name' => 'parallax_background_image',
			'description' => esc_html__( 'Please note that for parallax row type, background image from Design Options will not work so you should to fill this field', 'conall' ) ,
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__( 'Parallax speed', 'conall' ) ,
			'param_name' => 'parallax_speed',
			'value' => '',
			'dependency' => Array('element' => 'row_type', 'value' => array('parallax'))
		));
		vc_add_param('vc_row_inner', array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__( 'Content Aligment', 'conall' ) ,
			'param_name' => 'content_aligment',
			'value' => array(
				esc_html__( 'Left', 'conall' ) => 'left',
				esc_html__( 'Center', 'conall' ) => 'center',
				esc_html__( 'Right', 'conall' ) => 'right'
			)
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'CSS Animation', 'conall' ) ,
			'param_name' => 'css_animation',
			'admin_label' => true,
			'value' => $animations,
			'dependency' => Array('element' => 'row_type', 'value' => array('row'))
		));

		vc_add_param('vc_row_inner', array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Transition delay (ms)', 'conall' ) ,
			'param_name' => 'transition_delay',
			'admin_label' => true,
			'value' => '',
			'dependency' => Array('element' => 'row_type', 'value' => array('row'))
		));
	}

	add_action('vc_after_init', 'conall_edge_vc_row_map');
}