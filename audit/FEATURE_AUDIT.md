# BuddyX Feature Audit

Generated 2026-06-05 from audit/manifest.json (wp-plugin-onboard). Do not hand-edit counts; re-run the skill.

## Overview

| Field | Value |
|---|---|
| Name | BuddyX (free) |
| Version | 5.1.0 |
| Type | Classic WordPress theme |
| Namespace | `BuddyX\Buddyx` |
| Main file | functions.php |
| Text domain | buddyx |
| Pair | buddyx-pro (free role; 12 hooks consumed by Pro) |
| PHP files | 219 |
| Positioning | General-purpose theme, not a BuddyPress-only theme |

### 5.1.0 architecture

| Pillar | What it is |
|---|---|
| Customizer_Framework | Config-driven customizer engine. `Panel::add` / `Section::add` register containers; `Field::add` materializes every setting via `WP_Customize_Manager::add_setting` + `add_control` (inc/Customizer_Framework/Field.php:115). Field config arrays live in inc/Customizer_Settings/Fields/*.php and inc/compatibility/*/*.php. switch/checkbox defaults normalize to 0/1 via `sanitize_bool_int`. Replaces the removed Kirki dependency. |
| `--bx-color-*` token system | theme_mod -> `Tokens::collect()` -> inline `<style>:root { --bx-color-*: ... }` on the buddyx-global handle (inc/Tokens/Component.php). Dark overrides ride `:root[data-bx-mode="dark"]`. Semantic token names organized by role (bg, button, link, header, footer, copyright, headings). |
| Color-mode toggle | Visitor-facing light / dark / auto switch. FOUC-free: a tiny inline bootstrap script in `<head>` (priority 1) sets `<html data-bx-mode>` before any paint. |
| Style variations | 8 file-based variations (cool, dark, editorial, minimal, monochrome, pastel, vibrant, warm) selected via the `site_style_variation` customizer setting. |
| Starter content | `add_theme_support('starter-content', ...)` with posts, nav_menus, widgets, options, theme_mods (inc/Starter_Content/Component.php:73). |
| theme.json | Present (v3-style); palette presets referenced by block patterns. |

## Customizer

Framework class: `BuddyX\Buddyx\Customizer_Framework\Component` (config-driven). 18 sections, 128 settings. Legacy inc/Customizer/Component.php is active but only sets postMessage transport on core blogname/blogdescription/header_textcolor and adds an empty `theme_options` section (owns no settings). inc/EZ_Customizer is commented out at inc/Theme.php:156 (inactive).

| Section ID | Title | Panel | Settings | Controls |
|---|---|---|---|---|
| body_typography_section | Body | typography_panel | 1 | Body font family/size/weight |
| headings_typography_section | Headings | typography_panel | 6 | H1-H6 typography arrays |
| menu_typography_section | Menu | typography_panel | 2 | Menu + submenu typography |
| site_title_typography_section | Site Title | typography_panel | 2 | Site title + tagline typography |
| page_mapping | Page Mapping | site_layout_panel | 3 | 404 / login / registration page assignment |
| site_layout | Site Layout | site_layout_panel | 5 | Container width, wide/boxed layout, button/form/global border radius |
| site_loader | Site Loader | site_layout_panel | 7 | Preloader on/off, type, bg/fg color, logo, speed, text |
| site_header_section | Site Header | top-level | 5 | Cart, login/register links, search, sticky header |
| site_header_primary_section | (UNREGISTERED - referenced only) | none | 2 | `site_header_enable_cart` declared by both FluentCart and SureCart compat files; section never registered (orphan) |
| site_sub_header_section | Site Sub Header | top-level | 4 | Breadcrumbs, sub-header bg toggle/image, sub-header typography |
| site_skin_section | Site Skin | top-level | 54 | Full color palette, color mode + toggle position/visibility, style variation, button/link/header/footer/copyright colors, heading colors |
| site_blog_section | Site Blog | top-level | 12 | Blog layout, grid columns, image position, masonry, tags, edit link, overlay color, single-post width/title |
| site_sidebar_layout | Site Sidebar | top-level | 9 | Per-context sidebar position (default, single post, BP members/groups/activity, bbPress, WooCommerce, FluentCart) + sticky sidebar |
| site_wp_login_logo | Logo | site_wp_login | 9 | Custom login page enable, logo image + dimensions, title/url |
| site_buddypress_general_section | General Setting | site_buddypress_panel (BP-only) | 1 | BuddyPress avatar style (round/square) |
| site_footer_section | Footer Section | site_footer_panel | 2 | Footer background image + bg toggle |
| site_copyright_section | Copyright Section | site_footer_panel | 1 | Copyright text |
| site_performance_section | Site Performance | top-level | 3 | Load Google Fonts locally, preload local font, flush-local-font button |

