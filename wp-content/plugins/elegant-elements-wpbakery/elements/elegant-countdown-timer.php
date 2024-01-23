<?php
if ( ! class_exists( 'EEWPB_CountDown_Timer' ) && elegant_is_element_enabled( 'iee_countdown_timer' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.4.0
	 */
	class EEWPB_CountDown_Timer {

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
			add_filter( 'eewpb_attr_elegant-countdown-timer', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-countdown-ul', array( $this, 'ul_attr' ) );
			add_filter( 'eewpb_attr_elegant-countdown-timer-item', array( $this, 'item_attr' ) );
			add_filter( 'eewpb_attr_elegant-countdown-time-unit', array( $this, 'unit_attr' ) );
			add_filter( 'eewpb_attr_elegant-countdown-time-label', array( $this, 'label_attr' ) );

			add_shortcode( 'iee_countdown_timer', array( $this, 'render' ) );
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
					'count_date'               => '12/24/2016 12:00:00',
					'count_offset'             => '0',
					'count_style'              => 'default',
					'count_width'              => '100%',
					'alignment'                => 'left',
					'count_font_size'          => '60px',
					'count_font_color'         => '#555555',
					'count_details_font_size'  => '13px',
					'count_details_font_color' => '#888888',
					'border_color'             => '#cccccc',
					'border_width'             => '1px',
					'count_bg'                 => '',
					'count_margin'             => '10px',
					'show_days'                => 'yes',
					'show_hours'               => 'yes',
					'show_minutes'             => 'yes',
					'show_seconds'             => 'yes',
					'element_typography'       => 'default',
					'typography_count_unit'    => '',
					'typography_count_label'   => '',
					'hide_on_mobile'           => elegant_elements_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/countdown-timer/elegant-countdown-timer.php' ) ) {
				include locate_template( 'templates/countdown-timer/elegant-countdown-timer.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/countdown-timer/elegant-countdown-timer.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-countdown-timer',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

			$attr['data-offset'] = $this->args['count_offset'];
			$attr['data-date']   = $this->args['count_date'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= '--max-width:' . $this->args['count_width'] . ';';
			$attr['style'] .= '--margin:' . $this->args['count_margin'] . ';';

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
		 * @return array
		 */
		public function ul_attr() {
			$attr = array(
				'class' => 'elegant-countdown-ul',
				'style' => '',
			);

			if ( 'right' === $this->args['alignment'] ) {
				$attr['style'] .= 'margin:0 0 0 auto;';
			}

			if ( 'center' === $this->args['alignment'] ) {
				$attr['style'] .= 'margin:0 auto;';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @return array
		 */
		public function item_attr() {
			$attr = array(
				'class' => 'elegant-countdown-timer-item elegant-align-center',
				'style' => '',
			);

			$border_width   = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['border_width'], 'px' );
			$attr['style'] .= 'border-width:' . $border_width . ';';
			$attr['style'] .= 'border-color:' . $this->args['border_color'] . ';';

			if ( isset( $this->args['count_bg'] ) ) {
				$attr['style'] .= 'background-color:' . $this->args['count_bg'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @param array $args Attribute params.
		 * @return array
		 */
		public function unit_attr( $args ) {
			$attr = array(
				'class' => 'elegant-countdown-time-unit',
				'style' => '',
			);

			$attr['class'] .= ' ' . $args['unit'];

			if ( isset( $this->args['typography_count_unit'] ) && '' !== $this->args['typography_count_unit'] ) {
				$font_styling   = elegant_get_google_font_styling( $this->args, 'typography_count_unit' );
				$attr['style'] .= $font_styling;
			}

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['count_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';
			$attr['style'] .= 'color:' . $this->args['count_font_color'] . ';';

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.4.0
		 * @param array $args Attribute params.
		 * @return array
		 */
		public function label_attr( $args ) {
			$attr = array(
				'class' => 'elegant-countdown-time-label',
				'style' => '',
			);

			$attr['class'] .= ' ' . $args['unit'] . '-label';

			if ( isset( $this->args['typography_count_label'] ) && '' !== $this->args['typography_count_label'] ) {
				$font_styling   = elegant_get_google_font_styling( $this->args, 'typography_count_label' );
				$attr['style'] .= $font_styling;
			}

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['count_details_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';
			$attr['style'] .= 'color:' . $this->args['count_details_font_color'] . ';';

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
				'infi-countdown-timer',
				$eewpb_js_folder_url . '/infi-countdown-timer.min.js',
				$elegant_js_folder_path . '/infi-countdown-timer.min.js',
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
			wp_enqueue_style( 'infi-elegant-countdown-timer' );
		}
	}

	new EEWPB_CountDown_Timer();
} // End if().

/**
 * Map shortcode for countdown_timer.
 *
 * @since 1.4.0
 * @return void
 */
function map_elegant_elements_wpbakery_countdown_timer() {

	elegant_elements_map(
		array(
			'name'             => esc_attr__( 'Elegant Countdown Timer', 'elegant-elements' ),
			'shortcode'        => 'iee_countdown_timer',
			'icon'             => 'fas fa-sort-numeric-down-alt countdown-timer-icon',
			'front_enqueue_js' => EEWPB_PLUGIN_URL . 'elements/views/countdown-timer.js',
			'params'           => array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Upcoming Date', 'elegant-elements' ),
					'param_name'  => 'count_date',
					'value'       => '12/24/2021 12:00:00',
					'admin_label' => true,
					'description' => __( 'Enter the due date. eg : 12/24/2016 12:00:00 => month/day/year hour:minute:second', 'elegant-elements' ),
				),
				array(
					'heading'     => __( 'UTC Timezone', 'elegant-elements' ),
					'param_name'  => 'count_offset',
					'admin_label' => true,
					'value'       => array(
						'-12' => '-12',
						'-11' => '-11',
						'-10' => '-10',
						'-9'  => '-9',
						'-8'  => '-8',
						'-7'  => '-7',
						'-6'  => '-6',
						'-5'  => '-5',
						'-4'  => '-4',
						'-3'  => '-3',
						'-2'  => '-2',
						'-1'  => '-1',
						'0'   => '0',
						'+1'  => '+1',
						'+2'  => '+2',
						'+3'  => '+3',
						'+4'  => '+4',
						'+5'  => '+5',
						'+6'  => '+6',
						'+7'  => '+7',
						'+8'  => '+8',
						'+9'  => '+9',
						'+10' => '+10',
						'+12' => '+12',
					),
					'type'        => 'dropdown',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Style', 'elegant-elements' ),
					'param_name'  => 'count_style',
					'value'       => array(
						'default' => __( 'Default', 'elegant-elements' ),
						'custom'  => __( 'Custom', 'elegant-elements' ),
					),
					'description' => __( 'Customize the style of the counter element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Countdown Units Color', 'elegant-elements' ),
					'param_name'  => 'count_font_color',
					'value'       => '#555555',
					'description' => __( 'Select the color of the countdown units.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Countdown Details Color', 'elegant-elements' ),
					'param_name'  => 'count_details_font_color',
					'value'       => '#888888',
					'description' => __( 'Select the color for the days, hours, minutes and seconds text.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Border Width', 'elegant-elements' ),
					'param_name'  => 'border_width',
					'value'       => '1px',
					'description' => __( 'Enter the border width in pixels for the Countdown element. Default: 1px', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Border / Separator color', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '#cccccc',
					'description' => __( 'Select the border / separator color of the counter.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Background Color', 'elegant-elements' ),
					'param_name'  => 'count_bg',
					'value'       => '',
					'description' => __( 'Select the background color of the countdown element. Leave blank to have a transparent background', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Countdown Units Margin', 'elegant-elements' ),
					'param_name'  => 'count_margin',
					'value'       => '10px',
					'description' => __( 'Enter the margin of the countdown units in pixels or percents. Default: 10px', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Countdown Max Width', 'elegant-elements' ),
					'param_name'  => 'count_width',
					'value'       => '100%',
					'description' => __( 'Change here the maximum width for the Countdown element. You can enter values in % or px. E.g: 50% or 500px.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Description alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the description text to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Show Days', 'elegant-elements' ),
					'param_name'  => 'show_days',
					'value'       => array(
						'yes' => __( 'Yes', 'elegant-elements' ),
						'no'  => __( 'No', 'elegant-elements' ),
					),
					'description' => __( 'Show/hide the days units of the countdown element.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Show Hours', 'elegant-elements' ),
					'param_name'  => 'show_hours',
					'value'       => array(
						'yes' => __( 'Yes', 'elegant-elements' ),
						'no'  => __( 'No', 'elegant-elements' ),
					),
					'description' => __( 'Show/hide the hours units of the countdown element.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Show Minutes', 'elegant-elements' ),
					'param_name'  => 'show_minutes',
					'value'       => array(
						'yes' => __( 'Yes', 'elegant-elements' ),
						'no'  => __( 'No', 'elegant-elements' ),
					),
					'description' => __( 'Show/hide the minutes units of the countdown element.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => __( 'Show Seconds', 'elegant-elements' ),
					'param_name'  => 'show_seconds',
					'value'       => array(
						'yes' => __( 'Yes', 'elegant-elements' ),
						'no'  => __( 'No', 'elegant-elements' ),
					),
					'description' => __( 'Show/hide the seconds units of the countdown element.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'count_style',
						'value'   => 'custom',
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'std'         => 'custom',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Countdown Units Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the countdown unit text.', 'elegant-elements' ),
					'param_name'  => 'typography_count_unit',
					'value'       => '',
					'group'       => 'Typography',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Countdown Units Font Size', 'elegant-elements' ),
					'param_name'  => 'count_font_size',
					'value'       => '60px',
					'description' => __( 'Select the font size of the coundown units. Default: 60px', 'elegant-elements' ),
					'group'       => 'Typography',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'        => 'google_fonts',
					'heading'     => esc_attr__( 'Countdown Label Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the countdown label text.', 'elegant-elements' ),
					'param_name'  => 'typography_count_label',
					'value'       => '',
					'group'       => 'Typography',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Countdown Details Font Size', 'elegant-elements' ),
					'param_name'  => 'count_details_font_size',
					'value'       => '13px',
					'description' => __( 'Select the font size for the days, hours, minutes and seconds text. Default: 13px', 'elegant-elements' ),
					'group'       => 'Typography',
					'dependency'  => array(
						'element' => 'element_typography',
						'value'   => array( 'custom' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_countdown_timer', 99 );
