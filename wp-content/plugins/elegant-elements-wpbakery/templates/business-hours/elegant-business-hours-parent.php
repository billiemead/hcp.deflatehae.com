<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-business-hours' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-business-hours-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
