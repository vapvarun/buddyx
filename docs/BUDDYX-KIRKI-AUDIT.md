# BuddyX Kirki Customizer Audit

**Date:** December 19, 2025
**Scope:** Kirki implementation analysis for BuddyX FREE and PRO themes

---

## Executive Summary

| Metric | FREE | PRO | Difference |
|--------|------|-----|------------|
| **Total Fields** | 134 | 333 | +199 (+149%) |
| **Total Sections** | 17 | 35 | +18 (+106%) |
| **Total Panels** | 5 | 5 | Same |
| **Color Fields** | 59 | 108 | +49 (+83%) |
| **File Size (lines)** | 2,467 | 7,920 | +5,453 |
| **Field Types Used** | 13 | 20 | +7 |

---

## Kirki Dependency Status

### Both Themes: Plugin Dependency (Not Bundled)

```
Loading Method: TGM Plugin Activation (TGMPA)
Required: false (soft dependency)
Fallback: Custom installer prompt in Customizer
```

**Files Involved:**
```
/external/include-kirki.php     - Installer fallback if plugin not active
/external/kirki-utils.php       - Default values helper
/inc/Kirki/Component.php        - Kirki configuration wrapper
/inc/Kirki_Option/Component.php - All panels, sections, and fields
```

**Configuration:**
```php
Kirki::add_config('buddyx_kirki', [
    'capability'  => 'edit_theme_options',
    'option_type' => 'theme_mod',
]);
```

---

## Panel Structure (Both Themes)

| Panel | ID | Priority | Description |
|-------|-----|----------|-------------|
| General | `site_layout_panel` | 30 | Layout, loader, page mapping |
| Typography | `typography_panel` | 30 | Fonts for all elements |
| WP Login | `site_wp_login` | 31 | Admin login customization |
| Community Settings | `site_buddypress_panel` | 31 | BuddyPress/bbPress options |
| Site Footer | `site_footer_panel` | 31 | Footer and copyright |

---

## Field Types Comparison

### BuddyX FREE (134 fields)

| Field Type | Count | % | Usage |
|------------|-------|---|-------|
| Color | 59 | 44% | Site colors, buttons, footer |
| Checkbox_Switch | 21 | 16% | Feature toggles |
| Typography | 12 | 9% | Font settings |
| Radio_Image | 13 | 10% | Layout selection |
| Dimension | 8 | 6% | Width, radius, spacing |
| Custom | 9 | 7% | Dividers, HTML |
| Radio | 4 | 3% | Simple choices |
| Dropdown_Pages | 3 | 2% | Page selection |
| Text | 2 | 1% | Text inputs |
| Background | 2 | 1% | Background properties |
| Image | 1 | 1% | Image upload |
| URL | 1 | 1% | URL input |
| Textarea | 1 | 1% | Multiline text |
| Select | 1 | 1% | Dropdown |

### BuddyX PRO (333 fields)

| Field Type | Count | % | Usage |
|------------|-------|---|-------|
| Color | 108 | 32% | Extended theming |
| Checkbox_Switch | 46 | 14% | Feature toggles |
| Dimension | 27 | 8% | Spacing controls |
| Checkbox | 27 | 8% | Boolean options |
| Custom | 20 | 6% | Dividers, HTML |
| Radio_Image | 18 | 5% | Visual layouts |
| Select | 16 | 5% | Dropdowns |
| Typography | 12 | 4% | Font settings |
| Radio | 12 | 4% | Simple choices |
| Image | 9 | 3% | Image uploads |
| Text | 8 | 2% | Text inputs |
| Textarea | 7 | 2% | Multiline text |
| Slider | 6 | 2% | Range values |
| Radio_Buttonset | 5 | 2% | Button groups |
| URL | 3 | 1% | URL inputs |
| Dropdown_Pages | 3 | 1% | Page selection |
| Repeater | 2 | 1% | Repeatable fields |
| Background | 2 | 1% | Background props |
| Upload | 1 | <1% | File upload |
| Sortable | 1 | <1% | Drag/drop order |