Notes:
- Array-offset keys (`h1_typography_option[color]`, `typography_option[color]`, `site_sub_header_typography[color]`) are color sub-settings of parent typography arrays, registered as distinct WP settings by Skin_Fields.php and counted individually in the 128.
- theme_mods are namespaced under the `buddyx` stylesheet automatically; no `set_theme_mod` with literal `buddyx_` option keys.

## REST API

| Route | Methods | Handler | Permission | Purpose |
|---|---|---|---|---|
| `/buddyx/v1/settings` | POST/PUT/PATCH | `Options\Component::update_settings` | `manage_options` via `settings_permissions_check` | Persists arbitrary theme settings into the `buddyx_theme_settings` option, sanitized per-key (email_option/url_option/text). Route inc/Options/Component.php:118, callback :136, permission :191. |

## AJAX handlers

| Action | Handler | Nonce | Capability | Purpose |
|---|---|---|---|---|
| wp_ajax_buddyx_lms_toggle_theme_color | `Accessibility\Component::toggle_theme_color` | buddyx_toggle_theme_color (`wp_verify_nonce` on POST `nonce`) | none (logged-in only, nonce only) | LearnDash dark/light toggle; sets HttpOnly cookie `bxtheme`. Registered only when SFWD_LMS exists. No nopriv. inc/Accessibility/Component.php:43 (hook), :93 (handler). |
| wp_ajax_buddyx_regenerate_fonts_folder | `buddyx_regenerate_fonts_folder` (global fn) | buddyx_regenerate_fonts (`check_ajax_referer` on POST `nonce`) | `edit_theme_options` (checked before nonce) | Flush/regenerate locally cached Google Fonts folder when `buddyx_font_url` is set. No nopriv. inc/Webfont/class-buddyx-webfont-loader.php:771 (hook), :751 (handler). |

## Admin pages

| Title | Slug | Parent | Capability | Status |
|---|---|---|---|---|
| Getting Started | buddyx-welcome | themes.php (Appearance) | `edit_theme_options` | Active. Hooked on admin_menu (inc/Welcome/Component.php:35). after_switch_theme redirects here. 3-tab screen: Dashboard / Get BuddyX Pro / Community Addons. |
| BuddyX Settings | buddyx-settings | top-level (add_menu_page) | `manage_options` | DEAD. `add_admin_menu()` defined (inc/Options/Component.php:98) but its admin_menu hook is COMMENTED OUT at inc/Options/Component.php:42 - not registered at runtime. |

## Options owned

| Option | Type | Controls |
|---|---|---|
| buddyx_theme_settings | array | Generic key/value bag written by REST `/settings`. Sanitized per-key. No reader found in scanned scope. inc/Options/Component.php:145. |
| buddyx_font_url | string (URL) | Stored Google Fonts stylesheet URL; signals a local font cache exists; gates folder flush in AJAX + on customize_save_after. |
| buddyx_fluentcart_defaults_set | bool/flag | One-time guard so FluentCart compat default theme_mods seed only once. |
| buddyx_surecart_defaults_set | bool/flag | One-time guard so SureCart compat default theme_mods seed only once. |

