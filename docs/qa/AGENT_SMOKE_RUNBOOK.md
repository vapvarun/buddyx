# Agent Smoke Runbook - BuddyX

**Audience:** a browser-capable agent (Claude Sonnet or equivalent) with Playwright MCP + WP-CLI Bash access, OR a human QA person with the same access. Both should be able to execute every step of this runbook.

BuddyX is a **WordPress classic theme** (not a plugin). The runbook section labels A-G stay portfolio-uniform, but the content is theme-shaped: activation contract is `wp theme activate`, "DB schema" is theme_mod sanity, "upgrade" is theme_mod migration from a prior stable version, "extensions" are the integrations that BuddyX styles on top of (BuddyPress, WooCommerce).

## How to read this runbook

Each C and E step describes a **customer contract**: what the surface promises, why it matters, the screens it touches, and what "working" looks like in customer terms. It does NOT prescribe exact Playwright calls or selectors. Read the relevant theme code, pick the right mechanism, verify the contract. The verifier is expected to notice bugs we did not pre-imagine.

D (regression guards) stays specific - those are repros of past incidents; the exact fixture IS the contract.

Infrastructure sections (preconditions, output contract, debug-log protocol, fixture cleanup, failure protocol) stay specific because they are the stable machinery the walk rides on.

## Global preconditions

- Working directory: `/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx`
- Site URL: `http://buddyx.local`
- WP path: `/Users/varundubey/Local Sites/buddyx/app/public`
- WP-CLI: `wp <cmd>` (run from the site root)
- Admin auto-login: `?autologin=1` on any front-end URL
- Per-user auto-login: `?autologin=<user_login>`
- Playwright: one Chromium session throughout; restart with `browser_close` + `browser_navigate` if it dies.
- Theme version: read from `style.css` `Version:` header (no PHP constant)
- Pair theme: `buddyx-pro` (when running combo mode, swap to pro for the second half)
- BuddyX positions as a **general theme** (not BP-specific) - the `screenshot.png` shows generic block patterns. Don't flag the absence of BP UI on the home as a defect.

## Output contract

At the end of the walk, write exactly one JSON file to
`/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx/docs/qa/.last-smoke-pass.json`:

```json
{
  "mode": "free|combo",
  "release_version": "<from style.css Version: header>",
  "ran_at": "<ISO 8601 UTC>",
  "sections": {
    "A_fresh_install":     { "pass": N, "fail": N, "skipped": N },
    "B_upgrade":           { "pass": N, "fail": N, "skipped": N },
    "C_core_flows":        { "pass": N, "fail": N, "skipped": N },
    "D_regression_guards": { "pass": N, "fail": N, "skipped": N },
    "E_extensions":        { "pass": N, "fail": N, "skipped": N },
    "F_cross_browser":     { "pass": N, "fail": N, "skipped": N }
  },
  "failures": [
    { "id": "...", "origin": "from|for", "triage_note": "...", "expected": "...", "actual": "...", "url": "...", "screenshot": "..." }
  ],
  "debug_log_issues": [
    { "section": "...", "level": "fatal|warning|notice|deprecated", "line": "...", "file": "..." }
  ],
  "manual_required": []
}
```

Emit a Basecamp draft per failure using project id `37499979` (BuddyX board), Bugs column `7430694306`.

## Fixture cleanup (before every walk)

Themes don't create rows in custom tables - all state is in `theme_mods_buddyx` (the WP option). Reset anything a previous walk wrote.

```bash
wp eval '
// Reset typography/color test pollution from prior walks.
remove_theme_mod("site_sub_header_typography");
remove_theme_mod("site_skin_preset");
// Clear color-mode cookies via JS in the browser session before C.dark steps.
echo "theme_mods cleaned\n";
'
```

## Debug log protocol

Enable `WP_DEBUG` + `WP_DEBUG_LOG` + `WP_DEBUG_DISPLAY=false` before Section A. Baseline `wp-content/debug.log` byte count. After every section, diff new lines into `debug_log_issues[]` classified by level. Any new fatal or warning is a failure unless explicitly whitelisted.

```bash
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw
wp config set WP_DEBUG_DISPLAY false --raw
DEBUG_LOG=/Users/varundubey/Local\ Sites/buddyx/app/public/wp-content/debug.log
BASELINE=$(wc -c < "$DEBUG_LOG" 2>/dev/null || echo 0)
```

---

## A - Fresh install

