<?php
/**
 * BuddyX\Buddyx\Fonts\Custom_Fonts_Repository
 *
 * Read-only wrapper around WordPress core's Font Library (`wp_font_family` /
 * `wp_font_face` CPTs, Appearance → Fonts → Upload). BuddyX never writes to
 * these post types — core's own Font Library UI/REST endpoints own that —
 * this class only surfaces what a site owner has already uploaded so those
 * fonts can appear in BuddyX's Typography "Family" picker and be printed as
 * `@font-face` on the front end.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Fonts;

defined( 'ABSPATH' ) || exit;

class Custom_Fonts_Repository {

	/**
	 * Lazily-built cache: $family_name => $wp_font_family post ID.
	 *
	 * @var array<string, int>|null
	 */
	protected static ?array $family_ids = null;

	/**
	 * Get every font family uploaded via WordPress's Font Library.
	 *
	 * Returns `$name => $name`, matching the `value => label` convention
	 * `Google_Fonts_Catalog::get_family_choices()` already uses so the
	 * Typography control's picker/JS needs no changes for a third group.
	 *
	 * @return array<string, string>
	 */
	public static function get_families(): array {
		self::prime_family_ids();

		$families = array();
		foreach ( array_keys( self::$family_ids ) as $name ) {
			$families[ $name ] = $name;
		}
		return $families;
	}

	/**
	 * Get the `@font-face` definitions for one uploaded family, shaped for
	 * WordPress core's `wp_print_font_faces()` (wp-includes/fonts.php) —
	 * i.e. one entry per face with kebab-case properties (`font-family`,
	 * `font-style`, `font-weight`, `font-display`, `src`, ...).
	 *
	 * @param string $family_name Exact family name as returned by get_families().
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_faces( string $family_name ): array {
		self::prime_family_ids();

		if ( ! isset( self::$family_ids[ $family_name ] ) ) {
			return array();
		}

		$face_posts = get_children(
			array(
				'post_parent' => self::$family_ids[ $family_name ],
				'post_type'   => 'wp_font_face',
				'post_status' => 'publish',
				'numberposts' => -1,
			),
			ARRAY_A
		);

		$faces = array();
		foreach ( (array) $face_posts as $face_post ) {
			$settings = json_decode( (string) $face_post['post_content'], true );
			if ( ! is_array( $settings ) || empty( $settings['src'] ) ) {
				continue;
			}
			$faces[] = self::to_font_face_definition( $settings, $family_name );
		}
		return $faces;
	}

	/**
	 * Build the name => post-ID map once per request from published
	 * `wp_font_family` posts.
	 */
	protected static function prime_family_ids(): void {
		if ( null !== self::$family_ids ) {
			return;
		}
		self::$family_ids = array();

		$family_posts = get_posts(
			array(
				'post_type'      => 'wp_font_family',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $family_posts as $family_post ) {
			$settings = json_decode( (string) $family_post->post_content, true );
			$name     = is_array( $settings ) && ! empty( $settings['name'] )
				? (string) $settings['name']
				: (string) $family_post->post_title;
			if ( '' === $name ) {
				continue;
			}
			self::$family_ids[ $name ] = (int) $family_post->ID;
		}
	}

	/**
	 * Convert one `wp_font_face` post's decoded `font_face_settings` (camelCase,
	 * per WP_REST_Font_Faces_Controller's schema) into the kebab-case shape
	 * `wp_print_font_faces()` expects — the same transform core's own
	 * WP_Font_Face_Resolver::to_kebab_case() performs for theme.json fonts.
	 *
	 * `src` on an uploaded face is already an absolute URL (set at upload time
	 * by WP_REST_Font_Faces_Controller::create_item()), so unlike theme.json
	 * fonts there is no `file:./` placeholder to resolve here.
	 *
	 * @param array<string, mixed> $settings    Decoded font_face_settings.
	 * @param string               $family_name Canonical family name to force onto the face.
	 * @return array<string, mixed>
	 */
	protected static function to_font_face_definition( array $settings, string $family_name ): array {
		$map = array(
			'fontFamily'            => 'font-family',
			'fontStyle'             => 'font-style',
			'fontWeight'            => 'font-weight',
			'fontDisplay'           => 'font-display',
			'fontStretch'           => 'font-stretch',
			'fontVariant'           => 'font-variant',
			'fontFeatureSettings'   => 'font-feature-settings',
			'fontVariationSettings' => 'font-variation-settings',
			'ascentOverride'        => 'ascent-override',
			'descentOverride'       => 'descent-override',
			'lineGapOverride'       => 'line-gap-override',
			'sizeAdjust'            => 'size-adjust',
			'unicodeRange'          => 'unicode-range',
			'src'                   => 'src',
		);

		$face = array();
		foreach ( $map as $camel => $kebab ) {
			if ( isset( $settings[ $camel ] ) && '' !== $settings[ $camel ] ) {
				$face[ $kebab ] = $settings[ $camel ];
			}
		}

		// Force the canonical family name so the emitted @font-face rule's
		// `font-family` matches exactly what Output_Builder writes into the
		// `font-family:` declaration on the front end.
		$face['font-family'] = $family_name;

		return $face;
	}
}
