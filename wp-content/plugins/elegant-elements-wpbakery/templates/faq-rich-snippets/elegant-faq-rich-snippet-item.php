<?php
$content = wpautop( do_shortcode( $content ) );

if ( 'descriptive' === $this->args['output_type'] ) {
	$child_html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-faq-rich-snippet-item' ) . '>';
	$child_html .= '<h3 class="faq-rich-snippet-item-question">' . $this->child_args['question'] . '</h3>';
	$child_html .= '<div class="faq-rich-snippet-item-answer">' . $content . '</div>';
	$child_html .= '</div>';
} else {
	if ( shortcode_exists( 'vc_tta_accordion' ) ) {
		$accordion = '[vc_tta_section title="' . $this->child_args['question'] . '" open="no" tab_id="' . wp_rand() . '"]' . $content . '[/vc_tta_section]';
	} elseif ( shortcode_exists( 'vc_accordion_tab' ) ) {
		$accordion = '[vc_accordion_tab title="' . $this->child_args['question'] . '" open="no" tab_id="' . wp_rand() . '"]' . $content . '[/vc_accordion_tab]';
	}

	$child_html .= $accordion;
}

$this->seo_faq_data['mainEntity'][] = array(
	'@type'          => 'Question',
	'name'           => $this->child_args['question'],
	'acceptedAnswer' => array(
		'@type' => 'Answer',
		'text'  => $content,
	),
);
