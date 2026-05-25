# Screenshot Capture Protocol (free BuddyX)

This protocol guarantees every documentation screenshot reflects the actual current 5.1.0 surface — no stale 5.0.x captures, no pro-only UI accidentally embedded in free docs.

> **Stakeholder rule**: "all screenshots must be right." A wrong screenshot is worse than no screenshot — it teaches the customer something false and erodes trust on the very first impression.

---

## Source of truth

`tools/capture-map.json` is the canonical list of screenshots the free docs need. Each entry declares:

| Field | Purpose |
|---|---|
| `surface` | `customizer` or `frontend` |
| `target` | Customizer autofocus query OR frontend pathname |
| `prep` | Human instruction for state setup (scroll, picker open, etc.) |
| `viewport` | `1200x900` (the wp.org `screenshot.png` spec) |
| `dark` | `true` if the capture must be in dark mode |
| `path` | Save path relative to `docs/website/images/` (WebP format) |
| `doc_use` | Which doc consumes this image |

Add or remove entries here when docs evolve. **Do not add a screenshot to a doc page without first adding the entry to `capture-map.json`.**

---

## When a doc page changes

If you edit a customer-facing doc and:

- Add a reference to a screenshot that doesn't exist yet → add an entry to `capture-map.json`, then capture.
- Change which Customizer setting / panel is referenced → re-check that the existing screenshot still matches.
- Remove a reference → leave the screenshot file alone for now, but mark the `capture-map.json` entry as `"deprecated": true` so the next QA pass can prune.

---

## Capture run

Prerequisites:

1. Local site at `http://buddyx.local` (or set `BUDDYX_BASE_URL`)
2. **Active theme: `buddyx` (free)** — verify with `wp theme list --status=active --field=name`
3. **Logged-in admin** via the `?autologin=1` mu-plugin (or `BUDDYX_AUTOLOGIN_KEY` env)
4. **Clean Customizer state** — capture defaults, not your dev-time experiments
5. Playwright installed: `npm install --save-dev playwright && npx playwright install chromium`
6. `cwebp` binary for PNG → WebP conversion: `brew install webp` (macOS) or `apt install webp` (Linux)

Run:

```bash
node tools/capture-screenshots.mjs
```

Optional flags:
- `--filter=customizer-site-skin` — only capture entries whose path matches the filter
- `--dry-run` — print what would be captured without writing files
- `--verbose` / `-v` — extra logging

Outputs land in `docs/website/images/` matching the `path` field.

---

## Visual verification (mandatory)

Before committing a captured screenshot, **open the image and compare to the live site**:

1. Open the WebP file in Preview / GIMP / a browser
2. In a separate browser tab, open the same surface live (`?autofocus[section]=...` or `yoursite.com/path/`)
3. Confirm:
   - Same panel/section title visible
   - Same setting labels in the same order
   - Same default values (if shooting defaults)
   - Same color palette (if shooting Light mode)
   - No admin bar visible (32px top gap means you forgot to logout)
   - No browser scroll bar artifacts
   - No JS errors visible

If anything doesn't match, **delete the screenshot and re-capture** after fixing the prep state.

---

## What NOT to capture

- Per-customer-data states (e.g., a particular customer's saved customizer values). Capture defaults only.
- States that depend on a plugin not declared in BuddyPress's standard install (BuddyPress is fine; Jetonomy / MediaVerse are not standard).
- States the doc doesn't actually use. The capture-map is the contract — if a screenshot's `doc_use` is empty, the screenshot shouldn't exist.

---

## Theme-active check

Capture script does NOT verify the active theme. Run this manually before each capture pass:

```bash
wp --path="$HOME/Local Sites/buddyx/app/public" theme list --status=active --field=name
```

Must output exactly: `buddyx` (no Pro suffix). If you see `buddyx-pro`, switch with:

```bash
wp --path="..." theme activate buddyx
```

> Forgetting this and capturing while pro is active is the #1 way docs go wrong. Pro and free share the same `buddyx.local` site URL — only the active theme distinguishes them.

---

## Updating screenshots when the customizer changes

When a customizer field is added/removed/renamed in `inc/Customizer_Settings/Fields/*.php`:

1. Regenerate the inventory snapshot: `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt`
2. Diff the snapshot in the PR — every field rename / add / removal shows up
3. For every doc page referencing the changed setting, check whether its existing screenshot is now stale
4. Update the relevant capture-map entry and re-capture
5. **Commit the new screenshot alongside the doc change** — never let the doc claim a setting exists with a screenshot that doesn't show it

---

## Pre-commit guard (future)

Future enhancement: a pre-commit hook that:

1. Parses every `.md` file under `docs/website/` for `![...](images/...)` references
2. Verifies each referenced image exists
3. Cross-checks each image against `capture-map.json` (no orphan images)
4. Blocks the commit if a doc references a missing image OR an image isn't tracked by the map

Until that hook exists, this protocol is the manual guard. Reviewers should run the verification pass on every PR that adds/changes screenshots.

---

## Related

- [Local CI](../local-ci.md) — pre-commit hook chain (does not yet include screenshot verification)
- [COVERAGE_MATRIX.md](../COVERAGE_MATRIX.md) — customizer-section → plan-doc mapping
- [Customizer inventory snapshot](../customizer-inventory-snapshot.txt) — drift detection for customizer fields
- Pro equivalent: `wp-content/themes/buddyx-pro/docs/qa/screenshot-capture-protocol.md`
