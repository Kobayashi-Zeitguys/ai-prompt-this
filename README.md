# AI PromptThis

Deep-link buttons that send page content to AI platforms (ChatGPT, Claude, Gemini, Grok, Perplexity).

A standalone WordPress block plugin with a parent block (`zp/ai-prompt-this`) and a child block (`zp/ai-prompt-this-item`). Each item deep-links to a platform with a pre-filled prompt and the current page URL.

This `README.md` is developer-facing; WordPress.org reads `readme.txt` for the directory listing.

## Requirements

- WordPress 6.6+
- PHP 8.1+
- Node.js + npm (for building)

## Development

```bash
npm install
npm run build      # production build -> build/ (committed)
npm run start      # watch mode
npm run lint:js    # eslint via @wordpress/scripts
npm run lint:css   # stylelint via @wordpress/scripts
```

The `src/` folder holds the block source; `npm run build` compiles it into `build/` (which is committed). There is no custom `webpack.config.js` — the default `@wordpress/scripts` multi-entry build is sufficient and discovers the blocks from their `block.json` files (an earlier custom config was removed as unnecessary).

## Packaging

The production build is driven by `.distignore` (the single list of what does **not** ship). Two scripts:

```bash
npm run stage   # -> dist/ai-prompt-this/ (production-only staging)
npm run dist    # -> releases/ai-prompt-this-<version>.zip (wrapped in the slug folder)
```

`npm run dist` reads the version from `Version:` in `ai-prompt-this.php` and fails if it doesn't match `Stable tag:` in `readme.txt`. The zip contains only the production files (`ai-prompt-this.php`, `readme.txt`, `LICENSE`, `build/`, `languages/`) and is suitable for both the WordPress.org submission form and local "Upload Plugin" installs.

`npm run dist` does not rebuild `build/` — run `npm run build` first if `src/` changed. `dist/` and `releases/` are build artifacts and are gitignored.

## Releasing to WordPress.org

1. Bump the version: `Version:` in `ai-prompt-this.php` and `Stable tag:` in `readme.txt` must match (the build scripts enforce this). The `version` fields in `package.json` and both `block.json` files are informational and do not need to be kept in lock-step for a release.
2. Verify: `npm run lint:js && npm run lint:css`, run `phpcs` (WordPress standard) on `ai-prompt-this.php` and the render templates, and run Plugin Check on the built zip.
3. `npm run build` (if `src/` changed), then `npm run dist` → upload `releases/ai-prompt-this-<version>.zip` for the first approval.
4. After approval, configure and run the SVN release:

   ```bash
   SVN_URL=https://plugins.svn.wordpress.org/ai-prompt-this \
   SVN_WC_DIR=../ai-prompt-this-svn \
     npm run svn:release            # preview only (prints svn status)
   ```

   Review the output, then push:

   ```bash
   SVN_URL=… SVN_WC_DIR=… npm run svn:release -- --commit
   ```

`bin/svn.sh` syncs `trunk/`, creates `tags/<version>` from trunk, and copies `assets/` to SVN `/assets`. It never auto-commits — it previews by default and only pushes with `--commit`.

## Translations

`languages/` ships the `.pot` template and the `fr_CA` translation. The i18n regeneration workflow (`npm run make-pot`, `npm run make-json`, `npm run update-po`) is a later-release item and not part of the current release flow.

## Assets

`assets/` holds the WordPress.org listing images (`banner-*.png`, `icon-*.png`, `icon.svg`). They are pushed only to the SVN `/assets` directory — never into `trunk` or the zip.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
