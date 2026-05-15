# BuddyPress CSS — UX Audit (BuddyX 5.1.0)

**Date:** 2026-05-15
**Scope:** `assets/css/src/buddypress.css` (6,113 lines) + built `assets/css/buddypress.css` + the BuddyPress horizontal/vertical menu surfaces used on member profile, group profile, and directory pages.
**Method:** `ux-audit` skill — `~/.claude/skills/ux-audit/templates/ux-audit.sh` static scan + browser-side measurement on `http://buddyx.local/members/varundubey/` (BP 14.x Nouveau template pack, default horizontal mode).
**Rationale:** BuddyPress is BuddyX's core feature set. Auditing the CSS that drives member + group navigation before further BP feature work.

## Headline result

| Severity | Where | Count | Status |
|---|---:|---:|---|
| **Block** in BuddyPress CSS | `buddypress.css` source + built | **0** | clean |
| **Block** elsewhere (out of scope but listed) | other theme files | 8 | tracked separately, not BP |
| **Advisory** — outline-none / outline-0 with no `:focus-visible` replacement | `buddypress.css` source | **22** | **CONFIRMED BROKEN in browser** |
| **Advisory** — RTL raw-margin-left/right | `buddypress.css` source | 0 | clean (10 hits are in `bx-tokens-applied.css` + others, not BP) |
| Tap target floor (40px / Rule 13) | profile nav + subnav | 6 sampled, **all 42-43px** | pass |

**Headline finding:** keyboard accessibility on BuddyPress profile/group navigation is broken. 22 `outline: none` / `outline: 0` declarations in `buddypress.css` strip the focus indicator with no replacement. Browser-measured profile-nav links return `focusOutline: "rgb(...) none 1.5px"` and `focusBoxShadow: "none"` — meaning a keyboard user tabbing through Activity / Profile / Friends / Notifications has **zero visible indicator of where they are**. On a community theme where members navigate by tab, that's a release-blocking accessibility regression.

## Block-severity findings (8 total, 0 in BP)

These are theme-wide block violations the audit also caught — listed for completeness, not part of this BP audit's fix scope:

| File:line | Rule | Note |
|---|---|---|
| `inc/compatibility/surecart/surecart-functions.php:179` | F1 inline-style | SureCart compat — separate concern |
| `inc/compatibility/fluentcart/fluentcart-functions.php:361` | F1 inline-style | FluentCart compat — separate concern |
| `inc/compatibility/fluentcart/fluentcart-functions.php:385` | F2 inline-script | FluentCart compat — separate concern |
| `inc/extra.php:681` | F2 inline-script | helper bootstrap — separate concern |
| `inc/Accessibility/Component.php:85` | F2 inline-script | `no-js` removal — intentional in `<head>` (false positive candidate) |
| `inc/Accessibility/Component.php:137` | F2 inline-script | skip-link focus fix — intentional `<head>` script |
| `inc/Tokens/Component.php:905` | F2 inline-script | FOUC-prevention color-mode bootstrap — intentional `<head>` script |
| `inc/Speculation_Rules/Component.php:123` | F2 inline-script | Speculation Rules JSON — intentional `<script type="speculationrules">` |

The 4 inline `<script>`s flagged in `Accessibility/`, `Tokens/`, and `Speculation_Rules/` are **intentional** `<head>`-injected scripts that must be inline (FOUC-prevention, no-JS class swap, speculation-rules JSON). They are legitimate exceptions to the F2 rule — they should be added to the audit script's allowlist in a follow-up. The SureCart + FluentCart compat layers are real F1/F2 violations that belong to a separate compat cleanup.

## BuddyPress-specific findings — focus rings (THE issue)

22 declarations in `assets/css/src/buddypress.css` strip the outline without a `:focus-visible` replacement within 5 lines. Source + built file each contain 11 hits → same code shipped twice (built minified copy must be regenerated after fix).

Source-file hits:

| Line | Declaration | Selector context (read from file) |
|---|---|---|
| 129 | `outline: none;` | `body .buddypress-wrap .subnav-filters .component-filters select` / `.last select` |
| 225 | `outline: 0;` | (button) |
| 308 | `outline: 0;` | (input/select state) |
| 322 | `outline: 0;` | (input/select state) |
| 3604 | `outline: none;` | (likely activity / messages composer) |
| 3676 | `outline: 0;` | (likely composer/textarea) |
| 4443 | `outline: none;` | (likely member/group action) |
| 4457 | `outline: none;` | (likely member/group action) |
| 4903 | `outline: 0;` | (likely media/avatar) |
| 5916 | `outline: 0;` | (likely send/submit) |
| 5967 | `outline: none;` | (likely send/submit) |

Browser-measured proof (`/members/varundubey/`, profile horizontal nav):

```js
// Programmatically focus the "Profile" link, then read computed style:
defaultOutline: "rgb(17, 17, 17) none 1.5px"    // "none" keyword → no outline rendered
focusOutline:   "rgb(17, 17, 17) none 1.5px"    // same in focus state
focusBoxShadow: "none"                          // no box-shadow ring either
```

The `none` keyword in the `outline` shorthand suppresses the outline at every state. No `:focus-visible` rule restores a ring elsewhere in the file.

**Fix pattern** (per ux-foundation Rule 14):

```css
.buddypress-wrap .bp-navs li a {
    outline: none;  /* keep for legacy hover/active states */
}
.buddypress-wrap .bp-navs li a:focus-visible {
    outline: 2px solid var(--bx-color-accent);
    outline-offset: 2px;
    border-radius: var(--bx-radius-sm, 4px);
}
```

