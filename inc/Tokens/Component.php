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

require_once dirname( __DIR__ ) . '/Shared/Color_Math.php';

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Shared\Color_Math;
use function add_action;

defined( 'ABSPATH' ) || exit;

/**
 * Tokens
 */
class Component implements Component_Interface {

	use Color_Math;

	/**
	 * Theme default brand colour (light mode).
	 */
	const DEFAULT_ACCENT = '#c92a2b';

	/**
	 * Theme default brand colour in dark mode, used until the owner picks a Primary.
	 */
	const DEFAULT_DARK_ACCENT = '#ff6b6b';


	/**
	 * Token map: theme_mod_key => array(
	 *   'token' => primary --bx-color-* name,
	 *   'aliases' => array of legacy variable names to emit as well,
	 * )
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $simple_color_tokens = array(
		// Brand / accent.
		'site_primary_color'                  => array(
			'token'   => '--bx-color-accent',
			'aliases' => array( '--color-theme-primary' ),
		),
		'site_buttons_background_color'       => array(
			'token'   => '--bx-color-button-bg',
			'aliases' => array( '--button-background-color' ),
		),
		'site_buttons_background_hover_color' => array(
			'token'   => '--bx-color-button-bg-hover',
			'aliases' => array( '--button-background-hover-color' ),
		),
		'site_buttons_text_color'             => array(
			'token'   => '--bx-color-button-fg',
			'aliases' => array( '--button-text-color' ),
		),
		'site_buttons_text_hover_color'       => array(
			'token'   => '--bx-color-button-fg-hover',
			'aliases' => array( '--button-text-hover-color' ),
		),
		'site_buttons_border_color'           => array(
			'token'   => '--bx-color-button-border',
			'aliases' => array( '--button-border-color' ),
		),
		'site_buttons_border_hover_color'     => array(
			'token'   => '--bx-color-button-border-hover',
			'aliases' => array( '--button-border-hover-color' ),
		),

		// Surfaces (page / containers).
		'body_background_color'               => array(
			'token'   => '--bx-color-bg',
			'aliases' => array( '--color-theme-body' ),
		),
		'content_background_color'            => array(
			'token'   => '--bx-color-bg-page',
			'aliases' => array( '--color-layout-boxed' ),
		),
		'box_background_color'                => array(
			'token'   => '--bx-color-bg-elevated',
			'aliases' => array( '--color-theme-white-box' ),
		),
		'secondary_background_color'          => array(
			'token'   => '--bx-color-bg-muted',
			'aliases' => array( '--global-body-lightcolor' ),
		),

		// Text + links.
		'site_links_color'                    => array(
			'token'   => '--bx-color-link',
			'aliases' => array( '--color-link' ),
		),
		'site_links_focus_hover_color'        => array(
			'token'   => '--bx-color-link-hover',
			'aliases' => array( '--color-link-hover' ),
		),

		// Header.
		'site_header_bg_color'                => array(
			'token'   => '--bx-color-header-bg',
			'aliases' => array( '--color-header-bg' ),
		),
		'site_title_hover_color'              => array(
			'token'   => '--bx-color-site-title-hover',
			'aliases' => array( '--color-site-title-hover' ),
		),

		// Menu.
		'menu_hover_color'                    => array(
			'token'   => '--bx-color-menu-hover',
			'aliases' => array( '--color-menu-hover' ),
		),
		'menu_active_color'                   => array(
			'token'   => '--bx-color-menu-active',
			'aliases' => array( '--color-menu-active' ),
		),

		// Loader.
		'site_loader_bg'                      => array(
			'token'   => '--bx-color-loader-bg',
			'aliases' => array( '--color-theme-loader' ),
		),

		// Footer.
		'site_footer_title_color'             => array(
			'token'   => '--bx-color-footer-title',
			'aliases' => array( '--color-footer-title' ),
		),
		'site_footer_content_color'           => array(
			'token'   => '--bx-color-footer-fg',
			'aliases' => array( '--color-footer-content' ),
		),
		'site_footer_links_color'             => array(
			'token'   => '--bx-color-footer-link',
			'aliases' => array( '--color-footer-link' ),
		),
		'site_footer_links_hover_color'       => array(
			'token'   => '--bx-color-footer-link-hover',
			'aliases' => array( '--color-footer-link-hover' ),
		),

		// Copyright.
		'site_copyright_background_color'     => array(
			'token'   => '--bx-color-copyright-bg',
			'aliases' => array( '--color-copyright-bg' ),
		),
		'site_copyright_border_color'         => array(
			'token'   => '--bx-color-copyright-border',
			'aliases' => array(),
		),
		'site_copyright_content_color'        => array(
			'token'   => '--bx-color-copyright-fg',
			'aliases' => array( '--color-copyright-content' ),
		),
		'site_copyright_links_color'          => array(
			'token'   => '--bx-color-copyright-link',
			'aliases' => array( '--color-copyright-link' ),
		),
		'site_copyright_links_hover_color'    => array(
			'token'   => '--bx-color-copyright-link-hover',
			'aliases' => array( '--color-copyright-link-hover' ),
		),
	);

	/**
	 * Typography sub-key 'color' tokens. Map theme_mod array key => token+aliases.
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $typography_color_tokens = array(
		'site_title_typography_option'   => array(
			'token'   => '--bx-color-site-title',
			'aliases' => array( '--color-site-title' ),
		),
		'site_tagline_typography_option' => array(
			'token'   => '--bx-color-site-tagline',
			'aliases' => array( '--color-site-tagline' ),
		),
		'menu_typography_option'         => array(
			'token'   => '--bx-color-menu-fg',
			'aliases' => array( '--color-menu' ),
		),
		'site_sub_header_typography'     => array(
			'token'   => '--bx-color-subheader-fg',
			'aliases' => array( '--color-subheader-title' ),
		),
		'typography_option'              => array(
			'token'   => '--bx-color-fg',
			'aliases' => array( '--global-font-color' ),
		),
		'h1_typography_option'           => array(
			'token'   => '--bx-color-h1',
			'aliases' => array( '--color-h1' ),
		),
		'h2_typography_option'           => array(
			'token'   => '--bx-color-h2',
			'aliases' => array( '--color-h2' ),
		),
		'h3_typography_option'           => array(
			'token'   => '--bx-color-h3',
			'aliases' => array( '--color-h3' ),
		),
		'h4_typography_option'           => array(
			'token'   => '--bx-color-h4',
			'aliases' => array( '--color-h4' ),
		),
		'h5_typography_option'           => array(
			'token'   => '--bx-color-h5',
			'aliases' => array( '--color-h5' ),
		),
		'h6_typography_option'           => array(
			'token'   => '--bx-color-h6',
			'aliases' => array( '--color-h6' ),
		),
	);

	/**
	 * Radius/dimension tokens. Map theme_mod key => token+aliases.
	 *
	 * @var array<string, array{token:string, aliases:array<int,string>}>
	 */
	protected static array $dimension_tokens = array(
		'site_global_border_radius' => array(
			'token'   => '--bx-radius-global',
			'aliases' => array( '--global-border-radius' ),
		),
		'site_button_border_radius' => array(
			'token'   => '--bx-radius-button',
			'aliases' => array( '--button-border-radius' ),
		),
		'site_form_border_radius'   => array(
			'token'   => '--bx-radius-form',
			'aliases' => array( '--form-border-radius' ),
		),
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
		// Customer-facing role defaults. Mirror the registered customizer
		// field defaults so the no-preset, no-save look matches 5.0.x. The
		// style-variation overlay (Phase 7) and customer saves both layer on
		// top of these and remain authoritative.
		'--bx-color-bg'                   => '#f7f7f9',
		'--bx-color-bg-page'              => '#f7f7f9',
		'--bx-color-bg-elevated'          => '#ffffff',
		'--bx-color-bg-muted'             => '#fafafa',
		'--bx-color-fg'                   => '#505050',
		'--bx-color-header-bg'            => '#ffffff',
		'--bx-color-site-title'           => '#111111',
		'--bx-color-site-title-hover'     => 'var(--bx-color-accent)',
		'--bx-color-site-tagline'         => '#757575',
		'--bx-color-menu-fg'              => '#111111',
		'--bx-color-menu-hover'           => 'var(--bx-color-accent)',
		'--bx-color-menu-active'          => 'var(--bx-color-accent)',
		'--bx-color-subheader-fg'         => '#111111',
		'--bx-color-h1'                   => '#111111',
		'--bx-color-h2'                   => '#111111',
		'--bx-color-h3'                   => '#111111',
		'--bx-color-h4'                   => '#111111',
		'--bx-color-h5'                   => '#111111',
		'--bx-color-h6'                   => '#111111',
		'--bx-color-accent'               => self::DEFAULT_ACCENT,
		'--bx-color-link'                 => '#111111',
		'--bx-color-link-hover'           => 'var(--bx-color-accent)',
		'--bx-color-button-bg'            => 'var(--bx-color-accent)',
		'--bx-color-button-bg-hover'      => 'var(--bx-color-accent-hover)',
		'--bx-color-button-fg'            => '#ffffff',
		'--bx-color-button-fg-hover'      => '#ffffff',
		// Border mirrors the button background so a solid button stays solid.
		// `--bx-color-button-border` is not in $derive_for, so absent an explicit
		// `site_buttons_border_color`, only this default emits — hardcoding a hex
		// orphaned the border at red while a brand change / style-variation
		// repainted --bx-color-button-bg (red outline on a blue button).
		// Referencing the bg token makes the border follow the brand; a customer
		// who sets a distinct border color still wins (simple-token loop emits it
		// after this).
		'--bx-color-button-border'        => 'var(--bx-color-button-bg)',
		'--bx-color-button-border-hover'  => 'var(--bx-color-button-bg-hover)',
		'--bx-color-loader-bg'            => 'var(--bx-color-accent)',
		'--bx-color-footer-title'         => '#111111',
		'--bx-color-footer-fg'            => '#505050',
		'--bx-color-footer-link'          => '#111111',
		'--bx-color-footer-link-hover'    => 'var(--bx-color-accent)',
		'--bx-color-copyright-bg'         => '#ffffff',
		'--bx-color-copyright-border'     => '#e8e8e8',
		'--bx-color-copyright-fg'         => '#505050',
		'--bx-color-copyright-link'       => '#111111',
		'--bx-color-copyright-link-hover' => 'var(--bx-color-accent)',

		// Foreground (text) extras.
		'--bx-color-fg-muted'             => '#6a6a6a',                // Mid-tone text (5:1 on #fff for WCAG AA; #757575 was 4.48:1).
		'--bx-color-fg-subtle'            => '#9ca3af',                // Subtle / placeholder text.
		'--bx-color-fg-inverse'           => '#ffffff',                // Text on color (light mode default).

		// Surface extras.
		'--bx-color-bg-overlay'           => 'rgba(0, 0, 0, 0.5)',     // Modal / mobile-menu overlay.
		'--bx-color-bg-subtle'            => '#f7f7f9',                // Very-light surface tint.

		// Brand / accent extras.
		'--bx-color-accent-secondary'     => '#3a7882',           // Variation slot (cool teal).
		'--bx-color-accent-tertiary'      => '#f4d35e',           // Variation slot (warm yellow).

		// Structural.
		'--bx-color-border'               => '#e8e8e8',              // Generic border.
		'--bx-color-border-strong'        => '#d4d4d4',              // Stronger border / hr.
		'--bx-color-divider'              => '#f0f0f0',              // Subtle divider.
		'--bx-color-shadow'               => 'rgba(0, 0, 0, 0.08)',  // Card / popover shadow base.
		'--bx-color-shadow-strong'        => 'rgba(0, 0, 0, 0.16)',  // Modal / floating panel shadow.

		// State (semantic — replaces hardcoded #4caf50, #c0392b, #f1c40f, etc.).
		'--bx-color-success'              => '#16a34a',
		'--bx-color-success-bg'           => '#dcfce7',
		'--bx-color-warning'              => '#eab308',
		'--bx-color-warning-bg'           => '#fef9c3',
		'--bx-color-error'                => '#b91c1c', // 6:1 on the #f7f7f9 page (#dc2626 was 4.48:1).
		'--bx-color-error-bg'             => '#fee2e2',
		'--bx-color-info'                 => '#0284c7',
		'--bx-color-info-bg'              => '#dbeafe',

		// Community / presence (BuddyPress) — keeps legacy Material green for parity with master.
		'--bx-color-presence-online'      => '#4caf50',
		'--bx-color-presence-away'        => '#eab308',
		'--bx-color-presence-busy'        => '#dc2626',
		'--bx-color-presence-offline'     => '#9ca3af',
		'--bx-color-bp-friend'            => '#0284c7',
		'--bx-color-bp-favorite'          => '#dc2626',

		// Forms.
		'--bx-color-input-bg'             => '#ffffff',
		'--bx-color-input-border'         => '#d4d4d4',
		'--bx-color-input-focus-border'   => 'var(--bx-color-accent)',
		'--bx-color-input-fg'             => '#1a1a1a',
		'--bx-color-input-placeholder'    => '#9ca3af',

		// Header / Sub-header / Footer extras.
		'--bx-color-menu-bg'              => 'transparent',
		'--bx-color-subheader-bg'         => '#f3f4f6', // Slightly lighter than body for visual band; was rgba(255,255,255,0.5) in 5.0.x — same visual intent.
		'--bx-color-footer-bg'            => '#fafafa',

		// Dimension — radius extras.
		'--bx-radius-card'                => '12px',
		'--bx-radius-pill'                => '999px',

		// Dimension — spacing.
		'--bx-space-section'              => 'clamp(40px, 8vw, 80px)',     // Vertical rhythm between sections.
		'--bx-space-card'                 => '24px',                       // Card / panel padding.
		'--bx-space-inline'               => '8px',                        // Inline gap (icon + text, etc.).
		'--bx-space-stack'                => '12px',                       // Vertical gap between siblings.

		// Effect — shadow ladder.
		'--bx-shadow-card-sm'             => '0 1px 2px 0 var(--bx-color-shadow)',
		'--bx-shadow-card-md'             => '0 4px 12px -2px var(--bx-color-shadow)',
		'--bx-shadow-card-lg'             => '0 12px 32px -4px var(--bx-color-shadow-strong)',

		// Effect — motion.
		'--bx-duration-fast'              => '120ms',
		'--bx-duration-base'              => '200ms',
		'--bx-duration-slow'              => '400ms',
		'--bx-easing-base'                => 'cubic-bezier(0.4, 0, 0.2, 1)',
		'--bx-easing-bounce'              => 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',

		// Z-index ladder.
		'--bx-z-base'                     => '1',
		'--bx-z-dropdown'                 => '100',
		'--bx-z-sticky-header'            => '999',
		'--bx-z-overlay'                  => '9999',
		'--bx-z-loader'                   => '999991',
		'--bx-z-toast'                    => '999999',
	);

