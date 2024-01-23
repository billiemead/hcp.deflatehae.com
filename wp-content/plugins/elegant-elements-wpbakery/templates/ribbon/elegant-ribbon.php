<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-ribbon' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-ribbon-wrapper' ) . '>';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-ribbon-text' ) . '>';
$html .= esc_attr( $this->args['ribbon_text'] );
$html .= '</span>';
if ( 'style04' === $this->args['style'] ) {
	$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-ribbon-arrow' ) . '>';
	$html .= '<svg class="ribbon-triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="15" viewBox="0 0 100 100" preserveAspectRatio="none" style="fill: ' . $this->args['background_color'] . ';padding: 0;display: block;"><path d="M-1 -1 L50 99 L101 -1 Z"></path></svg>';
	$html .= '</span>';
}
$html .= '</div>';
$html .= '</div>';
