<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel' ) . '>';

if ( isset( $this->args['header_image'] ) && '' !== $this->args['header_image'] ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-header-image-wrapper' ) . '></div>';
}

if ( isset( $this->args['profile_image'] ) && '' !== $this->args['profile_image'] ) {
	$profile_image     = wp_get_attachment_image_src( $this->args['profile_image'], 'full' );
	$profile_image_url = $profile_image[0];
	$profile_image_url = esc_url( $profile_image_url );

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-profile-image-wrapper' ) . '>';
	$html .= '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-profile-image' ) . ' src="' . $profile_image_url . '" />';
	$html .= '</div>';
}

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<h3 ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-title' ) . '>' . $this->args['title'] . '</h3>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . Elegant_Elements_WPBakery::attributes( 'elegant-profile-panel-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $this->content );
$html .= '</div>';
$html .= '</div>';
