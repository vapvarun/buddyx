# WP Starter Content — fresh-install demo, WP.org-compliant

**Status:** queued for 5.1.0
**Why now:** Last unchecked WP.org-readiness item. Pattern library (27 patterns), style variations (8), token system, dark mode all shipped. Starter Content is the official WordPress feature that lets themes ship a curated starter site experience without violating directory rules (no auto-imported demo content, no third-party plugin dependencies, no hardcoded posts).

---

## 1. What it does (in one paragraph)

`add_theme_support('starter-content', $config)` registers a config array. WP shows the contents in the customizer when a customer activates the theme on a "fresh" site (`fresh_site` option = 1). The customer previews a curated home page + supporting pages + widgets + menus + theme_mods. Nothing is committed until they click **Publish**. After publish, the site has demo pages they can edit. If they leave/cancel, nothing is saved. **No back-end content is ever forced on the customer.**

## 2. Why it's WP.org-compliant

- It's a core WordPress API (`get_theme_starter_content()`) — themes are explicitly encouraged to use it.
- No bundled plugins. No external API calls. No TGM forced installs.
- Twenty Twenty-Four, TwentyTwentyFour, Storefront, Astra Free all ship starter content.
- Only fires on fresh sites — existing customers updating from 5.0.x to 5.1.0 never see this.
- Customer in full control — preview-first, opt-in publish.

## 3. Scope

### What we ship

**Pages** (7 — matches the existing front-page.php hero we currently use):
1. Home (front page) — composed from `hero-typography-led` + `social-proof-logos` + `features-alternating` + `social-proof-stats` + `services-grid` + `social-proof-testimonials` + `general-pricing` + `general-faq` + `cta-fullbleed`
2. About — `hero-split-screen` + `about-story` + `about-founder` + `team-grid`
3. Services — `services-grid` + `features-alternating` + `cta-newsletter`
4. Pricing — `general-pricing` + `general-faq`
5. Journal (blog) — empty page, set as `page_for_posts`
6. FAQ — `general-faq` + `cta-newsletter`
7. Contact — single column with heading + paragraph + button (no pattern needed)

Plus the existing demo "Hello world!" post.

**Widgets** (sidebar):
- `search` (core)
- `recent-posts` (core)
- `recent-comments` (core)

**Menus** (primary nav):
- Home → `{{home}}`
- About → `{{about}}`
- Services → `{{services}}`
- Pricing → `{{pricing}}`
- Journal → `{{blog}}`
- FAQ → `{{faq}}`
- Contact → `{{contact}}`

**Theme_mods** to seed:
- `show_on_front` = `page`
- `page_on_front` = `{{home}}`
- `page_for_posts` = `{{blog}}`
- `site_color_mode` = `auto` (matches OS — most respectful default)
- `site_color_mode_toggle_show` = `on` (showcases 5.1.0 dark mode)

**No attachments** in v1 — patterns work without images, and bundling stock images bloats the theme zip beyond WP.org's 10MB limit.

### Out of scope

