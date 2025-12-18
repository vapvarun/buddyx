# BuddyX 5.0.0 - Major Overhaul & Migration Plan

**Version:** 5.0.0 (Major Release)
**Date:** December 19, 2025
**Scope:** Complete Kirki overhaul, option migration, modernization

---

## Executive Summary

Version 5.0.0 is a major release that:
1. Properly implements all Kirki features
2. Migrates existing user settings automatically
3. Removes legacy code (Dynamic_Style)
4. Adds new features (presets, responsive, tabs)
5. Improves security (sanitization)
6. Improves performance (Kirki CSS output)

---

## Migration Philosophy

### Golden Rules
1. **No user loses their customizations** - Auto-migrate all settings
2. **No visual changes after update** - Sites look identical
3. **One-time migration on update** - Seamless process
4. **Fallback for edge cases** - Manual reset option

### Version Strategy
```
4.9.2 (current) → 4.9.3 (preparation) → 5.0.0 (overhaul)
```

| Version | Changes |
|---------|---------|
| 4.9.3 | Add sanitization, deprecation notices, backup helper |
| 5.0.0 | Full Kirki overhaul, auto-migration |

---

## Option Key Changes

### Naming Convention Update

**Current (inconsistent):**
```
site_loader_bg          → uses underscore
site-layout             → uses hyphen (in defaults)
body_background_color   → verbose
```

**New (consistent, shorter):**
```
loader_bg               → cleaner
site_layout             → underscore only
body_bg                 → shorter
```

### Complete Key Mapping

```php
/**
 * Option key migration map
 * OLD_KEY => NEW_KEY
 */
const OPTION_MIGRATION_MAP = [
    // Layout
    'site_layout'              => 'layout',
    'site_container_width'     => 'container_width',

    // Loader
    'site_loader'              => 'loader_style',
    'site_loader_bg'           => 'loader_bg',

    // Header
    'site_sticky_header'       => 'header_sticky',
    'site_search'              => 'header_search',
    'site_cart'                => 'header_cart',

    // Colors - Primary
    'site_primary_color'       => 'color_primary',
    'site_links_color'         => 'color_link',
    'site_links_focus_color'   => 'color_link_hover',

    // Colors - Body
    'body_background_color'    => 'body_bg',
    'content_background_color' => 'content_bg',
    'box_background_color'     => 'box_bg',

    // Colors - Text
    'body_text_color'          => 'text_color',
    'headings_color'           => 'heading_color',

    // Typography
    'site_title_typography'         => 'typo_site_title',
    'headings_typography'           => 'typo_headings',
    'h1_typography'                 => 'typo_h1',
    'h2_typography'                 => 'typo_h2',
    'h3_typography'                 => 'typo_h3',
    'h4_typography'                 => 'typo_h4',
    'h5_typography'                 => 'typo_h5',
    'h6_typography'                 => 'typo_h6',
    'menu_typography'               => 'typo_menu',
    'body_typography'               => 'typo_body',

    // Buttons
    'button_background_color'       => 'btn_bg',
    'button_background_hover_color' => 'btn_bg_hover',
    'button_text_color'             => 'btn_text',
    'button_text_hover_color'       => 'btn_text_hover',
    'button_border_color'           => 'btn_border',
    'button_border_hover_color'     => 'btn_border_hover',

    // Footer
    'site_footer_bg'                => 'footer_bg',
    'site_footer_title_color'       => 'footer_title_color',
    'site_footer_content_color'     => 'footer_text_color',
    'site_footer_links_color'       => 'footer_link_color',
    'site_footer_links_hover_color' => 'footer_link_hover',

    // Copyright
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
```

---

## New Option Structure

### Organized by Panels

