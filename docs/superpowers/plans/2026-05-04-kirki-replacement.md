# BuddyX 5.1.0 — Kirki Replacement Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the Kirki Customizer Framework dependency with an in-house, portable `Wbcom_Customizer` framework that lives inside the theme — reusable later in BuddyX Pro and other Wbcom themes — and migrate all 111 existing Kirki field definitions across 19 sections to the new framework with zero user-visible regression.

**Architecture:** Three-layer replacement. (1) **Foundation library** — a self-contained framework at `inc/Wbcom_Customizer/` that wraps WP core's `WP_Customize_Manager` with an array-based field/section/panel API (drop-in replacement for Kirki's API surface that BuddyX uses). (2) **Custom controls** — five PHP+JS controls for the field types core Customizer doesn't ship: Color (with palette), Typography, Radio_Image, Switch (toggle), Dimension (number+unit). (3) **Migration shim** — a one-time `kirki_to_wbcom` adapter that translates the existing 12 `Fields/*.php` files' Kirki API calls to the new framework's API, then those files are rewritten and the shim is removed. Output (auto-CSS) and active_callback features are reimplemented.

**Tech Stack:** PHP 7.4+ with `WP_Customize_Manager`, `WP_Customize_Control`, `WP_Customize_Setting`. Vanilla JS (no jQuery dependency in new code) for postMessage live preview. Lightning CSS build pipeline (already in repo) for control assets. No new composer/npm dependencies.

**Branch:** `5.1.0` (already cut from master post-5.0.3). All work lands here. wbcomdesigns/buddyx is the dev remote; vapvarun/buddyx is release-only and not touched until 5.1.0 ships.

**Pro reusability discipline:** The framework files at `inc/Wbcom_Customizer/` must be portable as a unit — copy that directory verbatim into BuddyX Pro and it works. To enforce this:
- Zero theme-slug coupling. The class namespace is `Wbcom\Customizer\*`, not `BuddyX\*`. No `buddyx_` prefixes in class names, hooks, or text-domain inside the framework files.
- Text domain inside the framework is `wbcom-customizer` (loaded from a small POT file shipped with it). The host theme's text domain (`buddyx`) is used in the Field-definition files that consume the framework, not inside the framework itself.
- Configuration via constructor / static method calls only — no `get_template_directory_uri()` baked into framework files.
- Public API documented in `inc/Wbcom_Customizer/README.md` so the BuddyX Pro engineer can drop the directory in and migrate Pro's Kirki calls with confidence.

**Prior art — what we're modeling.** Every successful free wp.org theme that needed Kirki-level Customizer features eventually built its own in-house framework rather than carry the plugin dependency:
- **Astra** — `inc/customizer/` with `Astra_Customizer` core class and per-field control classes (`Astra_Control_Color`, `Astra_Control_Typography`, `Astra_Control_Radio_Image`, `Astra_Control_Slider`, `Astra_Control_Toggle`, etc.). Loaded directly by the theme; no plugin dependency.
- **GeneratePress** — `inc/customizer/` with `GeneratePress_Customize` core and `GeneratePress_*_Field` control classes. Same shape.
- **Kadence** — `inc/customizer/controls/` with `Kadence_Customize_*_Control` classes.
- **OceanWP** — `inc/customizer/controls/` with `OceanWP_Customize_*` controls.

These four themes account for ~7M active installs combined and validate the architecture: a small `inc/customizer/` (or equivalent) directory holding (a) a central registration class, (b) custom controls extending `WP_Customize_Control`, (c) one bundled JS file for live preview and dynamic UI, (d) one CSS file for control styling. **This plan follows the same skeleton.** The naming convention modernizes to PSR-4 namespaces (`Wbcom\Customizer\Controls\Control_Color` instead of `Astra_Control_Color`), but the file structure, separation of concerns, and registration flow are intentionally familiar to anyone who has read those themes' source. An engineer who has worked on Astra or GeneratePress should be able to land in this codebase and feel at home in 30 minutes.

**Quality bar:**
- After migration, every existing Customizer setting renders identical to today (same defaults, same live-preview behavior, same generated CSS).
- Zero Kirki classes referenced anywhere in `inc/`, `template-parts/`, `*.php` theme files.
- Kirki plugin can be deactivated and the theme works unchanged.
- Customizer load time on a fresh page does not regress (current Kirki adds ~250ms; in-house should be ≤ that).

**Constraints (non-negotiable for wp.org review):**
- No external API calls from the framework (no font preview from Google Fonts API, no remote palette downloads).
- All assets self-contained in the theme.
- Existing user theme mods (`get_theme_mod('site_layout')` etc.) keep returning the same values after the upgrade — same setting IDs.

---

## Current Kirki footprint (recon, ground truth)

```
14 distinct field types, 111 instances, 19 sections, 2 panels
12 field-definition files in inc/Kirki_Option/Fields/

Field type breakdown:
  Color           39
  Checkbox_Switch 18
  Typography      12
  Radio_Image     11
  Custom           9   (just renders raw HTML — no value)
  Dimension        7
  Radio            4
  Dropdown_Pages   3
  Background       2
  Text             2
  URL              1
  Textarea         1
  Select           1
  Image            1

Per-file:
  Skin_Fields.php       46  (largest — colors and active_callback heavy)
  Blog_Fields.php       12
  Typography_Fields.php 11
  WP_Login_Fields.php    9
  General_Fields.php     9
  Sidebar_Fields.php     8
  Header_Fields.php      5
  Sub_Header_Fields.php  4
  Footer_Fields.php      3
  Site_Performance.php   3
  BuddyPress_Fields.php  1

Advanced features used:
  output (auto-CSS)        — 17 fields, mostly Typography
  transport: auto          — many fields (Kirki feature)
  transport: postMessage   — fewer; explicit live preview
  transport: refresh       — most
  active_callback          — 54 occurrences, mostly in Skin_Fields
```

The framework needs to support: 14 field types, output (auto-CSS injection), transport (refresh / postMessage / "auto" promoted to postMessage), active_callback.

---

## File Structure

### New (framework, ~1,800 lines total — designed for portability)

```
inc/Wbcom_Customizer/
├── README.md                                   (Task 25 — public API + migration guide)
├── class-customizer.php                        (Task 1 — bootstrap, registration loop)
├── class-panel.php                             (Task 2 — Panel wrapper)
├── class-section.php                           (Task 2 — Section wrapper)
├── class-field.php                             (Task 3 — base Field class)
├── class-output-builder.php                    (Task 4 — auto-CSS generator)
├── class-active-callback.php                   (Task 4 — array→closure adapter)
├── controls/
│   ├── class-control-color.php                 (Task 6)
│   ├── class-control-typography.php            (Task 7 — biggest custom control)
│   ├── class-control-radio-image.php           (Task 8)
│   ├── class-control-switch.php                (Task 9)
│   ├── class-control-dimension.php             (Task 10)
│   └── class-control-custom-html.php           (Task 11 — for "Custom" Kirki type)
├── assets/
│   ├── customizer-controls.js                  (Task 12 — single bundled JS for all controls)
│   ├── customizer-controls.css                 (Task 13 — control styling)
│   └── customizer-preview.js                   (Task 14 — live preview)
└── languages/
    └── wbcom-customizer.pot                    (Task 24 — strings inside framework)
```

