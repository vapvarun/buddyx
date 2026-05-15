---
name: BuddyX — replicate, fix, never defer; theme is general not BuddyPress
description: If we replicate a QA issue we MUST fix in 5.1.0 before moving to Ready for Testing. No deferrals to 5.2.0. Theme positions as general, not BuddyPress-specific.
type: feedback
originSessionId: ce41b5fa-38ba-4d41-99c1-48ac3dcbdb94
---
Three standing rules for BuddyX 5.1.0 (and beyond):

**1. Replicated → fix → only then Ready for Testing.**
If we can replicate an issue from a card, we must fix it in the current release (5.1.0) before moving the card to Ready for Testing. The flow is: read card → repro in browser → fix → browser-verify the fix → post comment with proof → move card. Do NOT post "we acknowledge" or "ask QA to retest" without a fix in hand.

**2. No deferrals to 5.2.0.**
Whatever QA / the team raises during 5.1.0 must ship in 5.1.0. Don't carry items forward to 5.2.0 even if they look pre-existing or out of original scope. If a card lands in the Bugs column it ships fixed in this release.

**3. BuddyX positions as a GENERAL theme, not a BuddyPress theme.**
The screenshot.png re-capture (commit `63b6dd1`, 2026-05-04) intentionally shows "Preview theme patterns" / general-site visuals — NOT BuddyPress activity stream / member directories. This is positioning, not a bug. When QA flags the screenshot content as "wrong" don't agree by reflex — verify whether they're objecting to the positioning vs. an actual visual defect (border baked in, wrong page, low res, etc.). Real visual defects in the captured PNG → re-capture. Positioning objections → push back.

**Why:**
- Half-cooked + deferral combo is what got us into the 5.0.x Kirki mess. 5.1.0's whole point is to ship complete.
- Telling QA "fix is shipped, please retest" feels efficient but isn't — they're not the verifier of our fixes, we are. If we replicate, we own the fix loop until the card moves.
- "BuddyX = general theme" was a deliberate positioning decision; the screenshot, copy, and demo content all express it. Caving to "looks too generic, add BP" feedback erases that positioning.

**How to apply:**
- For each card in BuddyX Bugs column: repro → fix in 5.1.0 → verify in browser at <http://buddyx.local> with `?autologin=1` → post HTML comment with before/after + commit SHA → move card to Ready for Testing.
- Reject "defer to 5.2.0" framing in any card verdict during 5.1.0 release cycle.
- When QA flags screenshot.png content (not visual quality), push back with the general-theme positioning rationale before agreeing to re-capture.