```php
/**
 * New Kirki Panel/Section Structure
 */

// PANEL: General
├── Section: Layout
│   ├── layout (wide/boxed)
│   ├── container_width
│   └── border_radius
├── Section: Loader
│   ├── loader_enabled
│   ├── loader_style
│   └── loader_bg
└── Section: Performance
    ├── local_google_fonts
    └── preload_fonts

// PANEL: Header
├── Section: Layout
│   ├── header_layout (PRO: 4 options)
│   ├── header_sticky
│   └── header_transparent
├── Section: Elements
│   ├── header_search
│   ├── header_cart
│   └── header_login
└── Section: Menu (PRO)
    ├── menu_effect
    └── menu_hover_style

// PANEL: Colors
├── Section: Brand
│   ├── color_primary
│   ├── color_secondary
│   └── color_accent
├── Section: Text
│   ├── text_color
│   ├── heading_color
│   ├── color_link
│   └── color_link_hover
├── Section: Surfaces
│   ├── body_bg
│   ├── content_bg
│   └── box_bg
├── Section: Buttons
│   ├── btn_bg / btn_bg_hover
│   ├── btn_text / btn_text_hover
│   └── btn_border / btn_border_hover
└── Section: Presets (NEW)
    └── color_preset (one-click schemes)

// PANEL: Typography
├── Section: Base
│   ├── typo_body
│   └── typo_headings
├── Section: Headings
│   ├── typo_h1 through typo_h6
└── Section: Navigation
    ├── typo_menu
    └── typo_submenu

// PANEL: Blog
├── Section: Archive
│   ├── blog_layout
│   ├── blog_columns
│   └── blog_sidebar
└── Section: Single
    ├── single_width
    ├── single_sidebar
    └── single_author_box

// PANEL: Footer
├── Section: Layout
│   ├── footer_layout (PRO)
│   └── footer_columns
├── Section: Colors
│   ├── footer_bg
│   ├── footer_text_color
│   └── footer_link_color
└── Section: Copyright
    ├── copyright_bg
    └── copyright_text

// PANEL: Community (BuddyPress)
├── Section: General
│   ├── bp_avatar_style
│   └── bp_cover_enabled
├── Section: Members (PRO)
│   ├── bp_member_layout
│   └── bp_member_sidebar
└── Section: Groups (PRO)
    ├── bp_group_layout
    └── bp_group_sidebar

// PANEL: Shop (WooCommerce)
├── Section: Catalog
│   ├── shop_sidebar
│   └── shop_columns
└── Section: Product (PRO)
    └── product_sidebar
```

---

## NEW Features in 5.0.0

### For Both FREE and PRO

| Feature | Description |
|---------|-------------|
| **Color Presets** | One-click color schemes |
| **Kirki CSS Output** | Auto-generated CSS |
| **Live Preview** | Instant customizer preview |
| **Sanitization** | All fields sanitized |
| **CSS Variables** | Modern CSS custom properties |
| **Tooltips** | Contextual help |
| **Section Icons** | Visual customizer navigation |

### PRO Exclusive Features

| Feature | Description |
|---------|-------------|
| **Responsive Controls** | Mobile/tablet specific settings |
| **Tabs Organization** | Grouped customizer controls |
| **Advanced Spacing** | Margin/Padding controls |
| **Style Variations** | Full theme presets |
| **Dark Mode Presets** | Dark color schemes |
| **Header Layouts** | 4+ header variations |
| **Footer Layouts** | 3+ footer variations |

---

## Color Presets System

### FREE Theme Presets (5)

