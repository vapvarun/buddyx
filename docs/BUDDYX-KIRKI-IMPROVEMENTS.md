# BuddyX Kirki Improvements & Backward Compatibility Strategy

**Date:** December 19, 2025
**Priority:** High (Security & Performance)

---

## Executive Summary

The current Kirki implementation has several critical issues:

| Issue | Severity | Impact |
|-------|----------|--------|
| Missing `sanitize_callback` | **CRITICAL** | Security vulnerability |
| Manual CSS generation | High | Performance, maintainability |
| Duplicate `get_theme_mod()` calls | Medium | Performance |
| No `partial_refresh` | Medium | User experience |
| Inconsistent defaults | Low | Developer experience |
| No preset system | Low | Feature gap |

---

## What Kirki Features Are NOT Being Used

### 1. Built-in CSS Output (NOT USED)

**Current (Wrong):**
```php
// In Dynamic_Style/Component.php - MANUAL CSS building
$site_loader_bg = get_theme_mod( 'site_loader_bg' );
// ... later ...
$color = get_theme_mod( 'site_loader_bg' ); // DUPLICATE CALL!
$color_var .= '--color-theme-loader: ' . $color . ' !important;';
```

**Should Be:**
```php
// In Kirki field definition - Kirki handles CSS automatically
new \Kirki\Field\Color([
    'settings' => 'site_loader_bg',
    'section'  => 'site_skin_section',
    'default'  => '#ef5455',
    'output'   => [
        [
            'element'  => ':root',
            'property' => '--color-theme-loader',
        ],
    ],
]);
```

### 2. Sanitize Callbacks (NOT USED)

**Current:** No sanitization on ANY of the 134 fields.

**Should Be:**
```php
new \Kirki\Field\Color([
    'settings'          => 'site_loader_bg',
    'sanitize_callback' => 'sanitize_hex_color',
]);

new \Kirki\Field\Text([
    'settings'          => 'site_copyright_text',
    'sanitize_callback' => 'wp_kses_post',
]);

new \Kirki\Field\URL([
    'settings'          => 'site_logo_url',
    'sanitize_callback' => 'esc_url_raw',
]);
```

### 3. Partial Refresh (NOT USED)

**Current:** Only uses `transport => 'auto'` or `'refresh'`

**Should Be:**
```php
new \Kirki\Field\Text([
    'settings'        => 'site_copyright_text',
    'transport'       => 'postMessage',
    'partial_refresh' => [
        'copyright_text' => [
            'selector'        => '.site-copyright',
            'render_callback' => function() {
                return get_theme_mod('site_copyright_text', '');
            },
        ],
    ],
]);
```

### 4. Preset/Palette System (NOT USED)

**Should Exist:**
```php
new \Kirki\Field\Palette([
    'settings' => 'color_scheme',
    'label'    => esc_html__('Color Scheme', 'buddyx'),
    'section'  => 'site_skin_section',
    'default'  => 'default',
    'choices'  => [
        'default' => ['#ef5455', '#41848f', '#111111', '#f7f7f9'],
        'ocean'   => ['#0077B6', '#00B4D8', '#023E8A', '#CAF0F8'],
        'forest'  => ['#2D6A4F', '#40916C', '#1B4332', '#D8F3DC'],
    ],
]);
```

### 5. Repeater Fields (UNDERUSED)

**Only 2 repeaters in PRO, 0 in FREE**

Could use for:
- Social links
- Team members in footer
- Custom buttons

### 6. Conditional Logic with Multiple Conditions (UNDERUSED)

**Current:** Simple single-condition callbacks

**Could Be:**
```php
'active_callback' => [
    [
        'setting'  => 'site_custom_colors',
        'operator' => '==',
        'value'    => true,
    ],
    [
        'setting'  => 'dark_mode_enabled',
        'operator' => '!=',
        'value'    => true,
    ],
],
```

---

## Backward Compatibility Strategy

### Golden Rules

1. **NEVER change setting keys** - Existing values stored by key
2. **NEVER remove settings** - Deprecate, don't delete
3. **ALWAYS provide defaults** - Same as current defaults
4. **TEST with existing data** - Before any release

