<?php

//Slider

$slider_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('slides'),
        'title' => esc_html__( 'Slide Background Type', 'conall' ),
        'name' => 'slides_type'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'          => 'edgtf_slide_background_type',
            'type'          => 'imagevideo',
            'default_value' => 'image',
            'label' => esc_html__( 'Slide Background Type', 'conall' ),
            'description' => esc_html__( 'Do you want to upload an image or video?', 'conall' ),
            'parent'        => $slider_meta_box,
            'args' => array(
                "dependence" => true,
                "dependence_hide_on_yes" => "#edgtf-meta-box-edgtf_slides_video_settings",
                "dependence_show_on_yes" => "#edgtf-meta-box-edgtf_slides_image_settings"
            )
        )
    );


//Slide Image

$slider_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('slides'),
        'title' => esc_html__( 'Slide Background Image', 'conall' ),
        'name' => 'edgtf_slides_image_settings',
        'hidden_property' => 'edgtf_slide_background_type',
        'hidden_values' => array('video')
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_image',
            'type'        => 'image',
            'label' => esc_html__( 'Slide Image', 'conall' ),
            'description' => esc_html__( 'Choose background image', 'conall' ),
            'parent'      => $slider_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_overlay_image',
            'type'        => 'image',
            'label' => esc_html__( 'Overlay Image', 'conall' ),
            'description' => esc_html__( 'Choose overlay image (pattern) for background image', 'conall' ),
            'parent'      => $slider_meta_box
        )
    );


//Slide Video

$video_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('slides'),
        'title' => esc_html__( 'Slide Background Video', 'conall' ),
        'name' => 'edgtf_slides_video_settings',
        'hidden_property' => 'edgtf_slide_background_type',
        'hidden_values' => array('image')
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_video_webm',
            'type'        => 'text',
            'label' => esc_html__( 'Video - webm', 'conall' ),
            'description' => esc_html__( 'Path to the webm file that you have previously uploaded in Media Section', 'conall' ),
            'parent'      => $video_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_video_mp4',
            'type'        => 'text',
            'label' => esc_html__( 'Video - mp4', 'conall' ),
            'description' => esc_html__( 'Path to the mp4 file that you have previously uploaded in Media Section', 'conall' ),
            'parent'      => $video_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_video_ogv',
            'type'        => 'text',
            'label' => esc_html__( 'Video - ogv', 'conall' ),
            'description' => esc_html__( 'Path to the ogv file that you have previously uploaded in Media Section', 'conall' ),
            'parent'      => $video_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_video_image',
            'type'        => 'image',
            'label' => esc_html__( 'Video Preview Image', 'conall' ),
            'description' => esc_html__( 'Choose background image that will be visible until video is loaded. This image will be shown on touch devices too.', 'conall' ),
            'parent'      => $video_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_slide_video_overlay',
            'type' => 'yesempty',
            'default_value' => '',
            'label' => esc_html__( 'Video Overlay Image', 'conall' ),
            'description' => esc_html__( 'Do you want to have a overlay image on video?', 'conall' ),
            'parent' => $video_meta_box,
            'args' => array(
                "dependence" => true,
                "dependence_hide_on_yes" => "",
                "dependence_show_on_yes" => "#edgtf_edgtf_slide_video_overlay_container"
            )
        )
    );

    $slide_video_overlay_container = conall_edge_add_admin_container(array(
        'name' => 'edgtf_slide_video_overlay_container',
        'parent' => $video_meta_box,
        'hidden_property' => 'edgtf_slide_video_overlay',
        'hidden_values' => array('','no')
    ));

        conall_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_slide_video_overlay_image',
                'type'        => 'image',
                'label' => esc_html__( 'Overlay Image', 'conall' ),
                'description' => esc_html__( 'Choose overlay image (pattern) for background video.', 'conall' ),
                'parent'      => $slide_video_overlay_container
            )
        );


//Slide Elements

