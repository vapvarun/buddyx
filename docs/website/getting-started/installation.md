# Installation Guide

Three install paths — pick whichever fits your setup.

> **Just want the short version?** [Quick Start Step 1](./quick-start.md#step-1--install--activate-the-theme-2-minutes) covers install in 2 minutes, then walks through first-time setup.

---

## What you need

| Requirement | Minimum *(theme header)* | Recommended |
|---|---|---|
| WordPress | 4.8 | 6.0+ for full feature support |
| PHP | 7.4 | 8.1+ |
| MySQL | 5.7 | 8.0+ |
| Memory limit | 64 MB | 256 MB |

> **Note**: BuddyX 5.1.0's theme header declares WordPress 4.8+ minimum, but the modern features (block patterns, block editor integration, FSE-style theme.json tokens) work best on WordPress 6.0+. We recommend WordPress 6.0 or newer for new installs.

Check what you have at **Tools → Site Health → Info**.

---

## Path 1 — Install from WordPress.org (recommended)

This is the easiest path and the one we recommend for most users.

1. Log in to your WordPress admin (`yoursite.com/wp-admin/`)
2. Go to **Appearance → Themes → Add New**
3. Search for **BuddyX**
4. Click **Install** on the BuddyX card (by **wbcomdesigns**)
5. Click **Activate** when the button changes

Done. You should see BuddyX's default look when you visit your site front page.

**Updates** install via the same panel — **Appearance → Themes → BuddyX → Update Available** when a new version ships.

---

## Path 2 — Upload a zip file

Use this if you downloaded BuddyX from GitHub or wbcomdesigns.com instead of WordPress.org.

1. Download `buddyx.zip` from one of:
   - [wordpress.org/themes/buddyx](https://wordpress.org/themes/buddyx/) → "Download" button
   - [GitHub release page](https://github.com/vapvarun/buddyx/releases)
2. Log in to WordPress admin
3. **Appearance → Themes → Add New → Upload Theme**
4. Choose `buddyx.zip` → **Install Now**
5. After install completes, click **Activate**

**Don't unzip the file before uploading** — WordPress expects the zip itself.

**Updates**: when uploading a new version this way, WordPress will warn that a theme with the same name exists. Click **Replace current with uploaded** to update. Your Customizer settings and content are preserved.

---

## Path 3 — Manual install via FTP/SFTP

Use this when you can't access the WordPress admin (e.g., debugging a broken site).

1. Download `buddyx.zip` (see Path 2)
2. Unzip it locally — you'll get a folder named `buddyx`
3. Connect to your site via FTP/SFTP (use FileZilla, Cyberduck, or similar)
4. Navigate to `/wp-content/themes/`
5. Upload the entire `buddyx` folder
6. Log in to WordPress admin → **Appearance → Themes**
7. Click **Activate** on the BuddyX card

---

## After install: required additional plugins

**None.** BuddyX is self-contained. It does **not** require Kirki, Redux, or any external framework.

In 5.0.x and earlier, BuddyX required the Kirki plugin for customizer controls. In 5.1.0, BuddyX ships its own in-theme customizer framework — Kirki is no longer needed.

---

## Optional plugins by use case

| Use case | Plugins to install |
|---|---|
| **Community site** | BuddyPress (free, buddypress.org) |
| **Forums** | bbPress (free) or Jetonomy (free version, from wbcomdesigns.com) |
| **Blog SEO** | Yoast SEO or Rank Math (free tiers fine) |
| **E-commerce** | WooCommerce (free) |
| **Caching** | WP Rocket (paid), W3 Total Cache (free), LiteSpeed Cache (free if on LiteSpeed host) |
| **Image optimization** | EWWW Image Optimizer or Smush (free) |
| **Forms** | WPForms Lite or Contact Form 7 (free) |
| **Backups** | UpdraftPlus or BlogVault (free tiers) |
| **Spam protection** | Akismet (free; included with WordPress) |

None are theme dependencies; pick based on what your site needs.

---

## Verifying install

After activation, visit your site front page. You should see:

- The BuddyX logo placeholder (or your site title in text) in a header
- A primary menu (or "Add a menu" placeholder if you haven't built one)
- The default red-on-soft-gray BuddyX palette
- A footer with widget areas (empty until you add widgets)

If you see a "white screen of death" or PHP errors, see [Troubleshooting → My site looks broken](../faq-support/troubleshooting.md#my-site-looks-broken--unstyled).

---

## Upgrading from an older BuddyX version

### From 5.0.x to 5.1.0

5.1.0 is a major release. Your Customizer settings, content, and BuddyPress configuration are preserved automatically; no manual migration needed.

**Before upgrading on a production site**:

1. **Take a backup** — your hosting dashboard usually has a one-click backup; or use UpdraftPlus / BlogVault.
2. **Test on staging first** if possible — most hosts offer a one-click staging site.
3. **Note any active child theme** — child themes from 5.0.x continue working, but if your child theme overrode any of the Kirki-based files (now removed), you may see issues. Check after upgrade and remove obsolete child-theme overrides if needed.

**To upgrade**:

1. **Dashboard → Updates** — if BuddyX shows an update, click **Update**.
2. After update completes, **clear all caches** (caching plugin, host cache, CDN).
3. Visit your front page in incognito to verify.

**What changed in 5.1.0**: Kirki removed (replaced by in-theme framework), unified design-token system, dark mode added with FOUC-free first paint, asset manifest, 8 style presets. See the [5.1.0 release notes](https://github.com/vapvarun/buddyx/releases) for full details.

### From 4.x or earlier

Same process as above, but expect a larger visual shift since you're skipping multiple major versions. Strongly recommend a staging test first.

---

## Uninstalling BuddyX

**Important**: deactivating BuddyX doesn't delete your customizer settings or content. They're preserved in the database in case you reactivate later.

1. **Appearance → Themes**
2. Activate a different theme (you must have one active)
3. Hover over the BuddyX card → **Theme Details → Delete**

This removes the theme files. Customizer settings remain in the database (`wp_options` table, `theme_mods_buddyx` row) — only relevant if you reinstall BuddyX later, in which case your old settings come back.

---

## Common install issues

### "Theme zip is invalid"

You're uploading a wrong zip. The zip from wordpress.org or GitHub is correct. If you downloaded from Wbcom Designs as a `.zip` from an "All Files" download, it may be a wrapper zip containing the real zip — unzip once, then upload the inner `buddyx.zip`.

### "Memory exhausted" during install

Your PHP memory limit is too low. Either:
1. Ask your host to raise `WP_MEMORY_LIMIT` to 256M
2. Or add this to `wp-config.php` (above the "stop editing" line): `define('WP_MEMORY_LIMIT', '256M');`

### "Style.css missing in stylesheet"

You uploaded the wrong zip or the wrong folder. Make sure you're uploading the `buddyx.zip` from wordpress.org or the contents of the `buddyx` folder (not a parent directory containing it).

### After install, my customizer settings from another theme don't show up

Customizer settings are stored per-theme. Each theme has its own `theme_mods_<slug>` entry. BuddyX won't read settings saved under a different theme's name. To migrate settings between themes, you'd need to manually map equivalent settings using a plugin like **Customizer Export/Import** or a developer's WP-CLI command.

---

## Related

- [Quick Start](./quick-start.md) — first-time setup after install
- [Introduction to BuddyX](./intro.md) — what BuddyX is + system requirements overview
- [Troubleshooting](../faq-support/troubleshooting.md) — fixes for common issues
- [FAQ](../faq-support/faqs.md) — common questions
