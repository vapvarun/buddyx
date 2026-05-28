# Typography Defaults Modernization

**Status:** Active in 5.1.0.
**Stakeholder direction:** "Modernize remaining typography defaults (h1-h6,
menu, body) we also have to plan on it" — user, 2026-05-04.
**Already done in 5.1.0:** site_title + site_tagline updated to modern
editorial defaults (commit c03ae8c).

---

## Why

BuddyX's typography defaults date from the 5.0 era. Since then, modern
editorial standards have shifted:

- **Body type 17px** is the new baseline (NYT, Medium, Substack — readers
  perceive 16px as "small text" on retina screens). The old 16px feels
  cramped and is below the modern accessibility comfort threshold.
- **Hero/H1 sizes have grown** — premium themes use 48-64px for a confident
  visual entry. Today's H1 is 32px, which reads more like an in-content
  subheading on contemporary sites.
- **Letter-spacing is now used purposefully** — negative letter-spacing
  on big text (-0.01em to -0.02em) is the modern typographic convention,
  improving optical balance on heavy weights. Today most defaults are 0.
- **Line-height is tighter** at the high end (1.05-1.15 for hero) and
  generous at body size (1.6-1.7 for readability).
- **font-family defaulting to ''** (inherit) ignores the editorial
  intent BuddyX 5.0.3 set up (Inter for sans, Newsreader for serif).
  Modern themes assign fonts deliberately per role.

These changes affect **only new installs** — existing customer saved
typography continues to render unchanged because `default` only applies
when no theme_mod is saved.

---

## Current vs proposed defaults

### Already done (commit c03ae8c)
| Field | 5.0.3 default | 5.1.0 modern default |
|---|---|---|
| site_title | '' / 600 / 38px / 1.2 / 0 | newsreader / 700 / 40px / 1.1 / -0.01em |
| site_tagline | '' / regular / 15px / 1.4 / 0 | inter / 400 / 14px / 1.5 / 0.01em |

### Proposed for this plan (8 remaining fields)

| Field | 5.0.3 default | Proposed 5.1.0 default | Rationale |
|---|---|---|---|
| h1_typography | '' / 600 / 32px / 1.2 / 0 | **newsreader / 700 / 40px / 1.1 / -0.01em** | Editorial weight, +25% size for premium hero presence |
| h2_typography | '' / 600 / 26px / 1.25 / 0 | **newsreader / 700 / 32px / 1.15 / -0.005em** | Strong section break, +23% size |
| h3_typography | '' / 600 / 22px / 1.3 / 0 | **newsreader / 600 / 24px / 1.25 / 0** | Sub-section, kept at sub-hero scale |
| h4_typography | '' / 600 / 20px / 1.3 / 0 | **newsreader / 600 / 20px / 1.4 / 0** | Block heading, slightly more breathing |
| h5_typography | '' / 600 / 18px / 1.4 / 0 | **newsreader / 600 / 17px / 1.5 / 0** | Quiet heading; aligns with body |
| h6_typography | '' / 600 / 16px / 1.4 / 0 | **newsreader / 700 / 15px / 1.5 / 0.01em** | Smaller than body but BOLDER for distinction |
| menu_typography | '' / 500 / 15px / 1.6 / 0.02em | **inter / 500 / 14px / 1.5 / 0.01em** | Inter for legibility at small sizes; less aggressive letter-spacing (was visually too spaced for navigation) |
| sub_menu_typography | '' / 500 / 14px / 1.6 / 0.02em | **inter / 400 / 13px / 1.5 / 0** | Lighter weight + tighter spacing for dropdown items |
| typography_option (body) | '' / regular / 16px / 1.6 / 0 | **inter / 400 / 17px / 1.65 / 0** | 17px body — modern editorial baseline |

### Why those specific values

**Type scale follows a perfect-fourth (1.333) ratio approximately:**
```
H1: 40    H4: 20
H2: 32    H5: 17
H3: 24    H6: 15  (smaller-than-body but bolder, breaks the scale by design)
Body: 17
Tagline: 14
```

**Line-height curve** (tight on big text, generous on small):
```
Big   → tight    1.05 (site_title) / 1.1 (h1) / 1.15 (h2)
Mid   → balanced 1.25 (h3) / 1.4 (h4)
Small → generous 1.5-1.65 (h5/h6/menu/body)
```

**Letter-spacing curve** (negative on big, positive on tiny):
```
Big   → negative -0.01em (site_title, h1, h2) — optical correction
Body  → 0
Small → positive +0.01em to +0.02em (tagline, menu, h6) — readability
```

**Family assignment** (deliberate, not inherited):
- Newsreader (serif) → site title + h1-h6 + tagline-pairing optional
- Inter (sans) → body + menus + tagline

---

## Implementation

`inc/Customizer_Settings/Fields/Typography_Fields.php`:
9 field declarations updated with new `default` arrays. No new keys
introduced (we already added font-style/text-align/text-decoration
support in commit c03ae8c). Existing keys preserved with new values.

**One-time CSS impact for new installs:**
On first install, the front-end automatically renders with the modern
defaults via Output_Builder. No customer action needed. Existing
upgrades from 5.0.3 see no change.

---

## Pass-3 implication

The audit plan's "byte-identical save round-trip" check still passes
because **defaults aren't saved** unless the customer explicitly opens
the customizer and saves. Even then, the customer's UI shows the new
defaults already populated, so saving with no edits stores those new
defaults — but that's the new install behavior.

For existing 5.0.3 → 5.1.0 upgrade testing:
1. Existing theme_mods preserved unchanged (Pass 3 sub-check 1)
2. UI shows the saved values (Pass 3 sub-check 3)
3. Templates render the saved values (Pass 3 sub-check 2)

The default change is invisible to existing customers.

---

## Acceptance criteria

- [ ] All 9 fields updated with new defaults
- [ ] `tools/dump-customizer-inventory.py` run; snapshot committed
- [ ] Visual check on a fresh install (or cleared theme_mods): hero is
      larger/heavier; body is more readable at 17px; H1 has presence
- [ ] Visual check on the existing dev DB: no change (saved values
      take precedence)
- [ ] All 12 typography fields' UI controls hydrate from the new
      defaults correctly (the merge-over-defaults path in
      customizer-controls.js)

---

## Estimated effort

~30 minutes — 9 mechanical edits + verification.
