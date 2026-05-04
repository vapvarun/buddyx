<?php
/**
 * BuddyX\Buddyx\Tokens\Component class
 *
 * Centralized design-token system. Reads customizer color settings and emits
 * CSS variables with two parallel namespaces:
 *
 *   1. New `--bx-color-*` tokens — semantic names organized by role.
 *      These are the canonical reference; theme.json + 5.2.0+ stylesheets
 *      consume these.
 *
 *   2. Legacy `--color-*` / `--global-*` / `--button-*` aliases — preserved
 *      for backward compatibility with the 5.0.3 stylesheet and any
 *      third-party CSS hooked to those variable names. Aliases are removed
 *      in 5.3.0.
 *
 * Design intent: the theme stylesheet (and any consumer) should reference
 * tokens, not theme_mod values directly. Customer changes to colors flow:
 *
 *   theme_mod  →  Tokens::collect()  →  <style>:root { --bx-color-… : …; }
 *
 * Future color-mode work layers a dark token set on top via
 * [data-bx-mode="dark"] :root { --bx-color-… : …; } overrides without any
 * change to consumer CSS — same tokens, different values.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Tokens;

use BuddyX\Buddyx\Component_Interface;
use function add_action;

defined( 'ABSPATH' ) || exit;

/**
 * Tokens
 */
class Component implements Component_Interface {

