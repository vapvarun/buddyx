# BuddyX FREE Theme - Roadmap v5.0.0

**Version:** 5.0.0 (Major Release)
**Theme:** BuddyX FREE (WordPress.org)
**Date:** December 19, 2025

---

## Overview

Version 5.0.0 is a major overhaul focusing on:
- Complete Kirki refactor with proper feature usage
- 40+ block patterns
- Option migration system
- Modern file structure
- Performance improvements

---

## Current State → Target State

| Component | Current (4.9.2) | Target (5.0.0) |
|-----------|-----------------|----------------|
| Kirki Fields | 134 | 140 |
| Kirki Output Usage | 4 fields | ALL fields |
| Sanitization | 0 fields | ALL fields |
| Block Patterns | 19 | 40+ |
| Pattern Categories | 4 | 8 |
| Color Presets | 0 | 5 |
| theme.json Version | 2 | 3 |
| File Structure | Monolithic | Modular |
| CSS Generation | Manual (Dynamic_Style) | Kirki Auto |

---

## Implementation Tasks

### Phase 1: File Structure Refactor

#### Task 1.1: Create New Directory Structure
- [ ] Create `inc/Kirki/Config.php`
- [ ] Create `inc/Kirki/Defaults.php`
- [ ] Create `inc/Kirki_Option/Panels/` directory
- [ ] Create `inc/Kirki_Option/Fields/` directory
- [ ] Create `inc/Kirki_Option/Presets/` directory
- [ ] Create `inc/Migration/` directory

**New Structure:**
```
buddyx/
├── inc/
│   ├── Kirki/
│   │   ├── Component.php (loader)
│   │   ├── Config.php (NEW)
│   │   └── Defaults.php (NEW)
│   ├── Kirki_Option/
│   │   ├── Component.php (refactored - small)
│   │   ├── Panels/
│   │   │   ├── General.php (NEW)
│   │   │   ├── Header.php (NEW)
│   │   │   ├── Colors.php (NEW)
│   │   │   ├── Typography.php (NEW)
│   │   │   ├── Blog.php (NEW)
│   │   │   ├── Footer.php (NEW)
│   │   │   └── Community.php (NEW)
│   │   ├── Fields/
│   │   │   ├── Layout_Fields.php (NEW)
│   │   │   ├── Loader_Fields.php (NEW)
│   │   │   ├── Header_Fields.php (NEW)
│   │   │   ├── Color_Fields.php (NEW)
│   │   │   ├── Typography_Fields.php (NEW)
│   │   │   ├── Blog_Fields.php (NEW)
│   │   │   ├── Footer_Fields.php (NEW)
│   │   │   └── BuddyPress_Fields.php (NEW)
│   │   └── Presets/
│   │       └── Color_Presets.php (NEW)
│   ├── Migration/
│   │   ├── Migrator.php (NEW)
│   │   └── Option_Map.php (NEW)
│   └── Dynamic_Style/
│       └── Component.php (DEPRECATED - keep as fallback)
└── patterns/ (40+ patterns)
```

---

### Phase 2: Kirki Defaults System

#### Task 2.1: Create Centralized Defaults
- [ ] Create `inc/Kirki/Defaults.php`
- [ ] Move all defaults from fields to single file
- [ ] Create `get()` method for single default
- [ ] Create `get_mod()` method with fallback
- [ ] Deprecate `external/kirki-utils.php`

