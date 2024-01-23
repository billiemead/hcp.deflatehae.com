<?php
$notification_icon = '';

if ( isset( $this->args['icon'] ) && '' !== $this->args['icon'] ) {
	$icon              = $this->args['icon'];
	$notification_icon = '<span class="elegant-notification-box-icon ' . trim( $icon ) . '"></span>';
}

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-notification-box' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-notification-title' ) . '>';

if ( isset( $this->args['type'] ) && 'classic' === $this->args['type'] ) {
	$html .= $notification_icon;
}

$html .= $this->args['title'];

$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-notification-content' ) . '>';

if ( isset( $this->args['type'] ) && 'modern' === $this->args['type'] ) {
	$html .= $notification_icon;
}

$html .= do_shortcode( $content );

$html .= '</div>';
$html .= '</div>';
