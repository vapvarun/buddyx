# BuddyX — Per-Entry Display Controls (Page Settings panel)

**Date:** 2026-05-15
**Status:** Design / plan — for review. Not yet implemented.
**Scope:** `wp-content/themes/buddyx` (branch `5.1.0` or a follow-up branch)

## Problem

BuddyX has **no per-entry settings story for pages or CPTs**. The only per-entry
metabox (`buddyx_post_settings`) is registered for the `post` type only and
exposes just a title-overwrite checkbox + a featured-image/title layout picker.

Concrete consequences (surfaced this cycle):

- A site owner who doesn't want the **sub-header bar** on a specific page has
  no UI option. The only built-in suppression is the "Page Full Screen" page
  template — which *also* forces full-width / no-sidebar. Sub-header visibility
  is **orthogonal to layout** but can't be controlled independently.
- Sidebar layout exists only as **mutually-exclusive page templates** — it
  can't compose with other per-entry choices.
- No per-entry control for header, footer, content width, or a transparent
  header — all standard in comparable themes.

## Research — what comparable themes ship

Per-entry settings (page + post + CPT), each overriding the Customizer global:

| Control | Astra | GeneratePress | Kadence |
|---|---|---|---|
| Disable title / page-header bar | yes | yes | yes |
| Sidebar layout (as per-entry meta) | yes | yes | yes |
| Content layout (boxed / full / narrow) | yes | yes | yes |
| Disable header | yes | - | yes |
| Disable footer | yes | yes | yes |
| Transparent / sticky header override | yes | - | partial |
| Available on | page + post + CPT | page + post + CPT | page + post + CPT |

The consistent pattern: **one per-entry settings panel, on all public post
types, per-entry beats global.**

## Goals

1. A unified **"Page Settings"** panel on `page` + `post` + public CPTs.
2. Per-entry overrides take precedence over the Customizer global.
3. WordPress.org-review compliant (it ships on wordpress.org): proper meta
   registration, sanitization, capability checks, nonces; presentational
   options only (no plugin-territory functionality).
4. Composable — every control is independent of the page template / sidebar
   layout choice. No combinatorial template explosion.
5. Backward compatible — existing `buddyx_post_settings` post controls and the
   existing page templates keep working; saved values untouched.

## Non-goals

- Not replacing the existing layout page templates (they stay for back-compat).
- Not a global redesign of the Customizer.
- No content/functionality features (plugin territory) — display options only.

## Architecture

- **Storage:** `register_post_meta()` per control — `single => true`, explicit
  `type`, `sanitize_callback`, `show_in_rest => true`,
  `auth_callback => current_user_can( 'edit_post', ... )`. REST-exposed so it
  works in the block editor and is readable on the front end.
- **UI:** extend the existing classic `buddyx_post_settings` metabox pattern to
  `page` + `post` + public CPTs (or a block-editor `PluginDocumentSettingPanel`
  — decide at plan time; classic metabox is the lowest-risk match to what the
  theme already ships and already passed review with). Nonce + capability check
  + sanitized `save_post`.
- **Render:** each render site reads `get_post_meta()` and falls back to the
  Customizer global when the meta is unset (`''`/`default`). e.g.
  `buddyx_sub_header()` gains an early-return; sidebar resolution checks the
  meta before the template/Customizer value.
- **Precedence:** unset meta -> use Customizer/template default; set meta ->
  per-entry value wins.

## Controls in scope

### Phase 1 — show/hide + layout overrides (lightweight, low risk)

Each is "register a meta + gate existing rendering" — no new capability needed.

| Control | Meta key | Type / values | What it gates |
|---|---|---|---|
| Sub-header bar | `_buddyx_page_header` | enum: `default` / `show` / `hide` | early-return in `buddyx_sub_header()` |
| Sidebar layout | `_buddyx_sidebar` | enum: `default` / `left` / `right` / `none` | per-entry override of the sidebar-resolution logic |
| Disable site header | `_buddyx_disable_header` | bool | gate the `#masthead` block in `header.php` |
| Disable site footer | `_buddyx_disable_footer` | bool | gate the footer in `footer.php` |
| Content width | `_buddyx_content_width` | enum: `default` / `full` / `narrow` | body-class -> existing container CSS |

### Phase 2 — transparent header (new capability, heavier)

`can we also give transparent header?` — **yes, feasible**, but BuddyX has no
transparent-header concept today (`header.php` header is in normal document
flow; sticky mode uses `position: fixed`). This is a new *capability*, not a
toggle on top of something existing. It needs:

- **CSS mode** — a body-class (`buddyx-transparent-header`) that makes
  `.site-header-wrapper` overlay the first content block: `position: absolute`,
  full width, `background: transparent`, and the content pulled up beneath it.
- **Legibility** — logo + menu colour treatment for the transparent state
  (likely a light/dark variant choice).
- **Scroll-to-solid** — small JS so the header gains a solid background once the
  visitor scrolls past the hero (the standard pattern); must coexist cleanly
  with the existing sticky-header (`position: fixed`) logic.
- **Per-entry meta** `_buddyx_transparent_header` (enum: `default` / `on` /
  `off`) + a global Customizer default ("Transparent header on: none / front
  page / all pages") so it isn't page-by-page only.
- When transparent header is on, the sub-header is normally suppressed for that
  entry (the hero replaces it) — define this interplay explicitly.

Because Phase 2 builds a capability (not just exposes meta), it is the largest
component and is recommended as its own phase / commit series, shippable
independently of Phase 1.

## WordPress.org compliance notes

- All meta via `register_post_meta()` with sanitize + auth callbacks.
- Metabox: `wp_nonce_field()` + `check_admin_referer()`, `current_user_can()`,
  sanitized `save_post`, bail on autosave / revisions.
- Presentational options only — no content/SEO/functionality settings.
- Text-domain `buddyx`, escaping on all output, no direct file access.
- Per-entry settings override the Customizer (matches Astra/GP/Kadence and the
  Theme Review expectation that the more specific scope wins).

## Proposed phase / component breakdown (for the implementation plan)

1. **Meta foundation** — `register_post_meta()` for all Phase 1 keys + the
   shared "should this entry override?" helper + sanitizers.
2. **Page Settings metabox** — extend `buddyx_post_settings` to page + post +
   public CPTs; render the Phase 1 controls; nonce + capability + save handler.
3. **Render wiring — sub-header + content width** — `buddyx_sub_header()`
   early-return; body-class for content width.
4. **Render wiring — sidebar override** — per-entry sidebar resolution ahead of
   the template/Customizer value.
5. **Render wiring — disable header / footer** — gate `#masthead` / footer.
6. **Phase 2 — transparent header capability** — CSS mode + scroll-to-solid JS
   + per-entry meta + global Customizer default + sub-header interplay.
7. **Changelog + full browser regression** — verify each control on page /
   post / a CPT, verify Customizer-fallback when meta unset, verify composability
   with each page template.

## Open decisions for review

1. **Control set** — confirm Phase 1 = all five controls above (sub-header,
   sidebar, disable header, disable footer, content width)? Trim or add any?
2. **Transparent header phasing** — ship Phase 2 with Phase 1, or as a
   follow-up? (It is the heaviest piece.)
3. **UI surface** — classic metabox (matches existing theme pattern, lowest
   risk) vs block-editor document panel (more modern, more JS build). Recommend
   classic metabox for consistency.
4. **Branch** — continue on `5.1.0`, or a dedicated feature branch given this
   is net-new feature work rather than migration fixes.
