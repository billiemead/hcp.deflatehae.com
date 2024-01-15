<?php

//Carousels

$carousel_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('carousels'),
        'title' => esc_html__( 'Carousel', 'conall' ),
        'name' => 'carousel_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_carousel_image',
            'type'        => 'image',
            'label' => esc_html__( 'Carousel Image', 'conall' ),
            'description' => esc_html__( 'Choose carousel image (min width needs to be 215px)', 'conall' ),
            'parent'      => $carousel_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_carousel_hover_image',
            'type'        => 'image',
            'label' => esc_html__( 'Carousel Hover Image', 'conall' ),
            'description' => esc_html__( 'Choose carousel hover image (min width needs to be 215px)', 'conall' ),
            'parent'      => $carousel_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_carousel_item_link',
            'type'        => 'text',
            'label' => esc_html__( 'Link', 'conall' ),
            'description' => esc_html__( 'Enter the URL to which you want the image to link to (e.g. http://www.example.com)', 'conall' ),
            'parent'      => $carousel_meta_box
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_carousel_item_target',
            'type'        => 'selectblank',
            'label' => esc_html__( 'Target', 'conall' ),
            'description' => esc_html__( 'Specify where to open the linked document', 'conall' ),
            'parent'      => $carousel_meta_box,
            'options' => array(
            	'_self' => esc_html__( 'Self', 'conall' ),
            	'_blank' => esc_html__( 'Blank', 'conall' )
        	)
        )
    );