---

## Sections Breakdown

### BuddyX FREE (17 sections)

| Section | Panel | Fields |
|---------|-------|--------|
| site_layout | General | Layout settings |
| site_loader | General | Loader options |
| page_mapping | General | Custom pages |
| site_title_typography_section | Typography | Site title fonts |
| headings_typography_section | Typography | H1-H6 fonts |
| menu_typography_section | Typography | Menu fonts |
| body_typography_section | Typography | Body fonts |
| site_header_section | Standalone | Header options |
| site_sub_header_section | Standalone | Sub-header |
| site_skin_section | Standalone | Colors/skin |
| site_blog_section | Standalone | Blog settings |
| site_sidebar_layout | Standalone | Sidebar layouts |
| site_wp_login_logo | WP Login | Login logo |
| site_buddypress_general_section | Community | BuddyPress general |
| site_footer_section | Site Footer | Footer settings |
| site_copyright_section | Site Footer | Copyright |
| site_performance_section | Standalone | Performance |

### BuddyX PRO Additional Sections (+18)

| Section | Panel | Description |
|---------|-------|-------------|
| site_scroll_top | General | Scroll to top button |
| site_wp_login_themes | WP Login | Login theme selection |
| site_wp_login_form | WP Login | Login form styling |
| site_wp_login_button | WP Login | Login button styling |
| site_wp_login_forget | WP Login | Forgot password form |
| site_community_register | Community | Registration page |
| buddyx_signin_popup_options | Community | Sign-in popup |
| buddyx_register_popup_options | Community | Register popup |
| site_buddypress_members_section | Community | Members directory |
| site_buddypress_groups_section | Community | Groups directory |
| site_buddypress_activity_section | Community | Activity settings |
| site_buddypress_profile_section | Community | Profile settings |
| site_woocommerce_section | Standalone | WooCommerce options |
| site_header_layout_section | Standalone | Header layouts |
| site_menu_effects_section | Standalone | Menu animations |
| site_side_panel_section | Standalone | Side panel |
| site_dark_mode_section | Standalone | Dark mode |
| site_social_links_section | Standalone | Social links |

---

## PRO-Exclusive Options by Category

### Header & Navigation (+30 options)
- 3 header layout variations
- 3 menu effect styles
- Side panel customization
- Sticky header controls
- Menu hover animations

### BuddyPress/Community (+60 options)
- Member directory layouts
- Member card customization (online status, followers, joined date)
- Group directory layouts
- Group card options (cover image, privacy, organizers)
- Profile header styles
- Activity stream controls
- Sign-in/Register popup configuration

### WooCommerce (+15 options)
- Off-canvas filter toggle
- Products per page
- Shop result count
- Sort options display
- Product pagination style

### WP Login Page (+50 options)
- Login theme selection (8 themes)
- Form field styling
- Button customization
- Background overlays
- Redirect options
- Description customization
- Forgot password form styling

### Visual/Colors (+49 colors)
- Extended body text colors
- Dark mode color scheme
- Additional component colors
- Gradient options
- Overlay colors

---

## Conditional Loading

Both themes intelligently load sections based on active plugins:

```php
// BuddyPress sections
if ( class_exists( 'BuddyPress' ) ) {
    // Load community sections
}

// bbPress sections
if ( class_exists( 'bbPress' ) ) {
    // Load forum sections
}

// LearnDash sections
if ( class_exists( 'SFWD_LMS' ) ) {
    // Load LMS sections
}

// WooCommerce sections
if ( class_exists( 'WooCommerce' ) ) {
    // Load shop sections
}

// Hide if Youzify active (conflict avoidance)
if ( ! class_exists( 'Youzify' ) ) {
    // Load community settings
}
```

---

## Technical Features

