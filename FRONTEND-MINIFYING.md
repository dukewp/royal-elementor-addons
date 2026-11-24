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

## Which site

rea.local and rea-prod are separate. Minify only the plugin folder you are working in. Do not copy files from one site onto the other unless the user explicitly names that site. Do not use `rea-prod-frontend-minify` unless the user explicitly says rea-prod.

From this plugin folder:

- `npm run build:frontend-safe` rewrites `assets/js/frontend.min.js` and writes `frontend.min.prebuild.backup.js` beside it.
- `npm run build:frontend-css` rewrites `assets/css/frontend.min.css` and writes `frontend.min.prebuild.backup.css` beside it.
- `npm run build:modal-popups-local` rewrites `assets/js/modal-popups.min.js`. No backup file is written.
