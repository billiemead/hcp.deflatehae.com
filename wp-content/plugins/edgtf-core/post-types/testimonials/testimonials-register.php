<?php
namespace EdgeCore\CPT\Testimonials;

use EdgeCore\Lib;


/**
 * Class TestimonialsRegister
 * @package EdgeCore\CPT\Testimonials
 */
class TestimonialsRegister implements Lib\PostTypeInterface {
    /**
     * @var string
     */
    private $base;
    private $taxBase;

    public function __construct() {
        $this->base = 'testimonials';
        $this->taxBase = 'testimonials_category';
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
     * Regsiters custom post type with WordPress
     */
    private function registerPostType() {
        global $conall_edge_framework;

        $menuPosition = 5;
        $menuIcon = 'dashicons-admin-post';

        if(edgt_core_theme_installed()) {
            $menuPosition = $conall_edge_framework->getSkin()->getMenuItemPosition('testimonial');
            $menuIcon = $conall_edge_framework->getSkin()->getMenuIcon('testimonial');
        }

        register_post_type('testimonials',
            array(
                'labels' 		=> array(
                    'name' 				=> esc_html__('Testimonials','edgtf-core' ),
                    'singular_name' 	=> esc_html__('Testimonial','edgtf-core' ),
                    'add_item'			=> esc_html__('New Testimonial','edgtf-core'),
                    'add_new_item' 		=> esc_html__('Add New Testimonial','edgtf-core'),
                    'edit_item' 		=> esc_html__('Edit Testimonial','edgtf-core')
                ),
                'public'		=>	false,
                'show_in_menu'	=>	true,
                'rewrite' 		=> 	array('slug' => 'testimonials'),
                'menu_position' => 	$menuPosition,
                'show_ui'		=>	true,
                'has_archive'	=>	false,
                'hierarchical'	=>	false,
                'supports'		=>	array('title', 'thumbnail'),
                'menu_icon'  =>  $menuIcon
            )
        );
    }

    /**
     * Registers custom taxonomy with WordPress
     */
    private function registerTax() {
        $labels = array(
            'name' => esc_html__( 'Testimonials Categories', 'taxonomy general name' ),
            'singular_name' => esc_html__( 'Testimonial Category', 'taxonomy singular name' ),
            'search_items' =>  esc_html__( 'Search Testimonials Categories','edgtf-core' ),
            'all_items' => esc_html__( 'All Testimonials Categories','edgtf-core' ),
            'parent_item' => esc_html__( 'Parent Testimonial Category','edgtf-core' ),
            'parent_item_colon' => esc_html__( 'Parent Testimonial Category:','edgtf-core' ),
            'edit_item' => esc_html__( 'Edit Testimonials Category','edgtf-core' ),
            'update_item' => esc_html__( 'Update Testimonials Category','edgtf-core' ),
            'add_new_item' => esc_html__( 'Add New Testimonials Category','edgtf-core' ),
            'new_item_name' => esc_html__( 'New Testimonials Category Name','edgtf-core' ),
            'menu_name' => esc_html__( 'Testimonials Categories','edgtf-core' ),
        );

        register_taxonomy($this->taxBase, array($this->base), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'show_admin_column' => true,
            'rewrite' => array( 'slug' => 'testimonials-category' ),
        ));
    }

}