# WooCommerce UX Audit — Planning Note (future work)

**Status:** Planning stub. Not yet executed. Slot after BuddyPress UX audit Phase 1 patches ship in 5.1.1.
**Bar:** Same as BuddyPress audit (`plans/2026-05-15-buddypress-css-ux-audit.md`) — 100% premium UX, inbuilt, uniform desktop+mobile, modern not legacy.
**Method:** Same as BP audit — `ux-audit` skill (`~/.claude/skills/ux-audit/`) static scan + Playwright per-screen browser measurement of computed tap targets, focus rings, border-radius, shadow, bg, padding, font-size on every interactive element. Both 1280px desktop and 390px mobile per screen.

## Head-start inventory (so the future planner doesn't re-do recon)

### WooCommerce CSS that BuddyX ships

| File | Built file | Lines (src) | Purpose |
|---|---|---:|---|
| `assets/css/src/woocommerce.css` | `woocommerce.css` + `.min.css` | 816 | Core WC theme styles (shop, product, cart, checkout, my-account) |
| `assets/css/src/wc-vendor.css` | (built) | — | WC Vendors compat (separate from core WC) |
| `assets/css/src/fluentcart.css` | (built) | — | FluentCart compat (separate cart plugin) |
| `assets/css/src/surecart.css` | (built) | — | SureCart compat (separate cart plugin) |
| `assets/css/src/dokan.css` | (built) | — | Dokan multi-vendor compat |
| `assets/css/src/multivendorx.css` | (built) | — | MultiVendorX compat |

**Scope decision** when the audit runs: WC core + WC Vendors. SureCart / FluentCart / Dokan / MultiVendorX are separate plugin ecosystems — list them as **out of scope** in the audit doc, same way Youzify/BuddyBoss were excluded from the BP audit. WC is the primary commerce engine; the others are alternates customers opt into.

### Overridden WC templates in BuddyX

Only **3** templates are theme-overridden (vs 13 for BP):

- `woocommerce/single-product.php`
- `woocommerce/archive-product.php`
- `woocommerce/cart/cart.php`

So most WC visible surfaces are rendered by WC's own templates (not BuddyX overrides). The audit should treat both "BuddyX-overridden" and "WC-default-rendered-through-BuddyX-CSS" identically — same lens as the BP audit ("all visible screens we are using" per stakeholder).

### WC-related Customizer toggles BuddyX exposes

| Setting | File | Effect |
|---|---|---|
| `site_cart` (switch) | `Header_Fields.php:49` | Header cart icon visibility |
| `woocommerce_sidebar_option` (radio_image) | `Sidebar_Fields.php:119` | Sidebar layout for WC pages (left / right / none) |

Both register only when WC is active (plugin-gated, per the `wbcom-kirki-to-tokens` skill's audit-rubric methodology — verify with the Mode 2 cross-check from the skill's Live Preview Audit helper).

### Visible-screens checklist (build the audit matrix from this)

**Tier 1 — pre-purchase (anonymous + logged-in):**
1. `/shop/` — Shop archive
2. `/product-category/<cat>/` — Category archive
3. `/product/<slug>/` — Single product (gallery, tabs, related)
4. Shop page with active filters / sorting
5. Mini-cart popout (header cart icon click)

**Tier 2 — purchase flow:**
6. `/cart/` — Cart page
7. `/checkout/` — Checkout (billing / shipping / payment / review)
8. `/checkout/order-received/<id>/` — Order confirmation

**Tier 3 — post-purchase (logged-in account):**
9. `/my-account/` — Dashboard
10. `/my-account/orders/` — Orders list
11. `/my-account/view-order/<id>/` — Single order view
12. `/my-account/downloads/`
13. `/my-account/edit-address/`
14. `/my-account/edit-account/`
15. `/my-account/payment-methods/`

**Tier 4 — auth surfaces:**
16. `/my-account/lost-password/`
17. `/my-account/` (logged-out → register + login forms)

**Variant axes (same as BP audit):**

- Default state @ 1280px + 390px
- Vertical nav variant (if applicable — `my-account` has the nav sidebar)
- Dark mode (Site Skin Phase 4 covers WC tokens — verify carries to new shadows/radii)
- RTL

### Expected findings (informed prediction from BP-audit patterns)

The BP audit's headline findings will almost certainly repeat on WC surfaces, since `buddypress.css` and `woocommerce.css` share BuddyX's same authoring conventions. Predict before measuring; verify in browser:

- **Focus rings missing** on WC product links, add-to-cart buttons, quantity inputs, my-account nav links, checkout fields, billing form selects, payment radio buttons. Apply the same Phase-1 cure (`.woocommerce-page :focus-visible { outline: 2px solid var(--bx-color-accent); outline-offset: 2px }`).
- **Tap targets below 40px** on quantity steppers (often default to 30px), my-account nav links, mini-cart remove buttons, payment-method radios.
- **Native `<select>` styling** on state/country dropdowns in checkout/billing/shipping — typically the worst-looking element on a checkout because it's a long native list.
- **Border-radius inconsistency** — same 6-value drift the BP audit found.
- **No card shadows** on product tiles in the shop grid. Probably 2px or 0 radius. Same flat-on-page feel.
- **Cart table** likely uses default WC `<table>` styling — premium needs a card-list pattern on mobile + a clean table on desktop.
- **Checkout layout** — single-column on mobile is correct, but the order-summary panel on desktop usually needs sticky-side positioning and modern card framing.

### Audit deliverable shape (mirror the BP audit doc)

When the future planner runs this:

1. Save the static scan output to `plans/audit-snapshots/<date>-ux-audit-woo-raw.md` (gitignored).
2. Walk every Tier 1-4 screen at both viewports; save screenshots to `plans/audit-evidence/wc-audit-*.png`.
3. Compile findings into `plans/<date>-woocommerce-css-ux-audit.md` with the same structure as the BP audit:
   - Headline result
   - What was tested vs inferred (pattern-lock table)
   - Findings categorized: A (standards) / B (UX-quality) / C (responsive) / D (inferred) / E (in flight).
   - Prioritized 4-phase cleanup plan (standards floor → visual lift → responsive → verify).
4. Cross-link from the BP audit doc + reference the same `ux-audit` skill method.
5. Single commit + push.

## Out of scope for the future WC audit

- **Non-WC commerce plugins** — SureCart, FluentCart, Dokan, MultiVendorX, WC Vendors. Each gets its own audit only if/when stakeholder asks.
- **Premium WC extensions** — Subscriptions, Bookings, Memberships, etc. — those are WC ecosystem add-ons, not BuddyX scope.
- **Stripe / payment-gateway UIs** — payment iframes are vendor-controlled.
- **WC admin** — order management, settings, etc. live in `wp-admin/` under the admin bar, separate concern.

## When to execute

After **5.1.1 BP Phase 1** ships (the standards-floor patch for BP). Reuse the audit method while it's fresh. Same single-session execution path that produced the BP audit.

## Reference

- BP audit (the template for this one): `plans/2026-05-15-buddypress-css-ux-audit.md`
- Free-theme gap inventory (informs WC-adjacent feature priorities): `plans/2026-05-15-free-theme-gap-inventory.md`
- ux-audit skill: `~/.claude/skills/ux-audit/SKILL.md` (static script + 3-mode browser sweep)
- Companion tokens skill: `~/.claude/skills/wbcom-kirki-to-tokens/SKILL.md` (Pillar 2 tokens, post-swap audit rubric, live-preview audit helper)
