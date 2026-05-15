# BuddyX 5.1.0 — Customizer Live Preview Audit

**Date:** 2026-05-15
**Scope:** Every `Field::add()` call in `inc/Customizer_Settings/Fields/*.php` — 125 fields across 11 panels.
**Trigger:** User reported "Site Loader options don't preview like Site Header options do" — a single concrete bug that turned out to be one symptom of a class of silent breakages.

## How the live preview pipeline actually works

Three layers decide what happens when a Customizer control changes:

1. **Each field's declared `transport`** — `postMessage` (no reload, JS patches inline CSS) or `refresh` (full preview reload). If undeclared, the framework default is `refresh`.

2. **`inc/Customizer_Framework/assets/customizer-preview.js`** — runs in the preview iframe, reads `window.buddyxCustomizerOutputs` (populated by `Customizer_Framework\Component::enqueue_preview()` from each field's `output` map), and binds `wp.customize(<settingId>).bind('change', …)` to rewrite `<style id="buddyx-customizer-preview-css">` in the preview head. **It only patches settings that ship an `output` payload.**

3. **`inc/Customizer/Component.php :: filter_dynamic_preview_setting_args`** — an allowlist of **41 token-driven settings** (global colors, button colors, header/footer/copyright colors, h1-h6 colors, border radii) that are **forced back to `'transport' => 'refresh'`** at priority 20. Reason: those settings render via the Tokens `:root` block, not their own `output` map, so postMessage would silently do nothing; refresh makes the preview reload, Tokens regenerates `:root`, change shows up.

## The silent-breakage formula

A setting is silently broken in the live preview when **all three** are true:

- `'transport' => 'postMessage'` (declared in the field args)
- **No `'output'` map** in the field args
- **Not** in the 41-setting refresh allowlist

Result: the change emits a postMessage event that no handler is listening for; no reload happens; the setting appears completely inert until the user saves and reloads manually.

## Findings — 8 silent breakages out of 125 fields

| # | File | Setting | Type | Status |
|---|---|---|---|---|
| 1 | `General_Fields.php` | `site_loader_type` | radio_image | SILENT |
| 2 | `WP_Login_Fields.php` | `custom_login_logo_image` | image | SILENT |
| 3 | `WP_Login_Fields.php` | `custom_login_logo_image_width` | dimension | SILENT |
| 4 | `WP_Login_Fields.php` | `custom_login_logo_image_height` | dimension | SILENT |
| 5 | `WP_Login_Fields.php` | `custom_login_logo_space` | dimension | SILENT |
| 6 | `WP_Login_Fields.php` | `custom_login_logo_url` | url | SILENT |
| 7 | `WP_Login_Fields.php` | `custom_login_logo_title` | text | SILENT |
| 8 | `WP_Login_Fields.php` | `custom_login_page_title` | text | SILENT |

The remaining **117 fields work** — some via `refresh` reload, some via `postMessage` + JS patch, some via the allowlist forcing refresh.

## Per-panel summary

| Panel | Fields | Silent | Status |
|---|---:|---:|---|
| Blog_Fields | 12 | 0 | ok — all reload |
| BuddyPress_Fields | 1 | 0 | ok |
| Footer_Fields | 3 | 0 | ok |
| General_Fields | 15 | 1 | 1 silent (Site Loader animation type) |
| Header_Fields | 5 | 0 | ok — all reload |
| Sidebar_Fields | 8 | 0 | ok |
| Site_Performance | 3 | 0 | ok |
| Skin_Fields | 54 | 0 | ok — 39 colors hit the allowlist + refresh |
| Sub_Header_Fields | 4 | 0 | ok |
| Typography_Fields | 11 | 0 | ok |
| WP_Login_Fields | 9 | **7** | **7 silent — whole panel preview non-functional** |

## Why these specific 8

The pattern is the same in every case: a developer set `'transport' => 'postMessage'` on a setting that needs the **front end** to react, but never paired it with an `output` map or a custom JS partial. Most are in the Login panel, where the customizer doesn't preview `wp-login.php` at all (the customizer always previews the front-end site), so postMessage was the wrong choice from the start — those settings only ever take effect after save anyway.

`site_loader_type` is the same mistake at the theme level: switching loader animation fundamentally swaps the markup (`<div class="loader-1">` vs `<div class="loader-2">` etc.), which postMessage can't patch — a reload is the only sensible transport.

## Fix

For each of the 8: change `'transport' => 'postMessage',` to `'transport' => 'refresh',` (or remove the line — default is refresh). One-line edit per setting; no JS or PHP behavior changes beyond the transport flag.

## Verification protocol (after fix)

For each of the 8 settings:

- [ ] Open the Customizer (`/wp-admin/customize.php`).
- [ ] Navigate to the containing section.
- [ ] Change the setting's value.
- [ ] Confirm the preview pane **reloads** within ~1 second.
- [ ] Confirm the new value is visible in the preview after reload.
- [ ] Reload `/wp-admin/customize.php` cleanly (no devtools cache) and confirm the saved value persists.

## Out of scope (decisions to keep)

- The 41-setting **forced-refresh allowlist** in `filter_dynamic_preview_setting_args` stays. Promoting those to true postMessage would require teaching `customizer-preview.js` to recompute `--bx-color-*` tokens and patch the Tokens `<style>` block per setting cluster — substantial work, deferred.
- The framework's **default transport of `refresh`** stays. It's the safe choice.

## Expanded sweep (3 additional failure modes)

After the 8 silent breakages were fixed, the audit was re-run in the live customizer to catch other wiring failure modes:

### Mode A — PHP-registered but missing from `wp.customize` (failed JS registration)

Cross-referenced every `Field::add()` against `wp.customize(<id>)` in the running customizer.

- **Total PHP-registered (excluding 14 display-only `custom-*` dividers + `site_flush_local_font`):** 111
- **Found in `wp.customize`:** 110
- **Missing:** 1 — `bbpress_sidebar_option`

The single miss is **by design**: `Sidebar_Fields.php:98` gates registration on `function_exists( 'is_bbpress' )`. bbPress is not active on this test site → setting skips registration. Same pattern for `woocommerce_sidebar_option` and the BuddyPress-specific sidebar options — they register because those plugins ARE active here.

### Mode B — postMessage with output map but missing JS handler entry

For every `transport => 'postMessage'` setting that ships an `output` map, the preview-side `window.buddyxCustomizerOutputs` should expose it so `customizer-preview.js` can patch CSS on change.

- **Total postMessage settings with handlers:** 20 (matches PHP count exactly)
- **Missing handler entries:** 0

All postMessage settings reach the JS handler correctly.

### Mode C — Output-map selectors that match zero elements in the preview DOM

For every output rule's `element` selector, queried `previewDocument.querySelectorAll()`. A zero-match would mean the JS patches CSS targeting nothing → silent partial breakage.

- **Selectors checked:** 24 across 20 postMessage settings
- **Zero-match (page-context conditional) selectors:** 8

All 8 zero-match cases are **legitimate conditional selectors** whose target classes are output by the theme under specific configurations or on specific content. None are stale/orphaned. Verified by `grep` in theme source:

| Setting | Selector | Why zero matches today (conditional) |
|---|---|---|
| `site_container_width` | `body.layout-boxed .site` | Only when `site_layout=boxed`; default is wide |
| `h4_typography_option` | `h4` | Only when current page has h4 tags |
| `h5_typography_option` | `h5` | same |
| `h6_typography_option` | `h6` | same |
| `sub_menu_typography_option` | `.main-navigation ul#primary-menu>li .sub-menu a` | Only when primary menu has nested items |
| `sub_header_background_setting` | `.site-sub-header` | Only when sub-header is enabled (`site_sub_header_bg=on`); markup emitted by `inc/extra.php:83` |
| `site_sub_header_typography` | `.site-sub-header, …` | same |
| `buddyx_section_title_over_overlay` | `.buddyx-section-title-over.has-featured-image.has-featured-image .post-thumbnail:after` | Only when `single_post_title_layout=buddyx-section-title-over` AND post has featured image; CSS class used heavily in `assets/css/content.css` |

No fix required for any of these — they correctly target real classes that the theme renders under the right conditions.

## Final verdict

After the 8 transport flips and the 3-mode sweep:

| Failure mode | Found | Status |
|---|---:|---|
| Silent breakage (postMessage + no output + not in allowlist) | 8 | **FIXED** (`site_loader_type` + 7 WP Login fields → refresh) |
| Missing JS registration | 1 | by design (plugin-gated) |
| Missing JS handler entry | 0 | clean |
| Zero-match selectors | 8 | all conditional, not bugs |
| Actual broken wiring | **0** | clean |

Every Customizer control is now properly wired — postMessage settings live-patch correctly, refresh settings reload the preview, plugin-gated settings register correctly when their plugin is active. The previously-broken Site Loader animation type and the entire WP Login section now correctly reload the preview on change.

## Reference

- Live-preview JS: `inc/Customizer_Framework/assets/customizer-preview.js`
- Outputs payload registration: `inc/Customizer_Framework/Component.php :: enqueue_preview()` (line ~224, emits `window.buddyxCustomizerOutputs`)
- Allowlist filter: `inc/Customizer/Component.php :: filter_dynamic_preview_setting_args()` (lines 57-127)
- Audit method: programmatic AST-style scan of every `Field::add()` block + live cross-check against `wp.customize()` + DOM selector validation via Playwright MCP.
