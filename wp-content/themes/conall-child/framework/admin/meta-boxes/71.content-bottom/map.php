<?php

$content_bottom_meta_box = conall_edge_create_meta_box(
	array(
		'scope' => array('page', 'portfolio-item', 'post'),
		'title' => esc_html__( 'Content Bottom', 'conall' ),
		'name' => 'content_bottom_meta'
	)
);

	conall_edge_create_meta_box_field(
		array(
			'name' => 'edgtf_enable_content_bottom_area_meta',
			'type' => 'selectblank',
			'default_value' => '',
			'label' => esc_html__( 'Enable Content Bottom Area', 'conall' ),
			'description' => esc_html__( 'This option will enable Content Bottom area on pages', 'conall' ),
			'parent' => $content_bottom_meta_box,
			'options' => array(
				'no' => esc_html__( 'No', 'conall' ),
				'yes' => esc_html__( 'Yes', 'conall' )
			),
			'args' => array(
				'dependence' => true,
				'hide' => array(
					'' => '#edgtf_edgtf_show_content_bottom_meta_container',
					'no' => '#edgtf_edgtf_show_content_bottom_meta_container'
				),
				'show' => array(
					'yes' => '#edgtf_edgtf_show_content_bottom_meta_container'
				)
			)
		)
	);

	$show_content_bottom_meta_container = conall_edge_add_admin_container(
		array(
			'parent' => $content_bottom_meta_box,
			'name' => 'edgtf_show_content_bottom_meta_container',
			'hidden_property' => 'edgtf_enable_content_bottom_area_meta',
			'hidden_value' => '',
			'hidden_values' => array('','no')
		)
	);

		conall_edge_create_meta_box_field(
			array(
				'name' => 'edgtf_content_bottom_sidebar_custom_display_meta',
				'type' => 'selectblank',
				'default_value' => '',
				'label' => esc_html__( 'Sidebar to Display', 'conall' ),
				'description' => esc_html__( 'Choose a Content Bottom sidebar to display', 'conall' ),
				'options' => conall_edge_get_custom_sidebars(),
				'parent' => $show_content_bottom_meta_container
			)
		);

		conall_edge_create_meta_box_field(
			array(
				'type' => 'selectblank',
				'name' => 'edgtf_content_bottom_in_grid_meta',
				'default_value' => '',
				'label' => esc_html__( 'Display in Grid', 'conall' ),
				'description' => esc_html__( 'Enabling this option will place Content Bottom in grid', 'conall' ),
				'options' => array(
					'no' => esc_html__( 'No', 'conall' ),
					'yes' => esc_html__( 'Yes', 'conall' )
				),
				'parent' => $show_content_bottom_meta_container
			)
		);

		conall_edge_create_meta_box_field(
			array(
				'type' => 'color',
				'name' => 'edgtf_content_bottom_background_color_meta',
				'default_value' => '',
				'label' => esc_html__( 'Background Color', 'conall' ),
				'description' => esc_html__( 'Choose a background color for Content Bottom area', 'conall' ),
				'parent' => $show_content_bottom_meta_container
			)
		);

        conall_edge_create_meta_box_field(
            array(
                'type' => 'image',
                'name' => 'edgtf_content_bottom_background_image_meta',
                'default_value' => '',
                'label' => esc_html__( 'Background Image', 'conall' ),
                'description' => esc_html__( 'Choose a background image for Content Bottom area', 'conall' ),
                'parent' => $show_content_bottom_meta_container
            )
        );