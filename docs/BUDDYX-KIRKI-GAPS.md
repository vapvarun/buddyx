# BuddyX Kirki Usage Gap Analysis

**Kirki Version Installed:** 5.1.1
**Date:** December 19, 2025

---

## Quick Summary

| Category | Available | Used by BuddyX | Gap |
|----------|-----------|----------------|-----|
| Field Types | 32 | 13 | **19 unused** |
| PRO Features | 7 | 0 | **7 unused** |
| Modules | 11 | 3 | **8 unused** |
| Output Features | 5 | 1 (partial) | **4 unused** |

---

## Field Types Comparison

### Currently Used by BuddyX (13 types)

| Field Type | FREE | PRO | Notes |
|------------|------|-----|-------|
| Color | 59 | 108 | Used heavily |
| Checkbox_Switch | 21 | 46 | Used for toggles |
| Typography | 12 | 12 | Used for fonts |
| Radio_Image | 13 | 18 | Used for layouts |
| Dimension | 8 | 27 | Used for sizing |
| Custom | 9 | 20 | Used for dividers |
| Radio | 4 | 12 | Basic choices |
| Dropdown_Pages | 3 | 3 | Page selection |
| Text | 2 | 8 | Text inputs |
| Background | 2 | 2 | Background settings |
| Image | 1 | 9 | Image upload |
| URL | 1 | 3 | URL inputs |
| Textarea | 1 | 7 | Multiline text |
| Select | 1 | 16 | Dropdowns |

### NOT Used by BuddyX (19 types)

| Field Type | What It Does | Where BuddyX Could Use It |
|------------|--------------|---------------------------|
| **Code** | Syntax-highlighted code editor | Custom CSS field |
| **Number** | Numeric input with constraints | Font sizes, line heights |
| **Multicheck** | Multiple selections | Enable/disable features |
| **Checkbox_Toggle** | Toggle button variant | Feature switches |
| **Sortable** | Drag-drop ordering | Element order, menu order |
| **Dashicons** | WordPress icon picker | Icon selections |
| **Date** | Date picker | Maintenance mode date |
| **Editor** | TinyMCE editor | Rich text content |
| **Color_Palette** | Predefined palettes | Quick color schemes |
| **Multicolor** | Multiple colors at once | Gradient colors |
| **Dimensions** | Multi-dimensional input | Padding/margin all sides |
| **Radio_Buttonset** | Button-style radios | Layout options |
| **FontAwesome** | FA icon picker | Social icons |
| **Generic** | Base control | Custom implementations |
| **ReactSelect** | Enhanced dropdown | Better selects |
| **Repeater** | 0 in FREE, 2 in PRO | Social links, team members |
| **Slider** | 0 in FREE, 6 in PRO | Range values |
| **Upload** | General file upload | File attachments |

---

## PRO Features NOT Used (7 total)

| PRO Feature | What It Does | Perfect For |
|-------------|--------------|-------------|
| **Margin** | 4-sided margin control | Element spacing |
| **Padding** | 4-sided padding control | Container padding |
| **Responsive** | Device-specific values | Mobile/tablet settings |
| **Tabs** | Organize controls in tabs | Group related options |
| **Input_Slider** | Slider + input combo | Font size, opacity |
| **Headline** | Section headers | Organize customizer |
| **Divider** | Visual separators | Group controls |

---

## Module Features NOT Used

### 1. Module-CSS (Automatic CSS) - PARTIALLY USED

**Available:**
```php
'output' => [
    [
        'element'  => '.site-title',
        'property' => 'color',
    ],
],
```

**BuddyX Status:** Only 4 fields use `output`, rest generate CSS manually in Dynamic_Style.

**Gap:** 130+ fields could use automatic CSS output.

---

### 2. Module-Postmessage (Live Preview) - UNDERUSED

**Available:**
```php
'transport' => 'auto', // Kirki auto-generates postMessage scripts
'js_vars' => [
    [
        'element'  => '.site-title',
        'function' => 'css',
        'property' => 'color',
    ],
],
```

**BuddyX Status:** Uses `transport` but not `js_vars`.

**Gap:** No instant preview for most color changes.

---

### 3. Module-Selective-Refresh - NOT USED

