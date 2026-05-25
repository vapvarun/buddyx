# Glossary

Plain-English definitions of WordPress, BuddyX, and related terms you'll encounter in these docs. Listed alphabetically — use Cmd+F (Mac) / Ctrl+F (Windows) to find a term quickly.

If a term isn't defined here and isn't obvious from context, email **support@wbcomdesigns.com** and we'll add it.

---

## A

**Activity Stream** *(BuddyPress)* — The shared feed of recent posts, replies, comments, and updates from all members of your community. Similar to Twitter's home timeline or Facebook's news feed. Available when BuddyPress is active.

**Admin** *(WordPress user role)* — A user with full control over your site. Can install plugins, change themes, edit content, manage other users. The first user you create when installing WordPress is an Admin.

**Akismet** — A free spam-filtering plugin built into WordPress. Catches spam comments and registrations. Needs a free API key (sign up at akismet.com) to activate.

**Auto Mode** *(BuddyX Color Mode)* — A setting where your site automatically displays in light or dark colors based on the visitor's device preference. Visitors with Mac/Windows/iOS/Android set to "dark mode" see your site dark; everyone else sees it light. See [Dark Mode](../skin-colors/dark-mode.md).

---

## B

**bbPress** — A free WordPress plugin that adds discussion forums. Used for traditional forum-style sites where members create topics and reply to each other. Often paired with BuddyPress for full community sites.

**Block** *(WordPress)* — The building unit of the modern WordPress editor. Each paragraph, image, heading, button, or video is a "block" you can move around. Replaced the older "classic editor" in WordPress 5.0.

**Block Editor** *(WordPress)* — The modern editor used when you create or edit a page/post. Also called "Gutenberg" (the project codename). Lets you drag-and-drop blocks to build pages. The default WordPress experience since 2018.

**Block Pattern** *(WordPress)* — A pre-designed group of blocks you can insert with one click. BuddyX ships several patterns for hero sections, features grids, and call-to-action sections. Access via the block editor: **Patterns → BuddyX**.

**Brand color** — A specific color (usually a "hex code" like `#FF5733`) that represents your company or project. Used consistently across your site for buttons, links, accents. Set via **Customize → Site Skin → Site Primary Color**.

