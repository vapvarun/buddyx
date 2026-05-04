# Local CI / Quality Checks

BuddyX runs all quality checks **locally** — the repo is private at
`wbcomdesigns/buddyx`, no GitHub Actions CI is configured. The
release-only mirror at `vapvarun/buddyx` is published manually after
local checks pass.

## Auto-firing on commit

A `pre-commit` git hook is wired via [simple-git-hooks](https://github.com/toplenboren/simple-git-hooks)
+ [lint-staged](https://github.com/lint-staged/lint-staged). It
activates automatically the first time you run `npm install` (via the
`prepare` script).

Per staged file:

| Pattern | Tool | What it catches |
|---|---|---|
| `*.php` | `php -l` (via `scripts/lint-php-staged.sh`) | PHP syntax errors |
| `assets/css/src/**/*.css` | `stylelint --fix` | CSS lint errors (auto-fixed inline) |
| `assets/js/src/**/*.{js,jsx,ts,tsx}` | `eslint --fix` | JS/TS lint errors (auto-fixed inline) |

The hook is fast (under 5 seconds for typical commits) so it won't
slow down the commit workflow. To bypass in an emergency,
`SKIP_SIMPLE_GIT_HOOKS=1 git commit -m "..."`.

## Manual checks (run before tagging a release)

### PHP

```bash
composer lint               # parallel-lint (PHP syntax)
composer run-phpcs          # WPCS (parallel=4, phpcs.xml.dist)
composer run-phpcbf         # PHPCS auto-fix
composer phpstan            # PHPStan level 5, 2G memory
composer test:unit          # PHPUnit unit tests
composer test:integration   # PHPUnit integration tests
composer test:all           # both PHPUnit suites
composer fix                # rector + php-cs-fixer + phpcbf
```

**Note on `composer lint`:** `vendor/bin/parallel-lint` cannot resolve
PHP binary paths that contain spaces (Local-by-Flywheel installs PHP
at `…/Application Support/Local/…`). When that's the case, use the
shim that the pre-commit hook also uses:

```bash
find . -name "*.php" \
  -not -path "./vendor/*" \
  -not -path "./node_modules/*" \
  -not -path "./tests/*" \
  -not -path "./dist/*" \
  -print0 | xargs -0 -n1 php -l
```

### JS / CSS

```bash
npm run lint                # ESLint + Stylelint
npm run lint:js             # ESLint only
npm run lint:css            # Stylelint only
npm run test:js             # Jest unit tests
npm run test:e2e            # Playwright E2E (tests/e2e/)
npm run test:perf           # Lighthouse CI autorun
```

### Build

```bash
npm run build               # JS + CSS production bundle
npm run build:js
npm run build:css
npm run bundle              # full production prep
npm run dist                # distribute (creates dist/buddyx-X.Y.Z.zip)
```

## Pre-tag checklist

Before tagging a release on `wbcomdesigns/buddyx`:

1. `composer phpstan` — level 5, zero errors
2. `composer run-phpcs` — WPCS clean
3. `npm run lint` — JS + CSS clean
4. `composer test:all` — PHPUnit green
5. `npm run test:js` — Jest green
6. `npm run build` — production assets up to date
7. `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt` — refresh inventory if customizer fields changed
8. Update `style.css` Version, `readme.txt` Stable tag + Changelog, `config/config.default.json` `theme.version`
9. `npm run dist -- --current --no-tag` — build versioned release zip
10. Browser-verify customize.php at `http://buddyx.local/wp-admin/customize.php?autologin=1` — light + dark mode, every section opens, no console errors

When the staging Pass 3 passes (real customer-DB upgrade test):

```bash
git tag -a v5.1.0 -m "Release v5.1.0"
git push origin 5.1.0 v5.1.0
git push vapvarun master:master v5.1.0
# Then create the GitHub Release at vapvarun/buddyx with dist/buddyx-5.1.0.zip
```

## Refreshing the PHPStan baseline

The baseline (`phpstan-baseline.neon`) snapshots known errors so they
don't block runs. Refresh after large refactors:

```bash
echo 'parameters:
    ignoreErrors: []' > phpstan-baseline.neon
composer phpstan:baseline
```

The 5.1.0 Kirki removal renamed `inc/Kirki_Option/` → `inc/Customizer_Settings/`,
which made the old baseline reference dead paths. Latest refresh: 2026-05-04.
