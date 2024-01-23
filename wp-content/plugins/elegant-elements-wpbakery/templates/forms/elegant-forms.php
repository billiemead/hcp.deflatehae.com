<?php
global $elegant_form;
$settings = get_option( 'elegant_elements_wpbakery_settings', array() );

$attr            = array();
$attr['id']      = ( isset( $this->args['form_css_id'] ) && '' !== $this->args['form_css_id'] ) ? $this->args['form_css_id'] : '';
$attr['class'][] = 'elegant-forms elegant-form-wrapper';

if ( isset( $this->args['form_class'] ) && '' !== $this->args['form_class'] ) {
	$attr['class'][] = $this->args['form_class'];
}

if ( isset( $this->args['form_class'] ) && '' !== $this->args['form_class'] ) {
	$attr['class'][] = $this->args['form_class'];
}

$attr['class'] = implode( ' ', $attr['class'] );

$elegant_form       = new Elegant_Forms( $this->args );
$form_content       = '';
$heading_padding    = '';
$heading_style      = '';
$this->field_types  = array();
$this->field_labels = array();

$field_style = '';

if ( isset( $this->args['margin_top'] ) && '' !== $this->args['margin_top'] ) {
	$field_style .= 'margin-top:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin_top'], 'px' ) . ';';
}

if ( isset( $this->args['margin_bottom'] ) && '' !== $this->args['margin_bottom'] ) {
	$field_style .= 'margin-bottom:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['margin_bottom'], 'px' ) . ';';
}

$recaptcha_content  = '';
$form_submit_button = '';
$re_captcha_class   = '';

if ( isset( $this->args['recaptcha'] ) && 'yes' === $this->args['recaptcha'] ) {
	$random_id          = wp_rand();
	$recaptcha_content .= '<div class="elegant-form-recaptcha-container">';
	$recaptcha_content .= '<div id="g-recaptcha-' . $random_id . '" class="elegant-form-recaptcha" data-theme="' . esc_attr( $this->args['recaptcha_color_scheme'] ) . '" data-sitekey="' . esc_attr( $settings['recaptcha_public'] ) . '"></div>';
	$recaptcha_content .= '</div>';

	$re_captcha_class = ' elegant-form-has-recaptcha';
}

$form_submit_button  = '<div class="elegant-form-submit-button-container elegant-form-last-column-button' . $re_captcha_class . '">';
$form_submit_button .= $recaptcha_content;
$form_submit_button .= $elegant_form->submit_button( array( 'field_type' => 'button' ) );
$form_submit_button .= '<div class="elegant-form-loader"></div>';
$form_submit_button .= '</div>';

$label_position = ( isset( $this->args['label_position'] ) && '' !== $this->args['label_position'] ) ? $this->args['label_position'] : 'above';

$is_last            = false;
$is_fullwidth       = false;
$column_width_count = 0;

$steps         = array();
$step_fields   = array();
$is_multi_step = false;
$step_uid      = wp_rand();