$elements_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('slides'),
        'title' => esc_html__( 'Slide Elements', 'conall' ),
        'name' => 'edgtf_slides_elements'
    )
);

    conall_edge_add_admin_section_title(
        array(
            'parent' => $elements_meta_box,
            'name' => 'edgtf_slides_elements_frame',
            'title' => esc_html__( 'Elements Holder Frame', 'conall' )
        )
    );

    conall_edge_add_slide_holder_frame_scheme(
        array(
            'parent' => $elements_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_holder_elements_alignment',
            'type'        => 'select',
            'label' => esc_html__( 'Elements Alignment', 'conall' ),
            'description' => esc_html__( 'How elements are aligned with respect to the Holder Frame', 'conall' ),
            'parent'      => $elements_meta_box,
            'default_value' => 'center',
            'options' => array(
                "center" => esc_html__( "Center", 'conall' ),
                "left" => esc_html__( "Left", 'conall' ),
                "right" => esc_html__( "Right", 'conall' ),
                "custom" => esc_html__( "Custom", 'conall' )
            ),
            'args'        => array(
                "dependence" => true,
                "hide" => array(
                    "center" => "#edgtf_edgtf_slide_holder_frame_height",
                    "left" => "#edgtf_edgtf_slide_holder_frame_height",
                    "right" => "#edgtf_edgtf_slide_holder_frame_height",
                    "custom" => ""
                ),
                "show" => array(
                    "center" => "",
                    "left" => "",
                    "right" => "",
                    "custom" => "#edgtf_edgtf_slide_holder_frame_height"
                )
            )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_slide_holder_frame_in_grid',
            'type'        => 'select',
            'label' => esc_html__( 'Holder Frame in Grid?', 'conall' ),
            'description' => esc_html__( 'Whether to keep the holder frame width the same as that of the grid.', 'conall' ),
            'parent'      => $elements_meta_box,
            'default_value' => 'no',
            'options' => array(
                "yes" => esc_html__( "Yes", 'conall' ),
                "no" => esc_html__( "No", 'conall' )
            ),
            'args'        => array(
                "dependence" => true,
                "hide" => array(
                    "yes" => "#edgtf_edgtf_slide_holder_frame_width, #edgtf_edgtf_holder_frame_responsive_container",
                    "no" => ""
                ),
                "show" => array(
                    "yes" => "",
                    "no" => "#edgtf_edgtf_slide_holder_frame_width, #edgtf_edgtf_holder_frame_responsive_container"
                )
            )
        )
    );

    $holder_frame = conall_edge_add_admin_group(array(
        'title' => esc_html__( 'Holder Frame Properties', 'conall' ),
        'description' => esc_html__( 'The frame is always positioned centrally on the slide. All elements are positioned and sized relatively to the holder frame. Refer to the scheme above.', 'conall' ),
        'name' => 'edgtf_holder_frame',
        'parent' => $elements_meta_box
    ));

        $row1 = conall_edge_add_admin_row(array(
            'name' => 'row1',
            'parent' => $holder_frame
        ));

            $holder_frame_width = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width',
                    'type'        => 'textsimple',
                    'label' => esc_html__( 'Relative width (C/A*100)', 'conall' ),
                    'parent'      => $row1,
                    'hidden_property' => 'edgtf_slide_holder_frame_in_grid',
                    'hidden_values' => array('yes')
                )
            );

            $holder_frame_height = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_height',
                    'type'        => 'textsimple',
                    'label' => esc_html__( 'Height to width ratio (D/C*100)', 'conall' ),
                    'parent'      => $row1,
                    'hidden_property' => 'edgtf_slide_holder_elements_alignment',
                    'hidden_values' => array('center', 'left', 'right')
                )
            );

    $holder_frame_responsive_container = conall_edge_add_admin_container(array(
        'name' => 'edgtf_holder_frame_responsive_container',
        'parent' => $elements_meta_box,
        'hidden_property' => 'edgtf_slide_holder_frame_in_grid',
        'hidden_values' => array('yes')
    ));

    $holder_frame_responsive = conall_edge_add_admin_group(array(
        'title' => esc_html__( 'Responsive Relative Width', 'conall' ),
        'description' => esc_html__( 'Enter different relative widths of the holder frame for each responsive stage. Leave blank to have the frame width scale proportionally to the screen size.', 'conall' ),
        'name' => 'edgtf_holder_frame_responsive',
        'parent' => $holder_frame_responsive_container
    ));
    
    $screen_widths_holder_frame = array(
        // These values must match those in edgt.layout.inc, slider.php and shortcodes.js
        "mobile" => 600,
        "tabletp" => 800,
        "tabletl" => 1024,
        "laptop" => 1440
    );

        $row2 = conall_edge_add_admin_row(array(
            'name' => 'row2',
            'parent' => $holder_frame_responsive
        ));

            $holder_frame_width = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width_mobile',
                    'type'        => 'textsimple',
                    'label'       => sprintf( esc_html__( 'Mobile (up to %dpx)', 'conall' ),$screen_widths_holder_frame["mobile"] ),
                    'parent'      => $row2
                )
            );

            $holder_frame_height = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width_tablet_p',
                    'type'        => 'textsimple',
                    'label'       => sprintf( esc_html__( 'Tablet - Portrait (%dpx - %dpx)', 'conall' ),$screen_widths_holder_frame["mobile"] + 1,$screen_widths_holder_frame["tabletp"] ),
                    'parent'      => $row2
                )
            );

            $holder_frame_height = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width_tablet_l',
                    'type'        => 'textsimple',
                    'label'       => sprintf( esc_html__( 'Tablet - Landscape (%dpx - %dpx)', 'conall' ),$screen_widths_holder_frame["tabletp"] + 1,$screen_widths_holder_frame["tabletl"] ),
                    'parent'      => $row2
                )
            );

        $row3 = conall_edge_add_admin_row(array(
            'name' => 'row3',
            'parent' => $holder_frame_responsive
        ));

            $holder_frame_width = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width_laptop',
                    'type'        => 'textsimple',
                    'label'       => sprintf( esc_html__( 'Laptop (%dpx - %dpx)', 'conall' ),$screen_widths_holder_frame["tabletl"] + 1,$screen_widths_holder_frame["laptop"] ),
                    'parent'      => $row3
                )
            );

            $holder_frame_height = conall_edge_create_meta_box_field(
                array(
                    'name'        => 'edgtf_slide_holder_frame_width_desktop',
                    'type'        => 'textsimple',
                    'label'       => sprintf( esc_html__( 'Desktop (above %dpx)', 'conall' ),$screen_widths_holder_frame["laptop"] ),
                    'parent'      => $row3
                )
            );

    conall_edge_create_meta_box_field(
        array(
            'parent' => $elements_meta_box,
            'type' => 'text',
            'name' => 'edgtf_slide_elements_default_width',
            'label' => esc_html__( 'Default Screen Width in px (A)', 'conall' ),
            'description' => esc_html__( 'All elements marked as responsive scale at the ratio of the actual screen width to this screen width. Default is 1920px.', 'conall' )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'parent' => $elements_meta_box,
            'type' => 'select',
            'name' => 'edgtf_slide_elements_default_animation',
            'default_value' => 'none',
            'label' => esc_html__( 'Default Elements Animation', 'conall' ),
            'description' => esc_html__( 'This animation will be applied to all elements except those with their own animation settings.', 'conall' ),
            'options' => array(
                "none" => esc_html__( "No Animation", 'conall' ),
                "flip" => esc_html__( "Flip", 'conall' ),
                "spin" => esc_html__( "Spin", 'conall' ),
                "fade" => esc_html__( "Fade In", 'conall' ),
                "from_bottom" => esc_html__( "Fly In From Bottom", 'conall' ),
                "from_top" => esc_html__( "Fly In From Top", 'conall' ),
                "from_left" => esc_html__( "Fly In From Left", 'conall' ),
                "from_right" => esc_html__( "Fly In From Right", 'conall' )
            )
        )
    );

    conall_edge_add_admin_section_title(
        array(
            'parent' => $elements_meta_box,
            'name' => 'edgtf_slides_elements_list',
            'title' => esc_html__( 'Elements', 'conall' )
        )
    );

    $slide_elements = conall_edge_add_slide_elements_framework(
        array(
            'parent' => $elements_meta_box,
            'name' => 'edgtf_slides_elements_holder'
        )
    );

