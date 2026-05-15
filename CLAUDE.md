# BuddyX — project-local instructions for Claude Code

This is the **wbcomdesigns/buddyx** WordPress classic theme. Active branch:
`5.1.0` — Kirki-removal release in flight; all code work shipped. Two items
remain: staging Pass 3 regression sweep (blocker: needs real 5.0.3 customer DB)
then tag-and-release.

## Working rules (read at session start, apply to every task)

### Repos: develop on wbcomdesigns, release on vapvarun

- **`wbcomdesigns/buddyx`** (private) — the development repo. All feature
  branches, bug fixes, work-in-progress. `origin` points here.
- **`vapvarun/buddyx`** (public) — release-only mirror. Customer-facing.
- Push dev branches (`5.0.x`, `5.1.0`, `feature/*`, `fix/*`) only to `origin`.
- Push to `vapvarun` only when cutting a release: `master` + version tag, then
  create the GitHub Release with the clean zip attached.
- The `readme.txt` GitHub link points to `vapvarun/buddyx` (public-facing).
- **Never** add `vapvarun` as a remote during non-release tasks. If a sandbox
  prompt asks to, decline.

### Fully cooked or not at all

- Stakeholder rule: "we can not release half cooked features on live theme."
  Every customer-facing feature in a release ships complete, OR is explicitly
  deferred with a written plan doc.
- When in doubt, finish a smaller scope completely rather than ship more
  partially.
- Test every feature in the browser end-to-end before claiming done.
  Bootstrap-presence in customizer JSON is not enough — the rendered control
  must work, the saved value must round-trip, the front end must reflect the
  saved value, the live preview must update.
- **Customer-data preservation is non-negotiable.** Every customer with a
  configured 5.0.x customizer must upgrade and see settings intact. Setting IDs,
  value shapes (string vs array vs JSON), and sanitize_callback behavior must
  all be preserved or migrated.

### Replicate, fix, never defer — and BuddyX is a GENERAL theme

- **Replicate → fix in current release → only then Ready for Testing.** If a
  card from QA can be replicated, fix it in the current release (5.1.0) before
  moving the card. Flow: read card → repro in browser → fix → browser-verify
  → post HTML comment with proof → move card. Never "ask QA to retest" without
  a fix in hand.
- **No deferrals to 5.2.0.** Whatever QA / the team raises during 5.1.0 must
  ship in 5.1.0. Even if it looks pre-existing or out of original scope.
- **BuddyX positions as a general theme**, not a BuddyPress theme. The
  `screenshot.png` re-capture (commit `63b6dd1`) intentionally shows generic
  "Preview theme patterns" visuals, NOT BuddyPress activity stream / member
  directories. When QA flags the screenshot content as "wrong", verify if
  they're objecting to positioning vs. an actual visual defect (border baked
  in, wrong page, low res). Visual defects → re-capture. Positioning
  objections → push back with the general-theme rationale.

### Bugs column: justify the verdict before coding

For any card in the BuddyX (37499979) or BuddyX Pro (37557698) **Bugs**
column during the 5.1.0 release cycle:

1. Read card + comments, check the symptom against in-flight 5.1.0 changes
   (Kirki removal, Phase 3 UX clusters, typography defaults, Phase 4 stylesheet
   cleanup, Google Fonts library restoration, live-preview fixes).
2. Decide: real bug / intended-change-misreported / stale-build artifact.
3. Post an HTML-formatted comment on the card (`mcp__basecamp__basecamp_comment`,
   using `<strong>` / `<br>` — markdown does not render). Comment must include:
   (a) one-line verdict, (b) link to the 5.1.0 change that's relevant,
   (c) if fixing, the file/area you will touch.
4. Only then write the fix.

Triage / Suggestions / Ready for Development cards are out of scope of this rule.

### Test by site-owner journey, not by setting

Matrix-by-setting sweeps prove each setting in isolation but miss
**interaction**, **flow**, **migration**, and **cross-surface** bugs. For each
release cycle, walk these end-to-end journeys in the browser before tagging:

