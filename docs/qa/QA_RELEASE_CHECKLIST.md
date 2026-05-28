# BuddyX — QA Release Checklist

> **The final gate before tagging a release.** Every row must pass, no exceptions.
> This is the backend counterpart to `PRE_RELEASE_SMOKE.md` (frontend/browser).
> Together they guarantee: code quality + feature behavior + safe packaging.

**Target time:** 30 minutes end-to-end (plus the 60-min browser smoke).

---

## 0 — Branch hygiene

- [ ] On a named release branch (`5.1.0`, `release/5.1.x`, or equivalent), NOT on `master`
- [ ] `git status` clean — no uncommitted changes
- [ ] `git pull` on the branch — up to date with origin (`wbcomdesigns/buddyx`)
- [ ] `master` merged into release branch (or rebased) — no stale base
- [ ] No `.DS_Store`, `.idea/`, `.vscode/`, `node_modules/`, `vendor/`, `dist/` staged for commit

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
git status
git fetch origin
git log --oneline origin/master..HEAD | head -20   # what's shipping
```

## 1 — Version triangulation

BuddyX keeps the version in multiple places. Every place must match.

- [ ] `style.css` `Version:` header equals release version
- [ ] `config/config.default.json` `theme.version` matches
- [ ] `package.json` `version` matches (if present)
- [ ] `composer.json` `version` matches (if present)
- [ ] `readme.txt` `Stable tag` matches (if `.org` published)
- [ ] `CHANGELOG.md` or readme.txt changelog has an entry for this version with real customer-facing release notes (per the global "WooCommerce-style action-prefix" format)
- [ ] Sister theme `buddyx-pro` is on the same version (lockstep release pattern)

Fast check:

```bash
grep -rE "Version:|\"version\":" style.css config/config.default.json package.json composer.json readme.txt 2>/dev/null \
  | grep -v vendor | grep -v node_modules
```

Every printed line should show the same X.Y.Z.

## 2 — Static analysis

Run from the theme root.

### WPCS (WordPress Coding Standards)

- [ ] `vendor/bin/phpcs --standard=phpcs.xml.dist` — 0 errors, 0 warnings on changed files
- [ ] No new `// phpcs:ignore` suppressions without a comment explaining why

### PHPStan

- [ ] `vendor/bin/phpstan analyse --memory-limit=2G` — level clean per `phpstan.neon.dist`, or only entries in `phpstan-baseline.neon`
- [ ] Baseline not grown this release (or the diff is documented in changelog)

### PHP lint (syntax)

```bash
find . -name "*.php" \
  -not -path "*/vendor/*" -not -path "*/node_modules/*" -not -path "*/dist/*" \
  -exec php -l {} \; 2>&1 | grep -v "No syntax errors"
```

- [ ] No output (no syntax errors)

## 3 — Tests

### PHPUnit (integration)

