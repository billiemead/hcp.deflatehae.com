<?php
$defaults = shortcode_atts(
	array(
		'circle'      => '',
		'circlecolor' => '',
		'icon'        => '',
		'iconcolor'   => '',
	),
	$args
);

$this->child_args = $defaults;

$html  = '<li ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-li-item' ) . '>';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-span' ) . '>';
$html .= '<i ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-icon' ) . '></i>';
$html .= '</span>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'list-box-shortcode-item-content' ) . '>' . do_shortcode( $content ) . '</div>';
$html .= '</li>';

$this->circle_class = 'circle-no';
