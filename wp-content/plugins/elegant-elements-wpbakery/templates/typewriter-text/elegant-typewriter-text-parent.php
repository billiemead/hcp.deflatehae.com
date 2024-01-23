<?php

do_shortcode( $content );

$parent_font_family = $child_font_family = '';

if ( isset( $this->args['typography_parent'] ) && '' !== $this->args['typography_parent'] ) {
	$parent_font_family = elegant_get_google_font_styling( $this->args, 'typography_parent' );
}

if ( isset( $this->args['typography_child'] ) && '' !== $this->args['typography_child'] ) {
	$child_font_family = elegant_get_google_font_styling( $this->args, 'typography_child' );
}

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text-container elegant-typewriter-text-container-' . $this->typewriter_text_counter ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text elegant-align-' . $this->args['alignment'] ) . ' style="font-size:' . $this->args['font_size'] . 'px; color:' . $this->args['title_color'] . ';" data-loop="' . $this->args['loop'] . '" data-deletedelay="' . $this->args['delete_delay'] . '" data-counter="elegant-typewriter-' . $this->typewriter_text_counter . '">';

if ( isset( $this->args['prefix'] ) && '' !== $this->args['prefix'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text-prefix' ) . ' style="' . $parent_font_family . '">';
	$html .= $this->args['prefix'] . '&nbsp;';
	$html .= '</p>';
}

$html .= '<div id="elegant-typewriter-' . $this->typewriter_text_counter . '" class="typewriter-text"></div>';
$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text-child' ) . '>';
foreach ( $this->typewriter_text[ $this->typewriter_text_counter ] as $key => $typewriter_text ) {
	$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text-wrap' ) . ' style="' . $child_font_family . ' color:' . $typewriter_text['title_color'] . ';">' . $typewriter_text['title'] . '</span>';
}
$html .= '</p>';

if ( isset( $this->args['suffix'] ) && '' !== $this->args['suffix'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-typewriter-text-suffix' ) . ' style="' . $parent_font_family . '">';
	$html .= $this->args['suffix'];
	$html .= '</p>';
}

$html .= '</div>';
$html .= '</div>';
