<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-faq-rich-snippets' ) . '>';

if ( '' !== $this->args['title'] ) {
	$html .= '<h2 class="faq-rich-snippets-title">' . $this->args['title'] . '</h2>';
}

if ( 'descriptive' === $this->args['output_type'] ) {
	$html .= do_shortcode( $content );
} else {

	if ( shortcode_exists( 'vc_tta_accordion' ) ) {
		$accordion  = '[vc_tta_accordion]';
		$accordion .= do_shortcode( $content );
		$accordion .= '[/vc_tta_accordion]';
	} elseif ( shortcode_exists( 'vc_accordion_tab' ) ) {
		$accordion  = '[vc_accordion]';
		$accordion .= do_shortcode( $content );
		$accordion .= '[/vc_accordion]';
	}

	$html .= do_shortcode( $accordion );
}

$html .= '</div>';

// Generate rich snippets JSON for FAQ.
$html .= '<script type="application/ld+json">' . wp_json_encode( $this->seo_faq_data ) . '</script>';
