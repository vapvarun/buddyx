# Visitor color-mode toggle (light / dark / auto) — fully-cooked plan

**Status:** queued for implementation after 5.1.0 ships
**Owner:** BuddyX core
**Tracking:** new feature, no Basecamp card yet
**Why now:** dark-mode infrastructure (token swap, FOUC head-script, customizer default) shipped in Phase 4. The visitor-side toggle was deferred and the customizer field description already references it — the feature is currently half-cooked. This plan completes it.

---

## 1. Problem

5.1.0 ships dark-mode token plumbing but no UI for visitors to choose. Today:

- Site admins can set `site_color_mode` to `light`, `dark`, or `auto` (follow OS) in the Customizer.
- The bootstrap script (`inc/Tokens/Component.php::output_color_mode_init_script`) already reads `localStorage.getItem('bx-color-mode')` BEFORE the customizer default — so visitor preference wins if it exists.
- But there's no UI to *write* `localStorage.bx-color-mode`. So the override path is dead code.

Result: the customizer description literally tells admins "Visitors can override via the front-end toggle if you add one." We shipped a hole.

## 2. Audience

**Both guests and logged-in members.** A reading-mode preference is a per-device UX setting, not an account setting. Storing in `localStorage` covers both:

- **Guests:** localStorage persists across visits on the same browser.
- **Logged-in members:** localStorage handles per-device (most common case). Cross-device sync via `user_meta` is **out of scope for v1** — adds REST round-trip + login dependency. If demand surfaces, add as v2.

## 3. Scope

### In scope (v1)

1. Front-end toggle button — site-wide, visible to everyone.
2. 3-state cycle: `light → dark → auto → light…` matching the customizer's three values.
3. Visual icon swap (sun / moon / monitor) reflecting current state.
4. `aria-pressed` and `aria-label` for screen readers.
5. Keyboard activation (Enter, Space).
6. localStorage write on click.
7. Live page swap (no reload) — already works because `data-bx-mode` cascades through CSS.
8. Customizer toggle: admin can hide the button entirely if they prefer to lock site to one mode.
9. Customizer placement: header (default) or mobile menu only or both.
10. Mobile-menu integration so the toggle is reachable on touch devices.

### Out of scope (deferred)

- Cross-device sync for logged-in members (v2; needs REST + nonce + meta migration).
- "System mode followed automatically" notification toast (auto already happens silently).
- Custom icon set per customizer (use Lucide-style SVG inline, single visual identity).
- Animations between states beyond the 200ms color transition that already exists in `bx-tokens-applied.css`.
- Per-section overrides ("dark for member directory only") — not a real customer ask.

## 4. UX design

### Button anatomy

```
<button class="bx-color-mode-toggle"
        type="button"
        aria-label="{{ current state label }} - click to change"
        aria-pressed="{{ true if dark }}"
        data-mode="{{ light | dark | auto }}">
  <svg class="bx-icon bx-icon-sun"     aria-hidden="true">…</svg>
  <svg class="bx-icon bx-icon-moon"    aria-hidden="true">…</svg>
  <svg class="bx-icon bx-icon-monitor" aria-hidden="true">…</svg>
  <span class="screen-reader-text">{{ current state }}</span>
</button>
```

CSS hides 2 of the 3 SVGs based on `[data-mode="…"]` attribute on the button. No JS needed for the visual swap beyond updating `data-mode`.

### State labels (i18n strings)

- `light` → "Light mode (click to switch to dark)"
- `dark`  → "Dark mode (click to switch to system)"
- `auto`  → "System mode (click to switch to light)"

### Placement

- **Default:** rightmost slot in `.menu-icons-wrapper` (next to cart/notifications). Uses the same `<div class="bx-mode-wrapper">` pattern as cart.
- **Mobile menu:** mirrored as a list-item at the bottom of `.buddyx-mobile-menu`.
- **Customizer fields (new section under Skin):**
  - `site_color_mode_toggle_show` (toggle: on/off, default `on`)
  - `site_color_mode_toggle_position` (radio: `header`, `mobile_only`, `both`, default `both`)

### Visual

- 32×32 hit area (matches cart/notification icons).
- Inherits header foreground color (`var(--bx-color-menu-fg)`).
- Hover: matches sibling icons (`var(--bx-color-menu-hover)`).
- Active state visual identical to `aria-pressed=true` styling on existing nav.

### What NOT to do

- No floating fixed-corner toggle (looks like an after-thought widget; adds CLS risk if injected late).
- No animated emoji or gimmicks.
- No "are you sure?" confirmation — instant swap is the point.

## 5. Technical implementation

### File map