	/**
	 * Legacy custom-property aliases for the canonical --bx-color-* /
	 * --bx-radius-* tokens.
	 *
	 * Audit on 5.1.0 surfaced ~830 legacy --color-* / --global-* var
	 * references in source CSS that the simple_color_tokens /
	 * typography_color_tokens alias mechanism doesn't cover (those
	 * only fire for customer-customized tokens; framework_tokens emit
	 * unconditionally but had no alias plumbing).
	 *
	 * Each entry maps a legacy var to a token. build_token_css emits
	 * each alias as `--legacy: var(--bx-token);` in both :root (light)
	 * and the dark block so the legacy var flips colour-mode through
	 * the canonical token. 3rd-party integrations and legacy
	 * stylesheets keep working without per-file edits.
	 *
	 * @var array<string, string> legacy alias => canonical bx token
	 */
	protected static array $legacy_aliases = array(
		// Structural.
		'--global-border-color'        => '--bx-color-border',
		'--global-border-radius'       => '--bx-radius-global',
		'--global-border-radius-inner' => '--bx-radius-global',
		// Type.
		'--global-title-color'         => '--bx-color-h1',
		'--color-h1'                   => '--bx-color-h1',
		'--color-h2'                   => '--bx-color-h2',
		'--color-h3'                   => '--bx-color-h3',
		'--color-h4'                   => '--bx-color-h4',
		'--color-h5'                   => '--bx-color-h5',
		'--color-h6'                   => '--bx-color-h6',
		// Surface.
		'--color-theme-grey'           => '--bx-color-fg-muted', // A text grey since 5.0 (see _custom-properties.css); never a surface.
		'--color-block-bg-subtle'      => '--bx-color-bg-muted',
		'--color-secondary-bg'         => '--bx-color-bg-muted',
		// Body / meta text.
		'--color-body-text'            => '--bx-color-fg',
		'--color-meta'                 => '--bx-color-fg-muted',
		'--color-neutral'              => '--bx-color-fg-muted',
		// HR / divider.
		'--color-hr'                   => '--bx-color-divider',
		// Quote.
		'--color-quote-border'         => '--bx-color-accent',
		'--color-quote-citation'       => '--bx-color-fg-muted',
		// Menu / panel (not already in alias map of simple_color_tokens).
		'--color-menu'                 => '--bx-color-menu-fg',
		'--color-menu-hover'           => '--bx-color-menu-hover',
		'--color-menu-active'          => '--bx-color-menu-active',
		'--color-panel-bg'             => '--bx-color-bg-elevated',
		'--color-panel-bg-hover'       => '--bx-color-bg-muted',
		'--color-panel-bg-active'      => '--bx-color-bg-muted',
		'--color-panel-menu'           => '--bx-color-menu-fg',
		'--color-panel-menu-hover'     => '--bx-color-menu-hover',
		'--color-panel-menu-active'    => '--bx-color-menu-active',
		// Footer / copyright (catches references that bypass simple_color_tokens).
		'--color-footer-bg'            => '--bx-color-footer-bg',
		'--color-footer-content'       => '--bx-color-footer-fg',
		'--color-footer-link'          => '--bx-color-footer-link',
		'--color-footer-link-hover'    => '--bx-color-footer-link-hover',
		'--color-footer-title'         => '--bx-color-footer-title',
		'--color-copyright-bg'         => '--bx-color-copyright-bg',
		'--color-copyright-content'    => '--bx-color-copyright-fg',
		'--color-copyright-link'       => '--bx-color-copyright-link',
		'--color-copyright-link-hover' => '--bx-color-copyright-link-hover',
	);