	/**
	 * Token map: theme_mod_key => array(
	 *   'token' => primary --bx-color-* name,
	 *   'aliases' => array of legacy variable names to emit as well,
	 * )
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $simple_color_tokens = array(
		// Brand / accent
		'site_primary_color'                  => array( 'token' => '--bx-color-accent',           'aliases' => array( '--color-theme-primary' ) ),
		'site_buttons_background_color'       => array( 'token' => '--bx-color-button-bg',        'aliases' => array( '--button-background-color' ) ),
		'site_buttons_background_hover_color' => array( 'token' => '--bx-color-button-bg-hover',  'aliases' => array( '--button-background-hover-color' ) ),
		'site_buttons_text_color'             => array( 'token' => '--bx-color-button-fg',        'aliases' => array( '--button-text-color' ) ),
		'site_buttons_text_hover_color'       => array( 'token' => '--bx-color-button-fg-hover',  'aliases' => array( '--button-text-hover-color' ) ),
		'site_buttons_border_color'           => array( 'token' => '--bx-color-button-border',    'aliases' => array( '--button-border-color' ) ),
		'site_buttons_border_hover_color'     => array( 'token' => '--bx-color-button-border-hover', 'aliases' => array( '--button-border-hover-color' ) ),

		// Surfaces (page / containers)
		'body_background_color'      => array( 'token' => '--bx-color-bg',          'aliases' => array( '--color-theme-body' ) ),
		'content_background_color'   => array( 'token' => '--bx-color-bg-page',     'aliases' => array( '--color-layout-boxed' ) ),
		'box_background_color'       => array( 'token' => '--bx-color-bg-elevated', 'aliases' => array( '--color-theme-white-box' ) ),
		'secondary_background_color' => array( 'token' => '--bx-color-bg-muted',    'aliases' => array( '--global-body-lightcolor' ) ),

		// Text + links
		'site_links_color'             => array( 'token' => '--bx-color-link',       'aliases' => array( '--color-link' ) ),
		'site_links_focus_hover_color' => array( 'token' => '--bx-color-link-hover', 'aliases' => array( '--color-link-hover' ) ),

		// Header
		'site_header_bg_color'   => array( 'token' => '--bx-color-header-bg',    'aliases' => array( '--color-header-bg' ) ),
		'site_title_hover_color' => array( 'token' => '--bx-color-site-title-hover', 'aliases' => array( '--color-site-title-hover' ) ),

		// Menu
		'menu_hover_color'  => array( 'token' => '--bx-color-menu-hover',  'aliases' => array( '--color-menu-hover' ) ),
		'menu_active_color' => array( 'token' => '--bx-color-menu-active', 'aliases' => array( '--color-menu-active' ) ),

		// Loader
		'site_loader_bg' => array( 'token' => '--bx-color-loader-bg', 'aliases' => array( '--color-theme-loader' ) ),

		// Footer
		'site_footer_title_color'       => array( 'token' => '--bx-color-footer-title',       'aliases' => array( '--color-footer-title' ) ),
		'site_footer_content_color'     => array( 'token' => '--bx-color-footer-fg',          'aliases' => array( '--color-footer-content' ) ),
		'site_footer_links_color'       => array( 'token' => '--bx-color-footer-link',        'aliases' => array( '--color-footer-link' ) ),
		'site_footer_links_hover_color' => array( 'token' => '--bx-color-footer-link-hover',  'aliases' => array( '--color-footer-link-hover' ) ),

		// Copyright
		'site_copyright_background_color'  => array( 'token' => '--bx-color-copyright-bg',         'aliases' => array( '--color-copyright-bg' ) ),
		'site_copyright_border_color'      => array( 'token' => '--bx-color-copyright-border',     'aliases' => array() ),
		'site_copyright_content_color'     => array( 'token' => '--bx-color-copyright-fg',         'aliases' => array( '--color-copyright-content' ) ),
		'site_copyright_links_color'       => array( 'token' => '--bx-color-copyright-link',       'aliases' => array( '--color-copyright-link' ) ),
		'site_copyright_links_hover_color' => array( 'token' => '--bx-color-copyright-link-hover', 'aliases' => array( '--color-copyright-link-hover' ) ),
	);

	/**
	 * Typography sub-key 'color' tokens. Map theme_mod array key => token+aliases.
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $typography_color_tokens = array(
		'site_title_typography_option'   => array( 'token' => '--bx-color-site-title',     'aliases' => array( '--color-site-title' ) ),
		'site_tagline_typography_option' => array( 'token' => '--bx-color-site-tagline',   'aliases' => array( '--color-site-tagline' ) ),
		'menu_typography_option'         => array( 'token' => '--bx-color-menu-fg',        'aliases' => array( '--color-menu' ) ),
		'site_sub_header_typography'     => array( 'token' => '--bx-color-subheader-fg',   'aliases' => array( '--color-subheader-title' ) ),
		'typography_option'              => array( 'token' => '--bx-color-fg',             'aliases' => array( '--global-font-color' ) ),
		'h1_typography_option'           => array( 'token' => '--bx-color-h1', 'aliases' => array( '--color-h1' ) ),
		'h2_typography_option'           => array( 'token' => '--bx-color-h2', 'aliases' => array( '--color-h2' ) ),
		'h3_typography_option'           => array( 'token' => '--bx-color-h3', 'aliases' => array( '--color-h3' ) ),
		'h4_typography_option'           => array( 'token' => '--bx-color-h4', 'aliases' => array( '--color-h4' ) ),
		'h5_typography_option'           => array( 'token' => '--bx-color-h5', 'aliases' => array( '--color-h5' ) ),
		'h6_typography_option'           => array( 'token' => '--bx-color-h6', 'aliases' => array( '--color-h6' ) ),
	);

	/**
	 * Radius/dimension tokens. Map theme_mod key => token+aliases.
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $dimension_tokens = array(
		'site_global_border_radius' => array( 'token' => '--bx-radius-global', 'aliases' => array( '--global-border-radius' ) ),
		'site_button_border_radius' => array( 'token' => '--bx-radius-button', 'aliases' => array( '--button-border-radius' ) ),
		'site_form_border_radius'   => array( 'token' => '--bx-radius-form',   'aliases' => array( '--form-border-radius' ) ),
	);

	public function get_slug(): string {
		return 'tokens';
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'emit_tokens' ), 20 );
	}

	/**
	 * Build the CSS variable block and attach it as inline style on the
	 * 'buddyx-global' stylesheet. Replaces the 5.0.3 Dynamic_Style emission.
	 */
	public function emit_tokens() {
		$css = $this->build_token_css();
		if ( '' !== $css ) {
			\wp_add_inline_style( 'buddyx-global', $css );
		}
	}

