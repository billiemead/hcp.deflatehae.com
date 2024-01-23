<?php
/**
 * Elegant elements helper functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define path and URL to the ACF plugin.
define( 'EEWPB_ACF_PATH', EEWPB_PLUGIN_DIR . 'inc/acf/' );
define( 'EEWPB_ACF_URL', EEWPB_PLUGIN_URL . 'inc/acf/' );

// Check if ACF PRO is installed as plugin, else, hide the admin menu.
if ( ! class_exists( 'ACF' ) ) {
	// ACF plugin is not installed. Hide the admin menu.
	add_filter( 'acf/settings/show_admin', 'eewpb_acf_settings_show_admin' );

	/**
	 * Hide ACF admin menu.
	 *
	 * @since 1.0
	 * @access public
	 * @param bool $show_admin Default value.
	 * @return bool.
	 */
	function eewpb_acf_settings_show_admin( $show_admin ) {
		return false;
	}
}

// Make sure to enable the default custom fields.
add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );

// Include the ACF plugin.
require_once EEWPB_ACF_PATH . 'acf.php';

// Include ACF Field groups.
require_once EEWPB_PLUGIN_DIR . 'inc/header-builder-meta.php';

// Include the color class.
require_once EEWPB_PLUGIN_DIR . 'inc/class-elegant-color.php';

/**
 * Check if the element is enabled or disabled.
 *
 * @since 1.0
 * @access public
 * @param string $element_base Element shortcode.
 * @return bool
 */
function elegant_is_element_enabled( $element_base ) {
	// Get options page settings.
	$settings = get_option( 'elegant_elements_wpbakery_settings', array() );

	// Check if element is disabled. All elements are enabled by default.
	if ( isset( $settings[ 'enable_' . $element_base ] ) ) {
		$element_enabled = $settings[ 'enable_' . $element_base ];

		if ( '0' === $element_enabled ) {
			return false;
		}
	}

	return true;
}

/**
 * Insert settings to existing element.
 *
 * @since 1.0
 * @access public
 * @param array $element Element array to map with WPBakery Page Builder.
 * @return void
 */
function elegant_elements_map( $element ) {

	$vc_element = $element;

	// Change shortcode param with base.
	$vc_element['base'] = $vc_element['shortcode'];

	if ( ! isset( $element['as_child'] ) ) {
		apply_filters(
			'elegant_elements_list',
			array(
				'name' => $vc_element['name'],
				'base' => $vc_element['base'],
			)
		);
	}

	// If element is disabled, do not map it with WPBakery Page Builder.
	if ( ! elegant_is_element_enabled( $vc_element['base'] ) ) {
		return;
	}

	// If is child element, make sure it's parent is enabled.
	if ( isset( $element['as_child'] ) ) {
		$element_enabled = elegant_is_element_enabled( $element['as_child']['only'] );

		if ( ! $element_enabled ) {
			return;
		}
	}

	// Set the elements to render at top.
	$vc_element['weight'] = 1;

	// Update icon class.
	if ( isset( $vc_element['icon'] ) ) {
		$vc_element['icon'] = 'elegant-element-icon ' . $vc_element['icon'];
	}

	// Loop through all the params.
	foreach ( $vc_element['params'] as $key => $param ) {
		// Replace param type values.
		if ( isset( $param['type'] ) && in_array( $param['type'], array( 'dropdown', 'ee_radio_button_set', 'ee_checkbox_button_set' ) ) ) {
			$value                                 = array_flip( $param['value'] );
			$vc_element['params'][ $key ]['value'] = $value;
		}
	}

	// Set element category.
	$vc_element['category'] = __( 'Elegant Elements', 'elegant-elements' );

	vc_map( $vc_element );

}

add_action( 'vc_after_init', 'map_important_vc_core_elements', 99 );
/**
 * Update important WPBakery Page Builder Core elements to render first.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function map_important_vc_core_elements() {
	$shortcodes = array(
		'vc_row',
		'vc_row_inner',
		'vc_column',
		'vc_column_inner',
		'vc_column_text',
		'vc_section',
		'vc_icon',
		'vc_separator',
		'vc_zigzag',
		'vc_text_separator',
		'vc_message',
		'vc_hoverbox',
		'vc_toggle',
		'vc_single_image',
		'vc_gallery',
		'vc_images_carousel',
		'vc_tta_tabs',
		'vc_tta_tour',
		'vc_tta_accordion',
		'vc_tta_pageable',
		'vc_tta_section',
		'vc_custom_heading',
		'vc_btn',
		'vc_cta',
	);
	foreach ( $shortcodes as $key => $shortcode ) {
		vc_map_update( $shortcode, array( 'weight' => 1000 - $key ) );
	}

	// Get existing favourite lements.
	$favourite_elements = get_option( 'eewpb_favourite_elements', array() );

	// If element is in favourite, make it grouped under Favourite and render at top.
	if ( ! empty( $favourite_elements ) ) {
		foreach ( $favourite_elements as $favourite ) {
			vc_map_update(
				$favourite,
				array(
					'weight'   => 1100,
					'category' => esc_attr__( 'Favourite', 'elegant-elements' ),
				)
			);
		}
	}
}

/**
 * Get contact form 7 forms list.
 *
 * @since 1.0
 * @access public
 * @return bool If combined styles enqueue or not.
 */
function eewpb_is_combined_enqueue() {
	// Get saved settings.
	$settings = get_option( 'elegant_elements_wpbakery_settings', array() );

	if ( empty( $settings ) || ( isset( $settings['enqueue_combined_scripts'] ) && 1 === absint( $settings['enqueue_combined_scripts'] ) ) ) {
		return true;
	}

	return false;
}

/**
 * Get contact form 7 forms list.
 *
 * @since 1.0
 * @access public
 * @return $forms Contact form 7 forms list.
 */
function eewpb_get_contact_form_list() {
	$args = array(
		'post_type'      => 'wpcf7_contact_form',
		'posts_per_page' => -1,
	);

	$cf7_forms = array();

	// @codingStandardsIgnoreLine
	if ( $data = get_posts( $args ) ) {
		foreach ( $data as $key ) {
			$cf7_forms[ $key->ID ] = $key->post_title;
		}
	} else {
		$cf7_forms['0'] = esc_html__( 'No Contact Form found', 'elegant-elements' );
	}

	return $cf7_forms;
}

/**
 * Return entry animation array.
 *
 * @since 1.0
 * @access public
 * @return $animations Array containig animation groups.
 */
function eewpb_get_entry_animations() {
	$animations = array();

	$animations['Attention Seekers'] = array(
		'bounce'     => 'bounce',
		'flash'      => 'flash',
		'pulse'      => 'pulse',
		'rubberBand' => 'rubberBand',
		'shake'      => 'shake',
		'swing'      => 'swing',
		'tada'       => 'tada',
		'wobble'     => 'wobble',
		'jello'      => 'jello',
	);

	$animations['Bouncing Entrances'] = array(
		'bounceIn'      => 'bounceIn',
		'bounceInDown'  => 'bounceInDown',
		'bounceInLeft'  => 'bounceInLeft',
		'bounceInRight' => 'bounceInRight',
		'bounceInUp'    => 'bounceInUp',
	);

	$animations['Fading Entrances'] = array(
		'fadeIn'         => 'fadeIn',
		'fadeInDown'     => 'fadeInDown',
		'fadeInDownBig'  => 'fadeInDownBig',
		'fadeInLeft'     => 'fadeInLeft',
		'fadeInLeftBig'  => 'fadeInLeftBig',
		'fadeInRight'    => 'fadeInRight',
		'fadeInRightBig' => 'fadeInRightBig',
		'fadeInUp'       => 'fadeInUp',
		'fadeInUpBig'    => 'fadeInUpBig',
	);

	$animations['Flippers'] = array(
		'flipInX' => 'flipInX',
		'flipInY' => 'flipInY',
	);

	$animations['Lightspeed'] = array(
		'lightSpeedIn' => 'lightSpeedIn',
	);

	$animations['Rotating Entrances'] = array(
		'rotateIn'          => 'rotateIn',
		'rotateInDownLeft'  => 'rotateInDownLeft',
		'rotateInDownRight' => 'rotateInDownRight',
		'rotateInUpLeft'    => 'rotateInUpLeft',
		'rotateInUpRight'   => 'rotateInUpRight',
	);

	$animations['Sliding Entrances'] = array(
		'slideInUp'    => 'slideInUp',
		'slideInDown'  => 'slideInDown',
		'slideInLeft'  => 'slideInLeft',
		'slideInRight' => 'slideInRight',
	);

	$animations['Zoom Entrances'] = array(
		'zoomIn'      => 'zoomIn',
		'zoomInDown'  => 'zoomInDown',
		'zoomInLeft'  => 'zoomInLeft',
		'zoomInRight' => 'zoomInRight',
		'zoomInUp'    => 'zoomInUp',
	);

	$animations['Specials'] = array(
		'rollIn' => 'rollIn',
	);

	return $animations;
}

/**
 * Return exit animation array.
 *
 * @since 1.0
 * @access public
 * @return $animations Array containig animation groups.
 */
function eewpb_get_exit_animations() {
	$animations = array();

	$animations['Bouncing Exits'] = array(
		'bounceOut'      => 'bounceOut',
		'bounceOutDown'  => 'bounceOutDown',
		'bounceOutLeft'  => 'bounceOutLeft',
		'bounceOutRight' => 'bounceOutRight',
		'bounceOutUp'    => 'bounceOutUp',
	);

	$animations['Fading Exits'] = array(
		'fadeOut'         => 'fadeOut',
		'fadeOutDown'     => 'fadeOutDown',
		'fadeOutDownBig'  => 'fadeOutDownBig',
		'fadeOutLeft'     => 'fadeOutLeft',
		'fadeOutLeftBig'  => 'fadeOutLeftBig',
		'fadeOutRight'    => 'fadeOutRight',
		'fadeOutRightBig' => 'fadeOutRightBig',
		'fadeOutUp'       => 'fadeOutUp',
		'fadeOutUpBig'    => 'fadeOutUpBig',
	);

	$animations['Flippers'] = array(
		'flipOutX' => 'flipOutX',
		'flipOutY' => 'flipOutY',
	);

	$animations['Lightspeed'] = array(
		'lightSpeedOut' => 'lightSpeedOut',
	);

	$animations['Rotating Exits'] = array(
		'rotateOut'          => 'rotateOut',
		'rotateOutDownLeft'  => 'rotateOutDownLeft',
		'rotateOutDownRight' => 'rotateOutDownRight',
		'rotateOutUpLeft'    => 'rotateOutUpLeft',
		'rotateOutUpRight'   => 'rotateOutUpRight',
	);

	$animations['Sliding Exits'] = array(
		'slideOutUp'    => 'slideOutUp',
		'slideOutDown'  => 'slideOutDown',
		'slideOutLeft'  => 'slideOutLeft',
		'slideOutRight' => 'slideOutRight',
	);

	$animations['Zoom Exits'] = array(
		'zoomOut'      => 'zoomOut',
		'zoomOutDown'  => 'zoomOutDown',
		'zoomOutLeft'  => 'zoomOutLeft',
		'zoomOutRight' => 'zoomOutRight',
		'zoomOutUp'    => 'zoomOutUp',
	);

	$animations['Specials'] = array(
		'rollOut' => 'rollOut',
	);

	return $animations;
}

/**
 * Insert elegant templates.
 *
 * @since 1.0
 * @param array $template Pre-built template.
 * @return array $templates
 */
