<?php
$icon_display = $this->args['icon_display'];

$icon_html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-icon-block-icon-wrapper' ) . '>';
$icon_html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-icon-block-icon' ) . '>';
$icon_html .= '</span>';
$icon_html .= '</div>';

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-icon-block' ) . '>';

if ( 'top' === $icon_display ) {
	$html .= $icon_html;
}

$html .= '<div class="elegant-icon-block-title-wrapper">';
$html .= '<h3 ' . Elegant_Elements_WPBakery::attributes( 'elegant-icon-block-title' ) . '>' . $this->args['title'] . '</h3>';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-icon-block-description' ) . '>';
$html .= '<p>' . $this->args['description'] . '</p>';
$html .= '</div>';

if ( 'top' !== $icon_display ) {
	$html .= $icon_html;
}

if ( isset( $this->args['link'] ) && '' !== $this->args['link'] ) {
	$link   = vc_build_link( $this->args['link'] );
	$url    = esc_url( $link['url'] );
	$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

	$html .= '<a class="icon-block-link" href="' . $url . '"' . $target . '></a>';
}

$html .= '</div>';
