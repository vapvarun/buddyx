# Dynamic-template token coverage — 5.2.x scope

**Direction:** BuddyX is a community-engine theme, not a static-pattern theme.
The customer's value comes from dynamic, template-driven plugins (BuddyPress,
WooCommerce, LearnDash, Dokan, MultiVendorX, FluentCart, SureCart, bbPress,
LifterLMS, Events Calendar, Platform/Wbcom Platform, WP Job Manager,
plus future **Jetonomy** integration). The theme must make those plugins
look right out of the box AND propagate customer customizer choices into
their dynamic surfaces.

**Status:** Audit complete. No code yet. Awaiting stakeholder review of
priority + Jetonomy direction before 5.2.x scoping commits.

---

## Audit findings — token coverage in compat stylesheets

`assets/css/<plugin>.min.css` are the per-plugin compat stylesheets BuddyX
ships. They're enqueued conditionally when the plugin's main class /
constant is present (`inc/Styles/Component.php`).

For customer customizer choices (`site_primary_color`, button colors,
link colors, etc.) to flow into these plugins' rendered surfaces,
the compat stylesheets must reference CSS variables (either the new
`--bx-color-*` tokens or the legacy `--color-theme-*` aliases). Direct
hardcoded hex values BYPASS the token system — those parts of the
plugin's screens render with a fixed color regardless of customer
brand choice.

| Plugin | File size | `--bx-*` refs | `--color-theme-*` refs | Unique hardcoded hex | Coverage grade |
|---|---|---|---|---|---|
| BuddyPress | 129 KB | 51 | 32 | **50** | 🟡 mixed — 50 hardcoded leaks |
| WooCommerce | 29 KB | 12 | 20 | **37** | 🔴 heavy hardcoded |
| LearnDash | 22 KB | 1 | 5 | 3 | 🟢 small but legacy-heavy |
| Dokan | 18 KB | 2 | 11 | 4 | 🟡 legacy-heavy |
| Platform (Wbcom) | 66 KB | 22 | 22 | **29** | 🟡 mixed |
| LifterLMS | 8 KB | 5 | 9 | 4 | 🟢 small |
| MultiVendorX | 6 KB | 1 | 7 | 1 | 🟢 small, legacy-heavy |
| bbPress | 5 KB | 3 | 1 | 3 | 🟢 small |
| FluentCart | 3 KB | 0 | 2 | 2 | 🟢 minimal |
| SureCart | 3 KB | 0 | 0 | 0 | 🟢 minimal (no chroma anyway) |
| Events Calendar | 26 KB | 0 | 8 | 4 | 🟢 small, legacy-only |
| WP Job Manager | n/a | n/a | n/a | n/a | needs audit |
| Youzify | n/a | n/a | n/a | n/a | needs audit |

**Key concern: BuddyPress + WooCommerce.** These are the two highest-traffic
dynamic surfaces for BuddyX customers. Together they have **87 unique
hardcoded hex values** that ignore customer customizer choices.

Examples of BP hardcoded leaks (top 10 by frequency):
- `#fff` × 38 (could often be `var(--bx-color-bg-elevated)`)
- `#bad` × 19 (cover/avatar placeholder; deliberate? needs review)
- `#0000` × 8 (transparent — fine, ignore)
- `#4caf50` × 5 (BP success-green; deliberate brand)
- `#122b461f` × 4, `#0003` × 4 (overlay tints)
- `#ffefba`, `#f5f5f5`, `#fffc`, `#ededed` × 2-3 each (neutrals)

Many of these are sensible defaults (transparency overlays, brand-greens
for success states, off-white card surfaces) that the customer SHOULDN'T
override. But the body bg (`#fff`) and link/text neutrals SHOULD route
through the token system so customer brand colors apply.

---

## Plugins with template (PHP) overrides today

BuddyX ships its own template overrides for selected dynamic flows. These
sit at the theme root in their plugin-namespaced directories:

```
buddypress/
├── activity/{entry, single/home}.php
├── groups/{groups-loop, single/{cover-image-header, group-header, home, members-loop}}.php
└── members/{members-loop, single/{cover-image-header, member-header}}.php

woocommerce/
├── single-product.php
├── archive-product.php
└── cart/cart.php
```

**Coverage gaps in template overrides:**

- LearnDash — 0 template overrides (only CSS). LD ships its own templates;
  BuddyX styling is CSS-only. Possible future gap if LD's default templates
  don't fit BuddyX layouts.
