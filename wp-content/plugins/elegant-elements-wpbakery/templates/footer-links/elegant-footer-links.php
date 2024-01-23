<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-footer-links' ) . '>';

if ( '' !== $this->args['links_heading'] ) {
	$html .= '<h3 ' . Elegant_Elements_WPBakery::attributes( 'elegant-footer-links-heading' ) . '>' . $this->args['links_heading'] . '</h3>';
}

$html .= '<ul ' . Elegant_Elements_WPBakery::attributes( 'elegant-footer-link-items' ) . '>';
$html .= $this->args['link_list'];
$html .= '</ul>';
$html .= '</div>';
