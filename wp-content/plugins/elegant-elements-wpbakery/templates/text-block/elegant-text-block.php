<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-text-block' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
