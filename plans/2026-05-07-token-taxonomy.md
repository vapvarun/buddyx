# BuddyX token taxonomy — complete variable map

**Decision:** Define the FULL set of tokens BuddyX needs BEFORE any
hex→token migration starts. Migrating piecemeal led to half-coverage.
This taxonomy is the single source of truth for what every token means,
defaults, dark-mode value, customer customizer field that drives it
(or "framework-only"), and the legacy alias being deprecated.

**Audit baseline:**
- Existing `--bx-color-*` tokens defined in `inc/Tokens/Component.php`: 35
- Hardcoded hex values in source CSS: 81 chromatic + 58 neutral = 139 unique
- Customer customizer color fields in `Skin_Fields.php`: 31 (color) + 5 (typography color)

---

## Naming convention

```
--bx-color-<role>             # color tokens
--bx-radius-<purpose>         # corner radii
--bx-space-<purpose>          # spacing
--bx-shadow-<level>           # box shadows
--bx-z-<layer>                # z-index ladder
--bx-duration-<speed>         # transition durations
--bx-easing-<curve>           # transition easings
```

All tokens emit at `:root` from `_custom-properties.css` (defaults) or
`Tokens/Component.php` inline (customer overrides). Dark-mode variants
emit at `:root[data-bx-mode="dark"]` and the auto-mode media query.

---

## Color tokens — complete map

Status legend:
- ✅ defined today — exists in inc/Tokens/Component.php
- 🆕 add in 5.1.0 — new token to introduce now
- 🔧 redefine — exists but needs cleanup (rename, alias, default)

### A. Surfaces

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-bg` | ✅ | `#ffffff` | `#0a0a0a` | `body_background_color` | `--color-theme-body` |
| `--bx-color-bg-page` | ✅ | `#ffffff` | `#0a0a0a` | `content_background_color` | `--color-layout-boxed` |
| `--bx-color-bg-elevated` | ✅ | `#ffffff` | `#161616` | `box_background_color` | `--color-theme-white-box` |
| `--bx-color-bg-muted` | ✅ | `#fafafa` | `#101010` | `secondary_background_color` | `--global-body-lightcolor` |
| `--bx-color-bg-overlay` | 🆕 | `rgba(0,0,0,0.5)` | `rgba(0,0,0,0.7)` | framework-only | — |
| `--bx-color-bg-subtle` | 🆕 | `#f7f7f9` | `#0d0d0d` | framework-only | — |

### B. Foreground (text)

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-fg` | ✅ | `#1a1a1a` | `#f5f5f5` | `typography_option[color]` | `--global-font-color` |
| `--bx-color-fg-muted` | ✅ | `#757575` | `#a0a0a0` | framework-only | — |
| `--bx-color-fg-subtle` | 🆕 | `#9ca3af` | `#6b7280` | framework-only | — |
| `--bx-color-fg-inverse` | 🆕 | `#ffffff` | `#0a0a0a` | framework-only | — |

### C. Brand / accent

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-accent` | ✅ | `#ef5455` | `#ff6b6b` | `site_primary_color` | `--color-theme-primary` |
| `--bx-color-accent-hover` | 🆕 | `#ee4036` | `#ff8989` | framework-only | — |
| `--bx-color-accent-bg` | 🆕 | `rgba(239,84,85,0.08)` | `rgba(255,107,107,0.12)` | framework-only | — |

### D. Interactive — links

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-link` | ✅ | `#1a1a1a` | `#f5f5f5` | `site_links_color` | `--color-link` |
| `--bx-color-link-hover` | ✅ | `#ef5455` | `#ff6b6b` | `site_links_focus_hover_color` | `--color-link-hover` |

### E. Interactive — buttons

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-button-bg` | ✅ | `#ef5455` | `#ff6b6b` | `site_buttons_background_color` | `--button-background-color` |
| `--bx-color-button-bg-hover` | ✅ | `#f83939` | `#ff8989` | `site_buttons_background_hover_color` | `--button-background-hover-color` |
| `--bx-color-button-fg` | ✅ | `#ffffff` | `#0a0a0a` | `site_buttons_text_color` | `--button-text-color` |
| `--bx-color-button-fg-hover` | ✅ | `#ffffff` | `#0a0a0a` | `site_buttons_text_hover_color` | `--button-text-hover-color` |
| `--bx-color-button-border` | ✅ | `#ef5455` | `#ff6b6b` | `site_buttons_border_color` | `--button-border-color` |
| `--bx-color-button-border-hover` | ✅ | `#f83939` | `#ff8989` | `site_buttons_border_hover_color` | `--button-border-hover-color` |

