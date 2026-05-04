#!/usr/bin/env bash
# Lint each PHP file passed as an arg with `php -l`. Exits non-zero on first
# syntax error so the pre-commit hook blocks the commit. Used by lint-staged
# because vendor/bin/parallel-lint can't handle PHP binary paths that
# contain spaces (Local-by-Flywheel installs at "Application Support/...").

set -e

if [ "$#" -eq 0 ]; then
	exit 0
fi

failed=0
for f in "$@"; do
	if ! php -l "$f" >/dev/null 2>&1; then
		php -l "$f"
		failed=1
	fi
done

if [ "$failed" -ne 0 ]; then
	exit 1
fi
echo "PHP syntax OK ($# file(s))"
