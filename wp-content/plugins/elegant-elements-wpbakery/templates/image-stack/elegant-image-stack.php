<?php

// Parse list item params.
$stacked_images = vc_param_group_parse_atts( $this->args['stacked_images'] );

$image_count = count( $stacked_images );

$this->args['image_count'] = $image_count + 10;

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-stack-wrapper' ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-stack-image-item', $this->args ) . '>';
$html .= '<div class="elegant-stack-image-wrap">';
$html .= '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-stack-image', $this->args ) . ' />';
$html .= '</div>';
$html .= '</div>';

// Loop through the list items and generate the stack.
foreach ( $stacked_images as $image ) {
	// Increase the counter.
	$this->count++;

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-stack-image-item', $image ) . '>';
	$html .= '<div class="elegant-stack-image-wrap">';
	$html .= '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-stack-image', $image ) . ' />';
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div>';
