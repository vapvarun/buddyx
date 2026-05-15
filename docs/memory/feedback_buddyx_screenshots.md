---
name: BuddyX screenshots — log out before capture, do not just hide admin bar
description: For all BuddyX site screenshots, log out of WordPress before capture so the 32px admin bar reserved space is gone — hiding the admin bar via CSS leaves a 32px gap at top
type: feedback
originSessionId: 101e1337-3bd5-4c7f-9bd1-bdb39c4962c2
---
When taking any screenshot of the BuddyX model site (`buddyx.local`) — wp.org `screenshot.png`, marketing visuals, design QA artifacts — **log out of WordPress before the capture**. Don't just hide the admin bar via CSS.

**Why:** Even with `#wpadminbar { display: none }`, WordPress applies `html { margin-top: 32px !important }` to reserve space for the admin bar on logged-in pages. That leaves a visible 32px gap above the header in screenshots. Logging out removes the reservation entirely (no admin bar, no margin-top rule).

**How to apply (Playwright workflow):**
1. `browser_navigate` to `http://buddyx.local/wp-login.php?action=logout&_wpnonce=test`
2. `browser_evaluate` to click the explicit logout link: `document.querySelector('a[href*="action=logout"]').click()`
3. `browser_resize` to target dimensions (1200×900 for wp.org `screenshot.png`)
4. `browser_navigate` to `http://buddyx.local/`
5. `browser_take_screenshot` saving to `wp-content/themes/buddyx/screenshot.png`

The auto-login mu-plugin lets you re-login afterwards via `?autologin=1` if needed.

**wp.org spec for `screenshot.png`:** 1200 × 900 PNG, theme root.
