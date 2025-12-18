# BuddyX Theme - Roadmap v4.9.3

## Block Patterns & Modern WordPress Enhancement Plan

**Version:** 4.9.3
**Focus:** Block patterns, theme.json improvements, WordPress.org compliance
**Reference:** Twenty Twenty-Five theme architecture

---

## Current State (v4.9.2)

| Component | Current | Target |
|-----------|---------|--------|
| Block Patterns | 19 | 40+ |
| Pattern Categories | 4 | 8 |
| theme.json version | 2 | 3 |
| Color Presets | 5 | 8 |
| Spacing Presets | Basic | 7 (fluid) |
| Block Styles | 1 | 8 |

---

## Phase 1: theme.json Upgrade

### 1.1 Update to Version 3

**File:** `theme.json`

```json
{
  "$schema": "https://schemas.wp.org/wp/6.7/theme.json",
  "version": 3
}
```

### 1.2 Add Fluid Spacing Scale

```json
{
  "settings": {
    "spacing": {
      "spacingSizes": [
        { "name": "Tiny", "slug": "10", "size": "clamp(8px, 2vw, 10px)" },
        { "name": "Small", "slug": "20", "size": "clamp(12px, 3vw, 20px)" },
        { "name": "Medium", "slug": "30", "size": "clamp(20px, 4vw, 30px)" },
        { "name": "Regular", "slug": "40", "size": "clamp(30px, 5vw, 50px)" },
        { "name": "Large", "slug": "50", "size": "clamp(40px, 6vw, 70px)" },
        { "name": "X-Large", "slug": "60", "size": "clamp(50px, 8vw, 100px)" },
        { "name": "XX-Large", "slug": "70", "size": "clamp(70px, 10vw, 140px)" }
      ]
    }
  }
}
```

### 1.3 Expand Color Palette

```json
{
  "settings": {
    "color": {
      "palette": [
        { "color": "#FFFFFF", "name": "Base", "slug": "base" },
        { "color": "#111111", "name": "Contrast", "slug": "contrast" },
        { "color": "#ef5455", "name": "Primary", "slug": "primary" },
        { "color": "#41848f", "name": "Secondary", "slug": "secondary" },
        { "color": "#F6F6F6", "name": "Tertiary", "slug": "tertiary" },
        { "color": "#f7f7f9", "name": "Light Gray", "slug": "light-gray" },
        { "color": "#505050", "name": "Dark Gray", "slug": "dark-gray" },
        { "color": "#27AE60", "name": "Success", "slug": "success" }
      ]
    }
  }
}
```

---

## Phase 2: New Pattern Categories

### 2.1 Register Categories

**File:** `inc/Base_Support/Component.php`

```php
function buddyx_register_pattern_categories() {
    // Existing
    register_block_pattern_category('buddyx-general', [
        'label' => __('BuddyX General', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-hero', [
        'label' => __('BuddyX Hero Sections', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-footer', [
        'label' => __('BuddyX Footer', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-query', [
        'label' => __('BuddyX Posts', 'buddyx')
    ]);

    // NEW Categories
    register_block_pattern_category('buddyx-header', [
        'label' => __('BuddyX Header', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-cta', [
        'label' => __('BuddyX Call to Action', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-team', [
        'label' => __('BuddyX Team', 'buddyx')
    ]);
    register_block_pattern_category('buddyx-testimonials', [
        'label' => __('BuddyX Testimonials', 'buddyx')
    ]);
}
```

---

## Phase 3: New Patterns to Create

### 3.1 Header Patterns (3 new)

| Pattern | File | Description |
|---------|------|-------------|
| Header Default | `header-default.php` | Standard header with navigation |
| Header Centered | `header-centered.php` | Logo centered, nav below |
| Header with CTA | `header-with-cta.php` | Header with action button |

### 3.2 CTA Patterns (5 new)

| Pattern | File | Description |
|---------|------|-------------|
| Join Community | `cta-join-community.php` | Community signup CTA |
| Newsletter | `cta-newsletter.php` | Email subscription |
| Membership Preview | `cta-membership.php` | Membership tiers |
| Download App | `cta-download-app.php` | Mobile app promotion |
| Contact Us | `cta-contact.php` | Contact section |

### 3.3 Team Patterns (4 new)

| Pattern | File | Description |
|---------|------|-------------|
| Team Grid 3-Col | `team-grid-3col.php` | 3 column team layout |
| Team Grid 4-Col | `team-grid-4col.php` | 4 column team layout |
| Team Featured | `team-featured.php` | Featured member highlight |
| Team with Social | `team-with-social.php` | Team cards with social links |

### 3.4 Testimonials Patterns (3 new)

| Pattern | File | Description |
|---------|------|-------------|
| Testimonial Centered | `testimonials-centered.php` | Single centered quote |
| Testimonials Grid | `testimonials-grid.php` | 2-3 column testimonials |
| Testimonial Large | `testimonials-large.php` | Large featured testimonial |

### 3.5 Services/Features Patterns (3 new)

| Pattern | File | Description |
|---------|------|-------------|
| Services 3-Col | `services-3col.php` | 3 column feature grid |
| Services with Icons | `services-icons.php` | Icon-based features |
| Services Alternating | `services-alternating.php` | Alternating image/text |

### 3.6 Hidden/Template Patterns (3 new)

| Pattern | File | Description |
|---------|------|-------------|
| 404 Content | `hidden-404.php` | 404 page content |
| Search Header | `hidden-search.php` | Search results header |
| No Results | `hidden-no-results.php` | Empty state message |

---