if ( 'form_step' === $this->form_fields[0]['field_type'] ) {
	$is_multi_step = true;

	foreach ( $this->form_fields as $field ) {
		if ( 'form_step' === $field['field_type'] ) {
			$step_uid           = wp_rand();
			$steps[ $step_uid ] = $field;
		} else {
			$step_fields[ $step_uid ][] = $field;
		}
	}

	$connector_style = '';
	if ( isset( $this->args['step_border_color'] ) && '' !== $this->args['step_border_color'] ) {
		$connector_style .= 'border-color:' . $this->args['step_border_color'] . ';';
	}

	$steps_classes = '';
	if ( 'yes' === $this->args['striped_bars'] ) {
		$steps_classes .= ' proress-sripped';
	}

	if ( 'yes' === $this->args['animated_stripe'] ) {
		$steps_classes .= ' proress-sripped-animated';
	}

	$form_content .= '<div class="elegant-form-steps-wrapper">';
	$form_content .= '<div class="elegant-form-steps-nav">';
	$form_content .= '<ul class="elegant-form-steps elegant-form-steps-' . $this->args['step_nav_type'] . $steps_classes . '" style="' . $connector_style . '">';

	$step_number = 1;
	foreach ( $steps as $step_key => $field ) {
		$active_step = ( 1 === $step_number ) ? ' active-step-nav complete-step-nav' : '';
		$field_label = $field['label'];
		$step_style  = 'font-size: ' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['step_nav_font_size'], 'px' ) . ';';

		$step_color   = '';
		$border_color = '';

		if ( isset( $this->args['step_active_color'] ) && '' !== $this->args['step_active_color'] ) {
			$step_color  = '--active-color:' . $this->args['step_active_color'] . ';';
			$step_color .= '--active-text-color:' . $this->args['step_active_text_color'] . ';';
		}

		if ( isset( $this->args['step_link_color'] ) && '' !== $this->args['step_link_color'] ) {
			$step_color .= '--color:' . $this->args['step_link_color'] . ';';
			$step_style .= '--background-color:' . $this->args['step_link_bg_color'] . ';';
		}

		if ( 'number' === $this->args['step_nav_type'] ) {
			$field_label = '<span class="elegant-form-step-number" style="' . $step_style . '">' . $step_number . '</span>';
		} elseif ( 'label' === $this->args['step_nav_type'] ) {
			$field_label = '<span class="elegant-form-step-number elegant-form-step-label" style="' . $step_style . '"><label>' . $field['label'] . '</label></span>';
		} elseif ( 'progress' === $this->args['step_nav_type'] ) {
			$step_color  = '--background-color:' . $this->args['progress_inactive_color'] . ';';
			$step_color .= '--active-background-color:' . $this->args['progress_complete_color'] . ';';
			$field_label = '<span class="elegant-form-step-progress" style="' . $step_style . '"></span>';
		}

		$form_content .= '<li class="elegant-form-step elegant-form-step-nav-' . $step_key . $active_step . '" style="' . $step_color . '">';
		$form_content .= '<a class="elegant-form-step-link" href="#elegant-form-step-' . $step_key . '">' . $field_label . '</a>';
		$form_content .= '</li>';

		$step_number++;
	}
	$form_content .= '</ul>';
	$form_content .= '</div>';

	$form_content .= '<div class="elegant-form-step-fields">';
	$step_number   = 1;
	$steps_count   = count( $step_fields );
	$step_keys     = array_keys( $step_fields );

	foreach ( $step_fields as $step_key => $fields ) {
		$active_step   = ( 1 === $step_number ) ? ' active-step' : '';
		$form_content .= '<div id="elegant-form-step-' . $step_key . '" class="elegant-form-step-data' . $active_step . '">';

		foreach ( $fields as $field ) {
			array_push( $this->field_types, $field['field_type'] );

			$column_width_count += $field['column_width'];

			if ( 100 === $column_width_count ) {
				$is_last            = true;
				$column_width_count = 0;
			} else {
				$is_last = false;
			}

			if ( 100 == (int) $field['column_width'] ) {
				$is_fullwidth = true;
				$is_last      = false;
			} else {
				$is_fullwidth = false;
			}

			$field_column_style = '';
			$column_width       = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $field['column_width'], '%' );

			if ( ! $is_last && ! $is_fullwidth ) {
				$field_column_style .= 'margin-right: 15px;';
				$column_width        = 'calc( ' . $column_width . ' - 15px )';
			} elseif ( $is_last ) {
				$field_column_style .= 'margin-left: 15px;';
				$column_width        = 'calc( ' . $column_width . ' - 15px )';
			}

			$field_column_style .= 'width:' . $column_width . ';';
			$field_column_style .= 'display: inline-block;';

			$form_content .= '<div class="elegant-form-field elegant-form-field-' . $field['field_type'] . ' elegant-form-label-' . $label_position . '" style="' . $field_style . ' ' . $field_column_style . '">';

			$form_field = $field;

			if ( ! isset( $field_data->value ) ) {
				$form_field['value'] = '';
			}
			$form_field['hidden'] = false;
			$form_field['name']   = ( str_replace( array( ' ', '_' ), '-', strtolower( $field['label'] ) ) );

			if ( isset( $this->args['field_height'] ) && '' !== $this->args['field_height'] ) {
				$field_height               = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['field_height'], 'px' );
				$form_field['field_height'] = $field_height;
			}

			if ( isset( $this->args['field_border_size'] ) && '' !== $this->args['field_border_size'] ) {
				$field_border_size           = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['field_border_size'], 'px' );
				$form_field['border_size']   = $field_border_size;
				$form_field['border_radius'] = isset( $this->args['field_border_radius'] ) ? $this->args['field_border_radius'] : 0;
				$form_field['border_color']  = $this->args['field_border_color'];
				$form_field['border_style']  = $this->args['field_border_style'];
			}

			$form_content .= $elegant_form->create_element( $form_field );

			$this->field_labels[ $form_field['name'] ] = $form_field['label'];

			$form_content .= '</div>';

		}

		if ( $step_number === $steps_count ) {
			$form_content .= $form_submit_button;
		} else {

			// Remove the current step key from the array so we get the correct next step.
			unset( $step_keys[ $step_key ] );

			$next_step        = next( $step_keys );
			$background_color = ( isset( $this->args['button_background_color'] ) && '' !== $this->args['button_background_color'] ) ? $this->args['button_background_color'] : $this->args['step_active_color'];
			$text_color       = ( isset( $this->args['button_text_color'] ) && '' !== $this->args['button_text_color'] ) ? $this->args['button_text_color'] : $this->args['step_active_text_color'];

			$button_color  = 'background-color:' . $background_color . ';';
			$button_color .= 'border-color:' . $background_color . ';';
			$button_color .= 'color:' . $text_color . ';';
			$form_content .= '<div class="elegant-align-' . $this->args['step_button_align'] . '"><a class="elegant-form-step-button" href="#elegant-form-step-' . $next_step . '" style="' . $button_color . '">' . esc_attr__( 'Next', 'elegant-elements' ) . '</a></div>';
		}

		$form_content .= '</div>';
		$step_number++;
	}

	$form_content .= '</div>';
	$form_content .= '</div>';
} else {
	foreach ( $this->form_fields as $field ) {
		array_push( $this->field_types, $field['field_type'] );

		$column_width_count += $field['column_width'];

		if ( 100 === $column_width_count ) {
			$is_last            = true;
			$column_width_count = 0;
		} else {
			$is_last = false;
		}

		if ( 100 == (int) $field['column_width'] ) {
			$is_fullwidth = true;
			$is_last      = false;
		} else {
			$is_fullwidth = false;
		}

		$field_column_style = '';
		$column_width       = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $field['column_width'], '%' );

		if ( ! $is_last && ! $is_fullwidth ) {
			$field_column_style .= 'margin-right: 15px;';
			$column_width        = 'calc( ' . $column_width . ' - 15px )';
		} elseif ( $is_last ) {
			$field_column_style .= 'margin-left: 15px;';
			$column_width        = 'calc( ' . $column_width . ' - 15px )';

			if ( 'yes' === $this->args['inline_submit_button'] ) {
				$field_column_style .= 'margin-right: 15px;';
			}
		}

		$field_column_style .= 'width:' . $column_width . ';';
		$field_column_style .= 'display: inline-block;';

		$form_content .= '<div class="elegant-form-field elegant-form-field-' . $field['field_type'] . ' elegant-form-label-' . $label_position . '" style="' . $field_style . ' ' . $field_column_style . '">';

		$form_field = $field;

		if ( ! isset( $field_data->value ) ) {
			$form_field['value'] = '';
		}
		$form_field['hidden'] = false;
		$form_field['name']   = ( str_replace( array( ' ', '_' ), '-', strtolower( $field['label'] ) ) );

		if ( isset( $this->args['field_height'] ) && '' !== $this->args['field_height'] ) {
			$field_height               = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['field_height'], 'px' );
			$form_field['field_height'] = $field_height;
		}

		if ( isset( $this->args['field_border_size'] ) && '' !== $this->args['field_border_size'] ) {
			$field_border_size           = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['field_border_size'], 'px' );
			$form_field['border_size']   = $field_border_size;
			$form_field['border_radius'] = isset( $this->args['field_border_radius'] ) ? $this->args['field_border_radius'] : 0;
			$form_field['border_color']  = $this->args['field_border_color'];
			$form_field['border_style']  = $this->args['field_border_style'];
		}

		$form_content .= $elegant_form->create_element( $form_field );

		$this->field_labels[ $form_field['name'] ] = $form_field['label'];

		$form_content .= '</div>';
	}
}

