<?php
/**
 * Header template.
 * Used for header preview only.
 *
 * @package Elegant Elements WPBakery
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<!DOCTYPE html>
<html class="elegant-elements-custom-header" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php
	// Enqueue header styles on frontend.
	wp_enqueue_style( 'infi-elegant-elements-header-min', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-elements-header.min.css', '', EEWPB_VERSION );
	?>
	<?php wp_head(); ?>

	<?php
	/**
	 * The setting below is not sanitized.
	 * In order to be able to take advantage of this,
	 * a user would have to gain access to the file system
	 * in which case this is the least of your worries.
	 */
	echo apply_filters( 'elegant_elements_header_before_head', '' ); // phpcs:ignore WordPress.Security.EscapeOutput

	do_action( 'elegant_header_placeholder_styles' );
	?>
</head>
<body <?php body_class(); ?>>
	<?php do_action( 'wp_body_open' ); ?>
	<?php do_action( 'elegant_elements_header_before_body_content' ); ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'elegant-elements' ); ?></a>
	<?php
	$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine

	if ( $is_vc_inline ) {
		?>
		<div class="wpb-controls-placeholder"></div>
		<?php
	}
	?>
	<div id="elegant-header-wrapper" class="elegant-header-wrapper">
		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php
					the_content();
					?>
				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->
			<?php
			// End the loop.
		endwhile;
		?>
	</div>
	<div id="page" class="hfeed site container eewpb-container">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<h3 class="header-edit-heading">Elegant Header Builder</h3>
			</main><!-- .site-main -->

		</div><!-- .content-area -->

<?php get_footer(); ?>