```php
const FREE_COLOR_PRESETS = [
    'default' => [
        'label'  => 'BuddyX Default',
        'colors' => [
            'color_primary'    => '#ef5455',
            'color_secondary'  => '#41848f',
            'body_bg'          => '#f7f7f9',
            'content_bg'       => '#ffffff',
            'text_color'       => '#505050',
            'heading_color'    => '#111111',
        ],
    ],
    'ocean' => [
        'label'  => 'Ocean Blue',
        'colors' => [
            'color_primary'    => '#0077B6',
            'color_secondary'  => '#00B4D8',
            'body_bg'          => '#F0F9FF',
            'content_bg'       => '#ffffff',
            'text_color'       => '#1E3A5F',
            'heading_color'    => '#023E8A',
        ],
    ],
    'forest' => [
        'label'  => 'Forest Green',
        'colors' => [
            'color_primary'    => '#2D6A4F',
            'color_secondary'  => '#40916C',
            'body_bg'          => '#F0FFF4',
            'content_bg'       => '#ffffff',
            'text_color'       => '#1B4332',
            'heading_color'    => '#081C15',
        ],
    ],
    'sunset' => [
        'label'  => 'Warm Sunset',
        'colors' => [
            'color_primary'    => '#E76F51',
            'color_secondary'  => '#F4A261',
            'body_bg'          => '#FFFAF5',
            'content_bg'       => '#ffffff',
            'text_color'       => '#5C4033',
            'heading_color'    => '#264653',
        ],
    ],
    'starter' => [
        'label'  => 'Starter (Minimal)',
        'colors' => [
            'color_primary'    => '#111111',
            'color_secondary'  => '#505050',
            'body_bg'          => '#ffffff',
            'content_bg'       => '#ffffff',
            'text_color'       => '#333333',
            'heading_color'    => '#111111',
        ],
    ],
];
```

### PRO Theme Additional Presets (+5)

```php
const PRO_COLOR_PRESETS = [
    // Inherits all FREE presets, plus:

    'midnight' => [
        'label'  => 'Midnight Dark',
        'colors' => [
            'color_primary'    => '#6366F1',
            'color_secondary'  => '#8B5CF6',
            'body_bg'          => '#0F172A',
            'content_bg'       => '#1E293B',
            'text_color'       => '#CBD5E1',
            'heading_color'    => '#F1F5F9',
        ],
    ],
    'royal' => [
        'label'  => 'Royal Purple',
        'colors' => [
            'color_primary'    => '#7C3AED',
            'color_secondary'  => '#A78BFA',
            'body_bg'          => '#FAF5FF',
            'content_bg'       => '#ffffff',
            'text_color'       => '#4C1D95',
            'heading_color'    => '#2E1065',
        ],
    ],
    'ember' => [
        'label'  => 'Ember Red',
        'colors' => [
            'color_primary'    => '#DC2626',
            'color_secondary'  => '#F87171',
            'body_bg'          => '#FEF2F2',
            'content_bg'       => '#ffffff',
            'text_color'       => '#7F1D1D',
            'heading_color'    => '#450A0A',
        ],
    ],
    'corporate' => [
        'label'  => 'Corporate Blue',
        'colors' => [
            'color_primary'    => '#1E40AF',
            'color_secondary'  => '#3B82F6',
            'body_bg'          => '#F8FAFC',
            'content_bg'       => '#ffffff',
            'text_color'       => '#334155',
            'heading_color'    => '#0F172A',
        ],
    ],
    'nature' => [
        'label'  => 'Nature Earth',
        'colors' => [
            'color_primary'    => '#854D0E',
            'color_secondary'  => '#CA8A04',
            'body_bg'          => '#FEFCE8',
            'content_bg'       => '#ffffff',
            'text_color'       => '#422006',
            'heading_color'    => '#1C1917',
        ],
    ],
];
```

---

## Migration Helper Class

