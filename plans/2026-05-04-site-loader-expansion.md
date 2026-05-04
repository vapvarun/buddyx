# Site Loader — Premium UX Expansion

**Status:** Deferred to 5.2.0 (5.1.0 ships parity-only, no UX changes).
**Stakeholder note:** "Site Loader ? does not have any options we must need
something for them what icon what color what error think like a perfect
solution and log that value for future ref" — user, 2026-05-04.

---

## Current state (5.1.0 — unchanged from Kirki era)

```
Section: site_loader (panel: site_layout_panel)
Fields:  1
  - site_loader (switch, default '2')
```

That's it. One toggle. The loader is shown when `site_loader == '1'`
(consumer at `inc/extra.php:120-124`). The color lives in **a different
section** (Site Skin → `site_loader_bg`, default `#ef5455`).

The actual loader HTML is hardcoded in `inc/extra.php:122`:

```html
<div class="site-loader">
  <div class="loader-inner">
    <span class="dot"></span>
    <span class="dot dot1"></span>
    <span class="dot dot2"></span>
    <span class="dot dot3"></span>
    <span class="dot dot4"></span>
  </div>
</div>
```

The "Loading" text (visible behind the dots at very low opacity) is in
`assets/css/loaders.css:16` as `content: "Loading";` — also hardcoded.

### UX gaps

1. **Section is empty when toggle is off** — confusing. Customer can't see
   any loader options when they want to enable.
2. **Color setting in wrong section** — `site_loader_bg` lives in Site Skin,
   not Site Loader. Discoverability problem.
3. **No loader type choice** — every site gets the 5-dot animation,
   regardless of brand.
4. **No way to customize the "Loading" text** — hardcoded in CSS.
5. **No way to use the brand logo** as a loader.
6. **No animation speed control** — for a slow site the 1.5s loader-cycle
   feels arbitrary.
7. **No accessibility metadata** — the loader has no `aria-label` or
   `aria-live`, no `role="status"`. Screen reader users get nothing.

---

## Proposed premium configuration (5.2.0)

```
Section: site_loader (panel: site_layout_panel)
Fields:  7

  1. site_loader              switch     'Show site loader?'  default 1
  2. site_loader_type         radio_image (gated by site_loader)
       choices: dots / spinner / pulse / theme-logo / custom
       default: dots
       previews: 5 small SVG previews
  3. site_loader_bg           color      'Background color'   default #ef5455
       MOVED into this section from site_skin_section (with a one-time
       migration so customer's saved value carries over)
  4. site_loader_color        color      'Loader element color' default #ffffff
       NEW — currently hardcoded in CSS
  5. site_loader_text         text       'Loader text (optional)' default 'Loading'
       NEW — currently hardcoded
  6. site_loader_logo         image      'Custom logo'
       NEW — visible only when type = 'custom'
       active_callback: site_loader_type == 'custom'
  7. site_loader_speed        slider     'Animation speed (seconds)'
       NEW — 0.5 to 3.0, step 0.1, default 1.5
```

### Front-end render contract (`inc/extra.php`)

Refactor `buddyx_site_loader()` to:

```php
function buddyx_site_loader() {
    if ( ! get_theme_mod( 'site_loader', 1 ) ) return;
    $type  = get_theme_mod( 'site_loader_type', 'dots' );
    $text  = get_theme_mod( 'site_loader_text', 'Loading' );
    $logo  = get_theme_mod( 'site_loader_logo', '' );
    $speed = (float) get_theme_mod( 'site_loader_speed', 1.5 );
    ?>
    <div class="site-loader site-loader--<?php echo esc_attr( $type ); ?>"
         role="status"
         aria-live="polite"
         aria-label="<?php echo esc_attr( $text ); ?>"
         style="--loader-speed: <?php echo esc_attr( $speed ); ?>s;">
      <?php if ( 'theme-logo' === $type ) { the_custom_logo(); } ?>
      <?php if ( 'custom' === $type && $logo ) { ?>
        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $text ); ?>" />
      <?php } else { ?>
        <div class="loader-inner"><!-- type-specific markup --></div>
      <?php } ?>
      <span class="screen-reader-text"><?php echo esc_html( $text ); ?></span>
    </div>
    <?php
}
```

### CSS contract (`assets/css/loaders.css`)

