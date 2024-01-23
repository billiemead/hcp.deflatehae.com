<?php
$button_shortcode = ( '' !== $this->args['element_content'] ) ? rawurldecode( base64_decode( $this->args['element_content'] ) ) : ''; // @codingStandardsIgnoreLine

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-overlay-button-wrapper' ) . '>';
$html .= '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-overlay-button-image' ) . ' />';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-overlay-button-overlay' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-overlay-button' ) . '>';
$html .= do_shortcode( $button_shortcode );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="fusion-clearfix"></div>';