function elegant_elements_wpbakery_insert_template( $template ) {
	return apply_filters( 'elegant_elements_wpbakery_templates', $template );
}

/**
 * Retrieve and return template data with image replacement.
 *
 * @since 1.0
 * @return void
 */
function eewpb_get_template_data() {
	// Check security.
	check_ajax_referer( 'elegant_templates_security', 'elegant_templates_security' );

	$template_content = ( isset( $_POST['template_content'] ) ) ? $_POST['template_content'] : '';
	$post_id          = ( isset( $_POST['post_id'] ) ) ? $_POST['post_id'] : '';
	$site_url         = get_site_url();
	$site_url         = str_replace( array( 'http://', 'https://' ), '', $site_url );

	if ( '' === $template_content ) {
		return;
	}

	// Get template content.
	$template_content = stripslashes( $template_content );

	// Retrieve all images from template content.
	$images = eewpb_get_all_image_urls( $template_content );

	// Get imported images array.
	$imported_images = get_option( 'elegant_imported_images', array() );

	// Loop through each image url and import if it is not already imported.
	if ( ! empty( $images ) ) {
		foreach ( $images['image'] as $image ) {
			if ( strpos( $image, $site_url ) == false ) {
				if ( isset( $imported_images[ $image ] ) ) {
					// Image is already imported, so just set the url from stored option.
					$imported_image = $imported_images[ $image ];
				} else {
					// Image isn't imported previously, so import it and use the new url and store it in imported option.
					$imported_image            = media_sideload_image( $image, $post_id, '', 'src' );
					$imported_images[ $image ] = $imported_image;
					update_option( 'elegant_imported_images', $imported_images );
				}

				$template_content = str_replace( $image, $imported_image, $template_content );
			}
		}
	}

	$updated_template_data = $template_content;

	echo wp_kses_post( $updated_template_data ); // phpcs:ignore.

	die();
}

add_action( 'wp_ajax_eewpb_get_template_data', 'eewpb_get_template_data' );

/**
 * Find image urls in content and retrieve urls by array.
 *
 * @since 1.0
 * @param string $content Template content.
 * @return array|null
 */
function eewpb_get_all_image_urls( $content ) {
	$pattern = '/((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{1,2}.)(\/\S*.(jpe?g|jpe|gif|png)\b)/i'; // find img tags and retrieve src.
	preg_match_all( $pattern, $content, $urls );

	if ( empty( $urls ) ) {
		return null;
	}

	$image_array = array();

	foreach ( $urls[0] as $index => $url ) {
		$image_array['image'][ $index ] = $urls[0][ $index ];
		$image_array['host'][ $index ]  = $urls[1][ $index ];
	}

	return $image_array;
}

/**
 * Material design colors for button element.
 *
 * @since 1.0
 * @return array Material button styles.
 */
function elegant_elements_wpbakery_material_button_styles() {
	$button_styles = array(
		'material-red'         => esc_attr__( 'Material - Red', 'elegant-elements' ),
		'material-pink'        => esc_attr__( 'Material - Pink', 'elegant-elements' ),
		'material-purple'      => esc_attr__( 'Material - Purple', 'elegant-elements' ),
		'material-deep-purple' => esc_attr__( 'Material - Deep Purple', 'elegant-elements' ),
		'material-indigo'      => esc_attr__( 'Material - Indigo', 'elegant-elements' ),
		'material-blue'        => esc_attr__( 'Material - Blue', 'elegant-elements' ),
		'material-light-blue'  => esc_attr__( 'Material - Light Blue', 'elegant-elements' ),
		'material-cyan'        => esc_attr__( 'Material - Cyan', 'elegant-elements' ),
		'material-teal'        => esc_attr__( 'Material - Teal', 'elegant-elements' ),
		'material-green'       => esc_attr__( 'Material - Green', 'elegant-elements' ),
		'material-light-green' => esc_attr__( 'Material - Light Green', 'elegant-elements' ),
		'material-lime'        => esc_attr__( 'Material - Lime', 'elegant-elements' ),
		'material-yellow'      => esc_attr__( 'Material - Yellow', 'elegant-elements' ),
		'material-amber'       => esc_attr__( 'Material - Amber', 'elegant-elements' ),
		'material-orange'      => esc_attr__( 'Material - Orange', 'elegant-elements' ),
		'material-deep-orange' => esc_attr__( 'Material - Deep Orange', 'elegant-elements' ),
		'material-brown'       => esc_attr__( 'Material - Brown', 'elegant-elements' ),
		'material-grey'        => esc_attr__( 'Material - Grey', 'elegant-elements' ),
		'material-blue-grey'   => esc_attr__( 'Material - Blue Grey', 'elegant-elements' ),
		'material-black'       => esc_attr__( 'Material - Black', 'elegant-elements' ),
	);

	return $button_styles;
}

/**
 * Builds the gradient color.
 *
 * @access public
 * @since 1.0
 * @param string $gradient_color_1   Gradient color 1.
 * @param string $gradient_color_2   Gradient color 2.
 * @param string $gradient_direction Gradient direction.
 * @param bool   $force_gradient     Option to use !important to force the gradient color.
 * @return string Generated gradient color.
 */
function eewpb_build_gradient_color( $gradient_color_1, $gradient_color_2, $gradient_direction = '0deg', $force_gradient = false ) {

	if ( '' === $gradient_color_1 || '' === $gradient_color_2 ) {
		return 'background-color:' . ( ( '' !== $gradient_color_1 ) ? $gradient_color_1 : $gradient_color_2 );
	}

	$gradient_top_color    = $gradient_color_1;
	$gradient_bottom_color = $gradient_color_2;

	if ( 'top' == $gradient_direction ) {
		// Safari 4-5, Chrome 1-9 support.
		$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
	} else {
		// Safari 4-5, Chrome 1-9 support.
		$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
	}

	// Safari 5.1, Chrome 10+ support.
	$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// Firefox 3.6+ support.
	$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// IE 10+ support.
	$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// Opera 11.10+ support.
	$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	return $gradient;
}

/**
 * Builds the gradient color with all the parameters.
 *
 * @access public
 * @since 1.0
 * @param string $angle          Gradient angle.
 * @param string $color_1        Gradient color 1.
 * @param string $color_2        Gradient color 2.
 * @param string $offset         Gradient offset.
 * @param string $color_1_offset First gradient color offset.
 * @param string $color_2_offset Second gradient color offset.
 * @return string Generated gradient color.
 */
function eewpb_gradient_color( $angle = '45', $color_1, $color_2, $offset = '50', $color_1_offset = '0', $color_2_offset = '100' ) {

	if ( '' === $color_1 || '' === $color_2 ) {
		return 'background-color:' . ( ( '' !== $color_1 ) ? $color_1 : $color_2 );
	}

	// General.
	$gradient = 'background: linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Safari 5.1, Chrome 10+ support.
	$gradient .= 'background: -webkit-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Firefox 3.6+ support.
	$gradient .= 'background: -moz-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// IE 10+ support.
	$gradient .= 'background: -ms-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Opera 11.10+ support.
	$gradient .= 'background: -o-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	return $gradient;
}

/**
 * Retrieve and return the saved templates.
 *
 * @since 1.0
 * @return array Array of saved templates.
 */
