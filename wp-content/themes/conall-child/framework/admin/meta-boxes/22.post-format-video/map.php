<?php

/*** Video Post Format ***/

$video_post_format_meta_box = conall_edge_create_meta_box(
	array(
		'scope' =>	array('post'),
		'title' => esc_html__( 'Video Post Format', 'conall' ),
		'name' 	=> 'post_format_video_meta'
	)
);

conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_video_type_meta',
		'type'        => 'select',
		'label' => esc_html__( 'Video Type', 'conall' ),
		'description' => esc_html__( 'Choose video type', 'conall' ),
		'parent'      => $video_post_format_meta_box,
		'default_value' => 'youtube',
		'options'     => array(
			'youtube' => esc_html__( 'Youtube', 'conall' ),
			'vimeo' => esc_html__( 'Vimeo', 'conall' ),
			'self' => esc_html__( 'Self Hosted', 'conall' )
		),
		'args' => array(
		'dependence' => true,
		'hide' => array(
			'youtube' => '#edgtf_edgtf_video_self_hosted_container',
			'vimeo' => '#edgtf_edgtf_video_self_hosted_container',
			'self' => '#edgtf_edgtf_video_embedded_container'
		),
		'show' => array(
			'youtube' => '#edgtf_edgtf_video_embedded_container',
			'vimeo' => '#edgtf_edgtf_video_embedded_container',
			'self' => '#edgtf_edgtf_video_self_hosted_container')
	)
	)
);

$edgtf_video_embedded_container = conall_edge_add_admin_container(
	array(
		'parent' => $video_post_format_meta_box,
		'name' => 'edgtf_video_embedded_container',
		'hidden_property' => 'edgtf_video_type_meta',
		'hidden_value' => 'self'
	)
);

$edgtf_video_self_hosted_container = conall_edge_add_admin_container(
	array(
		'parent' => $video_post_format_meta_box,
		'name' => 'edgtf_video_self_hosted_container',
		'hidden_property' => 'edgtf_video_type_meta',
		'hidden_values' => array('youtube', 'vimeo')
	)
);



conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_video_id_meta',
		'type'        => 'text',
		'label' => esc_html__( 'Video ID', 'conall' ),
		'description' => esc_html__( 'Enter Video ID', 'conall' ),
		'parent'      => $edgtf_video_embedded_container,

	)
);


conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_video_image_meta',
		'type'        => 'image',
		'label' => esc_html__( 'Video Image', 'conall' ),
		'description' => esc_html__( 'Upload video image', 'conall' ),
		'parent'      => $edgtf_video_self_hosted_container,

	)
);

conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_video_webm_link_meta',
		'type'        => 'text',
		'label' => esc_html__( 'Video WEBM', 'conall' ),
		'description' => esc_html__( 'Enter video URL for WEBM format', 'conall' ),
		'parent'      => $edgtf_video_self_hosted_container,

	)
);

conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_video_mp4_link_meta',
		'type'        => 'text',
		'label' => esc_html__( 'Video MP4', 'conall' ),
		'description' => esc_html__( 'Enter video URL for MP4 format', 'conall' ),
		'parent'      => $edgtf_video_self_hosted_container,

	)
);

conall_edge_create_meta_box_field(
	array(
		'name'        => 'edgtf_post_video_ogv_link_meta',
		'type'        => 'text',
		'label' => esc_html__( 'Video OGV', 'conall' ),
		'description' => esc_html__( 'Enter video URL for OGV format', 'conall' ),
		'parent'      => $edgtf_video_self_hosted_container,

	)
);