**Available:**
```php
'partial_refresh' => [
    'copyright_text' => [
        'selector'        => '.site-copyright',
        'render_callback' => 'buddyx_copyright_text',
    ],
],
```

**BuddyX Status:** Not implemented.

**Gap:** Footer text, header elements could update without full refresh.

---

### 4. Module-Preset - NOT USED

**Available:**
```php
'preset' => [
    'default' => [
        'label'    => 'Default Theme',
        'settings' => [
            'site_primary_color' => '#ef5455',
            'body_background'    => '#ffffff',
        ],
    ],
    'dark' => [
        'label'    => 'Dark Mode',
        'settings' => [
            'site_primary_color' => '#ff6b6b',
            'body_background'    => '#1a1a1a',
        ],
    ],
],
```

**BuddyX Status:** Not implemented.

**Gap:** No one-click color schemes or style presets.

---

### 5. Module-Field-Dependencies - PARTIALLY USED

**Available:**
```php
'required' => [
    [
        'setting'  => 'site_custom_colors',
        'operator' => '==',
        'value'    => true,
    ],
    [
        'setting'  => 'dark_mode',
        'operator' => '!=',
        'value'    => true,
    ],
],
```

**BuddyX Status:** Uses `active_callback` but not `required` (older method).

**Gap:** Could use more complex multi-condition logic.

---

### 6. CSS Variables (css_vars) - NOT USED

**Available:**
```php
'css_vars' => [
    [
        'name'     => '--theme-primary-color',
        'element'  => ':root',
    ],
],
```

**BuddyX Status:** Manually generates CSS variables in Dynamic_Style.

**Gap:** Kirki can auto-generate all CSS variables.

---

### 7. Module-Tooltips - NOT USED

**Available:**
```php
'tooltip' => esc_html__('This controls the primary theme color', 'buddyx'),
```

**BuddyX Status:** Not implemented.

**Gap:** No contextual help for users.

---

### 8. Module-Section-Icons - NOT USED

**Available:**
```php
Kirki::add_section('colors', [
    'title' => 'Colors',
    'icon'  => 'dashicons-admin-appearance',
]);
```

**BuddyX Status:** Not implemented.

**Gap:** No visual section indicators.

---

## Output Features Comparison

| Feature | Available | BuddyX Uses | Gap |
|---------|-----------|-------------|-----|
| `output` array | Yes | 4 fields only | 130+ fields manual |
| `css_vars` | Yes | No | All CSS vars manual |
| `js_vars` | Yes | No | No live preview |
| `prefix/suffix` | Yes | No | Manual string concat |
| `pattern` | Yes | No | Complex CSS manual |

### What `output` Can Do

```php
new \Kirki\Field\Color([
    'settings' => 'primary_color',
    'output'   => [
        // Multiple elements
        [
            'element'  => 'a, .btn-primary',
            'property' => 'color',
        ],
        // CSS variable
        [
            'element'  => ':root',
            'property' => '--theme-primary',
        ],
        // With prefix
        [
            'element'  => '.gradient-bg',
            'property' => 'background',
            'prefix'   => 'linear-gradient(135deg, ',
            'suffix'   => ', #ffffff)',
        ],
        // Media query
        [
            'element'     => '.mobile-menu',
            'property'    => 'background-color',
            'media_query' => '@media (max-width: 768px)',
        ],
    ],
]);
```

**BuddyX currently:** Manually builds all this in PHP.

---

## Sanitization Gap

| Sanitizer | Available | BuddyX Uses |
|-----------|-----------|-------------|
| Built-in color | Yes | No |
| Built-in typography | Yes | No |
| Built-in background | Yes | No |
| Built-in dimensions | Yes | No |
| Custom callback | Yes | **0 fields** |

**Risk:** All 134 fields lack explicit sanitization.

---

## Recommended Implementation Priority

### Phase 1: Security (v4.9.3)
Add to ALL fields:
```php
'sanitize_callback' => 'sanitize_hex_color', // or appropriate
```

