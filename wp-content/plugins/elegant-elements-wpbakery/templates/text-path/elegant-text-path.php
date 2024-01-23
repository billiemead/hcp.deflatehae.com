<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-text-path-wrapper' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-text-path' ) . '>';
$html .= eewpb_get_text_path_svg( $this->args );
$html .= '</div>';
$html .= '</div>';
