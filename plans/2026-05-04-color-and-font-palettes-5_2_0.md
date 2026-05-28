# Color & Font Palettes (Brndle-style Presets)

**Status:** Deferred to 5.2.0 by stakeholder direction (5.1.0 ships
without palette presets — current defaults stay as default).
**Stakeholder ask:** "Color palette and font palette like we had given
in Brndle. Current default may stay as default — we can give them more
presets for color and font set as extra option along with dark mode
options so they can tune up dark mode as well." — user, 2026-05-04.
**Prerequisite:** Site Skin tokens initiative Phases 1-4 complete in 5.1.0.

---

## Concept

Customers shouldn't have to pick 39 individual colors. They should pick
a **palette** (a curated, designer-coordinated set) and optionally tweak
individual values. Same for fonts: pick a heading-body pairing, not 12
typography fields.

Brndle's pattern (which BuddyX 5.0.3 partly mimicked via "Light" /
"Editorial" theme.json variations) is the model. We extend it:

- 6+ color palettes — each shipping a complete light + dark token set
- 6+ font palettes — each shipping heading family + body family + scale
- One-click apply via a dedicated customizer section
- Customer can fine-tune individual colors/fonts after applying

---

## Why this only works AFTER tokens (5.1.0)

Without the token system, applying a palette would require updating 39
customizer settings simultaneously and trusting that the theme stylesheet
consumes them all. With tokens (5.1.0):

- A palette = a set of `--bx-color-*` token values
- Apply = swap the token map
- Theme stylesheet doesn't care which palette is active
- Dark mode "just works" because each palette ships its dark counterpart

That's why this lands in 5.2.0, not 5.1.0.

---

## Proposed feature set

### Color palettes section

```
Customizing › Site Skin › Color palette

[Editorial Red]  ← currently selected (BuddyX 5.0.3 default)
  preview: 4 swatches (accent / fg / bg / link)

[Coral & Stone]
  preview: 4 swatches

[Forest & Cream]
  preview: 4 swatches

[Ocean & Slate]
  preview: 4 swatches

[Mono Editorial]
  preview: 4 swatches

[Warm Sand]
  preview: 4 swatches

  + Custom (advanced)  ← opens the existing 39-color section
```

**Each palette ships a structured definition:**

```php
'editorial-red' => array(
    'label' => __( 'Editorial Red', 'buddyx' ),
    'description' => __( 'Confident editorial red on warm whites — BuddyX classic', 'buddyx' ),
    'light' => array(
        '--bx-color-accent'           => '#ef5455',
        '--bx-color-bg'               => '#ffffff',
        '--bx-color-fg'               => '#111111',
        '--bx-color-fg-muted'         => '#505050',
        // … all 30+ tokens
    ),
    'dark' => array(
        '--bx-color-accent'           => '#ff6b6b',
        '--bx-color-bg'               => '#0a0a0a',
        '--bx-color-fg'               => '#f5f5f5',
        // … all 30+ tokens
    ),
    'preview_swatches' => array( '#ef5455', '#111111', '#ffffff', '#505050' ),
),
```

Customizer setting `site_color_palette` (radio_image) drives which
palette emits to the front-end. The selected palette's `light` map
populates `:root { … }` and the `dark` map populates
`:root[data-bx-mode="dark"] { … }`.

If the customer subsequently edits an individual color (e.g.
`site_primary_color`), that override layers on top of the palette in
emission order:

```css
:root {
  /* From palette */
  --bx-color-accent: #ef5455;
  /* Customer override (later in cascade) */
  --bx-color-accent: #d83734;
}
```

### Font palettes section

```
Customizing › Typography › Font palette

[Newsreader + Inter]  ← BuddyX 5.0.3 default
  preview: H1 sample in Newsreader / body sample in Inter

[Playfair + Source Sans]
  preview

[Inter Tight + Inter]
  preview

[Bricolage + Inter]
  preview

[Manrope + Manrope]
  preview

[Cormorant + Lato]
  preview

  + Custom (advanced)  ← opens existing per-element typography fields
```

**Each font palette ships:**

```php
'newsreader-inter' => array(
    'label' => __( 'Newsreader + Inter', 'buddyx' ),
    'heading_family' => 'newsreader',
    'body_family' => 'inter',
    'scale_ratio' => 1.333, // perfect fourth — drives the size scale
    'base_size' => 17,      // body size in px
    'preview' => array(
        'sample_heading' => 'The quick brown fox',
        'sample_body' => 'Lorem ipsum dolor sit amet…',
    ),
),
```

Selecting a palette populates the existing 12 typography fields'
defaults, computing sizes from the scale ratio:

```
H1 = base × ratio³ = 17 × 2.37 ≈ 40
H2 = base × ratio² = 17 × 1.78 ≈ 30
H3 = base × ratio  = 17 × 1.33 ≈ 23
H4 = base          = 17
H5 = base / ratio  = 17 / 1.33 ≈ 13
H6 = base / ratio² ≈ 10  (override to min 13)
```

Existing customer overrides (if any) layer over these defaults.