### F. Header / branding

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-header-bg` | ✅ | `#ffffff` | `#0a0a0a` | `site_header_bg_color` | `--color-header-bg` |
| `--bx-color-site-title` | ✅ | `#111111` | `#f5f5f5` | `site_title_typography_option[color]` | `--color-site-title` |
| `--bx-color-site-title-hover` | ✅ | `#ee4036` | `#ff6b6b` | `site_title_hover_color` | `--color-site-title-hover` |
| `--bx-color-site-tagline` | ✅ | `#757575` | `#a0a0a0` | `site_tagline_typography_option[color]` | `--color-site-tagline` |

### G. Navigation

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-menu-fg` | ✅ | `#111111` | `#e5e5e5` | `menu_typography_option[color]` | `--color-menu` |
| `--bx-color-menu-hover` | ✅ | `#ee4036` | `#ff6b6b` | `menu_hover_color` | `--color-menu-hover` |
| `--bx-color-menu-active` | ✅ | `#ee4036` | `#ff6b6b` | `menu_active_color` | `--color-menu-active` |
| `--bx-color-menu-bg` | 🆕 | transparent | transparent | framework-only | — |
| `--bx-color-subheader-fg` | ✅ | `#111111` | `#f5f5f5` | `site_sub_header_typography[color]` | `--color-subheader-title` |
| `--bx-color-subheader-bg` | 🆕 | `#fafafa` | `#161616` | framework-only (Sub_Header bg setting) | — |

### H. Headings

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-h1` | ✅ | `#111111` | `#f5f5f5` | `h1_typography_option[color]` | `--color-h1` |
| `--bx-color-h2` | ✅ | `#111111` | `#f5f5f5` | `h2_typography_option[color]` | `--color-h2` |
| `--bx-color-h3` | ✅ | `#111111` | `#f5f5f5` | `h3_typography_option[color]` | `--color-h3` |
| `--bx-color-h4` | ✅ | `#111111` | `#f5f5f5` | `h4_typography_option[color]` | `--color-h4` |
| `--bx-color-h5` | ✅ | `#111111` | `#e5e5e5` | `h5_typography_option[color]` | `--color-h5` |
| `--bx-color-h6` | ✅ | `#111111` | `#e5e5e5` | `h6_typography_option[color]` | `--color-h6` |

### I. Footer

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-footer-bg` | 🆕 | `#fafafa` | `#0a0a0a` | framework-only (Footer bg setting) | — |
| `--bx-color-footer-title` | ✅ | `#111111` | `#f5f5f5` | `site_footer_title_color` | `--color-footer-title` |
| `--bx-color-footer-fg` | ✅ | `#505050` | `#a0a0a0` | `site_footer_content_color` | `--color-footer-content` |
| `--bx-color-footer-link` | ✅ | `#111111` | `#e5e5e5` | `site_footer_links_color` | `--color-footer-link` |
| `--bx-color-footer-link-hover` | ✅ | `#ee4036` | `#ff6b6b` | `site_footer_links_hover_color` | `--color-footer-link-hover` |

### J. Copyright

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-copyright-bg` | ✅ | `#ffffff` | `#0a0a0a` | `site_copyright_background_color` | `--color-copyright-bg` |
| `--bx-color-copyright-border` | ✅ | `#e8e8e8` | `#2a2a2a` | `site_copyright_border_color` | (none) |
| `--bx-color-copyright-fg` | ✅ | `#505050` | `#a0a0a0` | `site_copyright_content_color` | `--color-copyright-content` |
| `--bx-color-copyright-link` | ✅ | `#111111` | `#e5e5e5` | `site_copyright_links_color` | `--color-copyright-link` |
| `--bx-color-copyright-link-hover` | ✅ | `#ee4036` | `#ff6b6b` | `site_copyright_links_hover_color` | `--color-copyright-link-hover` |

### K. Loader

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-loader-bg` | ✅ | `#ef5455` | `#161616` | `site_loader_bg` | `--color-theme-loader` |
| `--bx-loader-color` | ✅ | `#ffffff` | `#ffffff` | `site_loader_color` | (none — set via Output_Builder) |
| `--bx-loader-speed` | ✅ | `1.5s` | (same) | `site_loader_speed` | (none) |

### L. Structural — borders / dividers / shadows

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-border` | ✅ | `#e8e8e8` | `#2a2a2a` | framework-only | `--global-border-color` |
| `--bx-color-border-strong` | 🆕 | `#d4d4d4` | `#3a3a3a` | framework-only | — |
| `--bx-color-divider` | ✅ | `#f0f0f0` | `#1a1a1a` | framework-only | — |
| `--bx-color-shadow` | ✅ | `rgba(0,0,0,0.08)` | `rgba(0,0,0,0.4)` | framework-only | — |
| `--bx-color-shadow-strong` | 🆕 | `rgba(0,0,0,0.16)` | `rgba(0,0,0,0.6)` | framework-only | — |

