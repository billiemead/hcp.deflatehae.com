<?php
if ( ! class_exists( 'EEWPB_Syntax_Highlighter' ) && elegant_is_element_enabled( 'iee_syntax_highlighter' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.4.0
	 */
	class EEWPB_Syntax_Highlighter {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.4.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.4.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-syntax-highlighter', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-syntax-highlighter-textarea', array( $this, 'textarea_attr' ) );
			add_filter( 'eewpb_attr_elegant-syntax-highlighter-copy-code-title', array( $this, 'copy_code_title_attr' ) );

			add_shortcode( 'iee_syntax_highlighter', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.4.0
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
					'theme'                        => 'default',
					'language'                     => '',
					'line_numbers'                 => 'yes',
					'line_wrapping'                => 'scroll',
					'copy_to_clipboard'            => 'yes',
					'copy_to_clipboard_text'       => '',
					'font_size'                    => 14,
					'border_size'                  => 1,
					'border_color'                 => '#dddddd',
					'border_style'                 => 'solid',
					'background_color'             => '',
					'line_number_background_color' => '',
					'line_number_text_color'       => '',
					'hide_on_mobile'               => elegant_elements_default_visibility( 'string' ),
					'class'                        => '',
					'id'                           => '',
				),
				$args
			);

			$this->args = $defaults;

			$content = rawurldecode( base64_decode( $content ) ); // @codingStandardsIgnoreLine

			$html = '';

			if ( '' !== locate_template( 'templates/syntax-highlighter/elegant-syntax-highlighter.php' ) ) {
				include locate_template( 'templates/syntax-highlighter/elegant-syntax-highlighter.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/syntax-highlighter/elegant-syntax-highlighter.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @param array $args Highlighter args for CodeMirror.
		 * @return array
		 */
		public function attr( $args ) {
			$attr = array(
				'class' => 'elegant-syntax-highlighter',
				'style' => '',
			);

			$attr['class'] .= ' elegant-syntax-highlighter-' . $args['rand'];

			$theme = ( 'default' === $this->args['theme'] || 'elegant' === $this->args['theme'] ) ? 'light' : 'dark';

			$attr['class'] .= ' elegant-syntax-highlighter-theme-' . $theme;

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( $this->args['font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			if ( '' !== $this->args['border_size'] ) {
				$attr['style'] .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_size'], 'px' ) . ';';

				if ( $this->args['border_style'] ) {
					$attr['style'] .= 'border-style:' . $this->args['border_style'] . ';';
				}

				if ( $this->args['border_color'] ) {
					$attr['style'] .= 'border-color:' . $this->args['border_color'] . ';';
				}
			}

			// Compatibility for WP < 4.9.
			if ( ! function_exists( 'wp_enqueue_code_editor' ) && $this->args['background_color'] ) {
				$attr['style'] .= 'background-color:' . $this->args['background_color'] . ';';
				$attr['style'] .= 'padding:0 1em;';
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
		 * @since 1.4.0
		 * @param array $args Highlighter args for CodeMirror.
		 * @return array
		 */
		public function textarea_attr( $args ) {
			$attr = array(
				'class' => 'elegant-syntax-highlighter-textarea',
				'id'    => 'elegant_syntax_highlighter_' . $args['rand'],
				'style' => '',
			);

			unset( $args['rand'] );

			foreach ( $args as $setting => $value ) {
				$attr[ 'data-' . $setting ] = $value;
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @param array $args Highlighter args for CodeMirror.
		 * @return array
		 */
		public function copy_code_title_attr( $args ) {
			$attr = array(
				'class'   => 'elegant-syntax-highlighter-copy-code-title',
				'data-id' => 'elegant_syntax_highlighter_' . $args['rand'],
				'style'   => '',
			);

			if ( $this->args['font_size'] ) {
				$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return void
		 */
		public function add_scripts() {
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-syntax-highlighter',
				$eewpb_js_folder_url . '/infi-elegant-syntax-highlighter.min.js',
				$elegant_js_folder_path . '/infi-elegant-syntax-highlighter.min.js',
				array( 'jquery' ),
				EEWPB_VERSION,
				true
			);
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-syntax-highlighter' );
		}
	}

	new EEWPB_Syntax_Highlighter();
} // End if().

/**
 * Map shortcode for syntax_highlighter.
 *
 * @since 1.4.0
 * @return void
 */
function map_elegant_elements_wpbakery_syntax_highlighter() {

	$code_mirror_themes = apply_filters(
		'eewpb_syntax_highlighter_themes',
		array(
			'default'      => esc_attr__( 'Light 1', 'elegant-elements' ),
			'elegant'      => esc_attr__( 'Light 2', 'elegant-elements' ),
			'hopscotch'    => esc_attr__( 'Dark 1', 'elegant-elements' ),
			'oceanic-next' => esc_attr__( 'Dark 2', 'elegant-elements' ),
		)
	);

	elegant_elements_map(
		array(
			'name'             => esc_attr__( 'Elegant Syntax Highlighter', 'elegant-elements' ),
			'shortcode'        => 'iee_syntax_highlighter',
			'icon'             => 'fas fa-highlighter syntax-highlighter-icon',
			'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/syntax-highlighter.js',
			'params'           => array(
				array(
					'type'        => 'textarea_raw_html',
					'heading'     => esc_attr__( 'Code to Highlight', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter some code to be displayed with highlighted syntax.', 'elegant-elements' ),
					'param_name'  => 'content',
					'value'       => '',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Highlighter Theme', 'elegant-elements' ),
					'description' => esc_attr__( 'Select which theme you want to use for code highlighting.', 'elegant-elements' ),
					'param_name'  => 'theme',
					'value'       => $code_mirror_themes,
					'default'     => 'default',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Code Language', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the language the code is in.', 'elegant-elements' ),
					'param_name'  => 'language',
					'value'       => array(
						'css'        => esc_attr__( 'css', 'elegant-elements' ),
						'html'       => esc_attr__( 'html', 'elegant-elements' ),
						'javascript' => esc_attr__( 'javascript', 'elegant-elements' ),
						'json'       => esc_attr__( 'json', 'elegant-elements' ),
						'jsx'        => esc_attr__( 'jsx', 'elegant-elements' ),
						'x-php'      => esc_attr__( 'php', 'elegant-elements' ),
						'x-less'     => esc_attr__( 'less', 'elegant-elements' ),
						'x-sass'     => esc_attr__( 'sass', 'elegant-elements' ),
						'x-scss'     => esc_attr__( 'scss', 'elegant-elements' ),
						'x-sh'       => esc_attr__( 'bash', 'elegant-elements' ),
						'sql'        => esc_attr__( 'sql', 'elegant-elements' ),
						'conf'       => esc_attr__( 'conf', 'elegant-elements' ),
						'svg'        => esc_attr__( 'svg', 'elegant-elements' ),
						'txt'        => esc_attr__( 'txt', 'elegant-elements' ),
						'xml'        => esc_attr__( 'xml', 'elegant-elements' ),
						'diff'       => esc_attr__( 'diff', 'elegant-elements' ),
						'htm'        => esc_attr__( 'htm', 'elegant-elements' ),
						'http'       => esc_attr__( 'http', 'elegant-elements' ),
						'md'         => esc_attr__( 'md', 'elegant-elements' ),
						'patch'      => esc_attr__( 'patch', 'elegant-elements' ),
						'phtml'      => esc_attr__( 'phtml', 'elegant-elements' ),
						'yaml'       => esc_attr__( 'yaml', 'elegant-elements' ),
						'yml'        => esc_attr__( 'yml', 'elegant-elements' ),
					),
					'admin_label' => true,
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Line Numbers', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to display or hide line numbers.', 'elegant-elements' ),
					'param_name'  => 'line_numbers',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Line Wrapping', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls whether the long line should break or add horizontal scroll.', 'elegant-elements' ),
					'param_name'  => 'line_wrapping',
					'value'       => array(
						'scroll' => esc_attr__( 'Scroll', 'elegant-elements' ),
						'break'  => esc_attr__( 'Break', 'elegant-elements' ),
					),
					'default'     => 'scroll',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Copy to Clipboard', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to allow your visitors to easily copy your code with a click of the button.', 'elegant-elements' ),
					'param_name'  => 'copy_to_clipboard',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Copy to Clipboard Text', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be displayed for user to click to copy.', 'elegant-elements' ),
					'param_name'  => 'copy_to_clipboard_text',
					'dependency'  => array(
						'element'            => 'copy_to_clipboard',
						'value_not_equal_to' => 'no',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the syntax highlight code copy label. In pixels.', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'default'     => 14,
					'value'       => '14',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the syntax highlighter. In pixels.', 'elegant-elements' ),
					'param_name'  => 'border_size',
					'default'     => '1',
					'value'       => '1',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color.', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => '#dddddd',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'border_size',
						'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'border_style',
					'default'     => 'solid',
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
							'element'            => 'border_size',
							'value_not_equal_to' => '0',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color for code highlight area.', 'elegant-elements' ),
					'param_name'  => 'background_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Line Number Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the line number background color for code highlight area.', 'elegant-elements' ),
					'param_name'  => 'line_number_background_color',
					'value'       => '',
					'dependency'  => array(
							'element'            => 'line_numbers',
							'value_not_equal_to' => 'no',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Line Number Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the line number text color for code highlight area.', 'elegant-elements' ),
					'param_name'  => 'line_number_text_color',
					'value'       => '',
					'dependency'  => array(
							'element'            => 'line_numbers',
							'value_not_equal_to' => 'no',
					),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_syntax_highlighter', 99 );
