# bbPress UX Audit — Planning Note (future work)

**Status:** Planning stub. Not yet executed. Slot after WooCommerce audit OR in parallel.
**Bar:** Same as BuddyPress audit (`plans/2026-05-15-buddypress-css-ux-audit.md`) — 100% premium UX, inbuilt, uniform desktop+mobile, modern not legacy.
**Method:** Same as BP audit — `ux-audit` skill (`~/.claude/skills/ux-audit/`) static scan + Playwright per-screen browser measurement of computed tap targets, focus rings, border-radius, shadow, bg, padding, font-size on every interactive element. Both 1280px desktop and 390px mobile per screen.

## Scope constraint — **CSS-only, no template overrides**

Same constraint as the WooCommerce audit (`plans/2026-05-15-woocommerce-css-ux-audit-plan.md`). The bbPress audit + cleanup are CSS-only. BuddyX does NOT ship bbPress template overrides.

**Good news — already at 0 overrides today.** Inventory confirms: no `bbpress/` directory in the theme. `assets/css/src/bbpress.css` (299 lines) currently handles bbPress's default templates entirely via CSS. The audit's job is to verify and *upgrade* that CSS to premium quality — not to add overrides.

## Seed content prerequisite — **must populate before audit**

A bbPress audit measures rendered screens. Without forum content, the visible-screens checklist below is empty pages + "No forums yet" placeholders. **Seed before auditing:**

- Activate bbPress plugin.
- Create **at least 3 forums** (parent + 2 child) so the forum-list patterns render.
- Each forum needs **at least 2 topics** (so topic-list patterns render).
- Each topic needs **at least 3 replies from at least 2 different users** (so reply-list pagination + author-avatar patterns render).
- One topic should be **sticky/super-sticky** (so the sticky-topic indicator renders).
- One topic should be **closed** (so the closed-state styling renders).
- One topic should have **a long reply (>500 words)** to test typography hierarchy + readable line length on a real wall of text.
- One reply should **quote another reply** (to test quoted-reply styling).
- Seed two different test users so author avatar/name patterns vary across the reply thread.

Source: WP-CLI scaffolds (`wp bbp` commands if available) OR manual create OR bbPress's own demo content (if shipped). Either way, document the seed steps in the audit's commit body so the next person can reproduce.

## Head-start inventory

### bbPress CSS that BuddyX ships

| File | Built file | Lines (src) | Purpose |
|---|---|---:|---|
| `assets/css/src/bbpress.css` | `bbpress.css` + `.min.css` | 299 | All bbPress visible-screen styling |

299 lines is much smaller than `buddypress.css` (6,113 lines) — suggests bbPress styling is mostly leaning on BP/global tokens with light per-component overrides. Verify in the audit; this either means "low complexity, easy to upgrade" or "thin styling layer that lets BP/WP defaults bleed through."

### bbPress template overrides in BuddyX

**0** templates. No `bbpress/` directory exists in the theme. The audit's CSS-only constraint is **already met by today's codebase** — the goal is to keep it that way while raising visual quality.

### bbPress-related Customizer toggles BuddyX exposes

| Setting | File | Effect |
|---|---|---|
| `bbpress_sidebar_option` (radio_image) | `Sidebar_Fields.php:98` | Sidebar layout for bbPress pages (left / right / none) |

Plugin-gated on `function_exists('is_bbpress')` (confirmed in this session's customizer live-preview audit — see `plans/2026-05-15-buddypress-css-ux-audit.md`).

### Visible-screens checklist

**Tier 1 — Forum browsing:**
1. `/forums/` — Forums archive (list of all forums)
2. `/forums/forum/<slug>/` — Single forum page (list of topics in that forum)

**Tier 2 — Topic reading:**
3. `/forums/topic/<slug>/` — Single topic with reply thread + pagination
4. Same topic page in a **closed** state
5. Same topic page in a **sticky/super-sticky** state

**Tier 3 — Posting / interacting:**
6. New topic form (usually at the bottom of a single-forum page, or a dedicated form)
7. Reply form (at the bottom of a topic page)
8. Edit-reply screen
9. Quote-reply state (the quoted-reply markup rendered inside the new reply)

**Tier 4 — User-facing bbPress profile (if integrated with BP):**
10. `/forums/users/<user>/` OR `/members/<user>/forums/` — User's topics + replies tabs
11. User's subscriptions / favorites lists

**Tier 5 — Search / archives:**
12. bbPress search results page (`/forums/search/?bbp_search=...`)
13. Topic tag archive (if tags enabled)

**Variant axes (same as BP audit):**
- Default state @ 1280px + 390px
- Sidebar position variations (left / right / none) — bbPress has its own sidebar control
- Dark mode (Site Skin Phase 4 tokens carry; verify on a long reply thread)
- RTL (long-form text content; bbPress is a stress test for logical-property margins)

### Expected findings (informed prediction from BP-audit patterns)

The BP audit's headline findings are likely to repeat on bbPress surfaces:

- **Focus rings missing** on every interactive element — forum-list links, topic-list links, reply pagination, sticky/closed indicators, reply-form submit, quote/edit/reply/spam action links.
- **Tap targets below 40px** on action-link rows (typically inline `<a>` elements with small font-size + minimal padding).
- **Native form-field styling** on new-topic / reply forms — the topic-title input, reply-textarea, subscription-checkbox, etc. The reply textarea especially needs to match the design system's textarea token.
- **Reply table** likely uses `<table>` with `<tr>` per reply (bbPress convention). Modern premium needs a card-per-reply pattern on mobile + a clean table-like layout on desktop with avatar + author + reply body composed cleanly.
- **Pagination** likely default `<a>` rendering. Premium: matching button design or styled pagination component.
- **Avatar size** in reply list — bbPress defaults to 80px or smaller. Modern premium: 48-64px on mobile, 56-72px on desktop.
- **"Tagged: foo, bar"** chips below replies likely plain text or basic underlined links. Premium: rounded tag chips.

## Audit deliverable shape (mirror BP audit)

When the future planner runs this:

1. Seed content per the prerequisite section above. Document the seed steps in the commit body.
2. Save the static scan output to `plans/audit-snapshots/<date>-ux-audit-bbpress-raw.md` (gitignored).
3. Walk every Tier 1-5 screen at both viewports; save screenshots to `plans/audit-evidence/bbp-audit-*.png`.
4. Compile findings into `plans/<date>-bbpress-css-ux-audit.md` with the same structure as the BP audit: headline + tested vs inferred + categorized findings A/B/C/D/E + prioritized 4-phase cleanup plan.
5. Single commit + push.

## Out of scope

- bbPress extensions (bbp-Style-Pack, GD bbPress Tools, etc.) — those are plugin ecosystems.
- bbPress admin (forum management in `wp-admin/`) — separate concern.
- BuddyBoss forums (different plugin, not bbPress).

## Reference

- BP audit (the method template): `plans/2026-05-15-buddypress-css-ux-audit.md`
- WC audit plan (the CSS-only-no-overrides precedent): `plans/2026-05-15-woocommerce-css-ux-audit-plan.md`
- ux-audit skill: `~/.claude/skills/ux-audit/SKILL.md`
- Companion tokens skill: `~/.claude/skills/wbcom-kirki-to-tokens/SKILL.md`
