<?php

if ( ! function_exists('conall_edge_content_options_map') ) {

    function conall_edge_content_options_map() {

        conall_edge_add_admin_page(
            array(
                'slug'  => '_content_page',
                'title' => esc_html__( 'Content', 'conall' ),
                'icon'  => 'fa fa-file-text-o'
            )
        );

        $panel_content = conall_edge_add_admin_panel(
            array(
                'page'  => '_content_page',
                'name'  => 'panel_content',
                'title' => esc_html__( 'General', 'conall' )
            )
        );

        conall_edge_add_admin_field(array(
            'type'			=> 'text',
            'name'			=> 'content_top_padding',
            'description' => esc_html__( 'Enter top padding for content area for templates in full width. If you set this value then it is important to set also Content top padding for mobile header value', 'conall' ),
            'default_value'	=> '0',
            'label' => esc_html__( 'Content Top Padding for Template in Full Width', 'conall' ),
            'args'			=> array(
                'suffix'	=> 'px',
                'col_width' => 3
            ),
            'parent'		=> $panel_content
        ));

        conall_edge_add_admin_field(array(
            'type'			=> 'text',
            'name'			=> 'content_top_padding_in_grid',
            'description' => esc_html__( 'Enter top padding for content area for Templates in grid. If you set this value then it is important to set also Content top padding for mobile header value', 'conall' ),
            'default_value'	=> '40',
            'label' => esc_html__( 'Content Top Padding for Templates in Grid', 'conall' ),
            'args'			=> array(
                'suffix'	=> 'px',
                'col_width' => 3
            ),
            'parent'		=> $panel_content
        ));

        conall_edge_add_admin_field(array(
            'type'			=> 'text',
            'name'			=> 'content_top_padding_mobile',
            'description' => esc_html__( 'Enter top padding for content area for Mobile Header', 'conall' ),
            'default_value'	=> '0',
            'label' => esc_html__( 'Content Top Padding for Mobile Header', 'conall' ),
            'args'			=> array(
                'suffix'	=> 'px',
                'col_width' => 3
            ),
            'parent'		=> $panel_content
        ));

    }

    add_action( 'conall_edge_options_map', 'conall_edge_content_options_map', 10 );

}