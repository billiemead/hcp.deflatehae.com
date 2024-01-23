<?php

do_shortcode( $content );

$main_class = '.elegant-partner-logos-container.elegant-partner-logo-container-' . $this->partner_logos_counter;

$style  = '<style type="text/css">';
$style .= $main_class . ' .elegant-partner-logo {';

if ( isset( $this->args['border'] ) && $this->args['border'] ) {
	$style .= 'border-width: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border'], 'px' ) . ';';
	$style .= 'border-color: ' . $this->args['border_color'] . ';';
	$style .= 'border-style: ' . $this->args['border_style'] . ';';
}

if ( isset( $this->args['padding'] ) && '' !== $this->args['padding'] ) {
	$style .= 'padding:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['padding'], 'px' ) . ';';
}

if ( isset( $this->args['margin'] ) && '' !== $this->args['margin'] ) {
	$style .= 'margin:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin'], 'px' ) . ';';
}

if ( isset( $this->args['width'] ) && '' !== $this->args['width'] ) {
	$style .= 'max-width: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';';
}

if ( isset( $this->args['height'] ) && '' !== $this->args['height'] ) {
	$style .= 'max-height: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' ) . ';';
}

$style .= '}';

$style .= '</style>';

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-partner-logos-container elegant-partner-logo-container-' . $this->partner_logos_counter ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-partner-logos' ) . '>';

foreach ( $this->partner_logos[ $this->partner_logos_counter ] as $key => $partner ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-partner-logo elegant-partner-logo-' . $key ) . '>';

	$modal_data = ( isset( $partner['modal_anchor'] ) && '' !== $partner['modal_anchor'] && 'modal' === $partner['click_action'] ) ? 'data-toggle="modal" data-target=".elegant-modal.' . $partner['modal_anchor'] . '"' : '';

	$image = '<img ' . $modal_data . 'src="' . $partner['image_url'] . '" />';

	if ( 'url' === $partner['click_action'] ) {
		$url   = ( strpos( $partner['url'], '://' ) === false ) ? 'http://' . $partner['url'] : $partner['url'];
		$html .= '<a href="' . $url . '" target="' . $partner['target'] . '">';
		$html .= $image;
		$html .= '</a>';
	} else {
		$html .= $image;
	}

	$html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';

$html .= $style;
