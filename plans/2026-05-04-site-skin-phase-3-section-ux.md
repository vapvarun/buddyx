# Site Skin — Phase 3: Section UX Overhaul

**Status:** Active in 5.1.0 (per stakeholder direction "nothing is deferred").
**Prerequisite:** Phase 1 (token system) ✅ + Phase 2 (color modes) ✅ already shipped.
**Successor:** Phase 4 (stylesheet cleanup audit).

---

## Goal

Make the 45-field Site Skin section *navigable* in the customizer panel.
Today the section already groups fields with `<hr>` dividers; Phase 3
upgrades those into proper visual cluster headers with icons + captions,
and reorganizes a couple of mis-clustered fields.

**Scope is UX-only** — no setting IDs change, no defaults change, no DB
migration. Customer-saved values flow through unchanged.

---

## Current state inventory (45 fields)

| Position | Type | Setting | Cluster (existing) |
|---|---|---|---|
| 1  | radio_buttonset | site_color_mode                       | (top — no cluster) |
| 2  | switch          | site_custom_colors                    | (top — no cluster) |
| 3  | custom (HR)     | custom-header-divider                 | "Header" header |
| 4-10 | color (×7)    | site_title_*, header_bg, menu_*       | Header cluster |
| 11 | custom (HR)     | custom-body-divider                   | "Body" header |
| 12-19 | color (×8)   | body_bg, content_bg, box_bg, …, primary, links | Body cluster |
| 20 | custom (HR)     | custom-headings-divider               | "Headings" header |
| 21-26 | color (×6)   | h1-h6                                 | Headings cluster |
| 27 | custom (HR)     | custom-button-divider                 | "Buttons" header |
| 28-33 | color (×6)   | site_buttons_*                        | Buttons cluster |
| 34 | custom (HR)     | custom-footer-divider                 | "Footer" header |
| 35-38 | color (×4)   | site_footer_*                         | Footer cluster |
| 39 | custom (HR)     | custom-coyright-divider [sic]         | "Copyright" header |
| 40-44 | color (×5)   | site_copyright_*                      | Copyright cluster |

---

## Proposed cluster reorganization

8 clusters with descriptive captions and Lucide-style SVG icons. Cluster
order follows visual hierarchy of a typical site (top of page → bottom):

```
┌─ Mode & Master ──────────────────────┐
│ • Color mode  (auto | light | dark)  │
│ • Set custom colors? (master toggle) │
└──────────────────────────────────────┘

┌─ ⚡ Brand ────────────────────────────┐  NEW cluster (was in "Body")
│ • Theme color           (--bx-accent)│
└──────────────────────────────────────┘

┌─ ⬛ Header ──────────────────────────┐  was "Header"
│ Header surface                       │
│ • Header background color            │
│ Site title                           │
│ • Site title color                   │
│ • Site title hover color             │
│ • Site tagline color                 │
│ Menu                                 │
│ • Menu color                         │
│ • Menu hover color                   │
│ • Menu active color                  │
│ • Subheader title color (relocated)  │  was in "Body"
└──────────────────────────────────────┘

┌─ ◍ Surfaces ─────────────────────────┐  was part of "Body"
│ • Body background color              │
│ • Content background color           │
│ • Box background color               │
│ • Secondary background color         │
└──────────────────────────────────────┘

┌─ ¶ Text & Links ─────────────────────┐  was part of "Body"
│ • Body text color                    │
│ • Link color                         │
│ • Link hover                         │
└──────────────────────────────────────┘

┌─ H Headings ─────────────────────────┐  was "Headings"
│ • H1 .. H6 colors                    │
└──────────────────────────────────────┘

┌─ ⊟ Buttons ──────────────────────────┐  was "Buttons"
│ • Background, BG hover               │
│ • Text, Text hover                   │
│ • Border, Border hover               │
└──────────────────────────────────────┘

┌─ ↧ Footer ──────────────────────────┐  was "Footer"
│ • Title, Content, Link, Link hover   │
└──────────────────────────────────────┘

┌─ © Copyright ───────────────────────┐  was "Copyright"
│ • Background, Border, Content        │
│ • Link, Link hover                   │
└──────────────────────────────────────┘
```

### Changes

1. **New "Mode & Master" cluster** at top — wraps the 2 existing
   non-clustered fields (color mode + custom colors toggle).