//Slide Behaviour

$behaviours_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('slides'),
        'title' => esc_html__( 'Slide Behaviours', 'conall' ),
        'name' => 'edgtf_slides_behaviour_settings'
    )
);  

    conall_edge_add_admin_section_title(
        array(
            'parent' => $behaviours_meta_box,
            'name' => 'edgtf_header_styling_title',
            'title' => esc_html__( 'Header', 'conall' )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'parent' => $behaviours_meta_box,
            'type' => 'selectblank',
            'name' => 'edgtf_slide_header_style',
            'default_value' => '',
            'label' => esc_html__( 'Header Style', 'conall' ),
            'description' => esc_html__( 'Header style will be applied when this slide is in focus', 'conall' ),
            'options' => array(
                "light" => esc_html__( "Light", 'conall' ),
                "dark" => esc_html__( "Dark", 'conall' )
            )
        )
    );

    conall_edge_add_admin_section_title(
        array(
            'parent' => $behaviours_meta_box,
            'name' => 'edgtf_image_animation_title',
            'title' => esc_html__( 'Slide Image Animation', 'conall' )
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_enable_image_animation',
            'type' => 'yesno',
            'default_value' => 'no',
            'label' => esc_html__( 'Enable Image Animation', 'conall' ),
            'description' => esc_html__( 'Enabling this option will turn on a motion animation on the slide image', 'conall' ),
            'parent' => $behaviours_meta_box,
            'args' => array(
                "dependence" => true,
                "dependence_hide_on_yes" => "",
                "dependence_show_on_yes" => "#edgtf_edgtf_enable_image_animation_container"
            )
        )
    );

    $enable_image_animation_container = conall_edge_add_admin_container(array(
        'name' => 'edgtf_enable_image_animation_container',
        'parent' => $behaviours_meta_box,
        'hidden_property' => 'edgtf_enable_image_animation',
        'hidden_value' => 'no'
    ));

        conall_edge_create_meta_box_field(
            array(
                'parent' => $enable_image_animation_container,
                'type' => 'select',
                'name' => 'edgtf_enable_image_animation_type',
                'default_value' => 'zoom_center',
                'label' => esc_html__( 'Animation Type', 'conall' ),
                'options' => array(
                    "zoom_center" => esc_html__( "Zoom In Center", 'conall' ),
                    "zoom_top_left" => esc_html__( "Zoom In to Top Left", 'conall' ),
                    "zoom_top_right" => esc_html__( "Zoom In to Top Right", 'conall' ),
                    "zoom_bottom_left" => esc_html__( "Zoom In to Bottom Left", 'conall' ),
                    "zoom_bottom_right" => esc_html__( "Zoom In to Bottom Right", 'conall' )
                )
            )
        );