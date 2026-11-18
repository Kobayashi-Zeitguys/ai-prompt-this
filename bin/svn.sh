#!/usr/bin/env bash
set -euo pipefail

# Release the plugin to the WordPress.org SVN repo (trunk + tag + assets).
#
# PREVIEW BY DEFAULT: stages everything into the working copy and prints
# `svn status`; it only commits to the server with `--commit`. Requires
# WordPress.org approval first (set SVN_URL and SVN_WC_DIR).

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SLUG="ai-prompt-this"

SVN_URL="${SVN_URL:-}"
SVN_WC_DIR="${SVN_WC_DIR:-}"
COMMIT=0
COMMIT_MSG=""

for arg in "$@"; do
    case "$arg" in
        --commit) COMMIT=1 ;;
        --message=*) COMMIT_MSG="${arg#*=}" ;;
        *) echo "Unknown arg: $arg" >&2; exit 1 ;;
    esac
done

if [ -z "$SVN_URL" ] || [ -z "$SVN_WC_DIR" ]; then
    echo "SVN release is not configured yet (no WordPress.org approval)." >&2
    echo "Set SVN_URL and SVN_WC_DIR, e.g.:" >&2
    echo "  SVN_URL=https://plugins.svn.wordpress.org/$SLUG SVN_WC_DIR=../$SLUG-svn bash bin/svn.sh --commit" >&2
    exit 1
fi

"$ROOT/bin/stage.sh"

if [ ! -d "$SVN_WC_DIR" ]; then
    svn co "$SVN_URL" "$SVN_WC_DIR"
else
    svn up "$SVN_WC_DIR"
fi

# Sync trunk (delete stale files that no longer ship).
rsync -a --delete "$ROOT/dist/$SLUG/" "$SVN_WC_DIR/trunk/"
svn add --force "$SVN_WC_DIR/trunk" >/dev/null
svn status "$SVN_WC_DIR/trunk" | awk '/^!/ {print $2}' | while read -r f; do
    svn rm "$SVN_WC_DIR/trunk/$f" >/dev/null
done

# Assets are SVN-only (never in trunk/zip).
if [ -d "$ROOT/assets" ]; then
    rsync -a "$ROOT/assets/" "$SVN_WC_DIR/assets/"
    svn add --force "$SVN_WC_DIR/assets" >/dev/null
    for img in "$SVN_WC_DIR/assets"/*.png; do
        [ -e "$img" ] && svn propset svn:mime-type image/png "$img" >/dev/null
    done
fi

# Tag the release from trunk; never overwrite an existing tag.
VERSION="$(sed -n 's/^ \* Version:[[:space:]]*//p' "$ROOT/ai-prompt-this.php")"
if svn ls "$SVN_URL/tags/$VERSION" >/dev/null 2>&1; then
    echo "ERROR: tag $VERSION already exists; bump the version." >&2
    exit 1
fi
svn cp "$SVN_WC_DIR/trunk" "$SVN_WC_DIR/tags/$VERSION"

echo "--- svn status ---"
svn status "$SVN_WC_DIR"

if [ "$COMMIT" -eq 1 ]; then
    svn ci "$SVN_WC_DIR" -m "${COMMIT_MSG:-Release $VERSION}"
else
    echo "Preview only — run with --commit to push to WordPress.org."
fi
