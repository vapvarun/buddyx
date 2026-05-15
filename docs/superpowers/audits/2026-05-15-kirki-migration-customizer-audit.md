# BuddyX 5.1.0 — Kirki-Removal Customizer Migration Audit

**Date:** 2026-05-15
**Scope:** Every customizer setting registered on `master` (Kirki) vs `5.1.0` (in-house Customizer framework).
**Method:** Read-only diff of all 11 `inc/Kirki_Option/Fields/*.php` (master) vs `inc/Customizer_Settings/Fields/*.php` (5.1.0) file pairs, plus `Component.php` (panels/sections) and the `Customizer_Framework/Field.php` type map. No branch switching — master read via `git show master:<path>`.

**Why this audit exists:** the lost Google Fonts library was the *one symptom that got reported*. It surfaced the question: what *else* did the framework swap silently drop or change? This is the systematic check that should have run as part of the 5.1.0 work.

## Headline result — the migration is data-safe

| Axis | Result |
|---|---|
| Settings **dropped** | **1** — `custom-loader-divider` (a Kirki `Custom` HTML divider, stores no value). Zero customer data orphaned. |
| Setting IDs **renamed** | **0** — no saved theme_mod is orphaned. |
| Kirki control **types with no in-house equivalent** | **0** — `Field.php` maps every type used (`color`, `typography`, `radio_image`, `switch`←`Checkbox_Switch`, `dimension`, `custom`, `slider`, `radio_buttonset`, `select`, `radio`, `text`, `textarea`, `url`, `dropdown-pages`, `image`, `background`). |
| Panels / sections dropped | **0** — all map 1:1 with identical IDs/titles/priorities. |
| Sanitizer compatibility | Pre-5.1.0 `'on'/'off'` and `rgba()` saved values are explicitly handled (`sanitize_bool_int`, `sanitize_color_alpha`). |

So: no dropped settings of value, no renamed IDs, no missing control types. The remaining risks are **default-value changes** and **one control-capability reduction** — not data loss.

## Findings that need a decision (silent changes on upgrade)

### 1. `site_loader` default flipped `'2'` → `0` — HIGHEST RISK
- **File:** `General_Fields.php`
- master default `'2'` (loader shown, style 2). 5.1.0 default `0` (loader off). The 5.1.0 description text literally says "Off by default."
- **Impact:** any upgraded site that never explicitly set `site_loader` **loses its loading animation** after updating.
- **Decision needed:** is OFF-by-default the intended product decision (then: document in changelog), or should upgraders keep the loader (then: preserve `'2'` as the default, or add a migration)? This is the same preserve-vs-document tension as the Open Sans→Inter base-font change.

### 2. `sub_header_background_setting` background-color default `rgba(255,255,255,0.5)` → `''`
- **File:** `Sub_Header_Fields.php`
- Intentional per inline comment (avoid a hardcoded rgba clashing with dark mode / inline CSS).
- **Impact:** sub-header background appearance changes for sites that never enabled `site_sub_header_bg`.
- **Decision needed:** confirm intended; changelog mention.

### 3. Typography Google Fonts library loss — KNOWN, FIXED
- **File:** `Typography_Fields.php` (all 12 typography settings).
- Kirki's typography control shipped the full Google Fonts library in the family dropdown; the in-house control initially did not.
- **Status:** fixed in the 5.1.0 work — bundled catalog + restored searchable picker + value-driven dynamic loader (commits `d87b888`..`4aad6ac`). Typography *color* sub-fields were NOT lost — they are handled by the preserved Skin `*_typography_option[color]` fields.

### 4. Typography default values retuned — cosmetic
- Site title, tagline, body defaults changed (e.g. site title `38px/600` → `40px/700 newsreader`, body `variant 'regular'` → `'400'`).
- Only affects sites on theme defaults; saved customer values untouched. Changelog mention.

## Per-file summary

| File | Dropped | Type changed | Renamed | Capability reduced | Notes |
|---|---|---|---|---|---|
| General_Fields | 0 | 0 | 0 | 0 | `site_loader` default `'2'`→`0` (Finding 1); 6 new Site Loader sub-settings added |
| Typography_Fields | 0 | 0 | 0 | 12 (font library — Finding 3, fixed) | defaults retuned (Finding 4) |
| Header_Fields | 0 | 0 | 0 | 0 | identical 1:1 (5 settings) |
| Sub_Header_Fields | 0 | 0 | 0 | 0 | `sub_header_background_setting` default change (Finding 2) |
| Skin_Fields | 1 (`custom-loader-divider`, no value) | 0 | 0 | 0 | 38 colour settings intact; `site_loader_bg` relocated to General with ID preserved; 10+ new color-mode/style-variation settings |
| Blog_Fields | 0 | 0 | 0 | 0 | identical (13 settings) |
| Sidebar_Fields | 0 | 0 | 0 | 0 | identical (≤8 settings) |
| Footer_Fields | 0 | 0 | 0 | 0 | identical (3 settings) |
| Site_Performance | 0 | 0 | 0 | 0 | identical (3 settings) |
| WP_Login_Fields | 0 | 0 | 0 | 0 | identical (≤10 settings) |
| BuddyPress_Fields | 0 | 0 | 0 | 0 | identical (1 setting) |
| Component.php | 0 panels/sections | — | — | — | all panels/sections 1:1 |

## Recommended next steps

1. **Decide Finding 1 (`site_loader` default).** Highest-visibility upgrade regression. Either preserve `'2'` for upgraders, or accept off-by-default and add a clear changelog line.
2. **Confirm Finding 2** is intended; add a changelog line.
3. Findings 3 & 4 — already handled / changelog only.
4. No code change required for data integrity — the migration preserved every setting that holds a customer value.
