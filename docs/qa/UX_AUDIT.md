# BuddyX — UX Audit

> **Per-template surface check.** Every view × every persona × every viewport × every color mode.
> Run this when a release touches UI, or at least once per minor version.

The goal: catch silent surface regressions (broken spacing, wrong color token, hover/focus/visited state stripped by the theme, dark-mode bleed, mobile overflow) before a customer notices.

## Axes

| Axis | Values |
|------|--------|
| **Persona** | Anonymous, BuddyPress Member, Admin |
| **Viewport** | Desktop 1440px, Tablet 1024px (spot), Mobile 390px |
| **Color mode** | Light, Dark (header toggle + `emulateMedia({ colorScheme: "dark" })`) |
| **Browser** | Chromium primary, Firefox + Safari iOS in `manual_required[]` |

Every row below × every axis combination that applies = one audit cell.
Don't re-audit identical cells across releases — audit the ones that changed or the ones flagged in the last regression guard.

---

## Visual contract (run per template)

- [ ] Primary layout renders at 1440px — no horizontal scrollbar
- [ ] At 390px — no horizontal scrollbar, no clipped text, no off-screen buttons
- [ ] Typography hierarchy intact (H1 > H2 > H3 > body) — use computed `font-size` spot check
- [ ] Spacing consistent with BuddyX design tokens (`--bx-space-*`)
- [ ] Color tokens used (no hardcoded `#ffffff` outside debug/print styles); the `--color-*` system holds
- [ ] Icons load (no broken `<img>`, no 404 on SVG sprite)
- [ ] Images `loading="lazy"` where appropriate, `alt` set on content images

## Interactive states (every `<a>`, `<button>`, input)

- [ ] **default** — visible, legible, correct color
- [ ] **hover** — discoverable change (color, bg, border, underline)
- [ ] **focus-visible** — clear focus ring, meets contrast, not suppressed by the theme
- [ ] **active** — visual feedback on click
- [ ] **disabled** — clearly distinguishable, cursor `not-allowed`
- [ ] **visited** (links only) — different from default where meaningful

**Common trap:** theme CSS overrides link states (visited especially). Always verify against the live BuddyX site, not in isolation.

## Dark mode

- [ ] Header toggle moves between Light, Dark, Auto — every state paints correctly
- [ ] No bleed of light-mode tokens (e.g. `#fff` background inside a dark container)
- [ ] Form inputs visible (borders, placeholder text) in dark
- [ ] Focus rings visible against dark bg
- [ ] Block-pattern home renders correctly in dark (the `--wp--preset--color--*` overrides from `inc/Tokens/Component.php`)
- [ ] Code blocks, callouts, badges — all have dark variants

## Accessibility (spot check)

- [ ] Tab order logical
- [ ] Skip-to-content link present and reaches `#content`
- [ ] ARIA labels on icon-only buttons (dark-mode toggle, mobile menu trigger, search)
- [ ] Form inputs have `<label>` (or `aria-label`/`aria-labelledby`)
- [ ] Color contrast ≥ 4.5:1 for body text, ≥ 3:1 for large text
- [ ] `prefers-reduced-motion` respected (no auto-play animations on the site loader)

---

## BuddyX template list

| Template | Route / Selector | Personas | Audit cells |
|----------|------------------|----------|-------------|
| `index.php` (block-pattern home) | `/` | Anonymous, Member, Admin | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `single.php` (single post) | `/<post-slug>/` | Anonymous, Member, Admin | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `page.php` (page template) | `/<page-slug>/` | Anonymous, Member, Admin | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `archive.php` (blog list) | `/blog/` or `/category/<slug>/` | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `search.php` | `/?s=test` | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `404.php` | `/this-does-not-exist/` | Anonymous | Desktop-L, Desktop-D |
| `comments.php` (comments block) | embedded in single | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L |
| `buddypress.php` (BP wrapper) | `/members/`, `/groups/`, `/activity/` | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L, Mobile-D |
| `bbpress.php` (bbPress wrapper) | `/forums/<forum>/` | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L |
| `woocommerce.php` (Woo wrapper) | `/shop/`, `/product/<slug>/`, `/cart/`, `/checkout/` | Anonymous, Member | Desktop-L, Desktop-D, Mobile-L, Mobile-D |

## Customizer surfaces

| Section | Critical controls to spot-check |
|---------|--------------------------------|
| Site Identity | Logo, logo size, title, tagline, favicon |
| Site Skin | Style preset (9 swatches must render images), color mode default, color tokens |
| Site Sub Header | Background, **Content Typography (color picker — regression guard)**, breadcrumbs toggle |
| Header | Layout, sticky, search, dark-mode toggle visibility |
| Blog | Layout (grid/list/masonry), sidebar variation, excerpt length |
| Footer | Widget columns, copyright text, social icons |
| BuddyPress | Members/groups/activity sidebar layouts, avatar style (only if BP active) |
| WooCommerce | Shop columns, single product layout (only if Woo active) |

---

## Block / pattern surfaces

BuddyX registers block patterns under `patterns/`. Each pattern should render correctly in both editor preview and front end.

| Pattern category | Sample preview check |
|------------------|----------------------|
| Hero patterns | Editor preview matches front-end render; dark mode swaps background tokens |
| Card patterns | Grid layout holds at 1440 / 1024 / 390; no clipped images |
| FAQ / testimonial patterns | Typography hierarchy intact, hover/focus states on interactive elements |

Block editor checks:

- [ ] Inspector controls render without PHP/JS errors
- [ ] Preview matches front-end render (no "frontend-only" CSS surprises)
- [ ] Block validates (no "block contains unexpected content" warning on reload)

---

## Admin surfaces

- [ ] `Appearance → Customize` loads → every BuddyX section expands without JS console errors
- [ ] `Appearance → Themes` → BuddyX card has its `screenshot.png` (no broken image), description visible
- [ ] If BuddyX adds any admin pages (Demo Installer, Help), every tab renders, every link works

---

## Email surfaces

BuddyX does not register transactional emails directly. If integrations send mail (e.g. BP notifications), spot-check their HTML for the dark-mode email client rendering.

---

## Dark mode protocol (MCP-specific)

```javascript
// Chromium - via OS-level preference
browser_evaluate({
  function: `async () => { await page.emulateMedia({ colorScheme: "dark" }) }`
})
browser_take_screenshot({ filename: "dark-<template>.png" })

// Or via BuddyX's own header toggle
browser_evaluate({
  function: `() => { document.querySelector(".bx-color-mode-toggle [data-mode='dark']")?.click(); }`
})

// Reset before exiting
browser_evaluate({
  function: `async () => { await page.emulateMedia({ colorScheme: "light" }) }`
})
```

Every dark-mode screenshot in this audit is one snapshot to attach to the PR that changed the surface.

---

## Output

If invoked as part of an agent walk, append to `manual_required[]` anything that can only be verified on Firefox or Safari iOS. The Chromium walk can cover Chrome-mode + dark-mode + viewport matrix.

If invoked as a human audit, treat each unchecked row as a blocking issue, file a Basecamp card (project 37499979, column 7430694306), and halt the release.

## Regression guard promotion

After two clean release cycles where a UX row passes without touching it, the row is stable and can be moved to a structural assertion in `AGENT_SMOKE_RUNBOOK.md`. The rest stay here as slower, human-verified surface checks.
