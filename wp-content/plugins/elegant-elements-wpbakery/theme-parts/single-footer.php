<?php
/**
 * Footer template.
 *
 * @package Elegant Elements WPBakery
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
	</div> <!-- .eewpb-container -->
	<?php
	// Render the footer template.
	do_action( 'eewpb_footer_render_content' );

	// Woodmart theme mobile menu compatibility.
	if ( function_exists( 'woodmart_needs_footer' ) ) {
		?>
		<div class="woodmart-close-side"></div>
		<?php
	}
	?>
	<?php wp_footer(); ?>
	</body>
</html>