### Safe Migration Pattern

```php
/**
 * Safe way to improve Kirki fields without breaking existing sites
 */
class Kirki_Migration {

    /**
     * Version tracking for migrations
     */
    const KIRKI_VERSION = '2.0.0';

    /**
     * Run migrations on theme update
     */
    public static function maybe_migrate() {
        $current_version = get_option('buddyx_kirki_version', '1.0.0');

        if (version_compare($current_version, self::KIRKI_VERSION, '<')) {
            self::run_migrations($current_version);
            update_option('buddyx_kirki_version', self::KIRKI_VERSION);
        }
    }

    /**
     * Run version-specific migrations
     */
    private static function run_migrations($from_version) {
        // Migration from 1.x to 2.x
        if (version_compare($from_version, '2.0.0', '<')) {
            self::migrate_to_v2();
        }
    }

    /**
     * Example migration: Consolidate color settings
     */
    private static function migrate_to_v2() {
        // Get old value
        $old_value = get_theme_mod('old_setting_name');

        // If old value exists and new doesn't, migrate
        if ($old_value && !get_theme_mod('new_setting_name')) {
            set_theme_mod('new_setting_name', $old_value);
        }

        // DON'T remove old setting - keep for fallback
        // remove_theme_mod('old_setting_name'); // NEVER DO THIS
    }
}

// Hook into theme setup
add_action('after_setup_theme', ['Kirki_Migration', 'maybe_migrate']);
```

---

## Implementation Plan (Safe Approach)

### Phase 1: Add Sanitization (No Breaking Changes)

**Risk Level:** Zero - Only adds security, doesn't change behavior

```php
// Create sanitization helper class
class BuddyX_Sanitize {

    public static function color($value) {
        // Handle rgba colors
        if (preg_match('/^rgba?\(/', $value)) {
            return $value;
        }
        return sanitize_hex_color($value);
    }

    public static function dimension($value) {
        if (is_numeric($value)) {
            return absint($value) . 'px';
        }
        return sanitize_text_field($value);
    }

    public static function html($value) {
        return wp_kses_post($value);
    }

    public static function url($value) {
        return esc_url_raw($value);
    }
}
```

**Add to existing fields:**
```php
// BEFORE (current - unsafe)
new \Kirki\Field\Color([
    'settings' => 'site_loader_bg',
    'default'  => '#ef5455',
]);

// AFTER (safe - same behavior, added security)
new \Kirki\Field\Color([
    'settings'          => 'site_loader_bg',
    'default'           => '#ef5455',
    'sanitize_callback' => ['BuddyX_Sanitize', 'color'],
]);
```

### Phase 2: Add Kirki Output (Gradual Migration)

**Risk Level:** Low - CSS output is additive

**Strategy:** Add `output` to fields BUT keep Dynamic_Style as fallback

```php
// Step 1: Add output to field definition
new \Kirki\Field\Color([
    'settings' => 'site_loader_bg',
    'default'  => '#ef5455',
    'output'   => [
        [
            'element'  => ':root',
            'property' => '--color-theme-loader',
        ],
    ],
]);

// Step 2: In Dynamic_Style, check if Kirki already output
public function buddyx_color_options() {
    // Skip if Kirki is handling output
    if (class_exists('Kirki') && $this->kirki_handles_output()) {
        return;
    }

    // Fallback for older Kirki versions or if Kirki disabled
    $this->manual_css_output();
}

private function kirki_handles_output() {
    // Check Kirki version supports output
    return defined('KIRKI_VERSION') && version_compare(KIRKI_VERSION, '4.0', '>=');
}
```

### Phase 3: Add Partial Refresh (Enhancement Only)

**Risk Level:** Zero - Only improves live preview

```php
new \Kirki\Field\Textarea([
    'settings'        => 'site_copyright_text',
    'default'         => get_bloginfo('name'),
    'transport'       => 'postMessage',
    'partial_refresh' => [
        'site_copyright_partial' => [
            'selector'            => '.site-copyright-text',
            'container_inclusive' => false,
            'render_callback'     => function() {
                return esc_html(get_theme_mod('site_copyright_text', get_bloginfo('name')));
            },
        ],
    ],
]);
```

