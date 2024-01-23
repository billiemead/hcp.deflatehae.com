<?php
if ( ! class_exists( 'EEWPB_Team_Member' ) && elegant_is_element_enabled( 'iee_team_member' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.5.0
	 */
	class EEWPB_Team_Member {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.5.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.5.0
		 * @access public
		 */
		public function __construct() {
			add_filter( 'eewpb_attr_elegant-team-member', array( $this, 'attr' ) );
			add_shortcode( 'iee_team_member', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.5.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$social_icons = rawurlencode(
				wp_json_encode(
					array(
						array(
							'title'      => esc_attr__( 'Facebook', 'elegant-elements' ),
							'icon'       => esc_attr__( 'fab fa-facebook-f', 'elegant-elements' ),
							'icon_color' => '',
							'link'       => '#',
						),
						array(
							'title'      => esc_attr__( 'Twitter', 'elegant-elements' ),
							'icon'       => esc_attr__( 'fab fa-twitter', 'elegant-elements' ),
							'icon_color' => '',
							'link'       => '#',
						),
						array(
							'title'      => esc_attr__( 'Instagram', 'elegant-elements' ),
							'icon'       => esc_attr__( 'fab fa-instagram', 'elegant-elements' ),
							'icon_color' => '',
							'link'       => '#',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'design_style'      => 'default',
					'profile_image'     => '',
					'name'              => '',
					'job_title'         => '',
					'social_icons'      => $social_icons,
					'name_color'        => '#333333',
					'job_text_color'    => '#666666',
					'social_icon_color' => '#666666',
					'border_color'      => '#eaeaea',
					'hide_on_mobile'    => elegant_elements_default_visibility( 'string' ),
					'class'             => '',
					'id'                => '',
				),
				$args
			);

			$this->args = $defaults;

			// Parse social icon params.
			$social_icons = vc_param_group_parse_atts( $this->args['social_icons'] );

			// Loop through the list items and generate a shortcode.
			foreach ( $social_icons as $icon ) {
				$content .= $this->render_icon( $icon );
			}

			$html = '';

			if ( '' !== locate_template( 'templates/team-member/elegant-team-member.php' ) ) {
				include locate_template( 'templates/team-member/elegant-team-member.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/team-member/elegant-team-member.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.5.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-team-member',
				'style' => '',
			);

			$attr['class'] .= ' elegant-team-style-' . $this->args['design_style'];

			$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );

			$attr['style'] .= '--name-color:' . $this->args['name_color'] . ';';
			$attr['style'] .= '--job-color:' . $this->args['job_text_color'] . ';';
			$attr['style'] .= '--border-color:' . $this->args['border_color'] . ';';

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
		 * @since 1.5.0
		 * @param array $icon Icon options.
		 * @return array
		 */
		public function render_icon( $icon ) {
			$social_icon = $icon['icon'];
			$hover_color = ( isset( $icon['icon_color'] ) ) ? $icon['icon_color'] : '';
			$color       = ( isset( $this->args['social_icon_color'] ) ) ? $this->args['social_icon_color'] : '';
			$link        = $icon['link'];

			$link = vc_build_link( $link );
			$url  = esc_url( $link['url'] );

			$icon_html = '<a class="elegant-team-member-icon" href="' . $url . '" style="color:' . $color . ';--hover-color:' . $hover_color . ';"><i class="' . $social_icon . '"></i></a>';

			return $icon_html;
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-team-member' );
		}
	}

	new EEWPB_Team_Member();
} // End if().

/**
 * Map shortcode for team_member.
 *
 * @since 1.5.0
 * @return void
 */
function map_elegant_elements_wpbakery_team_member() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Team Member', 'elegant-elements' ),
			'shortcode' => 'iee_team_member',
			'icon'      => 'fas fa-users team-member-icon',
			'params'    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Design Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the design style for the team member.', 'elegant-elements' ),
					'param_name'  => 'design_style',
					'default'     => 'default',
					'value'       => array(
						'default' => esc_attr__( 'Plugin Default ( Boxed )', 'elegant-elements' ),
						'classic' => esc_attr__( 'Classic', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Profile Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Upload the image to be used as the member profile.', 'elegant-elements' ),
					'param_name'  => 'profile_image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Member Name', 'elegant-elements' ),
					'param_name'  => 'name',
					'value'       => '',
					'placeholder' => true,
					'admin_label' => true,
					'description' => esc_attr__( 'Enter the text to be used as member name.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Job Title', 'elegant-elements' ),
					'param_name'  => 'job_title',
					'value'       => '',
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the text to be used as the job title. e.g Designer', 'elegant-elements' ),
				),
				array(
					'type'       => 'param_group',
					'param_name' => 'social_icons',
					'heading'    => esc_attr__( 'Social Media Icons', 'elegant-elements' ),
					'value'      => rawurlencode(
						wp_json_encode(
							array(
								array(
									'title'      => esc_attr__( 'Facebook', 'elegant-elements' ),
									'icon'       => esc_attr__( 'fab fa-facebook-f', 'elegant-elements' ),
									'icon_color' => '',
									'link'       => '#',
								),
								array(
									'title'      => esc_attr__( 'Twitter', 'elegant-elements' ),
									'icon'       => esc_attr__( 'fab fa-twitter', 'elegant-elements' ),
									'icon_color' => '',
									'link'       => '#',
								),
								array(
									'title'      => esc_attr__( 'Instagram', 'elegant-elements' ),
									'icon'       => esc_attr__( 'fab fa-instagram', 'elegant-elements' ),
									'icon_color' => '',
									'link'       => '#',
								),
							)
						)
					),
					'params'     => array(
						array(
							'type'        => 'textfield',
							'heading'     => esc_attr__( 'Social Media', 'elegant-elements' ),
							'param_name'  => 'title',
							'value'       => esc_attr__( 'Social Media', 'elegant-elements' ),
							'description' => esc_attr__( 'Enter title for this item. Only placeholder to be used in admin settings.', 'elegant-elements' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_attr__( 'Icon', 'elegant-elements' ),
							'param_name'  => 'icon',
							'value'       => '',
							'description' => esc_attr__( 'Select the icon.', 'elegant-elements' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_attr__( 'Icon Color on Hover', 'elegant-elements' ),
							'param_name'  => 'icon_color',
							'value'       => '',
							'description' => esc_attr__( 'Choose the icon color on hover.', 'elegant-elements' ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_attr__( 'Link URL', 'elegant-elements' ),
							'param_name'  => 'link',
							'value'       => '',
							'description' => esc_attr__( 'Enter the external url or select existing page to link to.', 'elegant-elements' ),
						),
					),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Name Color', 'elegant-elements' ),
					'param_name'  => 'name_color',
					'value'       => '',
					'default'     => '#333333',
					'description' => esc_attr__( 'Controls the text color for the profile name.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Job Title Color', 'elegant-elements' ),
					'param_name'  => 'job_text_color',
					'value'       => '',
					'default'     => '#666666',
					'description' => esc_attr__( 'Controls the text color for the job title text.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Social Icon Color', 'elegant-elements' ),
					'param_name'  => 'social_icon_color',
					'value'       => '',
					'std'         => '#666666',
					'description' => esc_attr__( 'Controls the text color for the social media icons. The hover color can be set in the individual icon settings.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Border Color', 'elegant-elements' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => '#eaeaea',
					'description' => esc_attr__( 'Controls the border color for the team member box where required as per the style selected.', 'elegant-elements' ),
					'group'       => 'Design',
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

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_team_member', 99 );
