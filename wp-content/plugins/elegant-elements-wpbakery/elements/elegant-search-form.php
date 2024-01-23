<?php
if ( ! class_exists( 'EEWPB_Search_Form' ) && elegant_is_element_enabled( 'iee_search_form' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class EEWPB_Search_Form {

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
			add_filter( 'eewpb_attr_elegant-search-form', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-search-form-field', array( $this, 'attr_field' ) );
			add_filter( 'eewpb_attr_elegant-search-form-button', array( $this, 'attr_button' ) );
			add_filter( 'eewpb_attr_elegant-search-form-button-input', array( $this, 'attr_button_input' ) );

			add_shortcode( 'iee_search_form', array( $this, 'render' ) );
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
					'placeholder_text'    => esc_attr__( 'What are you looking for?', 'elegant-elements' ),
					'search_layout'       => 'classic',
					'height'              => '50',
					'button_type'         => 'text',
					'button_text'         => esc_attr__( 'Search', 'elegant-elements' ),
					'input_text_color'    => '',
					'input_text_bg_color' => '#fafafa',
					'button_bg_color'     => '#0274be',
					'button_text_color'   => '#ffffff',
					'icon_size'           => '16',
					'button_width'        => '80',
					'border_width'        => '1px',
					'border_width_top'    => '1px',
					'border_width_left'   => '1px',
					'border_width_bottom' => '1px',
					'border_width_right'  => '1px',
					'border_radius'       => '3',
					'border_color'        => '#eaeaea',
					'alignment'           => 'left',
					'fs_overlay_color'    => 'rgba(0,0,0,0.6)',
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$this->args = $defaults;

			if ( 'minimal' === $this->args['search_layout'] ) {
				$this->args['button_width'] = '60';
				$this->args['button_type']  = 'icon';
			}

			if ( 'fullscreen' === $this->args['search_layout'] ) {
				$this->add_scripts();
			}

			$html = '';

			if ( '' !== locate_template( 'templates/search-form/elegant-search-form.php' ) ) {
				include locate_template( 'templates/search-form/elegant-search-form.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/search-form/elegant-search-form.php';
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
				'class' => 'elegant-search-form',
				'style' => '',
			);

			$attr['class'] .= ' elegant-search-form-' . $this->args['search_layout'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
			$attr['style'] = 'height:' . $height . ';';

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
		 * @return array
		 */
		public function attr_field() {
			$attr = array(
				'class' => 'elegant-search-form-field',
				'style' => '',
			);

			if ( 'fullscreen' !== $this->args['search_layout'] ) {
				$button_width   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['button_width'], 'px' );
				$attr['style'] .= 'width: calc( 100% - ' . $button_width . ' );';

				if ( '' !== $this->args['border_width_top'] ) {
					$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
					$border_width   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_width_top'], 'px' );
					$attr['style'] .= 'height: calc( ' . $height . ' - ( ' . $border_width . ' * 2 ) );';
				}

				if ( '' !== $this->args['input_text_bg_color'] ) {
					$attr['style'] .= 'background-color:' . $this->args['input_text_bg_color'] . ';';
				}

				if ( '' !== $this->args['border_color'] ) {
					$attr['style'] .= 'border-color:' . $this->args['border_color'] . ';';
				}

				if ( '' !== $this->args['border_width'] ) {
					$attr['style'] .= 'border-width:' . $this->args['border_width'] . ';';
				}

				if ( '' !== $this->args['border_radius'] ) {
					$attr['style'] .= 'border-radius:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius'], 'px' ) . ';';
				}
			}

			if ( '' !== $this->args['input_text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['input_text_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr_button() {
			$attr = array(
				'class' => 'elegant-search-form-button',
				'style' => '',
			);

			$button_width   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['button_width'], 'px' );
			$attr['style'] .= 'width:' . $button_width . ';';

			if ( '' !== $this->args['button_bg_color'] && 'minimal' !== $this->args['search_layout'] ) {
				$attr['style'] .= 'background-color:' . $this->args['button_bg_color'] . ';';
			} elseif ( '' !== $this->args['input_text_bg_color'] && 'minimal' === $this->args['search_layout'] ) {
				$attr['style'] .= 'background-color:' . $this->args['input_text_bg_color'] . ';';

				if ( '' !== $this->args['border_color'] ) {
					$attr['style'] .= 'border-color:' . $this->args['border_color'] . ';';
				}

				if ( '' !== $this->args['border_width'] ) {
					$attr['style'] .= 'border-width:' . $this->args['border_width'] . ';';
				}

				if ( '' !== $this->args['border_radius'] ) {
					$attr['style'] .= 'border-radius:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius'], 'px' ) . ';';
				}
			}

			if ( '' !== $this->args['button_text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['button_text_color'] . ';';
				$attr['style'] .= 'stroke:' . $this->args['button_text_color'] . ';';
			}

			if ( '' !== $this->args['border_radius'] ) {
				$attr['style'] .= 'border-radius:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_radius'], 'px' ) . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr_button_input() {
			$attr = array(
				'class' => 'elegant-search-submit searchsubmit button-type-' . $this->args['button_type'],
				'style' => '',
			);

			if ( '' !== $this->args['border_width_top'] ) {
				$height         = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
				$border_width   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_width_top'], 'px' );
				$attr['style'] .= 'height: calc( ' . $height . ' - ( ' . $border_width . ' * 2 ) );';
			} else {
				$height        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['height'], 'px' );
				$attr['style'] = 'height:' . $height . ';';
			}

			if ( 'fullscreen' !== $this->args['search_layout'] ) {
				$attr['type'] = 'submit';
			} else {
				$attr['onclick'] = 'elegantOpenSearch();';
			}

			$attr['aria-label'] = 'Search';

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
			wp_enqueue_style( 'infi-elegant-search-form' );
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function add_scripts() {
			?>
			<script type="text/javascript">
			function elegantOpenSearch() {
				document.getElementsByClassName( 'elegant-search-form-overlay' )[0].style.display = 'block';
				document.getElementsByClassName( 'elegant-search-input' )[0].focus();
			}

			function elegantCloseSearch() {
				document.getElementsByClassName( 'elegant-search-form-overlay' )[0].style.display = 'none';
			}
			</script>
			<?php
		}
	}

	new EEWPB_Search_Form();
} // End if().

/**
 * Map shortcode for search_form.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_wpbakery_search_form() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Search Form', 'elegant-elements' ),
			'shortcode' => 'iee_search_form',
			'icon'      => 'fas fa-search search-form-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Placeholder Text', 'elegant-elements' ),
					'param_name'  => 'placeholder_text',
					'admin_label' => true,
					'value'       => esc_attr__( 'What are you looking for?', 'elegant-elements' ),
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Search Layout', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the search form layout.', 'elegant-elements' ),
					'param_name'  => 'search_layout',
					'default'     => 'classic',
					'value'       => array(
						'classic'    => esc_attr__( 'Classic', 'elegant-elements' ),
						'minimal'    => esc_attr__( 'Minimal', 'elegant-elements' ),
						'fullscreen' => esc_attr__( 'Full Screen', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height for the search field. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'height',
					'value'       => '50',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'fullscreen',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Input Text Color', 'elegant-elements' ),
					'param_name'  => 'input_text_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text color for the search form field input text.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Input Text Background Color', 'elegant-elements' ),
					'param_name'  => 'input_text_bg_color',
					'value'       => '#fafafa',
					'description' => esc_attr__( 'Choose the background color for the search form field input.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'fullscreen',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Search Button Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the search button type.', 'elegant-elements' ),
					'param_name'  => 'button_type',
					'default'     => 'text',
					'value'       => array(
						'text' => esc_attr__( 'Text', 'elegant-elements' ),
						'icon' => esc_attr__( 'Icon', 'elegant-elements' ),
						'both' => esc_attr__( 'Text + Icon', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'minimal',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background Color', 'elegant-elements' ),
					'param_name'  => 'button_bg_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the background color for the search field button. Works in classic layout only.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'minimal',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Text / Icon Color', 'elegant-elements' ),
					'param_name'  => 'button_text_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the text and icon color for the search form field button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Button Text', 'elegant-elements' ),
					'param_name'  => 'button_text',
					'value'       => esc_attr__( 'Search', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text and icon color for the search form field button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'button_type',
						'value_not_equal_to' => 'icon',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Search Button Icon Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css font-size for the search button icon. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'icon_size',
					'value'       => '16',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Button Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the button width. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'button_width',
					'value'       => '80',
					'min'         => '10',
					'max'         => '150',
					'step'        => '1',
					'group'       => esc_attr__( 'Button', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'minimal',
					),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Border Width', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the border width. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'border_width',
					'value'       => array(
						'border_width_top'    => '',
						'border_width_left'   => '',
						'border_width_bottom' => '',
						'border_width_right'  => '',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'fullscreen',
					),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the border radius. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'border_radius',
					'value'       => '3',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'fullscreen',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'description' => esc_attr__( 'Choose the border color for the search form field.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
					'dependency'  => array(
						'element'            => 'search_layout',
						'value_not_equal_to' => 'fullscreen',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Full Screen Overlay Color', 'elegant-elements' ),
					'param_name'  => 'fs_overlay_color',
					'value'       => 'rgba(0,0,0,0.6)',
					'description' => esc_attr__( 'Control the overlay background color for the fullscreen search field.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Search Button Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'std'         => 'left',
					'description' => esc_attr__( 'Controls the search form field alignment.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'search_layout',
						'value'   => array( 'fullscreen' ),
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
			),
		)
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_search_form', 99 );
