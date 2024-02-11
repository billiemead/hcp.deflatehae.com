<?php

namespace Osteo_Image_Hotspot\Widgets;
use WPBakeryShortCode;

defined( 'ABSPATH' ) || die();
class Osteo_Image_Hotspot extends WPBakeryShortCode {

    function __construct() {
        add_shortcode( 'osteo_image_hotspot', array( $this, 'osteo_image_hotspot_shortcode' ) );
        $this->map_element();
        $this->assets_manager();
    }

    public function osteo_image_hotspot_shortcode( $atts, $content = '' ) {

        $settings = shortcode_atts(
            array(
                'hotspot_content_theme'           => 'white',
                'hotspot_animation'               => 'swing',
                'hotspot_main_image'              => '#fff',
                'hotspot_list'                    => '',
                'hotspot_active'                  => 'no',
                'hotspot_trigger'                 => 'hover',
                'hotspot_arrow_position'          => 'top',
                'hotspot_pointer_theme'           => 'pointer-white',
                'hotspot_pointer_icon'            => 'fas fa-plus',
                'hotspot_position_top'            => '50',
                'hotspot_position_left'           => '50',
                'hotspot_image_switch'            => 'no',
                'hotspot_image'                   => '',
                'hotspot_image_size'              => '',
                'hotspot_youtube_video_switch'    => 'no',
                'hotspot_youtube_video_id'        => '',
                'hotspot_vimeo_video_switch'      => 'no',
                'hotspot_vimeo_video_id'          => '',
                'hotspot_soundcloud_audio_switch' => 'no',
                'hotspot_soundcloud_audio_id'     => '',
                'hotspot_audio_switch'            => 'no',
                'hotspot_audio_link'              => '',
                'hotspot_heading_switch'          => 'yes',
                'hotspot_heading'                 => '',
                'hotspot_heading_html_tag'        => 'h2',
                'hotspot_paragraph_switch'        => 'yes',
                'hotspot_paragraph'               => '',
                'hotspot_youtube_video_height'    => '',
                'hotspot_vimeo_video_height'      => '',
                'hotspot_soundcloud_audio_height' => '',
                'hotspot_image_size'              => 'medium',
            ),
            $atts
        );

        $hotspot_main_image = wp_get_attachment_image_src( $settings['hotspot_main_image'], 'full' );
        //Features
        $items = vc_param_group_parse_atts( $settings['hotspot_list'] );
        wp_enqueue_style( 'tooltipster' );
        wp_enqueue_style( 'image-hotspot' );
        wp_enqueue_style( 'osteo-image-hotspot-style' );
        wp_enqueue_script( 'tooltipster' );
        wp_enqueue_script( 'image-hotspot' );
        ob_start();

        ?>


        <?php if ( $items ) {

            ?>

        <div class="osteo-tooltip-container style-1" data-tooltipstyle="<?php echo esc_attr( $settings['hotspot_content_theme'] ); ?>" data-animation="<?php echo esc_attr( $settings['hotspot_animation'] ); ?>">
            <img src="<?php echo esc_url( $hotspot_main_image['0'] ); ?>" alt="<?php echo esc_html( $item['hotspot_image_alt'] ); ?>">
            <div class="osteo-hotspots">
                <?php foreach ( $items as $item ) {?>

                    <?php
$hotspot_image_size = $item['hotspot_image_size'];
                ?>
                    <div class="osteo-single-hotspot-item" style="top: <?php echo esc_attr( $item['hotspot_position_top'] ); ?>%!important;
                left: <?php echo esc_attr( $item['hotspot_position_left'] ); ?>%!important;">
                        <a href="#" class="osteo-tooltip pulse-effect" data-isopen="<?php echo esc_attr( $item['hotspot_active'] ); ?>" data-trigger="<?php echo esc_attr( $item['hotspot_trigger'] ); ?>" data-position="<?php echo esc_attr( $item['hotspot_arrow_position'] ); ?>">
                            <span>
                                <div class="osteo-hotspot-content">
                                <?php
if ( 'yes' === $item['hotspot_image_switch'] ) {
                    ?>
                <?php echo wp_get_attachment_image( $item['hotspot_image'], $hotspot_image_size ); ?>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_youtube_video_switch'] ) {
                    ?>
                                            <iframe src="https://www.youtube.com/embed/<?php echo esc_html( $item['hotspot_youtube_video_id'] ); ?>" height="<?php echo esc_html( $item['hotspot_youtube_video_height'] ); ?>" allowfullscreen></iframe>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_vimeo_video_switch'] ) {
                    ?>
                                            <iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $item['hotspot_vimeo_video_id'] ); ?>" height="<?php echo esc_attr( $item['hotspot_vimeo_video_height'] ); ?>" allowfullscreen></iframe>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_soundcloud_audio_switch'] ) {
                    ?>
                                            <iframe height="<?php echo esc_attr( $item['hotspot_soundcloud_audio_height'] ); ?>" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php echo esc_attr( $item['hotspot_soundcloud_audio_id'] ); ?>&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_audio_switch'] ) {
                    ?>
                                            <audio controls src="<?php echo esc_attr( $item['hotspot_audio_link'] ); ?>"></audio>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_heading_switch'] ) {
                    ?>
                                            <<?php esc_html_e( $item['hotspot_heading_html_tag'] );?>>
                                                <?php echo esc_html( $item['hotspot_heading'] ); ?>
                                            </<?php esc_html_e( $item['hotspot_heading_html_tag'] );?>>
                                        <?php
}
                ?>
                                <?php
if ( 'yes' === $item['hotspot_paragraph_switch'] ) {
                    ?>
                                            <p><?php echo esc_html( $item['hotspot_paragraph'] ); ?></p>
                                        <?php
}
                ?>
                                </div>
                            </span>
                            <div class="<?php echo esc_attr( $item['hotspot_pointer_theme'] ); ?>">
                                <i class="<?php echo esc_attr( $item['hotspot_pointer_icon'] ); ?>"></i>
                            </div>
                        </a>
                    </div>
                <?php }?>
            </div>
        </div>

        <?php

        }?>