	/**
	 * Build the full :root { … } CSS string from theme_mods.
	 * Public so Dynamic_Style/test code can call it directly.
	 *
	 * @return string CSS text (already includes the :root selector).
	 */
	public function build_token_css(): string {
		// Site Custom Colors master toggle gates token emission for parity
		// with 5.0.3 behavior.
		$enabled = \get_theme_mod( 'site_custom_colors', true );
		if ( ! $enabled ) {
			return '';
		}

		$mods = \get_theme_mods();
		$decls = '';

		// Simple hex color tokens.
		foreach ( self::$simple_color_tokens as $mod_key => $cfg ) {
			$value = $mods[ $mod_key ] ?? '';
			if ( '' === $value ) {
				continue;
			}
			// Color customizer values can be hex or rgba; we accept both via a
			// tolerant matcher that lets common CSS color formats through.
			$color = self::normalize_color( $value );
			if ( '' === $color ) {
				continue;
			}
			$decls .= $cfg['token'] . ':' . $color . ';';
			foreach ( $cfg['aliases'] as $alias ) {
				$decls .= $alias . ':' . $color . ';';
			}
		}

		// Typography sub-key 'color' tokens.
		foreach ( self::$typography_color_tokens as $mod_key => $cfg ) {
			$value = $mods[ $mod_key ] ?? array();
			$color_val = is_array( $value ) ? ( $value['color'] ?? '' ) : '';
			if ( '' === $color_val ) {
				continue;
			}
			$color = self::normalize_color( $color_val );
			if ( '' === $color ) {
				continue;
			}
			$decls .= $cfg['token'] . ':' . $color . ';';
			foreach ( $cfg['aliases'] as $alias ) {
				$decls .= $alias . ':' . $color . ';';
			}
		}

		// Radius/dimension tokens.
		foreach ( self::$dimension_tokens as $mod_key => $cfg ) {
			$value = $mods[ $mod_key ] ?? '';
			if ( '' === $value || ! is_string( $value ) ) {
				continue;
			}
			$value = \sanitize_text_field( $value );
			if ( '' === $value ) {
				continue;
			}
			$decls .= $cfg['token'] . ':' . $value . ';';
			foreach ( $cfg['aliases'] as $alias ) {
				$decls .= $alias . ':' . $value . ';';
			}
		}

		if ( '' === $decls ) {
			return '';
		}

		// Wrap in :root for the new tokens; legacy stylesheets that targeted
		// `body` for the same vars still resolve because :root cascades down.
		return ':root{' . $decls . '}';
	}

	/**
	 * Normalize a color value (hex or rgb/rgba string) to a safe CSS string.
	 * Returns '' if the value is unrecognized.
	 */
	protected static function normalize_color( string $value ): string {
		$value = trim( $value );
		// Hex (3, 4, 6, or 8 chars).
		if ( preg_match( '/^#([0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $value ) ) {
			return strtolower( $value );
		}
		// rgb()/rgba()/hsl()/hsla() — pass through if syntactically reasonable.
		if ( preg_match( '/^(rgba?|hsla?)\s*\(\s*[\d\s,.\-%\/]+\s*\)$/i', $value ) ) {
			return $value;
		}
		// CSS keyword (transparent, currentColor, etc.).
		if ( preg_match( '/^[a-z]{3,32}$/i', $value ) ) {
			return $value;
		}
		return '';
	}

	/**
	 * Public read-only accessors (for tests and the future docs generator).
	 */
	public static function get_simple_color_tokens(): array {
		return self::$simple_color_tokens;
	}
	public static function get_typography_color_tokens(): array {
		return self::$typography_color_tokens;
	}
	public static function get_dimension_tokens(): array {
		return self::$dimension_tokens;
	}
}
