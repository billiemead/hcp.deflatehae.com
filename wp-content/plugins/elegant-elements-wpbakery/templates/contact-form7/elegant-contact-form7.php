<?php
$html          = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-contact-form7' ) . '>';
$heading_style = $caption_style = '';

if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {

	if ( isset( $this->args['heading_padding'] ) && '' !== $this->args['heading_padding'] ) {
		$heading_padding = $this->args['heading_padding'];
		$heading_style  .= 'padding:' . esc_attr( $heading_padding ) . ';';
	}

	$heading_style .= ( isset( $this->args['heading_background_color'] ) && '' !== $this->args['heading_background_color'] ) ? 'background-color:' . $this->args['heading_background_color'] . ';' : '';

	if ( isset( $this->args['heading_background_image'] ) && '' !== $this->args['heading_background_image'] ) {
		$heading_background_image     = wp_get_attachment_image_src( $this->args['heading_background_image'], 'full' );
		$heading_background_image_url = $heading_background_image[0];
		$heading_background_image_url = esc_url( $heading_background_image_url );

		$heading_style .= 'background-image: url(' . $heading_background_image_url . ');';
		$heading_style .= 'background-position: ' . $this->args['heading_background_position'] . ';';
		$heading_style .= 'background-repeat: ' . $this->args['heading_background_repeat'] . ';';
		$heading_style .= 'background-blend-mode: overlay;';
	}

	if ( isset( $this->args['form_border_size'] ) && '' !== $this->args['form_border_size'] ) {
		$heading_style .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_size'], 'px' ) . ';';
		$heading_style .= 'border-color:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_color'], 'px' ) . ';';
		$heading_style .= 'border-style:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_style'], 'px' ) . ';';
		$heading_style .= 'border-bottom: none;';
	}

	if ( isset( $this->args['form_border_radius'] ) && '' !== $this->args['form_border_radius'] ) {
		$border_radius  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_radius'], 'px' );
		$heading_style .= 'border-radius:' . $border_radius . ' ' . $border_radius . ' 0 0;';
	}

	$html .= '<div class="elegant-contact-form7-heading-wrapper form-align-' . $this->args['heading_align'] . '" style="' . $heading_style . '">';

	$heading_style = '';

	if ( $this->args['heading_text'] ) {
		if ( $this->args['heading_color'] ) {
			$heading_style = 'color:' . esc_attr( $this->args['heading_color'] ) . ';';
		}

		if ( $this->args['heading_font_size'] ) {
			$heading_style .= 'font-size:' . esc_attr( Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' ) ) . ';';
		}

		if ( isset( $this->args['typography_heading'] ) && '' !== $this->args['typography_heading'] ) {
			$heading_typography = elegant_get_google_font_styling( $this->args, 'typography_heading' );

			$heading_style .= $heading_typography;
		}

		$html .= '<' . $this->args['heading_size'] . ' class="elegant-contact-form7-heading" style="' . $heading_style . '">' . $this->args['heading_text'] . '</' . $this->args['heading_size'] . '>';
	}

	if ( $this->args['caption_text'] ) {
		if ( $this->args['caption_color'] ) {
			$caption_style = 'color:' . $this->args['caption_color'] . ';';
		}

		if ( $this->args['caption_font_size'] ) {
			$caption_style .= 'font-size:' . esc_attr( Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['caption_font_size'], 'px' ) ) . ';';
		}

		if ( isset( $this->args['typography_caption'] ) && '' !== $this->args['typography_caption'] ) {
			$caption_typography = elegant_get_google_font_styling( $this->args, 'typography_caption' );

			$caption_style .= $caption_typography;
		}

		$html .= '<div class="elegant-contact-form7-caption" style="' . $caption_style . '">' . $this->args['caption_text'] . '</div>';
	}
	$html .= '</div>';
}

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-contact-form7-form-wrapper' ) . '>';
$html .= do_shortcode( '[contact-form-7 id="' . $this->args['form'] . '"]' );
$html .= '</div>';
$html .= '</div>';
