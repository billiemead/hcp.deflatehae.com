<?php

do_shortcode( $content );

$main_class      = '.elegant-testimonials-container.elegant-testimonial-container-' . $this->testimonials_counter;
$title_class     = $main_class . ' ' . $this->args['heading_size'] . '.elegant-testimonials-title';
$sub_title_class = $main_class . ' .elegant-testimonials-subtitle';
$content_class   = $main_class . ' .elegant-testimonials-content';

$title_font_family = $sub_title_font_family = $content_font_family = '';

if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
	$typography        = $this->args['typography_title'];
	$title_font_family = elegant_get_google_font_styling( $this->args, 'typography_title' );
}

if ( isset( $this->args['typography_sub_title'] ) && '' !== $this->args['typography_sub_title'] ) {
	$typography            = $this->args['typography_sub_title'];
	$sub_title_font_family = elegant_get_google_font_styling( $this->args, 'typography_sub_title' );
}

if ( isset( $this->args['typography_content'] ) && '' !== $this->args['typography_content'] ) {
	$typography          = $this->args['typography_content'];
	$content_font_family = elegant_get_google_font_styling( $this->args, 'typography_content' );
}

$style = '<style type="text/css">';

$style .= $main_class . '{';
if ( isset( $this->args['text_color'] ) && '' !== $this->args['text_color'] ) {
	$style .= 'color: ' . $this->args['text_color'] . ';';
}
if ( isset( $this->args['content_font_size'] ) && '' !== $this->args['content_font_size'] ) {
	$style .= 'font-size: ' . $this->args['content_font_size'] . 'px;';
}
if ( isset( $this->args['background_image'] ) && '' !== $this->args['background_image'] ) {
	$background_image     = wp_get_attachment_image_src( $this->args['background_image'], 'full' );
	$background_image_url = $background_image[0];
	$background_image_url = esc_url( $background_image_url );

	$style .= 'background-image: url(' . $background_image_url . ');';
	$style .= 'background-position: ' . $this->args['background_position'] . ';';
}
if ( isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ) {
	$style .= 'background-color: ' . $this->args['background_color'] . ';';
	$style .= 'background-blend-mode: overlay;';
}
$style .= '}';

$style .= $title_class . '{';
$style .= $title_font_family;

if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
	$style .= 'font-size: ' . $this->args['title_font_size'] . 'px !important;';
}

$style .= '}';

$style .= $sub_title_class . '{';
$style .= $sub_title_font_family;

if ( isset( $this->args['sub_title_font_size'] ) && '' !== $this->args['sub_title_font_size'] ) {
	$style .= 'font-size: ' . $this->args['sub_title_font_size'] . 'px;';
}

$style .= '}';

$style .= $content_class . '{';
$style .= $content_font_family;
$style .= '}';

$position = ( isset( $this->args['description_position'] ) && '' !== $this->args['description_position'] ) ? $this->args['description_position'] : 'left';
$html     = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-container elegant-testimonials-position-' . $position . ' elegant-testimonial-container-' . $this->testimonials_counter ) . '>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-description-container' ) . '>';

foreach ( $this->testimonials[ $this->testimonials_counter ] as $key => $testimony ) {
	$active_class = ( 0 == $key ) ? 'active-description' : '';

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-description elegant-testimonial-' . $key . ' ' . $active_class ) . '>';
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-title-container' ) . '>';
	$html .= '<' . $this->args['heading_size'] . ' class="elegant-testimonials-title" style="color: ' . $testimony['title_color'] . ';">' . $testimony['title'] . '</' . $this->args['heading_size'] . '>';
	$html .= '<span class="elegant-testimonials-subtitle" style="color: ' . $testimony['sub_title_color'] . ';">' . $testimony['sub_title'] . '</span>';
	$html .= '</div>';
	$html .= '<div class="elegant-testimonials-content">';
	$html .= $testimony['content'];
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div>';

$images_background_color = isset( $this->args['background_color'] ) && '' !== $this->args['background_color'] ? $this->args['background_color'] : '';
$html                   .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-images-container' ) . ' style="background-color:' . $images_background_color . ';">';

foreach ( $this->testimonials[ $this->testimonials_counter ] as $key => $testimony ) {
	$active_class = ( 0 == $key ) ? 'active-testimony' : '';

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-testimonials-image vc_col-sm-3 ' . $active_class ) . ' data-key="elegant-testimonial-' . $key . '">';
	$html .= '<img src="' . $testimony['image_url'] . '" />';
	$html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';

$style .= '</style>';

$html .= $style;
