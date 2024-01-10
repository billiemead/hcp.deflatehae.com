<?php
namespace EdgeCore\CPT\Portfolio;

use EdgeCore\Lib\PostTypeInterface;

/**
 * Class PortfolioRegister
 * @package EdgeCore\CPT\Portfolio
 */
class PortfolioRegister implements PostTypeInterface {
    /**
     * @var string
     */
    private $base;
    private $taxBase;

    public function __construct() {
        $this->base = 'portfolio-item';
        $this->taxBase = 'portfolio-category';

        add_filter('single_template', array($this, 'registerSingleTemplate'));
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
        $this->registerTagTax();
    }

    /**
     * Registers portfolio single template if one does'nt exists in theme.
     * Hooked to single_template filter
     * @param $single string current template
     * @return string string changed template
     */
    public function registerSingleTemplate($single) {
        global $post;

        if($post->post_type == $this->base) {
            if(!file_exists(get_template_directory().'/single-portfolio-item.php')) {
                return EDGE_CORE_CPT_PATH.'/portfolio/templates/single-'.$this->base.'.php';
            }
        }

        return $single;
    }

    /**
     * Registers custom post type with WordPress
     */
    private function registerPostType() {
        global $conall_edge_framework, $conall_edge_options;

        $menuPosition = 5;
        $menuIcon = 'dashicons-admin-post';
        $slug = $this->base;

        if(edgt_core_theme_installed()) {
            $menuPosition = $conall_edge_framework->getSkin()->getMenuItemPosition('portfolio');
            $menuIcon = $conall_edge_framework->getSkin()->getMenuIcon('portfolio');

            if(isset($conall_edge_options['portfolio_single_slug'])) {
                if($conall_edge_options['portfolio_single_slug'] != ""){
                    $slug = $conall_edge_options['portfolio_single_slug'];
                }
            }
        }

        register_post_type( $this->base,
            array(
                'labels' => array(
                    'name' => esc_html__( 'Portfolio','edgtf-core' ),
                    'singular_name' => esc_html__( 'Portfolio Item','edgtf-core' ),
                    'add_item' => esc_html__('New Portfolio Item','edgtf-core'),
                    'add_new_item' => esc_html__('Add New Portfolio Item','edgtf-core'),
                    'edit_item' => esc_html__('Edit Portfolio Item','edgtf-core')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug),
                'menu_position' => $menuPosition,
                'show_ui' => true,
                'supports' => array('author', 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'comments'),
                'menu_icon'  =>  $menuIcon
            )
        );
    }

    /**
     * Registers custom taxonomy with WordPress
     */
    private function registerTax() {
        $labels = array(
            'name' => esc_html__( 'Portfolio Categories', 'edgtf-core' ),
            'singular_name' => esc_html__( 'Portfolio Category', 'edgtf-core' ),
            'search_items' =>  esc_html__( 'Search Portfolio Categories','edgtf-core' ),
            'all_items' => esc_html__( 'All Portfolio Categories','edgtf-core' ),
            'parent_item' => esc_html__( 'Parent Portfolio Category','edgtf-core' ),
            'parent_item_colon' => esc_html__( 'Parent Portfolio Category:','edgtf-core' ),
            'edit_item' => esc_html__( 'Edit Portfolio Category','edgtf-core' ),
            'update_item' => esc_html__( 'Update Portfolio Category','edgtf-core' ),
            'add_new_item' => esc_html__( 'Add New Portfolio Category','edgtf-core' ),
            'new_item_name' => esc_html__( 'New Portfolio Category Name','edgtf-core' ),
            'menu_name' => esc_html__( 'Portfolio Categories','edgtf-core' ),
        );

        register_taxonomy($this->taxBase, array($this->base), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'portfolio-category' ),
        ));
    }

    /**
     * Registers custom tag taxonomy with WordPress
     */
    private function registerTagTax() {
        $labels = array(
            'name' => esc_html__( 'Portfolio Tags', 'edgtf-core' ),
            'singular_name' => esc_html__( 'Portfolio Tag', 'edgtf-core' ),
            'search_items' =>  esc_html__( 'Search Portfolio Tags','edgtf-core' ),
            'all_items' => esc_html__( 'All Portfolio Tags','edgtf-core' ),
            'parent_item' => esc_html__( 'Parent Portfolio Tag','edgtf-core' ),
            'parent_item_colon' => esc_html__( 'Parent Portfolio Tags:','edgtf-core' ),
            'edit_item' => esc_html__( 'Edit Portfolio Tag','edgtf-core' ),
            'update_item' => esc_html__( 'Update Portfolio Tag','edgtf-core' ),
            'add_new_item' => esc_html__( 'Add New Portfolio Tag','edgtf-core' ),
            'new_item_name' => esc_html__( 'New Portfolio Tag Name','edgtf-core' ),
            'menu_name' => esc_html__( 'Portfolio Tags','edgtf-core' ),
        );

        register_taxonomy('portfolio-tag',array($this->base), array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'portfolio-tag' ),
        ));
    }
}