# Frontend Minification (Deterministic)

Use this local build to avoid JSCompress config drift between releases.

## First time

```bash
npm install
```

## Build frontend bundle

```bash
npm run build:frontend-safe
```

This process:

- Reads `assets/js/frontend.min.js`
- Transpiles with pinned Babel preset targets
- Minifies with pinned Terser options (ES5-safe)
- Rewrites `assets/js/frontend.min.js`
- Creates backup at `assets/js/frontend.min.prebuild.backup.js`

## Build frontend stylesheet

```bash
npm run build:frontend-css
```

This process:

- Reads `assets/css/frontend.min.css`
- Minifies with the cleancss.com whitespace rules matched to rea-prod `frontend.min.css` (comments, newlines, and indentation only — colors, `0px`, `font-weight`, and duplicate rules stay as written)
- Rewrites `assets/css/frontend.min.css`
- Creates backup at `assets/css/frontend.min.prebuild.backup.css`
- Stops if the file already looks minified, unless you pass `--force`

## Minify the rea-prod copy

When asked to minify rea-prod JS or CSS, do not search and do not run the commands above in this plugin. Those rewrite the rea.local working files.

Tooling is already installed at `C:\Users\Goga\Local Sites\rea-prod-frontend-minify`. It only reads and writes rea-prod.

1. Copy the readable rea.local file onto the same relative path under `C:\Users\Goga\Local Sites\rea-prod\app\public\wp-content\plugins\royal-elementor-addons\`.
   - JS: `assets/js/frontend.min.js`
   - CSS: `assets/css/frontend.min.css`
2. From the tooling folder, run `npm run build:frontend-safe` for JS or `npm run build:frontend-css` for CSS.
3. The script writes `frontend.min.prebuild.backup.js` or `frontend.min.prebuild.backup.css` next to the rea-prod file, then rewrites that rea-prod file. It stops if the file already looks minified. Do not pass `--force` unless asked.

Upload the minified file from rea-prod. Leave the readable copy on rea.local.
