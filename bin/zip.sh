#!/usr/bin/env bash
set -euo pipefail

# Build releases/<slug>-<version>.zip, wrapped in the slug folder, containing
# only production files (same set that will be pushed to SVN trunk).

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SLUG="ai-prompt-this"
cd "$ROOT"

VERSION="$(sed -n 's/^ \* Version:[[:space:]]*//p' ai-prompt-this.php)"
STABLE="$(sed -n 's/^Stable tag:[[:space:]]*//p' readme.txt)"

[ -n "$VERSION" ] || { echo "ERROR: Version: not found in ai-prompt-this.php" >&2; exit 1; }
[ "$VERSION" = "$STABLE" ] || { echo "ERROR: version '$VERSION' != readme Stable tag '$STABLE'" >&2; exit 1; }

"$ROOT/bin/stage.sh"

mkdir -p releases
OUT="releases/$SLUG-$VERSION.zip"
rm -f "$OUT"
(cd dist && zip -r "$ROOT/$OUT" "$SLUG" >/dev/null)

echo "Built $OUT"
unzip -l "$OUT"

if unzip -l "$OUT" | grep -Eq '(^|[ /])(src|node_modules|assets)/|package\.json|\.gitignore|\.editorconfig|\.eslintrc|\.DS_Store'; then
    echo "ERROR: a dev/excluded file was found in the zip" >&2
    exit 1
fi
echo "OK: no dev files in zip."
