<?php
$html = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-timer' ) . '>';

$html .= '<ul ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-ul' ) . '>';

if ( 'yes' === $this->args['show_days'] ) {
	$html .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-timer-item' ) . '>
				<div class="days-wrap">
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-unit', array( 'unit' => 'days' ) ) . '></span>
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-label', array( 'unit' => 'days' ) ) . '>' . __( 'days', 'elegant-elements' ) . '</span>
				</div>
			</li>';
}

if ( 'yes' === $this->args['show_hours'] ) {
	$html .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-timer-item' ) . '>
				<div class="hours-wrap">
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-unit', array( 'unit' => 'hours' ) ) . '></span>
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-label', array( 'unit' => 'hours' ) ) . '>' . __( 'hours', 'elegant-elements' ) . '</span>
				</div>
			</li>';
}

if ( 'yes' === $this->args['show_minutes'] ) {
	$html .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-timer-item' ) . '>
				<div class="minutes-wrap">
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-unit', array( 'unit' => 'minutes' ) ) . '></span>
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-label', array( 'unit' => 'minutes' ) ) . '>' . __( 'minutes', 'elegant-elements' ) . '</span>
				</div>
			</li>';
}

if ( 'yes' === $this->args['show_seconds'] ) {
	$html .= '<li ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-timer-item' ) . '>
				<div class="seconds-wrap">
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-unit', array( 'unit' => 'seconds' ) ) . '></span>
					<span ' . Elegant_Elements_WPBakery::attributes( 'elegant-countdown-time-label', array( 'unit' => 'seconds' ) ) . '>' . __( 'seconds', 'elegant-elements' ) . '</span>
				</div>
			</li>';
}

$html .= '</ul>';
$html .= '</div>';
