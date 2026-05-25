# Filters Index

BuddyX exposes ~48 unique WordPress filter hooks. Use these to customize the theme's behavior without editing core theme files.

> **Customer using the theme?** You don't need this file. Filters are for plugin authors and child theme developers.

> **Verify each filter before relying on it** — function names and argument signatures occasionally change between minor releases. Grep the actual source: `grep -rn "apply_filters( 'filter_name'" inc/`.

---

## Asset filters

| Filter | Defined in | What it filters |
|---|---|---|
| `buddyx_css_files` | `inc/Styles/Component.php` | The full CSS manifest. See [Asset Manifest](./asset-manifest.md) for the schema |
| `buddyx_js_files` | `inc/Scripts/Component.php` | The full JS manifest |
| `buddyx_preloading_styles_enabled` | `inc/Styles/Component.php` | Whether to emit `<link rel="preload">` tags for theme stylesheets |
| `buddyx_local_font_file_name` | `inc/Fonts/Component.php` | Filename used when caching Google Fonts locally |
| `buddyx_local_fonts_base_path` | `inc/Fonts/Component.php` | Filesystem path for cached local fonts |
| `buddyx_local_fonts_base_url` | `inc/Fonts/Component.php` | URL for cached local fonts |
| `buddyx_local_fonts_directory_name` | `inc/Fonts/Component.php` | Directory name (relative to `wp-content/uploads/`) for cached fonts |
| `buddyx_local_google_fonts_format` | `inc/Fonts/Component.php` | Font format (default `woff2`) |
| `buddyx_google_fonts` | `inc/Fonts/Component.php` | List of Google fonts to load. Add/remove fonts here |
| `buddyx_speculation_rules` | `inc/extra.php` | Speculation Rules API config (predictive prefetch) |

---

## Customizer filters

| Filter | Defined in | What it filters |
|---|---|---|
| `buddyx_customizer_field_args` | `inc/Customizer_Framework/` | Arguments passed when registering a customizer field |
| `buddyx_customizer_field_type_map` | `inc/Customizer_Framework/` | Map of custom field types → registration classes |

---

## Layout + content filters

| Filter | Defined in | What it filters |
|---|---|---|
| `buddyx_footer_copyright_text` | `inc/extra.php` | Final copyright string output in the footer |
| `buddyx_enable_post_thumbnails` | `inc/extra.php` | Whether featured images render on blog archives / posts |
| `buddyx_search_field_toggle_data_attrs` | `inc/extra.php` | Data attributes on the header search toggle button |
| `buddyx_search_slide_toggle_data_attrs` | `inc/extra.php` | Data attributes on the sliding search field |

---

## Featured image / thumbnail size filters

BuddyX defines multiple thumbnail sizes for blog layouts (Default, List, Grid, Masonry, Featured). Each has width/height/crop filters:

| Filter | Default | Purpose |
|---|---|---|
| `buddyx_featured_width` | `1200` | Single-post featured image width |
| `buddyx_featured_height` | `630` | Single-post featured image height |
| `buddyx_featured_crop` | `true` | Crop behavior |
| `buddyx_large_width` | `1200` | Large card width |
| `buddyx_large_height` | `630` | Large card height |
| `buddyx_large_crop` | `true` | |
| `buddyx_list_width` | `600` | List-layout image width |
| `buddyx_list_height` | `400` | List-layout image height |
| `buddyx_list_crop` | `true` | |
| `buddyx_col_two_width` | `600` | Grid 2-col width |
| `buddyx_col_two_height` | `400` | Grid 2-col height |
| `buddyx_col_two_crop` | `true` | |
| `buddyx_col_three_width` | `400` | Grid 3-col width |
| `buddyx_col_three_height` | `300` | Grid 3-col height |
| `buddyx_col_three_crop` | `true` | |

**Verify exact defaults**: `grep -A 3 "buddyx_featured_width" inc/`.

> **Why filter?** Most sites don't need to. Use these only if you have a specific image strategy that conflicts with BuddyX defaults (e.g., your site uses 16:9 throughout and you want `_height` adjusted globally).

---

## BuddyPress filters

| Filter | Defined in | What it filters |
|---|---|---|
| `bp_activity_excerpt_append_text` | BuddyPress integration | Text appended to truncated activity excerpts (default `…`) |
| `bp_buddyx_user_progress` | BuddyPress integration | Raw user-progress percentage |
| `bp_buddyx_user_progress_formatted` | BuddyPress integration | Formatted user-progress string |
| `buddyx_bp_get_activity_css_first_class` | BuddyPress integration | First CSS class applied to activity items |
| `buddyx_add_feature_image_blog_post_as_activity_content` | BuddyPress integration | Whether to add the blog post's featured image into the activity entry |
| `buddyx_rtmedia_filter_enabled` | rtMedia integration | Whether the rtMedia BuddyX bridge is active |

---

## User badges / community filters

| Filter | Purpose |
|---|---|
| `buddyx_user_badges_limit` | Max number of badges shown per member (default depends on context) |

---

## Token / dark mode filters

| Filter | Defined in | What it filters |
|---|---|---|
| `buddyx_variation_is_dark_scheme` | `inc/Tokens/Component.php` | Whether a given style variation should be treated as dark. Override per slug |

---

## Accessibility filters

| Filter | Purpose |
|---|---|
| `buddyx_attr_{$context}` | Modify the array of HTML attributes for a given context (e.g., `body`, `post`) |
| `buddyx_attr_{$context}_output` | Modify the final attribute string for a given context |

---

## Common customization examples

### Replace footer copyright text

```php
add_filter( 'buddyx_footer_copyright_text', function( $text ) {
    return '© ' . date('Y') . ' My Company — All rights reserved';
});
```

### Add a custom Google Font

```php
add_filter( 'buddyx_google_fonts', function( $fonts ) {
    $fonts['Geist'] = array( '400', '500', '600', '700' );
    return $fonts;
});
```

### Disable featured-image rendering on archive

```php
add_filter( 'buddyx_enable_post_thumbnails', function( $enabled ) {
    if ( is_archive() ) {
        return false;
    }
    return $enabled;
});
```

### Mark a custom style variation as dark

```php
add_filter( 'buddyx_variation_is_dark_scheme', function( $is_dark, $slug ) {
    if ( 'midnight' === $slug ) {
        return true;
    }
    return $is_dark;
}, 10, 2 );
```

---

## How to find more filters

The grep command:

```bash
grep -rn "apply_filters" inc/ --include="*.php" | grep -v "^Binary"
```

…lists every `apply_filters` call site with file:line. Each result tells you (a) the filter name, (b) what gets filtered, (c) the surrounding context.

For one specific filter:

```bash
grep -rn "buddyx_search_field_toggle_data_attrs" inc/
```

---

## Why filters and not actions?

BuddyX intentionally uses **filters** (data passes through, you transform it) over **actions** (just an event fired) wherever possible. Filters compose better: many plugins can layer on a single filter without stepping on each other. Actions are for genuinely side-effecting events.

If you find yourself wanting an action hook for a customization point that BuddyX exposes only as a filter, the right answer is usually: use the filter and trigger your side effect inside the filter callback (return the value unchanged at the end).

---

## Related

- [Asset Manifest](./asset-manifest.md) — the CSS/JS manifest reference
- [Design Tokens](../buddyx-design-tokens.md) — CSS custom property reference
- [Local CI](../local-ci.md) — pre-commit hooks
- [Project README](../README.md) — top-level docs index
