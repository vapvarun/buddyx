<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Output_Builder — auto-CSS generator.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Output_Builder
 *
 * Iterates every registered field with a non-empty 'output' arg, reads its
 * theme_mod value, and emits inline CSS. Replaces Kirki's auto-CSS feature.
 *
 * Supports:
 *   - element / property / prefix / suffix / units (scalar values)
 *   - typography multi-property declaration block (object values)
 *   - Kirki legacy 'variant' key normalization to 'font-weight'
 */
class Output_Builder {

	/**
	 * Build a single inline CSS string from accumulated fields.
	 *
	 * @param array $fields Field args list from Component::get_fields().
	 * @return string Concatenated CSS (no <style> wrapper).
	 */
	public static function collect( array $fields ): string {
		$css = '';
		foreach ( $fields as $f ) {
			if ( empty( $f['output'] ) || empty( $f['settings'] ) ) {
				continue;
			}
			$value = get_theme_mod( $f['settings'], $f['default'] ?? '' );
			if ( '' === $value || is_null( $value ) ) {
				continue;
			}
			foreach ( (array) $f['output'] as $rule ) {
				$css .= self::rule_to_css( $rule, $value, $f['_type'] ?? '' );
			}
		}
		return $css;
	}

	/**
	 * Render one output rule for one value into a CSS string.
	 *
	 * @param array  $rule  Output rule { element, property, prefix, suffix, units }.
	 * @param mixed  $value Setting value (string, number, or typography array).
	 * @param string $type  Field type (drives default property + special cases).
	 */
	protected static function rule_to_css( array $rule, $value, string $type ): string {
		$element = $rule['element'] ?? '';
		if ( '' === $element ) {
			return '';
		}

		// Typography: multi-property declaration block from a structured array.
		if ( 'typography' === $type && is_array( $value ) ) {
			$decls = self::typography_declarations( $value );
			return $decls ? sprintf( '%s{%s}', $element, $decls ) : '';
		}

		// Background: expand the 6-key array into a multi-declaration block.
		if ( 'background' === $type && is_array( $value ) ) {
			$decls = self::background_declarations( $value );
			return $decls ? sprintf( '%s{%s}', $element, $decls ) : '';
		}

		$property = $rule['property'] ?? self::default_property( $type );
		if ( '' === $property ) {
			return '';
		}

		$prefix = $rule['prefix'] ?? '';
		$suffix = $rule['suffix'] ?? '';
		$units  = $rule['units']  ?? '';

		$rendered = $prefix . $value . $suffix . $units;
		return sprintf( '%s{%s:%s;}', $element, $property, $rendered );
	}

	/**
	 * Build typography CSS declarations from a structured value array.
	 * Accepts both modern keys ('font-weight', 'font-size', etc.) and Kirki
	 * legacy 'variant' (mapped to font-weight, with 'regular' → 400, 'bold' → 700).
	 */
	protected static function typography_declarations( array $value ): string {
		$decls = '';

		// Kirki legacy: 'variant' → font-weight (and possibly font-style for *italic combos).
		if ( ! empty( $value['variant'] ) && empty( $value['font-weight'] ) ) {
			$variant = strtolower( (string) $value['variant'] );
			$weight  = $variant;
			$style   = '';
			if ( str_contains( $variant, 'italic' ) ) {
				$style = 'italic';
				$weight = trim( str_replace( 'italic', '', $variant ) );
			}
			if ( 'regular' === $weight || '' === $weight ) {
				$weight = '400';
			} elseif ( 'bold' === $weight ) {
				$weight = '700';
			}
			$value['font-weight'] = $weight;
			if ( '' !== $style && empty( $value['font-style'] ) ) {
				$value['font-style'] = $style;
			}
		}

		$key_map = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'font-weight'    => 'font-weight',
			'text-transform' => 'text-transform',
			'font-style'     => 'font-style',
		);
		foreach ( $key_map as $k => $css_prop ) {
			if ( ! empty( $value[ $k ] ) ) {
				$decls .= sprintf( '%s:%s;', $css_prop, $value[ $k ] );
			}
		}
		return $decls;
	}

	/**
	 * Build background CSS declarations from a structured value array.
	 * background-image is wrapped in url(...); other keys passed through.
	 */
	protected static function background_declarations( array $value ): string {
		$decls = '';
		$keys  = array(
			'background-color',
			'background-image',
			'background-repeat',
			'background-position',
			'background-size',
			'background-attachment',
		);
		foreach ( $keys as $k ) {
			if ( empty( $value[ $k ] ) ) {
				continue;
			}
			$v = (string) $value[ $k ];
			if ( 'background-image' === $k ) {
				$v = sprintf( "url('%s')", esc_url( $v ) );
			}
			$decls .= sprintf( '%s:%s;', $k, $v );
		}
		return $decls;
	}

	/**
	 * Default CSS property for types that have an obvious one when the rule omits 'property'.
	 */
	protected static function default_property( string $type ): string {
		switch ( $type ) {
			case 'color':
				return 'color';
			case 'dimension':
				return 'width';
			default:
				return '';
		}
	}
}
