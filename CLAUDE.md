# BuddyX — project-local instructions for Claude Code

This is the **wbcomdesigns/buddyx** WordPress classic theme. Active branch:
`5.1.0` (Kirki-removal release in flight — see `docs/memory/project_buddyx_5_1_0.md`).

## Project memory — read at session start

Project-specific memory and preferences live in `docs/memory/` in this repo
(git-tracked, portable across machines). **At session start, read
`docs/memory/MEMORY.md`** — it is the index, one line per memory.

Conventions:

- `MEMORY.md` — the index (do not put memory content here; one line per memory only).
- `project_*.md` — ongoing project state (releases in flight, execution queues).
- `feedback_*.md` — user feedback / how-to-work-on-this rules. Highest-priority.
- `reference_*.md` — pointers, inventories, indexes.

Each memory is one file holding one fact, with frontmatter:

```yaml
---
name: <short-kebab-case-slug>
description: <one-line summary — used to decide relevance during recall>
type: project | feedback | reference | user
originSessionId: <uuid, optional>
---
```

When adding a memory: write the file under `docs/memory/`, then append a one-line
pointer to `docs/memory/MEMORY.md`. When updating a memory: edit the file in
place; don't duplicate.

## Cross-theme migration knowledge

For Kirki-to-tokens migration architecture (this theme, plus the planned ports
to buddyx-pro / KnowX / Reign), use the **`wbcom-kirki-to-tokens` skill**
(auto-triggered by phrases like "drop Kirki from <theme>" / "port BuddyX 5.1.0
architecture to <theme>"). The skill is the canonical home for cross-theme
findings — 5 architecture pillars, post-swap audit rubric, live-preview audit
helper, Google Fonts library restoration. **Do not duplicate skill content
into this repo's docs/memory/** — keep cross-theme in the skill,
BuddyX-specific in `plans/` + `docs/memory/`.

## Plans

`plans/YYYY-MM-DD-feature.md` — design + implementation specs for BuddyX-specific
work. Index in `docs/memory/reference_buddyx_plans_index.md`. **Start with**
`plans/2026-05-07-buddyx-5.1.0-master-summary.md` — the living index for the
5.1.0 release.

## Repos rule

- **Develop on `wbcomdesigns/buddyx`** (private; this is `origin`).
- **Release tag + master only on `vapvarun/buddyx`** (public). Never push dev
  branches there. See `docs/memory/feedback_buddyx_repos.md` for the full rule.

## Release notes style

WooCommerce-style action-prefix format for every `readme.txt` changelog AND
every GitHub release body. Bullets prefixed `New` / `Improve` / `Fix` /
`Security` / `Dev` / `Compat` (padded to 8 chars before ` - `). No emoji.
No em-dashes (use `-`). Release title format: `Plugin X.Y.Z - one-line summary`.
See the global `~/.claude/CLAUDE.md` "Release Notes Style" section for the full rules.
