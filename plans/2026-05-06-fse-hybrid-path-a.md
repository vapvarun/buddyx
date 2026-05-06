# Path A — Hybrid blocks-in-classic conversion plan

**Status:** Draft. Ready for review; no code yet.
**Scope:** 5.2.x. Convert specific sections of `header.php` / `footer.php` to
block template parts (`parts/*.html`) that classic PHP templates render via
`block_template_part()`. WP 6.1+ supports this. Theme stays classic;
Site Editor remains disabled; Appearance → Editor → Template Parts becomes
editable for the converted sections.

---

## Hard constraints

1. **Zero customer-data loss.** Customer-saved `theme_mod` values that today
   feed `header.php`/`footer.php` (display_header_text, site_copyright_text,
   site_loader, custom logo image, custom-header image, all 8 menu/branding
   typography sub-keys, all site-wide nav menu locations, footer widgets,
   the 4 sidebar registrations, etc.) MUST continue to drive output.
2. **No regression in plugin extensibility.** Today's PHP layer fires 9
   `do_action()` hooks (`buddyx_head_top`, `buddyx_body_top`,
   `buddyx_page_top`, `buddyx_header_before`, `buddyx_header_after`,
   `buddyx_footer_before`, `buddyx_footer_after`, `buddyx_page_bottom`,
   `buddyx_body_bottom`) and the Elementor `elementor_theme_do_location`
   compat. Block template parts must run alongside, not replace, those.
3. **AMP + BuddyPress integration in `navigation.php` stays in PHP.** It's
   not migrate-able to Navigation block without losing both integrations.
   Defer indefinitely or accept a major rewrite.
4. **5.2.x is non-breaking.** Customer perception: zero visual change.
   Win is "site owners can edit header/footer parts in block editor."

---

## Convertibility inventory

Each row classifies a current code unit by conversion cost and what blocks
the conversion would unlock for site owners.

| Unit | Lines | Reads theme_mods? | Relies on AMP/BP/Elementor? | Convert? | Phase |
|---|---|---|---|---|---|
| `header.php` outer shell (`<!doctype>` … `<div id="page">`) | 33 | No (just hooks) | Elementor location guard | **No** — pure shell | — |
| `template-parts/header/custom_header.php` | 17 | No | No | **Yes** — clean | 1 |
| `template-parts/header/branding.php` | 35 | `display_header_text` (1 toggle) | No | **Maybe** — see Phase-2 strategy | 2 |
| `template-parts/header/navigation.php` | 130 | 8+ menu/typography mods | AMP, BuddyPress profile, theme switcher, mobile drawer, user menu | **No** — keep classic | — |
| `template-parts/header/buddypress-profile.php` | (BP-only) | No | BP-conditional | **No** — BP-coupled | — |
| `footer.php` outer shell (footer container, sidebar enumeration) | 27 | No | Elementor guard | **No** — pure shell | — |
| `template-parts/footer/info.php` (copyright) | 22 | `site_copyright_text` | No | **Maybe** — needs migration | 3 |
| Footer widgets enumeration (4 dynamic_sidebar calls) | 12 | No (sidebars are widget-area-driven) | No | **Maybe** — see Phase-2 strategy | 2 |
| Comments wrapper (`comments.php`) | 67 | No | No | **Yes** — clean candidate | 1 |
| Sidebar (`sidebar.php`) | 39 | `sidebar_option` toggle | No | Out of scope | — |

