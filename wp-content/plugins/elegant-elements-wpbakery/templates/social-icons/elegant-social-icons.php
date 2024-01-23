<?php
$icons = array(
	'blogger'        => 'Blogger',
	'deviantart'     => 'Deviantart',
	'discord'        => 'Discord',
	'digg'           => 'Digg',
	'dribbble'       => 'Dribbble',
	'dropbox'        => 'Dropbox',
	'facebook'       => 'Facebook',
	'flickr'         => 'Flickr',
	'forrst'         => 'Forrst',
	'github'         => 'GitHub',
	'googlebusiness' => 'Google My Business',
	'instagram'      => 'Instagram',
	'linkedin'       => 'LinkedIn',
	'mixer'          => 'Mixer',
	'myspace'        => 'Myspace',
	'paypal'         => 'PayPal',
	'pinterest'      => 'Pinterest',
	'reddit'         => 'Reddit',
	'rss'            => 'RSS Feed',
	'skype'          => 'Skype',
	'soundcloud'     => 'SoundCloud',
	'spotify'        => 'Spotify',
	'tiktok'         => 'TikTok',
	'tumblr'         => 'Tumblr',
	'tripadvisor'    => 'TripAdvisor',
	'twitch'         => 'Twitch',
	'twitter'        => 'Ttwitter',
	'vimeo'          => 'Vimeo',
	'vk'             => 'VK',
	'wechat'         => 'WeChat',
	'whatsapp'       => 'WhatsApp',
	'wordpress'      => 'WordPress',
	'xing'           => 'Xing',
	'yahoo'          => 'Yahoo',
	'yelp'           => 'Yelp',
	'youtube'        => 'YouTube',
);

$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-social-icons' ) . '>';

foreach ( $icons as $icon => $title ) {
	if ( isset( $this->args[ $icon ] ) && '' !== $this->args[ $icon ] ) {
		ob_start();
		include EEWPB_PLUGIN_DIR . 'assets/icons/svg/' . $icon . '.svg';
		$icon_svg = ob_get_clean();
		$html    .= '<a ' . Elegant_Elements_WPBakery::attributes( 'elegant-social-icon', array( 'class' => $icon ) ) . ' title="' . $title . '" href="' . $this->args[ $icon ] . '">' . $icon_svg . '</a>';
	}
}

if ( isset( $this->args['email_address'] ) && '' !== $this->args['email_address'] ) {
	ob_start();
	$icon_svg = include EEWPB_PLUGIN_DIR . 'assets/icons/svg/mail.svg';
	$icon_svg = ob_get_clean();
	$html    .= '<a ' . Elegant_Elements_WPBakery::attributes( 'elegant-social-icon', array( 'class' => 'email' ) ) . ' title="' . esc_attr__( 'Email', 'elegant-elements' ) . '" href="mailto:' . $this->args['email_address'] . '">' . $icon_svg . '</a>';
}

if ( isset( $this->args['phone_number'] ) && '' !== $this->args['phone_number'] ) {
	ob_start();
	$icon_svg = include EEWPB_PLUGIN_DIR . 'assets/icons/svg/phone.svg';
	$icon_svg = ob_get_clean();
	$html    .= '<a ' . Elegant_Elements_WPBakery::attributes( 'elegant-social-icon', array( 'class' => 'phone' ) ) . ' title="' . esc_attr__( 'Phone', 'elegant-elements' ) . '" href="tel:' . $this->args['phone_number'] . '">' . $icon_svg . '</a>';
}

$html .= '</div>';
