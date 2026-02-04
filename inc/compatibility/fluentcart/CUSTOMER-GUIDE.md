# BuddyX Theme - FluentCart Integration Guide

**Version**: 1.0.0
**Last Updated**: October 2025
**Compatibility**: FluentCart Free & Pro

---

## Table of Contents

1. [Introduction](#introduction)
2. [What You Get](#what-you-get)
3. [Getting Started](#getting-started)
4. [Configuring Your Shop](#configuring-your-shop)
5. [Customizer Settings](#customizer-settings)
6. [Page Layout Options](#page-layout-options)
7. [Common Tasks](#common-tasks)
8. [Troubleshooting](#troubleshooting)
9. [FAQs](#faqs)
10. [Getting Help](#getting-help)

---

## Introduction

The BuddyX theme comes with **built-in FluentCart integration**, giving you a beautiful, modern online store that seamlessly blends with your theme's design. No additional setup required – just install FluentCart and you're ready to sell!

### What is FluentCart?

FluentCart is a lightweight, fast WordPress e-commerce plugin that lets you sell digital or physical products directly from your website. It's designed to be simple yet powerful.

---

## What You Get

### ✨ Automatic Features

When you activate FluentCart with BuddyX, you automatically get:

- **🛒 Header Cart Icon** - Shows your cart count in real-time
- **📱 Responsive Shop Pages** - Beautiful product grids that work on all devices
- **🎨 Theme-Matched Design** - Shop pages styled to match your theme
- **🔍 Category & Brand Pages** - Organized product browsing with sub-headers
- **📄 Clean Single Product Pages** - Professional product displays
- **✅ Optimized Checkout** - Streamlined purchasing experience
- **📊 Full-Width Layouts** - Maximum space for your products

### 🎯 No Coding Required

Everything works out of the box. Just install FluentCart, and the theme handles the rest!

---

## Getting Started

### Step 1: Install FluentCart

1. Log in to your WordPress admin panel
2. Go to **Plugins > Add New**
3. Search for "FluentCart"
4. Click **Install Now** on the FluentCart plugin
5. Click **Activate**

### Step 2: Configure FluentCart

After activation, FluentCart will guide you through a quick setup wizard:

1. Set your **store name** and **currency**
2. Configure **payment methods** (PayPal, Stripe, etc.)
3. Create your **shop page** (optional - can be done later)
4. Add your **first product**

> **Tip**: The theme will automatically apply beautiful styling to all your shop pages!

### Step 3: Check Your Shop

Visit your shop page to see your products displayed in a beautiful grid layout with:
- Product images
- Pricing
- Add to cart buttons
- Filters and sorting options

**That's it!** Your online store is ready.

---

## Configuring Your Shop

### Creating a Shop Page

FluentCart can create a dedicated shop page for you:

**Method 1: During Setup Wizard**
- The setup wizard will offer to create a shop page
- Click "Create Shop Page"
- Done!

**Method 2: Manual Creation**
1. Go to **Pages > Add New**
2. Title: "Shop" (or any name you prefer)
3. Add the FluentCart shop shortcode: `[fluent_cart_products]`
4. Click **Publish**

### Creating Product Categories

Organize your products into categories:

1. Go to **FluentCart > Categories** (or **Products > Categories**)
2. Enter category name (e.g., "Men's Clothing", "Electronics")
3. Add a description (optional)
4. Upload category image (optional)
5. Click **Add New Category**

The theme will automatically create beautiful category pages at:
- `yoursite.com/product-categories/category-name/`

### Creating Product Brands

Create brand pages for your products:

1. Go to **FluentCart > Brands** (or **Products > Brands**)
2. Enter brand name (e.g., "Nike", "Apple")
3. Add details and logo
4. Click **Add New Brand**

Brand pages appear at:
- `yoursite.com/product-brands/brand-name/`

---

## Customizer Settings

The theme adds special FluentCart options to the WordPress Customizer.

### Accessing the Customizer

1. Go to **Appearance > Customize**
2. You'll find FluentCart options in two sections

### Cart Icon Setting

**Location**: Customize > Header > Primary Header

**Option**: "Enable Cart Icon?"

**What it does**:
- Shows/hides the shopping cart icon in your header
- Displays the number of items in the cart
- Clicking opens the cart sidebar

**To Configure**:
1. Open Customizer
2. Navigate to **Header > Primary Header**
3. Find **"Enable Cart Icon?"**
4. Toggle **ON** (enabled) or **OFF** (disabled)
5. Click **Publish** to save

**When to use**:
- **ON**: Most stores (recommended)
- **OFF**: If using a custom cart solution or WooCommerce

---

### Product Sidebar Layout

**Location**: Customize > Sidebar > Sidebar Layout

**Option**: "FluentCart Product Sidebar"

**What it does**:
- Controls sidebar display on single product pages
- Choose from 4 layout options

**Layout Options**:

1. **No Sidebar** (Default - Recommended)
   - Full-width product display
   - More space for product images and description
   - Modern, clean look

2. **Right Sidebar**
   - Product on left, sidebar on right
   - Good for related products or ads

3. **Left Sidebar**
   - Sidebar on left, product on right
   - Alternative layout option

4. **Both Sidebars**
   - Sidebars on both sides
   - Product in the middle
   - For content-heavy sites

**To Configure**:
1. Open Customizer
2. Navigate to **Sidebar > Sidebar Layout**
3. Find **"FluentCart Product Sidebar"**
4. Click your preferred layout image
5. Click **Publish** to save

**Recommendation**: Use "No Sidebar" for the best product showcase.

---

## Page Layout Options

### Making Pages Full-Width

Want to remove the sidebar from specific pages?

#### Option 1: Using Page Template

**For individual pages** (Checkout, About, etc.):

1. Edit the page in WordPress
2. Look for **Page Attributes** box (right sidebar)
3. Under **Template**, select: **"Full Width"**
4. Click **Update**

**Pages that automatically use full-width**:
- Checkout page (set automatically by theme)
- All product category pages
- All product brand pages
- Main shop archive

#### Option 2: Global Sidebar Setting

**For all pages at once**:

1. Go to **Appearance > Customize**
2. Navigate to **Sidebar > Sidebar Layout**
3. Select **"No Sidebar"** option
4. Click **Publish**

> **Note**: Product archives (shop, categories, brands) are always full-width for the best shopping experience.

---

## Common Tasks

### Adding Your First Product

1. Go to **FluentCart > Products > Add New**
2. Enter product details:
   - **Title**: Product name
   - **Description**: Full product details
   - **Price**: Set your price
   - **Images**: Upload product photos
3. Select **Category** (if created)
4. Select **Brand** (if applicable)
5. Set **Stock** status
6. Click **Publish**

### Customizing Cart Icon Color

The cart icon matches your theme's primary color automatically.

**To change the color**:
1. Go to **Appearance > Customize**
2. Navigate to **Colors** (section name may vary)
3. Change **Primary Color**
4. The cart icon and badge will update automatically

### Viewing Your Shop Statistics

1. Go to **FluentCart > Dashboard**
2. View:
   - Total sales
   - Revenue
   - Recent orders
   - Popular products

### Managing Orders

1. Go to **FluentCart > Orders**
2. View all customer orders
3. Click an order to:
   - View details
   - Update status
   - Process refunds
   - Send customer emails

---

## Troubleshooting

### Cart Icon Not Showing

**Possible Causes & Solutions**:

1. **Cart icon disabled in settings**
   - Go to Customize > Header > Primary Header
   - Enable "Enable Cart Icon?"

2. **WooCommerce or SureCart active**
   - The theme defers to these plugins' cart icons
   - Disable them if you want to use FluentCart's cart

3. **Browser cache**
   - Clear your browser cache
   - Try viewing in incognito/private mode

### Product Pages Look Broken

**Solution**:
1. Go to **Settings > Permalinks**
2. Click **Save Changes** (even without changing anything)
3. This refreshes your URL structure
4. Check your product pages again

### Categories Not Showing Products

**Possible Causes**:

1. **Products not assigned to category**
   - Edit each product
   - Check the category box
   - Update product

2. **Empty category**
   - Make sure you've added products to that category

### Sub-Header Not Showing on Archive Pages

**Solution**:
1. The theme automatically adds sub-headers to FluentCart archives
2. If missing, deactivate and reactivate the theme
3. Clear browser and server cache
4. Contact support if issue persists

### Checkout Page Has Sidebar

**Solution**:
1. Edit the checkout page
2. Set **Page Template** to "Full Width"
3. Update the page

> **Note**: This should be automatic, but sometimes needs manual setting.

---

## FAQs

### Does this work with FluentCart Free?

**Yes!** The integration works perfectly with both FluentCart Free and FluentCart Pro.

### Can I use WooCommerce instead?

Yes, the theme supports WooCommerce separately. However, you can only use **one e-commerce plugin at a time**. If you activate WooCommerce, FluentCart features will be disabled.

### Will my shop pages match my theme?

Absolutely! All shop pages automatically inherit your theme's:
- Colors
- Fonts
- Spacing
- Layout structure

### Can I customize the shop page design?

Yes, through FluentCart's own settings:
1. Go to **FluentCart > Settings > Shop**
2. Configure:
   - Products per page
   - Grid columns
   - Sorting options
   - Filter display

The theme provides the layout structure, FluentCart handles the shop functionality.

### What happens if I deactivate FluentCart?

The theme gracefully handles this:
- Cart icon disappears
- Shop pages fall back to normal archive pages
- **No errors or broken pages**

### Can I translate the cart icon text?

Yes, the theme is translation-ready. Use a translation plugin like:
- Loco Translate
- WPML
- Polylang

### How do I add related products?

FluentCart Pro includes this feature:
1. Edit a product
2. Find "Related Products" section
3. Select products to show
4. Save

The theme will display them beautifully on the product page.

### Can I change the number of products per page?

Yes:
1. Go to **FluentCart > Settings > Shop**
2. Find **"Products per page"**
3. Enter your desired number (e.g., 12, 24, 48)
4. Save changes

### Does it work with page builders?

Yes! The shop pages work with popular page builders:
- Elementor
- Beaver Builder
- Divi Builder

Just use FluentCart's shortcodes or blocks.

### Is it mobile-friendly?

Absolutely! All shop pages are fully responsive and look great on:
- Desktop computers
- Tablets
- Mobile phones

### Can I hide the breadcrumbs on shop pages?

The breadcrumbs are part of the theme's sub-header section. To hide them:

**Option 1**: Use CSS (add to Customizer > Additional CSS):
```css
.tax-product-categories .buddyx-breadcrumbs,
.tax-product-brands .buddyx-breadcrumbs,
.post-type-archive-fluent-products .buddyx-breadcrumbs {
    display: none;
}
```

**Option 2**: Contact theme support for a setting to control this.

---

## Getting Help

### Documentation Resources

1. **FluentCart Documentation**
   - Visit: [fluentcart.com/docs](https://fluentcart.com/docs)
   - Comprehensive guides on all FluentCart features

2. **BuddyX Theme Documentation**
   - Check your theme package for full theme docs
   - Covers all theme features and settings

3. **Video Tutorials**
   - Check your theme vendor's YouTube channel
   - Look for FluentCart integration videos

### Support Channels

#### Theme Support

For issues with:
- Theme styling
- Customizer settings
- Page layouts
- Cart icon display

**Contact**: Your theme vendor's support team
- Include: Theme version, WordPress version, FluentCart version
- Provide: Screenshots of the issue
- Describe: Steps to reproduce the problem

#### FluentCart Support

For issues with:
- Product management
- Payment processing
- Order management
- FluentCart features

**Contact**: FluentCart support team
- Visit: FluentCart support portal
- Include: Plugin version, error messages

### Before Contacting Support

**Please check**:
1. ✅ WordPress is up to date
2. ✅ Theme is up to date
3. ✅ FluentCart is up to date
4. ✅ Permalinks refreshed (Settings > Permalinks > Save)
5. ✅ Browser cache cleared
6. ✅ Tried in incognito/private mode
7. ✅ Checked error logs (if technical)

**Prepare this information**:
- WordPress version
- Theme version
- FluentCart version
- Browser and device
- Screenshot of the issue
- Steps to reproduce

---

## Quick Reference

### Important Pages

| Page Type | URL Pattern | Purpose |
|-----------|-------------|------------|
| Shop | `/shop/` or `/item/` | Main product listing |
| Category | `/product-categories/name/` | Products by category |
| Brand | `/product-brands/name/` | Products by brand |
| Product | `/item/product-name/` | Single product page |
| Checkout | `/checkout/` | Purchase page |

### Default Settings

| Setting | Default Value | Location |
|---------|---------------|----------|
| Cart Icon | Enabled | Customizer > Header |
| Product Sidebar | No Sidebar | Customizer > Sidebar |
| Checkout Template | Full Width | Auto-configured |
| Shop Layout | Full Width | Automatic |

### Customizer Quick Access

```
Appearance > Customize >
├── Header > Primary Header
│   └── Enable Cart Icon?
└── Sidebar > Sidebar Layout
    └── FluentCart Product Sidebar
```

---

## Tips for Success

### 🎨 Design Tips

1. **Use High-Quality Images**
   - Minimum 800x800px for product photos
   - Square images work best in grids
   - Consistent image sizes look more professional

2. **Write Clear Descriptions**
   - Highlight key features
   - Include dimensions/specifications
   - Use bullet points for easy scanning

3. **Organize with Categories**
   - Create logical category structure
   - Don't create too many categories (5-10 is ideal)
   - Use category images for visual appeal

### 💰 Sales Tips

1. **Enable Related Products** (Pro)
   - Increases average order value
   - Shows customers more options

2. **Use Product Variations** (Pro)
   - Offer sizes, colors, etc.
   - One product page, multiple options

3. **Set Up Email Notifications**
   - Confirm orders automatically
   - Update customers on shipping
   - Build trust and professionalism

### 🚀 Performance Tips

1. **Optimize Images**
   - Use image compression plugins
   - WebP format for smaller file sizes
   - Lazy loading (built into WordPress 5.5+)

2. **Use Caching**
   - Install a caching plugin
   - Improves shop page loading speed
   - Better customer experience

3. **Keep Plugins Updated**
   - Update theme regularly
   - Update FluentCart when new versions release
   - Update WordPress core

---

## Congratulations! 🎉

You now have a fully-functional online store beautifully integrated with your BuddyX theme!

### Next Steps

1. ✅ Add more products
2. ✅ Set up payment gateways
3. ✅ Configure shipping options
4. ✅ Test the checkout process
5. ✅ Promote your shop!

### Need More Features?

Consider **FluentCart Pro** for:
- Product variations
- Advanced inventory
- Related products
- Coupon codes
- And much more!

---

**Happy Selling!** 🛍️

---

## Appendix: Shortcodes Reference

### FluentCart Shortcodes

Use these anywhere on your site:

**Display Product Grid**:
```
[fluent_cart_products]
```

**Display Specific Category**:
```
[fluent_cart_products category="category-slug"]
```

**Display Specific Brand**:
```
[fluent_cart_products brand="brand-slug"]
```

**Display Cart Button**:
```
[fluent_cart_cart_button]
```

**Display Checkout Form**:
```
[fluent_cart_checkout]
```

**Display Single Product**:
```
[fluent_cart_product id="123"]
```

### Example Use Cases

**Custom Shop Page**:
1. Create a new page
2. Add shortcode: `[fluent_cart_products]`
3. Customize with FluentCart's shortcode parameters

**Featured Products Section**:
1. Add to any page/post
2. Use: `[fluent_cart_products limit="4" featured="yes"]`
3. Shows only featured products

---

*This guide is designed to help you get the most out of your BuddyX theme's FluentCart integration. For the latest updates and additional resources, check your theme documentation and the FluentCart website.*

**Document Version**: 1.0.0
**Compatible With**: BuddyX 4.8.9+, FluentCart Free & Pro
