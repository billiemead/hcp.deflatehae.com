<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-lightbox' ) . '>';

$video_url     = ( 'hosted' !== $this->args['video_provider'] ) ? $this->args['video_url'] : $this->args['hosted_video_url'];
$video_trigger = '';

if ( 'icon' === $this->args['video_trigger'] ) {
	$video_trigger = '<i ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-lightbox-icon' ) . '></i>';
} elseif ( 'image' === $this->args['video_trigger'] ) {
	$video_trigger = '<img ' . Elegant_Elements_WPBakery::attributes( 'elegant-video-lightbox-image' ) . ' />';
} else {
	$video_trigger = '<span class="elegant-video-lightbox-text">' . $this->args['text'] . '</span>';
}

$html .= '<a href="' . $video_url . '" class="elegant-video-lightbox-link magnific_video" data-eewpb_lightbox>';
$html .= $video_trigger;
$html .= '</a>';

$html .= '</div>';