	/**
	 * Dark-mode token overrides. Applied via :root[data-bx-mode="dark"] { ... }.
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
		// Brand / accent: the colours come from dark_brand_tokens(), derived from
		// the owner's Primary (and Buttons colour), so these only point at them.
		'--bx-color-button-fg'            => 'var(--bx-color-button-bg-inverse)',
		'--bx-color-button-fg-hover'      => 'var(--bx-color-button-bg-inverse)',
		'--bx-color-button-border'        => 'var(--bx-color-button-bg)',
		'--bx-color-button-border-hover'  => 'var(--bx-color-button-bg-hover)',

		// Surfaces — near-black with gentle elevation.
		'--bx-color-bg'                   => '#0a0a0a',
		'--bx-color-bg-page'              => '#0a0a0a',
		'--bx-color-bg-elevated'          => '#161616',
		'--bx-color-bg-muted'             => '#101010',

		// Text + links.
		'--bx-color-fg'                   => '#f5f5f5',
		'--bx-color-link'                 => '#f5f5f5',
		'--bx-color-link-hover'           => 'var(--bx-color-accent)',

		// Header.
		'--bx-color-header-bg'            => '#0a0a0a',
		'--bx-color-site-title'           => '#f5f5f5',
		'--bx-color-site-title-hover'     => 'var(--bx-color-accent)',
		'--bx-color-site-tagline'         => '#a0a0a0',

		// Menu.
		'--bx-color-menu-fg'              => '#e5e5e5',
		'--bx-color-menu-hover'           => 'var(--bx-color-accent)',
		'--bx-color-menu-active'          => 'var(--bx-color-accent)',
		'--bx-color-subheader-fg'         => '#f5f5f5',

		// Loader.
		'--bx-color-loader-bg'            => '#161616',

		// Headings.
		'--bx-color-h1'                   => '#f5f5f5',
		'--bx-color-h2'                   => '#f5f5f5',
		'--bx-color-h3'                   => '#f5f5f5',
		'--bx-color-h4'                   => '#f5f5f5',
		'--bx-color-h5'                   => '#e5e5e5',
		'--bx-color-h6'                   => '#e5e5e5',

		// Footer.
		'--bx-color-footer-title'         => '#f5f5f5',
		'--bx-color-footer-fg'            => '#a0a0a0',
		'--bx-color-footer-link'          => '#e5e5e5',
		'--bx-color-footer-link-hover'    => 'var(--bx-color-accent)',

		// Copyright.
		'--bx-color-copyright-bg'         => '#0a0a0a',
		'--bx-color-copyright-border'     => '#2a2a2a',
		'--bx-color-copyright-fg'         => '#a0a0a0',
		'--bx-color-copyright-link'       => '#e5e5e5',
		'--bx-color-copyright-link-hover' => 'var(--bx-color-accent)',

		// Framework-supplied derived tokens (Phase 4 cleanup).
		'--bx-color-fg-muted'             => '#a0a0a0',
		'--bx-color-fg-subtle'            => '#6b7280',
		'--bx-color-fg-inverse'           => '#0a0a0a',

		// Surface extras (dark).
		'--bx-color-bg-overlay'           => 'rgba(0, 0, 0, 0.7)',
		'--bx-color-bg-subtle'            => '#0d0d0d',

		// Brand / accent extras (dark).
		'--bx-color-accent-secondary'     => '#5eaeb8',
		'--bx-color-accent-tertiary'      => '#facc15',

		// Structural (dark).
		'--bx-color-border'               => '#2a2a2a',
		'--bx-color-border-strong'        => '#3a3a3a',
		'--bx-color-divider'              => '#1a1a1a',
		'--bx-color-shadow'               => 'rgba(0, 0, 0, 0.4)',
		'--bx-color-shadow-strong'        => 'rgba(0, 0, 0, 0.6)',

		// State (dark) — slightly brighter for contrast on near-black surfaces.
		'--bx-color-success'              => '#22c55e',
		'--bx-color-success-bg'           => '#14532d',
		'--bx-color-warning'              => '#facc15',
		'--bx-color-warning-bg'           => '#713f12',
		'--bx-color-error'                => '#ef4444',
		'--bx-color-error-bg'             => '#7f1d1d',
		'--bx-color-info'                 => '#38bdf8',
		'--bx-color-info-bg'              => '#1e3a8a',

		// Community / presence (dark).
		'--bx-color-presence-online'      => '#4ade80',
		'--bx-color-presence-away'        => '#facc15',
		'--bx-color-presence-busy'        => '#ef4444',
		'--bx-color-presence-offline'     => '#6b7280',
		'--bx-color-bp-friend'            => '#38bdf8',
		'--bx-color-bp-favorite'          => '#ef4444',

		// Forms (dark).
		'--bx-color-input-bg'             => '#161616',
		'--bx-color-input-border'         => '#3a3a3a',
		'--bx-color-input-focus-border'   => 'var(--bx-color-accent)',
		'--bx-color-input-fg'             => '#f5f5f5',
		'--bx-color-input-placeholder'    => '#6b7280',

		// Header / Sub-header / Footer extras (dark).
		'--bx-color-subheader-bg'         => '#1c1c1c', // Distinct from body (#0a) and elevated cards (#16) for visual band.
		'--bx-color-footer-bg'            => '#0a0a0a',

		// theme.json palette overrides — block patterns reference these via.
		// .has-{slug}-background-color / .has-{slug}-color helpers, so we have
		// to invert the base/contrast scales for dark mode to take effect on
		// rendered blocks. Accent colors stay similar (slightly brighter for
		// dark contrast).
		'--wp--preset--color--base'       => '#0a0a0a',
		'--wp--preset--color--base-2'     => '#161616',
		'--wp--preset--color--base-3'     => '#1f1f1f',
		'--wp--preset--color--contrast'   => '#f5f5f5',
		'--wp--preset--color--contrast-2' => '#d0d0d0',
		'--wp--preset--color--contrast-3' => '#a0a0a0',
		'--wp--preset--color--surface-1'  => '#1a1310',
		'--wp--preset--color--surface-2'  => '#101a1c',
		'--wp--preset--color--surface-3'  => '#0f1419',
		'--wp--preset--color--primary'    => 'var(--bx-color-accent)',

		// Brand / accent slugs missing pre-5.1.0. theme.json declares
		// these (settings.color.palette) and patterns use accent
		// extensively (8 pattern files reference has-accent-color in
		// 5.1.0). Without dark counterparts, pattern-driven pages
		// (home/landing) painted with light brand colors while the
		// theme chrome flipped dark — visible as red/teal blocks sitting
		// on a near-black page. Values match the dark palette baseline:
		// brand-red lifted (#ff5350) for dark-bg contrast, teal lifted
		// (#5aa3ae / #4a96a3), yellow stays (works on both modes).
		'--wp--preset--color--accent'     => '#ff5350',
		'--wp--preset--color--accent-2'   => '#4a96a3',
		'--wp--preset--color--accent-3'   => '#f4d35e',
		'--wp--preset--color--secondary'  => '#5aa3ae',
	);

	/**
	 * Component slug — used by the theme's Component_Interface registry.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return 'tokens';
	}

	/**
	 * Hook the three emission paths that drive design-token output:
	 * inline :root tokens on buddyx-global, the FOUC-prevention <html
	 * data-bx-mode> script in <head>, and the variation typography
	 * filters that inject defaults into typography_option settings.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'emit_tokens' ), 20 );
		add_action( 'customize_register', array( $this, 'register_live_preview' ), 1000 );
		add_action( 'customize_preview_init', array( $this, 'keep_live_tokens_applied' ) );
		add_action( 'wp_head', array( $this, 'emit_mode_script' ), 1 );
		add_action( 'send_headers', array( $this, 'reissue_legacy_mode_cookie' ) );
		// Hooked after init priority 10 so customizer fields are registered
		// before we read their defaults; filter is a no-op when no variation
		// is active or the customer has actively saved the field.
		add_action( 'init', array( __CLASS__, 'register_variation_theme_mod_filters' ), 20 );
		// 5.1.0 one-time migration: purge color theme_mods that the pre-5.1.0
		// alpha-color control init bug spuriously persisted as rgb()-format
		// equivalents of their declared field defaults. Without this purge,
		// those spurious saves emit AFTER the style-variation overlay in :root
		// and clobber the variation's colors. Runs once per site.
		add_action( 'init', array( __CLASS__, 'maybe_purge_alpha_color_pollution' ), 5 );
		// Preset-equal saves are not personalisations: strip them at publish
		// time (and once on upgrade for sites that already published a
		// preset) so the mode-aware token overlay stays the source of truth.
		// Runs before register_variation_theme_mod_filters (init 20).
		add_action( 'init', array( __CLASS__, 'maybe_purge_preset_equal_saves_once' ), 15 );
		add_action( 'customize_save_after', array( __CLASS__, 'purge_preset_equal_saves' ), 20 );
		// Owner-saved Customizer colours feed theme.json's palette, so core
		// blocks, the block editor and plugins that read the WordPress
		// `--wp--preset--color--*` variables see the same value as BuddyX.
		\add_filter( 'wp_theme_json_data_theme', array( __CLASS__, 'filter_theme_json_palette' ) );
		// Core caches the resolved theme.json (persistently with an object
		// cache), so drop it whenever the saved Customizer values change.
		\add_action( 'update_option_theme_mods_' . \get_stylesheet(), 'wp_clean_theme_json_cache' );
	}

	/**
	 * Overlay owner-saved Customizer colours onto theme.json's palette.
	 *
	 * @param \WP_Theme_JSON_Data $json Theme layer data.
	 * @return \WP_Theme_JSON_Data
	 */
	public static function filter_theme_json_palette( $json ) {
		$map     = array( 'primary' => 'site_primary_color' );
		$data    = $json->get_data();
		$palette = $data['settings']['color']['palette']['theme'] ?? array();
		if ( ! is_array( $palette ) ) {
			return $json;
		}
		$changed = false;
		foreach ( $palette as $i => $entry ) {
			$mod = $map[ $entry['slug'] ?? '' ] ?? '';
			if ( '' === $mod ) {
				continue;
			}
			$saved = \get_theme_mod( $mod, '' );
			$color = is_string( $saved ) && '' !== $saved ? self::normalize_color( $saved ) : '';
			if ( '' !== $color && ! self::colors_canonically_equal( $color, (string) $entry['color'] ) ) {
				$palette[ $i ]['color'] = $color;
				$changed                = true;
			}
		}
		if ( ! $changed ) {
			return $json;
		}
		return $json->update_with(
			array(
				'version'  => $data['version'],
				'settings' => array( 'color' => array( 'palette' => $palette ) ),
			)
		);
	}

	/**
	 * Snapshot of declared field defaults for every alpha-color customizer
	 * field as of 5.1.0. Used by the one-time pollution-purge migration to
	 * detect saved values that match the field's pre-5.1.0 default — those
	 * are pollution artifacts, not real customer customizations.
	 *
	 * @var array<string, string>
	 */
	protected static array $alpha_color_field_defaults_5_1_0 = array(
		'site_loader_bg'                      => '#ef5455',
		'site_primary_color'                  => '#ef5455',
		'site_header_bg_color'                => '#ffffff',
		'site_title_hover_color'              => '#ef5455',
		'menu_hover_color'                    => '#ef5455',
		'menu_active_color'                   => '#ef5455',
		'body_background_color'               => '#f7f7f9',
		'content_background_color'            => '#f7f7f9',
		'box_background_color'                => '#ffffff',
		'secondary_background_color'          => '#fafafa',
		'site_links_color'                    => '#111111',
		'site_links_focus_hover_color'        => '#ef5455',
		'site_buttons_background_color'       => '#ef5455',
		'site_buttons_background_hover_color' => '#f83939',
		'site_buttons_text_color'             => '#ffffff',
		'site_buttons_text_hover_color'       => '#ffffff',
		'site_buttons_border_color'           => '#ef5455',
		'site_buttons_border_hover_color'     => '#f83939',
		'site_footer_title_color'             => '#111111',
		'site_footer_content_color'           => '#505050',
		'site_footer_links_color'             => '#111111',
		'site_footer_links_hover_color'       => '#ef5455',
		'site_copyright_background_color'     => '#ffffff',
		'site_copyright_border_color'         => '#e8e8e8',
		'site_copyright_content_color'        => '#505050',
		'site_copyright_links_color'          => '#111111',
		'site_copyright_links_hover_color'    => '#ef5455',
	);

	/**
	 * Same pollution pattern, but for structured-array typography settings
	 * where the `color` sub-key got polluted. setting => default-hex map.
	 *
	 * @var array<string, string>
	 */
	protected static array $alpha_color_typography_subkey_defaults_5_1_0 = array(
		'site_title_typography_option'    => '#111111',
		'site_tagline_typography_option'  => '#757575',
		'menu_typography_option'          => '#111111',
		'site_sub_header_typography'      => '#111111',
		'typography_option'               => '#505050',
		'h1_typography_option'            => '#111111',
		'h2_typography_option'            => '#111111',
		'h3_typography_option'            => '#111111',
		'h4_typography_option'            => '#111111',
		'h5_typography_option'            => '#111111',
		'h6_typography_option'            => '#111111',
	);