## Block patterns (27, file-based)

Registered via WP 6.0+ file-based auto-discovery from `/patterns/`. There are ZERO `register_block_pattern()` calls.

| Category | Patterns |
|---|---|
| buddyx-about | about-founder, about-story, team-grid |
| buddyx-cta | cta-fullbleed, cta-newsletter, general-banner-light |
| buddyx-features | features-alternating, services-grid, steps |
| buddyx-footer | footer-central, footer-default-mega, footer-mega, footer-simple, footer-small |
| buddyx-hero | hero-image-led, hero-split-screen, hero-typography-led |
| buddyx-pricing-faq | general-faq, general-pricing |
| buddyx-query | query-cover-featured, query-cover-grid, query-grid-excerpt, query-listbig, query-simple-list |
| buddyx-social-proof | social-proof-logos, social-proof-stats, social-proof-testimonials |

8 pattern categories registered in inc/Base_Support/Component.php (loop at line 222): buddyx-about, buddyx-cta, buddyx-features, buddyx-footer, buddyx-hero, buddyx-pricing-faq, buddyx-query, buddyx-social-proof. Patterns also tag core categories (about, banner, call-to-action, featured, footer, header, posts, services, team, testimonials, text).

## Sidebars / widget areas (20)

| ID | Name | Condition |
|---|---|---|
| sidebar-right | Right Sidebar | always |
| sidebar-left | Left Sidebar | always |
| buddypress-sidebar-left | Community Left Sidebar | bp_is_active && !Youzify |
| buddypress-sidebar-right | Activity Directory Right Sidebar | bp_is_active && !Youzify |
| buddypress-members-sidebar-right | Members Directory Right Sidebar | bp_is_active && !Youzify |
| buddypress-groups-sidebar-right | Groups Directory Right Sidebar | bp_is_active && !Youzify |
| single_member | Members Single Profile Sidebar | bp_is_active && !Youzify |
| single_member_activity | Members Single User Activity | bp_is_active && !Youzify |
| single_group | Groups Single Group Sidebar | bp_is_active && !Youzify |
| single_group_activity | Groups Single Group Activity | bp_is_active && !Youzify |
| bbpress-sidebar-left | bbPress Left Sidebar | is_bbpress exists |
| bbpress-sidebar-right | bbPress Right Sidebar | is_bbpress exists |
| woocommerce-sidebar-left | WooCommerce Left Sidebar | class_exists(WooCommerce) |
| woocommerce-sidebar-right | WooCommerce Right Sidebar | class_exists(WooCommerce) |
| fluentcart-sidebar-left | Single Product Left Sidebar | FLUENTCART_PLUGIN_FILE_PATH |
| fluentcart-sidebar-right | Single Product Right Sidebar | FLUENTCART_PLUGIN_FILE_PATH |
| footer-1 | Footer 1 | always |
| footer-2 | Footer 2 | always |
| footer-3 | Footer 3 | always |
| footer-4 | Footer 4 | always |

All registered in inc/Sidebars/Component.php (lines 108-347).

### Widgets

| Class | Name | Purpose |
|---|---|---|
| BP_Buddyx_Profile_Completion_Widget | (BuddyPress) Profile Completion | Logged-in user profile completion progress (BuddyPress/BuddyBoss). inc/widgets/bp-profile-completion-widget.php:13; register_widget :501. |

## Nav menus

| Location | Description | Where |
|---|---|---|
| primary | Primary | inc/Nav_Menus/Component.php:69 |
| user_menu | User Menu | inc/Nav_Menus/Component.php:74 |

## Frontend assets

