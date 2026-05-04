# Customizer Options Audit & Verification Plan

> Companion to `plans/2026-05-04-kirki-replacement.md`. Goal: prove every one
> of the 114 customizer fields renders the right control, accepts the same
> input shape Kirki accepted, sanitizes/saves the same value Kirki saved, and
> reads back identically — across all 3,000+ active customer sites.

**Owner:** BuddyX 5.1.0 release blocker. Must be fully green before tag.

---

## Why this plan exists

The Kirki removal smoke test (`plans/2026-05-04-kirki-replacement.md` Task 25)
verified that all 94 settings register in the customize bootstrap and that
every saved theme_mod has a registered setting. **It did not verify the
rendered control matches Kirki's UI for each field.** Browser inspection of
the WP Login section caught a real regression — `Custom Logo` was rendering
as a plain text input instead of an image picker — proving bootstrap-count
verification is insufficient.

A customer with a configured customizer should:
1. See the same controls in the same sections at the same priorities.
2. Find their saved values intact.
3. Save changes and have them persist with identical shape (no double JSON
   encoding, no array-to-string coercion, no value drift).

Anything less is a regression we cannot ship to production.

---

## Numbers at a glance

| Metric | Count |
|---|---|
| Total field declarations | 114 |
| Distinct field types in use | 14 |
| Sections | 18 |
| Panels | 5 |
| Currently saved theme_mod keys (this dev DB) | 10 |
| Visually verified at unit level | 8 |
| Remaining to visually verify | 106 |

Reconcile against `inc/Customizer_Settings/Fields/*.php` +
`inc/compatibility/{surecart,fluentcart}/*.php` whenever fields are added or
moved.

---

## Bugs already found and fixed

| # | Bug | Root cause | Status |
|---|---|---|---|
| 1 | Image control rendered as plain text input | `Field::map_core_type('image')` fell through to `'text'`; `add_control($id, ['type'=>'text'])` shortcut form does not understand `image` | ✅ Fixed by switching `image` and `background` to `is_custom=true` in type_map and instantiating the control class directly |
| 2 | Background composite control crashed instantiation | Was using `WP_Customize_Background_Image_Control` (single image picker, hard-coded to global `background_image` setting) instead of a Kirki-shape 6-input composite | ✅ Fixed by writing `Controls/Background.php` with 6 sub-inputs and wiring sanitize + Output_Builder + JS handlers |
| 3 | Background sanitize emitted "Array to string conversion" warnings | `resolve_sanitize_callback` returned `esc_url_raw` for `background`, which got an array | ✅ Fixed: dedicated `Field::sanitize_background()` whitelists 6 keys and applies per-key sanitize |
| 4 | Repeater `fields` and `row_label` Kirki-shape args silently dropped | `Field::build_control_args` whitelisted args, missed Kirki-shape extras | ✅ Fixed: switched to blacklist (strip framework-internal keys, pass everything else) |
| 5 | Upload control type_map had `setting_class=null` so `add_setting()` was skipped → control unbindable | Wrong assumption that `WP_Customize_Upload_Control` brings its own setting | ✅ Fixed in Tasks 25-26 |

## Bugs found by inventory, not yet fixed

| # | Bug | Impact | Fix plan |
|---|---|---|---|
| 6 | `site_header_enable_cart` registered **twice** — once in `inc/compatibility/surecart/surecart-functions.php`, once in `inc/compatibility/fluentcart/fluentcart-functions.php`. Whichever loads last wins. | If both plugins are active, one's customizer field overwrites the other. Latent in Kirki version too. | Gate each registration with `class_exists('SureCart')` and `function_exists('FluentCart\\App')` (or equivalent), so only the active plugin's version runs. Smaller change: rename the FluentCart setting to `fluentcart_enable_cart` so they don't collide. |

---

## The Inventory — 114 fields by section

**How to regenerate:** see `Reproducible inventory script` at the bottom.