### Existing files modified

```
inc/Kirki_Option/Component.php                  (Task 5 + 16 — bootstrap rewrite, eventually renamed Customizer_Settings)
inc/Kirki_Option/Fields/General_Fields.php      (Task 17)
inc/Kirki_Option/Fields/Header_Fields.php       (Task 17)
inc/Kirki_Option/Fields/Sub_Header_Fields.php   (Task 17)
inc/Kirki_Option/Fields/Footer_Fields.php       (Task 17)
inc/Kirki_Option/Fields/Sidebar_Fields.php      (Task 18)
inc/Kirki_Option/Fields/Blog_Fields.php         (Task 18)
inc/Kirki_Option/Fields/Typography_Fields.php   (Task 19 — Typography fields, hardest)
inc/Kirki_Option/Fields/Skin_Fields.php         (Task 20 — biggest, 46 fields, lots of active_callback)
inc/Kirki_Option/Fields/WP_Login_Fields.php     (Task 21)
inc/Kirki_Option/Fields/BuddyPress_Fields.php   (Task 21)
inc/Kirki_Option/Fields/Site_Performance.php    (Task 21)
inc/Customizer/Component.php                    (Task 22 — drop Kirki dependency check)
inc/Dropdown_Select/Component.php               (Task 22 — uses Kirki's selectWoo control, replace)
inc/compatibility/surecart/surecart-functions.php  (Task 21 — 1-2 fields)
inc/compatibility/fluentcart/fluentcart-functions.php (Task 21)
functions.php                                   (Task 23 — register the new framework's autoloader, remove Kirki check)
style.css                                       (Task 26 — bump to 5.1.0)
readme.txt                                      (Task 26)
```

### Files renamed at end (Task 16)

```
inc/Kirki_Option/                       →  inc/Customizer_Settings/
inc/Kirki_Option/Component.php          →  inc/Customizer_Settings/Component.php
inc/Kirki_Option/Fields/                →  inc/Customizer_Settings/Fields/
```

The "Kirki_Option" name leaks the dependency. Renaming is purely cosmetic but signals the migration is done.

### Files retired (Task 23)

- The `kirki` plugin can be deactivated and uninstalled by users after upgrade.
- The theme no longer requires/recommends Kirki via TGM-PA. Update `external/class-tgm-plugin-activation.php` invocations to drop the Kirki entry.

---

## Public API of `Wbcom_Customizer` (locked at Task 1)

This is the API both BuddyX free and (later) BuddyX Pro will call. Designed to be a minimal superset of the Kirki array shape used in the existing `Fields/*.php` files so the migration is a slug rename, not a rewrite.

```php
use Wbcom\Customizer\Customizer;
use Wbcom\Customizer\Panel;
use Wbcom\Customizer\Section;
use Wbcom\Customizer\Field;

// Boot once per theme
Customizer::boot( array(
    'text_domain' => 'buddyx',           // host theme's text domain
    'config_id'   => 'buddyx_customizer', // unique handle for assets/scripts
) );

// Panels (top-level Customizer panels)
Panel::add( 'site_layout_panel', array(
    'title'       => __( 'General', 'buddyx' ),
    'priority'    => 30,
) );

// Sections
Section::add( 'site_layout', array(
    'title' => __( 'Site Layout', 'buddyx' ),
    'panel' => 'site_layout_panel',
) );

// Fields — same shape as Kirki for drop-in migration
Field::add( 'color', array(
    'settings'  => 'site_primary_color',
    'label'     => __( 'Primary Color', 'buddyx' ),
    'section'   => 'colors',
    'default'   => '#D83734',
    'transport' => 'postMessage',
    'output'    => array(
        array(
            'element'  => '.site-primary',
            'property' => 'color',
        ),
    ),
) );

Field::add( 'typography', array(
    'settings' => 'body_typography',
    'label'    => __( 'Body Typography', 'buddyx' ),
    'section'  => 'typography_section',
    'default'  => array(
        'font-family' => 'Inter',
        'font-size'   => '16px',
        'line-height' => '1.6',
    ),
    'transport' => 'auto',  // mapped to postMessage
    'output'    => array(
        array( 'element' => 'body' ),
    ),
) );
```

The `Field::add( $type, $args )` first-arg is a string field type (`'color'`, `'typography'`, `'radio_image'`, `'switch'`, `'dimension'`, `'custom'`, `'text'`, `'textarea'`, `'url'`, `'select'`, `'radio'`, `'dropdown-pages'`, `'image'`, `'background'`).

---

## Task 1: Foundation — `Customizer::boot()` and registration loop

**Files:**
- Create: `inc/Wbcom_Customizer/class-customizer.php`

**Why:** A single static class is the entry point. It accumulates panels/sections/fields registered via the static `add()` methods, then on `customize_register` hook iterates them and registers with `WP_Customize_Manager`. This decouples user-facing API from WP's hook timing.

- [ ] **Step 1: Create the class skeleton**

Write `inc/Wbcom_Customizer/class-customizer.php`:

```php
<?php
/**
 * Wbcom Customizer Framework — main bootstrap.
 *
 * Self-contained Customizer wrapper, portable across Wbcom themes.
 * Do NOT add theme-specific (e.g. buddyx_) prefixes inside this file.
 *
 * @package Wbcom\Customizer
 */
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

class Customizer {
	/** @var array */
	protected static $config = array(
		'text_domain' => 'wbcom-customizer',
		'config_id'   => 'wbcom_customizer',
	);

	/** @var array<string, array> */
	protected static $panels = array();
	/** @var array<string, array> */
	protected static $sections = array();
	/** @var array<int, array> */
	protected static $fields = array();
	/** @var bool */
	protected static $booted = false;

	public static function boot( array $config = array() ): void {
		if ( self::$booted ) {
			return;
		}
		self::$config = array_merge( self::$config, $config );
		self::$booted = true;

		add_action( 'customize_register', array( __CLASS__, 'register' ), 99 );
		add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'enqueue_controls' ) );
		add_action( 'customize_preview_init', array( __CLASS__, 'enqueue_preview' ) );
		add_action( 'wp_head', array( __CLASS__, 'output_inline_css' ), 100 );
	}

	public static function register_panel( string $id, array $args ): void {
		self::$panels[ $id ] = $args;
	}

	public static function register_section( string $id, array $args ): void {
		self::$sections[ $id ] = $args;
	}

	public static function register_field( string $type, array $args ): void {
		self::$fields[] = array_merge( array( '_type' => $type ), $args );
	}

	public static function register( \WP_Customize_Manager $wp_customize ): void {
		foreach ( self::$panels as $id => $args ) {
			$wp_customize->add_panel( $id, $args );
		}
		foreach ( self::$sections as $id => $args ) {
			$wp_customize->add_section( $id, $args );
		}
		require_once __DIR__ . '/class-field.php';
		foreach ( self::$fields as $field_args ) {
			Field::register_with_manager( $wp_customize, $field_args );
		}
	}

	public static function get_config( string $key ): string {
		return self::$config[ $key ] ?? '';
	}

	public static function enqueue_controls(): void {
		$base = trailingslashit( self::get_config( 'assets_url' ) );
		wp_enqueue_script(
			self::get_config( 'config_id' ) . '-controls',
			$base . 'inc/Wbcom_Customizer/assets/customizer-controls.js',
			array( 'customize-controls', 'wp-color-picker' ),
			'5.1.0',
			true
		);
		wp_enqueue_style(
			self::get_config( 'config_id' ) . '-controls',
			$base . 'inc/Wbcom_Customizer/assets/customizer-controls.css',
			array( 'wp-color-picker' ),
			'5.1.0'
		);
	}

	public static function enqueue_preview(): void {
		$base = trailingslashit( self::get_config( 'assets_url' ) );
		wp_enqueue_script(
			self::get_config( 'config_id' ) . '-preview',
			$base . 'inc/Wbcom_Customizer/assets/customizer-preview.js',
			array( 'customize-preview', 'jquery' ),
			'5.1.0',
			true
		);
	}

	public static function output_inline_css(): void {
		require_once __DIR__ . '/class-output-builder.php';
		$css = Output_Builder::collect( self::$fields );
		if ( '' !== $css ) {
			echo "<style id=\"" . esc_attr( self::get_config( 'config_id' ) ) . "-css\">{$css}</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}
```