### Phase 4: Add Presets (New Feature)

**Risk Level:** Zero - Completely new feature

```php
// Add preset selector (doesn't affect existing settings)
new \Kirki\Field\Select([
    'settings' => 'buddyx_color_preset',
    'label'    => esc_html__('Quick Color Scheme', 'buddyx'),
    'section'  => 'site_skin_section',
    'default'  => 'custom', // Default to custom = no change
    'priority' => 1,
    'choices'  => [
        'custom'  => esc_html__('Custom (Current Settings)', 'buddyx'),
        'default' => esc_html__('BuddyX Default', 'buddyx'),
        'ocean'   => esc_html__('Ocean Blue', 'buddyx'),
        'forest'  => esc_html__('Forest Green', 'buddyx'),
    ],
]);

// Apply preset only when explicitly selected
add_action('customize_save_after', function($wp_customize) {
    $preset = get_theme_mod('buddyx_color_preset', 'custom');

    if ($preset !== 'custom') {
        $presets = BuddyX_Presets::get_colors($preset);
        foreach ($presets as $setting => $value) {
            set_theme_mod($setting, $value);
        }
        // Reset to custom after applying
        set_theme_mod('buddyx_color_preset', 'custom');
    }
});
```

---

## Centralized Defaults System

### Current Problem

Defaults defined in TWO places:
1. Field definition: `'default' => '#ef5455'`
2. Separate function: `buddyx_defaults('site_loader_bg')`

### Solution: Single Source of Truth

```php
/**
 * Centralized defaults - SINGLE SOURCE OF TRUTH
 * File: inc/Kirki_Option/Defaults.php
 */
class BuddyX_Defaults {

    /**
     * All theme defaults in one place
     * IMPORTANT: Never change keys, only values
     */
    private static $defaults = [
        // Layout
        'site_layout'           => 'wide',
        'site_container_width'  => '1170',

        // Loader
        'site_loader'           => '2',
        'site_loader_bg'        => '#ef5455',

        // Colors
        'site_primary_color'    => '#ef5455',
        'site_links_color'      => '#111111',
        'site_links_focus_color' => '#ef5455',
        'body_background_color' => '#f7f7f9',

        // Typography
        'site_title_typography' => [
            'font-family'    => 'Open Sans',
            'variant'        => '600',
            'font-size'      => '38px',
            'line-height'    => '1.2',
            'letter-spacing' => '0',
            'text-transform' => 'none',
        ],

        // ... all other defaults
    ];

    /**
     * Get a single default value
     */
    public static function get($key) {
        return self::$defaults[$key] ?? null;
    }

    /**
     * Get all defaults
     */
    public static function all() {
        return self::$defaults;
    }

    /**
     * Get theme_mod with default fallback
     */
    public static function get_mod($key) {
        return get_theme_mod($key, self::get($key));
    }
}

// Usage in field definition
new \Kirki\Field\Color([
    'settings' => 'site_loader_bg',
    'default'  => BuddyX_Defaults::get('site_loader_bg'),
]);

// Usage in template
$color = BuddyX_Defaults::get_mod('site_loader_bg');
```

---

## CSS Output Optimization

### Current: 40+ Duplicate Calls

```php
// Dynamic_Style/Component.php - INEFFICIENT
$site_loader_bg = get_theme_mod('site_loader_bg');
$site_primary_color = get_theme_mod('site_primary_color');
// ... 38 more calls ...

// Then AGAIN:
$color = get_theme_mod('site_loader_bg'); // DUPLICATE!
```

### Solution: Cached Color Manager

