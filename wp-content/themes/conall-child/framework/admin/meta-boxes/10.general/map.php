<?php

    $general_meta_box = conall_edge_create_meta_box(
        array(
            'scope' => array('page', 'portfolio-item', 'post'),
            'title' => esc_html__( 'General', 'conall' ),
            'name' => 'general_meta'
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_page_background_color_meta',
            'type' => 'color',
            'default_value' => '',
            'label' => esc_html__( 'Page Background Color', 'conall' ),
            'description' => esc_html__( 'Choose background color for page content', 'conall' ),
            'parent' => $general_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_page_slider_meta',
            'type' => 'text',
            'default_value' => '',
            'label' => esc_html__( 'Slider Shortcode', 'conall' ),
            'description' => esc_html__( 'Paste your slider shortcode here', 'conall' ),
            'parent' => $general_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_page_slider_meta_position',
            'type'        => 'select',
            'label' => esc_html__( 'Set Slider Shortcode to Start Behind Header', 'conall' ),
            'parent'      => $general_meta_box,
            'options'     => array(
                'no' => esc_html__( 'No', 'conall' ),
                'yes' => esc_html__( 'Yes', 'conall' ),
            )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_page_transition_type',
            'type'        => 'selectblank',
            'label' => esc_html__( 'Page Transition', 'conall' ),
            'description' => esc_html__( 'Choose the type of transition to this page', 'conall' ),
            'parent'      => $general_meta_box,
            'default_value' => '',
            'options'     => array(
                'no-animation' => esc_html__( 'No animation', 'conall' ),
                'fade' => esc_html__( 'Fade', 'conall' )
            )
        )
    );

    $edgtf_content_padding_group = conall_edge_add_admin_group(array(
        'name' => 'content_padding_group',
        'title' => esc_html__( 'Content Style', 'conall' ),
        'description' => esc_html__( 'Define styles for Content area', 'conall' ),
        'parent' => $general_meta_box
    ));

    $edgtf_content_padding_row = conall_edge_add_admin_row(array(
        'name' => 'edgtf_content_padding_row',
        'next' => true,
        'parent' => $edgtf_content_padding_group
    ));

    conall_edge_create_meta_box_field(
        array(
            'name'          => 'edgtf_page_content_top_padding',
            'type'          => 'textsimple',
            'default_value' => '',
            'label' => esc_html__( 'Content Top Padding', 'conall' ),
            'parent'        => $edgtf_content_padding_row,
            'args'          => array(
                'suffix' => 'px'
            )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_page_content_top_padding_mobile',
            'type'        => 'selectblanksimple',
            'label' => esc_html__( 'Set this top padding for mobile header', 'conall' ),
            'parent'      => $edgtf_content_padding_row,
            'options'     => array(
                'yes' => esc_html__( 'Yes', 'conall' ),
                'no' => esc_html__( 'No', 'conall' ),
            )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_page_comments_meta',
            'type'        => 'selectblank',
            'label' => esc_html__( 'Show Comments', 'conall' ),
            'description' => esc_html__( 'Enabling this option will show comments on your page', 'conall' ),
            'parent'      => $general_meta_box,
            'options'     => array(
                'yes' => esc_html__( 'Yes', 'conall' ),
                'no' => esc_html__( 'No', 'conall' ),
            )
        )
    );

    conall_edge_create_meta_box_field(array(
        'name'        => 'edgtf_enable_full_screen_content',
        'type'        => 'yesno',
        'label' => esc_html__( 'Full Screen Content', 'conall' ),
        'description' => esc_html__( 'Enabling this option will set full screen content for Coming Soon Template', 'conall' ),
        'default_value' => 'no',
        'parent'      => $general_meta_box,
        'args' => array(
            "dependence" => true,
            "hide" => array(
                "no" => "#edgtf_edgtf_full_screen_content_container",
                "yes" => ""
            ),
            "show" => array(
                "no" => "",
                "yes" => "#edgtf_edgtf_full_screen_content_container"
            )
        )
    ));

        $full_screen_content_container = conall_edge_add_admin_container(
            array(
                'parent' => $general_meta_box,
                'name' => 'edgtf_full_screen_content_container',
                'hidden_property' => 'edgtf_enable_full_screen_content',
                'hidden_value' => 'no',
            )
        );

            conall_edge_create_meta_box_field(
                array(
                    'parent' => $full_screen_content_container,
                    'type' => 'image',
                    'name' => 'edgtf_full_screen_content_background_image',
                    'default_value' => '',
                    'label' => esc_html__( 'Background Image', 'conall' ),
                    'description' => esc_html__( 'Choose a background image for coming soon page content', 'conall' )
                )
            );

            conall_edge_create_meta_box_field(
                array(
                    'parent' => $full_screen_content_container,
                    'type' => 'select',
                    'name' => 'edgtf_full_screen_content_vertical_alignment',
                    'default_value' => '',
                    'label' => esc_html__( 'Vertical Alignment', 'conall' ),
                    'description' => esc_html__( 'Specify content elements vertical alignment', 'conall' ),
                    'options'     => array(
                        '' => esc_html__( 'Default', 'conall' ),
                        'middle' => esc_html__( 'Middle', 'conall' ),
                    )
                )
            );