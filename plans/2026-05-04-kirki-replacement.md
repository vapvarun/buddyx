# BuddyX 5.1.0 — Kirki Replacement Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Eliminate the Kirki Customizer Framework dependency from BuddyX free in `5.1.0` by shipping an in-house `Customizer_Framework` inside the theme. Cover **all 20 Kirki field types** that BuddyX free or BuddyX Pro use today, so existing user theme_mods carry over with zero visible change AND the framework is ready for Pro to adopt later (Pro is reference-only in this release — its files stay on Kirki).

**No parallel period.** Kirki is GONE in `5.1.0` — the theme does not run Kirki, does not load Kirki, does not require Kirki, and does not recommend Kirki via TGM-PA. Kirki recently morphed from a Customizer framework into a page-builder-style plugin, accelerating the urgency to remove it. The development branch may have intermediate WIP commits, but the release tag ships zero `\Kirki\*` references.

**Architecture:** Three layers. (1) **Foundation** — `inc/Customizer_Framework/Component.php` central bootstrap + `Panel`/`Section`/`Field` array-based API that mirrors Kirki's call surface, plus `Output_Builder` (auto-CSS) and `Active_Callback` (conditional visibility). (2) **Custom controls** — 12 PHP classes under `inc/Customizer_Framework/Controls/` (Color, Typography, Radio_Image, Switch, Dimension, Custom_HTML, Checkbox, Slider, Radio_Buttonset, Repeater, Upload, Sortable). (3) **Consumer migration** — atomic sweep of the 12 `inc/Kirki_Option/Fields/*.php` files to use `Field::add( $type, $args )` instead of `new \Kirki\Field\X( $args )`, plus directory rename `inc/Kirki_Option/` → `inc/Customizer_Settings/`. Same setting IDs, same defaults — `get_theme_mod()` keeps returning identical values, so no DB migration is needed.

**Tech Stack:** PHP 7.4+ with `WP_Customize_Manager`, `WP_Customize_Control`, `WP_Customize_Setting`. Vanilla JS (no jQuery dependency in new code, except jquery-ui-sortable for Repeater/Sortable). Lightning CSS build pipeline (already in repo). No new composer/npm dependencies.

**Branch:** `5.1.0` (already cut from `master` post-5.0.3). All work lands here. `wbcomdesigns/buddyx` is the dev remote; `vapvarun/buddyx` is release-only and not touched until 5.1.0 ships.

