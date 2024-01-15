<?php

/**
 * Widget that adds separator boxes type
 *
 * Class Separator_Widget
 */
class ConallEdgeClassSeparatorWidget extends ConallEdgeClassWidget {
    /**
     * Set basic widget options and call parent class construct
     */
    public function __construct() {
        parent::__construct(
            'edgtf_separator_widget', // Base ID
            esc_html__( 'Conall Separator Widget', 'conall' ),
	        array( 'description' => esc_html__( 'Add a separator element to your widget areas', 'conall' ) )
        );

        $this->setParams();
    }

    /**
     * Sets widget options
     */
    protected function setParams() {
        $this->params = array(
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Type', 'conall' ),
                'name' => 'type',
                'options' => array(
                    'normal' => esc_html__( 'Normal', 'conall' ),
                    'full-width' => esc_html__( 'Full Width', 'conall' )
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Position', 'conall' ),
                'name' => 'position',
                'options' => array(
                    'center' => esc_html__( 'Center', 'conall' ),
                    'left' => esc_html__( 'Left', 'conall' ),
                    'right' => esc_html__( 'Right', 'conall' )
                )
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Style', 'conall' ),
                'name' => 'border_style',
                'options' => array(
                    'solid' => esc_html__( 'Solid', 'conall' ),
                    'dashed' => esc_html__( 'Dashed', 'conall' ),
                    'dotted' => esc_html__( 'Dotted', 'conall' )
                )
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Color', 'conall' ),
                'name' => 'color'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Width', 'conall' ),
                'name' => 'width'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Thickness (px)', 'conall' ),
                'name' => 'thickness'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Top Margin', 'conall' ),
                'name' => 'top_margin'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Bottom Margin', 'conall' ),
                'name' => 'bottom_margin'
            )
        );
    }

    /**
     * Generates widget's HTML
     *
     * @param array $args args from widget area
     * @param array $instance widget's options
     */
    public function widget($args, $instance) {

        extract($args);

        //prepare variables
        $params = '';

        //is instance empty?
        if(is_array($instance) && count($instance)) {
            //generate shortcode params
            foreach($instance as $key => $value) {
                $params .= " $key='$value' ";
            }
        }

        echo '<div class="widget edgtf-separator-widget">';

        //finally call the shortcode
        echo do_shortcode("[edgtf_separator $params]"); // XSS OK

        echo '</div>'; //close div.edgtf-separator-widget
    }
}