1. **Migration-from-5.0.x (HIGHEST PRIORITY for 5.1.x)** — Restore a real
   5.0.x customer DB dump on staging. Do not reset any theme_mod. Visit home,
   post, archive, page, login, footer in dark + light. Every customizer-set
   value must visibly apply. Catches the `(int) 'on' === 0` /
   `! empty('off')` class of silent intent-flips.
2. **First-time installer (general site owner)** — Fresh DB, theme activated
   cold. Default look first. Set logo, primary color, hero typography, layout
   (wide → boxed), site loader on. Save. Publish a post. Check home + post +
   mobile.
3. **Blogger/publisher** — Site title + tagline + heading/body typography +
   image overlay rgba + blog layout (grid → list → masonry) + sidebar
   variations. Publish post with featured image + tags. Verify post, archive,
   category, search.
4. **Community manager (BP active)** — Activate BuddyPress. Set members /
   groups / activity sidebar layouts. Set BP avatar style. Check member
   profile, group directory, activity feed.
5. **Dark mode user** — Toggle dark/light/auto from header. Check home, post,
   comments, form inputs, footer (widgets active), site-info, mobile.
   Persistence across page loads.

Each journey passes only if **every visible step** renders correctly. A single
failing step blocks the release — fix in the same cycle (no defer per the
rule above). Matrix sweeps remain useful as a **regression fence** after a
journey passes.

### Browser screenshots: log out before capture

Even with `#wpadminbar { display: none }`, WP applies `html { margin-top:
32px !important }` to reserve admin-bar space — leaves a visible 32px gap
above the header. **Logging out removes the reservation entirely.** Playwright
flow for `screenshot.png`:

1. `browser_navigate` → `http://buddyx.local/wp-login.php?action=logout&_wpnonce=test`
2. `browser_evaluate` → `document.querySelector('a[href*="action=logout"]').click()`
3. `browser_resize` → 1200 × 900 (wp.org spec for theme `screenshot.png`)
4. `browser_navigate` → `http://buddyx.local/`
5. `browser_take_screenshot` → `wp-content/themes/buddyx/screenshot.png`

The auto-login mu-plugin lets you re-login afterwards via `?autologin=1`.

## Cross-theme migration knowledge

For Kirki-to-tokens migration architecture (this theme, plus planned ports to
buddyx-pro / KnowX / Reign), use the **`wbcom-kirki-to-tokens` skill**
(auto-triggered by phrases like "drop Kirki from <theme>" / "port BuddyX 5.1.0
architecture to <theme>"). The skill is the canonical home for cross-theme
findings — 5 architecture pillars, post-swap audit rubric, live-preview audit
helper, Google Fonts library restoration. **Do not duplicate skill content
into this repo.** Cross-theme stays in the skill; BuddyX-specific stays in
`plans/`.

## Plans + docs

- `plans/YYYY-MM-DD-feature.md` — design + implementation specs for
  BuddyX-specific work. **Start with**
  `plans/2026-05-07-buddyx-5.1.0-master-summary.md` — the living index for
  the 5.1.0 release (section 4 = architecture pillars, 8-10 = port sequence,
  7 = 10 non-obvious gotchas). For a chronological view use `git log --oneline
  plans/`; the skill's reference commit map cites which plan each commit
  implements.
- `docs/local-ci.md` — pre-commit hook chain + manual command set
  (wbcomdesigns/buddyx is private; no GitHub Actions).
- `docs/buddyx-design-tokens.md` — public token reference for theme/plugin
  authors. Regenerate from `inc/Tokens/Component.php` after token changes.
- `docs/customizer-inventory-snapshot.txt` — committed customizer-field
  inventory. Regenerate with `python3 tools/dump-customizer-inventory.py >
  docs/customizer-inventory-snapshot.txt` after any field change.

## Release notes style

WooCommerce-style action-prefix format for every `readme.txt` changelog AND
every GitHub release body. Bullets prefixed `New` / `Improve` / `Fix` /
`Security` / `Dev` / `Compat` (padded to 8 chars before ` - `). No emoji.
No em-dashes (use `-`). Release title format: `Plugin X.Y.Z - one-line
summary`. See the global `~/.claude/CLAUDE.md` "Release Notes Style" section
for the full rules.
