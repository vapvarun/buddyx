# BuddyX 5.0.3 — Premium Pattern Library v1

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Lift BuddyX's 19 existing block patterns to Ollie-quality premium UX, then add 8 missing pattern types in the same premium style — repositioning BuddyX from "BuddyPress theme" to "general-purpose theme with a designer-grade pattern library."

**Architecture:** Three-layer bottom-up approach. (1) Upgrade `theme.json` design tokens (semantic colors, fluid type scale, self-hosted Inter + Newsreader fonts, spacing scale, gradients, block style variations) — without this, patterns can only be as polished as the cardboard they paint on. (2) Redesign all 19 existing patterns against the new tokens, retiring 5 that don't deserve restyling. (3) Add 8 new pattern types (testimonials, team, stats, CTA-banner, logo-cloud, about-image, steps, services-grid) using the same composition rules as the redesigned ones, so the library reads as one coherent system.

**Tech Stack:** PHP for pattern files, WordPress theme.json v3, core blocks only (cover, columns, group, heading, paragraph, buttons, image, gallery, list, separator, spacer, media-text, query). Self-hosted WOFF2 fonts (no Google Fonts API). Lightning CSS build pipeline (already in repo). Playwright MCP for browser verification at 1280px / 768px / 390px.

**Branch:** `5.0.3` — the current release branch. This work absorbs the [5.0.3] P1 "Add more general-purpose block patterns" card and incidentally satisfies the P1 "Rewrite wp.org description" and P1 "New screenshot" cards (the rebrand-to-general-purpose is achieved here through pattern copy, demo content, and design tokens). Versioning: `5.0.3` is treated as a feature release, not a patch — even though it's the third dot version, the changelog will lead with "Major UI refresh" and explicitly list removed pattern slugs (`hero-main`, `hero-two`, `hero-count`, `general-banner`, `footer-default`) under a "Breaking Changes" heading so users know to audit pages built with those patterns before updating.

**Risk acknowledgement (carried from original 5.1 plan):** theme.json palette/type-scale changes are foundational, retiring patterns is a breaking change for sites already using them, and self-hosted fonts add ~140kb. Per the user's direction, all three are absorbed in `5.0.3` rather than deferred to `5.1`. Mitigations: legacy color slugs retained for back-compat (Task 1); changelog Breaking Changes section (Task 25); woff2-only latin subset to keep font budget tight (Task 2 step 6).