- Woo — only 3 templates. Newer flows like checkout, account dashboard,
  wishlist not overridden.
- BuddyPress — covers core community flows but not BP newer features
  (cover images on profile editor, settings sub-pages, blogs/messaging
  templates).
- bbPress, Dokan, EventsCalendar, etc. — CSS only, no template overrides.

---

## Jetonomy — known unknown

The user noted Jetonomy as a key dynamic-template integration alongside
BP and Woo. No Jetonomy WordPress plugin found locally:

- `/Users/varundubey/Projects/jetonomy-app` (mobile app?)
- `/Users/varundubey/Projects/jetonomy-app-server` (server / API?)

Plugin search on this WP install returns 0 results for "jetonomy".

**Stakeholder question:**
- What form does the Jetonomy → BuddyX integration take? WordPress plugin
  with shortcodes? REST-API consumer? Something else?
- Is there a Jetonomy WP plugin in development that BuddyX should ship
  compat for?
- If yes, where's the source so we can inspect its CSS class structure
  and template entry points?

Without this, we can't write Jetonomy compat. Documented as a gap.

---

## Recommended 5.2.x scope (ordered by impact)

### Phase 1 — BuddyPress + WooCommerce token-migration (high impact, ~3 days)
For each `assets/css/src/<plugin>.css`:
1. Replace hardcoded `#fff` body/card backgrounds → `var(--bx-color-bg-elevated, #fff)`
2. Replace hardcoded text colors → `var(--bx-color-fg, #1a1a1a)` /
   `var(--bx-color-fg-muted, #757575)`
3. Replace hardcoded primary-color hex (e.g. `#ef5455`) →
   `var(--color-theme-primary, #ef5455)` (via legacy alias the customer
   already uses)
4. KEEP brand-success/error colors hardcoded (e.g. BP `#4caf50` for
   "online" state — that's an intentional brand signal, not customer
   territory)
5. Rebuild via `npm run build:css`

Expected reduction: ~60 hardcoded hex values across BP+Woo become tokens.
Customer's customizer-saved colors flow into BP member directories,
group headers, Woo product cards, cart, checkout.

### Phase 2 — LearnDash template overrides + token coverage (~2 days)
- Add `learndash/` directory with overrides for course-list, lesson-list,
  course-progress patterns to align with BuddyX layout system
- Migrate hardcoded colors in `learndash.min.css` to tokens (small
  surface — only 3 hardcoded values today)

### Phase 3 — Jetonomy compat skeleton (TBD — pending stakeholder direction)
- If Jetonomy ships as a WordPress plugin: add `assets/css/src/jetonomy.css`
  + conditional enqueue + (optional) template override directory
- If Jetonomy is API-driven: probably out of theme scope; Jetonomy app
  styles itself

### Phase 4 — Round out smaller compats (~1 day)
- bbPress, FluentCart, SureCart token migration
- WPJobManager, Youzify, Platform audit + cleanup
- Verify pre-existing stylelint debt (mentioned in
  `2026-05-06-tokens-cleanup-5_2_0.md`) doesn't block these edits — if
  it does, paydown of stylelint debt becomes Phase 0

---

## Acceptance criteria (per plugin in Phase 1)

- All occurrences of `#fff` / `#ffffff` as body/card BG → `var(--bx-color-bg-elevated, #fff)`
- All neutral text grays → `var(--bx-color-fg, ...)` /
  `var(--bx-color-fg-muted, ...)`
- All accent/primary occurrences → `var(--color-theme-primary, ...)` (legacy alias)
- Brand-state colors (success-green, error-red, info-blue) stay hardcoded
- Browser test: customer with `site_primary_color=#abc123` saved sees:
  - BP member directory: link colors / button bg use `#abc123`
  - BP group header: action buttons use `#abc123`
  - Woo product card: prices, sale badge, "Add to cart" button use `#abc123`
  - Woo cart: subtotal accent / "Proceed to checkout" button use `#abc123`
- Default site (no customizer overrides) renders byte-identical to today

---

## Out of scope

- Block patterns for static landing pages (Hero / Pricing / FAQ etc.) —
  those exist already, no expansion planned. BuddyX is dynamic-first.
- Full FSE migration — explicitly dropped per stakeholder direction.
- Adding new BP/Woo features (these are plugin territory) — only
  styling and template-override compat in BuddyX's swimlane.
