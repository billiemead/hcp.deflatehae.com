<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-advanced-video' ) . '>';
$html .= '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-advanced-video-image' ) . ' />';

// Add play icon.
$html .= '<div class="elegant-advanced-video-play-button">';

if ( 'icon' === $this->args['icon_type'] ) {
	$html .= '<i ' . Elegant_Elements_WPBakery::attributes( 'elegant-advanced-video-icon' ) . '></i>';
} elseif ( '' !== $this->args['image_icon'] ) {
	$image     = wp_get_attachment_image_src( $this->args['image_icon'], 'full' );
	$image_url = $image[0];
	$image_url = esc_url( $image_url );

	$html .= '<img src=" ' . $image_url . '" />';
}
$html .= '</div>';
$html .= '<div class="elegant-advanced-video-overlay" style="background:' . $this->args['image_overlay'] . ';"></div>';
$html .= '</div>';
$html .= '<div class="elegant-clearfix"></div>';