Drop the hardcoded `content: "Loading"` from `.loader-inner:before`. The
text is now in the markup as `<span class="screen-reader-text">` and no
longer rendered visually behind the loader (low-opacity background text
was a design relic).

Each loader type gets its own keyframe block:

```css
.site-loader {
    background: var(--color-theme-loader, var(--site-loader-bg, #ef5455));
}
.site-loader [class*="loader-inner"] > * {
    color: var(--site-loader-color, #ffffff);
    animation-duration: var(--loader-speed, 1.5s);
}
.site-loader--dots .loader-inner { /* 5-dot animation */ }
.site-loader--spinner .loader-inner { /* circular spinner */ }
.site-loader--pulse .loader-inner { /* pulsing circle */ }
.site-loader--theme-logo, .site-loader--custom { /* fade-pulse the logo */ }
```

### Live preview JS

`site_loader_type` change → swap CSS class on `.site-loader` element.
`site_loader_color` / `site_loader_bg` change → update CSS variable.
`site_loader_speed` change → update `--loader-speed` variable.
`site_loader_text` change → update `<span class="screen-reader-text">` text.
`site_loader_logo` change → swap `<img src>` and `alt`.

### Migration (one-time, on upgrade to 5.2.0)

```php
// inc/Customizer_Settings/migrations/2_site_loader_color_move.php
function buddyx_migrate_loader_color_5_2() {
    if ( get_option( 'buddyx_5_2_loader_color_migrated' ) ) return;
    $color = get_theme_mod( 'site_loader_bg' );  // legacy location (Skin section)
    if ( $color !== false ) {
        // Already in theme_mods — just leave it; the new declaration uses
        // the same setting ID, so it'll work without re-saving.
    }
    update_option( 'buddyx_5_2_loader_color_migrated', 1 );
}
```

The setting ID stays `site_loader_bg` — no DB shape change. We just MOVE
the field declaration from Skin_Fields.php to General_Fields.php (or a
new Loader_Fields.php). Customers see their existing color in the new
location with no manual action.

### Accessibility additions

- `role="status"` on the loader wrapper — announced by screen readers as a
  status region
- `aria-live="polite"` — announce loading state changes without
  interrupting the user
- `aria-label` from the customizable text
- `<span class="screen-reader-text">` with the text — visible to AT but
  hidden visually
- `prefers-reduced-motion: reduce` media query — fall back to a static
  fade or the logo-only loader for users with motion sensitivity

---

## Implementation scope estimate

- 5 new field declarations in a new section + 1 moved → ~120 LOC
- Refactored `buddyx_site_loader()` → ~50 LOC (replacing 1 hardcoded line)
- 4 new CSS keyframe blocks + cleanup of hardcoded text → ~80 LOC
- Live preview JS additions → ~50 LOC
- One-time migration glue → ~10 LOC
- Plan-doc / changelog entry → text only

Total: ~310 LOC + tests. ~1 day of focused work.

---

## Why we're NOT doing this in 5.1.0

5.1.0 is "remove Kirki, change nothing else". Adding fields means:
- New theme_mod keys to maintain forever
- More test surface in the audit plan
- Risk of new active_callback chains breaking
- More work for the staging Pass 3 verification

5.1.0 ships the framework first. 5.2.0 ships the better Site Loader on
top of the proven framework — at that point we have headroom to design
properly without conflating it with the Kirki removal.

## Tracking

When 5.2.0 work begins, this plan is the spec. Update sub-checks below
as fields land:

- [ ] New section header `site_loader_section` with description text
- [ ] `site_loader_type` radio_image with 5 SVG previews
- [ ] Move `site_loader_bg` from Skin_Fields.php to Loader_Fields.php
- [ ] `site_loader_color` color field
- [ ] `site_loader_text` text field
- [ ] `site_loader_logo` image field gated by type=custom
- [ ] `site_loader_speed` slider field
- [ ] Refactor `buddyx_site_loader()` in inc/extra.php
- [ ] Add per-type CSS in loaders.css
- [ ] Live-preview JS handler
- [ ] One-time upgrade migration entry
- [ ] Accessibility: role/aria-live/screen-reader-text + reduced-motion
- [ ] Update `plans/2026-05-04-customizer-options-audit.md` inventory
- [ ] Update `docs/customizer-inventory-snapshot.txt` via the dump script
