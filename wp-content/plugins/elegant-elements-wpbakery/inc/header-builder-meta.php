<?php
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_5f29c11530ebb',
			'title'                 => 'Elegant Template Display Options',
			'fields'                => array(
				array(
					'key'               => 'field_5f29c1269c546',
					'label'             => 'Display On',
					'name'              => 'display_template_on',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => 'field_5f29c1b59c547',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'row',
					'button_label'      => '+ Display Rule',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5f29c1b59c547',
							'label'             => 'Location Type',
							'name'              => 'template_location_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'global'   => 'Entire Website',
								'singular' => 'Singular',
								'archive'  => 'Archive',
								'404_page' => '404 Page',
								'search'   => 'Search Page',
								'blog'     => 'Blog / Posts Page',
								'front'    => 'Front / Home Page',
								'author'   => 'Author Archive',
							),
							'default_value'     => 'global',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5f29c9dbf79d5',
							'label'             => 'Singular Type',
							'name'              => 'singular_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29c1b59c547',
										'operator' => '==',
										'value'    => 'singular',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'all'    => 'All Singulars',
								'custom' => 'Custom Single Posts',
								'cpt'    => 'Single posts of a post type',
							),
							'default_value'     => 'all',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5f29c2cd9c548',
							'label'             => 'Posts',
							'name'              => 'posts',
							'type'              => 'relationship',
							'instructions'      => 'Select the posts you want this template to be displayed on.',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29c9dbf79d5',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'post_type'         => '',
							'taxonomy'          => '',
							'filters'           => array(
								0 => 'search',
								1 => 'post_type',
								2 => 'taxonomy',
							),
							'elements'          => '',
							'min'               => '',
							'max'               => '',
							'return_format'     => 'id',
						),
						array(
							'key'               => 'field_5f29cc55311f3',
							'label'             => 'Post Type',
							'name'              => 'post_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29c9dbf79d5',
										'operator' => '==',
										'value'    => 'cpt',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'post' => 'Posts',
								'page' => 'Pages',
								'docs' => 'Docs',
							),
							'default_value'     => false,
							'allow_null'        => 0,
							'multiple'          => 1,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
					),
				),
				array(
					'key'               => 'field_5f29d0584c289',
					'label'             => 'Hide On',
					'name'              => 'hide_template_on',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => 'field_5f29c1b59c547',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'row',
					'button_label'      => '+ Hide Rule',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5f29d0584c28a',
							'label'             => 'Location Type',
							'name'              => 'template_location_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'global'   => 'Entire Website',
								'singular' => 'Singular',
								'archive'  => 'Archive',
								'404_page' => '404 Page',
								'search'   => 'Search Page',
								'blog'     => 'Blog / Posts Page',
								'front'    => 'Front Page',
								'author'   => 'Author Archive',
							),
							'default_value'     => 'global',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5f29d0584c28b',
							'label'             => 'Singular Type',
							'name'              => 'singular_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29d0584c28a',
										'operator' => '==',
										'value'    => 'singular',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'all'    => 'All Singulars',
								'custom' => 'Custom Single Posts',
								'cpt'    => 'Single posts of a post type',
							),
							'default_value'     => 'all',
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5f29d0584c28c',
							'label'             => 'Posts',
							'name'              => 'posts',
							'type'              => 'relationship',
							'instructions'      => 'Select the posts you want this template to be hidden on.',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29d0584c28b',
										'operator' => '==',
										'value'    => 'custom',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'post_type'         => '',
							'taxonomy'          => '',
							'filters'           => array(
								0 => 'search',
								1 => 'post_type',
								2 => 'taxonomy',
							),
							'elements'          => '',
							'min'               => '',
							'max'               => '',
							'return_format'     => 'id',
						),
						array(
							'key'               => 'field_5f29d0584c28d',
							'label'             => 'Post Type',
							'name'              => 'post_type',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5f29d0584c28b',
										'operator' => '==',
										'value'    => 'cpt',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'post' => 'Posts',
								'page' => 'Pages',
								'docs' => 'Docs',
							),
							'default_value'     => false,
							'allow_null'        => 0,
							'multiple'          => 1,
							'ui'                => 1,
							'ajax'              => 0,
							'return_format'     => 'value',
							'placeholder'       => '',
						),
					),
				),
				// Add an option to set custom mobile header.
				array(
					'label'             => esc_attr__( 'Mobile Header', 'elegant-elements' ),
					'instructions'      => esc_attr__( 'Choose the header to be displayed on mobile if you want to display different header on mobile devices.', 'elegant-elements' ),
					'key'               => 'elegant_mobile_header',
					'name'              => 'elegant_mobile_header',
					'type'              => 'post_object',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'post_type'         => array(
						0 => 'eewpb_header',
					),
					'taxonomy'          => '',
					'allow_null'        => 1,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
				// Add an option to set page title bar under the header.
				array(
					'label'             => esc_attr__( 'Page Title Bar', 'elegant-elements' ),
					'instructions'      => esc_attr__( 'Choose the the page title bar for this header.', 'elegant-elements' ),
					'key'               => 'elegant_page_title_bar',
					'name'              => 'elegant_page_title_bar',
					'type'              => 'post_object',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'post_type'         => array(
						0 => 'eewpb_ptb',
					),
					'taxonomy'          => '',
					'allow_null'        => 1,
					'multiple'          => 0,
					'return_format'     => 'object',
					'ui'                => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'eewpb_header',
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'eewpb_footer',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

endif;