<?php
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-gradient-heading-wrapper' ) . '>';

if ( isset( $this->args['heading'] ) && '' !== $this->args['heading'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-gradient-heading' ) . '>' . $this->args['heading'] . '</' . $this->args['heading_size'] . '>';
}

$html .= '</div>';