```php
<?php
/**
 * BuddyX 5.0.0 Migration Helper
 *
 * @package BuddyX
 * @since 5.0.0
 */

namespace BuddyX\Migration;

class Migrator {

    /**
     * Current theme version
     */
    const VERSION = '5.0.0';

    /**
     * Option key for tracking migration
     */
    const MIGRATION_KEY = 'buddyx_migrated_version';

    /**
     * Option key for backup
     */
    const BACKUP_KEY = 'buddyx_pre_migration_backup';

    /**
     * Run migration on theme activation/update
     */
    public static function init() {
        add_action('after_switch_theme', [__CLASS__, 'maybe_migrate']);
        add_action('upgrader_process_complete', [__CLASS__, 'on_theme_update'], 10, 2);
    }

    /**
     * Check and run migration if needed
     */
    public static function maybe_migrate() {
        $migrated_version = get_option(self::MIGRATION_KEY, '0.0.0');

        if (version_compare($migrated_version, self::VERSION, '<')) {
            self::run_migration();
        }
    }

    /**
     * Hook into theme update
     */
    public static function on_theme_update($upgrader, $options) {
        if ($options['type'] === 'theme') {
            $theme = wp_get_theme();
            if ($theme->get('TextDomain') === 'buddyx') {
                self::maybe_migrate();
            }
        }
    }

    /**
     * Execute the migration
     */
    private static function run_migration() {
        // Step 1: Backup current theme_mods
        self::backup_options();

        // Step 2: Get all current theme_mods
        $theme_mods = get_theme_mods();

        // Step 3: Migrate each option
        foreach (Option_Map::get_migration_map() as $old_key => $new_key) {
            if (isset($theme_mods[$old_key])) {
                // Set new key with old value
                set_theme_mod($new_key, $theme_mods[$old_key]);

                // Keep old key for backwards compatibility (1 version)
                // Will be removed in 5.1.0
            }
        }

        // Step 4: Migrate typography (structure change)
        self::migrate_typography($theme_mods);

        // Step 5: Migrate colors to CSS variables format
        self::migrate_colors($theme_mods);

        // Step 6: Set migration version
        update_option(self::MIGRATION_KEY, self::VERSION);

        // Step 7: Clear any caches
        self::clear_caches();

        // Step 8: Log migration
        self::log_migration();
    }

    /**
     * Backup all theme_mods before migration
     */
    private static function backup_options() {
        $theme_mods = get_theme_mods();
        $backup = [
            'date'       => current_time('mysql'),
            'version'    => wp_get_theme()->get('Version'),
            'theme_mods' => $theme_mods,
        ];
        update_option(self::BACKUP_KEY, $backup);
    }

    /**
     * Restore from backup (admin action)
     */
    public static function restore_backup() {
        $backup = get_option(self::BACKUP_KEY);

        if (!$backup || empty($backup['theme_mods'])) {
            return false;
        }

        // Clear current theme_mods
        remove_theme_mods();

        // Restore each mod
        foreach ($backup['theme_mods'] as $key => $value) {
            set_theme_mod($key, $value);
        }

        // Reset migration version to force re-migration if needed
        delete_option(self::MIGRATION_KEY);

        return true;
    }

    /**
     * Migrate typography fields (structure may change)
     */
    private static function migrate_typography($theme_mods) {
        $typo_fields = [
            'site_title_typography' => 'typo_site_title',
            'headings_typography'   => 'typo_headings',
            'body_typography'       => 'typo_body',
            'menu_typography'       => 'typo_menu',
        ];

        foreach ($typo_fields as $old => $new) {
            if (isset($theme_mods[$old]) && is_array($theme_mods[$old])) {
                // Typography structure stays the same, just key changes
                set_theme_mod($new, $theme_mods[$old]);
            }
        }
    }

    /**
     * Migrate colors (ensure proper format)
     */
    private static function migrate_colors($theme_mods) {
        $color_fields = Option_Map::get_color_fields();

        foreach ($color_fields as $old => $new) {
            if (isset($theme_mods[$old])) {
                $color = $theme_mods[$old];

                // Ensure proper hex format
                if (!empty($color) && strpos($color, '#') !== 0 && strpos($color, 'rgba') !== 0) {
                    $color = '#' . $color;
                }

                set_theme_mod($new, $color);
            }
        }
    }

    /**
     * Clear all caches after migration
     */
    private static function clear_caches() {
        // Clear WordPress object cache
        wp_cache_flush();

        // Clear customizer cache
        delete_transient('buddyx_customizer_css');

        // Clear any page cache plugins
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
        }
    }

    /**
     * Log migration for debugging
     */
    private static function log_migration() {
        $log = [
            'date'         => current_time('mysql'),
            'from_version' => get_option(self::MIGRATION_KEY, 'unknown'),
            'to_version'   => self::VERSION,
            'options_migrated' => count(Option_Map::get_migration_map()),
        ];

        update_option('buddyx_migration_log', $log);
    }

    /**
     * Admin notice after migration
     */
    public static function migration_notice() {
        $log = get_option('buddyx_migration_log');

        if ($log && !get_option('buddyx_migration_notice_dismissed')) {
            ?>
            <div class="notice notice-success is-dismissible buddyx-migration-notice">
                <p>
                    <strong>BuddyX 5.0.0:</strong>
                    Your theme settings have been automatically migrated.
                    Please review your <a href="<?php echo admin_url('customize.php'); ?>">Customizer settings</a>.
                </p>
                <p>
                    <a href="<?php echo admin_url('themes.php?buddyx_dismiss_migration=1'); ?>" class="button">
                        Dismiss
                    </a>
                    <a href="<?php echo admin_url('themes.php?buddyx_restore_backup=1'); ?>" class="button">
                        Restore Previous Settings
                    </a>
                </p>
            </div>
            <?php
        }
    }
}

/**
 * Option mapping class
 */
class Option_Map {

    /**
     * Get full migration map
     */
    public static function get_migration_map() {
        return [
            // Layout
            'site_layout'              => 'layout',
            'site_container_width'     => 'container_width',
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

            // Footer
            'site_footer_bg'           => 'footer_bg',
            'site_footer_title_color'  => 'footer_title_color',
            'site_footer_content_color' => 'footer_text_color',
            'site_copyright_bg'        => 'copyright_bg',
            'site_copyright_text'      => 'copyright_text',

            // Blog
            'blog_layout'              => 'blog_layout',
            'site_sidebar_option'      => 'sidebar_default',

            // BuddyPress
            'buddypress_avatar_style'  => 'bp_avatar_style',
            'buddypress_sidebar_option' => 'sidebar_buddypress',
        ];
    }

    /**
     * Get color-specific fields
     */
    public static function get_color_fields() {
        return array_filter(self::get_migration_map(), function($new_key) {
            return strpos($new_key, 'color_') === 0 ||
                   strpos($new_key, '_bg') !== false ||
                   strpos($new_key, '_color') !== false;
        });
    }
}

// Initialize migration
Migrator::init();
add_action('admin_notices', [Migrator::class, 'migration_notice']);
```