### Dark-mode tuning

Built into the color palette — each palette ships a `dark` token map.
But Phase 5 (within this plan) adds an "Advanced dark mode" section
where customers can:

1. Pick a different palette for dark mode (e.g. light = "Editorial Red",
   dark = "Mono Editorial")
2. Override individual dark token values without touching light
3. Toggle "auto-derive from light" — uses an algorithm to invert the
   light palette to dark (HSL inversion + lightness flip + accent boost)

```
Customizing › Site Skin › Dark mode tuning

  Use:  ⏵ Same palette as light (default)
        ⏵ Different palette: [Mono Editorial ▼]
        ⏵ Auto-derive from light palette
        ⏵ Per-token custom (advanced) ← per-color dark override fields
```

---

## Implementation

### Palette registry

`inc/Palettes/Component.php` — central registry of all palettes
(colors + fonts) plus emit logic that integrates with `Tokens`:

```php
namespace BuddyX\Buddyx\Palettes;

class Component implements Component_Interface {
    protected static $color_palettes = array(
        'editorial-red'  => […],
        'coral-stone'    => […],
        'forest-cream'   => […],
        'ocean-slate'    => […],
        'mono-editorial' => […],
        'warm-sand'      => […],
    );
    protected static $font_palettes = array(
        'newsreader-inter'    => […],
        'playfair-source-sans' => […],
        'inter-tight-inter'   => […],
        // …
    );

    public function get_palette_tokens( $palette_id, $mode ) { … }
    public function compute_typography_defaults( $palette_id ) { … }
}
```

### Customizer fields

Two new radio_image fields:
- `site_color_palette` (default: 'editorial-red' = BuddyX 5.0.3 baseline)
- `site_font_palette` (default: 'newsreader-inter')

Plus optional 5.2.x advanced field:
- `site_color_dark_palette` (gated by 'use different palette for dark')

### Token emission integration

`Tokens::build_token_css()` updated:

1. Read `site_color_palette` value
2. Look up palette's `light` map
3. Apply customer per-token overrides on top
4. Emit as `:root { … }`
5. Same for dark — palette `dark` map + customer dark overrides

### Migration

Existing customers (5.1.0 → 5.2.0) get `site_color_palette = 'editorial-red'`
auto-set on first load — preserves their visual exactly. Their
per-token customizer values still apply via the override layer.

### Font loading

When a font palette is selected, the corresponding `@font-face`
declarations are enqueued. BuddyX already self-hosts Inter and
Newsreader; new palettes need their fonts shipped in `assets/fonts/`
under permissive licenses (SIL OFL 1.1 — same as the current fonts).

Recommended additions:
- Playfair Display (SIL OFL)
- Source Sans 3 (SIL OFL)
- Inter Tight (SIL OFL — variant of Inter)
- Bricolage Grotesque (SIL OFL)
- Manrope (SIL OFL)
- Cormorant (SIL OFL)
- Lato (SIL OFL)

All wp.org-compatible.

---

## UX preview component

A small "live preview" panel at the top of each palette section,
showing how the selected palette looks on a tiny mock article:

```
┌─ Editorial Red ─────────────────────┐
│   ━━ ━━━ ━━           [accent btn]  │  ← swatches + sample button
│                                     │
│   The quick brown fox jumped       │  ← sample H1 in palette font
│   Lorem ipsum dolor sit amet,       │  ← sample body
│   consectetur adipisicing.          │
│                                     │
│   [Apply this palette]              │  ← single click
└─────────────────────────────────────┘
```

---

## Out of scope (until later)

- Custom palette save (let customer save their tweaks as a named palette)
- Palette sharing (export/import JSON)
- Per-page palette overrides (different palettes per template)

These are 5.3.0+ features.

---

## Estimated effort

- Color palette registry + emit integration: 1 day
- Font palette registry + scale computation: 1 day
- Customizer UI (2 radio_image sections + preview panel): 1 day
- 6 color palettes designed (light + dark token sets each) + contrast
  verification: 1-2 days
- 6 font palettes designed + font files added + license review: 1 day
- Dark-mode tuning UI (advanced section): 0.5 day
- Migration + testing: 0.5 day

**Total:** ~6-7 days for 5.2.0 ship.

---

## Acceptance criteria for 5.2.0 release

- [ ] 6+ color palettes available, all with light+dark token sets
- [ ] 6+ font palettes available, all wp.org-license-compatible
- [ ] Live preview panel renders correctly for each palette
- [ ] Apply-palette flow updates all 30+ tokens at once
- [ ] Customer per-token overrides layer correctly on top of palette
- [ ] Dark-mode tuning section: 4 modes (same / different / auto-derive
      / per-token) all work
- [ ] Existing customers (5.1.0 → 5.2.0): `site_color_palette = 'editorial-red'`
      auto-applied; visuals byte-identical to pre-upgrade
- [ ] All palettes pass WCAG AA contrast on `bg`/`fg` and
      `accent`/`accent-fg` pairs
- [ ] Inventory snapshot regenerated; new fields documented