2. **New "Brand" cluster** — `site_primary_color` extracted from the
   sprawling "Body" cluster into its own single-field cluster (it's
   the most-used color and deserves prominence).
3. **"Body" cluster split** into:
   - **Surfaces** (4 background colors)
   - **Text & Links** (3 text/link colors)
4. **"Header" cluster** gets a sub-cluster grouping (header surface /
   site title / menu) using a smaller divider style.
5. **Subheader title color** relocated from "Body" to "Header" (it
   belongs with the other header-area colors).
6. **Cluster header markup** upgraded from `<hr style="border-color">`
   to a proper `<div class="bx-cluster-head">` with icon + label +
   optional caption.

---

## Implementation

### A. Cluster-head rendering

Replace the existing `<hr>` Custom_HTML defaults with a structured
markup:

```html
<div class="bx-cluster-head" data-cluster="header">
  <span class="bx-cluster-head__icon" aria-hidden="true">
    <!-- inline Lucide-style SVG -->
  </span>
  <div class="bx-cluster-head__text">
    <span class="bx-cluster-head__title">Header</span>
    <span class="bx-cluster-head__caption">Logo, navigation, top of page</span>
  </div>
</div>
```

Plus CSS in `inc/Customizer_Framework/assets/customizer-controls.css`:

```css
.bx-cluster-head {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 14px;
  margin: 16px -12px 8px;
  background: linear-gradient(180deg, #f0f0f1 0%, #f6f7f7 100%);
  border-top: 1px solid #c3c4c7;
  border-bottom: 1px solid #dcdcde;
}
.bx-cluster-head__icon {
  display: inline-flex; width: 18px; height: 18px;
  color: #2271b1;
}
.bx-cluster-head__title { font-weight: 600; font-size: 13px; color: #1d2327; }
.bx-cluster-head__caption { font-size: 11px; color: #50575e; }
```

### B. Field declarations

Update `inc/Customizer_Settings/Fields/Skin_Fields.php`:

1. Replace each existing `custom-*-divider` field's `default` value with
   the new structured HTML.
2. Add `priority` values explicitly so reordering is deterministic
   (Mode = 5, Brand = 10, Header = 20, Surfaces = 30, Text & Links = 40,
   Headings = 50, Buttons = 60, Footer = 70, Copyright = 80; field
   priorities within each cluster increment by 1).
3. Move `site_primary_color` priority from inside Body cluster to its
   own Brand cluster position.
4. Move `site_sub_header_typography[color]` priority into the Header
   cluster.
5. Add 3 new sub-cluster dividers inside Header (smaller markup style):
   "Header surface", "Site title", "Menu".

### C. Inline icons

Lucide-style SVGs (24×24 simplified to 18×18) inlined per cluster:

| Cluster | Icon | Lucide name |
|---|---|---|
| Mode & Master | half-moon | `moon` |
| Brand | flame | `flame` |
| Header | layout-template (top bar) | `panel-top` |
| Surfaces | square-stack | `layers` |
| Text & Links | type | `type` |
| Headings | heading | `heading` |
| Buttons | mouse-pointer-2 | `mouse-pointer-2` |
| Footer | layout-template (bottom bar) | `panel-bottom` |
| Copyright | copyright | `copyright` |

Each ships as inline `<svg>` in the Custom_HTML default value (kses-
whitelisted in Bug #13 fix). License: ISC (same as theme; permissive,
GPL-compatible per Lucide's license).

### D. Acceptance criteria

- [ ] All 45 existing fields still register (no regression in `wp.customize.control()` count)
- [ ] All 8 clusters render with the new header markup
- [ ] Existing customer saved values display correctly (UI shows their
      values, not new defaults)
- [ ] Cluster headers are visually distinct from regular field rows
- [ ] Sub-cluster dividers (inside Header) are visually subordinate
- [ ] Live preview unaffected (no setting IDs changed)
- [ ] Pass 1/2/3 from `plans/2026-05-04-customizer-options-audit.md` re-runs green

### Out of scope for Phase 3

- Collapsible/accordion clusters (heavy; defer to 5.2.x if requested)
- "Live tokens grid" preview panel (5.2.0 plan item)
- Per-color dark-mode override fields (5.2.1+)

---

## Estimated effort

~3 hours: ~30 min plan execution + 1.5 hr field reordering + 1 hr CSS
polish + verification.
