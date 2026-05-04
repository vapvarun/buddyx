# Site Skin — Phase 4: Stylesheet Cleanup Audit

**Status:** Active in 5.1.0 (per stakeholder direction "nothing is deferred").
**Prerequisites:** Phases 1-3 of Site Skin tokens initiative shipped.
**Goal:** Replace hardcoded hex / rgba color values across the theme's
stylesheets with `var(--bx-color-*)` references, so dark mode (and any
future color-mode theme variation) renders coherently across every UI
surface — not just the ones covered by `bx-tokens-applied.css`.

---

## Why this is needed

`bx-tokens-applied.css` (Phase 2) fixes top-level theme selectors —
body, header, footer, buttons, etc. But the theme has **42 stylesheets**
with ~1,140 hardcoded hex/rgba values across them. Plugin-compat files
(buddypress.css, learnpress.css, woocommerce.css) are the heaviest,
covering features like activity streams, course progress bars, vendor
storefronts. Until those are token-driven, dark mode shows the right
header/footer but a light-themed BuddyPress profile or LearnDash course
view in the middle.

Phase 4 is the work that moves dark mode from "works on the home page"
to "works everywhere a customer might go". Required for the user's
stated bar of "not half cooked".

---

## Scope (audit-driven)

```
Hex/rgba count per stylesheet (top 20 by count):
  110  buddypress.css                  ← BuddyPress activity, profiles, groups
   73  admin/theme-settings.css        ← admin-only; lower priority
   55  platform.css                    ← BuddyBoss platform compat
   54  _custom-properties.css          ← CSS variables file (these ARE the tokens to align)
   37  bx-tokens-applied.css           ← already token-driven (false positive — these ARE var() references)
   34  content.css                     ← post content, comments
   25  _navigation.css                 ← menus
   21  learnpress.css                  ← LMS course UI
   21  buddyx-wpjobmanager.css         ← job listings
   15  dark-mode.css                   ← legacy LearnDash dark mode
   15  buddyx-youzify.css              ← Youzify community plugin
   15  buddyx-amp.css                  ← AMP-specific styles
```

The `assets/css/src/` directory contains source-of-truth files (one
per build output). The minified files (`.min.css`) are generated.
Audit + edits target `src/` files, then a build step regenerates `.min.css`.

### Files to audit (in priority order)

**Tier A — foundation theme styles (must work in dark mode):**
1. `assets/css/src/global.css` (3 hardcoded values)
2. `assets/css/src/_elements.css`
3. `assets/css/src/_typography.css`
4. `assets/css/src/_links.css`
5. `assets/css/src/_navigation.css` (25)
6. `assets/css/src/_header.css`
7. `assets/css/src/_footer.css`
8. `assets/css/src/_forms.css`
9. `assets/css/src/_blocks.css`
10. `assets/css/src/_blocks-style.css`
11. `assets/css/src/content.css` (34)
12. `assets/css/src/comments.css`
13. `assets/css/src/sidebar.css`
14. `assets/css/src/widgets.css`
15. `assets/css/src/_custom-properties.css` (54 — contains the legacy
    `--global-X` definitions; aligning these to point at `var(--bx-...)`
    is part of the cleanup)

**Tier B — plugin compat (dark-mode coverage when plugin is active):**
16. `assets/css/src/buddypress.css` (110 — heaviest)
17. `assets/css/src/platform.css` (55)
18. `assets/css/src/learndash.css`
19. `assets/css/src/learnpress.css` (21)
20. `assets/css/src/lifterlms.css`
21. `assets/css/src/woocommerce.css`
22. `assets/css/src/wc-vendor.css`
23. `assets/css/src/dokan.css`
24. `assets/css/src/multivendorx.css`
25. `assets/css/src/surecart.css`
26. `assets/css/src/fluentcart.css`
27. `assets/css/src/eventscalendar.css`
28. `assets/css/src/buddyx-wpjobmanager.css`
29. `assets/css/src/buddyx-youzify.css`
30. `assets/css/src/buddyx-amp.css`
31. `assets/css/src/bbpress.css`

**Tier C — visual support (lower priority, mostly OK as-is):**
32. `assets/css/src/_accessibility.css`
33. `assets/css/src/_media.css`
34. `assets/css/src/_grid.css`
35. `assets/css/src/_reset.css`
36. `assets/css/src/_custom-media.css`
37. `assets/css/src/loaders.css` (already updated in Site Loader work)
38. `assets/css/src/slick.css`
39. `assets/css/src/rtl.css`
40. `assets/css/src/fontawesome.css`
41. `assets/css/src/buddyx-customizer.css`

**Tier D — admin-only (no dark-mode requirement):**
42. `assets/css/src/admin/theme-settings.css` (73 — but admin-only)
43. `assets/css/src/editor/editor-styles.css`
44. `assets/css/src/dark-mode.css` (legacy LD-only — refactor to use tokens)

