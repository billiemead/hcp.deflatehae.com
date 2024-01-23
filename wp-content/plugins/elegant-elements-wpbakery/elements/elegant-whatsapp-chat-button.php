<?php
if ( ! class_exists( 'EEWPB_WhatsApp_Chat_Button' ) && elegant_is_element_enabled( 'iee_whatsapp_chat_button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_WhatsApp_Chat_Button {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Elementor counter.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-whatsapp-chat-button', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-whatsapp-chat-button-link', array( $this, 'link_attr' ) );

			add_shortcode( 'iee_whatsapp_chat_button', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
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
					'phone_number'          => '',
					'title'                 => '',
					'secondary_title'       => '',
					'message'               => '',
					'avatar_image'          => '',
					'font_size'             => '18',
					'text_color'            => '#ffffff',
					'button_bg_color'       => '#0DC152',
					'button_hover_bg_color' => '#0DC152',
					'alignment'             => 'left',
					'hide_on_mobile'        => elegant_elements_default_visibility( 'string' ),
					'class'                 => '',
					'id'                    => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/whatsapp-chat-button/elegant-whatsapp-chat-button.php' ) ) {
				include locate_template( 'templates/whatsapp-chat-button/elegant-whatsapp-chat-button.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/whatsapp-chat-button/elegant-whatsapp-chat-button.php';
			}

			$this->counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-whatsapp-chat-button',
				'style' => '',
			);

			$attr['class'] .= ' whatsapp-chat-button-' . $this->counter;
			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

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
		 * @since 1.0
		 * @return array
		 */
		public function link_attr() {
			$attr = array(
				'class' => 'elegant-whatsapp-chat-button-link',
				'style' => '',
			);

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['text_color'] . ';';
			}

			if ( '' !== $this->args['button_bg_color'] ) {
				$attr['style'] .= 'background:' . $this->args['button_bg_color'] . ';';
			}

			$link_attr = array(
				'phone' => $this->args['phone_number'],
				'text'  => $this->args['message'],
			);

			$url = build_query( $link_attr );

			if ( wp_is_mobile() ) {
				$attr['href'] = 'https://api.whatsapp.com/send?' . $url;
			} else {
				$attr['href'] = 'https://web.whatsapp.com/send?' . $url;
			}

			$attr['target'] = '_blank';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return string
		 */
		public function generate_style() {
			$style = '';

			$style .= '.elegant-whatsapp-chat-button.whatsapp-chat-button-' . $this->counter . ' .elegant-whatsapp-chat-button-link:hover {';
			$style .= 'background:' . $this->args['button_hover_bg_color'] . ' !important;';
			$style .= '}';

			return $style;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-whatsapp-chat-button' );
		}
	}

	new EEWPB_WhatsApp_Chat_Button();
} // End if().

/**
 * Map shortcode for whatsapp_chat_button.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_whatsapp_chat_button() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant WhatsApp Chat Button', 'elegant-elements' ),
			'shortcode' => 'iee_whatsapp_chat_button',
			'icon'      => 'fa-whatsapp fab whatsapp-chat-button-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Phone Number', 'elegant-elements' ),
					'param_name'  => 'phone_number',
					'value'       => esc_attr__( '919822012345', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter your phone number with country code without the + sign.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'John Doe', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the title to be displayed on the button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Secondary Title', 'elegant-elements' ),
					'param_name'  => 'secondary_title',
					'value'       => esc_attr__( 'Support', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the secondary title you want to display inside the button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Message Content', 'elegant-elements' ),
					'param_name'  => 'message',
					'value'       => esc_attr__( 'I need your help with building my website.', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the message you want to get when someone clicks the button and contact you on the WhatsApp.', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Custom Avatar Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the custom avatar image. Square image is preferred. If no image is set, the default WhatsApp icon will be used.', 'elegant-elements' ),
					'param_name'  => 'avatar_image',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Text Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the button title. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '18',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for the button title.', 'elegant-elements' ),
					'param_name'  => 'text_color',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the button.', 'elegant-elements' ),
					'param_name'  => 'button_bg_color',
					'value'       => '#0DC152',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background Color on Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the button on hover.', 'elegant-elements' ),
					'param_name'  => 'button_hover_bg_color',
					'value'       => '#0DC152',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the text to left, right or center.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_whatsapp_chat_button', 99 );
