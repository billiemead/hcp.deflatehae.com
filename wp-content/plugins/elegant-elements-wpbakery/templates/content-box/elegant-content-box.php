<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box' ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-icon-wrapper' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-icon' ) . '>';

if ( 'icon' === $this->args['icon_type'] ) {
	$html .= '<i class="' . $this->args['icon'] . '"></i>';
} else {
	$image     = wp_get_attachment_image_src( $this->args['image_icon'], 'full' );
	$image_url = $image[0];
	$image_url = esc_url( $image_url );

	$html .= '<img src="' . $image_url . '" />';
}

$html .= '</div>';
$html .= '</div>'; // Icon wrapper.

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-content' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-heading' ) . '>';
$html .= '<h3 class="elegant-content-box-title">' . $this->args['heading_text'] . '</h3>';
$html .= '</div>';

// Content.
if ( '' !== trim( $content ) ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-description' ) . '>';
	$html .= do_shortcode( wpautop( $content ) );
	$html .= '</div>';
}

if ( 'none' !== $this->args['link_type'] ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-box-link' ) . '>';

	if ( 'button' === $this->args['link_type'] ) {
		$button_shortcode = rawurldecode( base64_decode( $this->args['button_shortcode'] ) ); // @codingStandardsIgnoreLine
		$html            .= do_shortcode( $button_shortcode );
	}

	if ( 'text' === $this->args['link_type'] ) {
		$link   = vc_build_link( $this->args['link_url'] );
		$url    = esc_url( $link['url'] );
		$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

		$html .= '<a href="' . $url . '"' . $target . '>' . $this->args['link_text'] . '</a>';
	}

	if ( 'box' === $this->args['link_type'] ) {
		$link   = vc_build_link( $this->args['link_url'] );
		$url    = esc_url( $link['url'] );
		$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

		$html .= '<a class="elegant-box-link" href="' . $url . '"' . $target . '></a>';
	}

	$html .= '</div>';
}

$html .= '</div>';

$html .= '<style type="text/css" scoped="true">';
$html .= '.elegant-content-box.elegant-content-box-' . $this->content_box_counter . ':hover .elegant-content-box-icon {';

if ( '' !== $this->args['icon_color_hover'] ) {
	$html .= 'color:' . $this->args['icon_color_hover'] . ' !important;';
}

if ( '' !== $this->args['icon_bg_color_hover'] ) {
	$html .= 'background-color:' . $this->args['icon_bg_color_hover'] . ' !important;';
}

if ( '' !== $this->args['icon_border_color_hover'] ) {
	$html .= 'border-color:' . $this->args['icon_border_color_hover'] . ' !important;';
}

$html .= '}</style>';

$html .= '</div>';