## Phase 4: Block Styles

### 4.1 Register Block Styles

**File:** `inc/Base_Support/Component.php`

```php
function buddyx_register_block_styles() {
    // Button Styles
    register_block_style('core/button', [
        'name' => 'outline',
        'label' => __('Outline', 'buddyx')
    ]);
    register_block_style('core/button', [
        'name' => 'rounded-full',
        'label' => __('Pill', 'buddyx')
    ]);

    // Image Styles
    register_block_style('core/image', [
        'name' => 'rounded',
        'label' => __('Rounded', 'buddyx')
    ]);
    register_block_style('core/image', [
        'name' => 'shadow',
        'label' => __('Shadow', 'buddyx')
    ]);

    // Group Styles
    register_block_style('core/group', [
        'name' => 'card',
        'label' => __('Card', 'buddyx')
    ]);
    register_block_style('core/group', [
        'name' => 'section-dark',
        'label' => __('Dark Section', 'buddyx')
    ]);

    // Quote Styles
    register_block_style('core/quote', [
        'name' => 'plain',
        'label' => __('Plain', 'buddyx')
    ]);
    register_block_style('core/quote', [
        'name' => 'large',
        'label' => __('Large', 'buddyx')
    ]);
}
add_action('init', 'buddyx_register_block_styles');
```

### 4.2 Block Style CSS

**File:** `assets/css/blocks/block-styles.css`

```css
/* Button - Outline */
.wp-block-button.is-style-outline .wp-block-button__link {
    background: transparent;
    border: 2px solid currentColor;
}

/* Button - Pill */
.wp-block-button.is-style-rounded-full .wp-block-button__link {
    border-radius: 9999px;
}

/* Image - Rounded */
.wp-block-image.is-style-rounded img {
    border-radius: var(--wp--preset--spacing--20, 8px);
}

/* Image - Shadow */
.wp-block-image.is-style-shadow img {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Group - Card */
.wp-block-group.is-style-card {
    background: var(--wp--preset--color--base, #fff);
    border-radius: var(--wp--preset--spacing--20, 8px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    padding: var(--wp--preset--spacing--40, 30px);
}

/* Group - Dark Section */
.wp-block-group.is-style-section-dark {
    background: var(--wp--preset--color--contrast, #111);
    color: var(--wp--preset--color--base, #fff);
}

/* Quote - Plain */
.wp-block-quote.is-style-plain {
    border: none;
    padding-left: 0;
}

/* Quote - Large */
.wp-block-quote.is-style-large {
    font-size: var(--wp--preset--font-size--large, 1.5rem);
}
```

---

## Phase 5: Pattern File Structure

### 5.1 Pattern Header Template

```php
<?php
/**
 * Title: [Pattern Title]
 * Slug: buddyx/[pattern-slug]
 * Categories: [buddyx-category]
 * Description: [Brief description of pattern]
 * Keywords: [searchable, keywords]
 * Viewport Width: 1200
 */
?>
<!-- Pattern HTML content -->
```

### 5.2 Example Pattern: CTA Join Community

**File:** `inc/Base_Support/patterns/cta-join-community.php`

```php
<?php
/**
 * Title: Join Community CTA
 * Slug: buddyx/cta-join-community
 * Categories: buddyx-cta, buddyx-general
 * Description: A call-to-action section inviting users to join the community.
 * Keywords: cta, join, community, signup, membership
 * Viewport Width: 1200
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"primary","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">

<!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e('Join Our Community', 'buddyx'); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e('Connect with like-minded people, share your experiences, and grow together.', 'buddyx'); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
<!-- wp:button {"backgroundColor":"base","textColor":"contrast"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button"><?php esc_html_e('Get Started Free', 'buddyx'); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
```

---

## Implementation Checklist

### Week 1-2: Foundation
- [ ] Update theme.json to version 3
- [ ] Add fluid spacing presets
- [ ] Expand color palette
- [ ] Register new pattern categories

### Week 3-4: Core Patterns
- [ ] Create 3 header patterns
- [ ] Create 5 CTA patterns
- [ ] Create 4 team patterns

### Week 5-6: Additional Patterns
- [ ] Create 3 testimonial patterns
- [ ] Create 3 services patterns
- [ ] Create 3 hidden/template patterns

### Week 7-8: Block Styles & Polish
- [ ] Register 8 block styles
- [ ] Create block styles CSS
- [ ] Test all patterns in editor
- [ ] Update documentation

---

## WordPress.org Compliance Notes

All additions are 100% compliant with WordPress.org theme guidelines:

- ✅ Block patterns are allowed (design-only)
- ✅ Block styles are allowed (CSS styling)
- ✅ theme.json settings are allowed
- ✅ No plugin functionality
- ✅ No upselling in patterns
- ✅ All text is translatable
- ✅ Uses WordPress core blocks only

---

## Files to Modify

| File | Changes |
|------|---------|
| `theme.json` | Version 3, spacing, colors |
| `inc/Base_Support/Component.php` | Pattern categories, block styles |
| `inc/Base_Support/patterns/*.php` | New pattern files (21 total) |
| `assets/css/blocks/block-styles.css` | Block style CSS |
| `style.css` | Version bump to 4.9.3 |

---

## References

- [Twenty Twenty-Five Theme](https://developer.wordpress.org/themes/)
- [Block Patterns Handbook](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/)
- [theme.json Reference](https://developer.wordpress.org/block-editor/how-to-guides/themes/global-settings-and-styles/)
- [WordPress Theme Guidelines](https://make.wordpress.org/themes/handbook/review/required/)
