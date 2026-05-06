# CSS 100% token-ready — 5.2.x

**Goal:** Every customer-brand-facing color in BuddyX's served CSS routes
through the token system, so customer customizer choices flow into every
rendered surface (theme + plugin compat + admin).

**Scope:** SOURCE files only. `assets/css/*.min.css` are auto-generated
by `npm run build:css` from `assets/css/src/*.css` + `assets/css/editor/`
+ `assets/css/admin/`. Editing min files would create drift.

---

## Audit baseline (sources only)

| Metric | Count |
|---|---|
| CSS source files | 45 |
| `var(--bx-color-*)` token refs | 174 |
| `var(--color-theme-*)` / `--global-*` / `--button-*` legacy alias refs | 877 |
| Hardcoded hex values (total) | 439 |
| Hardcoded hex values (unique) | 139 |
| Of which chromatic (have hue) | 81 |
| Of which neutral grayscale | 58 |

Customer-saved theme_mods already flow into the **1,051 var() refs** via
the Tokens emit. The **439 hardcoded hex** are the leak surface.

---

## Tiered migration policy

Not every hex should become a token — some are deliberate brand-fixed
state colors. The migration follows three tiers:

### Tier A — MUST tokenize (customer brand color)
Hex values that represent the theme's primary/accent/button/link colors.
Customer's `site_primary_color` choice MUST flow into these.

| Hex | Count | Token replacement | Files |
|---|---|---|---|
| `#ef5455` | 10× | `var(--color-theme-primary, #ef5455)` | `_custom-properties`, `loaders`, `admin/theme-settings` |
| `#ee4036` | 3× | `var(--color-link-hover, #ee4036)` | `_custom-properties` |
| `#f83939` | 2× | `var(--button-background-hover-color, #f83939)` | `_custom-properties` |

**Action:** ~15 replacements. Net effect: customer's primary-color choice
visible everywhere those values appear.

### Tier B — KEEP hardcoded (deliberate state / admin brand color)
State colors that carry semantic meaning across all installs and aren't
customer-customizable. Document with a comment.

| Hex | Use | Files |
|---|---|---|
| `#4caf50` | BP "online now" / success-green state | `buddypress`, `buddyx-youzify` |
| `#27ae60` | Generic success green | `_custom-properties` |
| `#c0392b` | Brand red (legacy) | `_custom-properties` |
| `#0957d0` | WP admin primary blue | `admin/theme-settings` ONLY |
| `#2271b1` | WP admin focus blue | `_accessibility`, `buddyx-customizer` |
| `#4cd964`, `#5ac8fa`, `#007aff`, `#5856d6`, `#ff2d55` | iOS-style status palette in BP profile/messaging | `buddypress`, `buddyx-youzify` |
| `#48bf1e`, `#e4f6dd` | Job Manager status color | `buddyx-wpjobmanager` |
| `#f1c40f`, `#2980b9`, `#1c2833`, `#95a5a6`, `#ecf0f1` | Legacy "flat color" palette already exposed as `--color-theme-yellow/blue/black/grey/white` | `_custom-properties` |

**Action:** add `/* state color: <reason> */` comment beside each. No
value change. ~25 distinct values, ~50 occurrences total.

### Tier C — Should tokenize (neutral surfaces / muted text)
Neutral grays / off-whites used for borders, dividers, muted text.
Customer customizer fields exist (`site_borders`, `secondary_background`)
that should drive these but currently don't.

| Hex | Token replacement | Files |
|---|---|---|
| `#a3a5a9`, `#939597`, `#565a62` | `var(--bx-color-fg-muted, ...)` | BP, platform, navigation |
| `#fbfdff`, `#f9fbff`, `#f7f7f9` | `var(--bx-color-bg-muted, ...)` | BP, bbPress, wc-vendor |
| `#e2e8f0`, `#e6e4e4`, `#e7e9ec`, `#ededed` | `var(--bx-color-border, ...)` | BP, admin |
| Pure-grayscale `#fff`/`#f5f5f5`/`#fafafa` body BGs | `var(--bx-color-bg-elevated, ...)` | BP, content, etc. |
| Pure-grayscale `#111`/`#1a1a1a`/`#222` text | `var(--bx-color-fg, ...)` | BP, content, etc. |

