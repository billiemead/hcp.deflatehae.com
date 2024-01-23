<?php
if ( ! class_exists( 'EEWPB_Gradient_Backgrounds' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Gradient_Backgrounds {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $column_args;

		/**
		 * Container counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $container_counter = 1;

		/**
		 * Column counter.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $column_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {

			// Pre-process the shortcodes.
			add_action( 'init', array( $this, 'update_shortcode_function' ), 12 );

			// Generate gradient style from shortcode attributes.
			add_filter( 'elegant_gradient_backgrounds', array( $this, 'attr' ) );

			// Generate gradient style from shortcode attributes.
			add_filter( 'elegant_gradient_column_backgrounds', array( $this, 'column_attr' ) );

			// Generate background slider from shortcode attributes.
			add_filter( 'elegant_slider_background', array( $this, 'slider_background_attr' ) );

			// Generate background slider from shortcode attributes.
			add_filter( 'elegant_slider_background_column', array( $this, 'slider_background_column_attr' ) );
		}

		/**
		 * Enqueue script required for slider.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function add_slider_js() {
			wp_enqueue_script( 'infi-elegant-background-slider' );
		}

		/**
		 * Update shortcode for container and columns and register new one for elegant elements.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function update_shortcode_function() {
			add_filter( 'pre_do_shortcode_tag', array( $this, 'change_row_column_shortcode_output' ), 10, 4 );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param bool   $false Null.
		 * @param string $tag   Shortcode tag.
		 * @param array  $attr  Shortcode attributes.
		 * @param array  $m     Regular expression match array.
		 * @return string       Shortcode output.
		 */
		public function change_row_column_shortcode_output( $false, $tag, $attr, $m ) {
			global $shortcode_tags;

			if ( '' === $attr ) {
				$attr = array();
			}

			if ( 'vc_row' !== $tag && 'vc_column' !== $tag ) {
				return false;
			}

			$content            = isset( $m[5] ) ? $m[5] : null;
			$column_order_style = '';
			$sticky_css         = '';

			if ( 'vc_column' === $tag ) {

				$attr['gradient_type']             = ( isset( $attr['gradient_type'] ) ) ? $attr['gradient_type'] : 'vertical';
				$attr['gradient_direction']        = ( isset( $attr['gradient_direction'] ) ) ? $attr['gradient_direction'] : '0deg';
				$attr['elegant_background_scale']  = ( isset( $attr['elegant_background_scale'] ) ) ? $attr['elegant_background_scale'] : 'cover';
				$attr['elegant_transition_effect'] = ( isset( $attr['elegant_transition_effect'] ) ) ? $attr['elegant_transition_effect'] : 'fade';

				// Responsive column order.
				$column_order = ( isset( $attr['column_order'] ) ) ? $attr['column_order'] : '';

				// Check if is sticky.
				$is_sticky = ( isset( $attr['eewpb_sticky_section'] ) ) ? $attr['eewpb_sticky_section'] : 'no';

				if ( 'yes' === $is_sticky ) {
					$attr['el_class'] = ' elegant-sticky-section';

					$sticky_css .= '<style type="text/css">';
					$sticky_css .= '.gradient-column-' . $this->column_counter . '.elegant-sticky-section {';
					$sticky_css .= 'top:' . $attr['sticky_top_position'] . 'px;';
					$sticky_css .= '}</style>';
				}

				if ( '' !== $column_order ) {
					$column_orders       = explode( ',', $column_order );
					$column_order_style .= '--desktop-order:' . $column_orders[0] . ';';
					$column_order_style .= '--tablet-order:' . $column_orders[1] . ';';
					$column_order_style .= '--mobile-order:' . $column_orders[2] . ';';
				}

				if ( isset( $attr['enable_background_gradient'] ) && 'yes' === trim( $attr['enable_background_gradient'] ) ) {
					if ( isset( $attr['gradient_top_color'] ) && '' !== trim( $attr['gradient_top_color'] ) ) {
						$this->column_args    = $attr;
						$gradient_backgrounds = apply_filters( 'elegant_gradient_column_backgrounds', $this->column_args );
						$gradient_style       = '<style class="elegant-gradient-column" type="text/css">';
						$gradient_style      .= $gradient_backgrounds;
						$gradient_style      .= '</style>';
						$content              = $content . $gradient_style;
					}
				}

				if ( isset( $attr['enable_background_slider'] ) && 'yes' === trim( $attr['enable_background_slider'] ) ) {
					$this->add_slider_js();

					$this->column_args = $attr;
					$transition        = ( '' !== $this->column_args['elegant_transition_effect'] ) ? $this->column_args['elegant_transition_effect'] : 'fade';
					$image_scale       = $this->column_args['elegant_background_scale'];
					$slider_images     = apply_filters( 'elegant_slider_background_column', $this->column_args );
					$slider_html       = '<div class="elegant-column-background-slider" data-image-scale="' . $image_scale . '" data-transition="' . $transition . '" style="display:none;">' . wp_json_encode( $slider_images ) . '</div>';
					$content           = $content . $slider_html;
				}

				if ( isset( $attr['el_class'] ) && '' !== $attr['el_class'] ) {
					$attr['el_class'] = 'gradient-column-' . $this->column_counter . ' ' . $attr['el_class'];
				} else {
					$attr['el_class'] = 'gradient-column-' . $this->column_counter;
				}

				$this->column_counter++;
			} elseif ( 'vc_row' === $tag ) {
				if ( isset( $attr['row_visibility_status'] ) && '' !== $attr['row_visibility_status'] ) {
					$status     = $attr['row_visibility_status'];
					$is_visible = true;

					if ( 'published' === $status ) {
						$is_visible = true;
					} elseif ( 'draft' === $status ) {
						$is_visible = false;
					} else {
						// Check if the set time is passed and set the visibility.
						$time = ( isset( $attr['publishing_date'] ) && '' !== $attr['publishing_date'] ) ? $attr['publishing_date'] : false;

						if ( $time ) {
							// Set to show until or after.
							$set_time      = strtotime( $time );
							$type          = 'timestamp';
							$wp_local_time = current_time( $type );

							if ( $set_time ) {
								if ( 'until' === $status ) {
									$is_visible = $wp_local_time < $set_time;
								}

								if ( 'after' === $status ) {
									$is_visible = $wp_local_time > $set_time;
								}
							}
						}
					}

					if ( false === $is_visible ) {

						// If is author, can also see.
						if ( is_user_logged_in() && current_user_can( 'publish_posts' ) ) {
							if ( isset( $attr['el_class'] ) && '' !== $attr['el_class'] ) {
								$attr['el_class'] = 'elegant-draft-row ' . $attr['el_class'];
							} else {
								$attr['el_class'] = 'elegant-draft-row';
							}
						} else {
							return '';
						}
					}
				}

				$attr['gradient_type']             = ( isset( $attr['gradient_type'] ) ) ? $attr['gradient_type'] : 'vertical';
				$attr['gradient_direction']        = ( isset( $attr['gradient_direction'] ) ) ? $attr['gradient_direction'] : '0deg';
				$attr['elegant_background_scale']  = ( isset( $attr['elegant_background_scale'] ) ) ? $attr['elegant_background_scale'] : 'cover';
				$attr['elegant_transition_effect'] = ( isset( $attr['elegant_transition_effect'] ) ) ? $attr['elegant_transition_effect'] : 'fade';

				if ( isset( $attr['enable_background_gradient'] ) && 'yes' === trim( $attr['enable_background_gradient'] ) ) {
					if ( isset( $attr['gradient_top_color'] ) && '' !== trim( $attr['gradient_top_color'] ) ) {
						$this->args           = $attr;
						$gradient_backgrounds = apply_filters( 'elegant_gradient_backgrounds', $this->args );
						$gradient_style       = '<div class="elegant-gradient-row"></div>';
						$gradient_style      .= '<style type="text/css">';
						$gradient_style      .= $gradient_backgrounds;
						$gradient_style      .= '</style>';
						$content              = $content . $gradient_style;
					}
				}

				if ( isset( $attr['enable_background_slider'] ) && 'yes' === trim( $attr['enable_background_slider'] ) ) {
					$this->add_slider_js();

					$this->args    = $attr;
					$transition    = ( '' !== $this->args['elegant_transition_effect'] ) ? $this->args['elegant_transition_effect'] : 'fade';
					$image_scale   = $this->args['elegant_background_scale'];
					$slider_images = apply_filters( 'elegant_slider_background', $this->args );
					$slider_html   = '<div class="elegant-row-background-slider" data-image-scale="' . $image_scale . '" data-transition="' . $transition . '" style="display:none;">' . wp_json_encode( $slider_images ) . '</div>';
					$content       = $content . $slider_html;
				}

				if ( isset( $attr['enable_background_video'] ) && 'yes' === $attr['enable_background_video'] ) {
					$video_placeholder = ( isset( $attr['video_placeholder'] ) && '' !== $attr['video_placeholder'] ) ? wp_get_attachment_image_src( $attr['video_placeholder'], 'full' ) : '';
					$video_placeholder = ( isset( $video_placeholder[0] ) ) ? $video_placeholder[0] : '';
					$video_bg_render   = '<div class="elegant-video-bg self-hosted-video">';
					$video_bg_render  .= '<video poster="' . $video_placeholder . '" preload="auto" loop autoplay muted>';
					$video_bg_render  .= '<source src="' . $attr['elegant_background_video'] . '" type="video/mp4" />';
					$video_bg_render  .= '</video>';

					if ( isset( $attr['video_overlay_color'] ) && '' !== $attr['video_overlay_color'] ) {
						$video_bg_render .= '<div class="elegant-video-overlay" style="background-color: ' . $attr['video_overlay_color'] . ';"></div>';
					}

					$video_bg_render .= '</div>';
					$content          = $content . $video_bg_render;
				}

				if ( isset( $attr['el_class'] ) && '' !== $attr['el_class'] ) {
					$attr['el_class'] = 'gradient-container-' . $this->container_counter . ' ' . $attr['el_class'];
				} else {
					$attr['el_class'] = 'gradient-container-' . $this->container_counter;
				}

				$this->container_counter++;
			}

			$rendered_output = call_user_func( $shortcode_tags[ $tag ], $attr, $content, $tag );
			$rendered_output = apply_filters( 'do_shortcode_tag', $rendered_output, $tag, $attr, $m );

			if ( '' !== $column_order_style ) {
				$rendered_output = str_replace( 'class="gradient-column-', 'style="' . $column_order_style . '" class="gradient-column-', $rendered_output );
			}

			return $rendered_output . $sticky_css;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {
			$class = '.vc_row.wpb_row.gradient-container-' . $this->container_counter . ' .elegant-gradient-row:before';

			if ( ! isset( $this->args['gradient_top_color'] ) ) {
				return;
			}

			$gradient_top_color    = $this->args['gradient_top_color'];
			$gradient_bottom_color = $this->args['gradient_bottom_color'];
			$force_gradient        = ( isset( $this->args['gradient_force'] ) && 'yes' === $this->args['gradient_force'] ) ? '!important' : '';
			$gradient_direction    = ( 'vertical' == $this->args['gradient_type'] ) ? 'top' : $this->args['gradient_direction'];

			if ( 'top' == $gradient_direction ) {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			$gradient_style  = '';
			$gradient_style .= $class . ' {';
			$gradient_style .= 'content: ""; position: absolute; width: 100%; height: 100%; top: 0; left: 0;z-index: 0;';
			$gradient_style .= $gradient;
			$gradient_style .= '}';

			return $gradient_style;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function column_attr() {
			$class  = '.vc_row.wpb_row .wpb_column.gradient-column-' . $this->column_counter;
			$class .= ' .vc_column-inner:before';

			if ( ! isset( $this->column_args['gradient_top_color'] ) && '' !== $this->column_args['gradient_top_color'] ) {
				return;
			}

			$gradient_top_color    = $this->column_args['gradient_top_color'];
			$gradient_bottom_color = $this->column_args['gradient_bottom_color'];
			$force_gradient        = ( isset( $this->column_args['gradient_force'] ) && 'yes' === $this->column_args['gradient_force'] ) ? '!important' : '';
			$gradient_direction    = ( 'vertical' === $this->column_args['gradient_type'] ) ? 'top' : $this->column_args['gradient_direction'];

			if ( 'top' == $gradient_direction ) {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

			$gradient_style  = '';
			$gradient_style .= $class . ' {';
			$gradient_style .= 'content: ""; position: absolute; width: 100%; height: 100%; top: 0; left: 0;z-index:-1;';
			$gradient_style .= $gradient;
			$gradient_style .= '}';

			return $gradient_style;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function slider_background_attr() {

			if ( ! isset( $this->args['enable_background_slider'] ) ) {
				return;
			}

			$images = $this->args['image_ids'];
			$images = explode( ',', $images );

			if ( empty( $images ) ) {
				return;
			}

			$slider_images = array();

			foreach ( $images as $image_id ) {
				$image           = wp_get_attachment_image_src( $image_id, 'full' );
				$image_url       = $image[0];
				$image_url       = esc_url( $image_url );
				$slider_images[] = $image_url;
			}

			return $slider_images;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function slider_background_column_attr() {
			if ( ! isset( $this->column_args['enable_background_slider'] ) ) {
				return;
			}

			$images = $this->column_args['image_ids'];
			$images = explode( ',', $images );

			if ( empty( $images ) ) {
				return;
			}

			$slider_images = array();

			foreach ( $images as $image_id ) {
				$image           = wp_get_attachment_image_src( $image_id, 'full' );
				$image_url       = $image[0];
				$image_url       = esc_url( $image_url );
				$slider_images[] = $image_url;
			}

			return $slider_images;
		}
	}

	new EEWPB_Gradient_Backgrounds();
} // End if().

/**
 * Map shortcode for gradient_backgrounds.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_gradient_backgrounds() {

	if ( function_exists( 'vc_add_params' ) ) {
		// Add settings to container and column.
		$gradient_options = array(
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Background Gradient', 'elegant-elements' ),
				'param_name'  => 'ee_info',
				'description' => '',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable Background Gradient', 'elegant-elements' ),
				'param_name'  => 'enable_background_gradient',
				'std'         => 'no',
				'value'       => array(
					esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
					esc_attr__( 'No', 'elegant-elements' ) => 'no',
				),
				'description' => esc_attr__( 'Add awesome gradients for your column or container background.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Gradient Top Color', 'elegant-elements' ),
				'param_name'  => 'gradient_top_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the top color of the background gradient.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'dependency'  => array(
					'element' => 'enable_background_gradient',
					'value'   => array( 'yes' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Gradient Bottom Color', 'elegant-elements' ),
				'param_name'  => 'gradient_bottom_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the bottom color of the background gradient.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'dependency'  => array(
					'element' => 'enable_background_gradient',
					'value'   => array( 'yes' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
				'param_name'  => 'gradient_type',
				'std'         => 'vertical',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					esc_attr__( 'Vertical', 'elegant-elements' ) => 'vertical',
					esc_attr__( 'Horizontal', 'elegant-elements' ) => 'horizontal',
				),
				'dependency'  => array(
					'element' => 'enable_background_gradient',
					'value'   => array( 'yes' ),
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
				'param_name'  => 'gradient_direction',
				'std'         => '0deg',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					esc_attr__( 'Left to Right', 'elegant-elements' ) => '0deg',
					esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ) => '45deg',
					esc_attr__( 'Top - Left Angle', 'elegant-elements' ) => '-45deg',
				),
				'dependency'  => array(
					'element'            => 'gradient_type',
					'value_not_equal_to' => 'vertical',
				),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Force Gradient Apply', 'elegant-elements' ),
				'description' => esc_attr__( 'Would you like to force gradient background over your background color and image settings? <br/>This will use only gradient background for this element.<br/>If set to "No", css will be generated, but might not applied if background image or background color is set.', 'elegant-elements' ),
				'param_name'  => 'gradient_force',
				'std'         => 'yes',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
					esc_attr__( 'No', 'elegant-elements' ) => 'no',
				),
				'dependency'  => array(
					'element' => 'enable_background_gradient',
					'value'   => array( 'yes' ),
				),
			),

			// Background Sliders.
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Background Image Slider', 'elegant-elements' ),
				'param_name'  => 'ee_info_2',
				'description' => '',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable Background Image Slider', 'elegant-elements' ),
				'param_name'  => 'enable_background_slider',
				'std'         => 'no',
				'value'       => array(
					esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
					esc_attr__( 'No', 'elegant-elements' ) => 'no',
				),
				'description' => esc_attr__( 'Add stunning image slider as your column or container background.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'attach_images',
				'heading'     => esc_attr__( 'Background Slider Images', 'elegant-elements' ),
				'param_name'  => 'image_ids',
				'value'       => '',
				'dependency'  => array(
					'element' => 'enable_background_slider',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Upload images for background slider.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_attr__( 'Background Image Slider Effect', 'elegant-elements' ),
				'param_name'  => 'elegant_transition_effect',
				'std'         => 'fade',
				'value'       => array(
					esc_attr__( 'Random Effects', 'elegant-elements' ) => 'random',
					esc_attr__( 'Fade', 'elegant-elements' ) => 'fade',
					esc_attr__( 'Fade-in-Out', 'elegant-elements' ) => 'fade_in_out',
					esc_attr__( 'Push Left', 'elegant-elements' ) => 'push_left',
					esc_attr__( 'Push Right', 'elegant-elements' ) => 'push_right',
					esc_attr__( 'Push Up', 'elegant-elements' ) => 'push_up',
					esc_attr__( 'Push Down', 'elegant-elements' ) => 'push_down',
					esc_attr__( 'Cover Left', 'elegant-elements' ) => 'cover_left',
					esc_attr__( 'Cover Right', 'elegant-elements' ) => 'cover_right',
					esc_attr__( 'Cover Up', 'elegant-elements' ) => 'cover_up',
					esc_attr__( 'Cover Down', 'elegant-elements' ) => 'cover_down',
				),
				'dependency'  => array(
					'element' => 'enable_background_slider',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Select the slider effect. Random will use all effects with random order for each slide.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Background Image Scale', 'elegant-elements' ),
				'param_name'  => 'elegant_background_scale',
				'std'         => 'cover',
				'value'       => array(
					esc_attr__( 'Cover', 'elegant-elements' ) => 'cover',
					esc_attr__( 'Fit', 'elegant-elements' ) => 'fit',
					esc_attr__( 'Fill', 'elegant-elements' ) => 'fill',
				),
				'dependency'  => array(
					'element' => 'enable_background_slider',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the scaling mode.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Video Backgrounds', 'elegant-elements' ),
				'param_name'  => 'ee_info_video',
				'description' => '',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable Background Video', 'elegant-elements' ),
				'param_name'  => 'enable_background_video',
				'std'         => 'no',
				'value'       => array(
					esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
					esc_attr__( 'No', 'elegant-elements' ) => 'no',
				),
				'description' => esc_attr__( 'Add self hosted videos as your column or container background.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_file_upload',
				'heading'     => esc_attr__( 'Background Video File ( mp4 )', 'elegant-elements' ),
				'param_name'  => 'elegant_background_video',
				'value'       => '',
				'dependency'  => array(
					'element' => 'enable_background_video',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Upload video file for background video.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Video Overlay Color', 'elegant-elements' ),
				'param_name'  => 'video_overlay_color',
				'dependency'  => array(
					'element' => 'enable_background_video',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Controls the overlay color for the video background.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_attr__( 'Background Video Placeholder', 'elegant-elements' ),
				'param_name'  => 'video_placeholder',
				'dependency'  => array(
					'element' => 'enable_background_video',
					'value'   => array( 'yes' ),
				),
				'description' => esc_attr__( 'Upload image for background video placeholder.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
			),
		);

		// Add settings to column for header.
		$responsive_options = array(
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Header Builder Settings', 'elegant-elements' ),
				'param_name'  => 'ee_info',
				'description' => '',
				'group'       => esc_attr__( 'Responsive Options', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_devices',
				'heading'     => esc_attr__( 'Column Order', 'elegant-elements' ),
				'description' => esc_attr__( 'You can set the column order on different devices. To get the preview in frontend, please save changes and refresh the page.', 'elegant-elements' ) . '<p></p>',
				'param_name'  => 'column_order',
				'min'         => '0',
				'max'         => '10',
				'value'       => '',
				'group'       => esc_attr__( 'Responsive Options', 'elegant-elements' ),
			),
		);

		// Add visibility settings to row.
		$visibility_options = array(
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Row Publishing Settings', 'elegant-elements' ),
				'param_name'  => 'ee_info_row',
				'description' => '',
				'group'       => esc_attr__( 'Publishing', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Row Publishing Status', 'elegant-elements' ),
				'description' => '<p>' . esc_attr__( 'Controls the publishing status of the row. If draft is selected the row will only be visible to logged in users with the capability to publish posts. If publish until or publish after are selected the row will be in draft mode when not published and will be visible to logged in users with reduced opacity.', 'elegant-elements' ) . '</p>',
				'param_name'  => 'row_visibility_status',
				'std'         => 'published',
				'value'       => array(
					esc_attr__( 'Published', 'elegant-elements' )       => 'published',
					esc_attr__( 'Published Until', 'elegant-elements' ) => 'until',
					esc_attr__( 'Publish After', 'elegant-elements' )   => 'after',
					esc_attr__( 'Draft', 'elegant-elements' )           => 'draft',
				),
				'group'       => esc_attr__( 'Publishing', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Publishing Date and Time', 'elegant-elements' ),
				'param_name'  => 'publishing_date',
				'value'       => '12/24/2021 12:00:00',
				'admin_label' => true,
				'description' => __( 'Enter the due date. eg : 12/24/2016 12:00:00 => month/day/year hour:minute:second', 'elegant-elements' ),
				'group'       => esc_attr__( 'Publishing', 'elegant-elements' ),
				'dependency'  => array(
					'element' => 'row_visibility_status',
					'value'   => array( 'until', 'after' ),
				),
			),
		);

		// Add sticky settings.
		$sticky_options = array(
			array(
				'type'        => 'ee_info',
				'heading'     => esc_attr__( 'Sticky Settings', 'elegant-elements' ),
				'param_name'  => 'ee_info_sticky',
				'description' => '',
				'group'       => esc_attr__( 'Sticky', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_radio_button_set',
				'heading'     => esc_attr__( 'Enable Sticky', 'elegant-elements' ),
				'description' => '<p>' . esc_attr__( 'Set this column or row as sticky. You can set the custom top position.', 'elegant-elements' ) . '</p>',
				'param_name'  => 'eewpb_sticky_section',
				'std'         => 'no',
				'value'       => array(
					esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
					esc_attr__( 'No', 'elegant-elements' ) => 'no',
				),
				'group'       => esc_attr__( 'Sticky', 'elegant-elements' ),
			),
			array(
				'type'        => 'ee_range_slider',
				'heading'     => __( 'Top Position', 'elegant-elements' ),
				'description' => __( 'Select the top position for the sticky section to be active.', 'elegant-elements' ),
				'param_name'  => 'sticky_top_position',
				'value'       => '50',
				'min'         => '0',
				'max'         => '1000',
				'step'        => '1',
				'admin_label' => true,
				'group'       => esc_attr__( 'Sticky', 'elegant-elements' ),
				'dependency'  => array(
					'element' => 'eewpb_sticky_section',
					'value'   => array( 'yes' ),
				),
			),
		);

		vc_add_params( 'vc_column', $gradient_options );
		vc_add_params( 'vc_column', $responsive_options );
		vc_add_params( 'vc_column', $sticky_options );
		vc_add_params( 'vc_row', $gradient_options );
		vc_add_params( 'vc_row', $visibility_options );
	}
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_gradient_backgrounds', 99 );
