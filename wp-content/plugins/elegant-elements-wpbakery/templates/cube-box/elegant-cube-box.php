<?php
$decode_method = 'base64_decode';
$front_content = rawurldecode( $decode_method( $this->args['front_content'] ) );

// Start cube box.
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box' ) . '>';

// Front side content.
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-front' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-front-content' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-content' ) . '>';
$html .= do_shortcode( $front_content );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

// Back side content.
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-back' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-back-content' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-cube-box-content' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

// Close cube box.
$html .= '</div>';