### M. State — semantic

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-success` | 🆕 | `#16a34a` | `#22c55e` | framework-only | (replaces hardcoded `#4caf50`, `#27ae60`, `#48bf1e`) |
| `--bx-color-success-bg` | 🆕 | `#dcfce7` | `#14532d` | framework-only | (replaces hardcoded `#e4f6dd`) |
| `--bx-color-warning` | 🆕 | `#eab308` | `#facc15` | framework-only | (replaces hardcoded `#f1c40f`) |
| `--bx-color-warning-bg` | 🆕 | `#fef9c3` | `#713f12` | framework-only | — |
| `--bx-color-error` | 🆕 | `#dc2626` | `#ef4444` | framework-only | (replaces hardcoded `#c0392b`, `red`, `#db2828`) |
| `--bx-color-error-bg` | 🆕 | `#fee2e2` | `#7f1d1d` | framework-only | — |
| `--bx-color-info` | 🆕 | `#0284c7` | `#38bdf8` | framework-only | (replaces hardcoded `#2980b9`, `#0957d0`, `#2271b1`) |
| `--bx-color-info-bg` | 🆕 | `#dbeafe` | `#1e3a8a` | framework-only | — |

### N. State — community / presence (BP/Youzify)

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-presence-online` | 🆕 | `#16a34a` | `#22c55e` | framework-only | (replaces `#4caf50` BP "online") |
| `--bx-color-presence-away` | 🆕 | `#eab308` | `#facc15` | framework-only | — |
| `--bx-color-presence-busy` | 🆕 | `#dc2626` | `#ef4444` | framework-only | — |
| `--bx-color-presence-offline` | 🆕 | `#9ca3af` | `#6b7280` | framework-only | — |
| `--bx-color-bp-friend` | 🆕 | `#0284c7` | `#38bdf8` | framework-only | (consistent with info) |
| `--bx-color-bp-favorite` | 🆕 | `#dc2626` | `#ef4444` | framework-only | (replaces hardcoded `#ff2d55`) |

### O. Forms

| Token | Status | Light default | Dark default | Customer field | Legacy alias |
|---|---|---|---|---|---|
| `--bx-color-input-bg` | 🆕 | `#ffffff` | `#161616` | framework-only | — |
| `--bx-color-input-border` | 🆕 | `#d4d4d4` | `#3a3a3a` | framework-only | — |
| `--bx-color-input-focus-border` | 🆕 | `#ef5455` (= accent) | `#ff6b6b` (= accent) | framework-only | — |
| `--bx-color-input-fg` | 🆕 | `#1a1a1a` (= fg) | `#f5f5f5` (= fg) | framework-only | — |
| `--bx-color-input-placeholder` | 🆕 | `#9ca3af` | `#6b7280` | framework-only | — |

---

## Dimension tokens

| Token | Status | Default | Customer field | Legacy alias |
|---|---|---|---|---|
| `--bx-radius-global` | ✅ | `8px` | `site_global_border_radius` | `--global-border-radius` |
| `--bx-radius-button` | ✅ | `6px` | `site_button_border_radius` | `--button-border-radius` |
| `--bx-radius-form` | ✅ | `4px` | `site_form_border_radius` | `--form-border-radius` |
| `--bx-radius-card` | 🆕 | `12px` | framework-only | — |
| `--bx-radius-pill` | 🆕 | `999px` | framework-only | — |

---

## Spacing / sizing tokens

(NEW category — currently mixed hardcoded values in compat CSS)

| Token | Status | Default | Customer field |
|---|---|---|---|
| `--bx-space-section` | 🆕 | `60px` (clamp on viewport) | framework-only |
| `--bx-space-card` | 🆕 | `24px` | framework-only |
| `--bx-space-inline` | 🆕 | `8px` | framework-only |
| `--bx-space-stack` | 🆕 | `12px` | framework-only |

---

## Effect tokens

(NEW category)

| Token | Status | Default | Customer field |
|---|---|---|---|
| `--bx-shadow-card-sm` | 🆕 | `0 1px 2px var(--bx-color-shadow)` | framework-only |
| `--bx-shadow-card-md` | 🆕 | `0 4px 12px var(--bx-color-shadow)` | framework-only |
| `--bx-shadow-card-lg` | 🆕 | `0 12px 32px var(--bx-color-shadow-strong)` | framework-only |
| `--bx-duration-fast` | 🆕 | `120ms` | framework-only |
| `--bx-duration-base` | 🆕 | `200ms` | framework-only |
| `--bx-duration-slow` | 🆕 | `400ms` | framework-only |
| `--bx-easing-base` | 🆕 | `cubic-bezier(0.4, 0, 0.2, 1)` | framework-only |
| `--bx-easing-bounce` | 🆕 | `cubic-bezier(0.68, -0.55, 0.265, 1.55)` | framework-only |