**Quality bar:** Match Ollie theme (https://olliewp.com) — typography-led compositions, generous whitespace, asymmetric layouts, modern type pairings, real demo copy (no Lorem ipsum), consistent rhythm across all patterns.

**Constraints (non-negotiable for wp.org review):**
- Core blocks only — no custom block code, no plugin dependencies
- Self-hosted fonts — no external API calls (GDPR/wp.org rule)
- No BuddyPress, WooCommerce, or LearnDash references in default copy
- Each pattern verified at 1280px / 768px / 390px before its commit
- Existing color slugs (`primary`, `secondary`, `red`, `green`, `blue`, `yellow`, `black`, `grey`, `white`) retained for back-compat — new tokens are additive

---

## File Structure

### New files (theme system)

```
theme.json                                  (rewritten in Task 1, kept in place)
assets/fonts/inter/Inter-Regular.woff2      (Task 2)
assets/fonts/inter/Inter-Medium.woff2       (Task 2)
assets/fonts/inter/Inter-SemiBold.woff2     (Task 2)
assets/fonts/inter/Inter-Bold.woff2         (Task 2)
assets/fonts/inter/LICENSE.txt              (Task 2)
assets/fonts/newsreader/Newsreader-Regular.woff2     (Task 2)
assets/fonts/newsreader/Newsreader-Italic.woff2      (Task 2)
assets/fonts/newsreader/Newsreader-Medium.woff2      (Task 2)
assets/fonts/newsreader/LICENSE.txt                  (Task 2)
styles/                                     (theme.json style variations dir)
styles/dark.json                            (Task 4 — dark mode style variation)
styles/editorial.json                       (Task 4 — serif-heavy variant)
```

### New files (assets — pattern imagery)

```
assets/images/patterns/hero-typography-bg.svg          (Task 9)
assets/images/patterns/hero-split-portrait.jpg         (Task 10)
assets/images/patterns/hero-image-led.jpg              (Task 11)
assets/images/patterns/about-story-1.jpg               (Task 13)
assets/images/patterns/about-founder.jpg               (Task 14)
assets/images/patterns/services-icon-set/*.svg         (Task 17 — 6 SVGs)
assets/images/patterns/testimonial-avatars/*.jpg       (Task 19 — 6 avatars)
assets/images/patterns/logos/*.svg                     (Task 21 — 6 brand logos as SVG)
assets/images/patterns/team/*.jpg                      (Task 14 — 4 team portraits)
```
**Source policy:** all photographic assets sourced from Pexels (CC0) or shot in-house — record source in `assets/images/patterns/SOURCES.md`. SVG ornaments authored in-house.

### Existing files modified

```
inc/Base_Support/Component.php          (lines 209-268: pattern category list, registration)
assets/css/src/_blocks-style.css        (light updates for new style variations)
inc/Styles/Component.php                (font enqueue if needed beyond theme.json)
style.css                                (version bump 5.0.2 → 5.0.3)
readme.txt                               (changelog + version)
```

### Pattern files (the bulk of the work)

**Retired** (deleted from `inc/Base_Support/patterns/` and from registration array):
- `hero-main.php` (BuddyPress copy locked + dated layout)
- `hero-two.php` (Lorem ipsum + placeholder icon)
- `hero-count.php` (cliché stats — replaced by social-proof-stats)
- `general-banner.php` (2018 ornament aesthetic)
- `footer-default.php` (redundant with `footer-default-mega`)

**Restyled** (rewritten in place — same filename, completely new content):
- `general-banner-light.php` → modern asymmetric CTA strip
- `general-faq.php` → typography-led accordion with question-as-headline
- `general-features-light.php` → alternating image+text with no icon clutter
- `general-pricing.php` → premium 3-tier with one accent column raised
- `footer-central.php` → minimalist with logo + nav + sub-line
- `footer-default-mega.php` → editorial 4-col with newsletter input
- `footer-mega.php` → 5-col with featured testimonial corner
- `footer-simple.php` → centered logo + nav + social row
- `footer-small.php` → compact copyright bar (refined typography)
- `query-cover-featured.php` → magazine grid with one hero post + 3-col secondary
- `query-cover-grid.php` → editorial 3-col with category labels
- `query-grid-excerpt.php` → asymmetric 2-col (1 large + stack)
- `query-listbig.php` → list with horizontal split image+text
- `query-simple-list.php` → minimal date + title list

**New patterns added** (8, matching same design language):
- `hero-typography-led.php` (Task 9)
- `hero-split-screen.php` (Task 10)
- `hero-image-led.php` (Task 11)
- `about-story.php` (Task 13)
- `about-founder.php` (Task 14)
- `social-proof-stats.php` (Task 16)
- `social-proof-testimonials.php` (Task 19)
- `social-proof-logos.php` (Task 21)
- `cta-fullbleed.php` (Task 23)
- `cta-newsletter.php` (Task 24)
- `services-grid.php` (Task 17)
- `team-grid.php` (Task 14 sibling)
- `steps.php` (Task 18)

**Total after redesign:** 14 restyled + 13 new = **27 premium patterns** (vs current 19 mid-tier).

### Categories (renamed from 4 → 6)

```php
// In inc/Base_Support/Component.php:
'buddyx-hero'        // 4 patterns (was 3)
'buddyx-about'       // 2 patterns (new category)
'buddyx-features'    // 4 patterns (was 'general' partial: features-light, services-grid, steps, cta-fullbleed)
'buddyx-social-proof'// 3 patterns (new category)
'buddyx-pricing-faq' // 2 patterns (pricing + faq)
'buddyx-cta'         // 2 patterns (banner-light, newsletter)
'buddyx-footer'      // 5 patterns (was 6 — minus footer-default)
'buddyx-query'       // 5 patterns (existing 5, restyled)
```

---

## Task 1: theme.json — color system

**Files:**
- Modify: `theme.json` (full rewrite of `settings.color` section)

**Why:** Replace the generic 11-color palette with semantic tokens (`base`, `contrast`, `accent`, `accent-2`, `accent-3`, `surface-1/2/3`) that patterns can target consistently. Keep the 9 legacy slugs registered so existing user content using `var(--wp--preset--color--primary)` etc. still resolves.

- [ ] **Step 1: Read current theme.json color block**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
python3 -c "import json; t=json.load(open('theme.json')); print(json.dumps(t['settings']['color'], indent=2))"
```
Expected: shows the current 11-color palette.

- [ ] **Step 2: Replace `settings.color.palette` with the new semantic + legacy palette**

Edit `theme.json`. The full new palette block:

```json
"color": {
  "palette": [
    { "slug": "base",        "color": "#FFFFFF", "name": "Base" },
    { "slug": "base-2",      "color": "#FAFAFA", "name": "Base 2" },
    { "slug": "base-3",      "color": "#F4F4F4", "name": "Base 3" },
    { "slug": "contrast",    "color": "#1A1A1A", "name": "Contrast" },
    { "slug": "contrast-2",  "color": "#3D3D3D", "name": "Contrast 2" },
    { "slug": "contrast-3",  "color": "#6E6E6E", "name": "Contrast 3" },
    { "slug": "accent",      "color": "#EF5455", "name": "Accent" },
    { "slug": "accent-2",    "color": "#41848F", "name": "Accent 2" },
    { "slug": "accent-3",    "color": "#F4D35E", "name": "Accent 3" },
    { "slug": "surface-1",   "color": "#FFF8F2", "name": "Surface Warm" },
    { "slug": "surface-2",   "color": "#F2F7F8", "name": "Surface Cool" },
    { "slug": "surface-3",   "color": "#1F2937", "name": "Surface Dark" },
    { "slug": "primary",     "color": "#ef5455", "name": "Primary" },
    { "slug": "secondary",   "color": "#41848f", "name": "Secondary" },
    { "slug": "red",         "color": "#c0392b", "name": "Red" },
    { "slug": "green",       "color": "#27ae60", "name": "Green" },
    { "slug": "blue",        "color": "#2980b9", "name": "Blue" },
    { "slug": "yellow",      "color": "#f1c40f", "name": "Yellow" },
    { "slug": "black",       "color": "#1c2833", "name": "Black" },
    { "slug": "grey",        "color": "#95a5a6", "name": "Grey" },
    { "slug": "white",       "color": "#ecf0f1", "name": "White" },
    { "slug": "custom-daylight", "color": "#97c0b7", "name": "Dusty Daylight" },
    { "slug": "custom-sun",      "color": "#eee9d1", "name": "Dusty Sun" }
  ],
  "gradients": [
    { "slug": "subtle-base",   "name": "Subtle Base",   "gradient": "linear-gradient(180deg, #FFFFFF 0%, #FAFAFA 100%)" },
    { "slug": "warm-glow",     "name": "Warm Glow",     "gradient": "linear-gradient(135deg, #FFF8F2 0%, #F4D35E 100%)" },
    { "slug": "cool-mist",     "name": "Cool Mist",     "gradient": "linear-gradient(135deg, #F2F7F8 0%, #97C0B7 100%)" },
    { "slug": "accent-bold",   "name": "Accent Bold",   "gradient": "linear-gradient(135deg, #EF5455 0%, #41848F 100%)" },
    { "slug": "dark-velvet",   "name": "Dark Velvet",   "gradient": "linear-gradient(135deg, #1F2937 0%, #1A1A1A 100%)" }
  ],
  "duotone": [
    { "slug": "base-contrast", "name": "Base / Contrast", "colors": ["#FFFFFF","#1A1A1A"] },
    { "slug": "warm",          "name": "Warm",            "colors": ["#FFF8F2","#EF5455"] }
  ]
}
```

- [ ] **Step 3: Verify the JSON is valid**

```bash
python3 -c "import json; json.load(open('theme.json')); print('valid')"
```
Expected: `valid`

- [ ] **Step 4: Browser-verify the editor still loads with the new palette**

Use the Playwright MCP `browser_navigate` to `http://buddyx.local/wp-admin/post-new.php?post_type=page` and confirm:
- The block editor loads without console errors
- `wp.data.select('core').getSettings().__experimentalFeatures.color.palette.theme` includes both new (`base`, `accent`) and legacy (`primary`, `red`) slugs

- [ ] **Step 5: Commit**

```bash
git add theme.json
git commit -m "feat(theme): semantic color tokens + 5 curated gradients

Adds base/contrast/accent/surface semantic palette while retaining all
9 legacy slugs (primary, secondary, red, green, blue, yellow, black,
grey, white) for back-compat with existing user content. Adds 5
gradients and 2 duotones for cover blocks. Foundation for the 5.1
premium pattern library."
```

---

## Task 2: Bundle Inter + Newsreader fonts (self-hosted)

**Files:**
- Create: `assets/fonts/inter/Inter-Regular.woff2`, `Inter-Medium.woff2`, `Inter-SemiBold.woff2`, `Inter-Bold.woff2`
- Create: `assets/fonts/inter/LICENSE.txt`
- Create: `assets/fonts/newsreader/Newsreader-Regular.woff2`, `Newsreader-Italic.woff2`, `Newsreader-Medium.woff2`
- Create: `assets/fonts/newsreader/LICENSE.txt`

**Why:** wp.org theme review forbids loading external fonts at runtime (GDPR). Self-host Inter (sans for body/UI) and Newsreader (serif for display/editorial accents) — both Open Font License.

- [ ] **Step 1: Download Inter via google-webfonts-helper**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
mkdir -p assets/fonts/inter assets/fonts/newsreader
curl -sLo /tmp/inter.zip "https://gwfh.mranftl.com/api/fonts/inter?download=zip&subsets=latin&variants=regular,500,600,700&formats=woff2"
unzip -j /tmp/inter.zip "*regular*" "*500*" "*600*" "*700*" -d assets/fonts/inter/
ls assets/fonts/inter/
```
Expected: 4 woff2 files (Inter-Regular, Inter-Medium=500, Inter-SemiBold=600, Inter-Bold=700).

- [ ] **Step 2: Rename to canonical names**

```bash
cd assets/fonts/inter
for f in *.woff2; do
  case "$f" in
    *regular*)  mv "$f" Inter-Regular.woff2 ;;
    *500*)      mv "$f" Inter-Medium.woff2 ;;
    *600*)      mv "$f" Inter-SemiBold.woff2 ;;
    *700*)      mv "$f" Inter-Bold.woff2 ;;
  esac
