# Troubleshooting

Common issues and how to fix them. If your issue isn't here, see [FAQ](./faqs.md) or email **support@wbcomdesigns.com**.

---

## My site looks broken / unstyled

**Symptom**: After activating BuddyX, your site shows raw HTML — no colors, no fonts, layout is broken.

**Most likely cause**: A caching plugin or your hosting cache is serving stale CSS from the previous theme.

**Fix**:
1. Clear your caching plugin's cache (WP Rocket → Settings → Clear Cache, etc.)
2. Clear your host's server cache (in your hosting dashboard, or the host's caching plugin if they install one)
3. Hard-refresh your browser: **Cmd+Shift+R** (Mac) or **Ctrl+Shift+R** (Windows)
4. Try a different browser or an incognito/private window to bypass your browser cache entirely
5. If using a CDN (Cloudflare etc.), purge its cache too

**Still broken?** Open browser developer tools (F12 → Network tab → reload) and check whether `buddyx-styles.css` returns 200 OK or 404. A 404 means file-permission issues — contact your host.

---

## I changed a Customizer setting but the front-end didn't update

**Symptom**: Set a color in **Customize → Site Skin**, clicked **Publish**, but the live site still shows the old color.

**Most likely cause**: Caching, again.

**Fix**:
1. **In the Customizer**: make sure you clicked the blue **Publish** button at the top (not just hit Esc). Until you publish, settings exist only in the preview.
2. **Clear all caches** (see above — caching plugin → host cache → browser → CDN)
3. **Hard-refresh** the front-end page

**Still not updating?** A child theme or another plugin may be overriding the setting. Temporarily switch to the **Twenty Twenty-Four** default theme — does the color appear correctly there? If yes, return to BuddyX. If no, the issue is plugin-related.

---

## The color-mode toggle (sun/moon icon) isn't showing

**Symptom**: You expect a dark/light toggle button in the header but don't see one.

