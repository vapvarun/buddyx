# Site Skin — Design Tokens + Color Modes

**Status:** ✅ Phases 1-4 shipped in 5.1.0 (commits `de462cc` Phase 1 / `7b80948` Phase 2 / `43f9a00` Phase 3 / `9ead513` 4a + `d226855` 4b + `a2eb088` 4c + `8640fa2` 4d, all 2026-05-04). Stakeholder direction "nothing is deferred" pulled the entire initiative from 5.2.0 into 5.1.0. Phase 5 (BuddyX Pro audit + alignment) remains in the 5.2.x backlog.
**Stakeholder ask:** "Site Skin — we have to improve it, and we have to 100%
token ready so we can add color mode support" — user, 2026-05-04.

---

## Why this matters

BuddyX 5.0.3 already emits some colors as CSS variables (`Dynamic_Style/
Component.php` maps ~30 of the 39 colors into `--color-theme-primary`,
`--color-link`, etc.). But the system is incomplete:

1. **Naming inconsistent** — variables use `--color-X`, `--global-X`,
   `--button-Y`, `--site-Z`. No coherent token namespace.
2. **Not 100% token-driven** — some theme stylesheets still hardcode hex
   values; when a customer changes a color, only the variables they
   intersect with the theme stylesheet's `var()` consumers actually update.
3. **No color mode infrastructure** — there's an `assets/css/dark-mode.css`
   file but it's loaded via a body-class `.buddyx-dark-theme` and only
   covers LearnDash dark mode, not the full theme.
4. **Site Skin section is a 46-field flat list** — no grouping by
   semantic role, no live preview swatch grid, no token reference for
   theme/plugin developers.

A proper token system unlocks:
- Theme variations via `theme.json` `styles.color` overrides without
  customizer changes
- Plug-and-play dark mode (one toggle = full theme inversion)
- Predictable extension API for plugin developers (read tokens, don't
  re-pick hex values)
- Better cross-block consistency (a custom block uses the same `--bx-color-fg`
  the theme uses)

---

## Proposed token namespace

All BuddyX-emitted CSS variables move to the `--bx-` prefix with a
two-segment naming convention: **role** + **state**.

### Role taxonomy (16 tokens)

| Token role | Purpose | Maps to today's customizer fields |
|---|---|---|
| `bg` | Page / surface backgrounds | body_background_color, content_background_color |
| `bg-elevated` | Cards / boxes | box_background_color |
| `bg-muted` | Quiet panels | secondary_background_color |
| `fg` | Body text | typography_option[color] |
| `fg-muted` | Secondary text, captions | site_footer_content_color |
| `fg-strong` | Headings (default) | h1-h6_typography_option[color] |
| `border` | Default border lines | site_copyright_border_color (today) |
| `accent` | Brand primary | site_primary_color |
| `accent-fg` | Text on accent surfaces | site_buttons_text_color |
| `link` | Hyperlink default | site_links_color |
| `link-hover` | Hyperlink hover state | site_links_focus_hover_color |
| `header-bg` | Site header surface | site_header_bg_color |
| `header-fg` | Site header text | site_title_typography_option[color] |
| `footer-bg` | Footer surface | (covered by background_setting today) |
| `footer-fg` | Footer text | site_footer_content_color |
| `loader-bg` | Loader background | site_loader_bg |

### State suffixes

- `--bx-color-{role}` — default
- `--bx-color-{role}-hover` — hover state
- `--bx-color-{role}-active` — active/pressed
- `--bx-color-{role}-focus` — focus ring
- `--bx-color-{role}-disabled` — disabled state

So the customizer's `site_buttons_background_hover_color` maps to
`--bx-color-accent-hover`.

### Backward-compatibility aliases

The 5.0.3 variable names (`--color-theme-primary`, `--color-link`, etc.)
get aliased to the new tokens:

```css
:root {
  --color-theme-primary: var(--bx-color-accent);
  --color-link: var(--bx-color-link);
  --color-link-hover: var(--bx-color-link-hover);
  /* ... 30 more aliases ... */
}
```

This keeps the theme stylesheet (and any third-party CSS hooked to those
old names) working unchanged through the 5.2.0 transition.

### Pro consumers

Pro themes / plugins ingest tokens via `var(--bx-color-X)` rather than
re-emitting their own hex values. Document the tokens in
`docs/buddyx-design-tokens.md` so Pro developers have a stable reference.

---

## Color modes

Three customizer modes:

