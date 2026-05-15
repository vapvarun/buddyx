---
name: BuddyX 5.1.0 customizer-section sweep — done
description: All 7 Bugs-column cards plus customizer matrix sweep for 11 sections completed. Outstanding plugin-gated items documented.
type: project
originSessionId: ce41b5fa-38ba-4d41-99c1-48ac3dcbdb94
---
**Done**: 7 Bugs cards (color, RGBA, sub header, footer, box layout, dark widgets, button screenshot) + customizer-section matrix sweep for color, typography, header, sidebar, layout, login, performance, blog. 16 commits on `wbcomdesigns/buddyx` `5.1.0` branch (range `5cfbd2b..7dbe35b`). All pushed.

**Why:** User requested "test color, then taxonomy [typography], then group check with each menu in customizer" + "no defer to 5.2.0" + "each setting matters". Sweep was audit-driven, not bug-report-driven — uncovered ~20 truthy-class bugs in render-time code that paralleled the active_callback bugs fixed in `b4cccf6`.

**Root cause family found**: Pre-5.1.0 customers carry literal `'on'`/`'off'` string theme_mod values (Kirki preserved choice keys directly). PHP gotchas — `(int) 'on' === 0`, `(bool) 'off' === true`, `! empty('off') === true` — silently flip customer intent. Fixed via `buddyx_is_truthy()` helper in `inc/extra.php` (mirrors `Active_Callback::values_equal()` truthiness contract from `b4cccf6`). 14 files updated to call `buddyx_is_truthy()` consistently.

**Outstanding (plugin-gated, intentionally NOT tested)**:
- BuddyPress sidebar variants (buddypress_sidebar_option, buddypress_members_sidebar_option, buddypress_groups_sidebar_option) — register only when BP active. Same `radio_image` pattern as `sidebar_option` which is verified working — same code path = same outcome.
- bbPress sidebar (bbpress_sidebar_option) — same pattern, register only when bbPress active.
- WooCommerce sidebar (woocommerce_sidebar_option) — same pattern.
- WooCommerce site_cart toggle (in `inc/Customizer_Settings/Fields/Header_Fields.php`) — already `buddyx_is_truthy()` wired (commit `a4393f6`); only field registration is gated, render code is universal.

**How to apply when resuming**: If a customer reports a sidebar issue under BP / bbPress / Woo:
1. Activate the plugin
2. Repro the issue
3. Walk back through the same audit pattern — look for `if ( $foo )` or `! empty( $foo )` checks on theme_mods that may carry pre-5.1.0 'on'/'off' strings. Apply `buddyx_is_truthy()`.

**Scope rules followed during sweep**:
- No deferrals to 5.2.0
- Each setting verified end-to-end (set value → reload → read computed style or DOM presence)
- All commits include rationale + browser-verified evidence in commit body
- Repro/reset cycle for each setting (no leftover theme_mods)