```php
/**
 * Cached color retrieval - one query, multiple uses
 */
class BuddyX_Colors {

    private static $colors = null;

    /**
     * Get all colors at once (cached)
     */
    public static function get_all() {
        if (self::$colors === null) {
            self::$colors = [
                'loader_bg'      => BuddyX_Defaults::get_mod('site_loader_bg'),
                'primary'        => BuddyX_Defaults::get_mod('site_primary_color'),
                'links'          => BuddyX_Defaults::get_mod('site_links_color'),
                'links_hover'    => BuddyX_Defaults::get_mod('site_links_focus_color'),
                'body_bg'        => BuddyX_Defaults::get_mod('body_background_color'),
                // ... all colors
            ];
        }
        return self::$colors;
    }

    /**
     * Get single color (from cache)
     */
    public static function get($key) {
        $colors = self::get_all();
        return $colors[$key] ?? '';
    }

    /**
     * Generate CSS variables (single call)
     */
    public static function get_css_variables() {
        $colors = self::get_all();
        $css = ':root {';

        foreach ($colors as $key => $value) {
            if (!empty($value)) {
                $css .= "--color-theme-{$key}: {$value};";
            }
        }

        $css .= '}';
        return $css;
    }

    /**
     * Clear cache (call on customizer save)
     */
    public static function clear_cache() {
        self::$colors = null;
    }
}

// Clear cache when customizer saves
add_action('customize_save_after', ['BuddyX_Colors', 'clear_cache']);
```

---

## Testing Checklist

Before releasing ANY Kirki changes:

### 1. Fresh Install Test
- [ ] Install theme on fresh WordPress
- [ ] Verify all defaults apply correctly
- [ ] Check Customizer shows all options
- [ ] Verify CSS output matches expected

### 2. Existing Site Test
- [ ] Update theme on site with existing customizations
- [ ] Verify ALL existing settings preserved
- [ ] Check no visual changes occur
- [ ] Test each Customizer section loads

### 3. Plugin Compatibility
- [ ] Test with Kirki plugin active
- [ ] Test with Kirki plugin inactive (graceful fallback)
- [ ] Test with different Kirki versions (4.x, 5.x)

### 4. Performance Test
- [ ] Compare page load time before/after
- [ ] Check number of database queries
- [ ] Verify CSS file size

### 5. Security Test
- [ ] Test with malicious input in text fields
- [ ] Verify color fields reject invalid values
- [ ] Check URL fields sanitize properly

---

## Migration Timeline

| Phase | Changes | Risk | Timeline |
|-------|---------|------|----------|
| 1 | Add sanitization | Zero | v4.9.3 |
| 2 | Add Kirki output (with fallback) | Low | v4.9.3 |
| 3 | Add partial_refresh | Zero | v4.9.4 |
| 4 | Centralize defaults | Low | v4.9.4 |
| 5 | Add preset system | Zero | v5.0.0 |
| 6 | Remove legacy Dynamic_Style | Medium | v5.0.0+ |

---

## Files to Modify

| File | Changes | Phase |
|------|---------|-------|
| `inc/Kirki_Option/Component.php` | Add sanitize_callback, output | 1-2 |
| `inc/Kirki_Option/Defaults.php` | NEW - Centralized defaults | 4 |
| `inc/Kirki_Option/Sanitize.php` | NEW - Sanitization helpers | 1 |
| `inc/Dynamic_Style/Component.php` | Add fallback check, optimize | 2 |
| `external/kirki-utils.php` | Deprecate, use Defaults.php | 4 |

---

## Summary

**Immediate Actions (v4.9.3):**
1. Add `sanitize_callback` to ALL fields (security fix)
2. Add `output` array to color fields (keep Dynamic_Style as fallback)
3. Optimize Dynamic_Style to avoid duplicate calls

**Future Improvements (v4.9.4+):**
4. Centralize defaults in single class
5. Add partial_refresh for live preview
6. Add color preset system
7. Eventually remove Dynamic_Style when confident

**Never Do:**
- Change setting keys (`site_loader_bg` must stay `site_loader_bg`)
- Remove settings without deprecation period
- Assume all users have latest Kirki version

---

Sources:
- [Kirki WordPress.org Plugin](https://wordpress.org/plugins/kirki/)
- [Kirki GitHub Repository](https://github.com/kirki-framework/kirki)
- [Building WordPress themes using Kirki](https://aristath.github.io/blog/build-wordpress-theme-using-kirki)