**Action:** ~80-100 replacements across BP / Woo / content / platform
sources. Fallback hex preserves current default for customers without
saved theme_mods.

---

## Phasing (4 phases, ~3-4 days total)

### Phase 1 — `_custom-properties.css` Tier A (~30 minutes, 0 risk)
Single source file. Replace 15 occurrences of theme-primary-related
hex (`#ef5455`, `#ee4036`, `#f83939`) with their corresponding `var()`
references. This file already declares the variables it references so
the substitution is self-contained.
- Acceptance: build, verify rendered defaults unchanged.

### Phase 2 — `buddypress.css` (~1 day)
Largest single migration. ~33 unique hex values, ~86 total occurrences.
Mix of Tier A (theme primary uses), Tier B (BP state colors — keep with
comment), Tier C (neutrals).
- Acceptance: customer with `site_primary_color=#abc123` saved sees that
  color on BP member directory, group header buttons, profile action
  buttons. BP "online" green stays `#4caf50`. No visible regression on
  defaults-only sites.

### Phase 3 — `woocommerce.css` + `learnpress.css` + `platform.css` (~1 day)
Mid-size compat surfaces. Same treatment.
- Acceptance: customer color flows into Woo product cards, prices, "Add
  to cart" button (alongside `site_buttons_background_color` if
  customer set it), into LearnPress course buttons, into Platform
  card surfaces.

### Phase 4 — Smaller compat + admin (~half day)
- `dokan.css`, `multivendorx.css`, `lifterlms.css`, `eventscalendar.css`,
  `wc-vendor.css`, `bbpress.css`, `fluentcart.css`, `surecart.css`,
  `buddyx-amp.css`, `buddyx-wpjobmanager.css`, `buddyx-youzify.css`
- `admin/theme-settings.css` — admin pages should keep WP admin blue
  (`#0957d0`) as is; no Tier A migration here, only Tier C neutrals.
- `editor/editor-styles.css` — should mirror frontend; same migration.

---

## Pre-flight: stylelint debt blocks pre-commit hook

`assets/css/src/*.css` files have **228 pre-existing stylelint errors**
(mostly `no-descending-specificity` which require manual rule reordering).
Auto-fix handles ~62 of them. The rest need either:

A. **Pay down lint debt first** (separate "lint cleanup" PR, no behavior
   change, ~half day). After that, all subsequent commits to `src/*.css`
   pass the hook cleanly.
B. **Migrate-and-clean in one PR per file**: token migration + lint
   fixes folded together. More work per PR but no separate paydown PR.

Recommend A — separates concerns and de-risks the migration.

---

## Acceptance criteria (final, after all 4 phases)

- `grep -rE '#[0-9a-fA-F]{3,8}\b' assets/css/src/ assets/css/editor/
  assets/css/admin/ | wc -l` shows **only Tier B values + neutrals
  with explicit comment justifying hardcoding**
- All Tier A hex (theme primary variants) replaced with `var()` refs
- Customer test: set `site_primary_color=#abc123`, browse:
  - home → primary color visible (already works today)
  - BP member directory → primary color visible (NEW)
  - Woo shop archive → primary color visible (already works partially;
    expand)
  - LD course archive → primary color visible (NEW)
  - admin → unchanged (admin colors stay WP-blue)
- No visible regression on default site (no theme_mods saved)
- PHPStan + WPCS clean
- All 4 phases shipped on `5.2.0` branch with per-file commits for clean
  rollback

---

## Out of scope

- Adding NEW theme tokens (e.g. `--bx-color-success`, `--bx-color-info`).
  Could be future 5.3.x but not part of this migration.
- Migrating away from legacy `--color-theme-*` aliases — those continue
  to work and customers' Tokens emit drives them. Migration to pure
  `--bx-color-*` is a separate parallel effort if desired.
- Block patterns with hardcoded inline colors (covered separately in
  `2026-05-06-dynamic-template-token-coverage.md` Phase 4).
