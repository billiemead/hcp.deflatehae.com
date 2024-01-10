<?php

function osteo_image_hotspot_setup() {
    add_image_size( 'Extra Small', 400, 400, true );
    add_image_size( 'Small', 600, 600, true );
    add_image_size( 'Medium', 800, 800, true );
    add_image_size( 'Large', 1024, 768, true );
    add_image_size( 'Extra Large', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'osteo_image_hotspot_setup' );