**Check**:
1. **Customize → Site Skin → Color Mode** — is **Show color-mode toggle** set to **Show**? (It's **on** by default.)
2. **Customize → Site Skin → Color Mode → Toggle position** — is it set to **Header**, **Mobile**, or **Both**? If set to **Mobile**, it only appears in the mobile menu (you won't see it on desktop). Change to **Header** or **Both** to make it visible on desktop.
3. Is your header layout overriding the icons? Some custom header configurations may hide icon slots.

---

## My logo is huge / tiny / cropped

**Symptom**: After uploading a logo in **Customize → Site Identity**, it displays at an unexpected size.

**Cause**: WordPress doesn't resize your logo — it uses your image's source dimensions (scaled responsively).

**Fix**:
1. Resize your logo to a sensible size **before uploading** — most horizontal logos work at around 200×60 pixels; most square logos at around 60×60.
2. Use a tool like [Squoosh.app](https://squoosh.app), Canva, Photoshop, or Preview (Mac) to resize.
3. Use a transparent PNG or SVG so it sits cleanly over any header background color.
4. After uploading a smaller version, clear caches and hard-refresh.

> BuddyX free doesn't have a "logo size" Customizer setting (Pro does). The fix is to resize the source image.

---

## Fonts aren't loading

**Symptom**: You picked a Google font in **Customize → Typography**, but the site still shows the default sans-serif.

**Possible causes**:

1. **Caching** — clear caches and hard-refresh first.
2. **Wrong setting** — make sure you selected a font in the **Font Family** dropdown (not just changed the size or weight).
3. **Google Fonts blocked** — if you have **Customize → Site Performance → Load Google Fonts locally** turned on, the fonts are pulled from your own server. Make sure your server has write permissions to `wp-content/uploads/` (where BuddyX caches the local font files).
4. **Browser console errors** — open developer tools (F12 → Console). If you see CORS errors or font 404s, that points to a server-config issue with your host.

**Fix**:
1. Turn **off** Load Google Fonts locally temporarily (to test if Google's CDN itself works for your server)
2. If that fixes it, your server has a write-permission issue on `wp-content/uploads/`. Contact your host.
3. If the font still doesn't load even from Google's CDN, your server may be blocking fonts.gstatic.com. Whitelist that domain (your host can help).

---

## My BuddyPress activity feed / member directory isn't styled

**Symptom**: After installing BuddyPress, the activity, members, or groups pages look unstyled or off.

**Check**:
1. **BuddyPress version** — BuddyX is tested with the latest BuddyPress. Update via **Plugins → Installed Plugins**.
2. **BuddyPress Components** — **Settings → BuddyPress → Components**. Make sure Activity, Members, Groups, etc. are enabled.
3. **BuddyPress Pages** — **Settings → BuddyPress → Pages**. Each component must be mapped to a real page (BuddyPress auto-creates these on first activation).
4. **Cache** — community page caches are notorious. Clear caches.

---

## The site loader spins forever (or shows a brief flash)

**Symptom**: When loading a page, a spinning loader appears and either never goes away, OR shows for a fraction of a second then disappears.

**Check**:
1. **Customize → General → Site Loader** — is **Show site loader** turned **on**? The 5.1.0 default is **off** (the loader is optional). If it's on and looks wrong, try turning it off.
2. **JavaScript error** — open browser developer tools (F12 → Console). If you see red errors, a plugin or custom code may be breaking JS. The loader hides itself when the page finishes loading; if JS is broken, it can hang.

**Fix**:
1. Turn off the loader if you don't need it (5.1.0 default).
2. If you need it, troubleshoot the JS error — disable plugins one at a time to find the culprit.

---

## Dark mode flashes on page load

**Symptom**: When loading a page as a dark-mode visitor, the page briefly shows light before snapping to dark.

**Cause**: This shouldn't happen on BuddyX 5.1.0+ — the FOUC-free first paint specifically prevents it. If you're seeing it, you're on an older version OR a plugin is delaying the inline dark-mode script.

**Fix**:
1. Update BuddyX to the latest 5.1.x — **Appearance → Themes → BuddyX → Update Available** (if shown), or upload the latest zip from wordpress.org.
2. If a caching plugin is HTML-minifying the inline `<script>` that BuddyX puts in `<head>` for first-paint, that script may run later than it should. Exclude `head` inline scripts from minification in your caching plugin's settings.
3. Hard-refresh and test in incognito.

---

## My header / menu disappears on mobile

**Symptom**: On a phone-sized screen, the menu doesn't appear (or the whole header looks empty).

**Check**:
1. **Appearance → Menus → Menu Settings → Display location** — make sure your menu is assigned to **Primary Menu** (and optionally **Mobile Menu** if BuddyX shows that location).
2. **Customize → Site Header** — verify your header layout option supports mobile (all BuddyX header layouts do).
3. Resize your browser window from wide → narrow to see the breakpoint. The mobile menu (hamburger) usually appears around 1024px.

---

## My footer widgets aren't appearing

**Symptom**: You added widgets at **Appearance → Widgets → Footer 1/2/3/4** but the footer is blank or shows different widgets.

**Check**:
1. **Right widget area** — BuddyX has 4 footer columns: Footer 1, 2, 3, 4. Make sure you added widgets to the right one(s).
2. **Cache** — clear caches.
3. **Theme override** — if you're using a child theme that customized the footer template, it may be ignoring BuddyX's widget areas. Switch off the child theme temporarily to test.

---

## I uploaded a file but it doesn't appear

**Symptom**: Tried to upload an image, video, or file but it fails (or only sometimes works).

**Most likely cause**: Hosting upload limits.

**Fix**:
1. **WordPress upload limit** — varies by host. Check at **Media → Add New** (you'll see "Maximum upload file size: XX MB" at the bottom).
2. **Your file is over the limit** — either reduce the file size (use Squoosh.app, Handbrake for videos), OR ask your host to raise the limit (usually they need to bump `upload_max_filesize` in `php.ini`).
3. **PHP errors** — large uploads may hit PHP timeout or memory limits. Look in your hosting dashboard for PHP error logs.

---

## I can't see the Customizer / settings panel

**Symptom**: **Appearance → Customize** is missing, or clicking it shows an error.

**Check**:
1. **User role** — only Administrators can access the Customizer by default. Are you logged in as Admin?
2. **JS error** — open browser developer tools (F12 → Console). Customizer relies on JavaScript; a JS error from a plugin can break it. Disable plugins one at a time.
3. **Caching/CDN** — sometimes the Customizer fails when JS is aggressively minified. Exclude `/wp-admin/` from caching in your caching plugin.

---

## After updating BuddyX, my customization is gone

**Symptom**: Just updated to a new BuddyX version and the colors, fonts, or layouts look reset.

**Most likely cause**: Caching, not actual data loss. WordPress updates don't delete Customizer settings.

**Fix**:
1. Clear all caches (caching plugin, host cache, browser cache, CDN)
2. Hard-refresh
3. Go to **Appearance → Customize** — your saved settings should still be there, just not visually applied yet due to old cached CSS

**If a setting is actually missing**, you may have upgraded from a pre-5.1.0 version that used a slightly different setting name. The 5.1.0 release carefully migrates old setting IDs — but if you see a specific setting reset, email support with the setting name and we'll investigate.

---

## Block patterns aren't showing up in the editor

**Symptom**: When editing a page, you click `+ → Patterns` and don't see BuddyX patterns.

**Check**:
1. **WordPress version** — block patterns require WordPress 6.0+. Update at **Dashboard → Updates**.
2. **Patterns category** — in the patterns dropdown, look under the **BuddyX** category specifically. There may also be a **WordPress core patterns** category — don't confuse them.
3. **Plugin conflicts** — some "block library" plugins override the patterns UI. Try deactivating them.

---

## Where do I report a bug?

If your issue isn't here and you've ruled out caching, plugin conflicts, and theme-version mismatch:

1. **WordPress.org BuddyX support forum** — [wordpress.org/support/theme/buddyx/](https://wordpress.org/support/theme/buddyx/). Community-supported, public.
2. **GitHub issues** — [github.com/vapvarun/buddyx/issues](https://github.com/vapvarun/buddyx/issues). Best for technical / developer bugs.
3. **Email support** — **support@wbcomdesigns.com**. We respond within 1 business day.

**Helpful info to include**:
- Your WordPress version (Dashboard → At a Glance, or **Tools → Site Health → Info**)
- Your BuddyX version (Appearance → Themes → BuddyX → Theme Details)
- Your PHP version (Tools → Site Health → Info)
- A list of active plugins
- A clear description of the symptom + screenshot if visual
- Steps you've already tried

---

## Related

- [FAQ](./faqs.md) — common questions
- [Glossary](./glossary.md) — plain-English term definitions
- [Quick Start](../getting-started/quick-start.md) — first-time setup walkthrough
- [Choose Your Path](../getting-started/choose-your-path.md) — pick your site type
