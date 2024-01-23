<?php
$style = '';

if ( isset( $this->args['border_color'] ) && '' !== $this->args['border_color'] ) {
	$style = '<style type="text/css">.elegant-modal.modal-' . $this->modal_counter . ' .modal-header, .elegant-modal.modal-' . $this->modal_counter . ' .modal-footer{border-color:' . $this->args['border_color'] . ';}</style>';
}

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode' ) . '>';
$html .= $style;
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-dialog' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-content' ) . '>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-header' ) . '>';
$html .= '<button ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-button' ) . '>&times;</button>';
$html .= '<h3 ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-heading' ) . '>' . $this->args['title'] . '</h3>';
$html .= '</div>';
$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-body' ) . '>' . do_shortcode( $content ) . '</div>';

if ( isset( $this->args['show_footer'] ) && 'yes' === $this->args['show_footer'] ) {
	$button_title = ( isset( $this->args['button_title'] ) && '' !== $this->args['button_title'] ) ? $this->args['button_title'] : esc_attr__( 'Close', 'elegant-elements' );

	$button_style = 'color:' . $this->args['button_color'] . ';';
	$button_style = 'background:' . $this->args['button_background_color'] . ';';
	$button_html  = '<button data-dismiss="modal" class="btn elegant-modal-close-button" style="' . $button_style . '">' . $button_title . '</button>';

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-footer' ) . '>';
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-shortcode-button-footer' ) . '>';
	$html .= do_shortcode( $button_html );
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div></div></div>';

if ( isset( $this->args['modal_trigger'] ) && 'none' !== $this->args['modal_trigger'] ) {
	$trigger = '';
	switch ( $this->args['modal_trigger'] ) {
		case 'image':
			$image_url = '';
			if ( isset( $this->args['image_url'] ) && '' !== $this->args['image_url'] ) {
				$image     = wp_get_attachment_image_src( $this->args['image_url'], 'full' );
				$image_url = $image[0];
				$image_url = esc_url( $image_url );
			}
			$trigger = '<img src="' . $image_url . '">';
			break;
		case 'icon':
			$trigger = ( isset( $this->args['icon'] ) && '' !== $this->args['icon'] ) ? '<i class="' . $this->args['icon'] . '"></i>' : '';  // @codingStandardsIgnoreLine
			break;
		case 'text':
			$trigger = ( isset( $this->args['custom_text'] ) && '' !== $this->args['custom_text'] ) ? do_shortcode( rawurldecode( base64_decode( $this->args['custom_text'] ) ) ) : ''; // @codingStandardsIgnoreLine
			break;
		case 'button':
			$trigger = ( isset( $this->args['button_shortcode'] ) && '' !== $this->args['button_shortcode'] ) ? do_shortcode( rawurldecode( base64_decode( $this->args['button_shortcode'] ) ) ) : ''; // @codingStandardsIgnoreLine
			break;
	}

	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'infi-modal-trigger' ) . '>';
	$html .= $trigger;
	$html .= '</div>';
}
