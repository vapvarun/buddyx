# BuddyPress UX Audit — Premium Bar (BuddyX 5.1.x → 5.2.0 candidate)

**Date:** 2026-05-15
**Scope:** **Every visible BuddyPress screen** in BuddyX (directories, member profile + sub-tabs, group profile + sub-tabs, activity stream, composer, comments, action bars). Regardless of which CSS file or template renders it. Both **1280px desktop and 390px mobile**.
**Method:** `ux-audit` skill — `templates/ux-audit.sh` static scan against `buddypress.css` + Playwright-driven browser measurement of computed tap targets, focus states, border-radius, shadow, background, padding, font-size on every interactive element across each screen.
**Bar:** **100% premium UX, inbuilt, uniform across desktop+mobile, modern not legacy.** Not "acceptable", not "passes a11y", but "this feels like a 2026 product, not a 2015 community plugin." Stakeholder restated this explicitly mid-audit.

## Headline result

**BuddyPress in BuddyX 5.1.0 today is *acceptable* on desktop, *flat* on mobile, and *not* premium on either.** Two structural problems are the root cause; everything else is downstream of them:

1. **Keyboard accessibility is broken site-wide.** 22 `outline: none` / `outline: 0` declarations in `buddypress.css` strip the focus indicator with no `:focus-visible` replacement. Browser-measured profile-nav, subnav, avatar, search, action chips, composer, and almost every other interactive element returns `focusOutline: "rgb(...) none 1.5px"` + `focusBoxShadow: "none"` → a keyboard user has **zero visible indicator** of where they are. On a community theme this is release-blocking and is the entire reason BP's reputation skews "not for serious customers." The lone exception is the member-profile avatar (which carries a proper shadow + focus ring — proving the team CAN do it; the rest of the surface just doesn't).

2. **Tap targets cluster at 34-39px — just below the 40px floor — across most action surfaces.** Activity post button (34px), activity action chips (37×59px), user actions on member profile (37px), member-profile subnav (39px), filters select (34px), and the **search submit button rendering at 6×22px** (invisible icon button on every directory) all fail Rule 13. Each individual miss is small; together they make BuddyX feel *cramped* on every BP page on desktop, and *unusable* on mobile.

After that, the legacy markers cluster: inconsistent border-radius across surfaces (50% / 8px / 6px / 4.57px / 2px / 0px — six different values where premium uses 1-3), most surfaces lacking shadow (only the member-profile avatar has one), most surfaces lacking hover state, browser-default `<select>` styling on every directory filter, generic placeholder cover/avatar imagery with no styled empty states, no card hover effects, no transition curves, no consistent gap between elements.

## What I tested vs. inferred

| Screen | Desktop 1280px | Mobile 390px | Status |
|---|---|---|---|
| `/members/` (Members directory) | ✓ probed + screenshot | ✓ probed + screenshot | data captured |
| `/groups/` (Groups directory) | inferred | ✓ probed | mobile data captured, desktop inferred (same CSS) |
| `/activity/` (Activity directory) | inferred | ✓ probed + screenshot | mobile data captured |
| `/members/<me>/` (Member profile, Activity tab) | ✓ probed + screenshot | ✓ probed + screenshot | full data |
| `/members/<me>/profile/` / `notifications/` / `messages/` / `friends/` / `groups/` / `settings/` | pattern-locked | pattern-locked | same .bp-navs / .subnav / .item-list CSS as Activity tab → same findings |
| `/groups/<slug>/` (Single group Home) | pattern-locked | pattern-locked | same cover + avatar + tabs structure as member profile → same findings |
| `/groups/<slug>/members/`, `/send-invites/`, `/manage/` | pattern-locked | pattern-locked | same subnav CSS, same item-list, same form patterns |

"Pattern-locked" = the same BP Nouveau template pack uses the same CSS selectors (`.bp-navs`, `.subnav`, `.item-list`, `.item-avatar`, `.generic-button`) on every screen. Every finding on a probed screen applies identically to every "pattern-locked" screen.

## Findings by category

### Category A — Standards violations (must fix, deterministic, no judgement call)

