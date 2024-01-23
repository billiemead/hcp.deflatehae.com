<?php
if ( ! class_exists( 'EEWPB_Social_Icons' ) && elegant_is_element_enabled( 'iee_social_icons' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class EEWPB_Social_Icons {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-social-icons', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-social-icon', array( $this, 'attr_icon' ) );
			add_shortcode( 'iee_social_icons', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'blogger'                   => '',
					'deviantart'                => '',
					'discord'                   => '',
					'digg'                      => '',
					'dribbble'                  => '',
					'dropbox'                   => '',
					'facebook'                  => '',
					'flickr'                    => '',
					'forrst'                    => '',
					'github'                    => '',
					'googlebusiness'            => '',
					'instagram'                 => '',
					'linkedin'                  => '',
					'mixer'                     => '',
					'myspace'                   => '',
					'paypal'                    => '',
					'pinterest'                 => '',
					'reddit'                    => '',
					'rss'                       => '',
					'skype'                     => '',
					'soundcloud'                => '',
					'spotify'                   => '',
					'tiktok'                    => '',
					'tumblr'                    => '',
					'tripadvisor'               => '',
					'twitch'                    => '',
					'twitter'                   => '',
					'vimeo'                     => '',
					'vk'                        => '',
					'wechat'                    => '',
					'whatsapp'                  => '',
					'wordpress'                 => '',
					'xing'                      => '',
					'yahoo'                     => '',
					'yelp'                      => '',
					'youtube'                   => '',
					'email_address'             => '',
					'phone_number'              => '',
					'boxed_icons'               => 'yes',
					'social_icon_border_radius' => '4',
					'social_icon_size'          => '16',
					'icons_space'               => '10',
					'color_type'                => 'brand',
					'custom_color'              => '#a0a0a0',
					'custom_color_boxed'        => '#e8e8e8',
					'icon_alignment'            => 'left',
					'hide_on_mobile'            => elegant_elements_default_visibility( 'string' ),
					'class'                     => '',
					'id'                        => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/social-icons/elegant-social-icons.php' ) ) {
				include locate_template( 'templates/social-icons/elegant-social-icons.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/social-icons/elegant-social-icons.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-social-icons',
				'style' => '',
			);

			$attr['class'] .= ' boxed-icons-' . $this->args['boxed_icons'];
			$attr['class'] .= ' elegant-align-' . $this->args['icon_alignment'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( '' !== $this->args['social_icon_size'] ) {
				$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['social_icon_size'], 'px' );
				$attr['style'] .= 'font-size:' . $font_size . ';';
			}

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param array $atts Attributes array passed by the filter.
		 * @return array
		 */
		public function attr_icon( $atts = array() ) {
			$attr = array(
				'class' => 'elegant-social-icon',
				'style' => '',
			);

			if ( $atts['class'] ) {
				$attr['class'] .= ' social-icon-' . $atts['class'];
			}

			if ( 'custom' === $this->args['color_type'] ) {
				if ( '' !== $this->args['custom_color'] ) {
					$attr['style'] .= 'fill:' . $this->args['custom_color'] . ';';
				}

				if ( 'yes' === $this->args['boxed_icons'] ) {
					if ( '' !== $this->args['custom_color_boxed'] ) {
						$attr['style'] .= 'background-color:' . $this->args['custom_color_boxed'] . ';';
					}
				}
			}

			if ( 'yes' === $this->args['boxed_icons'] ) {
				if ( '' !== $this->args['social_icon_border_radius'] ) {
					$attr['style'] .= 'border-radius:' . $this->args['social_icon_border_radius'] . 'px;';
				}
			}

			$attr['style'] .= 'margin-right:' . $this->args['icons_space'] . 'px;';

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-social-icons' );
		}
	}

	new EEWPB_Social_Icons();
} // End if().

/**
 * Map shortcode for social_icons.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_wpbakery_social_icons() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Social Icons', 'elegant-elements' ),
			'shortcode' => 'iee_social_icons',
			'icon'      => 'fa-share-alt fas social-icons-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Blogger Link', 'elegant-elements' ),
					'param_name'  => 'blogger',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Blogger link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Deviantart Link', 'elegant-elements' ),
					'param_name'  => 'deviantart',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Deviantart link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Discord Link', 'elegant-elements' ),
					'param_name'  => 'discord',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Discord link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Digg Link', 'elegant-elements' ),
					'param_name'  => 'digg',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Digg link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Dribbble Link', 'elegant-elements' ),
					'param_name'  => 'dribbble',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Dribbble link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Dropbox Link', 'elegant-elements' ),
					'param_name'  => 'dropbox',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Dropbox link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Facebook Link', 'elegant-elements' ),
					'param_name'  => 'facebook',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Facebook link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Flickr Link', 'elegant-elements' ),
					'param_name'  => 'flickr',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Flickr link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Forrst Link', 'elegant-elements' ),
					'param_name'  => 'forrst',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Forrst link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Github Link', 'elegant-elements' ),
					'param_name'  => 'github',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Github link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Google My Business Link', 'elegant-elements' ),
					'param_name'  => 'googlebusiness',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Google My Business link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Instagram Link', 'elegant-elements' ),
					'param_name'  => 'instagram',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Instagram link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'LinkedIn Link', 'elegant-elements' ),
					'param_name'  => 'linkedin',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom LinkedIn link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Mixer Link', 'elegant-elements' ),
					'param_name'  => 'mixer',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Mixer link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Myspace Link', 'elegant-elements' ),
					'param_name'  => 'myspace',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Myspace link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'PayPal Link', 'elegant-elements' ),
					'param_name'  => 'paypal',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom PayPal link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Pinterest Link', 'elegant-elements' ),
					'param_name'  => 'pinterest',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Pinterest link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Reddit Link', 'elegant-elements' ),
					'param_name'  => 'reddit',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Reddit link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'RSS Link', 'elegant-elements' ),
					'param_name'  => 'rss',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom RSS link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Skype Link', 'elegant-elements' ),
					'param_name'  => 'skype',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Skype link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'SoundCloud Link', 'elegant-elements' ),
					'param_name'  => 'soundcloud',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom SoundCloud link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Spotify Link', 'elegant-elements' ),
					'param_name'  => 'spotify',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Spotify link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Tiktok Link', 'elegant-elements' ),
					'param_name'  => 'tiktok',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Tiktok link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Tumblr Link', 'elegant-elements' ),
					'param_name'  => 'tumblr',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Tumblr link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'TripAdvisor Link', 'elegant-elements' ),
					'param_name'  => 'tripadvisor',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom TripAdvisor link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Twitch Link', 'elegant-elements' ),
					'param_name'  => 'twitch',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Twitch link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Twitter Link', 'elegant-elements' ),
					'param_name'  => 'twitter',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Twitter link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Vimeo Link', 'elegant-elements' ),
					'param_name'  => 'vimeo',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Vimeo link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'VK Link', 'elegant-elements' ),
					'param_name'  => 'vk',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom VK link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'WeChat Link', 'elegant-elements' ),
					'param_name'  => 'wechat',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom WeChat link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'WhatsApp Link', 'elegant-elements' ),
					'param_name'  => 'whatsapp',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom WhatsApp link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'WordPress Link', 'elegant-elements' ),
					'param_name'  => 'wordpress',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom WordPress link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Xing Link', 'elegant-elements' ),
					'param_name'  => 'xing',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Xing link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Yahoo Link', 'elegant-elements' ),
					'param_name'  => 'yahoo',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Yahoo link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Yelp Link', 'elegant-elements' ),
					'param_name'  => 'yelp',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Yelp link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Youtube Link', 'elegant-elements' ),
					'param_name'  => 'youtube',
					'value'       => '',
					'description' => esc_attr__( 'Insert your custom Youtube link.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Email Address', 'elegant-elements' ),
					'param_name'  => 'email_address',
					'value'       => '',
					'description' => esc_attr__( 'Insert an email address to display the email icon.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Phone Number', 'elegant-elements' ),
					'param_name'  => 'phone_number',
					'value'       => '',
					'description' => esc_attr__( 'Insert a phone number to display the phone icon.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Boxed Icons', 'elegant-elements' ),
					'description' => esc_attr__( 'Set the icons to be displayed in boxed layout.', 'elegant-elements' ),
					'param_name'  => 'boxed_icons',
					'std'         => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Social Icon Box Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border radius of the social icons box. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'social_icon_border_radius',
					'value'       => '4',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'boxed_icons',
						'value'   => array( 'yes' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font size for the social icons. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'social_icon_size',
					'value'       => '16',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Space Between Icons', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the space between icons. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icons_space',
					'value'       => '10',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Color Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the social icons color type. Brand colors will use the icon brand color. You can set the custom icons with the custom option.', 'elegant-elements' ),
					'param_name'  => 'color_type',
					'default'     => 'brand',
					'value'       => array(
						'brand'  => esc_attr__( 'Brand Colors', 'elegant-elements' ),
						'custom' => esc_attr__( 'Custom Colors', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Custom Color for Social Icons', 'elegant-elements' ),
					'param_name'  => 'custom_color',
					'value'       => '#a0a0a0',
					'description' => esc_attr__( 'Specify the icon color.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'color_type',
						'value'   => array( 'custom' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Social Icons Box Background Custom Color', 'elegant-elements' ),
					'param_name'  => 'custom_color_boxed',
					'value'       => '#e8e8e8',
					'description' => esc_attr__( 'Specify the icon background color.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'color_type',
						'value'   => array( 'custom' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the social icons alignment.', 'elegant-elements' ),
					'param_name'  => 'icon_alignment',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'elegant-elements' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => elegant_elements_visibility_options( 'full' ),
					'icons'       => elegant_get_visibility_icons(),
					'default'     => elegant_elements_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
					'param_name'  => 'class',
					'value'       => '',
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
					'param_name'  => 'id',
					'value'       => '',
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				),
			),
		)
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_social_icons', 99 );