### A.activate.first-request
**What to verify:** activating BuddyX from a state where another theme was previously active completes cleanly, and the first front-end request after activation returns HTTP 200 without any PHP error in debug.log.
**Why it matters:** an after_switch_theme hook that fatals leaves customers staring at a white screen on first paint.
**Acceptance:** `wp theme activate buddyx` returns Success; first `curl -sI http://buddyx.local/` is `HTTP/1.1 200`; debug.log diff during the activation HTTP is empty.

### A.theme-mods.sanity
**What to verify:** after a fresh activation, `wp option get theme_mods_buddyx` returns a valid array (may be empty, must not be the literal string `'a:0:{}'` or an unserialize error).
**Why it matters:** corrupt theme_mods is the only DB state a theme owns - regressions here cascade across every customizer surface.

### A.starter-content
**What to verify:** if BuddyX ships starter content (patterns, demo widgets, default menus), the first activation seeds them once and not again on re-activation.

---

## B - Upgrade from previous version

### B.theme-mod.migration-from-5.0.x
**What to verify:** restore a real 5.0.x customer DB dump on staging, do not reset any theme_mod, activate BuddyX 5.1.0. Visit home, post, archive, page, login, footer in dark + light. Every customizer-set value from 5.0.x must visibly apply on the upgraded site.
**Why it matters:** catches the silent intent-flip class (Kirki-era `'on'` / `'off'` strings vs modern `'1'` / `'0'` values, JSON-string sanitize wrappers around old array values).
**This is the HIGHEST PRIORITY journey for 5.1.x per the BuddyX CLAUDE.md.**

### B.style-css-version
**What to verify:** `wp theme list --format=csv | grep buddyx` shows the new version in the version column. WP's update flow read it from `style.css`.

---

## C - Core customer flows

Persona ladder: Anonymous > Member (BP) > Admin. Exercise both desktop 1440px and mobile 390px where the UI differs.

Each step is a contract, not a script. Verify the UI as a site owner / visitor would AND confirm the server-side effect (theme_mod round-trip, rendered CSS variable, persisted body class) to rule out a "looks right, didn't actually save" bug.

### C.anon.home
**What to verify:** the home page renders for a logged-out visitor with the active block pattern, primary color tokens applied, no console errors.

### C.anon.primary-views
**What to verify:** each public template (`index.php`, `archive.php`, `single.php`, `page.php`, `search.php`, `404.php`) renders for an anonymous visitor without fatal, and auth-gated actions cleanly redirect to login.

### C.owner.first-time-installer
**What to verify (HIGH PRIORITY JOURNEY):** fresh DB, theme activated cold. Set logo, primary color, hero typography, layout (wide → boxed), site loader on. Save (Customizer Publish). Publish a post. Check home + post + mobile. Every customizer value must visibly apply and round-trip on full reload.
**Save-roundtrip rule:** verification must use actual customizer **Publish** (which stores via `sanitize_json_array` for repeaters), not `wp theme mod set` (which stores plain PHP arrays).

### C.owner.blogger
**What to verify:** site title + tagline + heading/body typography + image overlay rgba + blog layout (grid → list → masonry) + sidebar variations. Publish post with featured image + tags. Verify post, archive, category, search pages all reflect the choices.

### C.owner.dark-mode
**What to verify:** toggle dark/light/auto from header. Check home, post, comments, form inputs, footer (widgets active), site-info, mobile. Color-mode preference persists across page loads (`localStorage bx-color-mode` + cookie + user_meta where applicable).

### C.owner.sub-header-typography
**What to verify:** Customize -> Site Sub Header -> Content Typography. Pick a non-default color. Publish. Reload `/members/` (or any sub-header surface). Computed color of `.site-sub-header .entry-title` matches the picked color. Remove the theme_mod, reload, color falls back to the dark/light token (no leak).
**This is the regression that landed on 2026-05-25 - guards the Customizer_Framework typography output handler.**

