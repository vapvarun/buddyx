# BuddyX (Free) - Coverage Matrix

Source-of-truth audit. Each row maps a Customizer section / 5.1.0 feature to the **plan document** that captures its design rationale + implementation scope.

This matrix is intentionally **plan-centric**, not docs-centric. BuddyX free is a wp.org-distributed community theme — its customer-facing documentation runs through the wp.org help forum + the bundled `readme.txt`, not through a parallel `docs/website/` manual. The buddyx-pro repo ships such a manual; free does not. So the "where is this documented?" question for free resolves to **which plan describes this section's design**, not "which user-manual page covers it." When/if free ever adds a `docs/website/` surface, this matrix gets a second column for that.

- **Generated against:** branch `5.1.0`, commit at audit date.
- **Audit date:** 2026-05-24
- **Settings counted:** 125 fields across 18 sections (per `docs/customizer-inventory-snapshot.txt`)
- **Plans counted:** 20 in `plans/` root

## Customizer sections -> plan(s)

| Customizer section | Fields | Plan(s) | State |
|---|---:|---|---|
| `site_skin_section` | 51 | `plans/2026-05-04-site-skin-tokens-color-modes.md` + `plans/2026-05-04-site-skin-phase-3-section-ux.md` + `plans/2026-05-04-site-skin-phase-4-stylesheet-cleanup.md` + `plans/2026-05-07-visitor-color-mode-toggle.md` + `plans/2026-05-04-color-and-font-palettes-5_2_0.md` | covered |
| `site_blog_section` | 12 | no dedicated plan; section design predates 5.1.0. Block-preset dark gap (4 slugs added 2026-05-24) tracked in pro's session record. | partial — section ships, design rationale not formalized in a plan |
| `site_wp_login_logo` | 9 | no dedicated plan; legacy login customization carried over from 5.0.x | partial — no plan, ships |
| `site_sidebar_layout` | 9 | `plans/2026-05-15-per-entry-display-controls.md` (sidebar overrides per page) | covered |
| `site_loader` | 7 | `plans/2026-05-04-site-loader-expansion.md` | covered |
| `headings_typography_section` | 6 | `plans/2026-05-04-typography-defaults-modernization.md` + `plans/2026-05-07-wp-starter-content.md` (font preset library) | covered |
| `site_layout` | 5 | no dedicated plan; section design predates 5.1.0 | partial — no plan, ships |
| `site_header_section` | 5 | no dedicated plan; section design predates 5.1.0 | partial — no plan, ships |
| `site_sub_header_section` | 4 | no dedicated plan | partial — no plan, ships |
| `page_mapping` | 3 | no dedicated plan; section design predates 5.1.0 | partial — no plan, ships |
| `site_performance_section` | 3 | no dedicated plan; performance toggles predate 5.1.0 | partial — no plan, ships |
| `site_footer_section` | 2 | no dedicated plan | partial — no plan, ships |
| `site_header_primary_section` | 2 | no dedicated plan | partial — no plan, ships |
| `menu_typography_section` | 2 | `plans/2026-05-04-typography-defaults-modernization.md` | covered |
| `site_title_typography_section` | 2 | `plans/2026-05-04-typography-defaults-modernization.md` | covered |
| `body_typography_section` | 1 | `plans/2026-05-04-typography-defaults-modernization.md` | covered |
| `site_buddypress_general_section` | 1 | `plans/2026-05-15-buddypress-css-ux-audit.md` (audit-only; 5.1.x → 5.2.0 candidate) | partial — section ships, audit plan is for future work |
| `site_copyright_section` | 1 | no dedicated plan | partial — no plan, ships |

**Sections with a plan**: 8 of 18 (44%)
**Sections without a plan but shipping**: 10 of 18 (56%)
**Fields with plan coverage**: 80 of 125 (64%)

The 10 "no dedicated plan" sections are pre-5.1.0 inheritance — they shipped before the plans/ system existed and have no design-rationale document. Not a 5.1.0-cycle gap; tracked for future "backfill plans for legacy sections" work (5.2.x candidate).

## 5.1.0 features - per-feature coverage

These are the features that landed (or changed shape) in the 5.1.0 release cycle. Each one has a plan because they're net-new architecture, not inherited code.

