<?php

do_shortcode( $content );

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-rotating-text-container' ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-rotating-text' ) . '>';

if ( isset( $this->args['prefix'] ) && '' !== $this->args['prefix'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-rotating-text-prefix' ) . '>';
	$html .= $this->args['prefix'] . '&nbsp;';
	$html .= '</p>';
}

$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-rotating-text-child' ) . '>';
foreach ( $this->rotating_text[ $this->rotating_text_counter ] as $key => $rotating_text ) {
	$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-rotating-text-wrap', $rotating_text ) . '>' . $rotating_text['title'] . '</span>';
}
$html .= '</p>';

$html .= '</div>';
$html .= '</div>';
