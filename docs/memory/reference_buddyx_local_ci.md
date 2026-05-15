---
name: BuddyX local CI / quality-check infrastructure
description: BuddyX runs all quality checks locally — no GitHub Actions. Documents the pre-commit hook chain and manual command set.
type: reference
originSessionId: a528b709-cd24-4cab-92db-25e134bfa4d4
---
**Why local-only:** `wbcomdesigns/buddyx` is a private repo with no
GitHub Actions CI available. The release-only mirror `vapvarun/buddyx`
is published manually after local checks pass.

**Auto-firing on commit (`pre-commit`):**
- Wired via `simple-git-hooks` + `lint-staged` (npm devDeps); activates
  on `npm install` via the `prepare` script.
- Per staged file:
  - `*.php` → `scripts/lint-php-staged.sh` (runs `php -l` per file —
    bypasses `vendor/bin/parallel-lint` which can't handle PHP-binary
    paths that contain spaces, e.g. Local-by-Flywheel's "Application
    Support" path)
  - `assets/css/src/**/*.css` → `stylelint --fix`
  - `assets/js/src/**/*.{js,jsx,ts,tsx}` → `eslint --fix`
- Bypass: `SKIP_SIMPLE_GIT_HOOKS=1 git commit -m "..."`

**Manual commands (run before tagging):**
- PHP: `composer run-phpcs`, `composer phpstan`, `composer test:all`,
  `composer fix` (rector + cs-fixer + phpcbf)
- JS/CSS: `npm run lint`, `npm run test:js`, `npm run test:e2e`,
  `npm run test:perf`
- Build: `npm run build`, `npm run dist -- --current --no-tag`
- Inventory: `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt`

**PHPStan baseline:** `phpstan-baseline.neon` snapshots known errors.
Refresh after large refactors:

```bash
echo 'parameters:
    ignoreErrors: []' > phpstan-baseline.neon
composer phpstan:baseline
```

Last refresh: 2026-05-04 (commit `2eeb9f5`) after the Kirki removal
renamed `inc/Kirki_Option/` → `inc/Customizer_Settings/`. 1150 known
errors currently captured.

**Docs:** Full reference at `docs/local-ci.md` in the theme repo.
