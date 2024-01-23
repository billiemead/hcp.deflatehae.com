<?php
if ( 'top' === $this->args['shape_divider_position'] ) {
	$shape_divider = elegant_get_top_divider_shape( $this->args['top_divider_type'], $this->args['shape_fill_color'], $this->args['shape_bg_color'] );
} else {
	$shape_divider = elegant_get_bottom_divider_shape( $this->args['bottom_divider_type'], $this->args['shape_fill_color'], $this->args['shape_bg_color'] );
}

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-shape-divider' ) . '>';
$html .= $shape_divider;
$html .= '</div>';