Apply the same pattern to the other 21 sites (select/input states, composer textareas, action buttons, media/avatar surfaces, send/submit buttons).

## Horizontal vs vertical menu — rule inventory

The user's specific concern. The theme handles four distinct nav contexts via different selectors:

| Surface | Selector | Mode | Where it applies |
|---|---|---|---|
| Single profile/group nav — horizontal (default) | `.buddypress-wrap:not(.bp-single-vert-nav) .bp-navs:not(.bp-subnavs) li` (line 410) | horizontal | Member profile + Group profile pages when Customizer "vertical nav" is OFF |
| Single profile/group nav — vertical | `#buddypress.buddypress-wrap .bp-navs.vertical ul.bp-priority-object-nav-nav-items > li`, `.bp-priority-subnav-nav-items > li` (lines 430-431) | vertical | Same pages when Customizer "Display the member/group navigation vertically" is ON. **Added this cycle** (commit on `5.1.0`) to display these items as `block` so the priority-object-nav wrapper stacks correctly. |
| Directory nav — horizontal | `.buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links)` (line 402) | horizontal | Members / Groups / Activity directory pages with horizontal tabs |
| Directory nav — vertical, activity-specific | `.bp-dir-vert-nav ul.component-navigation.activity-nav li` (line 476) | vertical | Activity directory when in vertical mode. **Added this cycle** with `display: block` because the priority-object-nav wrapper otherwise rendered them inline. |
| Group invite subnav | `#buddypress.buddypress-wrap .bp-invites-nav ul.subnav li` | inline-block | Group "Send Invites" → "Pending Invites" subnav. **Added this cycle** (`display: inline-block; float: none`) because the inherited vertical-nav `display: block` was breaking horizontal subnav inside a vertical parent. |

**Cross-pattern consistency check (from browser-measured `/members/varundubey/` in horizontal mode):**

| Property | Horizontal profile nav (4 links) | Profile subnav (4 links) | Match? |
|---|---|---|---|
| Height | 42px | 43px | ~1px drift — non-critical |
| Padding | 8px 10px | 8px 10px | ✅ |
| Color (inactive) | `rgb(17, 17, 17)` | `rgb(17, 17, 17)` | ✅ |
| Color (current) | `rgb(239, 84, 85)` (brand `#ef5455`) | (similar) | ✅ |
| Focus ring | **none** | **none** | ❌ (both broken) |

The horizontal nav + subnav are visually consistent on this page. Vertical mode wasn't tested in-browser this pass (Customizer toggle off on this dev site); the rule inventory above shows the vertical CSS exists and was browser-verified earlier in the 5.1.0 cycle. The focus-ring problem applies identically to vertical mode — same `.bp-navs li a` selector, same `outline: none` declarations.

**Color-source check:** the inactive `rgb(17, 17, 17)` (#111) and active `rgb(239, 84, 85)` (#ef5455 brand) likely come from the Tokens `:root` block (Site Skin Phase 4 tokenization), but a follow-up grep should confirm `buddypress.css` reads them via `var(--bx-color-*)` rather than carrying raw `#111` / `#ef5455` literals. Phase 4 covered foundation + community surfaces, so they should already be tokenized.

## Out of scope for this audit pass (defer to cleanup PR's verification)

Per the ux-audit skill §1.2, full UX compliance requires the following manual / browser checks. **None blocks this audit pass** (the focus-ring finding is sufficient to motivate the cleanup PR); they belong in the verification step of the cleanup itself:

- [ ] Visual at 5 viewports (390 / 430 / 768 / 1024 / 1440px) — members directory, member profile (horizontal), member profile (vertical), groups directory, group profile (horizontal), group profile (vertical), activity directory (vertical mode).
- [ ] Dark-mode walk on each of the above. Tokens already cover this per Site Skin Phase 4, but the nav surfaces specifically need a confirm.
- [ ] RTL walk — `<html dir="rtl">` rendered.
- [ ] Keyboard tab through every nav item — must show the new `:focus-visible` ring after fix.
- [ ] axe-core / Lighthouse accessibility audit on member profile + group profile + activity → score ≥ 95.
- [ ] Vertical-mode regression — Customizer toggle ON; member/group/activity nav stacks correctly with new focus rings.

## Recommended cleanup plan (single PR, single commit per logical group)

1. **Add `:focus-visible` partner rule for every `outline: none`/`outline: 0` in `buddypress.css`** (the 22 hits inventoried above). One commit. Selector grouping: nav links + composer textareas + select/input states + action buttons. Use token `var(--bx-color-accent)` for the ring; `outline-offset: 2px`; `border-radius: var(--bx-radius-sm)`.
2. **Audit hex color literals in `buddypress.css`** — grep for `#[0-9a-f]{3,6}` outside `:root`, replace with `var(--bx-color-*)` per Site Skin Phase 4 token taxonomy. Capture findings in a follow-up commit.
3. **Browser-verify** all 7 page types in 5 viewports + dark + RTL + keyboard (the §1.2 list above).
4. **Regenerate built CSS** — `npm run build:css` after the source edits.

Effort: 1-2 commits, ~1 hour static fix + ~2-3 hours browser verification.

## Audit artifacts

- Raw script output: `plans/audit-snapshots/2026-05-15-ux-audit-raw.md` (transient; do not commit if cleanup PR ships).
- Audit method preserved in the skill at `~/.claude/skills/ux-audit/SKILL.md` (already reusable for buddyx-pro / KnowX / Reign migrations).
