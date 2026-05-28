# Asset Manifest Reference

BuddyX 5.1.0 uses a unified CSS/JS asset manifest as the single source of truth for every conditional enqueue in the theme. This doc covers the manifest schema, available handles, and how plugin authors / child theme developers can extend or override the manifest.

> **Customer using the theme?** You don't need this file. The asset manifest is internal plumbing — every page just works.

---

## Why a manifest

Pre-5.1.0 BuddyX scattered CSS/JS enqueues across many files: blog stylesheets in one file, BuddyPress stylesheets in another, integration files in conditional functions. Each new feature meant a new `wp_enqueue_style()` call somewhere.

5.1.0 unifies these into a single manifest at `inc/Styles/Component.php` (CSS) and `inc/Scripts/Component.php` (JS). Each entry declares its file, when it loads, what other handles it depends on, and when to preload it.

**Benefits**:
- Single place to see every asset the theme can emit
- Conditional loading is data, not code (a `preload_callback` returns `true` to emit)
- Child themes / plugins can filter the manifest to disable, replace, or extend any entry
- Handle names mirror pre-5.1.0 so existing dequeue calls continue to work

---

## CSS manifest schema

Each entry in `$css_files` declares:

```php
'buddyx-handle-name' => array(
    'file'             => 'css/path/relative/to/theme.min.css',
    'global'           => true | false,
    'preload_callback' => '__return_true' | callable,
    'media'            => 'all' | 'screen' | 'print',
    'deps'             => array( 'buddyx-other-handle' ),
    'loading'          => 'normal' | 'preload',
    'usage_hint'       => 'short string explaining when this loads',
),
```

| Key | Type | Purpose |
|---|---|---|
| `file` | string | CSS file path relative to theme root. `.min.css` versions used in production; source `.css` used when `SCRIPT_DEBUG` is on |
| `global` | bool | `true` = enqueue on every page request. `false` = only when `preload_callback` returns true |
| `preload_callback` | string\|callable | When `global` is `false`, this decides per-request. Use `__return_true` for forced load, or a closure that checks `class_exists()`, `is_page()`, etc. |
| `media` | string | Media query (default `all`) |
| `deps` | array | Other manifest handles this depends on. Loaded in dependency order |
| `loading` | string | `normal` (link rel=stylesheet) or `preload` (link rel=preload to start fetch early) |
| `usage_hint` | string | Human-readable explanation for the audit log / docs. Not used at runtime |

### Example: a BuddyPress-only stylesheet

```php
'buddyx-buddypress' => array(
    'file'             => 'css/buddypress.min.css',
    'global'           => false,
    'preload_callback' => 'class_exists',  // checks if 'BuddyPress' class exists
    'media'            => 'all',
    'deps'             => array( 'buddyx-styles' ),
    'loading'          => 'normal',
    'usage_hint'       => 'BuddyPress community surfaces',
),
```

This entry loads only when BuddyPress is active. No `is_buddypress()` checks scattered through the codebase — the manifest is the boundary.

---

## Available CSS handles (free BuddyX 5.1.0)

The full list is at `inc/Styles/Component.php → $css_files`. Common handles:

| Handle | Loads when | Purpose |
|---|---|---|
| `buddyx-styles` | Always | Main theme stylesheet |
| `buddyx-google-fonts` | When Google Fonts are configured | Web font CSS |
| `buddyx-buddypress` | When BuddyPress is active | BuddyPress component styles |
| `buddyx-bbpress` | When bbPress is active | bbPress forum styles |
| `buddyx-woocommerce` | When WooCommerce is active | Shop / cart / checkout / account styles |
| `buddyx-fluentcart` | When FluentCart is active | FluentCart-specific styles |
| `buddyx-surecart` | When SureCart is active | SureCart-specific styles |
| `buddyx-blocks` | On block-editor and front-end (post/page contexts) | Block-style overrides |
| `buddyx-dark-mode` | When dark-mode toggle is enabled | Dark token overrides |
| `buddyx-slick` | When carousel components are present | Slick carousel library styles |
| `buddyx-loader` | When site loader is enabled | Site loader animation styles |

> **Verify before relying**: grep the actual manifest with `grep -A 5 "css_files" inc/Styles/Component.php`. The list above is illustrative; some handles may be added/renamed across point releases.

---

## Filter: `buddyx_css_files`

The manifest is filterable. Plugin authors / child themes can:

### Disable an entry

```php
add_filter( 'buddyx_css_files', function( $css_files ) {
    unset( $css_files['buddyx-loader'] );
    return $css_files;
});
```

### Replace an entry's file path

```php
add_filter( 'buddyx_css_files', function( $css_files ) {
    $css_files['buddyx-buddypress']['file'] = 'css/my-custom-bp-overrides.css';
    return $css_files;
});
```

### Add a new entry

```php
add_filter( 'buddyx_css_files', function( $css_files ) {
    $css_files['my-plugin-styles'] = array(
        'file'             => 'css/my-plugin.min.css',
        'global'           => false,
        'preload_callback' => function() { return is_page( 'my-page' ); },
        'media'            => 'all',
        'deps'             => array( 'buddyx-styles' ),
        'loading'          => 'normal',
        'usage_hint'       => 'My plugin custom styles',
    );
    return $css_files;
});
```

> **Note**: child themes should usually use the WordPress-native `wp_enqueue_style()` / `wp_dequeue_style()` instead. The manifest filter is for theme-level extensions and plugins doing conditional asset orchestration.

---

## JS manifest

The JS manifest at `inc/Scripts/Component.php` follows the same pattern. Filter: `buddyx_js_files`.

Common JS handles:
- `buddyx-main` — main theme JS
- `buddyx-dark-mode-toggle` — color-mode toggle handler
- `buddyx-slick` — Slick carousel library
- `buddyx-customizer-preview` — Customizer live preview (admin-side only)

---

## Dequeuing styles the WordPress-native way

If you just want to remove a BuddyX style (e.g., you're hosting fonts yourself and want to skip BuddyX's font loader):

```php
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'buddyx-google-fonts' );
}, 20 );
```

Use priority `20` (after BuddyX's own enqueue at default priority `10`). This works because BuddyX's handle names are stable across versions.

---

## Debugging the manifest

To see what's actually loading on a given page:

1. Visit the page in your browser
2. Open developer tools → Network tab → filter by `.css` or `.js`
3. The handle names are encoded in the loaded paths (e.g., `buddyx-buddypress.min.css`)

Alternatively, log all enqueued styles at runtime:

```php
add_action( 'wp_print_styles', function() {
    global $wp_styles;
    error_log( 'Enqueued styles: ' . print_r( $wp_styles->queue, true ) );
});
```

---

## Related

- [Filters Index](./filters-index.md) — all WordPress filter hooks in BuddyX
- [Design Tokens](../buddyx-design-tokens.md) — CSS custom property reference
- [Local CI](../local-ci.md) — pre-commit hooks + manual checks
- [Project README](../README.md) — top-level docs index
