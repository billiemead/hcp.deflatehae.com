<?php

namespace Osteo_Image_Hotspot;
defined( 'ABSPATH' ) || die();

add_filter ( 'plugin_action_links_'.OSTEO_IMAGE_HOTSPOT_PLUGIN_BASE, function( $links ) {
    $link = sprintf( "<a href='%s' style='color: #E91E63; font-weight: 700'>%s</a>", esc_url( 'https://docs.twinkletheme.com/docs/osteo-image-hotspot-for-wpbakery/installation/'), esc_html__('Documentation', 'osteo-image-hotspot'));
    array_push( $links, $link );
    
    return $links;
});