Data-driven manifest pipeline. CSS via `get_css_files()` (inc/Styles/Component.php:119+); JS via `get_js_files()` (inc/Scripts/Component.php:104+). Each registered file carries a `preload_callback`; preload is enabled unless AMP (`preloading_styles_enabled()`, filterable via `buddyx_preloading_styles_enabled`). When preloading is disabled, every registered sheet is enqueued directly so nothing drops.

CSS (selected; 30+ handles): buddyx-global (always), buddyx-tokens-applied (dep buddyx-global), buddyx-comments (preload when singular+comments open), buddyx-content (preload_callback `__return_false`), buddyx-front-page (preload on front-page template), buddyx-site-loader, buddyx-load-fontawesome, buddyx-slick (non-critical, media=print/onload), buddyx-rtl (is_rtl), buddyx-dark-mode (always), plus per-integration sheets (buddypress, platform, bbpress, woocommerce, learndash, learnpress, lifterlms, surecart, fluentcart, dokan, eventscalendar, multivendorx, youzify, wpjobmanager, wc-vendor, amp).

JS (selected): buddyx-custom (localized as `buddyx_ajax` with nonce buddyx_toggle_theme_color + ajaxurl), buddyx-navigation (async, !AMP, localized buddyxScreenReaderText), buddyx-color-mode-toggle (footer, priority 30, when toggle enabled), superfish, isotope, fitvids, sticky-kit, jquery-cookie, slick; per-integration buddyx-buddypress, buddyx-learndash, buddyx-gamipress. Admin: buddyx-admin-script (welcome page), buddyx-customizer-script (customize.php), Customizer_Framework `-controls` / `-preview` bundles, dev-only livereload.

Filters: `buddyx_css_files` (inc/Styles/Component.php:422), `buddyx_js_files` (inc/Scripts/Component.php:274).

## WP-CLI

Command `wp rig` (class `BuddyX_Command`, registered wp-cli/buddyx-commands.php:596, gated by `defined('WP_CLI')`).

| Subcommand | Where | Purpose |
|---|---|---|
| rig dev_setup | :27 | Dev environment setup |
| rig menu_export | :120 | Export a nav menu to JSON |
| rig menu_import | :218 | Import a nav menu from JSON |
| rig menu_list | :322 | List nav menus |
| rig fake_menu_items | :385 | Generate fake menu items |
| rig fonts_download | :576 | Download Google Fonts locally |

## Cron

| Hook | Interval | Handler | Condition |
|---|---|---|---|
| buddyx_delete_fonts_folder | monthly (CLEANUP_FREQUENCY) | `BuddyX_WebFont_Loader::buddyx_delete_fonts_folder()` (deletes local webfont folder + buddyx_font_url option) | single-site or main site of multisite; not while wp_installing. Schedule inc/Webfont/class-buddyx-webfont-loader.php:634; handler hook :128. |

## Integrations (25)

