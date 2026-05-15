---
name: BuddyX Bugs column — justify before fixing
description: For BuddyX cards in the Bugs column, justify on the card that it's a real bug (not intended 5.1.0 change / stale build) before fixing
type: feedback
originSessionId: ce41b5fa-38ba-4d41-99c1-48ac3dcbdb94
---
Scope is the **Bugs column only** — those are the cards we're committed to solving for the 5.1.0 release. For each one, before writing any fix:

1. Read card + comments and check the symptom against our 5.1.0 changes (Kirki removal, Phase 3 UX clusters, typography defaults, Phase 4 stylesheet cleanup).
2. Decide: real bug, intended-change-misreported, or stale-build artifact.
3. Post a HTML-formatted comment on the card with the justification — what was changed in 5.1.0, why this is (or isn't) a real bug, and the file/area we'll touch if we fix it.
4. Only then write the fix.

**Why:** The team filing these cards doesn't have full context on what's in flight on the 5.1.0 branch. Several of the symptoms ("Site Skin Colors Not Applying", "Background Settings UI Not Visible") could be either real regressions OR QA testing against a stale build / against a control we deliberately moved. Fixing without justifying on the card wastes cycles, risks reverting intended 5.1.0 changes, and leaves QA without an audit trail of why we did/didn't fix.

**How to apply:**
- Trigger: any card in BuddyX (37499979) or BuddyX Pro (37557698) **Bugs** column during the 5.1.0 release cycle. Triage / Suggestions / Ready for Development are out of scope of this rule.
- Tool: `mcp__basecamp__basecamp_comment` with HTML (`<strong>`, `<br>`) — markdown doesn't render.
- Comment must include: (a) one-line verdict (real bug / intended change / stale build), (b) link to the 5.1.0 change that's relevant, (c) if fixing, the file/area we'll touch.
- If the card is "intended change", explain in the comment and ask QA to retest against the latest 5.1.0 build before re-opening.