**BuddyPress** — A free, open-source WordPress plugin that adds community features: user registration, member profiles, activity feeds, groups, private messaging. The most popular choice for community sites on WordPress. Free at [buddypress.org](https://buddypress.org). BuddyX is built community-first to work with it.

**BuddyX Pro** — The paid premium version of this theme. Adds 14 color presets, 7 typography presets, sign-in popup, per-page settings, and dark customizer surface. Sold separately at [wbcomdesigns.com](https://wbcomdesigns.com/downloads/buddyx-theme/). The free BuddyX is a complete theme on its own — Pro is optional.

---

## C

**Cache** — Saved copies of web pages or data, kept to avoid recomputing them. Browsers cache pages so revisits load faster. WordPress can use page caching (via plugins like WP Rocket, W3 Total Cache, LiteSpeed Cache) to serve pages without running PHP each time. After making changes, you may need to clear (or "purge") your cache to see updates.

**Capability** *(WordPress)* — A specific permission, like "can publish posts" or "can install plugins." User roles (Admin / Editor / Author / Subscriber) are bundles of capabilities.

**Category** *(WordPress)* — A way to group blog posts by broad topic (e.g. "News", "Tutorials"). One post can belong to multiple categories. Categories typically appear in navigation. Different from **Tags** which are finer-grained keywords.

**CDN (Content Delivery Network)** — A network of servers worldwide that store copies of your site's files (images, CSS, JS). When a visitor in Australia loads your site, the CDN serves files from a nearby server instead of your origin server — faster page loads. Common CDNs: Cloudflare, Bunny, KeyCDN.

**Child Theme** — A custom version of a theme that inherits from a "parent" theme (BuddyX, in this case). Lets you customize without losing your changes when the parent updates. Recommended only if you plan to edit PHP or CSS files directly.

**Color Mode** *(BuddyX 5.1.0)* — One of three settings for how your site displays: Light, Dark, or Auto. Light always uses bright colors; Dark always uses dark; Auto matches the visitor's device. See [Dark Mode](../skin-colors/dark-mode.md).

**Cookie** *(web browser)* — A small piece of data your site stores in the visitor's browser. BuddyX uses cookies (and localStorage) to remember a visitor's color-mode choice across page loads. Subject to consent laws in some jurisdictions (GDPR, CCPA).

**Custom Post Type (CPT)** *(WordPress)* — A custom kind of content beyond the built-in Posts and Pages. Example: WooCommerce adds a "Product" custom post type.

**Customizer (WordPress Customizer)** — The visual settings panel at **Appearance → Customize** in your WordPress admin. Lets you change colors, fonts, header layout, etc. with a live preview on the right. The main place you'll spend time configuring BuddyX. Changes don't go live until you click **Publish**.

**Customizer Framework** *(BuddyX 5.1.0)* — The in-theme framework that powers BuddyX's Customizer controls. Pre-5.1.0, BuddyX used the third-party Kirki plugin; 5.1.0 ships its own framework, so no extra plugin is needed.

---

## D

**Dark Mode** — A version of your site that uses dark backgrounds + light text (instead of the traditional light backgrounds + dark text). Easier on the eyes in low light. BuddyX includes built-in dark mode + a visitor toggle. See [Dark Mode](../skin-colors/dark-mode.md).

**Dashboard** *(WordPress)* — The back-end admin area you see after logging in to your site. The URL is `yoursite.com/wp-admin/`. Distinct from the "front-end" which is what visitors see.

**Database** *(WordPress)* — Where WordPress stores all your content (posts, pages, settings, comments, users). Usually MySQL or MariaDB. You don't need to touch the database directly for normal use.

**Demo Content / Starter Content** — Pre-built example pages and Customizer values that WordPress shows on a fresh install. BuddyX ships starter content so a brand-new install previews like a real site before you configure anything. See the [Match the demo recipe → Step 3](../recipes/match-the-demo.md#step-3--load-wp-starter-content-5-minutes) for how to trigger it.

---

## E

**Editor** *(WordPress user role)* — A user who can write, edit, and publish posts and pages — including other users' content. One step below Admin (can't install plugins or change themes).

**Excerpt** *(WordPress)* — A short summary of a blog post used in archive listings (the page that lists multiple posts). Either WordPress auto-generates it from the post's opening, or you write a custom one.

---

## F

**Favicon / Site Icon** — The small icon shown next to your site title in browser tabs, bookmarks, and mobile home-screen shortcuts. Must be a 512×512 square image. Set in **Appearance → Customize → Site Identity**.

**Featured Image** *(WordPress)* — The main image attached to a blog post. Shown in archive listings, social shares (Facebook/Twitter preview), and at the top of single-post pages. Set in the right-side panel when editing a post.

**Footer** — The bottom section of every page, usually holding copyright text, site links, social icons, and widgets like recent posts or contact info. Configure via **Customize → Site Footer** and **Appearance → Widgets**.

**Forum** *(community feature)* — A traditional discussion area where members create topics and others reply. Usually organized by category. WordPress supports forums via plugins like bbPress (free) or Jetonomy (from Wbcom Designs, free + pro version sold separately).

**FOUC (Flash of Unstyled Content)** — The brief flash visitors see when a page loads in the "wrong" colors (e.g., light then snaps to dark). BuddyX 5.1.0 specifically prevents this — dark-mode visitors see dark from the very first paint, no flash. See [Dark Mode](../skin-colors/dark-mode.md).

**Front-end** — The public side of your site that visitors see. Opposite of "back-end" / "dashboard" (the admin side at `/wp-admin/`).

---

## G

**GDPR** — The European Union's General Data Protection Regulation. Requires sites to disclose what visitor data they collect, get consent for tracking, and let visitors request data deletion. If your site serves any EU traffic (it probably does), you need a Privacy Policy page and (depending on what tracking you use) a cookie consent banner.

**Google Fonts** — A library of 1000+ free fonts hosted by Google. BuddyX ships full integration — pick any font from the Google library in **Customize → Typography**. Optionally, BuddyX can host the fonts on your own server (**Customize → Site Performance → Load Google Fonts locally**) for faster loads and better privacy.

**Group** *(BuddyPress)* — A sub-community within your site. Members join groups based on shared interest, like a Facebook group. Each group has its own activity feed, members list, and (optionally) forum. Different from a bbPress Forum, which is topic-based not membership-based.

**Gutenberg** — The project codename for WordPress's modern Block Editor. See **Block Editor**.

---

## H

**Header** — The top section of every page, holding your logo, main menu, and possibly a search icon, cart icon, color-mode toggle, etc. Configure via **Customize → Site Header**.

**Hex code** *(color)* — A 6-character code that represents a specific color, like `#ef5455` (BuddyX's default red). Prefixed with `#`. Most color pickers accept hex codes.

**Homepage** *(WordPress)* — The page visitors see at your domain root (e.g. `yoursite.com/`). By default, WordPress shows your latest blog posts here. Most sites override this with a custom "Home" page via **Settings → Reading**.

**Host / Web hosting** — The company whose servers your WordPress site runs on. Examples: SiteGround, Cloudways, WP Engine, Kinsta. Different from your "domain registrar" (where you bought your site's URL).

**HTTPS / SSL** — Encrypted, secure version of HTTP. A site at `https://` (with the padlock icon in the browser) has an SSL certificate that encrypts data between visitor and server. Required by Google for SEO; essential for any site collecting payments or logins. Most hosts provide free SSL via Let's Encrypt.

---

## J

**Jetonomy** *(Wbcom Designs plugin)* — A modern community plugin from the same team that builds BuddyX. Adds forums, Q&A, ideas, and trust levels. Free version + pro version sold separately. Designed to integrate cleanly with BuddyX.

**Jetpack** — A WordPress plugin from Automattic (the company that makes WordPress.com). Offers traffic stats, related posts, social sharing, image optimization, and security features.

---

## K

**Kirki** — An older third-party WordPress Customizer framework. BuddyX used Kirki pre-5.1.0. In 5.1.0, BuddyX replaced Kirki with its own in-theme Customizer Framework — fewer plugin dependencies, faster customizer load, smaller asset footprint.

---

## L

**Landing page** — A standalone page designed to convert visitors to take a specific action (sign up, buy, download). Usually has a hero section, key benefits, social proof, and a clear call-to-action. Build with BuddyX's block patterns.

**Light Mode** — The traditional "bright background, dark text" appearance of most websites. Opposite of Dark Mode. See [Color Mode](#color-mode-buddyx-510).

**Live Preview** *(WordPress Customizer)* — The right-side panel in the Customizer that shows what your settings will look like, updated as you change them. Changes don't apply to the live site until you click **Publish**.

**Loco Translate** — A free WordPress plugin for translating themes and plugins into other languages without writing code. Edit translations directly in your WordPress admin. BuddyX is fully translation-ready.

**Logo** — The image (usually with your brand name) shown in the header instead of (or alongside) the text site title. Upload via **Customize → Site Identity → Logo**. Best as a transparent PNG or SVG, 200-300px wide.

---

## M

**MediaVerse** *(Wbcom Designs plugin)* — A plugin for native video upload, hosting, and playback from Wbcom Designs (BuddyX team). Free version + pro version sold separately.

**Member Directory** *(BuddyPress)* — The public page listing all the members of your community. Visitors browse member cards, filter, search. URL is typically `/members/`.

**Member Profile** *(BuddyPress)* — A page representing a single community member with their avatar, name, bio, activity, and social connections. URL is typically `/members/<username>/`.

**Menu** *(WordPress)* — The list of links shown in your header (or elsewhere). Built at **Appearance → Menus**. You can have multiple menus assigned to different "locations" (Primary, Footer, etc.).

---

## P

**Page** *(WordPress)* — Static content like Home, About, Contact, Privacy Policy. Unlike Posts, Pages don't have categories, tags, or chronological order. Used for site furniture, not articles.

**Patterns (Block Patterns)** — See **Block Pattern**.

**Permalink** — The full URL of a page or post (e.g. `yoursite.com/blog/my-first-post/`). WordPress lets you choose the URL pattern at **Settings → Permalinks**.

**PHP** — The programming language WordPress is written in. BuddyX requires PHP 7.4 or newer (PHP 8.x recommended). You don't need to know PHP for normal use; it's relevant only if you're building a custom plugin or child theme.

**Plugin** — A WordPress add-on that adds features. Install via **Plugins → Add New**. Most popular plugins are free; some are paid or have free + paid tiers.

**Polylang / WPML** — Plugins that turn WordPress into a multilingual site. WPML is paid + comprehensive; Polylang has a free tier. Different from Loco Translate, which translates the theme/plugin text but not your content.

**Post** *(WordPress)* — A blog entry with date, author, and (usually) categories + tags. Listed chronologically on your blog page. Different from a Page, which is static.

**Primary Color / Site Primary Color** *(BuddyX Site Skin)* — Your main brand color, used across buttons, links, and accent UI. Set via **Customize → Site Skin → Site Primary Color**. The default is `#ef5455` (BuddyX red). The most influential single color setting in the theme.

**Primary Menu** *(WordPress)* — A "menu location" — a named slot in your header where one of your menus appears. BuddyX registers two menu locations: **Primary** (the main header menu, also used on mobile) and **User Menu** (only when BuddyPress is active, controls the logged-in user dropdown). Footer content lives in widget areas, not a menu location.

**Privacy Policy** — A page disclosing what data your site collects, how it's used, and how visitors can request deletion. Required by GDPR, CCPA, and similar laws. WordPress ships a template at **Settings → Privacy → Create New Page** — customize the template to match what your specific site actually does.

**Publish** *(WordPress / Customizer)* — The action that makes your changes live to visitors. Drafts are saved-but-not-public; Published content is live. In the Customizer, the **Publish** button (top right) commits your changes; the Preview area shows them before you publish.

---

## R

**Rank Math** — A free + paid WordPress SEO plugin, alternative to Yoast SEO. Helps your articles rank in Google by analyzing titles, content length, keywords, and metadata.

**Responsive design** — A design that adapts to different screen sizes — looks right on desktop, tablet, and phone. BuddyX is fully responsive out of the box.

---

## S

**Sidebar** *(WordPress)* — A column beside your main content where widgets appear (recent posts, search box, social links, etc.). BuddyX lets you choose left / right / none / both per content type (blog archive, single post, page, BuddyPress, WooCommerce, bbPress) via **Customize → Site Sidebar**.

**Site Icon** — See **Favicon**.

**Site Identity** *(WordPress Customizer)* — The Customizer section where you set your site title, tagline, logo, and favicon. **Customize → Site Identity**. The first place to visit when setting up a new site.

**Site Loader** *(BuddyX)* — An optional animated loader shown briefly while a page loads. Off by default in 5.1.0 (the modern asset pipeline loads fast enough not to need it). Enable + customize via **Customize → Site Loader**.

**Site Skin** *(BuddyX)* — The Customizer section where you control all the site's colors, including the 8 style presets, the 50+ individual color settings, and the Color Mode toggle. **Customize → Site Skin**. Pre-5.1.0 this was called "Skin Settings"; renamed for clarity.

**Slug** — The URL-friendly part of a title. The page titled "About Our Company" might have slug `about-our-company` and URL `/about-our-company/`. WordPress generates slugs automatically; you can edit them when creating or editing content.

**SSL** — See **HTTPS**.

**Starter Content** — See **Demo Content**.

**Static page** — A page set as your homepage (instead of WordPress showing your latest blog posts). Set via **Settings → Reading → Your homepage displays → A static page**. Most sites use a static "Home" page.

**Sticky header** *(BuddyX header setting)* — Makes the header stay visible as the visitor scrolls (instead of scrolling off-screen). On by default. Toggle via **Customize → Site Header**.

**Style preset / Style variation** *(BuddyX 5.1.0)* — A pre-tuned palette you apply with one click. BuddyX free ships 8 style presets (Cool, Dark, Editorial, Minimal, Monochrome, Pastel, Vibrant, Warm) plus the Default look. Pick a preset as your starting palette; any custom color you set below still takes priority. See [Color Scheme](../skin-colors/color-scheme.md).

---

## T

**Tag** *(WordPress)* — A keyword associated with a blog post (e.g. "react", "tutorial", "beginner"). More granular than Categories; one post can have many tags. Visitors can browse posts by tag.

**Tagline** *(WordPress Site Identity)* — A short one-line description of your site that appears below or near the site title. The default tagline is "Just another WordPress site" — change it via **Customize → Site Identity**.

**Taxonomy** *(WordPress)* — A way to organize content. Categories and Tags are the two built-in taxonomies. Plugins can add custom taxonomies (e.g. WooCommerce adds "Product Category" and "Product Tag").

**Template** — A PHP file that defines how a specific type of page is displayed (single posts, blog archive, search results, etc.). Themes ship templates; you customize them via child themes.

**Theme** — The visual + structural foundation of your WordPress site. Controls layout, typography, colors, and the templates for different page types. BuddyX is a theme. Only one theme is active at a time; switch via **Appearance → Themes**.

**Theme Mod** *(WordPress)* — The technical name for a value saved by the Customizer. Stored in the WordPress database under `theme_mods_<theme_slug>` option. Customizer settings are "theme mods"; widgets/posts/pages are not.

**Token (CSS Custom Property)** *(BuddyX 5.1.0 architecture)* — A CSS variable used to keep colors consistent across the site. BuddyX uses tokens like `--bx-color-primary`, `--bx-color-bg-body`, `--bx-color-text-primary`. Customizer values are resolved into tokens, then tokens are referenced throughout the stylesheets. See [Design Tokens reference](../../buddyx-design-tokens.md).

---

## W

**WB Gamification** *(Wbcom Designs plugin)* — A points / ranks / badges / leaderboards plugin from the BuddyX team. Free version + pro version sold separately.

**Widget** *(WordPress)* — A reusable content block (like Recent Posts, Search, Tag Cloud) you can drop into your sidebar or footer widget areas. Configure at **Appearance → Widgets**.

**WooCommerce** — The free, industry-standard WordPress plugin for selling things online. Handles cart, checkout, payments (via gateways like Stripe and PayPal), shipping, inventory, taxes, and order management. BuddyX supports WooCommerce out of the box (shop layout, product layouts, sidebars).

**WP Admin / WordPress Admin / Dashboard** — The back-end area at `yoursite.com/wp-admin/`. Where you write posts, manage settings, install plugins. Only logged-in users with appropriate roles see this.

**WP Ads Manager** *(Wbcom Designs plugin)* — A plugin for display ad zones, sponsorship slots, and ad rotation from the BuddyX team. Free version + pro version sold separately.

**WP Career Board** *(Wbcom Designs plugin)* — A job board + resume management plugin from the BuddyX team. Free version + pro version sold separately.

**WPML** — A paid plugin for building multilingual WordPress sites. The most full-featured multilingual solution; alternative to Polylang.

---

## Y

**Yoast SEO** — A free + paid WordPress SEO plugin, the most widely-used. Helps your articles rank in Google by analyzing titles, content length, keywords, readability, and metadata.

---

## A note on terminology

WordPress and its ecosystem use a lot of overlapping or interchangeable words. A "Customizer" is technically the "WordPress Customizer". "Site Skin" is BuddyX's name for the customizer section that controls colors; some other themes call this "Theme Options" or "Styling". A "dashboard" usually means the WP Admin area.

When in doubt, search the WordPress documentation at [wordpress.org/documentation/](https://wordpress.org/documentation/) or ask us at **support@wbcomdesigns.com** — we're happy to clarify any term that's not covered here.

---

## Related

- [FAQ](./faqs.md) — common questions
- [Troubleshooting](./troubleshooting.md) — common issues and fixes
- [Quick Start](../getting-started/quick-start.md) — first-time setup walkthrough
- [Choose Your Path](../getting-started/choose-your-path.md) — pick your site type
