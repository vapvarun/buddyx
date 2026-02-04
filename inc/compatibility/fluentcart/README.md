# FluentCart Integration for BuddyX Theme

## Overview

The BuddyX theme includes full integration with FluentCart, providing seamless e-commerce functionality with native theme styling and layout options using a **hook-based approach**.

---

## Architecture: Hook-Based Integration

Unlike BuddyX Pro which uses custom template files, BuddyX uses a **hook-based architecture**:

- **No `add_theme_support('fluent_cart')`** - Theme does not declare FluentCart support
- **No custom template files** - Uses FluentCart's generic fallback template
- **Hook into FluentCart's template** - Injects theme elements via `fluent_cart/generic_template/before_content`

This approach is simpler and requires less maintenance while still providing full functionality.

---

## Features Implemented

### 1. **Single Product Pages**
- Automatic removal of duplicate product titles and featured images
- Clean, full-width product display (configurable via customizer)
- FluentCart's product interface renders natively
- Sub-header and breadcrumbs automatically hidden

### 2. **Product Archives (Shop Pages)**
- Category archives: `/product-categories/category-name/`
- Brand archives: `/product-brands/brand-name/`
- Main shop archive: `/item/` or custom shop page
- Full-width layout without sidebar space
- Sub-header with page title and breadcrumbs **injected via hook**
- FluentCart shop app with filters and product grid

### 3. **Cart Icon in Header**
- FluentCart cart icon integrated into theme header
- Real-time cart count updates
- Click to open FluentCart drawer
- Customizable via theme customizer

### 4. **Checkout & Receipt Pages**
- Floating cart button automatically disabled
- Clean checkout experience
- Full-width template support

---

## Technical Implementation

### Hook-Based Architecture

**How It Works**:

1. **FluentCart's Generic Template**:
   - When no theme support is declared, FluentCart uses `fallback-generic-template.php`
   - This template fires the hook: `fluent_cart/generic_template/before_content`

2. **BuddyX Hooks Into Template**:
   ```php
   add_action( 'fluent_cart/generic_template/before_content', array( $this, 'buddyx_fluentcart_render_archive_sub_header' ) );
   ```

3. **Sub-Header Injection**:
   ```php
   public function buddyx_fluentcart_render_archive_sub_header() {
       // Only render on FluentCart archive pages.
       if ( ! is_tax( 'product-categories' )
            && ! is_tax( 'product-brands' )
            && ! is_post_type_archive( 'fluent-products' ) ) {
           return;
       }

       // Render the theme's sub-header.
       do_action( 'buddyx_sub_header' );
   }
   ```

**Benefits**:
- ✅ Simpler implementation
- ✅ No template files to maintain
- ✅ Automatically works with FluentCart updates
- ✅ Less code to manage

---

## File Structure

```
buddyx/
├── inc/
│   └── compatibility/
│       └── fluentcart/
│           ├── fluentcart-functions.php (Main integration class)
│           ├── CUSTOMER-GUIDE.md (End-user documentation)
│           └── README.md (This file - Developer documentation)
└── single-fluent-products.php (Single product template)
```

**Note**: Only one template file exists (`single-fluent-products.php`). Archive pages use FluentCart's generic template + hooks.

---

## Main Integration Class

**Class**: `BuddyX_FluentCart_Support`

**Location**: `/inc/compatibility/fluentcart/fluentcart-functions.php`

### Key Methods

#### Initialization

