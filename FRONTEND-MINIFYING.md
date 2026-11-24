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

## Build modal popups script

Run this in the plugin folder, the same way as `build:frontend-safe`. It rewrites `assets/js/modal-popups.min.js` in that folder. It does not create a backup file. It stops if the file already looks minified.

```bash
npm run build:modal-popups-local
```

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

When asked to minify rea-prod frontend JS or CSS, do not search and do not run those commands in this plugin. Those rewrite the rea.local working files.

Tooling is already installed at `C:\Users\Goga\Local Sites\rea-prod-frontend-minify`. The frontend JS and CSS commands read and write only rea-prod. The modal popups commands are separate and each one is locked to one site.

1. Copy the readable rea.local file onto the rea-prod plugin.
   - Frontend JS: `assets/js/frontend.min.js` to the same path.
   - CSS: `assets/css/frontend.min.css` to the same path.
2. From the tooling folder, run `npm run build:frontend-safe` for frontend JS or `npm run build:frontend-css` for CSS.
3. The frontend JS and CSS scripts write `frontend.min.prebuild.backup.js` or `frontend.min.prebuild.backup.css` next to the rea-prod file, then rewrite that rea-prod file. Each script stops if the file already looks minified. Do not pass `--force` unless asked.

Modal popups is separate. From the plugin folder you are working in, run `npm run build:modal-popups-local`. That rewrites only that folder's `assets/js/modal-popups.min.js`. No backup file is written.

Upload the minified file from rea-prod. Leave the readable copy on rea.local.
