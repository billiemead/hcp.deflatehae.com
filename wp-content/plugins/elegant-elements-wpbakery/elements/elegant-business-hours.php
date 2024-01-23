<?php
if ( ! class_exists( 'EEWPB_Business_Hours' ) && elegant_is_element_enabled( 'iee_business_hours' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Business_Hours {

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
		 * Business hours counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $business_hours_counter = 1;

		/**
		 * Business hours child counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $business_hours_child_counter = 1;

		/**
		 * Business hours child count.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $business_hours_child_count = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			// Parent filter.
			add_filter( 'eewpb_attr_elegant-business-hours', array( $this, 'attr' ) );

			// Child item filters.
			add_filter( 'eewpb_attr_elegant-business-hours-item', array( $this, 'child_attr' ) );
			add_filter( 'eewpb_attr_elegant-business-hours-item-day', array( $this, 'child_attr_day' ) );
			add_filter( 'eewpb_attr_elegant-business-hours-item-hours', array( $this, 'child_attr_hours' ) );
			add_filter( 'eewpb_attr_elegant-business-hours-separator', array( $this, 'separator_attr' ) );

			add_shortcode( 'iee_business_hours', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_business_hours_item', array( $this, 'render_child' ) );
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

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$business_hour_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title'      => esc_attr__( 'Monday - Tuesday', 'elegant-elements' ),
							'hours_text' => esc_attr__( '9:00 - 17:00', 'elegant-elements' ),
						),
						array(
							'title'      => esc_attr__( 'Thursday - Friday', 'elegant-elements' ),
							'hours_text' => esc_attr__( '8:00 - 15:00', 'elegant-elements' ),
						),
						array(
							'title'      => esc_attr__( 'Saturday', 'elegant-elements' ),
							'hours_text' => esc_attr__( '8:00 - 12:00', 'elegant-elements' ),
						),
						array(
							'title'      => esc_attr__( 'Sunday', 'elegant-elements' ),
							'hours_text' => esc_attr__( 'Closed', 'elegant-elements' ),
							'text_color' => '#dd3333',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'day_alignment'       => 'left',
					'hours_alignment'     => 'right',
					'font_size'           => '18',
					'text_color'          => '',
					'separator_type'      => 'default',
					'sep_color'           => '#dddddd',
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
					'business_hour_items' => $business_hour_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$business_hour_items = vc_param_group_parse_atts( $this->args['business_hour_items'] );

			// Loop through the list items and generate a shortcode.
			foreach ( $business_hour_items as $item ) {
				$content .= '[iee_business_hours_item';
				foreach ( $item as $title => $value ) {
					$content .= ' ' . $title . '="' . $value . '"';
				}
				$content .= '/]';
			}

			$this->business_hours_child_count = count( explode( '[iee_business_hours_item', $content ) ) - 1;

			$html = '';

			if ( '' !== locate_template( 'templates/business-hours/elegant-business-hours-parent.php' ) ) {
				include locate_template( 'templates/business-hours/elegant-business-hours-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/business-hours/elegant-business-hours-parent.php';
			}

			$this->business_hours_counter++;

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
					'title'      => esc_attr__( 'Monday - Tuesday', 'elegant-elements' ),
					'hours_text' => esc_attr__( '9:00 - 17:00', 'elegant-elements' ),
					'text_color' => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/business-hours/elegant-business-hours-child.php' ) ) {
				include locate_template( 'templates/business-hours/elegant-business-hours-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/business-hours/elegant-business-hours-child.php';
			}

			$this->business_hours_child_counter++;

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
				'class' => 'elegant-business-hours',
				'style' => '',
			);

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( '' !== $this->args['text_color'] ) {
				$attr['style'] .= 'color: ' . $this->args['text_color'] . ';';
			}

			$attr['style'] .= 'font-size:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['font_size'], 'px' ) . ';';

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
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
		public function child_attr() {
			$attr = array(
				'class' => 'elegant-business-hours-item business-hours-item-' . $this->business_hours_child_counter,
				'style' => '',
			);

			if ( isset( $this->child_args['text_color'] ) && '' !== $this->child_args['text_color'] ) {
				$attr['style'] .= 'color: ' . $this->child_args['text_color'] . ';';
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
		public function child_attr_day() {
			$attr = array(
				'class' => 'elegant-business-hours-item-day',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['day_alignment'];

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function child_attr_hours() {
			$attr = array(
				'class' => 'elegant-business-hours-item-hours',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['hours_alignment'];

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function separator_attr() {
			$attr = array(
				'class' => 'elegant-business-hours-sep elegant-content-sep',
				'style' => '',
			);

			$styles = explode( ' ', $this->args['separator_type'] );

			foreach ( $styles as $style ) {
				$attr['class'] .= ' sep-' . $style;
			}

			if ( '' !== $this->args['sep_color'] ) {
				$attr['style'] .= 'border-color: ' . $this->args['sep_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-business-hours' );
		}
	}

	new EEWPB_Business_Hours();
} // End if().


/**
 * Map shortcode for business_hours.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_business_hours() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Business Hours', 'elegant-elements' ),
		'shortcode' => 'iee_business_hours',
		'icon'      => 'fa-business-time fas business-hours-icon',
		'params'    => array(
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Business Day Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment for the business days. This will align the only business days text.', 'elegant-elements' ),
				'param_name'  => 'day_alignment',
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
				'heading'     => esc_attr__( 'Business Hours Alignment', 'elegant-elements' ),
				'description' => esc_attr__( 'Set the text alignment for the business hours. This will align the only business hours text.', 'elegant-elements' ),
				'param_name'  => 'hours_alignment',
				'std'         => 'right',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'icons'       => elegant_get_alignment_icons(),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for the day and hours text. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the text color for the day and hours text. You can control the color for individual business hours item from the child settings.', 'elegant-elements' ),
				'param_name'  => 'text_color',
				'value'       => '',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_attr__( 'Separator', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the kind of the line separator you want to use.', 'elegant-elements' ),
				'param_name'  => 'separator_type',
				'value'       => array(
					'default'       => esc_attr__( 'Default', 'elegant-elements' ),
					'single solid'  => esc_attr__( 'Single Solid', 'elegant-elements' ),
					'single dashed' => esc_attr__( 'Single Dashed', 'elegant-elements' ),
					'single dotted' => esc_attr__( 'Single Dotted', 'elegant-elements' ),
					'double solid'  => esc_attr__( 'Double Solid', 'elegant-elements' ),
					'double dashed' => esc_attr__( 'Double Dashed', 'elegant-elements' ),
					'double dotted' => esc_attr__( 'Double Dotted', 'elegant-elements' ),
					'none'          => esc_attr__( 'None', 'elegant-elements' ),
				),
				'std'         => 'default',
				'group'       => esc_attr__( 'Design', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Separator Color', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the separator color.', 'elegant-elements' ),
				'param_name'  => 'sep_color',
				'value'       => '',
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
			array(
				'type'       => 'param_group',
				'param_name' => 'business_hour_items',
				'group'      => esc_attr__( 'Business Hour Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'title'      => esc_attr__( 'Monday - Tuesday', 'elegant-elements' ),
								'hours_text' => esc_attr__( '9:00 - 17:00', 'elegant-elements' ),
							),
							array(
								'title'      => esc_attr__( 'Thursday - Friday', 'elegant-elements' ),
								'hours_text' => esc_attr__( '8:00 - 15:00', 'elegant-elements' ),
							),
							array(
								'title'      => esc_attr__( 'Saturday', 'elegant-elements' ),
								'hours_text' => esc_attr__( '8:00 - 12:00', 'elegant-elements' ),
							),
							array(
								'title'      => esc_attr__( 'Sunday', 'elegant-elements' ),
								'hours_text' => esc_attr__( 'Closed', 'elegant-elements' ),
								'text_color' => '#dd3333',
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Day Text', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter text to be displayed as business day.', 'elegant-elements' ),
						'param_name'  => 'title',
						'admin_label' => true,
						'placeholder' => true,
						'value'       => esc_attr__( 'Monday - Tuesday', 'elegant-elements' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Hours Text', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter text to be displayed as business hours.', 'elegant-elements' ),
						'param_name'  => 'hours_text',
						'placeholder' => true,
						'value'       => esc_attr__( '9:00 - 17:00', 'elegant-elements' ),
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
						'description' => esc_attr__( 'Select the color for this business hours item text. Leave empty to inherit from parent settings.', 'elegant-elements' ),
						'param_name'  => 'text_color',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_business_hours', 99 );