### Phase 2: CSS Output (v4.9.3)
Convert manual CSS to Kirki output:
```php
// BEFORE (current)
// In Dynamic_Style/Component.php line 78:
$color = get_theme_mod('site_loader_bg');
$css .= '--color-theme-loader: ' . $color . ';';

// AFTER (using Kirki)
// In Kirki_Option/Component.php:
new \Kirki\Field\Color([
    'settings' => 'site_loader_bg',
    'output'   => [
        ['element' => ':root', 'property' => '--color-theme-loader'],
    ],
]);
// DELETE the manual CSS generation
```

### Phase 3: Live Preview (v4.9.4)
Add js_vars for instant preview:
```php
'js_vars' => [
    [
        'element'  => ':root',
        'function' => 'css',
        'property' => '--color-theme-loader',
    ],
],
```

### Phase 4: New Field Types (v4.9.4)

| Add This | Replace This | Benefit |
|----------|--------------|---------|
| `Dimensions` | 4 separate Dimension fields | One control for padding |
| `Color_Palette` | Manual color fields | Quick color schemes |
| `Sortable` | Checkboxes for order | Drag-drop ordering |
| `Code` | Textarea for CSS | Syntax highlighting |
| `Multicheck` | Multiple checkboxes | Cleaner UI |

### Phase 5: PRO Features (v5.0.0)

| Add This | Benefit |
|----------|---------|
| `Responsive` | Mobile/tablet specific colors |
| `Tabs` | Organized customizer sections |
| `Margin/Padding` | 4-sided spacing controls |
| `Input_Slider` | Better numeric inputs |

### Phase 6: Presets (v5.0.0)
```php
new \Kirki\Field\Select([
    'settings' => 'color_preset',
    'label'    => 'Color Scheme',
    'choices'  => [
        'default' => 'BuddyX Default',
        'ocean'   => 'Ocean Blue',
        'forest'  => 'Forest Green',
        'sunset'  => 'Warm Sunset',
        'dark'    => 'Dark Mode',
    ],
    'preset' => [
        'default' => [
            'settings' => [
                'site_primary_color' => '#ef5455',
                'body_background_color' => '#f7f7f9',
            ],
        ],
        'ocean' => [
            'settings' => [
                'site_primary_color' => '#0077B6',
                'body_background_color' => '#F0F9FF',
            ],
        ],
    ],
]);
```

---

## Code Cleanup Potential

### Current Dynamic_Style/Component.php

```php
// 40+ duplicate get_theme_mod calls
$site_loader_bg = get_theme_mod('site_loader_bg');
$site_primary_color = get_theme_mod('site_primary_color');
// ... 38 more ...

// Then builds CSS manually
$css = ':root {';
$css .= '--color-theme-loader: ' . $site_loader_bg . ';';
// ... 100+ lines of string concatenation
```

### After Kirki Output Implementation

```php
// Dynamic_Style/Component.php can be DELETED
// Kirki handles ALL CSS output automatically
```

**Lines of code saved:** ~300+ lines

---

## Files to Modify

| File | Action | Impact |
|------|--------|--------|
| `inc/Kirki_Option/Component.php` | Add output, sanitize_callback | High |
| `inc/Dynamic_Style/Component.php` | Remove after migration | High |
| `external/kirki-utils.php` | Consolidate defaults | Medium |

---

## Summary: What BuddyX Is Missing

| Feature | Status | Impact |
|---------|--------|--------|
| Automatic CSS output | Not using | Manual code, bugs |
| CSS variables output | Not using | Manual code |
| Live preview (js_vars) | Not using | Poor UX |
| Partial refresh | Not using | Slow preview |
| Sanitization | Not using | **Security risk** |
| Color presets | Not using | Feature gap |
| Tooltips | Not using | UX gap |
| Section icons | Not using | Visual gap |
| PRO field types | Not using | Feature gap |
| Responsive controls | Not using | Mobile gap |

---

## Backward Compatibility Reminder

When adding these features:
1. **Keep all setting keys identical** - `site_loader_bg` stays `site_loader_bg`
2. **Keep defaults identical** - `#ef5455` stays `#ef5455`
3. **Test existing sites** - Values must persist
4. **Keep Dynamic_Style as fallback** - Until confident in Kirki output

---

*This analysis is based on Kirki 5.1.1 installed at `/wp-content/plugins/kirki/`*