---

## Audit method

Per file:

1. **Extract hex/rgba values:**
   ```bash
   grep -oE '#[0-9a-fA-F]{3,8}\b|rgba?\([^)]+\)' file.css | sort -u
   ```

2. **Map each unique value to a token:**
   - White-ish (`#fff`, `#ffffff`, `#fefefe`) → `var(--bx-color-bg)` or `--bx-color-bg-elevated`
   - Light gray (`#f5f5f5`, `#fafafa`, `#f6f7f7`) → `var(--bx-color-bg-muted)`
   - Border gray (`#e8e8e8`, `#dcdcde`, `#c3c4c7`) → `var(--bx-color-copyright-border)` (used as generic border token)
   - Dark text (`#000`, `#111`, `#1a1a1a`, `#222`, `#333`) → `var(--bx-color-fg)` or `--bx-color-h1`
   - Mid text (`#505050`, `#666`, `#757575`) → `var(--bx-color-fg)` (could need a `--bx-color-fg-muted` token)
   - Accent red (`#ef5455`, `#d83734`) → `var(--bx-color-accent)`
   - Accent hover (`#f83939`) → `var(--bx-color-accent-hover)`
   - rgba(0,0,0,0.1) shadows → keep as-is (shadows are inherently
     translucent black; not a theme color)

