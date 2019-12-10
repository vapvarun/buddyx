<?php
/**
 *  Buddyx Customizer Support 
 * 
 * @package buddyx
 */

add_action( 'wp_head', 'buddyx_generate_customizer_css' );
/**
 * Add customizder typography style.
 */
function buddyx_generate_customizer_css() {
	$css = '<style type="text/css" id="customizer_style">';
	$typography = get_theme_mod( 'typography_option', array() );
    $css .= 'body{';
	if ( isset( $typography['font-family'] ) ) {
		$font_familly = $typography['font-family'];
		$css .= 'font-family:' . $font_familly . ';';
	}
	if ( isset( $typography['font-size'] ) ) {
		$font_size = $typography['font-size'];
		$css .= 'font-size:' . $font_size . ';';
	}	
	if ( isset( $typography['line-height'] ) ) {
		$line_height = $typography['line-height'];
		$css .= 'line-height:' . $line_height . ';';
	}
	if ( isset( $typography['letter-spacing'] ) ) {
		$letter_spacing = $typography['letter-spacing'];
		$css .= 'letter-spacing:' . $letter_spacing . ';';
	}
	if ( isset( $typography['color'] ) ) {
		$color = $typography['color'];
		$css .= 'color:' . $color . ';';
	}
	if ( isset( $typography['text-transform'] ) ) {
		$text_transform = $typography['text-transform'];
		$css .= 'text-transform:' . $text_transform . ';';
	}
	if ( isset( $typography['text-align'] ) ) {
		$text_align = $typography['text-align'];
		$css .= 'text-align:' . $text_align . ';';
	}
	if ( isset( $typography['font-weight'] ) ) {
		$font_weight = $typography['font-weight'];
		$css .= 'font-weight:' . $font_weight . ';';
	}
	if ( isset( $typography['font-style'] ) ) {
		$font_style = $typography['font-style'];
		$css .= 'font-style:' . $font_style . ';';
    }
    $css .= '}';

	$css .= '</style>';

	echo $css;

}