**File: `inc/Kirki/Defaults.php`**
```php
<?php
namespace BuddyX\Kirki;

class Defaults {

    private static $defaults = [
        // Layout
        'layout'            => 'wide',
        'container_width'   => '1170',
        'border_radius'     => '4',

        // Loader
        'loader_enabled'    => true,
        'loader_style'      => '2',
        'loader_bg'         => '#ef5455',

        // Header
        'header_sticky'     => true,
        'header_search'     => true,
        'header_cart'       => true,
        'header_login'      => true,

        // Colors - Brand
        'color_primary'     => '#ef5455',
        'color_secondary'   => '#41848f',
        'color_link'        => '#111111',
        'color_link_hover'  => '#ef5455',

        // Colors - Surfaces
        'body_bg'           => '#f7f7f9',
        'content_bg'        => '#ffffff',
        'box_bg'            => '#ffffff',

        // Colors - Text
        'text_color'        => '#505050',
        'heading_color'     => '#111111',

        // Colors - Buttons
        'btn_bg'            => '#ef5455',
        'btn_bg_hover'      => '#111111',
        'btn_text'          => '#ffffff',
        'btn_text_hover'    => '#ffffff',
        'btn_border'        => '#ef5455',
        'btn_border_hover'  => '#111111',

        // Typography
        'typo_body' => [
            'font-family'    => 'Open Sans',
            'variant'        => 'regular',
            'font-size'      => '15px',
            'line-height'    => '1.7',
            'letter-spacing' => '0',
        ],
        'typo_headings' => [
            'font-family'    => 'Open Sans',
            'variant'        => '600',
        ],
        'typo_site_title' => [
            'font-family'    => 'Open Sans',
            'variant'        => '600',
            'font-size'      => '38px',
            'line-height'    => '1.2',
        ],
        'typo_menu' => [
            'font-family'    => 'Open Sans',
            'variant'        => '600',
            'font-size'      => '14px',
            'text-transform' => 'none',
        ],

        // Blog
        'blog_layout'       => 'default-layout',
        'blog_sidebar'      => 'right',
        'single_width'      => 'small',
        'single_sidebar'    => 'none',

        // Footer
        'footer_bg'             => '#ffffff',
        'footer_title_color'    => '#111111',
        'footer_text_color'     => '#505050',
        'footer_link_color'     => '#111111',
        'footer_link_hover'     => '#ef5455',
        'copyright_bg'          => '#ffffff',
        'copyright_text'        => '',

        // Sidebar
        'sidebar_default'       => 'right',
        'sidebar_buddypress'    => 'right',
        'sidebar_bbpress'       => 'right',
        'sidebar_woocommerce'   => 'left',

        // BuddyPress
        'bp_avatar_style'       => 'suspended_circles',
    ];

    public static function get($key) {
        return self::$defaults[$key] ?? null;
    }

    public static function all() {
        return self::$defaults;
    }

    public static function get_mod($key) {
        return get_theme_mod($key, self::get($key));
    }
}
```

---

### Phase 3: Migration System

#### Task 3.1: Create Migration Helper
- [ ] Create `inc/Migration/Migrator.php`
- [ ] Create `inc/Migration/Option_Map.php`
- [ ] Add backup functionality
- [ ] Add restore functionality
- [ ] Add admin notice after migration
- [ ] Add migration logging

**File: `inc/Migration/Option_Map.php`**
```php
<?php
namespace BuddyX\Migration;

class Option_Map {

    /**
     * Old key => New key mapping
     */
    const MAP = [
        // Layout
        'site_layout'              => 'layout',
        'site_container_width'     => 'container_width',
        'site_global_radius'       => 'border_radius',

        // Loader
        'site_loader'              => 'loader_style',
        'site_loader_bg'           => 'loader_bg',

        // Header
        'site_sticky_header'       => 'header_sticky',
        'site_search'              => 'header_search',
        'site_cart'                => 'header_cart',

        // Colors
        'site_primary_color'       => 'color_primary',
        'site_links_color'         => 'color_link',
        'site_links_focus_color'   => 'color_link_hover',
        'body_background_color'    => 'body_bg',
        'content_background_color' => 'content_bg',
        'box_background_color'     => 'box_bg',
        'body_text_color'          => 'text_color',
        'headings_color'           => 'heading_color',

        // Buttons
        'button_background_color'       => 'btn_bg',
        'button_background_hover_color' => 'btn_bg_hover',
        'button_text_color'             => 'btn_text',
        'button_text_hover_color'       => 'btn_text_hover',
        'button_border_color'           => 'btn_border',
        'button_border_hover_color'     => 'btn_border_hover',

        // Typography
        'site_title_typography'    => 'typo_site_title',
        'headings_typography'      => 'typo_headings',
        'body_typography'          => 'typo_body',
        'menu_typography'          => 'typo_menu',
        'h1_typography'            => 'typo_h1',
        'h2_typography'            => 'typo_h2',
        'h3_typography'            => 'typo_h3',
        'h4_typography'            => 'typo_h4',
        'h5_typography'            => 'typo_h5',
        'h6_typography'            => 'typo_h6',

        // Footer
        'site_footer_bg'                => 'footer_bg',
        'site_footer_title_color'       => 'footer_title_color',
        'site_footer_content_color'     => 'footer_text_color',
        'site_footer_links_color'       => 'footer_link_color',
        'site_footer_links_hover_color' => 'footer_link_hover',
        'site_copyright_bg'             => 'copyright_bg',
        'site_copyright_text'           => 'copyright_text',

        // Blog
        'blog_layout'                   => 'blog_layout',
        'single_post_content_width'     => 'single_width',

        // Sidebar
        'site_sidebar_option'           => 'sidebar_default',
        'buddypress_sidebar_option'     => 'sidebar_buddypress',
        'bbpress_sidebar_option'        => 'sidebar_bbpress',
        'woocommerce_sidebar_option'    => 'sidebar_woocommerce',

        // BuddyPress
        'buddypress_avatar_style'       => 'bp_avatar_style',
    ];

    public static function get_map() {
        return self::MAP;
    }

    public static function get_new_key($old_key) {
        return self::MAP[$old_key] ?? $old_key;
    }
}
```

