<?php
if ( ! class_exists( 'EEWPB_Footer_Links' ) && elegant_is_element_enabled( 'iee_footer_links' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class EEWPB_Footer_Links {

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
			add_filter( 'eewpb_attr_elegant-footer-links', array( $this, 'attr' ) );
			add_filter( 'eewpb_attr_elegant-footer-link-items', array( $this, 'link_attr' ) );
			add_filter( 'eewpb_attr_elegant-footer-links-item', array( $this, 'link_item_attr' ) );
			add_filter( 'eewpb_attr_elegant-footer-links-heading', array( $this, 'link_heading_attr' ) );

			add_shortcode( 'iee_footer_links', array( $this, 'render' ) );
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

			$default_links = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title' => esc_attr__( 'About Us', 'elegant-elements' ),
							'link'  => 'url:%23',
						),
						array(
							'title' => esc_attr__( 'Contact Us', 'elegant-elements' ),
							'link'  => 'url:%23',
						),
						array(
							'title' => esc_attr__( 'Products', 'elegant-elements' ),
							'link'  => 'url:%23',
						),
						array(
							'title' => esc_attr__( 'Services', 'elegant-elements' ),
							'link'  => 'url:%23',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'links_heading'       => '',
					'display_type'        => 'vertical',
					'link_item_icon'      => '',
					'space_between_links' => '',
					'heading_color'       => '',
					'heading_font_size'   => '',
					'link_font_size'      => '',
					'link_color'          => '',
					'link_color_hover'    => '',
					'alignment'           => 'left',
					'links'               => $default_links,
					'hide_on_mobile'      => elegant_elements_default_visibility( 'string' ),
					'class'               => '',
					'id'                  => '',
				),
				$args
			);

			$this->args = $defaults;

			// Parse link item params.
			$link_items = vc_param_group_parse_atts( $this->args['links'] );

			// Loop through the link items and generate a link list.
			$link_list = '';
			foreach ( $link_items as $item ) {
				$link   = vc_build_link( $item['link'] );
				$url    = esc_url( $link['url'] );
				$target = ( isset( $link['target'] ) && '' !== $link['target'] ) ? ' target="' . trim( $link['target'] ) . '"' : '';

				$icon = '';
				if ( '' !== $this->args['link_item_icon'] ) {
					$icon = '<i class="elegant-footer-link-icon ' . $this->args['link_item_icon'] . '"></i>';
				}

				$link_list .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-footer-links-item' ) . '>';
				$link_list .= '<a href="' . $url . '"' . $target . '>' . $icon . $item['title'] . '</a>';
				$link_list .= '</li>';
			}

			$this->args['link_list'] = $link_list;

			$html = '';

			if ( '' !== locate_template( 'templates/footer-links/elegant-footer-links.php' ) ) {
				include locate_template( 'templates/footer-links/elegant-footer-links.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/footer-links/elegant-footer-links.php';
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
				'class' => 'elegant-footer-links',
				'style' => '',
			);

			$attr['class'] .= ' display-' . $this->args['display_type'];
			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

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
		 * @since 1.1.0
		 * @return array
		 */
		public function link_attr() {
			$attr = array(
				'class' => 'elegant-footer-link-items',
				'style' => '',
			);

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['link_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';
			$attr['style'] .= 'color:' . $this->args['link_color'] . ';';

			if ( '' !== $this->args['link_color_hover'] ) {
				$attr['style'] .= '--hover-color:' . $this->args['link_color_hover'] . ';';
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
		public function link_item_attr() {
			$attr = array(
				'class' => 'elegant-footer-link-item',
				'style' => '',
			);

			$space = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['space_between_links'], 'px' );

			if ( 'vertical' === $this->args['display_type'] ) {
				$attr['style'] .= 'margin-bottom:' . $space . ';';
			} else {
				$attr['style'] .= 'margin-right:' . $space . ';';
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
		public function link_heading_attr() {
			$attr = array(
				'class' => 'elegant-footer-link-heading',
				'style' => '',
			);

			$font_size      = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' );
			$attr['style'] .= 'font-size:' . $font_size . ';';
			$attr['style'] .= 'color:' . $this->args['heading_color'] . ';';

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
			wp_enqueue_style( 'infi-elegant-footer-links' );
		}
	}

	new EEWPB_Footer_Links();
} // End if().

/**
 * Map shortcode for footer_links.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_wpbakery_footer_links() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Footer Links', 'elegant-elements' ),
			'shortcode' => 'iee_footer_links',
			'icon'      => 'fas fa-bars footer-links-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Links Heading.', 'elegant-elements' ),
					'description' => esc_attr__( 'Heading for this links widget.', 'elegant-elements' ),
					'param_name'  => 'links_heading',
					'value'       => '',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Link Display Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to display the links - vertically or horizontally.', 'elegant-elements' ),
					'param_name'  => 'display_type',
					'default'     => 'vertical',
					'value'       => array(
						'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
						'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'iconpicker',
					'heading'     => esc_attr__( 'Link Item Icon.', 'elegant-elements' ),
					'description' => esc_attr__( 'Select icon for the link items.', 'elegant-elements' ),
					'param_name'  => 'link_item_icon',
					'value'       => '',
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Space Between Links', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the space between to create empty space between two links. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'space_between_links',
					'value'       => '10',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'std'         => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'description' => esc_attr__( 'Align the links text to left, right or center.', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for links widget heading text.', 'elegant-elements' ),
					'param_name'  => 'heading_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for the heading text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '18',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Link Text Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for the link text. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'link_font_size',
					'value'       => '14',
					'min'         => '1',
					'max'         => '100',
					'step'        => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Link Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for link text.', 'elegant-elements' ),
					'param_name'  => 'link_color',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Link Text Color on Hover', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the color for link text for hover state.', 'elegant-elements' ),
					'param_name'  => 'link_color_hover',
					'value'       => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'       => 'param_group',
					'param_name' => 'links',
					'group'      => esc_attr__( 'Links', 'elegant-elements' ),
					'value'      => rawurlencode(
						wp_json_encode(
							array(
								array(
									'title' => esc_attr__( 'About Us', 'elegant-elements' ),
									'link'  => 'url:%23',
								),
								array(
									'title' => esc_attr__( 'Contact Us', 'elegant-elements' ),
									'link'  => 'url:%23',
								),
								array(
									'title' => esc_attr__( 'Products', 'elegant-elements' ),
									'link'  => 'url:%23',
								),
								array(
									'title' => esc_attr__( 'Services', 'elegant-elements' ),
									'link'  => 'url:%23',
								),
							)
						)
					),
					'params'     => array(
						array(
							'type'        => 'textfield',
							'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
							'description' => esc_attr__( 'Enter text to be displayed as link text.', 'elegant-elements' ),
							'param_name'  => 'title',
							'placeholder' => true,
							'admin_label' => true,
							'value'       => '',
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_attr__( 'Link', 'elegant-elements' ),
							'description' => esc_attr__( 'Choose the link.', 'elegant-elements' ),
							'param_name'  => 'link',
							'value'       => '',
						),
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_footer_links', 99 );