	/**
	 * One-time migration — purge alpha-color theme_mods that match their
	 * declared field default. The pre-5.1.0 alpha-color control fired
	 * `syncToSetting()` on `ready()`, which reformatted a hex-default into
	 * `rgb()` and called `setting.set()` on the customizer setting — marking
	 * it dirty. On the next customizer save, the framework persisted that
	 * "value" to theme_mods even though the customer never touched the
	 * field. Result: those saves emit at the end of the :root cascade and
	 * override the style-variation overlay, breaking presets like Dark.
	 *
	 * This migration walks every known alpha-color setting, compares the
	 * saved value (any format) to the declared default (any format) using a
	 * canonical RGB-tuple comparison, and removes the theme_mod when they
	 * match. Customer-set values that genuinely differ from the default are
	 * preserved. Runs once per site (gated by `_buddyx_alpha_color_purged`).
	 */
	public static function maybe_purge_alpha_color_pollution(): void {
		if ( get_theme_mod( '_buddyx_alpha_color_purged', false ) ) {
			return;
		}
		$purged = 0;
		foreach ( self::$alpha_color_field_defaults_5_1_0 as $setting => $default ) {
			$saved = get_theme_mod( $setting, null );
			if ( null === $saved || ! is_string( $saved ) || '' === $saved ) {
				continue;
			}
			if ( self::colors_canonically_equal( $saved, $default ) ) {
				remove_theme_mod( $setting );
				++$purged;
			}
		}
		// Structured-array typography settings — the `color` sub-key was the
		// only thing the alpha picker controlled, so we purge JUST that sub-key
		// and keep any other typography keys (font-family / weight / size)
		// the customer set intentionally.
		foreach ( self::$alpha_color_typography_subkey_defaults_5_1_0 as $setting => $default ) {
			$saved = get_theme_mod( $setting, null );
			if ( ! is_array( $saved ) || empty( $saved['color'] ) || ! is_string( $saved['color'] ) ) {
				continue;
			}
			if ( self::colors_canonically_equal( $saved['color'], $default ) ) {
				unset( $saved['color'] );
				if ( empty( $saved ) ) {
					remove_theme_mod( $setting );
				} else {
					set_theme_mod( $setting, $saved );
				}
				++$purged;
			}
		}
		set_theme_mod( '_buddyx_alpha_color_purged', 1 );
		if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( sprintf( '[BuddyX 5.1.0] alpha-color pollution purge removed %d theme_mods', $purged ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- One-time migration note, only when WP_DEBUG_LOG is on.
		}
	}

	/**
	 * Purge saved color theme_mods that canonically equal the active Style
	 * preset's value for that setting (or the registered field default when
	 * no preset is active).
	 *
	 * Why: picking a Style preset in the customizer paints the preset's
	 * palette into every mapped per-control color setting (so the pickers
	 * visually reflect the preset). Publishing then persists those values as
	 * customer theme_mods even though the customer never personalised them.
	 * Two things break downstream:
	 *
	 *  1. Typography `color` sub-keys emit element-level CSS through
	 *     Output_Builder (e.g. `body{color:#0A0A0A}`) that applies in BOTH
	 *     color modes - a light preset then renders near-black text on the
	 *     dark surface (and the Dark preset near-white text on light).
	 *  2. The saves block/skew the variation theme_mod filters and clutter
	 *     the DB with values the variation overlay already provides.
	 *
	 * A save that equals the preset's value is not a personalisation - the
	 * token overlay (mode-aware) is the source of truth for it. Values that
	 * genuinely differ from the preset are always preserved.
	 *
	 * Reads/writes the raw theme_mods option (NOT get_theme_mod) so the
	 * variation theme_mod filters cannot leak injected values back into the
	 * saved option.
	 */
	public static function purge_preset_equal_saves(): void {
		$mods = \get_option( 'theme_mods_' . \get_stylesheet(), array() );
		if ( ! is_array( $mods ) ) {
			return;
		}
		$slug     = (string) ( $mods['site_style_variation'] ?? '' );
		$baseline = array();
		if ( '' !== $slug ) {
			$map      = \BuddyX\Buddyx\Customizer_Framework\Component::get_style_variation_defaults();
			$baseline = $map[ $slug ] ?? array();
		} else {
			// Empty "Default" preset: baseline is each field's registered
			// default (static 5.1.0 snapshots; kept in sync with Skin_Fields).
			$baseline = self::$alpha_color_field_defaults_5_1_0;
			foreach ( self::$alpha_color_typography_subkey_defaults_5_1_0 as $setting => $default ) {
				$baseline[ $setting . '[color]' ] = $default;
			}
		}
		if ( array() === $baseline ) {
			return;
		}

		$changed = false;
		foreach ( $baseline as $setting_id => $expected ) {
			if ( ! is_string( $expected ) || '' === $expected ) {
				continue;
			}
			if ( preg_match( '/^([^\[]+)\[([^\]]+)\]$/', $setting_id, $m ) ) {
				$parent = $m[1];
				$key    = $m[2];
				$saved  = $mods[ $parent ] ?? null;
				if ( ! is_array( $saved ) || empty( $saved[ $key ] ) || ! is_string( $saved[ $key ] ) ) {
					continue;
				}
				if ( self::colors_canonically_equal( $saved[ $key ], $expected ) ) {
					unset( $saved[ $key ] );
					if ( array() === $saved ) {
						unset( $mods[ $parent ] );
					} else {
						$mods[ $parent ] = $saved;
					}
					$changed = true;
				}
			} else {
				$saved = $mods[ $setting_id ] ?? null;
				if ( ! is_string( $saved ) || '' === $saved ) {
					continue;
				}
				if ( self::colors_canonically_equal( $saved, $expected ) ) {
					unset( $mods[ $setting_id ] );
					$changed = true;
				}
			}
		}
		// Typography sub-keys (font-family / weight / letter-spacing) that
		// equal the active variation's own override are filter-provided, not
		// customer personalisations - strip them too. Cleans rows persisted
		// before the customize_save filter detachment existed, where a save
		// serialized the injected variation fonts into theme_mods.
		if ( '' !== $slug ) {
			foreach ( self::resolve_variation_typography_overrides( $slug ) as $setting => $props ) {
				$saved = $mods[ $setting ] ?? null;
				if ( ! is_array( $saved ) || ! is_array( $props ) ) {
					continue;
				}
				$dirty = false;
				foreach ( $props as $key => $value ) {
					if ( isset( $saved[ $key ] ) && is_string( $saved[ $key ] ) && trim( $saved[ $key ] ) === trim( (string) $value ) ) {
						unset( $saved[ $key ] );
						$dirty = true;
					}
				}
				if ( $dirty ) {
					if ( array() === $saved ) {
						unset( $mods[ $setting ] );
					} else {
						$mods[ $setting ] = $saved;
					}
					$changed = true;
				}
			}
		}
		if ( $changed ) {
			\update_option( 'theme_mods_' . \get_stylesheet(), $mods );
		}
	}

	/**
	 * One-time cleanup for sites that already published a Style preset
	 * before the purge above existed (5.1.5). Runs before
	 * register_variation_theme_mod_filters() (init 20) so the variation
	 * filters see the cleaned option and correctly treat the purged
	 * settings as unsaved.
	 */
	public static function maybe_purge_preset_equal_saves_once(): void {
		if ( get_theme_mod( '_buddyx_preset_equal_purged', false ) ) {
			return;
		}
		self::purge_preset_equal_saves();
		set_theme_mod( '_buddyx_preset_equal_purged', 1 );
	}



	/**
	 * Build the CSS variable block and attach it as inline style on the
	 * 'buddyx-global' stylesheet. Replaces the 5.0.3 Dynamic_Style emission.
	 */
	public function emit_tokens() {
		$css = $this->build_token_css();
		if ( '' !== $css ) {
			// Its own <style id="buddyx-tokens-inline-css">, right after the global
			// stylesheet, so the Customizer can re-render it alone (live preview).
			\wp_register_style( 'buddyx-tokens', false, array( 'buddyx-global' ), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- No file: holds the inline token CSS only.
			\wp_enqueue_style( 'buddyx-tokens' );
			\wp_add_inline_style( 'buddyx-tokens', $css );
		}
	}

	/**
	 * Live preview for every colour the token engine paints.
	 *
	 * Instead of reloading the whole preview, the Customizer re-renders just the
	 * token <style> block from build_token_css(), so the preview shows exactly what
	 * Publish will (hovers, fills, labels and dark mode included), in one request.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager.
	 * @return void
	 */
	public function register_live_preview( $wp_customize ) {
		$live = array();
		foreach ( array_keys( self::$simple_color_tokens ) as $id ) {
			$setting = $wp_customize->get_setting( $id );
			if ( $setting && 'refresh' === $setting->transport ) {
				$setting->transport = 'postMessage';
				$live[]             = $id;
			}
		}
		if ( array() === $live ) {
			return;
		}
		$wp_customize->selective_refresh->add_partial(
			'buddyx_tokens',
			array(
				'selector'            => '#buddyx-tokens-inline-css',
				'container_inclusive' => true,
				'settings'            => $live,
				'fallback_refresh'    => true,
				'render_callback'     => function () {
					return '<style id="buddyx-tokens-inline-css">' . $this->build_token_css() . '</style>';
				},
			)
		);
	}
	/**
	 * Keep the live-preview token block applied in the Customizer preview.
	 *
	 * Selective refresh adds a title ("Shift-click to edit") to every partial
	 * container. On a <style> element a title makes it a named alternate style
	 * sheet, which browsers may not apply, so the re-rendered tokens were ignored.
	 * The preview removes the title whenever the token block renders.
	 *
	 * @return void
	 */
	public function keep_live_tokens_applied() {
		wp_add_inline_script(
			'customize-selective-refresh',
			'(function(api){function strip(){var el=document.getElementById("buddyx-tokens-inline-css");if(el){el.removeAttribute("title");}}api.bind("preview-ready",function(){strip();api.selectiveRefresh.bind("partial-content-rendered",function(p){if(p.partial&&p.partial.id==="buddyx_tokens"){strip();}});});})(wp.customize);'
		);
	}


	/**
	 * Collect theme_mods for token generation, preview-aware.
	 *
	 * `get_theme_mods()` reads the raw `theme_mods_{$stylesheet}` option and is
	 * NOT filtered by the customizer preview, so previewed-but-unsaved colour
	 * and radius changes never reached the token CSS — the live preview only
	 * updated after Publish. `get_theme_mod()` (singular) IS preview-filtered,
	 * so during a customizer preview we overlay the previewed value for every
	 * token-feeding setting.
	 *
	 * @return array<string, mixed> theme_mod values keyed by setting id.
	 */
	protected function collect_mods(): array {
		$mods = (array) \get_theme_mods();

		if ( ! \is_customize_preview() ) {
			return $mods;
		}

		$keys = array_merge(
			array_keys( self::$simple_color_tokens ),
			array_keys( self::$typography_color_tokens ),
			array_keys( self::$dimension_tokens ),
			array( 'site_custom_colors', 'site_style_variation' )
		);
		foreach ( $keys as $key ) {
			$mods[ $key ] = \get_theme_mod( $key, $mods[ $key ] ?? null );
		}

		return $mods;
	}

	/**
	 * Build the full :root { … } CSS string from theme_mods.
	 * Public so Dynamic_Style/test code can call it directly.
	 *
	 * @return string CSS text (already includes the :root selector).
	 */
	public function build_token_css(): string {
		$enabled = \get_theme_mod( 'site_custom_colors', true );
		$mods    = $this->collect_mods();
		$decls   = '';

		// Framework-derived tokens always emit — they back generic neutrals.
		// (borders, mid-tone text, shadows) that consumer CSS depends on
		// regardless of whether the customer enabled custom colors.
		foreach ( self::$framework_tokens as $token => $value ) {
			$decls .= $token . ':' . $value . ';';
		}

		// Style variation overlay (Phase 7). Route to the correct cascade
		// layer based on the variation's own luminance:
		//
		// Light / default variation -> :root { … }     (becomes the light default)
		// Dark variation            -> [data-bx-mode="dark"] { … } (becomes the dark default)
		//
		// Why: prior to this routing, the variation overlay always painted
		// into :root, which meant a Dark variation locked the site to dark
		// surfaces regardless of the visitor's color-mode toggle. Visitors
		// who picked "light" via the toggle still saw dark pages and the
		// dark logo - the toggle was effectively non-functional. Routing
		// the dark variation into the [data-bx-mode="dark"] selector makes
		// the variation the dark *default* while leaving :root as the light
		// surface a visitor's light toggle can fall back to.
		//
		// resolve_style_variation_tokens() converts the variation's palette
		// slugs to --bx-* tokens (accent -> --bx-color-accent, base ->
		// --bx-color-bg, contrast -> --bx-color-fg, etc.) AND propagates
		// accent to button-bg/link, base to header-bg, plus emits
		// --wp--preset--color--<slug> overrides so blocks/patterns repaint
		// too. $variation_covered tracks bases the variation already
		// painted so the downstream derive_for fallback (lines below)
		// doesn't clobber them with default-color variants when the
		// customer hasn't saved a value.
		//
		// Variation typography is injected into the Customizer Framework's
		// typography_option theme_mod defaults via
		// register_variation_theme_mod_filters() so the framework's
		// Output_Builder emits typography from a single source.
		$variation_covered        = array();
		$variation_slug           = (string) ( $mods['site_style_variation'] ?? '' );
		$variation_decls          = '';
		$variation_is_dark        = false;
		$dark_variation_token_set = array();
		if ( '' !== $variation_slug ) {
			$variation_is_dark = (bool) self::active_variation_is_dark_scheme();
			$variation_decls   = self::resolve_style_variation_tokens( $variation_slug, $variation_covered, $variation_is_dark );
			if ( '' === $variation_decls ) {
				$variation_is_dark = false;
			} else {
				if ( ! $variation_is_dark ) {
					// Light / default / unknown-luminance variation paints into
					// :root as before; this is the light default. Same behavior
					// the theme had pre-5.1.0-beta.3.
					$decls .= $variation_decls;
					// Variation no longer covers light tokens for the dark
					// cascade, so $variation_covered is correct as-is.
				}
				// For a dark variation we emit $variation_decls into the dark
				// cascade below via build_dark_block($variation_decls). $variation_covered
				// is cleared so the customer-default light derives in :root
				// can still paint a coherent light palette - otherwise the
				// theme would render *no* light-mode colors when the admin
				// picks Dark variation, breaking the visitor light toggle.
				if ( $variation_is_dark ) {
					// Save which --bx-* tokens the variation covers before clearing.
					// The customer-save emission loop below uses this to route those
					// tokens into the dark cascade instead of :root, preventing a
					// dark-preset value (e.g. site_header_bg_color = #0F0F0F) from
					// leaking onto the light surface when the visitor toggles to light.
					$dark_variation_token_set = array_flip( $variation_covered );
					$variation_covered        = array();
				}
			}
		}

		// Site Custom Colors master toggle gates the customizer-derived tokens
		// for parity with 5.0.3 behavior. Framework tokens + variation overlay
		// above still emit.
		// Customer saves routed to the dark cascade when the dark variation is active.
		$dark_customer_decls = '';
		if ( ! $enabled ) {
			$decls      .= self::legacy_alias_declarations() . self::auto_button_text_decls( $mods );
			$light_block = ':root{' . $decls . '}';
			$dark_block  = $this->build_dark_block( $variation_is_dark ? $variation_decls : '', self::dark_brand_tokens( '', '' ) );
			return $light_block . $dark_block;
		}

		// Customer-driven base colors that get the full derived-variants set.
		// (-rgb, -hover, -active, -focus, -bg, -bg-strong, -border, -disabled,.
		// -inverse). These are the brand colors most consumer CSS reaches for
		// on hover/active/focus/disabled states.
		//
		// Map: customer_field => array( token, default_color ).
		// Default colors mirror _bx-tokens.css so derivation always produces
		// the same variants whether or not customer has saved a value.
		$primary    = self::saved_brand_color( $mods, 'site_primary_color' );
		$button     = self::saved_brand_color( $mods, 'site_buttons_background_color' );
		$derive_for = array(
			'site_primary_color'             => array( '--bx-color-accent', '' !== $primary ? $primary : self::DEFAULT_ACCENT ),
			// An unsaved Buttons colour follows Primary, so one brand pick recolours every button.
			'site_buttons_background_color'  => array( '--bx-color-button-bg', '' !== $primary ? $primary : self::DEFAULT_ACCENT ),
			// No site_links_color row: its derived -hover would overwrite the
			// --bx-color-link-hover role token (brand colour) with a shade of the link colour.
			'body_background_color'          => array( '--bx-color-bg', '#f7f7f9' ),
			'box_background_color'           => array( '--bx-color-bg-elevated', '#ffffff' ),
			'site_header_bg_color'           => array( '--bx-color-header-bg', '#ffffff' ),
		);

		// Simple hex color tokens. The inline tokens emit is the last :root
		// rule in the cascade for every page render — global.min.css declares
		// defaults, then this inline block overrides for customer-saved
		// values. Values that canonically equal the registered field default
		// are skipped so the style-variation overlay remains the source of
		// truth for tokens the customer has not personalised.
		foreach ( self::$simple_color_tokens as $mod_key => $cfg ) {
			$value = $mods[ $mod_key ] ?? '';
			if ( '' !== $value && is_string( $value ) ) {
				$default = self::$alpha_color_field_defaults_5_1_0[ $mod_key ] ?? null;
				if ( null !== $default && self::colors_canonically_equal( $value, $default ) ) {
					$value = '';
				}
			}
			$color = '' !== $value ? self::normalize_color( $value ) : '';
			// A variation paints its accent into the buttons. Once the owner picks a
			// Theme Color, buttons they have not set follow it instead, so one brand
			// pick recolours every button (the variation only seeds the brand).
			if ( '' === $color && 'site_buttons_background_color' === $mod_key && '' !== $primary && in_array( $cfg['token'], $variation_covered, true ) ) {
				$color = $primary;
			}
			// When the dark variation is active, customer-saved values for tokens
			// the variation covers must go into the dark cascade — not :root. This
			// prevents a dark-preset color (e.g. site_header_bg_color = #0F0F0F)
			// from leaking onto the light surface when the visitor toggles to light.
			$in_dark_variation = isset( $dark_variation_token_set[ $cfg['token'] ] );
			if ( '' !== $color ) {
				if ( $in_dark_variation ) {
					$dark_customer_decls .= $cfg['token'] . ':' . $color . ';';
					foreach ( $cfg['aliases'] as $alias ) {
						$dark_customer_decls .= $alias . ':' . $color . ';';
					}
				} else {
					$decls .= $cfg['token'] . ':' . $color . ';';
					foreach ( $cfg['aliases'] as $alias ) {
						$decls .= $alias . ':' . $color . ';';
					}
				}
			}
			// Derive variants for every base in $derive_for. When the customer
			// has saved a value, that wins. When they haven't, the static
			// default is the fallback so consumer CSS always finds -rgb/-hover/
			// etc — UNLESS a variation already painted this base, in which case
			// the variation's variants survive (otherwise picking "Dark" leaves
			// every -rgb / -hover / -active / -focus computed from the default
			// red/white instead of the variation's palette).
			if ( isset( $derive_for[ $mod_key ] ) ) {
				$token = $derive_for[ $mod_key ][0];
				if ( '' !== $color ) {
					if ( $in_dark_variation ) {
						$dark_customer_decls .= self::derive_color_variants( $token, $color, true );
					} else {
						$decls .= self::derive_color_variants( $token, $color );
					}
				} elseif ( ! in_array( $token, $variation_covered, true ) ) {
					$decls .= self::derive_color_variants( $token, $derive_for[ $mod_key ][1] );
				}
			}
		}

		// Typography sub-key 'color' tokens. Same default-equals-skip as
		// simple_color_tokens — structured typography arrays carry a `color`
		// sub-key whose registered default would otherwise re-override the
		// variation overlay's site-title / heading values.
		foreach ( self::$typography_color_tokens as $mod_key => $cfg ) {
			$value     = $mods[ $mod_key ] ?? array();
			$color_val = is_array( $value ) ? ( $value['color'] ?? '' ) : '';
			if ( '' === $color_val ) {
				continue;
			}
			$default = self::$alpha_color_typography_subkey_defaults_5_1_0[ $mod_key ] ?? null;
			if ( null !== $default && self::colors_canonically_equal( $color_val, $default ) ) {
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

		// Legacy alias shim - emit `--legacy: var(--bx-token);` so 3rd-
		// party and pre-token CSS rules referencing legacy var names
		// flip colour-mode through the canonical token automatically.
		// See $legacy_aliases doc-block for context.
		$decls .= self::legacy_alias_declarations() . self::auto_button_text_decls( $mods );

		$light_block = ':root{' . $decls . '}';
		// Customer decls for dark-variation-covered tokens layer after $variation_decls
		// so the customer's explicit save wins over the variation's palette default.
		// A light style variation is a brand choice too: dark mode derives from its accent
		// unless the owner saved their own Theme Color.
		$dark_base = $primary;
		if ( '' === $dark_base && ! $variation_is_dark && preg_match( '/--bx-color-accent:(#[0-9a-fA-F]{3,8});/', $variation_decls, $m ) ) {
			$dark_base = $m[1];
		}
		$dark_block = $this->build_dark_block( $variation_is_dark ? $variation_decls . $dark_customer_decls : '', self::dark_brand_tokens( $dark_base, $button ) );

		return $light_block . $dark_block;
	}

	/**
	 * The light-mode brand colour: the owner's Theme Color, or the theme default.
	 *
	 * For plugins whose own design puts white text on a single brand colour in both
	 * modes (LearnPress), so the darker light-mode shade is the one that keeps it readable.
	 * ponytail: ignores a style variation's accent; read the variation palette if that matters.
	 *
	 * @return string Hex colour.
	 */
	public static function light_accent(): string {
		$primary = self::saved_brand_color( (array) \get_theme_mods(), 'site_primary_color' );
		return '' !== $primary ? $primary : self::DEFAULT_ACCENT;
	}

	/**
	 * The owner's saved brand colour for a field, or '' when unsaved.
	 *
	 * A save equal to the field's default counts as unsaved, the same rule
	 * the simple-token loop applies.
	 *
	 * @param array<string,mixed> $mods    Customizer theme mods.
	 * @param string              $mod_key Field id.
	 * @return string Normalized colour or ''.
	 */
	protected static function saved_brand_color( array $mods, string $mod_key ): string {
		$value = $mods[ $mod_key ] ?? '';
		if ( ! is_string( $value ) || '' === $value ) {
			return '';
		}
		foreach ( array( self::DEFAULT_ACCENT, self::$alpha_color_field_defaults_5_1_0[ $mod_key ] ?? '' ) as $default ) {
			if ( '' !== $default && self::colors_canonically_equal( $value, $default ) ) {
				return '';
			}
		}
		return self::normalize_color( $value );
	}

	/**
	 * Dark-mode brand tokens derived from the owner's light colours.
	 *
	 * The owner picks the brand once. Dark mode lightens the accent (links,
	 * labels) until it reads at 4.5:1 on the dark surfaces, so a green site stays
	 * green in dark mode. Buttons keep their light-mode fill, so a button and its
	 * label look the same in both modes; only a fill too dark to stand out on the
	 * dark cards (under 3:1) is lifted, just enough. Labels follow the contrast pick.
	 *
	 * @param string $primary Saved Primary colour, or '' for the theme default.
	 * @param string $button  Saved Buttons colour, or '' to follow Primary.
	 * @return array<string,string> token => value.
	 */
	protected static function dark_brand_tokens( string $primary, string $button ): array {
		$brand  = '' !== $primary ? $primary : self::DEFAULT_ACCENT;
		$accent = '' !== $primary ? self::readable_on_dark( $primary ) : self::DEFAULT_DARK_ACCENT;
		$tokens = array(
			'--bx-color-accent'    => $accent,
			'--bx-color-button-bg' => self::derived_shade( '' !== $button ? $button : $brand, 'fill', true ),
		);
		foreach ( $tokens as $token => $color ) {
			foreach ( explode( ';', rtrim( self::derive_color_variants( $token, $color, true ), ';' ) ) as $decl ) {
				list( $name, $value ) = array_pad( explode( ':', $decl, 2 ), 2, '' );
				if ( '' !== $value ) {
					$tokens[ $name ] = $value;
				}
			}
		}
		// Badges, counts and pills fill with the light brand colour too, not the
		// lighter text shade derived above.
		$tokens['--bx-color-accent-fill']         = self::derived_shade( $brand, 'fill', true );
		$tokens['--bx-color-accent-fill-inverse'] = self::derived_shade( $brand, 'fill-inverse', true );
		return $tokens;
	}


	/**
	 * Build the `--legacy: var(--bx-token);` declaration string from
	 * the $legacy_aliases map. Aliases re-resolve every paint - the
	 * alias is mode-agnostic; the referenced --bx-* token does the
	 * actual light/dark flip. The same string is therefore safe to
	 * emit in both :root and the dark block (build_dark_block already
	 * handles its own dark variants via the simple_color_tokens
	 * alias mechanism).
	 */
	protected static function legacy_alias_declarations(): string {
		$out = '';
		foreach ( self::$legacy_aliases as $alias => $token ) {
			$out .= $alias . ':var(' . $token . ');';
		}
		return $out;
	}

	/**
	 * Build the dark-mode override block for `:root[data-bx-mode="dark"]`.
	 *
	 * Each dark-default token also overrides its legacy aliases so any third-
	 * party CSS hooked to `--color-theme-primary` etc. picks up the dark
	 * value (otherwise legacy CSS would render light-mode colors on dark
	 * surfaces - a contrast failure).
	 *
	 * @param string $extra_decls Additional declarations to append AFTER the
	 *                            dark defaults so they win. Used by
	 *                            build_token_css() to route a dark style
	 *                            variation's palette into the dark cascade
	 *                            instead of stamping it onto :root.
	 * @param array  $brand       Dark brand tokens from dark_brand_tokens().
	 */
	protected function build_dark_block( string $extra_decls = '', array $brand = array() ): string {
		$alias_lookup = array(); // token => array of aliases.
		foreach ( self::$simple_color_tokens as $cfg ) {
			$alias_lookup[ $cfg['token'] ] = $cfg['aliases'];
		}
		foreach ( self::$typography_color_tokens as $cfg ) {
			$alias_lookup[ $cfg['token'] ] = $cfg['aliases'];
		}
		$dark_decls = '';
		foreach ( array_merge( self::$dark_defaults, $brand ) as $token => $value ) {
			$dark_decls .= $token . ':' . $value . ';';
			foreach ( ( $alias_lookup[ $token ] ?? array() ) as $alias ) {
				$dark_decls .= $alias . ':' . $value . ';';
			}
		}
		// Dark style variation overlay layers AFTER $dark_defaults so the
		// variation's palette wins for tokens it covers. The variation slugs
		// have already been mapped to --bx-* tokens (and legacy aliases) by
		// resolve_style_variation_tokens() in build_token_css() before being
		// passed here, so no additional alias expansion is needed.
		if ( '' !== $extra_decls ) {
			$dark_decls .= $extra_decls;
		}
		if ( '' === $dark_decls ) {
			return '';
		}
		// Explicit user choice only; the "auto"/system mode was retired in 5.1.4,
		// so there is no prefers-color-scheme media block any more.
		return ':root[data-bx-mode="dark"]{' . $dark_decls . '}';
	}

	/**
	 * Re-send the legacy LearnDash focus-mode `bxtheme` cookie without HttpOnly.
	 *
	 * Up to 5.1.8 the focus-mode toggle saved the visitor's choice in an
	 * HttpOnly `bxtheme` cookie, which the bootstrap script cannot read. Sending
	 * it back readable (same name and path, so the browser replaces it) lets the
	 * bootstrap migrate the choice on this very page and then expire it. The
	 * page HTML stays the same for every visitor, so page caches are unaffected.
	 */
	public function reissue_legacy_mode_cookie() {
		if ( ! isset( $_COOKIE['bxtheme'] ) || headers_sent() ) {
			return;
		}
		$legacy = 'dark' === sanitize_key( wp_unslash( $_COOKIE['bxtheme'] ) ) ? 'dark' : 'light';
		setcookie( 'bxtheme', $legacy, time() + MINUTE_IN_SECONDS, '/', '', is_ssl(), false );
	}

	/**
	 * Emit a tiny inline script that sets <html data-bx-mode="..."> before any
	 * CSS or paint happens — prevents a flash-of-light-mode on dark-mode pages.
	 *
	 * Reads from localStorage so the visitor's choice persists across pages,
	 * falling back to the customizer-configured default (light/dark). A legacy
	 * saved "auto", from before the option was retired in 5.1.4, resolves to light.
	 */
	public function emit_mode_script() {
		$default = (string) \get_theme_mod( 'site_color_mode', 'light' );
		if ( ! in_array( $default, array( 'light', 'dark' ), true ) ) {
			$default = 'light';
		}

		// A dark style preset makes dark the visual baseline, so force the default
		// to dark (a light-OS visitor can still toggle to light). The retired
		// "auto" mode no longer factors in here.
		if ( true === self::active_variation_is_dark_scheme() ) {
			$default = 'dark';
		}

		/**
		 * Filters whether the owner default mode is forced for every visitor.
		 *
		 * True by default when the visitor toggle is hidden: a stored choice would
		 * otherwise stick with no control left to change it. Return false to keep
		 * honouring stored choices without a visible toggle.
		 *
		 * @since 5.2.0
		 *
		 * @param bool $force Whether stored visitor choices are ignored.
		 */
		$force = (bool) \apply_filters( 'buddyx_force_default_color_mode', ! \buddyx_is_truthy( \get_theme_mod( 'site_color_mode_toggle_show', 'on' ) ) );

		self::print_mode_bootstrap(
			array(
				'def'    => $default,
				'force'  => $force,
				'legacy' => '',
			)
		);
	}

	/**
	 * Print the color-mode bootstrap inline: config, then the shared script.
	 *
	 * Inlined (not enqueued) because it must run before the first paint.
	 *
	 * @param array $config Keys def (light|dark), force (bool), legacy (light|dark|'').
	 * @return void
	 */
	public static function print_mode_bootstrap( array $config ): void {
		$file = \get_theme_file_path( 'assets/js/color-mode-bootstrap.min.js' );
		if ( ! is_readable( $file ) ) {
			return;
		}
		$script = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Bundled theme file, read locally.
		\wp_print_inline_script_tag(
			'var buddyxColorModeConfig = ' . \wp_json_encode( $config ) . ";\n" . $script,
			array( 'id' => 'buddyx-color-mode-bootstrap' )
		);
	}

	/**
	 * Whether the active style variation is a dark-scheme palette.
	 *
	 * Reads the variation's `base` palette color and tests luminance. The
	 * variation's own background color is the single source of truth — no
	 * redundant metadata flag in styles/*.json that could drift from the
	 * palette. Returns null for typography-only variations (no palette) and
	 * when no variation is active.
	 *
	 * Filterable via `buddyx_variation_is_dark_scheme` so authors of custom
	 * variations whose base color doesn't reflect their scheme intent (or
	 * mu-plugin overrides) can force the answer. Filter receives the slug
	 * as the 2nd argument.
	 *
	 * @return bool|null true = dark, false = light, null = unknown / none.
	 */
	protected static function active_variation_is_dark_scheme(): ?bool {
		$variation_slug = (string) \get_theme_mod( 'site_style_variation', '' );
		if ( '' === $variation_slug ) {
			return null;
		}
		/** This filter is documented in inc/Custom_Logo/Component.php */
		$override = \apply_filters( 'buddyx_variation_is_dark_scheme', null, $variation_slug );
		if ( null !== $override ) {
			return (bool) $override;
		}
		$data = self::load_variation_data( $variation_slug );
		if ( null === $data ) {
			return null;
		}
		$palette = $data['settings']['color']['palette'] ?? array();
		if ( ! is_array( $palette ) || empty( $palette ) ) {
			return null;
		}
		foreach ( $palette as $entry ) {
			if ( is_array( $entry ) && 'base' === ( $entry['slug'] ?? '' ) ) {
				$base_hex = (string) ( $entry['color'] ?? '' );
				return '' === $base_hex ? null : self::hex_is_dark( $base_hex );
			}
		}
		return null;
	}

	/**
	 * Whether a hex color reads as "dark" to a sighted viewer.
	 *
	 * Uses WCAG 2.1 relative luminance against the 0.5 midpoint. Accepts
	 * 3- or 6-digit hex with or without leading '#'. Returns false on
	 * malformed input so dark-scheme inference fails safe to "treat as
	 * light" (preserves pre-5.1.1 bootstrap behavior).
	 *
	 * @param string $hex Hex color, e.g. "#0F0F0F", "0f0f0f", "fff".
	 * @return bool True when relative luminance is below 0.5.
	 */
	protected static function hex_is_dark( string $hex ): bool {
		$rgb = self::color_to_rgb( '#' . ltrim( trim( $hex ), '#' ) );
		return null !== $rgb && self::rgb_luminance( $rgb ) < 0.5;
	}

	/**
	 * Single source of truth: theme.json palette slug → list of --bx-* tokens
	 * the variation should paint. The list combines (a) the direct semantic
	 * mapping (accent → --bx-color-accent) and (b) sibling tokens that must
	 * track the same color so the preset visibly applies (accent → button-bg,
	 * link; contrast → all heading + site-title + menu text colors; base →
	 * header-bg). Customer customizer saves still win because they emit later
	 * in the cascade.
	 *
	 * Adding a new token to a variation overlay = one line here. No other
	 * call site needs to change.
	 *
	 * @var array<string, array<int, string>>
	 */
	protected static array $variation_palette_targets = array(
		'accent'     => array(
			'--bx-color-accent',
			'--bx-color-button-bg',
			'--bx-color-link',
		),
		'accent-2'   => array( '--bx-color-accent-secondary' ),
		'accent-3'   => array( '--bx-color-accent-tertiary' ),
		'base'       => array(
			'--bx-color-bg',
			'--bx-color-bg-page',
			'--bx-color-header-bg',
			'--bx-color-footer-bg',
			'--bx-color-copyright-bg',
		),
		'base-2'     => array(
			'--bx-color-bg-elevated',
			'--bx-color-subheader-bg',
			'--bx-color-loader-bg',
			'--bx-color-input-bg',
		),
		'base-3'     => array(
			'--bx-color-bg-muted',
			'--bx-color-border',
			'--bx-color-border-strong',
			'--bx-color-divider',
			'--bx-color-copyright-border',
			'--bx-color-input-border',
		),
		'contrast'   => array(
			'--bx-color-fg',
			'--bx-color-h1',
			'--bx-color-h2',
			'--bx-color-h3',
			'--bx-color-h4',
			'--bx-color-h5',
			'--bx-color-h6',
			'--bx-color-site-title',
			'--bx-color-site-tagline',
			'--bx-color-menu-fg',
			'--bx-color-subheader-fg',
			'--bx-color-footer-title',
			'--bx-color-footer-link',
			'--bx-color-copyright-link',
			'--bx-color-input-fg',
		),
		'contrast-2' => array(
			'--bx-color-fg-muted',
			'--bx-color-footer-fg',
			'--bx-color-copyright-fg',
		),
		'contrast-3' => array( '--bx-color-fg-subtle' ),
	);

	/**
	 * --bx-* base tokens that get the full derived-variants set (-rgb / -hover
	 * / -active / -focus / -bg / -bg-strong / -border / -disabled / -inverse)
	 * whenever a value is emitted for them — variation overlay or customer
	 * customizer save. Centralised so the variation overlay and the customer
	 * derive_for path agree on which bases derive variants.
	 *
	 * @var array<int, string>
	 */
	protected static array $derive_bases = array(
		'--bx-color-accent',
		'--bx-color-bg',
		'--bx-color-bg-elevated',
		'--bx-color-fg',
		'--bx-color-button-bg',
		'--bx-color-link',
		'--bx-color-header-bg',
	);

	/**
	 * Variation typography overlay routes through the Customizer Framework's
	 * existing typography_option settings. theme.json element key → matching
	 * BuddyX customizer setting that the framework already turns into CSS.
	 * The variation injects values via `theme_mod_<setting>` filters when
	 * the customer hasn't actively saved that setting; the framework then
	 * emits typography from one place (no parallel rules, no specificity
	 * tricks, customer customizer saves naturally win).
	 *
	 * Adding a new element = add one line here.
	 *
	 * @var array<string, string>
	 */
	protected static array $variation_typography_settings = array(
		'@body' => 'typography_option',
		'h1'    => 'h1_typography_option',
		'h2'    => 'h2_typography_option',
		'h3'    => 'h3_typography_option',
		'h4'    => 'h4_typography_option',
		'h5'    => 'h5_typography_option',
		'h6'    => 'h6_typography_option',
	);

	/**
	 * Theme.json typography sub-key → typography_option array sub-key the
	 * Customizer Framework's Output_Builder consumes. The framework already
	 * knows how to render each of these as a CSS declaration.
	 *
	 * @var array<string, string>
	 */
	protected static array $variation_typography_props = array(
		'fontFamily'     => 'font-family',
		'fontWeight'     => 'font-weight',
		'fontStyle'      => 'font-style',
		'fontSize'       => 'font-size',
		'letterSpacing'  => 'letter-spacing',
		'lineHeight'     => 'line-height',
		'textTransform'  => 'text-transform',
		'textDecoration' => 'text-decoration',
	);

	/**
	 * Read + parse a styles/<slug>.json variation file. Single read path
	 * shared by every consumer (token overlay, typography overlay, future
	 * settings consumers) so slug validation and file-read error handling
	 * live in exactly one place.
	 *
	 * @param string $slug Variation slug.
	 * @return array<string, mixed>|null Parsed JSON, or null if the slug is
	 *                                   invalid / file is missing / JSON is
	 *                                   malformed.
	 */
	protected static function load_variation_data( string $slug ): ?array {
		// Whitelist + safety: slug must be a single hyphen-or-letter token.
		if ( ! preg_match( '/^[a-z][a-z0-9-]{0,30}$/', $slug ) ) {
			return null;
		}
		$path = \get_template_directory() . '/styles/' . $slug . '.json';
		if ( ! is_readable( $path ) ) {
			return null;
		}
		// Local theme file, not a remote URL — wp_remote_get() would be wrong.
		$json = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reads a bundled theme file.
		if ( false === $json ) {
			return null;
		}
		$data = json_decode( $json, true );
		return is_array( $data ) ? $data : null;
	}

	/**
	 * Read styles/<slug>.json and emit --bx-* token declarations from the
	 * variation's palette. Customer customizer-saved values OVERRIDE these
	 * downstream because the variation overlay is emitted earlier in the
	 * inline declarations block — same selector, later declaration wins
	 * via standard CSS cascade.
	 *
	 * Two layers of output, both driven by data:
	 *   1. For each palette entry, every --bx-* token registered in
	 *      $variation_palette_targets[ slug ] gets painted (with legacy
	 *      aliases and derived variants where applicable). One palette slug
	 *      can fan out to multiple BuddyX semantic tokens, e.g. accent →
	 *      accent + button-bg + link, contrast → fg + h1..h6 + site-title +
	 *      menu-fg + tagline + subheader-fg, base → bg + header-bg.
	 *   2. Each palette entry also emits --wp--preset--color--<slug> so block
	 *      patterns using `has-<slug>-background-color` repaint to the
	 *      variation's palette (otherwise theme.json's static palette wins
	 *      and patterns visually ignore the preset).
	 *
	 * @param string             $slug    Variation slug (e.g. 'dark', 'vibrant').
	 * @param array<int, string> $covered Out-param: --bx-* base tokens this
	 *                                    overlay painted (so the downstream
	 *                                    derive_for fallback skips them).
	 *                                    Use a throwaway array if not needed.
	 * @param bool               $on_dark True when the declarations go into the dark-mode block.
	 * @return string CSS declarations (no selector wrapper) or empty if the
	 *                variation file doesn't exist or has no palette.
	 */
	protected static function resolve_style_variation_tokens( string $slug, array &$covered = array(), bool $on_dark = false ): string {
		$data = self::load_variation_data( $slug );
		if ( null === $data ) {
			return '';
		}
		$palette = $data['settings']['color']['palette'] ?? array();
		if ( ! is_array( $palette ) || empty( $palette ) ) {
			return '';
		}

		$decls     = '';
		$has_slugs = array_column( $palette, 'slug' );
		foreach ( $palette as $entry ) {
			$pal_slug = $entry['slug'] ?? '';
			$color    = $entry['color'] ?? '';
			if ( '' === $pal_slug || '' === $color ) {
				continue;
			}
			$normalized = self::normalize_color( (string) $color );
			if ( '' === $normalized ) {
				continue;
			}

			// Always emit the WordPress preset alias so blocks/patterns using.
			// `has-<slug>-background-color` (or any.
			// `var(--wp--preset--color--<slug>)` reference) repaint to the
			// variation's palette instead of theme.json's static value.
			$decls .= '--wp--preset--color--' . $pal_slug . ':' . $normalized . ';';
			// theme.json's `primary` is the brand slot plugins read. Variations
			// name their brand colour `accent`, so mirror it unless the
			// variation defines `primary` itself.
			if ( 'accent' === $pal_slug && ! in_array( 'primary', $has_slugs, true ) ) {
				$decls .= '--wp--preset--color--primary:' . $normalized . ';';
			}

			// Paint every --bx-* token registered for this slug (declared in.
			// $variation_palette_targets). One slug fans out to multiple
			// tokens — accent → accent + button-bg + link, contrast → fg +
			// h1..h6 + site-title + menu-fg + tagline + subheader-fg, etc.
			foreach ( self::$variation_palette_targets[ $pal_slug ] ?? array() as $bx_token ) {
				// Links take the accent by reference, so a Theme Color the owner picks
				// after the variation recolours them too.
				$value     = ( 'accent' === $pal_slug && '--bx-color-link' === $bx_token ) ? 'var(--bx-color-accent)' : $normalized;
				$decls    .= self::emit_variation_token( $bx_token, $value, $on_dark );
				$covered[] = $bx_token;
			}
		}

		return $decls;
	}

	/**
	 * Emit a base --bx-* token + every legacy alias registered for it +
	 * (when the base is in $derive_bases) the full derived-variants set.
	 * Aliases come from both $simple_color_tokens and $typography_color_tokens
	 * so any legacy --color-* / --global-font-color / etc. hooked into either
	 * namespace gets the same color.
	 *
	 * @param string $token --bx-* base token name.
	 * @param string $color Normalized CSS color.
	 * @param bool   $on_dark True when the declarations go into the dark-mode block.
	 * @return string CSS declarations.
	 */
	protected static function emit_variation_token( string $token, string $color, bool $on_dark = false ): string {
		$decls = $token . ':' . $color . ';';
		foreach ( array( self::$simple_color_tokens, self::$typography_color_tokens ) as $map ) {
			foreach ( $map as $cfg ) {
				if ( $cfg['token'] === $token ) {
					foreach ( $cfg['aliases'] as $alias ) {
						$decls .= $alias . ':' . $color . ';';
					}
					break 2;
				}
			}
		}
		if ( in_array( $token, self::$derive_bases, true ) ) {
			$decls .= self::derive_color_variants( $token, $color, $on_dark );
		}
		return $decls;
	}

	/**
	 * Hook `theme_mod_<setting>` filters so the active variation's typography
	 * appears as the EFFECTIVE default for each typography_option setting.
	 * Customer customizer saves still win because the filter only fires when
	 * the customer hasn't actively saved that setting (key absent in raw
	 * theme_mods option). Result: variation flows through Output_Builder's
	 * existing typography emission — single source of truth, no specificity
	 * hacks, no parallel CSS rule output.
	 */
	public static function register_variation_theme_mod_filters(): void {
		$variation_slug = (string) \get_theme_mod( 'site_style_variation', '' );
		if ( '' === $variation_slug ) {
			return;
		}

		$overrides = self::resolve_variation_typography_overrides( $variation_slug );
		if ( empty( $overrides ) ) {
			return;
		}

		$saved_mods = \get_option( 'theme_mods_' . \get_stylesheet(), array() );
		if ( ! is_array( $saved_mods ) ) {
			$saved_mods = array();
		}

		foreach ( $overrides as $setting => $variation_value ) {
			// Layering: field default < variation < customer save. A partial
			// customer save (e.g. only the color sub-key persisted by a preset
			// pick in the customizer) must NOT disable the variation's other
			// sub-keys - skipping the whole filter on array_key_exists() used
			// to drop the preset's fontFamily/weight the moment any sub-key
			// was saved. $saved_raw is captured at registration because the
			// filter callback only receives the post-default value and cannot
			// tell a real save from the caller's default fallback.
			$saved_raw = $saved_mods[ $setting ] ?? null;
			if ( null !== $saved_raw && ! is_array( $variation_value ) ) {
				// Scalar override with a real customer save: save wins outright.
				continue;
			}
			$callback = static function ( $current ) use ( $variation_value, $saved_raw ) {
				if ( is_array( $variation_value ) ) {
					$base  = is_array( $current ) ? $current : array();
					$saved = is_array( $saved_raw ) ? $saved_raw : array();
					return array_merge( $base, $variation_value, $saved );
				}
				return $variation_value;
			};
			\add_filter( "theme_mod_{$setting}", $callback, 5 );
			self::$variation_filter_handles[] = array( "theme_mod_{$setting}", $callback );
		}

		// WP_Customize_Setting builds a multidimensional root value from the
		// FILTERED theme_mod before writing it back, so a customizer save
		// would otherwise persist the filter-injected variation sub-keys
		// (fontFamily/weight) as if the customer had saved them - polluting
		// theme_mods and surviving later preset switches. Detach the filters
		// for the duration of the save; purge_preset_equal_saves() then
		// cleans the color sub-keys the preset pick itself dirtied.
		\add_action( 'customize_save', array( __CLASS__, 'detach_variation_theme_mod_filters' ) );
	}

	/**
	 * Handles of the theme_mod filters registered by
	 * register_variation_theme_mod_filters(), as [hook, callback] pairs, so
	 * they can be detached during a customizer save.
	 *
	 * @var array<int, array{0:string, 1:callable}>
	 */
	protected static array $variation_filter_handles = array();

	/**
	 * Remove every variation theme_mod filter (used right before the
	 * customizer writes settings so injected values are never persisted).
	 */
	public static function detach_variation_theme_mod_filters(): void {
		foreach ( self::$variation_filter_handles as $pair ) {
			\remove_filter( $pair[0], $pair[1], 5 );
		}
		self::$variation_filter_handles = array();
	}

	/**
	 * Read styles/<slug>.json and translate `styles.typography` and
	 * `styles.elements.<tag>.typography` into typography_option-format
	 * arrays keyed by the corresponding customizer setting name. The
	 * framework's Output_Builder consumes these natively — see
	 * inc/Customizer_Framework/Output_Builder.php :: typography_declarations().
	 *
	 * @param string $slug Variation slug.
	 * @return array<string, array<string, string>> setting => partial value array.
	 */
	protected static function resolve_variation_typography_overrides( string $slug ): array {
		$data = self::load_variation_data( $slug );
		if ( null === $data ) {
			return array();
		}

		$overrides = array();
		foreach ( self::$variation_typography_settings as $element_key => $setting ) {
			$typo = '@body' === $element_key
				? ( $data['styles']['typography'] ?? null )
				: ( $data['styles']['elements'][ $element_key ]['typography'] ?? null );
			if ( ! is_array( $typo ) ) {
				continue;
			}
			$converted = self::convert_variation_typography_to_field_format( $typo );
			if ( ! empty( $converted ) ) {
				$overrides[ $setting ] = $converted;
			}
		}

		return $overrides;
	}

	/**
	 * Translate a theme.json typography sub-object (camelCase keys, e.g.
	 * fontFamily) into the kebab-case keys the Customizer Framework's
	 * Output_Builder expects (e.g. font-family). Only known keys flow
	 * through; values pass a strict sanitizer that rejects semicolons /
	 * braces / backslashes to keep the boundary defensible even though
	 * variation files ship in the theme.
	 *
	 * @param array<string, mixed> $typo theme.json typography sub-object.
	 * @return array<string, string> Field-format value array.
	 */
	protected static function convert_variation_typography_to_field_format( array $typo ): array {
		$converted = array();
		foreach ( self::$variation_typography_props as $json_key => $field_key ) {
			if ( ! isset( $typo[ $json_key ] ) ) {
				continue;
			}
			$raw = $typo[ $json_key ];
			if ( ! is_string( $raw ) && ! is_int( $raw ) && ! is_float( $raw ) ) {
				continue;
			}
			$value = trim( (string) $raw );
			if ( '' === $value || preg_match( '/[;{}<>\\\\]/', $value ) ) {
				continue;
			}
			$converted[ $field_key ] = $value;
		}
		return $converted;
	}







	/**
	 * Declarations that make the button label follow the button fill.
	 *
	 * The fill may be the theme default, a Customizer pick, or a style
	 * variation, so the label reads `--bx-color-button-bg-inverse` (emitted by
	 * derive_color_variants) instead of a fixed white. An owner-saved label
	 * colour wins: a saved value equal to the field default counts as unsaved,
	 * matching purge_preset_equal_saves().
	 *
	 * @param array<string,mixed> $mods Customizer theme mods.
	 * @return string CSS declarations.
	 */
	protected static function auto_button_text_decls( array $mods ): string {
		$default_bg = self::color_to_rgb( self::DEFAULT_ACCENT );
		$fallback   = null !== $default_bg ? self::contrast_pick( $default_bg ) : '#ffffff';
		$decls      = '';
		foreach ( array(
			'site_buttons_text_color'       => array( '--bx-color-button-fg', '--button-text-color', 'var(--bx-color-button-bg-inverse,' . $fallback . ')' ),
			'site_buttons_text_hover_color' => array( '--bx-color-button-fg-hover', '--button-text-hover-color', 'var(--bx-color-button-fg)' ),
		) as $mod_key => list( $token, $alias, $value ) ) {
			$saved = $mods[ $mod_key ] ?? '';
			if ( is_string( $saved ) && '' !== $saved && ! self::colors_canonically_equal( $saved, '#ffffff' ) ) {
				continue;
			}
			$decls .= $token . ':' . $value . ';' . $alias . ':var(' . $token . ');';
		}
		return $decls;
	}


	/**
	 * Normalize a color value (hex or rgb/rgba string) to a safe CSS string.
	 * Returns '' if the value is unrecognized.
	 *
	 * @param string $value Color string from a theme_mod or variation file.
	 * @return string Normalized CSS color, or '' on parse failure.
	 */
	protected static function normalize_color( string $value ): string {
		$value = trim( $value );
		// Hex shorthand or full form.
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
	 * Public accessor for $simple_color_tokens — used by tests and the
	 * docs-generator to enumerate the customizer color field set.
	 *
	 * @return array<string, array{token:string, aliases:array<int,string>}>
	 */
	public static function get_simple_color_tokens(): array {
		return self::$simple_color_tokens;
	}

	/**
	 * Public accessor for $typography_color_tokens.
	 *
	 * @return array<string, array{token:string, aliases:array<int,string>}>
	 */
	public static function get_typography_color_tokens(): array {
		return self::$typography_color_tokens;
	}

	/**
	 * Public accessor for $dimension_tokens.
	 *
	 * @return array<string, array{token:string, aliases:array<int,string>}>
	 */
	public static function get_dimension_tokens(): array {
		return self::$dimension_tokens;
	}
}