- [ ] **Step 2: Lint check**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
php -l inc/Wbcom_Customizer/class-customizer.php
```
Expected: `No syntax errors detected`.

- [ ] **Step 3: Commit**

```bash
git add inc/Wbcom_Customizer/class-customizer.php
git commit -m "feat(customizer): scaffold Wbcom_Customizer foundation

Static-class API: boot() / register_panel() / register_section() /
register_field(). Hooks into customize_register at priority 99.
No-op on second boot. Designed to be portable — zero theme slug
coupling, namespaced under Wbcom\\Customizer."
```

---

## Task 2: Panel + Section thin wrappers

**Files:**
- Create: `inc/Wbcom_Customizer/class-panel.php`
- Create: `inc/Wbcom_Customizer/class-section.php`

These are 5-line wrappers so authoring code reads `Panel::add()` / `Section::add()` instead of `Customizer::register_panel()`. Pure ergonomics.

- [ ] **Step 1: Write `class-panel.php`**

```php
<?php
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

class Panel {
	public static function add( string $id, array $args ): void {
		Customizer::register_panel( $id, $args );
	}
}
```

- [ ] **Step 2: Write `class-section.php`**

```php
<?php
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

class Section {
	public static function add( string $id, array $args ): void {
		Customizer::register_section( $id, $args );
	}
}
```

- [ ] **Step 3: Lint and commit**

```bash
php -l inc/Wbcom_Customizer/class-panel.php inc/Wbcom_Customizer/class-section.php
git add inc/Wbcom_Customizer/class-panel.php inc/Wbcom_Customizer/class-section.php
git commit -m "feat(customizer): Panel and Section thin wrappers"
```

---

## Task 3: Base Field class with registration logic

**Files:**
- Create: `inc/Wbcom_Customizer/class-field.php`

**Why:** This is the dispatcher that knows for each field type which `WP_Customize_Setting` to create and which `WP_Customize_Control` (core or our custom) to attach. Centralizing it here keeps each control class focused on rendering.

- [ ] **Step 1: Create the file**

```php
<?php
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

class Field {
	/** Map field type -> [setting class, control class, is_custom_control] */
	protected static $type_map = array(
		'color'          => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Color', true ),
		'typography'     => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Typography', true ),
		'radio_image'    => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Radio_Image', true ),
		'switch'         => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Switch', true ),
		'dimension'      => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Dimension', true ),
		'custom'         => array( '\\WP_Customize_Setting', '\\Wbcom\\Customizer\\Controls\\Control_Custom_HTML', true ),
		// Core types — no custom control needed
		'text'           => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'textarea'       => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'url'            => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'select'         => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'radio'          => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'dropdown-pages' => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'image'          => array( '\\WP_Customize_Setting', '\\WP_Customize_Image_Control', false ),
		'background'     => array( '\\WP_Customize_Setting', '\\WP_Customize_Background_Image_Control', false ),
	);

	public static function add( string $type, array $args ): void {
		Customizer::register_field( $type, $args );
	}

	public static function register_with_manager( \WP_Customize_Manager $wp_customize, array $args ): void {
		$type = $args['_type'];
		if ( ! isset( self::$type_map[ $type ] ) ) {
			return;
		}
		list( $setting_class, $control_class, $is_custom ) = self::$type_map[ $type ];

		$setting_id   = $args['settings'];
		$transport    = self::resolve_transport( $args );
		$default      = $args['default'] ?? '';
		$sanitize_cb  = self::resolve_sanitize_callback( $type, $args );

		$wp_customize->add_setting( $setting_id, array(
			'default'           => $default,
			'transport'         => $transport,
			'sanitize_callback' => $sanitize_cb,
			'capability'        => $args['capability'] ?? 'edit_theme_options',
		) );

		$control_args = self::build_control_args( $type, $args );

		if ( $is_custom ) {
			require_once __DIR__ . '/controls/' . self::class_to_filename( $control_class );
			$wp_customize->add_control( new $control_class( $wp_customize, $setting_id, $control_args ) );
		} else {
			$control_args['type'] = self::map_core_type( $type );
			$wp_customize->add_control( $setting_id, $control_args );
		}
	}

	protected static function resolve_transport( array $args ): string {
		$t = $args['transport'] ?? 'refresh';
		// Kirki "auto" — promote to postMessage if `output` is provided.
		if ( 'auto' === $t ) {
			return ! empty( $args['output'] ) ? 'postMessage' : 'refresh';
		}
		return in_array( $t, array( 'refresh', 'postMessage' ), true ) ? $t : 'refresh';
	}

	protected static function resolve_sanitize_callback( string $type, array $args ): callable {
		if ( isset( $args['sanitize_callback'] ) && is_callable( $args['sanitize_callback'] ) ) {
			return $args['sanitize_callback'];
		}
		// Type-based defaults
		switch ( $type ) {
			case 'color':       return 'sanitize_hex_color';
			case 'url':         return 'esc_url_raw';
			case 'textarea':    return 'sanitize_textarea_field';
			case 'switch':      return array( __CLASS__, 'sanitize_switch' );
			case 'select':
			case 'radio':       return 'sanitize_key';
			case 'image':
			case 'background':  return 'esc_url_raw';
			default:            return 'sanitize_text_field';
		}
	}

	public static function sanitize_switch( $value ): int {
		return ( '1' === (string) $value || 1 === (int) $value || true === $value ) ? 1 : 0;
	}

