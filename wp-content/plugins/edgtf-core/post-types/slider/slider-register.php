<?php
namespace EdgeCore\CPT\Slider;

use EdgeCore\Lib;

/**
 * Class SliderRegister
 * @package EdgeCore\CPT\Slider
 */
class SliderRegister implements Lib\PostTypeInterface {
    /**
     * @var string
     */
    private $base;
    private $taxBase;

    public function __construct() {
        $this->base = 'slides';
        $this->taxBase = 'slides_category';
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
            $menuPosition = $conall_edge_framework->getSkin()->getMenuItemPosition('slider');
            $menuIcon = $conall_edge_framework->getSkin()->getMenuIcon('slider');
        }

        register_post_type($this->base,
            array(
                'labels' 		=> array(
                    'name' 				=> esc_html__('Edge Slider','edgtf-core' ),
                    'menu_name'	=> esc_html__('Edge Slider','edgtf-core' ),
                    'all_items'	=> esc_html__('Slides','edgtf-core' ),
                    'add_new' =>  esc_html__('Add New Slide','edgtf-core'),
                    'singular_name' 	=> esc_html__('Slide','edgtf-core' ),
                    'add_item'			=> esc_html__('New Slide','edgtf-core'),
                    'add_new_item' 		=> esc_html__('Add New Slide','edgtf-core'),
                    'edit_item' 		=> esc_html__('Edit Slide','edgtf-core')
                ),
                'public'		=>	false,
                'show_in_menu'	=>	true,
                'rewrite' 		=> 	array('slug' => 'slides'),
                'menu_position' => 	$menuPosition,
                'show_ui'		=>	true,
                'has_archive'	=>	false,
                'hierarchical'	=>	false,
                'supports'		=>	array('title', 'thumbnail', 'page-attributes'),
                'menu_icon'  =>  $menuIcon
            )
        );
    }

    /**
     * Registers custom taxonomy with WordPress
     */
    private function registerTax() {
        $labels = array(
            'name' => esc_html__( 'Sliders', 'edgtf-core' ),
            'singular_name' => esc_html__( 'Slider', 'edgtf-core' ),
            'search_items' =>  esc_html__( 'Search Sliders','edgtf-core' ),
            'all_items' => esc_html__( 'All Sliders','edgtf-core' ),
            'parent_item' => esc_html__( 'Parent Slider','edgtf-core' ),
            'parent_item_colon' => esc_html__( 'Parent Slider:','edgtf-core' ),
            'edit_item' => esc_html__( 'Edit Slider','edgtf-core' ),
            'update_item' => esc_html__( 'Update Slider','edgtf-core' ),
            'add_new_item' => esc_html__( 'Add New Slider','edgtf-core' ),
            'new_item_name' => esc_html__( 'New Slider Name','edgtf-core' ),
            'menu_name' => esc_html__( 'Sliders','edgtf-core' ),
        );

        register_taxonomy($this->taxBase, array($this->base), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'show_admin_column' => true,
            'rewrite' => array( 'slug' => 'slides-category' ),
        ));
    }
}