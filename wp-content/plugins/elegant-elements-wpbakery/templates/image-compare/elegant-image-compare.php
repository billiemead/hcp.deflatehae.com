<?php
$after_image     = wp_get_attachment_image_src( $this->args['after_image'], 'full' );
$after_image_url = $after_image[0];
$after_image_url = esc_url( $after_image_url );

$before_image     = wp_get_attachment_image_src( $this->args['before_image'], 'full' );
$before_image_url = $before_image[0];
$before_image_url = esc_url( $before_image_url );

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare' ) . '>';
$html .= '<figure ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare-container' ) . '>';
$html .= '<img src="' . $after_image_url . '">';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare-label-after' ) . '>' . $this->args['after_image_caption'] . '</span>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare-after-image' ) . '>';
$html .= '<img src="' . $before_image_url . '">';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare-label-before' ) . '>' . $this->args['before_image_caption'] . '</span>';
$html .= '</div>';
$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-compare-handle' ) . '></span>';
$html .= '</figure>';
$html .= '</div>';
