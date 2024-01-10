<?php
namespace EdgeCore\CPT\Carousels;

use EdgeCore\Lib;

/**
 * Class CarouselRegister
 * @package EdgeCore\CPT\Carousels
 */
class CarouselRegister implements Lib\PostTypeInterface {
    /**
     * @var string
     */
    private $base;
    /**
     * @var string
     */
    private $taxBase;

    public function __construct() {
        $this->base = 'carousels';
        $this->taxBase = 'carousels_category';
    }

    /**
     * @return string
     */
    public function getBase() {
        return $this->base;
    }

    /**
     * Registers custom post type with WordPress
     */
    public function register() {
        $this->registerPostType();
        $this->registerTax();
    }

    /**
     * Registers custom post type with WordPress
     */
    private function registerPostType() {
        global $conall_edge_framework;

        $menuPosition = 5;
        $menuIcon = 'dashicons-admin-post';
        if(edgt_core_theme_installed()) {
            $menuPosition = $conall_edge_framework->getSkin()->getMenuItemPosition('carousel');
            $menuIcon = $conall_edge_framework->getSkin()->getMenuIcon('carousel');
        }

        register_post_type($this->base,
            array(
                'labels'    => array(
                    'name'        => esc_html__('Edge Carousel','edgtf-core' ),
                    'menu_name' => esc_html__('Edge Carousel','edgtf-core' ),
                    'all_items' => esc_html__('Carousel Items','edgtf-core' ),
                    'add_new' =>  esc_html__('Add New Carousel Item','edgtf-core'),
                    'singular_name'   => esc_html__('Carousel Item','edgtf-core' ),
                    'add_item'      => esc_html__('New Carousel Item','edgtf-core'),
                    'add_new_item'    => esc_html__('Add New Carousel Item','edgtf-core'),
                    'edit_item'     => esc_html__('Edit Carousel Item','edgtf-core')
                ),
                'public'    =>  false,
                'show_in_menu'  =>  true,
                'rewrite'     =>  array('slug' => 'carousels'),
                'menu_position' =>  $menuPosition,
                'show_ui'   =>  true,
                'has_archive' =>  false,
                'hierarchical'  =>  false,
                'supports'    =>  array('title'),
                'menu_icon'  =>  $menuIcon
            )
        );
    }

    /**
     * Registers custom taxonomy with WordPress
     */
    private function registerTax() {
        $labels = array(
            'name' => esc_html__( 'Carousels', 'taxonomy general name' ),
            'singular_name' => esc_html__( 'Carousel', 'taxonomy singular name' ),
            'search_items' =>  esc_html__( 'Search Carousels','edgtf-core' ),
            'all_items' => esc_html__( 'All Carousels','edgtf-core' ),
            'parent_item' => esc_html__( 'Parent Carousel','edgtf-core' ),
            'parent_item_colon' => esc_html__( 'Parent Carousel:','edgtf-core' ),
            'edit_item' => esc_html__( 'Edit Carousel','edgtf-core' ),
            'update_item' => esc_html__( 'Update Carousel','edgtf-core' ),
            'add_new_item' => esc_html__( 'Add New Carousel','edgtf-core' ),
            'new_item_name' => esc_html__( 'New Carousel Name','edgtf-core' ),
            'menu_name' => esc_html__( 'Carousels','edgtf-core' ),
        );

        register_taxonomy($this->taxBase, array($this->base), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'show_admin_column' => true,
            'rewrite' => array( 'slug' => 'carousels-category' ),
        ));
    }

}