```php
private function init_hooks() {
    // Single product page modifications
    add_action( 'wp', array( $this, 'buddyx_fluentcart_single_page_setup' ) );

    // Archive page modifications
    add_action( 'wp', array( $this, 'buddyx_fluentcart_archive_page_setup' ) );

    // Add sub-header to FluentCart archive pages (HOOK-BASED)
    add_action( 'fluent_cart/generic_template/before_content', array( $this, 'buddyx_fluentcart_render_archive_sub_header' ) );

    // Override sidebar for single products based on customizer setting
    add_filter( 'theme_mod_sidebar_option', array( $this, 'buddyx_fluentcart_override_sidebar_option' ) );
    add_filter( 'theme_mod_single_post_sidebar_option', array( $this, 'buddyx_fluentcart_override_sidebar_option' ) );

    // Theme activation - run once
    add_action( 'after_switch_theme', array( $this, 'buddyx_fluentcart_set_theme_defaults' ) );

    // Add customizer options
    add_action( 'init', array( $this, 'buddyx_fluentcart_add_customizer_option' ), 20 );

    // Cart display setup - only if WooCommerce and SureCart are not active
    if ( ! class_exists( 'WooCommerce' ) && ! defined( 'SURECART_PLUGIN_FILE' ) ) {
        add_action( 'init', array( $this, 'buddyx_fluentcart_setup_cart_display' ), 5 );
    }

    // Add body class for FluentCart pages
    add_filter( 'body_class', array( $this, 'buddyx_fluentcart_body_classes' ) );

    // Disable floating cart button on checkout pages
    add_filter( 'fluent_cart/buttons/enable_floating_cart_button', array( $this, 'buddyx_fluentcart_disable_floating_button' ), 10, 2 );
}
```

#### Single Product Page Setup

```php
public function buddyx_fluentcart_single_page_setup() {
    if ( ! is_singular( 'fluent-products' ) ) {
        return;
    }

    // Add inline CSS to hide duplicate elements
    add_action( 'wp_head', array( $this, 'buddyx_fluentcart_add_single_page_styles' ), 999 );

    // Remove all actions from buddyx_sub_header hook
    remove_all_actions( 'buddyx_sub_header' );

    // Prevent entry header from being loaded
    add_filter( 'get_post_type', array( $this, 'buddyx_fluentcart_prevent_entry_header' ), 10, 2 );
}
```

#### Archive Page Setup

```php
public function buddyx_fluentcart_archive_page_setup() {
    if ( ! is_tax( 'product-categories' )
         && ! is_tax( 'product-brands' )
         && ! is_post_type_archive( 'fluent-products' ) ) {
        return;
    }

    // Add inline CSS for full-width layout
    add_action( 'wp_head', array( $this, 'buddyx_fluentcart_archive_styles' ), 999 );
}
```

#### Sidebar Override (Critical Fix)

```php
public function buddyx_fluentcart_override_sidebar_option( $value ) {
    if ( is_singular( 'fluent-products' ) ) {
        // Get the customizer setting for product sidebar, default to 'none'
        $product_sidebar = get_theme_mod( 'fluentcart_product_sidebar', 'none' );
        return $product_sidebar;
    }
    return $value;
}
```

**Hooks Used**:
- `theme_mod_sidebar_option` - Overrides global sidebar setting
- `theme_mod_single_post_sidebar_option` - Overrides single post sidebar setting

This ensures the customizer setting (`fluentcart_product_sidebar`) controls the sidebar display.

---

## Customizer Options

### Header Cart Icon

**Location**: `Customize > Header > Primary Header`

```php
new \Kirki\Field\Checkbox_Switch(
    array(
        'settings'    => 'site_header_enable_cart',
        'label'       => esc_html__( 'Enable Cart Icon?', 'buddyx' ),
        'section'     => 'site_header_primary_section',
        'default'     => '1',
        'choices'     => array(
            'on'  => esc_html__( 'Yes', 'buddyx' ),
            'off' => esc_html__( 'No', 'buddyx' ),
        ),
        'tooltip'     => esc_html__( 'Display FluentCart cart icon in header', 'buddyx' ),
        'transport'   => 'refresh',
    )
);
```

### Product Sidebar Layout

**Location**: `Customize > Sidebar > Sidebar Layout`

