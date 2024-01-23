<?php
/**
 * Elegant elements post templates helper functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the template post for the given type.
 *
 * @since 1.0
 * @access public
 * @param string $type Template post type.
 * @return object Template Post.
 */
function eewpb_template_get_post( $type = 'header' ) {
	global $post;

	$is_special        = elegant_is_special_page();
	$current_post_id   = ( $post ) ? $post->ID : ( $is_special ? 0 : false );
	$current_post_type = ( $post ) ? $post->post_type : false;
	$template_post_id  = false;

	$args = array(
		'post_type'      => 'eewpb_' . $type,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'order'          => 'ASC',
	);

	$elegant_templates = elegant_wpbakery_cached_query( $args );

	// Check if there are items available.
	if ( $elegant_templates->have_posts() ) {
		// The loop.
		while ( $elegant_templates->have_posts() ) :
			$elegant_templates->the_post();
			$post_id                  = get_the_ID();
			$template_display_options = get_field( 'display_template_on', $post_id );
			$exclude_template_options = get_field( 'hide_template_on', $post_id );
			$mobile_header_option     = get_field( 'elegant_mobile_header', $post_id );

			if ( $mobile_header_option && wp_is_mobile() ) {
				$post_id = $mobile_header_option->ID;
			}

			foreach ( $template_display_options as $template_display_option ) {
				$incld_template_location_type = $template_display_option['template_location_type'];
				$incld_singular_type          = $template_display_option['singular_type'];
				$incld_posts                  = $template_display_option['posts'];
				$incld_post_type              = $template_display_option['post_type'];

				switch ( $incld_template_location_type ) {
					case 'global':
						$template_post_id = $post_id;

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'singular':
						$template_post_id = false;
						if ( 'all' === $incld_singular_type ) {
							if ( is_singular() ) {
								$template_post_id = $post_id;
							}
						} elseif ( 'custom' === $incld_singular_type ) {
							if ( ! empty( $incld_posts ) && false !== array_search( $current_post_id, $incld_posts, true ) ) {
								$template_post_id = $post_id;
							}
						} elseif ( 'cpt' === $incld_singular_type ) {
							if ( ! empty( $incld_post_type ) && false !== array_search( $current_post_type, $incld_post_type, true ) ) {
								$template_post_id = $post_id;
							}
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case '404_page':
						if ( is_404() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'author':
						if ( is_author() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'archive':
						if ( is_archive() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'search':
						if ( is_search() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'blog':
						if ( is_home() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'front':
						if ( is_front_page() ) {
							$template_post_id = $post_id;
						}

						// Check if exclude rules are applied.
						if ( ! empty( $exclude_template_options ) ) {
							$current_post_id  = ( $is_special ) ? 0 : $current_post_id;
							$template_post_id = elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_post_id );
						}

						break;

					case 'default':
						$template_post_id = false;
						break;
				}

				if ( $template_post_id ) {
					break 2;
				}
			}

		endwhile;

		// Restore original Post Data.
		wp_reset_postdata();
	}

	if ( $template_post_id ) {
		$template_post = get_post( $template_post_id );
	} else {
		$template_post = false;
	}

	return $template_post;
}

/**
 * Check if the page being displayed is a special page.
 *
 * @since  1.0
 * @access public
 * @return bool False if header is excluded or the header id.
 */
function elegant_is_special_page() {
	$is_special = false;

	// Check if page is home page, search page or a 404 page.
	if ( is_home() || is_front_page() || is_search() || is_404() || is_archive() ) {
		$is_special = true;
	}

	return apply_filters( 'elegant_is_special_page', $is_special );
}

/**
 * Check if the header or footer template is excluded for the current page
 *
 * @since  1.0
 * @access public
 * @param array $exclude_template_options The template exclude options.
 * @param int   $current_post_id          The ID of the current post.
 * @param int   $template_id              The ID  of the template currently being checked.
 * @return bool|int False if template is excluded for the page being displayed.
 */
function elegant_check_template_exclude( $exclude_template_options, $current_post_id, $template_id ) {

	foreach ( $exclude_template_options as $exclude_template_option ) {
		$excld_template_location_type = $exclude_template_option['template_location_type'];
		$excld_singular_type          = $exclude_template_option['singular_type'];
		$excld_posts                  = $exclude_template_option['posts'];
		$excld_post_type              = $exclude_template_option['post_type'];

		switch ( $excld_template_location_type ) {
			case 'global':
				// If template is excluded from entire site, skip it and check for other templates or display theme default.
				$template_id = false;
				break;
			case 'singular':
				if ( 'all' === $excld_singular_type ) {
					if ( is_singular() ) {
						$template_id = false;
					}
				} elseif ( 'custom' === $excld_singular_type ) {
					if ( ! empty( $excld_posts ) && false !== array_search( $current_post_id, $excld_posts, true ) ) {
						$template_id = false;
					}
				} elseif ( 'cpt' === $excld_singular_type ) {
					if ( ! empty( $excld_post_type ) && false !== array_search( $current_post_type, $excld_post_type, true ) ) {
						$template_id = false;
					}
				}
				break;

			case '404_page':
				if ( is_404() ) {
					$template_id = false;
				}

				break;

			case 'author':
				if ( is_author() ) {
					$template_id = false;
				}

				break;

			case 'archive':
				if ( is_archive() ) {
					$template_id = false;
				}

				break;

			case 'search':
				if ( is_search() ) {
					$template_id = false;
				}

				break;

			case 'blog':
				if ( is_home() ) {
					$template_id = false;
				}

				break;

			case 'front':
				if ( is_front_page() ) {
					$template_id = false;
				}

				break;
		}
	}

	return $template_id;
}

/**
 * Parse shortcodes custom css string.
 *
 * This function is used by self::buildShortcodesCustomCss and creates css string from shortcodes attributes
 * like 'css_editor'.
 *
 * @see    WPBakeryVisualComposerCssEditor
 * @since  1.0
 * @access public
 * @param string $content The post content.
 * @return string
 */
function elegant_parse_shortcodes_css( $content ) {
	$css = '';

	if ( ! preg_match( '/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $content ) ) {
		return $css;
	}

	WPBMap::addAllMappedShortcodes();

	preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );

	foreach ( $shortcodes[2] as $index => $tag ) {
		$shortcode = WPBMap::getShortCode( $tag );
		if ( ! empty( $shortcode['params'] ) && is_array( $shortcode['params'] ) ) {
			$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
			foreach ( $shortcode['params'] as $param ) {
				if ( isset( $param['type'] ) && 'css_editor' === $param['type'] && isset( $attr_array[ $param['param_name'] ] ) ) {
					$css .= $attr_array[ $param['param_name'] ];
				}
			}
		}
	}

	foreach ( $shortcodes[5] as $shortcode_content ) {
		$css .= elegant_parse_shortcodes_css( $shortcode_content );
	}

	return $css;
}

/**
 * Return the menu meta fields.
 *
 * @since 1.0
 * @access public
 * @return array
 */
function elegant_get_walker_fields() {
	$walker_args = array(
		'icon'             => '',
		'mega_menu_width'  => '',
		'class'            => '',
		'enable_mega_menu' => '0',
		'disable_link'     => '0',
		'hide_on_mobile'   => '0',
		'hide_on_desktop'  => '0',
		'enable_for_users' => 'all',
	);

	return $walker_args;
}

/**
 * Return the array of custom post types.
 *
 * @since 1.0
 * @access public
 * @param array $field ACF Field.
 * @return array
 */
function eewpb_acf_load_post_types( $field ) {
	$args = array(
		'public'   => true,
		'_builtin' => true,
	);

	$post_types = get_post_types( $args, 'objects' );
	unset( $post_types['attachment'] );

	$args['_builtin']            = false;
	$args['exclude_from_search'] = false;

	$custom_post_type = get_post_types( $args, 'objects' );

	$post_types = apply_filters( 'elegant_header_rule_post_types', array_merge( $post_types, $custom_post_type ) );

	foreach ( $post_types as $post_type ) {
		$field['choices'][ $post_type->name ] = $post_type->label;
	}

	// Return the field.
	return $field;
}
add_filter( 'acf/load_field/name=post_type', 'eewpb_acf_load_post_types' );

/**
 * Return the array of arguments passed to relationship field.
 *
 * @since 1.0
 * @access public
 * @param array  $options  Relationship field options.
 * @param array  $field    ACF Field.
 * @param object $the_post The current post object.
 * @return array
 */
function eewpb_relationship_options_filter( $options, $field, $the_post ) {

	$options['post_status'] = array( 'publish' );

	return $options;
}
add_filter( 'acf/fields/relationship/query/name=posts', 'eewpb_relationship_options_filter', 10, 3 );

/**
 * Render the header content.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function eewpb_header_render_content() {
	global $post;
	$header_post    = eewpb_header_builder()->header_template;
	$is_sticky      = get_field( 'sticky_header', $header_post->ID );
	$page_title_bar = get_field( 'elegant_page_title_bar', $header_post->ID );
	$header_class   = 'elegant-header-wrapper';
	$header_class  .= ( $is_sticky ) ? ' elegant-header-sticky' : '';
	?>
	<div id="elegant-header-wrapper" class="<?php echo esc_attr( $header_class ); ?>">
		<header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
			<p class="main-title eewpb-hidden" itemprop="headline">
				<a href="<?php echo bloginfo( 'url' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</p>
			<?php
			$header_post_content = elegant_dynamic_content_filter( $header_post->post_content );
			echo do_shortcode( shortcode_unautop( $header_post_content ) );
			?>
		</header>
		<?php
		$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine
		if ( $page_title_bar && ! $is_vc_inline ) {
			$title_bar_content = elegant_dynamic_content_filter( $page_title_bar->post_content );
			?>
			<div class="elegant-page-title-bar">
				<?php
				echo do_shortcode( $title_bar_content );
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
add_action( 'eewpb_header_render_content', 'eewpb_header_render_content' );

/**
 * Render the footer content.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function eewpb_footer_render_content() {
	global $post;
	$footer_post = eewpb_footer_builder()->footer_template;

	if ( $footer_post ) {
		$is_sticky     = get_field( 'sticky_footer', $footer_post->ID );
		$footer_class  = 'elegant-footer-wrapper';
		$footer_class .= ( $is_sticky ) ? ' elegant-footer-sticky' : '';
		?>
		<div id="elegant-footer-wrapper" class="<?php echo esc_attr( $footer_class ); ?>">
			<footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" role="contentinfo">
				<?php
				$footer_post_content = elegant_dynamic_content_filter( $footer_post->post_content );
				echo do_shortcode( shortcode_unautop( $footer_post_content ) );
				?>
			</footer>
		</div>
		<?php
		// Enqueue footer styles on frontend.
		wp_enqueue_style( 'infi-elegant-elements-footers-min', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-footers.min.css', '', EEWPB_VERSION );
	}
}
add_action( 'eewpb_footer_render_content', 'eewpb_footer_render_content' );

/**
 * Save the menu item content to the menu item meta.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function save_elegant_menu_item() {
	$form_data = $_POST; // @codingStandardsIgnoreLine

	$menu_id = isset( $form_data['elegant_menu_id'] ) ? absint( $form_data['elegant_menu_id'] ) : false;
	$item_id = isset( $form_data['elegant_menu_item_id'] ) ? absint( $form_data['elegant_menu_item_id'] ) : false;

	if ( ! $item_id ) {
		$errors = array(
			'error' => esc_html__( 'Menu item not exists.', 'elegant-elements' ),
		);

		die( wp_json_encode( $errors ) );
	}

	// Retrieve the menu item post.
	$post = get_post( $item_id );

	// Check if passed menu item exists.
	if ( 'nav_menu_item' !== $post->post_type ) {
		$errors = array(
			'error' => esc_html__( 'Menu item not exists.', 'elegant-elements' ),
		);

		die( wp_json_encode( $errors ) );
	}

	$content = ( ! empty( $form_data['content'] ) ) ? $form_data['content'] : '';

	if ( '' === $content ) {
		$errors = array(
			'error' => esc_html__( 'No content found.', 'elegant-elements' ),
		);

		die( wp_json_encode( $errors ) );
	} else {
		$shortcode_styles = elegant_parse_shortcodes_css( stripslashes( $content ) );
		$shortcode_styles = apply_filters( 'vc_base_build_shortcodes_custom_css', $shortcode_styles );
		$custom_css       = ( ! empty( $form_data['vc_post_custom_css'] ) ) ? $form_data['vc_post_custom_css'] : '';

		// Save shortcode styles.
		if ( $shortcode_styles || $custom_css ) {
			update_post_meta( $item_id, '_vc_custom_item_css', $shortcode_styles . $custom_css );
		} else {
			delete_post_meta( $item_id, '_vc_custom_item_css', $shortcode_styles . $custom_css );
		}

		// Save custom CSS.
		if ( $custom_css ) {
			update_post_meta( $item_id, '_vc_custom_post_css', $custom_css );
		} else {
			delete_post_meta( $item_id, '_vc_custom_post_css', $custom_css );
		}

		update_post_meta( $item_id, '_elegant_menu_item_content', $content );

		$response = array(
			'success' => esc_html__( 'Content saved successfully!', 'elegant-elements' ),
		);

		die( wp_json_encode( $response ) );
	}

	die();
}
add_action( 'wp_ajax_save_elegant_menu_item', 'save_elegant_menu_item', 0, 0 );

/**
 * Save the menu item content to the menu item meta.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function save_elegant_menu_item_settings() {
	$form_data = $_POST; // @codingStandardsIgnoreLine

	$item_id = $form_data['item_id'];

	$walker_args = elegant_get_walker_fields();

	foreach ( $walker_args as $key => $option ) {
		if ( isset( $form_data[ $key ] ) ) {
			$walker_args[ $key ] = $form_data[ $key ];
		}
	}

	update_post_meta( $item_id, '_elegant_menu_item_settings', $walker_args );

	$response = array(
		'success' => esc_html__( 'Settings saved successfully!', 'elegant-elements' ),
	);

	die( wp_json_encode( $response ) );
}
add_action( 'wp_ajax_save_elegant_menu_item_settings', 'save_elegant_menu_item_settings', 1, 0 );

// Include the menu walker class and custom fields.
require_once EEWPB_PLUGIN_DIR . 'elements/header/walker/navwalker.php';
require_once EEWPB_PLUGIN_DIR . 'elements/header/walker/menu-meta.php';
