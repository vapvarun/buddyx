# BuddyX Role Matrix

Generated 2026-06-05 from audit/manifest.json (wp-plugin-onboard). Do not hand-edit counts; re-run the skill.

BuddyX defines NO custom capabilities. All gating uses core WordPress caps (`edit_theme_options`, `manage_options`, `edit_post`, `publish_posts`) plus one logged-in-only AJAX surface. Standard role-to-cap mapping below assumes a default WP install.

## Capability ownership by role

| Capability | Admin | Editor | Author | Subscriber | Visitor |
|---|---|---|---|---|---|
| edit_theme_options | Yes | No | No | No | No |
| manage_options | Yes | No | No | No | No |
| edit_post (own) | Yes | Yes | Yes | No | No |
| publish_posts | Yes | Yes | Yes | No | No |

## Feature access matrix

| Feature | Gate | Admin | Editor | Author | Subscriber | Visitor |
|---|---|---|---|---|---|---|
| Customizer access (all 128 settings: skin, typography, layout, header, footer, sidebars, etc.) | `edit_theme_options` | Yes | No | No | No | No |
| Theme settings REST `POST buddyx/v1/settings` | `manage_options` (inc/Options/Component.php:191) | Yes | No | No | No | No |
| Getting Started / Welcome page (Appearance) | `edit_theme_options` (inc/Welcome/Component.php:65) | Yes | No | No | No | No |
| Font regenerate AJAX `buddyx_regenerate_fonts_folder` | `edit_theme_options` + nonce (class-buddyx-webfont-loader.php:753) | Yes | No | No | No | No |
| Starter-content admin actions | `manage_options` (inc/Starter_Content/Component.php:307/339/360) | Yes | No | No | No | No |
| LMS color toggle AJAX `buddyx_lms_toggle_theme_color` | nonce only, logged-in (NO capability check; inc/Accessibility/Component.php:95) | Yes | Yes | Yes | Yes | No |
| Color-mode toggle (light/dark/auto, localStorage path) | none (public JS) | Yes | Yes | Yes | Yes | Yes |
| Post edit link in entry meta (when blog_edit_link on) | `manage_options` (template-parts/content/entry_meta.php:145) | Yes | No | No | No | No |
| "Create first post" prompt on empty home | `publish_posts` (template-parts/content/error.php:16) | Yes | Yes | Yes | No | No |
| Inline/quick post-meta save guard | `edit_post` (per-post) (inc/extra.php:876) | Yes | Yes | Own posts | No | No |
| rtMedia group home access | `groups_access_group` via bp_current_user_can (rtmedia/main.php:185) | Per group membership | Per group | Per group | Per group | No |
| View site frontend (patterns, blog, dark mode, sidebars, footer) | none | Yes | Yes | Yes | Yes | Yes |

## Notes

- The dead `buddyx-settings` top-level menu (`manage_options`, inc/Options/Component.php:98) is NOT registered at runtime (admin_menu hook commented out at :42), so it grants no real access. Only the `buddyx-welcome` page renders.
- The LMS color toggle AJAX (`buddyx_lms_toggle_theme_color`) has a nonce but NO capability check. Classified by the wppqa baseline as likely intentional - it only sets the per-visitor `bxtheme` cookie / current-user preference and elevates nothing. It is reachable by any logged-in user (Subscriber and up); there is no nopriv variant, so visitors cannot reach it.
- The non-LMS color-mode toggle is pure client-side (localStorage) and available to everyone including unauthenticated visitors.
- `inc/extra.php:887` (the second nonce-without-cap finding) is flagged "needs triage" in the baseline - confirm what it mutates before relying on its current gating.
- No custom roles, no custom capabilities, no `map_meta_cap` filters owned by the theme.
