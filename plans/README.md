# BuddyX - Plans

Design + implementation specs for in-flight and completed work.

## Layout

| Path | Purpose |
|---|---|
| `plans/active/` | Currently-being-worked plans. New plans land here. |
| `plans/archive/<version>/` | Completed plans, archived by the release they shipped in. |
| `plans/audit-evidence/` | QA screenshots, regression samples, audit artifacts. |
| `plans/README.md` | This file. |

## Naming convention

`YYYY-MM-DD-feature-name.md` — date is the creation date, not the
shipping date. Once shipped, the file moves to
`plans/archive/<shipping-version>/` keeping the same filename.

## Workflow

1. Before non-trivial code: write a plan in `plans/active/`. Include
   design rationale, file impact list, migration plan, customer-data
   preservation strategy.
2. While shipping: keep the plan updated as decisions land.
3. After shipping (PR merged into the active release branch, feature
   complete on staging): move the plan to `plans/archive/<version>/`.

## Index

For the 5.1.0 release cycle, start with
`2026-05-07-buddyx-5.1.0-master-summary.md` — the living index of
release-cycle work.

For a chronological view of all plans:

```bash
git log --oneline plans/
```

## Cross-theme migration

Patterns that affect multiple themes (buddyx free + pro + KnowX +
Reign) belong in the `wbcom-kirki-to-tokens` skill, not duplicated
across repos. Free-specific extensions of those patterns stay here.