```php
new \Kirki\Field\Radio_Image(
    array(
        'settings'    => 'fluentcart_product_sidebar',
        'label'       => esc_html__( 'FluentCart Product Sidebar', 'buddyx' ),
        'section'     => 'site_sidebar_layout',
        'default'     => 'none',
        'choices'     => array(
            'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
            'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
            'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
            'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
        ),
        'tooltip'     => esc_html__( 'Choose sidebar layout for FluentCart single product pages', 'buddyx' ),
        'transport'   => 'refresh',
    )
);
```

---

## CSS Implementation

### Single Product Pages

**Dynamic CSS** (injected via `wp_head`):

```css
/* Hide duplicate title and featured image */
.single-fluent-products .entry-header .entry-title,
.single-fluent-products .entry-header-post,
.single-fluent-products .post-thumbnail,
.single-fluent-products .featured-wrap {
    display: none !important;
}

/* Ensure proper spacing */
.single-fluent-products .entry-content {
    margin-top: 0;
}

/* Full-width layout when no sidebar (conditional) */
.single-fluent-products .site-main {
    width: 100% !important;
    max-width: 100% !important;
    flex: 0 0 100% !important;
}

.single-fluent-products .site-wrapper {
    display: block !important;
}
```

**Note**: Sidebar hiding CSS removed (overkill) - sidebar is already not rendered due to filter hooks.

### Archive Pages

```css
/* Full-width layout for product archives */
.tax-product-categories .site-wrapper,
.tax-product-brands .site-wrapper,
.post-type-archive-fluent-products .site-wrapper {
    max-width: 100% !important;
}

.tax-product-categories .site-main,
.tax-product-brands .site-main,
.post-type-archive-fluent-products .site-main {
    width: 100% !important;
    max-width: 100% !important;
}

/* Hide sidebars on archive pages */
.tax-product-categories #secondary,
.tax-product-brands #secondary,
.post-type-archive-fluent-products #secondary {
    display: none !important;
}

/* Hide FluentCart's duplicate archive title */
.tax-product-categories .fc-archive-header-wrap,
.tax-product-brands .fc-archive-header-wrap {
    display: none !important;
}
```

---

## JavaScript Integration

### Cart Count Updates

```javascript
document.addEventListener('fluent_cart_updated', function(e) {
    const cartCount = document.querySelector('.fc-cart-count');
    if (cartCount && e.detail && e.detail.cart_data) {
        const itemCount = e.detail.cart_data.length;
        if (itemCount > 0) {
            cartCount.textContent = itemCount;
            cartCount.style.display = 'inline-block';
        } else {
            cartCount.style.display = 'none';
        }
    }
});
```

