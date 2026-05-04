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
		'--bx-color-fg-muted' => '#757575',                  // Mid-tone text (was #757575/#666/#aaa)
		'--bx-color-border'   => '#e8e8e8',                  // Generic border (was #e8e8e8/#eaeaea)
		'--bx-color-divider'  => '#f0f0f0',                  // Subtle divider (was #f0f0f0/#f6f6f6)
		'--bx-color-shadow'   => 'rgba(0, 0, 0, 0.08)',      // Card / popover shadow base
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
		'--bx-color-fg-muted' => '#a0a0a0',
		'--bx-color-border'   => '#2a2a2a',
		'--bx-color-divider'  => '#1a1a1a',
		'--bx-color-shadow'   => 'rgba(0, 0, 0, 0.4)',

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
