<?php
/**
 * Regenerate inc/Fonts/data/google-fonts.json from the live Google Fonts list.
 *
 * The bundled catalog gates which customer-saved typography families are
 * recognized and auto-loaded as webfonts (Google_Fonts_Catalog::is_google_font
 * + Fonts\Component::collect_active_google_families). Refresh it before a
 * release so newly added Google families are covered.
 *
 * Source: the public Google Fonts metadata endpoint (no API key required),
 * the same list the fonts.google.com directory uses.
 *
 * Output schema (unchanged, drop-in compatible):
 *   { "Family Name": { "family": "...", "category": "sans-serif", "variants": ["regular","italic","700",...] }, ... }
 *
 * Usage:
 *   php tools/generate-google-fonts-catalog.php            # fetch live + write
 *   php tools/generate-google-fonts-catalog.php /tmp/x.json # use a local metadata dump
 *
 * @package buddyx
 */

$source_url = 'https://fonts.google.com/metadata/fonts';
$out_file   = dirname( __DIR__ ) . '/inc/Fonts/data/google-fonts.json';

// Allow a pre-downloaded metadata file as arg 1 (offline / CI use).
$raw = '';
if ( isset( $argv[1] ) && is_readable( $argv[1] ) ) {
	$raw = (string) file_get_contents( $argv[1] );
} else {
	$ctx = stream_context_create( array( 'http' => array( 'timeout' => 60, 'header' => "User-Agent: buddyx-fonts-catalog-generator\r\n" ) ) );
	$raw = (string) @file_get_contents( $source_url, false, $ctx );
}

if ( '' === $raw ) {
	fwrite( STDERR, "ERROR: could not read Google Fonts metadata.\n" );
	exit( 1 );
}

$data = json_decode( $raw, true );
$list = isset( $data['familyMetadataList'] ) ? $data['familyMetadataList'] : array();
if ( empty( $list ) ) {
	fwrite( STDERR, "ERROR: familyMetadataList missing/empty.\n" );
	exit( 1 );
}

/**
 * Map a metadata `fonts` key (e.g. "400", "400i", "700i") to the
 * Google Webfonts API variant string the catalog uses.
 */
function bx_variant( $key ) {
	if ( '400' === $key ) {
		return 'regular';
	}
	if ( '400i' === $key ) {
		return 'italic';
	}
	if ( preg_match( '/^(\d+)i$/', $key, $m ) ) {
		return $m[1] . 'italic';
	}
	if ( preg_match( '/^\d+$/', $key ) ) {
		return $key;
	}
	return $key; // pass through anything unexpected
}

$catalog = array();
foreach ( $list as $entry ) {
	if ( empty( $entry['family'] ) ) {
		continue;
	}
	$family   = (string) $entry['family'];
	$category = strtolower( str_replace( ' ', '-', (string) ( $entry['category'] ?? '' ) ) );

	$variants = array();
	foreach ( array_keys( (array) ( $entry['fonts'] ?? array() ) ) as $vk ) {
		$variants[] = bx_variant( (string) $vk );
	}
	$variants = array_values( array_unique( $variants ) );
	sort( $variants ); // match the existing alphabetical ordering
	if ( empty( $variants ) ) {
		$variants = array( 'regular' );
	}

	$catalog[ $family ] = array(
		'family'   => $family,
		'category' => $category,
		'variants' => $variants,
	);
}

ksort( $catalog, SORT_FLAG_CASE | SORT_STRING );

$json = wp_json_encode_safe( $catalog );
file_put_contents( $out_file, $json );
fwrite( STDOUT, 'Wrote ' . count( $catalog ) . " families to $out_file\n" );

/**
 * json_encode with the flags the bundled file uses (compact, unescaped
 * slashes + unicode). Standalone so the script runs without WP loaded.
 */
function wp_json_encode_safe( $value ) {
	return json_encode( $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}
