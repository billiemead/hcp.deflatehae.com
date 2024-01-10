<?php

if ( ! function_exists('conall_edge_social_options_map') ) {

	function conall_edge_social_options_map() {

		conall_edge_add_admin_page(
			array(
				'slug'  => '_social_page',
				'title' => esc_html__( 'Social Networks', 'conall' ),
				'icon'  => 'fa fa-share-alt'
			)
		);

		/**
		 * Enable Social Share
		 */
		$panel_social_share = conall_edge_add_admin_panel(array(
			'page'  => '_social_page',
			'name'  => 'panel_social_share',
			'title' => esc_html__( 'Enable Social Share', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_social_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Social Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow social share on networks of your choice', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_panel_social_networks, #edgtf_panel_show_social_share_on'
			),
			'parent'		=> $panel_social_share
		));

		$panel_show_social_share_on = conall_edge_add_admin_panel(array(
			'page'  			=> '_social_page',
			'name'  			=> 'panel_show_social_share_on',
			'title' => esc_html__( 'Show Social Share On', 'conall' ),
			'hidden_property'	=> 'enable_social_share',
			'hidden_value'		=> 'no'
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_social_share_on_post',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Posts', 'conall' ),
			'description' => esc_html__( 'Show Social Share on Blog Posts', 'conall' ),
			'parent'		=> $panel_show_social_share_on
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_social_share_on_page',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Pages', 'conall' ),
			'description' => esc_html__( 'Show Social Share on Pages', 'conall' ),
			'parent'		=> $panel_show_social_share_on
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_social_share_on_attachment',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Media', 'conall' ),
			'description' => esc_html__( 'Show Social Share for Images and Videos', 'conall' ),
			'parent'		=> $panel_show_social_share_on
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_social_share_on_portfolio-item',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Portfolio Item', 'conall' ),
			'description' => esc_html__( 'Show Social Share for Portfolio Items', 'conall' ),
			'parent'		=> $panel_show_social_share_on
		));

		if(conall_edge_is_woocommerce_installed()){
			conall_edge_add_admin_field(array(
				'type'			=> 'yesno',
				'name'			=> 'enable_social_share_on_product',
				'default_value'	=> 'no',
				'label' => esc_html__( 'Product', 'conall' ),
				'description' => esc_html__( 'Show Social Share for Product Items', 'conall' ),
				'parent'		=> $panel_show_social_share_on
			));
		}

		/**
		 * Social Share Networks
		 */
		$panel_social_networks = conall_edge_add_admin_panel(array(
			'page'  			=> '_social_page',
			'name'				=> 'panel_social_networks',
			'title' => esc_html__( 'Social Networks', 'conall' ),
			'hidden_property'	=> 'enable_social_share',
			'hidden_value'		=> 'no'
		));

		/**
		 * Facebook
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'facebook_title',
			'title' => esc_html__( 'Share on Facebook', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_facebook_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via Facebook', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_facebook_share_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_facebook_share_container = conall_edge_add_admin_container(array(
			'name'		=> 'enable_facebook_share_container',
			'hidden_property'	=> 'enable_facebook_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'facebook_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_facebook_share_container
		));

		/**
		 * Twitter
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'twitter_title',
			'title' => esc_html__( 'Share on Twitter', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_twitter_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via Twitter', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_twitter_share_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_twitter_share_container = conall_edge_add_admin_container(array(
			'name'		=> 'enable_twitter_share_container',
			'hidden_property'	=> 'enable_twitter_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'twitter_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_twitter_share_container
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'text',
			'name'			=> 'twitter_via',
			'default_value'	=> '',
			'label' => esc_html__( 'Via', 'conall' ),
			'parent'		=> $enable_twitter_share_container
		));

		/**
		 * Linked In
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'linkedin_title',
			'title' => esc_html__( 'Share on LinkedIn', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_linkedin_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via LinkedIn', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_linkedin_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_linkedin_container = conall_edge_add_admin_container(array(
			'name'		=> 'enable_linkedin_container',
			'hidden_property'	=> 'enable_linkedin_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'linkedin_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_linkedin_container
		));

		/**
		 * Tumblr
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'tumblr_title',
			'title' => esc_html__( 'Share on Tumblr', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_tumblr_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via Tumblr', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_tumblr_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_tumblr_container = conall_edge_add_admin_container(array(
			'name'		=> 'enable_tumblr_container',
			'hidden_property'	=> 'enable_tumblr_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'tumblr_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_tumblr_container
		));

		/**
		 * Pinterest
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'pinterest_title',
			'title' => esc_html__( 'Share on Pinterest', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_pinterest_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via Pinterest', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_pinterest_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_pinterest_container = conall_edge_add_admin_container(array(
			'name'				=> 'enable_pinterest_container',
			'hidden_property'	=> 'enable_pinterest_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'pinterest_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_pinterest_container
		));

		/**
		 * VK
		 */
		conall_edge_add_admin_section_title(array(
			'parent'	=> $panel_social_networks,
			'name'		=> 'vk_title',
			'title' => esc_html__( 'Share on VK', 'conall' )
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'yesno',
			'name'			=> 'enable_vk_share',
			'default_value'	=> 'no',
			'label' => esc_html__( 'Enable Share', 'conall' ),
			'description' => esc_html__( 'Enabling this option will allow sharing via VK', 'conall' ),
			'args'			=> array(
				'dependence' => true,
				'dependence_hide_on_yes' => '',
				'dependence_show_on_yes' => '#edgtf_enable_vk_container'
			),
			'parent'		=> $panel_social_networks
		));

		$enable_vk_container = conall_edge_add_admin_container(array(
			'name'				=> 'enable_vk_container',
			'hidden_property'	=> 'enable_vk_share',
			'hidden_value'		=> 'no',
			'parent'			=> $panel_social_networks
		));

		conall_edge_add_admin_field(array(
			'type'			=> 'image',
			'name'			=> 'vk_icon',
			'default_value'	=> '',
			'label' => esc_html__( 'Upload Icon', 'conall' ),
			'parent'		=> $enable_vk_container
		));

		if(defined('EDGEF_TWITTER_FEED_VERSION')) {
            $twitter_panel = conall_edge_add_admin_panel(array(
                'title' => esc_html__( 'Twitter', 'conall' ),
                'name'  => 'panel_twitter',
                'page'  => '_social_page'
            ));

            conall_edge_add_admin_twitter_button(array(
                'name'   => 'twitter_button',
                'parent' => $twitter_panel
            ));
        }

        if(defined('EDGEF_INSTAGRAM_FEED_VERSION')) {
            $instagram_panel = conall_edge_add_admin_panel(array(
                'title' => esc_html__( 'Instagram', 'conall' ),
                'name'  => 'panel_instagram',
                'page'  => '_social_page'
            ));

            conall_edge_add_admin_instagram_button(array(
                'name'   => 'instagram_button',
                'parent' => $instagram_panel
            ));
        }
	}

	add_action( 'conall_edge_options_map', 'conall_edge_social_options_map', 18);
}