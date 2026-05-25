# Frequently Asked Questions

Quick answers to the most-asked questions. For deeper topics, see [Troubleshooting](./troubleshooting.md) or [Glossary](./glossary.md). Still stuck? Email **support@wbcomdesigns.com**.

---

## About BuddyX

### Is BuddyX really free?

Yes. BuddyX is 100% free on WordPress.org. No required paid add-ons, no "lite" version trick. Every feature you see in the docs ships in the free theme.

[BuddyX Pro](https://wbcomdesigns.com/downloads/buddyx-theme/) (the premium theme, sold separately) adds extra features on top — 14 color presets, 7 typography presets, sign-in popup, per-page settings, dark customizer surface. Optional.

### Do I need BuddyPress to use BuddyX?

No. BuddyX works fine as a regular blog / portfolio / business theme without BuddyPress. The community features only activate when BuddyPress is installed. See [Choose Your Path](../getting-started/choose-your-path.md) for non-community use cases.

### What's the difference between BuddyX (free) and BuddyX Pro?

| Feature | Free | Pro |
|---|---|---|
| 8 style presets (palette) | Yes | Yes (14 color presets) |
| Typography presets | No | 7 typography presets |
| Dark mode + visitor toggle | Yes | Yes |
| Per-element dark color customization | No | 30+ `dark_*` customizer fields |
| Sign-in popup | No | Yes |
| Per-page settings | No | Yes |
| Dark customizer surface | No | Yes |
| BuddyPress integration | Yes (full) | Yes (full) |
| WooCommerce / FluentCart / SureCart | Yes | Yes |
| LearnDash / Dokan / WCFM | No special styling | Deep integration |
| Block patterns | Yes | More patterns |
| Google Fonts | Yes (1000+) | Yes (1000+) |
| Translation-ready | Yes | Yes |
| 4 blog layouts | Yes | Yes |

The free theme is enough for most use cases. Upgrade to Pro when you've outgrown the free customization surface.

### How is BuddyX different from BuddyBoss Theme?

Both are community-ready WordPress themes. BuddyBoss Theme is a paid theme (~$228/yr) bundled with the BuddyBoss Platform plugin (paid alternative to BuddyPress). BuddyX (free) works with both BuddyPress (free) and BuddyBoss Platform.

If you want a single all-in-one paid product, BuddyBoss is your option. If you prefer free + add-ons-only-when-needed, BuddyX (+ free BuddyPress + the Wbcom Designs in-house plugin catalog) is the path.

### Does BuddyX support Full Site Editing (FSE)?

BuddyX is a **classic (non-FSE) theme** with strong block-editor support for pages and posts. Block patterns, the block editor for content, theme.json for design tokens — all supported. But the header, footer, and templates are PHP-rendered (not block-rendered like FSE themes).

If you specifically need a block theme with the full Site Editor, BuddyX is not it. For block-based community work, the WordPress ecosystem doesn't yet have a strong FSE-native community theme. Most community sites continue using classic themes.

---

## Setup + Customization

### How long does first-time setup take?

About **45 minutes** if you have a logo + brand color ready. See [Quick Start](../getting-started/quick-start.md) — 9 mandatory steps.

### Where do I set my colors?

**Appearance → Customize → Site Skin**. See [Color Scheme](../skin-colors/color-scheme.md).

### Where do I set my fonts?

**Appearance → Customize → Typography**. See [Customize your colors and fonts](../recipes/customize-brand.md).

### Where do I upload my logo?

**Appearance → Customize → Site Identity → Logo**. See [Quick Start Step 2](../getting-started/quick-start.md#step-2--site-identity-logo-title-tagline-favicon-5-minutes).

### Can I change the layout per page?

The free theme uses **global** layout settings — your sidebar option, container width, etc. apply across all pages of a given type (all blog archives, all single posts, all default pages).

Per-page settings (the ability to override the global setting on a single page) is a **BuddyX Pro** feature.

### Can I use a child theme?

Yes. Standard WordPress child theme rules apply. Create a child theme with at least:
- `style.css` declaring `Template: buddyx`
- `functions.php` enqueuing parent + child styles

Most users don't need a child theme — BuddyX covers customization through the Customizer. Use a child theme only if you need to edit PHP templates or add complex custom CSS.

---

## BuddyPress + community

### Does BuddyX work with the latest BuddyPress?

Yes. BuddyX 5.1.0 is tested with BuddyPress 12.x and 14.x. The BuddyX team uses BuddyPress on our own sites and tests every major BuddyPress release.

### Does BuddyX support BuddyBoss Platform?

Yes. BuddyX's BuddyPress integration applies to both BuddyPress (free) and BuddyBoss Platform (paid). Use whichever fits your needs.

### Can I make my community members-only?

Yes, but you'll need a third-party privacy plugin. Options:
- **BP Private Site** (free) — basic redirect-to-login for community URLs
- **MemberPress / Paid Memberships Pro / Restrict Content Pro** — full membership platforms with paid tiers, content gating, recurring billing

BuddyX's theme features work with all of these.

---

## E-commerce

### Does BuddyX work with WooCommerce?

Yes. BuddyX includes full WooCommerce styling out of the box — shop page, single product, cart, checkout, account. Configure shop layout at **Customize → Site Sidebar Layout → WooCommerce Sidebar**.

### Does it support digital downloads / subscriptions?

WooCommerce + the right extensions handles digital downloads, subscriptions, etc. BuddyX styles the standard WooCommerce templates; specialized add-on UIs (like Subscriptions account pages) usually inherit WooCommerce's defaults and look fine.

For lighter-weight digital-goods alternatives, BuddyX also includes FluentCart and SureCart styling.

### What about marketplace plugins (Dokan, WCFM)?

The **free** BuddyX theme has basic compatibility — marketplace pages render correctly because they use WooCommerce templates underneath. For polished vendor-dashboard styling specifically tuned to Dokan / WCFM / MultiVendorX, [BuddyX Pro](https://wbcomdesigns.com/downloads/buddyx-theme/) adds deeper integration.

---

## Performance + SEO

### Is BuddyX fast?

Yes. BuddyX 5.1.0 has a modern asset pipeline: conditional CSS loading (only loads what each page needs), font preloading, minimal JavaScript, FOUC-free dark mode. Pair with a caching plugin for production-grade speed.

### Does BuddyX hurt SEO?

No. BuddyX outputs clean, semantic HTML with proper heading hierarchy, accessible markup, and mobile-responsive layouts. It works with any SEO plugin (Yoast, Rank Math, SEOPress, All in One SEO).

### Does BuddyX support Core Web Vitals optimization?

The theme defaults already score well on Core Web Vitals (LCP, FID/INP, CLS). For top-tier scores, add a caching plugin, optimize images (EWWW / Smush), and enable **Load Google Fonts locally** in **Customize → Site Performance**.

---

## Updates + Support

### How often is BuddyX updated?

Major releases (5.x → 5.y) ship 2–4 times a year. Minor releases (5.1.0 → 5.1.1) ship as needed for bug fixes — often within a week of a reported issue.

### How do I get support?

| Channel | Best for |
|---|---|
| [WordPress.org BuddyX forum](https://wordpress.org/support/theme/buddyx/) | Public Q&A, free, community-supported |
| [GitHub issues](https://github.com/vapvarun/buddyx/issues) | Bug reports, developer-level questions |
| **support@wbcomdesigns.com** | Direct email, response within 1 business day |

### How do I report a bug?

[GitHub issues](https://github.com/vapvarun/buddyx/issues) is best for bug reports — include:
- BuddyX version + WordPress version + PHP version
- Active plugins list
- Reproduction steps
- Screenshot / screen recording if visual

### How do I request a feature?

Open a [GitHub discussion](https://github.com/vapvarun/buddyx/discussions) or email **support@wbcomdesigns.com** with what you're trying to do. Feature requests with a clear use case (not just "add X") get traction faster.

### How do I follow BuddyX development?

- **GitHub repo**: [github.com/vapvarun/buddyx](https://github.com/vapvarun/buddyx) — watch for release notifications
- **Wbcom Designs blog**: [wbcomdesigns.com/blog/](https://wbcomdesigns.com/blog/) — release announcements
- **Email newsletter**: sign up at wbcomdesigns.com for major releases

---

## Licensing + commercial use

### Can I use BuddyX on a client site?

Yes. BuddyX is licensed GPL v2 or later — same as WordPress. You can install it on any site (yours, client, multisite), modify it, redistribute it. No license keys, no per-site limits.

### Can I sell sites built with BuddyX?

Yes. Building a site for a client using BuddyX is fully allowed. You can keep the theme on the client's site indefinitely.

### Can I use the BuddyX brand / logo / screenshots in my own marketing?

The BuddyX name and Wbcom Designs logo are trademarks. You may say "built with BuddyX" or "uses BuddyX theme" in your client work attribution. Don't claim BuddyX is your own product or use the Wbcom Designs logo on your own marketing as if endorsed.

---

## Plugin catalog

### What's the Wbcom Designs plugin catalog?

Wbcom Designs (the team behind BuddyX) builds in-house WordPress plugins, each with a free version and a pro version sold separately. They're designed to work cleanly with BuddyX:

| Plugin | What it adds |
|---|---|
| **Jetonomy** | Modern community surface — forums, Q&A, ideas, trust levels |
| **MediaVerse** | Native video upload + hosting + player |
| **WB Gamification** | Points, ranks, badges, leaderboards |
| **WP Career Board** | Job board + resume management |
| **WP Ads Manager** | Display ad zones, sponsorship slots, ad rotation |
| **Listora** *(coming soon)* | Directory builder |
| **Learnomy** *(coming soon)* | First-party LMS |

Browse the full catalog at [wbcomdesigns.com/downloads/](https://wbcomdesigns.com/downloads/).

### Are these plugins required for BuddyX?

No. BuddyX is a complete theme on its own. The plugin catalog gives you optional features when your site grows beyond what the theme alone covers.

### Do I have to use BuddyX Pro to use these plugins?

No. The plugins work with the free BuddyX, with BuddyX Pro, and with most other WordPress themes. They're independent products.

---

## Related

- [Troubleshooting](./troubleshooting.md) — fixes for common issues
- [Glossary](./glossary.md) — plain-English term definitions
- [Quick Start](../getting-started/quick-start.md) — first-time setup walkthrough
- [Choose Your Path](../getting-started/choose-your-path.md) — pick your site type