---

## File Structure Changes

### Current Structure (4.x)
```
buddyx/
├── inc/
│   ├── Kirki/
│   │   └── Component.php (54 lines)
│   ├── Kirki_Option/
│   │   └── Component.php (2,467 lines - MONOLITHIC)
│   └── Dynamic_Style/
│       └── Component.php (300+ lines - MANUAL CSS)
└── external/
    └── kirki-utils.php (defaults)
```

### New Structure (5.0.0)
```
buddyx/
├── inc/
│   ├── Kirki/
│   │   ├── Component.php (main loader)
│   │   ├── Config.php (Kirki configuration)
│   │   └── Defaults.php (centralized defaults)
│   ├── Kirki_Option/
│   │   ├── Component.php (registers all - small)
│   │   ├── Panels/
│   │   │   ├── General.php
│   │   │   ├── Header.php
│   │   │   ├── Colors.php
│   │   │   ├── Typography.php
│   │   │   ├── Blog.php
│   │   │   ├── Footer.php
│   │   │   ├── Community.php (BuddyPress)
│   │   │   └── Shop.php (WooCommerce)
│   │   ├── Fields/
│   │   │   ├── Layout_Fields.php
│   │   │   ├── Color_Fields.php
│   │   │   ├── Typography_Fields.php
│   │   │   └── ... (modular)
│   │   └── Presets/
│   │       ├── Color_Presets.php
│   │       └── Style_Presets.php (PRO)
│   ├── Migration/
│   │   ├── Migrator.php
│   │   └── Option_Map.php
│   └── CSS/
│       └── Variables.php (CSS var helper - minimal)
└── external/
    └── kirki-utils.php (DEPRECATED - keep for BC)
```

---

## FREE vs PRO Comparison (5.0.0)

