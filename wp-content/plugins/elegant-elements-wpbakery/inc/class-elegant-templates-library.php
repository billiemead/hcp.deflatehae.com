<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elegant_Templates_Library {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function __construct() {
		// WPBakery Templates.
		add_filter( 'vc_get_all_templates', array( $this, 'eewpb_templates' ), 9 );
		add_filter( 'vc_templates_render_category', array( $this, 'render_templates' ) );
		add_filter( 'eewpb_templates_render_template', array( $this, 'render_elegant_templates' ), 10, 2 );
		add_filter( 'vc_templates_render_backend_template', array( $this, 'render_backend_templates' ), 10, 2 );
		add_filter( 'vc_templates_render_frontend_template', array( $this, 'render_frontend_templates' ), 10, 2 );
	}

	/**
	 * Render template fallback for non-default templates in backend editor.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param int    $template_id   Template ID.
	 * @param string $template_type Template category.
	 * @return string
	 */
	public function render_backend_templates( $template_id, $template_type ) {
		$templates = apply_filters( 'vc_get_all_templates', array() );
		$content   = '';

		foreach ( $templates as $template_category ) {
			if ( 'elegant_elements_templates' === $template_category['category'] ) {
				$elegant_templates = $template_category['templates'];
				foreach ( $elegant_templates as $template ) {
					if ( $template_id === $template['unique_id'] ) {
						$content = $template['content'];
						break;
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Render template fallback for non-default templates in frontend editor.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param int    $template_id   Template ID.
	 * @param string $template_type Template category.
	 * @return void
	 */
	public function render_frontend_templates( $template_id, $template_type ) {
		$templates = apply_filters( 'vc_get_all_templates', array() );
		$content   = '';

		foreach ( $templates as $template_category ) {
			if ( 'elegant_elements_templates' === $template_category['category'] ) {
				$elegant_templates = $template_category['templates'];
				foreach ( $elegant_templates as $template ) {
					if ( $template_id === $template['unique_id'] ) {
						$content = $template['content'];
						break;
					}
				}
			}
		}

		vc_frontend_editor()->setTemplateContent( trim( $content ) );
		vc_frontend_editor()->enqueueRequired();
		vc_include_template(
			'editors/frontend_template.tpl.php',
			array(
				'editor' => vc_frontend_editor(),
			)
		);
	}

	/**
	 * Render template category output.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param array $category Template category.
	 * @return array
	 */
	public function render_templates( $category ) {

		if ( 'elegant_elements_templates' === $category['category'] ) {
			$category['output'] = '<div class="vc_col-md-12">';
			if ( isset( $category['category_name'] ) ) {
				$category['output'] .= '<h3 style="margin-top: 0;">' . esc_html( $category['category_name'] ) . '</h3>';
			}
			if ( isset( $category['category_description'] ) ) {
				$category['output'] .= '<p class="vc_description">' . esc_html( $category['category_description'] ) . '</p>';
			}
			$category['output'] .= '</div>';
			$category['output'] .= '
			<div class="vc_column vc_col-sm-12">
				<div class="vc_ui-template-list vc_templates-list-elegant-templates" data-vc-action="collapseAll">';
			if ( ! empty( $category['templates'] ) ) {
				foreach ( $category['templates'] as $template ) {
					$category['output'] .= $this->render_template_list_item( $template );
				}
			}
			$category['output'] .= '
			</div>
		</div>';

		}

		return $category;
	}

	/**
	 * Add templates to the library list.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param array $data Template list array.
	 * @return array
	 */
	public function eewpb_templates( $data ) {
		$template_data = array();

		foreach ( glob( EEWPB_PLUGIN_DIR . 'inc/templates/*.php', GLOB_NOSORT ) as $filename ) {
			include $filename;
		}

		$elegant_templates = array(
			'category'             => 'elegant_elements_templates',
			'category_name'        => 'Elegant Templates',
			'category_description' => esc_attr__( 'Elegant Elements Templates for WPBakery', 'elegant-elements' ),
			'category_weight'      => 11,
			'templates'            => $template_data,
		);

		// Append elegant templates to WPBakery Template library.
		$data[] = $elegant_templates;

		return $data;
	}

	/**
	 * Render elegant templates category.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param string $template_name Template name.
	 * @param array  $template_data Template data.
	 * @return string
	 */
	public function render_elegant_templates( $template_name, $template_data ) {
		ob_start();
		$template_id            = esc_attr( $template_data['unique_id'] );
		$template_id_hash       = md5( $template_id ); // Needed for jquery target for TTA.
		$template_name          = esc_html( $template_name );
		$preview_template_title = esc_attr__( 'Preview template', 'elegant-elements' );
		$add_template_title     = esc_attr__( 'Add template', 'elegant-elements' );

		echo sprintf(
			'<button type="button" class="vc_ui-list-bar-item-trigger" title="%s"
			data-template-handler=""
			data-vc-ui-element="template-title"><span>%s</span></button>',
			esc_attr( $add_template_title ),
			esc_html( $template_name ),
			esc_attr( $add_template_title ),
			esc_attr( $preview_template_title ),
			esc_attr( $template_id_hash )
		);

		return ob_get_clean();
	}

	/**
	 * Render template fallback for non-default templates.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param array $template Single template.
	 * @return string
	 */
	public function render_template_list_item( $template ) {
		$name                = isset( $template['name'] ) ? esc_html( $template['name'] ) : esc_html__( 'No title', 'elegant-elements' );
		$template_id         = esc_attr( $template['unique_id'] );
		$template_id_hash    = md5( $template_id ); // Needed for jquery target for TTA.
		$template_name       = esc_html( $name );
		$template_name_lower = esc_attr( vc_slugify( $template_name ) );
		$template_type       = esc_attr( isset( $template['type'] ) ? $template['type'] : 'custom' );
		$template_cat        = esc_attr( isset( $template['category'] ) ? $template['category'] : 'all' );
		$custom_class        = esc_attr( isset( $template['custom_class'] ) ? $template['custom_class'] : '' );
		$preview_img         = esc_attr( isset( $template['image'] ) && '' !== $template['image'] ? $template['image'] : get_template_directory_uri() . '/nectar/nectar-vc-addons/img/templates/no-img.jpg' );
		$cat_display_name    = esc_attr( isset( $template['cat_display_name'] ) ? $template['cat_display_name'] : '' );

		$output = <<<HTML
					<div class="vc_ui-template vc_templates-template-type-$template_type elegant-template-$template_cat $custom_class"
						data-template_id="$template_id"
						data-template_id_hash="$template_id_hash"
						data-category="$template_type"
						data-template_unique_id="$template_id"
						data-template_name="$template_name_lower"
						data-template_type="$template_type"
						data-vc-content=".vc_ui-template-content">
						<div class="vc_ui-list-bar-item">
HTML;

		if ( ! empty( $preview_img ) && 'elegant_elements_templates' === $template_type ) {
			$output .= '<div class="template-preview-image" style="background-image: url( ' . $preview_img . ' );""></div>';
		}

		$output .= apply_filters( 'eewpb_templates_render_template', $name, $template );
		$output .= <<<HTML
						</div>
						<div class="vc_ui-template-content" data-js-content>
						</div>
					</div>
HTML;

		return $output;
	}

	/**
	 * Admin scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'elegant_admin_css', EEWPB_PLUGIN_URL . 'assets/admin/css/min/elegant-elements-admin.min.css', '', EEWPB_VERSION );
		wp_enqueue_script( 'elegant-admin-js', EEWPB_PLUGIN_URL . 'assets/admin/js/min/elegant-elements-admin.min.js', array( 'jquery' ), EEWPB_VERSION, true );
	}
}

new Elegant_Templates_Library();