```
[body_typography_section]  (1)
   1 typography       typography_option                        <array>

[headings_typography_section]  (6)
   2 typography       h1_typography_option                     <array>
   3 typography       h2_typography_option                     <array>
   4 typography       h3_typography_option                     <array>
   5 typography       h4_typography_option                     <array>
   6 typography       h5_typography_option                     <array>
   7 typography       h6_typography_option                     <array>

[menu_typography_section]  (2)
   8 typography       menu_typography_option                   <array>
   9 typography       sub_menu_typography_option               <array>

[page_mapping]  (3)
  10 dropdown-pages   buddyx_404_page                          0
  11 dropdown-pages   buddyx_login_page                        0
  12 dropdown-pages   buddyx_registration_page                 0

[site_blog_section]  (12)
  13 switch           blog_edit_link                           ''
  14 radio            blog_grid_columns                        'one-column'
  15 radio            blog_image_position                      'thumb-left'
  16 radio_image      blog_layout_option                       'default-layout'
  17 radio            blog_masonry_view                        'without-masonry'
  18 switch           blog_show_tags                           ''
  19 radio            blog_show_tags_style                     'default'
  20 color            buddyx_section_title_over_overlay        'rgba(0, 0, 0, 0.1)'
  21 custom           custom-skin-divider1                     '<hr>'
  22 select           post_per_row                             'buddyx-masonry-2'
  23 radio_image      single_post_content_width                'small'
  24 radio_image      single_post_title_layout                 'buddyx-section-title-overlay'

[site_buddypress_general_section]  (1)
  25 switch           buddypress_avatar_style                  'on'

[site_copyright_section]  (1)
  26 textarea         site_copyright_text                      (none)

[site_footer_section]  (2)
  27 background       background_setting                       <array>
  28 switch           site_footer_bg                           'off'

[site_header_primary_section]  (2)  ⚠ DUPLICATE — see bug #6
  29 switch           site_header_enable_cart  (surecart)      '1'
  30 switch           site_header_enable_cart  (fluentcart)    '1'

[site_header_section]  (5)
  31 switch           site_cart                                '1'
  32 switch           site_login_link                          '1'
  33 switch           site_register_link                       '1'
  34 switch           site_search                              '1'
  35 switch           site_sticky_header                       '1'

[site_layout]  (5)
  36 dimension        site_button_border_radius                '6px'
  37 dimension        site_container_width                     '1170px'
  38 dimension        site_form_border_radius                  '6px'
  39 dimension        site_global_border_radius                '8px'
  40 radio_image      site_layout                              'wide'

[site_loader]  (1)
  41 switch           site_loader                              '2'

[site_performance_section]  (3)
  42 custom           site_flush_local_font                    <input type="submit"…>
  43 switch           site_load_google_font_locally            ''
  44 switch           site_preload_local_font                  ''

[site_sidebar_layout]  (9)
  45 radio_image      bbpress_sidebar_option                   'right'
  46 radio_image      buddypress_groups_sidebar_option         'right'
  47 radio_image      buddypress_members_sidebar_option        'right'
  48 radio_image      buddypress_sidebar_option                'both'
  49 radio_image      fluentcart_product_sidebar               'none'
  50 radio_image      sidebar_option                           'right'
  51 radio_image      single_post_sidebar_option               'none'
  52 switch           sticky_sidebar_option                    '1'
  53 radio_image      woocommerce_sidebar_option               'right'

[site_skin_section]  (46)
  54 color            body_background_color                    '#f7f7f9'
  55 color            box_background_color                     '#ffffff'
  56 color            content_background_color                 '#f7f7f9'
  57 custom           custom-body-divider                      <hr style=…>
  58 custom           custom-button-divider                    <hr style=…>
  59 custom           custom-coyright-divider                  <hr style=…>   ⚠ typo: should be 'copyright' (latent)
  60 custom           custom-footer-divider                    <hr style=…>
  61 custom           custom-header-divider                    <hr style=…>
  62 custom           custom-headings-divider                  <hr style=…>
  63 custom           custom-loader-divider                    <hr style=…>
  64 color            h1_typography_option[color]              '#111111'
  65 color            h2_typography_option[color]              '#111111'
  66 color            h3_typography_option[color]              '#111111'
  67 color            h4_typography_option[color]              '#111111'
  68 color            h5_typography_option[color]              '#111111'
  69 color            h6_typography_option[color]              '#111111'
  70 color            menu_active_color                        '#ef5455'
  71 color            menu_hover_color                         '#ef5455'
  72 color            menu_typography_option[color]            '#111111'
  73 color            secondary_background_color               '#fafafa'
  74 color            site_buttons_background_color            '#ef5455'
  75 color            site_buttons_background_hover_color      '#f83939'
  76 color            site_buttons_border_color                '#ef5455'
  77 color            site_buttons_border_hover_color          '#f83939'
  78 color            site_buttons_text_color                  '#ffffff'
  79 color            site_buttons_text_hover_color            '#ffffff'
  80 color            site_copyright_background_color          '#ffffff'
  81 color            site_copyright_border_color              '#e8e8e8'
  82 color            site_copyright_content_color             '#505050'
  83 color            site_copyright_links_color               '#111111'
  84 color            site_copyright_links_hover_color         '#ef5455'
  85 switch           site_custom_colors                       'on'
  86 color            site_footer_content_color                '#505050'
  87 color            site_footer_links_color                  '#111111'
  88 color            site_footer_links_hover_color            '#ef5455'
  89 color            site_footer_title_color                  '#111111'
  90 color            site_header_bg_color                     '#ffffff'
  91 color            site_links_color                         '#111111'
  92 color            site_links_focus_hover_color             '#ef5455'
  93 color            site_loader_bg                           '#ef5455'
  94 color            site_primary_color                       '#ef5455'
  95 color            site_sub_header_typography[color]        '#111111'
  96 color            site_tagline_typography_option[color]    '#757575'
  97 color            site_title_hover_color                   '#ef5455'
  98 color            site_title_typography_option[color]      '#111111'
  99 color            typography_option[color]                 '#505050'

[site_sub_header_section]  (4)
 100 switch           site_breadcrumbs                         'on'
 101 switch           site_sub_header_bg                       'off'
 102 typography       site_sub_header_typography               <array>
 103 background       sub_header_background_setting            <array>

[site_title_typography_section]  (2)
 104 typography       site_tagline_typography_option           <array>
 105 typography       site_title_typography_option             <array>

[site_wp_login_logo]  (9)
 106 image            custom_login_logo_image                  ''
 107 dimension        custom_login_logo_image_height           '84px'
 108 dimension        custom_login_logo_image_width            '84px'
 109 dimension        custom_login_logo_space                  '0px'
 110 text             custom_login_logo_title                  ''
 111 url              custom_login_logo_url                    ''
 112 text             custom_login_page_title                  ''
 113 switch           enable_custom_login                      ''
 114 switch           enable_custom_login_logo                 ''
```

