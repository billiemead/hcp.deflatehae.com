<?php
$link   = vc_build_link( $this->args['link_url'] );
$url    = esc_url( $link['url'] );
$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cards' ) . '>';

if ( 'card' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
	$html .= '<a href="' . $url . '"' . $target . '>';
}

if ( isset( $this->args['image'] ) && '' !== $this->args['image'] ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cards-image-wrapper' ) . '>';

	$image     = wp_get_attachment_image_src( $this->args['image'], 'full' );
	$image_url = $image[0];
	$image_url = esc_url( $image_url );

	if ( 'image' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
		$html .= '<a href="' . $url . '"' . $target . '>';
		$html .= '<img src="' . $image_url . '" />';
		$html .= '</a>';
	} else {
		$html .= '<img src="' . $image_url . '" />';
	}

	$html .= '</div>';
}

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cards-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-cards-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-cards-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $this->content );
$html .= '</div>';

if ( 'card' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
	$html .= '</a>';
}

$html .= '</div>';
