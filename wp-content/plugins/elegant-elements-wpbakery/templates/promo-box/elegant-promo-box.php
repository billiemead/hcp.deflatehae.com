<?php
$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-promo-box' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-promo-box-image-wrapper' ) . '>';
$html .= '<img src="' . $image_url . '" />';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-promo-box-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-promo-box-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-promo-box-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
