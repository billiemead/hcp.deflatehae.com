<?php

/**
 * Widget that adds blog list
 *
 * Class Blog_List_Widget
 */
class ConallEdgeClassBlogListWidget extends ConallEdgeClassWidget {
    /**
     * Set basic widget options and call parent class construct
     */
    public function __construct() {
        parent::__construct(
            'edgtf_blog_list_widget', // Base ID
            esc_html__( 'Conall Blog List Widget', 'conall' ),
	        array( 'description' => esc_html__( 'Display a list of your blog posts', 'conall' ) )
        );

        $this->setParams();
    }

    /**
     * Sets widget options
     */
    protected function setParams() {
        $this->params = array(
            array(
                'name' => 'widget_title',
                'type' => 'textfield',
                'title' => esc_html__( 'Widget Title', 'conall' )
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Number of Posts', 'conall' ),
                'name' => 'number_of_posts'
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Order By', 'conall' ),
                'name' => 'order_by',
                'options' => array(
                    'title' => esc_html__( 'Title', 'conall' ),
                    'date' => esc_html__( 'Date', 'conall' )
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Order', 'conall' ),
                'name' => 'order',
                'options' => array(
                    'ASC' => esc_html__( 'ASC', 'conall' ),
                    'DESC' => esc_html__( 'DESC', 'conall' )
                )
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Category Slug', 'conall' ),
                'name' => 'category',
                'description' => esc_html__( 'Leave empty for all or use comma for list', 'conall' )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Title Tag', 'conall' ),
                'name' => 'title_tag',
                'options' => array(
                    'h5' => esc_html__( 'h5', 'conall' ),
                    'h2' => esc_html__( 'h2', 'conall' ),
                    'h3' => esc_html__( 'h3', 'conall' ),
                    'h4' => esc_html__( 'h4', 'conall' ),
                    'h6' => esc_html__( 'h6', 'conall' ),
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Display Category', 'conall' ),
                'name' => 'post_simple_info_category',
                'options' => array(
                    'no' => esc_html__( 'No', 'conall' ),
                    'yes' => esc_html__( 'Yes', 'conall' )
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Image Size', 'conall' ),
                'name' => 'widget_image_size',
                'options' => array(
                    'default' => esc_html__( 'Default (Small)', 'conall' ),
                    'large' => esc_html__( 'Large', 'conall' )
                )
            ),
        );
    }

    /**
     * Generates widget's HTML
     *
     * @param array $args args from widget area
     * @param array $instance widget's options
     */
    public function widget($args, $instance) {

        //prepare variables
        $params = '';

        $instance['type'] = 'simple';
        if(isset($instance['widget_image_size']) && $instance['widget_image_size'] == 'large') {
            $instance['image_size'] = 'sidebar';
        }

        //is instance empty?
        if(is_array($instance) && count($instance)) {
            //generate shortcode params
            foreach($instance as $key => $value) {
                $params .= " $key = '$value' ";
            }
        }

        echo '<div class="widget edgtf-blog-list-widget">';
            if (!empty($instance['widget_title']) && $instance['widget_title'] !== '') {
                echo wp_kses_post( $args['before_title'].$instance['widget_title'].$args['after_title'] );
            }
                
            //finally call the shortcode
            echo do_shortcode("[edgtf_blog_list $params]"); // XSS OK

            echo '</div>'; //close div.mkdf-plw-seven
    }
}