#!/usr/bin/env bash
set -euo pipefail

# Stage the production-only plugin into dist/<slug>/.
# The single source of truth for what ships is .distignore.

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SLUG="ai-prompt-this"
DIST="$ROOT/dist"
DEST="$DIST/$SLUG"

cd "$ROOT"

rm -rf "$DIST"
mkdir -p "$DEST"

rsync -a --exclude-from="$ROOT/.distignore" "$ROOT/" "$DEST/"

# Fail fast if a required file/dir is missing.
for f in ai-prompt-this.php readme.txt LICENSE; do
    [ -f "$DEST/$f" ] || { echo "ERROR: $f missing from staging" >&2; exit 1; }
done
[ -d "$DEST/build" ] || { echo "ERROR: build/ missing from staging" >&2; exit 1; }
[ -d "$DEST/languages" ] || { echo "ERROR: languages/ missing from staging" >&2; exit 1; }

echo "Staged $DEST:"
(cd "$DEST" && find . -type f | sort)