---

## Z-index ladder

(NEW category — currently scattered z-index values across CSS)

| Token | Status | Default | Purpose |
|---|---|---|---|
| `--bx-z-base` | 🆕 | `1` | Default content |
| `--bx-z-dropdown` | 🆕 | `100` | Menu dropdowns |
| `--bx-z-sticky-header` | 🆕 | `999` | Sticky site header |
| `--bx-z-overlay` | 🆕 | `9999` | Mobile menu overlay, modals |
| `--bx-z-loader` | 🆕 | `999991` | Site loader (matches existing) |
| `--bx-z-toast` | 🆕 | `999999` | Toast notifications, urgent |

---

## Counts

| Category | Tokens defined today (5.1.0) | Tokens to add now | Total target |
|---|---:|---:|---:|
| Color — Surface | 4 | 2 | 6 |
| Color — Foreground | 2 | 2 | 4 |
| Color — Brand | 1 | 2 | 3 |
| Color — Links | 2 | 0 | 2 |
| Color — Buttons | 6 | 0 | 6 |
| Color — Header/branding | 4 | 0 | 4 |
| Color — Navigation | 3 | 2 | 5 |
| Color — Headings | 6 | 0 | 6 |
| Color — Footer | 4 | 1 | 5 |
| Color — Copyright | 5 | 0 | 5 |
| Color — Loader | 3 | 0 | 3 |
| Color — Structural | 3 | 2 | 5 |
| Color — State | 0 | 8 | 8 |
| Color — Community | 0 | 6 | 6 |
| Color — Forms | 0 | 5 | 5 |
| Dimension — Radius | 3 | 2 | 5 |
| Dimension — Spacing | 0 | 4 | 4 |
| Effect — Shadow | 0 | 3 | 3 |
| Effect — Motion | 0 | 5 | 5 |
| Z-index | 0 | 6 | 6 |
| **TOTAL** | **46** | **50** | **96** |

---

## Implementation order (5.1.0 sub-phases)

1. **Phase 1.0 — Token definitions in PHP** (~2 hours, zero UI risk)
   - Add new tokens to `inc/Tokens/Component.php` `$framework_tokens`,
     `$dark_defaults`, plus new `$state_tokens`, `$community_tokens`,
     `$form_tokens`, `$shadow_tokens`, `$motion_tokens`, `$z_tokens`
   - Tokens emit at `:root` from PHP only — no CSS consumer changes
   - Verify via getComputedStyle: all 96 tokens present with sensible
     defaults
2. **Phase 1.1 — _custom-properties.css canonical** (~1 hour)
   - Replicate the 96-token taxonomy in source CSS for editor-iframe
     parity (which doesn't load PHP-emitted inline styles)
3. **Phase 2 — Migration begins** — per the architecture plan's phases
   2-7, with mappings KNOWN UPFRONT from this doc

---

## What this taxonomy enables

- **Phase 7** (customizer ↔ style variations): each variation declares
  its palette via the SAME 96 tokens. Customer customizer + variation
  reconcile through a documented precedence rule.
- **5.2.x dark-mode customer overrides**: the 96 tokens already have
  dark defaults; per-mode customizer fields can override per token.
- **5.2.x palette presets**: a "warm/cool/vibrant" preset is just a
  delta over the 96 token defaults — easy to define and ship.
- **Block patterns**: patterns reference `var(--bx-color-*)`, automatically
  pick up customer/variation/preset.
- **3rd-party CSS** (BuddyPress / WooCommerce / LearnDash compat):
  when migrated, all 87 hardcoded hex values become `var()` refs that
  flow customer choices through.

---

## Open question for stakeholder

1. **Naming**: keep `--bx-color-presence-online` etc. or rename to
   `--bx-color-status-online`? Either is consistent.
2. **Customer customization for state colors (success/error/warning/info)**?
   Currently planned as framework-only. Could expose a Customizer ▸
   State Colors section if customers want brand-aligned state colors.
   Probably defer to 5.3.0.
3. **Aliases vs hard-deprecation**: keep emitting the legacy
   `--color-theme-*` aliases for the next 2 minor versions, then remove
   in 5.4.0? Or aggressive removal in 5.3.0?
4. **Dimensions / spacing / motion / z-index**: customer-controllable
   for any of these (beyond the 3 radii already in customizer)? Probably
   no — these are theme-author territory.
