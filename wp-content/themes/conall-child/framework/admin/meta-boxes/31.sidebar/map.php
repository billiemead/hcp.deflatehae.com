<?php

$custom_sidebars = conall_edge_get_custom_sidebars();

$sidebar_meta_box = conall_edge_create_meta_box(
    array(
        'scope' => array('page', 'portfolio-item', 'post'),
        'title' => esc_html__( 'Sidebar', 'conall' ),
        'name' => 'sidebar_meta'
    )
);

    conall_edge_create_meta_box_field(
        array(
            'name'        => 'edgtf_sidebar_meta',
            'type'        => 'select',
            'label' => esc_html__( 'Layout', 'conall' ),
            'description' => esc_html__( 'Choose the sidebar layout', 'conall' ),
            'parent'      => $sidebar_meta_box,
            'options'     => array(
						''			=> 'Default',
						'no-sidebar'		=> 'No Sidebar',
						'sidebar-33-right'	=> 'Sidebar 1/3 Right',
						'sidebar-25-right' 	=> 'Sidebar 1/4 Right',
						'sidebar-33-left' 	=> 'Sidebar 1/3 Left',
						'sidebar-25-left' 	=> 'Sidebar 1/4 Left',
					)
        )
    );

if(count($custom_sidebars) > 0) {
    conall_edge_create_meta_box_field(array(
        'name' => 'edgtf_custom_sidebar_meta',
        'type' => 'selectblank',
        'label' => esc_html__( 'Choose Widget Area in Sidebar', 'conall' ),
        'description' => esc_html__( 'Choose Custom Widget area to display in Sidebar', 'conall' ),
        'parent' => $sidebar_meta_box,
        'options' => $custom_sidebars
    ));
}
