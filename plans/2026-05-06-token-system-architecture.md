# BuddyX token system — proper implementation

**One source of truth.** Replaces three earlier overlapping plans
(tokens-cleanup-5_2_0, dynamic-template-token-coverage,
css-100-percent-token-ready). 5.2.0 milestone.

---

## Honest current-state assessment

The 5.1.0 token system is **functionally correct** (customer values reach
the page) but **architecturally half-implemented**:

| Smell | Evidence | Why it's smelly |
|---|---|---|
| Two parallel namespaces | 174 `var(--bx-color-*)` refs + 877 `var(--color-theme-*\|--global-*\|--button-*)` refs | Maintenance burden; no single source of truth |
| Customer values flow through aliases, not the new tokens | Tokens emit writes BOTH `--bx-color-accent` and `--color-theme-primary`; consumer CSS mostly reads the legacy alias | The "new" namespace is mostly cosmetic; legacy alias does the real work |
| 439 hardcoded hex in 45 source files | 81 chromatic, 58 neutral | Customer brand choice doesn't reach those rendered surfaces |
| Duplicate `:root` blocks | 5 .min.css files re-declare 3.3 KB block; "fixed" via `!important` workaround | Cascade smell; the fix is dedup, not !important |
| Two color-input paths | Customizer (theme_mods) + Style Variations (theme.json palette) don't reconcile | Variation can't override customizer; documented confusion |
| Source vs build drift | loaders.css (5-type) ≠ src/loaders.css (1-type, outdated) until today | No build-validation in CI |
| 228 stylelint errors block any source-file edit | `no-descending-specificity` × ~165 + auto-fixable × 63 | Touching any file fights the hook |
| Memory + plan-doc inaccuracies | "framework tokens emit unconditionally" — only true for 4 of 8 | Future devs/AI build on wrong assumptions |

This is the **legacy of incremental development** + **mid-major-refactor
state** (5.1.0 replaced Kirki). Not a bad codebase, but not "properly
implemented" either.

---

## Target architecture (5.2.0)

### One namespace
- `--bx-color-*` is the canonical taxonomy.
- Legacy aliases (`--color-theme-*`, `--global-*`, `--button-*`) emit as
  one-way deprecation bridges from the canonical, marked `@deprecated`
  in the token reference doc, scheduled for removal in 5.3.0.

### One `:root` declaration
- `_custom-properties.css` declares the canonical `:root` with default
  `--bx-color-*` values + the legacy alias bridges.
- Imported by `src/global.css` ONLY (and by `editor/editor-styles.css`
  for the block-editor iframe).
- All compat stylesheets (woocommerce, learndash, etc.) consume via
  `var(--bx-color-*)`. They do NOT redeclare `:root`.
- Tokens emit writes customer-saved values to `:root` via inline style
  on `buddyx-global` handle. ONE rule wins via cascade order: customer
  > theme.json variation > _custom-properties default.

### One color input flow
```
                     ┌────────────────────────────┐
                     │  Customer customizer save  │  (highest precedence)
                     │  → theme_mods              │
                     └────────────┬───────────────┘
                                  │
                                  ▼
                     ┌────────────────────────────┐
                     │  Style Variation active    │
                     │  → theme.json fragment     │
                     │     overrides palette      │
                     └────────────┬───────────────┘
                                  │
                                  ▼
                     ┌────────────────────────────┐
                     │  _custom-properties.css    │
                     │  → DEFAULT values          │  (lowest precedence)
                     └────────────────────────────┘

         Tokens/Component reads in this order, emits a SINGLE :root
         block with the resolved value. Consumer CSS reads var().
```

### Zero hardcoded brand-color leaks
- Tier A (theme primary variants like `#ef5455`, `#ee4036`) → `var(--bx-color-accent, #ef5455)`
- Tier C (neutral text/border/BG grays) → `var(--bx-color-fg-muted, ...)` etc.
- Tier B (state colors: success-green, error-red, admin-blue) → kept hardcoded with `/* state-color */` comment
- Verified by grep — only Tier B + commented-neutrals remain after migration.

### No `!important`
- Inline tokens emit declares values once at `:root` after defaults.
- No duplicate `:root` blocks in late-loading stylesheets to fight.
- No `!important` on aliases.

### Single canonical token reference
- `docs/buddyx-design-tokens.md` is the single source of truth.
- Lists every `--bx-color-*` token with: purpose, default value, dark
  default, customizer field that drives it, deprecated aliases.
- Generated from `inc/Tokens/Component.php` so docs can never drift.

---

## Execution plan

### Sequenced because each step unblocks the next

**Phase 0 — Lint debt paydown** (~1-2 days, 0 functional change)
- 228 pre-existing stylelint errors block pre-commit on source-file edits.
- Auto-fix what's automatable (~63 errors).
- Manual rule-reorder for `no-descending-specificity` (~165 errors).
- Single PR. Reviewer can verify no functional change via diff.
- After this PR, source-file edits commit cleanly.

**Phase 1 — `_custom-properties.css` canonical** (~half day)
- Add `--bx-color-*` defaults next to existing `--color-theme-*` aliases.
- Document each alias as `@deprecated since 5.3.0; use --bx-color-* instead`.
- Build & verify:
  - All 174 existing `var(--bx-color-*)` consumers resolve.
  - All 877 legacy alias consumers resolve unchanged (alias bridge).
  - PHPStan + WPCS clean.