#### Task 3.2: Create Migrator Class
- [ ] Auto-detect version upgrade
- [ ] Backup current theme_mods
- [ ] Run migration mapping
- [ ] Update version tracker
- [ ] Show admin notice

---

### Phase 4: Kirki Fields Refactor

#### Task 4.1: Add Sanitization to ALL Fields
- [ ] Create `inc/Kirki/Sanitize.php` helper class
- [ ] Add `sanitize_callback` to all Color fields
- [ ] Add `sanitize_callback` to all Text fields
- [ ] Add `sanitize_callback` to all URL fields
- [ ] Add `sanitize_callback` to all Textarea fields
- [ ] Add `sanitize_callback` to all Dimension fields

**File: `inc/Kirki/Sanitize.php`**
```php
<?php
namespace BuddyX\Kirki;

class Sanitize {

    public static function color($value) {
        if (empty($value)) {
            return '';
        }
        if (preg_match('/^rgba?\(/', $value)) {
            return $value;
        }
        return sanitize_hex_color($value);
    }

    public static function dimension($value) {
        if (empty($value)) {
            return '';
        }
        if (is_numeric($value)) {
            return absint($value) . 'px';
        }
        return sanitize_text_field($value);
    }

    public static function text($value) {
        return sanitize_text_field($value);
    }

    public static function html($value) {
        return wp_kses_post($value);
    }

    public static function url($value) {
        return esc_url_raw($value);
    }

    public static function boolean($value) {
        return (bool) $value;
    }

    public static function select($value, $choices) {
        return array_key_exists($value, $choices) ? $value : '';
    }
}
```

#### Task 4.2: Add Kirki `output` to ALL Color Fields
- [ ] Add `output` array to `color_primary`
- [ ] Add `output` array to `color_secondary`
- [ ] Add `output` array to `color_link`
- [ ] Add `output` array to `color_link_hover`
- [ ] Add `output` array to `body_bg`
- [ ] Add `output` array to `content_bg`
- [ ] Add `output` array to `text_color`
- [ ] Add `output` array to `heading_color`
- [ ] Add `output` array to ALL button colors
- [ ] Add `output` array to ALL footer colors

**Example Field with Output:**
```php
new \Kirki\Field\Color([
    'settings'          => 'color_primary',
    'label'             => esc_html__('Primary Color', 'buddyx'),
    'section'           => 'colors_brand',
    'default'           => Defaults::get('color_primary'),
    'sanitize_callback' => [Sanitize::class, 'color'],
    'choices'           => ['alpha' => true],
    'output'            => [
        ['element' => ':root', 'property' => '--color-primary'],
        ['element' => 'a:hover, .btn-link:hover', 'property' => 'color'],
        ['element' => '.btn-primary, .bg-primary', 'property' => 'background-color'],
    ],
    'transport'         => 'auto',
]);
```