function elegant_get_template_collection() {
	$library_collection = array();

	if ( isset( $_POST['action'] ) ) { // @codingStandardsIgnoreLine
		return;
	}

	$args = array(
		'post_type'      => 'eewpb_template',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	$library_query = elegant_wpbakery_cached_query( $args );

	// Check if there are items available.
	if ( $library_query->have_posts() ) {
		// The loop.
		while ( $library_query->have_posts() ) :
			$library_query->the_post();
			$element_post_id = get_the_ID();

			$type          = get_post_type();
			$display_terms = '';
			$global        = '';
			$term_name     = '';

			$library_collection[ $term_name ][ $element_post_id ] = get_the_title();

		endwhile;

		// Restore original Post Data.
		wp_reset_postdata();
	}

	return $library_collection;
}

/**
 * Returns the embed url as per the provider.
 *
 * @access public
 * @since 1.0
 * @param string $provider Video provider.
 * @param string $video_id Video ID.
 * @param bool   $autoplay Autoplay video.
 * @return string
 */
function eewpb_get_embed_url_by_provider( $provider = 'youtube', $video_id = 'il2ZAZX9KpQ', $autoplay = false ) {
	$embed_url      = '';
	$autoplay_video = ( $autoplay ) ? 'autoplay=1' : '';

	switch ( $provider ) {
		case 'youtube':
			$embed_url = 'https://www.youtube.com/embed/' . $video_id . '?rel=0&start&end&controls=1&mute=0&modestbranding=0&' . $autoplay_video;
			break;
		case 'vimeo':
			$embed_url = 'https://player.vimeo.com/video/' . $video_id . '?' . $autoplay_video;
			break;
		case 'wistia':
			$embed_url = 'https://fast.wistia.net/embed/iframe/' . $video_id . '?dnt=1&videoFoam=true&' . $autoplay_video;
			break;
	}

	return $embed_url;
}

/**
 * Generate border styles.
 *
 * @since 1.0
 * @access public
 * @param array $atts Shortcode attributes.
 * @return string Generated border style.
 */
function eewpb_get_border_style( $atts ) {
	$style = '';

	$border_color    = ( isset( $atts['border_color'] ) ) ? $atts['border_color'] : '';
	$border_size     = ( isset( $atts['border_size'] ) ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $atts['border_size'], 'px' ) : '';
	$border_style    = ( isset( $atts['border_style'] ) ) ? $atts['border_style'] : '';
	$border_position = ( isset( $atts['border_position'] ) ) ? $atts['border_position'] : '';

	$border_radius_top_left     = isset( $atts['border_radius_top_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $atts['border_radius_top_left'], 'px' ) : '0px';
	$border_radius_top_right    = isset( $atts['border_radius_top_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $atts['border_radius_top_right'], 'px' ) : '0px';
	$border_radius_bottom_right = isset( $atts['border_radius_bottom_right'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $atts['border_radius_bottom_right'], 'px' ) : '0px';
	$border_radius_bottom_left  = isset( $atts['border_radius_bottom_left'] ) ? Elegant_Elements_WPBakery::validate_shortcode_attr_value( $atts['border_radius_bottom_left'], 'px' ) : '0px';
	$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
	$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '0px' : $border_radius;

	// Border.
	if ( $border_color && $border_size && $border_style ) {
		$border_direction = ( 'all' !== $border_position ) ? '-' . $border_position : '';
		$style           .= ( 'all' !== $border_position ) ? 'border:none;' : '';
		$style           .= 'border' . $border_direction . ':' . $border_size . ' ' . $border_style . ' ' . $border_color . ';';
	}

	// Border radius.
	if ( $border_radius ) {
		$style .= 'border-radius:' . esc_attr( $border_radius ) . ';';
	}

	return $style;
}

// Add basic oembed support for wistia.
wp_oembed_add_provider(
	'/https?\:\/\/(.+)?(wistia\.com|wi\.st|wistia\.net)\/.*/',
	'https://fast.wistia.com/oembed',
	true
);

/**
 * Returns a big old hunk of JSON from a non-private IG account page.
 * based on https://gist.github.com/cosmocatalano/4544576.
 *
 * @access public
 * @since 1.0
 * @param string $username      Instagram username.
 * @param string $target        Link target.
 * @param int    $limit         Images to display.
 * @param string $size          Image size.
 * @param bool   $show_likes    Show likes.
 * @param bool   $show_comments Show comments.
 * @param string $layout        Gallery layout.
 * @param bool   $fallback      Whether to use to JS fallback or not.
 * @return string|WP_Error
 */
function eewpb_scrape_instagram( $username, $target = '_self', $limit = 9, $size = 'large', $show_likes = false, $show_comments = false, $layout = 'grid', $fallback = true ) {

	$username = trim( strtolower( $username ) );
	$error    = '';

	$show_likes    = 'no';
	$show_comments = 'no';

	$api_data = get_option( 'elegant_elements_wpbakery_instagram_api_data', array() );
	if ( isset( $api_data['access_token'] ) ) {
		$username = $api_data['username'];

		$instagram = get_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $username ) );
		if ( false === $instagram ) {
			$media_api = 'https://graph.instagram.com/me/media?fields=id,caption,media_url,media_type,permalink,thumbnail_url&access_token=' . $api_data['access_token'] . '&limit=' . $limit;

			$response       = wp_remote_get( $media_api );
			$media_response = wp_remote_retrieve_body( $response );
			$media_response = json_decode( $media_response, true );
			$media_response = $media_response['data'];

			$instagram_media = array();
			foreach ( $media_response as $key => $media ) {
				$type    = $media['media_type'];
				$caption = __( 'Instagram Image', 'elegant-elements' );

				if ( isset( $media['caption'] ) && '' !== $media['caption'] ) {
					$caption = wp_kses( $media['caption'], array() );
				}

				$instagram_media[] = array(
					'description' => $caption,
					'link'        => $media['permalink'],
					'time'        => '',
					'comments'    => '',
					'likes'       => '',
					'thumbnail'   => ( isset( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'] ),
					'small'       => ( isset( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'] ),
					'large'       => $media['media_url'],
					'original'    => $media['media_url'],
					'type'        => $type,
				);
			} // End foreach().

			if ( ! empty( $instagram_media ) ) {
				$instagram = base64_encode( serialize( $instagram_media ) ); // @codingStandardsIgnoreLine
				set_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}

			return $instagram_media;
		} else {
			if ( ! empty( $instagram ) ) {
				return unserialize( base64_decode( $instagram ) ); // @codingStandardsIgnoreLine
			} else {
				return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'elegant-elements' ) );
			}
		}
	} else {
		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://www.instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;

			default:
				$url              = 'https://www.instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}

		$instagram = get_transient( 'ee-insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) );
		if ( false === $instagram ) {

			$remote = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'referer' => 'facebook.com',
					),
				)
			);

			if ( is_wp_error( $remote ) ) {
				$error  = new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'elegant-elements' ) );
				$remote = array();
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				$error = new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'elegant-elements' ) );
			}

			$shards = explode( 'window._sharedData = ', $remote['body'] );

			if ( isset( $shards[1] ) ) {
				$insta_json  = explode( ';</script>', $shards[1] );
				$insta_array = json_decode( $insta_json[0], true );
			} else {
				$insta_array = false;
			}

			if ( ! $insta_array ) {
				$error = new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			if ( ( isset( $insta_array['entry_data']['LoginAndSignupPage'] ) && true === $fallback ) || '' !== $error ) {
				$url   = $url . '/channel/?__a=1';
				$error = '';

				$remote = wp_remote_get(
					$url,
					array(
						'headers' => array(
							'referer' => 'facebook.com',
						),
					)
				);

				$insta_json  = $remote['body'];
				$insta_array = json_decode( $insta_json, true );

				if ( null === $insta_array ) {
					$insta_array                                     = array();
					$insta_array['entry_data']['LoginAndSignupPage'] = true;
				}

				if ( is_wp_error( $remote ) ) {
					$error  = new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'elegant-elements' ) );
					$remote = array();
				}

				if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
					$error = new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'elegant-elements' ) );
				}
			}

			if ( ( isset( $insta_array['entry_data']['LoginAndSignupPage'] ) && true === $fallback ) || '' !== $error ) {
				$user_id = str_replace( array( '@', '#', '.' ), '', $username );
				$ulclass = apply_filters( 'elegant_instagram_list_class', 'elegant-instagram-pics elegant-instagram-size-' . $size );
				$liclass = apply_filters( 'elegant_instagram_item_class', 'elegant-instagram-pic' );
				$aclass  = apply_filters( 'elegant_instagram_a_class', 'elegant-instagram-pic-link' );
				ob_start();
				?>
				<div <?php echo Elegant_Elements_WPBakery::attributes( 'elegant-instagram-gallery' ); ?>>
					<ul class="instagram-pics-with-js-<?php echo esc_attr( $user_id ); ?> <?php echo esc_attr( $ulclass ); ?>">
						<?php echo esc_html__( 'Loading Instagram data.', 'elegant-elements' ); ?>
					</ul>
				</div>
				<script type="text/javascript">
					jQuery.get( '<?php echo esc_attr( $url ); ?>', function( data ) {
						var images = '',
							imagesArray = [],
							target = '<?php echo esc_attr( $target ); ?>',
							size = '<?php echo esc_attr( $size ); ?>',
							imagesData = '',
							likes_comments = '',
							show_likes = '<?php echo $show_likes; ?>',
							show_comments = '<?php echo $show_comments; ?>';

						if ( 'undefined' === typeof data.graphql ) {
							return false;
						}

						if ( 'undefined' !== typeof data.graphql.user ) {
							images = data.graphql.user.edge_owner_to_timeline_media.edges;
						} else if ( 'undefined' !== typeof data.graphql.hashtag ) {
							images = data.graphql.hashtag.edge_hashtag_to_media.edges;
						}

						images = images.slice( 0, parseInt( '<?php echo esc_attr( $limit ); ?>') );

						jQuery.each( images, function( index, item ) {
							var $caption = '<?php echo esc_attr__( 'Instagram Image', 'elegant-elements' ); ?>',
								$instagram = [],
								comments = item.node.edge_media_to_comment.count,
								likes = item.node.edge_liked_by.count,
								likes_comments = '';

							if ( item.node.edge_media_to_caption.edges.length && 'undefined' !== item.node.edge_media_to_caption.edges[0]['node'] ) {
								$caption = item.node.edge_media_to_caption.edges[0]['node']['text'];
							}

							$instagram = {
								description: $caption,
								link: '//www.instagram.com/p/' + item.node.shortcode,
								time: item.node.taken_at_timestamp,
								thumbnail: item.node.thumbnail_resources[0]['src'],
								small: item.node.thumbnail_resources[2]['src'],
								large: item.node.thumbnail_resources[4]['src'],
								original: item.node.display_url,
							};

							if ( 'no' !== show_likes ) {
								likes_comments += '<span class="elegant-instagram-likes fa fa-heart"> ' + likes + '</span>';
							}

							if ( 'no' !== show_comments ) {
								likes_comments += '<span class="elegant-instagram-comments fa fa-comment"> ' + comments + '</span>';
							}

							if ( 'lightbox' !== target ) {
								imagesData += '<li class="<?php echo esc_attr( $liclass ); ?>">';
								imagesData += '<div class="elegant-instagram-pic-wrapper">';
								imagesData += '<a class="<?php echo esc_attr( $aclass ); ?>" href="' + $instagram.link + '" target="' + target + '">';
								imagesData += '<img src="' + $instagram[ size ] + '"  alt="' + $instagram.description + '" title="' + $instagram.description + '"/>';
								imagesData += '</a>';

								if ( '' !== likes_comments ) {
									imagesData += '<div class="elegant-instagram-pic-likes">';
									imagesData += likes_comments;
									imagesData += '</div>';
								}

								imagesData += '</div>';
								imagesData += '</li>';
							} else {
								imagesData += '<li class="<?php echo esc_attr( $liclass ); ?>">';
								imagesData += '<div class="elegant-instagram-pic-wrapper">';
								imagesData += '<a href="' + $instagram.original + '&type=.jpg" class="elegant-lightbox prettyphoto elegant-instagram-pic-link" data-rel="iLightbox[gallery_image_<?php echo $user_id; ?>]">';
								imagesData += '<img src="' + $instagram[ size ] + '">';
								imagesData += '</a>';

								if ( '' !== likes_comments ) {
									imagesData += '<div class="elegant-instagram-pic-likes">';
									imagesData += likes_comments;
									imagesData += '</div>';
								}

								imagesData += '</div>';
								imagesData += '</li>';
							}
						} );

						jQuery( '.instagram-pics-with-js-<?php echo esc_attr( $user_id ); ?>' ).html( imagesData ).promise().done( function() {
							<?php
							if ( 'grid' !== $layout ) {
								?>
								var galleryItems = jQuery( this ).find( 'li' );
								jQuery( this ).find( '.elegant-instagram-pics' ).css( 'opacity', '0' );
								jQuery( document ).trigger( 'instagramGalleryLoaded', { items: galleryItems } );
								<?php
							}
							?>
						} );
					} );
				</script>
				<?php
				$content = ob_get_clean();
				return new WP_Error( 'bad_json_2', $content );
			}

			$user = array();

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'] ) ) {
				$user = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'];
			} elseif ( isset( $insta_array['graphql']['user'] ) ) {
				$user = $insta_array['graphql']['user'];
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} elseif ( isset( $insta_array['graphql']['user'] ) ) {
				$images = $insta_array['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			$instagram = array();

			// If user is available, add to the array.
			if ( ! empty( $user ) ) {
				$instagram['user'] = $user;
			}

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'elegant-elements' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//www.instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][1]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][3]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) ); // @codingStandardsIgnoreLine
				set_transient( 'ee-insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( base64_decode( $instagram ) ); // @codingStandardsIgnoreLine
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'elegant-elements' ) );
		}
	}
}

/**
 * Converts a number into a short version, eg: 1000 -> 1k
 *
 * @since 1.0
 * @access public
 * @param int $number    Integer number.
 * @param int $precision Decimal precision.
 * @return string Number format.
 */
function eewpb_number_format_short( $number, $precision = 1 ) {
	if ( $number < 999 ) {
		// 0 - 999
		$number_format = number_format( $number, $precision );
		$suffix        = '';
	} elseif ( $number < 999999 ) {
		// 1k-999k
		$number_format = number_format( $number / 1000, $precision );
		$suffix        = 'K';
	} elseif ( $number < 999999999 ) {
		// 1m-999m
		$number_format = number_format( $number / 1000000, $precision );
		$suffix        = 'M';
	} else {
		// 1b+
		$number_format = number_format( $number / 1000000000, $precision );
		$suffix        = 'B';
	}

	// Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1".
	if ( $precision > 0 ) {
		$dotzero       = '.' . str_repeat( '0', $precision );
		$number_format = str_replace( $dotzero, '', $number_format );
	}

	return $number_format . $suffix;
}

/**
 * Returns an array of visibility options.
 *
 * @since 1.0
 * @param string $type whether to return full array or values only.
 * @return array
 */
function elegant_elements_visibility_options( $type ) {
	$vc_edit            = ( isset( $_GET['vc-edit'] ) ); // @codingStandardsIgnoreLine
	$visibility_options = array(
		'small-visibility'  => $vc_edit ? '<span class="eewpb-mobile"></span>|' . esc_attr__( 'Small Screen', 'elegant-elements' ) : esc_attr__( 'Small Screen', 'elegant-elements' ),
		'medium-visibility' => $vc_edit ? '<span class="eewpb-tablet"></span>|' . esc_attr__( 'Medium Screen', 'elegant-elements' ) : esc_attr__( 'Medium Screen', 'elegant-elements' ),
		'large-visibility'  => $vc_edit ? '<span class="eewpb-desktop"></span>|' . esc_attr__( 'Large Screen', 'elegant-elements' ) : esc_attr__( 'Large Screen', 'elegant-elements' ),
	);

	if ( 'values' === $type ) {
		$visibility_options = array_keys( $visibility_options );
	}

	return $visibility_options;
}

