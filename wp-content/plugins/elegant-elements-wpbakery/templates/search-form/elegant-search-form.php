<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-search-form' ) . '>';

if ( 'fullscreen' === $this->args['search_layout'] ) {
	$html .= '<div class="elegant-search-form-overlay" style="background-color: ' . $this->args['fs_overlay_color'] . ';">';
	$html .= '<span class="elegant-search-close" style="color:' . $this->args['input_text_color'] . ';" onclick="elegantCloseSearch();" title="' . __( 'Close Overlay', 'elegant-elements' ) . '">×</span>';
}

$html .= '<form role="search" class="searchform" method="get" action="' . home_url() . '">';
$html .= '<div class="elegant-search-form-wrapper">';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-search-form-field' ) . '>';
$html .= '<span class="screen-reader-text">Search for:</span>';
$html .= '<input type="search" autocomplete="off" value="" name="s" class="s elegant-search-input" placeholder="' . $this->args['placeholder_text'] . '" aria-required="true" aria-label="' . $this->args['placeholder_text'] . '">';
$html .= '</div>';

if ( 'fullscreen' === $this->args['search_layout'] ) {
	$html .= '</div>';
	$html .= '</form>';
	$html .= '</div>';
	$html .= '<div class="elegant-search-form-wrapper elegant-align-' . $this->args['alignment'] . '">';
}

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-search-form-button' ) . '>';
$html .= '<button ' . Elegant_Elements_WPBakery::attributes( 'elegant-search-form-button-input' ) . '>';

if ( 'both' === $this->args['button_type'] || 'text' === $this->args['button_type'] ) {
	$html .= '<div class="elegant-search-text">';
	$html .= $this->args['button_text'];
	$html .= '</div>';
}

if ( 'both' === $this->args['button_type'] || 'icon' === $this->args['button_type'] ) {
	$html .= '<div class="elegant-search-icon">';
	$html .= '<svg width="' . $this->args['icon_size'] . '" height="' . $this->args['icon_size'] . '" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-reactroot="">
	<path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="inherit" fill="none" d="M9.5 17C13.6421 17 17 13.6421 17 9.5C17 5.35786 13.6421 2 9.5 2C5.35786 2 2 5.35786 2 9.5C2 13.6421 5.35786 17 9.5 17Z"></path>
	<path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="inherit" d="M22 22L14.8 14.8"></path>
	</svg>';
	$html .= '</div>';
}

$html .= '</button>';
$html .= '</div>';

if ( 'fullscreen' !== $this->args['search_layout'] ) {
	$html .= '</div>';
	$html .= '</form>';
} else {
	$html .= '</div>';
}

$html .= '</div>';
