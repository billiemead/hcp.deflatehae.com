<?php
if ( ! class_exists( 'EEWPB_Rotating_Text' ) && elegant_is_element_enabled( 'iee_rotating_text' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Rotating_Text {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the child shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * Rotating Text counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $rotating_text_counter = 1;

		/**
		 * Rotating Text.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $rotating_text = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-rotating-text-container', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-rotating-text', array( $this, 'text_attr' ) );
			add_filter( 'eewpb_attr_elegant-rotating-text-wrap', array( $this, 'text_wrap_attr' ) );

			add_shortcode( 'iee_rotating_text', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_rotating_text_child', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {

			// Enqueue scripts.
			$this->add_scripts();

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$rotating_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title' => esc_attr__( 'Designers', 'elegant-elements' ),
						),
						array(
							'title' => esc_attr__( 'Developers', 'elegant-elements' ),
						),
						array(
							'title' => esc_attr__( 'Agencies', 'elegant-elements' ),
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'prefix'             => esc_attr__( 'Elegant Elements is for', 'elegant-elements' ),
					'element_typography' => 'default',
					'typography_parent'  => '',
					'typography_child'   => '',
					'font_size'          => '18',
					'delay'              => '2',
					'title_color'        => '',
					'center_align'       => 'no',
					'hide_on_mobile'     => elegant_elements_default_visibility( 'string' ),
					'class'              => '',
					'id'                 => '',
					'rotating_items'     => $rotating_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$rotating_items = vc_param_group_parse_atts( $this->args['rotating_items'] );

			// Loop through the list items and generate a shortcode.
			$content = '';
			foreach ( $rotating_items as $item ) {
				$content .= '[iee_rotating_text_child';
				foreach ( $item as $title => $value ) {
					$content .= ' ' . $title . '="' . $value . '"';
				}
				$content .= '/]';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/rotating-text/elegant-rotating-text-parent.php' ) ) {
				include locate_template( 'templates/rotating-text/elegant-rotating-text-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/rotating-text/elegant-rotating-text-parent.php';
			}

			$this->rotating_text_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'title'       => esc_attr__( 'Rotating Text Item', 'elegant-elements' ),
					'title_color' => '',
					'class'       => '',
					'id'          => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/rotating-text/elegant-rotating-text-child.php' ) ) {
				include locate_template( 'templates/rotating-text/elegant-rotating-text-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/rotating-text/elegant-rotating-text-child.php';
			}

			return $child_html;
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
				'class' => 'elegant-rotating-text-container',
			);

			$attr['class'] .= ' elegant-rotating-text-container-' . $this->rotating_text_counter;

			if ( isset( $this->args['center_align'] ) && 'no' !== $this->args['center_align'] ) {
				$attr['class'] .= ' elegant-align-center';
			}

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

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
		public function text_attr() {
			$attr = array(
				'class' => 'elegant-rotating-text',
				'style' => '',
			);

			$delay = ( isset( $this->args['delay'] ) ) ? $this->args['delay'] : 2;

			if ( isset( $this->args['typography_parent'] ) && '' !== $this->args['typography_parent'] ) {
				$parent_font_family = elegant_get_google_font_styling( $this->args, 'typography_parent' );
				$attr['style']     .= $parent_font_family;
			}

			$attr['style']     .= 'font-size:' . $this->args['font_size'] . 'px;';
			$attr['style']     .= 'color:' . $this->args['title_color'] . ';';
			$attr['data-delay'] = $delay * 1000;

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $rotating_text Rotating text child attr.
		 * @return array
		 */
		public function text_wrap_attr( $rotating_text ) {
			$attr = array(
				'class' => 'elegant-rotating-text-wrap',
				'style' => '',
			);

			if ( isset( $this->args['typography_child'] ) && '' !== $this->args['typography_child'] ) {
				$child_font_family = elegant_get_google_font_styling( $this->args, 'typography_child' );
				$attr['style']    .= $child_font_family;
			}

			if ( isset( $rotating_text['title_color'] ) && '' !== $rotating_text['title_color'] ) {
				$attr['style'] .= ' color:' . $rotating_text['title_color'] . ';';
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

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-rotating-text',
				$eewpb_js_folder_url . '/infi-elegant-rotating-text.min.js',
				$elegant_js_folder_path . '/infi-elegant-rotating-text.min.js',
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
			wp_enqueue_style( 'infi-elegant-rotating-text' );
		}
	}

	new EEWPB_Rotating_Text();
} // End if().


/**
 * Map shortcode for rotating_text.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_rotating_text() {

	$parent_args = array(
		'name'             => esc_attr__( 'Elegant Rotating Text', 'elegant-elements' ),
		'shortcode'        => 'iee_rotating_text',
		'icon'             => 'fas fa-text-height',
		'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/rotating-text.js',
		'params'           => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Prefix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed before the rotating text.', 'elegant-elements' ),
				'param_name'  => 'prefix',
				'placeholder' => true,
				'value'       => esc_attr__( 'Elegant Elements is for', 'elegant-elements' ),
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
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Prefix Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the text before the rotating text.', 'elegant-elements' ),
				'param_name'  => 'typography_parent',
				'value'       => '',
				'dependency'  => array(
					'element' => 'element_typography',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'google_fonts',
				'heading'     => esc_attr__( 'Rotating Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the rotating text.', 'elegant-elements' ),
				'param_name'  => 'typography_child',
				'value'       => '',
				'dependency'  => array(
					'element' => 'element_typography',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for text. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Rotating Delay', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the delay for changing the text rotation. ( In Seconds. )', 'elegant-elements' ),
				'param_name'  => 'delay',
				'value'       => '2',
				'min'         => '1',
				'max'         => '15',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for prefix text.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Center Aligned Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Select if you want to set the text in center.', 'elegant-elements' ),
				'param_name'  => 'center_align',
				'std'         => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
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
			array(
				'type'       => 'param_group',
				'param_name' => 'rotating_items',
				'group'      => esc_attr__( 'Rotating Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'title' => esc_attr__( 'Designers', 'elegant-elements' ),
							),
							array(
								'title' => esc_attr__( 'Developers', 'elegant-elements' ),
							),
							array(
								'title' => esc_attr__( 'Agencies', 'elegant-elements' ),
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter text to be displayed as rotating text.', 'elegant-elements' ),
						'param_name'  => 'title',
						'placeholder' => true,
						'admin_label' => true,
						'value'       => esc_attr__( 'Rotating Text', 'elegant-elements' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
						'description' => esc_attr__( 'Select the color for this rotating text.', 'elegant-elements' ),
						'param_name'  => 'title_color',
						'value'       => '',
					),
				),
			),
		),
	);

	elegant_elements_map(
		$parent_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_rotating_text', 99 );
