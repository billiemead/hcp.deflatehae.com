<?php
$style = '<style type="text/css">';

if ( isset( $this->args['color'] ) && '' !== $this->args['color'] ) {
	$hover_color = ( isset( $this->args['color_hover'] ) && '' !== $this->args['color_hover'] ) ? '--color:' . $this->args['color_hover'] . ' !important;' : '';
	$style      .= '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link { color:' . $this->args['color'] . ' !important;' . $hover_color . ' border-color:' . $this->args['color'] . ';--border-color:' . $this->args['background'] . '}';
	$style      .= '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link:hover { color:' . $this->args['color_hover'] . ' !important;}';
}

if ( isset( $this->args['background'] ) && '' !== $this->args['background'] ) {
	$style_class = '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link.elegant-button-' . $this->args['style'];
	switch ( $this->args['style'] ) {
		case 'swipe':
			$style .= $style_class . ':before{ background:' . $this->args['background'] . ';}';
			break;
		case 'diagonal-swipe':
			$style .= $style_class . ':after { border-top-color:' . $this->args['background'] . ';}';
			break;
		case 'double-swipe':
			$style .= $style_class . ':before { border-left-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ':after { border-bottom-color:' . $this->args['background'] . ';}';
			break;
		case 'zoning-in':
			$style .= $style_class . ':before { border-left-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ':after { border-right-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ' span:before { border-bottom-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ' span:after { border-top-color:' . $this->args['background'] . ';}';
			break;
		case 'diagonal-close':
			$style .= $style_class . ':before { border-left-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ':after { border-right-color:' . $this->args['background'] . ';}';
			break;
		case 'corners':
			$style .= $style_class . ':before, ' . $style_class . ':after, ' . $style_class . ' span:before, ' . $style_class . ' span:after { border-color:' . $this->args['background'] . ';}';
			break;
		case 'alternate':
			$style .= $style_class . ':before, ' . $style_class . ':after, ' . $style_class . ' span:before, ' . $style_class . ' span:after { background-color:' . $this->args['background'] . ';}';
			break;
		case 'slice':
			$style .= $style_class . ':before{ border-left-color:' . $this->args['background'] . ';}';
			$style .= $style_class . ':after{ border-right-color:' . $this->args['background'] . ';}';
			break;
		case 'position-aware':
			$style .= $style_class . ' span{ background-color:' . $this->args['background'] . ';}';
			break;
		case 'smoosh':
		case 'collision':
			$style .= $style_class . ':before, ' . $style_class . ':after{ background-color:' . $this->args['background'] . ';}';
			break;
		case 'vertical-overlap':
		case 'horizontal-overlap':
			$color        = Elegant_Color::new_color( $this->args['background'] );
			$color->alpha = 0.50;
			$color_css    = $color->getNew( 'brightness', 0.50 );
			$style       .= $style_class . ':before, ' . $style_class . ':after{ background-color:' . $color_css->color . ';}';
			$style       .= $style_class . ' span:before, ' . $style_class . ' span:after{ background-color:' . $color_css->color . ';}';
			break;
	}
}

$style .= '</style>';

$icon = '';

if ( isset( $this->args['button_icon'] ) && '' !== $this->args['button_icon'] ) {
	$icon_class = $this->args['button_icon'];
	$icon       = '<i class="' . $icon_class . '"></i>';
}

if ( isset( $this->args['button_title'] ) && '' !== $this->args['button_title'] ) {
	$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-fancy-button-wrapper' ) . '>';
	$html .= $style;
	$html .= '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-fancy-button' ) . '>';

	$button_title = $this->args['button_title'];

	if ( '' !== $icon && 'left' === $this->args['icon_position'] ) {
		$button_title = $icon . $button_title;
	} elseif ( '' !== $icon && 'right' === $this->args['icon_position'] ) {
		$button_title = $button_title . $icon;
	}

	if ( 'corners' === $this->args['style'] || 'alternate' === $this->args['style'] || 'zoning-in' === $this->args['style'] || 'vertical-overlap' === $this->args['style'] || 'horizontal-overlap' === $this->args['style'] ) {
		$button_title = '<span>' . $button_title . '</span>';
	}

	if ( 'position-aware' == $this->args['style'] ) {
		$button_title = '<span></span>' . $button_title;
	}

	$html .= '<a ' . Elegant_Elements_WPBakery::attributes( 'elegant-fancy-button-link' ) . '>' . $button_title . '</a>';
	$html .= '</div>';
	$html .= '</div>';
}
