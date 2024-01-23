<?php
if ( ! class_exists( 'EEWPB_Forms' ) && elegant_is_element_enabled( 'iee_forms' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class EEWPB_Forms {

		/**
		 * An array of the form fields.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $form_fields;

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Form counter.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $form_counter = 1;

		/**
		 * The recaptcha class instance
		 *
		 * @access public
		 * @var bool|object
		 */
		public $re_captcha = false;

		/**
		 * Do we have an error? (bool)
		 *
		 * @access public
		 * @var bool
		 */
		public $has_error = false;

		/**
		 * Whats the error?
		 *
		 * @access public
		 * @var string
		 */
		public $captcha_error = '';

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'iee_forms', array( $this, 'render' ) );

			// Handle ajax request to send form to email.
			add_action( 'wp_ajax_elegant_form_submit_to_email', array( $this, 'submit_to_email' ) );
			add_action( 'wp_ajax_nopriv_elegant_form_submit_to_email', array( $this, 'submit_to_email' ) );

			// Handle ajax request to send form to url.
			add_action( 'wp_ajax_elegant_form_submit_to_url', array( $this, 'submit_to_url' ) );
			add_action( 'wp_ajax_nopriv_elegant_form_submit_to_url', array( $this, 'submit_to_url' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $eewpb_js_folder_url;

			$settings = get_option( 'elegant_elements_wpbakery_settings', array() );

			if ( ! is_admin() && ( isset( $settings['recaptcha_public'] ) && '' !== $settings['recaptcha_public'] ) && ( isset( $settings['recaptcha_private'] ) && '' !== $settings['recaptcha_private'] ) ) {
				$this->init_recaptcha();
			}

			wp_enqueue_script( 'elegant-form-js', $eewpb_js_folder_url . '/infi-elegant-forms.min.js', array( 'jquery' ), EEWPB_VERSION, true );

			// Localize Scripts.
			wp_localize_script(
				'elegant-form-js',
				'elegantFormsConfig',
				array(
					'ajaxurl'            => admin_url( 'admin-ajax.php' ),
					'elegant_form_nonce' => wp_create_nonce( 'elegant_form_nonce' ),
					'post_id'            => get_the_ID(),
					'invalid_email'      => esc_attr__( 'The supplied email address is invalid.', 'elegant-elements' ),
					'textarea_limit'     => esc_attr__( 'Maximum 500 characters are allowed.', 'elegant-elements' ),
					'text_limit'         => esc_attr__( 'Maximum 120 characters are allowed.', 'elegant-elements' ),
				)
			);

			// Enqueue styles.
			if ( ! eewpb_is_combined_enqueue() ) {
				$this->add_styles();
			}

			$default_form_fields = rawurlencode(
				wp_json_encode(
					array(
						array(
							'label'      => esc_attr__( 'Name', 'elegant-elements' ),
							'field_type' => 'text',
							'required'   => 'no',
						),
						array(
							'label'      => esc_attr__( 'Email', 'elegant-elements' ),
							'field_type' => 'email',
							'required'   => 'no',
						),
						array(
							'label'      => esc_attr__( 'Message', 'elegant-elements' ),
							'field_type' => 'textarea',
							'required'   => 'no',
						),
					)
				)
			);

			$defaults = Elegant_Elements_WPBakery::set_shortcode_defaults(
				array(
					'form_name'                => '',
					'form_fields'              => $default_form_fields,
					'action'                   => get_permalink(),
					'data_attributes'          => array(),
					'elements'                 => array(),
					'email'                    => '',
					'email_from'               => '',
					'email_from_id'            => '',
					'email_subject'            => '',
					'error'                    => esc_attr__( 'Form could not be sent. Please check all fields.', 'elegant-elements' ),
					'field_height'             => 30,
					'field_border_size'        => '1',
					'field_border_radius'      => '0',
					'field_border_color'       => '#c7c7c7',
					'field_border_style'       => 'solid',
					'form_align'               => 'left',
					'form_background_color'    => '',
					'form_background_image'    => '',
					'form_background_position' => '',
					'form_background_repeat'   => '',
					'form_border_radius'       => '',
					'form_border_size'         => '',
					'form_border_color'        => '',
					'form_border_style'        => '',
					'form_class'               => '',
					'form_css_id'              => '',
					'form_id'                  => 'elegant-form-' . $this->form_counter,
					'form_number'              => $this->form_counter,
					'form_padding'             => '',
					'form_wrapper_class'       => '',
					'form_wrapper_id'          => '',
					'label_position'           => 'above',
					'margin_bottom'            => '',
					'margin_top'               => '',
					'method'                   => 'post',
					'placeholder'              => false,
					'recaptcha'                => 'no',
					'recaptcha_color_scheme'   => 'light',
					'send_function'            => array( $this, 'send_form' ),
					'submit_label'             => esc_attr__( 'Submit', 'elegant-elements' ),
					'success'                  => esc_attr__( 'Congratulations! Your data was sent securely.', 'elegant-elements' ),
					'type'                     => 'email',
					'inline_submit_button'     => 'no',
					'button_background_color'  => '#03A9F4',
					'button_text_color'        => '#ffffff',
					'step_nav_type'            => 'number',
					'step_nav_font_size'       => 16,
					'step_border_color'        => '#03A9F4',
					'step_link_color'          => '#03A9F4',
					'step_link_bg_color'       => '#E1F5FE',
					'step_active_color'        => '#03A9F4',
					'step_active_text_color'   => '#ffffff',
					'progress_complete_color'  => '#03A9F4',
					'progress_inactive_color'  => '#e3f6ff',
					'striped_bars'             => 'yes',
					'animated_stripe'          => 'yes',
					'step_button_align'        => 'left',
					'hide_on_mobile'           => elegant_elements_default_visibility( 'string' ),
					'class'                    => '',
					'id'                       => '',
				),
				$args
			);

			$this->args = $defaults;

			// Parse list item params.
			$this->form_fields = vc_param_group_parse_atts( $this->args['form_fields'] );

			$form_html = '';

			if ( '' !== locate_template( 'templates/forms/elegant-forms.php' ) ) {
				include locate_template( 'templates/forms/elegant-forms.php', false );
			} else {
				include EEWPB_PLUGIN_DIR . 'templates/forms/elegant-forms.php';
			}

			$this->form_counter++;

			return $form_html;
		}

		/**
		 * Setup ReCaptcha.
		 *
		 * @since 1.0
		 * @access private
		 * @return void
		 */
		private function init_recaptcha() {
			$settings = get_option( 'elegant_elements_wpbakery_settings', array() );

			if ( isset( $settings['recaptcha_public'] ) && isset( $settings['recaptcha_private'] ) && ! function_exists( 'recaptcha_get_html' ) ) {
				if ( version_compare( PHP_VERSION, '5.3' ) >= 0 && ! class_exists( 'ReCaptcha' ) ) {

					wp_enqueue_script( 'elegant-forms-recaptcha-api', 'https://www.google.com/recaptcha/api.js', array(), EEWPB_VERSION, false );

					require_once EEWPB_PLUGIN_DIR . '/inc/recaptcha/src/autoload.php';

					// We use a wrapper class to avoid fatal errors due to syntax differences on PHP 5.2.
					require_once EEWPB_PLUGIN_DIR . '/inc/recaptcha/class-elegant-form-recaptcha.php';

					// Instantiate ReCaptcha object.
					$re_captcha_wrapper = new ElegantForms_ReCaptcha( $settings['recaptcha_private'] );
					$this->re_captcha   = $re_captcha_wrapper->recaptcha;
				}
			}
		}

		/**
		 * Check recaptcha.
		 *
		 * @since 1.0
		 * @access private
		 * @param array $post form POST object.
		 * @return void
		 */
		private function process_recaptcha( $post ) {
			if ( $this->re_captcha ) {
				$re_captcha_response = null;

				// Was there a reCAPTCHA response?
				$post_recaptcha_response = ( isset( $post['g_recaptcha_response'] ) ) ? trim( wp_unslash( $post['g_recaptcha_response'] ) ) : '';

				// Check the reCaptcha post data.
				if ( '' === $post_recaptcha_response ) {
					$this->has_error     = true;
					$this->captcha_error = __( 'Please verify that you are not a robot.', 'elegant-elements' );
				} else {
					// @codingStandardsIgnoreLine
					$server_remote_addr = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? trim( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

					if ( $post_recaptcha_response && ! empty( $post_recaptcha_response ) ) {
						$re_captcha_response = $this->re_captcha->verify( $post_recaptcha_response, $server_remote_addr );
					}

					// Check the reCaptcha response.
					if ( null == $re_captcha_response || ! $re_captcha_response->isSuccess() ) {
						$this->has_error     = true;
						$this->captcha_error = __( 'Please verify that you are not a robot.', 'elegant-elements' );
					}
				}
			}
		}

		/**
		 * Form submission will be sent to url or Webhook.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function submit_to_url() {

			// Verify the form submission nonce.
			check_ajax_referer( 'elegant_form_nonce', 'elegant_form_nonce' );

			$file_errors  = '';
			$upload_error = '';
			$post         = $_POST;

			// Decode required data.
			$form_secure_data = base64_decode( $post['formSecureData'] ); // @codingStandardsIgnoreLine
			$form_secure_data = json_decode( $form_secure_data, true );

			// Remove the processed data.
			unset( $post['formSecureData'] );

			// Merge the processed data with original post object.
			$post = array_merge( $post, $form_secure_data );

			if ( isset( $form_secure_data['g_recaptcha_response'] ) ) {
				$this->init_recaptcha();
				$this->process_recaptcha( $form_secure_data );
				unset( $form_secure_data['g_recaptcha_response'] );
				unset( $post['g_recaptcha_response'] );
				if ( $this->has_error ) {
					$error   = $this->captcha_error;
					$message = $this->render_notices( $error, 'error' );
					$results = array(
						'status'  => 'error',
						'captcha' => 'failed',
						'message' => $message,
					);

					$this->has_error = false;
					die( wp_json_encode( $results ) );
				}
			}

			$form_data = $post['formData'];
			$files     = ( isset( $_FILES ) && ! empty( $_FILES ) ) ? $_FILES : array();
			$uploads   = ( ! empty( $files ) ) ? elegant_form_handle_upload( $files ) : array();

			$success      = $post['success'];
			$error        = $post['error'];
			$field_labels = $post['field_labels'];

			parse_str( $post['formData'], $form_data );

			// Remove data used for internal purpose.
			unset( $form_data['elegant_form_email'] );
			unset( $form_data['elegant_form_email_from'] );
			unset( $form_data['elegant_form_email_from_id'] );
			unset( $form_data['elegant_form_email_subject'] );
			unset( $form_data['g_recaptcha_response'] );

			if ( ! empty( $uploads ) && is_array( $uploads ) ) {
				if ( isset( $uploads['error'] ) ) {
					$file_errors = $uploads['error'];
					unset( $uploads['error'] );
				}
				if ( isset( $uploads['files'] ) ) {
					$form_data['files'] = $uploads['files'];
				}
			}

			if ( ! empty( $file_errors ) ) {
				$upload_error = '<ul>';
				foreach ( $file_errors as $file_name => $file_error ) {
					$upload_error .= '<li><strong>' . $file_name . '</strong> : ' . $file_error . '</li>';
				}
				$upload_error .= '</ul>';
			}

			$webhook_data = array();

			foreach ( $form_data as $field => $value ) {
				if ( 'files' === $field && ! empty( $value ) ) {
					$field_data = array();

					foreach ( $value as $file_name => $file_url ) {
						$field_data[] .= $file_url;
					}

					$value = $field_data;
				} else {
					$field_data = ( is_array( $value ) ) ? implode( ' | ', $value ) : $value;
				}

				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
				}

				$field_label = ( isset( $field_labels[ $field ] ) ) ? $field_labels[ $field ] : '';

				if ( '' === $field_label && 'files' === $field ) {
					$field_label = esc_attr__( 'Files', 'elegant-elements' );
				}

				$webhook_data[ $field_label ] = $value;
			}

			$submit_url = $post['url_to_submit'];

			$response = wp_remote_post(
				$submit_url,
				array(
					'body'    => $webhook_data,
					'headers' => array(
						'User-Agent' => 'WordPress - Elegant Elements',
					),
				)
			);

			$response_code    = wp_remote_retrieve_response_code( $response );
			$response_message = wp_remote_retrieve_response_message( $response );

			if ( 200 === $response_code ) {
				$message = $this->render_notices( $success, 'success' );
				$message = $upload_error . $message;
				$results = array(
					'status'  => 'success',
					'message' => $message,
				);
			} else {
				$message = $this->render_notices( $error, 'error' );
				$message = $upload_error . $message . $response_message;
				$results = array(
					'status'  => 'error',
					'message' => $message,
				);
			}

			die( wp_json_encode( $results ) );
		}

		/**
		 * Form submission will be sent to email.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		public function submit_to_email() {

			$file_errors = $upload_error = '';

			// Verify the form submission nonce.
			check_ajax_referer( 'elegant_form_nonce', 'elegant_form_nonce' );

			$post = $_POST;

			// Decode required data.
			$form_secure_data = base64_decode( $post['formSecureData'] ); // @codingStandardsIgnoreLine
			$form_secure_data = json_decode( $form_secure_data, true );

			// Remove the processed data.
			unset( $post['formSecureData'] );

			// Merge the processed data with original post object.
			$post = array_merge( $post, $form_secure_data );

			if ( isset( $form_secure_data['g_recaptcha_response'] ) ) {
				$this->init_recaptcha();
				$this->process_recaptcha( $form_secure_data );
				unset( $form_secure_data['g_recaptcha_response'] );
				unset( $post['g_recaptcha_response'] );
				if ( $this->has_error ) {
					$error   = $this->captcha_error;
					$message = $this->render_notices( $error, 'error' );
					$results = array(
						'status'  => 'error',
						'captcha' => 'failed',
						'message' => $message,
					);

					$this->has_error = false;
					die( wp_json_encode( $results ) );
				}
			}

			$form_data = $post['formData'];
			$files     = ( isset( $_FILES ) && ! empty( $_FILES ) ) ? $_FILES : array();
			$uploads   = ( ! empty( $files ) ) ? elegant_form_handle_upload( $files ) : array();

			$success      = $post['success'];
			$error        = $post['error'];
			$field_labels = $post['field_labels'];

			parse_str( $post['formData'], $form_data );
			$to        = $form_data['elegant_form_email'];
			$from_name = ( isset( $form_data['elegant_form_email_from'] ) && '' !== trim( $form_data['elegant_form_email_from'] ) ) ? $form_data['elegant_form_email_from'] : 'WordPress';
			$from_id   = ( isset( $form_data['elegant_form_email_from_id'] ) && '' !== trim( $form_data['elegant_form_email_from_id'] ) ) ? $form_data['elegant_form_email_from_id'] : 'wordpress@' . home_url();
			$subject   = ( isset( $form_data['elegant_form_email_subject'] ) && '' !== trim( $form_data['elegant_form_email_subject'] ) ) ? $form_data['elegant_form_email_subject'] : $post['form_id'] . ' ' . __( 'form submissions received!!!', 'elegant-elements' );

			// Remove data used for internal purpose.
			unset( $form_data['elegant_form_email'] );
			unset( $form_data['elegant_form_email_from'] );
			unset( $form_data['elegant_form_email_from_id'] );
			unset( $form_data['elegant_form_email_subject'] );
			unset( $form_data['g_recaptcha_response'] );

			if ( ! empty( $uploads ) && is_array( $uploads ) ) {
				if ( isset( $uploads['error'] ) ) {
					$file_errors = $uploads['error'];
					unset( $uploads['error'] );
				}
				if ( isset( $uploads['files'] ) ) {
					$form_data['files'] = $uploads['files'];
				}
			}

			if ( ! empty( $file_errors ) ) {
				$upload_error = '<ul>';
				foreach ( $file_errors as $file_name => $file_error ) {
					$upload_error .= '<li><strong>' . $file_name . '</strong> : ' . $file_error . '</li>';
				}
				$upload_error .= '</ul>';
			}

			$email_data = '';
			$row_count  = 1;
			foreach ( $form_data as $field => $value ) {
				if ( 'files' === $field && ! empty( $value ) ) {
					$field_data = '<ul>';
					foreach ( $value as $file_name => $file_url ) {
						$field_data .= '<li><strong><a href="' . $file_url . '" target="_blank">' . $file_name . '</a></strong></li>';
					}
					$field_data .= '</ul>';
					$value       = $field_data;
				} else {
					$field_data = ( is_array( $value ) ) ? implode( ' | ', $value ) : $value;
				}

				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
				}

				$field_label = ( isset( $field_labels[ $field ] ) ) ? $field_labels[ $field ] : '';

				if ( '' === $field_label && 'files' === $field ) {
					$field_label = esc_attr__( 'Files', 'elegant-elements' );
				}

				if ( '' !== $field_label ) {

					$style = ( $row_count % 2 ) ? 'style="background: #fbfbfb;"' : '';

					$email_data .= '<tr ' . $style . '>';
					$email_data .= '<th align="left" style="padding: 7px 15px 7px 10px;">' . $field_label . '</th>';
					$email_data .= '<td style="padding: 7px 15px 7px 10px;">' . $value . '</td>';
					$email_data .= '</tr>';

					$row_count++;
				}
			}

			$message = <<<CONTENT
<html>
<head>
<title>$subject</title>
</head>
<body>
<table cellspacing="4" cellpadding="4" align="left">
$email_data
</table>
</body>
</html>
CONTENT;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ' . $from_name . ' <' . $from_id . '>' . "\r\n";

			// Try sending email using WP native mail function.
			$sendmail = wp_mail( $to, $subject, $message, $headers );

			// If WP native mail function fails, try sending using PHP mail function.
			if ( ! $sendmail ) {
				$sendmail = mail( $to, $subject, $message, $headers );
			}

			if ( $sendmail ) {
				$message = $this->render_notices( $success, 'success' );
				$message = $upload_error . $message;
				$results = array(
					'status'  => 'success',
					'message' => $message,
				);
			} else {
				$message = $this->render_notices( $error, 'error' );
				$message = $upload_error . $message;
				$results = array(
					'status'  => 'error',
					'message' => $message,
				);
			}

			die( wp_json_encode( $results ) );
		}

		/**
		 * Renders form submission notices.
		 *
		 * @since 1.0
		 * @access private
		 * @param string $notice      The submission notice.
		 * @param string $notice_type Can be error|success.
		 * @return string The form submission notices.
		 */
		private function render_notices( $notice, $notice_type ) {

			// If form was not sent yet, $notice will be empty, so return early.
			if ( ! $notice ) {
				return '';
			}

			if ( 'success' === $notice_type ) {
				$icon  = 'fas fa-check-circle';
				$class = 'alert-success';
			} else {
				$icon  = 'fas fa-exclamation-triangle';
				$class = 'alert-danger';
			}

			$notice_html  = '<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-' . $class . '">';
			$notice_html .= '<div class="vc_message_box-icon"><i class="' . $icon . '"></i></div>';
			$notice_html .= '<p>' . $notice . '</p>';
			$notice_html .= '</div>';

			return apply_filters( 'elegant_form_notice', $notice_html, $notice_type );
		}

		/**
		 * Sets the necessary styles.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function add_styles() {
			wp_enqueue_style( 'infi-elegant-forms' );
		}
	}

	new EEWPB_Forms();
} // End if().

/**
 * Map shortcode for forms.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_wpbakery_forms() {

	elegant_elements_map(
		array(
			'name'      => esc_attr__( 'Elegant Forms', 'elegant-elements' ),
			'shortcode' => 'iee_forms',
			'icon'      => 'fab fa-wpforms forms-icon',
			'params'    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Form Name', 'elegant-elements' ),
					'description' => esc_attr__( 'The form name will be used to identify the forms when the data is submitted.', 'elegant-elements' ),
					'param_name'  => 'form_name',
					'admin_label' => true,
					'value'       => esc_attr__( 'Contact Us', 'elegant-elements' ),
				),
				array(
					'type'       => 'param_group',
					'param_name' => 'form_fields',
					'group'      => esc_attr__( 'Form Fields', 'elegant-elements' ),
					'value'      => rawurlencode(
						wp_json_encode(
							array(
								array(
									'label'      => esc_attr__( 'Name', 'elegant-elements' ),
									'field_type' => 'text',
									'required'   => 'no',
								),
								array(
									'label'      => esc_attr__( 'Email', 'elegant-elements' ),
									'field_type' => 'email',
									'required'   => 'no',
								),
								array(
									'label'      => esc_attr__( 'Message', 'elegant-elements' ),
									'field_type' => 'textarea',
									'required'   => 'no',
								),
							)
						)
					),
					'params'     => array(
						array(
							'type'        => 'dropdown',
							'heading'     => esc_attr__( 'Field Type', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the input field type.', 'elegant-elements' ),
							'param_name'  => 'field_type',
							'admin_label' => true,
							'value'       => array(
								esc_attr__( 'Text', 'elegant-elements' ) => 'text',
								esc_attr__( 'Password', 'elegant-elements' ) => 'password',
								esc_attr__( 'Textarea', 'elegant-elements' ) => 'textarea',
								esc_attr__( 'Number', 'elegant-elements' ) => 'number',
								esc_attr__( 'Email', 'elegant-elements' ) => 'email',
								esc_attr__( 'Tel', 'elegant-elements' ) => 'tel',
								esc_attr__( 'Dropdown', 'elegant-elements' ) => 'select',
								esc_attr__( 'Radio', 'elegant-elements' ) => 'radio',
								esc_attr__( 'Radio Image', 'elegant-elements' ) => 'radio_image',
								esc_attr__( 'Checkbox', 'elegant-elements' ) => 'checkbox',
								esc_attr__( 'Acceptance', 'elegant-elements' ) => 'acceptance',
								esc_attr__( 'File Upload', 'elegant-elements' ) => 'upload',
								esc_attr__( 'Range', 'elegant-elements' ) => 'range',
								esc_attr__( 'Date', 'elegant-elements' ) => 'date',
								esc_attr__( 'Time', 'elegant-elements' ) => 'time',
								esc_attr__( 'URL', 'elegant-elements' ) => 'url',
								esc_attr__( 'Hidden', 'elegant-elements' ) => 'hidden',
								esc_attr__( 'Step', 'elegant-elements' ) => 'form_step',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_attr__( 'Label', 'elegant-elements' ),
							'description' => esc_attr__( 'Enter text to be displayed as field label. Even if you don\'t want to display the label, this will be used when the form is submitted.', 'elegant-elements' ),
							'param_name'  => 'label',
							'admin_label' => true,
							'value'       => esc_attr__( 'Field Name', 'elegant-elements' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_attr__( 'Placeholder', 'elegant-elements' ),
							'description' => esc_attr__( 'Enter the placeholder text for this field. Leave empty for no placeholder.', 'elegant-elements' ),
							'param_name'  => 'placeholder',
							'value'       => esc_attr__( 'Field Name', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'text', 'password', 'textarea', 'email', 'tel', 'url' ),
							),
						),
						array(
							'type'        => 'exploded_textarea',
							'heading'     => esc_attr__( 'Options', 'elegant-elements' ),
							'description' => esc_attr__( 'Enter the options. One per line. To use different label and value, use | to seperate. eg. Option Name|optionvalue', 'elegant-elements' ),
							'param_name'  => 'options',
							'value'       => esc_attr__( 'Option 1,Option 2,Option 3', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'select', 'radio', 'checkbox' ),
							),
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Number of Rows', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the number of rows to be used for the textarea field.', 'elegant-elements' ),
							'param_name'  => 'rows',
							'std'         => '4',
							'value'       => '4',
							'min'         => '1',
							'max'         => '10',
							'step'        => '1',
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'textarea' ),
							),
						),
						array(
							'type'        => 'attach_images',
							'heading'     => esc_attr__( 'Images and Options', 'elegant-elements' ),
							'param_name'  => 'images',
							'value'       => '',
							'description' => esc_attr__( 'Upload images for radio buttons. Image file name will be used as an option.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Image Width', 'elegant-elements' ),
							'param_name'  => 'width',
							'std'         => '80',
							'value'       => '80',
							'min'         => '10',
							'max'         => '500',
							'step'        => '1',
							'description' => esc_attr__( 'In pixels (px).', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Image Height', 'elegant-elements' ),
							'param_name'  => 'height',
							'std'         => '80',
							'value'       => '80',
							'min'         => '10',
							'max'         => '500',
							'step'        => '1',
							'description' => esc_attr__( 'In pixels (px).', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'ee_radio_button_set',
							'heading'     => esc_attr__( 'Image Title as Option Label', 'elegant-elements' ),
							'description' => esc_attr__( 'Display the image title you set in the image meta as a label under the image.', 'elegant-elements' ),
							'param_name'  => 'title_as_label',
							'std'         => 'no',
							'value'       => array(
								esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
								esc_attr__( 'No', 'elegant-elements' ) => 'no',
							),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_attr__( 'Active Image Border and Label Background Color', 'elegant-elements' ),
							'param_name'  => 'active_color',
							'value'       => '',
							'description' => esc_attr__( 'Set border color for selected image and background color for the label.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_attr__( 'In-active Image Border and Label Background Color', 'elegant-elements' ),
							'param_name'  => 'in_active_color',
							'value'       => '',
							'description' => esc_attr__( 'Set border color for in-active image and background color for the label.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'radio_image' ),
							),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_attr__( 'In-active Image Label Text Color', 'elegant-elements' ),
							'param_name'  => 'in_active_label_color',
							'value'       => '',
							'description' => esc_attr__( 'Set text color for in-active image label.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'title_as_label',
								'value'   => array( 'yes' ),
							),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_attr__( 'Active Image Label Text Color', 'elegant-elements' ),
							'param_name'  => 'active_label_color',
							'value'       => '',
							'description' => esc_attr__( 'Set text color for active image label.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'title_as_label',
								'value'   => array( 'yes' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_attr__( 'Allowed File Types', 'elegant-elements' ),
							'param_name'  => 'allowed_file_types',
							'value'       => '',
							'description' => esc_attr__( 'Enter the allowed file types separated by comma. Eg. .pdf, .txt, .jpg.', 'elegant-elements' ),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'upload' ),
							),
						),
						array(
							'type'        => 'ee_radio_button_set',
							'heading'     => esc_attr__( 'Multiple Files', 'elegant-elements' ),
							'description' => esc_attr__( 'Do you want to allow users to upload multiple files?', 'elegant-elements' ),
							'param_name'  => 'allow_multiple',
							'std'         => 'no',
							'value'       => array(
								esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
								esc_attr__( 'No', 'elegant-elements' ) => 'no',
							),
							'dependency'  => array(
								'element' => 'field_type',
								'value'   => array( 'upload' ),
							),
						),
						array(
							'type'        => 'ee_radio_button_set',
							'heading'     => esc_attr__( 'Required Field?', 'elegant-elements' ),
							'description' => esc_attr__( 'Is this field a required for form submission?', 'elegant-elements' ),
							'param_name'  => 'required',
							'std'         => 'no',
							'value'       => array(
								esc_attr__( 'Yes', 'elegant-elements' ) => 'yes',
								esc_attr__( 'No', 'elegant-elements' ) => 'no',
							),
							'dependency'  => array(
								'element'            => 'field_type',
								'value_not_equal_to' => 'hidden',
							),
						),
						array(
							'type'        => 'ee_range_slider',
							'heading'     => esc_attr__( 'Column Width', 'elegant-elements' ),
							'description' => esc_attr__( 'Select the column width for this field. In percentage (%).', 'elegant-elements' ),
							'param_name'  => 'column_width',
							'std'         => '100',
							'value'       => '100',
							'min'         => '10',
							'max'         => '100',
							'step'        => '1',
						),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Submit Button Title', 'elegant-elements' ),
					'param_name'  => 'submit_label',
					'value'       => esc_attr__( 'Submit', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the form submit button title.', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Submission Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Select how you want the form should be submitted.', 'elegant-elements' ),
					'param_name'  => 'type',
					'default'     => 'email',
					'value'       => array(
						'email' => esc_attr__( 'Send to email', 'elegant-elements' ),
						'url'   => esc_attr__( 'Send to url or Webhook', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Form Submission URL', 'elegant-elements' ),
					'param_name'  => 'action',
					'value'       => '',
					'description' => esc_attr__( 'URL to send the form data.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'url' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Form Submission Email', 'elegant-elements' ),
					'param_name'  => 'email',
					'value'       => '',
					'description' => esc_attr__( 'Email ID to send the form data.', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'email' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Email Subject', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter text to be used as the email subject.', 'elegant-elements' ),
					'param_name'  => 'email_subject',
					'value'       => esc_attr__( 'Subscribe form submission received!!!', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'email' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Email From Name', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter name to be used as the From name in email.', 'elegant-elements' ),
					'param_name'  => 'email_from',
					'value'       => esc_attr__( 'My Blog', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'email' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Sender Email', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter email to be used as sender email.', 'elegant-elements' ),
					'param_name'  => 'email_from_id',
					'value'       => esc_attr__( 'wordpress@yoursite.com', 'elegant-elements' ),
					'dependency'  => array(
						'element' => 'type',
						'value'   => array( 'email' ),
					),
				),
				array(
					'type'        => 'ee_info',
					'heading'     => esc_attr__( 'Form Design', 'elegant-elements' ),
					'param_name'  => 'ee_info',
					'description' => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the background color for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_color',
					'default'     => '#f2f2f2',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_attr__( 'Form Background Image', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the image to be used as background image for the form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_image',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Form Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_position',
					'default'     => 'left top',
					'dependency'  => array(
						'element'   => 'form_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Form Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats for form area.', 'elegant-elements' ),
					'param_name'  => 'form_background_repeat',
					'default'     => 'no-repeat',
					'dependency'  => array(
						'element'   => 'form_background_image',
						'not_empty' => true,
					),
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Form Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select alignment for form and input fields.', 'elegant-elements' ),
					'param_name'  => 'form_align',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Form Area Padding', 'elegant-elements' ),
					'description' => esc_attr__( 'In pixels (px), ex: 10px.', 'elegant-elements' ),
					'param_name'  => 'form_padding',
					'value'       => '15px',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Form Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of the form. In pixels.', 'elegant-elements' ),
					'param_name'  => 'form_border_size',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color of the form.', 'elegant-elements' ),
					'param_name'  => 'form_border_color',
					'value'       => '#dddddd',
					'dependency'  => array(
						'element'            => 'form_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Form Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'form_border_style',
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
					'default'     => 'solid',
					'dependency'  => array(
						'element'            => 'form_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Form Background Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border radius for the form background. In pixels (px).', 'elegant-elements' ),
					'param_name'  => 'form_border_radius',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'value'       => '0',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Label Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Label position for the input field label. You can hide the labels for cleaner form design.', 'elegant-elements' ),
					'param_name'  => 'label_position',
					'default'     => 'above',
					'value'       => array(
						'above' => esc_attr__( 'Above', 'elegant-elements' ),
						'below' => esc_attr__( 'Below', 'elegant-elements' ),
						'hide'  => esc_attr__( 'Hide', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Success Message', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter message to be displayed after form submitted successfully.', 'elegant-elements' ),
					'param_name'  => 'success',
					'value'       => esc_attr__( 'Congratulations! Your data was sent securely.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Error Message', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter message to be displayed if form submission failed.', 'elegant-elements' ),
					'param_name'  => 'error',
					'value'       => esc_attr__( 'Form could not be sent. Please check all fields.', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_info',
					'heading'     => esc_attr__( 'Field Design', 'elegant-elements' ),
					'param_name'  => 'ee_info',
					'description' => '',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_dimensions',
					'heading'     => esc_attr__( 'Field Margin', 'elegant-elements' ),
					'param_name'  => 'field_margin',
					'value'       => array(
						'margin_top'    => '15px',
						'margin_bottom' => '15px',
					),
					'description' => esc_attr__( 'Controls the vertical height between form fields. In pixels (px), ex: 10px.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Field Height', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the height of the form fields. In pixels (px).', 'elegant-elements' ),
					'param_name'  => 'field_height',
					'min'         => '20',
					'max'         => '100',
					'step'        => '1',
					'value'       => '30',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Field Border Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border size of input field. In pixels.', 'elegant-elements' ),
					'param_name'  => 'field_border_size',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'value'       => '1',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Field Border Radius', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border radius of input field. In pixels.', 'elegant-elements' ),
					'param_name'  => 'field_border_radius',
					'min'         => '0',
					'max'         => '50',
					'step'        => '1',
					'value'       => '0',
					'dependency'  => array(
						'element'            => 'field_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Field Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color of the form row.', 'elegant-elements' ),
					'param_name'  => 'field_border_color',
					'value'       => '#c7c7c7',
					'dependency'  => array(
						'element'            => 'field_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Field Border Style', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border style.', 'elegant-elements' ),
					'param_name'  => 'field_border_style',
					'value'       => array(
						'solid'  => esc_attr__( 'Solid', 'elegant-elements' ),
						'dashed' => esc_attr__( 'Dashed', 'elegant-elements' ),
						'dotted' => esc_attr__( 'Dotted', 'elegant-elements' ),
					),
					'default'     => 'solid',
					'dependency'  => array(
						'element'            => 'field_border_size',
						'value_not_equal_to' => '0',
					),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_info',
					'heading'     => esc_attr__( 'Button Design', 'elegant-elements' ),
					'param_name'  => 'ee_info',
					'description' => 'Controls the button styles for submit button and the multi-step form buttons',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Display Submit Button Inline', 'elegant-elements' ),
					'description' => esc_attr__( 'Submit button will be added in the same line as the form is. Useful when you have a few fields and all are in the same line. Does not work with the the multistep form.', 'elegant-elements' ),
					'param_name'  => 'inline_submit_button',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'std'         => 'no',
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Text Color', 'elegant-elements' ),
					'param_name'  => 'button_text_color',
					'value'       => '#ffffff',
					'description' => esc_attr__( 'Set text color for the submit and next step button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Button Background Color', 'elegant-elements' ),
					'param_name'  => 'button_background_color',
					'value'       => '#03A9F4',
					'description' => esc_attr__( 'Set background color for the submit and next step button.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_attr__( 'Step Navigation Type', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how you want the steps navigation to be displayed.', 'elegant-elements' ),
					'param_name'  => 'step_nav_type',
					'value'       => array(
						'number'   => esc_attr__( 'Step Count', 'elegant-elements' ),
						'label'    => esc_attr__( 'Step Label', 'elegant-elements' ),
						'progress' => esc_attr__( 'Progress Bar', 'elegant-elements' ),
					),
					'default'     => 'number',
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Step Connector Border Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the border color of the form step connector line.', 'elegant-elements' ),
					'param_name'  => 'step_border_color',
					'value'       => '#03A9F4',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Step Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color of the form step number or label.', 'elegant-elements' ),
					'param_name'  => 'step_link_color',
					'value'       => '#03A9F4',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Form Step Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color of the form step number or label.', 'elegant-elements' ),
					'param_name'  => 'step_link_bg_color',
					'value'       => '#E1F5FE',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Step Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the background color of step number circle and label for active step.', 'elegant-elements' ),
					'param_name'  => 'step_active_color',
					'value'       => '#1172c1',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Active Step Text Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the text color of step number circle and label for active step.', 'elegant-elements' ),
					'param_name'  => 'step_active_text_color',
					'value'       => '#ffffff',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_range_slider',
					'heading'     => esc_attr__( 'Step Text Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the font size of the step navigation text. In pixels.', 'elegant-elements' ),
					'param_name'  => 'step_nav_font_size',
					'min'         => '10',
					'max'         => '100',
					'step'        => '1',
					'value'       => '16',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'number', 'label' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Progress Bar Completed Steps Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the progress bar background color of completed steps.', 'elegant-elements' ),
					'param_name'  => 'progress_complete_color',
					'value'       => '#03A9F4',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'progress' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Progress Bar Inactive Steps Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the progress bar background color of inactive steps.', 'elegant-elements' ),
					'param_name'  => 'progress_inactive_color',
					'value'       => '#e3f6ff',
					'dependency'  => array(
						'element' => 'step_nav_type',
						'value'   => array( 'progress' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Striped Progress Bar', 'elegant-elements' ),
					'description' => esc_attr__( 'Select if you want to use striped progress bar.', 'elegant-elements' ),
					'param_name'  => 'striped_bars',
					'default'     => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Animate Strips', 'elegant-elements' ),
					'description' => esc_attr__( 'Select if you want to animate the strips in progress bar.', 'elegant-elements' ),
					'param_name'  => 'animated_stripe',
					'default'     => 'yes',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Next Step Button Alignment', 'elegant-elements' ),
					'description' => esc_attr__( 'Select alignment for next step button inside the step content.', 'elegant-elements' ),
					'param_name'  => 'step_button_align',
					'default'     => 'left',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'icons'       => elegant_get_alignment_icons(),
					'group'       => esc_attr__( 'Steps Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'Enable ReCaptcha', 'elegant-elements' ),
					'description' => esc_attr__( 'Select if you want to use reCaptcha validation. The reCaptcha secrete key and site key should be configured in the Elegant Elements -> Settings in order to get the reCaptcha working.', 'elegant-elements' ),
					'param_name'  => 'recaptcha',
					'std'         => 'no',
					'value'       => array(
						'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
						'no'  => esc_attr__( 'No', 'elegant-elements' ),
					),
				),
				array(
					'type'        => 'ee_radio_button_set',
					'heading'     => esc_attr__( 'ReCaptcha Color Scheme', 'elegant-elements' ),
					'description' => esc_attr__( 'Controls the recaptcha color scheme.', 'elegant-elements' ),
					'param_name'  => 'recaptcha_color_scheme',
					'default'     => 'light',
					'value'       => array(
						'light' => esc_html__( 'Light', 'elegant-elements' ),
						'dark'  => esc_html__( 'Dark', 'elegant-elements' ),
					),
					'dependency'  => array(
						'element' => 'recaptcha',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'        => 'ee_checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'elegant-elements' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => elegant_elements_visibility_options( 'full' ),
					'icons'       => elegant_get_visibility_icons(),
					'default'     => elegant_elements_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
					'param_name'  => 'class',
					'value'       => '',
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
					'param_name'  => 'id',
					'value'       => '',
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				),
			),
		)
	);
}

add_action( 'vc_after_init', 'map_elegant_elements_wpbakery_forms', 99 );
