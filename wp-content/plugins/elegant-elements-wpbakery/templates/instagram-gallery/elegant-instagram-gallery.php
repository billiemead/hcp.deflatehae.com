<?php
$username      = ( '' !== $this->args['username'] ) ? $this->args['username'] : '';
$limit         = ( '' !== $this->args['photos_count'] ) ? $this->args['photos_count'] : 9;
$size          = ( '' !== $this->args['photo_size'] ) ? $this->args['photo_size'] : 'large';
$target        = ( '' !== $this->args['link_target'] ) ? $this->args['link_target'] : '_self';
$show_likes    = ( 'no' !== $this->args['show_likes'] ) ? true : false;
$show_comments = ( 'no' !== $this->args['show_comments'] ) ? true : false;
$hover_type    = ( '' !== $this->args['hover_type'] ) ? $this->args['hover_type'] : 'none';

if ( 'lightbox' === $target ) {
	wp_enqueue_script( 'prettyphoto' );
	wp_enqueue_style( 'prettyphoto' );
}

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-instagram-gallery' ) . '>';

if ( 'grid' !== $this->args['gallery_layout'] ) {
	// Enqueue Isotope for Masonry.
	wp_enqueue_script( 'isotope' );
	wp_add_inline_script(
		'isotope',
		"// Set the layout after all the images are loaded.
		jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).isotope();
		jQuery( window ).load( function() {
			jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).each( function() {
				jQuery( this ).isotope( 'layout' );
			} );
		} );

		jQuery( document ).on( 'instagramGalleryLoaded', function( event, items ) {
			jQuery( '.elegant-instagram-gallery-masonry .elegant-instagram-pics' ).each( function() {
				var galleryItems = items.items;
				jQuery( this ).isotope( 'appended', jQuery( galleryItems ) );
				jQuery( this ).isotope( 'layout' );
				jQuery( this ).css( 'opacity', 1 );
			} );
		} );"
	);
}

if ( '' !== $username ) {

	$column = $size;
	if ( 'grid' !== $this->args['gallery_layout'] ) {
		$column = ( '' !== $this->args['masonry_columns'] ) ? $this->args['masonry_columns'] : $size;
		$size   = 'original';
	}

	$media_array = eewpb_scrape_instagram( $username, $target, $limit, $column, $this->args['show_likes'], $this->args['show_comments'], $this->args['gallery_layout'] );

	if ( is_wp_error( $media_array ) ) {

		$html .= $media_array->get_error_message();

	} else {
		unset( $media_array['user'] );

		// slice list down to required limit.
		$media_array = array_slice( $media_array, 0, $limit );

		// filters for custom classes.
		$ulclass  = apply_filters( 'elegant_instagram_list_class', 'elegant-instagram-pics elegant-instagram-size-' . $column );
		$liclass  = apply_filters( 'elegant_instagram_item_class', 'elegant-instagram-pic' );
		$aclass   = apply_filters( 'elegant_instagram_a_class', 'elegant-instagram-pic-link' );
		$imgclass = apply_filters( 'elegant_instagram_img_class', '' );

		if ( 'none' !== $hover_type ) {
			$aclass .= ' hover-type-' . $hover_type;
		}

		$images = '';
		foreach ( $media_array as $item ) {
			$comments       = $item['comments'];
			$likes          = $item['likes'];
			$likes_comments = '';

			if ( $show_likes ) {
				$likes_comments .= '<span class="elegant-instagram-likes fa fa-heart"> ' . $likes . '</span>';
			}

			if ( $show_comments ) {
				$likes_comments .= '<span class="elegant-instagram-comments fa fa-comment"> ' . $comments . '</span>';
			}

			if ( 'lightbox' !== $target ) {

				$images .= '<li class="' . esc_attr( $liclass ) . '">';
				$images .= '<div class="elegant-instagram-pic-wrapper">';
				$images .= '<a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"  class="' . esc_attr( $aclass ) . '">';
				$images .= '<img src="' . esc_url( $item[ $size ] ) . '" alt="' . esc_html( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '"  class="' . esc_attr( $imgclass ) . '"/>';
				$images .= '</a>';

				if ( '' !== $likes_comments ) {
					$images .= '<div class="elegant-instagram-pic-likes">';
					$images .= $likes_comments;
					$images .= '</div>';
				}

				$images .= '</div>';
				$images .= '</li>';
			} else {
				$item_link = $item['original'] . '&type=.jpg';
				$user_id   = str_replace( array( '@', '#', '.' ), '', $username );
				$data_rel  = 'prettyPhoto[gallery_image_' . $user_id . ']';

				$images .= '<li class="' . esc_attr( $liclass ) . '">';
				$images .= '<div class="elegant-instagram-pic-wrapper">';
				$images .= '<a href="' . esc_url( $item_link ) . '" data-rel="' . $data_rel . '" class="elegant-lightbox prettyphoto ' . esc_attr( $aclass ) . '">';
				$images .= '<img src="' . esc_url( $item[ $size ] ) . '"  class="' . esc_attr( $imgclass ) . '">';
				$images .= '</a>';

				if ( '' !== $likes_comments ) {
					$images .= '<div class="elegant-instagram-pic-likes">';
					$images .= $likes_comments;
					$images .= '</div>';
				}

				$images .= '</div>';
				$images .= '</li>';
			}
		}

		$html .= '<ul class="' . esc_attr( $ulclass ) . '">';
		$html .= $images;
		$html .= '</ul>';
	}
}

$html .= '</div>';
