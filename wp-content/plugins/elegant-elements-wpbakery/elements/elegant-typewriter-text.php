<?php
if ( ! class_exists( 'EEWPB_Typewriter_Text' ) && elegant_is_element_enabled( 'iee_typewriter_text' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Typewriter_Text {

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
		 * Typewriter Text counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $typewriter_text_counter = 1;

		/**
		 * Typewriter Text.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $typewriter_text = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'iee_typewriter_text', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_typewriter_text_child', array( $this, 'render_child' ) );
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

			$typewriter_text_items = rawurlencode(
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
					'prefix'                => '',
					'suffix'                => '',
					'loop'                  => 'no',
					'alignment'             => 'left',
					'element_typography'    => 'default',
					'typography_parent'     => '',
					'typography_child'      => '',
					'font_size'             => '28',
					'title_color'           => '',
					'delete_delay'          => '1000',
					'hide_on_mobile'        => elegant_elements_default_visibility( 'string' ),
					'class'                 => '',
					'id'                    => '',
					'typewriter_text_items' => $typewriter_text_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$typewriter_text_items = vc_param_group_parse_atts( $this->args['typewriter_text_items'] );

			// Loop through the list items and generate a shortcode.
			$content = '';
			foreach ( $typewriter_text_items as $item ) {
				$content .= '[iee_typewriter_text_child';
				foreach ( $item as $title => $value ) {
					$content .= ' ' . $title . '="' . $value . '"';
				}
				$content .= '/]';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/typewriter-text/elegant-typewriter-text-parent.php' ) ) {
				include locate_template( 'templates/typewriter-text/elegant-typewriter-text-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/typewriter-text/elegant-typewriter-text-parent.php';
			}

			$this->typewriter_text_counter++;

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
					'title'       => esc_attr__( 'Type this text', 'elegant-elements' ),
					'title_color' => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/typewriter-text/elegant-typewriter-text-child.php' ) ) {
				include locate_template( 'templates/typewriter-text/elegant-typewriter-text-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/typewriter-text/elegant-typewriter-text-child.php';
			}

			return $child_html;
		}

		/**
		 * Returns equivalent global information for FB param.
		 *
		 * @since 1.0
		 * @return array|bool Element option data.
		 */
		public function iee_typewriter_text_map_descriptions() {
			$shortcode_option_map = array();

			$shortcode_option_map['background_color']['iee_typewriter_text'] = array(
				'theme-option' => 'iee_typewriter_text_background_color',
				'reset'        => true,
			);

			return $shortcode_option_map;
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
				'infi-elegant-typewriter-text',
				$eewpb_js_folder_url . '/infi-elegant-typewriter-text.min.js',
				$elegant_js_folder_path . '/infi-elegant-typewriter-text.min.js',
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
			wp_enqueue_style( 'infi-elegant-typewriter-text' );
		}
	}

	new EEWPB_Typewriter_Text();
} // End if().


/**
 * Map shortcode for typewriter_text.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_typewriter_text() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Typewriter Text', 'elegant-elements' ),
		'shortcode' => 'iee_typewriter_text',
		'icon'      => 'fas fa-i-cursor typewriter-icon',
		'params'    => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Prefix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed before the typewriter text.', 'elegant-elements' ),
				'param_name'  => 'prefix',
				'placeholder' => true,
				'value'       => esc_attr__( 'Elegant Elements is for', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Suffix Text', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter text to be displayed after the typewriter text.', 'elegant-elements' ),
				'param_name'  => 'suffix',
				'placeholder' => true,
				'value'       => esc_attr__( 'all around the world', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Infinite Loop', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose if you want to loop the text when it reaches to the end.', 'elegant-elements' ),
				'param_name'  => 'loop',
				'std'         => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Text Delete Delay ( In ms )', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the text delete delay. The cursor will wait till this delay reach to start deleting the text. ( In Milliseconds. )', 'elegant-elements' ),
				'param_name'  => 'delete_delay',
				'value'       => '1000',
				'min'         => '500',
				'max'         => '5000',
				'step'        => '100',
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment. This will align the entier text with prefix and suffix to the set alignment.', 'elegant-elements' ),
				'param_name'  => 'alignment',
				'std'         => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'icons'       => elegant_get_alignment_icons(),
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
				'heading'     => esc_attr__( 'Prefix and Suffix Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the text before and after the typewriter text.', 'elegant-elements' ),
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
				'heading'     => esc_attr__( 'Typewriter Text Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the typewriter text.', 'elegant-elements' ),
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
				'value'       => '28',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
				'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the color for prefix text.', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
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
			array(
				'type'       => 'param_group',
				'param_name' => 'typewriter_text_items',
				'group'      => esc_attr__( 'Text Items', 'elegant-elements' ),
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
						'heading'     => esc_attr__( 'Text', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter text to be displayed as typewriter text.', 'elegant-elements' ),
						'param_name'  => 'title',
						'placeholder' => true,
						'admin_label' => true,
						'value'       => esc_attr__( 'Type this text', 'elegant-elements' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
						'description' => esc_attr__( 'Select the color for this typewriter text.', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_typewriter_text', 99 );
