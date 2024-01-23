<?php
if ( ! class_exists( 'EEWPB_Fancy_Button' ) && elegant_is_element_enabled( 'iee_fancy_button' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Fancy_Button {

		/**
		 * The button counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $button_counter = 1;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			$is_vc_inline = ( isset( $_GET['vc_editable'] ) ) ? true : false; // @codingStandardsIgnoreLine
			if ( $is_vc_inline ) {
				$this->button_counter = wp_rand();
			}

			add_filter( 'eewpb_attr_elegant-fancy-button', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-fancy-button-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'eewpb_attr_elegant-fancy-button-link', array( $this, 'link_attr' ) );

			add_shortcode( 'iee_fancy_button', array( $this, 'render' ) );
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

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'button_title'            => esc_attr__( 'Fancy Button', 'elegant-elements' ),
					'style'                   => 'swipe',
					'button_icon'             => '',
					'icon_position'           => 'left',
					'action'                  => 'custom_link',
					'custom_link'             => '',
					'lightbox_image_url'      => '',
					'lightbox_video_url'      => '',
					'modal_name'              => '',
					'color'                   => '#0062d3',
					'color_hover'             => '#ffffff',
					'background'              => '#0062d3',
					'size'                    => 'medium',
					'shape'                   => 'square',
					'margin'                  => '',
					'alignment'               => 'left',
					'element_typography'      => '',
					'typography_button_title' => '',
					'title_font_size'         => '18',
					'hide_on_mobile'          => elegant_elements_default_visibility( 'string' ),
					'class'                   => '',
					'id'                      => '',
				),
				$args
			);

			$this->args = $defaults;

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$html = '';

			if ( '' !== locate_template( 'templates/fancy-button/elegant-fancy-button.php' ) ) {
				include locate_template( 'templates/fancy-button/elegant-fancy-button.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/fancy-button/elegant-fancy-button.php';
			}

			$this->button_counter++;

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
				'class' => 'elegant-fancy-button-wrap elegant-fancy-button-' . $this->button_counter,
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['margin'] ) && '' !== $this->args['margin'] ) {
				$attr['style'] = 'margin:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin'], 'px' ) . ';';
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
		 * @since 1.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-fancy-button-wrapper',
			);

			if ( $this->args['alignment'] ) {
				$attr['class'] .= ' elegant-align-' . $this->args['alignment'];
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
				'class' => 'elegant-fancy-button-link elegant-button-' . $this->args['style'] . ' elegant-button-' . $this->args['shape'] . ' elegant-button-' . $this->args['size'] . '',
				'style' => '',
				'href'  => '#',
			);

			if ( isset( $this->args['icon_position'] ) && '' !== $this->args['icon_position'] ) {
				$attr['class'] .= ' elegant-fancy-button-icon-' . $this->args['icon_position'];
			}

			if ( isset( $this->args['action'] ) && 'custom_link' === $this->args['action'] ) {
				$link   = vc_build_link( $this->args['custom_link'] );
				$url    = esc_url( $link['url'] );
				$target = ( isset( $link['target'] ) ) ? trim( $link['target'] ) : '';

				$attr['href']   = $url;
				$attr['target'] = $target;
			} elseif ( isset( $this->args['action'] ) && 'image_lightbox' === $this->args['action'] ) {
				$lightbox_image_url = '#';
				if ( isset( $this->args['lightbox_image_url'] ) && '' !== $this->args['lightbox_image_url'] ) {
					$lightbox_image     = wp_get_attachment_image_src( $this->args['lightbox_image_url'], 'full' );
					$lightbox_image_url = $lightbox_image[0];
					$lightbox_image_url = esc_url( $lightbox_image_url );
				}

				$attr['href']     = $lightbox_image_url;
				$attr['data-rel'] = 'prettyPhoto';
				$attr['class']   .= ' elegant-lightbox prettyphoto';
			} elseif ( isset( $this->args['action'] ) && 'video_lightbox' === $this->args['action'] ) {
				$attr['href']     = ( isset( $this->args['lightbox_video_url'] ) && '' !== $this->args['lightbox_video_url'] ) ? $this->args['lightbox_video_url'] : '#';
				$attr['data-rel'] = 'prettyPhoto';
				$attr['class']   .= ' elegant-lightbox prettyphoto';
			} elseif ( isset( $this->args['action'] ) && 'modal' === $this->args['action'] ) {
				$attr['data-toggle'] = 'modal';
				$attr['data-target'] = '.modal.' . $this->args['modal_name'];
			}

			if ( isset( $this->args['typography_button_title'] ) && '' !== $this->args['typography_button_title'] ) {
				$button_title_typography = elegant_get_google_font_styling( $this->args, 'typography_button_title' );

				$attr['style'] .= $button_title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height: 1em;';
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_scripts() {
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			if ( isset( $this->args['action'] ) && ( 'image_lightbox' === $this->args['action'] || 'video_lightbox' === $this->args['action'] ) ) {
				wp_enqueue_script( 'prettyphoto' );
				wp_enqueue_style( 'prettyphoto' );
			}

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-fancy-button',
				$eewpb_js_folder_url . '/infi-elegant-fancy-button.min.js',
				$elegant_js_folder_path . '/infi-elegant-fancy-button.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-fancy-button' );
		}
	}

	new EEWPB_Fancy_Button();
} // End if().

/**
 * Map shortcode for fancy_button.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_fancy_button() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Fancy Button', 'elegant-elements' ),
			'shortcode' => 'iee_fancy_button',
			'icon'      => 'fa-hand-point-up far elegant-fancy-button-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Button Text', 'elegant-elements' ),
					'param_name'  => 'button_title',
					'value'       => esc_attr__( 'Fancy Button', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter button title text.', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Button Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the button style.', 'elegant-elements' ),
					'param_name'  => 'style',
					'std'         => 'swipe',
					'value'       => array(
						'swipe'              => esc_attr__( 'Swipe', 'elegant-elements' ),
						'diagonal-swipe'     => esc_attr__( 'Diagonal Swipe', 'elegant-elements' ),
						'double-swipe'       => esc_attr__( 'Double Swipe', 'elegant-elements' ),
						'diagonal-close'     => esc_attr__( 'Diagonal Close', 'elegant-elements' ),
						'zoning-in'          => esc_attr__( 'Zoning In', 'elegant-elements' ),
						'corners'            => esc_attr__( '4 Corners', 'elegant-elements' ),
						'slice'              => esc_attr__( 'Slice', 'elegant-elements' ),
						'position-aware'     => esc_attr__( 'Position Aware', 'elegant-elements' ),
						'alternate'          => esc_attr__( 'Alternate', 'elegant-elements' ),
						'smoosh'             => esc_attr__( 'Smoosh', 'elegant-elements' ),
						'vertical-overlap'   => esc_attr__( 'Vertical Overlap', 'elegant-elements' ),
						'horizontal-overlap' => esc_attr__( 'Horizontal Overlap', 'elegant-elements' ),
						'collision'          => esc_attr__( 'Collision', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
					'param_name'  => 'button_icon',
					'value'       => '',
					'description' => esc_attr__( 'Choose icon to display in button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Icon Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon position.', 'elegant-elements' ),
					'param_name'  => 'icon_position',
					'std'         => 'left',
					'value'       => array(
						'left'  => esc_attr__( 'Left', 'elegant-elements' ),
						'right' => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element'   => 'button_icon',
						'not_empty' => true,
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the action to perfom on button click.', 'elegant-elements' ),
					'param_name'  => 'action',
					'std'         => 'custom_link',
					'value'       => array(
						'custom_link'    => esc_attr__( 'Open Custom Link', 'elegant-elements' ),
						'image_lightbox' => esc_attr__( 'Open Image in Lightbox', 'elegant-elements' ),
						'video_lightbox' => esc_attr__( 'Open Video in Lightbox', 'elegant-elements' ),
						'modal'          => esc_attr__( 'Open Modal Dialog', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_attr__( 'Custom Link', 'elegant-elements' ),
					'param_name'  => 'custom_link',
					'value'       => '',
					'description' => esc_attr__( 'Enter or select the link you want to open on button click.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'action',
						'value'   => array( 'custom_link' ),
					),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Lightbox Image', 'elegant-elements' ),
					'param_name'  => 'lightbox_image_url',
					'value'       => '',
					'description' => esc_attr__( 'Upload or select the image from library that you want to open in lightbox on button click.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'action',
						'value'   => array( 'image_lightbox' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Video URL', 'elegant-elements' ),
					'param_name'  => 'lightbox_video_url',
					'value'       => '',
					'description' => esc_attr__( 'Enter video url of YouTube or Vimeo that you want to open in lightbox on button click.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'action',
						'value'   => array( 'video_lightbox' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Name', 'elegant-elements' ),
					'param_name'  => 'modal_name',
					'value'       => '',
					'description' => esc_attr__( 'Enter the name of the modal dialog you want to open on button click.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'action',
						'value'   => array( 'modal' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Text & Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button text and border color.', 'elegant-elements' ),
					'param_name'  => 'color',
					'value'       => '#0062d3',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Text Hover Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button text color on hover.', 'elegant-elements' ),
					'param_name'  => 'color_hover',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background on Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button background color on hover animation.', 'elegant-elements' ),
					'param_name'  => 'background',
					'value'       => '#0062d3',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Button Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button size.', 'elegant-elements' ),
					'param_name'  => 'size',
					'std'         => 'medium',
					'value'       => array(
						'small'  => esc_attr__( 'Small', 'elegant-elements' ),
						'medium' => esc_attr__( 'Medium', 'elegant-elements' ),
						'large'  => esc_attr__( 'Large', 'elegant-elements' ),
						'xlarge' => esc_attr__( 'X-Large', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Button Shape', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the button shape.', 'elegant-elements' ),
					'param_name'  => 'shape',
					'std'         => 'square',
					'value'       => array(
						'square' => esc_attr__( 'Square', 'elegant-elements' ),
						'pill'   => esc_attr__( 'Pill', 'elegant-elements' ),
						'round'  => esc_attr__( 'Round', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Button Margin', 'elegant-elements' ),
					'param_name'  => 'margin',
					'value'       => '',
					'description' => esc_attr__( 'Enter margin to add space around the button. In Pixels (px) eg. 10px.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Button alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the button to left, right or center.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'std'         => 'default',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Button Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the button title text.', 'elegant-elements' ),
					'param_name'  => 'typography_button_title',
					'value'       => '',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for button title text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '75',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_fancy_button', 99 );
