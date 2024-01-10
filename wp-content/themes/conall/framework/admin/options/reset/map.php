<?php

if ( ! function_exists('conall_edge_reset_options_map') ) {
	/**
	 * Reset options panel
	 */
	function conall_edge_reset_options_map() {

		conall_edge_add_admin_page(
			array(
				'slug'  => '_reset_page',
				'title' => esc_html__( 'Reset', 'conall' ),
				'icon'  => 'fa fa-retweet'
			)
		);

		$panel_reset = conall_edge_add_admin_panel(
			array(
				'page'  => '_reset_page',
				'name'  => 'panel_reset',
				'title' => esc_html__( 'Reset', 'conall' )
			)
		);

		conall_edge_add_admin_field(array(
			'type'	=> 'yesno',
			'name'	=> 'reset_to_defaults',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Reset to Defaults', 'conall' ),
			'description' => esc_html__( 'This option will reset all Select Options values to defaults', 'conall' ),
			'parent'		=> $panel_reset
		));

	}

	add_action( 'conall_edge_options_map', 'conall_edge_reset_options_map', 100 );

}