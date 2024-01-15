<?php

/*** Link Post Format ***/

$link_post_format_meta_box = conall_edge_create_meta_box(
	array(
		'scope' => array('post'),
		'title' => esc_html__( 'Link Post Format', 'conall' ),
		'name' => 'post_format_link_meta'
	)
);

conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_link_link_meta',
		'type'        => 'text',
		'label' => esc_html__( 'Link', 'conall' ),
		'description' => esc_html__( 'Enter link', 'conall' ),
		'parent'      => $link_post_format_meta_box,

	)
);

