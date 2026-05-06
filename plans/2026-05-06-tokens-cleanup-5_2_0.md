# Token system house-cleanup — DEFERRED FROM 5.1.0 to 5.2.x

**Status:** **NOT SHIPPING in 5.1.0.** Cleanup verified safe via LTR + RTL
live tests, but the source-file edits trigger pre-existing stylelint debt
in `_blocks.css`, `_typography.css`, `learnpress.css`, `rtl.css`,
`woocommerce.css` (228 errors total: 165 `no-descending-specificity`
requiring manual rule reordering + 63 auto-fixable). Pre-commit hook
blocks any commit touching those files until the lint debt is paid down.

**Decision:** keep `!important` workaround in 5.1.0 (already shipping via
`74246b5`). Schedule the cleanup PLUS the lint-debt paydown as a single
5.2.x effort.

This document captures the verified-working approach + risk analysis so
the 5.2.x work can pick up where this stopped.

**Why this matters:** ~3,000 live customer sites. Cleanup that ALMOST works
is worse than current state — current state ships, customers' colors apply
correctly thanks to the `74246b5` `!important` patch. We only proceed if
the cleanup is provably safer than the patch.

---

## What this changes

### The architectural problem
The legacy `:root { … }` block (50+ CSS variables: `--color-theme-primary`,
`--global-font-family`, `--button-background-color`, all `--color-h1..h6`,
`--color-footer-*`, `--color-copyright-*`, etc., totalling ~3.3 KB) is
currently emitted from FIVE separate stylesheets:

| File | Loaded on | Has duplicate `:root`? |
|---|---|---|
| `assets/css/global.min.css` | every page | YES (canonical) |
| `assets/css/content.min.css` | every page | YES (duplicate) |
| `assets/css/woocommerce.min.css` | Woo pages | YES (duplicate) |
| `assets/css/learnpress.min.css` | LearnDash pages | YES (duplicate) |
| `assets/css/rtl.min.css` | RTL languages | YES (duplicate) |

The duplicates load AFTER the inline Tokens emit (which carries customer-
saved color values), so without `!important` they overrode the customer's
choice with the default `:root`. Today's `!important` workaround masks the
issue but the dedup is the real fix.

### The cleanup
1. **Source `@import` chain edits** — Remove `@import "_custom-properties.css"`
   from every source file EXCEPT `src/global.css` and
   `editor/editor-styles.css`. After this, `npm run build:css` produces
   .min.css files where only those two carry the legacy `:root` block.

2. **Tokens/Component.php** — Remove `!important` from every alias
   declaration in `build_token_css()` + `build_dark_block()`. With
   duplicates gone, the inline Tokens emit naturally wins via cascade
   ordering (loaded right after global.min.css; nothing later redefines).

3. **Dead code removal** — Delete `assets/js/customizer-controls.min.js` +
   `assets/js/src/customizer-controls.js`. These are Kirki dependency-
   evaluator JS files left over from before 5.1.0 replaced Kirki.
   Verified not enqueued anywhere in 5.1.0.

### Files staged (currently uncommitted)
```
M  assets/css/content.min.css       (-3,297 bytes — :root block removed)
M  assets/css/woocommerce.min.css   (-3,297 bytes)
M  assets/css/learnpress.min.css    (-3,297 bytes)
M  assets/css/rtl.min.css           (-3,297 bytes)
M  assets/css/global.min.css        (rebuild, content unchanged)
M  assets/css/loaders.min.css       (rebuild, semantic equivalent — Lightning CSS reordered properties)
M  assets/css/editor/editor-styles.min.css  (rebuild, content equivalent)
M  assets/css/src/_blocks.css       (-1 @import line)
M  assets/css/src/_elements.css     (-1 @import line)
M  assets/css/src/_links.css        (-1 @import line)
M  assets/css/src/_media.css        (-1 @import line)
M  assets/css/src/_typography.css   (-1 @import line)
M  assets/css/src/learnpress.css    (-1 @import line)
M  assets/css/src/rtl.css           (-1 @import line)
M  assets/css/src/woocommerce.css   (-1 @import line)
D  assets/js/customizer-controls.min.js
D  assets/js/src/customizer-controls.js
M  inc/Tokens/Component.php         (remove !important from 4 spots)
A  plans/2026-05-06-fse-hybrid-path-a.md  (unrelated, separate plan)
A  plans/2026-05-06-tokens-cleanup-5_1_0.md  (this file)
```

Total CSS savings: ~13.2 KB on every page that loads multiple compat
stylesheets (Woo + LD pages save more than blog-only pages).

---

## Risk analysis (per scenario)

