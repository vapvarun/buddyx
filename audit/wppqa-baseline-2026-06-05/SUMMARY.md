# wppqa Baseline — buddyx (free) — 2026-06-05

Run from wp-plugin-onboard Phase 0 against `master` @ ac3b242.

| Check | Passed | Failed | Skipped |
|---|---|---|---|
| plugin-dev-rules | 7 | 2 | 0 |
| rest-js-contract | 1 | 0 | 0 |
| wiring-completeness | 0 | 0 | 1 (no `includes/admin/` + `templates/` pair — theme layout) |

## Findings

### plugin-dev-rules — 2 high errors

1. **Nonce check without capability check** — `inc/Accessibility/Component.php:95`
   - Classification: **likely intentional** — frontend accessibility preference toggle for logged-in users; saving own user meta needs no elevated cap. Verify the handler only writes to `get_current_user_id()` meta; if so, document and suppress.
2. **Nonce check without capability check** — `inc/extra.php:887`
   - Classification: **needs triage** — confirm what the handler mutates; pair with `current_user_can()` if it touches anything beyond the current user's own data.

### Warnings (medium)

- **Breakpoint proliferation**: 36 distinct CSS breakpoints across the theme (Rule 1 target: 3). Pre-existing debt; candidate for a ux-audit consolidation pass, not a release blocker.

## Notes

- rest-js-contract clean: the theme's single REST surface (`buddyx/v1` after the namespace fix) has no JS shape drift.
- wiring check skipped: heuristic only inspects `includes/admin/` + `templates/`, which doesn't match this theme's `inc/` + root-template layout. Not a pass — just out of scope for the tool.
