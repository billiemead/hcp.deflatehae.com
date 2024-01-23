<?php
/**
 * Handles the creation of forms.
 *
 * @package Elegant Elements
 * @since 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handles the creation of forms.
 *
 * @since 1.0
 */
class Elegant_Forms {

	/**
	 * Static counter for forms to identify the objects.
	 *
	 * @var int
	 * @since 1.0
	 */
	public static $form_number = 0;

	/**
	 * Form params.
	 *
	 * @var array
	 * @since 1.0
	 */
	private $params = array();

	/**
	 * Class constructor.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $params Form params.
	 */
	public function __construct( $params ) {
		$this->set_params( $params );
	}

	/**
	 * Setup the form parameters.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $params The form parameters.
	 * @return void
	 */
	private function set_params( $params ) {
		self::$form_number++;

		// Set all needed form wrapper classes.
		$params['form_wrapper_class'] = array(
			'elegant-form-wrapper',
			'elegant-form-wrapper-align-' . $params['form_align'],
			$params['form_wrapper_class'],
		);

		// Set form wrapper id, if present.
		if ( $params['form_wrapper_id'] ) {
			$params['form_wrapper_id'] = ' id="' . $params['form_wrapper_id'] . '"';
		}

		// Set action url to # if submission type is not url.
		if ( 'url' !== $params['type'] ) {
			$params['action'] = '#';
		}

		// Set form id to dynamic one if its blank.
		if ( '' === trim( $params['form_id'] ) ) {
			$params['form_id'] = $defaults['form_id'];
		}
		$this->params = $params;
	}

	/**
	 * Renders the pre form content, heading and caption.
	 *
	 * @since 1.0
	 * @access public
	 * @return string Form heading and caption.
	 */
	public function render_pre_form_content() {
		$html = $heading_style = $caption_style = '';

		if ( $this->params['heading_text'] ) {
			if ( $this->params['heading_color'] ) {
				$heading_style = 'color:' . esc_attr( $this->params['heading_color'] ) . ';';
			}
			if ( $this->params['heading_font_size'] ) {
				$heading_style .= 'font-size:' . esc_attr( $this->params['heading_font_size'] ) . 'px;';
			}
			$html .= '<' . $this->params['heading_size'] . ' class="elegant-form-heading" style="' . $heading_style . '">' . $this->params['heading_text'] . '</' . $this->params['heading_size'] . '>';
		}

		if ( $this->params['caption_text'] ) {
			if ( $this->params['caption_color'] ) {
				$caption_style = 'color:' . $this->params['caption_color'] . ';';
			}
			if ( $this->params['caption_font_size'] ) {
				$caption_style .= 'font-size:' . esc_attr( $this->params['caption_font_size'] ) . 'px;';
			}
			$html .= '<div class="elegant-form-caption" style="' . $caption_style . '">' . $this->params['caption_text'] . '</div>';
		}

		return apply_filters( 'elegant_form_pre_form_content', $html, $this->params );
	}

	/**
	 * Renders the opening form tag.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $field_types The form field types.
	 * @param bool  $is_heading  If the heading is blank or not.
	 * @return string The form tag.
	 */
	public function open_form( $field_types, $is_heading ) {
		$data_attributes = '';
		$styles          = '';
		$id              = '';
		$html            = '';
		$enctype         = '';
		$class           = 'elegant-form';

		if ( ! empty( $this->params['data_attributes'] ) ) {
			foreach ( $this->params['data_attributes'] as $key => $value ) {
				$data_attributes .= ' data-' . $key . '="' . $value . '"';
			}
		}

		if ( array_search( 'upload', $field_types ) ) {
			$enctype = ' enctype="multipart/form-data"';
		}

		// Set form styles.
		if ( $this->params['form_padding'] ) {
			$styles .= 'padding:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->params['form_padding'], 'px' ) . ';';
		}

		if ( $this->params['form_background_color'] ) {
			$styles .= 'background-color:' . $this->params['form_background_color'] . ';';
		}

		if ( $this->params['form_background_image'] ) {
			$image     = wp_get_attachment_image_src( $this->params['form_background_image'], 'full' );
			$image_url = $image[0];
			$image_url = esc_url( $image_url );

			$styles .= 'background-image: url(' . $image_url . ');';
			$styles .= 'background-position: ' . ( ( '' !== $this->params['form_background_position'] ) ? $this->params['form_background_position'] : 'left top' ) . ';';
			$styles .= 'background-repeat: ' . ( ( '' !== $this->params['form_background_repeat'] ) ? $this->params['form_background_repeat'] : 'no-repeat' ) . ';';
			$styles .= 'background-blend-mode: overlay;';
		}

		if ( $this->params['form_border_size'] ) {
			$styles .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->params['form_border_size'], 'px' ) . ';';
			$styles .= 'border-color:' . $this->params['form_border_color'] . ';';
			$styles .= 'border-style:' . $this->params['form_border_style'] . ';';

			if ( $is_heading ) {
				$styles .= 'border-top: none;';
			}
		}

