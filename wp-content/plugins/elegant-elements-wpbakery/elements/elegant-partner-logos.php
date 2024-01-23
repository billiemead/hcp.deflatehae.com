<?php
if ( ! class_exists( 'EEWPB_Partner_Logo' ) && elegant_is_element_enabled( 'iee_partner_logos' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Partner_Logo {

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
		 * Partner Logo counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $partner_logos_counter = 1;

		/**
		 * Partner Logo.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $partner_logos = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-partner-logos', array( $this, 'attr' ) );

			add_shortcode( 'iee_partner_logos', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_partner_logo', array( $this, 'render_child' ) );
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

			$partner_logo_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title' => esc_attr__( 'Company Logo 1', 'elegant-elements' ),
						),
						array(
							'title' => esc_attr__( 'Company Logo 2', 'elegant-elements' ),
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'border'             => '0',
					'border_color'       => '',
					'border_style'       => 'solid',
					'padding'            => '',
					'margin'             => '',
					'width'              => '',
					'height'             => '',
					'logo_alignment'     => 'left',
					'hide_on_mobile'     => elegant_elements_default_visibility( 'string' ),
					'class'              => '',
					'id'                 => '',
					'partner_logo_items' => $partner_logo_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$partner_logo_items = vc_param_group_parse_atts( $this->args['partner_logo_items'] );

			// Loop through the list items and generate a shortcode.
			foreach ( $partner_logo_items as $item ) {
				$content .= '[iee_partner_logo';
				foreach ( $item as $title => $value ) {
					$content .= ' ' . $title . '="' . $value . '"';
				}
				$content .= '/]';
			}

			$html = '';

			if ( '' !== locate_template( 'templates/partner-logos/elegant-partner-logos-parent.php' ) ) {
				include locate_template( 'templates/partner-logos/elegant-partner-logos-parent.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/partner-logos/elegant-partner-logos-parent.php';
			}

			$this->partner_logos_counter++;

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
					'image_url'    => '',
					'title'        => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					'click_action' => 'none',
					'modal_anchor' => '',
					'url'          => '',
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/partner-logos/elegant-partner-logos-child.php' ) ) {
				include locate_template( 'templates/partner-logos/elegant-partner-logos-child.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/partner-logos/elegant-partner-logos-child.php';
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
				'class' => 'elegant-partner-logos',
			);

			if ( isset( $this->args['logo_alignment'] ) && '' !== $this->args['logo_alignment'] ) {
				$attr['class'] .= ' elegant-partner-logo-align-' . $this->args['logo_alignment'];
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
			wp_enqueue_style( 'infi-elegant-partner-logo' );
		}
	}

	new EEWPB_Partner_Logo();
} // End if().


/**
 * Map shortcode for partner_logos.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_partner_logos() {

	$parent_args = array(
		'name'      => esc_attr__( 'Elegant Partner Logo', 'elegant-elements' ),
		'shortcode' => 'iee_partner_logos',
		'icon'      => 'fas fa-handshake',
		'params'    => array(
			array(
				'type'        => 'ee_range_slider',
				'heading'     => esc_attr__( 'Border', 'elegant-elements' ),
				'param_name'  => 'border',
				'default'     => '',
				'value'       => '0',
				'min'         => '0',
				'max'         => '10',
				'step'        => '1',
				'description' => esc_attr__( 'Select the border size.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
				'param_name'  => 'border_color',
				'value'       => '',
				'dependency'  => array(
					'element'            => 'border',
					'value_not_equal_to' => '0',
				),
				'description' => esc_attr__( 'Select border color for the partner logo.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Border Style', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the border style you want to use.', 'elegant-elements' ),
				'param_name'  => 'border_style',
				'std'         => 'solid',
				'value'       => array(
					'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
					'double' => esc_attr__( 'Double', 'elegant-elements' ),
					'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
					'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
				),
				'dependency'  => array(
					'element'            => 'border',
					'value_not_equal_to' => '0',
				),
			),
			array(
				'type'        => 'ee_dimensions',
				'heading'     => esc_attr__( 'Padding', 'elegant-elements' ),
				'param_name'  => 'padding',
				'value'       => '',
				'description' => esc_attr__( 'Enter padding to add space around logo image. In pixels (px) eg. 10px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_dimensions',
				'heading'     => esc_attr__( 'Margin', 'elegant-elements' ),
				'param_name'  => 'margin',
				'value'       => '',
				'description' => esc_attr__( 'Enter margin to add space around logo image wrapper. In pixesl (px) eg. 10px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Max Width', 'elegant-elements' ),
				'param_name'  => 'width',
				'value'       => '',
				'description' => esc_attr__( 'Enter width to be set as max width for image wrapper. In Pixels (px). eg. 120px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Image Max Height', 'elegant-elements' ),
				'param_name'  => 'height',
				'value'       => '',
				'description' => esc_attr__( 'Enter height to be set as max height for image wrapper. Leave empty to set image height auto in width proportion. In Pixels (px). eg. 120px.', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Align logos', 'elegant-elements' ),
				'param_name'  => 'logo_alignment',
				'std'         => 'left',
				'value'       => array(
					'left'   => esc_attr__( 'Left', 'elegant-elements' ),
					'center' => esc_attr__( 'Center', 'elegant-elements' ),
					'right'  => esc_attr__( 'Right', 'elegant-elements' ),
				),
				'icons'       => elegant_get_alignment_icons(),
				'description' => esc_attr__( 'Set logos alignment. Useful if there\'s empty space after logos due to logo width being less than its container width.', 'elegant-elements' ),
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
				'param_name' => 'partner_logo_items',
				'group'      => esc_attr__( 'Logo Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'title'        => esc_attr__( 'Company Logo 1', 'elegant-elements' ),
								'click_action' => 'none',
							),
							array(
								'title'        => esc_attr__( 'Company Logo 2', 'elegant-elements' ),
								'click_action' => 'none',
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'attach_image',
						'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
						'description' => esc_attr__( 'Upload an image to display in the frame.', 'elegant-elements' ),
						'param_name'  => 'image_url',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Logo Title', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter title to be used for this partner logo.', 'elegant-elements' ),
						'param_name'  => 'title',
						'placeholder' => true,
						'admin_label' => true,
						'value'       => esc_attr__( 'Your Content Goes Here', 'elegant-elements' ),
					),
					array(
						'type'        => 'ee_radio_button_set',
						'heading'     => esc_attr__( 'On Click Action', 'elegant-elements' ),
						'description' => esc_attr__( 'Choose what you want to do when user click on partner logo.', 'elegant-elements' ),
						'param_name'  => 'click_action',
						'std'         => 'none',
						'value'       => array(
							__( 'Open Modal', 'elegant-elements' ) => 'modal',
							__( 'Open URL', 'elegant-elements' )   => 'url',
							__( 'Do Nothing', 'elegant-elements' ) => 'none',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Modal Window Anchor', 'elegant-elements' ),
						'description' => esc_attr__( 'Add the class name of the modal window you want to open on partner logo click.', 'elegant-elements' ),
						'param_name'  => 'modal_anchor',
						'value'       => '',
						'dependency'  => array(
							'element' => 'click_action',
							'value'   => array( 'modal' ),
						),
					),
					array(
						'type'        => 'vc_link',
						'heading'     => esc_attr__( 'URL to Open', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter the url you want to open on partner logo click.', 'elegant-elements' ),
						'param_name'  => 'url',
						'value'       => '',
						'dependency'  => array(
							'element' => 'click_action',
							'value'   => array( 'url' ),
						),
					),
				),
			),
		),
	);

	elegant_elements_map(
		$parent_args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_partner_logos', 99 );
