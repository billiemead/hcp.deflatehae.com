<?php
$rand = wp_rand();
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-syntax-highlighter', array( 'rand' => $rand ) ) . '>';

if ( 'yes' === $this->args['copy_to_clipboard'] ) {
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-syntax-highlighter-copy-code' ) . '>';
	$html .= '<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-syntax-highlighter-copy-code-title', array( 'rand' => $rand ) ) . '>' . $this->args['copy_to_clipboard_text'] . '</span>';
	$html .= '</div>';
}

// Enqueue code editor and settings for manipulating CSS.
if ( function_exists( 'wp_enqueue_code_editor' ) ) {
	wp_enqueue_code_editor( array() );

	$type = in_array( $this->args['language'], array( 'json', 'xml' ), true ) ? 'application' : 'text';

	// Get CodeMirror options.
	$settings                 = array();
	$settings['readOnly']     = 'nocursor';
	$settings['lineNumbers']  = ( 'yes' === $this->args['line_numbers'] ) ? true : false;
	$settings['lineWrapping'] = ( 'break' === $this->args['line_wrapping'] ) ? true : false;
	$settings['theme']        = $this->args['theme'];
	$settings['rand']         = $rand;

	if ( isset( $this->args['language'] ) && '' !== $this->args['language'] ) {
		$settings['mode'] = $type . '/' . $this->args['language'];
	}

	$html .= '<textarea ' . Elegant_Elements_WPBakery::attributes( 'elegant-syntax-highlighter-textarea', $settings ) . '>' . $content . '</textarea>';
} else {
	// Compatibility for WP < 4.9.
	$html .= '<pre id="elegant_syntax_highlighter_' . $rand . '">' . $content . '</pre>';
}

$style = '<style type="text/css" scopped="scopped">';

if ( $this->args['background_color'] ) {
	$style .= '.elegant-syntax-highlighter-' . $rand . ' > .CodeMirror, .elegant-syntax-highlighter-' . $rand . ' > .CodeMirror .CodeMirror-gutters {' . sprintf( 'background-color:%s;', $this->args['background_color'] ) . '}';
}

if ( 'no' !== $this->args['line_numbers'] ) {
	$style .= '.elegant-syntax-highlighter-' . $rand . ' > .CodeMirror .CodeMirror-gutters { background-color: ' . $this->args['line_number_background_color'] . '; }';
	$style .= '.elegant-syntax-highlighter-' . $rand . ' > .CodeMirror .CodeMirror-linenumber { color: ' . $this->args['line_number_text_color'] . '; }';
}

$style .= '</style>';

$html .= $style;
$html .= '</div>';
