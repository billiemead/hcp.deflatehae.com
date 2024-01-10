<?php

if(!function_exists('conall_edge_map_portfolio_settings')) {
    function conall_edge_map_portfolio_settings() {
        $meta_box = conall_edge_create_meta_box(array(
            'scope' => 'portfolio-item',
            'title' => esc_html__( 'Portfolio Settings', 'conall' ),
            'name'  => 'portfolio_settings_meta_box'
        ));

        conall_edge_create_meta_box_field(array(
            'name'        => 'edgtf_portfolio_single_template_meta',
            'type'        => 'select',
            'label' => esc_html__( 'Portfolio Type', 'conall' ),
            'description' => esc_html__( 'Choose a default type for Single Project pages', 'conall' ),
            'parent'      => $meta_box,
            'options'     => array(
                '' => esc_html__( 'Default', 'conall' ),
                'small-images' => esc_html__( 'Portfolio small images', 'conall' ),
                'small-slider' => esc_html__( 'Portfolio small slider', 'conall' ),
                'big-images' => esc_html__( 'Portfolio big images', 'conall' ),
                'big-slider' => esc_html__( 'Portfolio big slider', 'conall' ),
                'custom' => esc_html__( 'Portfolio custom', 'conall' ),
                'full-width-custom' => esc_html__( 'Portfolio full width custom', 'conall' ),
                'gallery' => esc_html__( 'Portfolio gallery', 'conall' )
            )
        ));

        $all_pages = array();
        $pages     = get_pages();
        foreach($pages as $page) {
            $all_pages[$page->ID] = $page->post_title;
        }

        conall_edge_create_meta_box_field(array(
            'name'        => 'portfolio_single_back_to_link',
            'type'        => 'select',
            'label' => esc_html__( 'Back To Link', 'conall' ),
            'description' => esc_html__( 'Choose Back To page to link from portfolio Single Project page', 'conall' ),
            'parent'      => $meta_box,
            'options'     => $all_pages
        ));

        conall_edge_create_meta_box_field(array(
            'name'        => 'portfolio_external_link',
            'type'        => 'text',
            'label' => esc_html__( 'Portfolio External Link', 'conall' ),
            'description' => esc_html__( 'Enter URL to link from Portfolio List page', 'conall' ),
            'parent'      => $meta_box,
            'args'        => array(
                'col_width' => 3
            )
        ));

        conall_edge_create_meta_box_field(array(
            'name'        => 'portfolio_masonry_dimenisions',
            'type'        => 'select',
            'label' => esc_html__( 'Dimensions for Masonry', 'conall' ),
            'description' => esc_html__( 'Choose image layout when it appears in Masonry type portfolio lists', 'conall' ),
            'parent'      => $meta_box,
            'options'     => array(
                'default' => esc_html__( 'Default', 'conall' ),
                'large_width' => esc_html__( 'Large width', 'conall' ),
                'large_height' => esc_html__( 'Large height', 'conall' ),
                'large_width_height' => esc_html__( 'Large width/height', 'conall' )
            )
        ));
    }

    add_action('conall_edge_meta_boxes_map', 'conall_edge_map_portfolio_settings');
}