| Feature | FREE | PRO |
|---------|------|-----|
| **Color Presets** | 5 | 10 |
| **Header Layouts** | 1 | 4 |
| **Footer Layouts** | 1 | 3 |
| **Responsive Controls** | No | Yes |
| **Tabs in Customizer** | No | Yes |
| **Margin/Padding Controls** | No | Yes |
| **Style Variations** | No | 6 |
| **Block Patterns** | 40 | 103 |
| **BuddyPress Options** | Basic (5) | Full (60+) |
| **WooCommerce Options** | Basic (8) | Full (30+) |
| **Dark Mode** | No | Yes |
| **Typography Presets** | No | Yes |

---

## PRO-Specific Additions

### 1. Responsive Controls
```php
// PRO: Device-specific settings
new \Kirki\Pro\Field\Responsive([
    'settings' => 'container_width_responsive',
    'label'    => esc_html__('Container Width', 'buddyx-pro'),
    'section'  => 'layout',
    'default'  => [
        'desktop' => '1170px',
        'tablet'  => '100%',
        'mobile'  => '100%',
    ],
    'output'   => [
        [
            'element'  => '.container',
            'property' => 'max-width',
        ],
    ],
]);
```

### 2. Tabs Organization
```php
// PRO: Grouped controls
new \Kirki\Pro\Field\Tabs([
    'settings' => 'header_tabs',
    'section'  => 'header',
    'tabs'     => [
        'layout'   => esc_html__('Layout', 'buddyx-pro'),
        'elements' => esc_html__('Elements', 'buddyx-pro'),
        'colors'   => esc_html__('Colors', 'buddyx-pro'),
    ],
]);
```

### 3. Style Variations (theme.json)
```
buddyx-pro/
└── styles/
    ├── default.json
    ├── ocean.json
    ├── forest.json
    ├── midnight.json (dark)
    ├── corporate.json
    └── starter.json
```

---

## Timeline

### Phase 1: Preparation (v4.9.3) - 2 weeks
- [ ] Add deprecation notices to old option keys
- [ ] Add sanitization to all existing fields
- [ ] Create backup helper (admin tool)
- [ ] Test migration helper in staging

### Phase 2: FREE Theme (v5.0.0-free) - 4 weeks
- [ ] Refactor file structure
- [ ] Implement new Kirki fields with output
- [ ] Add color presets (5)
- [ ] Add migration helper
- [ ] Remove Dynamic_Style (use Kirki output)
- [ ] Add tooltips and section icons
- [ ] Test extensively
- [ ] Submit to WordPress.org

### Phase 3: PRO Theme (v5.0.0-pro) - 4 weeks
- [ ] All FREE changes
- [ ] Add PRO Kirki controls (responsive, tabs)
- [ ] Add PRO color presets (+5)
- [ ] Add style variations (6)
- [ ] Add header/footer layouts
- [ ] Add dark mode presets
- [ ] Test extensively

### Phase 4: Testing & Release - 2 weeks
- [ ] Beta testing with users
- [ ] Migration testing on real sites
- [ ] Documentation update
- [ ] Release

---

## Rollback Plan

If issues arise after 5.0.0 release:

1. **Immediate:** Users can click "Restore Previous Settings" in admin notice
2. **Short-term:** Release 5.0.1 patch with fixes
3. **Worst-case:** Release 4.9.4 with rollback option

---

## Documentation Needed

| Document | Audience |
|----------|----------|
| Migration Guide | Users |
| Changelog | Users |
| Developer Docs | Developers |
| Hook Reference | Developers |
| Filter Reference | Developers |

---

## Testing Checklist

### Fresh Install
- [ ] All defaults apply correctly
- [ ] Customizer loads without errors
- [ ] CSS output is correct
- [ ] No console errors

### Migration (4.9.x → 5.0.0)
- [ ] All existing settings preserved
- [ ] Visual appearance unchanged
- [ ] Admin notice appears
- [ ] Restore backup works
- [ ] No PHP errors

### Plugin Compatibility
- [ ] BuddyPress options work
- [ ] WooCommerce options work
- [ ] bbPress options work
- [ ] LearnDash options work (PRO)

### Kirki Versions
- [ ] Works with Kirki 5.x
- [ ] Graceful fallback without Kirki

---

*This plan ensures a smooth transition to BuddyX 5.0.0 while preserving all user customizations.*
