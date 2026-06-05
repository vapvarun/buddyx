# BuddyX Code Flows

Generated 2026-06-05 from audit/manifest.json (wp-plugin-onboard). Do not hand-edit counts; re-run the skill.

Flow traces for the major user-facing features. Each flow lists entry point, code path, key files, and permissions.

---

## (a) Customizer setting -> token CSS output -> frontend paint

How an admin's color choice in Customizer reaches the rendered page as a `--bx-color-*` token, including the FOUC-free dark paint.

| Step | Code path |
|---|---|
| Entry | Admin sets a color in Customizer > Site Skin (e.g. `site_primary_color`). |
| Register | Customizer_Framework materializes the control: `Field::add` -> `WP_Customize_Manager::add_setting` + `add_control` (inc/Customizer_Framework/Field.php:115). Config from inc/Customizer_Settings/Fields/Skin_Fields.php. |
| Persist | On Publish, WP writes the value into `theme_mods_buddyx`. |
| Collect | Frontend request: `Tokens\Component::collect_mods()` reads theme_mods (inc/Tokens/Component.php:832); `build_token_css()` maps each mod to its semantic token via the role table at :43-242 (e.g. `site_primary_color` -> `--bx-color-accent`). build at :858. |
| Emit | `emit_tokens()` (hooked `wp_enqueue_scripts` pri 20, inc/Tokens/Component.php:626) injects `<style>:root { --bx-color-*: ... }` via `wp_add_inline_style('buddyx-global', $css)` (:813-816). |
| Dark overrides | `build_dark_block()` (:1077) emits `:root[data-bx-mode="dark"]{...}` plus `@media (prefers-color-scheme:dark){:root[data-bx-mode="auto"]{...}}` (:1103-1104) in the same inline style. |
| Paint | Stylesheets (buddyx-global, buddyx-tokens-applied) consume the tokens via `var(--bx-color-*)`. Page paints with the saved palette. |

### FOUC-free dark-mode paint

| Step | Code path |
|---|---|
| Bootstrap script | `emit_mode_script()` hooked `wp_head` priority 1 (inc/Tokens/Component.php:627, body :1114) prints inline `<script id="buddyx-color-mode-bootstrap">` BEFORE any CSS/paint. |
| Default resolution | PHP computes default from `get_theme_mod('site_color_mode','light')`. If the active style variation is dark-scheme (`active_variation_is_dark_scheme()`) and the saved value is not an explicit `auto`/`dark`, default becomes `dark` (:1135-1140) so a Dark preset renders dark on first paint. |
| Client preference | Script reads `localStorage.getItem('bx-color-mode')`; falls back to the PHP default; validates against light/dark/auto; sets `document.documentElement.setAttribute('data-bx-mode', mode)` synchronously (:1141-1151). |
| Result | `<html data-bx-mode>` is set before the browser paints, so the `[data-bx-mode="dark"]` token cascade applies with no flash of light mode. |

Key files:

| File | Role |
|---|---|
| inc/Customizer_Framework/Field.php | Materializes settings/controls |
| inc/Customizer_Settings/Fields/Skin_Fields.php | Skin color field config |
| inc/Tokens/Component.php | theme_mod -> token map, CSS emit, dark block, FOUC bootstrap |
| assets/css/global.min.css, bx-tokens-applied.css | Consume `var(--bx-color-*)` |

Permissions: editing requires `edit_theme_options` (Customizer). Frontend render is public.

---

## (b) Color mode toggle (user click -> JS -> storage -> CSS)

| Step | Code path |
|---|---|
| Entry | Visitor clicks the toggle button `.bx-color-mode-toggle__btn` (rendered by inc/Color_Mode_Toggle/Component.php:120, gated on `site_color_mode_toggle_show`). |
| Enqueue | `buddyx-color-mode-toggle` script enqueued in footer pri 30 when the toggle is enabled (inc/Color_Mode_Toggle/Component.php:148). |
| Click handler | assets/js/color-mode-toggle.min.js: click delegates to `.bx-color-mode-toggle__btn`, reads current mode, cycles light -> dark -> auto. |
| Persist | `localStorage.setItem('bx-color-mode', next)`. |
| Apply | Sets `document.documentElement.setAttribute('data-bx-mode', next)`, updates each button's `data-mode`/`aria-pressed`/`aria-label`, dispatches `bx:color-mode-change` CustomEvent. |
| Repaint | `[data-bx-mode="dark"]` / `@media auto` token blocks (flow a) take effect instantly via CSS. `pageshow` (bf-cache restore) re-applies the saved mode. |
| Persistence | On every subsequent load the head bootstrap (flow a) reads the same `localStorage` key before paint. |

LearnDash variant: when SFWD_LMS is active, an AJAX path (`buddyx_lms_toggle_theme_color`, inc/Accessibility/Component.php:93) sets an HttpOnly cookie `bxtheme`. Nonce `buddyx_toggle_theme_color`; logged-in only, no capability check.

Key files: inc/Color_Mode_Toggle/Component.php, assets/js/color-mode-toggle.min.js, inc/Tokens/Component.php (cascade), inc/Accessibility/Component.php (LMS AJAX).

Permissions: visitor-facing, no auth (localStorage path). LMS cookie path is logged-in only via nonce.

---

## (c) Theme options REST flow (admin JS -> buddyx/v1/settings -> option storage)

