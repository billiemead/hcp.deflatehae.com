<?php
$icon_class = $this->child_args['pointer_icon'];
$link       = vc_build_link( $this->child_args['link_url'] );
$url        = esc_url( $link['url'] );
$target     = ( isset( $link['target'] ) && '' !== $link['target'] ) ? ' target="' . trim( $link['target'] ) . '"' : '';

$pointer  = ( 'icon' === $this->child_args['pointer_type'] ) ? '<i class="' . $icon_class . '"></i>' : $this->image_hotspot_child_counter;
$position = ( isset( $this->child_args['tooltip_position'] ) && '' !== $this->child_args['tooltip_position'] ) ? $this->child_args['tooltip_position'] : 'top';
$title    = ( isset( $this->child_args['link_url'] ) && '' !== $url ) ? '<a href="' . $url . '"' . $target . '>' . $this->child_args['title'] . '</a>' : $this->child_args['title'];

$child_html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-hotspot-item' ) . '>';
$child_html .= '<span class="elegant-image-hotspot-pointer">' . $pointer . '</span>';
$child_html .= '<span class="elegant-image-hotspot-tooltip tooltip-position-' . $position . '"  aria-label="' . $this->child_args['title'] . '" role="tooltip">' . $title . '</span>';
$child_html .= '<span class="elegant_' . $this->args['pointer_effect'] . '"></span>';
$child_html .= '</div>';