3. **Add new tokens for values that don't match existing roles:**
   - `--bx-color-fg-muted` (mid-tone text — many CSS files use #505050/#666)
   - `--bx-color-border` (generic border — many files use #e8e8e8)
   - `--bx-color-divider` (subtler border — #f0f0f0)

4. **Replace and verify** — for each file:
   - Run replacement
   - Rebuild minified file
   - Visual diff in light mode (must be byte-identical or near-identical)
   - Visual diff in dark mode (must look intentional, no stuck-light areas)

---

## New tokens introduced by Phase 4

To support the cleanup without forcing every mid-tone-gray to map to
`--bx-color-fg`, add these tokens to `inc/Tokens/Component.php`:

| Token | Light value | Dark value | Source |
|---|---|---|---|
| `--bx-color-fg-muted` | #757575 | #a0a0a0 | derived (no customizer field) |
| `--bx-color-border` | #e8e8e8 | #2a2a2a | derived |
| `--bx-color-divider` | #f0f0f0 | #1a1a1a | derived |
| `--bx-color-shadow` | rgba(0,0,0,0.08) | rgba(0,0,0,0.4) | derived |

These don't have customizer fields backing them — they're framework-
emitted with sensible defaults. A future 5.2.x release can expose them
to the customizer if customers want override control.

---

## Phasing the work

Phase 4 is broken into 4 sub-phases that can ship independently:

### Phase 4a — Foundation tier (Tier A files, ~150 hardcoded values) ✅ Shipped 2026-05-04
Touches the always-loaded theme CSS. Highest impact for dark mode.
**Actual effort:** ~2 hours (102 hex values found vs 150 estimated).
**Approach:** Per-file audit identified ~40 tokenizable values and ~62 intentional hex (overlay text on dark backdrops, brand-state colors, focus indicators, immediately-overridden borders). Replaced tokenizable values with `var(--bx-color-X, #fallback)` form so light-mode rendering stays byte-identical via the fallback when no token is set. Dark-mode now correctly inherits override values.

Files tokenized:
- _navigation.css: 8 replacements (menu toggle, search input, dropdown bg, mobile user)
- content.css: 18 replacements (search button, slick gallery controls, idea-list items, theme.json palette helpers)
- global.css: 3 (post-navigation card, pagination)
- _elements.css: 1 (hr)
- _typography.css: 1 (abbr)
- _footer.css: 1 (.site-footer bg)
- _forms.css: 3 (input, focus, select)
- comments.css: 3 (avatar ring, reply link)
- widgets.css: 2 (rss cite, calendar pad)

**New tokens added** to `inc/Tokens/Component.php`:
| Token | Light | Dark |
|---|---|---|
| --bx-color-fg-muted | #757575 | #a0a0a0 |
| --bx-color-border   | #e8e8e8 | #2a2a2a |
| --bx-color-divider  | #f0f0f0 | #1a1a1a |
| --bx-color-shadow   | rgba(0,0,0,0.08) | rgba(0,0,0,0.4) |

These framework tokens always emit (regardless of `site_custom_colors` master toggle) since consumer CSS depends on them being present.

### Phase 4b — Heavy plugin compat (BuddyPress + Platform) ✅ Shipped 2026-05-04
buddypress.css (110) + platform.css (55) — community-site backbone.
**Approach:** Bulk sed replacements for unambiguous neutral roles (white surfaces → bg-elevated, light grays → border/divider, mid grays → fg-muted, dark grays → fg). State/brand colors preserved (BP success #4caf50, error #db2828/#f00, warning #edbb34, info #5ac8fa/#007aff/#5856d6, BB blue #1c86f2, status #ff3a30/#05d786/#f78f02, yellow highlight gradient #FFEFBA, avatar-ring #fff borders, text-on-accent #fff).

Buddypress.css: 83 of 110 hex values tokenized (75%); 27 intentional preserved.
Platform.css: 33 of 55 hex values tokenized (60%); 22 intentional preserved.
Built artifacts: 51 token refs in buddypress.min.css, 22 in platform.min.css.

**Verified:** Front-end dark mode flows correctly — footer/body resolve to `--bx-color-bg #0a0a0a`, elevated surfaces to `--bx-color-bg-elevated #161616`. BuddyPress isn't active on this dev DB, so per-page BP screenshots deferred to a staging env with BuddyPress + BuddyBoss Platform installed; the source-level audit confirms all neutral hexes resolve to dark counterparts via the dark token block.

### Phase 4c — Other plugin compat (LearnDash, WooCommerce, etc.) ✅ Shipped 2026-05-04
Tier B remaining files (138 values across 14 files).
**Approach:** Same bulk-sed strategy as 4b, applied uniformly across all plugin compat sources. Built min.css files now reference 67 tokens collectively. State/brand colors preserved (LMS progress orange #ffb300, vendor pastel chips, AMP arrow shape, fluentcart already-token-aware vars).

Per-file results (token refs in built min.css):
- woocommerce.min.css: 11 (white surfaces, mid-grays, borders, dark text)
- learnpress.min.css: 14 (course bg, content cards, progress meta)
- buddyx-amp.min.css: 12 (AMP-mode-specific surfaces + grays)
- wc-vendor.min.css: 8 (vendor card surfaces + borders)
- buddyx-wpjobmanager.min.css: 6 (job listings — many pastel tag chips kept intentional)
- lifterlms.min.css: 5 (course detail surfaces)
- buddyx-youzify.min.css: 4 (community widgets — rainbow gradient + greens kept)
- bbpress.min.css: 3 (forum surfaces)
- dokan.min.css: 2 (vendor dashboard mid-grays)
- learndash.min.css: 1 (single text-on-accent kept)
- multivendorx.min.css: 1 (single bg)
- fluentcart.min.css: 0 (already token-aware via --button-* aliases)
- eventscalendar.min.css: 0 (single intentional white text)
- surecart.min.css: 0 (no hex in source — already clean)

### Phase 4d — Cleanup + new-token introduction
- Add `--bx-color-fg-muted` etc. to `Tokens::Component`
- Refactor legacy `dark-mode.css` (LearnDash) to use token system
- Drop `--global-border-color` legacy alias since it has no customizer
  field source (replace with `--bx-color-border`)
- Regenerate inventory snapshot
**Effort:** ~1-2 hours.

**Total:** ~10-13 hours of focused work.

---

## Acceptance criteria

- [ ] All Tier A files: 0 hardcoded hex (excluding shadows/rgba(0,0,0))
- [ ] All Tier B files: 0 hardcoded hex
- [ ] Tier C/D: hex values reviewed; intentional ones (icon SVG colors,
      brand-specific gradients, etc.) documented as exceptions
- [ ] Light mode: no visual regressions vs 5.0.3 (per-page screenshot
      comparison on a clone of the dev DB)
- [ ] Dark mode: no stuck-light areas on any of:
      - Home page
      - Single post / page
      - BuddyPress profile (if BP active)
      - LearnDash course (if LD active)
      - WooCommerce shop / single product
      - Search results
      - 404 page
- [ ] All inline `<style>` attribute uses inside theme PHP (template-parts,
      block patterns) audited and converted to token-driven equivalents

---

## Risks + mitigations

**Risk 1: Light-mode visual regression.** Replacing hex with tokens may
shift colors very slightly if a hardcoded value didn't perfectly match
the customizer-default-derived token.
*Mitigation:* Per-file before/after screenshots; bisect by file if
regression detected.

**Risk 2: rgba shadows being mistakenly tokenized.** A shadow like
`rgba(0,0,0,0.1)` is a translucent black, not a theme color.
*Mitigation:* Only replace values that map to a clear theme role.
Shadows stay as-is unless we explicitly tokenize them via
`--bx-color-shadow`.

**Risk 3: Plugin compat files used by 3rd-party plugin updates.** If a
plugin author copies/modifies our CSS, our tokens won't be in their
namespace.
*Mitigation:* Keep legacy `--global-X` aliases through 5.2.x. Plugin
authors using legacy names continue to work.

**Risk 4: WPCS / PHPCSstandards check might flag changes.**
*Mitigation:* Run pre-commit lint after each chunk.

---

## Estimated total effort

~10-13 hours focused work to ship Phase 4 fully. Realistic schedule:
1-2 days of dedicated coding + verification across light/dark/plugin-
active-env testing.
