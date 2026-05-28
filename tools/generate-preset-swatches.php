<?php
/**
 * Regenerate the Style preset swatch SVG previews shown by the
 * Customizer's Skin section. Reads palette colors from each
 * `styles/<slug>.json` variation file and writes one
 * `assets/images/presets/<slug>.svg` per variation (plus `default.svg`
 * for the empty "Default (theme)" choice).
 *
 * Run after a variation's palette changes (or after adding / removing a
 * variation). The radio_image control reads files from
 * `assets/images/presets/`, so the next customizer load picks up the
 * regenerated swatches.
 *
 * Usage:
 *   wp eval-file wp-content/themes/buddyx/tools/generate-preset-swatches.php
 *
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit;

$THEME_DIR  = dirname( __DIR__ );
$STYLES_DIR = $THEME_DIR . '/styles';
$OUT_DIR    = $THEME_DIR . '/assets/images/presets';

if ( ! is_dir( $OUT_DIR ) ) {
	mkdir( $OUT_DIR, 0755, true );
}

function bxgen_norm_hex( $hex ) {
	$hex = is_string( $hex ) ? strtolower( trim( $hex ) ) : '';
	if ( '' === $hex || '#' !== $hex[0] ) {
		return '#cccccc';
	}
	if ( ! preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $hex ) ) {
		return '#cccccc';
	}
	if ( 4 === strlen( $hex ) ) {
		return '#' . $hex[1] . $hex[1] . $hex[2] . $hex[2] . $hex[3] . $hex[3];
	}
	return $hex;
}

function bxgen_svg( $header, $bg, $primary, $accent ) {
	$header  = bxgen_norm_hex( $header );
	$bg      = bxgen_norm_hex( $bg );
	$primary = bxgen_norm_hex( $primary );
	$accent  = bxgen_norm_hex( $accent );
	return '<?xml version="1.0" encoding="UTF-8"?>'
		. '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 80" preserveAspectRatio="xMidYMid meet">'
		. '<rect width="120" height="80" fill="' . $bg . '"/>'
		. '<rect width="120" height="14" fill="' . $header . '"/>'
		. '<rect x="14" y="34" width="34" height="14" rx="3" fill="' . $primary . '"/>'
		. '<rect x="54" y="34" width="34" height="14" rx="3" fill="' . $accent . '" opacity="0.9"/>'
		. '<rect x="14" y="56" width="92" height="6" rx="2" fill="' . $primary . '" opacity="0.25"/>'
		. '<rect x="14" y="66" width="60" height="4" rx="2" fill="' . $primary . '" opacity="0.18"/>'
		. '</svg>';
}

$count = 0;

// Empty "Default (theme)" choice gets a neutral preview.
file_put_contents( $OUT_DIR . '/default.svg', bxgen_svg( '#ffffff', '#1d2327', '#2271b1', '#50575e' ) );
$count++;

$slugs = array( 'cool', 'dark', 'editorial', 'minimal', 'monochrome', 'pastel', 'vibrant', 'warm' );
foreach ( $slugs as $slug ) {
	$file = $STYLES_DIR . '/' . $slug . '.json';
	if ( ! is_readable( $file ) ) {
		continue;
	}
	$data = json_decode( (string) file_get_contents( $file ), true );
	if ( ! is_array( $data ) ) {
		continue;
	}
	$palette = $data['settings']['color']['palette'] ?? array();
	$by_slug = array();
	foreach ( (array) $palette as $entry ) {
		if ( isset( $entry['slug'], $entry['color'] ) ) {
			$by_slug[ $entry['slug'] ] = (string) $entry['color'];
		}
	}
	$bg      = $by_slug['base']     ?? '#ffffff';
	$header  = $by_slug['accent']   ?? ( $by_slug['contrast'] ?? '#1d2327' );
	$primary = $by_slug['contrast'] ?? '#1d2327';
	$accent  = $by_slug['accent-2'] ?? ( $by_slug['surface-1'] ?? $header );
	file_put_contents( $OUT_DIR . '/' . $slug . '.svg', bxgen_svg( $header, $bg, $primary, $accent ) );
	$count++;
}

echo "wrote {$count} swatch SVGs to {$OUT_DIR}\n";
