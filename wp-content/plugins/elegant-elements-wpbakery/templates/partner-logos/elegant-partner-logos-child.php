<?php
$image     = wp_get_attachment_image_src( $this->child_args['image_url'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$link   = vc_build_link( $this->child_args['url'] );
$url    = esc_url( $link['url'] );
$target = ( isset( $link['target'] ) ) ? trim( $link['target'] ) : '';

$this->partner_logos[ $this->partner_logos_counter ][] = array(
	'image_url'    => $image_url,
	'title'        => $this->child_args['title'],
	'click_action' => $this->child_args['click_action'],
	'modal_anchor' => $this->child_args['modal_anchor'],
	'url'          => $url,
	'target'       => $target,
);