Convertible total: 6 sections.
Not convertible (and shouldn't try): 4 (navigation, BP profile, sidebar, the page-shell wrappers).

---

## Phase 1 — Risk-free proof-of-architecture (~3 days)

Goal: ship 2-3 parts that have **zero theme_mod dependency**, prove the
hybrid architecture is sound, get one round of customer feedback, then
escalate.

### Files added

```
parts/
├── header-image.html        (NEW — block markup)
└── comments.html            (NEW — block markup)
```

### theme.json — add to `templateParts` array

```json
{
  "area": "general",
  "name": "header-image",
  "title": "Header Image"
},
{
  "area": "general",
  "name": "comments",
  "title": "Comments"
}
```

The `comments` entry already exists — just verify it's wired.

### PHP wiring changes

**`template-parts/header/custom_header.php`** (current 17 lines):
```php
if ( ! has_header_image() ) {
    return;
}
?>
<figure class="header-image">
    <?php the_header_image_tag(); ?>
</figure>
```

Replace with:
```php
if ( ! has_header_image() ) {
    return;
}
block_template_part( 'header-image' );
```

**`parts/header-image.html`** (NEW, raw block markup):
```html
<!-- wp:template-part {"slug":"header-image","tagName":"figure","className":"header-image"} -->
<!-- wp:cover {"useFeaturedImage":false,"dimRatio":0,"isUserOverlayColor":true,"customOverlayColor":"#00000000","layout":{"type":"constrained"}} -->
<div class="wp-block-cover">
  <!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"site-header-image"} -->
  <figure class="wp-block-image size-full site-header-image"><img alt="" /></figure>
  <!-- /wp:image -->
</div>
<!-- /wp:cover -->
<!-- /wp:template-part -->
```

The image src is filled at render time by a small filter that hooks
`render_block_core/template-part` and injects `the_header_image_tag()`'s
URL — preserving the existing customer header-image flow.

**`comments.php`** (current 67 lines):
- Keep the early-return guards (`post_password_required()`, etc.)
- Replace the `<ol class="comment-list">` + `wp_list_comments()` block with
  `block_template_part( 'comments' )`
- Keep the comment form portion classic (the form has too many filter
  hooks that block-comments-form doesn't expose yet)

### Commit pattern

One commit per part for clean rollback:
- `feat(fse): add header-image block template part (Path A phase 1)`
- `feat(fse): add comments block template part (Path A phase 1)`

### Acceptance criteria

- Browser test: home page + single post + page render byte-identical to
  pre-change state with default + customer theme_mods.
- Appearance → Editor → Template Parts shows both parts and they're
  visually editable.
- `do_action('buddyx_header_after')` etc. still fire.
- 0 entries added to debug.log on any page load.

---

## Phase 2 — Medium-cost conversions (~1 week)

Pre-requisite: Phase 1 shipped, no regressions reported for 2 weeks.

### parts/branding.html

**Risk:** customer's `display_header_text=false` toggle. FSE Site Logo /
Title / Tagline blocks have their OWN visibility, not driven by the
theme_mod.

**Strategy:** read the theme_mod at part-render time. Hook
`render_block_core/template-part` for `slug==='branding'`; if
`! get_theme_mod('display_header_text', true)`, replace the rendered
output's `<h1 class="site-title">…</p class="site-description">` chunk
with empty. Preserves the toggle without migrating data.

```html
<!-- wp:group {"className":"site-branding","layout":{"type":"flex"}} -->
<div class="wp-block-group site-branding">
  <!-- wp:site-logo /-->
  <!-- wp:group {"className":"site-branding-inner","layout":{"type":"constrained"}} -->
  <div class="wp-block-group site-branding-inner">
    <!-- wp:site-title {"className":"site-title"} /-->
    <!-- wp:site-tagline {"className":"site-description"} /-->
  </div>
  <!-- /wp:group -->
</div>
<!-- /wp:group -->
```

### parts/footer-widgets.html

**Risk:** widget-area registrations are PHP-side. Block themes use the
Legacy Widget block which renders sidebars by name — that PRESERVES
customer-saved widgets in the 4 footer-1 / footer-2 / footer-3 / footer-4
sidebars.

**Strategy:** part with 4 columns, each containing a Legacy Widget block
pointing at the corresponding sidebar slug.

```html
<!-- wp:columns {"className":"footer-inner"} -->
<div class="wp-block-columns footer-inner">
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:legacy-widget {"id":"sidebar-footer-1","idBase":"...","instance":{...}} /-->
  </div>
  <!-- /wp:column -->
  ... ×4
</div>
<!-- /wp:columns -->
```

The Legacy Widget block needs runtime widget data; alternatively, use
`<!-- wp:template-part-content -->` markers and let WP render
`dynamic_sidebar('footer-1')` server-side. The exact wiring needs a
small render filter — about 30 lines.

### Acceptance criteria

- Customer-saved widgets in footer-1..4 still display in the same 4 columns.
- `display_header_text=false` correctly hides title+tagline.
- Logo upload via Customize ▸ Site Identity still works on classic theme.

---

## Phase 3 — Copyright text + Elementor / theme-mod migration (~3 days, optional)

**`parts/copyright.html`** + a one-time data migration:

- Move `site_copyright_text` from theme_mod to block content via a
  `themes_api` upgrade routine (runs once per site, idempotent).
- Customers WHO ALREADY EDITED the copyright string see their text
  preserved in the block.
- Block template part takes over the rendered output.

**This is the highest-friction Phase** because:
- The migration must roll back cleanly if Phase 3 is reverted.
- Customer who edits copyright in classic Customize ▸ Site Footer will
  have their changes overwritten by the block template part on next save
  unless we keep both paths writeable.
- Recommended: defer to 5.3+ until there's stakeholder appetite for the
  data-migration cost.

---

## Out of scope for Path A

| Section | Why excluded |
|---|---|
| `template-parts/header/navigation.php` | AMP `<amp-state>` + BP profile drawer + theme-switcher hook + user menu + WooCommerce cart icon + 18 customizer typography fields. Core Navigation block doesn't expose any of these. Conversion would require a forked Navigation block — multi-week project that doesn't fit Path A's "non-disruptive" goal. Recommend: stays classic indefinitely OR rewrites in Path C (full FSE 6.0). |
| `template-parts/header/buddypress-profile.php` | BP-conditional, not relevant to non-BP installs. Already gated; leave classic. |
| `sidebar.php` | Reads `sidebar_option` theme_mod, dynamic_sidebar enumeration. Same migration cost as footer widgets. Defer to Phase 2 batch. |
| All 30+ `single-*.php` / `archive-*.php` / `taxonomy-*.php` templates | These are Path C territory (full FSE migration). Out of scope for hybrid. |

---

## Effort & sequencing

| Phase | Days | Rollback risk | Customer-visible change |
|---|---|---|---|
| 1: header-image + comments parts | 3 | Low — both pure-WP-API | None visually; new editable in Site Editor → Template Parts |
| 2: branding + footer widgets | 5 | Medium — `display_header_text` filter is custom logic | None if filter is correct; site owners can drag/edit branding layout |
| 3: copyright migration | 3 | High — data migration | Customers see existing copyright, can now edit as block |

**Total budget: ~2 weeks across 5.2.x cycles. Could ship Phase 1 in 5.2.0,
Phase 2 in 5.2.1, Phase 3 only if stakeholder asks.**

---

## What this plan does NOT promise

- Site Editor is **not** activated — `templates/index.html` does NOT exist
  after Path A. WordPress still considers BuddyX a classic theme. Only
  template-parts editing gets unlocked.
- The WP.org "Block Theme" filter does NOT light up. Listing stays
  classic. Path C is the route to that designation.
- BP / WooCommerce / LearnDash hooks fire from the same PHP layer. Their
  templates aren't touched.
- Existing 27 patterns + 8 style variations carry through unchanged.

---

## Open questions for stakeholder

1. **Phase 3 (copyright migration) — yes/no?** It's a real data-migration
   cost. Recommend deferring unless there's a clear customer ask.
2. **Footer widgets — Legacy Widget block (preserves data) or Block-Based
   Widgets (forces customer migration)?** Recommend Legacy.
3. **Schedule:** Phase 1 in 5.2.0 alongside other 5.2 features, or its
   own 5.1.x point release?
4. **Marketing:** is "edit your header/footer in the block editor" a
   selling point worth a release-note callout, or a quiet feature?
