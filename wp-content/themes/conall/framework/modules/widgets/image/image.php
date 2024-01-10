<?php

/**
 * Widget that adds image type
 *
 * Class Image_Widget
 */
class ConallEdgeClassImageWidget extends ConallEdgeClassWidget {
    /**
     * Set basic widget options and call parent class construct
     */
    public function __construct() {
        parent::__construct(
            'edgtf_image_widget', // Base ID
            esc_html__( 'Conall Image Widget', 'conall' ),
	        array( 'description' => esc_html__( 'Add image element to widget areas', 'conall' ) )
        );

        $this->setParams();
    }

    /**
     * Sets widget options
     */
    protected function setParams() {
        $this->params = array(
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Extra Class Name', 'conall' ),
                'name' => 'extra_class'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Widget Title', 'conall' ),
                'name' => 'widget_title'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Image Source', 'conall' ),
                'name' => 'image_src'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Image Alt', 'conall' ),
                'name' => 'image_alt'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Image Width', 'conall' ),
                'name' => 'image_width'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Image Height', 'conall' ),
                'name' => 'image_height'
            ),
            array(
                'type' => 'textfield',
                'title' => esc_html__( 'Link', 'conall' ),
                'name' => 'link'
            ),
            array(
                'type' => 'dropdown',
                'title' => esc_html__( 'Target', 'conall' ),
                'name' => 'target',
                'options' => array(
                    '_self' => esc_html__( 'Same Window', 'conall' ),
                    '_blank' => esc_html__( 'New Window', 'conall' )
                )
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

        $extra_class = '';
        if (!empty($instance['extra_class']) && $instance['extra_class'] !== '') {
            $extra_class = $instance['extra_class'];
        }

        $image_src = '';
        $image_alt = 'Widget Image';
        $image_width = '';
        $image_height = '';

        if (!empty($instance['image_alt']) && $instance['image_alt'] !== '') {
            $image_alt = $instance['image_alt'];
        }

        if (!empty($instance['image_width']) && $instance['image_width'] !== '') {
            $image_width = intval($instance['image_width']);
        }

        if (!empty($instance['image_height']) && $instance['image_height'] !== '') {
            $image_height = intval($instance['image_height']);
        }

        if (!empty($instance['image_src']) && $instance['image_src'] !== '') {
            $image_src = '<img src="'.esc_url($instance['image_src']).'" alt="'.esc_attr($image_alt).'" width="'.$image_width.'" height="'.$image_height.'" />';
        }

        $link_begin_html = '';
        $link_end_html = '';
        $target = '_self';

        if (!empty($instance['target']) && $instance['target'] !== '') {
            $target = $instance['target'];
        }

        if (!empty($instance['link']) && $instance['link'] !== '') {
            $link_begin_html = '<a href="'.esc_url($instance['link']).'" target="'.$target.'">';
            $link_end_html = '</a>';
        }
        ?>

        <div class="widget edgtf-image-widget <?php echo esc_html($extra_class); ?>">
            <?php
                if (!empty($instance['widget_title']) && $instance['widget_title'] !== '') {
                    echo wp_kses_post( $args['before_title'].$instance['widget_title'].$args['after_title'] );
                }
                if ($link_begin_html !== '') {
                    echo conall_edge_get_module_part($link_begin_html);
                }
                if ($image_src !== '') {
                    echo conall_edge_get_module_part($image_src);
                }
                if ($link_end_html !== '') {
                    echo conall_edge_get_module_part($link_end_html);
                }
            ?>
        </div>
    <?php 
    }
}