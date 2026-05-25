# Recipe: Match the demo design

**What you'll build**: Your site looks like the published BuddyX demo / screenshots at [wordpress.org/themes/buddyx](https://wordpress.org/themes/buddyx/).

**Time**: ~30 minutes

**Prerequisites**:
- BuddyX 5.1.0+ activated ([Quick Start Step 1](../getting-started/quick-start.md#step-1--install--activate-the-theme-2-minutes))
- Admin access

---

## What "match the demo" actually means

The BuddyX demo is the **out-of-box look** with sample content. On a fresh install with no customization, you're already 80% there. The remaining 20% is: loading the same starter content + leaving Customizer defaults alone.

This recipe walks through making sure you didn't accidentally drift from the demo look.

---

## Step 1 — Verify you're on the latest BuddyX (1 minute)

1. **Dashboard → Updates** — if BuddyX shows an update, install it
2. **Appearance → Themes → BuddyX → Theme Details** — confirm version 5.1.0 or newer

The demo screenshots on wordpress.org/themes/buddyx are produced from the latest release. Older versions look slightly different.

---

## Step 2 — Reset to defaults (if your site is already customized)

If you've been experimenting and want to start over:

### Option A — Reset just the Customizer

Use the **Customizer Reset** plugin (free at wordpress.org):
1. **Plugins → Add New → search "Customizer Reset" → Install → Activate**
2. **Appearance → Customize → Reset** (now appears at bottom of the panel)
3. Confirm

This wipes only Customizer settings; your posts, pages, widgets, and menus stay.

### Option B — Reset everything (only do this on a test site)

If your site is a test install and you want a true fresh start, the easiest path is to reinstall. **Don't do this on a production site.**

---

## Step 3 — Load WP Starter Content (5 minutes)

WordPress has a built-in starter content feature that BuddyX uses. On a **fresh install**, the Customizer shows demo pages, demo widgets, and demo Customizer values *as a preview*. Saving that preview locks it in as your real content.

### How to trigger starter content

Starter content only shows when:

1. Your site has **no published pages or posts** (other than the auto-generated "Sample Page" / "Hello World")
2. You haven't already published any Customizer changes

If both are true:

1. **Appearance → Customize**
2. You'll see a banner offering to load starter content — accept it
3. The preview will show: a Home page, About page, Contact page, Blog widgets, the BuddyX header + menu, default colors
4. Click **Publish** to make it real

If the banner doesn't appear, your site has too much existing content. Either trash all pages/posts first or use a fresh install.

> **Already past this stage?** That's fine. You can manually recreate the demo pages following [Quick Start Step 7](../getting-started/quick-start.md#step-7--essential-pages-10-minutes).

---

## Step 4 — Leave Customizer settings at default (verify, 5 minutes)

Walk through these Customizer panels and make sure nothing's been changed from the default. If you don't remember changing something but it's not default — reset just that setting (clear the value or pick the default again).

### Site Identity

| Setting | Default |
|---|---|
| Site title | Your site name (set this to whatever you want — the demo uses the site title) |
| Tagline | Either empty or your one-liner |
| Logo | Empty (the demo uses just the text site title) |

### Site Skin

| Setting | Default |
|---|---|
| Default color mode | **Light** |
| Show color-mode toggle | **On** |
| Toggle position | **Both** |
| Style preset | **Default** (empty value) — no preset selected |
| Site Primary Color | `#ef5455` |
| Body background | `#f7f7f9` |
| Box background | `#ffffff` |
| Site links | `#111111` |
| Header background | `#ffffff` |

### Site Header

| Setting | Default |
|---|---|
| Sticky header | **On** |
| Search icon | **On** |
| Cart icon | **On** (auto-hides if no shop plugin active) |
| Sign-in link | **On** |
| Register link | **On** |

### Site Layout

| Setting | Default |
|---|---|
| Site layout | **Wide** (`wide`) |
| Container width | `1170px` |
| Global border radius | `8px` |
| Button border radius | `6px` |
| Form border radius | `6px` |

### Site Loader

| Setting | Default |
|---|---|
| Show site loader | **Off** (5.1.0 default — the modern asset pipeline loads fast enough that a loader is optional) |
| Loader type | **Dots** |
| Loader background | `#ef5455` |
| Loader color | `#ffffff` |

> **Want the loader anyway?** Turn it on at **Customize → Site Loader → Show site loader**. The default-off matches the wordpress.org demo (no loader). Some marketing screenshots show the loader; those reflect older releases or a customized demo.

### Typography

The demo uses the default WordPress font stack. If you've picked a specific Google font, switch back to **Default** (the dropdown's first option) for each typography setting (Body, H1–H6, Menu).

---

## Step 5 — Verify the menu structure (2 minutes)

The demo menu has these items:

- Home
- About
- Blog
- Contact

If you ran starter content (Step 3), these were auto-created. Verify at **Appearance → Menus → Primary Menu**.

If yours is different, edit the menu to add/remove items so it matches.

---

## Step 6 — Verify the homepage is set (1 minute)

**Settings → Reading**:
- **Your homepage displays** → **A static page**
- **Homepage** → **Home** (or whatever your Home page is named)
- **Posts page** → **Blog**

If yours shows **Your latest posts** instead, the front page will be the blog feed, not the demo home. Change it.

---

## Step 7 — Take a screenshot, compare to the demo (5 minutes)

1. Visit your site front page in an **incognito/private browser window** (so you're not viewing it as a logged-in user with the admin bar showing)
2. Compare to [wordpress.org/themes/buddyx](https://wordpress.org/themes/buddyx/)
3. Spot the difference

Common drift you might see:
- **Logo where the demo has none** — remove your logo if you want the demo look (or leave it if you want your logo, which is most sites' choice)
- **Different colors** — verify Site Skin defaults from Step 4
- **No loader** — turn on Site Loader (Step 4)
- **Different menu** — verify menu items (Step 5)
- **Different homepage** — verify static homepage setting (Step 6)

---

## Step 8 — Optional: enable BuddyPress for the community demo

The demo screenshots on wordpress.org are the **general-theme demo** (no BuddyPress). If you want the community-flavored demo from the BuddyX product page:

1. **Plugins → Add New → BuddyPress → Install → Activate**
2. **Settings → BuddyPress → Components → enable Activity, Members, Groups, Notifications, Friend Connections, Private Messaging, User Groups**
3. **Settings → BuddyPress → Pages → make sure each component is mapped to its auto-created page**
4. Add **Activity**, **Members**, **Groups** to your **Primary Menu** (Appearance → Menus)
5. Visit `yoursite.com/members/` and `yoursite.com/activity/` — you should see styled member/activity pages

Now your site has the community surfaces. Add a few test users to flesh out the member directory (or use a demo-user generator plugin).

---

## You're done

Your site now matches (or closely matches) the BuddyX demo:

- Out-of-box BuddyX colors (red primary on soft-gray)
- Default WordPress fonts
- Wide site layout, sticky header, optional loader
- Light mode with visitor toggle
- Home / About / Blog / Contact menu
- Static homepage, separate blog page

---

## Next steps

You probably want to **NOT** match the demo exactly — the demo is a baseline; your site should reflect *your* brand. So:

- [Customize your brand colors and fonts](./customize-brand.md) — pick your primary color, your fonts, make it yours
- [Choose Your Path](../getting-started/choose-your-path.md) — what kind of site are you building (blog / portfolio / business / community)?

---

## Common questions

**Where's the "import demo content" button?**
BuddyX doesn't ship a one-click demo importer. WordPress's built-in starter content (Step 3) is the supported path. For richer demo data on community sites, BuddyPress + the BuddyX Pro release ships a Demo Importer; the free release doesn't.

**Can I change the demo to look like a different BuddyX demo?**
The wordpress.org demo is the only official "demo" for free BuddyX. Wbcom Designs runs other showcase sites with different configurations — those use BuddyX Pro + extra plugins, not free BuddyX.

**My demo doesn't match a screenshot I saw somewhere else.**
Make sure the screenshot is from the latest BuddyX free, not from a Wbcom Designs marketing page (which often shows Pro features). The official "this is what free BuddyX looks like" reference is [wordpress.org/themes/buddyx](https://wordpress.org/themes/buddyx/).

---

## Related

- [Customize your brand colors and fonts](./customize-brand.md) — make it look like *your* site
- [Quick Start](../getting-started/quick-start.md) — first-time setup walkthrough
- [Color Scheme](../skin-colors/color-scheme.md) — all color settings explained