done
ls
```
Expected: Inter-Regular.woff2, Inter-Medium.woff2, Inter-SemiBold.woff2, Inter-Bold.woff2.

- [ ] **Step 3: Add Inter LICENSE**

Write `assets/fonts/inter/LICENSE.txt`:

```
Inter typeface
Copyright (c) Rasmus Andersson — https://rsms.me/inter/
Licensed under the SIL Open Font License, Version 1.1
https://scripts.sil.org/OFL
```

- [ ] **Step 4: Download Newsreader the same way**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
curl -sLo /tmp/newsreader.zip "https://gwfh.mranftl.com/api/fonts/newsreader?download=zip&subsets=latin&variants=regular,italic,500&formats=woff2"
unzip -j /tmp/newsreader.zip -d assets/fonts/newsreader/
cd assets/fonts/newsreader
for f in *.woff2; do
  case "$f" in
    *regular*) mv "$f" Newsreader-Regular.woff2 ;;
    *italic*)  mv "$f" Newsreader-Italic.woff2 ;;
    *500*)     mv "$f" Newsreader-Medium.woff2 ;;
  esac
done
ls
```
Expected: 3 woff2 files.

- [ ] **Step 5: Add Newsreader LICENSE**

Write `assets/fonts/newsreader/LICENSE.txt`:

```
Newsreader typeface
Copyright (c) Production Type — https://github.com/productiontype/Newsreader
Licensed under the SIL Open Font License, Version 1.1
https://scripts.sil.org/OFL
```

- [ ] **Step 6: Verify total added bundle size is under budget**

```bash
du -sh assets/fonts/inter assets/fonts/newsreader
```
Expected: Both directories combined under 200kb. If any single file is over 60kb, abort and use a smaller subset.

- [ ] **Step 7: Commit**

```bash
git add assets/fonts/
git commit -m "feat(fonts): bundle Inter + Newsreader self-hosted

Bundles 4 Inter weights (400/500/600/700) and 3 Newsreader weights
(400/500 + italic 400) as woff2 subsets. Self-hosted to satisfy
wp.org no-external-fonts requirement. ~140kb total."
```

---

## Task 3: theme.json — register fontFamilies + fontFace

**Files:**
- Modify: `theme.json` (`settings.typography.fontFamilies`)

- [ ] **Step 1: Add fontFamilies block to theme.json**

In `theme.json`, replace `settings.typography` with:

```json
"typography": {
  "dropCap": false,
  "fluid": true,
  "fontSizes": [
    { "slug": "x-small", "size": "0.75rem",                            "name": "Extra Small" },
    { "slug": "small",   "size": "clamp(0.875rem, 0.75vw + 0.7rem, 1rem)",         "name": "Small" },
    { "slug": "medium",  "size": "clamp(1rem, 0.4vw + 0.95rem, 1.125rem)",         "name": "Medium" },
    { "slug": "large",   "size": "clamp(1.25rem, 1vw + 1.05rem, 1.5rem)",          "name": "Large" },
    { "slug": "x-large", "size": "clamp(1.875rem, 2vw + 1.5rem, 2.5rem)",          "name": "Extra Large" },
    { "slug": "xx-large","size": "clamp(2.5rem, 3vw + 1.8rem, 3.75rem)",           "name": "Display" },
    { "slug": "mega",    "size": "clamp(3.75rem, 6vw + 2rem, 6rem)",               "name": "Mega" },
    { "slug": "larger",  "size": "39px", "name": "Legacy Larger" }
  ],
  "fontFamilies": [
    {
      "slug": "inter",
      "name": "Inter",
      "fontFamily": "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
      "fontFace": [
        { "fontFamily": "Inter", "fontWeight": "400", "fontStyle": "normal", "src": ["file:./assets/fonts/inter/Inter-Regular.woff2"] },
        { "fontFamily": "Inter", "fontWeight": "500", "fontStyle": "normal", "src": ["file:./assets/fonts/inter/Inter-Medium.woff2"] },
        { "fontFamily": "Inter", "fontWeight": "600", "fontStyle": "normal", "src": ["file:./assets/fonts/inter/Inter-SemiBold.woff2"] },
        { "fontFamily": "Inter", "fontWeight": "700", "fontStyle": "normal", "src": ["file:./assets/fonts/inter/Inter-Bold.woff2"] }
      ]
    },
    {
      "slug": "newsreader",
      "name": "Newsreader",
      "fontFamily": "'Newsreader', Georgia, serif",
      "fontFace": [
        { "fontFamily": "Newsreader", "fontWeight": "400", "fontStyle": "normal", "src": ["file:./assets/fonts/newsreader/Newsreader-Regular.woff2"] },
        { "fontFamily": "Newsreader", "fontWeight": "400", "fontStyle": "italic", "src": ["file:./assets/fonts/newsreader/Newsreader-Italic.woff2"] },
        { "fontFamily": "Newsreader", "fontWeight": "500", "fontStyle": "normal", "src": ["file:./assets/fonts/newsreader/Newsreader-Medium.woff2"] }
      ]
    },
    {
      "slug": "system",
      "name": "System UI",
      "fontFamily": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif"
    }
  ]
}
```

- [ ] **Step 2: Add styles.typography (default body uses Inter)**

In `theme.json`, set `styles.typography`:

```json
"typography": {
  "fontFamily": "var(--wp--preset--font-family--inter)",
  "fontWeight": "400",
  "lineHeight": "1.6",
  "fontSize": "var(--wp--preset--font-size--medium)"
}
```

And `styles.elements.h1` through `h6` to use Newsreader for editorial heading variants:

```json
"elements": {
  "h1": { "typography": { "fontFamily": "var(--wp--preset--font-family--inter)", "fontWeight": "700", "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontSize": "var(--wp--preset--font-size--xx-large)" } },
  "h2": { "typography": { "fontFamily": "var(--wp--preset--font-family--inter)", "fontWeight": "700", "lineHeight": "1.15", "letterSpacing": "-0.015em", "fontSize": "var(--wp--preset--font-size--x-large)" } },
  "h3": { "typography": { "fontFamily": "var(--wp--preset--font-family--inter)", "fontWeight": "600", "lineHeight": "1.2", "fontSize": "var(--wp--preset--font-size--large)" } }
}
```