### Active Callbacks (Conditional Fields)
- **FREE:** 40+ fields with conditional display
- **PRO:** 80+ fields with conditional display

**Example:**
```php
'active_callback' => [
    [
        'setting'  => 'site_custom_colors',
        'operator' => '==',
        'value'    => '1',
    ],
],
```

### CSS Output
- **FREE:** 25+ fields with direct CSS output
- **PRO:** 60+ fields with direct CSS output

### Transport Methods
- `auto` - Live updates (majority)
- `postMessage` - Partial refresh
- `refresh` - Full page refresh

### Body Class Filters
- **FREE:** 5+ body class methods
- **PRO:** 11+ body class methods for dynamic styling

---

## Default Values

Both themes define defaults in `external/kirki-utils.php`:

```php
function buddyx_defaults( $key = '' ) {
    $defaults = array(
        'site_layout'         => 'wide',
        'site_loader'         => 'suspended_circles',
        'site_sidebar_option' => 'right',
        // ... 120+ defaults
    );

    return $key ? $defaults[$key] : $defaults;
}
```

---

## Identified Gaps & Opportunities

### Missing Field Types (Neither Theme Uses)
- `multicolor` - Could use for gradient options
- `code` - Could use for custom CSS
- `link` - Could use for social links
- `multicheck` - Could use for feature selection
- `palette` - Could use for color scheme selection
- `dashicons` - Could use for icon selection

### Improvement Opportunities

1. **Split Monolithic File**
   - Current: Single 7,920 line file (PRO)
   - Recommended: Split by panel/section

2. **Add Preset System**
   - Color scheme presets
   - Layout presets
   - One-click style changes

3. **Typography Presets**
   - Font pairing presets
   - One-click typography schemes

4. **Missing Controls**
   - Border style selector
   - Shadow controls
   - Animation speed controls

5. **Performance**
   - Lazy load sections
   - Cache compiled CSS

---

## Recommendations for v4.9.3

### FREE Theme
1. Keep current field count (134) - WordPress.org compliant
2. Add 3-5 block pattern category toggles
3. Add spacing preset selector
4. Improve default values for better OOTB experience

### PRO Theme
1. Add color scheme presets (Kirki `palette` type)
2. Add typography presets
3. Split Kirki_Option/Component.php into multiple files:
   - `Header_Options.php`
   - `Footer_Options.php`
   - `BuddyPress_Options.php`
   - `WooCommerce_Options.php`
   - `Colors_Options.php`
4. Add dark mode color controls
5. Add style variation controls

---

## File Organization Recommendation

### Current Structure
```
inc/Kirki_Option/
└── Component.php (7,920 lines)
```

### Recommended Structure
```
inc/Kirki_Option/
├── Component.php (main loader)
├── Panels/
│   ├── General.php
│   ├── Typography.php
│   ├── WP_Login.php
│   ├── Community.php
│   └── Footer.php
├── Sections/
│   ├── Header.php
│   ├── Colors.php
│   ├── Blog.php
│   ├── BuddyPress.php
│   └── WooCommerce.php
└── Presets/
    ├── Colors.php
    └── Typography.php
```

---

## Summary

| Aspect | FREE | PRO | Notes |
|--------|------|-----|-------|
| Kirki Dependency | Soft | Soft | Plugin required for customizer |
| Total Options | 134 | 333 | PRO is 2.5x more customizable |
| Color Options | 59 | 108 | Heavy focus on theming |
| Plugin Integration | 3 | 5 | BuddyPress, bbPress, WooCommerce, LearnDash |
| Custom Controls | 0 | 0 | Uses standard Kirki only |
| File Organization | Monolithic | Monolithic | Opportunity to split |
| Default Values | Yes | Yes | Well-defined defaults |
| Conditional Fields | 40+ | 80+ | Good UX |

---

*This audit serves as reference for Kirki customizer improvements in BuddyX v4.9.3*