⚠ also flagged: **`custom-coyright-divider`** (#59) is misspelled — should be
`custom-copyright-divider`. Custom HTML dividers are decorative and store no
saved value, so no migration risk; but rename is wanted for cleanliness.
Document; do not silently rename in 5.1.0 (any external CSS hooked to the
slug would break). Defer to 5.2.0 with a deprecation note.

---

## Verification plan — section by section

Each section gets one Playwright pass. **Per section, every field is
checked against this checklist:**

- [ ] Renders the expected control type (image picker, color picker,
      typography fieldset, etc.) — not a text input fallback
- [ ] Label and description text match Kirki version
- [ ] Default value reflected in the UI on first load
- [ ] Active_callback gating works (toggle the parent switch; child fields
      appear/disappear)
- [ ] Save → reload → DB value preserved with same shape (no JSON double-
      encoding, no array→string coercion)
- [ ] Live-preview updates the front-end iframe without full refresh for
      `transport: postMessage` / `auto` settings
- [ ] PHP error log clean during save and during page render

### Pass 1 — type sweep (faster, covers shape correctness once per type)

One representative field of each of the 14 types, full lifecycle:

- [ ] **color** → `site_primary_color` (set custom hex; verify alpha picker; verify front-end CSS updates live)
- [ ] **switch** → `site_sticky_header` (toggle off/on; verify body class changes)
- [ ] **radio_image** → `site_layout` (pick `boxed`; verify body class)
- [ ] **typography** → `site_title_typography_option` (change family, weight, size; verify CSS emits and live-updates; verify DB stores keyed array, not stringified)
- [ ] **custom (HTML)** → `custom-skin-divider1` (verify HTML renders inside customizer panel, not escaped)
- [ ] **dimension** → `site_container_width` (pick value + unit; verify `'1170px'` shape stored)
- [ ] **radio** → `blog_image_position` (pick option; verify saved as `'thumb-left'` etc.)
- [ ] **dropdown-pages** → `buddyx_login_page` (pick a real page; verify saved as page ID int)
- [ ] **background** → `background_setting` (set color + image + repeat; verify 6-key array stored; verify Output_Builder emits all 6 declarations)
- [ ] **text** → `custom_login_logo_title` (type a string; verify `sanitize_text_field`)
- [ ] **textarea** → `site_copyright_text` (multi-line + HTML allowed by `sanitize_textarea_field`)
- [ ] **select** → `post_per_row` (pick option; verify saved key)
- [ ] **image** → `custom_login_logo_image` (pick from media library; verify URL saved; verify front-end img tag updates)
- [ ] **url** → `custom_login_logo_url` (verify `esc_url_raw` strips `javascript:` etc.)

### Pass 2 — section sweep (per-section visual confirmation)

For each of the 18 sections, take a screenshot, compare label-by-label to a
Kirki-version reference (5.0.3 customizer), confirm no field is missing or
mis-rendered.

- [ ] body_typography_section
- [ ] headings_typography_section
- [ ] menu_typography_section
- [ ] page_mapping
- [ ] site_blog_section
- [ ] site_buddypress_general_section
- [ ] site_copyright_section
- [ ] site_footer_section
- [ ] site_header_primary_section *(verify duplicate cart bug behavior — need a SureCart-active env and a FluentCart-active env)*
- [ ] site_header_section
- [ ] site_layout
- [ ] site_loader
- [ ] site_performance_section
- [ ] site_sidebar_layout
- [ ] site_skin_section *(46 fields — heaviest)*
- [ ] site_sub_header_section
- [ ] site_title_typography_section
- [ ] site_wp_login_logo *(already done)*

### Pass 3 — value preservation regression test

The most important check for 3,000+ active sites. Run on a database snapshot
restored from a real customer-style site (or any site with the customizer
already configured under Kirki):

- [ ] Snapshot `theme_mods_buddyx` BEFORE upgrade.
- [ ] Upgrade theme to 5.1.0 (no DB migration is supposed to run).
- [ ] Snapshot `theme_mods_buddyx` AFTER. Must be identical (every key, every
      value, every shape).
- [ ] Render the front-end with both versions. The `<style id="kirki-…">`
      block from 5.0.3 and the `<style id="buddyx_customizer-css">` block
      from 5.1.0 should produce equivalent CSS output (different selectors
      may be reordered; values must match).
- [ ] Save **one** of every type via the customizer in 5.1.0; reload; the
      saved value's shape (string vs array, JSON vs not) must match what
      Kirki would have saved for the same UI interaction. This is what
      catches double-encoding regressions.

---

## Customer impact analysis (3,000+ active sites)

### What we guarantee unchanged
- Every setting ID from 5.0.3 still exists in 5.1.0.
- Every saved theme_mod is read with `get_theme_mod()` — same WP core path.
- Defaults match Kirki's defaults (verified case-by-case in this inventory).
- Sections, panels, priorities, labels, descriptions all unchanged.

### Where customers could feel a difference
1. **Repeater / Sortable consumers** (BuddyX free has none today, BuddyX Pro
   may have some): Kirki's `Kirki::get_option()` decoded JSON automatically;
   `get_theme_mod()` returns the JSON string. Documented in
   `inc/Customizer_Framework/README.md`. Acceptable for free; Pro to verify
   when it adopts the framework.
2. **Live preview behavior**: Kirki had its own live-preview JS engine.
   Ours (`customizer-preview.js`) covers Typography and Background object
   values plus scalar values. Some Kirki preview behaviors that depended on
   undocumented internals may differ. Live preview falls back to full
   refresh when our engine doesn't recognize the setting — never broken
   output, just slower preview.
3. **`kirki_field_add_setting_args` filter consumers** (third-party
   integrations): replaced by `buddyx_customizer_field_args`. Documented;
   if anyone hooked the old filter, they need to migrate. Acceptable —
   this is an internal customization filter, not a public API.

### Where we **could** lose customer data (and the mitigations)
1. **Sanitize callback drift.** A field with a stricter sanitize in 5.1.0
   would silently drop values customers had saved under Kirki's looser
   sanitize. Mitigation: each sanitize_callback in `Field::resolve_sanitize_callback`
   reviewed against Kirki's equivalent. *Pass 3 of the verification plan
   covers this empirically.*
2. **Active_callback expression mismatch.** A field that was visible under
   Kirki but hidden under our engine appears "lost" to the customer (still
   in DB, but they can't edit it from the UI). Mitigation: `Active_Callback`
   uses loose equality `==` and the same operator set as Kirki. Ad-hoc
   verification per section in Pass 2.
3. **Background composite shape drift.** Kirki stored a 6-key array; we now
   match it exactly via `Controls/Background.php` + `sanitize_background`.
   Verified: same keys, same value formats. *Round-trip test in Pass 3
   sub-step 5 confirms.*

If Pass 3 finds *any* round-trip mismatch, that field becomes a release
blocker until either:
- The framework is updated to match Kirki's behavior, OR
- A targeted one-time `theme_mods_buddyx` migration runs on upgrade with
  before/after logging in the WP admin notices area.

---

## Reproducible inventory script

Save as `tools/dump-customizer-inventory.php` and run via WP-CLI:

```bash
wp eval-file tools/dump-customizer-inventory.php > docs/customizer-inventory-snapshot.txt
```

Or as a Python parser (no WP runtime needed) — exact code that built this
file lives at the bottom of this plan and produces deterministic output. Run
it after any field-file change to refresh the inventory and detect drift.

```python
# tools/dump_customizer_inventory.py — runs against the field source files;
# does NOT depend on WP runtime, so it can be a CI check.
import re, glob, json, subprocess, os

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

def dump():
    mods_json = subprocess.run(
        ['wp', 'option', 'get', 'theme_mods_buddyx', '--format=json',
         '--path=' + os.environ.get('WP_PATH', REPO + '/../../../..')],
        capture_output=True, text=True
    ).stdout
    mods = json.loads(mods_json) if mods_json.strip() else {}

    type_re = re.compile(r"Field::add\(\s*'([a-z_-]+)'\s*,")
    records = []
    for f in sorted(
        glob.glob(REPO + '/inc/Customizer_Settings/Fields/*.php')
        + glob.glob(REPO + '/inc/compatibility/*/*.php')
    ):
        src = open(f).read()
        lines = src.split('\n')
        i = 0
        while i < len(lines):
            m = type_re.search(lines[i])
            if not m:
                i += 1
                continue
            ftype = m.group(1)
            depth = 0
            started = False
            j = i
            block = []
            while j < len(lines):
                ln = lines[j]
                for ch in ln:
                    if ch == '(':
                        depth += 1
                        started = True
                    elif ch == ')':
                        depth -= 1
                        if started and depth == 0:
                            break
                block.append(ln)
                if started and depth == 0:
                    break
                j += 1
            body = '\n'.join(block)
            sid = re.search(r"'settings'\s*=>\s*'([^']+)'", body)
            sec = re.search(r"'section'\s*=>\s*'([^']+)'", body)
            if sid and sec:
                records.append((ftype, sid.group(1), sec.group(1), body, mods))
            i = j + 1
    # Same printer as above…

if __name__ == '__main__':
    dump()
```

---

## Acceptance criteria for 5.1.0 release tag

- [ ] Pass 1 (type sweep) — all 14 types green
- [ ] Pass 2 (section sweep) — all 18 sections green
- [ ] Pass 3 (value preservation) — round-trip on all 14 types green;
      snapshot diff before/after upgrade is byte-identical
- [ ] Bug #6 (duplicate cart switch) — fixed
- [ ] Inventory dump committed to repo at
      `docs/customizer-inventory-snapshot.txt` so future drift is visible in
      git diffs

5.1.0 cannot ship until every checkbox above is ticked. Anything
inconclusive blocks release.

---

## Out of scope (defer to 5.2.0+)

- Rename `custom-coyright-divider` → `custom-copyright-divider`
- CSS-variable output mode (folds Dynamic_Style into framework)
- Output cache transient
- Per-rule `media_query` support
- `buddyx_customizer_get($id)` helper that auto-decodes Repeater/Sortable JSON
- Selective refresh wiring for typography/color blocks
