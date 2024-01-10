<?php

if ( ! function_exists('conall_edge_general_options_map') ) {
    /**
     * General options page
     */
    function conall_edge_general_options_map() {

        conall_edge_add_admin_page(
            array(
                'slug'  => '',
                'title' => esc_html__( 'General', 'conall' ),
                'icon'  => 'fa fa-institution'
            )
        );

        $panel_design_style = conall_edge_add_admin_panel(
            array(
                'page'  => '',
                'name'  => 'panel_design_style',
                'title' => esc_html__( 'Design Style', 'conall' )
            )
        );

	    conall_edge_add_admin_field(
		    array(
			    'name'          => 'enable_google_fonts',
			    'type'          => 'yesno',
			    'default_value' => 'yes',
			    'label'         => esc_html__( 'Enable Google Fonts', 'themenametd' ),
			    'parent'        => $panel_design_style,
			    'args'          => array(
				    "dependence" => true,
				    "dependence_hide_on_yes" => "",
				    "dependence_show_on_yes" => "#edgtf_google_fonts_container"
			    )
		    )
	    );

	    $google_fonts_container = conall_edge_add_admin_container(
		    array(
			    'parent'          => $panel_design_style,
			    'name'            => 'google_fonts_container',
		    )
	    );

        conall_edge_add_admin_field(
            array(
                'name'          => 'google_fonts',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose a default Google font for your site', 'conall' ),
                'parent' => $google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_fonts',
                'type'          => 'yesno',
                'default_value' => 'no',
                'label' => esc_html__( 'Additional Google Fonts', 'conall' ),
                'description'   => '',
                'parent'        => $google_fonts_container,
                'args'          => array(
                    "dependence" => true,
                    "dependence_hide_on_yes" => "",
                    "dependence_show_on_yes" => "#edgtf_additional_google_fonts_container"
                )
            )
        );

        $additional_google_fonts_container = conall_edge_add_admin_container(
            array(
                'parent'            => $google_fonts_container,
                'name'              => 'additional_google_fonts_container',
                'hidden_property'   => 'additional_google_fonts',
                'hidden_value'      => 'no'
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_font1',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose additional Google font for your site', 'conall' ),
                'parent'        => $additional_google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_font2',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose additional Google font for your site', 'conall' ),
                'parent'        => $additional_google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_font3',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose additional Google font for your site', 'conall' ),
                'parent'        => $additional_google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_font4',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose additional Google font for your site', 'conall' ),
                'parent'        => $additional_google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'additional_google_font5',
                'type'          => 'font',
                'default_value' => '-1',
                'label' => esc_html__( 'Font Family', 'conall' ),
                'description' => esc_html__( 'Choose additional Google font for your site', 'conall' ),
                'parent'        => $additional_google_fonts_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name' => 'google_font_weight',
                'type' => 'checkboxgroup',
                'default_value' => '',
                'label' => esc_html__( 'Google Fonts Style & Weight', 'conall' ),
                'description' => esc_html__( 'Choose a default Google font weights for your site. Impact on page load time', 'conall' ),
                'parent' => $google_fonts_container,
                'options' => array(
                    '100' => esc_html__( '100 Thin', 'conall' ),
                    '100italic' => esc_html__( '100 Thin Italic', 'conall' ),
                    '200' => esc_html__( '200 Extra-Light', 'conall' ),
                    '200italic' => esc_html__( '200 Extra-Light Italic', 'conall' ),
                    '300' => esc_html__( '300 Light', 'conall' ),
                    '300italic' => esc_html__( '300 Light Italic', 'conall' ),
                    '400' => esc_html__( '400 Regular', 'conall' ),
                    '400italic' => esc_html__( '400 Regular Italic', 'conall' ),
                    '500' => esc_html__( '500 Medium', 'conall' ),
                    '500italic' => esc_html__( '500 Medium Italic', 'conall' ),
                    '600' => esc_html__( '600 Semi-Bold', 'conall' ),
                    '600italic' => esc_html__( '600 Semi-Bold Italic', 'conall' ),
                    '700' => esc_html__( '700 Bold', 'conall' ),
                    '700italic' => esc_html__( '700 Bold Italic', 'conall' ),
                    '800' => esc_html__( '800 Extra-Bold', 'conall' ),
                    '800italic' => esc_html__( '800 Extra-Bold Italic', 'conall' ),
                    '900' => esc_html__( '900 Ultra-Bold', 'conall' ),
                    '900italic' => esc_html__( '900 Ultra-Bold Italic', 'conall' )
                )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name' => 'google_font_subset',
                'type' => 'checkboxgroup',
                'default_value' => '',
                'label' => esc_html__( 'Google Fonts Subset', 'conall' ),
                'description' => esc_html__( 'Choose a default Google font subsets for your site', 'conall' ),
                'parent' => $google_fonts_container,
                'options' => array(
                    'latin' => esc_html__( 'Latin', 'conall' ),
                    'latin-ext' => esc_html__( 'Latin Extended', 'conall' ),
                    'cyrillic' => esc_html__( 'Cyrillic', 'conall' ),
                    'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'conall' ),
                    'greek' => esc_html__( 'Greek', 'conall' ),
                    'greek-ext' => esc_html__( 'Greek Extended', 'conall' ),
                    'vietnamese' => esc_html__( 'Vietnamese', 'conall' )
                )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'first_color',
                'type'          => 'color',
                'label' => esc_html__( 'First Main Color', 'conall' ),
                'description' => esc_html__( 'Choose the most dominant theme color. Default color is #00bbb3', 'conall' ),
                'parent'        => $panel_design_style
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'page_background_color',
                'type'          => 'color',
                'label' => esc_html__( 'Page Background Color', 'conall' ),
                'description' => esc_html__( 'Choose the background color for page content. Default color is #ffffff', 'conall' ),
                'parent'        => $panel_design_style
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'selection_color',
                'type'          => 'color',
                'label' => esc_html__( 'Text Selection Color', 'conall' ),
                'description' => esc_html__( 'Choose the color users see when selecting text', 'conall' ),
                'parent'        => $panel_design_style
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'boxed',
                'type'          => 'yesno',
                'default_value' => 'no',
                'label' => esc_html__( 'Boxed Layout', 'conall' ),
                'description'   => '',
                'parent'        => $panel_design_style,
                'args'          => array(
                    "dependence" => true,
                    "dependence_hide_on_yes" => "",
                    "dependence_show_on_yes" => "#edgtf_boxed_container"
                )
            )
        );

        $boxed_container = conall_edge_add_admin_container(
            array(
                'parent'            => $panel_design_style,
                'name'              => 'boxed_container',
                'hidden_property'   => 'boxed',
                'hidden_value'      => 'no'
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'page_background_color_in_box',
                'type'          => 'color',
                'label' => esc_html__( 'Page Background Color', 'conall' ),
                'description' => esc_html__( 'Choose the page background color outside box.', 'conall' ),
                'parent'        => $boxed_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'boxed_background_image',
                'type'          => 'image',
                'label' => esc_html__( 'Background Image', 'conall' ),
                'description' => esc_html__( 'Choose an image to be displayed in background', 'conall' ),
                'parent'        => $boxed_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'boxed_pattern_background_image',
                'type'          => 'image',
                'label' => esc_html__( 'Background Pattern', 'conall' ),
                'description' => esc_html__( 'Choose an image to be used as background pattern', 'conall' ),
                'parent'        => $boxed_container
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'boxed_background_image_attachment',
                'type'          => 'select',
                'default_value' => 'fixed',
                'label' => esc_html__( 'Background Image Attachment', 'conall' ),
                'description' => esc_html__( 'Choose background image attachment', 'conall' ),
                'parent'        => $boxed_container,
                'options'       => array(
                    'fixed' => esc_html__( 'Fixed', 'conall' ),
                    'scroll' => esc_html__( 'Scroll', 'conall' )
                )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'initial_content_width',
                'type'          => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Initial Width of Content', 'conall' ),
                'description' => esc_html__( 'Choose the initial width of content which is in grid (Applies to pages set to Default Template and rows set to In Grid', 'conall' ),
                'parent'        => $panel_design_style,
                'options'       => array(
                    "" => esc_html__( "1100px - default", 'conall' ),
                    "grid-1300" => esc_html__( "1300px", 'conall' ),
                    "grid-1200" => esc_html__( "1200px", 'conall' ),
                    "grid-1000" => esc_html__( "1000px", 'conall' ),
                    "grid-800" => esc_html__( "800px", 'conall' )
                )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'preload_pattern_image',
                'type'          => 'image',
                'label' => esc_html__( 'Preload Pattern Image', 'conall' ),
                'description' => esc_html__( 'Choose preload pattern image to be displayed until images are loaded ', 'conall' ),
                'parent'        => $panel_design_style
            )
        );

        conall_edge_add_admin_field(
            array(
                'name' => 'element_appear_amount',
                'type' => 'text',
                'label' => esc_html__( 'Element Appearance', 'conall' ),
                'description' => esc_html__( 'For animated elements, set distance (related to browser bottom) to start the animation', 'conall' ),
                'parent' => $panel_design_style,
                'args' => array(
                    'col_width' => 2,
                    'suffix' => 'px'
                )
            )
        );

        $panel_settings = conall_edge_add_admin_panel(
            array(
                'page'  => '',
                'name'  => 'panel_settings',
                'title' => esc_html__( 'Settings', 'conall' )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'smooth_scroll',
                'type'          => 'yesno',
                'default_value' => 'no',
                'label' => esc_html__( 'Smooth Scroll', 'conall' ),
                'description' => esc_html__( 'Enabling this option will perform a smooth scrolling effect on every page (except on Mac and touch devices)', 'conall' ),
                'parent'        => $panel_settings
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'smooth_page_transitions',
                'type'          => 'yesno',
                'default_value' => 'no',
                'label' => esc_html__( 'Smooth Page Transitions', 'conall' ),
                'description' => esc_html__( 'Enabling this option will perform a smooth transition between pages when clicking on links.', 'conall' ),
                'parent'        => $panel_settings,
                'args'          => array(
                    "dependence" => true,
                    "dependence_hide_on_yes" => "",
                    "dependence_show_on_yes" => "#edgtf_page_transitions_container"
                )
            )
        );

        $page_transitions_container = conall_edge_add_admin_container(
            array(
                'parent'            => $panel_settings,
                'name'              => 'page_transitions_container',
                'hidden_property'   => 'smooth_page_transitions',
                'hidden_value'      => 'no'
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'smooth_pt_bgnd_color',
                'type'          => 'color',
                'label' => esc_html__( 'Page Loader Background Color', 'conall' ),
                //'description' => esc_html__( 'Enabling this option will perform a smooth transition between pages when clicking on links.', 'conall' ),
                'parent'        => $page_transitions_container
            )
        );

        $group_pt_spinner_animation = conall_edge_add_admin_group(array(
            'name'          => 'group_pt_spinner_animation',
            'title' => esc_html__( 'Loader Style', 'conall' ),
            'description' => esc_html__( 'Define styles for loader spinner animation', 'conall' ),
            'parent'        => $page_transitions_container
        ));

        $row_pt_spinner_animation = conall_edge_add_admin_row(array(
            'name'      => 'row_pt_spinner_animation',
            'parent'    => $group_pt_spinner_animation
        ));

        conall_edge_add_admin_field(array(
            'type'          => 'selectsimple',
            'name'          => 'smooth_pt_spinner_type',
            'default_value' => '',
            'label' => esc_html__( 'Spinner Type', 'conall' ),
            'parent'        => $row_pt_spinner_animation,
            'options'       => array(
                "pulse" => esc_html__( "Pulse", 'conall' ),
                "double_pulse" => esc_html__( "Double Pulse", 'conall' ),
                "cube" => esc_html__( "Cube", 'conall' ),
                "rotating_cubes" => esc_html__( "Rotating Cubes", 'conall' ),
                "stripes" => esc_html__( "Stripes", 'conall' ),
                "wave" => esc_html__( "Wave", 'conall' ),
                "two_rotating_circles" => esc_html__( "2 Rotating Circles", 'conall' ),
                "five_rotating_circles" => esc_html__( "5 Rotating Circles", 'conall' ),
                "atom" => esc_html__( "Atom", 'conall' ),
                "clock" => esc_html__( "Clock", 'conall' ),
                "mitosis" => esc_html__( "Mitosis", 'conall' ),
                "lines" => esc_html__( "Lines", 'conall' ),
                "fussion" => esc_html__( "Fussion", 'conall' ),
                "wave_circles" => esc_html__( "Wave Circles", 'conall' ),
                "pulse_circles" => esc_html__( "Pulse Circles", 'conall' )
            )
        ));

        conall_edge_add_admin_field(array(
            'type'          => 'colorsimple',
            'name'          => 'smooth_pt_spinner_color',
            'default_value' => '',
            'label' => esc_html__( 'Spinner Color', 'conall' ),
            'parent'        => $row_pt_spinner_animation
        ));

        conall_edge_add_admin_field(
            array(
                'name'          => 'show_back_button',
                'type'          => 'yesno',
                'default_value' => 'yes',
                'label' => esc_html__( 'Show Back To Top Button', 'conall' ),
                'description' => esc_html__( 'Enabling this option will display a Back to Top button on every page', 'conall' ),
                'parent'        => $panel_settings
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'responsiveness',
                'type'          => 'yesno',
                'default_value' => 'yes',
                'label' => esc_html__( 'Responsiveness', 'conall' ),
                'description' => esc_html__( 'Enabling this option will make all pages responsive', 'conall' ),
                'parent'        => $panel_settings
            )
        );

        $panel_custom_code = conall_edge_add_admin_panel(
            array(
                'page'  => '',
                'name'  => 'panel_custom_code',
                'title' => esc_html__( 'Custom Code', 'conall' )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'custom_css',
                'type'          => 'textarea',
                'label' => esc_html__( 'Custom CSS', 'conall' ),
                'description' => esc_html__( 'Enter your custom CSS here', 'conall' ),
                'parent'        => $panel_custom_code
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'custom_js',
                'type'          => 'textarea',
                'label' => esc_html__( 'Custom JS', 'conall' ),
                'description' => esc_html__( 'Enter your custom Javascript here', 'conall' ),
                'parent'        => $panel_custom_code
            )
        );

        $panel_google_maps_api_key = conall_edge_add_admin_panel(
            array(
                'page'  => '',
                'name'  => 'google_maps_api_key',
                'title' => esc_html__( 'Google Maps API key', 'conall' )
            )
        );

        conall_edge_add_admin_field(
            array(
                'name'          => 'google_maps_api_key',
                'type'          => 'text',
                'label' => esc_html__( 'Google Maps API key', 'conall' ),
                'description' => esc_html__( 'Enter your Google Maps API key here', 'conall' ),
                'parent'        => $panel_google_maps_api_key
            )
        );

    }

    add_action( 'conall_edge_options_map', 'conall_edge_general_options_map', 1);

}