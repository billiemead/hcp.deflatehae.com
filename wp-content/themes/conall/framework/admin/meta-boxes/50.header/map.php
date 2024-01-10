<?php

$header_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('page', 'portfolio-item', 'post'),
        'title' => esc_html__( 'Header', 'conall' ),
        'name' => 'header_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_header_type_meta',
            'type' => 'select',
            'default_value' => '',
            'label' => esc_html__( 'Choose Header Type', 'conall' ),
            'description' => esc_html__( 'Select header type layout', 'conall' ),
            'parent' => $header_meta_box,
            'options' => array(
                '' => esc_html__( 'Default', 'conall' ),
                'header-standard' => esc_html__( 'Standard Header Layout', 'conall' ),
                'header-simple' => esc_html__( 'Simple Header Layout', 'conall' ),
                'header-classic' => esc_html__( 'Classic Header Layout', 'conall' ),
                'header-divided' => esc_html__( 'Divided Header Layout', 'conall' ),
                'header-full-screen' => esc_html__( 'Full Screen Header Layout', 'conall' )
            ),
            'args' => array(
                "dependence" => true,
                "hide" => array(
                    "" => '#edgtf_edgtf_header_standard_type_meta_container, #edgtf_edgtf_header_simple_type_meta_container, #edgtf_edgtf_header_classic_type_meta_container, #edgtf_edgtf_header_divided_type_meta_container, #edgtf_edgtf_header_full_screen_type_meta_container',
                    "header-standard" => '#edgtf_edgtf_header_simple_type_meta_container, #edgtf_edgtf_header_divided_type_meta_container, #edgtf_edgtf_header_classic_type_meta_container, #edgtf_edgtf_header_full_screen_type_meta_container',
                    "header-simple" => '#edgtf_edgtf_header_standard_type_meta_container, #edgtf_edgtf_header_divided_type_meta_container, #edgtf_edgtf_header_classic_type_meta_container, #edgtf_edgtf_header_full_screen_type_meta_container',
                    "header-classic" => '#edgtf_edgtf_header_standard_type_meta_container, #edgtf_edgtf_header_simple_type_meta_container, #edgtf_edgtf_header_divided_type_meta_container, #edgtf_edgtf_header_full_screen_type_meta_container',
                    "header-divided" => '#edgtf_edgtf_header_standard_type_meta_container, #edgtf_edgtf_header_simple_type_meta_container, #edgtf_edgtf_header_classic_type_meta_container, #edgtf_edgtf_header_full_screen_type_meta_container',
                    "header-full-screen" => '#edgtf_edgtf_header_standard_type_meta_container, #edgtf_edgtf_header_simple_type_meta_container, #edgtf_edgtf_header_classic_type_meta_container, #edgtf_edgtf_header_divided_type_meta_container'
                ),
                "show" => array(
                    "" => '',
                    "header-standard" => '#edgtf_edgtf_header_standard_type_meta_container',
                    "header-simple" => '#edgtf_edgtf_header_simple_type_meta_container',
                    "header-classic" => '#edgtf_edgtf_header_classic_type_meta_container',
                    "header-divided" => '#edgtf_edgtf_header_divided_type_meta_container',
                    "header-full-screen" => '#edgtf_edgtf_header_full_screen_type_meta_container'
                )
            )
        )
    );

    $header_standard_type_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $header_meta_box,
            'name' => 'edgtf_header_standard_type_meta_container',
            'hidden_property' => 'edgtf_header_type_meta',
            'hidden_values' => array(
                'header-simple',
                'header-classic',
                'header-divided',
                'header-full-screen'
            ),
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_color_header_standard_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for header area', 'conall' ),
                'parent' => $header_standard_type_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_transparency_header_standard_meta',
                'type' => 'text',
                'label' => esc_html__( 'Background Transparency', 'conall' ),
                'description' => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'conall' ),
                'parent' => $header_standard_type_meta_container,
                'args' => array(
                    'col_width' => 2
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_border_bottom_color_header_standard_meta',
                'type' => 'color',
                'label' => esc_html__( 'Border Bottom Color', 'conall' ),
                'description' => esc_html__( 'Choose a border bottom color for header area', 'conall' ),
                'parent' => $header_standard_type_meta_container
            )
        );

    $header_simple_type_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $header_meta_box,
            'name' => 'edgtf_header_simple_type_meta_container',
            'hidden_property' => 'edgtf_header_type_meta',
            'hidden_values' => array(
                'header-standard',
                'header-classic',
                'header-divided',
                'header-full-screen'
            ),
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_enable_grid_layout_header_simple_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Enable Grid Layout', 'conall' ),
                'description' => esc_html__( 'Enabling this option you will set simple header area to be in grid', 'conall' ),
                'parent' => $header_simple_type_meta_container,
                'options' => array(
                    '' => '',
                    'no' => esc_html__( 'No', 'conall' ),
                    'yes' => esc_html__( 'Yes', 'conall' ),
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_color_header_simple_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for header area', 'conall' ),
                'parent' => $header_simple_type_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_transparency_header_simple_meta',
                'type' => 'text',
                'label' => esc_html__( 'Background Transparency', 'conall' ),
                'description' => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'conall' ),
                'parent' => $header_simple_type_meta_container,
                'args' => array(
                    'col_width' => 2
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_border_bottom_color_header_simple_meta',
                'type' => 'color',
                'label' => esc_html__( 'Border Bottom Color', 'conall' ),
                'description' => esc_html__( 'Choose a border bottom color for header area', 'conall' ),
                'parent' => $header_simple_type_meta_container
            )
        );

    $header_classic_type_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $header_meta_box,
            'name' => 'edgtf_header_classic_type_meta_container',
            'hidden_property' => 'edgtf_header_type_meta',
            'hidden_values' => array(
                'header-standard',
                'header-simple',
                'header-divided',
                'header-full-screen'
            ),
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_color_header_classic_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for header area', 'conall' ),
                'parent' => $header_classic_type_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_transparency_header_classic_meta',
                'type' => 'text',
                'label' => esc_html__( 'Background Transparency', 'conall' ),
                'description' => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'conall' ),
                'parent' => $header_classic_type_meta_container,
                'args' => array(
                    'col_width' => 2
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_border_bottom_color_header_classic_meta',
                'type' => 'color',
                'label' => esc_html__( 'Border Bottom Color', 'conall' ),
                'description' => esc_html__( 'Choose a border bottom color for header area', 'conall' ),
                'parent' => $header_classic_type_meta_container
            )
        );    

    $header_divided_type_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $header_meta_box,
            'name' => 'edgtf_header_divided_type_meta_container',
            'hidden_property' => 'edgtf_header_type_meta',
            'hidden_values' => array(
                'header-standard',
                'header-simple',
                'header-classic',
                'header-full-screen'
            ),
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_enable_grid_layout_header_divided_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Enable Grid Layout', 'conall' ),
                'description' => esc_html__( 'Enabling this option you will set divided header area to be in grid', 'conall' ),
                'parent' => $header_divided_type_meta_container,
                'options' => array(
                    '' => '',
                    'no' => esc_html__( 'No', 'conall' ),
                    'yes' => esc_html__( 'Yes', 'conall' ),
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_color_header_divided_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for header area', 'conall' ),
                'parent' => $header_divided_type_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_transparency_header_divided_meta',
                'type' => 'text',
                'label' => esc_html__( 'Background Transparency', 'conall' ),
                'description' => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'conall' ),
                'parent' => $header_divided_type_meta_container,
                'args' => array(
                    'col_width' => 2
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_border_bottom_color_header_divided_meta',
                'type' => 'color',
                'label' => esc_html__( 'Border Bottom Color', 'conall' ),
                'description' => esc_html__( 'Choose a border bottom color for header area', 'conall' ),
                'parent' => $header_divided_type_meta_container
            )
        );    

    $header_full_screen_type_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $header_meta_box,
            'name' => 'edgtf_header_full_screen_type_meta_container',
            'hidden_property' => 'edgtf_header_type_meta',
            'hidden_values' => array(
                'header-standard',
                'header-simple',
                'header-classic',
                'header-divided'
            ),
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_enable_grid_layout_header_full_screen_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Enable Grid Layout', 'conall' ),
                'description' => esc_html__( 'Enabling this option you will set full screen header area to be in grid', 'conall' ),
                'parent' => $header_full_screen_type_meta_container,
                'options' => array(
                    '' => '',
                    'no' => esc_html__( 'No', 'conall' ),
                    'yes' => esc_html__( 'Yes', 'conall' ),
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_color_header_full_screen_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for header area', 'conall' ),
                'parent' => $header_full_screen_type_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_background_transparency_header_full_screen_meta',
                'type' => 'text',
                'label' => esc_html__( 'Background Transparency', 'conall' ),
                'description' => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'conall' ),
                'parent' => $header_full_screen_type_meta_container,
                'args' => array(
                    'col_width' => 2
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_menu_area_border_bottom_color_header_full_screen_meta',
                'type' => 'color',
                'label' => esc_html__( 'Border Bottom Color', 'conall' ),
                'description' => esc_html__( 'Choose a border bottom color for header area', 'conall' ),
                'parent' => $header_full_screen_type_meta_container
            )
        );  

        conall_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_scroll_amount_for_sticky_meta',
                'type'            => 'text',
                'label' => esc_html__( 'Scroll amount for sticky header appearance', 'conall' ),
                'description' => esc_html__( 'Define scroll amount for sticky header appearance', 'conall' ),
                'parent'          => $header_meta_box,
                'args'            => array(
                    'col_width' => 2,
                    'suffix'    => 'px'
                ),
                'hidden_property' => 'header_behaviour',
                'hidden_values'   => array("sticky-header-on-scroll-up", "fixed-on-scroll")
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_header_style_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Header Skin', 'conall' ),
                'description' => esc_html__( 'Choose a header style to make header elements (logo, main menu, side menu button) in that predefined style', 'conall' ),
                'parent' => $header_meta_box,
                'options' => array(
                    '' => '',
                    'light-header' => esc_html__( 'Light', 'conall' ),
                    'dark-header' => esc_html__( 'Dark', 'conall' )
                )
            )
        );