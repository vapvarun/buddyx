# BuddyX — Pre-Release Smoke Checklist

> **Run this before every tagged release. Every row must pass.**
> Any failure → file a Basecamp card in Bugs (project 37499979) and **halt the release**.
> Target time: 60 minutes end-to-end (themes are smaller surfaces than plugins).

**Matrix:** 3 personas × 3 browsers × 2 viewports × 2 color modes.

- Personas: Anonymous visitor, BuddyPress member, Admin
- Browsers: Chrome Desktop, Firefox Desktop, Safari iOS (sim or real)
- Viewports: 1440px desktop, 390px mobile
- Color modes: Light, Dark (toggle in header)

**Environment:**

- Clean Local site with **BuddyX on the previous stable version** (5.0.x) for the upgrade test
- A second clean Local site (or fresh DB on the same site) for the fresh-activation test
- BuddyPress + WooCommerce + bbPress installed for the integration rows (Section E)
- Access to `wp-content/debug.log` and DevTools Network tab

---

## A — Fresh activation (10 min)

- [ ] `wp theme activate buddyx` → Success, no fatal, no PHP warning in debug.log during the activation HTTP
- [ ] `wp theme list --format=csv | grep buddyx` shows the new version in column 4
- [ ] Front-end home loads as the very first request after activation → HTTP 200, body has class `theme-buddyx`
- [ ] `wp option get theme_mods_buddyx` returns a valid array (or empty array; not a serialize error)
- [ ] Deactivate (switch to TwentyTwentyFour) → reactivate BuddyX → no duplicate `after_switch_theme` side effects, theme_mods preserved
- [ ] Activate buddyx-pro (if combo mode) → no fatal, only one of the two is active at a time

## B — Upgrade from previous release (10 min) — HIGHEST PRIORITY for 5.1.x

- [ ] Restore a real 5.0.x customer theme_mods dump on staging (do NOT reset any theme_mod)
- [ ] Activate the 5.1.x build over the top → no fatal
- [ ] Home, blog post, archive, page, login, footer — every customizer-set value from 5.0.x still visibly applies
- [ ] Dark mode toggle still works AND remembers the prior site choice
- [ ] No new entries in `debug.log` during the upgrade request

## C — Core site-owner journeys (20 min)

### C1 — First-time installer
- [ ] Fresh DB, BuddyX activated cold. Default look first.
- [ ] Set logo, primary color, hero typography, layout (wide → boxed), site loader on
- [ ] Customizer **Publish** (not just preview) → reload home → every change applies
- [ ] Publish a post → reload home + post → typography + layout intact
- [ ] Resize to 390px → no horizontal overflow, every customizer value still applies

### C2 — Blogger / publisher
- [ ] Set title, tagline, heading + body typography, image overlay rgba, blog layout
- [ ] Set sidebar variation (left, right, both, none)
- [ ] Publish a post with featured image + tags
- [ ] Verify on post, archive, category, search pages

### C3 — Community manager (BP active)
- [ ] Activate BuddyPress
- [ ] Set members / groups / activity sidebar layouts in Customizer
- [ ] Set BP avatar style
- [ ] Visit member profile, group directory, activity feed → all render with BuddyX styles

### C4 — Dark mode user
- [ ] Toggle dark/light/auto from header
- [ ] Check home, post, comments, form inputs, footer (widgets active), site-info
- [ ] Reload page → color choice persists
- [ ] Mobile 390px → toggle still reachable, still persists

### C5 — Sub-header typography (regression guard for 2026-05-25 fix)
- [ ] Customize → Site Sub Header → Content Typography → pick a non-default color
- [ ] Publish → reload `/members/` (or any BP directory)
- [ ] Computed color of `.site-sub-header .entry-title` equals the picked color
- [ ] Remove the theme_mod → reload → color falls back to `--color-subheader-title` token (no leak)

## D — Known-regression guards (10 min)

Every row here is a bug that caused customer pain. Walk each one verbatim.

- [ ] **D.presets-zip-missing** (2026-05-25 #9914236698): build the dist ZIP, `unzip -l dist/buddyx-<version>.zip | grep assets/images/presets/ | wc -l` must equal 9
- [ ] **D.subheader-typography-color** (2026-05-25 #9914936841): `set_theme_mod("site_sub_header_typography", array("color"=>"#ff00aa"))`; `/members/` `.site-sub-header .entry-title` computed color must be `rgb(255, 0, 170)`

**Rule:** every customer-visible fix that ships after this document adds a new row here in the same PR.

## E — Extension surfaces BuddyX styles (10 min, run only if host plugin active)

- [ ] **BuddyPress**: `/activity/`, `/members/`, `/groups/` render with BuddyX styles; sub-header breadcrumbs visible; member profile tab nav renders
- [ ] **WooCommerce**: `/shop/`, single product, cart, checkout — layouts clean, no clipping
- [ ] **bbPress**: forum, topic, reply form — typography + colors match, 390px clean

## F — Cross-browser quick pass (10 min)

Run these 5 surfaces on **Chrome + Firefox + Safari iOS**:

1. `/` — landing page (block patterns)
2. `/blog/` — blog list
3. `/sample-post/` — single post
4. `/members/` — BP members directory (if BP active)
5. `/?customize_changeset_uuid=...&wp_customize=on` — customizer with a sub-header preview

Expectations: no JS errors, no layout breaks, dark-mode toggle works in each, customizer color picker can drag.

## G — Post-release verification (first 24h)

- [ ] `wp-content/debug.log` clean of new warnings/notices/fatals on a production install
- [ ] Zoho Desk / Slack #support — no "broke after update" or "my customizations vanished" tickets
- [ ] Wbcom store update flow — no install/upgrade failures reported

---

## Failure protocol

1. **Stop.** Do not merge the release branch.
2. File a Basecamp card in **Bugs** (project 37499979, column 7430694306) with the failed row verbatim, environment, browser, persona.
3. Fix + push to the release branch (per CLAUDE.md "no deferrals" rule).
4. Re-walk the failed row AND the section that contains it.
5. Resume only after the failure is resolved.

## Version-specific additions

Append a section below for every release with the specific regression guards added that cycle. After 2 clean releases of a row → graduate it into the main flow.
