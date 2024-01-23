<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-blob-shape-image-wrapper' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-blob-shape-image' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-blob-shape-image-background' ) . '></div>';

if ( '' !== $content ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-blob-shape-image-content' ) . '>';
	$html .= wpautop( do_shortcode( $content ) );
	$html .= '</div>';
}

if ( '' !== $this->args['link_url'] ) {
	$link   = vc_build_link( $this->args['link_url'] );
	$url    = esc_url( $link['url'] );
	$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';
	$html  .= '<a class="elegant-blog-link"  style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" href="' . $url . '"' . $target . '></a>';
}

$html .= '</div>';
$html .= '</div>';
