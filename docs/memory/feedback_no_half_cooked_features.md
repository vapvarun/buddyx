---
name: BuddyX — fully cooked or not at all
description: Customer-facing features must ship complete, not partially working. Affects scope decisions and release readiness on the BuddyX theme.
type: feedback
originSessionId: 101e1337-3bd5-4c7f-9bd1-bdb39c4962c2
---
**Rule:** Every customer-facing feature in a BuddyX release must be fully working end-to-end, not partial.

**Why:** User stated explicitly "we can not release half cooked featured on live theme" (2026-05-04, in the BuddyX 5.1.0 work). Earlier in the same conversation: "we can not give broken flow to customers." Followed by "for now we can deliver things without kirki with 100% working options" — confirming 5.1.0 ships everything except palettes (deferred to 5.2.0) but everything that ships works.

**How to apply:**
- When in doubt about scope, finish a smaller scope completely rather than ship more partially.
- If a feature can't be completed in the current cycle, defer it (with a written plan doc) rather than shipping a half-version.
- Stakeholder will say "nothing is deferred" sometimes — that means *finish* the listed scope, not "ship partial".
- Test every feature in the browser end-to-end before claiming done. Bootstrap-presence in customize JSON is not enough — the rendered control must work, the saved value must round-trip, the front-end must reflect the saved value, the live preview must update.
- Customer-data preservation is non-negotiable: every customer with a configured 5.0.3 customizer must upgrade and see their settings intact. Setting IDs, value shapes (string vs array vs JSON), sanitize_callback behavior — all preserved or migrated.