| Step | Code path |
|---|---|
| Entry | Admin page JS POSTs settings to the REST route. |
| Route | `register_rest_route('buddyx/v1', '/settings', ...)` (inc/Options/Component.php:118), methods POST/PUT/PATCH. |
| Permission | `settings_permissions_check()` -> `current_user_can('manage_options')` (inc/Options/Component.php:191). |
| Handler | `update_settings(WP_REST_Request)` (inc/Options/Component.php:136). Reads `settings` param; returns 400 `invalid_settings` if not an array. |
| Sanitize | `sanitize_theme_settings()` cleans per-key (email_option / url_option / text). |
| Persist | `update_option('buddyx_theme_settings', $settings)` (inc/Options/Component.php:145). |
| Response | `WP_REST_Response` success payload, or `WP_Error` on failure. |

Key files: inc/Options/Component.php. No reader of `buddyx_theme_settings` found in scanned scope (write-side only).

Permissions: `manage_options` required for the endpoint.

---

## (d) Webfont pipeline (font URL option -> fonts folder -> cron cleanup -> regenerate AJAX)

| Step | Code path |
|---|---|
| Enable | Admin enables "Load Google Fonts locally" (`site_load_google_font_locally`, Site Performance section). |
| Download | The loader fetches the Google Fonts stylesheet, rewrites it to local URLs, and stores files under the local fonts directory; `buddyx_font_url` option records the source URL (the cache-exists signal). `wp rig fonts_download` is the CLI equivalent. |
| Cache invalidate | On `customize_save_after`, `buddyx_delete_cached_partials()` deletes the cached folder if `buddyx_font_url` is set (inc/Webfont/class-buddyx-webfont-loader.php). |
| Cron cleanup | Scheduled `buddyx_delete_fonts_folder` (monthly, CLEANUP_FREQUENCY) deletes the folder + `buddyx_font_url` option (schedule :634, handler hook :128). Single-site / main-site only; skipped while wp_installing. |
| Manual regenerate | "Flush local font" button (`site_flush_local_font`) triggers AJAX `buddyx_regenerate_fonts_folder` (handler :751, hook :771): checks `current_user_can('edit_theme_options')` FIRST, then `check_ajax_referer('buddyx_regenerate_fonts','nonce')`; if `buddyx_font_url` set, calls `buddyx_delete_fonts_folder()` and returns success / `failed_to_flush`; else `no_font_loader`. |

Key files: inc/Webfont/class-buddyx-webfont-loader.php, inc/Customizer_Settings/Fields/Site_Performance.php, wp-cli/buddyx-commands.php (fonts_download).

Permissions: regenerate AJAX gated on `edit_theme_options` (cap checked before nonce). Cron runs as system.

---

## (e) Asset enqueue pipeline (get_css_files manifest -> preload_callback -> wp_enqueue_style)

| Step | Code path |
|---|---|
| Manifest | `get_css_files()` returns `$handle => [src, global, preload_callback]` (inc/Styles/Component.php:119+), filterable via `buddyx_css_files` (:422). JS mirror: `get_js_files()` (inc/Scripts/Component.php:104+), filter `buddyx_js_files` (:274). |
| Preload gate | `preloading_styles_enabled()` returns `! buddyx()->is_amp()`, filterable via `buddyx_preloading_styles_enabled` (inc/Styles/Component.php:344-352). |
| Decision | For each file (inc/Styles/Component.php:100-118): if `global_style` OR preloading disabled OR `preload_callback` evaluates true -> `wp_enqueue_style`; else only register. |
| Edge case (Basecamp "Code level issues") | When preloading is disabled (e.g. AMP, or the filter returns false), the branch at :115-118 enqueues EVERY registered sheet directly. Without this, a non-global sheet whose `preload_callback` is `__return_false` (e.g. buddyx-content) would NEVER load - it is only ever pulled in by preload, so disabling preload would silently drop it. The `|| ! $preloading_styles_enabled` guard prevents that. |
| Preload tags | `action_preload_styles()` (hooked wp_head, :69/214) emits `<link rel=preload>` for sheets whose `preload_callback` is true; returns early if preloading disabled (:217). `add_preload_for_critical_css` filters `style_loader_tag` (:74). |

Key files: inc/Styles/Component.php, inc/Scripts/Component.php.

Permissions: frontend render, public.

---

## (f) Starter content + style variation application

### Starter content

| Step | Code path |
|---|---|
| Register | `register()` calls `add_theme_support('starter-content', $this->config())` (inc/Starter_Content/Component.php:73). |
| Config | `config()` returns posts, nav_menus, widgets, options, theme_mods (inc/Starter_Content/Component.php:85-89). Post keys become `{{placeholder}}` tokens usable in nav_menus/options/theme_mods. |
| Apply | WP imports the content on a fresh site (Customizer "fresh site" preview / first activation). |
| Admin guards | Starter-content admin actions gated on `manage_options` (inc/Starter_Content/Component.php:307, :339, :360). |

### Style variation

| Step | Code path |
|---|---|
| Select | Admin picks a variation via `site_style_variation` (Site Skin section; values: cool/dark/editorial/minimal/monochrome/pastel/vibrant/warm, or empty). |
| Load | `Tokens::load_variation_data($slug)` (inc/Tokens/Component.php:1368) loads the variation palette; `register_variation_theme_mod_filters()` (hooked init pri 20, :631) overlays variation values onto theme_mods so the token build picks them up. |
| Paint | `build_token_css()` routes a dark variation's palette into `[data-bx-mode="dark"]` so it becomes the dark default; light/unknown variations paint `:root` (:900-918). theme.json `--wp--preset--color--*` overrides repaint blocks/patterns. |
| Mode default | `active_variation_is_dark_scheme()` (:1172) makes the head bootstrap default to `dark` for a dark variation (see flow a). |

Key files: inc/Starter_Content/Component.php, inc/Tokens/Component.php, theme.json, styles/*.json.

Permissions: variation/starter setup requires `edit_theme_options` (Customizer) / `manage_options` (starter-content admin actions). Frontend render is public.