### Scenario 1 — Frontend page with only `global.min.css` enqueued (e.g. blog)
- **Before cleanup:** content.min.css always loads alongside global.min.css.
  Both have `:root`. Cascade: global declares default → content redeclares
  default (no-op overlap) → inline tokens emit declares customer with
  `!important` → wins.
- **After cleanup:** global.min.css declares default → inline tokens emit
  declares customer (no `!important`) → wins via source-order cascade.
- **Risk:** zero. Cascade ordering is deterministic.

### Scenario 2 — WooCommerce active, customer on shop page
- **Before:** global default → content default → inline customer (`!important`)
  → woocommerce default → cascade winner: customer (because `!important`).
- **After:** global default → inline customer → no later `:root` declaration
  → cascade winner: customer.
- **Risk:** zero AS LONG AS global.min.css is enqueued before woocommerce.min.css.
  Verified in `inc/Styles/Component.php`: `buddyx-global` is registered
  before `buddyx-woocommerce`.

### Scenario 3 — LearnDash active, customer on courses archive
- Identical reasoning to Scenario 2. Verified `buddyx-global` before
  `buddyx-learnpress` in Styles/Component.php.

### Scenario 4 — RTL language site (VERIFIED SAFE)
- Initial assumption (WRONG): WP swaps `rtl.min.css` IN PLACE OF
  `global.min.css` via `wp_style_add_data(..., 'rtl', 'replace')`.
- **Actual wiring (Styles/Component.php:178):** rtl.min.css is enqueued
  via `wp_enqueue_style('buddyx-rtl', …)` ALONGSIDE buddyx-global. Both
  load in RTL mode.
- **Live test on dev site (Arabic locale active, html dir=rtl):**
  - 2 `:root` rules emitting `--color-theme-primary`: global.min.css
    (default `#ef5455`) + inline tokens (`#abc123` customer)
  - All other defaults (`--global-font-color`, `--button-background-color`)
    flow from global.min.css unchanged
  - Customer accent `#abc123` resolves correctly via cascade
- **Conclusion:** removing the duplicate `:root` from rtl.min.css is
  SAFE because global.min.css remains in scope.

### Scenario 5 — Third-party plugin loads its own stylesheet that uses `var(--color-theme-primary)` etc. on Woo/LD pages
- These plugins' stylesheets reference `var()` at element level, not
  redeclare `:root`. Cascade for `var()` consumers is "use whichever
  `:root` is in scope at runtime" — global.min.css's :root is in scope
  for the entire document. ✓
- **Risk:** zero, assuming third party stylesheets don't redeclare `:root`.

### Scenario 6 — Customer already saved theme_mod color via 5.0.x with rgba
- Stored value preserved (theme_mod is just an option). Inline tokens emit
  reads it on next page load → emits via `sanitize_color_alpha` → flows.
- **Risk:** zero (`d77f114` already preserves rgba).

### Scenario 7 — Customer explicitly never saved any colors (defaults only)
- Inline tokens emit declares ONLY framework neutral tokens
  (`--bx-color-fg-muted` etc.) — does NOT declare `--color-theme-primary`
  / `--bx-color-accent` because there's no customer value to emit.
- Cascade: global.min.css's `:root { --color-theme-primary: #ef5455 }` → wins.
- All page-pattern CSS that uses `var(--color-theme-primary)` resolves to default.
- **Risk:** zero (matches pre-cleanup behavior).

### Scenario 8 — Customer applies a Style Variation (Dark / Vibrant / Pastel etc.)
- Style Variations override `theme.json` palette (`--wp--preset--color--accent` etc.)
- Tokens emit unchanged (driven by `theme_mods`, not theme.json palette).
- Theme stylesheets mostly reference `var(--color-theme-primary)` not
  `var(--wp--preset--color--*)`.
- **Result:** Style Variation visually changes block patterns + block-styled
  elements (which use the theme.json preset slugs) but does NOT change
  customizer-driven sections (header, footer, buttons, links).
- This is a PRE-EXISTING split between customizer + variation systems,
  unchanged by this cleanup. **Out of scope here. Tracked for 5.2.x.**

### Scenario 9 — Inline tokens emit fails / is empty (PHP error)
- `wp_add_inline_style('buddyx-global', $css)` where `$css = ''`.
- No inline `:root` block → cascade falls back to global.min.css default.
- Customer sees DEFAULTS, not their saved values.
- **Risk:** equal pre/post cleanup. Tokens/Component PHPStan-clean,
  unlikely to throw.

### Scenario 10 — Site with build pipeline OUT OF SYNC with source
- Customer downloaded 5.1.0 zip → installed → built once.
- A future contributor edits `src/_typography.css` but does NOT run
  `npm run build:css` → ships outdated `.min.css`.
