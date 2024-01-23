<?php
/**
 * Page Title Bar edit template.
 * Used for page title bar preview only.
 *
 * @package Elegant Elements WPBakery
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

get_header();
?>
<div class="elegant-page-title-bar">
	<?php
	// Start the loop.
	while ( have_posts() ) :
		the_post();
		?>
		<div class="page-title-bar-content">
			<?php
			the_content();
			?>
		</div><!-- .footer-content -->
		<?php
		// End the loop.
	endwhile;
	?>
</div>
	<div id="elegant-page-title-bar-placeholder-content" class="content-area">
		<main id="main" class="site-main" role="main">
			<h3 class="header-edit-heading">Elegant Page Title Bar Builder</h3>
		</main><!-- .site-main -->

	</div><!-- .content-area -->
</div>
<?php
// Run the wp_footer action to enqueue required scripts.
do_action( 'wp_footer' );
?>
<style type="text/css">
.page-title-bar-content.vc-main-sortable-container > .vc_empty-placeholder {
	display: none;
}
div#main {
	z-index: 999999;
}
</style>
</body>
</html>
