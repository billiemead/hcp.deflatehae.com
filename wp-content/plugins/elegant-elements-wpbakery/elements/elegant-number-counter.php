<?php
if ( ! class_exists( 'EEWPB_Number_Counter' ) && elegant_is_element_enabled( 'iee_number_counter' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.2
	 */
	class EEWPB_Number_Counter {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.2
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.2
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-number-counter', array( $this, 'attr' ) );

			add_shortcode( 'iee_number_counter', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.2
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			// Enqueue scripts.
			$this->add_scripts();

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'start_number'   => 100,
					'end_number'     => 1000,
					'prefix'         => ( isset( $args['prefix'] ) && '' !== $args['prefix'] ) ? $args['prefix'] : '',
					'suffix'         => ( isset( $args['suffix'] ) && '' !== $args['suffix'] ) ? $args['suffix'] : '',
					'decimal_places' => ( isset( $args['decimal_places'] ) && '' !== $args['decimal_places'] ) ? $args['decimal_places'] : 0,
					'decimal'        => ( isset( $args['decimal'] ) && '' !== $args['decimal'] ) ? $args['decimal'] : '',
					'duration'       => ( isset( $args['duration'] ) && '' !== $args['duration'] ) ? $args['duration'] : '2',
					'use_grouping'   => ( isset( $args['use_grouping'] ) && '' !== $args['use_grouping'] ) ? $args['use_grouping'] : true,
					'separator'      => ( isset( $args['separator'] ) && '' !== $args['separator'] ) ? $args['separator'] : '',
					'text_color'     => '',
					'font_size'      => 18,
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/number-counter/elegant-number-counter.php' ) ) {
				include locate_template( 'templates/number-counter/elegant-number-counter.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/number-counter/elegant-number-counter.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.2
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-number-counter',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['data-startVal']      = isset( $this->args['start_number'] ) ? $this->args['start_number'] : '';
			$attr['data-endVal']        = isset( $this->args['end_number'] ) ? $this->args['end_number'] : '';
			$attr['data-decimalPlaces'] = isset( $this->args['decimal_places'] ) ? $this->args['decimal_places'] : 0;
			$attr['data-duration']      = isset( $this->args['duration'] ) ? $this->args['duration'] : 2;
			$attr['data-useGrouping']   = isset( $this->args['use_grouping'] ) ? $this->args['use_grouping'] : false;
			$attr['data-separator']     = isset( $this->args['separator'] ) ? $this->args['separator'] : '';
			$attr['data-decimal']       = isset( $this->args['decimal'] ) ? $this->args['decimal'] : '';
			$attr['data-prefix']        = isset( $this->args['prefix'] ) ? $this->args['prefix'] : '';
			$attr['data-suffix']        = isset( $this->args['suffix'] ) ? $this->args['suffix'] : '';

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color:' . $this->args['text_color'] . ';';
			}

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			} else {
				$attr['id'] = 'eewpb-' . wp_rand();
			}

			return $attr;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.2
		 * @return void
		 */
		public function add_scripts() {
			global $eewpb_js_folder_url, $elegant_js_folder_path;

			Elegant_Elements_WPBakery::enqueue_script(
				'infi-elegant-countup',
				$eewpb_js_folder_url . '/countup.min.js',
				$elegant_js_folder_path . '/countup.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new EEWPB_Number_Counter();
} // End if().

/**
 * Map shortcode for number_counter.
 *
 * @since 1.2
 * @return void
 */
function map_elegant_elements_wpbakery_number_counter() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Number Counter', 'elegant-elements' ),
			'shortcode' => 'iee_number_counter',
			'icon'      => 'fas fa-sort-numeric-down number-counter-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Start Number', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the number to start the counter from.', 'elegant-elements' ),
					'param_name'  => 'start_number',
					'value'       => '100',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'End Number', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the number to stop the counter on.', 'elegant-elements' ),
					'param_name'  => 'end_number',
					'value'       => '1000',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Prefix', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter prefix string for this number counter.', 'elegant-elements' ),
					'param_name'  => 'prefix',
					'value'       => '',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Suffix', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter suffix string for this number counter.', 'elegant-elements' ),
					'param_name'  => 'suffix',
					'value'       => '',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Number of Decimal Places', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the number decimal places you want to count.', 'elegant-elements' ),
					'param_name'  => 'decimal_places',
					'value'       => '0',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Decimal Separator', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the decimal separator.', 'elegant-elements' ),
					'param_name'  => 'decimal',
					'value'       => '.',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Duration', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the duration in seconds you want to run the counter for.', 'elegant-elements' ),
					'param_name'  => 'duration',
					'value'       => '2',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Use Digit Grouping', 'elegant-elements' ),
					'param_name'  => 'use_grouping',
					'std'         => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select if you want to display digits with thousand grouping.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Digit Separator', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the digit separator.', 'elegant-elements' ),
					'param_name'  => 'separator',
					'value'       => ',',
					'dependency'  => array(
						'element' => 'use_grouping',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the text color for the number counter.', 'elegant-elements' ),
					'param_name'  => 'text_color',
					'value'       => '',
					'group'       => esc_attr__( 'Styles', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the font size for the number counter.', 'elegant-elements' ),
					'param_name'  => 'font_size',
					'value'       => '18',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Styles', 'elegant-elements' ),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_number_counter', 99 );
