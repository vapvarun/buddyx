# LearnDash UX Audit — Planning Note (future work)

**Status:** Planning stub. Not yet executed. Slot after BP / WC / bbPress audits.
**Bar:** Same as BuddyPress audit (`plans/2026-05-15-buddypress-css-ux-audit.md`) — 100% premium UX, inbuilt, uniform desktop+mobile, modern not legacy.
**Method:** Same as BP audit — `ux-audit` skill (`~/.claude/skills/ux-audit/`) static scan + Playwright per-screen browser measurement. Both 1280px desktop and 390px mobile per screen.

## Scope constraint — **CSS-only, no template overrides**

Same constraint as WooCommerce / bbPress audits. LearnDash audit + cleanup are CSS-only. BuddyX does NOT ship LearnDash template overrides.

**Already at 0 overrides today.** Inventory confirms: no `learndash/` or `sfwd-courses/` directory in the theme. `assets/css/src/learndash.css` (722 lines) handles LearnDash's default templates entirely via CSS. Audit's job: verify + upgrade the CSS; keep template overrides at 0.

LearnDash is special — it ships LD30 (its default visual layer) which already provides a coherent design with its own conventions. The audit needs to decide for each surface: (a) cooperate with LD30 (let LD's design carry, BuddyX adds a thin layer that matches the theme palette + tokens), OR (b) override LD30 visually via CSS targeting LD's classes. The latter is more work + more fragile when LD ships new versions. Recommended: **(a) cooperate — LD30 already gets close to premium; BuddyX's job is to harmonize colors/radius/typography with the theme tokens, not to redesign LD's layouts.**

## Seed content prerequisite — **must populate before audit**

A LearnDash audit measures rendered screens. LD has the most data-shaped UI of any plugin in the stack (course curriculum trees, quiz state machines, completion progress, certificates). Without seed data the audit cannot run. **Seed before auditing:**

### Core course structure

- **2 courses** — one with rich linear curriculum (8+ lessons), one with simpler structure (3 lessons).
- The rich course needs: **at least 2 sections**, **8 lessons total**, **3 of those lessons should have nested topics (3-4 topics each)**, **at least 1 quiz**.
- Each lesson needs body content (a few paragraphs) so typography + spacing renders.
- The quiz needs **at least 5 questions** mixing single-choice + multi-choice + free-text + matching (LD's question types each render differently).
- **Each lesson + topic should have a "mark complete" state machine** — required for the in-progress vs completed visual variants.

### Students + enrollment

- **2 test users enrolled** in the rich course — one fully completed, one mid-progress (5 of 8 lessons done) so completion bar + completed-checkmark + locked-lesson states all render.
- The mid-progress user should have **1 quiz attempt with a partial score** (so quiz-results screen renders with a real number).
- At least one course should have a **certificate awarded** to the completed user (so the certificate template/page renders).

### LD Groups (if active)

- **1 LD Group** with 1 course assigned + 1 student enrolled — so group-leader + group-member screens render.

### Source — use the `learndash-testing-toolkit` (canonical)

**Repo:** [vapvarun/learndash-testing-toolkit](https://github.com/vapvarun/learndash-testing-toolkit.git) — WP-CLI plugin (`wp ldtt ...`) built specifically for this kind of seed work.

```bash
# 1. Install + activate (one time)
git clone https://github.com/vapvarun/learndash-testing-toolkit.git wp-content/plugins/learndash-testing-toolkit
wp plugin activate learndash-testing-toolkit

# 2. Seed the audit fixture (rich course + simple course)
wp ldtt create-courses --count=2 --access_mode=open          # 2 courses, all open
wp ldtt create-lessons --count=8 --course_id=<rich-course>   # 8 lessons in the rich course
wp ldtt create-lessons --count=3 --course_id=<simple-course> # 3 lessons in the simple course
wp ldtt create-topics  --count=9 --lesson_id=<lesson-id>     # 3 topics × 3 nested lessons (=9) in the rich course
wp ldtt create_quiz    --title="Module Check" --questions=5  # the 5-question mixed quiz

# 3. Seed students + enrollment
wp user create student1 student1@test.local --role=subscriber --user_pass=test
wp user create student2 student2@test.local --role=subscriber --user_pass=test
# Enroll both into rich course (toolkit supports this; check `wp ldtt --help` for the exact subcommand)

# 4. Mark progress for student1 (mid-progress: 5 of 8 lessons done)
# Mark complete for student2 across all lessons + quiz attempt for certificate

# 5. (Optional) Create LD Group with rich course + student1 + student2
```

Verify after seeding: `wp post list --post_type=sfwd-courses --format=count` should return 2; `sfwd-lessons` 11; `sfwd-topic` 9; `sfwd-quiz` 1.

**Document the exact seed steps + final post counts in the audit's commit body** so the next person can reproduce.

## Head-start inventory

### LearnDash CSS that BuddyX ships

| File | Built file | Lines (src) | Purpose |
|---|---|---:|---|
| `assets/css/src/learndash.css` | `learndash.css` + `.min.css` | 722 | LD-specific theme styling layered over LD30 defaults |

722 lines is mid-sized — significantly larger than `bbpress.css` (299) but smaller than `buddypress.css` (6,113). This reflects the cooperate-with-LD30 approach: the theme adds palette/typography/spacing harmonization, not a full visual rewrite.

### LearnDash template overrides in BuddyX

**0** templates. No `learndash/` directory exists in the theme. CSS-only is the steady state.

### LearnDash-related Customizer toggles BuddyX exposes

None at the time of inventory. LD pages use the global `sidebar_option` (left / right / none) since BuddyX has no LD-specific sidebar control. The audit may recommend adding `learndash_sidebar_option` if customer demand exists — that would be a Phase 2 add, not a Phase 1 fix.

### Visible-screens checklist

**Tier 1 — Course discovery:**
1. `/courses/` — Courses archive (LD course list)
2. `/courses/<course>/` — Single course page (overview + curriculum tree)

**Tier 2 — Learning flow (most LD time is spent here):**
3. `/courses/<course>/lessons/<lesson>/` — Single lesson page (curriculum sidebar + lesson body + nav)
4. Same lesson with **completed** state
5. Same lesson with **locked** state (prerequisite not met)
6. Single topic page (nested under lesson)

**Tier 3 — Assessment:**
7. Quiz start screen
8. Quiz in-progress (each question type — single / multi / free-text / matching / sort)
9. Quiz results screen (pass + fail variants)
10. Assignment submission form (if assignment-on-lesson)

**Tier 4 — Achievement:**
11. Course completion screen
12. Certificate download/view
13. Profile / dashboard page showing enrolled courses (LD's own profile if used, or the BP profile sub-tab)

**Tier 5 — Groups (if active):**
14. Group leader dashboard
15. Group member dashboard
16. Group reports / progress views

**Variant axes:**
- Default state @ 1280px + 390px (curriculum sidebar is the BIG mobile-stack concern)
- Sidebar position variations (left / right / none) — global sidebar control affects all LD pages
- Dark mode (Site Skin Phase 4 tokens carry; verify on quiz buttons + progress bars + completed-checkmarks specifically)
- RTL (long-form course content; LMS is text-heavy)

### Expected findings (informed prediction)

- **Focus rings missing** on every interactive element — same pattern.
- **Tap targets below 40px** on: quiz answer radios, lesson-list links in curriculum sidebar, "mark complete" button, "next lesson" / "previous lesson" nav, certificate download link.
- **Progress bar** styling — LD30 default is decent but may not match the theme palette. Verify uses `var(--bx-color-accent)` for filled state.
- **Curriculum sidebar** on lesson pages is critical UX — should be sticky + scrollable, with clear current-lesson highlight and completion checkmarks. Likely needs polish.
- **Quiz answer cards** (radio + label combos) need clear hover + selected states. LD defaults are basic.
- **Certificate template** — typically a separate PDF/print template, may not be CSS-controllable. Out of scope if so.
- **Completion-state visuals** — checkmarks, "Completed" badges, progress %. Need brand-aligned colors.
- **"Sample lesson"** / "Course preview" surface (for non-enrolled visitors) should look as polished as the enrolled experience.

## Audit deliverable shape (mirror BP audit)

When the future planner runs this:

1. Seed content per the prerequisite section. Document seed steps in the commit body.
2. Save static scan output to `plans/audit-snapshots/<date>-ux-audit-learndash-raw.md` (gitignored).
3. Walk every Tier 1-5 screen at both viewports; save screenshots to `plans/audit-evidence/ld-audit-*.png`.
4. Compile findings into `plans/<date>-learndash-css-ux-audit.md` mirroring the BP audit doc shape.
5. Single commit + push.

## Out of scope

- **LearnDash extensions** — ProPanel, Notifications, Gradebook, Stripe integration, Wisdm Reports, etc. Each is its own plugin ecosystem.
- **LearnDash admin** — course/lesson/quiz builders in `wp-admin/`. Separate concern.
- **Certificate PDF/print template** — if non-CSS-controllable.
- **TutorLMS / LifterLMS / Sensei** — different LMS plugins, not LearnDash.

## Reference

- BP audit (the method template): `plans/2026-05-15-buddypress-css-ux-audit.md`
- WC plan (CSS-only-no-overrides precedent): `plans/2026-05-15-woocommerce-css-ux-audit-plan.md`
- bbPress plan (companion stub): `plans/2026-05-15-bbpress-css-ux-audit-plan.md`
- ux-audit skill: `~/.claude/skills/ux-audit/SKILL.md`
- Companion tokens skill: `~/.claude/skills/wbcom-kirki-to-tokens/SKILL.md`
