<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-distortion-hover-image-wrapper' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-distortion-hover-image' ) . '></div>';

if ( '' !== $content ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-distortion-hover-content' ) . '>';
	$html .= wpautop( do_shortcode( $content ) );
	$html .= '</div>';
}

$html .= '</div>';
