<?php
$link   = vc_build_link( $this->args['link_url'] );
$url    = esc_url( $link['url'] );
$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

$image = '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-retina-image-src' ) . ' />';
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-retina-image' ) . '>';

if ( '' !== $this->args['link_url'] ) {
	$html .= '<a href="' . $url . '"' . $target . '>';
	$html .= $image;
	$html .= '</a>';
} else {
	$html .= $image;
}

$html .= '</div>';
$html .= '<div class="elegant-clearfix"></div>';