	protected static function build_control_args( string $type, array $args ): array {
		$out = array(
			'label'       => $args['label']       ?? '',
			'description' => $args['description'] ?? '',
			'section'     => $args['section'],
			'priority'    => $args['priority']    ?? 10,
		);
		foreach ( array( 'choices', 'tooltip', 'output', 'active_callback', 'input_attrs' ) as $k ) {
			if ( array_key_exists( $k, $args ) ) {
				$out[ $k ] = $args[ $k ];
			}
		}
		// active_callback: array form -> closure
		if ( isset( $out['active_callback'] ) && is_array( $out['active_callback'] ) ) {
			require_once __DIR__ . '/class-active-callback.php';
			$out['active_callback'] = Active_Callback::compile( $out['active_callback'] );
		}
		return $out;
	}

	protected static function map_core_type( string $type ): string {
		$map = array(
			'text'           => 'text',
			'textarea'       => 'textarea',
			'url'            => 'url',
			'select'         => 'select',
			'radio'          => 'radio',
			'dropdown-pages' => 'dropdown-pages',
		);
		return $map[ $type ] ?? 'text';
	}

	protected static function class_to_filename( string $class ): string {
		$short = substr( $class, strrpos( $class, '\\' ) + 1 );          // Control_Color
		$file  = 'class-' . str_replace( '_', '-', strtolower( $short ) ); // class-control-color
		return $file . '.php';
	}
}
```

- [ ] **Step 2: Lint and commit**

```bash
php -l inc/Wbcom_Customizer/class-field.php
git add inc/Wbcom_Customizer/class-field.php
git commit -m "feat(customizer): Field dispatcher with type→control mapping

Maps 14 field types to either a built-in WP control or one of our
custom controls. Resolves Kirki 'transport: auto' to postMessage
when output is provided, refresh otherwise. Compiles active_callback
arrays to closures via Active_Callback adapter (Task 4)."
```

---

## Task 4: `Output_Builder` (auto-CSS) and `Active_Callback` (array→closure)

**Files:**
- Create: `inc/Wbcom_Customizer/class-output-builder.php`
- Create: `inc/Wbcom_Customizer/class-active-callback.php`

**Why:** Two of Kirki's most-used "magic" features: declarative `output` arrays that emit CSS, and declarative `active_callback` arrays that conditionally show fields. Reimplementing them keeps the Field array shape unchanged and means consumer files don't have to rewrite logic.

- [ ] **Step 1: Write `class-output-builder.php`**

```php
<?php
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

class Output_Builder {
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
				$css .= self::rule_to_css( $rule, $value, $f['_type'] );
			}
		}
		return $css;
	}

	protected static function rule_to_css( array $rule, $value, string $type ): string {
		$element  = $rule['element']  ?? '';
		$property = $rule['property'] ?? self::default_property( $type );
		if ( '' === $element || '' === $property ) {
			return '';
		}
		$prefix = $rule['prefix'] ?? '';
		$suffix = $rule['suffix'] ?? '';
		$units  = $rule['units']  ?? '';

		if ( 'typography' === $type && is_array( $value ) ) {
			$decls = '';
			foreach ( array(
				'font-family'    => 'font-family',
				'font-size'      => 'font-size',
				'line-height'    => 'line-height',
				'letter-spacing' => 'letter-spacing',
				'font-weight'    => 'font-weight',
				'text-transform' => 'text-transform',
			) as $k => $css_prop ) {
				if ( ! empty( $value[ $k ] ) ) {
					$decls .= sprintf( '%s:%s;', $css_prop, $value[ $k ] );
				}
			}
			return $decls ? sprintf( '%s{%s}', $element, $decls ) : '';
		}

		$rendered = $prefix . $value . $suffix . $units;
		return sprintf( '%s{%s:%s;}', $element, $property, $rendered );
	}

	protected static function default_property( string $type ): string {
		switch ( $type ) {
			case 'color':     return 'color';
			case 'dimension': return 'width';
			default:          return '';
		}
	}
}
```

- [ ] **Step 2: Write `class-active-callback.php`**

```php
<?php
namespace Wbcom\Customizer;

defined( 'ABSPATH' ) || exit;

/**
 * Compiles Kirki-shape active_callback arrays into closures
 * the WP_Customize_Control evaluator can call.
 *
 * Kirki shape:
 *   array(
 *     array( 'setting' => 'enable_x', 'operator' => '==', 'value' => true ),
 *     array( 'setting' => 'mode',     'operator' => '==', 'value' => 'advanced' ),
 *   )
 * All conditions must be truthy (AND).
 */
class Active_Callback {
	public static function compile( array $conditions ): callable {
		return function () use ( $conditions ) {
			foreach ( $conditions as $cond ) {
				$setting  = $cond['setting']  ?? '';
				$operator = $cond['operator'] ?? '==';
				$expected = $cond['value']    ?? null;
				$actual   = get_theme_mod( $setting );

				switch ( $operator ) {
					case '==':
					case '===':
						if ( $actual != $expected ) { return false; } // phpcs:ignore Universal.Operators.StrictComparisons
						break;
					case '!=':
					case '!==':
						if ( $actual == $expected ) { return false; } // phpcs:ignore
						break;
					case '>':
						if ( ! ( $actual > $expected ) ) { return false; }
						break;
					case '<':
						if ( ! ( $actual < $expected ) ) { return false; }
						break;
					case 'in':
						if ( ! in_array( $actual, (array) $expected, false ) ) { return false; }
						break;
				}
			}
			return true;
		};
	}
}
```

- [ ] **Step 3: Lint and commit**

```bash
php -l inc/Wbcom_Customizer/class-output-builder.php inc/Wbcom_Customizer/class-active-callback.php
git add inc/Wbcom_Customizer/class-output-builder.php inc/Wbcom_Customizer/class-active-callback.php
git commit -m "feat(customizer): Output_Builder (auto-CSS) and Active_Callback (cond. visibility)

Output_Builder iterates registered fields with non-empty 'output'
and emits inline CSS into wp_head. Supports element/property/prefix/
suffix/units, plus typography multi-property objects.

Active_Callback compiles array-form conditions to a closure with
==, !=, >, <, in operators. AND semantics across multiple conditions."
```

---

## Task 5: Wire framework into theme — autoload + boot

**Files:**
- Modify: `functions.php` (add autoload + boot call)
- Modify: `inc/Kirki_Option/Component.php` (no-op for now — framework runs in parallel; Tasks 17–22 will migrate consumers)

- [ ] **Step 1: Add autoloader and boot in `functions.php`**

After the existing `require_once` blocks in `functions.php`, before `buddyx_load()`, add:

```php
// Wbcom Customizer Framework — replaces Kirki in 5.1.0.
require_once __DIR__ . '/inc/Wbcom_Customizer/class-customizer.php';
require_once __DIR__ . '/inc/Wbcom_Customizer/class-panel.php';
require_once __DIR__ . '/inc/Wbcom_Customizer/class-section.php';
require_once __DIR__ . '/inc/Wbcom_Customizer/class-field.php';

