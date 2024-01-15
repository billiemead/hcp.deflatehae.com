<?php

//WooCommerce
if(conall_edge_is_woocommerce_installed()){

    $woocommerce_meta_box = conall_edge_create_meta_box(
        array(
            'scope' => array('product'),
            'title' => esc_html__( 'Product Meta', 'conall' ),
            'name' => 'woo_product_meta'
        )
    );

        conall_edge_create_meta_box_field(array(
            'name'        => 'edgtf_product_featured_image_size',
            'type'        => 'select',
            'label' => esc_html__( 'Dimensions for Product List Shortcode', 'conall' ),
            'description' => esc_html__( 'Choose image layout when it appears in Edge Product List shortcode', 'conall' ),
            'parent'      => $woocommerce_meta_box,
            'options'     => array(
                'edgtf-woo-image-normal-width' => esc_html__( 'Default', 'conall' ),
                'edgtf-woo-image-large-width' => esc_html__( 'Large Width', 'conall' ),
            )
        ));
}