| # | Issue | Where | Browser-verified | Severity |
|---|---|---|---|---|
| A1 | 22 `outline:none/0` in `buddypress.css` with no `:focus-visible` replacement → zero visible focus indicator on profile nav, subnav, search input, filters select, action chips, composer, group actions, user actions, send/submit buttons | `assets/css/src/buddypress.css` lines 129, 225, 308, 322, 3604, 3676, 4443, 4457, 4903, 5916, 5967 (source) + 11 mirrored in built file | yes — `focusOutline: "rgb(...) none 1.5px"` + `focusBoxShadow: "none"` confirmed on profile nav + subnav + avatar + search + filters select + submit | **block** (a11y) |
| A2 | Search submit button renders at **6×22px** absolute-positioned on every BP directory | members/groups/activity dir form | yes | **block** (Rule 13 tap target, ~85% below floor) |
| A3 | Filters `<select>` renders at **34px** native-styled on every BP directory | members/groups/activity dir | yes | block (Rule 13, 6px below floor) + UX-quality (legacy native select) |
| A4 | Activity action chips (Like / Comment / Trash) render at **37×59px** | activity item meta | yes | block (Rule 13, 3px below floor) |
| A5 | Activity post button renders at **34×124px** | activity composer | yes | block (Rule 13, 6px below floor) |
| A6 | User-action buttons (Send Message / Add Friend / etc.) render at **37px** on member profile header | member profile | yes | block (Rule 13) |
| A7 | Subnav links render at **39px** on member profile (Personal / Mentions / Favorites / Friends / Groups) | member profile subnav | yes | block (Rule 13, 1px below floor) |
| A8 | Sidebar collapses to `h: 0` with `display: block` on mobile with no view-sidebar affordance — sidebar widgets just disappear, no "see filters" / "see widgets" toggle | every BP screen below 1024px | yes | block (responsive UX, mobile users lose access to filters/widgets) |

### Category B — UX-quality / legacy markers (modern-not-legacy bar)

