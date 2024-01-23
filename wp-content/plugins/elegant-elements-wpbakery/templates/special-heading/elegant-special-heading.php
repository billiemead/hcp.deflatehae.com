<?php
$additional_content = do_shortcode( $content );

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-special-heading' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-special-heading-wrapper' ) . '>';

if ( 'above_heading' === $this->args['additional_content_position'] ) {
	$html .= $additional_content;
}

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-special-heading-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( 'after_heading' === $this->args['additional_content_position'] ) {
	$html .= $additional_content;
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-special-heading-description' ) . '>' . $this->args['description'] . '</p>';
}

if ( 'after_decription' === $this->args['additional_content_position'] ) {
	$html .= $additional_content;
}

$html .= '</div>';
$html .= '</div>';
