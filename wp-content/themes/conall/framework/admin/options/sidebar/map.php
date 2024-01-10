<?php

if ( ! function_exists('conall_edge_sidebar_options_map') ) {

	function conall_edge_sidebar_options_map() {

		conall_edge_add_admin_page(
			array(
				'slug'  => '_sidebar_page',
				'title' => esc_html__( 'Sidebar', 'conall' ),
				'icon'  => 'fa fa-bars'
			)
		);

		$panel_widgets = conall_edge_add_admin_panel(
			array(
				'page'  => '_sidebar_page',
				'name'  => 'panel_widgets',
				'title' => esc_html__( 'Widgets', 'conall' )
			)
		);

		/**
		 * Navigation style
		 */
		conall_edge_add_admin_field(array(
			'type'			=> 'color',
			'name'			=> 'sidebar_background_color',
			'default_value'	=> '',
			'label' => esc_html__( 'Sidebar Background Color', 'conall' ),
			'description' => esc_html__( 'Choose background color for sidebar', 'conall' ),
			'parent'		=> $panel_widgets
		));

		$group_sidebar_padding = conall_edge_add_admin_group(array(
			'name'		=> 'group_sidebar_padding',
			'title' => esc_html__( 'Padding', 'conall' ),
			'parent'	=> $panel_widgets
		));

		$row_sidebar_padding = conall_edge_add_admin_row(array(
			'name'		=> 'row_sidebar_padding',
			'parent'	=> $group_sidebar_padding
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_top',
			'default_value'	=> '',
			'label' => esc_html__( 'Top Padding', 'conall' ),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_right',
			'default_value'	=> '',
			'label' => esc_html__( 'Right Padding', 'conall' ),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_bottom',
			'default_value'	=> '',
			'label' => esc_html__( 'Bottom Padding', 'conall' ),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_left',
			'default_value'	=> '',
			'label' => esc_html__( 'Left Padding', 'conall' ),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'select',
			'name'			=> 'sidebar_alignment',
			'default_value'	=> '',
			'label' => esc_html__( 'Text Alignment', 'conall' ),
			'description' => esc_html__( 'Choose text aligment', 'conall' ),
			'options'		=> array(
				'left' => esc_html__( 'Left', 'conall' ),
				'center' => esc_html__( 'Center', 'conall' ),
				'right' => esc_html__( 'Right', 'conall' )
			),
			'parent'		=> $panel_widgets
		));

	}

	add_action( 'conall_edge_options_map', 'conall_edge_sidebar_options_map', 9);
}