- After cleanup: source-side @import removal is in place. If outdated
  .min file is shipped, .min still has duplicate `:root`. `!important`
  is gone in PHP, so customer color is overridden by the duplicate.
- **Mitigation:** the build pipeline must be part of the release process.
  Verified: `npm run dist` → builds CSS + JS before zipping. ✓

---

## Pre-commit live test results (LTR + RTL)

After applying the staged cleanup + `npm run build:css`, both site modes
verified end-to-end:

| Mode | `:root` rules emitting `--color-theme-primary` | Customer `site_primary_color=#abc123` resolves? |
|---|---|---|
| LTR (en_US) | 2 — global.min.css default `#ef5455` + inline customer `#abc123` | ✓ `#abc123` |
| RTL (ar) | 2 — same (rtl.min.css enqueued alongside global, not replacing) | ✓ `#abc123` |

Initial concern about `rtl.min.css` losing `:root` after dedup was based
on a wrong assumption about `wp_style_add_data('rtl', 'replace')`
semantics. Live test confirms BuddyX uses simple conditional enqueue
that loads BOTH files in RTL mode.

## Final cleanup scope

| File | Edit | Reason |
|---|---|---|
| `src/_blocks.css` | remove @import | content.min.css dedup |
| `src/_elements.css` | remove @import | redundant in build chains |
| `src/_links.css` | remove @import | redundant |
| `src/_media.css` | remove @import | redundant (also feeds content.css) |
| `src/_typography.css` | remove @import | redundant |
| `src/woocommerce.css` | remove @import | woocommerce.min.css dedup |
| `src/learnpress.css` | remove @import | learnpress.min.css dedup |
| `src/rtl.css` | remove @import | rtl.min.css dedup (safe: enqueued alongside global, not replace) |
| `src/_blocks-style.css` | DON'T edit | only imported by global.css; pre-commit lint blocker on file |
| `src/global.css` | KEEP @import | canonical for LTR frontend |
| `editor/editor-styles.css` | KEEP @import | canonical for block-editor iframe |
| `inc/Tokens/Component.php` | drop !important | naturally cascade-wins after dedup |
| `assets/js/customizer-controls.*` | delete | orphan Kirki dependency JS |

---

## Acceptance criteria (must all pass before commit)

1. `npm run build:css` succeeds.
2. `:root` block with `--color-theme-primary` exists in EXACTLY 3 .min.css
   files: global.min.css, **rtl.min.css** (after revert), editor-styles.min.css.
3. content.min.css, woocommerce.min.css, learnpress.min.css have NO :root
   for legacy variables.
4. Browser test on dev site (LTR, no plugins) — customer
   `site_primary_color=#abc123` resolves on home + single + page.
5. Browser test on dev site (LTR + WooCommerce active) — customer color
   resolves on shop archive + product page.
6. Browser test on dev site (LTR + LearnDash active) — customer color
   resolves on courses archive.
7. **Browser test simulating RTL** — switch site language to Arabic via
   wp option update WPLANG (or visit `?lang=ar_SA`); rtl.min.css loads;
   customer `site_primary_color=#abc123` resolves; default
   `--color-theme-primary` falls back to `#ef5455` if no customer value.
8. PHPStan + WPCS clean on Tokens/Component.php.
9. Editor: visit post-editor; verify block-editor iframe still has
   `--color-theme-primary` available (editor-styles.min.css's :root
   provides it).
10. Customizer round-trip: change `site_primary_color` → save → reload →
    customer value persists. (Already verified in earlier work; revalidate
    after cleanup.)

---

## Decision points for stakeholder

1. **Proceed with the cleanup** (after the rtl.min.css revert above)?
   OR keep the `!important` workaround and address all of this in 5.2.x?
2. **`_blocks-style.css` lint hook** — block-style.css has 168 pre-existing
   stylelint specificity errors that block any commit touching the file.
   For this cleanup we don't need to touch it (only imported by global.css).
   Long-term: should `_blocks-style.css` be cleaned up in a separate
   no-functional-change commit so future edits to it don't get blocked?

---

## Test commands (for stakeholder reproduction)

```bash
# After revert + rebuild:
cd wp-content/themes/buddyx
git restore assets/css/src/rtl.css
npm run build:css
grep -l ':root{[^}]*--color-theme-primary' assets/css/*.min.css
# Expected: only global.min.css, rtl.min.css, editor/editor-styles.min.css

# Browser:
# 1. Navigate to http://buddyx.local/
# 2. document.querySelectorAll('script,link[rel=stylesheet]').forEach(s => console.log(s.href || s.id))
#    Verify global.min.css before content.min.css before woocommerce.min.css
# 3. getComputedStyle(document.documentElement).getPropertyValue('--color-theme-primary')
#    Should equal customer-saved value (#abc123 on this dev DB)
```