if ( isset( $this->args['heading_padding'] ) && '' !== $this->args['heading_padding'] ) {
	$heading_padding = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['heading_padding'], 'px' );
	$heading_style  .= 'padding:' . esc_attr( $heading_padding ) . ';';
}

$heading_style .= ( isset( $this->args['heading_background_color'] ) && '' !== $this->args['heading_background_color'] ) ? 'background-color:' . $this->args['heading_background_color'] . ';' : '';

if ( isset( $this->args['heading_background_image'] ) && '' !== $this->args['heading_background_image'] ) {
	$heading_style .= 'background-image: url(' . $this->args['heading_background_image'] . ');';
	$heading_style .= 'background-position: ' . $this->args['heading_background_position'] . ';';
	$heading_style .= 'background-repeat: ' . $this->args['heading_background_repeat'] . ';';
	$heading_style .= 'background-blend-mode: overlay;';
}

if ( isset( $this->args['form_border_size'] ) && '' !== $this->args['form_border_size'] ) {
	$heading_style .= 'border-width:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_size'], 'px' ) . ';';
	$heading_style .= 'border-color:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_color'], 'px' ) . ';';
	$heading_style .= 'border-style:' . Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_style'], 'px' ) . ';';
	$heading_style .= 'border-bottom: none;';
}

