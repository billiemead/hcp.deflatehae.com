<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-mask-heading-wrapper' ) . '>';

if ( isset( $this->args['heading'] ) && '' !== $this->args['heading'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-mask-heading' ) . '><span>' . $this->args['heading'] . '</span></' . $this->args['heading_size'] . '>';
}

$html .= '</div>';
