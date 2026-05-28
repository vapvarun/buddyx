<?php
/**
 * BuddyX\Buddyx\Fonts\Google_Fonts_Catalog
 *
 * Reads the bundled Google Fonts catalog (inc/Fonts/data/google-fonts.json).
 * Used by the Customizer Typography control to populate the font picker.
 * Loaded only in the customizer admin context — never on the front end.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Fonts;

defined( 'ABSPATH' ) || exit;

/**
 * Google_Fonts_Catalog
 */
class Google_Fonts_Catalog {

	/**
	 * Parsed catalog cache.
	 *
	 * @var array|null
	 */
	protected static $catalog = null;

	/**
	 * Read and cache the catalog JSON.
	 *
	 * @return array<string, array> Family name => { family, category, variants }.
	 */
	public static function get_catalog(): array {
		if ( null !== self::$catalog ) {
			return self::$catalog;
		}
		$file = __DIR__ . '/data/google-fonts.json';
		if ( ! is_readable( $file ) ) {
			self::$catalog = array();
			return self::$catalog;
		}
		$data          = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		self::$catalog = is_array( $data ) ? $data : array();
		return self::$catalog;
	}

	/**
	 * Family name => label map for the picker (family name is both key and label).
	 *
	 * @return array<string, string>
	 */
	public static function get_family_choices(): array {
		static $choices = null;
		if ( null !== $choices ) {
			return $choices;
		}
		$out = array();
		foreach ( self::get_catalog() as $family => $info ) {
			$name         = isset( $info['family'] ) ? (string) $info['family'] : (string) $family;
			$out[ $name ] = $name;
		}
		$choices = $out;
		return $choices;
	}

	/**
	 * Whether a given family name exists in the bundled catalog.
	 *
	 * @param string $family Family name.
	 * @return bool
	 */
	public static function is_google_font( string $family ): bool {
		if ( '' === $family ) {
			return false;
		}
		$catalog = self::get_catalog();
		return isset( $catalog[ $family ] );
	}
}
