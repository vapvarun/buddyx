---
name: BuddyX 5.1.0+ plan documents index
description: Central index of all active plan documents for the BuddyX theme work. All plans live in plans/ at the theme root; this index links them by purpose.
type: reference
originSessionId: 101e1337-3bd5-4c7f-9bd1-bdb39c4962c2
---
**Where:** `plans/` at the theme root (`/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx/plans/`). Committed on the `5.1.0` branch. 16 plans on disk as of 2026-05-15 (refreshed after this session's doc consolidation moved 2 specs in from `docs/superpowers/`).

**How to use:** When resuming, open the relevant plan first. Filenames are `YYYY-MM-DD-feature.md`. Completion notes below cite the canonical buddyx 5.1.0 commit so the truth is git, not this memory — verify with `git show <sha>`.

## 5.1.0 — foundational (shipped per the skill's reference commit map)

- `2026-05-04-kirki-replacement.md` — 28-task original Kirki removal. Done (commit range `2a1b596..2751391`).
- `2026-05-04-customizer-options-audit.md` — Per-field 3-pass audit. Passes 1-3 done.
- `2026-05-04-site-loader-expansion.md` — Site Loader 5 animations. Done (`5ccedf1`).
- `2026-05-04-site-skin-tokens-color-modes.md` — Master Site Skin plan (Phases 1-5). Phases 1-2 done (`de462cc`/`7b80948`/`9d03d52`/`6fd58a6`/`90f2d2b`/`74246b5`).
- `2026-05-04-site-skin-phase-3-section-ux.md` — UX clusters reorganization. Done (`43f9a00`).
- `2026-05-04-site-skin-phase-4-stylesheet-cleanup.md` — 4 sub-phases replacing hardcoded hex with `var(--bx-color-*)`. Done (`9ead513`/`d226855`/`a2eb088`/`8640fa2`).
- `2026-05-04-typography-defaults-modernization.md` — h1-h6/menu/body modern editorial defaults. Done (`d425bf1`/`a43ffe1`).
- `2026-05-04-premium-pattern-library-v1.md` — 5.0.3 work, closed in 5.1.0 (`331ae0a`).
- `2026-05-06-token-system-architecture.md` — Token system architecture spec.
- `2026-05-07-buddyx-5.1.0-master-summary.md` — **Living index — START HERE when porting**. Section 4 = architecture pillars, 8-10 = port sequence, 7 = 10 non-obvious gotchas.
- `2026-05-07-token-taxonomy.md` — 96-token canonical taxonomy + auto-derivation rules.
- `2026-05-07-visitor-color-mode-toggle.md` — Visitor light/dark/auto cycle + FOUC prevention.
- `2026-05-07-wp-starter-content.md` — Fresh-install demo via WP starter-content API.

## 5.1.0+ feature planning (post-release candidates — added 2026-05-15)

- `2026-05-15-free-theme-gap-inventory.md` — Astra/GP/Kadence/OceanWP/Neve/Blocksy gap analysis + recommended quick-win batch (scroll-to-top, reading time, related posts, performance toggles, breadcrumb separator, footer columns, off-canvas menu controls, social share). **Custom font upload OUT of scope** for 5.1.0 — the Customizer typography stack (1,358-font picker + value-driven loader + Kirki-era BC) covers the real need; Astra ships its custom-font upload as a separate plugin too.
- `2026-05-15-per-entry-display-controls.md` — "Page Settings" panel design: per-entry sub-header / sidebar / header / footer / content-width overrides + Phase 2 transparent header capability. wp.org-review compliant via `register_post_meta()` with sanitize + auth + nonce. Composable (no template explosion).

## Deferred to 5.2.0

- `2026-05-04-color-and-font-palettes-5_2_0.md` — Brndle-style palette/font presets + advanced dark-mode tuning. Stakeholder confirmed: NOT 5.1.0 scope.

## Generated artifacts (in docs/, not plans/)

- `docs/buddyx-design-tokens.md` — public token reference for theme/plugin authors. Regenerate from `inc/Tokens/Component.php` after token changes.
- `docs/customizer-inventory-snapshot.txt` — committed customizer-field inventory. Regenerate with `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt` after any field change.
- `docs/local-ci.md` — Local CI infrastructure (pre-commit hook + manual command set; the project's private CI since wbcomdesigns/buddyx has no GitHub Actions).
- `tools/dump-customizer-inventory.py` — canonical inventory generator (no WP runtime needed).

## What's NOT here

Cross-theme architecture and migration findings (Kirki-to-tokens, Google Fonts library, live-preview audit) live in the `wbcom-kirki-to-tokens` skill at `~/.claude/skills/wbcom-kirki-to-tokens/SKILL.md`, not in this repo. That's the canonical home for findings reusable across buddyx-pro / KnowX / Reign. See [[buddyx_5_1_0_active_branch_state]].
