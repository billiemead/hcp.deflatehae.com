<?php
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filters-wrapper' ) . '>';

$html .= '<ul ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filters-navigation' ) . '>';

$filter_separator = '';
if ( isset( $this->args['navigation_layout'] ) && 'horizontal' == $this->args['navigation_layout'] ) {
	$filter_separator = ( isset( $this->args['filter_separator'] ) && '' !== $this->args['filter_separator'] ) ? '<span class="image-filter-navigation-separator">' . $this->args['filter_separator'] . '</span>' : '';
}

// Add "All" filter.
if ( isset( $this->args['use_all_filter'] ) && 'yes' === $this->args['use_all_filter'] ) {
	$all_filter_text = ( isset( $this->args['all_filter_text'] ) && '' !== $this->args['all_filter_text'] ) ? $this->args['all_filter_text'] : esc_attr__( 'All', 'elegant-elements' );
	$html           .= '<li data-filter-all="true" ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filters-navigation-item' ) . '>';
	$html           .= '<a href="#" data-filter="*">' . $all_filter_text . '</a>';
	$html           .= '</li>';
	$html           .= $filter_separator;
}

$i = 1;

if ( isset( $this->image_filter_navigation[ $this->image_filters_counter ] ) ) {
	$c = count( $this->image_filter_navigation[ $this->image_filters_counter ] );

	foreach ( $this->image_filter_navigation[ $this->image_filters_counter ] as $id => $title ) {
		$html .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filters-navigation-item' ) . '>';
		$html .= '<a href="#" data-filter=".' . $id . '">' . $title . '</a>';
		$html .= '</li>';

		if ( $i < $c ) {
			$html .= $filter_separator;
		}

		$i++;
	}
}

$html .= '</ul>';

$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filters-content' ) . '>';

$html .= do_shortcode( $content );

$html .= '</div>';
$html .= '</div>';
