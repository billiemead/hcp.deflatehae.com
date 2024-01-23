<?php
$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-separator' ) . '>';
$html .= '<img src="' . $image_url . '">';
$html .= '</div>';