```
Section: site_skin_section (or new site_appearance_section)
Field:   site_color_mode (radio_buttonset)
  choices:
    'auto'  -> Auto (follow OS prefers-color-scheme)
    'light' -> Always light
    'dark'  -> Always dark
  default: 'auto'
```

### CSS strategy

```css
/* Light tokens (default) */
:root {
  --bx-color-bg: #ffffff;
  --bx-color-fg: #111111;
  --bx-color-fg-muted: #555555;
  --bx-color-bg-elevated: #f7f7f9;
  --bx-color-border: #e8e8e8;
  --bx-color-accent: #ef5455;
  /* ... full set ... */
}

/* Dark tokens */
:root[data-bx-mode="dark"],
[data-bx-mode="auto"] {
  /* Dark overrides applied automatically when prefers-color-scheme: dark */
}
@media (prefers-color-scheme: dark) {
  :root[data-bx-mode="auto"] {
    --bx-color-bg: #0a0a0a;
    --bx-color-fg: #f5f5f5;
    --bx-color-fg-muted: #a0a0a0;
    --bx-color-bg-elevated: #161616;
    --bx-color-border: #2a2a2a;
    --bx-color-accent: #ef5455;
    /* ... */
  }
}
:root[data-bx-mode="dark"] {
  --bx-color-bg: #0a0a0a;
  /* same dark set */
}
```

### Mode toggle script

A small inline `<script>` in `<head>` reads the saved mode from a cookie/
localStorage and applies the `data-bx-mode` attribute before render —
prevents a flash of unthemed content (FOUC) on page load.

```html
<script>
(function(){
  var m = localStorage.getItem('bx-color-mode')
       || document.documentElement.dataset.bxModeDefault
       || 'auto';
  document.documentElement.dataset.bxMode = m;
})();
</script>
```

The current value (`auto`/`light`/`dark`) is initialized from the
customizer setting via PHP, then a frontend toggle (in the menu, footer,
or a floating button) lets visitors override it.

### Existing dark-mode.css

`assets/css/dark-mode.css` (already in 5.0.3) gets refactored to use the
new tokens. Most of its hardcoded hex values become token overrides.

### LearnDash dark-mode legacy

The existing `body.buddyx-dark-theme` class (set via the
`bxtheme=dark` cookie on LD pages) gets a soft-deprecation: continues to
work in 5.2.0, removed in 5.3.0 once the new mode toggle replaces it.

---

## Site Skin section UX overhaul

### Current state (46 fields, flat with custom-html dividers)

```
[Set Custom Colors?]
─ Loader ───────────
[Site Loader Background]
─ Header ───────────
[Site Title Color]
[Site Title Hover Color]
... 7 dividers, 39 color fields, all stacked
```

### Proposed grouping (semantic clusters)

```
[Set Custom Colors?]                      [toggle]

Mode                                      [Auto · Light · Dark]
                                          ↑ NEW radio_buttonset

Brand                                     ┐
  Primary               [swatch]          │ accent / accent-hover / accent-fg
  On primary            [swatch]          │
  Hover                 [swatch]          ┘

Surfaces                                  ┐
  Page background       [swatch]          │ bg / bg-elevated / bg-muted
  Box background        [swatch]          │
  Subtle background     [swatch]          ┘

Text                                      ┐
  Body text             [swatch]          │ fg / fg-muted / fg-strong
  Muted text            [swatch]          │
  Headings              [swatch]          ┘

Links                                     ┐
  Default               [swatch]          │ link / link-hover
  Hover                 [swatch]          ┘

Header                                    ┐ header-bg / header-fg / menu-fg / menu-hover
  Background, title, menu, hover          │
                                          ┘

Buttons                                   ┐
  6 token swatches                        ┘

Footer + Copyright                        ┐
  ~10 token swatches                      ┘
```

Each cluster gets a collapsible section header (using the
existing `Custom_HTML` divider pattern) with a small descriptive
caption. Total field count drops from 46 to ~25 (consolidations) with
the saved-state migration mapping old → new.

### Visual swatch grid (new control type)

Optional 5.2.0+: a "live tokens grid" panel at the top of Site Skin
showing all current token swatches in a grid, with each swatch clickable
to expand its individual color picker. Like Tailwind's color palette
view — fast scan, fast edit.

---

## Migration (5.0.3 → 5.2.0)

### Setting ID preservation

Every existing customizer setting ID is preserved in 5.2.0. The new
token names are emitted from the same setting values:

```php
// Dynamic_Style migration: was emitting --color-theme-primary, now
// emits BOTH (token + alias) for one release cycle.
'site_primary_color' => array(
    'token' => '--bx-color-accent',       // new
    'alias' => '--color-theme-primary',   // backward-compat for 5.2.x
),
```

5.3.0 drops the alias.

### Theme stylesheet rewrite

`assets/css/global.min.css` and the theme.json `styles.color` block get
re-emitted to consume `var(--bx-color-X)` exclusively. No hex values
in shipped CSS.

### Color mode default for upgrades

Existing customers default to `light` (matches their 5.0.3 experience —
no surprise dark mode on upgrade). New installs default to `auto`.

---

## Implementation phases

### Phase 1 — Token mapping (3-4 days)
- Define the 16-role token taxonomy in code: `inc/Tokens/Component.php`
- Update `Dynamic_Style/Component.php` to emit new tokens AND aliases
- Document tokens in `docs/buddyx-design-tokens.md`
- Update `theme.json` `styles.color` to consume tokens
- Keep customizer field UI unchanged in this phase

### Phase 2 — Color mode toggle (2 days)
- Add `site_color_mode` field
- Build the inline FOUC-prevention script
- Refactor `assets/css/dark-mode.css` into the token override pattern
- Add the optional front-end mode toggle component (menu/footer/floating button)

### Phase 3 — Site Skin section UX (2-3 days)
- Reorganize fields into semantic clusters
- Add cluster headers with descriptions (Custom_HTML dividers)
- Optional: live swatch grid component

### Phase 4 — Stylesheet cleanup (3-4 days)
- Audit every `.css` file in the theme for hardcoded color values
- Replace with `var(--bx-color-X)`
- Verify with WAVE/axe that contrast stays AA at all token combinations
  in both light and dark modes

### Phase 5 — Pro coordination
- Audit BuddyX Pro's customizer-additions for color hardcoding
- Align Pro's stylesheets to consume the new tokens
- Update Pro's docs

Total: ~2 weeks of focused work.

---

## Tracking checklist (5.1.0 — phases 1-4 ✅ shipped)

### Phase 1 — Tokens ✅
- [x] `inc/Tokens/Component.php` defines token taxonomy + light defaults (4 framework + 8 simple-color groups + 11 typography color sub-keys + 3 dimension)
- [x] `Tokens::emit_tokens()` emits `--bx-color-*` plus aliases (replaces 5.0.3 Dynamic_Style emission)
- [x] `docs/buddyx-design-tokens.md` written
- [x] `theme.json` `styles.color` block uses var() / preset color references

### Phase 2 — Modes ✅
- [x] `site_color_mode` field with auto/light/dark choices in Site Skin section
- [x] Inline FOUC `<head>` script reads localStorage + customizer default
- [x] Dark token set in `inc/Tokens/Component.php` `$dark_defaults`
- [x] `bx-tokens-applied.css` maps tokens to standard theme selectors
- [ ] Optional front-end toggle component — deferred to 5.2.x (theme uses bootstrap script + JS API only; no built-in UI toggle yet)

### Phase 3 — Site Skin UX ✅
- [x] Field reorder under 9 cluster heads (commit `43f9a00`)
- [x] New cluster header markup via Custom_HTML with Lucide-style SVG icons
- [x] Inventory snapshot regenerated (45 → 51 fields in site_skin_section)

### Phase 4 — Stylesheet cleanup ✅
- [x] All .css files audited for hardcoded hex; 180 token refs across 27 stylesheets (commits `9ead513` / `d226855` / `a2eb088` / `8640fa2`)
- [ ] Contrast AA check in both modes — verified on home page; full audit deferred to staging with the plugin matrix
- [ ] Cross-browser verification (Safari/Chrome/Firefox) — deferred to staging

### Phase 5 — Pro (5.2.x backlog)
- [ ] Pro customizer additions audited
- [ ] Pro stylesheets aligned
- [ ] Pro docs updated

---

## Why we're NOT doing this in 5.1.0

5.1.0 is parity-only — Kirki removal, no Skin section changes. The token/
color-mode initiative changes:
- Customer-facing UI (new mode field, restructured section)
- Setting consolidations (39 → ~25 fields means actual theme_mod migration)
- Stylesheet rewrites that affect visual output

Mixing those changes with the Kirki removal risks conflating two
roll-back surfaces. 5.2.0 ships on top of the proven 5.1.0 framework
foundation, with its own staging cycle and customer comms.

5.1.0 ships first; 5.2.0 ships this initiative ~4-6 weeks after.