### Drawer Toggle

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const cartIcon = document.querySelector('.fcart-cart-toogle-button');
    if (cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            // Trigger FluentCart drawer toggle
            const event = new CustomEvent('fluent_cart_toggle_drawer');
            document.dispatchEvent(event);
        });
    }
});
```

---

## Body Classes Added

The integration automatically adds these body classes for styling purposes:

| Page Type | Body Class |
|-----------|------------|
| Checkout Page | `fluent-cart-checkout` |
| Receipt Page | `fluent-cart-receipt` |
| Single Product | `fluent-cart-product` |

---

## Compatibility Functions

When WooCommerce and SureCart are not active, the theme creates:

### Dummy Functions

```php
if ( ! function_exists( 'is_woocommerce' ) ) {
    function is_woocommerce() {
        return false;
    }
}
```

### Override Functions

```php
if ( ! function_exists( 'buddyx_render_cart_icon' ) ) {
    function buddyx_render_cart_icon() {
        // FluentCart cart icon implementation
    }
}
```

---

## Theme Activation Defaults

On theme activation, the following defaults are automatically set:

1. **Checkout Page Template**: Set to `Full Width`
2. **Cart Icon**: Enabled by default
3. **Product Sidebar**: Set to None (no sidebar)

**Implementation**:

```php
public function buddyx_fluentcart_set_theme_defaults() {
    if ( get_option( 'buddyx_fluentcart_defaults_set', false ) ) {
        return;
    }

    // Set FluentCart pages to use full width template if they exist
    $checkout_page_id = get_option( 'fluent_cart_checkout_page_id', 0 );
    if ( $checkout_page_id && get_post( $checkout_page_id ) ) {
        update_post_meta( $checkout_page_id, '_wp_page_template', 'page-templates/full-width.php' );
    }

    // Mark defaults as set
    update_option( 'buddyx_fluentcart_defaults_set', true );
}
```

---

## Hooks Available for Developers

### FluentCart Hooks Used

**Archive Sub-Header Injection**:
```php
fluent_cart/generic_template/before_content
```

**Floating Cart Button Control**:
```php
fluent_cart/buttons/enable_floating_cart_button
```

**Cart Update Event** (JavaScript):
```javascript
fluent_cart_updated
fluent_cart_toggle_drawer
```

### WordPress Hooks Used

**Sidebar Override**:
- `theme_mod_sidebar_option`
- `theme_mod_single_post_sidebar_option`

**Post Type Filter**:
- `get_post_type`

**Body Classes**:
- `body_class`

**Theme Activation**:
- `after_switch_theme`

---

## Differences from BuddyX Pro

| Feature | BuddyX (Free) | BuddyX Pro |
|---------|---------------|------------|
| **Architecture** | Hook-based | Template-based |
| **Theme Support** | Not declared | `add_theme_support('fluent_cart')` |
| **Template Files** | Single product only | All archives + single |
| **Sub-Header Injection** | Via hook | Via templates |
| **Maintenance** | Lower | Higher |
| **FluentCart Updates** | Auto-compatible | May need template updates |

### Why Different Approaches?

**BuddyX (Free)**:
- Simpler codebase
- Easier maintenance
- Hook-based is sufficient for needs

**BuddyX Pro**:
- More control over markup
- Custom template structure
- Professional-grade customization

Both achieve the same result for end users!

---

## Troubleshooting

### Cart Icon Not Showing

**Check**:
1. Customizer setting: `Customize > Header > Primary Header > Enable Cart Icon?`
2. WooCommerce/SureCart not active (theme defers to these)
3. Browser cache cleared

### Archive Pages Not Full Width

**Check**:
1. FluentCart plugin is active
2. Hook is firing: `fluent_cart/generic_template/before_content`
3. Clear browser and server cache

### Product Sidebar Still Showing

**Check**:
1. Customizer setting: `Customize > Sidebar > FluentCart Product Sidebar`
2. Filter hooks are registered (lines 86-87)
3. Template file exists: `single-fluent-products.php`

### Sub-Header Not Showing on Archives

**Check**:
1. FluentCart is using generic template (no theme support declared)
2. Hook `buddyx_fluentcart_render_archive_sub_header()` is registered
3. Conditional checks pass (is_tax, is_post_type_archive)

**Debug**:
```php
add_action( 'fluent_cart/generic_template/before_content', function() {
    error_log( 'FluentCart generic template hook fired' );
}, 5 );
```

---

## Developer Notes

### Customizing the Integration

**Disable Cart Icon Programmatically**:
```php
add_filter( 'theme_mod_site_header_enable_cart', '__return_false' );
```

**Change Default Product Sidebar**:
```php
add_filter( 'theme_mod_fluentcart_product_sidebar', function( $value ) {
    return 'right'; // Options: left, right, both, none
} );
```

**Add Custom Content to Archive Sub-Header**:
```php
add_action( 'fluent_cart/generic_template/before_content', function() {
    if ( is_tax( 'product-categories' ) ) {
        echo '<div class="custom-banner">Shop Now!</div>';
    }
}, 20 ); // Priority > 10 to run after theme's sub-header
```

### Extending Single Product Pages

The `single-fluent-products.php` template uses the standard WordPress loop:

```php
while ( have_posts() ) {
    the_post();
    get_template_part( 'template-parts/content/entry', get_post_type() );
}
```

To customize, create a custom entry template:
`template-parts/content/entry-fluent-products.php`

Or hook into FluentCart's product rendering:
```php
add_action( 'fluent_cart/product/before_content', function() {
    // Custom content before product
} );
```

### Performance Optimization

**Conditional Script Loading**:

The cart scripts only load when cart is enabled:

```php
$cart_enabled = get_theme_mod( 'site_header_enable_cart', true );
if ( ! $cart_enabled ) {
    return; // Skip script injection
}
```

**CSS is Conditionally Injected**:

CSS only loads on FluentCart pages:
- Single products: `is_singular('fluent-products')`
- Archives: `is_tax()` or `is_post_type_archive()`

---

## Code Quality & Optimization

### Recent Optimizations (October 2025)

1. **Removed Redundant CSS** (7 lines):
   - Breadcrumb hiding CSS (already not rendered)
   - Sidebar hiding CSS (already not rendered)

2. **Removed Unnecessary Method** (4 lines):
   - `buddyx_fluentcart_remove_sub_header_actions()`
   - Replaced with direct `remove_all_actions()` call

3. **Removed Unused Variable** (1 line):
   - `$cart_items` in cart icon function

**Result**: Cleaner, leaner codebase (563 lines, down from 575).

### Code Standards

- ✅ WordPress Coding Standards compliant
- ✅ Namespaced functions
- ✅ Singleton pattern for class instance
- ✅ Proper escaping and sanitization
- ✅ Comprehensive inline documentation
- ✅ Early returns for performance

---

## Version History

### Version 1.0.0 (October 2025)
- Initial FluentCart integration
- Hook-based architecture
- Single product page support
- Archive sub-header injection
- Header cart icon
- Customizer options
- Full-width layouts
- Performance optimization
- Code cleanup

---

## Testing Checklist

### Single Product Pages
- [ ] Duplicate title hidden
- [ ] Duplicate featured image hidden
- [ ] Sub-header/breadcrumbs hidden
- [ ] Sidebar respects customizer setting
- [ ] Full-width layout when sidebar = none
- [ ] FluentCart product interface renders
- [ ] Add to cart works

### Archive Pages
- [ ] Category archives show sub-header
- [ ] Brand archives show sub-header
- [ ] Post type archive shows sub-header (if applicable)
- [ ] All archives are full-width
- [ ] FluentCart shop interface renders
- [ ] Filters work
- [ ] Sorting works

### Cart Icon
- [ ] Icon appears in header
- [ ] Cart count displays correctly
- [ ] Count updates on add to cart
- [ ] Click opens FluentCart drawer
- [ ] Can be disabled via customizer

### Checkout & Receipt
- [ ] Floating cart button hidden
- [ ] Full-width layout
- [ ] Checkout process works
- [ ] Receipt displays correctly

### Compatibility
- [ ] Works with FluentCart Free
- [ ] Works with FluentCart Pro
- [ ] Deactivates gracefully when WooCommerce active
- [ ] No JavaScript errors
- [ ] No PHP errors or warnings

---

## Support

For issues or questions about FluentCart integration:

1. **Check this documentation**
2. **Verify FluentCart plugin is up to date**
3. **Check theme customizer settings**
4. **Review browser console for JavaScript errors**
5. **Check PHP error logs**
6. **Contact theme support with specific details**

---

## Additional Resources

- **FluentCart Documentation**: https://fluentcart.com/docs/
- **FluentCart Hooks Reference**: Check plugin source code
- **Theme Documentation**: Check theme package
- **WordPress Template Hierarchy**: https://developer.wordpress.org/themes/basics/template-hierarchy/

---

## Contributing

If you find bugs or have suggestions:

1. Document the issue clearly
2. Provide reproduction steps
3. Include environment details (WP version, PHP version, etc.)
4. Submit to theme support

---

**Maintained by**: BuddyX Theme Team
**Last Updated**: October 2025
**Version**: 1.0.0