#### Task 4.3: Add Live Preview (`js_vars`)
- [ ] Add `js_vars` to color fields for instant preview
- [ ] Add `js_vars` to typography fields
- [ ] Add `partial_refresh` to text content fields

---

### Phase 5: Color Presets

#### Task 5.1: Create Color Preset System
- [ ] Create `inc/Kirki_Option/Presets/Color_Presets.php`
- [ ] Define 5 color presets
- [ ] Add preset selector field
- [ ] Add preset application logic

**File: `inc/Kirki_Option/Presets/Color_Presets.php`**
```php
<?php
namespace BuddyX\Kirki_Option\Presets;

class Color_Presets {

    const PRESETS = [
        'default' => [
            'label'  => 'BuddyX Default',
            'colors' => [
                'color_primary'   => '#ef5455',
                'color_secondary' => '#41848f',
                'body_bg'         => '#f7f7f9',
                'content_bg'      => '#ffffff',
                'text_color'      => '#505050',
                'heading_color'   => '#111111',
                'color_link'      => '#111111',
                'color_link_hover'=> '#ef5455',
            ],
        ],
        'ocean' => [
            'label'  => 'Ocean Blue',
            'colors' => [
                'color_primary'   => '#0077B6',
                'color_secondary' => '#00B4D8',
                'body_bg'         => '#F0F9FF',
                'content_bg'      => '#ffffff',
                'text_color'      => '#1E3A5F',
                'heading_color'   => '#023E8A',
                'color_link'      => '#023E8A',
                'color_link_hover'=> '#0077B6',
            ],
        ],
        'forest' => [
            'label'  => 'Forest Green',
            'colors' => [
                'color_primary'   => '#2D6A4F',
                'color_secondary' => '#40916C',
                'body_bg'         => '#F0FFF4',
                'content_bg'      => '#ffffff',
                'text_color'      => '#1B4332',
                'heading_color'   => '#081C15',
                'color_link'      => '#1B4332',
                'color_link_hover'=> '#2D6A4F',
            ],
        ],
        'sunset' => [
            'label'  => 'Warm Sunset',
            'colors' => [
                'color_primary'   => '#E76F51',
                'color_secondary' => '#F4A261',
                'body_bg'         => '#FFFAF5',
                'content_bg'      => '#ffffff',
                'text_color'      => '#5C4033',
                'heading_color'   => '#264653',
                'color_link'      => '#264653',
                'color_link_hover'=> '#E76F51',
            ],
        ],
        'starter' => [
            'label'  => 'Starter (Minimal)',
            'colors' => [
                'color_primary'   => '#111111',
                'color_secondary' => '#505050',
                'body_bg'         => '#ffffff',
                'content_bg'      => '#ffffff',
                'text_color'      => '#333333',
                'heading_color'   => '#111111',
                'color_link'      => '#111111',
                'color_link_hover'=> '#505050',
            ],
        ],
    ];

    public static function get_all() {
        return self::PRESETS;
    }

    public static function get($preset) {
        return self::PRESETS[$preset] ?? null;
    }

    public static function get_choices() {
        $choices = ['custom' => esc_html__('Custom', 'buddyx')];
        foreach (self::PRESETS as $key => $preset) {
            $choices[$key] = $preset['label'];
        }
        return $choices;
    }

    public static function apply($preset) {
        $data = self::get($preset);
        if (!$data) return false;

        foreach ($data['colors'] as $key => $value) {
            set_theme_mod($key, $value);
        }
        return true;
    }
}
```

---

### Phase 6: theme.json Upgrade

#### Task 6.1: Update to Version 3
- [ ] Update `$schema` to WordPress 6.7
- [ ] Update `version` to 3
- [ ] Add fluid spacing scale
- [ ] Add expanded color palette
- [ ] Add typography presets

