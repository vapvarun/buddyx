<?php
/**
 * Colour maths shared by the BuddyX and BuddyX Pro token systems.
 *
 * Master copy lives in BuddyX free; BuddyX Pro receives it through
 * bin/sync-shared.sh. The using class must define DEFAULT_DARK_ACCENT.
 *
 * @package buddyx
 */

namespace BuddyX\Shared;

defined( 'ABSPATH' ) || exit;

/**
 * Parse, measure and shift colours (WCAG luminance, readable accents, token variants).
 */
trait Color_Math {

	/**
	 * Parse a hex/rgb/rgba color string into [r, g, b] integer channels (0-255).
	 *
	 * Returns null if the input doesn't match a recognized color format.
	 *
	 * Supported inputs:
	 *   - #rgb / #rgba / #rrggbb / #rrggbbaa  (alpha channel ignored for derivation)
	 *   - rgb(r, g, b) / rgba(r, g, b, a)
	 *
	 * @param string $color Customer-saved color string.
	 * @return array<int,int>|null [R, G, B] each 0-255, or null on parse failure.
	 */
	protected static function color_to_rgb( string $color ): ?array {
		$color = trim( $color );
		// Hex.
		if ( preg_match( '/^#([A-Fa-f0-9]{3,8})$/', $color, $m ) ) {
			$hex = $m[1];
			$len = strlen( $hex );
			if ( 3 === $len || 4 === $len ) {
				$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
			} elseif ( 8 === $len ) {
				$hex = substr( $hex, 0, 6 );
			} elseif ( 6 !== $len ) {
				return null;
			}
			return array(
				hexdec( substr( $hex, 0, 2 ) ),
				hexdec( substr( $hex, 2, 2 ) ),
				hexdec( substr( $hex, 4, 2 ) ),
			);
		}
		// rgb() / rgba().
		if ( preg_match( '/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})/', $color, $m ) ) {
			return array(
				min( 255, max( 0, (int) $m[1] ) ),
				min( 255, max( 0, (int) $m[2] ) ),
				min( 255, max( 0, (int) $m[3] ) ),
			);
		}
		return null;
	}

	/**
	 * WCAG relative-luminance of an [r, g, b] color (0 = black, 1 = white).
	 *
	 * @param array<int,int> $rgb [R, G, B] integer channels.
	 * @return float Luminance value 0.0 - 1.0.
	 */
	protected static function rgb_luminance( array $rgb ): float {
		$linearize = static function ( $c ) {
			$c = $c / 255;
			return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		};
		return 0.2126 * $linearize( $rgb[0] ) + 0.7152 * $linearize( $rgb[1] ) + 0.0722 * $linearize( $rgb[2] );
	}

	/**
	 * Lighten or darken an RGB color by `$amount` (0.0 - 1.0).
	 *
	 * If `$luminance_aware` is true (default), the direction is chosen
	 * automatically: light colors darken, dark colors lighten. This produces
	 * a "more pressed" hover feel regardless of the base color's hue.
	 *
	 * @param array<int,int> $rgb              Base color [R, G, B].
	 * @param float          $amount           Shift amount, 0.0 - 1.0.
	 * @param bool           $luminance_aware  Auto direction (default true).
	 * @return array<int,int> Shifted [R, G, B].
	 */
	protected static function rgb_shift( array $rgb, float $amount, bool $luminance_aware = true ): array {
		if ( $luminance_aware ) {
			$direction = self::rgb_luminance( $rgb ) > 0.5 ? -1 : 1;
			$amount    = abs( $amount ) * $direction;
		}
		return array_map(
			static function ( $c ) use ( $amount ) {
				if ( $amount > 0 ) {
					return (int) round( $c + ( 255 - $c ) * $amount );
				}
				return (int) round( $c * ( 1 + $amount ) );
			},
			$rgb
		);
	}

