<?php
if ( ! class_exists( 'EEWPB_FAQ_Rich_Snippets' ) && elegant_is_element_enabled( 'iee_faq_rich_snippets' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_FAQ_Rich_Snippets {

		/**
		 * Parent SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Parse FAQ rich snippets.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $seo_faq_data;

		/**
		 * Child SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $child_args;

		/**
		 * List box counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $faqs_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			add_filter( 'eewpb_attr_elegant-faq-rich-snippets', array( $this, 'attr' ) );

			add_shortcode( 'iee_faq_rich_snippets', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_faq_rich_snippet_item', array( $this, 'render_child' ) );
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

			$faq_items = rawurlencode(
				wp_json_encode(
					array(
						array(
							'question' => esc_attr__( 'What is WPBakery Page Builder', 'elegant-elements' ),
							'answer'   => esc_attr__( 'WPBakery Page Builder is a Frontend and Backend Page Builder for WordPress.', 'elegant-elements' ),
						),
						array(
							'question' => esc_attr__( 'What is Elegant Elements for WPBakery Page Builder', 'elegant-elements' ),
							'answer'   => esc_attr__( 'Elegant Elements is an add-on for the WPBakery Page Builder plugin.', 'elegant-elements' ),
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'output_type'    => 'descriptive',
					'title'          => esc_attr__( 'Frequently Asked Questions', 'elegant-elements' ),
					'hide_on_mobile' => elegant_elements_default_visibility( 'string' ),
					'class'          => '',
					'id'             => '',
					'faq_items'      => $faq_items,
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$faq_items = vc_param_group_parse_atts( $this->args['faq_items'] );

			// Loop through the list items and generate a shortcode.
			$content = '';
			foreach ( $faq_items as $item ) {
				$content .= '[iee_faq_rich_snippet_item';
				$content .= ' question="' . $item['question'] . '"]';
				$content .= $item['answer'];
				$content .= '[/iee_faq_rich_snippet_item]';
			}

			$this->seo_faq_data = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array(),
			);

			$html = '';

			if ( '' !== locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php' ) ) {
				include locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/faq-rich-snippets/elegant-faq-rich-snippets.php';
			}

			$this->faqs_counter++;

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
					'question' => esc_attr__( 'Your Question Goes Here', 'elegant-elements' ),
					'answer'   => esc_attr__( 'Your Answer Goes Here', 'elegant-elements' ),
				),
				$args
			);

			$this->child_args = $defaults;

			$child_html = '';

			if ( '' !== locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php' ) ) {
				include locate_template( 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/faq-rich-snippets/elegant-faq-rich-snippet-item.php';
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
				'class' => 'elegant-faq-rich-snippets',
			);

			$attr['class'] .= ' output-type-' . $this->args['output_type'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
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
			wp_enqueue_style( 'infi-elegant-faq-rich-snippets' );
		}
	}

	new EEWPB_FAQ_Rich_Snippets();
} // End if().

/**
 * Map shortcode for faq_rich_snippets.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_faq_rich_snippets() {

	$args = array(
		'name'      => esc_attr__( 'Elegant FAQ Rich Snippets', 'elegant-elements' ),
		'shortcode' => 'iee_faq_rich_snippets',
		'icon'      => 'fas fa-question-circle faq-rich-snippets-icon',
		'params'    => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_attr__( 'Output Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how you want the FAQs to be displayed on this page.', 'elegant-elements' ),
				'param_name'  => 'output_type',
				'std'         => 'descriptive',
				'value'       => array(
					'descriptive' => esc_attr__( 'Descriptive ( Boxed )', 'elegant-elements' ),
					'accordions'  => esc_attr__( 'Accordion (Toggle )', 'elegant-elements' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'FAQs Section Title', 'elegant-elements' ),
				'param_name'  => 'title',
				'value'       => esc_attr__( 'Frequently Asked Questions', 'elegant-elements' ),
				'placeholder' => true,
				'description' => esc_attr__( 'Enter section title to be displayed above the FAQs. Keep empty to remove.', 'elegant-elements' ),
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
				'param_name' => 'faq_items',
				'group'      => esc_attr__( 'FAQ Items', 'elegant-elements' ),
				'value'      => rawurlencode(
					wp_json_encode(
						array(
							array(
								'question' => esc_attr__( 'What is WPBakery Page Builder', 'elegant-elements' ),
								'answer'   => esc_attr__( 'WPBakery Page Builder is a Frontend and Backend Page Builder for WordPress.', 'elegant-elements' ),
							),
							array(
								'question' => esc_attr__( 'What is Elegant Elements for WPBakery Page Builder', 'elegant-elements' ),
								'answer'   => esc_attr__( 'Elegant Elements is an add-on for the WPBakery Page Builder plugin.', 'elegant-elements' ),
							),
						)
					)
				),
				'params'     => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_attr__( 'Question', 'elegant-elements' ),
						'param_name'  => 'question',
						'admin_label' => true,
						'value'       => esc_attr__( 'Your Question Goes Here', 'elegant-elements' ),
						'description' => esc_attr__( 'Enter the question for this FAQ item.', 'elegant-elements' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_attr__( 'Answer Text', 'elegant-elements' ),
						'description' => esc_attr__( 'Add the FAQ answer. Only text with links is acceptable. Avoid adding images and any other HTML.', 'elegant-elements' ),
						'param_name'  => 'answer',
						'value'       => esc_attr__( 'Your Answer Goes Here.', 'elegant-elements' ),
					),
				),
			),
		),
	);

	elegant_elements_map(
		$args
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_faq_rich_snippets', 99 );