**File: `theme.json`**
```json
{
  "$schema": "https://schemas.wp.org/wp/6.7/theme.json",
  "version": 3,
  "settings": {
    "appearanceTools": true,
    "color": {
      "palette": [
        { "color": "#ffffff", "name": "Base", "slug": "base" },
        { "color": "#111111", "name": "Contrast", "slug": "contrast" },
        { "color": "#ef5455", "name": "Primary", "slug": "primary" },
        { "color": "#41848f", "name": "Secondary", "slug": "secondary" },
        { "color": "#f7f7f9", "name": "Tertiary", "slug": "tertiary" },
        { "color": "#505050", "name": "Dark Gray", "slug": "dark-gray" },
        { "color": "#27AE60", "name": "Success", "slug": "success" },
        { "color": "#E74C3C", "name": "Error", "slug": "error" }
      ]
    },
    "spacing": {
      "spacingSizes": [
        { "name": "Tiny", "slug": "10", "size": "clamp(8px, 2vw, 10px)" },
        { "name": "Small", "slug": "20", "size": "clamp(12px, 3vw, 20px)" },
        { "name": "Medium", "slug": "30", "size": "clamp(20px, 4vw, 30px)" },
        { "name": "Regular", "slug": "40", "size": "clamp(30px, 5vw, 50px)" },
        { "name": "Large", "slug": "50", "size": "clamp(40px, 6vw, 70px)" },
        { "name": "X-Large", "slug": "60", "size": "clamp(50px, 8vw, 100px)" },
        { "name": "XX-Large", "slug": "70", "size": "clamp(70px, 10vw, 140px)" }
      ]
    },
    "typography": {
      "fluid": true,
      "fontSizes": [
        { "name": "Small", "slug": "small", "size": "clamp(0.875rem, 2vw, 1rem)" },
        { "name": "Medium", "slug": "medium", "size": "clamp(1rem, 2.5vw, 1.125rem)" },
        { "name": "Large", "slug": "large", "size": "clamp(1.25rem, 3vw, 1.5rem)" },
        { "name": "X-Large", "slug": "x-large", "size": "clamp(1.5rem, 4vw, 2rem)" },
        { "name": "XX-Large", "slug": "xx-large", "size": "clamp(2rem, 5vw, 3rem)" }
      ]
    }
  }
}
```

---

### Phase 7: Block Patterns

#### Task 7.1: Register New Pattern Categories
- [ ] Register `buddyx-header` category
- [ ] Register `buddyx-cta` category
- [ ] Register `buddyx-team` category
- [ ] Register `buddyx-testimonials` category
- [ ] Register `buddyx-services` category

#### Task 7.2: Create Header Patterns (3)
- [ ] Create `patterns/header-default.php`
- [ ] Create `patterns/header-centered.php`
- [ ] Create `patterns/header-with-cta.php`

#### Task 7.3: Create CTA Patterns (5)
- [ ] Create `patterns/cta-join-community.php`
- [ ] Create `patterns/cta-newsletter.php`
- [ ] Create `patterns/cta-membership.php`
- [ ] Create `patterns/cta-download-app.php`
- [ ] Create `patterns/cta-contact.php`

#### Task 7.4: Create Team Patterns (4)
- [ ] Create `patterns/team-grid-3col.php`
- [ ] Create `patterns/team-grid-4col.php`
- [ ] Create `patterns/team-featured.php`
- [ ] Create `patterns/team-with-social.php`

#### Task 7.5: Create Testimonial Patterns (3)
- [ ] Create `patterns/testimonials-centered.php`
- [ ] Create `patterns/testimonials-grid.php`
- [ ] Create `patterns/testimonials-large.php`

#### Task 7.6: Create Services Patterns (3)
- [ ] Create `patterns/services-3col.php`
- [ ] Create `patterns/services-icons.php`
- [ ] Create `patterns/services-alternating.php`

#### Task 7.7: Create Hidden/Utility Patterns (3)
- [ ] Create `patterns/hidden-404.php`
- [ ] Create `patterns/hidden-search.php`
- [ ] Create `patterns/hidden-no-results.php`

---

### Phase 8: Block Styles

#### Task 8.1: Register Block Styles
- [ ] Create `inc/Block_Styles/Component.php`
- [ ] Register button styles (outline, pill)
- [ ] Register image styles (rounded, shadow)
- [ ] Register group styles (card, dark-section)
- [ ] Register quote styles (plain, large)

