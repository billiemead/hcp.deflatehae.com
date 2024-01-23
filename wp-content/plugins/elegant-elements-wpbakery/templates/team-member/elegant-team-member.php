<?php
$image     = wp_get_attachment_image_src( $this->args['profile_image'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-team-member' ) . '>';
$html .= '<div class="elegant-team-member-profile">';
$html .= '<div class="elegant-team-member-image">';
$html .= '<img src="' . $image_url . '">';
$html .= '</div>';
$html .= '<div class="elegant-team-member-text">';
$html .= '<h3>' . $this->args['name'] . '</h3>';
$html .= '<span>' . $this->args['job_title'] . '</span>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="elegant-team-member-social-icons">';
$html .= $content;
$html .= '</div>';
$html .= '</div>';
