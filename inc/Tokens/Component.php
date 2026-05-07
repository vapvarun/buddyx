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

	/**
	 * Framework-supplied derived tokens (no customizer field backing them).
	 * Phase 4 introduces these to support stylesheet cleanup — they cover
	 * neutral roles (mid-tone text, generic borders, dividers, shadows)
	 * that didn't have a dedicated customizer color in 5.0.x but show up
	 * dozens of times across the Tier A foundation stylesheets.
	 *
	 * Always emitted, regardless of the `site_custom_colors` master toggle,
	 * so consumers (theme CSS + plugin compat CSS) can rely on them being
	 * present. Dark-mode overrides live in $dark_defaults below.
	 *
	 * Future 5.2.x: expose to customizer if customers want override control.
	 *
	 * @var array<string, string>
	 */
	protected static array $framework_tokens = array(
		// Foreground (text) extras
		'--bx-color-fg-muted'   => '#757575',                // Mid-tone text
		'--bx-color-fg-subtle'  => '#9ca3af',                // Subtle / placeholder text
		'--bx-color-fg-inverse' => '#ffffff',                // Text on color (light mode default)

		// Surface extras
		'--bx-color-bg-overlay' => 'rgba(0, 0, 0, 0.5)',     // Modal / mobile-menu overlay
		'--bx-color-bg-subtle'  => '#f7f7f9',                // Very-light surface tint

		// Brand / accent extras
		'--bx-color-accent-secondary' => '#3a7882',           // Variation slot (cool teal)
		'--bx-color-accent-tertiary'  => '#f4d35e',           // Variation slot (warm yellow)

		// Structural
		'--bx-color-border'        => '#e8e8e8',              // Generic border
		'--bx-color-border-strong' => '#d4d4d4',              // Stronger border / hr
		'--bx-color-divider'       => '#f0f0f0',              // Subtle divider
		'--bx-color-shadow'        => 'rgba(0, 0, 0, 0.08)',  // Card / popover shadow base
		'--bx-color-shadow-strong' => 'rgba(0, 0, 0, 0.16)',  // Modal / floating panel shadow

		// State (semantic — replaces hardcoded #4caf50, #c0392b, #f1c40f, etc.)
		'--bx-color-success'    => '#16a34a',
		'--bx-color-success-bg' => '#dcfce7',
		'--bx-color-warning'    => '#eab308',
		'--bx-color-warning-bg' => '#fef9c3',
		'--bx-color-error'      => '#dc2626',
		'--bx-color-error-bg'   => '#fee2e2',
		'--bx-color-info'       => '#0284c7',
		'--bx-color-info-bg'    => '#dbeafe',

		// Community / presence (BP / Youzify) — replaces iOS-style hardcoded palette
		'--bx-color-presence-online'  => '#16a34a',
		'--bx-color-presence-away'    => '#eab308',
		'--bx-color-presence-busy'    => '#dc2626',
		'--bx-color-presence-offline' => '#9ca3af',
		'--bx-color-bp-friend'        => '#0284c7',
		'--bx-color-bp-favorite'      => '#dc2626',

		// Forms
		'--bx-color-input-bg'           => '#ffffff',
		'--bx-color-input-border'       => '#d4d4d4',
		'--bx-color-input-focus-border' => '#ef5455',         // Same as accent default
		'--bx-color-input-fg'           => '#1a1a1a',
		'--bx-color-input-placeholder'  => '#9ca3af',

		// Header / Sub-header / Footer extras
		'--bx-color-menu-bg'        => 'transparent',
		'--bx-color-subheader-bg'   => '#fafafa',
		'--bx-color-footer-bg'      => '#fafafa',

		// Dimension — radius extras
		'--bx-radius-card' => '12px',
		'--bx-radius-pill' => '999px',

		// Dimension — spacing
		'--bx-space-section' => 'clamp(40px, 8vw, 80px)',     // Vertical rhythm between sections
		'--bx-space-card'    => '24px',                       // Card / panel padding
		'--bx-space-inline'  => '8px',                        // Inline gap (icon + text, etc.)
		'--bx-space-stack'   => '12px',                       // Vertical gap between siblings

		// Effect — shadow ladder
		'--bx-shadow-card-sm' => '0 1px 2px 0 var(--bx-color-shadow)',
		'--bx-shadow-card-md' => '0 4px 12px -2px var(--bx-color-shadow)',
		'--bx-shadow-card-lg' => '0 12px 32px -4px var(--bx-color-shadow-strong)',

		// Effect — motion
		'--bx-duration-fast' => '120ms',
		'--bx-duration-base' => '200ms',
		'--bx-duration-slow' => '400ms',
		'--bx-easing-base'   => 'cubic-bezier(0.4, 0, 0.2, 1)',
		'--bx-easing-bounce' => 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',

		// Z-index ladder
		'--bx-z-base'           => '1',
		'--bx-z-dropdown'       => '100',
		'--bx-z-sticky-header'  => '999',
		'--bx-z-overlay'        => '9999',
		'--bx-z-loader'         => '999991',
		'--bx-z-toast'          => '999999',
	);

	/**
	 * Dark-mode token overrides. Applied via [data-bx-mode="dark"] :root { ... }
	 * and via @media (prefers-color-scheme: dark) for users in 'auto' mode.
	 *
	 * Values are framework-supplied premium defaults that pass WCAG AA contrast
	 * against `--bx-color-bg` (#0a0a0a). Customer customizer-saved values are
	 * LIGHT-mode values; dark mode uses these defaults. Per-color dark
	 * overrides are deferred to 5.2.1+ when the customizer adds a dark
	 * companion field per role.
	 *
	 * @var array<string, string>
	 */
	protected static array $dark_defaults = array(
		// Brand / accent — slightly brighter red for dark-bg contrast
		'--bx-color-accent'           => '#ff6b6b',
		'--bx-color-button-bg'        => '#ff6b6b',
		'--bx-color-button-bg-hover'  => '#ff8989',
		'--bx-color-button-fg'        => '#0a0a0a',
		'--bx-color-button-fg-hover'  => '#0a0a0a',
		'--bx-color-button-border'    => '#ff6b6b',
		'--bx-color-button-border-hover' => '#ff8989',

		// Surfaces — near-black with gentle elevation
		'--bx-color-bg'          => '#0a0a0a',
		'--bx-color-bg-page'     => '#0a0a0a',
		'--bx-color-bg-elevated' => '#161616',
		'--bx-color-bg-muted'    => '#101010',

		// Text + links
		'--bx-color-fg'         => '#f5f5f5',
		'--bx-color-link'       => '#f5f5f5',
		'--bx-color-link-hover' => '#ff6b6b',

		// Header
		'--bx-color-header-bg'        => '#0a0a0a',
		'--bx-color-site-title'       => '#f5f5f5',
		'--bx-color-site-title-hover' => '#ff6b6b',
		'--bx-color-site-tagline'     => '#a0a0a0',

		// Menu
		'--bx-color-menu-fg'     => '#e5e5e5',
		'--bx-color-menu-hover'  => '#ff6b6b',
		'--bx-color-menu-active' => '#ff6b6b',
		'--bx-color-subheader-fg' => '#f5f5f5',

		// Loader
		'--bx-color-loader-bg' => '#161616',

		// Headings
		'--bx-color-h1' => '#f5f5f5',
		'--bx-color-h2' => '#f5f5f5',
		'--bx-color-h3' => '#f5f5f5',
		'--bx-color-h4' => '#f5f5f5',
		'--bx-color-h5' => '#e5e5e5',
		'--bx-color-h6' => '#e5e5e5',

		// Footer
		'--bx-color-footer-title'      => '#f5f5f5',
		'--bx-color-footer-fg'         => '#a0a0a0',
		'--bx-color-footer-link'       => '#e5e5e5',
		'--bx-color-footer-link-hover' => '#ff6b6b',

		// Copyright
		'--bx-color-copyright-bg'         => '#0a0a0a',
		'--bx-color-copyright-border'     => '#2a2a2a',
		'--bx-color-copyright-fg'         => '#a0a0a0',
		'--bx-color-copyright-link'       => '#e5e5e5',
		'--bx-color-copyright-link-hover' => '#ff6b6b',

		// Framework-supplied derived tokens (Phase 4 cleanup).
		'--bx-color-fg-muted'   => '#a0a0a0',
		'--bx-color-fg-subtle'  => '#6b7280',
		'--bx-color-fg-inverse' => '#0a0a0a',

		// Surface extras (dark)
		'--bx-color-bg-overlay' => 'rgba(0, 0, 0, 0.7)',
		'--bx-color-bg-subtle'  => '#0d0d0d',

		// Brand / accent extras (dark)
		'--bx-color-accent-secondary' => '#5eaeb8',
		'--bx-color-accent-tertiary'  => '#facc15',

		// Structural (dark)
		'--bx-color-border'        => '#2a2a2a',
		'--bx-color-border-strong' => '#3a3a3a',
		'--bx-color-divider'       => '#1a1a1a',
		'--bx-color-shadow'        => 'rgba(0, 0, 0, 0.4)',
		'--bx-color-shadow-strong' => 'rgba(0, 0, 0, 0.6)',

		// State (dark) — slightly brighter for contrast on near-black surfaces
		'--bx-color-success'    => '#22c55e',
		'--bx-color-success-bg' => '#14532d',
		'--bx-color-warning'    => '#facc15',
		'--bx-color-warning-bg' => '#713f12',
		'--bx-color-error'      => '#ef4444',
		'--bx-color-error-bg'   => '#7f1d1d',
		'--bx-color-info'       => '#38bdf8',
		'--bx-color-info-bg'    => '#1e3a8a',

		// Community / presence (dark)
		'--bx-color-presence-online'  => '#22c55e',
		'--bx-color-presence-away'    => '#facc15',
		'--bx-color-presence-busy'    => '#ef4444',
		'--bx-color-presence-offline' => '#6b7280',
		'--bx-color-bp-friend'        => '#38bdf8',
		'--bx-color-bp-favorite'      => '#ef4444',

		// Forms (dark)
		'--bx-color-input-bg'           => '#161616',
		'--bx-color-input-border'       => '#3a3a3a',
		'--bx-color-input-focus-border' => '#ff6b6b',
		'--bx-color-input-fg'           => '#f5f5f5',
		'--bx-color-input-placeholder'  => '#6b7280',

		// Header / Sub-header / Footer extras (dark)
		'--bx-color-subheader-bg' => '#161616',
		'--bx-color-footer-bg'    => '#0a0a0a',

		// theme.json palette overrides — block patterns reference these via
		// .has-{slug}-background-color / .has-{slug}-color helpers, so we have
		// to invert the base/contrast scales for dark mode to take effect on
		// rendered blocks. Accent colors stay similar (slightly brighter for
		// dark contrast).
		'--wp--preset--color--base'      => '#0a0a0a',
		'--wp--preset--color--base-2'    => '#161616',
		'--wp--preset--color--base-3'    => '#1f1f1f',
		'--wp--preset--color--contrast'  => '#f5f5f5',
		'--wp--preset--color--contrast-2'=> '#d0d0d0',
		'--wp--preset--color--contrast-3'=> '#a0a0a0',
		'--wp--preset--color--surface-1' => '#1a1310',
		'--wp--preset--color--surface-2' => '#101a1c',
		'--wp--preset--color--surface-3' => '#0f1419',
		'--wp--preset--color--primary'   => '#ff6b6b',
	);

	public function get_slug(): string {
		return 'tokens';
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'emit_tokens' ), 20 );
		// FOUC-prevention head script: sets <html data-bx-mode> before any
		// CSS loads, so dark-mode users never see a light-mode flash.
		add_action( 'wp_head', array( $this, 'emit_mode_script' ), 1 );
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
		$enabled = \get_theme_mod( 'site_custom_colors', true );
		$mods    = \get_theme_mods();
		$decls   = '';

		// Framework-derived tokens always emit — they back generic neutrals
		// (borders, mid-tone text, shadows) that consumer CSS depends on
		// regardless of whether the customer enabled custom colors.
		foreach ( self::$framework_tokens as $token => $value ) {
			$decls .= $token . ':' . $value . ';';
		}

		// Site Custom Colors master toggle gates the customizer-derived tokens
		// for parity with 5.0.3 behavior. Framework tokens above still emit.
		if ( ! $enabled ) {
			$light_block = ':root{' . $decls . '}';
			$dark_block  = $this->build_dark_block();
			return $light_block . $dark_block;
		}

		// Customer-driven base colors that get the full derived-variants set
		// (-rgb, -hover, -active, -focus, -bg, -bg-strong, -border, -disabled,
		// -inverse). These are the brand colors most consumer CSS reaches for
		// on hover/active/focus/disabled states.
		//
		// Map: customer_field => array( token, default_color ).
		// Default colors mirror _bx-tokens.css so derivation always produces
		// the same variants whether or not customer has saved a value.
		$derive_for = array(
			'site_primary_color'             => array( '--bx-color-accent',       '#ef5455' ),
			'site_buttons_background_color'  => array( '--bx-color-button-bg',    '#ef5455' ),
			'site_links_color'               => array( '--bx-color-link',         '#111111' ),
			'body_background_color'          => array( '--bx-color-bg',           '#ffffff' ),
			'box_background_color'           => array( '--bx-color-bg-elevated',  '#ffffff' ),
			'site_header_bg_color'           => array( '--bx-color-header-bg',    '#ffffff' ),
		);

		// Simple hex color tokens.
		// With the architectural cleanup (5.1.0 source @import dedup), the
		// inline tokens emit is the LAST :root rule in the cascade for every
		// page render — global.min.css declares defaults, then this inline
		// block overrides for customer-saved values. No !important needed.
		foreach ( self::$simple_color_tokens as $mod_key => $cfg ) {
			$value = $mods[ $mod_key ] ?? '';
			$color = '' !== $value ? self::normalize_color( $value ) : '';
			if ( '' !== $color ) {
				$decls .= $cfg['token'] . ':' . $color . ';';
				foreach ( $cfg['aliases'] as $alias ) {
					$decls .= $alias . ':' . $color . ';';
				}
			}
			// Derive variants for every base in $derive_for, regardless of
			// whether customer has saved a value. Falls back to the static
			// default so consumer CSS always finds -rgb/-hover/etc tokens.
			if ( isset( $derive_for[ $mod_key ] ) ) {
				$effective = '' !== $color ? $color : $derive_for[ $mod_key ][1];
				$decls    .= self::derive_color_variants( $derive_for[ $mod_key ][0], $effective );
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

		$light_block = ':root{' . $decls . '}';
		$dark_block  = $this->build_dark_block();

		return $light_block . $dark_block;
	}

	/**
	 * Build the dark-mode override block. Two selectors share the same body:
	 *   :root[data-bx-mode="dark"]                 — explicit user choice
	 *   @media (prefers-color-scheme: dark) :root[data-bx-mode="auto"]
	 *
	 * Each dark-default token also overrides its legacy aliases so any third-
	 * party CSS hooked to `--color-theme-primary` etc. picks up the dark
	 * value (otherwise legacy CSS would render light-mode colors on dark
	 * surfaces — a contrast failure).
	 */
	protected function build_dark_block(): string {
		$alias_lookup = array(); // token => array of aliases.
		foreach ( self::$simple_color_tokens as $cfg ) {
			$alias_lookup[ $cfg['token'] ] = $cfg['aliases'];
		}
		foreach ( self::$typography_color_tokens as $cfg ) {
			$alias_lookup[ $cfg['token'] ] = $cfg['aliases'];
		}
		$dark_decls = '';
		foreach ( self::$dark_defaults as $token => $value ) {
			$dark_decls .= $token . ':' . $value . ';';
			foreach ( ( $alias_lookup[ $token ] ?? array() ) as $alias ) {
				$dark_decls .= $alias . ':' . $value . ';';
			}
		}
		if ( '' === $dark_decls ) {
			return '';
		}
		return ':root[data-bx-mode="dark"]{' . $dark_decls . '}'
			. '@media (prefers-color-scheme:dark){:root[data-bx-mode="auto"]{' . $dark_decls . '}}';
	}

	/**
	 * Emit a tiny inline script that sets <html data-bx-mode="..."> before any
	 * CSS or paint happens — prevents a flash-of-light-mode on dark-mode pages.
	 *
	 * Reads from localStorage so the visitor's choice persists across pages,
	 * falling back to the customizer-configured default (auto/light/dark).
	 */
	public function emit_mode_script() {
		$default = (string) \get_theme_mod( 'site_color_mode', 'light' );
		if ( ! in_array( $default, array( 'auto', 'light', 'dark' ), true ) ) {
			$default = 'light';
		}
		?>
		<script id="buddyx-color-mode-bootstrap">
		(function(){
			try {
				var saved = localStorage.getItem('bx-color-mode');
				var mode = saved || <?php echo \wp_json_encode( $default ); ?>;
				if (mode !== 'auto' && mode !== 'light' && mode !== 'dark') mode = 'light';
				document.documentElement.setAttribute('data-bx-mode', mode);
			} catch (e) {
				document.documentElement.setAttribute('data-bx-mode', <?php echo \wp_json_encode( $default ); ?>);
			}
		})();
		</script>
		<?php
	}

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
		return self::rgb_luminance( $rgb ) > 0.5 ? '#0a0a0a' : '#ffffff';
	}

	/**
	 * Build the full set of derived variants for a customer-driven base color.
	 *
	 * For a base token like `--bx-color-accent`, emits:
	 *   --bx-color-accent-rgb        171, 193, 35
	 *   --bx-color-accent-hover      shifted 10% (luminance-aware)
	 *   --bx-color-accent-active     shifted 20%
	 *   --bx-color-accent-focus      shifted 5%
	 *   --bx-color-accent-bg         rgba(171,193,35,0.08)
	 *   --bx-color-accent-bg-strong  rgba(171,193,35,0.16)
	 *   --bx-color-accent-border     rgba(171,193,35,0.24)
	 *   --bx-color-accent-disabled   shifted 50% (luminance-aware) — washed out
	 *   --bx-color-accent-inverse    contrast-pick #fff or #0a0a0a
	 *
	 * @param string $base_token_name e.g. `--bx-color-accent`.
	 * @param string $color           Resolved color value (hex or rgba string).
	 * @return string Concatenated CSS declarations (or empty if color unparseable).
	 */
	protected static function derive_color_variants( string $base_token_name, string $color ): string {
		$rgb = self::color_to_rgb( $color );
		if ( null === $rgb ) {
			return '';
		}
		$csv = self::rgb_to_csv( $rgb );
		$decls  = '';
		$decls .= $base_token_name . '-rgb:' . $csv . ';';
		$decls .= $base_token_name . '-hover:'    . self::rgb_to_hex( self::rgb_shift( $rgb, 0.10 ) ) . ';';
		$decls .= $base_token_name . '-active:'   . self::rgb_to_hex( self::rgb_shift( $rgb, 0.20 ) ) . ';';
		$decls .= $base_token_name . '-focus:'    . self::rgb_to_hex( self::rgb_shift( $rgb, 0.05 ) ) . ';';
		$decls .= $base_token_name . '-bg:rgba('         . $csv . ', 0.08);';
		$decls .= $base_token_name . '-bg-strong:rgba('  . $csv . ', 0.16);';
		$decls .= $base_token_name . '-border:rgba('     . $csv . ', 0.24);';
		$decls .= $base_token_name . '-disabled:'        . self::rgb_to_hex( self::rgb_shift( $rgb, 0.40 ) ) . ';';
		$decls .= $base_token_name . '-inverse:'         . self::contrast_pick( $rgb ) . ';';
		return $decls;
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
