---
name: BuddyX 5.1.0 execution queue (in order)
description: The remaining work to ship 5.1.0, sequenced. Currently 2 items in queue; everything in code is ready, only Pass 3 staging + tag/release remain.
type: project
originSessionId: 101e1337-3bd5-4c7f-9bd1-bdb39c4962c2
---
**Why:** Stakeholder confirmed "deliver things without Kirki with 100% working options" + "no half-cooked features on a live theme" (see [[feedback_no_half_cooked_features]]). Each completed item shipped fully-cooked; remaining items must too.

**How to apply:** Resume top-down on the queue. Don't skip ahead. Pass 3 is the only blocker for tag/release.

## Queue (remaining — 2 items)

1. **Final Pass 3 regression sweep on staging** — NEXT.
   - Plan: `plans/2026-05-04-customizer-options-audit.md` Pass 3 final.
   - Blocker: needs a real 5.0.3 customer-style DB on staging — not available on the dev env.
   - Must verify: byte-identical `theme_mods` after upgrade from 5.0.3; CSS output equivalent; every type round-trips; plugin-gated sections (BuddyPress / SureCart / FluentCart) verified separately.

2. **Tag and release** — release prep done (`c22c913`); TAG GATE waiting on item 1.
   - ✅ `style.css` Version: 5.1.0
   - ✅ `readme.txt` stable tag 5.1.0 + comprehensive changelog
   - ✅ `config/config.default.json` theme.version 5.1.0
   - ✅ Built dist zip: `dist/buddyx-5.1.0.zip` (494 files, 1.94 MB), committed
   - ⏸ TAG GATE: staging Pass 3 must pass first
   - 📋 When ready: `git tag -a v5.1.0 -m "Release v5.1.0"`, then push tag + master to `vapvarun/buddyx` (NOT `wbcomdesigns/buddyx` — see [[feedback_buddyx_repos]]), and create the GitHub Release with the zip attached + release notes in the action-prefix format (Plugin X.Y.Z - one-line summary; bullets prefixed `New` / `Improve` / `Fix` / `Security` / `Dev` / `Compat`).

## Recently completed (this session, 2026-05-15)

- **Google Fonts library restoration** — Open Sans → Inter base font + bundled 1,358-family catalog + value-driven loader + grouped picker (Theme / Google) with searchable filter + off-catalog "Saved" optgroup for Kirki-era custom selections. Commits `02a6b7d`, `d87b888`, `fb8d99d`, `f2638e2`, `4aad6ac`. Browser-verified Poppins/Lato/Montserrat all continue loading with no DB migration. Cross-theme architecture preserved in Pillar 5 of the wbcom-kirki-to-tokens skill.
- **Live-preview silent-breakage fixes** — 8 settings (`site_loader_type` + 7 WP_Login_Fields fields) flipped from `'transport' => 'postMessage'` to `'refresh'`. Commit `a77004a`. Audit methodology + 3-mode sweep helper preserved as Lesson #21 + Live Preview Audit verification helper in the skill.
- **Documentation consolidation** — cross-theme findings moved into the wbcom-kirki-to-tokens skill (single canonical home); BuddyX-specific feature specs moved to `plans/` (per-entry-display-controls + free-theme-gap-inventory); `docs/superpowers/` tree removed. Commits `bcd6d59`, `5efdc63`. See [[reference_buddyx_plans_index]] for the updated plans index.

## Completed earlier in 5.1.0 cycle (per skill's reference commit map)

- **Phase 3 UX clusters**: `43f9a00` — 9 clusters with Lucide-icon heads + 3 Header sub-clusters; 45 fields preserved.
- **Typography defaults modernization**: `d425bf1` / `a43ffe1` — Newsreader for serif headings, Inter for body/menus, 17px body baseline. New installs only; saved values preserved.
- **Site Skin Phase 4 stylesheet cleanup**: `9ead513` (4a Foundation) / `d226855` (4b BuddyPress + Platform) / `a2eb088` (4c plugin compat) / `8640fa2` (4d new tokens + legacy `dark-mode.css` refactor). 180 token refs across 27 stylesheets. LD legacy `dark-mode.css` deeper refactor deferred to 5.2.x.
- **Customizer-section sweep + 7 Bugs cards**: commit range `5cfbd2b..7dbe35b` — 11-section sweep + ~20 truthy bugs fixed via `buddyx_is_truthy()` helper. Full breakdown in [[project_buddyx_5_1_0_customizer_sweep_done]].

## Risk callouts

- Pass 3 staging access is the only remaining blocker for tag/release. **Not a code blocker** — every code-side item is shipped.
- H6 default size 15px < body 17px is deliberate (bolder weight for distinction). Confirmed in the typography plan.
- BuddyX repos rule: develop on `wbcomdesigns/buddyx`; release tag + master only on `vapvarun/buddyx`. See [[feedback_buddyx_repos]].
- 5.2.x deferrals are documented and gated. The "fully cooked or not at all" rule applies — see [[feedback_no_half_cooked_features]]. Custom-font upload UI is explicitly out of scope for 5.1.0 per the gap inventory in `plans/`.
