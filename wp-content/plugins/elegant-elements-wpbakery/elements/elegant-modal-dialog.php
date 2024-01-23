<?php
if ( ! class_exists( 'EEWPB_Modal_Dialog' ) && elegant_is_element_enabled( 'iee_modal_dialog' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Modal_Dialog {

		/**
		 * The modals counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $modal_counter = 1;

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

			add_filter( 'eewpb_attr_infi-modal-shortcode', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-dialog', array( $this, 'dialog_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-content', array( $this, 'content_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-heading', array( $this, 'heading_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-header', array( $this, 'header_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-body', array( $this, 'body_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-footer', array( $this, 'footer_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-button', array( $this, 'button_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-shortcode-button-footer', array( $this, 'button_footer_attr' ) );
			add_filter( 'eewpb_attr_infi-modal-trigger', array( $this, 'modal_trigger' ) );

			add_shortcode( 'iee_modal_dialog', array( $this, 'render' ) );
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

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'name'                     => '',
					'title'                    => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'size'                     => 'medium',
					'modal_width'              => '400',
					'header_background'        => '',
					'title_color'              => '',
					'body_background'          => '',
					'footer_background'        => '',
					'border_color'             => '',
					'show_footer'              => 'yes',
					'button_title'             => esc_attr__( 'Close', 'elegant-elements' ),
					'button_color'             => '#ffffff',
					'button_background_color'  => '#333333',
					'entry_animation'          => '',
					'exit_animation'           => '',
					'element_typography'       => 'default',
					'typography_title'         => '',
					'title_font_size'          => '24',
					'typography_content'       => '',
					'content_font_size'        => '18',
					'typography_footer_button' => '',
					'modal_trigger'            => 'none',
					'icon'                     => '',
					'icon_size'                => '32',
					'icon_color'               => '#333333',
					'button_shortcode'         => '',
					'image_url'                => '',
					'custom_text'              => '',
					'hide_on_mobile'           => elegant_elements_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/modal-dialog/elegant-modal-dialog.php' ) ) {
				include locate_template( 'templates/modal-dialog/elegant-modal-dialog.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/modal-dialog/elegant-modal-dialog.php';
			}

			$this->modal_counter++;

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
				'class'                => 'elegant-modal modal fade modal-' . $this->modal_counter,
				'tabindex'             => '-1',
				'role'                 => 'dialog',
				'aria-labelledby'      => 'modal-heading-' . $this->modal_counter,
				'aria-hidden'          => 'true',
				'data-animation-start' => ( isset( $this->args['entry_animation'] ) && '' !== $this->args['entry_animation'] ) ? 'infi-' . $this->args['entry_animation'] : 'infi-fadeIn',
				'data-animation-exit'  => ( isset( $this->args['exit_animation'] ) && '' !== $this->args['exit_animation'] ) ? 'infi-' . $this->args['exit_animation'] : 'infi-fadeOut',
			);

			if ( $this->args['name'] ) {
				$attr['class'] .= ' ' . $this->args['name'];
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
		 * Builds the dialog attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function dialog_attr() {

			$attr = array(
				'class' => 'elegant-modal-dialog modal-dialog',
			);

			$modal_size = '';
			if ( 'small' === $this->args['size'] ) {
				$modal_size = ' modal-sm';
			} elseif ( 'large' === $this->args['size'] ) {
				$modal_size = ' modal-lg';
			} else {
				$modal_size = ' modal-md';
			}

			$attr['class']    .= $modal_size;
			$attr['data-size'] = $modal_size;

			if ( isset( $this->args['modal_width'] ) && '' !== $this->args['modal_width'] && 'custom' === $this->args['size'] ) {
				$attr['style']  = 'width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['modal_width'], 'px' ) . ';';
				$attr['style'] .= 'max-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['modal_width'], 'px' ) . ';';
			}

			return $attr;

		}

		/**
		 * Builds the content attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function content_attr() {

			$attr = array(
				'class' => 'elegant-modal-content modal-content',
			);

			if ( isset( $this->args['body_background'] ) && '' !== $this->args['body_background'] ) {
				$attr['style'] = 'background-color:' . $this->args['body_background'];
			}

			return $attr;

		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function button_attr() {
			$attr = array(
				'class'        => 'close',
				'type'         => 'button',
				'data-dismiss' => 'modal',
				'aria-hidden'  => 'true',
				'style'        => '',
			);

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function heading_attr() {
			$attr = array(
				'class'        => 'elegant-modal-title modal-title',
				'id'           => 'modal-heading-' . $this->modal_counter,
				'data-dismiss' => 'modal',
				'aria-hidden'  => 'true',
				'style'        => '',
			);

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$attr['style'] .= 'color:' . $this->args['title_color'] . ';';
			}

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$title_typography = elegant_get_google_font_styling( $this->args, 'typography_title' );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function header_attr() {
			$attr = array(
				'class'       => 'elegant-modal-header modal-header',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['header_background'] ) && '' !== $this->args['header_background'] ) {
				$attr['style'] .= 'background-color:' . $this->args['header_background'];
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function footer_attr() {
			$attr = array(
				'class'       => 'elegant-modal-footer modal-footer',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['footer_background'] ) && '' !== $this->args['footer_background'] ) {
				$attr['style'] .= 'background-color:' . $this->args['footer_background'];
			}

			return $attr;
		}

		/**
		 * Builds the heading attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function body_attr() {
			$attr = array(
				'class'       => 'elegant-modal-body modal-body',
				'aria-hidden' => 'true',
				'style'       => '',
			);

			if ( isset( $this->args['typography_content'] ) && '' !== $this->args['typography_content'] ) {
				$content_typography = elegant_get_google_font_styling( $this->args, 'typography_content' );

				$attr['style'] .= $content_typography;
			}

			if ( isset( $this->args['content_font_size'] ) && '' !== $this->args['content_font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['content_font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function button_footer_attr() {
			$attr = array(
				'class'        => 'elegant-modal-dialog-button-wrapper',
				'data-dismiss' => 'modal',
				'style'        => '',
			);

			if ( isset( $this->args['typography_footer_button'] ) && '' !== $this->args['typography_footer_button'] ) {
				$button_typography = elegant_get_google_font_styling( $this->args, 'typography_footer_button' );

				$attr['style'] .= $button_typography;
			}

			return $attr;
		}

		/**
		 * Builds the button attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function modal_trigger() {
			$attr = array(
				'class'       => 'elegant-modal-trigger',
				'data-toggle' => 'modal',
				'data-target' => '.elegant-modal.' . $this->args['name'],
				'style'       => '',
			);

			if ( '' !== $this->args['icon_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['icon_size'], 'px' ) . ';';
			}

			if ( '' !== $this->args['icon_color'] ) {
				$attr['style'] .= 'color:' . $this->args['icon_color'];
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
			global $eewpb_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			wp_enqueue_script( 'bootstrap-modal' );

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-modal-dialog',
				$eewpb_js_folder_url . '/infi-elegant-modal-dialog.min.js',
				$elegant_js_folder_path . '/infi-elegant-modal-dialog.min.js',
				array( 'bootstrap-modal' ),
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
			wp_enqueue_style( 'infi-elegant-modal-dialog' );
		}
	}

	new EEWPB_Modal_Dialog();
} // End if().


/**
 * Map shortcode for modal_dialog.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_modal_dialog() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Modal Dialog', 'elegant-elements' ),
			'shortcode' => 'iee_modal_dialog',
			'icon'      => 'fas fa-window-restore',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Name Of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Needs to be a unique identifier (lowercase), used for button or modal_text_link element to open the modal. ex: mymodal.', 'elegant-elements' ),
					'param_name'  => 'name',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Modal Heading', 'elegant-elements' ),
					'description' => esc_attr__( 'Heading text for the modal.', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Size Of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the modal window size.', 'elegant-elements' ),
					'param_name'  => 'size',
					'value'       => array(
						'small'  => esc_attr__( 'Small', 'elegant-elements' ),
						'medium' => esc_attr__( 'Medium', 'elegant-elements' ),
						'large'  => esc_attr__( 'Large', 'elegant-elements' ),
						'custom' => esc_attr__( 'Custom Width', 'elegant-elements' ),
					),
					'std'         => 'medium',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Modal Custom Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css width to create empty space between two elements. In Pixel (px). eg. 400px.', 'elegant-elements' ),
					'param_name'  => 'modal_width',
					'value'       => '400',
					'min'         => '100',
					'max'         => '1200',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'size',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_attr__( 'Contents of Modal', 'elegant-elements' ),
					'description' => esc_attr__( 'Add your content to be displayed in modal.', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Header Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal header background color. ', 'elegant-elements' ),
					'param_name'  => 'header_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Header Title Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal header title text background color. ', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Body Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal body background color. ', 'elegant-elements' ),
					'param_name'  => 'body_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Footer Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal footer background color. ', 'elegant-elements' ),
					'param_name'  => 'footer_background',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the modal border color. ', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Show Footer', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose to show the modal footer with close button.', 'elegant-elements' ),
					'param_name'  => 'show_footer',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'std'         => 'yes',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Button Text', 'elegant-elements' ),
					'param_name'  => 'button_title',
					'value'       => esc_attr__( 'Close', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Set a title attribute for the button link.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'show_footer',
						'value'   => array( 'yes' ),
					),
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color of the footer close button.', 'elegant-elements' ),
					'param_name'  => 'button_background_color',
					'value'       => '#333333',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => '',
					'dependency'  => array(
						'element' => 'show_footer',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Accent Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the color of the button border, divider, text and icon.', 'elegant-elements' ),
					'param_name'  => 'button_color',
					'value'       => '#ffffff',
					'group'       => esc_attr__( 'Footer', 'elegant-elements' ),
					'default'     => '',
					'dependency'  => array(
						'element' => 'show_footer',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'        => 'ee_select_optgroup',
					'heading'     => esc_attr__( 'Entry Animation', 'elegant-elements' ),
					'param_name'  => 'entry_animation',
					'value'       => eewpb_get_entry_animations(),
					'description' => esc_attr__( 'Select the animation that is applied when modal is opened.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Animations', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_select_optgroup',
					'heading'     => esc_attr__( 'Exit Animation', 'elegant-elements' ),
					'param_name'  => 'exit_animation',
					'value'       => eewpb_get_exit_animations(),
					'description' => esc_attr__( 'Select the animation that is applied when modal is closing.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Animations', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Heading Title Typography', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'description' => esc_attr__( 'Select the typography for the heading title.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for modal title. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '24',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
					'param_name'  => 'typography_content',
					'value'       => '',
					'description' => esc_attr__( 'Select the typography for the modal content.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for modal content paragraphs. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'content_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Footer Button Typography', 'elegant-elements' ),
					'param_name'  => 'typography_footer_button',
					'value'       => '',
					'description' => esc_attr__( 'Select the typography for the modal footer button.', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Open modal with', 'elegant-elements' ),
					'param_name'  => 'modal_trigger',
					'std'         => 'none',
					'value'       => array(
						'none'   => esc_attr__( 'None', 'elegant-elements' ),
						'icon'   => esc_attr__( 'Icon', 'elegant-elements' ),
						'image'  => esc_attr__( 'Image', 'elegant-elements' ),
						'button' => esc_attr__( 'Button', 'elegant-elements' ),
						'text'   => esc_attr__( 'Custom Content', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select how you want the modal to open. Select none to use the modal name to open from your custom trigger.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_inner_element',
					'heading'     => esc_attr__( 'Button Shortcode', 'elegant-elements' ),
					'param_name'  => 'button_shortcode',
					'value'       => '',
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'button' ),
					),
					'description' => esc_attr__( 'Click the link to generate or edit button shortcode.', 'elegant-elements' ),
					'element_tag' => 'iee_fancy_button',
					'edit_title'  => 'Edit Button Settings',
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Choose Icon', 'elegant-elements' ),
					'param_name'  => 'icon',
					'value'       => '',
					'description' => esc_attr__( 'Select the icon to trigger the modal.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for triggering icon. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'icon_size',
					'value'       => '32',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Icon Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the icon color of the trigger icon.', 'elegant-elements' ),
					'param_name'  => 'icon_color',
					'value'       => '#333333',
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'icon' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
					'param_name'  => 'image_url',
					'value'       => '',
					'description' => esc_attr__( 'Select or upload image to open modal.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'image' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea_raw_html',
					'heading'     => esc_attr__( 'Custom Content', 'elegant-elements' ),
					'param_name'  => 'custom_text',
					'value'       => '',
					'description' => esc_attr__( 'Enter text or shortcode of a button element to open modal.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'modal_trigger',
						'value'   => array( 'text' ),
					),
					'group'       => esc_attr__( 'Trigger', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_modal_dialog', 99 );
