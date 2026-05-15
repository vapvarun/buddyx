# BuddyX vs Free Themes — Feature-Gap Inventory

Date: 2026-05-15. Status: planning. Read-only research.

Scope: `wp-content/themes/buddyx`, branch `5.1.0`. This is a planning document.
Nothing here is implemented. Every "BuddyX status" cell below was verified
against the actual codebase (customizer fields, `inc/extra.php`, JS sources,
`inc/` components, `patterns/`, `readme.txt`).

Free themes compared: Astra, GeneratePress, Kadence, OceanWP, Neve, Blocksy —
free versions only.

---

## Quick-win gaps (small, essential — the priority)

These are the embarrassing-to-be-missing, commonly-expected features. Effort:
**S** = ~1 small commit, **M** = a few commits.

| Feature | What the free themes do | BuddyX status (verified) | Effort | Notes |
|---|---|---|---|---|
| Scroll-to-top / back-to-top button | Astra, Kadence, OceanWP, Blocksy ship a built-in floating back-to-top button with on/off, position, size, breakpoint visibility | **Missing.** Grep for `back-to-top` / `scroll-up` / `scrolltop` finds only `sticky-kit.js` (sidebar sticky) and header-shrink scroll logic. No button markup, no control. | S | Single button + tiny JS + 3 customizer fields (toggle, position, mobile visibility). High-visibility win. |
| Estimated reading time | Kadence/Blocksy expose a post-meta "reading time" element in blog layout settings | **Missing.** No `reading-time` / `read-time` / `word_count` logic anywhere in PHP. | S | Word-count helper + a `buddyx_posted_on` style template tag + 1 toggle. |
| Related posts | Astra, OceanWP, Blocksy show a related-posts block below single posts (by category/tag) | **Missing.** No `related-post` references in any template or `inc/` file. | M | Query + template part + customizer toggle/columns. Needs styling pass for grid + dark mode. |
| Social share buttons | OceanWP and Blocksy ship native single-post share icons (Astra/Kadence via companion); broadly expected as a built-in | **Missing.** No `social-share` / `sharing` markup or control. | M | Network list + template part + position control. Keep it API-free (simple share URLs), no counts. |
| Custom font upload / management | Astra, Kadence, OceanWP, Blocksy let a site owner upload their own font files (.woff2/.ttf) and use them in the typography controls — no plugin | **Missing.** BuddyX is a classic theme, so WP's native Font Library "Manage Fonts" UI (Site Editor only) is unavailable — and there is **no `add_theme_support` flag** that unlocks it; it is gated on being a block theme. `inc/Fonts/Component.php` already calls `wp_register_font_collection()`, but that only registers collections and is inert without the Site-Editor UI. No upload/management UI exists. | M | Build a small "Custom Fonts" UI (customizer section or admin page): upload -> sanitize/store -> emit `@font-face` -> feed `available_font_families()`. The 5.1.0 typography picker + value-driven loader already consume that list, so only the management layer is missing. Alternative: go "hybrid" (add `templates/index.html`) to unlock the native Font Library — but that exposes the whole Site Editor on a PHP-template theme, a bigger UX/architecture call. |
| Performance toggles (disable emojis, disable block-library CSS, remove jQuery Migrate, etc.) | Astra/GeneratePress/Blocksy expose "disable WP emojis", "remove block-library CSS", asset cleanup toggles | **Partial / mostly missing.** `Site_Performance` panel only has Google-Fonts-local + preload-fonts + flush-cache. No emoji-disable, no block-library-CSS toggle, no jQuery Migrate removal, no `dns-prefetch`/`preconnect` for other origins. | S | Add 3-4 checkboxes to the existing `Site_Performance` panel; each is a few lines of `remove_action` / dequeue. |
| Off-canvas / slide-in mobile menu polish | Neve, Blocksy, OceanWP ship a configurable off-canvas drawer (side, width, behavior) | **Partial.** BuddyX has a working mobile menu drawer (`buddyx-mobile-menu`, focus-trapped, ESC-close) but **no customizer controls** — no side/width/style options. | S | The hard part (accessible drawer) exists. Gap is just exposing 2-3 controls. |
| Breadcrumb separator / styling controls | Astra, OceanWP, Blocksy let you pick separator, home label, toggle per context | **Partial.** BuddyX has breadcrumbs (`buddyx_the_breadcrumb`, Yoast-aware, in sub-header) with a single on/off toggle (`site_breadcrumbs`). No separator, home-label, or per-context controls. | S | Add separator + home-label fields to `Sub_Header_Fields`. |
| Footer widget column count control | Astra, GeneratePress, Kadence, OceanWP let the user choose 1-4/5 footer widget columns | **Partial.** BuddyX registers exactly 4 fixed footer widget areas (`footer-1..4`) rendered in a fixed grid. No control to pick 1/2/3/4 columns. | S | One `select` in `Footer_Fields` + a body/grid class. Widget areas already exist. |
| Custom 404 page design | Kadence/Astra/OceanWP ship a styled 404 template out of the box; some let you assign a 404 page | **Already covered.** `404.php` + `template-parts/content/error-404.php` exist, and General_Fields has a "404" page selector + `buddyx_404_redirect()`. *Not a gap — listed for completeness.* | — | No action needed. |
| Per-entry display controls (disable sub-header / per-page sidebar / content width / header / footer) | Astra, GeneratePress, Kadence ship a per-entry settings panel on page/post/CPT, each overriding the global | **Already planned.** See `docs/superpowers/specs/2026-05-15-per-entry-display-controls-design.md`. BuddyX today has only a `post`-only metabox with a title-overwrite + image-layout picker. | M | Do not re-analyze — design doc exists. |
| Transparent header | Astra/Blocksy ship a transparent-header mode (global + per-entry override) | **Already planned.** Covered in the same per-entry-display-controls design doc as a per-entry + global option. | M | Do not re-analyze — design doc exists. |