- Image attachments (defer to v2 if customer feedback asks)
- BuddyPress-specific demo (BP isn't in WP.org review scope; admins set up BP separately)
- WooCommerce demo (same reason)
- Custom post types (BuddyX doesn't register any)
- Multilingual variants (en-US only; WP handles i18n via __())

## 4. Technical implementation

### File structure

```
inc/Starter_Content/
  Component.php          — new: registers add_theme_support call
                          + builds the config array from pattern composition
```

### Component skeleton

```php
namespace BuddyX\Buddyx\Starter_Content;

class Component implements Component_Interface {
    public function get_slug(): string {
        return 'starter_content';
    }

    public function initialize(): void {
        add_action( 'after_setup_theme', array( $this, 'register' ), 12 );
    }

    public function register(): void {
        add_theme_support( 'starter-content', $this->config() );
    }

    protected function config(): array {
        return array(
            'posts'         => $this->posts(),
            'nav_menus'     => $this->nav_menus(),
            'widgets'       => $this->widgets(),
            'options'       => $this->options(),
            'theme_mods'    => $this->theme_mods(),
        );
    }

    protected function posts(): array {
        return array(
            'home'     => array( /* … */ ),
            'about'    => array( /* … */ ),
            'services' => array( /* … */ ),
            // …
        );
    }

    // …
}
```

### Pattern composition

Pages compose patterns via block markup. WP's starter content accepts `post_content` as block markup (the same string you'd save from the editor). For each page we either:

- **Inline the pattern slugs:** `<!-- wp:pattern {"slug":"buddyx/hero-typography-led"} /-->` (WP 6.5+, falls back to nothing on older)
- **Inline the pattern HTML:** include the raw block markup directly so it works on WP 5.8+ (our minimum)

Going with **option B (inline raw markup)** for compatibility. The composition functions (`build_home_content()`, `build_about_content()`) read each pattern's PHP file via `do_blocks()` once at registration and concatenate.

### Wire-up

```php
// inc/Theme.php
$components[] = new Starter_Content\Component();
```

## 5. Edge cases

| Case | Handling |
|---|---|
| Customer already has content (`fresh_site = 0`) | WP automatically skips — starter content only loads on `is_customize_preview()` for fresh sites |
| Customer cancels customizer | Nothing saved — WP rolls back the preview |
| Customer enables only some pages then publishes | WP commits the published subset; un-touched starter content never persists |
| Pattern file missing on activation (e.g. customer deleted patterns) | `do_blocks()` returns empty string, page becomes blank but doesn't crash |
| Customer runs WP < 5.8 | Starter content API doesn't exist; `add_theme_support` is a no-op. Theme still works, just no demo. |
| Customer updates theme (5.0.3 → 5.1.0) | `fresh_site` is 0 (they have content), so starter content skipped. **No data loss, no surprise.** |
| Customer's database has special characters in title (multilingual install) | `wp_insert_post` handles encoding via core sanitization |
| Page slug conflict (customer has page with slug `home`) | WP appends `-2` suffix automatically |
| Plugin (e.g., WPML) hooks `wp_insert_post` | Plugin's hook fires, content gets translated/duplicated as plugin chooses. Acceptable. |

## 6. WP.org review checklist

- [x] No external HTTP calls during starter content setup
- [x] No bundled images >100KB (we ship zero images in v1)
- [x] No third-party fonts (theme already self-hosts)
- [x] No hardcoded URLs in pattern markup (use `{{ wp_make_link_relative }}` / placeholder tokens)
- [x] All copy is escapable + translation-ready (`__()` wrappers)
- [x] No JavaScript injected during content setup
- [x] Customer can opt out by not clicking "Publish"
- [x] Existing customers (`fresh_site = 0`) don't see preview
- [x] `add_theme_support` registered after `after_setup_theme` priority 12 (matches WP convention)

## 7. Acceptance criteria

A reviewer can verify:

1. ✅ Fresh WP install + activate BuddyX → customizer shows "Live Preview" with 7 pages
2. ✅ Each page has its hero + sections rendered via patterns (not Lorem ipsum)
3. ✅ Sidebar shows search + recent posts + recent comments widgets
4. ✅ Primary menu has 7 items in correct order
5. ✅ "Publish" button creates the pages, sets `page_on_front`, sets `page_for_posts`, sets the menu
6. ✅ "Cancel" leaves the site empty (no posts/menus committed)
7. ✅ Existing site (with content) — activating theme does NOT show starter preview
8. ✅ Re-activating theme on already-published site does NOT re-create pages
9. ✅ Customizer color-mode toggle visible in starter preview (showcases the new 5.1.0 feature)
10. ✅ No PHP errors, no JS errors during preview or commit
11. ✅ Final zip with starter content stays under 10MB (WP.org limit)

## 8. Effort estimate

- Component scaffold: 30 min
- Build 7 page compositions from patterns: 2 hrs
- Widget + menu + theme_mod wiring: 30 min
- Wire into Theme.php + verify boot: 15 min
- Test on fresh WP install + customizer flow: 1 hr
- Edge case verification: 30 min
- **Total: ~5 hrs / single workday**

## 9. Release plan

Lands in **5.1.0** (this release). Reasoning:
- This is the last unchecked WP.org compliance item.
- Pattern library (the prerequisite) is already shipped.
- Starter content is opt-in — zero risk to existing customer sites (only fires on `fresh_site = 1`).
- Marketing claim: "WordPress.org Theme Directory ready" becomes defensible.

## 10. Files to create / modify

```
A inc/Starter_Content/Component.php          ~250 lines
M inc/Theme.php                              +1 line (component registration)
A plans/2026-05-07-wp-starter-content.md     this doc
```

Net add: ~250 LOC, 1 new file, 1 modified file, no asset changes (we reuse existing patterns).

## 11. Decision log

- **Why no images?** WP.org zip limit is 10MB. Theme is currently 1.2GB total but the production zip is ~1.9MB. Adding 5-10 hero images at 200KB each = 2MB extra. Patterns work without images (typography-led design). Defer to v2.
- **Why ship now vs 5.2?** Starter content is the last WP.org checklist item. Holding it adds zero release-quality value but defers a marketing claim.
- **Why inline pattern markup vs slug refs?** Slug refs need WP 6.5+ block-pattern blocks. We support 5.8+. Inline markup works everywhere.
- **Why `auto` color mode in starter content?** Showcases the 5.1.0 dark-mode toggle feature without forcing dark on every preview viewer. Respects OS preference. Visitor toggle still overrides.
