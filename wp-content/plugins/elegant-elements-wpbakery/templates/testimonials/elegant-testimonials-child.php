<?php
$image     = wp_get_attachment_image_src( $this->child_args['image_url'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$this->testimonials[ $this->testimonials_counter ][] = array(
	'title'           => isset( $this->child_args['title'] ) ? $this->child_args['title'] : '',
	'title_color'     => isset( $this->child_args['title_color'] ) && '' !== $this->child_args['title_color'] ? $this->child_args['title_color'] : '',
	'sub_title'       => isset( $this->child_args['sub_title'] ) ? $this->child_args['sub_title'] : '',
	'sub_title_color' => isset( $this->child_args['sub_title_color'] ) && '' !== $this->child_args['sub_title_color'] ? $this->child_args['sub_title_color'] : '',
	'content'         => do_shortcode( $content ),
	'image_url'       => isset( $this->child_args['image_url'] ) ? $image_url : '',
);