| Plugin | Detection | Files (selected) |
|---|---|---|
| BuddyPress | class_exists('BuddyPress') / bp_is_active() | inc/compatibility/buddypress/, inc/Sidebars, inc/Styles, buddypress.php, bb-buddypress/ |
| BuddyBoss Platform | isset(buddypress()->buddyboss) / bb_* | inc/Styles/Component.php:134 (platform.min.css), inc/Sidebars:510 |
| bbPress | function_exists('is_bbpress') | bbpress.php, inc/Sidebars:232, inc/Styles:138 |
| WooCommerce | class_exists('WooCommerce') / WC() | woocommerce/, inc/compatibility/woocommerce/, inc/Sidebars:258, inc/Styles:158 |
| Youzify | class_exists('Youzify') | inc/Styles:163, inc/Sidebars:133 (suppresses BP sidebars) |
| Dokan | class_exists('WeDevs_Dokan') | inc/Styles:279, inc/Sidebars:505 |
| LearnDash | class_exists('SFWD_LMS') | inc/Styles:292, inc/Scripts:255, inc/Accessibility:43 (toggle AJAX) |
| LearnPress | class_exists('LearnPress') | inc/Styles:148 |
| LifterLMS | class_exists('LifterLMS') | inc/Styles:153, buddyx_llms_* helpers |
| Elementor | elementor_theme_do_location / register_locations | inc/extra.php:416-422 (header/footer locations), header.php, footer.php, 404.php |
| The Events Calendar | class_exists('Tribe__Events__Main') | inc/Styles:266 |
| WP Job Manager | class_exists('WP_Job_Manager') | inc/Styles:169 |
| WC Vendors | class_exists('WC_Vendors') | inc/Styles:144 |
| MultiVendorX | class_exists('MVX') | inc/Styles:174 |
| GamiPress | class_exists('GamiPress') | inc/Scripts:233, buddyx_profile_achievements |
| SureCart | defined('SURECART_PLUGIN_FILE') | inc/compatibility/surecart/, inc/Styles:194 |
| FluentCart | defined('FLUENTCART_PLUGIN_FILE_PATH') | inc/compatibility/fluentcart/, inc/Styles:200, inc/Sidebars:285, single-fluent-products.php |
| rtMedia | rtmedia_init / class_exists('RTMedia') | inc/compatibility/rtmedia/, rtmedia/main.php |
| MediaPress | class_exists('MediaPress') | inc/Sidebars:540 (body class) |
| BP Group Email Subscription | class_exists('BPGES_Subscription') | inc/Sidebars:545 (body class) |
| BuddyPress Docs | bp_docs_is_docs_component | inc/Sidebars:450 |
| AMP | buddyx()->is_amp() / is_amp_endpoint | inc/AMP/Component.php, inc/Styles:186, inc/Accessibility |
| PWA / Service Workers | wp_service_worker_error_message_placeholder + add_theme_support('service_worker') | inc/PWA/Component.php, offline.php, 500.php |
| Jetpack | add_theme_support module flags | inc/Jetpack/Component.php |
| Yoast SEO | function_exists('yoast_breadcrumb') | inc/class-buddyx-breadcrumbs.php |

## Theme features

| Feature | Detail |
|---|---|
| Style variations (8) | cool, dark, editorial, minimal, monochrome, pastel, vibrant, warm. Selected by `site_style_variation`; data loaded via `Tokens::load_variation_data()`. A dark-scheme variation drives the color-mode bootstrap default to `dark`. |
| theme.json | Present; palette presets referenced by block patterns. |
| Starter content | posts, nav_menus, widgets, options, theme_mods (inc/Starter_Content/Component.php:73-89). |

## Known issues (wppqa baseline 2026-06-05)

| Issue | Location | Notes |
|---|---|---|
| Nonce check without capability check | inc/Accessibility/Component.php:95 | Likely intentional - frontend color preference toggle for logged-in users (writes only current-user state / cookie). Document and suppress if confirmed. |
| Nonce check without capability check | inc/extra.php:887 | Needs triage - confirm what the handler mutates; pair with `current_user_can()` if it touches anything beyond the current user's own data. |
| Orphan section reference | site_header_primary_section | `site_header_enable_cart` (FluentCart + SureCart compat) targets this section id, but it is never registered via Section::add. Field is orphaned at the section level. |
| Dead admin menu | inc/Options/Component.php:42/98 | `buddyx-settings` add_menu_page never hooked (admin_menu commented out). Only buddyx-welcome renders. |
| Breakpoint proliferation (medium) | theme-wide | 36 distinct CSS breakpoints (target 3). Pre-existing debt, not a release blocker. |

### Empty categories

| Category | Status |
|---|---|
| Custom DB tables | _None_ |
| Custom post types | _None_ |
| Taxonomies | _None_ |
| Shortcodes | _None_ |
| Custom Gutenberg blocks | _None_ (the single block.json is a scaffold template at scripts/templates/block/, never registered) |
| Custom capabilities | _None_ (uses core caps only) |
