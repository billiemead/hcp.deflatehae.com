<?php
if ( ! class_exists( 'EEWPB_Document_Viewer' ) && elegant_is_element_enabled( 'iee_document_viewer' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Document_Viewer {

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

			add_filter( 'eewpb_attr_elegant-document-viewer', array( $this, 'attr' ) );

			add_shortcode( 'iee_document_viewer', array( $this, 'render' ) );
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

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'file_type'       => 'pdf',
					'pdf_file_api'    => 'browser',
					'file_url'        => '',
					'document_height' => '400',
					'hide_on_mobile'  => elegant_elements_default_visibility( 'string' ),
					'class'           => '',
					'id'              => '',
				),
				$args
			);

			$this->args = $defaults;

			$html = '';

			if ( '' !== locate_template( 'templates/document-viewer/elegant-document-viewer.php' ) ) {
				include locate_template( 'templates/document-viewer/elegant-document-viewer.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/document-viewer/elegant-document-viewer.php';
			}

			return $html;
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
				'class' => 'elegant-document-viewer',
			);

			$attr['class'] .= ' document-type-' . $this->args['file_type'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$height        = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['document_height'], 'px' );
			$attr['style'] = 'height:' . $height . ';';

			if ( $this->args['class'] ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( $this->args['id'] ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}
	}

	new EEWPB_Document_Viewer();
} // End if().

/**
 * Map shortcode for document_viewer.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_document_viewer() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Document Viewer', 'elegant-elements' ),
			'shortcode' => 'iee_document_viewer',
			'icon'      => 'fa-file-alt fas document-viewer-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'File Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select which file you want to add for document viewer.', 'elegant-elements' ),
					'param_name'  => 'file_type',
					'default'     => 'pdf',
					'value'       => array(
						'pdf'  => esc_attr__( 'PDF', 'elegant-elements' ),
						'docx' => esc_attr__( 'Word Document ( DOC and .DOCX )', 'elegant-elements' ),
						'xlsx' => esc_attr__( 'Excel Spreadsheet ( .XLS and .XLSX )', 'elegant-elements' ),
						'ppt'  => esc_attr__( 'PowerPoint Presentation ( .PPT and .PPTX )', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'PDF Viewer API Source', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want to display the PDF document.', 'elegant-elements' ),
					'param_name'  => 'pdf_file_api',
					'default'     => 'browser',
					'value'       => array(
						'browser' => esc_attr__( 'Browser Default', 'elegant-elements' ),
						'google'  => esc_attr__( 'Google Docs Viewer API', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_file_upload',
					'heading'     => esc_attr__( 'File URL', 'elegant-elements' ),
					'param_name'  => 'file_url',
					'value'       => '',
					'description' => esc_attr__( 'Upload your file and provide url to the downloadable file here. You can also use external downloadable file url.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Document Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the css height to view the document. If the document overflows the height, the scroll will apprear inside the document. ( In Pixel ).', 'elegant-elements' ),
					'param_name'  => 'document_height',
					'value'       => '400',
					'min'         => '100',
					'max'         => '5000',
					'step'        => '1',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_document_viewer', 99 );