| Feature | Plan | Shipped state |
|---|---|---|
| Kirki replacement (entire customizer infrastructure migration) | `plans/2026-05-04-kirki-replacement.md` | shipped — customizer no longer depends on Kirki; native WP customizer + custom controls; all customer values preserved through migration |
| Customizer fields audit (verification companion to Kirki replacement) | `plans/2026-05-04-customizer-options-audit.md` | shipped — every Kirki-era field round-trip-verified; drift detection automated via `tools/dump-customizer-inventory.py` |
| Site Skin Phase 3 (section UX overhaul) | `plans/2026-05-04-site-skin-phase-3-section-ux.md` | shipped — Site Skin section restructured into surface-based clusters (page / box / header / footer / etc.) |
| Site Skin Phase 4 (stylesheet cleanup audit) | `plans/2026-05-04-site-skin-phase-4-stylesheet-cleanup.md` | shipped — legacy `.bx-color-*` aliases retired; consumer CSS migrated to canonical `--bx-color-*` tokens |
| Site Skin design tokens + color modes (3-layer token architecture) | `plans/2026-05-04-site-skin-tokens-color-modes.md` | shipped — `:root` + `:root[data-bx-mode="dark"]` + `@media (prefers-color-scheme:dark)` selector trio; FOUC-free first paint |
| BuddyX token system architecture | `plans/2026-05-06-token-system-architecture.md` | shipped — single source of truth for theme colors + dimensions; legacy aliases for back-compat |
| Token taxonomy (complete variable map) | `plans/2026-05-07-token-taxonomy.md` | shipped — published reference at `docs/buddyx-design-tokens.md` |
| Visitor color-mode toggle (light / dark / auto) | `plans/2026-05-07-visitor-color-mode-toggle.md` | shipped — header / topbar / side-panel / footer placements; cookie + localStorage persistence; OS-preference auto path |
| Block-preset dark gap (block patterns flipping correctly in dark mode) | not in plans/; tracked in commit `020e34a` (2026-05-24) | shipped — 4 missing semantic preset slugs (accent / accent-2 / accent-3 / secondary) added to `$dark_defaults` |
| Typography defaults modernization | `plans/2026-05-04-typography-defaults-modernization.md` | shipped — Inter + Geist + Plex defaults; Google Fonts catalog with 1934 families; preset library |
| WP Starter Content (fresh-install demo, WP.org-compliant) | `plans/2026-05-07-wp-starter-content.md` | shipped — first-install demo pages + patterns auto-populate the customizer preview |
| Premium pattern library v1 | `plans/2026-05-04-premium-pattern-library-v1.md` | shipped (5.0.3 timeframe) — pattern surface scaffolded; populated through 5.1.0 |
| Per-entry display controls (Page Settings panel) | `plans/2026-05-15-per-entry-display-controls.md` | shipped — per-page sidebar / header / loader / color-mode overrides |
| Site loader premium UX expansion | `plans/2026-05-04-site-loader-expansion.md` | shipped — type / text / speed / logo support; per-entry override via Page Settings |
| Color & Font Palettes (Brndle-style presets) | `plans/2026-05-04-color-and-font-palettes-5_2_0.md` | **deferred to 5.2.0** — plan describes the design; implementation lands in next major |
| BuddyX vs free-themes gap inventory | `plans/2026-05-15-free-theme-gap-inventory.md` | reference document — no code change; informs roadmap |

## 5.2.x+ audit plans (future work, not in 5.1.0)

These plans describe audits / work scheduled for releases AFTER 5.1.0. Listed for index completeness.

| Plan | Scope | Target |
|---|---|---|
| `plans/2026-05-15-buddypress-css-ux-audit.md` | BuddyPress UX premium-bar audit | 5.2.0 candidate |
| `plans/2026-05-15-bbpress-css-ux-audit-plan.md` | bbPress UX audit | future |
| `plans/2026-05-15-learndash-css-ux-audit-plan.md` | LearnDash UX audit | future |
| `plans/2026-05-15-woocommerce-css-ux-audit-plan.md` | WooCommerce UX audit | future |

## Drift detection (regenerate before each release)

Use the snapshots:

- `docs/customizer-inventory-snapshot.txt` — 125 field declarations across 18 sections. Regenerate with `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt` and diff against the previous snapshot. Any new section / removed field / changed default surfaces as a line diff.

When a section's field count changes in the snapshot, update the corresponding row in this matrix (the Fields column) and confirm the state classification still holds.

## Compared to buddyx-pro

| Surface | Free | Pro |
|---|---|---|
| Customizer sections | 18 | 36 (2x) |
| Customizer fields | 125 | 275 (2.2x) |
| `docs/website/` customer manual | absent (wp.org forum + readme.txt) | present (95+ pages) |
| Plans | 20 (cross-cutting + feature) | 9 (mostly cross-cutting; per-feature work is documented in pro's docs/website/ instead) |
| COVERAGE_MATRIX scope | sections → plans (this file) | sections → docs/website pages (pro's file) |

The two matrices are structurally similar but track different artifacts because the doc surfaces differ. Cross-theme work (kirki removal, token system, color modes, etc.) is the same conceptually but lives in different files: plans/ on free, plans/ + docs/website/ on pro.

## Related

- [`../plans/README.md`](../plans/README.md) — plans lifecycle (active / archive / audit-evidence)
- [`../plans/2026-05-07-buddyx-5.1.0-master-summary.md`](../plans/2026-05-07-buddyx-5.1.0-master-summary.md) — living 5.1.0 release-cycle index
- [`buddyx-design-tokens.md`](buddyx-design-tokens.md) — published token reference (the `--bx-color-*` taxonomy)
- [`local-ci.md`](local-ci.md) — pre-commit hooks + manual command set
- [`customizer-inventory-snapshot.txt`](customizer-inventory-snapshot.txt) — committed inventory for drift detection
- [`../CLAUDE.md`](../CLAUDE.md) — working rules (the "Fully cooked or not at all" + "general theme positioning" + dev→origin / release→vapvarun rules)
