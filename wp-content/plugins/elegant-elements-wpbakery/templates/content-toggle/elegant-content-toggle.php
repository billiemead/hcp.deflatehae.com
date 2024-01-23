<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-content-toggle' ) . '>';

$html .= $this->build_styles();

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-switch' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-switch-first' ) . '>';
$html .= '<span class="content-toggle-title">' . $this->args['title_first'] . '</span>';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-switch-button' ) . '>';
$html .= '<label class="content-toggle-switch-label"></label>';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-switch-last' ) . '>';
$html .= '<span class="content-toggle-title">' . $this->args['title_last'] . '</span>';
$html .= '</div>';
$html .= '</div>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-first' ) . '>';
$html .= do_shortcode( rawurldecode( base64_decode( $this->args['content_first'] ) ) ); // @codingStandardsIgnoreLine
$html .= '</div>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'content-toggle-last' ) . '>';
$html .= do_shortcode( rawurldecode( base64_decode( $this->args['content_last'] ) ) ); // @codingStandardsIgnoreLine
$html .= '</div>';
$html .= '</div>';
