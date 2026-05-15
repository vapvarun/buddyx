---
name: BuddyX — test by site-owner journey, not by setting
description: For 5.1.0+ verification, walk one complete site-owner journey end-to-end; matrix-by-setting misses interaction bugs and real customer flow
type: feedback
originSessionId: ce41b5fa-38ba-4d41-99c1-48ac3dcbdb94
---
For BuddyX verification work going forward, the unit of testing is a **site-owner journey** end-to-end, not an individual customizer setting.

**Why:**
We kept getting QA bug reports even after the 25-color + 11-typography matrix sweeps because matrix-by-setting only proves each setting in isolation. It misses:
- **Interaction bugs**: setting A + setting B together produce a visual that neither setting causes alone (e.g., dark mode + footer widgets + custom copyright color all interacting at the same selector).
- **Flow bugs**: the order a real customer takes through the customizer changes which active_callbacks fire, which Output_Builder rules emit, and whether `customize_save_after` re-sanitizes everything.
- **Migration bugs**: pre-5.1.0 'on'/'off' string DB values silently flip in render code; no fresh-install matrix catches this.
- **Cross-surface bugs**: setting hits one rule on home page, a different rule on single-post, neither tested if matrix runs on only one page.

**How to apply:**
For each release cycle, walk these journeys end-to-end before tagging:

1. **Migration-from-5.0.x (HIGHEST PRIORITY for 5.1.x)** — Restore a real 5.0.x customer DB dump on staging. Don't reset any theme_mod. Visit home, post, archive, page, login, footer in dark + light. Every customizer-set value must visibly apply. Already-found bug class: `(int) 'on' === 0` and `! empty('off') === true` silently flipping intent.

2. **First-time installer (general site owner)** — Fresh DB, theme activated cold. Visit site (default look). Open customizer, set: logo, primary color, hero typography, layout (wide → boxed), site loader on. Save. Publish a post. Check homepage + post + mobile.

3. **Blogger/publisher** — Set site title + tagline + heading/body typography + image overlay rgba + blog layout (grid → list → masonry) + sidebar (right → left → none). Publish post with featured image + tags. Verify post page, archive, category, search.

4. **Community manager (BP active)** — Activate BuddyPress. Set members/groups/activity sidebar layouts. Set BP avatar style. Check member profile, group directory, activity feed.

5. **Dark mode user** — Toggle dark/light/auto from header. Check home, post, comments, form inputs, footer (with widgets active), site-info, mobile. Persistence across page loads.

**Each journey passes only if EVERY visible step renders correctly.** If a single step fails, the journey blocks the release — fix in the same release cycle (no defer per existing rule).

**Track journeys in plans/ directory:** Each journey gets its own checklist file `plans/YYYY-MM-DD-journey-{name}.md` with step-by-step checks. Marked done with browser proof + commit SHAs.

**Matrix sweeps still useful** — but as REGRESSION FENCE, not as primary verification. After a journey passes, the matrix proves no individual setting silently broke. Keep the matrices automated where possible (Playwright scripts in tools/).