```
inc/Color_Mode_Toggle/
  Component.php          — new: wires up customizer fields + render hook
  toggle-template.php    — new: server-renders the <button> markup
inc/Customizer_Settings/Fields/Skin_Fields.php
                         — extend: add 2 new fields under existing
                           Skin / Color Mode cluster
assets/js/src/color-mode-toggle.js
                         — new: handles click → cycle state, write
                           localStorage, update data-bx-mode + button
                           data-mode + aria-pressed
assets/css/src/_color-mode-toggle.css
                         — new: button styling + icon visibility rules
inc/Scripts/Component.php
                         — modify: register/enqueue color-mode-toggle.js
inc/Styles/Component.php
                         — modify: import _color-mode-toggle.css into
                           global.css (foundation tier, always loaded)
inc/extra.php
                         — modify: hook toggle render at the right point
                           in .menu-icons-wrapper + .buddyx-mobile-menu
```

### State machine

```
                   click
       ┌──────────────────────────┐
       v                          │
    [ light ] → [ dark ] → [ auto ] ─┘
```

On click:
1. Read current `data-mode` from button.
2. Compute next: `light → dark`, `dark → auto`, `auto → light`.
3. Write `localStorage.setItem('bx-color-mode', next)`.
4. Update `document.documentElement.setAttribute('data-bx-mode', next === 'auto' ? 'auto' : next)`.
5. Update button: `setAttribute('data-mode', next)`, `aria-pressed`, `aria-label`, `screen-reader-text`.
6. Dispatch a `bx:color-mode-change` CustomEvent for plugins (BuddyX Pro hooks, third-party widgets).

### FOUC interaction

Already handled. The bootstrap script in `inc/Tokens/Component.php::output_color_mode_init_script` runs in `<head>` BEFORE any rendering, reads localStorage, sets `data-bx-mode` on `<html>`. Our toggle just rewrites localStorage + the attribute on subsequent clicks — no flash.

### Customizer wiring

Field 1 — show/hide toggle:
```php
Field::add( 'switch', array(
    'settings'    => 'site_color_mode_toggle_show',
    'label'       => __( 'Show color-mode toggle', 'buddyx' ),
    'description' => __( 'Lets visitors switch between light, dark, and system. Hide if you want to lock the site to one mode.', 'buddyx' ),
    'section'     => 'site_skin_section',
    'default'     => 'on',
    'choices'     => array( 'on' => __( 'Show', 'buddyx' ), 'off' => __( 'Hide', 'buddyx' ) ),
) );
```

Field 2 — placement:
```php
Field::add( 'radio', array(
    'settings'         => 'site_color_mode_toggle_position',
    'label'            => __( 'Toggle position', 'buddyx' ),
    'section'          => 'site_skin_section',
    'default'          => 'both',
    'choices'          => array(
        'header'      => __( 'Header only',      'buddyx' ),
        'mobile_only' => __( 'Mobile menu only', 'buddyx' ),
        'both'        => __( 'Header + mobile menu', 'buddyx' ),
    ),
    'active_callback'  => array(
        array( 'setting' => 'site_color_mode_toggle_show', 'operator' => '==', 'value' => 'on' ),
    ),
) );
```

### Render gating

`buddyx_render_color_mode_toggle()` early-returns if:
- `site_color_mode_toggle_show !== 'on'`
- (for header context) `site_color_mode_toggle_position === 'mobile_only'`
- (for mobile-menu context) `site_color_mode_toggle_position === 'header'`

### Initial render data-mode

Server reads `site_color_mode` (admin default) and renders the button with that as initial `data-mode`. The bootstrap script then overrides on the client side if localStorage has a different value, and we re-sync the button's `data-mode` to match — done via a tiny inline script right after the toggle markup OR a single `DOMContentLoaded` in the toggle JS.

## 6. Edge cases

| Case | Handling |
|---|---|
| User has `prefers-reduced-motion: reduce` | Skip the 200ms color transition (existing CSS already respects this) |
| User clicks toggle then toggles OS dark mode | Visitor's localStorage choice wins until they click again or clear localStorage |
| `localStorage` unavailable (private mode in some browsers) | `try/catch` around localStorage call. Toggle still cycles per-page-load but doesn't persist. No JS error. |
| Customizer admin sets site to "Dark" and hides toggle | Site is dark for everyone, no escape — that's the admin's intent. Honored. |
| Multisite / customer overrides theme color via plugin | `data-bx-mode` is theme-level; plugins that fight the cascade are out of scope. |
| BP Pro / Reign Pro want their own placement | `bx:color-mode-change` event + filter on render gating selector lets them customize. |
| Browser back-button restores stale `<html data-bx-mode>` | bfcache `pageshow` listener re-syncs from localStorage. |
| RTL layouts | Button uses logical properties (`margin-inline-start`); RTL just works. |
| Server-rendered icon mismatch flashes for ~50ms | Bootstrap script syncs button `data-mode` IMMEDIATELY after `<html data-bx-mode>` is set. Inline script in `<head>`. No flash. |