if ( isset( $this->args['form_border_radius'] ) && '' !== $this->args['form_border_radius'] ) {
	$border_radius  = Elegant_Elements_WPBakery::validate_shortcode_attr_value( $this->args['form_border_radius'], 'px' );
	$heading_style .= 'border-radius:' . $border_radius . ' ' . $border_radius . ' 0 0;';
}

if ( '' !== $heading_style ) {
	$heading_style = ' style="' . $heading_style . '"';
}

if ( isset( $this->args['hide_on_mobile'] ) && '' !== $this->args['hide_on_mobile'] ) {
	$attr = elegant_elements_visibility_atts( $this->args['hide_on_mobile'], $attr );
}

$is_heading   = false;
$heading_html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-forms-heading', $attr ) . '>';
if ( isset( $this->args['heading_text'] ) && '' !== trim( $this->args['heading_text'] ) || isset( $this->args['caption_text'] ) && '' !== trim( $this->args['caption_text'] ) ) {
	$heading_html .= '<div class="elegant-form-heading-wrapper form-align-' . $this->args['heading_align'] . '"' . $heading_style . '>';
	$heading_html .= $elegant_form->render_pre_form_content();
	$heading_html .= '</div>';
	$is_heading    = true;
}

$elegant_form->localize_form_data( $this->field_labels );

$form_html  = $heading_html;
$form_html .= $elegant_form->open_form( $this->field_types, $is_heading );
$form_html .= $form_content;
$form_html .= ( ! $is_multi_step ) ? $form_submit_button : '';
$form_html .= $elegant_form->close_form();
$form_html .= '<div class="elegant-form-message-container"></div>';
$form_html .= '</div>';