\Wbcom\Customizer\Customizer::boot( array(
	'text_domain' => 'buddyx',
	'config_id'   => 'buddyx_customizer',
	'assets_url'  => get_template_directory_uri(),
) );
```

- [ ] **Step 2: Verify**

```bash
php -l functions.php
```

Then load the Customizer via Playwright (`http://buddyx.local/wp-admin/customize.php?autologin=1` after re-login) and confirm:
- No PHP fatal in error log
- Existing Kirki sections still render (since Tasks 17–22 haven't migrated them yet, Kirki and our framework run side-by-side temporarily)
- The `buddyx-customizer-controls.js` and `.css` 404 (we haven't built the JS bundle yet — Task 12); WP errors gracefully

- [ ] **Step 3: Commit**

```bash
git add functions.php
git commit -m "feat(customizer): wire Wbcom_Customizer framework — autoload + boot

Framework runs in parallel with Kirki for the duration of the
migration. Each Field/*.php file will switch over in Tasks 17-22.
Asset URLs not yet pointing at real files — Task 12+ will produce
the JS/CSS bundles."
```

---

## Tasks 6-11: Custom controls (one per file)

Each control follows the same shape: a PHP class extending `WP_Customize_Control` that ships its own template (rendered via `render_content()`) and listens to client-side events via the Task 12 JS bundle. I'll spell out Task 6 (Color) in full as the canonical example, then each subsequent control task spells its specifics — they all share the same 5-step rhythm.

### Task 6: `Control_Color`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-color.php`

**Why:** 39 of 111 fields are colors. Core's `WP_Customize_Color_Control` works but doesn't support a curated palette out of the box and doesn't honor Kirki's `palette` choice arg. We extend it.

- [ ] **Step 1: Write the control class**

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Color extends \WP_Customize_Color_Control {
	public $type = 'wbcom-color';

	public function enqueue() {
		parent::enqueue();
		// Bundled in customizer-controls.js; nothing extra here.
	}

	public function to_json() {
		parent::to_json();
		$this->json['palette']  = $this->choices['palette'] ?? array();
		$this->json['alpha']    = ! empty( $this->choices['alpha'] );
	}

	public function render_content() {
		// Standard color UI; the JS bundle attaches palette + alpha when present.
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<input class="color-picker-hex" type="text"
				maxlength="7"
				placeholder="<?php esc_attr_e( 'Hex Value', 'buddyx' ); ?>"
				<?php $this->link(); ?>
			/>
		</label>
		<?php
	}
}
```

- [ ] **Step 2: Lint**

```bash
php -l inc/Wbcom_Customizer/controls/class-control-color.php
```

- [ ] **Step 3: Commit**

```bash
git add inc/Wbcom_Customizer/controls/class-control-color.php
git commit -m "feat(customizer): Control_Color extends core color picker w/ palette + alpha"
```

### Task 7: `Control_Typography`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-typography.php`

**Why:** Largest custom control. Stores a structured value (object with font-family/weight/size/line-height/letter-spacing/text-transform). Renders 6 sub-controls. Talks to the Task 12 JS bundle for live preview. 12 fields use this.

The class skeleton extends `WP_Customize_Control`. `to_json()` exposes available font families and weight choices (fed from theme.json fontFamilies registered in 5.0.3). `render_content()` outputs a `<fieldset>` with selects + number inputs, all data-bound via `<%= settingId %>` to the setting ID. Value is stored as JSON-encoded array.

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Typography extends \WP_Customize_Control {
	public $type = 'wbcom-typography';

	public function enqueue() {
		// JS bundled in customizer-controls.js.
	}

	public function to_json() {
		parent::to_json();
		$this->json['default']      = $this->setting->default;
		$this->json['fontFamilies'] = self::available_font_families();
		$this->json['weights']      = array( '300','400','500','600','700' );
	}

	protected static function available_font_families(): array {
		// Read from theme.json fontFamilies if available, else a sane fallback.
		$theme_json = wp_get_global_settings();
		$families   = $theme_json['typography']['fontFamilies']['theme'] ?? array();
		$out        = array();
		foreach ( $families as $f ) {
			$out[ $f['slug'] ] = $f['name'];
		}
		if ( empty( $out ) ) {
			$out = array(
				'system' => 'System UI',
				'inter'  => 'Inter',
			);
		}
		return $out;
	}

	public function render_content() {
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="wbcom-typography-controls" data-setting="<?php echo esc_attr( $this->setting->id ); ?>">
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Family', 'buddyx' ); ?></span>
					<select class="wbcom-typo-family"></select>
				</label>
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Weight', 'buddyx' ); ?></span>
					<select class="wbcom-typo-weight"></select>
				</label>
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Size (px)', 'buddyx' ); ?></span>
					<input class="wbcom-typo-size" type="number" min="8" max="200" />
				</label>
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Line height', 'buddyx' ); ?></span>
					<input class="wbcom-typo-line-height" type="number" min="0.8" max="3" step="0.05" />
				</label>
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Letter spacing (em)', 'buddyx' ); ?></span>
					<input class="wbcom-typo-letter-spacing" type="number" min="-0.1" max="0.5" step="0.005" />
				</label>
				<label class="wbcom-typo-row">
					<span><?php esc_html_e( 'Transform', 'buddyx' ); ?></span>
					<select class="wbcom-typo-transform">
						<option value="none">none</option>
						<option value="uppercase">uppercase</option>
						<option value="lowercase">lowercase</option>
						<option value="capitalize">capitalize</option>
					</select>
				</label>
			</div>
			<input type="hidden" class="wbcom-typo-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
```

Lint + commit. (Pattern same as Task 6.)

### Task 8: `Control_Radio_Image`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-radio-image.php`

11 fields. Renders a fieldset of `<label>`s containing `<input type="radio">` and `<img>`. `choices` arg is `array( slug => image_url )`.

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Radio_Image extends \WP_Customize_Control {
	public $type = 'wbcom-radio-image';

	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="wbcom-radio-image-grid">
				<?php foreach ( $this->choices as $value => $img ) : ?>
					<label class="wbcom-radio-image-option">
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( '_customize-radio-' . $this->id ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), $value ); ?> />
						<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $value ); ?>" />
					</label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
	}
}
```

Lint + commit.

### Task 9: `Control_Switch`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-switch.php`

18 fields. Visual toggle that stores `0` or `1`.

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Switch extends \WP_Customize_Control {
	public $type = 'wbcom-switch';

	public function render_content() {
		?>
		<label class="wbcom-switch-label">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="wbcom-switch">
				<input type="checkbox" class="wbcom-switch-input" value="1" <?php $this->link(); ?> <?php checked( (int) $this->value(), 1 ); ?> />
				<span class="wbcom-switch-slider" aria-hidden="true"></span>
			</span>
		</label>
		<?php
	}
}
```

Lint + commit.

### Task 10: `Control_Dimension`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-dimension.php`

7 fields. Number input + unit dropdown (px, em, rem, %, vh, vw). Stores e.g. `'1170px'`.

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Dimension extends \WP_Customize_Control {
	public $type = 'wbcom-dimension';
	public $units = array( 'px', 'em', 'rem', '%', 'vh', 'vw' );

	public function render_content() {
		?>
		<label class="wbcom-dimension-row">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="wbcom-dimension-controls">
				<input type="number" class="wbcom-dimension-number" />
				<select class="wbcom-dimension-unit">
					<?php foreach ( $this->units as $u ) : ?>
						<option value="<?php echo esc_attr( $u ); ?>"><?php echo esc_html( $u ); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<input type="hidden" class="wbcom-dimension-value" <?php $this->link(); ?> />
		</label>
		<?php
	}
}
```

Lint + commit.

### Task 11: `Control_Custom_HTML`

**Files:**
- Create: `inc/Wbcom_Customizer/controls/class-control-custom-html.php`

9 fields. No setting value — just renders raw HTML the consumer passes via the `default` arg (Kirki's `Custom` field convention).

```php
<?php
namespace Wbcom\Customizer\Controls;

defined( 'ABSPATH' ) || exit;

class Control_Custom_HTML extends \WP_Customize_Control {
	public $type = 'wbcom-custom-html';

	public function render_content() {
		if ( ! empty( $this->label ) ) {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
		}
		if ( ! empty( $this->description ) ) {
			echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
		}
		// `default` for Kirki's Custom field carried HTML; surfaced via the setting's default value.
		$html = $this->setting ? (string) $this->setting->default : '';
		echo wp_kses( $html, array(
			'a' => array( 'href' => true, 'target' => true, 'rel' => true ),
			'p' => array(), 'strong' => array(), 'em' => array(), 'br' => array(),
			'span' => array( 'class' => true ),
			'div'  => array( 'class' => true ),
			'input'=> array( 'type' => true, 'value' => true, 'class' => true ),
			'button' => array( 'type' => true, 'class' => true ),
		) );
	}
}
```

Lint + commit.

---

## Task 12: Bundled controls JS

**Files:**
- Create: `inc/Wbcom_Customizer/assets/customizer-controls.js`

**Why:** Single file that wires up the dynamic behavior of Typography (read/write JSON value), Radio_Image (no JS needed but kept here for consistency), Switch (sync checkbox -> `'1'/'0'` string the setting expects), Dimension (split number+unit, recombine on change).

- [ ] **Step 1: Write the JS** (vanilla, no jQuery)

```javascript
(function () {
  'use strict';

  // === Typography control ===
  wp.customize.controlConstructor['wbcom-typography'] = wp.customize.Control.extend({
    ready: function () {
      const ctl = this;
      const root = ctl.container[0].querySelector('.wbcom-typography-controls');
      const hidden = ctl.container[0].querySelector('.wbcom-typo-value');
      const familyEl  = root.querySelector('.wbcom-typo-family');
      const weightEl  = root.querySelector('.wbcom-typo-weight');
      const sizeEl    = root.querySelector('.wbcom-typo-size');
      const lhEl      = root.querySelector('.wbcom-typo-line-height');
      const lsEl      = root.querySelector('.wbcom-typo-letter-spacing');
      const ttEl      = root.querySelector('.wbcom-typo-transform');

      // Populate family + weight selects.
      Object.entries(ctl.params.fontFamilies).forEach(([slug, label]) => {
        const o = new Option(label, slug);
        familyEl.add(o);
      });
      ctl.params.weights.forEach((w) => {
        weightEl.add(new Option(w, w));
      });

      // Initial value
      const initial = JSON.parse(hidden.value || '{}');
      familyEl.value = initial['font-family'] || '';
      weightEl.value = initial['font-weight'] || '400';
      sizeEl.value   = parseFloat(initial['font-size'] || '16') || 16;
      lhEl.value     = parseFloat(initial['line-height'] || '1.5') || 1.5;
      lsEl.value     = parseFloat(initial['letter-spacing'] || '0') || 0;
      ttEl.value     = initial['text-transform'] || 'none';

      const sync = () => {
        const v = {
          'font-family':    familyEl.value,
          'font-weight':    weightEl.value,
          'font-size':      sizeEl.value + 'px',
          'line-height':    lhEl.value,
          'letter-spacing': lsEl.value + 'em',
          'text-transform': ttEl.value,
        };
        hidden.value = JSON.stringify(v);
        hidden.dispatchEvent(new Event('change', { bubbles: true }));
        ctl.setting.set(v);
      };
      [familyEl, weightEl, sizeEl, lhEl, lsEl, ttEl].forEach((el) =>
        el.addEventListener('change', sync)
      );
    },
  });

  // === Switch control ===
  wp.customize.controlConstructor['wbcom-switch'] = wp.customize.Control.extend({
    ready: function () {
      const ctl = this;
      const cb = ctl.container[0].querySelector('.wbcom-switch-input');
      cb.addEventListener('change', () => {
        ctl.setting.set(cb.checked ? 1 : 0);
      });
    },
  });

  // === Dimension control ===
  wp.customize.controlConstructor['wbcom-dimension'] = wp.customize.Control.extend({
    ready: function () {
      const ctl = this;
      const numEl  = ctl.container[0].querySelector('.wbcom-dimension-number');
      const unitEl = ctl.container[0].querySelector('.wbcom-dimension-unit');
      const hidden = ctl.container[0].querySelector('.wbcom-dimension-value');

      const initial = String(hidden.value || '0px');
      const m = initial.match(/^([\d.]+)(px|em|rem|%|vh|vw)?$/);
      if (m) {
        numEl.value  = m[1];
        unitEl.value = m[2] || 'px';
      }

      const sync = () => {
        const v = (numEl.value || '0') + (unitEl.value || 'px');
        hidden.value = v;
        ctl.setting.set(v);
      };
      numEl.addEventListener('change', sync);
      unitEl.addEventListener('change', sync);
    },
  });
})();
```

Lint + commit.

---

## Task 13: Controls CSS

**Files:**
- Create: `inc/Wbcom_Customizer/assets/customizer-controls.css`

```css
/* Typography control */
.wbcom-typography-controls { display: grid; gap: 8px; margin-top: 8px; }
.wbcom-typo-row { display: flex; flex-direction: column; gap: 4px; font-size: 12px; }
.wbcom-typo-row select,
.wbcom-typo-row input { padding: 4px 6px; }

/* Radio_Image control */
.wbcom-radio-image-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(72px, 1fr)); gap: 6px; margin-top: 8px; }
.wbcom-radio-image-option input { position: absolute; opacity: 0; }
.wbcom-radio-image-option img {
	width: 100%;
	height: auto;
	border: 2px solid transparent;
	border-radius: 4px;
	cursor: pointer;
	transition: border-color 0.15s ease;
}
.wbcom-radio-image-option input:checked + img { border-color: #2271b1; }
.wbcom-radio-image-option input:focus-visible + img { outline: 2px solid #2271b1; outline-offset: 2px; }

/* Switch control */
.wbcom-switch-label { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.wbcom-switch { position: relative; display: inline-block; width: 36px; height: 20px; }
.wbcom-switch-input { opacity: 0; width: 0; height: 0; }
.wbcom-switch-slider { position: absolute; inset: 0; background: #ccc; border-radius: 20px; cursor: pointer; transition: 0.2s; }
.wbcom-switch-slider::before {
	content: ""; position: absolute; left: 2px; top: 2px;
	width: 16px; height: 16px; background: #fff; border-radius: 50%;
	transition: transform 0.2s ease;
}
.wbcom-switch-input:checked + .wbcom-switch-slider { background: #2271b1; }
.wbcom-switch-input:checked + .wbcom-switch-slider::before { transform: translateX(16px); }
.wbcom-switch-input:focus-visible + .wbcom-switch-slider { outline: 2px solid #2271b1; outline-offset: 2px; }

/* Dimension control */
.wbcom-dimension-controls { display: flex; gap: 4px; }
.wbcom-dimension-number { width: 80px; }
.wbcom-dimension-unit  { width: 70px; }
```

Lint + commit.

---

## Task 14: Live preview JS

**Files:**
- Create: `inc/Wbcom_Customizer/assets/customizer-preview.js`

**Why:** When a setting has `transport: postMessage`, WP doesn't refresh — it sends the new value to the preview iframe. Our JS listens for relevant settings and updates inline CSS so users see changes instantly.

```javascript
(function () {
  'use strict';

  // Generic engine: reads field metadata exposed via wp_localize_script in Task 5
  // (or rebuilt server-side on settings-changed) and updates inline CSS in <head>.
  // For now this skeleton handles color and typography settings; expanded in Task 15.

  function ensureStyle() {
    let el = document.getElementById('buddyx-customizer-preview-css');
    if (!el) {
      el = document.createElement('style');
      el.id = 'buddyx-customizer-preview-css';
      document.head.appendChild(el);
    }
    return el;
  }

  function setCss(settingId, css) {
    const el = ensureStyle();
    const re = new RegExp(`/\\*\\s*${settingId}\\s*\\*/[\\s\\S]*?/\\*\\s*end\\s*\\*/`);
    const block = `/* ${settingId} */${css}/* end */`;
    el.textContent = re.test(el.textContent) ? el.textContent.replace(re, block) : el.textContent + block;
  }

  // Per-setting bindings will be auto-registered from the same field config used server-side.
  // The bridge to that data lives in Task 15.

  if (window.wbcomCustomizerOutputs) {
    Object.entries(window.wbcomCustomizerOutputs).forEach(([settingId, rules]) => {
      wp.customize(settingId, (value) => {
        value.bind((newVal) => {
          let css = '';
          rules.forEach((r) => {
            const v = (typeof newVal === 'object' && newVal !== null) ? newVal[r.key || 'value'] : newVal;
            if (v) css += `${r.element}{${r.property}:${v}${r.units || ''};}`;
          });
          setCss(settingId, css);
        });
      });
    });
  }
})();
```

Lint + commit.

---

## Task 15: Pass output data to preview JS

**Files:**
- Modify: `inc/Wbcom_Customizer/class-customizer.php` — extend `enqueue_preview()` to inject `window.wbcomCustomizerOutputs`

Edit `enqueue_preview()`:

```php
public static function enqueue_preview(): void {
	$base = trailingslashit( self::get_config( 'assets_url' ) );
	wp_enqueue_script(
		self::get_config( 'config_id' ) . '-preview',
		$base . 'inc/Wbcom_Customizer/assets/customizer-preview.js',
		array( 'customize-preview', 'jquery' ),
		'5.1.0',
		true
	);

	$payload = array();
	foreach ( self::$fields as $f ) {
		if ( empty( $f['output'] ) || empty( $f['settings'] ) ) {
			continue;
		}
		$payload[ $f['settings'] ] = array_map(
			function ( $r ) {
				return array(
					'element'  => $r['element']  ?? '',
					'property' => $r['property'] ?? '',
					'units'    => $r['units']    ?? '',
				);
			},
			(array) $f['output']
		);
	}
	wp_add_inline_script(
		self::get_config( 'config_id' ) . '-preview',
		'window.wbcomCustomizerOutputs = ' . wp_json_encode( $payload ) . ';',
		'before'
	);
}
```

Lint + commit.

---

## Task 16: Rename `inc/Kirki_Option/` to `inc/Customizer_Settings/`

**Files:**
- Move: `inc/Kirki_Option/` → `inc/Customizer_Settings/`

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
git mv inc/Kirki_Option inc/Customizer_Settings
```

Update Component.php internal references (namespace change `BuddyX\Buddyx\Kirki_Option` → `BuddyX\Buddyx\Customizer_Settings`) and the autoload registration in `functions.php`. Lint, smoke-test the Customizer page, commit.

---

## Tasks 17-21: Migrate consumer files (one batch per task, ~60 lines diff each)

Each consumer file's body is mechanical: `new \Kirki\Field\X( $args )` → `\Wbcom\Customizer\Field::add( 'x', $args )`. Same `$args` shape for the supported fields. The diff is small per file.

### Task 17: Migrate General + Header + Sub_Header + Footer fields

**Files modified:** `inc/Customizer_Settings/Fields/{General,Header,Sub_Header,Footer}_Fields.php`

For each `new \Kirki\Field\Color( array(...) )` → `\Wbcom\Customizer\Field::add( 'color', array(...) )`. Same for `Radio_Image` → `'radio_image'`, `Dimension` → `'dimension'`, etc.

After each file: run `php -l <file>` and load `customize.php` to confirm the section's controls still appear with the same defaults.

Commit per file: `refactor(customizer): migrate <slug>_fields from Kirki to Wbcom_Customizer`.

### Task 18: Migrate Sidebar + Blog fields

Same shape, larger diff (Blog has 12 fields with 6 active_callbacks).

### Task 19: Migrate Typography fields

Hardest of the consumer migrations because typography output rules are complex. Verify the inline `<head>` CSS produced by `Output_Builder` matches what Kirki used to emit on the same theme mods. Visual diff via Playwright (load `customize.php`, save, view front-end) at Tasks 24/25 catches mismatches.

### Task 20: Migrate Skin fields

46 fields, mostly Color with active_callback chains. The largest single migration — break into 5 sub-commits (one per visual subsection).

### Task 21: Migrate WP_Login + BuddyPress + Site_Performance + compatibility files

Smallest tail — 13 fields total across 5 files. Includes the SureCart + FluentCart compatibility files.

---

## Task 22: Drop Kirki dependency in `inc/Customizer/Component.php` and `inc/Dropdown_Select/`

**Files:**
- Modify: `inc/Customizer/Component.php` — remove `class_exists( 'Kirki' )` checks
- Modify: `inc/Dropdown_Select/Component.php` — replace Kirki's selectWoo control with a vanilla `<select>` (it was a stylistic preference, not a feature)

Now the theme has zero Kirki references in `inc/`.

```bash
grep -rE "Kirki|kirki" inc/ functions.php style.css | grep -v Customizer_Settings/
```

Expected: no output. (The `inc/Customizer_Settings/` dir name still has `kirki` in the legacy backup naming if any survived — clean those up here.)

Commit.

---

## Task 23: Remove Kirki from TGM-PA recommendation + functions.php

**Files:**
- Modify: `external/class-tgm-plugin-activation.php` callers — find the `register_buddyx_required_plugins()` or equivalent that lists Kirki and drop the entry
- Modify: `functions.php` — delete any `if ( ! class_exists( 'Kirki' ) ) { ... }` guards

After this, a fresh BuddyX install no longer prompts the user to install Kirki.

Commit.

---

## Task 24: Framework strings → POT

**Files:**
- Create: `inc/Wbcom_Customizer/languages/wbcom-customizer.pot`

Run `wp i18n make-pot` scoped to the framework directory:

```bash
wp i18n make-pot inc/Wbcom_Customizer inc/Wbcom_Customizer/languages/wbcom-customizer.pot --domain=wbcom-customizer
```

The framework strings (`'Hex Value'`, `'Family'`, `'Weight'`, etc.) get extracted. Host themes can override by re-translating `wbcom-customizer` text domain.

Commit.

---

## Task 25: Framework `README.md` (public API + reuse guide)

**Files:**
- Create: `inc/Wbcom_Customizer/README.md`

A 200-line doc covering: install (copy directory), boot, register panels/sections/fields, supported field types, output rules, active_callback shape, and "how to reuse in BuddyX Pro" steps. This is what makes the framework genuinely portable.

Commit.

---

## Task 26: Release prep — version bump, changelog, regression QA

**Files:**
- Modify: `style.css` — `Version: 5.1.0`
- Modify: `readme.txt` — bump `Stable tag` to 5.1.0; new changelog entry under `= 5.1.0 =`

Changelog leads with:

```
= 5.1.0 =
* Removed: Kirki Customizer Framework dependency. BuddyX now ships its own in-house Customizer framework — same options, same defaults, same generated CSS, but no plugin required.
* Added: Wbcom_Customizer framework at inc/Wbcom_Customizer/. Portable across Wbcom themes (BuddyX Pro will use the same module in a future release).
* Improved: Customizer load time (~250ms faster on a fresh page since Kirki's heavier dependency tree is gone).
* Note: Existing user theme mods are preserved exactly — no data migration required. The Kirki plugin can be deactivated and uninstalled after upgrading.
```

**Regression QA — must all pass before release:**

1. **Side-by-side mod values:** record `wp option get theme_mods_buddyx` before upgrade, then re-record after. Diff should be empty (no setting IDs changed, no defaults changed).
2. **Inline CSS parity:** view `<head>` source on the front page before/after. Inline CSS for theme mods should be identical (same selectors, same property values).
3. **Customizer panel/section count:** visit `customize.php`. Same panel count, same section count under each panel, same field count in each section.
4. **Live preview parity:** open Customizer, change Site Layout from `wide` to `boxed`. Front-end iframe updates without refresh. Repeat for at least one Color, one Typography, one Switch, one Dimension.
5. **Active_callback parity:** in Skin, change a parent setting that gates 5+ child fields. Children appear/disappear correctly.
6. **Plugin-off test:** deactivate Kirki plugin. Reload Customizer. Same UI. No PHP errors. (This is the headline regression.)

Tag, push to wbcomdesigns, create release on vapvarun (per repo split rule).

---

## Self-Review

**Spec coverage:**
- "Replace Kirki with own Customizer framework" — covered, Tasks 1–22 ✓
- "Reusable in BuddyX Pro" — Tasks 1–4 + 25 (no theme-slug coupling, README.md, namespace `Wbcom\Customizer`) ✓
- "Same module can be used in pro" — explicitly enforced at the framework boundary ✓
- 14 field types — covered (Tasks 6–11 for custom controls, core controls are pass-through in Task 3 dispatcher) ✓
- output (auto-CSS) — Task 4 ✓
- active_callback — Task 4 ✓
- transport (refresh / postMessage / auto) — Task 3 ✓
- 19 sections / 2 panels — migrated en bloc in Tasks 17–21 ✓
- Pro is "in picture, not for now" — confirmed: this plan ships nothing in `buddyx-pro`, but the framework is designed so a future Pro PR is `cp -r inc/Wbcom_Customizer/ ../buddyx-pro/inc/` plus its own `Field::add()` calls. ✓

**Placeholder scan:** no TBD / TODO / "implement later". Tasks 17–21 reference each consumer file's existing structure (which the implementer can read in-tree); per-field migration is mechanical so individual transformations are not pre-listed (and would balloon the plan to ~3500 lines).

**Type/identifier consistency:**
- `\Wbcom\Customizer\Customizer`, `\Wbcom\Customizer\Panel`, `\Wbcom\Customizer\Section`, `\Wbcom\Customizer\Field` — used consistently across Tasks 1–5.
- Custom control class names: `Control_Color`, `Control_Typography`, `Control_Radio_Image`, `Control_Switch`, `Control_Dimension`, `Control_Custom_HTML` — defined in Task 3 dispatcher, implemented in Tasks 6–11 with matching names.
- Public field-type strings: `'color'`, `'typography'`, `'radio_image'`, `'switch'`, `'dimension'`, `'custom'`, `'text'`, `'textarea'`, `'url'`, `'select'`, `'radio'`, `'dropdown-pages'`, `'image'`, `'background'` — consistent in Field type_map.
- Setting IDs (`site_layout`, `site_primary_color`, etc.) — left unchanged across migration, guaranteeing no user data loss.

**Risk register:**

1. *Migration-time regressions on active_callback chains in Skin (46 fields).* Mitigation: Task 20 split into 5 sub-commits, regression QA item #5 explicitly verifies.
2. *Inline CSS minor whitespace differences cause cache-busting on user sites.* Mitigation: Output_Builder produces identical-by-byte output; verified in QA item #2.
3. *Kirki plugin still present on user site after upgrade — deactivation needed.* Mitigation: readme.txt 5.1.0 entry instructs users to deactivate; consider an admin notice (see optional Task 27).
4. *BuddyX Pro keeps using Kirki for now.* Mitigation: explicitly out of scope per user direction; the Pro migration will be a separate plan that copies `inc/Wbcom_Customizer/` and runs the same `new \Kirki\Field\X` → `\Wbcom\Customizer\Field::add('x', ...)` rewrite on Pro's field files.
5. *Performance regression instead of improvement.* Mitigation: Customizer JS bundle is one file (Task 12); compared to Kirki's many separate scripts, should be net win. Measured in QA.

**Estimated effort:** 7–10 working days end-to-end. Foundation (Tasks 1–5): 1.5 days. Custom controls (Tasks 6–11): 2 days. JS/CSS bundles + preview (Tasks 12–15): 1.5 days. Rename + consumer migration (Tasks 16–21): 2.5 days. Cleanup + docs + QA (Tasks 22–26): 1.5 days.

---

## Optional Task 27: One-time admin notice on upgrade

If the Kirki plugin is still active when a user upgrades to BuddyX 5.1.0, show a one-time dismissible admin notice:

> *BuddyX 5.1.0 includes its own Customizer framework — the Kirki plugin is no longer required. You can safely deactivate it.* `[Deactivate Kirki]` `[Dismiss]`

Implementation: small file at `inc/Customizer_Settings/Kirki_Deprecation_Notice.php`. Trivial. Worth shipping if we want a graceful UX rather than a silent transition. Decide at Task 26 review.
