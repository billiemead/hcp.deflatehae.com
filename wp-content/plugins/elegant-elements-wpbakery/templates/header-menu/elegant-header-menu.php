<?php
$mobile_class = ( wp_is_mobile() ) ? ' elegant-mobile-menu' : '';
$menu_options = array(
	'menu_style'         => $this->args['menu_style'],
	'mobile_toggle_type' => $this->args['mobile_toggle_type'],
	'mobile_toggle_text' => $this->args['mobile_toggle_text'],
	'mobile_toggle_icon' => $this->args['mobile_toggle_icon'],
);

$mobile_close_button = '<div class="mobile-menu-close-button"><span class="toggle-icon-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff" width="24" height="24"><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg></span></div>';

$menu_markup = wp_nav_menu(
	array(
		'menu'         => $this->args['menu'],
		'menu_class'   => 'elegant-menu elegant-custom-menu elegant-menu-element-list' . $mobile_class,
		'items_wrap'   => '<ul id="%1$s" class="%2$s menu__list">' . $mobile_close_button . '%3$s</ul>',
		'container'    => false,
		'fallback_cb'  => false,
		'echo'         => false,
		'depth'        => 5,
		'walker'       => new Elegant_Mega_Menu_Walker( $menu_options ),
		'item_spacing' => 'discard',
	)
);

$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-header-menu' ) . '>';
$html .= '<div id="elegant-menu-toggle" class="elegant-menu-toggle elegant-menu-toggle-wrapper">';

$button_css_vars  = '--toggle_font_size:' . $this->args['mobile_toggle_font_size'] . 'px;';
$button_css_vars .= '--mobile_toggle_background:' . $this->args['mobile_toggle_background'] . ';';
$button_css_vars .= '--mobile_toggle_color:' . $this->args['mobile_toggle_color'] . ';';

$html .= '<button class="elegant-menu-toggle" aria-controls="menu" aria-pressed="false" style="' . $button_css_vars . '">';

if ( 'both' === $this->args['mobile_toggle_type'] || 'icon' === $this->args['mobile_toggle_type'] ) {
	$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>';

	if ( '' !== $this->args['mobile_toggle_icon'] ) {
		$icon = '<i class="' . $this->args['mobile_toggle_icon'] . '"></i>';
	}

	$html .= '<span class="toggle-icon-open">' . $icon . '</span>';
}

$html .= '<span class="toggle-icon-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg></span>';

if ( 'both' === $this->args['mobile_toggle_type'] || 'text' === $this->args['mobile_toggle_type'] ) {
	$text = esc_attr__( 'Menu', 'elegant-elements' );

	if ( '' !== $this->args['mobile_toggle_text'] ) {
		$text = $this->args['mobile_toggle_text'];
	}

	$html .= '<span class="toggle-text">' . $text . '</span>';
}

$html .= '</button>';

$html .= '</div>';
$html .= $menu_markup;
$html .= '</div>';

if ( '' !== $this->mega_menu_styles ) {
	$html .= '<style type="text/css" scoped>' . $this->mega_menu_styles . '</style>';
}