- [ ] `composer test` or `vendor/bin/phpunit --configuration phpunit.integration.xml.dist` — all pass
- [ ] PHP matrix covers PHP 8.0 (the theme's declared floor from `BUDDYX_MINIMUM_PHP_VERSION`) AND current stable (8.3 / 8.4)
- [ ] WP matrix covers `Requires at least: 5.4` AND current stable AND `latest`

### Jest / JS tests

- [ ] `npm test` (Jest config exists at `jest.config.cjs`) — all pass
- [ ] No `.only` / `.skip` left in test files

### Playwright (E2E)

- [ ] `tests/e2e/specs/smoke.spec.ts` passes (basic homepage / body class / title checks)

## 4 — Security sweep

Themes have a smaller security surface than plugins (no REST routes, rarely any AJAX). The hot-checks that matter:

### Escape on output

- [ ] Every echoed variable passes through an escape function (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`, etc.)
- [ ] No `echo $variable` without escape in templates/* added this release
- [ ] Translations via `esc_html__`, `esc_attr__`, `esc_html_e` in output context (not bare `__`)

```bash
git diff origin/master...HEAD -- '*.php' | grep -E "^\+.*echo \\\$" | grep -v "esc_"
```

### Customizer sanitize callbacks

- [ ] Every new customizer setting registered this release has a `sanitize_callback`
- [ ] Color settings use `sanitize_hex_color` (or stricter); typography colors at minimum `sanitize_text_field`
- [ ] Repeater/sortable settings use `Customizer_Framework\Field::sanitize_json_array`

```bash
git diff origin/master...HEAD -- '*.php' | grep -E "add_setting|Field::add" | head -20
```

### File operations

- [ ] No `file_get_contents` on user-supplied paths
- [ ] No dynamic-code execution functions called with user-supplied data

## 5 — Translations (i18n)

- [ ] `.pot` file regenerated and matches current strings: `wp i18n make-pot . languages/buddyx.pot`
- [ ] No em-dashes (`—`) inside any `__()`, `_e()`, `_x()`, `_n()`, `esc_html__()` (reads as AI-generated and per the global CLAUDE.md rule)
- [ ] Text domain consistent across all files (`buddyx`)
- [ ] `_n()` used for pluralizable strings

```bash
grep -rE "(__|_e|_x|_n|esc_html__|esc_attr__|esc_html_e|esc_attr_e)\\([^)]*—" \
  . | grep -v vendor | grep -v node_modules | grep -v dist | grep -v languages
```

## 6 — Readme + Docs

- [ ] `readme.txt` validates at https://wordpress.org/plugins/developers/readme-validator/ (themes use the same format)
- [ ] `Requires at least`, `Tested up to`, `Requires PHP` current
- [ ] `Stable tag` matches the release version
- [ ] Changelog entry written using WooCommerce-style action-prefix format (`New` / `Improve` / `Fix` / `Security` / `Dev` / `Compat` — see global CLAUDE.md)
- [ ] No em-dashes, no emoji, no internal references (ticket IDs, commit SHAs)
- [ ] `docs/qa/AGENT_SMOKE_RUNBOOK.md` Section D updated with any new regression guards from this release
- [ ] `docs/buddyx-design-tokens.md` regenerated if token system changed
- [ ] `docs/customizer-inventory-snapshot.txt` regenerated after any customizer field change

## 7 — Browser smoke gate (external dependency)

- [ ] `docs/qa/.last-smoke-pass.json` exists
- [ ] Report `release_version` equals the release version
- [ ] Report `ran_at` within the last 24 hours
- [ ] `failures[]` is empty
- [ ] `debug_log_issues[]` is empty (no fatals/warnings during walk)
- [ ] `manual_required[]` reviewed — Firefox / Safari iOS flows verified separately by a human

If the report is missing or stale, run the `wp-plugin-smoke` skill (Claude-level) against this theme.

## 8 — Packaging dry-run

- [ ] `NODE_ENV=production node scripts/cli.js bundle` succeeds (this is what `npm run dist` calls under the hood)
- [ ] Resulting `dist/buddyx-<version>.zip` has NO: `.git/`, `node_modules/`, `tests/`, `.github/`, `phpunit.xml.dist`, `phpcs.xml.dist`, `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `.DS_Store`
- [ ] Resulting zip HAS: `style.css`, `functions.php`, `theme.json`, `readme.txt`, `screenshot.png`, `templates/`, `parts/`, `inc/Customizer_Framework/**`, `assets/css/**`, `assets/js/**`, `assets/images/presets/*.svg` (regression guard for #9914236698), `languages/*.pot`
- [ ] Zip extracts to a folder named exactly `buddyx/` (not `buddyx-<version>/`)
- [ ] Zip size reasonable (flag if >2× previous release)

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
NODE_ENV=production node scripts/cli.js bundle
unzip -l dist/buddyx-<version>.zip | head -50
unzip -l dist/buddyx-<version>.zip | grep "assets/images/presets/" | wc -l   # MUST equal 9
ls -lh dist/buddyx-<version>.zip
```

## 9 — Install-in-anger

On a **second clean** Local site (not the development site):

- [ ] Install the generated zip via WP `Appearance → Themes → Add New → Upload Theme`
- [ ] Activate succeeds — no fatal, no PHP warning in debug.log
- [ ] Front-end home (first request after activation) returns HTTP 200
- [ ] `wp option get theme_mods_buddyx` returns valid array
- [ ] Customize → Site Skin → Style preset cards all render their swatch images (regression guard)

## 10 — Upgrade-in-anger

On a **third clean** site with the **previous stable version** installed + real customer theme_mods:

- [ ] Upload the new zip via "Replace theme" or the WP admin update flow
- [ ] Upgrade succeeds — no fatal
- [ ] Every customizer-set value from the previous version still visibly applies (the HIGHEST PRIORITY 5.1.x journey from CLAUDE.md)
- [ ] Dark mode preference + sub-header typography color + site skin preset choice all preserved
- [ ] No new `debug.log` entries during the upgrade request

## 11 — Release metadata

- [ ] Git tag created: `v<version>` (annotated: `git tag -a v<version> -m "..."`)
- [ ] Tag points at the release-branch commit (not `master` yet)
- [ ] GitHub Release drafted on `vapvarun/buddyx` (the public release-only mirror per CLAUDE.md) with changelog copied from readme.txt
- [ ] Release zip attached to GitHub Release
- [ ] Matching tag on `wbcomdesigns/buddyx-pro` repo with same version (lockstep)

## 12 — Post-tag checks (first push)

- [ ] CI (if configured on `wbcomdesigns/buddyx` — note that repo is private and may not run GHA) is green
- [ ] Release branch merged back to `master` (per CLAUDE.md repo convention)
- [ ] `master` branch protection intact

## 13 — Customer-facing publish

Only once sections 0–12 are ticked:

- [ ] Wbcom store product page updated with new version + changelog
- [ ] Docs website updated at the GitHub level (edit `docs/website/*.md` in the repo per CLAUDE.md — DO NOT call `mcp__wbcom-docs__publish_product_docs`)
- [ ] Customer update email drafted (with the real changelog, not marketing fluff)
- [ ] Internal Slack post to `#ready-for-release` with zip link + GitHub Release link + smoke report excerpt

## 14 — Post-release monitor (first 24h)

- [ ] `wp-content/debug.log` on a production install clean of new warnings/notices/fatals
- [ ] Zoho Desk / Crisp — no "broke after update" or "my customizations vanished" tickets
- [ ] Basecamp Bugs column (project 37499979) — no new cards matching the release
- [ ] Wbcom store update telemetry — no spike in failed updates

If any post-release signal is red → open a `<version>.1` patch cycle immediately.

---

## Failure protocol

If ANY row in sections 0–11 fails:

1. **Stop.** Do not tag or publish.
2. Fix in the release branch (per CLAUDE.md "no deferrals" rule — must ship in this cycle, not 5.2.0).
3. Re-run from Section 0 (branch hygiene) — a fix can regress earlier sections.
4. Only tag after the entire checklist is green in one continuous run.

## Emergency patch

For a genuinely emergency patch (security CVE, dataloss bug reaching production):

- The `--skip-browser-smoke` flag on the distribute script is allowed
- But sections 0–6 and 8–11 are still non-negotiable
- Document the skipped browser smoke in the release notes with a reason

## Version-specific additions

Append a section below for every release with the specific extra checks added that cycle. After 2 clean releases of a row, graduate it into the main checklist above.
