<?php

$title_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('page', 'portfolio-item', 'post'),
        'title' => esc_html__( 'Title', 'conall' ),
        'name' => 'title_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_show_title_area_meta',
            'type' => 'select',
            'default_value' => '',
            'label' => esc_html__( 'Show Title Area', 'conall' ),
            'description' => esc_html__( 'Disabling this option will turn off page title area', 'conall' ),
            'parent' => $title_meta_box,
            'options' => array(
                '' => '',
                'no' => esc_html__( 'No', 'conall' ),
                'yes' => esc_html__( 'Yes', 'conall' )
            ),
            'args' => array(
                "dependence" => true,
                "hide" => array(
                    "" => "",
                    "no" => "#edgtf_edgtf_show_title_area_meta_container",
                    "yes" => ""
                ),
                "show" => array(
                    "" => "#edgtf_edgtf_show_title_area_meta_container",
                    "no" => "",
                    "yes" => "#edgtf_edgtf_show_title_area_meta_container"
                )
            )
        )
    );

    $show_title_area_meta_container = conall_edge_add_admin_container(
        array(
            'parent' => $title_meta_box,
            'name' => 'edgtf_show_title_area_meta_container',
            'hidden_property' => 'edgtf_show_title_area_meta',
            'hidden_value' => 'no'
        )
    );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_type_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Title Area Type', 'conall' ),
                'description' => esc_html__( 'Choose title type', 'conall' ),
                'parent' => $show_title_area_meta_container,
                'options' => array(
                    '' => '',
                    'standard' => esc_html__( 'Standard', 'conall' ),
                    'breadcrumb' => esc_html__( 'Breadcrumb', 'conall' )
                ),
                'args' => array(
                    "dependence" => true,
                    "hide" => array(
                        "standard" => "",
                        "breadcrumb" => "#edgtf_edgtf_title_area_type_meta_container"
                    ),
                    "show" => array(
                        "" => "#edgtf_edgtf_title_area_type_meta_container",
                        "standard" => "#edgtf_edgtf_title_area_type_meta_container",
                        "breadcrumb" => ""
                    )
                )
            )
        );

        $title_area_type_meta_container = conall_edge_add_admin_container(
            array(
                'parent' => $show_title_area_meta_container,
                'name' => 'edgtf_title_area_type_meta_container',
                'hidden_property' => 'edgtf_title_area_type_meta',
                'hidden_value' => '',
                'hidden_values' => array('breadcrumb'),
            )
        );

            conall_edge_create_meta_box_field(
                array(
                    'name' => 'edgtf_title_area_enable_breadcrumbs_meta',
                    'type' => 'select',
                    'default_value' => '',
                    'label' => esc_html__( 'Enable Breadcrumbs', 'conall' ),
                    'description' => esc_html__( 'This option will display Breadcrumbs in Title Area', 'conall' ),
                    'parent' => $title_area_type_meta_container,
                    'options' => array(
                        '' => '',
                        'no' => esc_html__( 'No', 'conall' ),
                        'yes' => esc_html__( 'Yes', 'conall' )
                    ),
                )
            );

            conall_edge_create_meta_box_field(
                array(
                    'name' => 'edgtf_title_predefined_size_meta',
                    'type' => 'select',
                    'default_value' => '',
                    'label' => esc_html__( 'Predefined Title Size', 'conall' ),
                    'description' => esc_html__( 'Choose Title Predefined size', 'conall' ),
                    'parent' => $title_area_type_meta_container,
                    'options' => array(
                        '' => '',
                        'small' => esc_html__( 'Small', 'conall' ),
                        'medium' => esc_html__( 'Medium', 'conall' ),
                        'large' => esc_html__( 'Large', 'conall' )
                    )
                )
            );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_animation_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Animations', 'conall' ),
                'description' => esc_html__( 'Choose an animation for Title Area', 'conall' ),
                'parent' => $show_title_area_meta_container,
                'options' => array(
                    '' => '',
                    'no' => esc_html__( 'No Animation', 'conall' ),
                    'right-left' => esc_html__( 'Text right to left', 'conall' ),
                    'left-right' => esc_html__( 'Text left to right', 'conall' )
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_vertial_alignment_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Vertical Alignment', 'conall' ),
                'description' => esc_html__( 'Specify title vertical alignment', 'conall' ),
                'parent' => $show_title_area_meta_container,
                'options' => array(
                    '' => '',
                    'header_bottom' => esc_html__( 'From Bottom of Header', 'conall' ),
                    'window_top' => esc_html__( 'From Window Top', 'conall' )
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_content_alignment_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Horizontal Alignment', 'conall' ),
                'description' => esc_html__( 'Specify title horizontal alignment', 'conall' ),
                'parent' => $show_title_area_meta_container,
                'options' => array(
                    '' => '',
                    'left' => esc_html__( 'Left', 'conall' ),
                    'center' => esc_html__( 'Center', 'conall' ),
                    'right' => esc_html__( 'Right', 'conall' )
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_text_color_meta',
                'type' => 'color',
                'label' => esc_html__( 'Title Color', 'conall' ),
                'description' => esc_html__( 'Choose a color for title text', 'conall' ),
                'parent' => $show_title_area_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_breadcrumb_color_meta',
                'type' => 'color',
                'label' => esc_html__( 'Breadcrumb Color', 'conall' ),
                'description' => esc_html__( 'Choose a color for breadcrumb text', 'conall' ),
                'parent' => $show_title_area_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_background_color_meta',
                'type' => 'color',
                'label' => esc_html__( 'Background Color', 'conall' ),
                'description' => esc_html__( 'Choose a background color for Title Area', 'conall' ),
                'parent' => $show_title_area_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_hide_background_image_meta',
                'type' => 'yesno',
                'default_value' => 'no',
                'label' => esc_html__( 'Hide Background Image', 'conall' ),
                'description' => esc_html__( 'Enable this option to hide background image in Title Area', 'conall' ),
                'parent' => $show_title_area_meta_container,
                'args' => array(
                    "dependence" => true,
                    "dependence_hide_on_yes" => "#edgtf_edgtf_hide_background_image_meta_container",
                    "dependence_show_on_yes" => ""
                )
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_background_overlay_meta',
                'type' => 'yesno',
                'label' => esc_html__( 'Background Gradient Overlay', 'conall' ),
                'description' => esc_html__( 'Place gradient overlay for Title Area', 'conall' ),
                'parent' => $show_title_area_meta_container
            )
        );

        $hide_background_image_meta_container = conall_edge_add_admin_container(
            array(
                'parent' => $show_title_area_meta_container,
                'name' => 'edgtf_hide_background_image_meta_container',
                'hidden_property' => 'edgtf_hide_background_image_meta',
                'hidden_value' => 'yes'
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_background_image_meta',
                'type' => 'image',
                'label' => esc_html__( 'Background Image', 'conall' ),
                'description' => esc_html__( 'Choose an Image for Title Area', 'conall' ),
                'parent' => $hide_background_image_meta_container
            )
        );

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_title_area_background_image_responsive_meta',
                'type' => 'select',
                'default_value' => '',
                'label' => esc_html__( 'Background Responsive Image', 'conall' ),
                'description' => esc_html__( 'Enabling this option will make Title background image responsive', 'conall' ),
                'parent' => $hide_background_image_meta_container,
                'options' => array(
                    '' => '',
                    'no' => esc_html__( 'No', 'conall' ),
                    'yes' => esc_html__( 'Yes', 'conall' )
                ),
                'args' => array(
                    "dependence" => true,
                    "hide" => array(
                        "" => "",
                        "no" => "",
                        "yes" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta"
                    ),
                    "show" => array(
                        "" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta",
                        "no" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta",
                        "yes" => ""
                    )
                )
            )
        );

        $title_area_background_image_responsive_meta_container = conall_edge_add_admin_container(
            array(
                'parent' => $hide_background_image_meta_container,
                'name' => 'edgtf_title_area_background_image_responsive_meta_container',
                'hidden_property' => 'edgtf_title_area_background_image_responsive_meta',
                'hidden_value' => 'yes'
            )
        );

            conall_edge_create_meta_box_field(
                array(
                    'name' => 'edgtf_title_area_background_image_parallax_meta',
                    'type' => 'select',
                    'default_value' => '',
                    'label' => esc_html__( 'Background Image in Parallax', 'conall' ),
                    'description' => esc_html__( 'Enabling this option will make Title background image parallax', 'conall' ),
                    'parent' => $title_area_background_image_responsive_meta_container,
                    'options' => array(
                        '' => '',
                        'no' => esc_html__( 'No', 'conall' ),
                        'yes' => esc_html__( 'Yes', 'conall' ),
                        'yes_zoom' => esc_html__( 'Yes, with zoom out', 'conall' )
                    )
                )
            );

        conall_edge_create_meta_box_field(array(
            'name' => 'edgtf_title_area_height_meta',
            'type' => 'text',
            'label' => esc_html__( 'Height', 'conall' ),
            'description' => esc_html__( 'Set a height for Title Area', 'conall' ),
            'parent' => $show_title_area_meta_container,
            'args' => array(
                'col_width' => 2,
                'suffix' => 'px'
            )
        ));

        conall_edge_create_meta_box_field(array(
            'name' => 'edgtf_title_area_subtitle_meta',
            'type' => 'text',
            'default_value' => '',
            'label' => esc_html__( 'Subtitle Text', 'conall' ),
            'description' => esc_html__( 'Enter your subtitle text', 'conall' ),
            'parent' => $show_title_area_meta_container,
            'args' => array(
                'col_width' => 6
            )
        ));

        conall_edge_create_meta_box_field(
            array(
                'name' => 'edgtf_subtitle_color_meta',
                'type' => 'color',
                'label' => esc_html__( 'Subtitle Color', 'conall' ),
                'description' => esc_html__( 'Choose a color for subtitle text', 'conall' ),
                'parent' => $show_title_area_meta_container
            )
        );