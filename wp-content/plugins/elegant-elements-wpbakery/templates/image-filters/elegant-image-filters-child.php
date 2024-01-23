<?php
$image     = wp_get_attachment_image_src( $this->child_args['image_url'], 'full' );
$image_url = $image[0];
$image_url = esc_url( $image_url );

$lightbox_image     = wp_get_attachment_image_src( $this->child_args['lightbox_image_url'], 'full' );
$lightbox_image_url = $lightbox_image[0];
$lightbox_image_url = esc_url( $lightbox_image_url );

// Get image ID.
$image_id = $this->child_args['image_url'];

$orientation = isset( $this->child_args['orientation'] ) ? $this->child_args['orientation'] : 'auto';

if ( 'auto' === $orientation ) {
	// Get image meta data to check orientation.
	$image_metadata = wp_get_attachment_metadata( $image_id );

	// Check for image orientation.
	$image_orientation = 'elegant-image-portrait';
	$image_sizes       = ( isset( $image_metadata['sizes'] ) && ! empty( $image_metadata['sizes'] ) ) ? $image_metadata['sizes'] : array();
	$image_width       = ( isset( $image_metadata['width'] ) ) ? $image_metadata['width'] : '';
	$image_height      = ( isset( $image_metadata['height'] ) ) ? $image_metadata['height'] : '';

	if ( ! empty( $image_sizes ) ) {
		foreach ( $image_sizes as $size => $image_data ) {
			if ( basename( $image_url ) == $image_data['file'] ) {
				$image_width  = $image_data['width'];
				$image_height = $image_data['height'];
			}
		}
	}

	$ratio = '0.8';

	$lower_limit = ( $ratio / 2 ) + ( $ratio / 4 );
	$upper_limit = ( $ratio * 2 ) - ( $ratio / 2 );

	// Landscape image.
	if ( $image_width ) {
		if ( $lower_limit > $image_height / $image_width ) {
			$image_orientation = 'elegant-image-landscape';
		} elseif ( $upper_limit < $image_height / $image_width ) {
			$image_orientation = 'elegant-image-portrait';
		}
	}
} else {
	$image_orientation = 'elegant-image-' . $orientation;
}

$link   = vc_build_link( $this->child_args['url'] );
$url    = esc_url( $link['url'] );
$target = ( isset( $link['target'] ) ) ? trim( $link['target'] ) : '';

$filter_image = array(
	'image_url'          => $image_url,
	'lightbox_image_url' => $lightbox_image_url,
	'title'              => $this->child_args['title'],
	'navigation'         => $this->child_args['navigation'],
	'click_action'       => $this->child_args['click_action'],
	'modal_anchor'       => $this->child_args['modal_anchor'],
	'url'                => $url,
	'target'             => $target,
	'class'              => $this->child_args['class'],
	'id'                 => $this->child_args['id'],
	'orientation'        => $image_orientation,
	'args'               => $this->child_args,
);

$this->image_filters[ $this->image_filters_counter ][] = $filter_image;

// Create navigation array.
$navigation_title = $this->child_args['navigation'];
$navigation_title = explode( ',', $navigation_title );

foreach ( $navigation_title as $title ) {
	if ( '' !== trim( $title ) ) {
		$navigation_id = str_replace( array( ' ', '_', '-' ), '', strtolower( $title ) );
		$this->image_filter_navigation[ $this->image_filters_counter ][ $navigation_id ] = $title;
	}
}

$image_alt = get_post_meta( $filter_image['args']['image_url'], '_wp_attachment_image_alt', true );

$args           = array();
$args['id']     = $filter_image['id'];
$args['class']  = 'elegant-image-filter-item';
$args['class'] .= ' ' . $filter_image['orientation'];
$args['class'] .= ( '' !== $filter_image['class'] ) ? ' ' . $filter_image['class'] : '';

$args['data-nav-items'] = $filter_image['navigation'];

$args['style'] = 'padding:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['grid_item_padding'], 'px' ) . ';';

// Get navigation title.
$navigation_title = $filter_image['navigation'];
$navigation_title = explode( ',', $navigation_title );

foreach ( $navigation_title as $title ) {
	$navigation_id  = str_replace( array( ' ', '_', '-' ), '', strtolower( $title ) );
	$args['class'] .= ' ' . $navigation_id;
}

