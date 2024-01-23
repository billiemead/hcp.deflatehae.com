<?php
$button_1 = ( isset( $this->args['button_1'] ) && '' !== $this->args['button_1'] ) ? rawurldecode( base64_decode( $this->args['button_1'] ) ) : ''; // @codingStandardsIgnoreLine
$button_2 = ( isset( $this->args['button_2'] ) && '' !== $this->args['button_2'] ) ? rawurldecode( base64_decode( $this->args['button_2'] ) ) : ''; // @codingStandardsIgnoreLine

$separator_content = '';

if ( isset( $this->args['separator_type'] ) && 'string' === $this->args['separator_type'] ) {
	$separator_content = ( isset( $this->args['sep_text'] ) && '' !== $this->args['sep_text'] ) ? $this->args['sep_text'] : '';
} elseif ( isset( $this->args['separator_type'] ) && 'icon' === $this->args['separator_type'] ) {
	$separator_content = ( isset( $this->args['sep_icon'] ) && '' !== $this->args['sep_icon'] ) ? $this->args['sep_icon'] : '';
	if ( '' !== $separator_content ) {
		$icon_class        = $separator_content;
		$separator_content = '<span class="' . $icon_class . '"></span>';
	}
}

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-button' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-button-first' ) . '>';
$html .= do_shortcode( $button_1 );

if ( '' !== $separator_content ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-button-separator' ) . '>';
	$html .= '<span class="elegant-dual-button-separator-text">' . $separator_content . '</span>';
	$html .= '</div>';
}

$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-button-last' ) . '>';
$html .= do_shortcode( $button_2 );
$html .= '</div>';
$html .= '</div>';
