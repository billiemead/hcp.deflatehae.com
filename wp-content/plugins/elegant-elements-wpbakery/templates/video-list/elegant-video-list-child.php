<?php
$icon = '<i ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-list-icon' ) . '"></i>';

$child_html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-list-item' ) . '>';
$child_html .= '<h3 ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-list-item-title', $this->child_args ) . '>' . $icon . $this->child_args['title'] . '</h3>';
$child_html .= '</div>';
