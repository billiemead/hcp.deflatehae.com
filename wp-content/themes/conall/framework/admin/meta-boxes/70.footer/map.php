<?php

$footer_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('page', 'portfolio-item', 'post'),
        'title' => esc_html__( 'Footer', 'conall' ),
        'name' => 'footer_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name' => 'edgtf_disable_footer_meta',
            'type' => 'yesno',
            'default_value' => 'no',
            'label' => esc_html__( 'Disable Footer for this Page', 'conall' ),
            'description' => esc_html__( 'Enabling this option will hide footer on this page', 'conall' ),
            'parent' => $footer_meta_box,
        )
    );