$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter', $args ) . '>';

if ( ( isset( $this->args['image_title_position'] ) && 'before_image' == $this->args['image_title_position'] ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
	$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';
	$child_html .= $filter_image['title'];
	$child_html .= '</div>';
}

$modal_data = ( isset( $filter_image['modal_anchor'] ) && '' !== $filter_image['modal_anchor'] ) ? 'data-toggle="modal" data-target=".elegant-modal.' . $filter_image['modal_anchor'] . '"' : '';

$image = '<img ' . $modal_data . 'src="' . $filter_image['image_url'] . '" alt="' . $image_alt . '"/>';

if ( 'url' === $filter_image['click_action'] ) {
	$url         = ( strpos( $filter_image['url'], '://' ) === false ) ? 'http://' . $filter_image['url'] : $filter_image['url'];
	$child_html .= '<a href="' . $url . '" target="' . $filter_image['target'] . '">';
	$child_html .= $image;
	$child_html .= '</a>';
} elseif ( 'lightbox' === $filter_image['click_action'] ) {
	wp_enqueue_script( 'prettyphoto' );
	wp_enqueue_style( 'prettyphoto' );

	$lightbox_image_url = ( isset( $filter_image['lightbox_image_url'] ) && '' !== $filter_image['lightbox_image_url'] ) ? $filter_image['lightbox_image_url'] : $filter_image['image_url'];
	$lightbox_image_url = str_replace( array( '×', '&#215;' ), 'x', $lightbox_image_url );

	$image_caption       = '';
	$image_title         = '';
	$lightbox_image_meta = ( '' !== $filter_image['args']['lightbox_image_meta'] ) ? $filter_image['args']['lightbox_image_meta'] : $this->args['lightbox_image_meta'];

	if ( '' !== $lightbox_image_meta ) {
		$lightbox_image_id = $this->child_args['lightbox_image_url'];

		if ( false !== strpos( $lightbox_image_meta, 'caption' ) ) {
			$image_caption = wp_get_attachment_caption( $lightbox_image_id );
		}

		if ( false !== strpos( $lightbox_image_meta, 'title' ) ) {
			$image_meta_data = wp_get_attachment_metadata( $lightbox_image_id );
			$image_title     = ( $image_meta_data['image_meta'] ) ? $image_meta_data['image_meta']['title'] : '';

			if ( '' === $image_title && $lightbox_image_id ) {
				$image_title = get_the_title( $lightbox_image_id );
			}
		}
	}

	$lightbox_image = $lightbox_image_url;
	$data_rel       = 'prettyPhoto[gallery_image_' . $this->image_filters_counter . ']';

	$child_html .= '<a href="' . $lightbox_image . '" class="elegant-lightbox prettyphoto" data-rel="' . $data_rel . '" data-caption="' . $image_caption . '" title="' . $image_title . '">';
	$child_html .= str_replace( '/>', 'title="' . $image_title . '" />', $image );

	if ( ( isset( $this->args['image_title_position'] ) && ( 'on_image_hover' == $this->args['image_title_position'] || 'after_image' == $this->args['image_title_position'] ) ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
		$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';

		if ( 'on_image_hover' == $this->args['image_title_position'] ) {
			$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter-title-overlay', $filter_image['args'] ) . '>' . $filter_image['title'] . '</div>';
		} else {
			$child_html .= $filter_image['title'];
		}
		$child_html .= '</div>';
	}

	$child_html .= '</a>';
} else {
	$child_html .= $image;
}

if ( 'lightbox' !== $filter_image['click_action'] ) {
	if ( ( isset( $this->args['image_title_position'] ) && ( 'on_image_hover' == $this->args['image_title_position'] || 'after_image' == $this->args['image_title_position'] ) ) && isset( $filter_image['title'] ) && '' !== $filter_image['title'] ) {
		$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter-title', $filter_image['args'] ) . '>';

		if ( 'on_image_hover' == $this->args['image_title_position'] ) {
			$child_html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-image-filter-title-overlay', $filter_image['args'] ) . '>' . $filter_image['title'] . '</div>';
		} else {
			$child_html .= $filter_image['title'];
		}
		$child_html .= '</div>';
	}
}

$child_html .= '</div>';