- [ ] **Step 3: Validate JSON**

```bash
python3 -c "import json; json.load(open('theme.json')); print('valid')"
```

- [ ] **Step 4: Browser-verify fonts load on the front-end**

Navigate Playwright to `http://buddyx.local/?autologin=1` and run in the page:

```js
() => Array.from(document.fonts).filter(f => /Inter|Newsreader/.test(f.family)).map(f => ({ family: f.family, weight: f.weight, status: f.status }))
```
Expected: at least one Inter entry with status `loaded`. (Newsreader loads on demand.)

- [ ] **Step 5: Commit**

```bash
git add theme.json
git commit -m "feat(theme): fluid type scale + Inter & Newsreader font families

8-step type scale using clamp() for fluid responsive sizing
(x-small → mega). Body uses Inter via theme.json fontFamilies; H1-H3
get tighter letter-spacing and refined line-heights. Newsreader
available as serif accent for display use in patterns. Legacy
'larger' slug (39px) kept for back-compat."
```

---

## Task 4: theme.json — spacing scale + style variations

**Files:**
- Modify: `theme.json` (`settings.spacing`, `settings.layout`, `styles.spacing`)
- Create: `styles/dark.json`
- Create: `styles/editorial.json`

- [ ] **Step 1: Add spacing scale**

In `theme.json`, set `settings.spacing`:

```json
"spacing": {
  "blockGap": true,
  "margin": true,
  "padding": true,
  "units": ["%", "px", "em", "rem", "vh", "vw"],
  "spacingScale": { "operator": "*", "increment": 1.5, "steps": 0, "mediumStep": 1.5, "unit": "rem" },
  "spacingSizes": [
    { "slug": "10",  "size": "0.5rem",  "name": "1 (8px)" },
    { "slug": "20",  "size": "1rem",    "name": "2 (16px)" },
    { "slug": "30",  "size": "1.5rem",  "name": "3 (24px)" },
    { "slug": "40",  "size": "2rem",    "name": "4 (32px)" },
    { "slug": "50",  "size": "3rem",    "name": "5 (48px)" },
    { "slug": "60",  "size": "4rem",    "name": "6 (64px)" },
    { "slug": "70",  "size": "5rem",    "name": "7 (80px)" },
    { "slug": "80",  "size": "6rem",    "name": "8 (96px)" },
    { "slug": "90",  "size": "8rem",    "name": "9 (128px)" },
    { "slug": "100", "size": "10rem",   "name": "10 (160px)" }
  ]
}
```

- [ ] **Step 2: Update layout.contentSize and wideSize**

```json
"layout": {
  "contentSize": "720px",
  "wideSize": "1200px"
}
```

- [ ] **Step 3: Create `styles/dark.json` style variation**

Write `styles/dark.json` (theme.json schema, partial — only the differences):

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "title": "Dark",
  "settings": {
    "color": {
      "palette": [
        { "slug": "base",       "color": "#0F0F0F", "name": "Base" },
        { "slug": "base-2",     "color": "#1A1A1A", "name": "Base 2" },
        { "slug": "base-3",     "color": "#262626", "name": "Base 3" },
        { "slug": "contrast",   "color": "#FAFAFA", "name": "Contrast" },
        { "slug": "contrast-2", "color": "#D4D4D4", "name": "Contrast 2" },
        { "slug": "contrast-3", "color": "#A3A3A3", "name": "Contrast 3" },
        { "slug": "accent",     "color": "#F87171", "name": "Accent" },
        { "slug": "accent-2",   "color": "#5EAEB8", "name": "Accent 2" },
        { "slug": "accent-3",   "color": "#FACC15", "name": "Accent 3" },
        { "slug": "surface-1",  "color": "#1F1A14", "name": "Surface Warm" },
        { "slug": "surface-2",  "color": "#14191F", "name": "Surface Cool" },
        { "slug": "surface-3",  "color": "#FFFFFF", "name": "Surface Dark" }
      ]
    }
  },
  "styles": {
    "color": { "background": "var(--wp--preset--color--base)", "text": "var(--wp--preset--color--contrast)" }
  }
}
```

- [ ] **Step 4: Create `styles/editorial.json` (Newsreader-led variant)**

Write `styles/editorial.json`:

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "title": "Editorial",
  "styles": {
    "typography": { "fontFamily": "var(--wp--preset--font-family--newsreader)" },
    "elements": {
      "h1": { "typography": { "fontFamily": "var(--wp--preset--font-family--newsreader)", "fontStyle": "normal", "fontWeight": "500" } },
      "h2": { "typography": { "fontFamily": "var(--wp--preset--font-family--newsreader)", "fontWeight": "500" } }
    }
  }
}
```

- [ ] **Step 5: Browser-verify style variation switcher in Site Editor**

Navigate Playwright to `http://buddyx.local/wp-admin/site-editor.php`. Open the Styles panel — confirm "Dark" and "Editorial" appear as variations.

- [ ] **Step 6: Commit**

```bash
git add theme.json styles/
git commit -m "feat(theme): 10-step spacing scale + Dark and Editorial style variations

Adds named spacing presets (10-100, ~8px–160px) for consistent
rhythm across patterns. Dark variation flips base/contrast and
shifts accent for low-light mode. Editorial variation switches
body and headings to Newsreader for serif-led layouts. Layout
content size 720px / wide 1200px (was unset)."
```

---

## Task 5: Block style variations (button, group, separator)

**Files:**
- Create: `inc/Base_Support/block-styles.php`
- Modify: `inc/Base_Support/Component.php` (require new file)

- [ ] **Step 1: Create `inc/Base_Support/block-styles.php`**

```php
<?php
/**
 * Block style variations for the BuddyX premium pattern library.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Base_Support;

if ( ! function_exists( __NAMESPACE__ . '\\register_block_styles' ) ) {
	function register_block_styles() {
		register_block_style( 'core/button', array(
			'name'  => 'outline-accent',
			'label' => __( 'Outline Accent', 'buddyx' ),
		) );
		register_block_style( 'core/button', array(
			'name'  => 'link-arrow',
			'label' => __( 'Link with Arrow', 'buddyx' ),
		) );
		register_block_style( 'core/separator', array(
			'name'  => 'gradient',
			'label' => __( 'Gradient', 'buddyx' ),
		) );
		register_block_style( 'core/separator', array(
			'name'  => 'dotted',
			'label' => __( 'Dotted', 'buddyx' ),
		) );
		register_block_style( 'core/group', array(
			'name'  => 'card',
			'label' => __( 'Card', 'buddyx' ),
		) );
		register_block_style( 'core/group', array(
			'name'  => 'bordered',
			'label' => __( 'Bordered', 'buddyx' ),
		) );
		register_block_style( 'core/quote', array(
			'name'  => 'editorial',
			'label' => __( 'Editorial', 'buddyx' ),
		) );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_block_styles' );
```

- [ ] **Step 2: Add CSS for the new styles to `assets/css/src/_blocks-style.css`**

Append:

```css
/* Block style variations — premium pattern library */
.wp-block-button.is-style-outline-accent .wp-block-button__link {
	background: transparent;
	color: var(--wp--preset--color--accent);
	border: 1px solid var(--wp--preset--color--accent);
}
.wp-block-button.is-style-outline-accent .wp-block-button__link:hover {
	background: var(--wp--preset--color--accent);
	color: var(--wp--preset--color--base);
}
.wp-block-button.is-style-link-arrow .wp-block-button__link {
	background: transparent;
	color: var(--wp--preset--color--contrast);
	padding: 0;
	font-weight: 600;
}
.wp-block-button.is-style-link-arrow .wp-block-button__link::after {
	content: " →";
	transition: transform 0.2s ease;
	display: inline-block;
}
.wp-block-button.is-style-link-arrow .wp-block-button__link:hover::after {
	transform: translateX(4px);
}
.wp-block-separator.is-style-gradient {
	height: 1px;
	border: 0;
	background: linear-gradient(90deg, transparent, var(--wp--preset--color--contrast-3), transparent);
}
.wp-block-separator.is-style-dotted {
	border: 0;
	border-top: 2px dotted var(--wp--preset--color--contrast-3);
	background: transparent;
	height: 0;
}
.wp-block-group.is-style-card {
	background: var(--wp--preset--color--base-2);
	border-radius: 16px;
	padding: var(--wp--preset--spacing--40);
}
.wp-block-group.is-style-bordered {
	border: 1px solid var(--wp--preset--color--base-3);
	border-radius: 16px;
	padding: var(--wp--preset--spacing--40);
}
.wp-block-quote.is-style-editorial {
	font-family: var(--wp--preset--font-family--newsreader);
	font-style: italic;
	font-size: var(--wp--preset--font-size--large);
	border: 0;
	padding-left: var(--wp--preset--spacing--40);
	border-left: 3px solid var(--wp--preset--color--accent);
}

/* Mobile baseline — patterns must look great at 390px */
@media (max-width: 640px) {
	.wp-block-group.is-style-card,
	.wp-block-group.is-style-bordered { padding: var(--wp--preset--spacing--30); }
}
```

- [ ] **Step 3: Wire `block-styles.php` from `Component.php`**

In `inc/Base_Support/Component.php`, after the namespace declaration, require the new file:

```php
require_once __DIR__ . '/block-styles.php';
```

- [ ] **Step 4: Build CSS**

```bash
npm run build:css 2>&1 | tail -5
```
Expected: `CSS build complete (production)`

- [ ] **Step 5: Browser-verify**

Open editor, insert a button block, switch to Block sidebar → Styles → confirm "Outline Accent" and "Link with Arrow" appear and apply.

- [ ] **Step 6: Commit**

```bash
git add inc/Base_Support/block-styles.php inc/Base_Support/Component.php assets/css/src/_blocks-style.css assets/css/_blocks-style.css assets/css/_blocks-style.min.css
git commit -m "feat(blocks): 7 block style variations for pattern library

Adds button (outline-accent, link-arrow), separator (gradient,
dotted), group (card, bordered), quote (editorial) style
variations. Backs the upcoming premium pattern library with
consistent visual primitives."
```

---

## Task 6: Update pattern category registration + retire 5 patterns

**Files:**
- Modify: `inc/Base_Support/Component.php` (lines 209–268)
- Delete: `inc/Base_Support/patterns/hero-main.php`
- Delete: `inc/Base_Support/patterns/hero-two.php`
- Delete: `inc/Base_Support/patterns/hero-count.php`
- Delete: `inc/Base_Support/patterns/general-banner.php`
- Delete: `inc/Base_Support/patterns/footer-default.php`

**Why:** New 6-category structure replaces the old 4. The 5 retired patterns don't meet the bar even with restyling; their slots are taken by new premium patterns in Tasks 9–24.

- [ ] **Step 1: Replace the `buddyx_register_block_pattern_categories` method body**

In `inc/Base_Support/Component.php`, find the method and replace its body with 8 categories:

```php
public function buddyx_register_block_pattern_categories() {
	$categories = array(
		'buddyx-hero'         => __( 'BuddyX Hero',          'buddyx' ),
		'buddyx-about'        => __( 'BuddyX About',         'buddyx' ),
		'buddyx-features'     => __( 'BuddyX Features',      'buddyx' ),
		'buddyx-social-proof' => __( 'BuddyX Social Proof',  'buddyx' ),
		'buddyx-pricing-faq'  => __( 'BuddyX Pricing & FAQ', 'buddyx' ),
		'buddyx-cta'          => __( 'BuddyX CTA',           'buddyx' ),
		'buddyx-footer'       => __( 'BuddyX Footer',        'buddyx' ),
		'buddyx-query'        => __( 'BuddyX Query',         'buddyx' ),
	);
	foreach ( $categories as $slug => $label ) {
		register_block_pattern_category( $slug, array( 'label' => $label ) );
	}
}
```

- [ ] **Step 2: Replace the `$block_patterns` array in the registration loop**

Replace the array (currently lines 238–258) with:

```php
$block_patterns = array(
	// Hero
	'hero-typography-led',
	'hero-split-screen',
	'hero-image-led',
	// About
	'about-story',
	'about-founder',
	// Features
	'features-alternating',
	'services-grid',
	'steps',
	'cta-fullbleed',  // also under 'buddyx-features' in pattern file
	// Social proof
	'social-proof-stats',
	'social-proof-testimonials',
	'social-proof-logos',
	// Pricing & FAQ
	'general-pricing',
	'general-faq',
	// CTA
	'general-banner-light',
	'cta-newsletter',
	// Footer
	'footer-central',
	'footer-default-mega',
	'footer-mega',
	'footer-simple',
	'footer-small',
	// Query
	'query-cover-featured',
	'query-cover-grid',
	'query-grid-excerpt',
	'query-listbig',
	'query-simple-list',
);
```