### C.owner.site-skin-presets
**What to verify:** Customize -> Site Skin -> Style preset. All 9 preset cards (cool, dark, default, editorial, minimal, monochrome, pastel, vibrant, warm) render their swatch SVG without broken-image icons.
**Critical when running against the dist ZIP, not the dev install.** The dev install always has presets/* on disk; only the packaged ZIP catches the filesToCopy regression.

### C.member.bp-flows
**What to verify:** with BuddyPress active, a member can visit Activity / Members / Groups / their own profile. Cards render with avatars, typography matches the active style variation, no fatal in debug.log on any BP template.

### C.admin.appearance
**What to verify:** Appearance -> Customize loads, every BuddyX section expands, every control renders without "Cannot read properties of undefined" in JS console.

### C.cron
**What to verify:** the only buddyx-prefixed cron event registered is `buddyx_delete_fonts_folder` (font folder cleanup, intentional since 4.3.9, carries a phpcs:ignore comment on `wp_schedule_event` at `inc/Webfont/class-buddyx-webfont-loader.php`). Any OTHER `buddyx_*` cron event is a regression - investigate.
**Whitelist:** `buddyx_delete_fonts_folder` (monthly).

---

## D - Known-regression guards

Each row is a repro of a past bug that caused customer pain. D rows stay specific - the fixture IS the contract.

| ID | Bug | Fixture + assertion |
|----|-----|---------------------|
| D.presets-zip-missing | 2026-05-25 #9914236698: dist ZIP shipped without `assets/images/presets/*.svg` because `config/config.default.json` filesToCopy did not list them. | After `npm run dist`, `unzip -l dist/buddyx-<version>.zip \| grep assets/images/presets/ \| wc -l` must equal 9 (one per preset SVG). |
| D.subheader-typography-color | 2026-05-25 #9914936841: Customizer typography color picker silently no-op'd because `Output_Builder::typography_declarations()` `$key_map` was missing `'color' => 'color'`. | `wp eval 'set_theme_mod("site_sub_header_typography", array("color" => "#ff00aa"));'` then load `/members/`; computed color of `.site-sub-header .entry-title` must be `rgb(255, 0, 170)`. Then `remove_theme_mod("site_sub_header_typography")`; computed color must fall back to `--color-subheader-title` (the token, not a hardcoded value). |

Rule: every customer-visible fix adds a D row in the same PR. After 2 clean releases, a D row graduates into C/E.

---

## E - Extensions BuddyX styles on top of

BuddyX is a community-friendly theme that ships specific CSS for several plugins. Each integration is a customer contract. If the host plugin is not active, the section is `skipped`, not failed.

### E.buddypress
**What to verify:** with BuddyPress active, the directory pages (`/activity/`, `/members/`, `/groups/`) render with BuddyX styles applied; no raw-unstyled lists; the sub-header shows the breadcrumb; member profile pages render the tab navigation.

### E.woocommerce
**What to verify:** with WooCommerce active, `/shop/` renders the product grid with the active BuddyX layout choice; single-product page renders cleanly; cart and checkout pages render without overlap or clipping.

### E.bbpress
**What to verify:** with bbPress active, forum / topic templates render with BuddyX typography and color tokens; reply form displays without overflow at 390px.

---

## F - Cross-browser, RTL, accessibility

### F.chromium
Already covered by Sections A-E.

### F.firefox-desktop and F.safari-ios
Chromium-only MCP cannot walk these. Populate `manual_required[]` with the critical flows a human must spot-check: customizer color picker drag, BP nouveau scroll behavior on iOS, dark-mode toggle keyboard accessibility on Firefox.

### F.rtl
**What to verify:** switch site language to an RTL locale (e.g. Hebrew/Arabic); primary templates render right-to-left without overflow; icons mirror where appropriate; brand glyphs stay untransformed.

### F.a11y
**What to verify:** primary interactive surfaces have visible focus rings; tab order is logical; icon-only buttons have `aria-label`; dark-mode toggle is keyboard-reachable and announces its state.

---

## G - Post-release monitoring (first 24h after tag)

Runs on a production install with the new release. Watch for new debug.log entries, support tickets reporting "broke after update", and any "my customizations vanished" reports (that is the B.theme-mod.migration class).

---

## Failure protocol

1. Screenshot on every failure: `browser_take_screenshot({ filename: "fail-<id>.png" })`.
2. **Triage: from vs for the theme.**
   - `from` = our theme is at fault.
   - `for` = failure surfaces while BuddyX is active but root cause is elsewhere (plugin conflict, browser limit, child-theme override, hosting).
3. Record in `failures[]` with `{ id, origin, triage_note, expected, actual, url, screenshot }`.
4. Never halt. Collect all failures in one pass.
5. Emit a Basecamp draft per failure with the origin line populated.

Triage is Sonnet's job; fix-or-document is the calling session's job.

## Step ID format

`<Section>.<persona-or-surface>.<feature>` e.g. `C.owner.dark-mode`. D rows: `D.<descriptor>`. E rows: `E.<host-plugin>`.