#### Task 8.2: Create Block Styles CSS
- [ ] Create `assets/css/blocks/block-styles.css`
- [ ] Add all block style CSS rules
- [ ] Enqueue in editor and frontend

---

### Phase 9: Cleanup & Deprecation

#### Task 9.1: Deprecate Dynamic_Style
- [ ] Add fallback check in Dynamic_Style
- [ ] Log deprecation warning
- [ ] Keep as fallback for non-Kirki users

#### Task 9.2: Deprecate old functions
- [ ] Deprecate `buddyx_defaults()` in kirki-utils.php
- [ ] Add `_deprecated_function()` notices
- [ ] Document migration path

#### Task 9.3: Remove unused code
- [ ] Remove duplicate CSS generation
- [ ] Remove redundant get_theme_mod calls
- [ ] Clean up legacy conditionals

---

### Phase 10: Documentation & Testing

#### Task 10.1: Update Documentation
- [ ] Update README.md
- [ ] Create CHANGELOG.md entry
- [ ] Update inline code documentation

#### Task 10.2: Testing
- [ ] Test fresh install
- [ ] Test migration from 4.9.x
- [ ] Test all customizer options
- [ ] Test block patterns in editor
- [ ] Test with BuddyPress active
- [ ] Test with WooCommerce active
- [ ] Test with Kirki inactive (fallback)

---

## Files to Create

| File | Purpose |
|------|---------|
| `inc/Kirki/Config.php` | Kirki configuration |
| `inc/Kirki/Defaults.php` | Centralized defaults |
| `inc/Kirki/Sanitize.php` | Sanitization helpers |
| `inc/Kirki_Option/Panels/*.php` | Modular panels |
| `inc/Kirki_Option/Fields/*.php` | Modular fields |
| `inc/Kirki_Option/Presets/Color_Presets.php` | Color schemes |
| `inc/Migration/Migrator.php` | Migration handler |
| `inc/Migration/Option_Map.php` | Key mapping |
| `inc/Block_Styles/Component.php` | Block styles |
| `patterns/*.php` | 21 new patterns |
| `assets/css/blocks/block-styles.css` | Block style CSS |

## Files to Modify

| File | Changes |
|------|---------|
| `theme.json` | Version 3, spacing, colors |
| `style.css` | Version bump to 5.0.0 |
| `functions.php` | Load new components |
| `inc/Kirki_Option/Component.php` | Refactor to loader |
| `inc/Base_Support/Component.php` | Pattern categories, block styles |

## Files to Deprecate

| File | Action |
|------|--------|
| `external/kirki-utils.php` | Keep with deprecation notice |
| `inc/Dynamic_Style/Component.php` | Keep as fallback |

---

## Summary Checklist

### Kirki Overhaul
- [ ] Centralized defaults system
- [ ] Sanitization on ALL fields
- [ ] Kirki `output` on ALL color fields
- [ ] Live preview with `js_vars`
- [ ] 5 color presets
- [ ] Modular file structure

### Block Patterns
- [ ] 8 pattern categories
- [ ] 21 new patterns (40+ total)
- [ ] Hidden/utility patterns

### Block Styles
- [ ] 8 registered block styles
- [ ] Block styles CSS file

### Migration
- [ ] Automatic backup
- [ ] Key mapping migration
- [ ] Admin notice
- [ ] Restore functionality

### theme.json
- [ ] Version 3
- [ ] Fluid spacing
- [ ] Expanded colors
- [ ] Typography presets

---

## Timeline

| Week | Tasks |
|------|-------|
| 1 | File structure, Defaults, Sanitize |
| 2 | Migration system, Option mapping |
| 3 | Kirki fields refactor (output, js_vars) |
| 4 | Color presets, theme.json |
| 5 | Block patterns (21 new) |
| 6 | Block styles, cleanup |
| 7 | Testing, documentation |
| 8 | Release preparation |

---

*This roadmap ensures BuddyX FREE 5.0.0 is a modern, well-structured theme with proper Kirki usage.*