/**
 * Returns an array of default visibility options.
 *
 * @since 1.0
 * @param  string $type either array or string to return.
 * @return string|array
 */
function elegant_elements_default_visibility( $type ) {

	$default_visibility = elegant_elements_visibility_options( 'values' );

	if ( 'string' === $type ) {
		$default_visibility = implode( ', ', $default_visibility );
	}

	return $default_visibility;
}

/**
 * Reverses the visibility selection and adds to attribute array.
 *
 * @since 1.0
 * @param string|array $selection Devices selected to be shown on.
 * @param array        $attr      Current attributes to add to.
 * @return array
 */
function elegant_elements_visibility_atts( $selection, $attr ) {
	$visibility_values = elegant_elements_visibility_options( 'values' );

	// If empty, show all.
	if ( empty( $selection ) ) {
		$selection = $visibility_values;
	}

	// If no is used, change that to all options selected, as fallback.
	if ( 'no' === $selection ) {
		$selection = $visibility_values;
	}

	// If yes is used, use all selections with mobile visibility removed.
	if ( 'yes' === $selection ) {
		$key = array_search( 'small-visibility', $visibility_values, true );
		if ( false !== $key ) {
			unset( $visibility_values[ $key ] );
			$selection = $visibility_values;
		}
	}

	// Make sure the selection is an array.
	if ( ! is_array( $selection ) ) {
		$selection = explode( ',', str_replace( ' ', '', $selection ) );
	}

	$visibility_options = elegant_elements_visibility_options( 'values' );

	foreach ( $visibility_options as $visibility_option ) {
		if ( ! in_array( $visibility_option, $selection ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( is_array( $attr ) ) {
				$attr['class'] .= ( ( $attr['class'] ) ? ' eewpb-no-' . $visibility_option : 'eewpb-no-' . $visibility_option );
			} else {
				$attr .= ( ( $attr ) ? ' eewpb-no-' . $visibility_option : 'eewpb-no-' . $visibility_option );
			}
		}
	}

	return $attr;
}

/**
 * Return the array with icons for alignment.
 *
 * @since 1.0
 * @return array
 */
function elegant_get_alignment_icons() {
	return array(
		'left'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 15h14v2H3v-2zm0-5h18v2H3v-2zm0-5h14v2H3V9z"/></svg>',
		'center' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm2 15h14v2H5v-2zm-2-5h18v2H3v-2zm2-5h14v2H5V9z"/></svg>',
		'right'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm4 15h14v2H7v-2zm-4-5h18v2H3v-2zm4-5h14v2H7V9z"/></svg>',
	);
}

/**
 * Return the array with icons for alignment including justified alignment.
 *
 * @since 1.0
 * @return array
 */
function elegant_get_four_alignment_icons() {
	$default_alignments              = elegant_get_alignment_icons();
	$default_alignments['justified'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 15h18v2H3v-2zm0-5h18v2H3v-2zm0-5h18v2H3V9z"/></svg>';

	return $default_alignments;
}

/**
 * Return the array with icons for directions.
 *
 * @since 1.0
 * @return array
 */
function elegant_get_direction_icons() {
	return array(
		'left'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg>',
		'right'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>',
		'top'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 7.828V20h-2V7.828l-5.364 5.364-1.414-1.414L12 4l7.778 7.778-1.414 1.414L13 7.828z"/></svg>',
		'bottom' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 16.172l5.364-5.364 1.414 1.414L12 20l-7.778-7.778 1.414-1.414L11 16.172V4h2v12.172z"/></svg>',
	);
}

/**
 * Return the array with icons for devices.
 *
 * @since 1.0
 * @return array
 */
function elegant_get_visibility_icons() {
	return array(
		'small-visibility'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7 4v16h10V4H7zM6 2h12a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm6 15a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>',
		'medium-visibility' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6 4v16h12V4H6zM5 2h14a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm7 15a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>',
		'large-visibility'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16h16V5H4v11zm9 2v2h4v2H7v-2h4v-2H2.992A.998.998 0 0 1 2 16.993V4.007C2 3.451 2.455 3 2.992 3h18.016c.548 0 .992.449.992 1.007v12.986c0 .556-.455 1.007-.992 1.007H13z"/></svg>',
	);
}

/**
 * Generate Gogle Fonts styling from the shortcode attributes for the given param.
 *
 * @since 1.0
 * @param array  $atts       Shortode attributes.
 * @param string $param_name Google font param name.
 * @return string Generated Google font CSS.
 */
function elegant_get_google_font_styling( $atts, $param_name ) {
	$google_fonts_param = new Vc_Google_Fonts();
	$field_settings     = array(
		'font_family_description' => esc_attr__( 'Select font family.', 'elegant-elements' ),
		'font_style_description'  => esc_attr__( 'Select font styling.', 'elegant-elements' ),
	);
	$fonts_data         = strlen( $atts[ $param_name ] ) > 0 ? $google_fonts_param->_vc_google_fonts_parse_attributes( $field_settings, $atts[ $param_name ] ) : '';

	elegant_enqueue_google_fonts( $fonts_data );

	$font_styles = elegant_google_fonts_styles( $fonts_data );
	$style       = esc_attr( implode( ';', $font_styles ) );

	return $style . ';';
}

/**
 * Generate Gogle Fonts css for inline use in the shortcode output.
 *
 * @since 1.0
 * @param array $fonts_data Google font data retrieved from shortcode attributes.
 * @return array Generated Google font CSS as array of css attributes.
 */
function elegant_google_fonts_styles( $fonts_data ) {
	// Inline styles.
	$font_family = explode( ':', $fonts_data['values']['font_family'] );
	$styles[]    = 'font-family:' . $font_family[0];
	$font_styles = explode( ':', $fonts_data['values']['font_style'] );

	if ( isset( $font_styles[1] ) && '' !== $font_styles[1] ) {
		$styles[] = 'font-weight:' . $font_styles[1];
	}

	if ( isset( $font_styles[2] ) && '' !== $font_styles[2] ) {
		$styles[] = 'font-style:' . $font_styles[2];
	}

	return $styles;
}

/**
 * Enqueue the Gogle Fonts.
 *
 * @since 1.0
 * @param array $fonts_data Google font data retrieved from shortcode attributes.
 * @return void
 */
function elegant_enqueue_google_fonts( $fonts_data ) {
	// Get extra subsets for settings (latin/cyrillic/etc).
	$settings = get_option( 'wpb_js_google_fonts_subsets' );

	// Add subsets to request if selected any.
	if ( is_array( $settings ) && ! empty( $settings ) ) {
		$subsets = '&subset=' . implode( ',', $settings );
	} else {
		$subsets = '';
	}

	// We also need to enqueue font from googleapis.
	if ( isset( $fonts_data['values']['font_family'] ) && '' !== $fonts_data['values']['font_family'] ) {
		wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?display=swap&family=' . $fonts_data['values']['font_family'] . $subsets, '', '1.0' );
	}
}

/**
 * Returns a cached query.
 * If the query is not cached then it caches it and returns the result.
 *
 * @since 1.0
 * @param string|array $args Same as in WP_Query.
 * @return object
 */
function elegant_wpbakery_cached_query( $args ) {
	$query_id = md5( maybe_serialize( $args ) );
	$query    = wp_cache_get( $query_id, 'eewpb_query' );

	if ( false === $query ) {
		$query = new WP_Query( $args );
		wp_cache_set( $query_id, $query, 'eewpb_query' );
	}

	return $query;
}

add_action( 'wp_ajax_wpb_edit_hotspot_form', 'elegant_render_hotspot_item_fields' );
/**
 * Returns a settings edit form for the hotspot element.
 *
 * @since 1.0
 * @return void
 */
function elegant_render_hotspot_item_fields() {
	$tag    = vc_post_param( 'tag' );
	$items  = vc_post_param( 'shortcodeItems' );
	$params = array();
	$output = '';

	require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-edit-form-fields.php' );

	foreach ( $items as $key => $params ) {
		$index  = $key + 1;
		$params = (array) stripslashes_deep( $params );
		$params = array_map( 'vc_htmlspecialchars_decode_deep', $params );
		$fields = new Vc_Edit_Form_Fields( $tag, $params );

		ob_start();
		echo wp_kses_post( $fields->render() );
		$render = ob_get_clean();

		$output .= '<div class="elegant-hotspot-content" data-rel="' . $index . '"><div class="wpb_element_label"><span><a class="delete" href="#" title="Delete Hotspot"><i class="fa fa-trash"></i></a></span> Hotspot <i>Number <span class="num">' . $index . '</span></i></div>' . $render . '</div>';
	}

	wp_die( $output );
}

/**
 * Returns svg shape divider for top.
 *
 * @since 1.0
 * @param string $divider_shape Shape divider type.
 * @param string $fill_color    Shape divider fill color.
 * @param string $bg_color      Shape divider background color.
 * @return string
 */
function elegant_get_top_divider_shape( $divider_shape, $fill_color, $bg_color ) {
	$shape_divider_svg = '';

	switch ( $divider_shape ) {

		case 'curve':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 0 c 0 0 200 50 500 50 s 500 -50 500 -50 v 101 h -1000 v -100 z"></path> </svg>';
			break;
		case 'slant-2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0 0v140h1280L0 0z" fill-opacity=".5"/><path d="M0 42v98h1280L0 42z"/> </svg>';
			break;
		case 'triangle_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"> <path xmlns="http://www.w3.org/2000/svg" d="M640 140L1280 0H0z" fill-opacity=".5"/><path xmlns="http://www.w3.org/2000/svg" d="M640 98l640-98H0z"/> </svg>';
			break;
		case 'curve_asym':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 20 0 50 0 100 100 Z"></path> </svg>';
			break;
		case 'curve_asym_2':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 50 0 80 0 100 100 Z"></path> </svg>';
			break;
		case 'tilt':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-right" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 10 0 0 0 10"></polygon> </svg>';
			break;
		case 'tilt_flip':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 0 104 10 0 10"></polygon> </svg>';
			break;
		case 'tilt_alt':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="100 10 100 0 -4 10"></polygon> </svg>';
			break;
		case 'triangle':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <polygon  points="501 53.27 0.5 0.56 0.5 100 1000.5 100 1000.5 0.66 501 53.27"/></svg>';
			break;
		case 'fan':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1003.92 91" preserveAspectRatio="none">  <polygon class="cls-1" points="502.46 46.31 1 85.67 1 91.89 1002.91 91.89 1002.91 85.78 502.46 46.31"/><polygon class="cls-2" points="502.46 45.8 1 0 1 91.38 1002.91 91.38 1002.91 0.1 502.46 45.8"/><rect class="cls-3" y="45.81" width="1003.92" height="46.09"/>
	</svg>';
			break;
		case 'fan2':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none">  <path d="M1280 0L640 70 0 0v140l640-70 640 70V0z" fill-opacity=".5"/><path d="M1280 0H0l640 70 640-70z"/> </svg>';
			break;

		case 'ramp':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 0s573.08 140 1280 140V0z"/> </svg>';
			break;
		case 'ramp_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 0v60s573.09 80 1280 80V0z" fill-opacity=".3"/><path d="M0 0v30s573.09 110 1280 110V0z" fill-opacity=".5"/><path d="M0 0s573.09 140 1280 140V0z"/> </svg>';
			break;

		case 'rocks':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 70.35l320-49.24 640 98.49 320-49.25V140H0V70.35z"/> </svg>';
			break;
		case 'rocks_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none">  <path d="M0 90.72l140-28.28 315.52 24.14L796.48 65.8 1140 104.89l140-14.17V0H0v90.72z" fill-opacity=".5"/><path d="M0 0v47.44L170 0l626.48 94.89L1110 87.11l170-39.67V0H0z"/> </svg>';
			break;
		case 'wave1':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M320 28C160 28 80 49 0 70V0h1280v70c-80 21-160 42-320 42-320 0-320-84-640-84z"/> </svg>';
			break;
		case 'wave2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 51.76c36.21-2.25 77.57-3.58 126.42-3.58 320 0 320 57 640 57 271.15 0 312.58-40.91 513.58-53.4V0H0z" fill-opacity=".3"/><path d="M0 24.31c43.46-5.69 94.56-9.25 158.42-9.25 320 0 320 89.24 640 89.24 256.13 0 307.28-57.16 481.58-80V0H0z" fill-opacity=".5"/><path d="M0 0v3.4C28.2 1.6 59.4.59 94.42.59c320 0 320 84.3 640 84.3 285 0 316.17-66.85 545.58-81.49V0z"/> </svg>';
			break;
		case 'waves_opacity_3':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M504.854,80.066c7.812,0,14.893,0.318,21.41,0.879 c-25.925,22.475-56.093,40.852-102.946,40.852c-20.779,0-37.996-2.349-52.898-6.07C413.517,107.295,434.056,80.066,504.854,80.066z M775.938,51.947c19.145,18.596,39.097,35.051,77.956,35.051c46.907,0,62.299-14.986,80.912-24.98 c-21.357-15.783-46.804-28.348-85.489-28.348C816.829,33.671,794.233,41.411,775.938,51.947z" fill-opacity=".3"/><path d="M1200.112,46.292c39.804,0,59.986,22.479,79.888,39.69v16.805 c-19.903-10.835-40.084-21.777-79.888-21.777c-72.014,0-78.715,43.559-147.964,43.559c-56.84,0-81.247-35.876-117.342-62.552 c9.309-4.998,19.423-8.749,34.69-8.749c55.846,0,61.99,39.617,115.602,39.617C1143.177,92.887,1142.618,46.292,1200.112,46.292z M80.011,115.488c-40.006,0-60.008-12.206-80.011-29.506v16.806c20.003,10.891,40.005,21.782,80.011,21.782 c80.004,0,78.597-30.407,137.669-30.407c55.971,0,62.526,24.026,126.337,24.026c9.858,0,18.509-0.916,26.404-2.461 c-57.186-14.278-80.177-48.808-138.66-48.808C154.698,66.919,131.801,115.488,80.011,115.488z M526.265,80.945 c56.848,4.902,70.056,28.726,137.193,28.726c54.001,0,73.43-35.237,112.48-57.724C751.06,27.782,727.548,0,665.691,0 C597.381,0,567.086,45.555,526.265,80.945z" fill-opacity=".5"/><path d="M0,0v85.982c20.003,17.3,40.005,29.506,80.011,29.506c51.791,0,74.688-48.569,151.751-48.569 c58.482,0,81.473,34.531,138.66,48.808c43.096-8.432,63.634-35.662,134.433-35.662c7.812,0,14.893,0.318,21.41,0.879 C567.086,45.555,597.381,0,665.691,0c61.856,0,85.369,27.782,110.246,51.947c18.295-10.536,40.891-18.276,73.378-18.276 c38.685,0,64.132,12.564,85.489,28.348c9.309-4.998,19.423-8.749,34.69-8.749c55.846,0,61.99,39.617,115.602,39.617 c58.08,0,57.521-46.595,115.015-46.595c39.804,0,59.986,22.479,79.888,39.69V0H0z"/> </svg>';
			break;

		case 'asym':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M978.81 122.25L0 0h1280l-262.1 116.26a73.29 73.29 0 0 1-39.09 5.99z"/> </svg>';
			break;
		case 'asym2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M978.81 122.25L0 0h1280l-262.1 116.26a73.29 73.29 0 0 1-39.09 5.99z" fill-opacity=".5"/><path d="M983.19 95.23L0 0h1280l-266 91.52a72.58 72.58 0 0 1-30.81 3.71z"/> </svg>';
			break;
		case 'graph2':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M1214.323 66.051h-79.863l-70.793 18.224-66.285-10.933-83.672 22.953-97.601-7.328-73.664 22.125-76.961 8.475-82.664-13.934-76.926 11.832-94.453-7.666-90.137 17.059-78.684-9.731-86.363 13.879-95.644 3.125L0 126.717V0h1280l-.001 35.844z" fill-opacity=".5"/><path d="M0 0h1280v.006l-70.676 36.578-74.863 4.641-70.793 23.334-66.285-11.678-83.672 29.618-97.602-7.07-63.664 21.421-76.961 12.649-91.664-20.798-77.926 17.66-94.453-7.574-90.137 21.595-78.683-9.884-86.363 16.074-95.645 6.211L0 127.905z"/> </svg>';
			break;
		case 'graph1':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-left" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M156 35.41l95.46 34.73 120.04.25 71.5 33.24 90.09-3.89L640 137.25l102.39-37.06 85.55 10.61 88.11-7.17L992 65.08l73.21 5.31L1132 48.35l77-.42L1280 0H0l64.8 38.57 91.2-3.16z" fill-opacity=".5"/><path d="M156 28.32l95.46 27.79 120.04.2L443 82.9l90.09-3.11L640 109.8l102.39-29.65 85.55 8.49 88.11-5.74L992 52.07l73.21 4.24L1132 38.68l77-.34L1280 0H0l64.8 30.86 91.2-2.54z"/> </svg>';
			break;
		case 'clouds2':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 86" preserveAspectRatio="xMidYMid slice"> <path d="M1280 0H0v65.2c6.8 0 13.5.9 20.1 2.6 14-21.8 43.1-28 64.8-14 5.6 3.6 10.3 8.3 14 13.9 7.3-1.2 14.8-.6 21.8 1.6 2.1-37.3 34.1-65.8 71.4-63.7 24.3 1.4 46 15.7 56.8 37.6 19-17.6 48.6-16.5 66.3 2.4C323 54 327.4 65 327.7 76.5c.4.2.8.4 1.2.7 3.3 1.9 6.3 4.2 8.9 6.9 15.9-23.8 46.1-33.4 72.8-23.3 11.6-31.9 46.9-48.3 78.8-36.6 9.1 3.3 17.2 8.7 23.8 15.7 6.7-6.6 16.7-8.4 25.4-4.8 29.3-37.4 83.3-44 120.7-14.8 14 11 24.3 26.1 29.4 43.1 4.7.6 9.3 1.8 13.6 3.8 7.8-24.7 34.2-38.3 58.9-30.5 14.4 4.6 25.6 15.7 30.3 30 14.2 1.2 27.7 6.9 38.5 16.2 11.1-35.7 49-55.7 84.7-44.7 14.1 4.4 26.4 13.3 35 25.3 12-5.7 26.1-5.5 37.9.6 3.9-11.6 15.5-18.9 27.7-17.5.2-.3.3-.6.5-.9 23.3-41.4 75.8-56 117.2-32.6 14.1 7.9 25.6 19.7 33.3 33.8 28.8-23.8 71.5-19.8 95.3 9 2.6 3.1 4.9 6.5 6.9 10 3.8-.5 7.6-.8 11.4-.8L1280 0z"/> </svg>';
			break;
		case 'clouds3':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M833.9 27.5c-5.8 3.2-11 7.3-15.5 12.2-7.1-6.9-17.5-8.8-26.6-5-30.6-39.2-87.3-46.1-126.5-15.5-1.4 1.1-2.8 2.2-4.1 3.4C674.4 33.4 684 48 688.8 64.3c4.7.6 9.3 1.8 13.6 3.8 7.8-24.7 34.2-38.3 58.9-30.5 14.4 4.6 25.6 15.7 30.3 30 14.2 1.2 27.7 6.9 38.5 16.2C840.6 49.6 876 29.5 910.8 38c-20.4-20.3-51.8-24.6-76.9-10.5zM384 43.9c-9 5-16.7 11.9-22.7 20.3 15.4-7.8 33.3-8.7 49.4-2.6 3.7-10.1 9.9-19.1 18.1-26-15.4-2.3-31.2.6-44.8 8.3zm560.2 13.6c2 2.2 3.9 4.5 5.7 6.9 5.6-2.6 11.6-4 17.8-4.1-7.6-2.4-15.6-3.3-23.5-2.8zM178.7 7c29-4.2 57.3 10.8 70.3 37 8.9-8.3 20.7-12.8 32.9-12.5C256.4 1.8 214.7-8.1 178.7 7zm146.5 56.3c1.5 4.5 2.4 9.2 2.5 14 .4.2.8.4 1.2.7 3.3 1.9 6.3 4.2 8.9 6.9 5.8-8.7 13.7-15.7 22.9-20.5-11.1-5.2-23.9-5.6-35.5-1.1zM33.5 54.9c21.6-14.4 50.7-8.5 65 13 .1.2.2.3.3.5 7.3-1.2 14.8-.6 21.8 1.6.6-10.3 3.5-20.4 8.6-29.4.3-.6.7-1.2 1.1-1.8-32.1-17.2-71.9-10.6-96.8 16.1zm1228.9 2.7c2.3 2.9 4.4 5.9 6.2 9.1 3.8-.5 7.6-.8 11.4-.8V48.3c-6.4 1.8-12.4 5-17.6 9.3zM1127.3 11c1.9.9 3.7 1.8 5.6 2.8 14.2 7.9 25.8 19.7 33.5 34 13.9-11.4 31.7-16.9 49.6-15.3-20.5-27.7-57.8-36.8-88.7-21.5z" fill-opacity=".5"/><path d="M0 0v66c6.8 0 13.5.9 20.1 2.6 3.5-5.4 8.1-10.1 13.4-13.6 24.9-26.8 64.7-33.4 96.8-16 10.5-17.4 28.2-29.1 48.3-32 36.1-15.1 77.7-5.2 103.2 24.5 19.7.4 37.1 13.1 43.4 31.8 11.5-4.5 24.4-4.2 35.6 1.1l.4-.2c15.4-21.4 41.5-32.4 67.6-28.6 25-21 62.1-18.8 84.4 5.1 6.7-6.6 16.7-8.4 25.4-4.8 29.2-37.4 83.3-44.1 120.7-14.8l1.8 1.5c37.3-32.9 94.3-29.3 127.2 8 1.2 1.3 2.3 2.7 3.4 4.1 9.1-3.8 19.5-1.9 26.6 5 24.3-26 65-27.3 91-3.1.5.5 1 .9 1.5 1.4 12.8 3.1 24.4 9.9 33.4 19.5 7.9-.5 15.9.4 23.5 2.8 7-.1 13.9 1.5 20.1 4.7 3.9-11.6 15.5-18.9 27.7-17.5.2-.3.3-.6.5-.9 22.1-39.2 70.7-54.7 111.4-35.6 30.8-15.3 68.2-6.2 88.6 21.5 18.3 1.7 35 10.8 46.5 25.1 5.2-4.3 11.1-7.4 17.6-9.3V0H0z"/> </svg>';
			break;
		case 'waves':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-right" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none"> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
			break;
		case 'speech':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 45.86 h 458 c 29 0 42 19.27 42 19.27 s 13 -19.27 42.74 -19.27 h 457.26 v 54.14 h -1000 z"></path>  </svg>';
			break;
		case 'clouds':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 983.71 4.47 a 56.19 56.19 0 0 0 -37.61 14.38 a 15.24 15.24 0 0 0 -25.55 -0.55 a 40.65 40.65 0 0 0 -55.45 13 a 15.63 15.63 0 0 0 -22.69 1.52 a 73.82 73.82 0 0 0 -98.57 27.91 a 14.72 14.72 0 0 0 -9.31 0.55 a 26.13 26.13 0 0 0 -42.63 1.92 a 39.08 39.08 0 0 0 -47 10.08 a 18.45 18.45 0 0 0 -34.18 -0.45 a 12.21 12.21 0 0 0 -14.23 0.9 a 11.47 11.47 0 0 0 -16.59 -6 a 47.2 47.2 0 0 0 -66.12 -4.07 a 21.32 21.32 0 0 0 -26.48 -4.91 a 15 15 0 0 0 -29 -7.79 a 10.47 10.47 0 0 0 -14 5.13 a 31.55 31.55 0 0 0 -50.68 12.32 a 23 23 0 0 0 -28.69 -5.34 a 54.54 54.54 0 0 0 -89.93 5.71 a 16.3 16.3 0 0 0 -22.71 2.3 a 33.41 33.41 0 0 0 -44.93 9.65 a 17.72 17.72 0 0 0 -9.79 -2.94 h -0.22 a 29 29 0 0 0 -39.66 -12.26 a 75.24 75.24 0 0 0 -94 -12.19 a 22.91 22.91 0 0 0 -14.78 -5.34 h -0.69 a 33 33 0 1 0 -52.53 31.55 h -29.69 v 143.45 h 79.5 v -57.21 a 75.26 75.26 0 0 0 132.93 -46.7 a 28.88 28.88 0 0 0 12.78 -6.86 a 17.61 17.61 0 0 0 12.79 0 a 33.41 33.41 0 0 0 63.93 -7.44 a 54.56 54.56 0 0 0 101.57 18.56 v 7.65 h 140.21 a 47.23 47.23 0 0 0 79.55 -15.88 l 51.25 1.95 a 39.07 39.07 0 0 0 67.12 2.55 l 29.76 1.13 a 73.8 73.8 0 0 0 143.76 -16.75 h 66.17 a 56.4 56.4 0 1 0 36.39 -99.53 z"></path>  </svg>';
			break;
		case 'waves_opacity':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-right" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">  <path d="M 850.23 235.79 a 1.83 1.83 0 0 0 -0.8 -3.24 c -10.23 -2 -53.38 -23.41 -97.44 -43.55 c -244.99 -112 -337.79 97.38 -432.99 104 c -115 8 -217 -87 -330 -37 c 0 0 9 15 9 42 v -1 h 849 l 2 -55 s -2.87 -3 1.23 -6.21 z"></path>  <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
			break;
		case 'waves_opacity_alt':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip-right" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
	<path d="M 1000 299 l 2 -279 c -155 -36 -310 135 -415 164 c -102.64 28.35 -149 -32 -232 -31 c -80 1 -142 53 -229 80 c -65.54 20.34 -101 15 -126 11.61 v 54.39 z"></path> <path d="M 1000 286 l 2 -252 c -157 -43 -302 144 -405 178 c -101.11 33.38 -159 -47 -242 -46 c -80 1 -145.09 54.07 -229 87 c -65.21 25.59 -104.07 16.72 -126 10.61 v 22.39 z"></path> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path>
	</svg>';
			break;
		case 'curve_opacity':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 14 s 88.64 3.48 300 36 c 260 40 514 27 703 -10 l 12 28 l 3 36 h -1018 z"></path> <path d="M 0 45 s 271 45.13 500 32 c 157 -9 330 -47 515 -63 v 86 h -1015 z"></path> <path d="M 0 58 s 188.29 32 508 32 c 290 0 494 -35 494 -35 v 45 h -1002 z"></path> </svg>';
			break;
		case 'mountains':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
	<path d="M 1014 264 v 122 h -808 l -172 -86 s 310.42 -22.84 402 -79 c 106 -65 154 -61 268 -12 c 107 46 195.11 5.94 275 137 z"></path>   <path d="M -302 55 s 235.27 208.25 352 159 c 128 -54 233 -98 303 -73 c 92.68 33.1 181.28 115.19 235 108 c 104.9 -14 176.52 -173.06 267 -118 c 85.61 52.09 145 123 145 123 v 74 l -1306 10 z"></path>
	<path d="M -286 255 s 214 -103 338 -129 s 203 29 384 101 c 145.57 57.91 178.7 50.79 272 0 c 79 -43 301 -224 385 -63 c 53 101.63 -62 129 -62 129 l -107 84 l -1212 12 z"></path>
	<path d="M -24 69 s 299.68 301.66 413 245 c 8 -4 233 2 284 42 c 17.47 13.7 172 -132 217 -174 c 54.8 -51.15 128 -90 188 -39 c 76.12 64.7 118 99 118 99 l -12 132 l -1212 12 z"></path>
	<path d="M -12 201 s 70 83 194 57 s 160.29 -36.77 274 6 c 109 41 184.82 24.36 265 -15 c 55 -27 116.5 -57.69 214 4 c 49 31 95 26 95 26 l -6 151 l -1036 10 z"></path> </svg>';
			break;

	}

	return $shape_divider_svg;
}

/**
 * Returns svg shape divider for bottom.
 *
 * @since 1.0
 * @param string $divider_shape Shape divider type.
 * @param string $fill_color    Shape divider fill color.
 * @param string $bg_color      Shape divider background color.
 * @return string
 */
function elegant_get_bottom_divider_shape( $divider_shape, $fill_color, $bg_color ) {
	$shape_divider_svg = '';

	switch ( $divider_shape ) {
		case 'curve':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 0 c 0 0 200 50 500 50 s 500 -50 500 -50 v 101 h -1000 v -100 z"></path> </svg>';
			break;
		case 'slant-2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0 0v140h1280L0 0z" fill-opacity=".5"/><path d="M0 42v98h1280L0 42z"/> </svg>';
			break;
		case 'triangle_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"> <path xmlns="http://www.w3.org/2000/svg" d="M640 140L1280 0H0z" fill-opacity=".5"/><path xmlns="http://www.w3.org/2000/svg" d="M640 98l640-98H0z"/> </svg>';
			break;
		case 'curve_asym':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 20 0 50 0 100 100 Z"></path> </svg>';
			break;
		case 'curve_asym_2':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"> <path d="M0 100 C 50 0 80 0 100 100 Z"></path> </svg>';
			break;
		case 'tilt':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 10 0 0 0 10"></polygon> </svg>';
			break;
		case 'tilt_flip':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="104 0 104 10 0 10"></polygon> </svg>';
			break;
		case 'tilt_alt':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none"> <polygon points="100 10 100 0 -4 10"></polygon> </svg>';
			break;
		case 'triangle':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <polygon  points="501 53.27 0.5 0.56 0.5 100 1000.5 100 1000.5 0.66 501 53.27"/></svg>';
			break;
		case 'fan':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1003.92 91" preserveAspectRatio="none">  <polygon class="cls-1" points="502.46 46.31 1 85.67 1 91.89 1002.91 91.89 1002.91 85.78 502.46 46.31"/><polygon class="cls-2" points="502.46 45.8 1 0 1 91.38 1002.91 91.38 1002.91 0.1 502.46 45.8"/><rect class="cls-3" y="45.81" width="1003.92" height="46.09"/>
	</svg>';
			break;
		case 'fan2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none">  <path d="M1280 0L640 70 0 0v140l640-70 640 70V0z" fill-opacity=".5"/><path d="M1280 0H0l640 70 640-70z"/> </svg>';
			break;

		case 'ramp':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 0s573.08 140 1280 140V0z"/> </svg>';
			break;
		case 'ramp_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 0v60s573.09 80 1280 80V0z" fill-opacity=".3"/><path d="M0 0v30s573.09 110 1280 110V0z" fill-opacity=".5"/><path d="M0 0s573.09 140 1280 140V0z"/> </svg>';
			break;

		case 'rocks':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 70.35l320-49.24 640 98.49 320-49.25V140H0V70.35z"/> </svg>';
			break;
		case 'rocks_op':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none">  <path d="M0 90.72l140-28.28 315.52 24.14L796.48 65.8 1140 104.89l140-14.17V0H0v90.72z" fill-opacity=".5"/><path d="M0 0v47.44L170 0l626.48 94.89L1110 87.11l170-39.67V0H0z"/> </svg>';
			break;
		case 'wave1':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M320 28C160 28 80 49 0 70V0h1280v70c-80 21-160 42-320 42-320 0-320-84-640-84z"/> </svg>';
			break;
		case 'wave2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M0 51.76c36.21-2.25 77.57-3.58 126.42-3.58 320 0 320 57 640 57 271.15 0 312.58-40.91 513.58-53.4V0H0z" fill-opacity=".3"/><path d="M0 24.31c43.46-5.69 94.56-9.25 158.42-9.25 320 0 320 89.24 640 89.24 256.13 0 307.28-57.16 481.58-80V0H0z" fill-opacity=".5"/><path d="M0 0v3.4C28.2 1.6 59.4.59 94.42.59c320 0 320 84.3 640 84.3 285 0 316.17-66.85 545.58-81.49V0z"/> </svg>';
			break;
		case 'waves_opacity_3':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M504.854,80.066c7.812,0,14.893,0.318,21.41,0.879 c-25.925,22.475-56.093,40.852-102.946,40.852c-20.779,0-37.996-2.349-52.898-6.07C413.517,107.295,434.056,80.066,504.854,80.066z M775.938,51.947c19.145,18.596,39.097,35.051,77.956,35.051c46.907,0,62.299-14.986,80.912-24.98 c-21.357-15.783-46.804-28.348-85.489-28.348C816.829,33.671,794.233,41.411,775.938,51.947z" fill-opacity=".3"/><path d="M1200.112,46.292c39.804,0,59.986,22.479,79.888,39.69v16.805 c-19.903-10.835-40.084-21.777-79.888-21.777c-72.014,0-78.715,43.559-147.964,43.559c-56.84,0-81.247-35.876-117.342-62.552 c9.309-4.998,19.423-8.749,34.69-8.749c55.846,0,61.99,39.617,115.602,39.617C1143.177,92.887,1142.618,46.292,1200.112,46.292z M80.011,115.488c-40.006,0-60.008-12.206-80.011-29.506v16.806c20.003,10.891,40.005,21.782,80.011,21.782 c80.004,0,78.597-30.407,137.669-30.407c55.971,0,62.526,24.026,126.337,24.026c9.858,0,18.509-0.916,26.404-2.461 c-57.186-14.278-80.177-48.808-138.66-48.808C154.698,66.919,131.801,115.488,80.011,115.488z M526.265,80.945 c56.848,4.902,70.056,28.726,137.193,28.726c54.001,0,73.43-35.237,112.48-57.724C751.06,27.782,727.548,0,665.691,0 C597.381,0,567.086,45.555,526.265,80.945z" fill-opacity=".5"/><path d="M0,0v85.982c20.003,17.3,40.005,29.506,80.011,29.506c51.791,0,74.688-48.569,151.751-48.569 c58.482,0,81.473,34.531,138.66,48.808c43.096-8.432,63.634-35.662,134.433-35.662c7.812,0,14.893,0.318,21.41,0.879 C567.086,45.555,597.381,0,665.691,0c61.856,0,85.369,27.782,110.246,51.947c18.295-10.536,40.891-18.276,73.378-18.276 c38.685,0,64.132,12.564,85.489,28.348c9.309-4.998,19.423-8.749,34.69-8.749c55.846,0,61.99,39.617,115.602,39.617 c58.08,0,57.521-46.595,115.015-46.595c39.804,0,59.986,22.479,79.888,39.69V0H0z"/> </svg>';
			break;

		case 'asym':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M978.81 122.25L0 0h1280l-262.1 116.26a73.29 73.29 0 0 1-39.09 5.99z"/> </svg>';
			break;
		case 'asym2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M978.81 122.25L0 0h1280l-262.1 116.26a73.29 73.29 0 0 1-39.09 5.99z" fill-opacity=".5"/><path d="M983.19 95.23L0 0h1280l-266 91.52a72.58 72.58 0 0 1-30.81 3.71z"/> </svg>';
			break;
		case 'graph2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M1214.323 66.051h-79.863l-70.793 18.224-66.285-10.933-83.672 22.953-97.601-7.328-73.664 22.125-76.961 8.475-82.664-13.934-76.926 11.832-94.453-7.666-90.137 17.059-78.684-9.731-86.363 13.879-95.644 3.125L0 126.717V0h1280l-.001 35.844z" fill-opacity=".5"/><path d="M0 0h1280v.006l-70.676 36.578-74.863 4.641-70.793 23.334-66.285-11.678-83.672 29.618-97.602-7.07-63.664 21.421-76.961 12.649-91.664-20.798-77.926 17.66-94.453-7.574-90.137 21.595-78.683-9.884-86.363 16.074-95.645 6.211L0 127.905z"/> </svg>';
			break;
		case 'graph1':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M156 35.41l95.46 34.73 120.04.25 71.5 33.24 90.09-3.89L640 137.25l102.39-37.06 85.55 10.61 88.11-7.17L992 65.08l73.21 5.31L1132 48.35l77-.42L1280 0H0l64.8 38.57 91.2-3.16z" fill-opacity=".5"/><path d="M156 28.32l95.46 27.79 120.04.2L443 82.9l90.09-3.11L640 109.8l102.39-29.65 85.55 8.49 88.11-5.74L992 52.07l73.21 4.24L1132 38.68l77-.34L1280 0H0l64.8 30.86 91.2-2.54z"/> </svg>';
			break;
		case 'clouds2':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-horizontal-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 86" preserveAspectRatio="xMidYMid slice"> <path d="M1280 0H0v65.2c6.8 0 13.5.9 20.1 2.6 14-21.8 43.1-28 64.8-14 5.6 3.6 10.3 8.3 14 13.9 7.3-1.2 14.8-.6 21.8 1.6 2.1-37.3 34.1-65.8 71.4-63.7 24.3 1.4 46 15.7 56.8 37.6 19-17.6 48.6-16.5 66.3 2.4C323 54 327.4 65 327.7 76.5c.4.2.8.4 1.2.7 3.3 1.9 6.3 4.2 8.9 6.9 15.9-23.8 46.1-33.4 72.8-23.3 11.6-31.9 46.9-48.3 78.8-36.6 9.1 3.3 17.2 8.7 23.8 15.7 6.7-6.6 16.7-8.4 25.4-4.8 29.3-37.4 83.3-44 120.7-14.8 14 11 24.3 26.1 29.4 43.1 4.7.6 9.3 1.8 13.6 3.8 7.8-24.7 34.2-38.3 58.9-30.5 14.4 4.6 25.6 15.7 30.3 30 14.2 1.2 27.7 6.9 38.5 16.2 11.1-35.7 49-55.7 84.7-44.7 14.1 4.4 26.4 13.3 35 25.3 12-5.7 26.1-5.5 37.9.6 3.9-11.6 15.5-18.9 27.7-17.5.2-.3.3-.6.5-.9 23.3-41.4 75.8-56 117.2-32.6 14.1 7.9 25.6 19.7 33.3 33.8 28.8-23.8 71.5-19.8 95.3 9 2.6 3.1 4.9 6.5 6.9 10 3.8-.5 7.6-.8 11.4-.8L1280 0z"/> </svg>';
			break;
		case 'clouds3':
			$shape_divider_svg = '<svg class="elegant-shape-divider divider-vertical-flip" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"> <path d="M833.9 27.5c-5.8 3.2-11 7.3-15.5 12.2-7.1-6.9-17.5-8.8-26.6-5-30.6-39.2-87.3-46.1-126.5-15.5-1.4 1.1-2.8 2.2-4.1 3.4C674.4 33.4 684 48 688.8 64.3c4.7.6 9.3 1.8 13.6 3.8 7.8-24.7 34.2-38.3 58.9-30.5 14.4 4.6 25.6 15.7 30.3 30 14.2 1.2 27.7 6.9 38.5 16.2C840.6 49.6 876 29.5 910.8 38c-20.4-20.3-51.8-24.6-76.9-10.5zM384 43.9c-9 5-16.7 11.9-22.7 20.3 15.4-7.8 33.3-8.7 49.4-2.6 3.7-10.1 9.9-19.1 18.1-26-15.4-2.3-31.2.6-44.8 8.3zm560.2 13.6c2 2.2 3.9 4.5 5.7 6.9 5.6-2.6 11.6-4 17.8-4.1-7.6-2.4-15.6-3.3-23.5-2.8zM178.7 7c29-4.2 57.3 10.8 70.3 37 8.9-8.3 20.7-12.8 32.9-12.5C256.4 1.8 214.7-8.1 178.7 7zm146.5 56.3c1.5 4.5 2.4 9.2 2.5 14 .4.2.8.4 1.2.7 3.3 1.9 6.3 4.2 8.9 6.9 5.8-8.7 13.7-15.7 22.9-20.5-11.1-5.2-23.9-5.6-35.5-1.1zM33.5 54.9c21.6-14.4 50.7-8.5 65 13 .1.2.2.3.3.5 7.3-1.2 14.8-.6 21.8 1.6.6-10.3 3.5-20.4 8.6-29.4.3-.6.7-1.2 1.1-1.8-32.1-17.2-71.9-10.6-96.8 16.1zm1228.9 2.7c2.3 2.9 4.4 5.9 6.2 9.1 3.8-.5 7.6-.8 11.4-.8V48.3c-6.4 1.8-12.4 5-17.6 9.3zM1127.3 11c1.9.9 3.7 1.8 5.6 2.8 14.2 7.9 25.8 19.7 33.5 34 13.9-11.4 31.7-16.9 49.6-15.3-20.5-27.7-57.8-36.8-88.7-21.5z" fill-opacity=".5"/><path d="M0 0v66c6.8 0 13.5.9 20.1 2.6 3.5-5.4 8.1-10.1 13.4-13.6 24.9-26.8 64.7-33.4 96.8-16 10.5-17.4 28.2-29.1 48.3-32 36.1-15.1 77.7-5.2 103.2 24.5 19.7.4 37.1 13.1 43.4 31.8 11.5-4.5 24.4-4.2 35.6 1.1l.4-.2c15.4-21.4 41.5-32.4 67.6-28.6 25-21 62.1-18.8 84.4 5.1 6.7-6.6 16.7-8.4 25.4-4.8 29.2-37.4 83.3-44.1 120.7-14.8l1.8 1.5c37.3-32.9 94.3-29.3 127.2 8 1.2 1.3 2.3 2.7 3.4 4.1 9.1-3.8 19.5-1.9 26.6 5 24.3-26 65-27.3 91-3.1.5.5 1 .9 1.5 1.4 12.8 3.1 24.4 9.9 33.4 19.5 7.9-.5 15.9.4 23.5 2.8 7-.1 13.9 1.5 20.1 4.7 3.9-11.6 15.5-18.9 27.7-17.5.2-.3.3-.6.5-.9 22.1-39.2 70.7-54.7 111.4-35.6 30.8-15.3 68.2-6.2 88.6 21.5 18.3 1.7 35 10.8 46.5 25.1 5.2-4.3 11.1-7.4 17.6-9.3V0H0z"/> </svg>';
			break;
		case 'waves':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none"> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
			break;
		case 'speech':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 45.86 h 458 c 29 0 42 19.27 42 19.27 s 13 -19.27 42.74 -19.27 h 457.26 v 54.14 h -1000 z"></path>  </svg>';
			break;
		case 'clouds':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 983.71 4.47 a 56.19 56.19 0 0 0 -37.61 14.38 a 15.24 15.24 0 0 0 -25.55 -0.55 a 40.65 40.65 0 0 0 -55.45 13 a 15.63 15.63 0 0 0 -22.69 1.52 a 73.82 73.82 0 0 0 -98.57 27.91 a 14.72 14.72 0 0 0 -9.31 0.55 a 26.13 26.13 0 0 0 -42.63 1.92 a 39.08 39.08 0 0 0 -47 10.08 a 18.45 18.45 0 0 0 -34.18 -0.45 a 12.21 12.21 0 0 0 -14.23 0.9 a 11.47 11.47 0 0 0 -16.59 -6 a 47.2 47.2 0 0 0 -66.12 -4.07 a 21.32 21.32 0 0 0 -26.48 -4.91 a 15 15 0 0 0 -29 -7.79 a 10.47 10.47 0 0 0 -14 5.13 a 31.55 31.55 0 0 0 -50.68 12.32 a 23 23 0 0 0 -28.69 -5.34 a 54.54 54.54 0 0 0 -89.93 5.71 a 16.3 16.3 0 0 0 -22.71 2.3 a 33.41 33.41 0 0 0 -44.93 9.65 a 17.72 17.72 0 0 0 -9.79 -2.94 h -0.22 a 29 29 0 0 0 -39.66 -12.26 a 75.24 75.24 0 0 0 -94 -12.19 a 22.91 22.91 0 0 0 -14.78 -5.34 h -0.69 a 33 33 0 1 0 -52.53 31.55 h -29.69 v 143.45 h 79.5 v -57.21 a 75.26 75.26 0 0 0 132.93 -46.7 a 28.88 28.88 0 0 0 12.78 -6.86 a 17.61 17.61 0 0 0 12.79 0 a 33.41 33.41 0 0 0 63.93 -7.44 a 54.56 54.56 0 0 0 101.57 18.56 v 7.65 h 140.21 a 47.23 47.23 0 0 0 79.55 -15.88 l 51.25 1.95 a 39.07 39.07 0 0 0 67.12 2.55 l 29.76 1.13 a 73.8 73.8 0 0 0 143.76 -16.75 h 66.17 a 56.4 56.4 0 1 0 36.39 -99.53 z"></path>  </svg>';
			break;
		case 'waves_opacity':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">  <path d="M 850.23 235.79 a 1.83 1.83 0 0 0 -0.8 -3.24 c -10.23 -2 -53.38 -23.41 -97.44 -43.55 c -244.99 -112 -337.79 97.38 -432.99 104 c -115 8 -217 -87 -330 -37 c 0 0 9 15 9 42 v -1 h 849 l 2 -55 s -2.87 -3 1.23 -6.21 z"></path>  <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path> </svg>';
			break;
		case 'waves_opacity_alt':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
	<path d="M 1000 299 l 2 -279 c -155 -36 -310 135 -415 164 c -102.64 28.35 -149 -32 -232 -31 c -80 1 -142 53 -229 80 c -65.54 20.34 -101 15 -126 11.61 v 54.39 z"></path> <path d="M 1000 286 l 2 -252 c -157 -43 -302 144 -405 178 c -101.11 33.38 -159 -47 -242 -46 c -80 1 -145.09 54.07 -229 87 c -65.21 25.59 -104.07 16.72 -126 10.61 v 22.39 z"></path> <path d="M 1000 300 l 1 -230.29 c -217 -12.71 -300.47 129.15 -404 156.29 c -103 27 -174 -30 -257 -29 c -80 1 -130.09 37.07 -214 70 c -61.23 24 -108 15.61 -126 10.61 v 22.39 z"></path>
	</svg>';
			break;
		case 'curve_opacity':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"> <path d="M 0 14 s 88.64 3.48 300 36 c 260 40 514 27 703 -10 l 12 28 l 3 36 h -1018 z"></path> <path d="M 0 45 s 271 45.13 500 32 c 157 -9 330 -47 515 -63 v 86 h -1015 z"></path> <path d="M 0 58 s 188.29 32 508 32 c 290 0 494 -35 494 -35 v 45 h -1002 z"></path> </svg>';
			break;
		case 'mountains':
			$shape_divider_svg = '<svg class="elegant-shape-divider" style="background-color:' . $bg_color . ';" fill="' . $fill_color . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300" preserveAspectRatio="none">
	<path d="M 1014 264 v 122 h -808 l -172 -86 s 310.42 -22.84 402 -79 c 106 -65 154 -61 268 -12 c 107 46 195.11 5.94 275 137 z"></path>   <path d="M -302 55 s 235.27 208.25 352 159 c 128 -54 233 -98 303 -73 c 92.68 33.1 181.28 115.19 235 108 c 104.9 -14 176.52 -173.06 267 -118 c 85.61 52.09 145 123 145 123 v 74 l -1306 10 z"></path>
	<path d="M -286 255 s 214 -103 338 -129 s 203 29 384 101 c 145.57 57.91 178.7 50.79 272 0 c 79 -43 301 -224 385 -63 c 53 101.63 -62 129 -62 129 l -107 84 l -1212 12 z"></path>
	<path d="M -24 69 s 299.68 301.66 413 245 c 8 -4 233 2 284 42 c 17.47 13.7 172 -132 217 -174 c 54.8 -51.15 128 -90 188 -39 c 76.12 64.7 118 99 118 99 l -12 132 l -1212 12 z"></path>
	<path d="M -12 201 s 70 83 194 57 s 160.29 -36.77 274 6 c 109 41 184.82 24.36 265 -15 c 55 -27 116.5 -57.69 214 4 c 49 31 95 26 95 26 l -6 151 l -1036 10 z"></path> </svg>';
			break;

	}

	return $shape_divider_svg;
}

/**
 * Ajax for favourite elements.
 *
 * @since 1.0
 * @return void
 */
function eewpb_add_to_favourite() {
	$element = isset( $_POST['element_tag'] ) ? $_POST['element_tag'] : ''; // @codingStandardsIgnoreLine
	if ( '' !== $element ) {
		$status = '';

		// Get existing favourite lements.
		$favourite_elements = get_option( 'eewpb_favourite_elements', array() );

		// Check if element is already in favourite and remove it, else add it.
		if ( isset( $favourite_elements[ $element ] ) ) {
			unset( $favourite_elements[ $element ] );
			$status = 'removed';
		} else {
			$favourite_elements[ $element ] = $element;
			$status                         = 'added';
		}

		update_option( 'eewpb_favourite_elements', $favourite_elements );
		die( $status );
	} else {
		die( 'no element' );
	}

	die();
}
add_action( 'wp_ajax_eewpb_add_to_favourite', 'eewpb_add_to_favourite' );

/**
 * Handles the file upload using wp native function.
 *
 * @since 1.0
 * @param array $files The uploaded files array.
 * @return array $moved_files Array containing uploaded files data or the error.
 */
function elegant_form_handle_upload( $files ) {
	$uploaded_files = array();
	$moved_files    = array();

	foreach ( $files as $file ) {
		foreach ( $file as $key => $data ) {
			foreach ( $data as $key2 => $file_data ) {
				$uploaded_files[ $key2 ][ $key ] = $file_data;
			}
		}
	}

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	foreach ( $uploaded_files as $uploaded_file ) {
		$upload_overrides = array(
			'test_form' => false,
		);

		$move_file = wp_handle_upload( $uploaded_file, $upload_overrides );

		if ( $move_file && ! isset( $move_file['error'] ) ) {
			$file_url  = $move_file['url'];
			$file_name = basename( $move_file['file'] );

			$moved_files['files'][ $file_name ] = $file_url;
		} else {
			$moved_files['error'][ $uploaded_file['name'] ] = $move_file['error'];
		}
	}

	return $moved_files;
}

/**
 * Add more radius options to css editor param.
 *
 * @since 1.0
 * @param array $radius_options The default radius options.
 * @return array $radius_options Array with updated values.
 */
function eewpb_css_param_border_radius( $radius_options ) {

	// Add more options to border radius.
	$radius_options['40px']  = '40px';
	$radius_options['45px']  = '45px';
	$radius_options['50px']  = '50px';
	$radius_options['100px'] = '100px';

	return $radius_options;
}
add_filter( 'vc_css_editor_border_radius_options_data', 'eewpb_css_param_border_radius' );

/**
 * Filter the link query arguments to change the post types.
 *
 * @since 1.1.0
 * @param array $query An array of WP_Query arguments.
 * @return array $query
 */
function disable_link_query_for_headers( $query ) {

	// Custom post type slug to be removed.
	$cpts_to_remove = array(
		'eewpb_header',
		'eewpb_footer',
		'elegant_menu',
		'eewpb_ptb',
	);

	foreach ( $cpts_to_remove as $cpt ) {
		// Find the corresponding array key.
		$key = array_search( $cpt, $query['post_type'] );

		// Remove the array item.
		if ( $key ) {
			unset( $query['post_type'][ $key ] );
		}
	}

	return $query;
}
add_filter( 'wp_link_query_args', 'disable_link_query_for_headers' );

/**
 * Add the mega menu, header and footer builder post types to WPBakery default editor post types.
 *
 * @since 1.1.0
 * @return void
 */
function eewpb_set_default_post_types() {
	if ( function_exists( 'vc_editor_set_post_types' ) ) {

		// Get the list of post types.
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output   = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')

		$existing_post_types = get_post_types( $args, $output, $operator );

		// Get the post type array of enabled post types.
		$existing_post_types[] = 'page';
		$existing_post_types[] = 'post';
		$existing_post_types[] = 'eewpb_header';
		$existing_post_types[] = 'eewpb_ptb';
		$existing_post_types[] = 'eewpb_footer';
		$existing_post_types[] = 'elegant_menu';

		// Enable the WPBakery Page Builder for the new post type.
		vc_editor_set_post_types( $existing_post_types );
	}
}
add_action( 'admin_init', 'eewpb_set_default_post_types', 10 );

/**
 * Filter the content to render the dynamic content.
 *
 * @since 1.2.0
 * @param string $content Post content.
 * @return string
 */
function elegant_dynamic_content_filter( $content ) {
	global $post;

	$regex_pattern = '/(?<=\{\{)(.*?)(?=\}\})/';

	preg_match_all( $regex_pattern, $content, $matches );

	if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
		$dynamic_params = $matches[1];

		foreach ( $dynamic_params as $param ) {
			$param_value = $param;

			// If param is ACF field.
			if ( false !== strpos( $param, 'acf' ) ) {
				$acf_field   = explode( ':', $param );
				$param_value = 'acf';
				$acf_field   = $acf_field[1];
			}

			// If param is custom meta.
			if ( false !== strpos( $param, 'meta' ) ) {
				$meta_field  = explode( ':', $param );
				$param_value = 'meta';
				$meta_field  = $meta_field[1];
			}

			switch ( $param_value ) {
				case 'page_title':
					$content = str_replace( '{{' . $param . '}}', get_the_title(), $content );
					break;

				case 'post_date':
					$content = str_replace( '{{' . $param . '}}', get_the_date(), $content );
					break;

				case 'excerpt':
					$content = str_replace( '{{' . $param . '}}', get_the_excerpt(), $content );
					break;

				case 'featured_image':
					$featured_img_url = get_the_post_thumbnail_url( $post->ID, 'full' );
					$content          = str_replace( '{{' . $param . '}}', $featured_img_url, $content );
					break;

				case 'author_name':
					$author_id   = $post->post_author;
					$author_name = get_the_author_meta( 'user_nicename', $author_id );
					$content     = str_replace( '{{' . $param . '}}', $author_name, $content );
					break;

				case 'acf':
					if ( function_exists( 'get_field' ) ) {
						$field_value = get_field( $acf_field );
						$content     = str_replace( '{{' . $param . '}}', $field_value, $content );
					}
					break;

				case 'meta':
					$field_value = get_post_meta( $post->ID, $meta_field, true );
					$content     = str_replace( '{{' . $param . '}}', $field_value, $content );
					break;
			}
		}

		return $content;
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'elegant_dynamic_content_filter' );

/**
 * Return the SVG with text path.
 *
 * @since 1.6.0
 * @access public
 * @param array $args Element attributes.
 * @return string
 */
function eewpb_get_text_path_svg( $args ) {
	$shape = $args['shape'];
	$text  = $args['text'];
	$id    = wp_rand();
	$svg   = '';

	if ( isset( $args['link'] ) && '' !== $args['link'] ) {
		$link   = vc_build_link( $args['link'] );
		$url    = esc_url( $link['url'] );
		$target = ( isset( $link['target'] ) ) ? ' target="' . trim( $link['target'] ) . '"' : '';

		$text = '<a href="' . $url . '" ' . $target . '>' . $text . '</a>';
	}

	$text_node = '<text><textPath id="ee-text-path-' . $id . '" href="#ee-path-' . $id . '" startOffset="0%">' . $text . '</textPath></text>';

	switch ( $shape ) {
		case 'wave':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="42.4994" viewBox="0 0 250 42.4994"><path id="ee-path-' . $id . '" d="M0,42.2494C62.5,42.2494,62.5.25,125,.25s62.5,41.9994,125,41.9994"/><path d="M-41.6693,49.25"/><path d="M-208.3307-6.75"/>' . $text_node . '</svg>';
			break;
		case 'arc-top':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' . $id . '" d="M.25,125.25a125,125,0,0,1,250,0"/>' . $text_node . '</svg>';
			break;
		case 'arc-bottom':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' . $id . '" d="M 0 0 C 0 180 250 180 250 0"/>' . $text_node . '</svg>';
			break;
		case 'circle':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="250.5" viewBox="0 0 250.5 250.5"><path id="ee-path-' . $id . '" d="M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125"/>' . $text_node . '</svg>';
			break;
		case 'line-top':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' . $id . '" d="M 0 27 l 250 -22"/>' . $text_node . '</svg>';
			break;
		case 'line-bottom':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' . $id . '" d="M 0 27 l 250 22"/>' . $text_node . '</svg>';
			break;
		case 'oval':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.75" viewBox="0 0 250.5 125.75"><path id="ee-path-' . $id . '" class="b473dc75-7459-43a5-8a1c-89caf910da53" d="M.25,62.875C.25,28.2882,56.2144.25,125.25.25s125,28.0382,125,62.625-55.9644,62.625-125,62.625S.25,97.4619.25,62.875"/>' . $text_node . '</svg>';
			break;
		case 'spiral':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.4348" height="239.4454" viewBox="0 0 250.4348 239.4454"><path id="ee-path-' . $id . '" d="M.1848,49.0219a149.3489,149.3489,0,0,1,210.9824-9.8266,119.479,119.479,0,0,1,7.8613,168.786A95.5831,95.5831,0,0,1,84,214.27a76.4666,76.4666,0,0,1-5.0312-108.023"/>' . $text_node . '</svg>';
			break;
	}

	return $svg;
}