	/**
	 * Format an RGB array as a `#rrggbb` hex string.
	 *
	 * @param array<int,int> $rgb [R, G, B] integer channels.
	 * @return string Lowercase 6-char hex with leading `#`.
	 */
	protected static function rgb_to_hex( array $rgb ): string {
		return sprintf( '#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2] );
	}

	/**
	 * Format an RGB array as a comma-separated channel string for use as
	 * `rgba(var(--token-rgb), 0.X)` in CSS.
	 *
	 * @param array<int,int> $rgb [R, G, B] integer channels.
	 * @return string E.g. "171, 193, 35".
	 */
	protected static function rgb_to_csv( array $rgb ): string {
		return implode( ', ', $rgb );
	}

	/**
	 * Pick `#0a0a0a` or `#ffffff` based on which would have ≥4.5:1 contrast
	 * against the given color. Used for text-on-color labels (`-inverse`).
	 *
	 * @param array<int,int> $rgb [R, G, B] integer channels.
	 * @return string Either `#0a0a0a` or `#ffffff`.
	 */
	protected static function contrast_pick( array $rgb ): string {
		$lum      = self::rgb_luminance( $rgb ) + 0.05;
		$vs_white = 1.05 / $lum;
		$vs_dark  = $lum / ( self::rgb_luminance( array( 10, 10, 10 ) ) + 0.05 );
		return $vs_white >= $vs_dark ? '#ffffff' : '#0a0a0a';
	}

	/**
	 * Lighten a colour until it reaches 4.5:1 on the darkest raised surface.
	 *
	 * @param string $color Light-mode colour.
	 * @return string Hex colour readable in dark mode.
	 */
	protected static function readable_on_dark( string $color ): string {
		$rgb = self::color_to_rgb( $color );
		if ( null === $rgb ) {
			return self::DEFAULT_DARK_ACCENT;
		}
		return self::rgb_to_hex( self::contrast_shift( $rgb, self::dark_surface(), 4.5 ) );
	}

	/**
	 * One derived shade of a colour, e.g. 'fill' or 'text', as derive_color_variants() emits it.
	 *
	 * @param string $color   Colour.
	 * @param string $shade   Variant suffix.
	 * @param bool   $on_dark Dark-mode derivation.
	 * @return string Hex colour, or '' when the colour is unparseable.
	 */
	protected static function derived_shade( string $color, string $shade, bool $on_dark ): string {
		return preg_match( '/--x-' . preg_quote( $shade, '/' ) . ':(#[0-9a-f]{6});/', self::derive_color_variants( '--x', $color, $on_dark ), $m ) ? $m[1] : '';
	}

	/**
	 * The lightest dark-mode surface (--bx-color-bg-elevated): the hardest dark
	 * background for a colour to stand out on.
	 *
	 * @return array<int,int> [R, G, B].
	 */
	protected static function dark_surface(): array {
		return array( 22, 22, 22 );
	}

	/**
	 * The darkest light-mode page surface (--bx-color-bg): the hardest light
	 * background for a colour to stand out on.
	 *
	 * @return array<int,int> [R, G, B].
	 */
	protected static function light_surface(): array {
		return array( 247, 247, 249 );
	}

	/**
	 * Move a colour away from a surface (darker on light, lighter on dark) until
	 * it reaches the contrast ratio. Colours that already pass come back unchanged.
	 *
	 * @param array<int,int> $rgb     Colour [R, G, B].
	 * @param array<int,int> $surface Background [R, G, B].
	 * @param float          $ratio   WCAG ratio: 4.5 for text, 3 for focus rings and icons.
	 * @return array<int,int> [R, G, B].
	 */
	protected static function contrast_shift( array $rgb, array $surface, float $ratio ): array {
		$bg     = self::rgb_luminance( $surface ) + 0.05;
		$amount = $bg < 0.5 ? 0.1 : -0.1;
		for ( $steps = 0; $steps < 20; $steps++ ) {
			$fg = self::rgb_luminance( $rgb ) + 0.05;
			if ( max( $fg, $bg ) / min( $fg, $bg ) >= $ratio ) {
				break;
			}
			$rgb = self::rgb_shift( $rgb, $amount, false );
		}
		return $rgb;
	}

	/**
	 * Compare two color strings (hex, rgb(), rgba()) by canonical RGB tuple.
	 *
	 * @param string $a Color value (hex, rgb(), rgba()).
	 * @param string $b Color value (hex, rgb(), rgba()).
	 * @return bool True when the colors resolve to the same RGB triplet AND
	 *              alpha within float-equality tolerance.
	 */
	protected static function colors_canonically_equal( string $a, string $b ): bool {
		$rgb_a = self::color_to_rgba_tuple( $a );
		$rgb_b = self::color_to_rgba_tuple( $b );
		if ( null === $rgb_a || null === $rgb_b ) {
			return false;
		}
		return $rgb_a[0] === $rgb_b[0]
			&& $rgb_a[1] === $rgb_b[1]
			&& $rgb_a[2] === $rgb_b[2]
			&& abs( $rgb_a[3] - $rgb_b[3] ) < 0.01;
	}

	/**
	 * Parse a color string into [R, G, B, A] integers (0-255) + float alpha.
	 *
	 * @param string $color Color value.
	 * @return array{0:int,1:int,2:int,3:float}|null
	 */
	protected static function color_to_rgba_tuple( string $color ): ?array {
		$color = trim( $color );
		if ( preg_match( '/^#([0-9a-f]{6})$/i', $color, $m ) ) {
			return array(
				(int) hexdec( substr( $m[1], 0, 2 ) ),
				(int) hexdec( substr( $m[1], 2, 2 ) ),
				(int) hexdec( substr( $m[1], 4, 2 ) ),
				1.0,
			);
		}
		if ( preg_match( '/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i', $color, $m ) ) {
			return array(
				(int) hexdec( $m[1] . $m[1] ),
				(int) hexdec( $m[2] . $m[2] ),
				(int) hexdec( $m[3] . $m[3] ),
				1.0,
			);
		}
		if ( preg_match( '/^rgba?\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)(?:\s*,\s*([\d.]+))?\s*\)$/i', $color, $m ) ) {
			return array(
				(int) $m[1],
				(int) $m[2],
				(int) $m[3],
				isset( $m[4] ) ? (float) $m[4] : 1.0,
			);
		}
		return null;
	}

	/**
	 * Build the full set of derived variants for a customer-driven base color.
	 *
	 * For a base token like `--bx-color-accent`, emits:
	 *   --bx-color-accent-rgb        171, 193, 35
	 *   --bx-color-accent-hover      shifted 10% (luminance-aware)
	 *   --bx-color-accent-active     shifted 20%
	 *   --bx-color-accent-focus      focus ring: 3:1 on both the light and the dark surface,
	 *                                so one value works in every mode (unchanged when it passes)
	 *   --bx-color-accent-text       brand-coloured text: 4.5:1 on this mode's surface
	 *   --bx-color-accent-bg         rgba(171,193,35,0.08)
	 *   --bx-color-accent-bg-strong  rgba(171,193,35,0.16)
	 *   --bx-color-accent-border     rgba(171,193,35,0.24)
	 *   --bx-color-accent-disabled   shifted 50% (luminance-aware) — washed out
	 *   --bx-color-accent-inverse    contrast-pick #fff or #0a0a0a
	 *   --bx-color-accent-fill       the colour as a fill (lifted to 3:1 on dark only)
	 *   --bx-color-accent-fill-inverse  contrast-pick for the fill
	 *
	 * @param string $base_token_name e.g. `--bx-color-accent`.
	 * @param string $color           Resolved color value (hex or rgba string).
	 * @param bool   $on_dark         True when the declarations go into the dark-mode block.
	 * @return string Concatenated CSS declarations (or empty if color unparseable).
	 */
	protected static function derive_color_variants( string $base_token_name, string $color, bool $on_dark = false ): string {
		$rgb = self::color_to_rgb( $color );
		if ( null === $rgb ) {
			return '';
		}
		$csv    = self::rgb_to_csv( $rgb );
		$decls  = '';
		$decls .= $base_token_name . '-rgb:' . $csv . ';';
		$decls .= $base_token_name . '-hover:' . self::rgb_to_hex( self::rgb_shift( $rgb, 0.10 ) ) . ';';
		$decls .= $base_token_name . '-active:' . self::rgb_to_hex( self::rgb_shift( $rgb, 0.20 ) ) . ';';
		$focus  = self::contrast_shift( self::contrast_shift( $rgb, self::light_surface(), 3.0 ), self::dark_surface(), 3.0 );
		$decls .= $base_token_name . '-focus:' . self::rgb_to_hex( $focus ) . ';';
		$decls .= $base_token_name . '-text:' . self::rgb_to_hex( self::contrast_shift( $rgb, $on_dark ? self::dark_surface() : self::light_surface(), 4.5 ) ) . ';';
		$decls .= $base_token_name . '-bg:rgba(' . $csv . ', 0.08);';
		$decls .= $base_token_name . '-bg-strong:rgba(' . $csv . ', 0.16);';
		$decls .= $base_token_name . '-border:rgba(' . $csv . ', 0.24);';
		$decls .= $base_token_name . '-disabled:' . self::rgb_to_hex( self::rgb_shift( $rgb, 0.40 ) ) . ';';
		$decls .= $base_token_name . '-inverse:' . self::contrast_pick( $rgb ) . ';';
		// Filled shapes (buttons, badges, counts): the colour itself, so a fill looks
		// the same in both modes; on dark it is lifted only when it would vanish on
		// the dark card (under 3:1). The label is the contrast pick for that fill.
		$fill   = $on_dark ? self::contrast_shift( $rgb, self::dark_surface(), 3.0 ) : $rgb;
		$decls .= $base_token_name . '-fill:' . self::rgb_to_hex( $fill ) . ';';
		$decls .= $base_token_name . '-fill-inverse:' . self::contrast_pick( $fill ) . ';';
		return $decls;
	}
}