- [ ] **Step 3: Delete the 5 retired pattern files**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
rm inc/Base_Support/patterns/hero-main.php
rm inc/Base_Support/patterns/hero-two.php
rm inc/Base_Support/patterns/hero-count.php
rm inc/Base_Support/patterns/general-banner.php
rm inc/Base_Support/patterns/footer-default.php
ls inc/Base_Support/patterns/
```
Expected: 14 remaining .php files (the 14 to be restyled).

- [ ] **Step 4: Verify the editor reports zero `core/missing` for any registered pattern**

Run earlier audit script in browser: `wp.data.select('core').getBlockPatterns().filter(p => /^buddyx\//.test(p.name))`. Confirm only the patterns whose files still exist appear (the 14 restyled — registration of new ones happens as we add the files). Confirm zero patterns return `null` content.

- [ ] **Step 5: Commit**

```bash
git add inc/Base_Support/Component.php
git rm inc/Base_Support/patterns/hero-main.php inc/Base_Support/patterns/hero-two.php inc/Base_Support/patterns/hero-count.php inc/Base_Support/patterns/general-banner.php inc/Base_Support/patterns/footer-default.php
git commit -m "refactor(patterns): 8-category taxonomy + retire 5 weakest patterns

New categories: hero, about, features, social-proof, pricing-faq,
cta, footer, query. Retires hero-main (BuddyPress copy locked),
hero-two (Lorem ipsum), hero-count (cliché), general-banner (2018
ornaments), footer-default (redundant with default-mega). Their
slots are taken by new premium patterns in subsequent tasks."
```

---

## Pattern composition rules (apply to every pattern in Tasks 7–24)

These rules turn ad-hoc files into a coherent library. Every restyled or new pattern must satisfy all of them:

1. **Typography**: H1 uses Inter 700 with `--wp--preset--font-size--xx-large` or `mega` for hero; H2 uses x-large; lede paragraphs use large with line-height 1.5; small caption text uses small with letter-spacing 0.04em.
2. **Vertical rhythm**: Section padding `var(--wp--preset--spacing--70)` top + bottom on desktop, `--40` on mobile. Block gap inside sections `--40` desktop / `--30` mobile.
3. **Color**: Light patterns use `base` background with `contrast` text and `accent` for one CTA per pattern. Dark patterns use `surface-3` background with `base` text. No more than 2 accent colors per pattern.
4. **Composition**: At least one pattern in each category must be asymmetric (not centered + balanced). Use 5/7 or 7/5 column splits where it's a 2-up.
5. **Demo copy**: Real, specific. No "Lorem ipsum". Headings 4–8 words. Numbers feel real (e.g. "4.9 from 2,847 reviews", not "10,000+ customers").
6. **Image strategy**: Photographic patterns use Unsplash-aesthetic placeholders bundled in `assets/images/patterns/`. Typography-led patterns use no images. SVG ornaments authored in-house.
7. **Mobile verification**: Each pattern reviewed at 390px after authoring; collapses gracefully (columns stack, padding reduces, type stays readable).
8. **Categories**: Every pattern is assigned both its `buddyx-*` category and at least one core category from the standard list (`call-to-action`, `featured`, `gallery`, `header`, `footer`, `posts`, `services`, `testimonials`, `team`, `text`, `about`, `contact`) for discoverability in the WP default browser.

---

## Tasks 7–24: Pattern authoring (one task per pattern)

Each pattern task follows the same shape. Below is the canonical task; subsequent pattern tasks reference it for steps but show the unique pattern content.

### Task 7 (canonical): Restyle `general-faq.php`

**Files:**
- Modify: `inc/Base_Support/patterns/general-faq.php` (full rewrite)

- [ ] **Step 1: Read existing pattern to capture current title and category context**

```bash
cat inc/Base_Support/patterns/general-faq.php
```

- [ ] **Step 2: Replace file content**

Write `inc/Base_Support/patterns/general-faq.php`:

```php
<?php
/**
 * Pattern: FAQ — typography-led accordion.
 *
 * @package buddyx
 */
return array(
	'title'      => __( 'FAQ — Typography-led', 'buddyx' ),
	'categories' => array( 'buddyx-pricing-faq', 'text' ),
	'content'    => '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"880px"}} -->
<div class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)">

  <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","letterSpacing":"0.08em","textTransform":"uppercase"},"color":{"text":"#6e6e6e"}}} -->
  <p class="has-text-align-center has-text-color" style="color:#6e6e6e;font-size:var(--wp--preset--font-size--small);letter-spacing:0.08em;text-transform:uppercase">FAQ</p>
  <!-- /wp:paragraph -->

  <!-- wp:heading {"textAlign":"center","level":2,"style":{"typography":{"fontSize":"var:preset|font-size|x-large","fontWeight":"700","letterSpacing":"-0.015em"},"spacing":{"margin":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|50"}}}} -->
  <h2 class="wp-block-heading has-text-align-center" style="margin-top:var(--wp--preset--spacing--10);margin-bottom:var(--wp--preset--spacing--50);font-size:var(--wp--preset--font-size--x-large);font-weight:700;letter-spacing:-0.015em">Questions, answered.</h2>
  <!-- /wp:heading -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>What does the theme include out of the box?</strong></summary>
  <!-- wp:paragraph --><p>A library of 27 page-section patterns, full Site Editor support, fluid type scale, light and dark style variations, and ten spacing presets. No plugin required.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Will it work without page builders?</strong></summary>
  <!-- wp:paragraph --><p>Yes. Patterns assemble from core WordPress blocks only — no Elementor, no Divi, no proprietary block plugins. Drop them in, edit the copy, ship.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Can I switch the typography?</strong></summary>
  <!-- wp:paragraph --><p>Pick the Editorial style variation in Site Editor and the entire site flips to Newsreader serif headings. Or set your own font in Global Styles.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

  <!-- wp:separator {"className":"is-style-dotted"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-dotted"/><!-- /wp:separator -->

  <!-- wp:details -->
  <details class="wp-block-details"><summary><strong>Is it accessible?</strong></summary>
  <!-- wp:paragraph --><p>WCAG 2.1 AA. Visible focus rings, real label associations, no aria-hidden on focusable elements, and keyboard parity across every pattern.</p><!-- /wp:paragraph -->
  </details>
  <!-- /wp:details -->

</div>
<!-- /wp:group -->
',
);
```

- [ ] **Step 3: Browser-verify in editor + front-end at 1280px**

Open editor, insert pattern, screenshot. Then save the page, view front-end, screenshot. Confirm:
- Headline is centered, uses Inter 700, has tight letter-spacing
- Eyebrow "FAQ" is uppercase 12px with letter-spacing
- Each `<details>` collapses/expands with click + keyboard (Space/Enter)
- Dotted separator visible between items

- [ ] **Step 4: Browser-verify at 390px**

Resize Playwright to 390x844, screenshot. Confirm:
- Heading reduces (clamp() should fire) but stays readable
- No horizontal scroll
- Padding-left/right collapses to spacing--40

- [ ] **Step 5: Commit**

```bash
git add inc/Base_Support/patterns/general-faq.php
git commit -m "feat(pattern): redesign general-faq as typography-led accordion

Replaces the old plain-accordion FAQ with a typography-led pattern:
eyebrow + tight-tracked headline + native <details> blocks separated
by dotted rules. Uses Inter type tokens, content size 880px, real
demo copy answering theme-buyer concerns. Verified at 1280/390px."
```

---

### Task 8: Restyle `general-features-light.php` → alternating image+text

**Files:**
- Rename + rewrite: `inc/Base_Support/patterns/general-features-light.php` → keep filename, retitle to "Features — Alternating"

Compose two sections of `core/columns` with 5/7 → 7/5 split. Left column = image (use `assets/images/patterns/services-icon-set/feature-1.svg`), right column = small uppercase eyebrow + 28px H3 + 16px paragraph + link-arrow CTA. Second section flips. Reuses spacing/color tokens. Demo copy: "Built for content-first sites" / "Theming that scales with your brand". Same 5-step shape (Read → Replace → Verify desktop → Verify mobile → Commit).

### Task 9: Create `hero-typography-led.php`

Single-column 880px-content-size group on `base-2` background. Eyebrow "Hero" + Mega-size H1 with mixed Inter+Newsreader (use Newsreader for one italic word) + lede paragraph (large size) + 2 buttons (filled accent + outline-accent). Ornament: faint `assets/images/patterns/hero-typography-bg.svg` decoration absolute-positioned via inline cover background. Demo copy: "Build sites that **read** like editorial."

### Task 10: Create `hero-split-screen.php`

`core/columns` 5/7 split (text left, image right). Left: eyebrow / H1 / lede / button + link-arrow combo. Right: full-bleed `core/cover` with `assets/images/patterns/hero-split-portrait.jpg` and a small floating quote card overlapping the image bottom-right (`is-style-card` group with star rating + name).

### Task 11: Create `hero-image-led.php`

Full-bleed `core/cover` (90vh) with image + dim overlay + bottom-aligned content. Centered content max-width 720px: small eyebrow, H1 mega, single CTA. Used as a homepage hero.

### Task 12: Restyle `general-banner-light.php` → asymmetric CTA strip

Single 7/5 columns row inside a `surface-1` group. Left: eyebrow / "Plan your site in an afternoon, not a weekend." / lede. Right: vertical button stack (primary + ghost link). No image.

### Task 13: Create `about-story.php`

Two stacked sections inside an aligned `wide` group. (1) Centered eyebrow "About us" + Newsreader H2 + 2-column paragraph text (use `is-style-card` for left card with quote, right card with body copy). (2) `core/media-text` 50/50 with `assets/images/patterns/about-story-1.jpg` left, paragraph + button right.

### Task 14: Create `about-founder.php` + `team-grid.php`

`about-founder.php`: `core/media-text` 40/60 portrait of founder + multi-paragraph story with one Newsreader pull-quote (`is-style-editorial`).

`team-grid.php`: 4-col grid of team cards (image / name / role / one-line bio + social links — use core/social-links). Bundle 4 portrait JPGs in `assets/images/patterns/team/`.

(Two patterns in one task because they share asset prep.)

### Task 15: Restyle `general-pricing.php` → premium 3-tier

3 columns inside a `wide` group. Middle column raised: `is-style-card` background `accent` with white text and "Most popular" eyebrow; outer columns `is-style-bordered`. Each card: tier name (medium) / price (xx-large with /mo small caption) / 5-item check list (`core/list`) / button. Demo copy: "Starter / Studio / Agency". Pricing $19/$49/$129 — feels real.

### Task 16: Create `social-proof-stats.php`

Single full-width strip with 4 columns. Each column: large number (mega Newsreader weight 500) + small caption (small uppercase letter-spaced). Demo: "12,400 sites shipped · 4.9★ on wp.org · 64+ countries · 3.2s avg LCP." On `surface-2` background, no images.

### Task 17: Create `services-grid.php`

3-col grid (becomes 1-col below 640px). Each card `is-style-card`: SVG icon (24px) / H3 / paragraph / link-arrow. Bundle 6 SVG icons in `assets/images/patterns/services-icon-set/`. Demo: brand identity, web design, content strategy, accessibility audit, performance tuning, ongoing support.

### Task 18: Create `steps.php`

4 columns numbered 01–04 with stacked rule above each number (use `is-style-gradient` separator). Title + 1-line description. Demo: "Discover / Design / Develop / Deliver".

### Task 19: Create `social-proof-testimonials.php`

`core/columns` 3-up. Each column `is-style-card`: 5-star row (use SVG star or core/social-links) + Newsreader italic quote + avatar + name + role. Bundle 3 avatars (Pexels CC0). Demo quotes feel real (specific outcomes, not "great theme").

### Task 20: Restyle existing footers (Tasks 20a–20e)

Five sub-tasks one per remaining footer file: `footer-central`, `footer-default-mega`, `footer-mega`, `footer-simple`, `footer-small`. Each rewrites the file using the design tokens. Same 5-step shape. Bundle into one combined commit at the end of Task 20e.

### Task 21: Create `social-proof-logos.php`

Centered eyebrow "Trusted by" + 6-col logo gallery (or 3×2 below 768px). Bundle 6 SVG brand logos (generic — "Acme", "Northwind", "Globex", etc., authored in-house). Reduced opacity (60%) for visual cohesion.

### Task 22: Restyle 5 query patterns (Tasks 22a–22e)

Each query pattern restyled to use the new tokens, premium typography, real-feeling category labels and dates, and asymmetric layouts where appropriate. One commit per query pattern.

### Task 23: Create `cta-fullbleed.php`

Full-bleed `core/cover` with `accent-bold` gradient. Centered content: H2 mega "Ready to ship?" + lede "Get the theme, the patterns, and a year of updates for $79." + 2 buttons (white solid + ghost outline-base).

### Task 24: Create `cta-newsletter.php`

Group on `surface-1`. Two-column 7/5: left = eyebrow / H3 / lede / small disclaimer ("No spam. Unsub in one click."). Right = `core/buttons` (placeholder for newsletter input — use `core/group` mimicking input style with border + button next to it; pure HTML approximation since `core/search` is keyword-search-only). Note in inline comment that integration with mail providers is the user's job.

### Task 25: Update style.css version, README, changelog

**Files:**
- Modify: `style.css` (version 5.0.3)
- Modify: `readme.txt` (changelog entry)
- Modify: `package.json` (if version field tracked there)

- [ ] **Step 1: Bump version**

In `style.css` header: `Version: 5.0.3`. In `readme.txt`: add `== Changelog ==` entry under `= 5.0.3 =`:

```
= 5.0.3 =
Major UI refresh and pattern library overhaul. This release positions BuddyX
as a general-purpose WordPress theme with a designer-grade pattern library.

Breaking Changes:
* Removed pattern: buddyx/hero-main — pages using this pattern will lose the section. Replace with buddyx/hero-typography-led, hero-split-screen, or hero-image-led.
* Removed pattern: buddyx/hero-two — replace with hero-split-screen.
* Removed pattern: buddyx/hero-count — replace with social-proof-stats.
* Removed pattern: buddyx/general-banner — replace with cta-fullbleed or general-banner-light.
* Removed pattern: buddyx/footer-default — replace with footer-default-mega.

New:
* 13 new premium patterns: hero-typography-led, hero-split-screen, hero-image-led, about-story, about-founder, team-grid, services-grid, steps, social-proof-stats, social-proof-testimonials, social-proof-logos, cta-fullbleed, cta-newsletter.
* Self-hosted Inter (body) and Newsreader (display) fonts via theme.json. No external font API calls.
* Dark and Editorial style variations available in Site Editor.
* Fluid 8-step type scale (x-small → mega) using clamp() for responsive sizing.
* 10-step named spacing scale (10–100, ~8px–160px).
* 5 curated gradients for cover blocks; 2 duotone presets.
* 7 block style variations: button (outline-accent, link-arrow), separator (gradient, dotted), group (card, bordered), quote (editorial).

Improved:
* All 14 retained patterns redesigned with new tokens, premium typography, real demo copy, and asymmetric compositions where appropriate.
* P0 accessibility fixes: visible focus rings (:focus-visible 2px brand outline), 7 image alts added, 2 form label associations, tabindex on aria-hidden post-thumbnail links.

Compatibility:
* All 9 legacy color slugs (primary, secondary, red, green, blue, yellow, black, grey, white) remain registered for back-compat with existing user content.
* Theme.json schema bumped to v3.
```

- [ ] **Step 2: Run full build**

```bash
npm run build:css
```

- [ ] **Step 3: Commit**

```bash
git add style.css readme.txt package.json package-lock.json assets/css/
git commit -m "chore(release): bump version to 5.0.3 and update changelog"
```

---

## Task 26: Final QA pass

**Files:** none modified — verification only.

- [ ] **Step 1: Static recon — run pattern audit script across all 25 final patterns**

```bash
cd "/Users/varundubey/Local Sites/buddyx/app/public/wp-content/themes/buddyx"
python3 - <<'PY'
import os, re
PATTERN_DIR = "inc/Base_Support/patterns"
expected = [
  'hero-typography-led','hero-split-screen','hero-image-led',
  'about-story','about-founder','team-grid',
  'features-alternating','services-grid','steps','cta-fullbleed','cta-newsletter',
  'social-proof-stats','social-proof-testimonials','social-proof-logos',
  'general-pricing','general-faq','general-banner-light',
  'footer-central','footer-default-mega','footer-mega','footer-simple','footer-small',
  'query-cover-featured','query-cover-grid','query-grid-excerpt','query-listbig','query-simple-list',
]
on_disk = sorted(f[:-4] for f in os.listdir(PATTERN_DIR) if f.endswith('.php'))
exp = sorted(expected)
missing = [p for p in exp if p not in on_disk]
extra = [p for p in on_disk if p not in exp]
print("Missing files:", missing or "none")
print("Extra files:",   extra or "none")
print("Total expected:", len(exp), " on disk:", len(on_disk))
PY
```
Expected: `Missing files: none`, `Extra files: none`, total 27.

- [ ] **Step 2: Browser audit — load each pattern in editor and screenshot**

Use Playwright to open `http://buddyx.local/wp-admin/post-new.php?post_type=page`, then for each pattern in the expected list above: insert via `wp.data.dispatch('core/block-editor').insertBlocks(wp.blocks.parse(pattern.content))`, screenshot at 1280px and at 390px, save under `tmp/pattern-screenshots/<slug>-<width>.png`. Visually scan all 54 screenshots for layout bugs, broken images, copy errors.

- [ ] **Step 3: Disable all plugins and re-verify**

Deactivate every plugin via WP-CLI (or admin), reload the editor, confirm zero patterns show "Some blocks no longer available" errors. Re-activate plugins.

```bash
wp plugin deactivate --all --path="/Users/varundubey/Local Sites/buddyx/app/public"
# verify in browser
wp plugin activate --all --path="/Users/varundubey/Local Sites/buddyx/app/public"
```

- [ ] **Step 4: Lighthouse pass on a page using 5 patterns**

Build a test page combining hero-image-led + features-alternating + social-proof-testimonials + cta-fullbleed + footer-default-mega. Run Lighthouse:

```bash
npx lighthouse http://buddyx.local/test-patterns/ --output=json --output-path=tmp/lighthouse.json --only-categories=performance,accessibility --quiet
python3 -c "import json; r=json.load(open('tmp/lighthouse.json'))['categories']; print('Performance:', r['performance']['score']*100); print('Accessibility:', r['accessibility']['score']*100)"
```
Expected: Performance ≥ 85, Accessibility = 100.

- [ ] **Step 5: WPCS + PHPStan**

```bash
composer install
vendor/bin/phpcs --standard=phpcs.xml.dist inc/Base_Support/
vendor/bin/phpstan analyse --level=5 inc/Base_Support/
```
Expected: zero errors. Fix any that surface.

- [ ] **Step 6: Commit any QA-driven fixes; otherwise no commit**

If issues fixed, commit each fix with `fix(pattern): <slug> — <issue>`. If clean, skip.

---

## Self-Review

**Spec coverage:** every requirement in the brainstorm:
- Foundation (theme.json upgrade) → Tasks 1–5 ✓
- Improve all existing patterns → Tasks 7, 8, 12, 15, 20a–20e, 22a–22e (14 patterns) ✓
- Add missing patterns in same style → Tasks 9–11, 13, 14, 16–19, 21, 23, 24 (13 patterns) ✓
- Premium UX bar (Ollie) → Composition rules section + per-pattern criteria ✓
- wp.org compliance → Constraints section + Task 26 step 3 ✓
- Browser verification per pattern → "Verify desktop / Verify mobile" steps in every pattern task ✓

**Placeholder scan:** No "TBD", no "implement later", no "similar to Task N — see above" without inlined code. Pattern authoring tasks 8 onward reference Task 7 as the canonical 5-step shape but each one names its specific files and unique design intent. The actual block markup for the new 13 patterns will be authored at execution time per the composition rules and design tokens defined in Tasks 1–5; the plan does not pre-author every pattern's markup because (a) the markup is design work that benefits from in-editor iteration, and (b) the plan would balloon to ~3000 lines without proportional planning value.

**Type/identifier consistency:**
- `buddyx-pricing-faq` used consistently in Task 6 + Task 7 + Task 15.
- `is-style-card`, `is-style-bordered`, `is-style-link-arrow`, `is-style-outline-accent`, `is-style-editorial`, `is-style-dotted`, `is-style-gradient` defined in Task 5 and consumed in pattern tasks.
- Color slugs (`base`, `contrast`, `accent`, `surface-1/2/3`) defined in Task 1 and consumed throughout.
- Spacing slugs (`10` through `100`) defined in Task 4 and consumed in pattern tasks.
- Font slugs (`inter`, `newsreader`) defined in Task 3.

**Risk register:**
1. *theme.json fluid clamp() values may produce unexpected sizes on edge viewport widths.* Mitigation: each pattern verified at 390/768/1280 — out-of-range sizes caught on first encounter.
2. *Self-hosted fonts add ~140kb. wp.org reviewers may flag size budget.* Mitigation: woff2-only, latin subset only, 4+3 weights total. Document in readme that Editorial variation only loads Newsreader on demand.
3. *Retiring patterns is a breaking change for sites using them in user content.* Mitigation: changelog explicitly lists removed pattern slugs under a "Breaking Changes" heading; readme.txt 5.0.3 entry leads with "Major UI refresh" so users know to audit; consider an Upgrade Notice block in readme.txt that surfaces in the WP admin update screen.
4. *Pattern authoring quality may drift across 27 patterns.* Mitigation: Task 26 dedicated QA pass with paired desktop/mobile screenshots reviewed as a sheet.

**Estimated effort:** 5–7 working days end-to-end. Foundation (Tasks 1–6): 1 day. Pattern authoring (Tasks 7–24): 3–5 days. QA + version bump (Tasks 25–26): 1 day.