| # | Issue | Premium baseline | Current state |
|---|---|---|---|
| B1 | **Border-radius inconsistency** — 6 different values across BP surfaces (50% avatar / 8px activity item / 6px composer / 4.57px action chips / 2px member-card+filters+search-input+textarea / 0px many) | One tokenized scale: `--bx-radius-sm: 4px`, `--bx-radius-md: 8px`, `--bx-radius-lg: 12px`, `--bx-radius-full: 9999px`. Every surface uses one of those 4. | `4.57142px` on action chips is calc()/math leakage — non-tokenized; 2px is 2015-era; 0px on member-card-inner means "no card" feel |
| B2 | **No shadows anywhere except member-profile avatar.** Member cards, group cards, activity items, subnav, action chips, buttons all have `box-shadow: none` | Subtle elevation token system: `--bx-shadow-sm: 0 1px 2px rgb(0 0 0 / 0.04)`, `--bx-shadow-md: 0 2px 8px rgb(0 0 0 / 0.06)`. Cards get sm; hovered/active cards get md | Flat-on-page treatment. Lacks the lifted feel modern community apps have. |
| B3 | **No hover state visible in CSS.** No `transition: transform`, `transition: box-shadow`, etc. on member/group cards | Cards lift on hover (`transform: translateY(-2px)`, shadow upgrade sm→md, transition 200ms cubic-bezier). Action chips brighten bg + grow text-color. Tabs underline-slide | No interactive feedback on hover — feels static |
| B4 | **Browser-default `<select>` styling on every directory filter** (Last Active, Newest, Alphabetical, etc.) | Custom-styled select with chevron icon, matching input padding/border/radius, matching focus ring | Native select on every directory — looks like a 2010 admin form |
| B5 | **Composer textarea: plain `<textarea>` with thin border, no inline media tools, no formatting affordances, no character counter** | Modern composer: rounded container with inline media buttons (image / GIF / poll), placeholder microcopy with personality, optional character counter, post button shown only when text entered | "What's new, varundubey?" placeholder + a flat post button below |
| B6 | **Avatar 126×126 on mobile inside 195w card** = avatar nearly fills card width; cramped layout | Mobile card: single-column or 2-col with 48-64px avatar on left, name+meta+action on right (horizontal card pattern) | Carry-over from desktop grid with no responsive tuning |
| B7 | **Cover image placeholder is flat gray (#c5c5c5)** with no styled empty state | Subtle gradient + helpful "Upload a cover image" CTA when empty for own profile; just gradient/blur for others | Stock placeholder, "broken default" feel |
| B8 | **Generic group avatar (when no avatar set) is a 2010-era SVG placeholder** | Initials-based avatar (e.g. "TG" for "Test Group") or a colored circle with single letter — modern social pattern | Old BP "two-figures" silhouette |
| B9 | **No empty states.** Members dir with 1 member shows 1 card + 7 columns of empty space + "Viewing 1 active member" microcopy. Activity stream with 0 posts probably shows nothing or "No updates to display" plain text. | Styled empty state on every list with icon + heading + body copy + CTA: "No members yet. Invite your team to join." | Pre-2015 "list is short, oh well" treatment |
| B10 | **Sub-header bar uses big serif h1 + breadcrumbs on the right** — better than nothing but feels disconnected from the rest of the page | Either: (a) integrated page-header inside the white content card with title + actions + breadcrumbs aligned, OR (b) hero-style header with title + microcopy + primary action prominent | Sub-header bar pulls visual weight away from content |
| B11 | **Activity card headers ("Activity" / "Groups") look like form section labels** (bold sans-serif, plain) | Either drop the label (the icons + structure carry meaning) or render as small uppercase chip with brand-color underline | Section-label feel breaks the "social card" mental model |
| B12 | **No loading states / skeleton screens.** When activity loads more, BP shows a 2010-style spinner | Skeleton screens (gray bars at avatar + text positions) before content loads | "wait, did it break?" UX |
| B13 | **No card hover lift / press feedback on avatars or member tiles** | Click feedback: brief scale or shadow pop on press; hover lift on desktop | Static feel |
| B14 | **Action chip count (`0` next to comment icon) is always shown, even when count is 0** | Hide the count when 0, OR show the count in a subtle color that fades when 0 | Visual noise when no engagement |

### Category C — Responsive / mobile-specific

| # | Issue | Current | Premium |
|---|---|---|---|
| C1 | Member card avatar 126×126 inside 195w 2-col grid on 390px viewport | Avatar too dominant; card width barely > avatar width; name + microcopy cramped below | Single-column on mobile, horizontal card: 56px avatar left, name+meta+action right |
| C2 | Cover image 300px tall × full viewport width on mobile (~80% of fold) | First-paint of member profile is mostly gray cover with no content visible | Reduce to ~160-200px on mobile; reveal name+avatar+actions above the fold |
| C3 | Sidebar collapsed to `h:0` on mobile with no access path | Sidebar widgets (which contain filters, widgets, etc.) disappear entirely below 1024px | Provide a "Filters" / "View sidebar" sheet/drawer on mobile with the sidebar widgets inside |
| C4 | Header logo `BuddyX` (serif) + admin-bar + WP top bar + theme nav = 4 stacked bars consume ~150px of fold on mobile | Most of the first viewport is chrome, not content | Either: (a) consolidate logo + nav into a single 56px sticky bar with hamburger, or (b) auto-hide the second bar on scroll-down |
| C5 | Vertical nav (Customizer toggle) — when enabled, the `.bp-navs.vertical` and `.bp-priority-object-nav-nav-items` rules added this cycle stack correctly per static rule inventory, but on mobile the vertical nav still consumes substantial width because there's no automatic horizontal-on-mobile fallback | Vertical nav switches back to horizontal pill-bar on mobile automatically | Forces user to scroll past entire vertical nav before seeing content on mobile |

### Category D — Inferred from pattern lock (not directly tested this pass)

All identical structurally to a tested page. **Listed for completeness — every finding above applies.**

- `/members/<me>/profile/` (xprofile) — uses `.bp-tables-user`, `.profile-fields` table → likely worse: BP defaults table-with-borders styling, no breathing room.
- `/members/<me>/messages/` — composer / threads list; same plain-textarea problem as composer.
- `/members/<me>/notifications/` — list-based, same item-list pattern.
- `/members/<me>/settings/` — form-heavy. Inputs share the search-input styling (38px h, no focus ring).
- `/members/<me>/friends/`, `/groups/` (member's groups) — same item-list patterns as directories.
- `/groups/<slug>/` (Home) — same cover + avatar + tabs as member profile.
- `/groups/<slug>/members/` — same item-list as members directory.
- `/groups/<slug>/send-invites/` — uses `.bp-invites-nav` subnav (added inline-block fix this cycle, line 476 of `buddypress.css`); same focus-ring problem.
- `/groups/<slug>/manage/` — form-heavy admin surface.

### Category E — Already in flight (not new findings)

- Dark mode coverage — Site Skin Phase 4 wired `var(--bx-color-*)` tokens through BP CSS; visual confirmation deferred to cleanup PR's verification step. Tokens carry but **the focus-ring + tap-target findings are token-independent so they manifest identically in dark mode**.
- Vertical nav rules — `.bp-navs.vertical` (lines 430-431), `.bp-dir-vert-nav` (476), `.bp-invites-nav` (subnav) — added this cycle, browser-verified in the customizer sweep. Same focus-ring problem applies to vertical mode (same `.bp-navs li a` selector).

## Visual evidence

Screenshots saved at `plans/audit-evidence/`:

- `bp-audit-desktop-1280-members-dir.png` — Members directory desktop. Sub-header + tab + search/filter row + single member tile + 80% empty grid right of it. *Looks decent on the styled card; falls apart on the empty space.*
- `bp-audit-desktop-1280-member-profile.png` — Member profile desktop, Activity tab. Cover + avatar + name + nav + subnav + composer. *Best-looking BP page in the theme.*
- `bp-audit-mobile-390-member-profile.png` — Same on 390px mobile. *Cover dominates; composer reasonable; nav stacks.*
- `bp-audit-mobile-390-activity.png` — Activity directory @ 390px. *Activity items look closest-to-premium of any BP surface; "Activity" / "Groups" labels still feel like form sections, not card titles.*
- `bp-audit-01-members-dir.png` — Members directory @ desktop, first capture (early run).

## Premium UX cleanup plan — prioritized

Effort scale: **S** = 1 commit, ~1-2 hours. **M** = 3-5 commits, ~half-day. **L** = ~1 day. **XL** = ~2-3 days (warrants its own plan doc).

### Phase 1 — Standards floor (S effort each, ship together as 5.1.x patch)

The "this is broken, fix immediately" tier. Apply across `buddypress.css` source + regen built.

1. **A1 — Restore focus rings** (S). Add a single `:focus-visible` rule near the top of `buddypress.css` that targets every interactive element used by BP surfaces:

   ```css
   .buddypress-wrap a:focus-visible,
   .buddypress-wrap button:focus-visible,
   .buddypress-wrap input:focus-visible,
   .buddypress-wrap select:focus-visible,
   .buddypress-wrap textarea:focus-visible,
   .buddypress-wrap [tabindex]:focus-visible {
       outline: 2px solid var(--bx-color-accent);
       outline-offset: 2px;
       border-radius: var(--bx-radius-sm, 4px);
   }
   ```

   Plus keep `outline: none` on the 22 declarations so legacy hover doesn't double-up. Browser-verify by tabbing through profile nav + subnav + composer + activity action chips.
2. **A2 — Search submit button** (S). Find the `.bp-search-form button[type=submit]` rule, replace with a styled icon button: `width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: transparent; color: var(--bx-color-fg-muted); border: 1px solid var(--bx-color-border); border-radius: var(--bx-radius-sm); position: static`. Removing the absolute-positioning is the key fix.
3. **A3 — Filters `<select>`** (S). Add a custom-styled select rule (`appearance: none; padding-right: 32px; background-image: url('data:image/svg+xml,...'); background-position: right 8px center; background-repeat: no-repeat; height: 40px; border-radius: var(--bx-radius-sm); border: 1px solid var(--bx-color-border)`). Apply to every BP directory + activity filter dropdown.
4. **A4 + A5 + A6 + A7 — Bump tap targets to 40px floor** (S). Single sweep: any rule on activity action chips / activity post button / user actions / subnav with `height` or `padding` that resolves to < 40px gets bumped to `min-height: var(--bx-tap-min, 40px)` + adjusted padding.
5. **A8 — Mobile sidebar access** (S). On mobile (< 1024px), instead of `display: none` on `#secondary`, render a "View filters / widgets" trigger that opens the sidebar as a slide-in drawer. Reuse the existing mobile-menu drawer infrastructure (`buddyx-mobile-menu`) — same focus-trap + ESC-close.

**Phase 1 total effort:** ~1 commit per item × 5 items = ~5 commits, half-day. **Ship as 5.1.1.**

### Phase 2 — Visual quality lift (M effort, ship as 5.2.0 candidate)

The "stop feeling legacy" tier. Bigger touch surface; warrants its own plan doc.

1. **B1 — Tokenize border-radius across BP** (S). Sweep `buddypress.css` source. Replace every `border-radius: 2px` (heavy use) with `var(--bx-radius-sm)`. Replace `4.57142px` chip radius with `var(--bx-radius-full)` (pill chip) or `var(--bx-radius-sm)`. Standardize on the existing global token system.
2. **B2 — Add subtle shadows** (S). On `.activity-item`, member-card inner, group-card inner, single-item-header card: `box-shadow: var(--bx-shadow-sm)`. On hover: `box-shadow: var(--bx-shadow-md)`. Token-driven so dark mode auto-flips.
3. **B3 — Card hover transitions** (S). `transition: transform 200ms ease, box-shadow 200ms ease` on member-card / group-card / activity-item. `transform: translateY(-2px)` on hover.
4. **B4 — Custom-styled `<select>`** (already covered by A3) — reuse for all BP filter selects + composer "visibility" dropdown + settings.
5. **B5 — Composer redesign** (M). Replace the plain textarea + below-the-fold post button with: rounded white container, inline avatar (top-left), textarea fills the rest, inline media buttons (paperclip / GIF / poll) along the bottom-left, post button bottom-right shown only when textarea has content. Coordinate with bp-nouveau classnames so other BP themes don't break.
6. **B6 — Mobile member/group card layout** (S). At `@media (max-width: 640px)`: switch to single-column horizontal cards (avatar left 56-64px, name+meta+action right) instead of 2-col vertical cards with 126px avatars.
7. **B7 + B8 — Empty cover + avatar treatments** (S). Cover empty state: gradient + "Add a cover image" CTA on own profile; just gradient for others. Avatar empty state: initials-based avatar generator (CSS-only, deterministic color per user-id hash) — replaces BP's "two figures" SVG.
8. **B9 — Styled empty states** (M). Add `.bp-empty` component (icon + h2 + body + CTA) to: empty members dir, empty groups dir, empty activity stream, empty notifications, empty messages. Reuse the ux-foundation empty-state pattern.
9. **B10 — Sub-header integration** (S). Either drop the global sub-header bar on BP pages (let the cover image carry it), OR mirror the in-content card pattern (title + actions inline with content card top).
10. **B11 — Activity card section labels** (S). Drop the bold "Activity" / "Groups" / etc. labels at the top of each activity item — the icon + content carry the meaning.
11. **B12 — Skeleton loaders** (M). Replace the BP loading spinner with skeleton screens (gray bars at avatar + name + body positions) using a single shared `.bp-skeleton` partial.
12. **B13 — Press feedback on tap** (S). On member-card / group-card / avatar links: `:active { transform: scale(0.98); transition: transform 50ms }`.
13. **B14 — Hide zero-count chips** (S). Activity action chip `.activity-comments-count` etc. — `display: none` when count is 0; `visibility: visible` when ≥1.

**Phase 2 total effort:** ~13 commits, ~half-week. **Ship as 5.2.0 candidate** since it's net-new feature work, not 5.1.0 release-blocker.

### Phase 3 — Responsive / mobile-specific (M effort, slot into Phase 2)

- **C1 — Mobile horizontal member/group cards** (S) — covered by B6.
- **C2 — Mobile cover image height cap** (S). On `< 640px`: `#header-cover-image { height: 160px }` instead of 300px.
- **C3 — Mobile sidebar drawer** (S) — covered by A8.
- **C4 — Mobile header bar consolidation** (M) — this is a theme-wide concern, not BP-specific. Tracked in `plans/2026-05-15-free-theme-gap-inventory.md` "Off-canvas mobile menu polish" row.
- **C5 — Vertical nav → horizontal pill on mobile** (S). At `@media (max-width: 768px)`: force `.bp-navs.vertical` ul + items back to horizontal flex layout. Eliminates the "scroll past 8 vertical nav items to see content" problem.

**Phase 3 total effort:** ~4 commits (most are S). **Slot into Phase 2.**

### Phase 4 — Cross-tier polish + dark mode + RTL verify (S effort, after Phase 1-3)

- Full visual diff at 5 viewports (390/430/768/1024/1440) across every BP screen.
- Dark-mode walk — confirm Phase 4 tokens carry the new shadow/radius/focus-ring values; tweak any per-component override if needed.
- RTL walk — confirm no `margin-left`/`right` regressions; logical properties everywhere.
- Lighthouse a11y ≥ 95 on each BP page.

## Effort summary

| Phase | What | Effort | Ship in |
|---|---|---:|---|
| 1 | Standards floor (8 fixes) | ~5 commits, half-day | 5.1.1 patch |
| 2 | Visual quality lift (13 items) | ~13 commits, half-week | 5.2.0 candidate |
| 3 | Responsive / mobile | ~4 commits | slotted into Phase 2 |
| 4 | Verify (visual / dark / RTL / Lighthouse) | ~1 day | end of 5.2.0 cleanup |

Phase 1 alone moves BuddyPress in BuddyX from "broken keyboard a11y + cramped tap targets" to "passes the standards floor". Phase 2 is what moves it from "acceptable" to "premium."

## Out of scope for this audit (followups)

- Real customer DB on staging — every finding above is on a synthetic test DB with 1 member + 1 group + 5 activity items. Empty-state findings may shift when real data populates.
- BuddyBoss / other community-plugin compat surfaces — the audit covered `buddypress.css` (the theme's BP styling) + the theme's overridden templates only. Third-party community plugins are explicitly out of scope; BuddyPress is BuddyX's primary community engine and the audit's exclusive focus.
- Notification / message threading UX (complex stateful flows beyond CSS).
- xprofile field rendering — the `.bp-tables-user` legacy table look needs its own plan doc.

## Reproducing this audit (and the Phase-1 cleanup verification)

This first pass ran on a near-empty dev install (1 member, 1 group, ~5 activities) — enough to confirm the structural CSS findings but **not** enough for a meaningful visual/density check. The Phase-1 cleanup PR's verification step + any future re-audit should seed properly first:

```bash
# Install + activate the seed toolkit
git clone https://github.com/vapvarun/buddypress-playground-cli.git wp-content/plugins/buddypress-playground-cli
wp plugin activate buddypress-playground-cli

# Seed: small_community gives 50 users / 10 groups / 200 activities (also seeds bbPress forums/topics/replies if active)
wp bp playground scenario generate small_community --clean --yes

# Verify
wp post list --post_type=bp_group --format=count    # >= 10
wp user list --role=subscriber --format=count       # >= 40
```

After seeding, re-walk the visible-screens checklist below. Empty-state findings (B9) may shift — that's intended. Standards findings (A1-A8) and most UX-quality findings (B1-B14) are content-independent and will hold.

## Reference

- Audit method preserved in skill: `~/.claude/skills/ux-audit/SKILL.md` (the static script + 3-mode browser sweep).
- BP/bbPress seed toolkit: [vapvarun/buddypress-playground-cli](https://github.com/vapvarun/buddypress-playground-cli.git) — used for reproducing this audit + downstream cleanup verification.
- Companion skill: `wbcom-kirki-to-tokens` (Pillar 2 — Tokens; Pillar 5 — Fonts; Lessons 21-23 — live preview, default-value audit, render-time truthiness).
- Free-theme gap inventory: `plans/2026-05-15-free-theme-gap-inventory.md` — overlaps on mobile header bar (C4) and off-canvas menu polish.
- Audit raw output: `plans/audit-snapshots/2026-05-15-ux-audit-raw.md` (gitignored; regenerate with `~/.claude/skills/ux-audit/templates/ux-audit.sh .`).
- Evidence screenshots: `plans/audit-evidence/bp-audit-*.png`.