        <style>
        </style>
		<?php

        return ob_get_clean();
    }

    public function assets_manager() {
        wp_register_style( 'tooltipster', OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/tooltipster/tooltipster.css', array(), '3.2.6', 'all' );
        wp_register_style( 'image-hotspot', OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/image-hotspot/image-hotspot.css', array(), '1.0.0', 'all' );
        wp_register_style( 'osteo-image-hotspot-style', OSTEO_IMAGE_HOTSPOT_URL . 'assets/css/style.css', array(), '1.0.0', 'all' );
        wp_style_add_data( 'osteo-image-hotspot-style', 'rtl', 'replace' );
        wp_register_script( 'tooltipster', OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/tooltipster/jquery.tooltipster.min.js', array( 'jquery' ), '3.2.6', true );
        wp_register_script( 'image-hotspot', OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/image-hotspot/image-hotspot.js', array( 'jquery' ), '1.0.0', true );
    }

    public function map_element() {
        vc_map(
            array(
                'name'             => esc_html__( 'Osteo Image Hotspot', 'osteo-image-hotspot' ),
                'base'             => 'osteo_image_hotspot',
                'category'         => esc_html__( 'Osteo Image Hotspot', 'osteo-image-hotspot' ),
                "content_element"  => true,
                'front_enqueue_js' => array( OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/tooltipster/jquery.tooltipster.min.js', OSTEO_IMAGE_HOTSPOT_URL . 'assets/vendor/image-hotspot/image-hotspot.js' ),
                'params'           => array(
                    //Content
                    array(
                        'type'             => 'dropdown',
                        'heading'          => esc_html__( 'Theme', 'osteo-image-hotspot' ),
                        'group'            => esc_html__( 'Content', 'osteo-image-hotspot' ),
                        'edit_field_class' => 'vc_col-sm-12',
                        'param_name'       => 'hotspot_content_theme',
                        'std'              => 'white',
                        'value'            => [
                            esc_html__( 'White', 'osteo-image-hotspot' )       => 'white',
                            esc_html__( 'Black', 'osteo-image-hotspot' )       => 'black',
                            esc_html__( 'Red', 'osteo-image-hotspot' )         => 'red',
                            esc_html__( 'Pink', 'osteo-image-hotspot' )        => 'pink',
                            esc_html__( 'Purple', 'osteo-image-hotspot' )      => 'purple',
                            esc_html__( 'Deep Purple', 'osteo-image-hotspot' ) => 'deep-purple',
                            esc_html__( 'Indigo', 'osteo-image-hotspot' )      => 'indigo',
                            esc_html__( 'Blue', 'osteo-image-hotspot' )        => 'blue',
                            esc_html__( 'Light Blue', 'osteo-image-hotspot' )  => 'light-blue',
                            esc_html__( 'Cyan', 'osteo-image-hotspot' )        => 'cyan',
                            esc_html__( 'Teal', 'osteo-image-hotspot' )        => 'teal',
                            esc_html__( 'Green', 'osteo-image-hotspot' )       => 'green',
                            esc_html__( 'Light Green', 'osteo-image-hotspot' ) => 'light-green',
                            esc_html__( 'Lime', 'osteo-image-hotspot' )        => 'lime',
                            esc_html__( 'Yellow', 'osteo-image-hotspot' )      => 'yellow',
                            esc_html__( 'Amber', 'osteo-image-hotspot' )       => 'amber',
                            esc_html__( 'Orange', 'osteo-image-hotspot' )      => 'orange',
                            esc_html__( 'Deep Orange', 'osteo-image-hotspot' ) => 'deep-orange',
                            esc_html__( 'Brown', 'osteo-image-hotspot' )       => 'brown',
                            esc_html__( 'Grey', 'osteo-image-hotspot' )        => 'grey',
                            esc_html__( 'Blue Grey', 'osteo-image-hotspot' )   => 'blue-grey',

                        ],
                        'save_always'      => true,
                    ),

                    array(
                        'type'             => 'dropdown',
                        'heading'          => esc_html__( 'Animation', 'osteo-image-hotspot' ),
                        'group'            => esc_html__( 'Content', 'osteo-image-hotspot' ),
                        'edit_field_class' => 'vc_col-sm-12',
                        'param_name'       => 'hotspot_animation',
                        'std'              => 'swing',
                        'value'            => array(
                            esc_html__( 'Fade', 'osteo-image-hotspot' )  => 'fade',
                            esc_html__( 'Grow', 'osteo-image-hotspot' )  => 'grow',
                            esc_html__( 'Swing', 'osteo-image-hotspot' ) => 'swing',
                            esc_html__( 'Slide', 'osteo-image-hotspot' ) => 'slide',
                            esc_html__( 'Fall', 'osteo-image-hotspot' )  => 'fall',
                        ),
                        'save_always'      => true,
                    ),

                    array(
                        'type'             => 'attach_image',
                        'heading'          => esc_html__( 'Upload Image', 'osteo-image-hotspot' ),
                        'group'            => esc_html__( 'Content', 'osteo-image-hotspot' ),
                        'edit_field_class' => 'vc_col-sm-12',
                        'param_name'       => 'hotspot_main_image',
                    ),

                    array(
                        'type'        => 'param_group',
                        'group'       => esc_html__( 'Content', 'osteo-image-hotspot' ),
                        'description' => esc_html__( 'Single Hotspot Item', 'osteo-image-hotspot' ),
                        'param_name'  => 'hotspot_list',
                        'params'      => array(
                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Hotspot Active', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_active',
                                'std'              => 'no',
                                'value'            => [
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ],
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Trigger', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_trigger',
                                'std'              => 'hover',
                                'value'            => [
                                    esc_html__( 'Click', 'osteo-image-hotspot' ) => 'click',
                                    esc_html__( 'Hover', 'osteo-image-hotspot' ) => 'hover',
                                ],
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Arrow Position', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_arrow_position',
                                'std'              => 'top',
                                'value'            => [
                                    esc_html__( 'Top', 'osteo-image-hotspot' )          => 'top',
                                    esc_html__( 'Right', 'osteo-image-hotspot' )        => 'right',
                                    esc_html__( 'Bottom', 'osteo-image-hotspot' )       => 'bottom',
                                    esc_html__( 'Left', 'osteo-image-hotspot' )         => 'left',
                                    esc_html__( 'Top Right', 'osteo-image-hotspot' )    => 'top-right',
                                    esc_html__( 'Top Left', 'osteo-image-hotspot' )     => 'top-left',
                                    esc_html__( 'Bottom Right', 'osteo-image-hotspot' ) => 'bottom-right',
                                    esc_html__( 'Bottom Left', 'osteo-image-hotspot' )  => 'bottom-left',
                                ],
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Pointer Theme', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_pointer_theme',
                                'std'              => 'pointer-white',
                                'value'            => [
                                    esc_html__( 'White', 'osteo-image-hotspot' )       => 'pointer-white',
                                    esc_html__( 'Black', 'osteo-image-hotspot' )       => 'pointer-black',
                                    esc_html__( 'Red', 'osteo-image-hotspot' )         => 'pointer-red',
                                    esc_html__( 'Pink', 'osteo-image-hotspot' )        => 'pointer-pink',
                                    esc_html__( 'Purple', 'osteo-image-hotspot' )      => 'pointer-purple',
                                    esc_html__( 'Deep Purple', 'osteo-image-hotspot' ) => 'pointer-deep-purple',
                                    esc_html__( 'Indigo', 'osteo-image-hotspot' )      => 'pointer-indigo',
                                    esc_html__( 'Blue', 'osteo-image-hotspot' )        => 'pointer-blue',
                                    esc_html__( 'Light Blue', 'osteo-image-hotspot' )  => 'pointer-light-blue',
                                    esc_html__( 'Cyan', 'osteo-image-hotspot' )        => 'pointer-cyan',
                                    esc_html__( 'Teal', 'osteo-image-hotspot' )        => 'pointer-teal',
                                    esc_html__( 'Green', 'osteo-image-hotspot' )       => 'pointer-green',
                                    esc_html__( 'Light Green', 'osteo-image-hotspot' ) => 'pointer-light-green',
                                    esc_html__( 'Lime', 'osteo-image-hotspot' )        => 'pointer-lime',
                                    esc_html__( 'Yellow', 'osteo-image-hotspot' )      => 'pointer-yellow',
                                    esc_html__( 'Amber', 'osteo-image-hotspot' )       => 'pointer-amber',
                                    esc_html__( 'Orange', 'osteo-image-hotspot' )      => 'pointer-orange',
                                    esc_html__( 'Deep Orange', 'osteo-image-hotspot' ) => 'pointer-deep-orange',
                                    esc_html__( 'Brown', 'osteo-image-hotspot' )       => 'pointer-brown',
                                    esc_html__( 'Grey', 'osteo-image-hotspot' )        => 'pointer-grey',
                                    esc_html__( 'Blue Grey', 'osteo-image-hotspot' )   => 'pointer-blue-grey',
                                ],
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'iconpicker',
                                'heading'          => esc_html__( 'Hotspot Icon', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_pointer_icon',
                                'std'              => 'fas fa-plus',
                            ),

                            array(
                                'type'             => 'textfield',
                                'heading'          => esc_html__( 'Top', 'osteo-image-hotspot' ),
                                'description'      => esc_html__( 'Not Include (%). Max 100% Supported.', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-6',
                                'param_name'       => 'hotspot_position_top',
                                'std'              => '50',
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textfield',
                                'heading'          => esc_html__( 'Left', 'osteo-image-hotspot' ),
                                'description'      => esc_html__( 'Not Include (%). Max 100% Supported.', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-6',
                                'param_name'       => 'hotspot_position_left',
                                'std'              => '50',
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Image', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_image_switch',
                                'std'              => 'no',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'attach_image',
                                'heading'          => esc_html__( 'Upload Image', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_image',
                                'dependency'       => array( 'element' => 'hotspot_image_switch', 'value' => array( 'yes' ) ),
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Image Size', 'osteo-image-hotspot' ),
                                'group'            => esc_html__( 'Settings', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_image_size',
                                'std'              => 'medium',
                                'value'            => get_intermediate_image_sizes(),
                                'dependency'       => array( 'element' => 'hotspot_image_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Youtube Video', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_youtube_video_switch',
                                'std'              => 'no',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Youtube Video ID', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_youtube_video_id',
                                'dependency'       => array( 'element' => 'hotspot_youtube_video_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textfield',
                                'heading'          => esc_html__( 'Height', 'osteo-image-hotspot' ),
                                'description'      => esc_html__( 'Not Include (px).', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_youtube_video_height',
                                'std'              => '250',
                                'dependency'       => array( 'element' => 'hotspot_youtube_video_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Vimeo Video', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_vimeo_video_switch',
                                'std'              => 'no',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Vimeo Video ID', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_vimeo_video_id',
                                'dependency'       => array( 'element' => 'hotspot_vimeo_video_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textfield',
                                'heading'          => esc_html__( 'Height', 'osteo-image-hotspot' ),
                                'description'      => esc_html__( 'Not Include (px).', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_vimeo_video_height',
                                'std'              => '250',
                                'dependency'       => array( 'element' => 'hotspot_vimeo_video_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Soundcloud Audio', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_soundcloud_audio_switch',
                                'std'              => 'no',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Soundcloud Audio ID', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_soundcloud_audio_id',
                                'dependency'       => array( 'element' => 'hotspot_soundcloud_audio_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textfield',
                                'heading'          => esc_html__( 'Height', 'osteo-image-hotspot' ),
                                'description'      => esc_html__( 'Not Include (px).', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_soundcloud_audio_height',
                                'std'              => '250',
                                'dependency'       => array( 'element' => 'hotspot_soundcloud_audio_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Audio', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_audio_switch',
                                'std'              => 'no',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Audio Link', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_audio_link',
                                'dependency'       => array( 'element' => 'hotspot_audio_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Heading', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_heading_switch',
                                'std'              => 'yes',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Heading', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_heading',
                                'dependency'       => array( 'element' => 'hotspot_heading_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'HTML Tag', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_heading_html_tag',
                                'std'              => 'h2',
                                'value'            => array(
                                    esc_html__( 'H1', 'osteo-image-hotspot' ) => 'h1',
                                    esc_html__( 'H2', 'osteo-image-hotspot' ) => 'h2',
                                    esc_html__( 'H3', 'osteo-image-hotspot' ) => 'h3',
                                    esc_html__( 'H4', 'osteo-image-hotspot' ) => 'h4',
                                    esc_html__( 'H5', 'osteo-image-hotspot' ) => 'h5',
                                    esc_html__( 'H6', 'osteo-image-hotspot' ) => 'h6',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'dropdown',
                                'heading'          => esc_html__( 'Show Paragraph', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_paragraph_switch',
                                'std'              => 'yes',
                                'value'            => array(
                                    esc_html__( 'No', 'osteo-image-hotspot' )  => 'no',
                                    esc_html__( 'Yes', 'osteo-image-hotspot' ) => 'yes',
                                ),
                                'save_always'      => true,
                            ),

                            array(
                                'type'             => 'textarea',
                                'heading'          => esc_html__( 'Paragraph', 'osteo-image-hotspot' ),
                                'edit_field_class' => 'vc_col-sm-12',
                                'param_name'       => 'hotspot_paragraph',
                                'dependency'       => array( 'element' => 'hotspot_paragraph_switch', 'value' => array( 'yes' ) ),
                                'save_always'      => true,
                            ),

                        ),
                    ),
                ),
            )
        );
    }
}