**Phase 2 — Dedup `:root` + drop `!important`** (~half day)
- Remove `@import "_custom-properties.css"` from non-canonical sources
  (covered by tokens-cleanup-5_2_0 plan, already verified safe via LTR
  + RTL live tests).
- Drop `!important` from Tokens/Component.php aliases.
- Build, browser-verify customer color flows on home + BP + Woo + RTL.
- Per-file commits for clean rollback.

**Phase 3 — Tier A migration** (~half day)
- Replace 15 occurrences of `#ef5455`, `#ee4036`, `#f83939` with
  appropriate `var(--bx-color-* / --color-theme-*)` references.
- Each in `_custom-properties`, `loaders`, `admin/theme-settings`.
- Build, browser-verify default unchanged + customer color flows.

**Phase 4 — BuddyPress migration** (~1 day)
- 86 hex occurrences in `src/buddypress.css`.
- Apply per-tier policy: A → tokenize, B → keep with comment, C → tokenize.
- Browser-verify customer color flows into BP member directory, group
  header, profile actions. BP "online" green stays `#4caf50`.

**Phase 5 — WooCommerce + LearnPress + Platform migration** (~1 day)
- Same per-tier treatment. Smaller hardcoded surface than BP.
- Browser-verify customer color flows into Woo product cards, prices,
  cart; LP course cards; Platform community surfaces.

**Phase 6 — Smaller compats + admin + editor** (~half day)
- bbpress, fluentcart, surecart, multivendorx, lifterlms, dokan,
  eventscalendar, wc-vendor, buddyx-amp, buddyx-wpjobmanager,
  buddyx-youzify.
- `admin/theme-settings.css` — keep WP admin blue, tokenize neutrals
  only.
- `editor/editor-styles.css` — mirror frontend changes.

**Phase 7 — Customizer + Style Variations reconciliation** (~1 day)
- Tokens/Component reads from BOTH theme_mods AND active style variation
  palette.
- Documents the precedence rule (customer > variation > default).
- Tokens emit writes resolved value per variable.

**Phase 8 — Token reference doc generator** (~half day)
- Update `tools/dump-customizer-inventory.py` (or new
  `tools/dump-token-reference.py`) to emit `docs/buddyx-design-tokens.md`
  from `inc/Tokens/Component.php` arrays.
- CI integration: regenerate on each release.

**Phase 9 — Deprecation on legacy aliases** (5.3.0, not 5.2.0)
- After 5.2.0 migration is in customers' hands for one cycle.
- Add `@deprecated` console warning in dev mode for any `--color-theme-*`
  consumer found in CSS or PHP.
- 5.4.0: remove legacy alias emission. Customers who haven't migrated
  see defaults.

---

## What ships in 5.2.0

Phases 0 → 8. Approximately 6 working days for the migration + ~1 day for
verification + release prep. **Total: ~7 days.**

Out of scope for 5.2.0:
- Adding NEW tokens (e.g. `--bx-color-success`, `--bx-color-info`) — 5.3.0
- Removing legacy aliases entirely — 5.4.0
- Block-pattern hardcoded color audit — 5.3.0 (separate concern from
  CSS source migration)

---

## Risk for 3000 customer sites

- **Phase 0 (lint debt):** zero functional change. Diff-only review.
- **Phase 1 (canonical defaults):** zero functional change. Adds
  parallel `--bx-color-*` declarations alongside existing aliases.
- **Phase 2 (dedup + drop !important):** verified safe via LTR + RTL
  live tests; cascade ordering keeps customer values winning.
- **Phase 3-6 (hex → token migration):** every replacement uses fallback
  `var(--token, OLD_HEX)` so default-only sites render byte-identical.
  Customer-saved values flow correctly via Tokens emit.
- **Phase 7 (customizer + variation):** new code path; thorough browser
  testing + opt-in feature flag for the first cycle if needed.
- **Phase 8 (doc generator):** zero customer impact. Doc-only.

Each phase is independently shippable. If any phase reveals a bug in
testing, we ship the working phases and defer the broken one without
breaking the architecture for what's already shipped.

---

## Open questions for stakeholder

1. **Phase 0 scope.** Pay down all 1,974 stylelint errors? Or only the
   files we'll touch in Phase 3-6? The latter is half the work but
   leaves debt; the former is the "proper" choice.
2. **Phase 7 (customizer ↔ variation).** New behavior. Should it be
   feature-flagged for a release before becoming default?
3. **Phase 9 (legacy alias removal).** Aggressive (5.3.0) or
   conservative (5.4.0)?
4. **Migration messaging.** Release-note language for customers — how
   to communicate "no visible change for you, but the foundation under
   your colors is now solid"?

---

## Token reference (canonical, will live in docs/)

The `inc/Tokens/Component.php` arrays already define the taxonomy. The
final `docs/buddyx-design-tokens.md` will list:

```
--bx-color-accent          (customer setting: site_primary_color)
                            default: #ef5455 / dark: #ff6b6b
                            deprecated alias: --color-theme-primary
                            consumed by: 50+ selectors across foundation
--bx-color-bg              (customer setting: body_background_color)
                            default: #ffffff / dark: #0a0a0a
                            deprecated alias: --color-theme-body
--bx-color-bg-elevated     (customer setting: box_background_color)
... 35 tokens total ...
```

After Phase 8 the doc is auto-generated. Before Phase 8 it's in
`inc/Tokens/Component.php` source comments.