## 7. Acceptance criteria

A reviewer can verify each:

1. ✅ Toggle button renders in header on every front-end page.
2. ✅ Mobile menu shows toggle as a list item.
3. ✅ Click cycles light → dark → auto → light correctly.
4. ✅ Page colors swap immediately, no reload, no flash.
5. ✅ Refresh page → mode persists (localStorage).
6. ✅ New tab on same site → mode persists.
7. ✅ Set OS to dark, choose `auto` → site goes dark; switch OS to light → site goes light without reload.
8. ✅ `aria-pressed` flips on dark, `aria-label` updates on each click.
9. ✅ Tab to button + Enter triggers cycle.
10. ✅ Admin sets `site_color_mode_toggle_show = off` → button disappears front-end.
11. ✅ Admin sets `position = mobile_only` → desktop header has no button.
12. ✅ No PHP errors, no JS console errors.
13. ✅ Lighthouse a11y score unchanged or improved.
14. ✅ CLS still 0 — toggle has reserved 32×32 slot.
15. ✅ Works for guest visitors (logged out) — no auth dependency.
16. ✅ Works for logged-in members — same behavior as guests in v1.
17. ✅ Customizer live preview reflects toggle position changes.
18. ✅ Selectively-enqueued bundle: toggle JS only loads when `site_color_mode_toggle_show = on`.
19. ✅ RTL passes visual review.
20. ✅ Existing dark-mode infrastructure tests still pass.

## 8. Testing matrix

- **Browsers:** Chrome (current), Safari (current), Firefox (current), Edge (current)
- **Viewports:** 390px (mobile), 1024px (tablet), 1440px (desktop)
- **States:** logged-out guest, logged-in admin, logged-in subscriber
- **OS modes:** light + dark for the `auto` state verification
- **Page types:** home, blog single, BP member directory, WC shop, WC checkout, BP edit-profile

## 9. Estimated effort

- Customizer fields wiring: 30 min
- PHP renderer + render-gating: 1 hr
- JS toggle logic: 1 hr
- CSS + 3 inline-SVG icons: 1 hr
- Mobile menu placement: 30 min
- Bootstrap-script sync (button initial state): 30 min
- Manual QA across matrix: 2 hrs
- **Total: ~6.5 hrs / 1 day work**

## 10. Release plan

- Lands in **5.2.0** (NOT 5.1.0). 5.1.0 thesis is "Kirki removal + token system" — adding a new feature post-RC expands scope.
- 5.2.0 thesis becomes "Performance Pass + Visitor color-mode toggle" — both complete the dark-mode story.
- Backwards-compat: customers running 5.1.0 already have `site_color_mode` settings in theme_mods; the new toggle fields default to `on`, so toggle appears automatically on update unless admin explicitly hides.

## 11. Future v2 (deferred)

- **Cross-device sync for logged-in members:** Add `bx_color_mode` user_meta. On login, read meta → write localStorage. On click, write localStorage AND send REST PATCH to `/wp-json/buddyx/v1/me/color-mode`. Adds nonce + REST endpoint + capability check. ~3 hrs additional work.
- **Custom toggle position via filter:** `apply_filters( 'buddyx_color_mode_toggle_render_location', $location )` for theme-child overrides.
- **Preset accessibility modes:** "High contrast" / "Reduced contrast" as additional `data-bx-mode` values. Token taxonomy supports it; UI just needs more cycle states.

## 12. Decision log

- **Why not a `<select>` instead of a 3-state button?** Cycle button is one tap on mobile vs. select-then-tap. Modern apps (X/Twitter, GitHub, Tailwind UI docs) all use cycle button. Faster + smaller hit area.
- **Why not store in user_meta from day 1?** Adds REST + login coupling. 80% of value (per-device persistence) comes from localStorage alone. Keep v1 lean.
- **Why icon-only, no text label?** Header space is tight; sibling icons (search, cart, bell) are also icon-only. Consistency wins.
- **Why hide via customizer instead of disable via filter?** Customizer is where admins already manage `site_color_mode`. Same mental model.

## 13. Files changed (preview)

```
A inc/Color_Mode_Toggle/Component.php           ~120 lines
A inc/Color_Mode_Toggle/toggle-template.php      ~40 lines
M inc/Customizer_Settings/Fields/Skin_Fields.php  +30 lines
A assets/js/src/color-mode-toggle.js             ~80 lines
A assets/css/src/_color-mode-toggle.css          ~60 lines
M inc/Scripts/Component.php                      +5 lines (enqueue)
M inc/Styles/Component.php                       +1 line (import)
M inc/extra.php                                  +6 lines (render hooks)
M plans/2026-05-07-visitor-color-mode-toggle.md   this doc
```

Net add: ~340 LOC, 7 new files, 4 modified.
