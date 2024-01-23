<?php
if ( ! class_exists( 'EEWPB_Content_Toggle' ) && elegant_is_element_enabled( 'iee_content_toggle' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Content_Toggle {

		/**
		 * Toggle counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $toggle_counter = 1;

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

			add_filter( 'eewpb_attr_elegant-content-toggle', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_content-toggle-first', array( $this, 'attr_first_toggle' ) );
			add_filter( 'eewpb_attr_content-toggle-last', array( $this, 'attr_last_toggle' ) );

			add_shortcode( 'iee_content_toggle', array( $this, 'render' ) );
			add_shortcode( 'elegant_libray_element', array( $this, 'render_library_element' ) );
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
					'title_first'        => esc_attr__( 'Annual', 'elegant-elements' ),
					'content_first'      => '',
					'title_last'         => esc_attr__( 'Lifetime', 'elegant-elements' ),
					'content_last'       => '',
					'switch_bg_inactive' => '#807e7e',
					'switch_bg_active'   => '#4CAF50',
					'element_typography' => '',
					'typography_title'   => '',
					'title_font_size'    => '18',
					'title_color'        => '#333333',
					'typography_heading' => '',
					'hide_on_mobile'     => elegant_elements_default_visibility( 'string' ),
					'class'              => '',
					'id'                 => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/content-toggle/elegant-content-toggle.php' ) ) {
				include locate_template( 'templates/content-toggle/elegant-content-toggle.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/content-toggle/elegant-content-toggle.php';
			}

			$this->toggle_counter++;

			return $html;
		}

		/**
		 * Render the library element content.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $args Shortcode paramters.
		 * @return string     HTML output.
		 */
		public function render_library_element( $args ) {
			$post_id               = $args['id'];
			$template_content_post = get_post( $post_id );
			$template_content      = $template_content_post->post_content;

			return do_shortcode( $template_content );
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
				'class' => 'elegant-content-toggle',
			);

			$attr['class'] .= ' elegant-content-toggle-' . $this->toggle_counter;
			$attr['class'] .= ' elegant-clearfix';

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
		public function attr_first_toggle() {
			$attr = array(
				'class' => 'content-toggle-first active-content',
			);

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr_last_toggle() {
			$attr = array(
				'class' => 'content-toggle-last',
			);

			return $attr;
		}

		/**
		 * Builds the styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function build_styles() {
			$main_class = '.elegant-content-toggle.elegant-content-toggle-' . $this->toggle_counter;

			$style = '<style type="text/css">';

			if ( isset( $this->args['switch_bg_inactive'] ) && '' !== $this->args['switch_bg_inactive'] ) {
				$style .= $main_class . ' .content-toggle-switch-label {';
				$style .= 'background:' . $this->args['switch_bg_inactive'] . ';';
				$style .= '}';
			}

			if ( isset( $this->args['switch_bg_active'] ) && '' !== $this->args['switch_bg_active'] ) {
				$style .= $main_class . ' .switch-active .content-toggle-switch-label {';
				$style .= 'background:' . $this->args['switch_bg_active'] . ';';
				$style .= '}';
			}

			$style .= $main_class . ' .content-toggle-switch-first,';
			$style .= $main_class . ' .content-toggle-switch-last {';

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$style .= elegant_get_google_font_styling( $this->args, 'typography_title' );
			}

			if ( isset( $this->args['title_color'] ) && '' !== $this->args['title_color'] ) {
				$style .= 'color:' . $this->args['title_color'] . ';';
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$style .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			$style .= '}';

			$style .= '</style>';

			return $style;
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

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-content-toggle',
				$eewpb_js_folder_url . '/infi-elegant-content-toggle.min.js',
				$elegant_js_folder_path . '/infi-elegant-content-toggle.min.js',
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
			wp_enqueue_style( 'infi-elegant-content-toggle' );
		}
	}

	new EEWPB_Content_Toggle();
} // End if().

/**
 * Map shortcode for content_toggle.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_content_toggle() {
	$templates = elegant_get_template_collection();

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Content Toggle', 'elegant-elements' ),
			'shortcode' => 'iee_content_toggle',
			'icon'      => 'fa-toggle-on fas content-toggle-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the title for the first toggle content.', 'elegant-elements' ),
					'param_name'  => 'title_first',
					'value'       => esc_attr__( 'Annual', 'elegant-elements' ),
					'group'       => esc_attr__( 'First Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea_raw_html',
					'heading'     => esc_attr__( 'Content Template', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content template from saved elements library to display in the first toggle.', 'elegant-elements' ),
					'param_name'  => 'content_first',
					'value'       => '',
					'group'       => esc_attr__( 'First Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the title for the second toggle content.', 'elegant-elements' ),
					'param_name'  => 'title_last',
					'value'       => esc_attr__( 'Lifetime', 'elegant-elements' ),
					'group'       => esc_attr__( 'Last Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'textarea_raw_html',
					'heading'     => esc_attr__( 'Content Template', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the content template from saved elements library to display in the second toggle.', 'elegant-elements' ),
					'param_name'  => 'content_last',
					'value'       => '',
					'group'       => esc_attr__( 'Last Toggle', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Inactive Switch Color', 'elegant-elements' ),
					'param_name'  => 'switch_bg_inactive',
					'value'       => '#807e7e',
					'description' => esc_attr__( 'Controls the background color of inactive switch button.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Switch Color', 'elegant-elements' ),
					'param_name'  => 'switch_bg_active',
					'value'       => '#4CAF50',
					'description' => esc_attr__( 'Controls the background color of inactive switch button.', 'elegant-elements' ),
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
					'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the toggle title text.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'group'       => 'Typography',
					'dependency'  => array(
						'element'            => 'element_typography',
						'value_not_equal_to' => 'default',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for toggle title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Toggle Title Color', 'elegant-elements' ),
					'param_name'  => 'title_color',
					'value'       => '#333333',
					'description' => esc_attr__( 'Controls the text color for toggle title.', 'elegant-elements' ),
					'group'       => 'Typography',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_content_toggle', 999 );
