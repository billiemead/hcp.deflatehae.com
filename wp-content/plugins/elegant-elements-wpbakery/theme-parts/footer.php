<?php
/**
 * Footer template.
 * Used for footer preview only.
 *
 * @package Elegant Elements WPBakery
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php
get_header();
?>
	<div id="elegant-footer-placeholder-content" class="content-area">
		<main id="main" class="site-main" role="main">
			<h3 class="header-edit-heading">Elegant Footer Builder</h3>
		</main><!-- .site-main -->

	</div><!-- .content-area -->
</div>
<?php
// Enqueue footer styles on frontend.
wp_enqueue_style( 'infi-elegant-elements-footers-min', EEWPB_PLUGIN_URL . 'assets/css/min/elegant-footers.min.css', '', EEWPB_VERSION );
?>
	<div id="elegant-footer-wrapper" class="elegant-footer-wrapper">
		<footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" role="contentinfo">
			<?php
			// Start the loop.
			while ( have_posts() ) :
				the_post();
				?>
				<div class="footer-content">
					<?php
					the_content();
					?>
				</div><!-- .footer-content -->
				<?php
				// End the loop.
			endwhile;
			?>
		</footer>
	</div>
<?php
// Add footer placeholder content styles.
do_action( 'elegant_footer_placeholder_styles' );

// Run the wp_footer action to enqueue required scripts.
do_action( 'wp_footer' );
?>
</body>
</html>
