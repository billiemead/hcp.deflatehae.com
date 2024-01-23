<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-big-caps' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '<style type="text/css" scoped="true">';
$html .= $this->generate_style();
$html .= '</style>';
