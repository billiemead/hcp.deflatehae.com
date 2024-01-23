<?php
$html  = '<div ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-style-heading' ) . '>';
$html .= '<' . $this->args['heading_tag'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-style-heading-first' ) . '>';
$html .= $this->args['heading_first'];
$html .= '</' . $this->args['heading_tag'] . '>';
$html .= '<' . $this->args['heading_tag'] . ' ' . Elegant_Elements_WPBakery::attributes( 'elegant-dual-style-heading-last' ) . '>';
$html .= $this->args['heading_last'];
$html .= '</' . $this->args['heading_tag'] . '>';
$html .= '</div>';
