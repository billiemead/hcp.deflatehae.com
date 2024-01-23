<?php

$html  = '<div class="elegant-list-box-container">';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-title' ) . '>';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-title-span' ) . '>' . $defaults['title'] . '</span>';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-items' ) . '>';
$html .= '<ul ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode' ) . '>' . do_shortcode( $content ) . '</ul>';
$html .= '</div>';
$html .= '</div>';

$html = str_replace( '</li><br />', '</li>', $html );
