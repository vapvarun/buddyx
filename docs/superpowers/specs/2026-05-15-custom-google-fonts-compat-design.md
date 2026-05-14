# BuddyX — Custom Google Fonts Compatibility & Picker (5.1.x)

**Date:** 2026-05-15
**Status:** Approved design — ready for implementation plan
**Scope:** `wp-content/themes/buddyx` (branch `5.1.0`)

## Problem

BuddyX pre-5.1.0 used **Kirki**, whose typography control shipped the full
Google Fonts library as a picker *and* dynamically loaded whichever font a
site selected. The 5.1.0 Kirki removal replaced that with an in-house
`Typography` control that only knows the three `theme.json` fonts
(Inter, Newsreader, System UI).

Consequences for the ~3,000 existing installs:

- A site that picked, say, **Poppins** for headings via Kirki still has that
  value saved in its typography theme_mods, and `Output_Builder` still emits
  `font-family:Poppins;` — but **the Poppins webfont is never loaded**, because
  `get_google_fonts()` is a *static* hardcoded list that never scanned what the
  site actually selected. The text renders as a system fallback.
- The Customizer typography controls no longer offer any Google Font to pick.

This is a pre-existing regression introduced by the Kirki removal — not by the
recent Open Sans -> Inter base-font change. It must be fixed without ignoring
any customer's saved choice and without removing the font library.

## Goals

1. **Honour every saved selection.** Any `font-family` value already saved in a
   site's typography theme_mods must load and render again — whether or not it
   is in any bundled catalog.
2. **Restore the picker.** Customizer typography controls offer the full Google
   Fonts library again, like Kirki.
3. **Keep the GDPR story.** Reuse the existing "Load Google Fonts Locally"
   toggle so selected fonts can be self-hosted.
4. **New installs default to Inter** (already shipped) — unchanged.

## Non-goals

- No destructive migration of theme_mods. Saved values are honoured as-is.
- No change to the Inter/Newsreader self-hosted brand fonts.
- No Google Fonts API key requirement.

## Approach

Replicate Kirki's model — **catalog -> picker -> dynamic loader** — on the
in-house Customizer framework, reusing BuddyX's existing Webfont loader
(`inc/Webfont/class-buddyx-webfont-loader.php`) and the existing
"Load Google Fonts Locally" toggle. The loader becomes **value-driven**: it
loads what is saved in the database, so even a font absent from the bundled
catalog still works.

Catalog options considered:

- *Curated shortlist* — rejected: removes choice.
- *Google Fonts API* — rejected: per-site API key friction.
- **Bundle the full catalog (chosen)** — same as Kirki. ~195 KB, loaded **only**
  in the Customizer admin context, zero front-end weight, no API key, works
  offline. Goes stale slowly; refreshable by updating one file.

## Components

### 1. Bundled Google Fonts catalog

- New data file: `inc/Fonts/data/google-fonts.json` — the full Google Fonts
  list (`family`, `category`, `variants`), equivalent to Kirki's
  `webfonts.json` (~1,358 families).
- New reader class: `inc/Fonts/Google_Fonts_Catalog.php` — loads/caches the
  JSON, exposes `get_families()` (slug/label + variants) and
  `is_google_font( $family )`.
- Enqueued **only** on `customize_controls_enqueue_scripts` and passed to the
  Typography control JS. Never loaded on the front end.

### 2. Restore the picker in the Typography control

- Extend `Typography::available_font_families()` to return, in groups:
  - **Theme fonts** — `theme.json` families (Inter, Newsreader, System UI),
    shown first, plus the "Default (theme)" entry.
  - **Google Fonts** — the full catalog from component 1.
- The family `<select>` becomes **searchable** via a lightweight filter added
  to `customizer-controls.js` (no heavy dependency such as select2).
- If a saved value is not present in the catalog, the control injects it as a
  selected `<option>` so the dropdown always reflects the real saved value.

### 3. Dynamic font loader (compat-critical)

- Rewrite the body of `Fonts\Component::get_google_fonts()`: instead of a
  static list, **scan every registered typography theme_mod**, collect each
  saved `font-family` together with the weights/variants actually used
  (`variant` / `font-weight`), and drop the self-hosted/system families
  (Inter, Newsreader, System UI, and bare generic stacks).
- Return the remaining families to the **existing** `get_google_fonts_url()`
  and Webfont loader unchanged. The existing "Load Google Fonts Locally"
  toggle continues to govern embed-vs-self-host.
- Result: every existing site's saved selection loads again — catalog or not.

### 4. Defaults, migration, docs

- New installs: Inter default — already shipped, unchanged.
- Existing sites: theme_mods untouched; selections render correctly once
  component 3 is live. No migration routine.
- Changelog: document that new installs default to Inter and that existing
  font selections are preserved and now load via the in-house loader.

## Data flow

```
Customizer admin:
  google-fonts.json --> Google_Fonts_Catalog --> Typography control picker
                                              (Theme fonts + Google Fonts, searchable)

Front end:
  typography theme_mods --> get_google_fonts() [scans saved values]
                        --> get_google_fonts_url() --> Webfont loader
                        --> embed <link>  OR  self-host (per "Load Locally" toggle)
  Output_Builder --> emits font-family from saved theme_mod (already works)
```

## Error handling / edge cases

- Missing or malformed `google-fonts.json` -> catalog reader returns an empty
  list; picker falls back to theme.json fonts only; loader (value-driven) is
  unaffected.
- Saved value not in catalog -> still loaded by the value-driven loader and
  injected as a control option.
- Self-hosted families (Inter/Newsreader/System) -> excluded from the Google
  loader; never double-loaded.
- "Load Google Fonts Locally" on -> selected fonts downloaded to
  `wp-content/uploads/buddyx-local-fonts/` by the existing loader.
- No Google fonts selected anywhere -> `get_google_fonts()` returns empty,
  `get_google_fonts_url()` returns `''`, no Google request (current behaviour).

## Testing

- **Loader unit-ish:** `get_google_fonts()` returns the correct families +
  weights from a representative set of typography theme_mods; self-hosted
  families excluded.
- **Browser — compat:** seed a Kirki-style theme_mod
  (`h1_typography_option` -> `font-family: Poppins`); confirm Poppins is
  enqueued/`@font-face`-loaded and H1 renders Poppins.
- **Browser — picker:** family dropdown shows Theme fonts + full Google list,
  is searchable; selecting a Google font saves and loads it.
- **Browser — self-host:** "Load Google Fonts Locally" on -> font file appears
  in `uploads/buddyx-local-fonts/` and is served from the site domain.
- **Browser — new install:** no typography theme_mods -> only Inter/Newsreader
  self-hosted, no `fonts.googleapis` / `fonts.gstatic` request.
- **WPCS / PHP lint / stylelint** clean on all changed files.

## Files touched

- `inc/Fonts/data/google-fonts.json` — new
- `inc/Fonts/Google_Fonts_Catalog.php` — new
- `inc/Fonts/Component.php` — dynamic `get_google_fonts()`; enqueue catalog in
  customizer context
- `inc/Customizer_Framework/Controls/Typography.php` — grouped + Google-aware
  `available_font_families()`
- `inc/Customizer_Framework/assets/customizer-controls.js` — searchable family
  select; inject saved-but-missing value
- `readme.txt` — changelog entry
