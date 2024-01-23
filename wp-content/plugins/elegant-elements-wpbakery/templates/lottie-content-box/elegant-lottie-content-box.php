<?php
$button_shortcode = ( '' !== $this->args['element_content'] ) ? rawurldecode( base64_decode( $this->args['element_content'] ) ) : ''; // @codingStandardsIgnoreLine

$link   = vc_build_link( $this->args['link_url'] );
$url    = esc_url( $link['url'] );
$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box' ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box-icon' ) . '>';
$html .= '<lottie-player ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-icon-player' ) . '></lottie-player>';
$html .= '</div>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box-content' ) . '>';

$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box-heading' ) . '>';
$html .= $this->args['heading_text'];
$html .= '</' . $this->args['heading_size'] . '>';

$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box-description' ) . '>';
$html .= $this->args['description_text'];
$html .= '</p>';

// Content Box Button.
if ( 'button' === $this->args['link_type'] ) {
	$html .= do_shortcode( $button_shortcode );
}

// Content Box Text Link.
if ( 'text' === $this->args['link_type'] ) {
	$html .= '<a ' . Elegant_Elements_WPBakery::attributes( 'elegant-lottie-content-box-link-text' ) . '>' . $this->args['link_text'] . '</a>';
}

$html .= '</div>';
$html .= '</div>';

// Content Box Link.
if ( 'content' === $this->args['link_type'] ) {
	$html = '<a href="' . $url . '" target="' . $target . '">' . $html . '</a>';
}
