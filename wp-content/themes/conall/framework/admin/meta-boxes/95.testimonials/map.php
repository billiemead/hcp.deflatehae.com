<?php

//Testimonials

$testimonial_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('testimonials'),
        'title' => esc_html__( 'Testimonial', 'conall' ),
        'name' => 'testimonial_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'        	=> 'edgtf_testimonial_title',
            'type'        	=> 'text',
            'label' => esc_html__( 'Title', 'conall' ),
            'description' => esc_html__( 'Enter testimonial title', 'conall' ),
            'parent'      	=> $testimonial_meta_box,
        )
    );


    conall_edge_create_meta_box_field(
        array(
            'name'        	=> 'edgtf_testimonial_author',
            'type'        	=> 'text',
            'label' => esc_html__( 'Author', 'conall' ),
            'description' => esc_html__( 'Enter author name', 'conall' ),
            'parent'      	=> $testimonial_meta_box,
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        	=> 'edgtf_testimonial_author_position',
            'type'        	=> 'text',
            'label' => esc_html__( 'Job Position', 'conall' ),
            'description' => esc_html__( 'Enter job position', 'conall' ),
            'parent'      	=> $testimonial_meta_box,
        )
    );

    conall_edge_create_meta_box_field(
        array(
            'name'        	=> 'edgtf_testimonial_text',
            'type'        	=> 'text',
            'label' => esc_html__( 'Text', 'conall' ),
            'description' => esc_html__( 'Enter testimonial text', 'conall' ),
            'parent'      	=> $testimonial_meta_box,
        )
    );