		if ( isset( $this->params['form_border_radius'] ) && '' !== $this->params['form_border_radius'] ) {
			$border_radius = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->params['form_border_radius'], 'px' );

			if ( $is_heading ) {
				$styles .= 'border-radius:0 0 ' . $border_radius . ' ' . $border_radius . ';';
			} else {
				$styles .= 'border-radius:' . $border_radius . ';';
			}
		}

		if ( $styles ) {
			$styles = ' style="' . $styles . '"';
		}

		$class .= ' elegant-form-' . self::$form_number;

		if ( 'yes' === $this->params['inline_submit_button'] ) {
			$class .= ' elegant-button-inline';
		}

		$html .= '<form action="' . $this->params['action'] . '" method="POST"' . $data_attributes . ' class="' . $class . ' form-align-' . $this->params['form_align'] . '"' . $id . $styles . $enctype . '>';

		/**
		 * The elegant_form_after_open hook.
		 */
		do_action( 'elegant_form_after_open' );

		return $html;

	}

	/**
	 * The main element creation wrapper.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $element Form field settings.
	 * @return string All form elements.
	 */
	public function create_element( $element ) {
		$elements_html = '';

		$this->params['field_height'] = $element['field_height'] . ';';

		switch ( $element['field_type'] ) {
			case 'default':
			case 'text':
				$elements_html .= $this->text( $element );
				break;
			case 'password':
				$elements_html .= $this->password( $element );
				break;
			case 'color':
				$elements_html .= $this->color( $element );
				break;
			case 'number':
				$elements_html .= $this->number( $element );
				break;
			case 'email':
				$elements_html .= $this->email( $element );
				break;
			case 'checkbox':
				$elements_html .= $this->checkbox( $element );
				break;
			case 'radio':
				$elements_html .= $this->radio_button( $element );
				break;
			case 'radio_image':
				$elements_html .= $this->radio_image_set( $element );
				break;
			case 'select':
				$elements_html .= $this->select( $element );
				break;
			case 'range':
				$elements_html .= $this->range( $element );
				break;
			case 'date':
				$elements_html .= $this->date( $element );
				break;
			case 'textarea':
				$elements_html .= $this->textarea( $element );
				break;
			case 'upload':
				$elements_html .= $this->upload( $element );
				break;
			case 'tel':
				$elements_html .= $this->tel( $element );
				break;
			case 'time':
				$elements_html .= $this->time( $element );
				break;
			case 'url':
				$elements_html .= $this->url( $element );
				break;
			case 'hidden':
				$elements_html .= $this->hidden( $element );
				break;
			case 'acceptance':
				$elements_html .= $this->acceptance( $element );
				break;
		} // End switch().

		return $elements_html;
	}

	/**
	 * Renders normal text inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array  $element All needed values for the form field.
	 * @param string $type The type of the input, e.g. text, number, email.
	 * @return string The text input element.
	 */
	private function text( $element, $type = 'text' ) {

		$element_data = $this->create_element_data( $element );
		$element_id   = uniqid( $this->params['form_number'] );

		if ( 'text' === $type ) {
			$element_id_attr = 'data-element-id="' . $element_id . '"';
		} else {
			$element_id_attr = '';
		}

		$html = $element_data['element_wrapper_open'];

		$element_html = '<input type="' . $type . '" name="' . $element['name'] . '" value="' . $element['value'] . '" ' . $element_id_attr . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . '/>';

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		$html .= $element_data['element_wrapper_close'];

		$html = apply_filters( 'elegant_form_text_output', $html, $element );

		return $html;
	}

	/**
	 * Renders password inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The password input element.
	 */
	private function password( $element ) {
		$html = $this->text( $element, 'password' );

		return apply_filters( 'elegant_form_password_output', $html, $element );
	}

	/**
	 * Renders date input.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The date input element.
	 */
	private function date( $element ) {

		$element_data = $this->create_element_data( $element );

		$html = $element_data['element_wrapper_open'];

		$element_html = '<input type="date" name="' . $element['name'] . '" value="' . $element['value'] . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . '/>';

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		$html .= $element_data['element_wrapper_close'];

		$html = apply_filters( 'elegant_form_date_output', $html, $element );

		return $html;
	}

	/**
	 * Renders range inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The range input element.
	 */
	private function range( $element ) {

		$element_data = $this->create_element_data( $element );

		$html = $element_data['element_wrapper_open'];

		$min  = isset( $element['min'] ) ? $element['min'] : 1;
		$max  = isset( $element['max'] ) ? $element['max'] : 100;
		$step = isset( $element['step'] ) ? $element['step'] : 1;

		$element_html  = '<div class="elegant-range-slider">';
		$element_html .= '<input type="number" class="ui-range-slider-value" value="' . $element['value'] . '"/>';
		$element_html .= '<input type="range" name="' . $element['name'] . '" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $element['value'] . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . '/>';
		$element_html .= '</div>';

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		$html .= $element_data['element_wrapper_close'];

		$html = apply_filters( 'elegant_form_range_output', $html, $element );

		return $html;
	}

	/**
	 * Renders color input.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The color input element.
	 */
	private function color( $element ) {
		$html = $this->text( $element, 'color' );

		return apply_filters( 'elegant_form_color_output', $html, $element );
	}

	/**
	 * Renders number inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The number input element.
	 */
	private function number( $element ) {
		$html = $this->text( $element, 'number' );

		return apply_filters( 'elegant_form_number_output', $html, $element );
	}

	/**
	 * Renders telephone inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The telephone input element.
	 */
	private function tel( $element ) {
		$html = $this->text( $element, 'tel' );

		return apply_filters( 'elegant_form_tel_output', $html, $element );
	}

	/**
	 * Renders time inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The time input element.
	 */
	private function time( $element ) {
		$html = $this->text( $element, 'time' );

		return apply_filters( 'elegant_form_time_output', $html, $element );
	}

	/**
	 * Renders url inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The url input element.
	 */
	private function url( $element ) {
		$html = $this->text( $element, 'url' );

		return apply_filters( 'elegant_form_url_output', $html, $element );
	}

	/**
	 * Renders hidden inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The hidden input element.
	 */
	private function hidden( $element ) {
		$element_data = $this->create_element_data( $element );
		$element_id   = uniqid( $this->params['form_number'] );

		$html  = $element_data['element_wrapper_open'];
		$html .= '<input type="hidden" name="' . $element['name'] . '" value="' . $element['value'] . '" ' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . '/>';
		$html .= $element_data['element_wrapper_close'];

		$html = apply_filters( 'elegant_form_hidden_output', $html, $element );

		return $html;
	}

	/**
	 * Renders acceptance inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The acceptance input element.
	 */
	private function acceptance( $element ) {
		$element_data = $this->create_element_data( $element );
		$element_id   = uniqid( $this->params['form_number'] );

		$label    = $element_data['label'];
		$label_id = str_replace( ' ', '-', strtolower( $label ) ) . '-' . wp_rand();

		$html  = $element_data['element_wrapper_open'];
		$html .= '<input type="checkbox" id="' . $element['name'] . '" name="' . $element['name'] . '" value="yes" ' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . '/>';
		$html .= $label;
		$html .= $element_data['element_wrapper_close'];

		$html = apply_filters( 'elegant_form_acceptance_output', $html, $element );

		return $html;
	}

	/**
	 * Renders email inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The email input element.
	 */
	private function email( $element ) {
		$html = $this->text( $element, 'email' );

		return apply_filters( 'elegant_form_email_output', $html, $element );
	}

	/**
	 * Renders file upload inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The file upload input element.
	 */
	private function upload( $element ) {
		$element_data = $this->create_element_data( $element );
		$element_id   = uniqid( $this->params['form_number'] );

		$html = $element_data['element_wrapper_open'];

		$additional_attrs = '';
		if ( 'yes' === $element['allow_multiple'] ) {
			$additional_attrs .= ' multiple';
		}

		if ( isset( $element['allowed_file_types'] ) && '' !== $element['allowed_file_types'] ) {
			$additional_attrs .= ' accept="' . $element['allowed_file_types'] . '"';
		}

		$element_html = '<input type="file" name="' . $element['name'] . '" value="' . $element['value'] . '" ' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . $additional_attrs . '/>';

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		$html .= $element_data['element_wrapper_close'];

		return apply_filters( 'elegant_form_upload_output', $html, $element );
	}

	/**
	 * Renders radio button inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The radio button input element.
	 */
	private function radio_button( $element ) {
		$html = $this->checkbox( $element, 'radio' );

		return apply_filters( 'elegant_form_radio_output', $html, $element );
	}

	/**
	 * Renders checkbox inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array  $element All needed values for the form field.
	 * @param string $type The type of the input, e.g. radio, checkbox.
	 * @return string The checkbox input element.
	 */
	private function checkbox( $element, $type = 'checkbox' ) {

		$options      = '';
		$html         = '';
		$options_html = '';

		if ( empty( $element['options'] ) ) {
			return $html;
		}

		$element_data = $this->create_element_data( $element );
		$options      = explode( ',', $element['options'] );
		$display_view = ( isset( $element['display_view'] ) ) ? $element['display_view'] : 'inline';

		if ( is_array( $options ) ) {
			foreach ( $options as $key => $option ) {
				if ( false !== strpos( $option, '|' ) ) {
						$option = explode( '|', $option );
						$label  = trim( $option[0] );
						$value  = trim( $option[1] );
				} else {
					$label = $value = trim( $option );
				}

				$element_name = ( 'checkbox' == $type ) ? $element['name'] . '[]' : $element['name'];

				$checkbox_class = ( 'inline' == $display_view ) ? 'elegant-form-' . $type . ' option-inline' : 'elegant-form-' . $type;
				$label_id       = $type . '-' . str_replace( ' ', '-', strtolower( $label ) ) . '-' . $key;
				$options_html  .= '<div class="' . $checkbox_class . '">';
				$options_html  .= '<label for="' . $label_id . '">';
				$options_html  .= '<input id="' . $label_id . '" type="' . $type . '" value="' . $label . '" name="' . $element_name . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['checked'] . '/>';
				$options_html  .= '<span>' . $label . '</span></label>';
				$options_html  .= '</div>';
			}
		} else {
			$options_html .= '<input type="' . $type . '" value="' . $options . '" name="' . $element['name'] . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['checked'] . '/>';
			$options_html .= $options;
		}

		$element_html  = $element_data['element_wrapper_open'];
		$element_html .= '<fieldset>';
		$element_html .= $options_html;
		$element_html .= '</fieldset>';
		$element_html .= $element_data['element_wrapper_close'];

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		return apply_filters( 'elegant_form_checkbox_output', $html, $element );
	}

	/**
	 * Renders checkbox inputs.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The checkbox input element.
	 */
	private function radio_image_set( $element ) {

		$options = $html = $styles = '';

		if ( empty( $element['images'] ) ) {
			return $html;
		}

		$images = explode( ',', $element['images'] );

		$element_data = $this->create_element_data( $element );

		if ( is_array( $images ) ) {
			foreach ( $images as $image_id ) {
				$image_label  = '';
				$option_value = '';
				if ( '' !== $image_id ) {
					$image_url    = wp_get_attachment_url( $image_id, 'thumbnail' );
					$option_value = basename( $image_url );

					if ( isset( $element['title_as_label'] ) && 'yes' === $element['title_as_label'] ) {
						$image_label = get_the_title( $image_id );
						$image_label = '<span class="elegant-radio-image-title">' . $image_label . '</span>';
					}
				}

				$element_name = $element['name'];

				$width  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $element['width'], 'px' );
				$height = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $element['height'], 'px' );

				$element_style = 'style="width: ' . $width . '; height: ' . $height . ';"';

				$options .= '<label class="elegant-form-radio-image" ' . $element_style . '>';
				$options .= '<input type="radio" value="' . $option_value . '" name="' . $element_name . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['checked'] . '/>';
				$options .= '<img src="' . $image_url . '" />';
				$options .= $image_label;
				$options .= '</label>';
			}
		}

		if ( isset( $element['in_active_color'] ) && '' !== $element['in_active_color'] ) {
			$styles .= '.elegant-form.elegant-form-' . self::$form_number . ' .elegant-form-radio-image { border-color: ' . $element['in_active_color'] . ';}';

			if ( isset( $element['title_as_label'] ) && 'yes' === $element['title_as_label'] ) {
				$in_active_label_color = ( isset( $element['in_active_label_color'] ) && '' !== $element['in_active_label_color'] ) ? 'color:' . $element['in_active_label_color'] . ';' : '';
				$styles               .= '.elegant-form.elegant-form-' . self::$form_number . ' .elegant-form-radio-image .elegant-radio-image-title { background: ' . $element['in_active_color'] . '; border-color: ' . $element['in_active_color'] . ';' . $in_active_label_color . '}';
			}
		}

		if ( isset( $element['active_color'] ) && '' !== $element['active_color'] ) {
			$styles .= '.elegant-form.elegant-form-' . self::$form_number . ' .elegant-form-radio-image.active { border-color: ' . $element['active_color'] . ';}';

			if ( isset( $element['title_as_label'] ) && 'yes' === $element['title_as_label'] ) {
				$active_label_color = ( isset( $element['active_label_color'] ) && '' !== $element['active_label_color'] ) ? 'color:' . $element['active_label_color'] . ';' : '';
				$styles            .= '.elegant-form.elegant-form-' . self::$form_number . ' .elegant-form-radio-image.active .elegant-radio-image-title { background: ' . $element['active_color'] . '; border-color: ' . $element['active_color'] . ';' . $active_label_color . '}';
			}
		}

		$element_html = $element_data['element_wrapper_open'];

		if ( '' !== $styles ) {
			$element_html .= '<style type="text/css" scoped="scoped">' . $styles . '</style>';
		}

		$element_html .= '<fieldset>';
		$element_html .= $options;
		$element_html .= '</fieldset>';
		$element_html .= $element_data['element_wrapper_close'];

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		return apply_filters( 'elegant_form_radio_images_output', $html, $element );
	}

	/**
	 * Renders select fields.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The select element.
	 */
	private function select( $element ) {

		$options      = '';
		$html         = '';
		$options_html = '';

		if ( empty( $element['options'] ) ) {
			return $html;
		}

		$element_data = $this->create_element_data( $element );
		$options      = explode( ',', $element['options'] );

		if ( $element_data['placeholder'] ) {
			$options = array_merge( $element_data['placeholder'], $options );
		}

		foreach ( $options as $key => $option ) {
			if ( false !== strpos( $option, '|' ) ) {
					$option = explode( '|', $option );
					$label  = trim( $option[0] );
					$value  = trim( $option[1] );
			} else {
				$label = $value = trim( $option );
			}

			$options_html .= '<option value="' . $value . '">' . $label . '</option>';
		}

		$element_html  = $element_data['element_wrapper_open'];
		$element_html .= '<select name="' . $element['name'] . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['style'] . '>';
		$element_html .= $options_html;
		$element_html .= '</select>';

		$arrow_style = '';
		if ( isset( $element['border_color'] ) && '' !== $element['border_color'] ) {
			$arrow_style .= ' style="border-left-style:' . $element['border_style'] . '; fill:' . $element['border_color'] . '; border-color:' . $element['border_color'] . '; height: calc( ' . $element['field_height'] . ' - 2px ); line-height: ' . $element['field_height'] . ';"';
		}

		$element_html .= '<div class="select-arrow"' . $arrow_style . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg></div>';
		$element_html .= $element_data['element_wrapper_close'];

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		return apply_filters( 'elegant_form_select_output', $html, $element );
	}

	/**
	 * Renders textareas.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The number input element.
	 */
	private function textarea( $element ) {
		unset( $element['field_height'] );
		$element_data = $this->create_element_data( $element );

		$html = $element_data['element_wrapper_open'];

		$rows = ( isset( $element['rows'] ) ) ? $element['rows'] : 4;

		$element_html = '<textarea cols="40" rows="' . $rows . '" name="' . $element['name'] . '"' . $element_data['class'] . $element_data['id'] . $element_data['required'] . $element_data['placeholder'] . $element_data['style'] . '>' . $element['value'] . '</textarea>';

		if ( 'above' === $this->params['label_position'] ) {
			$html .= $element_data['label'] . $element_html;
		} elseif ( 'below' === $this->params['label_position'] ) {
			$html .= $element_html . $element_data['label'];
		} else {
			$html .= $element_html;
		}

		$html .= $element_data['element_wrapper_close'];

		return apply_filters( 'elegant_form_textarea_output', $html, $element );
	}

	/**
	 * Renders submit button.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $form_button_atts submit button attributes.
	 * @return string The submit input element.
	 */
	public function submit_button( $form_button_atts ) {

		$element_data = $this->create_element_data( $form_button_atts );

		$html         = $element_data['element_wrapper_open'];
		$submit_class = '';

		if ( 'email' === $this->params['type'] ) {
			$html .= '<input type="hidden" value="' . $this->params['email'] . '" name="elegant_form_email" />';
			$html .= '<input type="hidden" value="' . $this->params['email_from'] . '" name="elegant_form_email_from" />';
			$html .= '<input type="hidden" value="' . $this->params['email_from_id'] . '" name="elegant_form_email_from_id" />';
			$html .= '<input type="hidden" value="' . $this->params['email_subject'] . '" name="elegant_form_email_subject" />';
		}

		if ( 'url' !== $this->params['type'] ) {
			$submit_class = 'elegant-form-submit';
		}

		$background_color = ( isset( $this->params['button_background_color'] ) && '' !== $this->params['button_background_color'] ) ? $this->params['button_background_color'] : '';
		$text_color       = ( isset( $this->params['button_text_color'] ) && '' !== $this->params['button_text_color'] ) ? $this->params['button_text_color'] : '';

		$button_style  = 'background-color:' . $background_color . ';';
		$button_style .= 'border-color:' . $background_color . ';';
		$button_style .= 'color:' . $text_color . ';';

		if ( isset( $this->params['field_border_radius'] ) && '' !== $this->params['field_border_radius'] ) {
			$border_radius = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->params['field_border_radius'], 'px' );
			$button_style .= 'border-radius:' . $border_radius . ';';
		}

		if ( 'yes' === $this->params['inline_submit_button'] ) {
			if ( isset( $this->params['field_height'] ) ) {
				$button_style .= 'min-width: calc( ' . $this->params['field_height'] . 'px * 2.25 );';
				$button_style .= 'height:' . $this->params['field_height'] . 'px;';
				$button_style .= 'margin-left:15px;';
			}
		}

		$html .= '<button class="elegant-form-button elegant-form-submit form-button-submit" data-form-number="' . $this->params['form_number'] . '" style="' . $button_style . '">' . $this->params['submit_label'] . '</button>';

		$html .= $element_data['element_wrapper_close'];

		return apply_filters( 'elegant_form_submit_output', $html, $this->params );
	}

	/**
	 * Renders step button.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $form_button_atts Multi-step button attributes.
	 * @return string The step button element.
	 */
	public function step_button( $form_button_atts ) {

		$button_shortcode = $shortcode_atts = $content = $html = '';

		foreach ( $form_button_atts as $name => $value ) {
			if ( 'element_content' === $name ) {
				$content = $value;
			} else {

				if ( 'link_attributes' === $name ) {
					$step_id = $form_button_atts['step_id'];
					$value  .= " data-next-step='" . $step_id . "'";
				}

				$shortcode_atts .= $name . '="' . $value . '" ';
			}
		}

		$button_shortcode = '[elegant_button ' . $shortcode_atts . ']' . $content . '[/elegant_button]';

		$html .= do_shortcode( $button_shortcode );

		return apply_filters( 'elegant_form_step_button_output', $html, $this->params );
	}

	/**
	 * Localize form data.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $field_labels Form field label.
	 * @return void
	 */
	public function localize_form_data( $field_labels ) {

		wp_localize_script(
			'elegant-form-js',
			'elegantFormsConfig_' . $this->params['form_number'],
			array(
				'form_id'      => $this->params['form_id'],
				'form_type'    => $this->params['type'],
				'success'      => $this->params['success'],
				'error'        => $this->params['error'],
				'field_labels' => $field_labels,
			)
		);
	}

	/**
	 * Creates all meta data for the form elements.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $element All needed values for the form field.
	 * @return string The meta data of a form element.
	 */
	private function create_element_data( $element ) {
		$data = array(
			'checked'               => '',
			'required'              => '',
			'required_label'        => '',
			'required_placeholder'  => '',
			'class'                 => '',
			'id'                    => '',
			'placeholder'           => '',
			'element_wrapper_open'  => '<div class="elegant-form-element-wrapper elegant-form-' . $element['field_type'] . '-wrapper">',
			'element_wrapper_close' => '</div>',
			'label'                 => '',
			'label_class'           => '',
			'style'                 => '',
		);

		if ( isset( $element['hidden'] ) && $element['hidden'] ) {
			$data['element_wrapper_open'] = '<div class="elegant-form-element-wrapper elegant-form-element-hidden">';
		}

		if ( ! $element ) {
			return $data;
		}

		if ( 'checkbox' === $element['field_type'] && isset( $element['checked'] ) && $element['checked'] ) {
			$data['checked'] = ' checked="checked"';
		}

		if ( isset( $element['required'] ) && 'yes' === $element['required'] ) {
			$data['required']             = ' required="true" aria-required="true"';
			$data['required_label']       = '<abbr class="elegant-form-element-required" title="' . __( 'required', 'elegant-elements' ) . '">*</abbr>';
			$data['required_placeholder'] = '*';
		}

		if ( isset( $element['class'] ) && $element['class'] ) {
			$data['class'] = ' class="' . $element['class'] . ' elegant-form-input"';
		} else {
			$data['class'] = ' class="elegant-form-input"';
		}

		if ( isset( $element['id'] ) && $element['id'] ) {
			$data['id'] = ' id="' . $element['id'] . '"';
		}

		if ( isset( $element['placeholder'] ) && '' !== $element['placeholder'] ) {
			if ( 'select' === $element['field_type'] ) {
				$data['placeholder'] = array( $element['placeholder'] . $data['required_placeholder'] );
			} else {
				$data['placeholder'] = ' placeholder="' . $element['placeholder'] . ' ' . $data['required_placeholder'] . '"';
			}
		}

		if ( 'checkbox' === $element['field_type'] ) {
			$data['label_class'] = ' class="elegant-form-checkbox-label"';
		}
		if ( isset( $element['label'] ) && '' !== $element['label'] ) {
			$data['label'] = '<label for="' . $element['name'] . '"' . $data['label_class'] . '>' . $element['label'] . $data['required_label'] . '</label>';
		}

		$style = '';
		if ( isset( $element['field_height'] ) ) {
			$style .= 'height:' . $element['field_height'] . ';';
			$style .= 'line-height:' . $element['field_height'] . ';';
		}

		if ( isset( $element['border_size'] ) && '' !== $element['border_size'] ) {
			$style .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $element['border_size'], 'px' ) . ';';
			$style .= 'border-color:' . $element['border_color'] . ';';
			$style .= 'border-style:' . $element['border_style'] . ';';
			$style .= 'border-radius:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $element['border_radius'], 'px' ) . ';';
		}

		if ( '' !== $style ) {
			$data['style'] = 'style="' . $style . '"';
		}

		return $data;
	}

	/**
	 * Closes the form and adds an action.
	 *
	 * @since 1.0
	 * @access public
	 * @return string Form closing plus action output.
	 */
	public function close_form() {

		/**
		 * The elegant_form_before_close hook.
		 */
		ob_start();
		do_action( 'elegant_form_before_close' );
		$html = ob_get_clean();

		$html = '</form>';

		return $html;
	}
}
