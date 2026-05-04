# BuddyX Customizer Framework

A theme-native, Kirki-compatible Customizer module. Built for BuddyX 5.1.0+ and shipped as a portable namespace so BuddyX Pro can consume the same API verbatim.

- 20 field types (12 custom controls + 8 core-dispatched)
- Drop-in compatible with Kirki-shape args (settings/section/output/transport/active_callback/...)
- Auto inline-CSS via `Output_Builder` (collects each field's `output` map and renders on `wp_head`)
- Filter-based extensibility for adding/overriding control types

---

## Public API

### Panel / Section

```php
\BuddyX\Buddyx\Customizer_Framework\Panel::add( 'my_panel', array(
    'title'    => __( 'My Panel', 'mytheme' ),
    'priority' => 30,
) );

\BuddyX\Buddyx\Customizer_Framework\Section::add( 'my_section', array(
    'title' => __( 'My Section', 'mytheme' ),
    'panel' => 'my_panel',
) );
```

### Field

```php
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color', array(
    'settings'  => 'site_primary_color',
    'label'     => __( 'Primary Color', 'mytheme' ),
    'section'   => 'my_section',
    'default'   => '#0066cc',
    'transport' => 'auto',
    'output'    => array(
        array(
            'element'  => '.site-primary',
            'property' => 'color',
        ),
    ),
) );
```

### Active callbacks

Pass `active_callback` as an array of conditions (compiled into a closure):

```php
'active_callback' => array(
    array(
        'setting'  => 'site_layout',
        'operator' => '==',
        'value'    => 'boxed',
    ),
),
```

Supported operators: `==`, `!=`, `===`, `!==`, `>`, `<`, `>=`, `<=`, `in`, `contains`.

---

## Field types

### 12 custom controls
| Type | Purpose |
|------|---------|
| `color` | Hex/RGBA color picker with palette and alpha |
| `typography` | 6-input fieldset (family, weight, size, line-height, letter-spacing, transform) |
| `radio_image` | Grid of clickable image previews |
| `switch` | Visual on/off toggle (PHP class `Toggle` because `Switch` is reserved) |
| `dimension` | Number + unit dropdown → `'120px'` / `'1.5rem'` |
| `custom` | Raw HTML output from `setting->default`, sanitized via `wp_kses_post` |
| `checkbox` | Standard checkbox with inline title |
| `slider` | Range + number + unit suffix |
| `radio_buttonset` | Radios as a button group |
| `repeater` | JSON array, drag-reorder, add/remove rows |
| `upload` | File upload (extends `WP_Customize_Upload_Control`) |
| `sortable` | Drag-reorder + checkbox toggle |

### 8 core-dispatched types
`text`, `textarea`, `url`, `select`, `radio`, `dropdown-pages`, `image`, `background` — pass through to WP core controls.

---

## Filters

### `buddyx_customizer_field_type_map`
Add/override control types. Useful for Pro-only types.

```php
add_filter( 'buddyx_customizer_field_type_map', function ( $map ) {
    $map['my_custom_type'] = array(
        '\\WP_Customize_Setting',                 // setting class
        '\\BuddyXPro\\Buddyx_Pro\\Customizer_Framework\\Controls\\My_Custom', // control class
        true,                                     // is_custom_control
    );
    return $map;
} );
```

### `buddyx_customizer_field_args`
Mutate field args before sanitize/transport resolution. Used internally by `inc/Customizer/Component.php` to force `postMessage` on settings rendered via dynamic CSS variables.

```php
add_filter( 'buddyx_customizer_field_args', function ( $args, $wp_customize ) {
    if ( in_array( $args['settings'] ?? '', array( 'my_a', 'my_b' ), true ) ) {
        $args['transport'] = 'postMessage';
    }
    return $args;
}, 20, 2 );
```

---

## Reusing in BuddyX Pro (or any other theme)

The framework is intentionally namespace-isolated. To reuse:

1. Copy `inc/Customizer_Framework/` into your theme.
2. Search-replace `BuddyX\Buddyx\Customizer_Framework` → `YourTheme\YourSlug\Customizer_Framework` across all files in the copied directory.
3. Update the file requires in your theme's `functions.php` to point to the new path.
4. Boot the framework on `customize_register` priority 99 (matches our `Component::boot()`).

The 20 field types and the `output`/`active_callback` semantics are stable; consumers in BuddyX free and any theme that copies this directory will register identical settings.

---

## Internal structure

| File | Responsibility |
|------|----------------|
| `Component.php` | Bootstrap, panel/section/field registration queue, `customize_register` callback at priority 99, asset enqueue (controls JS/CSS, preview JS) |
| `Panel.php`, `Section.php` | Thin add() wrappers around Component::register_panel/register_section |
| `Field.php` | Type→control dispatcher; resolves transport, sanitize, builds control args; applies `buddyx_customizer_field_type_map` and `buddyx_customizer_field_args` filters |
| `Output_Builder.php` | Iterates fields with non-empty `output`, emits inline CSS in `wp_head` |
| `Active_Callback.php` | Compiles `active_callback` array conditions into a closure |
| `Controls/` | 12 custom control classes (one per non-core type) |
| `assets/customizer-controls.js` | JS for Typography, Switch, Dimension, Slider, Repeater, Sortable interactions |
| `assets/customizer-controls.css` | Styles for all 12 custom controls |
| `assets/customizer-preview.js` | Live-preview updater that reads `window.buddyxCustomizerOutputs` and patches inline CSS |

---

## Kirki value-format compatibility

Two read-time normalizations let theme_mods saved by Kirki carry over without migration:

1. **Typography `variant` → `font-weight`**: legacy `variant: 'regular'` / `'700'` / `'700italic'` translates to `font-weight` (+ `font-style: italic` when present). Implemented in both `Output_Builder::typography_declarations()` (PHP) and `customizer-preview.js::typographyCss()` (JS) so live-preview matches refresh output.
2. **Switch boolean → int**: Kirki `switch` saved values may be `true`/`false`/`'on'`/`'off'`/`1`/`0`. Sanitized to `0`/`1` via `Field::sanitize_bool_int()`.
