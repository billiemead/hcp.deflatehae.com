<?php

if ( ! function_exists('conall_edge_page_options_map') ) {

    function conall_edge_page_options_map() {

        conall_edge_add_admin_page(
            array(
                'slug'  => '_page_page',
                'title' => esc_html__( 'Page', 'conall' ),
                'icon'  => 'fa fa-institution'
            )
        );

        $custom_sidebars = conall_edge_get_custom_sidebars();

        $panel_sidebar = conall_edge_add_admin_panel(
            array(
                'page'  => '_page_page',
                'name'  => 'panel_sidebar',
                'title' => esc_html__( 'Design Style', 'conall' )
            )
        );

        conall_edge_add_admin_field(array(
            'name'        => 'page_sidebar_layout',
            'type'        => 'select',
            'label' => esc_html__( 'Sidebar Layout', 'conall' ),
            'description' => esc_html__( 'Choose a sidebar layout for pages', 'conall' ),
            'default_value' => 'default',
            'parent'      => $panel_sidebar,
            'options'     => array(
                'default' => esc_html__( 'No Sidebar', 'conall' ),
                'sidebar-33-right' => esc_html__( 'Sidebar 1/3 Right', 'conall' ),
                'sidebar-25-right' => esc_html__( 'Sidebar 1/4 Right', 'conall' ),
                'sidebar-33-left' => esc_html__( 'Sidebar 1/3 Left', 'conall' ),
                'sidebar-25-left' => esc_html__( 'Sidebar 1/4 Left', 'conall' )
            )
        ));


        if(count($custom_sidebars) > 0) {
            conall_edge_add_admin_field(array(
                'name' => 'page_custom_sidebar',
                'type' => 'selectblank',
                'label' => esc_html__( 'Sidebar to Display', 'conall' ),
                'description' => esc_html__( 'Choose a sidebar to display on pages. Default sidebar is Sidebar', 'conall' ),
                'parent' => $panel_sidebar,
                'options' => $custom_sidebars
            ));
        }

        conall_edge_add_admin_field(array(
            'name'        => 'page_show_comments',
            'type'        => 'yesno',
            'label' => esc_html__( 'Show Comments', 'conall' ),
            'description' => esc_html__( 'Enabling this option will show comments on your page', 'conall' ),
            'default_value' => 'yes',
            'parent'      => $panel_sidebar
        ));
    }

    add_action( 'conall_edge_options_map', 'conall_edge_page_options_map', 8);
}