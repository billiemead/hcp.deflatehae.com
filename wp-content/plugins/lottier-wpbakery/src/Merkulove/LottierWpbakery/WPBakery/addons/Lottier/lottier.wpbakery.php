<?php
/**
 * Lottier for Wpbakery
 * Lottie animations in just a few clicks without writing a single line of code
 * Exclusively on https://1.envato.market/lottier-wpbakery
 *
 * @encoding        UTF-8
 * @version         1.1.5
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\LottierWpbakery;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use Exception;
use Merkulove\LottierWpbakery\Unity\Plugin as UnityPlugin;

class wpbLottier {

	/**
	 * Get things started.
	 *
	 * @throws Exception
	 * @since  1.0.0
	 * @access public
	 **/
	public function __construct() {

		/** Lottier addon map. */
        $this->params_map();

		/** Shortcode for Lottier addon. */
		add_shortcode( 'mdp_wpb_lottier', [ $this, 'render' ] );

        /** Front-End Styles */
        wp_register_style( 'mdp-lottier-wpbakery',
            UnityPlugin::get_url() . 'css/lottier-wpbakery' . UnityPlugin::get_suffix() . '.css',
            [],
            UnityPlugin::get_version() );

        wp_enqueue_style( 'mdp-lottier-wpbakery' );

        /** Front-End Scripts */
        wp_enqueue_script( 'lottie-player',
            UnityPlugin::get_url() . 'js/lottie-player' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            true);

        wp_enqueue_script( 'dotlottie-player',
            UnityPlugin::get_url() . 'js/dotlottie-player' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            true);

        wp_enqueue_script( 'mdp-lottier-wpbakery',
            UnityPlugin::get_url() . 'js/lottier-wpbakery' . UnityPlugin::get_suffix() . '.js',
            [ 'lottie-player' ],
            UnityPlugin::get_version(),
            true);

	}

    public function style_inline( $id, $settings ){

        /** Prepare inline CSS for this instance of addon. */
        $css = '';

        if ( 'yes' === $settings['properties_header'] ) {

            /** Style Header. */
            $style_header_font = $this->get_google_font_inline_css( $settings['style_header_font'] );
            $css               .= "#mdp-lottier-box-{$id} .mdp-lottier-header{ {$style_header_font} }";

            if ( ! empty( $settings['style_header_font_container'] ) ) {
                $settings['style_header_font_container'] = urldecode( $settings['style_header_font_container'] );
                $settings['style_header_font_container'] = str_replace( '|', ';', $settings['style_header_font_container'] );
                $settings['style_header_font_container'] = str_replace( '_', '-', $settings['style_header_font_container'] );
                $css .= "#mdp-lottier-box-{$id} .mdp-lottier-header { {$settings['style_header_font_container']}; }";
            }

            if ( ! empty( $settings['style_header_alignment'] ) ) {
                $css .= "#mdp-lottier-box-{$id} .mdp-lottier-header { text-align: {$settings['style_header_alignment']}; }";
            }

            /** Style SubHeader. */
            $style_subheader_font = $this->get_google_font_inline_css( $settings['style_subheader_font'] );
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-subheader{ {$style_subheader_font} }";

            if ( ! empty( $settings['style_subheader_font_container'] ) ) {
                $settings['style_subheader_font_container'] = urldecode( $settings['style_subheader_font_container'] );
                $settings['style_subheader_font_container'] = str_replace( '|', ';', $settings['style_subheader_font_container'] );
                $settings['style_subheader_font_container'] = str_replace( '_', '-', $settings['style_subheader_font_container'] );
                $css .= "#mdp-lottier-box-{$id} .mdp-lottier-subheader { {$settings['style_subheader_font_container']}; }";
            }

            if ( ! empty( $settings['style_subheader_alignment'] ) ) {
                $css .= "#mdp-lottier-box-{$id} .mdp-lottier-subheader { text-align: {$settings['style_subheader_alignment']}; }";
            }

        }

        if ( 'yes' === $settings['properties_description'] ) {

            /** Style description. */
            $style_description_font = $this->get_google_font_inline_css( $settings['style_description_font'] );
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-description{ {$style_description_font} }";

            if ( ! empty( $settings['style_description_font_container'] ) ) {
                $settings['style_description_font_container'] = urldecode( $settings['style_description_font_container'] );
                $settings['style_description_font_container'] = str_replace( '|', ';', $settings['style_description_font_container'] );
                $settings['style_description_font_container'] = str_replace( '_', '-', $settings['style_description_font_container'] );
                $css                                          .= "#mdp-lottier-box-{$id} .mdp-lottier-description { {$settings['style_description_font_container']}; }";
            }

            if ( ! empty( $settings['style_description_alignment'] ) ) {
                $css .= "#mdp-lottier-box-{$id} .mdp-lottier-description { text-align: {$settings['style_description_alignment']}; }";
            }

        }

        /** Style animation. */
        if ( ! empty( $settings['style_animation_width'] ) ) {
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-svg .mdp-lottier-player{ width: {$settings['style_animation_width']}; }";
        }

        if ( ! empty( $settings['style_animation_height'] ) ) {
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-svg > div { height: {$settings['style_animation_height']}; }";
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-animation { height: {$settings['style_animation_height']}; }";
        }

        if( ! empty( $settings['style_animation_alignment'] ) ){
            $css .= "#mdp-lottier-box-{$id} .mdp-lottier-svg { justify-content: {$settings['style_animation_alignment']}; }";
        }

        echo sprintf("<style>%s</style>", $css );
    }

    /**
     * Shortcode [mdp_wpb_lottier] output.
     *
     * @param $atts array - Shortcode parameters.
     *
     * @return false|string
     * @since 1.0.0
     * @access public
     **/
    public function render( $atts ) {

	    ob_start();

	    $settings = shortcode_atts( [
		    // Properties content
            'properties_type_player'            => 'web_player',
		    'properties_animation_speed'        => 1,
		    'properties_playback'               => 'autoplay',
		    'properties_play_mode'              => 'normal',
		    'properties_loop'                   => 'yes',
		    'properties_finish_before_pause'    => '',
		    'properties_controls'               => '',
		    'properties_header'                 => '',
		    'properties_description'            => '',
		    'properties_enable_link'            => '',

		    // Header content
		    'header_content_type'               => 'Auto play',
		    'header_html_tag'                   => 'h3',
		    'header_subheader_position'         => 'top',
		    'header_content_subheader'          => 'Subheader',

		    // Description content
		    'description_position'              => 'header',
		    'description_html_tag'              => 'div',
		    'description_text'                  => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',

		    // Link content
		    'link_position'                     => 'svg',
		    'link_url'                          => $this->get_link('https://codecanyon.net/user/merkulove'),
		    'animation_url'                     => $this->get_link('https://assets5.lottiefiles.com/packages/lf20_bmkCIy.json'),
		    'animation_url_dot'                 => $this->get_link('https://lottie.host/7e57ee10-5709-4317-bc47-fbe0e4bbd1d5/gBVwscFNXX.lottie'),

		    // Style header
		    'style_header_editor'               => '',
		    'style_header_font'                 => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
		    'style_header_font_container'       => '',
		    'style_header_alignment'            => '',

		    // Style description
		    'style_description_css'             => '',
		    'style_description_font'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
		    'style_description_font_container'  => '',
		    'style_description_alignment'       => '',

		    // Style SubHeader
		    'style_subheader_editor'            => '',
		    'style_subheader_font'              => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
		    'style_subheader_font_container'    => '',
		    'style_subheader_alignment'         => '',

		    // Style animation
		    'style_animation_css'               => '',
		    'style_animation_width'             => '100%',
		    'style_animation_height'            => '',
		    'style_animation_alignment'         => 'flex-start',
            'style_animation_class'             => ''

	    ], $atts, 'mdp_wpb_lottier' );

	    /** We save the data into a variable depending on the selected data type. */
	    $src =   $this->get_link($settings['animation_url']);
	    $src_dot =   $this->get_link($settings['animation_url_dot']);

	    /** Unique id for current block. */
	    $id = md5( json_encode( $atts ) );

        $this->style_inline($id, $settings);

        echo "<!-- Start Lottier for WPBakery WordPress Plugin -->";
        echo wp_sprintf(
            '<div id="mdp-lottier-box-%s" class="mdp-lottier-box%s">',
	        esc_attr( $id ),
	        ! empty( $settings[ 'style_animation_class' ] ) ? ' ' . $settings[ 'style_animation_class' ] : ''
        );

	    if( $settings['properties_header'] === 'yes' ){

		    echo '<' . esc_attr( $settings['header_html_tag'] ) . ' class="mdp-lottier-heading">';

		    /** Display the subheader above the title. */
		    $css_subheader_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $settings['style_subheader_editor'], '' ), 'mdp_wpb_lottier', $atts );
		    if( $settings['header_subheader_position'] === 'top' ){
			    echo '<span class="mdp-lottier-subheader ' . esc_attr__( $css_subheader_class ) . '">' . wp_kses_post( $settings['header_content_subheader'] ). '</span>';
		    }

		    /** Display the header. */
		    $css_header_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $settings['style_header_editor'], '' ), 'mdp_wpb_lottier', $atts );
		    echo '<span class="mdp-lottier-header ' . esc_attr__( $css_header_class ) . '">' . wp_kses_post( $settings['header_content_type'] ) . '</span>';

		    /** Display the subheader. */
		    if( $settings['header_subheader_position'] === 'bottom' ){
			    echo '<span class="mdp-lottier-subheader ' . esc_attr__( $css_subheader_class ) . '">' . wp_kses_post( $settings['header_content_subheader'] ) . '</span>';
		    }

		    echo '</' . esc_attr( $settings['header_html_tag'] ) . '>';

	    }

	    /** We display a brief description if you want to display it after the title. */
	    $css_description_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $settings['style_description_css'], '' ), 'mdp_wpb_lottier', $atts );
	    if ( $settings['description_position'] === 'header' && $settings['properties_description'] === 'yes' ) {
		    echo '<' . esc_attr( $settings['description_html_tag'] ) . ' class="mdp-lottier-description ' . esc_attr__($css_description_class) . '" >' . wp_kses_post( $settings['description_text'] ) . '</' . esc_attr( $settings['description_html_tag'] ) . '>';
	    }

        if( $settings['properties_enable_link'] === 'yes' && $settings['link_position'] === 'svg' ):
            $link = $this->get_link_attributes( $settings[ 'link_url' ] );
            echo wp_sprintf(
                '<a href="%s"%s%s%s>',
	            isset( $link[ 'url' ] ) ? $link[ 'url' ] : '#',
	            isset( $link[ 'target' ] ) ? ' target="' . $link[ 'target' ] . '"' : '',
	            isset( $link[ 'title' ] ) ? ' title="' . $link[ 'title' ] . '"' : '',
                isset( $link[ 'rel' ] ) ? ' rel="' . $link[ 'rel' ] . '"' : ''
            );
        endif;
        ?>

	    <div class="mdp-lottier-svg">
		    <div class="mdp-lottier-player"
                 data-id="<?php echo esc_attr($id); ?>"
                 data-finish-before-pause="<?php echo esc_attr( $settings['properties_finish_before_pause'] )?>"
		         data-autoplay="<?php echo esc_attr( $settings['properties_playback'] ); ?>"
		         data-speed="<?php echo esc_attr( $settings['properties_animation_speed'] ); ?>"
		         data-loop="<?php echo $settings['properties_loop'] === 'yes' ? 'true' : 'false'; ?>"
		         data-controls="<?php echo $settings['properties_controls'] === 'yes' ? 'true' : 'false'; ?>"
		         data-mode="<?php echo esc_attr( $settings['properties_play_mode'] ); ?>"
		    >
			    <!--suppress HtmlUnknownTag -->
			    <?php $css_animation_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
                                                            vc_shortcode_custom_css_class( $settings['style_animation_css'], '' ),
                                                            'mdp_wpb_lottier', $atts );

                if( $settings['properties_type_player'] == 'web_player' ): ?>
                    <lottie-player id="mdp-lottier-<?php echo esc_attr($id); ?>"
                                   class="mdp-lottier-animation <?php echo esc_attr( $css_animation_class ); ?>"
                                   src='<?php echo esc_attr($src); ?>'></lottie-player>
                <?php elseif ( $settings['properties_type_player'] == 'dot_lottie' ): ?>
                    <dotlottie-player id="mdp-lottier-<?php echo esc_attr($id); ?>"
                                      class="mdp-lottier-animation <?php echo esc_attr( $css_animation_class ); ?>"
                                      src='<?php echo esc_attr($src_dot); ?>'></dotlottie-player>
                <?php endif; ?>
		    </div>
	    </div>

	    <?php if( $settings['properties_enable_link'] === 'yes' && $settings['link_position'] === 'svg' ): ?>
		    </a>
	    <?php endif; ?>

	    <?php
	    /** We display a short description if you want to display it at the end of the section. */
	    if ( $settings['description_position'] === 'footer' && $settings['properties_description'] === 'yes' ) {
		    echo '<' . esc_attr( $settings['description_html_tag'] ) . ' class="mdp-lottier-description ' . esc_attr__($css_description_class) . '">' . wp_kses_post( $settings['description_text'] ) . '</' . esc_attr( $settings['description_html_tag'] ) . '>';
	    }

	    if( $settings['properties_enable_link'] === 'yes' && $settings['link_position'] === 'box' ):
		    $link = $this->get_link_attributes( $settings[ 'link_url' ] );
		    echo wp_sprintf(
			    '<a href="%s" class="mdp-link-max" %s%s%s>',
			    isset( $link[ 'url' ] ) ? $link[ 'url' ] : '#',
			    isset( $link[ 'target' ] ) ? ' target="' . $link[ 'target' ] . '"' : '',
                isset( $link[ 'title' ] ) ? ' title="' . $link[ 'title' ] . '"' : '',
                isset( $link[ 'rel' ] ) ? ' rel="' . $link[ 'rel' ] . '"' : ''
		    );
	    endif; ?>
	    </div>
	    <!-- End Lottier for WPBakery WordPress Plugin -->

	    <?php
	    return ob_get_clean();

    }

    /**
     * Lottier addon map.
     *
     * @throws Exception
     **/
    public function params_map() {

	    /** Check, just in case. */
	    if ( ! function_exists( 'vc_map' ) ) { return; }

	    /** Control params. */
	    $params = [];

	    /***********************
	     * Properties Content Tab. *
	     ***********************/
	    if ( true ) {

            $params[] = [
                'param_name'       => 'properties_type_player',
                'type'             => 'dropdown',
                'heading'          => esc_html__( 'Type lottie player', 'lottier-wpbakery' ),
                'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
                'value'            => [
                    esc_html__( 'Web player', 'lottier-wpbakery' ) => 'web_player',
                    esc_html__( 'dotLottie',  'lottier-wpbakery' ) => 'dot_lottie'
                ],
                'edit_field_class' => 'mdp-control vc_col-xs-12',
            ];

		    /** Animation speed. */
		    $params[] = [
			    'param_name'       => 'properties_animation_speed',
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Animation speed', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'value'            => 1,
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Playback. */
		    $params[] = [
			    'param_name'       => 'properties_playback',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Playback', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'Auto play', 'lottier-wpbakery' )     => 'autoplayne',
				    esc_html__( 'SVG hover', 'lottier-wpbakery' )     => 'hover',
				    esc_html__( 'Section hover', 'lottier-wpbakery' ) => 'section',
				    esc_html__( 'On click', 'lottier-wpbakery' )      => 'click',
				    esc_html__( 'Player visible', 'lottier-wpbakery' ) => 'visible',
				    esc_html__( 'Scrolling forward', 'lottier-wpbakery' ) => 'scroll_forward',
				    esc_html__( 'Scrolling backward', 'lottier-wpbakery' ) => 'scroll_backward',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Play mode. */
		    $params[] = [
			    'param_name'       => 'properties_play_mode',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Play mode', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'Normal', 'lottier-wpbakery' ) => 'normal',
				    esc_html__( 'Bounce', 'lottier-wpbakery' ) => 'bounce',

			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_loop',
				    'value'   => 'yes'
			    ],
		    ];

            /** Controls. */
            $params[] = [
                'param_name'       => 'properties_finish_before_pause',
                "type"             => "checkbox",
                "heading"          => esc_html__( "Finish animation before pause", "lottier-wpbakery" ),
                'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
                'std'              => '',
                "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
                'dependency'       => [
                    'element' => 'properties_playback',
                    'value'   => ['hover', 'section']
                ],
                'edit_field_class' => 'mdp-control vc_col-xs-12',
            ];

		    /** Loop. */
		    $params[] = [
			    'param_name'       => 'properties_loop',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Loop", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'std'              => 'yes',
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Controls. */
		    $params[] = [
			    'param_name'       => 'properties_controls',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Controls", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'std'              => '',
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Header. */
		    $params[] = [
			    'param_name'       => 'properties_header',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Header", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'std'              => '',
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Description. */
		    $params[] = [
			    'param_name'       => 'properties_description',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Description", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'std'              => '',
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Enable link. */
		    $params[] = [
			    'param_name'       => 'properties_enable_link',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Enable link", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Properties', 'lottier-wpbakery' ),
			    'std'              => '',
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

	    } // END Properties Content Tab.

	    /*********************
	     * Animation Content Tab. *
	     *********************/
	    if ( true ) {

		    /** URL */
		    $params[] = [
			    'param_name'       => 'animation_url',
			    "type"             => "vc_link",
			    'heading'          => esc_html__( 'Link to file json', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Animation', 'lottier-wpbakery' ),
			    "value"            => 'https://assets8.lottiefiles.com/packages/lf20_Y5wOyL.json',
			    "description"      => esc_html__( 'Choose animation on ', 'lottier-wpbakery' ).
			                          '<a href="https://lottiefiles.com/" target="_blank">(lottiefiles.com)</a>' .
			                          esc_html__( ' and import it as JSON.', 'lottier-wpbakery' ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
                'dependency'       => [
                    'element' => 'properties_type_player',
                    'value'   => 'web_player'
                ]
		    ];

            $params[] = [
                'param_name'       => 'animation_url_dot',
                "type"             => "vc_link",
                'heading'          => esc_html__( 'Link to file lottie', 'lottier-wpbakery' ),
                'group'            => esc_html__( 'Animation', 'lottier-wpbakery' ),
                "value"            => 'https://lottie.host/7e57ee10-5709-4317-bc47-fbe0e4bbd1d5/gBVwscFNXX.lottie',
                "description"      => esc_html__( 'Choose animation on ', 'lottier-wpbakery' ).
                    '<a href="https://lottiefiles.com/" target="_blank">(lottiefiles.com)</a>' .
                    esc_html__( ' and import it as lottie.', 'lottier-wpbakery' ),
                'edit_field_class' => 'mdp-control vc_col-xs-12',
                'dependency'       => [
                    'element' => 'properties_type_player',
                    'value'   => 'dot_lottie'
                ]
            ];

	    } // END Animation Content Tab.

	    /***********************
	     * Header Content Tab. *
	     ***********************/
	    if ( true ) {

		    /** Header. */
		    $params[] = [
			    'param_name'       => "header_content_type",
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Header', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Header', 'lottier-wpbakery' ),
			    'value'            => esc_html__( 'Auto play', 'lottier-wpbakery' ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ]
		    ];

		    /** HTML Tag. */
		    $params[] = [
			    'param_name'       => 'header_html_tag',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'HTML Tag', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Header', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'H1', 'lottier-wpbakery' )   => 'h1',
				    esc_html__( 'H2', 'lottier-wpbakery' )   => 'h2',
				    esc_html__( 'H3', 'lottier-wpbakery' )   => 'h3',
				    esc_html__( 'H4', 'lottier-wpbakery' )   => 'h4',
				    esc_html__( 'H5', 'lottier-wpbakery' )   => 'h5',
				    esc_html__( 'H6', 'lottier-wpbakery' )   => 'h6',
				    esc_html__( 'div', 'lottier-wpbakery' )  => 'div',
				    esc_html__( 'span', 'lottier-wpbakery' ) => 'span',
				    esc_html__( 'p', 'lottier-wpbakery' )    => 'p',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Subheader Position. */
		    $params[] = [
			    'param_name'       => 'header_subheader_position',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Subheader Position', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Header', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'Top', 'lottier-wpbakery' )    => 'top',
				    esc_html__( 'Bottom', 'lottier-wpbakery' ) => 'bottom',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Subheader. */
		    $params[] = [
			    'param_name'       => "header_content_subheader",
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Subheader', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Header', 'lottier-wpbakery' ),
			    'value'            => esc_html__( 'Subheader', 'lottier-wpbakery' ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

	    } // END Header Content Tab.

	    /****************************
	     * Description Content Tab. *
	     ****************************/
	    if ( true ) {

		    /** Description Position. */
		    $params[] = [
			    'param_name'       => 'description_position',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Description Position', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Description', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'After header', 'lottier-wpbakery' ) => 'header',
				    esc_html__( 'Footer', 'lottier-wpbakery' )       => 'footer',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

		    /** HTML Tag. */
		    $params[] = [
			    'param_name'       => 'description_html_tag',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'HTML Tag', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Description', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'div', 'lottier-wpbakery' )  => 'div',
				    esc_html__( 'span', 'lottier-wpbakery' ) => 'span',
				    esc_html__( 'p', 'lottier-wpbakery' )    => 'p',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Description text. */
		    $params[] = [
			    'param_name'       => 'description_text',
			    "type"             => "textarea",
			    "class"            => "",
			    "heading"          => esc_html__( "Description", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Description', 'lottier-wpbakery' ),
			    "value"            => esc_html__( "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "lottier-wpbakery" ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

	    }

	    /***********************
	     * Link Content Tab. *
	     ***********************/
	    if ( true ) {

		    /** Link Position */
		    $params[] = [
			    'param_name'       => 'link_position',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Link Position', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Link', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'SVG', 'lottier-wpbakery' ) => 'svg',
				    esc_html__( 'Box', 'lottier-wpbakery' ) => 'box',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_enable_link',
				    'value'   => 'yes'
			    ],
		    ];

		    /** URL */
		    $params[] = [
			    'param_name'       => 'link_url',
			    "type"             => "vc_link",
			    'group'            => esc_html__( 'Link', 'lottier-wpbakery' ),
			    "value"            => 'https://codecanyon.net/user/merkulove',
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_enable_link',
				    'value'   => 'yes'
			    ],
		    ];

	    } // END Link Content Tab.

	    /*****************************
	     * Style Header Tab. *
	     *****************************/
	    if ( true ) {

		    /** Style Header. */
		    $params[] = [
			    'param_name'       => 'style_header_editor',
			    'type'             => 'css_editor',
			    'heading'          => esc_html__( 'Style', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Header', 'lottier-wpbakery' ),
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Font Style. */
		    $params[] = [
			    'param_name'       => 'style_header_font',
			    'type'             => 'google_fonts',
			    'group'            => esc_html__( 'Style Header', 'lottier-wpbakery' ),
			    'value'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			    'settings'         => [
				    'fields' => [
					    'font_family_description' => esc_html__( 'Select font family.', 'lottier-wpbakery' ),
					    'font_style_description'  => esc_html__( 'Select font styling.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Header font container. */
		    $params[] = [
			    'param_name'       => 'style_header_font_container',
			    'type'             => 'font_container',
			    'group'            => esc_html__( 'Style Header', 'lottier-wpbakery' ),
			    'value'            => '',
			    'edit_field_class' => 'mdp-control vc_col-xs-12 mdp-control-small',
			    'settings'         => [
				    'fields' => [
					    'font_size',
					    'line_height',
					    'color',
					    'font_size_description'   => esc_html__( 'Enter font size.', 'lottier-wpbakery' ),
					    'line_height_description' => esc_html__( 'Enter line height.', 'lottier-wpbakery' ),
					    'color_description'       => esc_html__( 'Select heading color.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Alignment */
		    $params[] = [
			    'param_name'       => 'style_header_alignment',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Alignment', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Header', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'left', 'lottier-wpbakery' )    => 'left',
				    esc_html__( 'center', 'lottier-wpbakery' )  => 'center',
				    esc_html__( 'right', 'lottier-wpbakery' )   => 'right',
				    esc_html__( 'justify', 'lottier-wpbakery' ) => 'justify',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

	    } // END Style Header Tab.

	    /*****************************
	     * Style SubHeader Tab. *
	     *****************************/
	    if( true ){

		    /** Style SubHeader. */
		    $params[] = [
			    'param_name'       => 'style_subheader_editor',
			    'type'             => 'css_editor',
			    'heading'          => esc_html__( 'Style', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style SubHeader', 'lottier-wpbakery' ),
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Font Style. */
		    $params[] = [
			    'param_name'       => 'style_subheader_font',
			    'type'             => 'google_fonts',
			    'group'            => esc_html__( 'Style SubHeader', 'lottier-wpbakery' ),
			    'value'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			    'settings'         => [
				    'fields' => [
					    'font_family_description' => esc_html__( 'Select font family.', 'lottier-wpbakery' ),
					    'font_style_description'  => esc_html__( 'Select font styling.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Header font container. */
		    $params[] = [
			    'param_name'       => 'style_subheader_font_container',
			    'type'             => 'font_container',
			    'group'            => esc_html__( 'Style SubHeader', 'lottier-wpbakery' ),
			    'value'            => '',
			    'edit_field_class' => 'mdp-control vc_col-xs-12 mdp-control-small',
			    'settings'         => [
				    'fields' => [
					    'font_size',
					    'line_height',
					    'color',
					    'font_size_description'   => esc_html__( 'Enter font size.', 'lottier-wpbakery' ),
					    'line_height_description' => esc_html__( 'Enter line height.', 'lottier-wpbakery' ),
					    'color_description'       => esc_html__( 'Select heading color.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Alignment */
		    $params[] = [
			    'param_name'       => 'style_subheader_alignment',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Alignment', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style SubHeader', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'left', 'lottier-wpbakery' )    => 'left',
				    esc_html__( 'center', 'lottier-wpbakery' )  => 'center',
				    esc_html__( 'right', 'lottier-wpbakery' )   => 'right',
				    esc_html__( 'justify', 'lottier-wpbakery' ) => 'justify',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_header',
				    'value'   => 'yes'
			    ],
		    ];
	    }

	    /*********************
	     * Style Description Tab. *
	     *********************/
	    if ( true ) {

		    /** Description css Style. */
		    $params[] = [
			    'param_name'       => 'style_description_css',
			    'type'             => 'css_editor',
			    'heading'          => esc_html__( 'Style', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Description', 'lottier-wpbakery' ),
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Font Style. */
		    $params[] = [
			    'param_name'       => 'style_description_font',
			    'type'             => 'google_fonts',
			    'group'            => esc_html__( 'Style Description', 'lottier-wpbakery' ),
			    'value'            => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			    'settings'         => [
				    'fields' => [
					    'font_family_description' => esc_html__( 'Select font family.', 'lottier-wpbakery' ),
					    'font_style_description'  => esc_html__( 'Select font styling.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Description font container. */
		    $params[] = [
			    'param_name'       => 'style_description_font_container',
			    'type'             => 'font_container',
			    'group'            => esc_html__( 'Style Description', 'lottier-wpbakery' ),
			    'value'            => '',
			    'edit_field_class' => 'mdp-control vc_col-xs-12 mdp-control-small',
			    'settings'         => [
				    'fields' => [
					    'font_size',
					    'line_height',
					    'color',
					    'font_size_description'   => esc_html__( 'Enter font size.', 'lottier-wpbakery' ),
					    'line_height_description' => esc_html__( 'Enter line height.', 'lottier-wpbakery' ),
					    'color_description'       => esc_html__( 'Select heading color.', 'lottier-wpbakery' ),
				    ],
			    ],
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Alignment */
		    $params[] = [
			    'param_name'       => 'style_description_alignment',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Alignment', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Description', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'left', 'lottier-wpbakery' )    => 'left',
				    esc_html__( 'center', 'lottier-wpbakery' )  => 'center',
				    esc_html__( 'right', 'lottier-wpbakery' )   => 'right',
				    esc_html__( 'justify', 'lottier-wpbakery' ) => 'justify',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'properties_description',
				    'value'   => 'yes'
			    ],
		    ];

	    } // END Style Description Tab.

	    /*********************
	     * Style Animation Tab. *
	     *********************/
	    if ( true ) {

		    /** Animation css Style. */
		    $params[] = [
			    'param_name'       => 'style_animation_css',
			    'type'             => 'css_editor',
			    'heading'          => esc_html__( 'Style', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
		    ];

		    /** Width. */
		    $params[] = [
			    'param_name'       => "style_animation_width",
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Width', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
			    'value'            => esc_html__( '100%', 'lottier-wpbakery' ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Custom height. */
		    $params[] = [
			    'param_name'       => 'style_animation_custom_height',
			    "type"             => "checkbox",
			    "heading"          => esc_html__( "Custom height", "lottier-wpbakery" ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
			    "value"            => [ esc_html__( 'Yes', 'lottier-wpbakery' ) => 'yes' ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

		    /** Height. */
		    $params[] = [
			    'param_name'       => "style_animation_height",
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Height', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
			    'value'            => esc_html__( '100px', 'lottier-wpbakery' ),
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
			    'dependency'       => [
				    'element' => 'style_animation_custom_height',
				    'value'   => 'yes'
			    ],
		    ];

		    /** Alignment */
		    $params[] = [
			    'param_name'       => 'style_animation_alignment',
			    'type'             => 'dropdown',
			    'heading'          => esc_html__( 'Alignment', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
			    'value'            => [
				    esc_html__( 'left', 'lottier-wpbakery' )    => 'start',
				    esc_html__( 'center', 'lottier-wpbakery' )  => 'center',
				    esc_html__( 'right', 'lottier-wpbakery' )   => 'end',
			    ],
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

            /** Custom CSS class for lottier-box */
		    $params[] = [
			    'param_name'       => 'style_animation_class',
			    'type'             => 'textfield',
			    'heading'          => esc_html__( 'Custom CSS class for lottier-box element', 'lottier-wpbakery' ),
                'description'      => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'lottier-wpbakery' ),
			    'group'            => esc_html__( 'Style Animation', 'lottier-wpbakery' ),
			    'value'            => '',
			    'edit_field_class' => 'mdp-control vc_col-xs-12',
		    ];

	    }// END Style Animation Tab.

	    /** Add [mdp_wpb_lottier ] shortcode to the WPBakery Page Builder. */
	    vc_map( [
		    'name'                    => esc_html__( 'Lottier', 'lottier-wpbakery' ),
		    'description'             => esc_html__( 'Lottie animations in just a few clicks without writing a single line of code', 'lottier-wpbakery' ),
		    'base'                    => 'mdp_wpb_lottier',
		    'icon'                    => 'icon-mdp-lottier-wpbakery',
		    'category'                => esc_html__( 'Content', 'lottier-wpbakery' ),
		    'show_settings_on_create' => true,
		    'params'                  => $params,
	    ] );

    }

	/**
	 * We get a link to the file, without other settings.
	 *
	 * @param $str - The string that contains the link.
	 *
	 * @return string
	 * @since 1.0.0
	 * @access public
	 */
	public function get_link( $str ){

		$result = [];

		$delete_url_attribute = str_replace(['url:', '|'], '', $str );
		$change_symbol = str_replace('%2F', '/',$delete_url_attribute );
		$change_point = str_replace('%3A', ':', $change_symbol );

		$attribute = ['title', 'target', 'nofollow', 'rel'];
		foreach ($attribute as $val){
			$result[] = explode( $val, $change_point);
		}

		return $result[0][0];
	}

	/**
	 * Parse wpb link settings and get attributes
	 * @param $set
	 *
	 * @return array
	 */
    private function get_link_attributes( $set ) {

        $link = array();

        foreach( explode( '|', $set ) as $attribute ) {

            if ( strpos( $attribute,'url:' ) === 0 ) {
	            $link[ 'url' ] = str_replace( 'url:', '', $attribute );
	            $link[ 'url' ] = str_replace('%2F', '/', $link[ 'url' ] );
	            $link[ 'url' ] = str_replace('%3A', ':', $link[ 'url' ] );
	            $link[ 'url' ] = str_replace('%23', '#', $link[ 'url' ] );
            }

	        if ( strpos( $attribute, 'title:' ) === 0 ) {
		        $link[ 'title' ] = str_replace( 'title:', '', $attribute );
	        }

	        if ( strpos( $attribute, 'target:' ) === 0 ) {
		        $link[ 'target' ] = str_replace( 'target:', '', $attribute );
	        }

	        if ( strpos( $attribute, 'rel:' ) === 0 ) {
		        $link[ 'rel' ] = str_replace( 'rel:', '', $attribute );
	        }

        }

        return $link;

    }

	/**
	 * Enqueue custom Google Font.
	 *
	 * @param string $font_family - Google font family.
	 *
	 * @since  1.0.0
	 * @access public
	 **/
	private function enqueue_google_font( $font_family ) {

		/** Get extra subsets for settings (latin/cyrillic/etc). */
		$settings = get_option( 'wpb_js_google_fonts_subsets' );

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			$subsets = '&subset=' . implode( ',', $settings );
		} else {
			$subsets = '';
		}

		/** Enqueue font from googleapis. */
		if ( ! empty( $font_family ) ) {
			wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $font_family ), '//fonts.googleapis.com/css?family=' . $font_family . $subsets );
		}

	}

	/**
	 * Return inline css style for google_font field.
	 *
	 * @param string $param - value of google_font field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string - css font styles.
	 **/
	private function get_google_font_inline_css( $param ) {

		$res = '';

		/** Decode. */
		$param = urldecode( $param );

		/** Split. */
		$tmp = explode( '|', $param );

		/** Combine to array. */
		$google_font['font_family'] = str_replace( 'font_family:', '', $tmp[0] );
		$google_font['font_style'] = str_replace( 'font_style:', '', $tmp[1] );

		/** Enqueue custom Google Font. */
		$this->enqueue_google_font( $google_font['font_family'] );

		/** Generate CSS. */
		$font_family = explode( ':', $google_font['font_family'] );
		$res .= 'font-family:' . $font_family[0] . ';';

		$font_styles = explode( ':', $google_font['font_style'] );
		$res .= 'font-weight:' . $font_styles[1] . ';';
		$res .= 'font-style:' . $font_styles[2] . ';';

		return $res;
	}

}
/** Run Lottier addon. */
new wpbLottier();
