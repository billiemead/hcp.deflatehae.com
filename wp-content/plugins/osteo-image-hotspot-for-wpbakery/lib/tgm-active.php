<?php

require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'osteo_image_hotspot_register_required_plugins' );

function osteo_image_hotspot_register_required_plugins() {

    $plugins = array(

        //Envato Market Plugin
        array(
            'name'               => 'Envato Market',
            'slug'               => 'envato-market',
            'source'             => dirname( __FILE__ ) . '/plugins/envato-market.zip',
            'required'           => true,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
            'is_callable'        => '',
        ),
    );

    $config = array(
        'id'           => 'osteo-image-hotspot',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'plugins.php',
        'capability'   => 'manage_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'notice_can_install_required' => _n_noop(
                'This plugin requires the following plugin: : %1$s.',
                'This plugin requires the following plugin: %1$s.',
                'osteo-image-hotspot'
            ),
        )
    );

    tgmpa( $plugins, $config );
}
