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

To minify the client copy without touching this working file, use `C:\Users\Goga\Local Sites\rea-prod-frontend-minify` and `npm run build:frontend-css` there. That script only writes inside rea-prod.