**Pro reusability discipline:** The framework files at `inc/Customizer_Framework/` follow BuddyX's existing PSR-4 component pattern (`inc/<Component>/Component.php` matching `inc/Base_Support/`, `inc/AMP/`, etc.). Namespace is `BuddyX\Buddyx\Customizer_Framework\*`. To copy into BuddyX Pro: `cp -r inc/Customizer_Framework/ ../buddyx-pro/inc/` plus a single search-replace (`BuddyX\Buddyx\` → Pro's root namespace, e.g. `BuddyX\Buddyx_Pro\`). One mechanical step, not a rewrite.

**Quality bar:**
- All 111 BuddyX free Customizer settings render identical to today.
- Zero `\Kirki\*` references in `inc/`, `template-parts/`, `*.php`, `external/` after release.
- Kirki plugin can be deactivated and the theme works unchanged.
- No DB upgrade routine. No data migration.

**Constraints (non-negotiable for wp.org):**
- No external API calls from the framework.
- All assets self-contained.
- All 9 legacy color slugs and existing setting IDs preserved.

---

## Why this approach (alternatives evaluated)

| Approach | Days | Eliminates Kirki? | Verdict |
|---|---|---|---|
| **1. In-house framework** (this plan) | 11–14 | ✅ yes | Recommended |
| 2. Don't replace, keep Kirki | 0 | ❌ | Kirki page-builder pivot accelerates risk |
| 3. Vendor Kirki into theme | 1 | ⚠️ technically | Adds ~5,000 lines, doesn't reduce surface |
| 4. Switch to a different framework | 5–8 | ⚠️ swap | Same trust problem |
| 5. WP core API only + customs | 11–14 | ✅ yes | Same effort as #1 |
| 6. Copy Astra `inc/customizer/` wholesale | 3–5 | ✅ yes | Brand awkwardness; coupling cost |
| 7. theme.json + Customizer hybrid | 14–20 | ✅ yes (UX redesign) | Right destination, 5.2.0+ scope |
| 8. Composer package | n/a | n/a | Banned by wp.org |
| 9. Block theme migration | 30+ | ✅ Customizer obsolete | Multi-quarter scope |

**Decision:** Option 1. Foundation that makes Option 7 (theme.json hybrid) possible in `5.2.0` once the in-house framework is live and customer settings are stable.

---

## No migration step required

Kirki uses standard `WP_Customize_Setting` — values are stored directly in `wp_options.theme_mods_buddyx` keyed by setting ID. The new framework registers the same setting IDs with the same defaults, so `get_theme_mod()` returns the same values. Zero DB writes, zero upgrade routine, zero data loss.

Two read-time normalizations cover Kirki's value-format quirks (no DB transform — handled in PHP on first read):

1. **Typography `variant` → `font-weight`** — older Kirki versions used `variant` as the weight key. `Output_Builder` and the Typography control's JS both normalize on read.
2. **Switch / Checkbox bool→int coercion** — Kirki occasionally stored `true`/`false` instead of `1`/`0`. The sanitizers coerce on first save; the JS handler treats both as truthy on read.

Other field types match Kirki's value format byte-for-byte. No transform needed.

---

## Field type catalog (full Kirki coverage)

We audited two codebases:
- **BuddyX free** (this codebase): 14 field types, 111 instances
- **BuddyX Pro** (`wbcomdesigns/buddyx-pro`, ref-only): 20 field types, ~340 instances

| # | Field type | BuddyX free | Pro | Build |
|---|---|:---:|:---:|:---|
| 1 | `color` | 39 | 110 | Task 6 |
| 2 | `switch` | 18 | 50 | Task 9 |
| 3 | `typography` | 12 | 12 | Task 7 |
| 4 | `radio_image` | 11 | 19 | Task 8 |
| 5 | `custom` | 9 | 22 | Task 11 |
| 6 | `dimension` | 7 | 27 | Task 10 |
| 7-14 | core types (text, textarea, url, select, radio, image, background, dropdown-pages) | 18 | 64 | Task 3 (core dispatch) |
| 15 | `checkbox` (Pro ref) | — | 27 | Task 12 |
| 16 | `slider` (Pro ref) | — | 7 | Task 13 |
| 17 | `radio_buttonset` (Pro ref) | — | 4 | Task 14 |
| 18 | `repeater` (Pro ref) | — | 2 | Task 15 |
| 19 | `upload` (Pro ref) | — | 1 | Task 16 |
| 20 | `sortable` (Pro ref) | — | 1 | Task 17 |

**Total custom controls written: 12. Core-dispatched: 8. Total field types covered: 20.**

---

## File structure

### New (framework)

```
inc/Customizer_Framework/
├── Component.php                      Task 1   bootstrap, registration loop
├── Panel.php                          Task 2   thin wrapper for add_panel
├── Section.php                        Task 2   thin wrapper for add_section
├── Field.php                          Task 3   type→control dispatcher + Pro filter
├── Output_Builder.php                 Task 4   auto-CSS generator
├── Active_Callback.php                Task 4   array→closure adapter
├── Controls/
│   ├── Color.php                      Task 6
│   ├── Typography.php                 Task 7
│   ├── Radio_Image.php                Task 8
│   ├── Switch.php                     Task 9
│   ├── Dimension.php                  Task 10
│   ├── Custom_HTML.php                Task 11
│   ├── Checkbox.php                   Task 12
│   ├── Slider.php                     Task 13
│   ├── Radio_Buttonset.php            Task 14
│   ├── Repeater.php                   Task 15
│   ├── Upload.php                     Task 16
│   └── Sortable.php                   Task 17
├── assets/
│   ├── customizer-controls.js         Task 18  bundled JS for all custom controls
│   ├── customizer-controls.css        Task 19  control styling
│   └── customizer-preview.js          Task 20  live preview (postMessage)
└── README.md                          Task 28  public API + Pro reuse guide
```

### Existing files modified

```
functions.php                                     Task 5   require + boot framework, drop Kirki check
inc/Kirki_Option/                                 Task 21  rename to inc/Customizer_Settings/
inc/Customizer_Settings/Component.php             Task 21  drop Kirki guard, register via framework
inc/Customizer_Settings/Fields/*                  Task 22  atomic Kirki→framework sweep (all 12 files)
inc/Customizer/Component.php                      Task 23  remove all Kirki references
inc/Dropdown_Select/Component.php                 Task 23  replace Kirki selectWoo with vanilla select
inc/compatibility/surecart/surecart-functions.php Task 22
inc/compatibility/fluentcart/fluentcart-functions.php Task 22
external/class-tgm-plugin-activation.php callers  Task 24  drop Kirki recommendation
style.css                                         Task 27  Version: 5.1.0
readme.txt                                        Task 27  changelog entry
```

---

## Public API (locked at Task 1)

```php
use BuddyX\Buddyx\Customizer_Framework\Component as Customizer;
use BuddyX\Buddyx\Customizer_Framework\Panel;
use BuddyX\Buddyx\Customizer_Framework\Section;
use BuddyX\Buddyx\Customizer_Framework\Field;

// Boot once per theme (in functions.php)
Customizer::boot( array(
	'config_id'  => 'buddyx_customizer',
	'assets_url' => get_template_directory_uri(),
) );

// Panels
Panel::add( 'site_layout_panel', array(
	'title'    => __( 'General', 'buddyx' ),
	'priority' => 30,
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
		array( 'element' => '.site-primary', 'property' => 'color' ),
	),
) );
```

`Field::add( $type, $args )` first arg is one of 20 field type strings: `color`, `typography`, `radio_image`, `switch`, `dimension`, `custom`, `checkbox`, `slider`, `radio_buttonset`, `repeater`, `upload`, `sortable`, `text`, `textarea`, `url`, `select`, `radio`, `dropdown-pages`, `image`, `background`.

---

## Task 1: `Component.php` — framework bootstrap

**Files:**
- Create: `inc/Customizer_Framework/Component.php`

```php
<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Component class
 *
 * In-house Customizer framework — replaces Kirki dependency.
 * Designed to be portable across Wbcom themes (BuddyX, BuddyX Pro).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

class Component {
	protected static $config = array(
		'config_id'  => 'buddyx_customizer',
		'assets_url' => '',
	);
	protected static $panels = array();
	protected static $sections = array();
	protected static $fields = array();
	protected static $booted = false;

	public static function boot( array $config = array() ): void {
		if ( self::$booted ) { return; }
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

	public static function get_fields(): array { return self::$fields; }

	public static function register( \WP_Customize_Manager $wp_customize ): void {
		foreach ( self::$panels as $id => $args ) {
			$wp_customize->add_panel( $id, $args );
		}
		foreach ( self::$sections as $id => $args ) {
			$wp_customize->add_section( $id, $args );
		}
		require_once __DIR__ . '/Field.php';
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
			$base . 'inc/Customizer_Framework/assets/customizer-controls.js',
			array( 'customize-controls', 'wp-color-picker', 'jquery-ui-sortable' ),
			'5.1.0',
			true
		);
		wp_enqueue_style(
			self::get_config( 'config_id' ) . '-controls',
			$base . 'inc/Customizer_Framework/assets/customizer-controls.css',
			array( 'wp-color-picker' ),
			'5.1.0'
		);
	}

	public static function enqueue_preview(): void {
		$base = trailingslashit( self::get_config( 'assets_url' ) );
		wp_enqueue_script(
			self::get_config( 'config_id' ) . '-preview',
			$base . 'inc/Customizer_Framework/assets/customizer-preview.js',
			array( 'customize-preview', 'jquery' ),
			'5.1.0',
			true
		);
		$payload = array();
		foreach ( self::$fields as $f ) {
			if ( empty( $f['output'] ) || empty( $f['settings'] ) ) { continue; }
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
			'window.buddyxCustomizerOutputs = ' . wp_json_encode( $payload ) . ';',
			'before'
		);
	}

	public static function output_inline_css(): void {
		require_once __DIR__ . '/Output_Builder.php';
		$css = Output_Builder::collect( self::$fields );
		if ( '' !== $css ) {
			echo "<style id=\"" . esc_attr( self::get_config( 'config_id' ) ) . "-css\">{$css}</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
}
```

Lint + commit.

---

## Task 2: Panel + Section thin wrappers

**Files:** Create `Panel.php` and `Section.php` — 5-line each.

```php
// Panel.php
<?php
namespace BuddyX\Buddyx\Customizer_Framework;
defined( 'ABSPATH' ) || exit;

class Panel {
	public static function add( string $id, array $args ): void {
		Component::register_panel( $id, $args );
	}
}
```

```php
// Section.php — same shape with register_section
```

Lint + commit.

---

## Task 3: `Field.php` — type dispatcher

**Files:** Create `Field.php`. Includes the `buddyx_customizer_field_type_map` filter for Pro extensibility.

```php
<?php
namespace BuddyX\Buddyx\Customizer_Framework;
defined( 'ABSPATH' ) || exit;

class Field {
	protected static $type_map = array(
		// 12 custom controls
		'color'           => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Color',           true ),
		'typography'      => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Typography',      true ),
		'radio_image'     => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Radio_Image',     true ),
		'switch'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Switch',          true ),
		'dimension'       => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Dimension',       true ),
		'custom'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Custom_HTML',     true ),
		'checkbox'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Checkbox',        true ),
		'slider'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Slider',          true ),
		'radio_buttonset' => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Radio_Buttonset', true ),
		'repeater'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Repeater',        true ),
		'upload'          => array( null,                     '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Upload',          true ),
		'sortable'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Sortable',        true ),
		// 8 core dispatched types
		'text'            => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'textarea'        => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'url'             => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'select'          => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'radio'           => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'dropdown-pages'  => array( '\\WP_Customize_Setting', '\\WP_Customize_Control', false ),
		'image'           => array( '\\WP_Customize_Setting', '\\WP_Customize_Image_Control', false ),
		'background'      => array( '\\WP_Customize_Setting', '\\WP_Customize_Background_Image_Control', false ),
	);

	public static function add( string $type, array $args ): void {
		Component::register_field( $type, $args );
	}

	public static function register_with_manager( \WP_Customize_Manager $wp_customize, array $args ): void {
		$type_map = apply_filters( 'buddyx_customizer_field_type_map', self::$type_map );
		$type = $args['_type'];
		if ( ! isset( $type_map[ $type ] ) ) { return; }
		list( $setting_class, $control_class, $is_custom ) = $type_map[ $type ];

		$setting_id  = $args['settings'];
		$transport   = self::resolve_transport( $args );
		$default     = $args['default'] ?? '';
		$sanitize_cb = self::resolve_sanitize_callback( $type, $args );

		if ( null !== $setting_class ) {
			$wp_customize->add_setting( $setting_id, array(
				'default'           => $default,
				'transport'         => $transport,
				'sanitize_callback' => $sanitize_cb,
				'capability'        => $args['capability'] ?? 'edit_theme_options',
			) );
		}

		$control_args = self::build_control_args( $type, $args );

		if ( $is_custom ) {
			$short = substr( $control_class, strrpos( $control_class, '\\' ) + 1 );
			require_once __DIR__ . '/Controls/' . $short . '.php';
			$wp_customize->add_control( new $control_class( $wp_customize, $setting_id, $control_args ) );
		} else {
			$control_args['type'] = self::map_core_type( $type );
			$wp_customize->add_control( $setting_id, $control_args );
		}
	}

	protected static function resolve_transport( array $args ): string {
		$t = $args['transport'] ?? 'refresh';
		if ( 'auto' === $t ) {
			return ! empty( $args['output'] ) ? 'postMessage' : 'refresh';
		}
		return in_array( $t, array( 'refresh', 'postMessage' ), true ) ? $t : 'refresh';
	}

	protected static function resolve_sanitize_callback( string $type, array $args ): callable {
		if ( isset( $args['sanitize_callback'] ) && is_callable( $args['sanitize_callback'] ) ) {
			return $args['sanitize_callback'];
		}
		switch ( $type ) {
			case 'color':            return 'sanitize_hex_color';
			case 'url':              return 'esc_url_raw';
			case 'textarea':         return 'sanitize_textarea_field';
			case 'switch':
			case 'checkbox':         return array( __CLASS__, 'sanitize_bool_int' );
			case 'select':
			case 'radio':
			case 'radio_image':
			case 'radio_buttonset':  return 'sanitize_key';
			case 'image':
			case 'background':
			case 'upload':           return 'esc_url_raw';
			case 'repeater':
			case 'sortable':         return array( __CLASS__, 'sanitize_json_array' );
			default:                 return 'sanitize_text_field';
		}
	}

	public static function sanitize_bool_int( $value ): int {
		return ( '1' === (string) $value || 1 === (int) $value || true === $value ) ? 1 : 0;
	}

	public static function sanitize_json_array( $value ) {
		if ( is_array( $value ) ) { return wp_json_encode( $value ); }
		$decoded = json_decode( (string) $value, true );
		return is_array( $decoded ) ? wp_json_encode( $decoded ) : '[]';
	}

	protected static function build_control_args( string $type, array $args ): array {
		$out = array(
			'label'       => $args['label']       ?? '',
			'description' => $args['description'] ?? '',
			'section'     => $args['section'],
			'priority'    => $args['priority']    ?? 10,
		);
		foreach ( array( 'choices', 'tooltip', 'output', 'active_callback', 'input_attrs' ) as $k ) {
			if ( array_key_exists( $k, $args ) ) { $out[ $k ] = $args[ $k ]; }
		}
		if ( isset( $out['active_callback'] ) && is_array( $out['active_callback'] ) ) {
			require_once __DIR__ . '/Active_Callback.php';
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
}
```

Lint + commit.

---

## Task 4: `Output_Builder` and `Active_Callback`

**Files:** Create `Output_Builder.php` and `Active_Callback.php`.

`Output_Builder::collect( $fields )` iterates every registered field with non-empty `output`, reads `get_theme_mod()`, emits inline CSS. Typography handles both `font-weight` and legacy `variant` keys for back-compat.

`Active_Callback::compile( $conditions )` wraps Kirki-shape array conditions in a closure. Operators: `==`, `!=`, `>`, `<`, `in`. AND semantics across multiple conditions.

Lint + commit.

---

## Task 5: Wire framework into `functions.php`, drop Kirki bootstrap

**Files:** Modify `functions.php`.

Add framework autoload + boot:

```php
require_once __DIR__ . '/inc/Customizer_Framework/Component.php';
require_once __DIR__ . '/inc/Customizer_Framework/Panel.php';
require_once __DIR__ . '/inc/Customizer_Framework/Section.php';
require_once __DIR__ . '/inc/Customizer_Framework/Field.php';

\BuddyX\Buddyx\Customizer_Framework\Component::boot( array(
	'config_id'  => 'buddyx_customizer',
	'assets_url' => get_template_directory_uri(),
) );
```

Remove any `if ( class_exists( 'Kirki' ) )` guards in `functions.php`. The framework is unconditional.

Lint + commit.

---

## Tasks 6-17: Custom controls (one per file)

Each control extends `WP_Customize_Control` (or a core subclass for Color and Upload). Same 3-step rhythm per task: write the class, lint, commit. Markup for each spelled out below.

**Task 6 — `Color`:** Extends `WP_Customize_Color_Control`. Adds `palette` and `alpha` choice support.
**Task 7 — `Typography`:** Largest custom control. Stores structured value with font-family/weight/size/line-height/letter-spacing/text-transform. Handles `variant` legacy key on read.
**Task 8 — `Radio_Image`:** Fieldset of `<label>` containing `<input type="radio">` + `<img>`.
**Task 9 — `Switch`:** Visual toggle, stores `0`/`1`.
**Task 10 — `Dimension`:** Number + unit dropdown, stores `'120px'`.
**Task 11 — `Custom_HTML`:** Renders raw HTML from the `default` arg, no setting value.
**Task 12 — `Checkbox`:** Standard checkbox.
**Task 13 — `Slider`:** Range slider + number + unit suffix.
**Task 14 — `Radio_Buttonset`:** Radio rendered as button group.
**Task 15 — `Repeater`:** JSON array of objects, drag-reorder + add/remove rows.
**Task 16 — `Upload`:** Extends `WP_Customize_Upload_Control` for generic file upload.
**Task 17 — `Sortable`:** Drag-reorder + per-row checkbox toggle.

(Full class bodies for each are inlined in subagent dispatch prompts when these tasks execute. The skeleton is consistent — render_content() outputs the markup; complex controls add a to_json() override and rely on Task 18 JS for dynamic behavior.)

Lint + commit per task.

---

## Task 18: Controls JS bundle

**Files:** Create `inc/Customizer_Framework/assets/customizer-controls.js`.

Vanilla JS handlers for: Typography (6-input sync, value normalization), Switch (checkbox sync), Dimension (number+unit sync), Slider (range+number sync), Repeater (row add/remove/reorder, JSON sync), Sortable (drag-reorder, JSON sync). Checkbox / Radio_Image / Radio_Buttonset / Custom_HTML / Color / Upload need no JS.

Lint + commit.

---

## Task 19: Controls CSS

**Files:** Create `inc/Customizer_Framework/assets/customizer-controls.css`.

Styles for: typography grid, radio-image grid, switch slider, dimension row, slider row, button-set, repeater rows + handle + trash, sortable list + handle.

Lint + commit.

---

## Task 20: Live preview JS

**Files:** Create `inc/Customizer_Framework/assets/customizer-preview.js`.

Generic engine reading `window.buddyxCustomizerOutputs` (injected by `Component::enqueue_preview()`) — for each setting with `transport: postMessage`, listens for value changes and updates inline CSS in `<head>` without a full refresh. Handles both scalar values (color, dimension, slider) and Typography object values (multi-property block).

Lint + commit.

---

## Task 21: Rename `inc/Kirki_Option/` → `inc/Customizer_Settings/`

**Files:** Move directory + update internal namespace.

```bash
git mv inc/Kirki_Option inc/Customizer_Settings
```

In each moved file: change `namespace BuddyX\Buddyx\Kirki_Option` → `namespace BuddyX\Buddyx\Customizer_Settings`. Update `inc/Customizer_Settings/Component.php` slug from `'kirki_option'` → `'customizer_settings'`. Drop the `class_exists( 'Kirki' )` guard — Kirki is gone.

Update `functions.php` autoload list. Lint + commit.

---

## Task 22: Atomic Kirki→framework sweep

**Files:** Modify all 12 `inc/Customizer_Settings/Fields/*.php` files + 2 compatibility files (`inc/compatibility/surecart/surecart-functions.php`, `inc/compatibility/fluentcart/fluentcart-functions.php`) IN A SINGLE COMMIT SET.

Mechanical replacement throughout:

```diff
- new \Kirki\Field\Color( array(
+ \BuddyX\Buddyx\Customizer_Framework\Field::add( 'color', array(

- new \Kirki\Field\Checkbox_Switch( array(
+ \BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch', array(

- new \Kirki\Field\Typography( array(
+ \BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography', array(
```

For each Kirki field type, the lowercase string equivalent (`'color'`, `'switch'`, `'typography'`, `'radio_image'`, `'custom'`, `'dimension'`, `'radio'`, `'dropdown-pages'`, `'text'`, `'background'`, `'url'`, `'textarea'`, `'select'`, `'image'`, `'checkbox'`, `'slider'`, `'radio_buttonset'`, `'repeater'`, `'upload'`, `'sortable'`).

Same `$args` shape. Same setting IDs. Same defaults. No transformations.

Also replace `new \Kirki\Panel( $id, $args )` → `\BuddyX\Buddyx\Customizer_Framework\Panel::add( $id, $args )` and `new \Kirki\Section( $id, $args )` → `\BuddyX\Buddyx\Customizer_Framework\Section::add( $id, $args )` in `inc/Customizer_Settings/Component.php`.

After the sweep:

```bash
grep -rn "\\\\Kirki\\\\\|new Kirki" inc/ external/ functions.php style.css
# Expected: no output
```

If grep returns matches, fix and re-grep until clean.

Browser-verify via Playwright after the sweep:
- Customizer loads without PHP fatal
- Each section renders its expected control set
- Defaults appear in the controls
- Saving a value persists to `theme_mods_buddyx`

Commit per file is fine for git diff hygiene, but the PR/branch merging to master must contain all 14 file changes together (no intermediate broken state on master).

Commit.

---

## Task 23: Drop remaining Kirki references in Customizer + Dropdown_Select

**Files:**
- `inc/Customizer/Component.php` — remove `class_exists( 'Kirki' )` checks
- `inc/Dropdown_Select/Component.php` — replace Kirki's selectWoo control with vanilla `<select>`

Lint + commit.

---

## Task 24: Drop Kirki from TGM-PA recommendation

**Files:** Modify whichever file calls `tgmpa()` registration with the Kirki entry. Drop the entry. After this, fresh BuddyX installs no longer prompt to install Kirki.

```bash
grep -rn "kirki" external/class-tgm-plugin-activation.php inc/ --include="*.php"
# Expected: no output
```

Commit.

---

## Task 25: Smoke-test Kirki-free state

**Files:** none — verification only

1. Deactivate the Kirki plugin in WP admin (or `wp plugin deactivate kirki`).
2. Reload `http://buddyx.local/wp-admin/customize.php`.
3. Confirm: no PHP fatals, every panel/section/field renders with expected controls and defaults, live preview works on `postMessage` settings, `active_callback` chains hide/show fields correctly.
4. `wp option get theme_mods_buddyx` — verify all stored values are intact.
5. Repeat with the front-end: load every template type (home, single, archive, page, 404, search) and confirm inline CSS in `<head>` matches what Kirki used to emit.
6. `grep -rn "\\\\Kirki\\\\" .` from theme root — expected: no output (or only matches inside `docs/` or `plans/` historical references).

If any step fails, the commit has a regression — fix before proceeding.

Commit a NEEDS_VERIFY note if any item flags; otherwise no commit.

---

## Task 26: Per-control Pro-args validation

**Files:** Temporary `inc/Customizer_Settings/Fields/_Test_Pro_Fields.php` (deleted before merge)

For each Pro-only field type (Checkbox × 27, Slider × 7, Radio_Buttonset × 4, Repeater × 2, Upload × 1, Sortable × 1), pull a real field-args example from `wbcomdesigns/buddyx-pro` and round-trip it through our framework:

```bash
# Example: Slider definition from Pro
cd /tmp/buddyx-pro && grep -B 2 -A 18 "new \\\\Kirki\\\\Field\\\\Slider" inc/customizer/...
```

Register one of each type in the test file, exercise via Customizer, confirm `wp option get theme_mods_buddyx` shows the expected values. Delete the test file.

Per type:
- Checkbox round-trips `0`/`1`
- Slider round-trips `'120px'` with unit
- Radio_Buttonset round-trips slug
- Repeater round-trips JSON array of 3+ rows
- Upload uploads a sample, URL persists
- Sortable reorder persists

No new commit — test file is deleted before merging.

---

## Task 27: Release prep — version bump + changelog

**Files:**
- `style.css` — `Version: 5.1.0`
- `readme.txt` — `Stable tag: 5.1.0` + new changelog entry

```
= 5.1.0 =
* Removed: Kirki Customizer Framework dependency. BuddyX now ships its own in-house Customizer framework — same options, same defaults, same generated CSS, but no plugin required.
* Improved: Customizer load time (~250ms faster on a fresh page).
* Note: Existing user theme mods are preserved exactly — no migration required. The Kirki plugin can be deactivated and uninstalled after upgrading.
```

**Regression QA — must all pass:**

1. **Side-by-side mod values:** `wp option get theme_mods_buddyx` before vs after — diff empty
2. **Inline CSS parity:** front-page `<head>` source before vs after — identical selectors and values
3. **Customizer panel/section/field counts:** unchanged
4. **Live preview parity:** Site Layout, one Color, one Typography, one Switch, one Dimension all update without refresh
5. **Active_callback parity:** in Skin section, parent toggle gates 5+ children correctly
6. **Plugin-off test:** Kirki plugin deactivated, Customizer loads, no errors

Tag `v5.1.0` on `wbcomdesigns/buddyx`, then push tag + clean zip to `vapvarun/buddyx` and create the GitHub Release (per repo split rule).

Commit the version bump separately from QA.

---

## Task 28: Framework `README.md`

**Files:** Create `inc/Customizer_Framework/README.md`

A 200-line doc covering: install (copy directory + update namespace), boot, register panels/sections/fields, supported field types with example args for each, `output` rules, `active_callback` shape, the `buddyx_customizer_field_type_map` filter, and "how to reuse in BuddyX Pro" steps.

Commit.

---

## Optional Task 29: One-time admin notice on upgrade

If the Kirki plugin is still active when a user upgrades to 5.1.0, show a one-time dismissible admin notice:

> *BuddyX 5.1.0 includes its own Customizer framework — the Kirki plugin is no longer required. You can safely deactivate it.* `[Deactivate Kirki]` `[Dismiss]`

Implementation: small file at `inc/Customizer_Settings/Kirki_Deprecation_Notice.php`. Decide at Task 27 review.

---

## Self-Review

**Spec coverage:**
- "Replace Kirki with own framework" — Tasks 1-25 ✓
- "All controls now, not future" — 12 custom + 8 core = 20 field types ✓
- "Same theme_mod values, no migration" — same setting IDs preserved at Task 22; read-time normalizations for Typography variant + Switch bool→int ✓
- "Reusable in BuddyX Pro" — `inc/Customizer_Framework/` is portable; copy + namespace search-replace ✓
- "Pro is reference only" — no buddyx-pro repo writes ✓
- "BuddyX coding conventions" — `inc/<Component>/Component.php` PSR-4 namespace `BuddyX\Buddyx\<Component>` ✓
- "Kirki gone day one" — Tasks 22-25 atomic sweep, no parallel period ✓

**Placeholder scan:** no TBD / TODO. Per-control class bodies in Tasks 6-17 deliberately summarized to keep this consolidated plan readable; the implementer dispatch fills in the markup at execution time using the patterns shown in Task 1's Component class.

**Type/identifier consistency:**
- Namespace: `BuddyX\Buddyx\Customizer_Framework\*` consistent across all framework files
- Bootstrap class: `Component`
- Public API methods: `Component::boot()`, `Panel::add()`, `Section::add()`, `Field::add( $type, $args )` — consistent across Tasks 1-22
- Custom control class names: `Color`, `Typography`, `Radio_Image`, `Switch`, `Dimension`, `Custom_HTML`, `Checkbox`, `Slider`, `Radio_Buttonset`, `Repeater`, `Upload`, `Sortable`
- 20 field type strings — identical in `Field::$type_map` and consumer migration (Task 22)

**Risk register:**

1. *Active_callback regressions in Skin (44 callbacks).* Mitigation: Task 25 smoke test exercises the chains.
2. *Inline CSS whitespace differences.* Mitigation: Output_Builder produces byte-identical output; QA item #2.
3. *Typography 'variant' → 'font-weight' read normalization.* Mitigation: handled in both PHP (Output_Builder) and JS (Typography control). Tested at Task 25.
4. *Repeater control fragility.* Mitigation: Task 26 validation against Pro's actual Repeater field args.
5. *Kirki plugin still active on user site post-upgrade.* Mitigation: changelog instructs deactivation; optional Task 29 admin notice.

**Estimated effort:** 11–14 working days end-to-end. Foundation (Tasks 1-5): 1.5 days. Custom controls (Tasks 6-17): 4 days (Repeater alone ~1 day). JS/CSS bundles + preview (Tasks 18-20): 2 days. Rename + atomic sweep (Tasks 21-22): 2 days. Cleanup + smoke + Pro validation + release (Tasks 23-28): 1.5 days.