---

## Larger gaps (bigger lifts — not the immediate focus)

One line each, why it's a big lift:

- **Header builder** — Astra/Kadence/Blocksy ship drag-and-drop header rows/elements. BuddyX has a fixed header layout with a handful of toggles. A builder is a multi-week architecture project, not a quick win.
- **Footer builder** — same story as the header builder; BuddyX's footer is fixed widget rows + copyright.
- **Demo / starter-sites importer** — Astra/Kadence/OceanWP ship one-click full-site demo libraries. BuddyX has WP-native `add_theme_support('starter-content')` + a small demo setup action, but not a hosted multi-demo importer. Building/hosting a demo library is a product effort.
- **Mega menu** — Kadence/Blocksy ship a built-in mega-menu builder. BuddyX uses `superfish` dropdowns only. A real mega menu needs a builder UI + nav-walker work.
- **WooCommerce niceties (off-canvas cart, quick view, infinite scroll, sale badges)** — comparable themes bundle these; BuddyX has basic Woo compat + a header cart icon. Each is its own mini-feature with styling/dark-mode passes.
- **Sticky-header presets / scroll effects** — Blocksy ships 6 sticky presets with shrink/fade effects. BuddyX has a single on/off sticky header (with a basic `has-sticky-header` scroll class). Presets are a medium-to-large styling + JS effort.

---

## Where BuddyX already matches or leads

Honest balance — these are NOT gaps:

- **Site Skin design tokens + native light/dark/auto color mode** — `inc/Tokens/`, `inc/Color_Mode_Toggle/`, `Skin_Fields`. Full token system with a real mode toggle (desktop + mobile menu render hooks). Many free themes still don't ship a true dark mode.
- **Premium Typography control** — `Typography_Fields` + the Customizer Framework's Typography control: 5-row paired layout, 9 inputs per field, `font-style` / `text-align` / `text-decoration` exposed.
- **Google Fonts library picker** — searchable full-library picker in the typography controls (5.1.0), with self-hosting when "Load Google Fonts Locally" is on.
- **Site Loader / preloader** — `buddyx_site_loader()` + General_Fields loader controls (animation type, colors, custom logo, speed, accessible loader text). This is the "preloader" feature, and it's premium-grade.
- **Sticky header** — present (`Header_Fields` toggle + `headerClass`/`headerScroll` JS). Single mode, but it exists.
- **Breadcrumbs** — present (`buddyx_the_breadcrumb`, Yoast-aware, cached, in the sub-header) with an on/off toggle.
- **Container / content width controls** — `General_Fields`: site layout, max content width, single-post content width; plus border-radius controls (global / buttons / forms).
- **Blog / archive layout options** — `Blog_Fields`: layout, image position, grid columns, posts-per-row, tags display + style, edit-link toggle, single-post title layout, image overlay color.
- **Global color palette** — editor color palette registered in `inc/Editor/Component.php`, wired to the token system.
- **Copyright editor** — `Footer_Fields` copyright textarea with `[current_year]` / `[site_title]` / `[theme_author]` shortcodes.
- **Block patterns** — 27 bundled patterns in `patterns/` (heroes, CTAs, footers, query loops, team, pricing, FAQ, social proof) + registered pattern categories.
- **Starter content** — `inc/Starter_Content/` uses the WP Starter Content API plus a demo-setup admin action.
- **RTL** — `assets/css/rtl.css` (+ min + src) shipped.
- **Accessibility** — `inc/Accessibility/`: skip-link focus fix, focus-trap in mobile menu, ESC handling; a11y-aware loader text.
- **PWA** — `inc/PWA/Component.php`.
- **Speculation Rules** — `inc/Speculation_Rules/Component.php` (modern prefetch/prerender — ahead of most free themes).
- **Custom 404** — `404.php` + `error-404.php` template part + 404 page selector.

---

## Recommended quick-win batch

The smallest, highest-value gaps — candidate first sprint, ordered:

1. **Scroll-to-top button** (S) — most visible, most "how is this missing", self-contained.
2. **Performance toggles** (S) — disable emojis, disable block-library CSS, remove jQuery Migrate, extra preconnect; all bolt onto the existing `Site_Performance` panel.
3. **Estimated reading time** (S) — word-count helper + one toggle; reuses the `buddyx_posted_on` meta pattern.
4. **Footer widget column control** (S) — one select; the 4 widget areas already exist.
5. **Breadcrumb separator / home-label controls** (S) — breadcrumbs already render; just add fields.
6. **Off-canvas mobile menu controls** (S) — drawer already works and is accessible; expose side/width/style.
7. **Related posts** (M) — needs a template part + grid styling + dark-mode pass, but high user value.
8. **Social share buttons** (M) — API-free share links + template part + position control.
9. **Custom font upload / management** (M) — slots straight onto the 5.1.0 font picker + value-driven loader; only the upload/management UI layer is missing.

Items 1-6 are all single-commit S-effort and could plausibly land in one sprint.
Items 7-9 (M) are the natural follow-up. Per-entry display controls and
transparent header are tracked separately in their own design doc.

---

## Sources

- https://techitez.org/series/wordpress-programming-masterclass/best-wordpress-themes-for-beginners/
- https://wpmet.com/kadence-vs-generatepress-vs-astra/
- https://worldpressit.com/astra-theme-vs-generatepress-vs-kadence-which-lightweight-theme-wins/
- https://oceanwp.org/oceanwp-features/
- https://flywithwp.com/blocksy-free-vs-pro/
- https://docs.themeisle.com/article/946-neve-doc
- https://www.kadencewp.com/help-center/docs/kadence-theme/how-to-add-scroll-to-top/
- https://www.kadencewp.com/help-center/docs/kadence-general/kadence-simple-share/
- https://wpastra.com/docs/add-breadcrumbs-with-astra/
- https://wpastra.com/docs/how-astra-is-tuned-for-performance-and-is-the-fastest-theme/
- https://kinsta.com/blog/progress-bar-wordpress/
- https://kripeshadwani.com/free-wordpress-themes/
